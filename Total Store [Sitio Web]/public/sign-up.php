<?php
session_start();
include ('../includes/conexion.inc'); // Archivo donde están tus credenciales de conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST["nombreUsuario"]);
  $clave = trim($_POST["claveUsuario"]);
  $clave_confirmada = trim($_POST["confirm-password"]);
  $tipoUsuario = trim($_POST["aplica-dueño"]);
  if ($tipoUsuario) {
    $tipoUsuario = "Dueño de local";
  } else {
    $tipoUsuario = "Cliente";
  }
  if ($clave != $clave_confirmada) {
    $_SESSION['message'] = "Las contraseñas no coinciden.";
  } else {
    $password_hash = password_hash($clave, PASSWORD_DEFAULT);

    // Verificar si el correo ya está registrado
    $qry = "SELECT * FROM usuarios WHERE nombreUsuario = '$email'";
    $result = mysqli_query($link, $qry) or die(mysqli_error($link));
    $vResult = mysqli_fetch_array($result);

    if ($vResult) {
      $_SESSION['message'] = "El correo ya está registrado.";
    } else {
      $insert_qry = "INSERT INTO usuarios (nombreUsuario, claveUsuario, tipoUsuario, categoriaCliente) VALUES ('$email', '$password_hash', '$tipoUsuario', 'Inicial')";
      if (mysqli_query($link, $insert_qry)) {
        $_SESSION['message'] = "Registro exitoso. Puedes iniciar sesión ahora.";
        header("Location: login.php");
        exit();
      } else {
        $_SESSION['message'] = "Hubo un error al registrarse. Por favor, inténtalo de nuevo.";
      }
    }
    mysqli_close($link);
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro - Total Store</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/sign-up.css">
</head>

<body>
  <div class="container">
    <h2 class="mt-5 text-center">Registrarse</h2>
    <form action="sign-up.php" method="post">
      <div class="form-group">
        <label for="nombre">Email:</label>
        <input type="text" class="form-control" id="nombre" name="nombreUsuario" required>
      </div>
      <div class="form-group">
        <label for="password">Contraseña:</label>
        <input type="password" class="form-control" id="clave" name="claveUsuario" required>
      </div>
      <div class="form-group">
        <label for="confirm-password">Confirmar Contraseña:</label>
        <input type="password" class="form-control" id="confirm-password" name="confirm-password" required>
      </div>
      <div class="form-group checkbox-container">
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="aplica-dueño" name="aplica-dueño">
          <label class="form-check-label" for="aplica-dueño">Aplicar para dueño</label>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
    </form>
    <?php if (isset($_SESSION['message'])): ?>
      <div class="alert alert-danger text-center">
        <?php
        echo $_SESSION['message'];
        unset($_SESSION['message']);
        ?>
      </div>
    <?php endif; ?>
  </div>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>
<?php
include ("../includes/footer.html");?>