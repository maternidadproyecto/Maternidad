<?php

if (!defined('BASEPATH')) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
}

require_once '../../FirePHP/fb.php';

$path   = dirname(__FILE__);
$modulo = 'medico';
$fin    = strpos($path, $modulo);
$path   = substr($path, 0, $fin);

require_once $path . 'mantenimientos/Especialidad.php';
class Medico extends Especialidad
{

    private $_mensaje;
    private $_cod_msg;

    public function __construct()
    {
        $this->firephp = FirePHP::getInstance(true);
    }

    public function getCod()
    {
        $data   = array('tabla' => 'codigo_telefono', 'campos' => "cod_telefono,codigo");
        $result = $this->select($data, FALSE);
        return $result;
    }
    public function addpersonalMedico($datos)
    {

        try {
            $cedula_pm    = $datos['cedula_pm'];
            $existe = $this->recordExists("personal_medico", "cedula_pm='" . $cedula_pm . "'");
            if ($existe === TRUE) {
                $this->_cod_msg   = 15;
                $this->_mensaje   = '<span style="color:#FF0000">La C&eacute;dula se Encuentra Registrada en el Sistema</span>';
            } else {

                $insert = $this->insert('personal_medico', $datos);
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

    public function getpersonalMedico($cedula_pm)
    {
        $data = array('tabla' => 'personalMedico', 'campos' => 'cedula_pm,nombre,apellido,telefono,direccion,num_const,cod_esp', 'condicion' => "cedula_pm =$cedula_pm");
        $result = $this->row($data);
        return $result;
    }

    public function editpersonalMedico($datos)
    {
        
        $cedula_pm    = $datos['cedula_pm'];
        $cod_telefono = $datos['cod_telefono'];
        $telefono     = $datos['telefono'];
        $direccion    = $datos['direccion'];

        try {
           
            $data  = array('cod_telefono'=>$cod_telefono,'telefono' => $telefono, 'direccion' => $direccion);
            $where = "cedula_pm='$cedula_pm'";

            $update = (boolean) $this->update('personal_medico', $data, $where);

            if ($update === TRUE || $update > 0) {
                $this->_cod_msg   = 22;
                $this->_mensaje   = "El Registro ha sido  Modificado exitosamente";
                
            } else {
                $this->_tipoerror = 'error';
                $this->_mensaje   = '<span style="color:#FF0000">Ocurrio un error comuniquese con informatica</span>';
                $this->_cod_msg   = 16;
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
              return array('error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage());
        }
    }

    public function deletepersonalMedico($cedula_pm)
    {
        $delete = $this->delete("personalMedico", "cedula_pm=$cedula_pm");

        try {
            if ($delete === TRUE) {
                $this->_mensaje = 'Eliminación con exito';
                $this->_cod_msg = 502;
                throw new Exception($this->_mensaje, $this->_cod_msg);
            }
        } catch (Exception $e) {
            echo json_encode(array('error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage()));
        }
    }
    
    public function getMedico($datos)
    {
        
        $cedula_pm    = $datos['nacionalidad'].$datos['cedula_pm'];
        $data = array(
            "tabla"     => "personal_medico",
            "campos"    => "direccion,cod_especialidad,cod_telefono",
            "condicion" => "CONCAT_WS('-',nacionalidad,cedula_pm)='$cedula_pm'"
        );
        $result = $this->row($data, FALSE);
        return $result;
    }
    
    public function getPersonalMedicoAll()
    {
        $data = array(
            "tabla"     => "personal_medico pm",
            "campos"    => "CONCAT_WS('-',pm.nacionalidad,pm.cedula_pm) AS cedula_pm,pm.nombre,pm.apellido,CONCAT('0', CONCAT_WS('-', (SELECT codigo FROM codigo_telefono WHERE cod_telefono=pm.cod_telefono),pm.telefono)) AS telefono"
        );
        $result = $this->select($data, FALSE);
        return $result;
    }


    public function getConsultorio($datos)
    {
        $cod_esp = $datos['cod_esp'];

        $data   = array(
            'tabla'  => 'consultorio AS c',
            'campos' => "c.num_consultorio,c.consultorio",
            'condicion'=>"c.cod_especialidad=$cod_esp"
        );
        $result = $this->select($data, FALSE);
        return $result;
    }
    public function getTurno($datos)
    {
        $num_cons = $datos['num_cons'];
        //$this->_firephp->error($datos,'Modelo');
        $data   = array(
            'tabla'  => 'consultorio_horario  ch,turno t',
            'campos' => "t.cod_turno,t.turno",
            'condicion'=>"ch.cod_turno=t.cod_turno AND ch.num_consultorio=$num_cons"
        );
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getDatos($datos)
    {
        $cedula_pm = $datos['cedula_pm'];
        $data   = array(
            'tabla'     => 'personal_medico pm,consultorio_medico cm,consultorio c, consultorio_horario ch',
            'campos'    => "pm.nombre,pm.apellido,pm.telefono,pm.direccion,ch.cod_especialidad,ch.num_consultorio,ch.cod_turno",
            'condicion' => "pm.cedula_pm=cm.cedula_pm AND cm.num_consultorio= c.num_consultorio AND c.num_consultorio=ch.num_consultorio AND pm.cedula_pm=$cedula_pm"
        );
        $result = $this->row($data);

        return $result;
    }
}
