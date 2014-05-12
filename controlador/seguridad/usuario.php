<?php

//define('BASEPATH', str_replace("\\", "/", $system_path));
define('BASEPATH', '');

require_once '../../modelo/seguridad/Usuario.php';
$user = new Usuario();
if (!isset($_POST['accion'])) {
    
} else {

    $accion = $_POST['accion'];

    if (isset($_POST['id_usuario'])) {
        $datos['id_usuario'] = $_POST['id_usuario'];
    }
    if (isset($_POST['usuario'])) {
        $datos['usuario'] = $_POST['usuario'];
    }
    if (isset($_POST['clave'])) {
        $datos['clave'] = $_POST['clave'];
    }
    if (isset($_POST['perfil'])) {
        $datos['codigo_perfil'] = $_POST['perfil'];
    }
    if (isset($_POST['u_estatus'])) {
        $datos['activo'] = $_POST['u_estatus'];
    }

    switch ($accion) {
        case 'Agregar':
            $resultado = $user->addUsuario($datos);
            echo json_encode($resultado);
        break;
        case 'Ingresar':
            $passw = $user->loginUsuario($usuario, $clave, $tipo, $status);
            if ($passw === TRUE) {
                echo 500;
            } else if ($passw == 4) {
                echo $passw;
            } else if ($passw == 2) {
                echo $passw;
            } else if ($passw == 1) {
                echo $passw;
            } else {
                echo 0;
            }
        break;
    }
}
?>
