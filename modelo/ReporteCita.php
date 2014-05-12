<?php
if (!defined('BASEPATH')){
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
}

require_once $_SERVER['DOCUMENT_ROOT'].'/Maternidad/FirePHP/fb.php';

require_once 'Conexion.php';
class ReporteCita extends Conexion
{
    private $_mensaje;
    private $_cod_msg;
    private $_tipoerror;

    public function __construct()
    {
         $this->firephp = FirePHP::getInstance(true);
    }
    public function getEspecialidad()
    {
        $data   = array(
            'tabla'  => 'especialidad',
            'campos' => "cod_especialidad,especialidad"
        );
        $result = $this->select($data, FALSE);
        return $result;
    }

}
