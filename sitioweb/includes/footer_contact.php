<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include ("conexion.inc");
include ("sesiones.php");

// Incluyendo las clases de PHPMailer
require '/storage/ssd5/392/22391392/public_html/libs/PHPMailer-master/src/Exception.php';
require '/storage/ssd5/392/22391392/public_html/libs/PHPMailer-master/src/PHPMailer.php';
require '/storage/ssd5/392/22391392/public_html/libs/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message_footer = '';
$message_type_footer = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['mailUsuario'];
  $nom = $_POST['name'];
  $ape = $_POST['apellido'];
  $con = $_POST['consulta'];
  $redirect_to = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : '/';

  $mail = new PHPMailer(true);

  try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';  // Servidor SMTP de tu dominio
    $mail->SMTPAuth = true;
    $mail->Username = 'totalstoreshopping@gmail.com';  // Cuenta válida para autenticación
    $mail->Password = 'dbgm xrgf hgoh cdte';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Destinatarios
    $mail->setFrom($email, $nom . ' ' . $ape);  // Dirección del remitente (puede no existir)
    $mail->addAddress('totalstoreshopping@gmail.com', 'Total Store');

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Consulta Total Store';
    $mail->Body = "<p><strong>Nombre:</strong> $nom $ape</p>
                       <p><strong>Email:</strong> $email</p>
                       <p><strong>Consulta:</strong></p>
                       <p>$con</p>";
    $mail->AltBody = "Nombre: $nom $ape\nEmail: $email\nConsulta:\n$con";

    $mail->send();
    $_SESSION['message_footer'] = 'Correo enviado con éxito';
    $_SESSION['message_type_footer'] = 'success';
  } catch (Exception $e) {
    $_SESSION['message_footer'] = "Error al enviar el correo: {$mail->ErrorInfo}";
    $_SESSION['message_type_footer'] = 'error';
  }

  header('Location: ' . htmlspecialchars($redirect_to));
  exit;
}
?>