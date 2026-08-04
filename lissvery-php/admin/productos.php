<?php
require __DIR__ . '/../includes/session.php';
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/functions.php';
require_admin();

/* Eliminar producto: borra la fila y su imagen, así desaparece de la web al instante */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    if (verify_csrf($_POST['csrf'] ?? '')) {
        $id = (int) $_POST['eliminar_id'];
        $stmt = $pdo->prepare("SELECT imagen FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        $imagen = $stmt->fetchColumn();

        $pdo->prepare("DELETE FROM productos WHERE id = ?")->execute([$id]);

        if ($imagen) {
            $ruta = __DIR__ . '/../uploads/productos/' . $imagen;
            if (is_file($ruta)) {
                unlink($ruta);
            }
        }
        flash('success', 'Producto eliminado. Ya no aparece en la página web.');
    }
    header('Location: productos.php');
    exit;
}

$productos = $pdo->query(
    "SELECT p.*, c.nombre AS categoria_nombre
     FROM productos p JOIN categorias c ON c.id = p.categoria_id
     ORDER BY p.id DESC"
)->fetchAll();

$pageTitle = 'Productos — Panel Lissvery';
require __DIR__ . '/includes/admin-header.php';
?>

<div class="admin-head-row">
  <h1>Productos</h1>
  <a href="producto-form.php" class="btn btn-solid">+ Nuevo producto</a>
</div>

<?php if (empty($productos)): ?>
  <p>Todavía no has subido ningún producto.</p>
<?php else: ?>
  <table class="admin-table">
    <thead>
      <tr><th>Imagen</th><th>Nombre</th><th>Categoría</th><th>Precio</th><th>Estado</th><th>Acciones</th></tr>
    </thead>
    <tbody>
      <?php foreach ($productos as $p): ?>
        <tr>
          <td>
            <?php if ($p['imagen']): ?>
              <img src="../uploads/productos/<?= htmlspecialchars($p['imagen']) ?>" class="admin-thumb" alt="">
            <?php else: ?>—<?php endif; ?>
          </td>
          <td><?= htmlspecialchars($p['nombre']) ?></td>
          <td><?= htmlspecialchars($p['categoria_nombre']) ?></td>
          <td>Q<?= number_format($p['precio'], 2) ?></td>
          <td><?= $p['activo'] ? 'Publicado' : 'Oculto' ?></td>
          <td class="admin-actions">
            <a href="producto-form.php?id=<?= (int) $p['id'] ?>" class="btn btn-outline btn-sm">Editar</a>
            <form method="post" data-confirm="¿Eliminar este producto? También se quitará de la página web.">
              <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
              <button type="submit" name="eliminar_id" value="<?= (int) $p['id'] ?>" class="btn btn-outline btn-sm btn-danger">Eliminar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
