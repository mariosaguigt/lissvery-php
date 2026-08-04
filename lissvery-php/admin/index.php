<?php
require __DIR__ . '/../includes/session.php';
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/functions.php';
require_admin();

$totalProductos = (int) $pdo->query("SELECT COUNT(*) FROM productos")->fetchColumn();
$totalPublicados = (int) $pdo->query("SELECT COUNT(*) FROM productos WHERE activo = 1")->fetchColumn();
$totalPedidos = (int) $pdo->query("SELECT COUNT(*) FROM pedidos")->fetchColumn();
$totalUsuarios = (int) $pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'cliente'")->fetchColumn();

$pageTitle = 'Panel — Lissvery';
require __DIR__ . '/includes/admin-header.php';
?>

<h1>Hola, <?= htmlspecialchars(explode(' ', $_SESSION['user_nombre'])[0]) ?></h1>
<p>Desde aquí controlas lo que aparece en la página web: subir un producto, editarlo o eliminarlo se refleja al instante en el sitio.</p>

<div class="admin-stats">
  <div class="admin-stat-card"><span><?= $totalPublicados ?></span><p>Productos publicados (de <?= $totalProductos ?> en total)</p></div>
  <div class="admin-stat-card"><span><?= $totalPedidos ?></span><p>Pedidos recibidos</p></div>
  <div class="admin-stat-card"><span><?= $totalUsuarios ?></span><p>Cuentas de clientes registradas</p></div>
</div>

<p>
  <a href="producto-form.php" class="btn btn-solid">+ Subir un nuevo producto</a>
  <a href="productos.php" class="btn btn-outline" style="margin-left:10px;">Ver todos los productos</a>
</p>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
