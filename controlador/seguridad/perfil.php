<?php

define('BASEPATH', '');

require_once $_SERVER['DOCUMENT_ROOT'] . '/Maternidad/FirePHP/fb.php';
ob_start();
$firephp = new FirePHP();
//$firephp->log($_POST);
//exit;
require_once '../../modelo/seguridad/Perfil.php';
$obj_perfil = new Perfil();

if (!isset($_POST['accion'])) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
} else {

    $accion = addslashes($_POST['accion']);
    
    if (isset($_POST['codigo_perfil'])) {
        $data['codigo_perfil'] = addslashes($_POST["codigo_perfil"]);
    }else{
        $data['codigo_perfil'] = addslashes($_POST["cod_perfil"]);
    }
    if (isset($_POST['perfil'])) {
        $data['perfil'] = addslashes($_POST["perfil"]);
    }
    if (isset($_POST['activados'])) {
        $data['activados'] = addslashes($_POST["activados"]);
    }
    switch ($accion) {
        case 'Guardar':
            $resultado = $obj_perfil->addPerfil($data);
            echo json_encode($resultado);
        break;
        case 'Buscar':
            $resultado = $obj_perfil->getPrivilegio($privilegio);
            echo json_encode(array('privilegio' => $resultado['privilegio'], 'privilegio' => $resultado['privilegio']));
        break;
        case 'Modificar':
            $resultado = $obj_perfil->editPerfil($data);
            echo json_encode($resultado);
        break;
        case 'Eliminar':
            $resultado = $obj_perfil->delPerfil($data);
            echo json_encode($resultado);
            break;
        case 'BuscarSub':
            $resultado = $obj_perfil->getSubModulos($data);
            if (count($resultado) > 0) {
                for ($j = 0; $j < count($resultado); $j++) {
                    $datos[] = array('codigo' => $resultado[$j]['codigo'], 'descripcion' => $resultado[$j]['descripcion']);
                }
                echo json_encode($datos);
            } else {
                echo 0;
            }
            break;
        case 'BuscarPrivilegios':
            $datos = '';
            $resultado = $obj_perfil->getPrivilegioPerfil($data);
            
            if ($resultado != 0) {
                for ($j = 0; $j < count($resultado); $j++) {
                    $datos .= $resultado[$j]['cod_submodulo'].';'.$resultado[$j]['agregar'].';'.$resultado[$j]['modificar'].';'.$resultado[$j]['eliminar'].';'.$resultado[$j]['consultar'].';'.$resultado[$j]['imprimir'].',';
                    /*$datos[] = array(
                        'cod_submodulo' => $resultado[$j]['cod_submodulo'],
                        'agregar'       => $resultado[$j]['agregar'],
                        'modificar'     => $resultado[$j]['modificar'],
                        'eliminar'      => $resultado[$j]['eliminar'],
                        'consultar'     => $resultado[$j]['consultar'],
                        'imprimir'      => $resultado[$j]['imprimir']
                    );*/
                }
                $datos = substr($datos, 0,-1);
                echo $datos;
            } else {
                echo 0;
            }
            break;
        case 'AgregarPrivilegios':
            $resultado = $obj_perfil->addPrivilegios($data);
            echo json_encode($resultado);
        break;
    }
}