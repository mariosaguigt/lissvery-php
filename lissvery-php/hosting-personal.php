<?php
require __DIR__ . '/includes/session.php';
require __DIR__ . '/config/db.php';
require __DIR__ . '/includes/functions.php';

$planes = get_productos($pdo, 'hosting-personal');
$pageTitle = 'Hosting personal — Lissvery';
require __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="wrap">
    <p class="breadcrumb"><a href="index.php">Inicio</a> / Hosting / Hosting personal</p>
    <p class="eyebrow">Hosting personal</p>
    <h1>Planes para tu sitio propio, con espacio SSD y correo con tu dominio</h1>
    <p>Elige el plan según cuánto almacenamiento y cuántas cuentas de correo necesitas. Todos incluyen SSL gratuito, servidores rápidos y soporte para arrancar.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">

    <?php if (empty($planes)): ?>
      <p>Todavía no hay planes publicados. Muy pronto vas a encontrarlos aquí.</p>
    <?php else: ?>

      <div class="billing-toggle reveal" role="group" aria-label="Tipo de facturación">
        <button type="button" class="toggle-opt is-active" data-cycle="mensual">Mensual</button>
        <button type="button" class="toggle-opt" data-cycle="anual">Anual <span class="save-badge">Precio especial</span></button>
      </div>

      <div class="pricing-grid reveal">
        <?php foreach ($planes as $p): $caracteristicas = producto_caracteristicas($p); $ahorro = !empty($p['precio_anual']) ? ($p['precio'] * 12 - $p['precio_anual']) : 0; ?>
          <article class="price-card <?= $p['destacado'] ? 'is-featured' : '' ?>">
            <?php if ($p['destacado']): ?><p class="featured-tag">Más elegido</p><?php endif; ?>
            <h3><?= htmlspecialchars($p['nombre']) ?></h3>
            <p class="price-desc"><?= htmlspecialchars((string) $p['descripcion']) ?></p>
            <p class="price">
              <span class="currency">Q</span><span class="amount" data-monthly="<?= number_format($p['precio'], 2, '.', '') ?>" data-yearly="<?= number_format((float) $p['precio_anual'], 2, '.', '') ?>"><?= number_format($p['precio'], 2) ?></span><span class="period">/mes</span>
            </p>
            <p class="price-save" data-save="<?= $ahorro > 0 ? 'Ahorras Q' . number_format($ahorro, 2) . ' al año' : '' ?>"></p>
            <?php if (!empty($caracteristicas)): ?>
              <ul class="price-features">
                <?php foreach ($caracteristicas as $c): ?><li><?= htmlspecialchars($c) ?></li><?php endforeach; ?>
              </ul>
            <?php endif; ?>
            <a href="producto.php?id=<?= (int) $p['id'] ?>" class="btn <?= $p['destacado'] ? 'btn-solid' : 'btn-outline' ?> btn-block">Elegir <?= htmlspecialchars($p['nombre']) ?></a>
          </article>
        <?php endforeach; ?>
      </div>

      <div class="pricing-footnote reveal">
        <h3>Todos los planes incluyen</h3>
        <ul>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 4 6v6c0 5 3.4 8.7 8 10 4.6-1.3 8-5 8-10V6l-8-4Z"/><path d="m9 12 2 2 4-4"/></svg>Certificado SSL sin costo</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18v-2a4 4 0 0 1 4-4h1M21 18v-2a4 4 0 0 0-4-4h-1"/><circle cx="8" cy="8" r="3"/><circle cx="16" cy="8" r="3"/><path d="M9 18a3 3 0 0 1 6 0"/></svg>Asistencia para comenzar</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 11 14 10 22 21 10 13 10 13 2"/></svg>Servidores rápidos SSD</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>Seguridad para tu sitio</li>
        </ul>
      </div>

    <?php endif; ?>
  </div>
</section>

<section class="cta-band" id="contacto">
  <div class="wrap cta-inner reveal">
    <div class="cta-copy">
      <h2>¿No estás seguro cuál plan elegir?</h2>
      <p>Crea tu cuenta, escoge el plan que más se acerque y desde el detalle del producto puedes escribirnos con tus dudas antes de confirmar.</p>
      <ul class="contact-list">
        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.37 1.9.72 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.35 1.85.59 2.81.72A2 2 0 0 1 22 16.92Z"/></svg>+502 4000 1122</li>
        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>hola@lissvery.com</li>
      </ul>
    </div>
    <div>
      <?php if (is_logged_in()): ?>
        <a href="catalogo.php?categoria=hosting-personal" class="btn btn-solid btn-lg">Ver planes en el catálogo</a>
      <?php else: ?>
        <a href="registro.php?redirect=<?= urlencode('hosting-personal.php') ?>" class="btn btn-solid btn-lg">Crear cuenta y elegir plan</a>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
