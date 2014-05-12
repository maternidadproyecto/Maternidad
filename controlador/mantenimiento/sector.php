<?php

define('BASEPATH', '');
require_once '../../FirePHP/fb.php';
$firephp = new FirePHP();
if (!isset($_POST['accion'])) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
} else {
    require_once '../../modelo/mantenimientos/Sector.php';
$obj = new Sector();
    $accion = addslashes($_POST['accion']);
    if (isset($_POST['cod_sector'])) {
        $datos['cod_sector'] = addslashes($_POST["cod_sector"]);
    }
    if (isset($_POST['sector'])) {
        $datos['sector'] = addslashes($_POST["sector"]);
    }
    if (isset($_POST['municipio'])) {
        $datos['cod_municipio'] = addslashes($_POST['municipio']);
    }
    
    switch ($accion) {
        case 'Agregar':
            $resultado = $obj->addSector($datos);
            echo json_encode($resultado);
        break;
        case 'Modificar':
            $resultado = $obj->editSector($datos);
            echo json_encode($resultado);
        break;
        case 'Eliminar':
            $resultado = $obj->deleteSector($datos);
            echo json_encode($resultado);
        break;
        case 'Sector':
            $resultado = $obj->getSector($codigo_municipio);
            for ($j = 0; $j < count($resultado); $j++) {
                $datos[] = array('cod_sector' => $resultado[$j]['cod_sector'], 'sector' => $resultado[$j]['sector']);
            }
            echo json_encode($datos);
        break;
    }
}

