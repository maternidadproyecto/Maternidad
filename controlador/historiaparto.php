<?php

define('BASEPATH', '');
require_once '../modelo/historiaParto.php';
$obj = new historiaParto();

if (!isset($_POST['accion'])) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
} else {

    $accion = addslashes($_POST['accion']);
    if (isset($_POST['cedula_p'])) {
        $cedula_p = addslashes($_POST["cedula_p"]);
    } else {
        $cedula_p = "";
    }
    if (isset($_POST['historia'])) {
        $historia = addslashes($_POST["historia"]);
    } else {
        $historia = "";
    }
    if (isset($_POST['sexo'])) {
        $sexo = addslashes($_POST["sexo"]);
    } else {
        $sexo = "";
    }
    if (isset($_POST['peso'])) {
        $peso = addslashes($_POST["peso"]);
    } else {
        $peso = "";
    }
    if (isset($_POST['tamano'])) {
        $tamano = addslashes($_POST["tamano"]);
    } else {
        $tamano = "";
    }
    if (isset($_POST['fecha_parto'])) {
        $fecha_parto = addslashes($_POST["fecha_parto"]);
    } else {
        $fecha_parto = "";
    }
    if (isset($_POST['hora_parto'])) {
        $hora_parto = addslashes($_POST["hora_parto"]);
    } else {
        $hora_parto = "";
    }
    if (isset($_POST['observacion'])) {
        $observacion = addslashes($_POST["observacion"]);
    } else {
        $observacion = "";
    }
    switch ($accion) {
        case 'Agregar':
            $obj->addhistoriaParto($cedula_p, $historia, $sexo, $peso, $tamano, $fecha_parto, $hora_parto, $observacion);
            break;
        case 'Buscar':
            $resultado = $obj->gethistoriaParto($cedula_p);
            echo json_encode(array('' => $resultado['historiaParto'], 'cedula_p' => $resultado['cedula_p']));
            break;
        case 'Modificar':
            $obj->edihistoriaParto($cedula_p, $historia, $sexo, $peso, $tamano, $fecha_parto, $hora_parto, $observacion);
            break;
        case 'Eliminar':
            $obj->deletehistoriaParto($cedula_p);
            break;
    }
}
?>
