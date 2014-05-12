<?php

define('BASEPATH', '');

require_once $_SERVER['DOCUMENT_ROOT'].'/Maternidad/FirePHP/fb.php';

$firephp = new FirePHP();

require_once '../../modelo/paciente/Historia.php';


$obj      = new Historia();

$obj_medi = new Historia();

if (!isset($_POST['accion'])) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
} else {

    $accion = addslashes($_POST['accion']);
    if (isset($_POST['hnac'])) {
        $data['nacionalidad'] = addslashes($_POST["hnac"]);
    }
    if (isset($_POST['cedula_p'])) {
        $data['cedula_p'] = addslashes($_POST["cedula_p"]);
    }
    if (isset($_POST['cedula_pm'])) {
        $data['cedula_pm'] = substr(addslashes($_POST["cedula_pm"]),2);
    }
    if (isset($_POST['num_consultorio'])) {
        $data['num_consultorio'] = addslashes($_POST["num_consultorio"]);
    }
    if (isset($_POST['historia'])) {
       $data['historia']  = addslashes($_POST["historia"]);
    }
    if (isset($_POST['lugar_control'])) {
        $data['lugar_control'] = addslashes($_POST["lugar_control"]);
    }
    if (isset($_POST['fur'])) {
        $data['fur'] = addslashes($_POST["fur"]);
    }
    if (isset($_POST['fpp'])) {
        $data['fpp'] = addslashes($_POST["fpp"]);
    }
    if (isset($_POST['diagnostico'])) {
        $data['diagnostico'] = addslashes($_POST["diagnostico"]);
    }
    if (isset($_POST['observacion'])) {
         $data['observacion']  = addslashes($_POST["observacion"]);
    }
    if (isset($_POST['hnac'])) {
        $data['nacionalidad'] = addslashes($_POST["hnac"]);
    }
    if (isset($_POST['tamano'])) {
        $data['tamano'] = addslashes($_POST["tamano"]);
    }
    if (isset($_POST['peso'])) {
        $data['peso'] = addslashes($_POST["peso"]);
    }
    if (isset($_POST['tension'])) {
        $data['tension'] = addslashes($_POST["tension"]);
    }
    switch ($accion) {
        case 'Agregar':
            $resultado = $obj->addhistoria($data);
            echo json_encode($resultado);
        break;
        case 'BuscarDatos':
            $resultado = $obj->BuscarDatos($data);

            if($resultado === FALSE){
                $datos['error'] = 'error';
                $datos['cod_error'] = 17;
                $datos['mensaje'] = '<span style="color:#FF0000">El N&uacute;mero de Cédula no se encuentra registrado</span>';
            }else{
                $datos = $resultado;
            }
            
            echo json_encode($datos);
        break;
        case 'Modificar':
            $obj->edithistoria($cedula_p, $historia, $lugar_control, $fur, $fpp, $diagnostico, $observacion);
            break;
        case 'Eliminar':
            $obj->deletehistoria($cedula_p);
            break;
    }
}
