<?php
/**
 * Arranca la sesión y expone funciones cortas para saber si hay
 * alguien conectado, si es administrador, y para exigir login.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in(): bool
{
    return !empty($_SESSION['user_id']);
}

function is_admin(): bool
{
    return is_logged_in() && ($_SESSION['user_rol'] ?? '') === 'admin';
}

function current_user(): ?array
{
    if (!is_logged_in()) {
        return null;
    }
    return [
        'id'     => $_SESSION['user_id'],
        'nombre' => $_SESSION['user_nombre'],
        'correo' => $_SESSION['user_correo'],
        'rol'    => $_SESSION['user_rol'],
    ];
}

/**
 * Corta la ejecución y manda a login.php si no hay sesión.
 * $redirect es a dónde volver después de iniciar sesión.
 */
function require_login(?string $redirect = null): void
{
    if (!is_logged_in()) {
        $target = $redirect ?? ($_SERVER['REQUEST_URI'] ?? 'index.php');
        header('Location: login.php?redirect=' . urlencode($target));
        exit;
    }
}

/**
 * Igual que require_login pero exige además que el rol sea admin.
 * Se usa desde dentro de la carpeta /admin, por eso redirige a
 * "login.php" en relativo (que ahí adentro es admin/login.php).
 */
function require_admin(): void
{
    if (!is_admin()) {
        header('Location: login.php');
        exit;
    }
}

function flash(string $tipo, string $mensaje): void
{
    $_SESSION['flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function verify_csrf(?string $token): bool
{
    return isset($_SESSION['csrf']) && is_string($token) && hash_equals($_SESSION['csrf'], $token);
}
