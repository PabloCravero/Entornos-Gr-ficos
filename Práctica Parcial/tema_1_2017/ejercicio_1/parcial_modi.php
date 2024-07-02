<html>

<head>
  <title>Alta Médico</title>
</head>

<body>

  <?php
  include ("conexion.inc");
  $dia = $_POST['consulta'];
  $nom = $_POST['nom_ape'];

  $mquery = "select * from medico_s where nom_ape = ' $nom ' ";
  $vResultado = mysqli_query($link, $mquery) or die(mysqli_error($link));
  $result = mysqli_fetch_array($vResultado);
  if ($result) {
    $modi_query = "update medico_s set consulta = '$dia' where nom_ape = '$nom'";
    mysqli_query($link, $modi_query) or die(mysqli_error($link));
    echo 'Médico actualizado';
    echo ("<a href='menu.html'>Volver al menú </a>");
    mysqli_free_result($vResultado);
  } else {
    echo 'Médico inexistente';
  }
  mysqli_close($link);
  ?>
</body>

</html>