<?php

session_start();
define('BASEPATH', '');
require_once '../../modelo/seguridad/Login.php';
$user = new Login();
if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $result     = $user->logoutUsuario($id_usuario);
}
if ($result === true) {
    header('location:../../');
}else{
     header('location:../../');
}

