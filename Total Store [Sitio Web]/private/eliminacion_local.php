<?php
include ("../includes/sesiones.php");
include ("../includes/conexion.inc");
include ("../includes/navbar.php");

if ($_SESSION['tipoUsuario'] !== 'administrador') {
  header("Location: ../public/home.php"); // Redirigir si no es dueño de local
  exit();
}
$message = "";
$localData = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $idLocal = trim($_POST["codLocal"]);

  // Verificar si el local existe
  $qryBuscarLocal = "SELECT * FROM locales WHERE codLocal = '$idLocal'";
  $resultBuscarLocal = mysqli_query($link, $qryBuscarLocal);
  $vResultBuscarLocal = mysqli_fetch_assoc($resultBuscarLocal);

  if (!$vResultBuscarLocal) {
    $message = 'No existe un local con ese ID.';
  } else {
    $localData = $vResultBuscarLocal; // Guardar los datos del local encontrado
  }

  // Mostrar confirmación antes de eliminar
  if (isset($_POST['confirmar_eliminar'])) {
    // Eliminar las promociones asociadas con el local
    $qryEliminarUsoPromociones = "DELETE uso FROM uso_promociones uso INNER JOIN promociones promo ON uso.codPromo = promo.codPromo WHERE promo.codLocal = '$idLocal'";
    $resultEliminarUsoPromociones = mysqli_query($link, $qryEliminarUsoPromociones);

    $qryEliminarPromociones = "DELETE FROM promociones WHERE codLocal = '$idLocal'";
    $resultEliminarPromociones = mysqli_query($link, $qryEliminarPromociones);

    if ($resultEliminarPromociones && $resultEliminarUsoPromociones) {
      // Ahora eliminar el local
      $qryEliminarLocal = "DELETE FROM locales WHERE codLocal = '$idLocal'";
      $resultEliminarLocal = mysqli_query($link, $qryEliminarLocal);

      if ($resultEliminarLocal) {
        $message = "Local y todas las promociones asociadas eliminadas correctamente.";
        $localData = []; // Limpiar los datos del local después de la eliminación
      } else {
        $message = "Hubo un error al eliminar el local. Por favor, inténtalo de nuevo.";
      }
    } else {
      $message = "Hubo un error al eliminar las promociones y uso de promociones asociados al local. Por favor, inténtalo de nuevo.";
    }
  }
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eliminar Local - Total Store</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/admin_locales.css">
</head>

<body>
  <div class="container">
    <h2 class="mt-5 text-center">Eliminar Local</h2>
    <form action="eliminacion_local.php" method="POST">
      <div class="form-group">
        <label for="idLocal">Código del Local a eliminar:</label>
        <div class="input-group">
          <input type="text" class="form-control" id="idLocal" name="codLocal" required>
          <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="submit">Buscar</button>
          </div>
        </div>
      </div>
    </form>

    <?php if (!empty($localData)): ?>
      <div class="card mt-4">
        <div class="card-body">
          <h5 class="card-title">Información del Local</h5>
          <p><strong>Código:</strong> <?php echo $localData['codLocal']; ?></p>
          <p><strong>Nombre:</strong> <?php echo $localData['nombreLocal']; ?></p>
          <p><strong>Ubicación:</strong> <?php echo $localData['ubicacionLocal']; ?></p>
          <p><strong>Rubro:</strong> <?php echo $localData['rubroLocal']; ?></p>
          <!-- Agrega más campos según la estructura de tu tabla locales -->

          <h5 class="card-title mt-4">Confirmación de Eliminación</h5>
          <p>¿Está seguro que desea eliminar el local "<?php echo $localData['nombreLocal']; ?>" y todas las promociones
            asociadas?</p>
          <form action="eliminacion_local.php" method="POST">
            <input type="hidden" name="codLocal" value="<?php echo $localData['codLocal']; ?>">
            <button type="submit" name="confirmar_eliminar" class="btn btn-danger">Confirmar Eliminación</button>
            <button type="button" class="btn btn-secondary ml-2" onclick="cerrarInformacionLocal()">Cancelar</button>
          </form>
        </div>
      </div>
    <?php endif; ?>

    <?php if ($message): ?>
      <div class="alert alert-danger text-center mt-3">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>

    <!-- Botón para volver al home -->
    <div class="mt-3">
      <a href="../../public/home.php" class="btn btn-secondary btn-block mt-3">Volver al Home</a>
    </div>
  </div>

  <!--<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>-->

  <script>
    function cerrarInformacionLocal() {
      <?php $localData = []; ?>
      document.querySelector('.card').style.display = 'none';
    }
  </script>
</body>

</html>
<?php
include ("../includes/footer.html");?>