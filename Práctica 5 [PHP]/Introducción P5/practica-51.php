<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Práctica PHP</title>
</head>

<body>
  <?php
  $destinatario = "xx@xx.com ";
  $asunto = "Prueba";
  $cuerpo = "Esto es una prueba de envío de correo a través del servidor";
  mail($destinatario, $asunto, $cuerpo)
    ?>
</body>

</html>