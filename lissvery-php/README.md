# Lissvery — sitio en PHP con tienda, cuentas y panel de administración

Este proyecto convierte el sitio de Lissvery en una aplicación PHP + MySQL
real: catálogo de productos, cuentas de usuario, carrito de compras y un
panel de administración desde el que puedes subir, editar y eliminar
productos (los cambios se reflejan al instante en la página web).

El diseño (colores, tipografías, componentes) es el mismo que ya tenías
aprobado; solo cambió el lenguaje detrás.

## Requisitos

- PHP 8.0 o superior, con la extensión `pdo_mysql` activada.
- MySQL o MariaDB.
- Un servidor local para probarlo (XAMPP, Laragon, WAMP o MAMP) o un
  hosting con PHP + MySQL.

## Instalación paso a paso

1. **Copia la carpeta completa** (`lissvery-php`, o como la renombres) dentro
   de la carpeta pública de tu servidor:
   - XAMPP: `C:\xampp\htdocs\lissvery\`
   - Laragon: `C:\laragon\www\lissvery\`
   - Un hosting: súbela por FTP a `public_html/` o la carpeta que use tu plan.

2. **Crea la base de datos.**
   - Abre phpMyAdmin (o tu gestor de MySQL).
   - Crea una base llamada `lissvery` (o importa el archivo `database.sql`,
     que ya la crea por ti con `CREATE DATABASE IF NOT EXISTS`).
   - Ve a la pestaña "Importar" y selecciona el archivo `database.sql` de
     este proyecto. Esto crea todas las tablas y deja cargados: las
     categorías, tu cuenta de administrador y los planes de Hosting
     personal (Lite, One, Max) con los datos que nos diste, además de
     algunos productos de ejemplo en Dominios, Sitios web y Licencias que
     puedes editar o borrar desde el panel.

3. **Configura la conexión a la base de datos.**
   - Abre `config/db.php`.
   - Cambia estos valores por los de tu servidor:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'lissvery');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     ```
   - En un hosting normalmente `DB_USER` y `DB_PASS` te los da el panel de
     control (cPanel, Plesk, etc.), y `DB_NAME` suele llevar un prefijo
     como `usuario_lissvery`.

4. **Dale permisos de escritura a la carpeta de imágenes.**
   - La carpeta `uploads/productos/` necesita permiso de escritura para
     que el panel pueda guardar las fotos de los productos.
   - En Linux/hosting: `chmod 755 uploads/productos` (o `775` si tu
     servidor lo requiere).
   - En XAMPP/Laragon en Windows normalmente no hace falta tocar nada.

5. **Abre el sitio** en tu navegador, por ejemplo `http://localhost/lissvery/`.

## Entrar al panel de administración

- URL: `tu-sitio.com/admin/login.php`
- Correo: `admin@lissvery.com`
- Contraseña: `Admin123!`

**Cambia esta contraseña cuanto antes.** Por ahora, para cambiarla:
genera un nuevo hash con
`password_hash('tu-nueva-contraseña', PASSWORD_DEFAULT)` (puedes correr un
script PHP de una línea para eso) y actualiza el campo `password_hash` de
ese usuario en la tabla `usuarios` desde phpMyAdmin. Si más adelante
quieres, puedo agregarte una pantalla de "cambiar contraseña" dentro del
panel para no tener que tocar la base de datos directamente.

## Qué puedes hacer desde el panel

- **Resumen**: cuántos productos, pedidos y clientes tienes.
- **Productos**: ver todos, subir uno nuevo, editar uno existente (incluida
  la imagen, que se reemplaza automáticamente) o eliminarlo. Eliminar un
  producto lo borra de la base de datos, borra su imagen del servidor y
  hace que desaparezca del catálogo, del detalle y de cualquier sección
  de la página donde aparecía — todo al instante, sin tocar código.
- **Pedidos**: ver los pedidos que los clientes confirmaron y cambiar su
  estado (pendiente / confirmado / cancelado).

Cada producto tiene un campo de **categoría** (Hosting personal, Hosting
empresarial, Dominios, Sitios web, Licencias). La página de **Hosting
personal** lee directamente los productos de esa categoría, así que si
agregas un cuarto plan ahí, aparece automáticamente con el mismo diseño
de tarjetas que ya tenías.

## Cómo funciona la tienda para un cliente

1. Puede navegar el **catálogo** (`catalogo.php`) sin necesidad de cuenta.
2. Al entrar al **detalle de un producto**, el sitio le pide iniciar sesión
   o crear una cuenta si todavía no lo ha hecho (así lo pediste).
3. Ya con sesión iniciada, puede agregar productos al **carrito**
   (`carrito.php`), ajustar cantidades y quitar productos.
4. Al **confirmar el pedido** (`checkout.php`), se guarda en la tabla
   `pedidos` con estado "pendiente" y el carrito se vacía. Tu equipo revisa
   el pedido desde el panel y contacta al cliente para coordinar el pago.

**Importante:** este checkout no procesa pagos en línea todavía — no está
conectado a ninguna pasarela de pago (tarjeta, transferencia, etc.). Solo
registra el pedido para que ustedes den seguimiento manual. Si más
adelante quieres cobrar en línea, se puede integrar una pasarela como
Stripe, PayPal o una local guatemalteca; es un proyecto aparte porque
implica cuentas comerciales y cumplimiento adicional.

## Estructura de archivos

```
lissvery-php/
├── config/db.php              Conexión a la base de datos (edítala primero)
├── includes/                  Sesión, funciones de productos/carrito, header y footer
├── assets/styles.css          Todo el diseño (el mismo que ya tenías)
├── assets/script.js           Menú, submenús, animaciones
├── uploads/productos/         Aquí se guardan las fotos que subas desde el panel
├── index.php                  Página de inicio
├── catalogo.php                Catálogo completo, con filtro por categoría
├── producto.php                Detalle de un producto (pide iniciar sesión)
├── carrito.php / checkout.php  Carrito y confirmación de pedido
├── login.php / registro.php / logout.php   Cuentas de cliente
├── hosting-personal.php        Planes Lite / One / Max (desde la base de datos)
├── hosting-empresarial.php     "En construcción" hasta que publiques planes
├── database.sql                Estructura de la base de datos + datos iniciales
└── admin/                      Panel de administración
    ├── login.php / logout.php
    ├── index.php                Resumen
    ├── productos.php            Listado, con botón de eliminar
    ├── producto-form.php        Crear / editar producto (con subida de imagen)
    └── pedidos.php               Listado de pedidos
```

## Seguridad ya incluida

- Contraseñas guardadas con `password_hash` (nunca en texto plano).
- Consultas a la base de datos con sentencias preparadas (PDO), para
  evitar inyección SQL.
- Token contra CSRF en todos los formularios que cambian datos.
- Solo se aceptan imágenes JPG, PNG o WEBP de hasta 3 MB en el panel.
- La carpeta `uploads/productos/` tiene un `.htaccess` que bloquea la
  ejecución de archivos PHP, por si alguien lograra subir uno.
- Las páginas del panel (`/admin/`) exigen sesión con rol de administrador.

## Cómo colocar tu logo

Igual que en la versión anterior: reemplaza el bloque `<span
class="logo-mark">...</span>` (aparece en `includes/header.php`,
`includes/footer.php` y en `admin/includes/admin-header.php`) por tu
imagen, por ejemplo:

```html
<img src="assets/logo.png" alt="Lissvery" width="30" height="30" style="border-radius:9px;">
```

Guarda tu archivo de logo dentro de `assets/` para que la ruta funcione
igual en todas las páginas.
