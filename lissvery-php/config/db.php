<?php
/**
 * Conexión a la base de datos.
 * Cambia estos 4 valores por los datos reales de tu hosting o de tu
 * servidor local (XAMPP / Laragon / WAMP) antes de usar el sitio.
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'lissvery');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    die('No se pudo conectar a la base de datos. Revisa config/db.php. Detalle: ' . htmlspecialchars($e->getMessage()));
}
