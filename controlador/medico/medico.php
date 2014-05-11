<?php
define('BASEPATH', '');

require_once '../../FirePHP/fb.php';
$firephp = new FirePHP();

if (!isset($_POST['accion'])) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
} else {
    require_once '../../modelo/medico/Medico.php';
    $obj = new Medico();


    $accion = addslashes($_POST['accion']);
    if (isset($_POST['hnac'])) {
        $datos['nacionalidad'] = addslashes($_POST["hnac"]);
    }
    if (isset($_POST['cedula_pm'])) {
        $nacionalidad       = substr(addslashes($_POST["cedula_pm"]), 0, 2);  // devuelve "abcde"
        $datos['cedula_pm'] = substr(addslashes($_POST["cedula_pm"]),2);
    }
    if (isset($_POST['nombre'])) {
        $datos['nombre'] = addslashes($_POST["nombre"]);
    }
    if (isset($_POST['apellido'])) {
         $datos['apellido'] = addslashes($_POST["apellido"]);
    }
    if (isset($_POST['hcod_telefono'])) {
        $datos['cod_telefono'] = addslashes($_POST["hcod_telefono"]);
    }
    if (isset($_POST['telefono'])) {
        $datos['telefono'] = substr(addslashes($_POST["telefono"]),5);
    }
    if (isset($_POST['direccion'])) {
        $datos['direccion'] = addslashes($_POST["direccion"]);
    }
    if (isset($_POST['cod_esp'])) {
        $datos['cod_especialidad'] = addslashes($_POST["cod_esp"]);
    }
    switch ($accion) {
        case 'Agregar':
            $resultado = $obj->addpersonalMedico($datos);
            echo json_encode($resultado);
        break;

        case 'BuscarDatos':
            $datos['nacionalidad'] = $nacionalidad;
            $resultado = $obj->getMedico($datos);
            $es_array = is_array($resultado) ? TRUE:FALSE;
            if ($es_array) {
                echo json_encode(array('nombre'=>$resultado['nombre'],'apellido'=>$resultado['apellido'], 'especialidad'=>$resultado['cod_especialidad'],'direccion'=>$resultado['direccion'],'cod_telefono'=>$resultado['cod_telefono']));
            }else{
                echo 0;
            }
        break;
        case 'Modificar':
            $resultado = $obj->editpersonalMedico($datos);
            echo json_encode($resultado);
        break;
        case 'Eliminar':
            $obj->deletepersonalMedico($cedula_pm);
        break;
    }
}

