<?php

define('BASEPATH', '');
require_once $_SERVER['DOCUMENT_ROOT'] . 'Maternidad/FirePHP/fb.php';
ob_start();
$firephp = new FirePHP();

require_once '../../modelo/Privilegio.php';
$obj = new Privilegio();

if (!isset($_POST['accion'])) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
} else {

    $accion = addslashes($_POST['accion']);
    if (isset($_POST['privilegio'])) {
        $data['privilegio'] = addslashes($_POST["privilegio"]);
    } else {
        $data['privilegio'] = "";
    }

    if (isset($_POST['codigo_privilegio'])) {
        $data['codigo_privilegio'] = addslashes($_POST["codigo_privilegio"]);
    } else {
        $data['codigo_privilegio'] = "";
    }

    switch ($accion) {
        case 'Agregar':
            $resultado = $obj->add($data);
            echo json_encode($resultado);
        break;
        case 'Modificar':
            $resultado = $obj->edit($data);
            echo json_encode($resultado);
        break;

        case 'Eliminar':
            $resultado = $obj->del($data);
            echo json_encode($resultado);
        break;
    }
}
