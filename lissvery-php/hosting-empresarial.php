<?php
require __DIR__ . '/includes/session.php';
require __DIR__ . '/config/db.php';
require __DIR__ . '/includes/functions.php';

$categoria = get_categoria_by_slug($pdo, 'hosting-empresarial');
$enConstruccion = !$categoria || !empty($categoria['en_construccion']);
$planes = $enConstruccion ? [] : get_productos($pdo, 'hosting-empresarial');

$pageTitle = 'Hosting empresarial — Lissvery';
require __DIR__ . '/includes/header.php';
?>

<?php if ($enConstruccion): ?>

  <section class="construction">
    <div class="wrap wrap-narrow">
      <p class="breadcrumb"><a href="index.php">Inicio</a> / Hosting / Hosting empresarial</p>
      <div class="construction-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="m14.7 6.3 3 3L7 20H4v-3Z"/><path d="M17.5 3.5a2.1 2.1 0 0 1 3 3l-1.5 1.5-3-3Z"/></svg>
      </div>
      <h1>Estamos construyendo el hosting empresarial</h1>
      <p>Estamos diseñando planes para operaciones con varias marcas, más tráfico y mayores necesidades de infraestructura. Todavía no están disponibles, pero puedes escribirnos y te avisamos apenas estén listos.</p>
      <a href="index.php#contacto" class="btn btn-solid btn-lg">Avísenme cuando esté listo</a>
    </div>
  </section>

<?php else: ?>

  <section class="page-hero">
    <div class="wrap">
      <p class="breadcrumb"><a href="index.php">Inicio</a> / Hosting / Hosting empresarial</p>
      <p class="eyebrow">Hosting empresarial</p>
      <h1>Planes para operaciones con varias marcas y más tráfico</h1>
    </div>
  </section>

  <section class="section">
    <div class="wrap">
      <div class="pricing-grid reveal">
        <?php foreach ($planes as $p): $caracteristicas = producto_caracteristicas($p); ?>
          <article class="price-card <?= $p['destacado'] ? 'is-featured' : '' ?>">
            <?php if ($p['destacado']): ?><p class="featured-tag">Recomendado</p><?php endif; ?>
            <h3><?= htmlspecialchars($p['nombre']) ?></h3>
            <p class="price-desc"><?= htmlspecialchars((string) $p['descripcion']) ?></p>
            <p class="price"><span class="currency">Q</span><span class="amount"><?= number_format($p['precio'], 2) ?></span><span class="period">/mes</span></p>
            <?php if (!empty($caracteristicas)): ?>
              <ul class="price-features"><?php foreach ($caracteristicas as $c): ?><li><?= htmlspecialchars($c) ?></li><?php endforeach; ?></ul>
            <?php endif; ?>
            <a href="producto.php?id=<?= (int) $p['id'] ?>" class="btn btn-outline btn-block">Ver detalle</a>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
