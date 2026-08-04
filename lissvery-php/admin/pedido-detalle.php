<?php
require __DIR__ . '/../includes/session.php';
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/functions.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT p.*, u.nombre AS cliente, u.correo FROM pedidos p JOIN usuarios u ON u.id=p.usuario_id WHERE p.id=?");
$stmt->execute([$id]);
$pedido = $stmt->fetch();

if (!$pedido) { exit('Pedido no encontrado'); }

$items = $pdo->prepare("SELECT * FROM pedido_items WHERE pedido_id=?");
$items->execute([$id]);
$items = $items->fetchAll();

$pageTitle = 'Detalle pedido — Admin';
require __DIR__ . '/includes/admin-header.php';
?>
<h1>Detalle pedido #<?= (int)$pedido['id'] ?></h1>
<p>Cliente: <?= htmlspecialchars($pedido['cliente']) ?></p>
<p>Correo: <?= htmlspecialchars($pedido['correo']) ?></p>
<p>Estado: <?= htmlspecialchars($pedido['estado']) ?></p>

<table class="admin-table">
<thead><tr><th>Producto</th><th>Cantidad</th><th>Precio</th></tr></thead>
<tbody>
<?php foreach($items as $item): ?>
<tr>
<td><?= htmlspecialchars($item['nombre_producto']) ?></td>
<td><?= (int)$item['cantidad'] ?></td>
<td>Q<?= number_format($item['precio_unitario'],2) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<h3>Total: Q<?= number_format($pedido['total'],2) ?></h3>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>