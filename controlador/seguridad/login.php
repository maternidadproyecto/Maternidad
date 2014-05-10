<?php

define('BASEPATH', '');
require_once '../../modelo/seguridad/Login.php';
$user = new Login();

if (!isset($_POST['accion'])) {

} else {

    $accion = $_POST['accion'];

    if (isset($_POST['usuario'])) {
        $datos['usuario'] = addslashes($_POST['usuario']);
    }
    if (isset($_POST['clave'])) {
        $datos['clave'] = addslashes($_POST['clave']);
    }
    if (isset($_POST['perfil'])) {
        $perfil = $_POST['perfil'];
    }
    if (isset($_POST['estatus'])) {
        $estatus = $_POST['estatus'];
    }
    switch ($accion) {
        case 'Agregar':
            $resultado = $user->addUsuario($usuario, $clave, $perfil, $estatus);
            echo json_encode($resultado);
            break;
        case 'Ingresar':
            $resultado = $user->loginUser($datos);
            if ($resultado === TRUE) {
                echo 21;
            } else if ($resultado == 14) {
                echo $resultado;
            } else if ($resultado == 12) {
                echo $resultado;
            } else if ($passw == 11) {
                echo $resultado;
            } else {
                echo 13;
            }
     break;
    }
}