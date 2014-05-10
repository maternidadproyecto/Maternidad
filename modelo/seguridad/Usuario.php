<?php
if (!defined('BASEPATH')) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
}
$path = dirname(__FILE__);
require_once "$path/Perfil.php";
class Usuario extends Perfil
{
    public function __construct() 
    {
        $this->table   = 's_usuario';
        ob_start();
        $this->firephp = FirePHP::getInstance(true);
    }
     private function _addUsuario()
    {
        $resultado = parent::add();
        return $resultado;
    }

    public function addUsuario($datos)
    {
        
        try {
            $where    = "usuario='" . $datos['usuario'] . "'";
            $exis_reg = parent::recordExists($this->table, $where);
            if ($datos['usuario'] == 'admin') {
                $this->_cod_msg = 20;
                $this->_mensaje = "<span style='color:#FF0000'>Hubo un error comuniquese con Inform&aacute;tica</span>";
            } else if ($exis_reg === TRUE) {
                $this->_cod_msg = 15;
                $this->_mensaje = "<span style='color:#FF0000'>El Nombre del Perfil ya existe</span>";
            } else {
         
                $datos['clave'] = $this->clave($datos['clave']);
                $this->a_datos = $datos;
                $resultado     = $this->_addUsuario();
                
                if ($resultado === TRUE) {
                    $this->_cod_msg = 21;
                    $this->_mensaje = "El Registro ha sido Guardado Exitosamente";
                }
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
            return array('error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage());
        }
    }
     protected function getUsuarios()
    {
        if(!isset($this->_sql)){
            $data   = array('tabla' => $this->table, 'campos' => $this->_campos);
            $result = parent::select($data, FALSE);
        }else{
            $result     = parent::ex_query($this->_sql);
        }
        return $result;
    }

    public function getUsuario($datos)
    {
        
        if (!empty($datos['campos'])) {
            $this->_campos = $datos['campos'];
        }
        if(isset($datos['sql'])){
            $this->_sql = $datos['sql'];
        }
        $result = $this->getUsuarios();
        return $result;
    }
}