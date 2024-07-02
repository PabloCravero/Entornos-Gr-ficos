<?php
include ("../includes/sesiones.php");
include ("../includes/conexion.inc");
include ("../includes/navbar.php");

if ($_SESSION['tipoUsuario'] !== 'administrador') {
  header("Location: ../public/home.php"); // Redirigir si no es dueño de local
  exit();
}
$message = "";

function subirImagen($file)
{
  $target_dir = '/sitio_web/uploads/';
  $target_file = $target_dir . basename($file["name"]);
  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

  // Verificar si el archivo es una imagen real
  $check = getimagesize($file["tmp_name"]);
  if ($check === false) {
    return ["error" => "El archivo no es una imagen válida."];
  }

  // Verificar el tamaño del archivo (máximo 5MB)
  if ($file["size"] > 5000000) {
    return ["error" => "El archivo es demasiado grande. Máximo 5MB."];
  }

  // Permitir ciertos formatos de archivo
  $allowed_formats = ["jpg", "jpeg", "png", "gif"];
  if (!in_array($imageFileType, $allowed_formats)) {
    return ["error" => "Sólo se permiten archivos JPG, JPEG, PNG y GIF."];
  }

  // Intentar subir el archivo
  if (move_uploaded_file($file["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $target_file)) {
    return ["success" => $target_file];
  } else {
    return ["error" => "Hubo un error al subir tu archivo."];
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $idLocal = trim($_POST["codLocal"]);
  $nombreLocal = trim($_POST["nombreLocal"]);
  $ubicacionLocal = trim($_POST["ubicacionLocal"]);
  $rubro = trim($_POST["rubroLocal"]);

  // Verificar si el local existe
  $qryBuscarLocal = "SELECT * FROM locales WHERE codLocal = '$idLocal'";
  $resultBuscarLocal = mysqli_query($link, $qryBuscarLocal);
  $vResultBuscarLocal = mysqli_fetch_array($resultBuscarLocal);

  if (!$vResultBuscarLocal) {
    $message = 'No existe un local con ese ID.';
  } else {
    // Subir nueva imagen si se proporciona
    if ($_FILES["imagenLocal"]["name"]) {
      $imagenResult = subirImagen($_FILES["imagenLocal"]);
      if (isset($imagenResult["error"])) {
        $message = $imagenResult["error"];
      } else {
        $imagenLocal = $imagenResult["success"];
      }
    } else {
      // Mantener la imagen actual si no se sube una nueva
      $imagenLocal = $vResultBuscarLocal["imagenLocal"];
    }

    // Actualizar los datos del local
    $qryModificarLocal = "UPDATE locales SET nombreLocal = '$nombreLocal', ubicacionLocal = '$ubicacionLocal', rubroLocal = '$rubro', imagenLocal = '$imagenLocal' WHERE codLocal = '$idLocal'";
    $resultModificarLocal = mysqli_query($link, $qryModificarLocal);

    if ($resultModificarLocal) {
      $message = "Local modificado exitosamente.";
    } else {
      $message = "Hubo un error al modificar el local. Por favor, inténtalo de nuevo.";
    }
  }

  mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modificar Local - Total Store</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/admin_locales.css">
</head>

<body>
  <div class="container">
    <h2 class="mt-5 text-center">Modificar Local</h2>
    <form action="modificacion_local.php" method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="idLocal">Código del Local a modificar:</label>
        <input type="text" class="form-control" id="idLocal" name="codLocal" required>
      </div>
      <div class="form-group">
        <label for="nombre">Nombre del Local:</label>
        <input type="text" class="form-control" id="nombre" name="nombreLocal" required>
      </div>
      <div class="form-group">
        <label for="ubicacion">Ubicación del Local:</label>
        <textarea class="form-control" id="ubicacion" name="ubicacionLocal" required></textarea>
      </div>
      <div class="form-group">
        <label for="rubro">Rubro del Local:</label>
        <select class="form-select form-control" id="rubro" name="rubroLocal" required>
          <option value="" disabled selected>Selecciona un rubro</option>
          <option value="Indumentaria">Indumentaria</option>
          <option value="Perfumeria">Perfumería</option>
          <option value="Optica">Óptica</option>
          <option value="Comida">Comida</option>
          <option value="Computacion">Computación</option>
        </select>
      </div>
      <div class="form-group">
        <label for="imagen">Imagen del Local:</label>
        <div class="custom-file">
          <input type="file" class="custom-file-input" id="imagen" name="imagenLocal">
          <label class="custom-file-label" for="imagen">Elige una imagen...</label>
        </div>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Modificar Local</button>
      <a href="../../public/home.php" class="btn btn-secondary btn-block mt-3">Volver al Home</a>
    </form>
    <?php if ($message): ?>
      <div class="alert alert-danger text-center mt-3">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>
  </div>

  <!--<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>-->
</body>

</html>
<?php
include ("../includes/footer.html");?>