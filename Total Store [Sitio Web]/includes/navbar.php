<?php
include ("../includes/conexion.inc");
include ("../includes/sesiones.php");
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Total Store</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css"
    rel="stylesheet">
  <link rel="stylesheet" href="../css/navbar.css">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="../public/home.php">Total Store</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="../public/home.php">Home</a>
        </li>
        <?php if ($_SESSION['tipoUsuario'] == 'administrador'): ?>
          <li class="nav-item">
            <a class="nav-link" href="../private/seccion_administrador.php">Panel de Administrador</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../private/gestionar_descuentos.php">Gestión de descuentos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../private/validar_dueño.php">Validación de dueños</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../private/reportes.php">Reportes</a>
          </li>
        <?php elseif ($_SESSION['tipoUsuario'] == 'Dueño de local'): ?>
          <li class="nav-item">
            <a class="nav-link" href="../duenio/gestion_promocion.php">Solicitar Promoción</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../private/reportes.php">Reportes</a>
          </li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link" href="../public/locales.php">Locales</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../public/promociones.php">Promociones</a>
        </li>
        <?php if (basename($_SERVER['PHP_SELF']) == 'promociones.php' || basename($_SERVER['PHP_SELF']) == 'locales.php'): ?>
          <li class="nav-item">
            <form class="form-inline my-2 my-lg-0" method="get"
              action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
              <input class="form-control mr-sm-2" type="search" name="search" placeholder="Buscar" aria-label="Buscar">
              <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
            </form>
          </li>
        <?php endif; ?>
        <?php if ($_SESSION['tipoUsuario'] == 'No registrado'): ?>
          <li class="nav-item"><a href="login.php" class="nav-link">Log in</a></li>
        <?php elseif (isset($_SESSION['tipoUsuario'])): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              <i class="bi bi-person-circle"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
              <div class="dropdown-header">
                <strong><?php echo htmlspecialchars($_SESSION['nombreUsuario']); ?></strong>
              </div>
              <div class="dropdown-item">
                Tipo de Usuario: <?php echo htmlspecialchars($_SESSION['tipoUsuario']); ?>
              </div>
              <?php if ($_SESSION['categoriaCliente']): ?>
                <div class="dropdown-item">
                  Categoría: <?php echo htmlspecialchars($_SESSION['categoriaCliente']); ?>
                </div>
              <?php endif; ?>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="../public/logout.php">Cerrar Sesión</a>
            </div>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </nav>

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>