<?php
require __DIR__ . '/includes/session.php';
require __DIR__ . '/config/db.php';
require __DIR__ . '/includes/functions.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$redirect = $_GET['redirect'] ?? ($_POST['redirect'] ?? 'index.php');
$errores = [];
$nombre = '';
$correo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? '')) {
        $errores[] = 'Tu sesión expiró, intenta de nuevo.';
    } else {
        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $password = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';

        if ($nombre === '') $errores[] = 'Escribe tu nombre.';
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = 'Escribe un correo válido.';
        if (strlen($password) < 6) $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
        if ($password !== $password2) $errores[] = 'Las contraseñas no coinciden.';

        if (empty($errores)) {
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ?");
            $stmt->execute([$correo]);
            if ($stmt->fetch()) {
                $errores[] = 'Ya existe una cuenta con ese correo.';
            }
        }

        if (empty($errores)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, password_hash, rol) VALUES (?,?,?,'cliente')");
            $stmt->execute([$nombre, $correo, $hash]);

            $_SESSION['user_id']     = (int) $pdo->lastInsertId();
            $_SESSION['user_nombre'] = $nombre;
            $_SESSION['user_correo'] = $correo;
            $_SESSION['user_rol']    = 'cliente';

            header('Location: ' . ($redirect ?: 'index.php'));
            exit;
        }
    }
}

$pageTitle = 'Crear cuenta — Lissvery';
require __DIR__ . '/includes/header.php';
?>

<section class="section">
  <div class="wrap wrap-narrow">
    <p class="eyebrow">Cuenta</p>
    <h1>Crea tu cuenta en Lissvery</h1>
    <p>Con tu cuenta puedes ver el detalle de cada producto, guardar tu carrito y hacer pedidos.</p>

    <?php foreach ($errores as $e): ?><p class="form-note error"><?= htmlspecialchars($e) ?></p><?php endforeach; ?>

    <form method="post" class="auth-form">
      <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
      <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">

      <label for="nombre">Nombre completo</label>
      <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre) ?>" required autofocus>

      <label for="correo">Correo</label>
      <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($correo) ?>" required>

      <label for="password">Contraseña</label>
      <input type="password" id="password" name="password" minlength="6" required>

      <label for="password2">Confirmar contraseña</label>
      <input type="password" id="password2" name="password2" minlength="6" required>

      <button type="submit" class="btn btn-solid btn-block">Crear cuenta</button>
    </form>

    <p class="auth-alt">¿Ya tienes cuenta? <a href="login.php?redirect=<?= urlencode($redirect) ?>">Inicia sesión</a>.</p>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
