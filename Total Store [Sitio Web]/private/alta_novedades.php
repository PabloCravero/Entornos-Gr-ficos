<?php
include ("../includes/sesiones.php");
include ("../includes/conexion.inc");
include ("../includes/navbar.php");

if ($_SESSION['tipoUsuario'] !== 'administrador') {
  header("Location: ../public/home.php"); // Redirigir si no es dueño de local
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $descripcion = trim($_POST["textoNovedad"]);
  $fechaD = trim($_POST["fechaDesdeNovedad"]);
  $fechaH = trim($_POST["fechaHastaNovedad"]);
  $tipoUsuario = trim($_POST["categoriaCliente"]);

  $today = date("Y-m-d");

  if ($fechaD <= $today) {
    $message = "La fecha desde debe ser mayor a hoy.";
  } elseif ($fechaH <= $fechaD) {
    $message = "La fecha hasta debe ser mayor a la fecha desde.";
  } else {
    // Subir la imagen
    $buscarNovedad = "select * from novedades where textoNovedad = '$descripcion' and fechaDesdeNovedad = '$fechaD' and fechaHastaNovedad = '$fechaH' and categoriaCliente = '$tipoUsuario'";
    $result = mysqli_query($link, $buscarNovedad);
    $vResult = mysqli_fetch_array($result);
    if ($vResult) {
      $message = 'Ya existe esa novedad';
    } else {
      // Construir la consulta SQL para insertar en la base de datos
      $altaNovedad = "insert into novedades (textoNovedad, fechaDesdeNovedad, fechaHastaNovedad, categoriaCliente) values('$descripcion', '$fechaD', '$fechaH', '$tipoUsuario')";
      $result2 = mysqli_query($link, $altaNovedad);
      if ($result2) {
        $message = "Novedad generada exitosamente.";
      } else {
        $message = "Hubo un error al generar la novedad. Por favor, inténtalo de nuevo.";
      }
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
  <title>Generar novedad - Total Store</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/admin_locales.css">
</head>

<body>
  <div class="container">
    <h2 class="mt-5 text-center">Generar una novedad</h2>
    <form action="alta_novedades.php" method="POST">
      <div class="form-group">
        <label for="nombre">Descripción de la Novedad:</label>
        <textarea class="form-control" name="textoNovedad" id="textoNovedad" cols="30" rows="10"></textarea>
      </div>
      <div class="form-group">
        <label for="fechaDesdeNovedad">Fecha Desde Novedad</label>
        <input type="date" class="form-control" id="fechaDesdeNovedad" name="fechaDesdeNovedad" required>
      </div>
      <div class="form-group">
        <label for="fechaHastaNovedad">Fecha Hasta Novedad</label>
        <input type="date" class="form-control" id="fechaHastaNovedad" name="fechaHastaNovedad" required>
      </div>
      <div class="form-group">
        <label for="categoriaCliente">Categoría del cliente:</label>
        <select class="form-select form-control" id="categoriaCliente" name="categoriaCliente" required>
          <option value="" disabled selected>Seleccione la categoría del cliente</option>
          <option value="Inicial">Inicial</option>
          <option value="Medium">Medium</option>
          <option value="Premium">Premium</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Generar Novedad</button>
      <a href="../../public/home.php" class="btn btn-secondary btn-block mt-3">Volver al Home</a>
    </form>
    <?php if (isset($message)): ?>
      <div class="alert <?php echo ($message === "Novedad generada exitosamente.") ? 'alert-success' : 'alert-danger'; ?> text-center mt-3">
        <?php
        echo $message;
        ?>
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