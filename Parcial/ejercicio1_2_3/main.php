<?php
session_start();
?>
<html>

<head>
  <title>Formulario de alta</title>
</head>

<body>
  <?php
  include ("conexion.inc");

  $nom = $_POST['nombre'];
  $mail = $_POST['email'];
  $prod = $_POST['producto'];
  $month = $_POST['mes'];
  $cant = $_POST['cantidad'];
  $accept = $_POST['terminos'];
  $fec_hor = getdate();


  $query = "select * from producto where email = '$mail' and mes = '$month' and '$cant'";
  $vResultado = mysqli_query($link, $query) or die(mysqli_error($link));
  $result = mysqli_fetch_array($vResultado);

  if (!$nom or !$result['email'] or !$result['producto'] or !$result['mes'] or !$result['cantidad'] or !$result['terminos']) {
    echo "<a href = main.html> Debe completar los campos del formulario</a>";
  } else {
    if (!isset($_SESSION)) {
      $_SESSION['nombre'] = $nom;
      $_SESSION['email'] = $mail;
      $_SESSION['producto'] = $prod;
      $_SESSION['mes'] = $month;
      $_SESSION['cantidad'] = $cant;
      $_SESSION['terminos'] = $accept;
      $_SESSION['fecha'] = $fec_hor;
    }
    if (!$result) {
      $alta = "insert into producto values ('$nom', '$mail', '$prod', '$month', '$cant', '$fec_hor')";
      mysqli_query($link, $alta) or die(mysqli_error($link));
      echo 'Huesped registrado';
      echo "<a href='menu.html'>Volver al menú</a>";
      setcookie("registro", $fec_hor, time() + 3600 * 24 * 365);
      mysqli_free_result($vResultado);
    } else {
      echo "No es posible cargar su registro porque ya existe";
    }
    mysqli_close($link);
  }
  ?>
</body>

</html>