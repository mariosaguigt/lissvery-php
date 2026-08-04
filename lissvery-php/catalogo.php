<?php
require __DIR__ . '/includes/session.php';
require __DIR__ . '/config/db.php';
require __DIR__ . '/includes/functions.php';

$categoriaSlug  = isset($_GET['categoria']) ? trim($_GET['categoria']) : null;
$categorias     = get_categorias($pdo);
$categoriaActual = $categoriaSlug ? get_categoria_by_slug($pdo, $categoriaSlug) : null;
$productos      = $categoriaSlug ? get_productos($pdo, $categoriaSlug) : get_productos($pdo);

$pageTitle = ($categoriaActual ? $categoriaActual['nombre'] : 'Catálogo') . ' — Lissvery';
require __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="wrap">
    <p class="breadcrumb"><a href="index.php">Inicio</a> / Catálogo<?php if ($categoriaActual): ?> / <?= htmlspecialchars($categoriaActual['nombre']) ?><?php endif; ?></p>
    <p class="eyebrow">Catálogo</p>
    <h1><?= $categoriaActual ? htmlspecialchars($categoriaActual['nombre']) : 'Todos nuestros productos y servicios' ?></h1>
    <?php if ($categoriaActual && !empty($categoriaActual['descripcion'])): ?>
      <p><?= htmlspecialchars($categoriaActual['descripcion']) ?></p>
    <?php else: ?>
      <p>Hosting, dominios, sitios web y licencias de software, todo en un mismo lugar.</p>
    <?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="category-filters">
      <a href="catalogo.php" class="filter-chip <?= !$categoriaSlug ? 'is-active' : '' ?>">Todos</a>
      <?php foreach ($categorias as $cat): ?>
        <a href="catalogo.php?categoria=<?= urlencode($cat['slug']) ?>" class="filter-chip <?= $categoriaSlug === $cat['slug'] ? 'is-active' : '' ?>"><?= htmlspecialchars($cat['nombre']) ?></a>
      <?php endforeach; ?>
    </div>

    <?php if ($categoriaActual && !empty($categoriaActual['en_construccion'])): ?>
      <div class="construction-inline">
        <p>Esta categoría está en construcción. Muy pronto vas a encontrar productos aquí.</p>
      </div>
    <?php elseif (empty($productos)): ?>
      <p>Todavía no hay productos publicados<?= $categoriaActual ? ' en esta categoría' : '' ?>.</p>
    <?php else: ?>
      <div class="catalog-grid">
        <?php foreach ($productos as $p): ?>
          <article class="catalog-card">
            <a href="producto.php?id=<?= (int) $p['id'] ?>" class="catalog-img">
              <?php if (!empty($p['imagen'])): ?>
                <img src="uploads/productos/<?= htmlspecialchars($p['imagen']) ?>" alt="<?= htmlspecialchars($p['nombre']) ?>">
              <?php else: ?>
                <span class="catalog-img-placeholder" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 8h18M8 5v3"/></svg></span>
              <?php endif; ?>
            </a>
            <div class="catalog-body">
              <p class="eyebrow"><?= htmlspecialchars($p['categoria_nombre']) ?></p>
              <h3><a href="producto.php?id=<?= (int) $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></a></h3>
              <p><?= htmlspecialchars(mb_strimwidth((string) $p['descripcion'], 0, 110, '…')) ?></p>
              <p class="catalog-price">
                <span class="currency">Q</span><?= number_format($p['precio'], 2) ?>
                <?php if ($p['categoria_slug'] === 'hosting-personal'): ?><span class="period">/mes</span><?php endif; ?>
              </p>
              <a href="producto.php?id=<?= (int) $p['id'] ?>" class="btn btn-outline btn-block">Ver detalle</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
