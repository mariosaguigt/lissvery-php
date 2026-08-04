<?php
require __DIR__ . '/../includes/session.php';
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/functions.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'], $_POST['estado'])) {
    if (verify_csrf($_POST['csrf'] ?? '')) {
        $estadosValidos = ['pendiente', 'confirmado', 'cancelado'];
        $estado = in_array($_POST['estado'], $estadosValidos, true) ? $_POST['estado'] : 'pendiente';
        $stmt = $pdo->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
        $stmt->execute([$estado, (int) $_POST['pedido_id']]);
        flash('success', 'Estado del pedido actualizado.');
    }
    header('Location: pedidos.php');
    exit;
}

$pedidos = $pdo->query(
    "SELECT p.*, u.nombre AS cliente, u.correo
     FROM pedidos p JOIN usuarios u ON u.id = p.usuario_id
     ORDER BY p.creado_en DESC"
)->fetchAll();

$pageTitle = 'Pedidos — Panel Lissvery';
require __DIR__ . '/includes/admin-header.php';
?>

<h1>Pedidos</h1>

<?php if (empty($pedidos)): ?>
  <p>Todavía no hay pedidos registrados.</p>
<?php else: ?>
  <table class="admin-table">
    <thead><tr><th>#</th><th>Cliente</th><th>Total</th><th>Fecha</th><th>Estado</th><th>Detalle</th></tr></thead>
    <tbody>
      <?php foreach ($pedidos as $p): ?>
        <tr>
          <td>#<?= (int) $p['id'] ?></td>
          <td><?= htmlspecialchars($p['cliente']) ?><br><small><?= htmlspecialchars($p['correo']) ?></small></td>
          <td>Q<?= number_format($p['total'], 2) ?></td>
          <td><?= htmlspecialchars($p['creado_en']) ?></td>
          <td>
            <form method="post" style="display:flex; gap:8px; align-items:center;">
              <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
              <input type="hidden" name="pedido_id" value="<?= (int) $p['id'] ?>">
              <select name="estado" onchange="this.form.submit()">
                <option value="pendiente" <?= $p['estado'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                <option value="confirmado" <?= $p['estado'] === 'confirmado' ? 'selected' : '' ?>>Confirmado</option>
                <option value="cancelado" <?= $p['estado'] === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
              </select>
            </form>
          </td>
          <td>
            <a href="pedido-detalle.php?id=<?= (int) $p['id'] ?>" class="btn btn-outline">Ver detalle</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
