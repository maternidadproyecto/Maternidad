<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/Maternidad/FirePHP/fb.php';
ob_start();
$firephp = new FirePHP();


define('BASEPATH', '');
require_once '../../modelo/seguridad/SubModulo.php';
$obj = new SubModulo();
//$resulsub  = $obj->getSubModulos(1);

if (!isset($_POST['accion'])) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
} else {

    $accion = addslashes($_POST['accion']);

    if (isset($_POST['cod_submodulo'])) {
        $data['cod_submodulo'] = addslashes($_POST["cod_submodulo"]);
    }
    if (isset($_POST['cod_modulo'])) {
    $data['cod_modulo'] = addslashes($_POST["cod_modulo"]);
    } else if(isset ($_POST['nommodulo'])) {
        $data['cod_modulo'] = $_POST['nommodulo'];
    }
    if (isset($_POST['submodulo'])) {
        $data['sub_modulo'] = addslashes($_POST["submodulo"]);
    }
    if (isset($_POST['sbm_posicion'])) {
        $data['posicion'] = addslashes($_POST["sbm_posicion"]);
    }
    if (isset($_POST['sbmod_estatus'])) {
        $data['activo'] = addslashes($_POST["sbmod_estatus"]);
    }
    if (isset($_POST['ruta'])) {
        $data['ruta'] = addslashes($_POST["ruta"]);
    }

    switch ($accion) {
        case 'Guardar':
            $result = $obj->addSubModulo($data);
            echo json_encode($result);
        break;

        case 'Modificar':
            $resultado = $obj->editSubModulo($data);
            echo json_encode($resultado);
        break;

        case 'Buscar':
            $resultado = $obj->getSubModulo($cod_submodulo);
            echo json_encode(array('sub_modulo' => $resultado['sub_modulo'], 'orden_submodulo' => $resultado['orden_submodulo']));
        break;

        case 'Eliminar':
            $resultado = $obj->delSubModulo($data);
            echo json_encode($resultado);
        break;
        case 'BuscarSubModulos':
            $datos = '';
            $data['campos'] = 'cod_submodulo,sub_modulo,posicion,activo';
            $data['menu']   = 0;
            $resultado = $obj->getSubModulo($data);
            $es_array  = is_array($resultado) ? TRUE : FALSE;
            $es_int    = is_int($resultado) ? TRUE : FALSE;
      
            if ($es_array === FALSE && $es_int == TRUE) {
                echo $resultado;
            } else {
                $ultimo = array_pop($resultado);
                for ($j = 0; $j < count($resultado); $j++) {
                    $datos .= $resultado[$j]['cod_submodulo'].';'. $resultado[$j]['sub_modulo'].';'.$resultado[$j]['posicion'].';'.$resultado[$j]['activo'].',';
                }
                 $datos = substr($datos, 0,-1);
                 echo $ultimo.'/'.$datos;
            }
        break;
        case 'BuscarSub':
            $data      = array();
            $resultado = $obj->getSubModuloAll();
            for ($j = 0; $j < count($resultado); $j++) {
                $data[] = array('cod_modulo' => $resultado[$j]['cod_modulo'], 'modulo' => $resultado[$j]['modulo'], 'cod_submodulo' => $resultado[$j]['cod_submodulo'], 'submodulo' => $resultado[$j]['sub_modulo']);
            }
            echo json_encode($data);
        break;
        case 'CodMod':
            $resultado = $obj->getCodMod($data);
            echo $resultado;
        break;
        case 'BuscarRuta':
            $data['campos'] = 'ruta';
            $data['menu']   = TRUE;
            $resultado = $obj->getSubModulo($data);  
            echo $resultado[0]['ruta'];
        break;
    }
}

