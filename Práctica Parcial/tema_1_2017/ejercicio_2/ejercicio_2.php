<?php
if (!isset($_COOKIE['visitas'])) {
  $visitas = 1;
  setcookie("visitas", $visitas, time() + 3600 * 24 * 30);
} else {
  $visitas = $_COOKIE['visitas'] + 1;
  setcookie("visitas", $visitas, time() + 3600 * 24 * 30);
}
?>

<html>

<body>
  <?php
  if ($visitas >= 1) {
    echo "Esta es tu visita nro " . $_COOKIE['visitas'];
  } else {
    echo "Bienvenido. Es la primera vez que ingresa a la página";
    echo $visitas;
  }
  ?>
</body>

</html>