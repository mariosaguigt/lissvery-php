<?php
/**
 * Consultas reutilizables. Requiere $pdo ya creado (config/db.php).
 */

function get_categorias(PDO $pdo): array
{
    return $pdo->query("SELECT * FROM categorias ORDER BY orden ASC, nombre ASC")->fetchAll();
}

function get_categoria_by_slug(PDO $pdo, string $slug): ?array
{
    $stmt = $pdo->prepare("SELECT * FROM categorias WHERE slug = ?");
    $stmt->execute([$slug]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function get_productos(PDO $pdo, ?string $categoriaSlug = null, bool $soloActivos = true): array
{
    $sql = "SELECT p.*, c.nombre AS categoria_nombre, c.slug AS categoria_slug
            FROM productos p
            JOIN categorias c ON c.id = p.categoria_id";
    $cond = [];
    $params = [];

    if ($categoriaSlug !== null) {
        $cond[] = 'c.slug = ?';
        $params[] = $categoriaSlug;
    }
    if ($soloActivos) {
        $cond[] = 'p.activo = 1';
    }
    if ($cond) {
        $sql .= ' WHERE ' . implode(' AND ', $cond);
    }
    $sql .= ' ORDER BY p.destacado DESC, p.precio ASC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function get_producto(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare(
        "SELECT p.*, c.nombre AS categoria_nombre, c.slug AS categoria_slug
         FROM productos p JOIN categorias c ON c.id = p.categoria_id
         WHERE p.id = ?"
    );
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function producto_caracteristicas(array $producto): array
{
    if (empty($producto['caracteristicas'])) {
        return [];
    }
    $lines = preg_split('/\r\n|\r|\n/', trim($producto['caracteristicas']));
    return array_values(array_filter(array_map('trim', $lines), fn($l) => $l !== ''));
}

/* ---------------- Carrito (guardado en base de datos, por usuario) ---------------- */

function cart_add(PDO $pdo, int $userId, int $productoId, int $cantidad, string $ciclo): void
{
    $ciclo = $ciclo === 'anual' ? 'anual' : 'mensual';
    $stmt = $pdo->prepare("SELECT id, cantidad FROM carrito WHERE usuario_id = ? AND producto_id = ? AND ciclo = ?");
    $stmt->execute([$userId, $productoId, $ciclo]);
    $existente = $stmt->fetch();

    if ($existente) {
        $nueva = $existente['cantidad'] + $cantidad;
        $u = $pdo->prepare("UPDATE carrito SET cantidad = ? WHERE id = ?");
        $u->execute([$nueva, $existente['id']]);
    } else {
        $i = $pdo->prepare("INSERT INTO carrito (usuario_id, producto_id, cantidad, ciclo) VALUES (?,?,?,?)");
        $i->execute([$userId, $productoId, $cantidad, $ciclo]);
    }
}

function cart_items(PDO $pdo, int $userId): array
{
    $stmt = $pdo->prepare(
        "SELECT ci.id AS item_id, ci.cantidad, ci.ciclo, p.*
         FROM carrito ci JOIN productos p ON p.id = ci.producto_id
         WHERE ci.usuario_id = ?
         ORDER BY ci.agregado_en DESC"
    );
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

function cart_count(PDO $pdo, int $userId): int
{
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(cantidad),0) AS total FROM carrito WHERE usuario_id = ?");
    $stmt->execute([$userId]);
    return (int) $stmt->fetch()['total'];
}

function cart_remove(PDO $pdo, int $userId, int $itemId): void
{
    $stmt = $pdo->prepare("DELETE FROM carrito WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$itemId, $userId]);
}

function cart_update_qty(PDO $pdo, int $userId, int $itemId, int $cantidad): void
{
    $cantidad = max(1, $cantidad);
    $stmt = $pdo->prepare("UPDATE carrito SET cantidad = ? WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$cantidad, $itemId, $userId]);
}

function item_precio_unitario(array $item): float
{
    if ($item['ciclo'] === 'anual' && !empty($item['precio_anual'])) {
        return (float) $item['precio_anual'];
    }
    return (float) $item['precio'];
}

function cart_total(array $items): float
{
    $total = 0.0;
    foreach ($items as $item) {
        $total += item_precio_unitario($item) * (int) $item['cantidad'];
    }
    return $total;
}

function formato_q(float $valor): string
{
    return 'Q' . number_format($valor, 2);
}
