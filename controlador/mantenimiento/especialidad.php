<?php
define('BASEPATH', '');

if (!isset($_POST['accion'])) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
}else{

    $accion = addslashes($_POST['accion']);
    if(isset($_POST['cod_especialidad'])){
        $data['cod_especialidad'] = addslashes($_POST['cod_especialidad']);
    }
    if (isset($_POST['especialidad'])) {
        $data['especialidad'] = addslashes($_POST["especialidad"]);
    }
    require_once '../../modelo/mantenimientos/Especialidad.php';
    $obj = new Especialidad();

    switch ($accion) {
        case 'Agregar':
            $resultado = $obj->addEspecialidad($data);
            echo json_encode($resultado);
       break;
       case 'Modificar':
       $resultado = $obj->editEspecialidad($data);
       echo json_encode($resultado);
       break;
       case 'Eliminar':
       $resultado = $obj->deleteEspecialidad($data);
       echo json_encode($resultado);
       break;
    }
}