<?php

if (!defined('BASEPATH')) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/Maternidad/FirePHP/fb.php';

$path   = dirname(__FILE__);
$modulo = 'paciente';
$fin    = strpos($path, $modulo);
$path   = substr($path, 0, $fin);
require_once $path . 'seguridad/Seguridad.php';

class Paciente extends Seguridad
{

    private $_mensaje;
    private $_cod_msg;

    public function __construct()
    {
        $this->firephp = FirePHP::getInstance(true);
    }

    public function addPaciente($datos)
    {

        try {
            $cedula_p         = $datos['cedula_p'];
            $fecha_nacimiento = $datos['fecha_nacimiento'];
            $exi_ced          = $this->recordExists("paciente", "cedula_p='" . $cedula_p . "'");
            if ($exi_ced === TRUE) {
                $this->_cod_msg = 15;
                $this->_mensaje = '<span style="color:#FF0000">La C&eacute;dula se Encuentra Registrada en el Sistema</span>';
            } else {

                $fecha_nacimiento = $this->formateaBD($fecha_nacimiento);
                $fecha            = array('fecha_nacimiento' => $fecha_nacimiento);
                $datos            = array_merge($datos, $fecha);
                $insert           = $this->insert('paciente', $datos);
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

    public function getPaciente($datos)
    {

        if (!isset($datos['sql'])) {
            $cedula_p = $datos['cedula_p'];
            $data     = array(
                'tabla'     => 'paciente AS p',
                'campos'    => "p.nombre,p.apellido,p.cod_telefono,CONCAT('0',CONCAT_WS('-',(SELECT codigo FROM codigo_telefono  WHERE cod_telefono=p.cod_telefono),p.telefono)) AS telefono,p.cod_celular,CONCAT('0',CONCAT_WS('-',(SELECT codigo FROM codigo_telefono WHERE cod_telefono=p.cod_celular),p.celular)) AS celular,p.direccion,(SELECT cod_municipio FROM sector WHERE cod_sector=p.cod_sector) AS cod_municipio,p.cod_sector",
                'condicion' => "p.cedula_p = $cedula_p");
            $result   = $this->row($data);
        } else {
            $result = $this->ex_query($datos['sql']);
        }
        return $result;
    }

    public function editPaciente($datos)
    {
        try {
            
                
            $cedula_p         = $datos['cedula_p'];
            $fecha_nacimiento = $datos['fecha_nacimiento'];
            $telefono         = substr($datos['telefono'],5);
            $celular          = substr($datos['celular'],5);  
            
            $fecha_nacimiento = $this->formateaBD($fecha_nacimiento);
            $dat_new          = array('fecha_nacimiento' =>$fecha_nacimiento ,'telefono' => $telefono,'celular'=>$celular);
            $datos            = array_merge($datos, $dat_new);
            
            unset($datos['cedula_p']);
            unset($datos['nacionalidad']);

            $where  = "cedula_p='$cedula_p'";

            $update = (boolean) $this->update('paciente', $datos, $where);

            if ($update === TRUE || $update > 0) {
                $this->_cod_msg = 22;
                $this->_mensaje = "El Registro ha sido  Modificado exitosamente";
            } else {
                $this->_cod_msg = 16;
                $this->_mensaje = '<span style="color:#FF0000">Ocurrio un error comuniquese con informatica</span>';
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
            return array('error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage());
        }
    }

    public function deletePaciente($cedula_p)
    {
        $delete = $this->_mysql->delete("paciente", "cedula_p=$cedula_p");

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

    public function getPacienteAll()
    {
        $data   = array(
            'tabla'     => 'paciente AS p ,codigo_telefono AS ct',
            'campos'    => "CONCAT_WS('-',p.nacionalidad,p.cedula_p) AS cedula_p,CONCAT_WS(' ',p.nombre,p.apellido) AS nombres,DATE_FORMAT(p.fecha_nacimiento, '%d-%m-%Y') AS fecha,CONCAT('0',IF(p.telefono=0,(SELECT CONCAT_WS('-',ctt.codigo,p.celular) FROM codigo_telefono AS ctt WHERE ctt.cod_telefono=p.cod_celular),(SELECT CONCAT_WS('-',ctt.codigo,p.telefono) FROM codigo_telefono AS ctt WHERE ctt.cod_telefono=p.cod_telefono))) AS telefono",
            'condicion' => 'p.cod_telefono=ct.cod_telefono'
        );
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getDatos($datos)
    {
        $cedula_p = $datos['cedula_p'];
        $data     = array('tabla' => 'sector', 'campos' => "cod_sector,sector", "condicion" => "cod_municipio='" . $codigo_municipio . "'");
        $result   = $this->select($data, FALSE);
        return $result;
    }

    public function getMunicipio()
    {
        $data   = array('tabla' => 'municipios', 'campos' => "codigo_municipio,municipio");
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getSector($datos)
    {
        $codigo_municipio = $datos['codigo_municipio'];
        $data             = array(
            'tabla'     => 'sector',
            'campos'    => "cod_sector,sector",
            "condicion" => "cod_municipio='" . $codigo_municipio . "'"
        );
        $result           = $this->select($data, FALSE);
        return $result;
    }

    public function getCodLocal()
    {
        $data   = array('tabla' => 'codigo_telefono', 'campos' => "cod_telefono,codigo", "condicion" => "tipo=1");
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getCodCelular()
    {
        $data   = array('tabla' => 'codigo_telefono', 'campos' => "cod_telefono,codigo", "condicion" => "tipo=2");
        $result = $this->select($data, FALSE);
        return $result;
    }

}
