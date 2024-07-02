<?php
include ("../includes/sesiones.php");
include ("../includes/conexion.inc");
include ("../includes/navbar.php");

// Verificar que el usuario sea dueño de local
if ($_SESSION['tipoUsuario'] !== 'Dueño de local') {
  header("Location: ../public/home.php"); // Redirigir si no es dueño de local
  exit();
}

$message = null; // Inicializar message como null

// Aprobar o rechazar uso de promoción
// Aprobar o rechazar uso de promoción
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $codUsuario = $_POST["codCliente"];
  $codPromo = $_POST["codPromo"];
  $accion = $_POST["accion"];

  // Query para actualizar el estado del uso de la promoción
  $nuevoEstado = ($accion == 'aprobar') ? 'aceptada' : 'rechazada';
  $queryActualizar = "UPDATE uso_promociones SET estado = '$nuevoEstado' WHERE codCliente = '$codUsuario' AND codPromo = '$codPromo'";

  if (mysqli_query($link, $queryActualizar)) {
    if ($accion == 'aprobar') {
      // Sumar 1 al atributo cantUsadas en la tabla promociones
      $querySumar = "UPDATE promociones SET cantUsadas = cantUsadas + 1 WHERE codPromo = '$codPromo'";
      mysqli_query($link, $querySumar);

      // Obtener el contadorCategoria actual del cliente
      $queryContador = "SELECT contadorCategoria FROM usuarios WHERE codUsuario = '$codUsuario'";
      $resultContador = mysqli_query($link, $queryContador);
      $rowContador = mysqli_fetch_assoc($resultContador);
      $contadorActual = $rowContador['contadorCategoria'];

      // Incrementar el contador
      $contadorNuevo = $contadorActual + 1;

      // Actualizar el contadorCategoria del cliente
      $queryActualizarContador = "UPDATE usuarios SET contadorCategoria = '$contadorNuevo' WHERE codUsuario = '$codUsuario'";
      mysqli_query($link, $queryActualizarContador);

      // Verificar si el contador alcanza los umbrales para actualizar la categoría del cliente
      if ($contadorNuevo == 10) {
        $queryActualizarCategoria = "UPDATE usuarios SET categoriaCliente = 'Medium' WHERE codUsuario = '$codUsuario'";
        mysqli_query($link, $queryActualizarCategoria);
      } elseif ($contadorNuevo == 20) {
        $queryActualizarCategoria = "UPDATE usuarios SET categoriaCliente = 'Premium' WHERE codUsuario = '$codUsuario'";
        mysqli_query($link, $queryActualizarCategoria);
      }
    }
    $message = "La promoción ha sido " . (($accion == 'aprobar') ? 'aceptada' : 'rechazada') . " correctamente.";
  } else {
    $message = "Hubo un error al actualizar la promoción. Por favor, inténtalo de nuevo.";
  }
}

// Obtener todos los uso_promociones en estado "Enviada" para el local del dueño
$codUsuario = $_SESSION['codUsuario'];
$queryPromociones = "SELECT u.codCliente, u.codPromo, u.fechaUsoPromo, u.estado, p.textoPromo
                     FROM uso_promociones u
                     INNER JOIN promociones p ON u.codPromo = p.codPromo
                     WHERE u.estado = 'Enviada' AND p.codLocal IN (SELECT codLocal FROM locales WHERE codUsuario = '$codUsuario')";
$resultPromociones = mysqli_query($link, $queryPromociones);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Uso de Promociones - Total Store</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/admin_promociones.css">
  <style>
    .table-container {
      margin-top: 50px;
    }

    .table-responsive {
      max-height: 500px;
      overflow-y: auto;
    }

    .alert-success,
    .alert-danger {
      margin-top: 20px;
    }

    .btn-acciones {
      display: flex;
      justify-content: space-between;
    }

    .btn-acciones form {
      margin-right: 5px;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2 class="mt-5 text-center">Gestión de Uso de Promociones</h2>
    <?php if (!is_null($message)): ?>
      <div
        class="alert <?php echo ($message === "La promoción ha sido aceptada correctamente.") ? 'alert-success' : 'alert-danger'; ?> text-center mt-3">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>
    <div class="table-container">
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead class="thead-dark">
            <tr>
              <th>Código Cliente</th>
              <th>Código Promoción</th>
              <th>Texto Promoción</th>
              <th>Fecha de Uso</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($resultPromociones)): ?>
              <tr>
                <td><?php echo $row['codCliente']; ?></td>
                <td><?php echo $row['codPromo']; ?></td>
                <td><?php echo $row['textoPromo']; ?></td>
                <td><?php echo $row['fechaUsoPromo']; ?></td>
                <td class="btn-acciones">
                  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <input type="hidden" name="codCliente" value="<?php echo $row['codCliente']; ?>">
                    <input type="hidden" name="codPromo" value="<?php echo $row['codPromo']; ?>">
                    <button type="submit" name="accion" value="aprobar" class="btn btn-success btn-sm">Aprobar</button>
                  </form>
                  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <input type="hidden" name="codCliente" value="<?php echo $row['codCliente']; ?>">
                    <input type="hidden" name="codPromo" value="<?php echo $row['codPromo']; ?>">
                    <button type="submit" name="accion" value="rechazar" class="btn btn-danger btn-sm">Rechazar</button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
    <a href="../public/home.php" class="btn btn-secondary btn-block mt-3">Volver al Home</a>
  </div>

</body>

</html>
<?php
include ("../includes/footer.html");
mysqli_free_result($resultPromociones);
unset($message);
mysqli_close($link);
?>