<?php
session_start();
$_SESSION['tipoUsuario'] = 'No registrado'; 
header('Location: home.php'); 
exit();
?>