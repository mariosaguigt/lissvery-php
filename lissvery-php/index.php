<?php
require __DIR__ . '/includes/session.php';
require __DIR__ . '/config/db.php';
require __DIR__ . '/includes/functions.php';

$licencias = get_productos($pdo, 'licencias');
$licenciasDestacadas = array_slice($licencias, 0, 3);

$pageTitle = 'Lissvery — Sitios web, hosting, dominios y licencias';
require __DIR__ . '/includes/header.php';
?>

<!-- HERO -->
<section class="hero">
  <div class="wrap hero-grid">
    <div class="hero-copy reveal">
      <p class="eyebrow">Sitios web · Hosting · Dominios · Correo · Licencias</p>
      <h1>Tu negocio entra a internet con una sola llamada, no con cinco proveedores distintos.</h1>
      <p class="hero-lead">Diseñamos tu sitio, lo alojamos en servidores rápidos, registramos tu dominio, activamos tu correo y te vendemos las licencias que necesitas. Un equipo, una factura, un número al que llamar.</p>
      <div class="hero-cta">
        <a href="hosting-personal.php" class="btn btn-solid btn-lg">Ver planes de hosting</a>
        <a href="catalogo.php" class="btn btn-outline btn-lg">Ver catálogo</a>
      </div>
      <dl class="hero-stats">
        <div><dt>99.9%</dt><dd>tiempo de actividad</dd></div>
        <div><dt>+250</dt><dd>sitios activos</dd></div>
        <div><dt>&lt; 2 h</dt><dd>tiempo de respuesta</dd></div>
      </dl>
    </div>

    <div class="hero-visual reveal" aria-hidden="true">
      <div class="browser-card">
        <div class="browser-chrome">
          <span class="dot dot-a"></span><span class="dot dot-b"></span><span class="dot dot-c"></span>
          <div class="address-bar">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <span id="typedDomain">tunegocio</span><span class="caret">|</span><span class="tld">.com</span>
          </div>
        </div>
        <div class="browser-body">
          <div class="skeleton-nav"><span></span><span></span><span></span></div>
          <div class="skeleton-hero"></div>
          <div class="skeleton-row"><div class="skeleton-box"></div><div class="skeleton-box"></div><div class="skeleton-box"></div></div>
        </div>
      </div>
      <ul class="status-stack" id="statusStack">
        <li><span class="status-dot"></span>Sitio web publicado</li>
        <li><span class="status-dot"></span>Hosting activo</li>
        <li><span class="status-dot"></span>Dominio conectado</li>
        <li><span class="status-dot"></span>Correo funcionando</li>
      </ul>
    </div>
  </div>
</section>

<section class="trust-strip">
  <div class="wrap trust-inner">
    <p>Con certificado SSL incluido</p><span class="trust-sep" aria-hidden="true">•</span>
    <p>Servidores con monitoreo 24/7</p><span class="trust-sep" aria-hidden="true">•</span>
    <p>Respaldos automáticos</p><span class="trust-sep" aria-hidden="true">•</span>
    <p>Soporte real, en español</p>
  </div>
</section>

<!-- SERVICIOS -->
<section class="section" id="servicios">
  <div class="wrap">
    <div class="section-head reveal">
      <p class="eyebrow">Servicios</p>
      <h2>Todo lo que tu presencia en línea necesita, bajo un mismo techo</h2>
      <p class="section-lead">Cada servicio funciona solo o en conjunto. Empieza con uno y suma los demás cuando los necesites.</p>
    </div>
    <div class="card-grid reveal">
      <article class="service-card">
        <div class="service-icon tint-teal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><polyline points="8 6 2 12 8 18"/><polyline points="16 6 22 12 16 18"/><line x1="14" y1="4" x2="10" y2="20"/></svg></div>
        <h3>Sitios web</h3>
        <p>Diseño y desarrollo a medida para negocios, profesionales y tiendas en línea. Responsive y pensado para convertir visitas en clientes.</p>
        <a href="index.php#contacto" class="card-link">Pedir una cotización <span aria-hidden="true">→</span></a>
      </article>
      <article class="service-card">
        <div class="service-icon tint-gold"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="6" rx="1.5"/><rect x="3" y="14" width="18" height="6" rx="1.5"/><circle cx="7" cy="7" r="0.9" fill="currentColor" stroke="none"/><circle cx="7" cy="17" r="0.9" fill="currentColor" stroke="none"/></svg></div>
        <h3>Hosting</h3>
        <p>Alojamiento en servidores SSD con monitoreo constante, SSL incluido y respaldos automáticos.</p>
        <a href="hosting-personal.php" class="card-link">Ver planes de hosting <span aria-hidden="true">→</span></a>
      </article>
      <article class="service-card">
        <div class="service-icon tint-teal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18 14 14 0 0 1 0-18Z"/></svg></div>
        <h3>Dominios</h3>
        <p>Registro, renovación y transferencia de dominios .com, .net, .org y .gt. Configuramos el DNS por ti.</p>
        <a href="catalogo.php?categoria=dominios" class="card-link">Ver dominios <span aria-hidden="true">→</span></a>
      </article>
      <article class="service-card">
        <div class="service-icon tint-gold"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg></div>
        <h3>Correo electrónico</h3>
        <p>Cuentas de correo con tu propio nombre de dominio, configuradas en tu teléfono y computadora el mismo día.</p>
        <a href="index.php#contacto" class="card-link">Ver cuentas de correo <span aria-hidden="true">→</span></a>
      </article>
    </div>
  </div>
</section>

<!-- HOSTING TEASER -->
<section class="section section-alt" id="hosting">
  <div class="wrap">
    <div class="section-head reveal">
      <p class="eyebrow">Hosting</p>
      <h2>Elige el hosting según el tamaño de lo que estás construyendo</h2>
    </div>
    <div class="hosting-teaser reveal">
      <div class="hosting-panel is-highlight">
        <span class="tag">Disponible ahora</span>
        <h3>Hosting personal</h3>
        <p>Para un sitio propio, un portafolio o un negocio pequeño. Tres planes según cuánto espacio y correo necesites.</p>
        <ul>
          <li>Desde 1 sitio web con SSL gratuito</li>
          <li>Correos con tu propio dominio</li>
          <li>Instalación de WordPress con 1 clic</li>
        </ul>
        <a href="hosting-personal.php" class="btn btn-solid btn-block">Ver planes de hosting personal</a>
      </div>
      <div class="hosting-panel">
        <span class="tag">En construcción</span>
        <h3>Hosting empresarial</h3>
        <p>Para operaciones con varias marcas, más tráfico y necesidades de infraestructura más grandes.</p>
        <ul>
          <li>Múltiples sitios y sucursales</li>
          <li>Soporte dedicado 24/7</li>
          <li>Capacidad a la medida</li>
        </ul>
        <a href="hosting-empresarial.php" class="btn btn-outline btn-block">Ver estado del proyecto</a>
      </div>
    </div>
  </div>
</section>

<!-- LICENCIAS -->
<section class="section" id="licencias">
  <div class="wrap">
    <div class="section-head reveal">
      <p class="eyebrow">Licencias de software</p>
      <h2>Windows, Office y antivirus, con activación garantizada</h2>
      <p class="section-lead">Licencias originales para equipar tu computadora o la de tu negocio.</p>
    </div>
    <?php if (empty($licenciasDestacadas)): ?>
      <p>Muy pronto vas a encontrar aquí las licencias disponibles.</p>
    <?php else: ?>
      <div class="catalog-grid reveal">
        <?php foreach ($licenciasDestacadas as $p): ?>
          <article class="catalog-card">
            <a href="producto.php?id=<?= (int) $p['id'] ?>" class="catalog-img">
              <?php if ($p['imagen']): ?>
                <img src="uploads/productos/<?= htmlspecialchars($p['imagen']) ?>" alt="<?= htmlspecialchars($p['nombre']) ?>">
              <?php else: ?>
                <span class="catalog-img-placeholder" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 8h18M8 5v3"/></svg></span>
              <?php endif; ?>
            </a>
            <div class="catalog-body">
              <p class="eyebrow"><?= htmlspecialchars($p['categoria_nombre']) ?></p>
              <h3><a href="producto.php?id=<?= (int) $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></a></h3>
              <p><?= htmlspecialchars(mb_strimwidth((string) $p['descripcion'], 0, 90, '…')) ?></p>
              <p class="catalog-price"><span class="currency">Q</span><?= number_format($p['precio'], 2) ?></p>
              <a href="producto.php?id=<?= (int) $p['id'] ?>" class="btn btn-outline btn-block">Ver detalle</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
      <p style="margin-top:2rem;"><a href="catalogo.php?categoria=licencias" class="card-link">Ver todas las licencias <span aria-hidden="true">→</span></a></p>
    <?php endif; ?>
  </div>
</section>

<!-- POR QUÉ -->
<section class="section section-alt" id="por-que">
  <div class="wrap">
    <div class="section-head reveal">
      <p class="eyebrow">Por qué Lissvery</p>
      <h2>Un solo proveedor, para no perder tiempo explicando el problema dos veces</h2>
    </div>
    <div class="feature-grid reveal">
      <div class="feature"><svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 11 14 10 22 21 10 13 10 13 2"/></svg><h3>Velocidad real</h3><p>Servidores SSD y caché optimizada para que tu sitio cargue rápido.</p></div>
      <div class="feature"><svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 4 6v6c0 5 3.4 8.7 8 10 4.6-1.3 8-5 8-10V6l-8-4Z"/><path d="m9 12 2 2 4-4"/></svg><h3>SSL incluido</h3><p>Certificado de seguridad activo desde el primer día, sin costo adicional.</p></div>
      <div class="feature"><svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M17 16v-2a5 5 0 0 0-10 0v2"/><path d="M4 16h16l-1.5 5h-13Z"/><path d="M12 3v3"/></svg><h3>Respaldos automáticos</h3><p>Copia de tu sitio y tu correo, con restauración disponible.</p></div>
      <div class="feature"><svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18v-2a4 4 0 0 1 4-4h1M21 18v-2a4 4 0 0 0-4-4h-1"/><circle cx="8" cy="8" r="3"/><circle cx="16" cy="8" r="3"/><path d="M9 18a3 3 0 0 1 6 0"/></svg><h3>Soporte humano</h3><p>Escribes o llamas y responde una persona que conoce tu cuenta.</p></div>
      <div class="feature"><svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-3-6.7"/><polyline points="21 3 21 9 15 9"/></svg><h3>Migración sin costo</h3><p>Si ya tienes un sitio en otro proveedor, lo movemos por ti.</p></div>
      <div class="feature"><svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg><h3>Licencias originales</h3><p>Windows, Office y antivirus con activación garantizada.</p></div>
    </div>
  </div>
</section>

<!-- PROCESO -->
<section class="section" id="proceso">
  <div class="wrap">
    <div class="section-head reveal">
      <p class="eyebrow">Cómo funciona</p>
      <h2>De la idea a tu sitio publicado, en cuatro pasos</h2>
    </div>
    <ol class="process-list reveal">
      <li><span class="process-num">01</span><h3>Crea tu cuenta</h3><p>Regístrate para poder ver el detalle de cada producto y comprar cuando quieras.</p></li>
      <li><span class="process-num">02</span><h3>Elige tu plan</h3><p>Hosting, dominio, sitio web o licencia: agrégalo a tu carrito.</p></li>
      <li><span class="process-num">03</span><h3>Confirma tu pedido</h3><p>Revisamos contigo los detalles y coordinamos el pago.</p></li>
      <li><span class="process-num">04</span><h3>Lo activamos</h3><p>Publicamos tu sitio, activamos tu correo o entregamos tu licencia.</p></li>
    </ol>
  </div>
</section>

<!-- PREGUNTAS -->
<section class="section section-alt" id="preguntas">
  <div class="wrap wrap-narrow">
    <div class="section-head reveal">
      <p class="eyebrow">Preguntas frecuentes</p>
      <h2>Lo que casi todos preguntan antes de empezar</h2>
    </div>
    <div class="faq-list reveal">
      <details class="faq-item" open><summary>¿Por qué me piden crear una cuenta?<span class="faq-icon" aria-hidden="true"></span></summary><p>Así podemos guardar tu carrito, tus pedidos y darte soporte más rápido la próxima vez que nos escribas.</p></details>
      <details class="faq-item"><summary>¿Puedo transferir un dominio que ya tengo?<span class="faq-icon" aria-hidden="true"></span></summary><p>Sí. Transferimos tu dominio desde otro proveedor sin que deje de funcionar durante el proceso.</p></details>
      <details class="faq-item"><summary>¿Las licencias de Windows y Office son originales?</summary><p>Sí, todas nuestras licencias son originales y quedan activadas a tu nombre.</p></details>
      <details class="faq-item"><summary>¿Cómo pago mi pedido?</summary><p>Al confirmar tu pedido, nuestro equipo te contacta por correo o teléfono para coordinar el pago.</p></details>
      <details class="faq-item"><summary>¿Hacen respaldo de mi información?</summary><p>Sí, todos los planes de hosting incluyen respaldo automático de tu sitio y tu correo.</p></details>
      <details class="faq-item"><summary>¿Cuál es la diferencia entre hosting personal y empresarial?</summary><p>El hosting personal está disponible hoy. El hosting empresarial, para varias marcas o mayor tráfico, está en construcción.</p></details>
    </div>
  </div>
</section>

<!-- CTA CONTACTO -->
<section class="cta-band" id="contacto">
  <div class="wrap cta-inner reveal">
    <div class="cta-copy">
      <h2>Cuéntanos qué necesitas y te respondemos hoy mismo</h2>
      <p>Escríbenos con el nombre de dominio que tienes en mente y el tipo de sitio que quieres.</p>
      <ul class="contact-list">
        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.37 1.9.72 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.35 1.85.59 2.81.72A2 2 0 0 1 22 16.92Z"/></svg>+502 4000 1122</li>
        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>hola@lissvery.com</li>
        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16.5 14.5"/></svg>Lunes a viernes, 8:00–18:00</li>
      </ul>
    </div>
    <form class="cta-form" id="contactForm">
      <label for="nombre">Nombre</label>
      <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>
      <label for="correo">Correo</label>
      <input type="email" id="correo" name="correo" placeholder="tu@correo.com" required>
      <label for="mensaje">Cuéntanos qué necesitas</label>
      <textarea id="mensaje" name="mensaje" rows="3" placeholder="Quiero un sitio para..."></textarea>
      <button type="submit" class="btn btn-solid btn-block">Enviar mensaje</button>
      <p class="form-note" id="formNote" role="status" aria-live="polite"></p>
    </form>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
