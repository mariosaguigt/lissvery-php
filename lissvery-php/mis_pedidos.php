<?php
require __DIR__ . '/includes/session.php';
require __DIR__ . '/config/db.php';
require __DIR__ . '/includes/functions.php';

require_login('mis_pedidos.php');
$userId = (int) $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY creado_en DESC");
$stmt->execute([$userId]);
$pedidos = $stmt->fetchAll();

$pageTitle = 'Mis pedidos — Lissvery';
require __DIR__ . '/includes/header.php';
?>
<section class="section">
<div class="wrap">
<h1>Mis pedidos</h1>
<?php foreach ($pedidos as $pedido): ?>
<div class="cart-table">
<h3>Pedido #<?= (int)$pedido['id'] ?></h3>
<p>Fecha: <?= htmlspecialchars($pedido['creado_en']) ?></p>
<p>Estado: <?= htmlspecialchars($pedido['estado']) ?></p>
<p>Total: Q<?= number_format($pedido['total'],2) ?></p>

<?php
$d = $pdo->prepare("SELECT * FROM pedido_items WHERE pedido_id = ?");
$d->execute([$pedido['id']]);
foreach($d->fetchAll() as $item):
?>
<p><?= htmlspecialchars($item['nombre_producto']) ?> x <?= (int)$item['cantidad'] ?> - Q<?= number_format($item['precio_unitario'],2) ?></p>
<?php endforeach; ?>
</div>
<?php endforeach; ?>
</div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>