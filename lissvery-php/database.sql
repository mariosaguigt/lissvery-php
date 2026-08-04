-- ============================================================
-- LISSVERY — Base de datos
-- Importa este archivo completo en phpMyAdmin (o por consola)
-- en una base llamada "lissvery" antes de usar el sitio.
-- ============================================================

CREATE DATABASE IF NOT EXISTS lissvery CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lissvery;

-- ---------------- Usuarios ----------------
CREATE TABLE usuarios (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  nombre        VARCHAR(120) NOT NULL,
  correo        VARCHAR(160) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  rol           ENUM('cliente','admin') NOT NULL DEFAULT 'cliente',
  creado_en     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------- Categorías ----------------
CREATE TABLE categorias (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  slug            VARCHAR(60) NOT NULL UNIQUE,
  nombre          VARCHAR(120) NOT NULL,
  descripcion     VARCHAR(255) NULL,
  en_construccion TINYINT(1) NOT NULL DEFAULT 0,
  orden           INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;

-- ---------------- Productos ----------------
CREATE TABLE productos (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  categoria_id    INT NOT NULL,
  nombre          VARCHAR(160) NOT NULL,
  descripcion     TEXT NULL,
  precio          DECIMAL(10,2) NOT NULL DEFAULT 0,
  precio_anual    DECIMAL(10,2) NULL,
  imagen          VARCHAR(180) NULL,
  caracteristicas TEXT NULL,
  destacado       TINYINT(1) NOT NULL DEFAULT 0,
  activo          TINYINT(1) NOT NULL DEFAULT 1,
  creado_en       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------- Carrito ----------------
CREATE TABLE carrito (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id  INT NOT NULL,
  producto_id INT NOT NULL,
  cantidad    INT NOT NULL DEFAULT 1,
  ciclo       ENUM('mensual','anual') NOT NULL DEFAULT 'mensual',
  agregado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------- Pedidos ----------------
CREATE TABLE pedidos (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  total      DECIMAL(10,2) NOT NULL DEFAULT 0,
  estado     ENUM('pendiente','confirmado','cancelado') NOT NULL DEFAULT 'pendiente',
  creado_en  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE pedido_items (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  pedido_id        INT NOT NULL,
  producto_id      INT NULL,
  nombre_producto  VARCHAR(160) NOT NULL,
  precio_unitario  DECIMAL(10,2) NOT NULL,
  cantidad         INT NOT NULL DEFAULT 1,
  ciclo            VARCHAR(20) NOT NULL DEFAULT 'mensual',
  FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
  FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
-- Datos iniciales
-- ============================================================

-- Categorías
INSERT INTO categorias (slug, nombre, descripcion, en_construccion, orden) VALUES
('hosting-personal',   'Hosting personal',   'Planes de hosting para un sitio propio o un negocio pequeño.', 0, 1),
('hosting-empresarial','Hosting empresarial','Planes para operaciones con varias marcas y más tráfico.',      1, 2),
('dominios',           'Dominios',           'Registro y renovación de dominios .com, .net, .org y .gt.',    0, 3),
('sitios-web',         'Sitios web',         'Diseño y desarrollo de sitios web a medida.',                   0, 4),
('licencias',          'Licencias de software','Licencias originales de Windows, Office y antivirus.',        0, 5);

-- Cuenta de administrador
-- Correo:      admin@lissvery.com
-- Contraseña:  Admin123!   (cámbiala después de tu primer ingreso)
INSERT INTO usuarios (nombre, correo, password_hash, rol) VALUES
('Administrador Lissvery', 'admin@lissvery.com', '$2b$12$X93lLOo1/Qe6ztdD6sY7Guh8OOhXzFxhVvN5hk/feHxQJB69Y6Idm', 'admin');

-- Planes de Hosting personal (tal como los definiste)
INSERT INTO productos (categoria_id, nombre, descripcion, precio, precio_anual, caracteristicas, destacado, activo) VALUES
(
  (SELECT id FROM categorias WHERE slug='hosting-personal'),
  'Lissvery Lite',
  'Para un primer sitio sencillo: portafolio, currículum o landing page.',
  39.00, 390.00,
  '1 sitio web
700 MB de almacenamiento rápido SSD
1 dominio conectado
3 correos empresariales
Seguridad SSL gratuita
Instalación de WordPress con 1 clic
1 base de datos
Respaldo mensual
Página optimizada para celulares
Soporte
Botón de WhatsApp integrado',
  0, 1
),
(
  (SELECT id FROM categorias WHERE slug='hosting-personal'),
  'Lissvery One',
  'Para un negocio que ya recibe visitas, pedidos y correo a diario.',
  59.00, 590.00,
  '1 sitio web
1 GB de almacenamiento rápido SSD
1 dominio conectado
6 correos empresariales
2 bases de datos
Seguridad SSL gratuita
Instalación de WordPress con 1 clic
Respaldo semanal
Protección con Cloudflare
Soporte prioritario
Mapa del sitio (Sitemap)
Optimización SEO inicial
Formularios de contacto
Página optimizada para celulares
Botón de WhatsApp integrado',
  1, 1
),
(
  (SELECT id FROM categorias WHERE slug='hosting-personal'),
  'Lissvery Max',
  'Para el sitio que ya crece: más correo, más subdominios y respaldo con restauración.',
  79.00, 790.00,
  '1 sitio web
3 GB de almacenamiento rápido SSD
1 dominio conectado
8 correos empresariales
6 subdominios
5 bases de datos
Seguridad SSL gratuita
Instalación de WordPress con 1 clic
Protección con Cloudflare
Restauración del sitio web — aplican condiciones
Soporte prioritario
Mapa del sitio (Sitemap)
Formularios de contacto
Página optimizada para celulares
Botón de WhatsApp integrado',
  0, 1
);

-- Dominios (ejemplo)
INSERT INTO productos (categoria_id, nombre, descripcion, precio, caracteristicas, destacado, activo) VALUES
(
  (SELECT id FROM categorias WHERE slug='dominios'),
  'Registro de dominio .com',
  'Registro de un dominio .com por un año, con DNS configurado por nuestro equipo.',
  120.00,
  'Registro por 1 año
Panel de administración del DNS
Configuración incluida
Renovación con recordatorio previo',
  1, 1
),
(
  (SELECT id FROM categorias WHERE slug='dominios'),
  'Registro de dominio .gt',
  'Registro de un dominio .gt por un año, ideal para negocios guatemaltecos.',
  180.00,
  'Registro por 1 año
Panel de administración del DNS
Configuración incluida',
  0, 1
);

-- Sitios web (ejemplo de servicio)
INSERT INTO productos (categoria_id, nombre, descripcion, precio, caracteristicas, destacado, activo) VALUES
(
  (SELECT id FROM categorias WHERE slug='sitios-web'),
  'Diseño de sitio web a medida',
  'Diseño y desarrollo de un sitio de hasta 5 páginas, adaptado a tu marca.',
  1500.00,
  'Hasta 5 páginas
Diseño responsive
Formulario de contacto
Optimización básica de SEO
1 ronda de ajustes incluida',
  1, 1
);

-- Licencias de software (ejemplo)
INSERT INTO productos (categoria_id, nombre, descripcion, precio, caracteristicas, destacado, activo) VALUES
(
  (SELECT id FROM categorias WHERE slug='licencias'),
  'Windows 11 Home',
  'Licencia original de Windows 11 Home para 1 equipo, activación digital.',
  350.00,
  'Licencia original de por vida
Activación digital
Para 1 equipo
Soporte para instalar',
  0, 1
),
(
  (SELECT id FROM categorias WHERE slug='licencias'),
  'Windows 11 Pro',
  'Licencia original de Windows 11 Pro para 1 equipo, activación digital.',
  480.00,
  'Licencia original de por vida
Activación digital
Para 1 equipo
Incluye funciones profesionales',
  1, 1
),
(
  (SELECT id FROM categorias WHERE slug='licencias'),
  'Microsoft Office Home & Business',
  'Word, Excel, PowerPoint y Outlook, licencia original para 1 equipo.',
  650.00,
  'Licencia original de por vida
Word, Excel, PowerPoint, Outlook
Para 1 equipo (PC o Mac)
Activación digital',
  0, 1
),
(
  (SELECT id FROM categorias WHERE slug='licencias'),
  'Antivirus Total Security — 1 PC / 1 año',
  'Protección antivirus completa para 1 equipo durante 1 año.',
  180.00,
  'Protección para 1 equipo
Vigencia de 1 año
Firewall y protección en tiempo real
Soporte para instalar',
  0, 1
);
