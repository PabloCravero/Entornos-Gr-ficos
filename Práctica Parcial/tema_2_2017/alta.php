<html>

<head>
  <title>Alta Huésped</title>
</head>

<body>
  <?php
  include ("conexion.inc");

  $id = $_POST['dni'];
  $pro = $_POST['procedencia'];
  $fec_i = $_POST['ingreso'];
  $fec_f = $_POST['finalizacion'];
  $nro = $_POST['habitaciones'];
  $nom = $_POST['nombre'];

  $query = "select * from huesped where dni = '$id'";
  $vResultado = mysqli_query($link, $query) or die(mysqli_error($link));
  $result = mysqli_fetch_array($vResultado);

  if (!$result) {
    $alta = "insert into huesped values ('$id', '$pro', '$fec_i', '$fec_f', '$nro', '$nom')";
    mysqli_query($link, $alta) or die(mysqli_error($link));
    echo 'Huesped registrado';
    echo "<a href='menu.html'>Volver al menú</a>";
    mysqli_free_result($vResultado);
  } else {
    echo "El huesped ya está registrado";
  }
  mysqli_close($link);
  ?>
</body>

</html>