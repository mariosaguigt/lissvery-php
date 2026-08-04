<?php
require __DIR__ . '/../includes/session.php';
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/functions.php';
require_admin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$producto = $id ? get_producto($pdo, $id) : null;
$categorias = get_categorias($pdo);
$errores = [];

/* Valores para repoblar el formulario si hay errores */
$valores = [
    'nombre'          => $producto['nombre'] ?? '',
    'categoria_id'    => $producto['categoria_id'] ?? '',
    'descripcion'     => $producto['descripcion'] ?? '',
    'precio'          => $producto['precio'] ?? '0',
    'precio_anual'    => $producto['precio_anual'] ?? '',
    'caracteristicas' => $producto['caracteristicas'] ?? '',
    'destacado'       => $producto['destacado'] ?? 0,
    'activo'          => $producto['activo'] ?? 1,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? '')) {
        $errores[] = 'Tu sesión expiró, recarga la página e intenta de nuevo.';
    } else {
        $valores['nombre']          = trim($_POST['nombre'] ?? '');
        $valores['categoria_id']    = (int) ($_POST['categoria_id'] ?? 0);
        $valores['descripcion']     = trim($_POST['descripcion'] ?? '');
        $valores['precio']          = (float) ($_POST['precio'] ?? 0);
        $valores['precio_anual']    = $_POST['precio_anual'] !== '' ? (float) $_POST['precio_anual'] : null;
        $valores['caracteristicas'] = trim($_POST['caracteristicas'] ?? '');
        $valores['destacado']       = isset($_POST['destacado']) ? 1 : 0;
        $valores['activo']          = isset($_POST['activo']) ? 1 : 0;

        if ($valores['nombre'] === '') $errores[] = 'El nombre es obligatorio.';
        if ($valores['categoria_id'] <= 0) $errores[] = 'Elige una categoría.';
        if ($valores['precio'] < 0) $errores[] = 'El precio no puede ser negativo.';

        $imagenFinal = $producto['imagen'] ?? null;

        if (!empty($_FILES['imagen']['name'])) {
            if ($_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
                $errores[] = 'Hubo un problema al subir la imagen, intenta de nuevo.';
            } else {
                $permitidos = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
                $tipo = mime_content_type($_FILES['imagen']['tmp_name']);

                if (!isset($permitidos[$tipo])) {
                    $errores[] = 'La imagen debe ser JPG, PNG o WEBP.';
                } elseif ($_FILES['imagen']['size'] > 3 * 1024 * 1024) {
                    $errores[] = 'La imagen no puede pesar más de 3 MB.';
                } else {
                    $ext = $permitidos[$tipo];
                    $nuevoNombre = uniqid('prod_', true) . '.' . $ext;
                    $carpeta = __DIR__ . '/../uploads/productos/';
                    if (!is_dir($carpeta)) {
                        mkdir($carpeta, 0755, true);
                    }
                    $destino = $carpeta . $nuevoNombre;

                    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
                        if (!empty($producto['imagen'])) {
                            $anterior = $carpeta . $producto['imagen'];
                            if (is_file($anterior)) {
                                unlink($anterior);
                            }
                        }
                        $imagenFinal = $nuevoNombre;
                    } else {
                        $errores[] = 'No se pudo guardar la imagen, intenta de nuevo.';
                    }
                }
            }
        }

        if (empty($errores)) {
            if ($producto) {
                $stmt = $pdo->prepare(
                    "UPDATE productos SET categoria_id=?, nombre=?, descripcion=?, precio=?, precio_anual=?, imagen=?, caracteristicas=?, destacado=?, activo=?
                     WHERE id = ?"
                );
                $stmt->execute([
                    $valores['categoria_id'], $valores['nombre'], $valores['descripcion'],
                    $valores['precio'], $valores['precio_anual'], $imagenFinal,
                    $valores['caracteristicas'], $valores['destacado'], $valores['activo'],
                    $producto['id'],
                ]);
                flash('success', 'Producto actualizado. Los cambios ya están visibles en la página web.');
            } else {
                $stmt = $pdo->prepare(
                    "INSERT INTO productos (categoria_id, nombre, descripcion, precio, precio_anual, imagen, caracteristicas, destacado, activo)
                     VALUES (?,?,?,?,?,?,?,?,?)"
                );
                $stmt->execute([
                    $valores['categoria_id'], $valores['nombre'], $valores['descripcion'],
                    $valores['precio'], $valores['precio_anual'], $imagenFinal,
                    $valores['caracteristicas'], $valores['destacado'], $valores['activo'],
                ]);
                flash('success', 'Producto publicado. Ya aparece en la página web.');
            }
            header('Location: productos.php');
            exit;
        }
    }
}

$pageTitle = ($producto ? 'Editar' : 'Nuevo') . ' producto — Panel Lissvery';
require __DIR__ . '/includes/admin-header.php';
?>

<h1><?= $producto ? 'Editar producto' : 'Nuevo producto' ?></h1>
<p>Los cambios que guardes aquí se reflejan de inmediato en la página web: al publicar aparece en el catálogo, al editar se actualiza y al eliminar desaparece.</p>

<?php foreach ($errores as $e): ?><p class="form-note error"><?= htmlspecialchars($e) ?></p><?php endforeach; ?>

<form method="post" enctype="multipart/form-data" class="admin-form">
  <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

  <label for="nombre">Nombre</label>
  <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($valores['nombre']) ?>" required>

  <label for="categoria_id">Categoría</label>
  <select id="categoria_id" name="categoria_id" required>
    <option value="">Selecciona una categoría</option>
    <?php foreach ($categorias as $c): ?>
      <option value="<?= $c['id'] ?>" <?= (string) $valores['categoria_id'] === (string) $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nombre']) ?></option>
    <?php endforeach; ?>
  </select>

  <label for="descripcion">Descripción</label>
  <textarea id="descripcion" name="descripcion" rows="3"><?= htmlspecialchars($valores['descripcion']) ?></textarea>

  <div class="admin-form-row">
    <div>
      <label for="precio">Precio mensual (Q)</label>
      <input type="number" step="0.01" min="0" id="precio" name="precio" value="<?= htmlspecialchars((string) $valores['precio']) ?>" required>
    </div>
    <div>
      <label for="precio_anual">Precio anual (Q) — opcional</label>
      <input type="number" step="0.01" min="0" id="precio_anual" name="precio_anual" value="<?= htmlspecialchars((string) $valores['precio_anual']) ?>">
    </div>
  </div>

  <label for="caracteristicas">Características (una por línea, aparecen con ✓ en la página)</label>
  <textarea id="caracteristicas" name="caracteristicas" rows="7" placeholder="1 sitio web&#10;1 GB de almacenamiento SSD&#10;Seguridad SSL gratuita"><?= htmlspecialchars($valores['caracteristicas']) ?></textarea>

  <label for="imagen">Imagen del producto</label>
  <?php if (!empty($producto['imagen'])): ?>
    <img src="../uploads/productos/<?= htmlspecialchars($producto['imagen']) ?>" class="admin-thumb-lg" alt="">
  <?php endif; ?>
  <input type="file" id="imagen" name="imagen" accept=".jpg,.jpeg,.png,.webp">

  <label class="admin-checkbox"><input type="checkbox" name="destacado" <?= $valores['destacado'] ? 'checked' : '' ?>> Marcar como destacado ("Más elegido")</label>
  <label class="admin-checkbox"><input type="checkbox" name="activo" <?= $valores['activo'] ? 'checked' : '' ?>> Publicado (visible en la página web)</label>

  <button type="submit" class="btn btn-solid btn-lg"><?= $producto ? 'Guardar cambios' : 'Publicar producto' ?></button>
</form>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
