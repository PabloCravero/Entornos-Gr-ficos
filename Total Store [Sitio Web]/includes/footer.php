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

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['nombreUsuario'];
    $nom = $_POST['name'];
    $ape = $_POST['apellido'];
    $con = $_POST['consulta'];
    $asunto = $nom . " " . $ape;

    $query = "SELECT * FROM usuarios WHERE nombreUsuario = '$name'";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    $vNombre = mysqli_fetch_array($result);

    if (!$vNombre) {
      echo 'Usuario inexistente';
    } else {
      $to = "totalstore@gmail.com";
      $headers = "From: " . $vNombre['nombreUsuario'];
      if (mail($to, $asunto, $con, $headers)) {
        echo "El correo ha sido enviado.";
        $_SESSION['usuario'] = $vNombre['nombre'];
      } else {
        echo "No se pudo enviar el correo.";
      }
    }
  }
  ?>

</body>

</html>