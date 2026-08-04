<?php
require __DIR__ . '/includes/session.php';
require __DIR__ . '/config/db.php';
require __DIR__ . '/includes/functions.php';

require_login('carrito.php');
$userId = (int) $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (verify_csrf($_POST['csrf'] ?? '')) {
        $itemId = (int) ($_POST['item_id'] ?? 0);
        if (isset($_POST['eliminar'])) {
            cart_remove($pdo, $userId, $itemId);
            flash('success', 'Producto eliminado del carrito.');
        } elseif (isset($_POST['actualizar'])) {
            cart_update_qty($pdo, $userId, $itemId, (int) ($_POST['cantidad'] ?? 1));
            flash('success', 'Carrito actualizado.');
        }
    }
    header('Location: carrito.php');
    exit;
}

$items = cart_items($pdo, $userId);
$total = cart_total($items);
$pageTitle = 'Mi carrito — Lissvery';
require __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="wrap">
    <p class="eyebrow">Carrito</p>
    <h1>Tu carrito de compras</h1>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <?php if (empty($items)): ?>
      <p>Tu carrito está vacío. <a href="catalogo.php">Ver catálogo</a>.</p>
    <?php else: ?>
      <div class="cart-table">
        <?php foreach ($items as $it): ?>
          <div class="cart-row">
            <div class="cart-row-media">
              <?php if (!empty($it['imagen'])): ?>
                <img src="uploads/productos/<?= htmlspecialchars($it['imagen']) ?>" alt="">
              <?php else: ?>
                <span class="cart-row-placeholder" aria-hidden="true"></span>
              <?php endif; ?>
            </div>
            <div class="cart-row-info">
              <h3><a href="producto.php?id=<?= (int) $it['id'] ?>"><?= htmlspecialchars($it['nombre']) ?></a></h3>
              <p class="cart-row-meta"><?= $it['ciclo'] === 'anual' ? 'Facturación anual' : 'Facturación mensual' ?></p>
            </div>
            <form method="post" class="cart-row-qty">
              <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
              <input type="hidden" name="item_id" value="<?= (int) $it['item_id'] ?>">
              <input type="number" name="cantidad" value="<?= (int) $it['cantidad'] ?>" min="1">
              <button type="submit" name="actualizar" value="1" class="btn btn-outline btn-sm">Actualizar</button>
            </form>
            <p class="cart-row-price">Q<?= number_format(item_precio_unitario($it) * (int) $it['cantidad'], 2) ?></p>
            <form method="post">
              <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
              <input type="hidden" name="item_id" value="<?= (int) $it['item_id'] ?>">
              <button type="submit" name="eliminar" value="1" class="cart-remove" aria-label="Eliminar producto">✕</button>
            </form>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="cart-summary">
        <p>Total <strong>Q<?= number_format($total, 2) ?></strong></p>
        <a href="checkout.php" class="btn btn-solid btn-lg">Confirmar pedido</a>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
