<?php
session_start();
?>
<html>

<head>
  <title>Verificar sesión</title>
</head>

<body>
  <?php
  include ("conexion.inc");
  $id = $_POST['dni'];
  $query = "select * from huesped where dni = '$id'";
  $result = mysqli_query($link, $query) or die(mysqli_error($link));
  $vNombre = mysqli_fetch_array($result);
  if (!$vNombre) {
    echo 'Usuario inexistente';
  } else {
    $_SESSION['usuario'] = $vNombre['nombre'];
  }
  ?>
  <a href="pagina_2.php">Ir a la página principal</a>
</body>

</html>