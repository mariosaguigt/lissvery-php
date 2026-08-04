<?php
require __DIR__ . '/includes/session.php';
require __DIR__ . '/config/db.php';
require __DIR__ . '/includes/functions.php';

require_login('checkout.php');
$userId = (int) $_SESSION['user_id'];

$items = cart_items($pdo, $userId);
if (empty($items)) {
    header('Location: carrito.php');
    exit;
}

$pedidoId = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf($_POST['csrf'] ?? '')) {
    $total = cart_total($items);
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO pedidos (usuario_id, total, estado) VALUES (?, ?, 'pendiente')");
    $stmt->execute([$userId, $total]);
    $pedidoId = (int) $pdo->lastInsertId();

    $ins = $pdo->prepare(
        "INSERT INTO pedido_items (pedido_id, producto_id, nombre_producto, precio_unitario, cantidad, ciclo)
         VALUES (?,?,?,?,?,?)"
    );
    foreach ($items as $it) {
        $ins->execute([$pedidoId, $it['id'], $it['nombre'], item_precio_unitario($it), $it['cantidad'], $it['ciclo']]);
    }

    $pdo->prepare("DELETE FROM carrito WHERE usuario_id = ?")->execute([$userId]);
    $pdo->commit();
}

$pageTitle = 'Confirmar pedido — Lissvery';
require __DIR__ . '/includes/header.php';
?>

<section class="section">
  <div class="wrap wrap-narrow">
    <?php if ($pedidoId): ?>
      <p class="eyebrow">Pedido recibido</p>
      <h1>¡Gracias! Tu pedido #<?= $pedidoId ?> quedó registrado</h1>
      <p>Nuestro equipo te va a escribir a tu correo para coordinar el pago y la activación de cada producto.</p>
      <a href="catalogo.php" class="btn btn-outline">Seguir viendo el catálogo</a>
    <?php else: ?>
      <p class="eyebrow">Confirmar pedido</p>
      <h1>Revisa tu pedido antes de confirmar</h1>

      <div class="cart-table">
        <?php $total = 0; foreach ($items as $it): $sub = item_precio_unitario($it) * (int) $it['cantidad']; $total += $sub; ?>
          <div class="cart-row cart-row-simple">
            <p><?= htmlspecialchars($it['nombre']) ?> × <?= (int) $it['cantidad'] ?> <?= $it['ciclo'] === 'anual' ? '(anual)' : '' ?></p>
            <p>Q<?= number_format($sub, 2) ?></p>
          </div>
        <?php endforeach; ?>
      </div>

      <p class="cart-summary"><strong>Total: Q<?= number_format($total, 2) ?></strong></p>

      <form method="post">
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
        <button type="submit" class="btn btn-solid btn-lg btn-block">Confirmar pedido</button>
      </form>
      <p class="form-note">Este pedido no procesa un pago en línea todavía; nuestro equipo te contacta para completar el cobro.</p>
    <?php endif; ?>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
