<?php
require __DIR__ . '/includes/session.php';
require __DIR__ . '/config/db.php';
require __DIR__ . '/includes/functions.php';

$id = (int) ($_GET['id'] ?? 0);
$producto = $id ? get_producto($pdo, $id) : null;

if (!$producto || !$producto['activo']) {
    http_response_code(404);
    $pageTitle = 'Producto no encontrado — Lissvery';
    require __DIR__ . '/includes/header.php';
    echo '<section class="section"><div class="wrap wrap-narrow"><h1>Producto no encontrado</h1><p>Es posible que ya no esté disponible. Vuelve al <a href="catalogo.php">catálogo</a>.</p></div></section>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

/* Seleccionar un producto exige haber iniciado sesión */
require_login('producto.php?id=' . $id);

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_carrito'])) {
    if (!verify_csrf($_POST['csrf'] ?? '')) {
        $errores[] = 'Tu sesión expiró, recarga la página e intenta de nuevo.';
    } else {
        $cantidad = max(1, (int) ($_POST['cantidad'] ?? 1));
        $ciclo = ($_POST['ciclo'] ?? 'mensual') === 'anual' ? 'anual' : 'mensual';
        cart_add($pdo, (int) $_SESSION['user_id'], $producto['id'], $cantidad, $ciclo);
        flash('success', 'Agregamos "' . $producto['nombre'] . '" a tu carrito.');
        header('Location: carrito.php');
        exit;
    }
}

$caracteristicas = producto_caracteristicas($producto);
$pageTitle = $producto['nombre'] . ' — Lissvery';
require __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="wrap">
    <p class="breadcrumb">
      <a href="index.php">Inicio</a> /
      <a href="catalogo.php?categoria=<?= htmlspecialchars($producto['categoria_slug']) ?>"><?= htmlspecialchars($producto['categoria_nombre']) ?></a> /
      <?= htmlspecialchars($producto['nombre']) ?>
    </p>
  </div>
</section>

<section class="section">
  <div class="wrap product-detail">
    <div class="product-media">
      <?php if (!empty($producto['imagen'])): ?>
        <img src="uploads/productos/<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
      <?php else: ?>
        <div class="product-media-placeholder" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 8h18M8 5v3"/></svg></div>
      <?php endif; ?>
    </div>

    <div class="product-info">
      <p class="eyebrow"><?= htmlspecialchars($producto['categoria_nombre']) ?></p>
      <h1><?= htmlspecialchars($producto['nombre']) ?></h1>
      <p><?= nl2br(htmlspecialchars((string) $producto['descripcion'])) ?></p>

      <p class="price">
        <span class="currency">Q</span><span class="amount"><?= number_format($producto['precio'], 2) ?></span>
        <?php if ($producto['categoria_slug'] === 'hosting-personal'): ?><span class="period">/mes</span><?php endif; ?>
      </p>

      <?php if (!empty($caracteristicas)): ?>
        <ul class="price-features">
          <?php foreach ($caracteristicas as $c): ?><li><?= htmlspecialchars($c) ?></li><?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <?php foreach ($errores as $e): ?><p class="form-note error"><?= htmlspecialchars($e) ?></p><?php endforeach; ?>

      <form method="post" class="add-to-cart-form">
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

        <?php if ($producto['categoria_slug'] === 'hosting-personal' && !empty($producto['precio_anual'])): ?>
          <label for="ciclo">Ciclo de pago</label>
          <select name="ciclo" id="ciclo">
            <option value="mensual">Mensual — Q<?= number_format($producto['precio'], 2) ?></option>
            <option value="anual">Anual — Q<?= number_format($producto['precio_anual'], 2) ?></option>
          </select>
        <?php endif; ?>

        <label for="cantidad">Cantidad</label>
        <input type="number" name="cantidad" id="cantidad" value="1" min="1">

        <button type="submit" name="agregar_carrito" value="1" class="btn btn-solid btn-lg btn-block">Agregar al carrito</button>
      </form>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
