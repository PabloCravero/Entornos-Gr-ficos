<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$tipo_usuario = isset($_SESSION['tipoUsuario']) ? $_SESSION['tipoUsuario'] : 'No registrado';
$_SESSION['tipoUsuario'] = $tipo_usuario;
