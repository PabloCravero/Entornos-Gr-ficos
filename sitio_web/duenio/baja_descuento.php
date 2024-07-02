<?php
include ("../includes/sesiones.php");
include ("../includes/conexion.inc");
include ("../includes/navbar.php");

// Verificar que el usuario sea dueño de local
if ($_SESSION['tipoUsuario'] !== 'Dueño de local') {
  header("Location: ../public/home.php"); // Redirigir si no es dueño de local
  exit();
}

$message = "";

// Eliminación de promoción si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminarPromo'])) {
  $codPromocion = $_POST["codPromo"];

  // Query para eliminar la promoción
  $queryEliminar = "DELETE FROM promociones WHERE codPromo = '$codPromocion'";
  if (mysqli_query($link, $queryEliminar)) {
    $message = "Promoción eliminada correctamente.";
  } else {
    $message = "Hubo un error al eliminar la promoción. Por favor, inténtalo de nuevo.";
  }
}

// Configuración de paginación
$registrosPorPagina = 10;
$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$offset = ($pagina - 1) * $registrosPorPagina;

// Obtener todas las promociones del local actual con paginación
$codUsuario = $_SESSION['codUsuario'];
$queryTotal = "SELECT COUNT(*) AS total FROM promociones WHERE codLocal IN (SELECT codLocal FROM locales WHERE codUsuario = '$codUsuario')";
$resultTotal = mysqli_query($link, $queryTotal);
$totalPromociones = mysqli_fetch_assoc($resultTotal)['total'];

$totalPaginas = ceil($totalPromociones / $registrosPorPagina);

$queryPromociones = "SELECT p.codPromo, p.textoPromo, p.fechaDesdePromo, p.fechaHastaPromo, l.codLocal, l.nombreLocal 
                     FROM promociones p 
                     INNER JOIN locales l ON p.codLocal = l.codLocal 
                     WHERE p.codLocal IN (SELECT codLocal FROM locales WHERE codUsuario = '$codUsuario') 
                     ORDER BY p.codPromo DESC 
                     LIMIT $offset, $registrosPorPagina";
$resultPromociones = mysqli_query($link, $queryPromociones);

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eliminación de Promociones - Total Store</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/admin_promociones.css">
</head>

<body>
  <div class="container">
    <h2 class="mt-5 text-center">Eliminación de Promociones</h2>
    <?php if ($message): ?>
      <div class="alert alert-success text-center mt-3">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>
    <div class="row mt-4">
      <div class="col-md-10 mx-auto">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Código</th>
              <th>Nombre del Local</th>
              <th>Texto</th>
              <th>Fecha Desde</th>
              <th>Fecha Hasta</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($resultPromociones)): ?>
              <tr>
                <td><?php echo $row['codPromo']; ?></td>
                <td><?php echo $row['nombreLocal']; ?></td>
                <td><?php echo $row['textoPromo']; ?></td>
                <td><?php echo $row['fechaDesdePromo']; ?></td>
                <td><?php echo $row['fechaHastaPromo']; ?></td>
                <td>
                  <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                    data-target="#eliminarPromoModal<?php echo $row['codPromo']; ?>">
                    Eliminar
                  </button>
                  <!-- Modal -->
                  <div class="modal fade" id="eliminarPromoModal<?php echo $row['codPromo']; ?>" tabindex="-1"
                    role="dialog" aria-labelledby="eliminarPromoModalLabel<?php echo $row['codPromo']; ?>"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="eliminarPromoModalLabel<?php echo $row['codPromo']; ?>">Confirmar
                            Eliminación</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          ¿Estás seguro de que deseas eliminar esta promoción?
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                            <input type="hidden" name="codPromo" value="<?php echo $row['codPromo']; ?>">
                            <button type="submit" name="eliminarPromo" class="btn btn-danger">Eliminar</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>

        <!-- Paginación -->
        <nav aria-label="Page navigation example">
          <ul class="pagination justify-content-center">
            <?php if ($pagina > 1): ?>
              <li class="page-item">
                <a class="page-link" href="?pagina=<?php echo $pagina - 1; ?>" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
                </a>
              </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
              <li class="page-item <?php echo ($pagina == $i) ? 'active' : ''; ?>">
                <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
              </li>
            <?php endfor; ?>

            <?php if ($pagina < $totalPaginas): ?>
              <li class="page-item">
                <a class="page-link" href="?pagina=<?php echo $pagina + 1; ?>" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
                </a>
              </li>
            <?php endif; ?>
          </ul>
        </nav>

        <a href="../public/home.php" class="btn btn-secondary btn-block mt-3">Volver al Home</a>
      </div>
    </div>
  </div>

</body>

</html>
<?php
include ("../includes/footer.html");
?>