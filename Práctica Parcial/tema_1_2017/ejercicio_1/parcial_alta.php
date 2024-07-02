<html>

<head>
  <title>Alta Médico</title>
</head>

<body>

  <?php
  include ("conexion.inc");
  $mat = $_POST['matricula'];
  $nom = $_POST['nom_ape'];
  $esp = $_POST['especialidad'];
  $con = $_POST['consulta'];

  $mquery = "select * from medico_s where matricula = ' $mat ' ";
  $result = mysqli_query($link, $mquery) or die(mysqli_error($link));
  $vMatriculaDoc = mysqli_fetch_array($result);
  if (!$vMatriculaDoc) {
    $alta_query = "insert into medico_s values ('$mat', '$nom', '$esp', '$con' )";
    mysqli_query($link, $alta_query) or die(mysqli_error($link));
    echo 'Médico registrado';
    echo ("<a href='menu.html'>Volver al menú </a>");
    mysqli_free_result($result);
  } else {
    echo 'Médico ya registrado';
  }
  mysqli_close($link);
  ?>
</body>

</html>