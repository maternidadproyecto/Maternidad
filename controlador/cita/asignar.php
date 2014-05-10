<?php

define('BASEPATH', '');

require_once $_SERVER['DOCUMENT_ROOT'] . '/Maternidad/FirePHP/fb.php';

$firephp = new FirePHP();

if (!isset($_POST['accion'])) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
} else {
    require_once '../../modelo/cita/Asignar.php';
    $obj    = new Asignar();
    $accion = addslashes($_POST['accion']);
    if (isset($_POST['hnac'])) {
        $datos['nacionalidad'] = addslashes($_POST["hnac"]);
    }
    if (isset($_POST['cedula_p'])) {
        $datos['cedula_p'] = addslashes($_POST["cedula_p"]);
    }
    if (isset($_POST['fecha'])) {
        $datos['fecha'] = addslashes($_POST["fecha"]);
    }
    if (isset($_POST['num_consultorio'])) {
        
        $datos['num_consultorio'] = addslashes($_POST["num_consultorio"]);
    }
    if (isset($_POST['observacion'])) {
        $datos['observacion'] = addslashes($_POST["observacion"]);
    }
    if (isset($_POST['turno'])) {
        $datos['turno'] = $_POST["turno"];
    }

    switch ($accion) {
        case 'Asignar':
            $resultado = $obj->addAsignar($datos);
            echo json_encode($resultado);
        break;
        case 'BuscarDatos':
            $resultado = $obj->BuscarDatos($datos);
            if ($resultado === FALSE) {
                echo json_encode(array(
                    'tipo_error'       => 'error',
                    'error_codmensaje' => 17,
                    'error_mensaje'    => '<span style="color:#FF0000;margin-left:40%">ERROR<br/>Est&aacute; Cédula no se encuentra registrada</span>'
                ));
            } else if ($resultado === 4) {
                echo json_encode(array(
                    'error_codmensaje' => 15,
                    'error_mensaje'    => '<span style="color:#FF0000;margin-left:40%">ERROR<br/>Debe esperar 24 horas para generar una cita nueva</span>'
                ));
            } else {
                $datos = array(
                    'total'      => $resultado['total'],
                    'fech_max'   => $resultado['fech_max'],
                    'asistencia' => $resultado['asistencia'],
                    'nombre'     => $resultado['nombre'],
                    'apellido'   => $resultado['apellido'],
                    'telefono'   => $resultado['telefono'],
                    'citas'      => $resultado['citas']
                );
                echo json_encode($datos);
            }

        break;
        case 'CancelarCita':
            $resultado = $obj->CancelarCita($datos);
            echo json_encode($resultado);
        break;
        case 'BuscarCitas':
            $data = '';
            $resultado = $obj->BuscarCitas($datos);
            $es_array = is_array($resultado) ? TRUE : FALSE;
            if ($es_array === TRUE) {
                for ($j = 0; $j < count($resultado); $j++) {
                    $data .= $resultado[$j]['dias'].';'.$resultado[$j]['asistencia'].';'.$resultado[$j]['fecha'].';'.$resultado[$j]['consultorio'].';'.$resultado[$j]['turno'].';'.$resultado[$j]['observacion'].',';
                }
                $data = substr($data, 0,-1);
                echo $data;
            } else {
                echo 0;
            }
        break;
        case 'EliminarCita':
            $resultado = $obj->EliminarCita($datos);
            if ($resultado === TRUE) {
                echo json_encode(array(
                    'tipo_error'       => 'error',
                    'error_codmensaje' => 23,
                    'error_mensaje'    => 'El Registro ha sido Eliminado con exito'
                ));
            } else {
                echo json_encode(array(
                    'tipo_error'       => 'error',
                    'error_codmensaje' => 16,
                    'error_mensaje'    => 'Ocurrio un error comuniquese con informatica'
                ));
            }
        break;
        case 'BuscarTurno':
            $resultado = $obj->getDatosCons($datos);
            $turnos = $resultado['manana'].';'.$resultado['tarde'];
            echo $turnos;
        break;
    }
}