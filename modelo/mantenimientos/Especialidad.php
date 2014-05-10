<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Maternidad/FirePHP/fb.php';
if (!defined('BASEPATH')) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
}

$path   = dirname(__FILE__);
$modulo = 'mantenimientos';
$fin    = strpos($path, $modulo);
$path   = substr($path, 0, $fin);
require_once $path . 'seguridad/Seguridad.php';

class Especialidad extends Seguridad
{

    private $_mensaje;
    private $_cod_msg;
    private $_tipoerror;
    private $_table = 'especialidad';
    public function __construct()
    {
        $this->_firephp = FirePHP::getInstance(true);
    }

    public function addEspecialidad($data)
    {

        try {
            $especialidad = $data['especialidad'];

            $exi_esp = $this->recordExists("especialidad", "especialidad='" . $especialidad . "'");
            if ($exi_esp === TRUE) {
                $this->_mensaje   = 'El nombre de la Especialidad se encuentra Registrada';
                $this->_cod_msg   = 15;
            } else {

                $insert = $this->insert('especialidad', $data);
                if ($insert === TRUE) {
                    $this->_cod_msg = 21;
                    $this->_mensaje = "El Registro ha sido Guardado Exitosamente";
                } else {
                    $this->_cod_msg = 16;
                    $this->_mensaje = '<span style="color:#FF0000">Ocurrio un error comuniquese con informatica</span>';
                }
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
            return array('error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage());
        }
    }

    public function getEspecialidad($especialidad)
    {
        $data   = array('tabla' => 'especialidad', 'campos' => 'cod_especialidad,especialidad', 'condicion' => "especialidad =$especialidad");
        $result = $this->row($data);
        return $result;
    }

    public function editEspecialidad($data)
    {

        try {

            $cod_especialidad = array_shift($data);
            $especialidad     = $data['especialidad'];
            $where            = "cod_especialidad=$cod_especialidad";
            $exi_esp          = $this->recordExists("especialidad", "especialidad='" . $especialidad . "' AND cod_especialidad!=$cod_especialidad");
            if ($exi_esp === TRUE) {
                $this->_cod_msg = 22;
                $this->_mensaje = "El Registro ha sido Modificado Exitosamente";
            } else {

                $update = (boolean) $this->update('especialidad', $data, $where);

                if ($update === TRUE) {
                    $this->_cod_msg = 22;
                    $this->_mensaje = "El Registro ha sido Modificado Exitosamente";
                } else {
                    $this->_cod_msg = 16;
                    $this->_mensaje = '<span style="color:#FF0000">Ocurrio un error comuniquese con informatica</span>';
                }
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
            return array('error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage());
        }
    }

    public function deleteEspecialidad($data)
    {

        try {
            
            
            $cod_especialidad = array_shift($data);
            
            $where = "cod_especialidad=$cod_especialidad";
            $existe = $this->recordExists('consultorio', $where);
            
            if ($existe === TRUE) {
                $this->_cod_msg = 30;
                $this->_mensaje = '<span style="color:#FF0000">La Especialidad no puede ser eliminada ya que posee registros asociados</span>';
            } else {
                $delete = $this->delete('especialidad', $where);

                if ($delete == TRUE) {
                    $this->_cod_msg = 23;
                    $this->_mensaje = "El Registro ha sido Eliminado Exitosamente";
                } else {
                    $this->_cod_msg = 16;
                    $this->_mensaje = '<span style="color:#FF0000">Ocurrio un error comuniquese con informatica</span>';
                }
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
            return array('error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage());
        }
    }

    public function getEspecialidadAll()
    {
        $data   = array(
            'tabla'  => 'especialidad',
            'campos' => "cod_especialidad,especialidad"
        );
        $result = $this->select($data, FALSE);
        
        return $result;
    }

}
