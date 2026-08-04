<?php
/**
 * Header compartido por todas las páginas públicas (no /admin).
 * Espera que el archivo que lo incluye ya haya hecho:
 *   require includes/session.php
 *   require config/db.php
 *   require includes/functions.php
 * y opcionalmente haya definido $pageTitle.
 */
$cartCount = is_logged_in() ? cart_count($pdo, (int) $_SESSION['user_id']) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle ?? 'Lissvery — Sitios web, hosting, dominios y licencias') ?></title>
<meta name="description" content="Lissvery: sitios web, hosting, dominios, correo y licencias de Windows, Office y antivirus, todo en un solo lugar.">
<link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 32 32%22><rect width=%2232%22 height=%2232%22 rx=%228%22 fill=%22%2324baa1%22/><path d=%22M9 21V11l7 6 7-6v10%22 stroke=%22%23f9bc14%22 stroke-width=%222.4%22 fill=%22none%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22/></svg>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/styles.css">
</head>
<body>

<a class="skip-link" href="#main">Saltar al contenido</a>

<header class="site-header" id="top">
  <div class="wrap header-inner">
    <a href="index.php" class="logo" aria-label="Lissvery, inicio">
      <span class="logo-mark" aria-hidden="true">
        <svg viewBox="0 0 32 32" width="30" height="30"><rect width="32" height="32" rx="9" fill="#24baa1"/><path d="M9 21V11l7 6 7-6v10" stroke="#f9bc14" stroke-width="2.4" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </span>
      <span class="logo-word">Liss<em>very</em></span>
    </a>

    <nav class="main-nav" id="main-nav" aria-label="Navegación principal">
      <a href="index.php#servicios">Servicios</a>

      <div class="nav-item has-submenu">
        <button class="nav-parent" type="button" aria-expanded="false" aria-haspopup="true">
          Hosting
          <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <ul class="submenu">
          <li><a href="hosting-personal.php">Hosting personal<small>Planes para tu sitio propio</small></a></li>
          <li><a href="hosting-empresarial.php">Hosting empresarial<small>Próximamente</small></a></li>
        </ul>
      </div>

      <a href="catalogo.php?categoria=licencias">Licencias</a>
      <a href="catalogo.php">Catálogo</a>
      <a href="index.php#preguntas">Preguntas</a>
      <a href="index.php#contacto">Contacto</a>
    </nav>

    <div class="header-actions">
      <a href="carrito.php" class="cart-link" aria-label="Ver carrito de compras">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="20" r="1.4" fill="currentColor" stroke="none"/><circle cx="18" cy="20" r="1.4" fill="currentColor" stroke="none"/><path d="M2.5 3h2l2.4 12.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 2-1.6L21 7H6"/></svg>
        <?php if ($cartCount > 0): ?><span class="cart-badge"><?= (int) $cartCount ?></span><?php endif; ?>
      </a>

      <?php if (is_logged_in()): ?>
        <div class="nav-item has-submenu">
          <button class="nav-parent" type="button" aria-expanded="false" aria-haspopup="true">
            <?= htmlspecialchars(explode(' ', $_SESSION['user_nombre'])[0]) ?>
            <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <ul class="submenu">
            <?php if (is_admin()): ?>
              <li><a href="admin/index.php">Panel de administración<small>Productos y pedidos</small></a></li>
            <?php endif; ?>
            <li><a href="carrito.php">Mi carrito<small><?= $cartCount ?> producto(s)</small></a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
          </ul>
        </div>
      <?php else: ?>
        <a href="login.php" class="btn btn-ghost hide-mobile">Iniciar sesión</a>
        <a href="registro.php" class="btn btn-solid">Crear cuenta</a>
      <?php endif; ?>

      <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="main-nav" aria-label="Abrir menú">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>

<main id="main">
<?php if (!empty($_SESSION['flash'])): ?>
  <div class="wrap flash-wrap">
    <div class="flash-msg <?= htmlspecialchars($_SESSION['flash']['tipo']) ?>"><?= htmlspecialchars($_SESSION['flash']['mensaje']) ?></div>
  </div>
  <?php unset($_SESSION['flash']); ?>
<?php endif; ?>
