<?php
session_start();
?>
<html>

<head>
  <title>Bienvenida a página</title>
</head>

<body>
  <?php
  if (isset($_SESSION['usuario'])) {
    echo "Bienvenido" . $_SESSION['usuario'];
  } else {
    echo 'Lo siento, no tiene permitido acceder al sitio';
  }
  session_destroy();
  ?>
</body>

</html>