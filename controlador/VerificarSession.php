<?php

session_start();
require '../librerias/php/validarSessionUsuario.php';
define('BASEPATH', 'JOSUE');

require_once '../modelo/Autenticar.php';
$user = new Autenticar();
$user->_verificarSesion(1);
?>
