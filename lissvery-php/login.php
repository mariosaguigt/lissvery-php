<?php
require __DIR__ . '/includes/session.php';
require __DIR__ . '/config/db.php';
require __DIR__ . '/includes/functions.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$redirect = $_GET['redirect'] ?? ($_POST['redirect'] ?? 'index.php');
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? '')) {
        $error = 'Tu sesión expiró, intenta de nuevo.';
    } else {
        $correo = trim($_POST['correo'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id']     = $user['id'];
            $_SESSION['user_nombre'] = $user['nombre'];
            $_SESSION['user_correo'] = $user['correo'];
            $_SESSION['user_rol']    = $user['rol'];
            header('Location: ' . ($redirect ?: 'index.php'));
            exit;
        }
        $error = 'Correo o contraseña incorrectos.';
    }
}

$pageTitle = 'Iniciar sesión — Lissvery';
require __DIR__ . '/includes/header.php';
?>

<section class="section">
  <div class="wrap wrap-narrow">
    <p class="eyebrow">Cuenta</p>
    <h1>Inicia sesión para continuar</h1>
    <p>Necesitas una cuenta para ver el detalle de los productos y comprar.</p>

    <?php if ($error): ?><p class="form-note error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <form method="post" class="auth-form">
      <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
      <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">

      <label for="correo">Correo</label>
      <input type="email" id="correo" name="correo" required autofocus>

      <label for="password">Contraseña</label>
      <input type="password" id="password" name="password" required>

      <button type="submit" class="btn btn-solid btn-block">Iniciar sesión</button>
    </form>

    <p class="auth-alt">¿Todavía no tienes cuenta? <a href="registro.php?redirect=<?= urlencode($redirect) ?>">Regístrate aquí</a>.</p>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
