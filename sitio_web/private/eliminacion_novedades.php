<?php
include ("../includes/sesiones.php");
include ("../includes/conexion.inc");
include ("../includes/navbar.php");

if ($_SESSION['tipoUsuario'] !== 'administrador') {
  header("Location: ../public/home.php"); // Redirigir si no es dueño de local
  exit();
}
// Variable para almacenar el mensaje de confirmación
$message = '';
$novedades = []; // Inicializar como un array vacío

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["fechaDesdeNovedad"]) && isset($_POST["fechaHastaNovedad"]) && isset($_POST["categoriaCliente"])) {
    $fechaD = trim($_POST["fechaDesdeNovedad"]);
    $fechaH = trim($_POST["fechaHastaNovedad"]);
    $tipoUsuario = trim($_POST["categoriaCliente"]);

    // Consulta para buscar las novedades a eliminar
    $buscarNovedades = "SELECT * FROM novedades WHERE fechaDesdeNovedad >= '$fechaD' AND fechaHastaNovedad <= '$fechaH' AND categoriaCliente = '$tipoUsuario'";
    $result = mysqli_query($link, $buscarNovedades);

    // Mostrar los resultados y permitir la selección para eliminar
    if (mysqli_num_rows($result) > 0) {
      $novedades = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
      $message = "No se encontraron novedades con los criterios especificados.";
    }

    mysqli_close($link);
  }
}

// Proceso de eliminación si se ha seleccionado una novedad para eliminar
if (isset($_POST['eliminar_novedad'])) {
  $idNovedad = $_POST['eliminar_novedad'];

  // Consulta SQL para eliminar la novedad seleccionada
  $eliminarNovedad = "DELETE FROM novedades WHERE codNovedad = '$idNovedad'";
  $result = mysqli_query($link, $eliminarNovedad);

  if ($result) {
    $message = "Novedad eliminada correctamente.";
  } else {
    $message = "Hubo un error al intentar eliminar la novedad. Por favor, inténtalo de nuevo.";
  }

  mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eliminar novedad - Total Store</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/admin_locales.css">
</head>

<body>
  <div class="container">
    <h2 class="mt-5 text-center">Eliminar una novedad</h2>
    <form action="eliminacion_novedades.php" method="POST">
      <div class="form-group">
        <label for="fechaDesdeNovedad">Fecha Desde Novedad</label>
        <input type="date" class="form-control" id="fechaDesdeNovedad" name="fechaDesdeNovedad" required>
      </div>
      <div class="form-group">
        <label for="fechaHastaNovedad">Fecha Hasta Novedad</label>
        <input type="date" class="form-control" id="fechaHastaNovedad" name="fechaHastaNovedad" required>
      </div>
      <div class="form-group">
        <label for="categoriaCliente">Tipo de Usuario:</label>
        <select class="form-select form-control" id="categoriaCliente" name="categoriaCliente" required>
          <option value="" disabled selected>Seleccione la categoría del cliente</option>
          <option value="Inicial">Inicial</option>
          <option value="Medium">Medium</option>
          <option value="Premium">Premium</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Buscar Novedades</button>
      <a href="../../public/home.php" class="btn btn-secondary btn-block mt-3">Volver al Home</a>
    </form>

    <?php if (!empty($message)): ?>
      <div class="alert alert-danger text-center mt-3">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($novedades)): ?>
      <h2 class="mt-5 text-center">Resultados encontrados:</h2>
      <form action="eliminacion_novedades.php" method="POST">
        <ul class="list-group mt-3">
          <?php foreach ($novedades as $novedad): ?>
            <li class="list-group-item">
              <input type="radio" name="eliminar_novedad" value="<?php echo $novedad['codNovedad']; ?>">
              Descripción: <?php echo $novedad['textoNovedad']; ?> | Desde: <?php echo $novedad['fechaDesdeNovedad']; ?> |
              Hasta: <?php echo $novedad['fechaHastaNovedad']; ?> | Cliente: <?php echo $novedad['categoriaCliente']; ?>
            </li>
          <?php endforeach; ?>
        </ul>
        <button type="submit" class="btn btn-danger btn-block mt-3">Eliminar Novedad Seleccionada</button>
      </form>
    <?php endif; ?>
  </div>

  <!--<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>-->
</body>

</html>
<?php
include ("../includes/footer.html");?>