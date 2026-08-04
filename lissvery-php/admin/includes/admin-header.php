<?php
/**
 * Requiere que el archivo que lo incluye ya haya hecho:
 *   require __DIR__.'/../includes/session.php'
 *   require __DIR__.'/../config/db.php'
 *   require __DIR__.'/../includes/functions.php'
 *   require_admin();
 * y opcionalmente haya definido $pageTitle.
 */
$actual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle ?? 'Panel — Lissvery') ?></title>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
<div class="admin-shell">
  <aside class="admin-sidebar">
    <a href="../index.php" class="logo" target="_blank">
      <span class="logo-mark" aria-hidden="true"><svg viewBox="0 0 32 32" width="26" height="26"><rect width="32" height="32" rx="9" fill="#24baa1"/><path d="M9 21V11l7 6 7-6v10" stroke="#f9bc14" stroke-width="2.4" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
      <span class="logo-word">Liss<em>very</em></span>
    </a>
    <p class="admin-role">Panel de administración</p>
    <nav class="admin-nav">
      <a href="index.php" class="<?= $actual === 'index.php' ? 'is-active' : '' ?>">Resumen</a>
      <a href="productos.php" class="<?= in_array($actual, ['productos.php', 'producto-form.php']) ? 'is-active' : '' ?>">Productos</a>
      <a href="pedidos.php" class="<?= $actual === 'pedidos.php' ? 'is-active' : '' ?>">Pedidos</a>
      <a href="../index.php" target="_blank">Ver sitio web ↗</a>
      <a href="logout.php">Cerrar sesión</a>
    </nav>
  </aside>

  <div class="admin-main">
    <?php if (!empty($_SESSION['flash'])): ?>
      <div class="flash-msg <?= htmlspecialchars($_SESSION['flash']['tipo']) ?>"><?= htmlspecialchars($_SESSION['flash']['mensaje']) ?></div>
      <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
