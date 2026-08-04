<?php
require __DIR__ . '/../includes/session.php';
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/functions.php';

if (is_admin()) {
    header('Location: index.php');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? '')) {
        $error = 'Tu sesión expiró, intenta de nuevo.';
    } else {
        $correo = trim($_POST['correo'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ? AND rol = 'admin'");
        $stmt->execute([$correo]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id']     = $user['id'];
            $_SESSION['user_nombre'] = $user['nombre'];
            $_SESSION['user_correo'] = $user['correo'];
            $_SESSION['user_rol']    = $user['rol'];
            header('Location: index.php');
            exit;
        }
        $error = 'Credenciales de administrador incorrectas.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Acceso administrador — Lissvery</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
<div class="admin-login">
  <form method="post" class="auth-form">
    <h1>Panel de administración</h1>
    <p>Inicia sesión con tu cuenta de administrador de Lissvery.</p>
    <?php if ($error): ?><p class="form-note error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
    <label for="correo">Correo</label>
    <input type="email" id="correo" name="correo" required autofocus>
    <label for="password">Contraseña</label>
    <input type="password" id="password" name="password" required>
    <button type="submit" class="btn btn-solid btn-block">Entrar</button>
  </form>
</div>
</body>
</html>
