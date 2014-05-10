<?php

define('BASEPATH', '');

require_once $_SERVER['DOCUMENT_ROOT'].'/Maternidad/FirePHP/fb.php';

$firephp = new FirePHP();
require_once '../modelo/Asignar.php';
$obj = new Asignar();

if (!isset($_POST['accion'])) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
} else {

    $accion = addslashes($_POST['accion']);
    if (isset($_POST['cedula_p'])) {
        $datos['cedula_p'] = addslashes($_POST["cedula_p"]);
    } else {
        $datos['cedula_p'] = "";
    }
    if (isset($_POST['cedula_pm'])) {
        $datos['cedula_pm'] = addslashes($_POST["cedula_pm"]);
    } else {
        $datos['cedula_pm'] = "";
    }
    if (isset($_POST['num_consultorio'])) {
        $datos['num_consultorio'] = addslashes($_POST["num_consultorio"]);
    } else {
       $datos['num_cosultorio'] = "";
    }

    if (isset($_POST['fecha'])) {
        $datos['fecha'] = addslashes($_POST["fecha"]);
    } else {
        $datos['fecha'] = "";
    }


    switch ($accion) {
        case 'Agregar':
            $obj->addAsignar($datos);
        break;
        case 'BuscarDatos':
            $resultado = $obj->getAsignar($datos);
            if ($resultado === FALSE) {
                echo json_encode(array(
                    'tipo_error'       => 'error',
                    'error_codmensaje' => 17,
                    'error_mensaje'    => 'La Cédula no esta registrada'
                ));
            } else {
                echo json_encode(array(
                    'nombre'   => $resultado['nombre'],
                    'apellido' => $resultado['apellido'],
                    'telefono' => $resultado['telefono'],
                ));
            }

        break;
        case 'Modificar':
            $obj->editEspecialidad($cod_esp, $especialidad);
        break;
        case 'Eliminar':
            $obj->deletePrivilegio($codigo_privilegio);
        break;
        case 'BuscarTurno':
            $resultado = $obj->getDatosCons($datos);

            if ((boolean)$resultado == 1) {
                for ($j = 0; $j < count($resultado); $j++) {
                    $data[] = array(
                        'cedula_pm' => $resultado[$j]['cedula_pm'],
                        'cod_turno' => $resultado[$j]['cod_turno'],
                        'nombres'   => $resultado[$j]['nombres'],
                        'turno'     => $resultado[$j]['turno'],
                        'horario'   => $resultado[$j]['horario']
                    );
                }
                echo json_encode($data);
            }else{
                echo 0;
            }
        break;


    }
}
?>
