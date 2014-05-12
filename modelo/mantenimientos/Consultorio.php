<?php

if (!defined('BASEPATH')) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/Maternidad/FirePHP/fb.php';

$path = dirname(__FILE__);
require_once $path . '/Especialidad.php';

class Consultorio extends Especialidad
{

    private $_mensaje;
    private $_cod_msg;
    private $_tipoerror;

    public function __construct()
    {
        $this->firephp = FirePHP::getInstance(true);
    }

    // funcion para registrar el consultorio
    public function addConsultorio($datos)
    {

        try {

            $cosultorio = $datos['consultorio'];
            $turnos     = array_pop($datos);
            $tur_opc    = array('manana'=>'0','tarde'=>'0'); 
            $turno      = array_merge($tur_opc,$turnos);
            $datos = array_merge($datos,$turno);

            // verificar que no exista un consultorio con el mismo nombre
            $existe_consultorio = $this->recordExists("consultorio", "consultorio='" . $cosultorio . "'");
            if ($existe_consultorio === TRUE) {
                $this->_cod_msg = 15;
                $this->_mensaje = '<span style="color:#FF0000">El nombre del Consultorio se encuentra Registrado</span>';
            } else {
                $insert = $this->insert('consultorio', $datos);
                if ($insert === TRUE) {
                    $this->_cod_msg = 21;
                    $this->_mensaje = "El Registro ha sido Guardado Exitosamente";
                }
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
            return array(
                'tipo_error'       => $this->_tipoerror,
                'error_codmensaje' => $e->getCode(),
                'error_mensaje'    => $e->getMessage()
            );
        }
    }

    public function editConsultorio($datos)
    {
  
            $consultorio = $datos['consultorio'];
            $turnos     = array_pop($datos);
            $tur_opc    = array('manana'=>'0','tarde'=>'0'); 
            $turno      = array_merge($tur_opc,$turnos);
            $datos = array_merge($datos,$turno);
            array_shift($datos);
        try {


            $where = "consultorio='$consultorio'";

            $update = $this->update('consultorio', $datos, $where);

            if ($update === TRUE || $update > 0) {
                $this->_cod_msg   = 22;
                $this->_tipoerror = 'info';
                $this->_mensaje   = "El Registro ha sido  Modificado con exito";
            } else {
                $this->_cod_msg   = 16;
                $this->_tipoerror = 'error';
                $this->_mensaje   = "Ocurrio un error comuniquese con informatica";
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
            echo json_encode(array(
                'tipo_error'       => $this->_tipoerror,
                'error_codmensaje' => $e->getCode(),
                'error_mensaje'    => $e->getMessage()
            ));
        }
    }

    public function getHorarioConsultorio($datos)
    {

        $num_consultorio = $datos['num_consultorio'];

        $data   = array(
            'tabla'     => 'consultorio_horario ch',
            'campos'    => 'ch.cod_especialidad,ch.cod_turno,ch.cod_hora_desde AS desde ,ch.cod_hora_hasta AS hasta',
            'condicion' => "ch.num_consultorio =$num_consultorio"
        );
        $result = $this->row($data);
        return $result;
    }

    public function getDatosConsultorio($datos)
    {

        $consultorio = $datos['consultorio'];

        $data   = array(
            'tabla'     => 'consultorio',
            'campos'    => 'cod_especialidad',
            'condicion' => "consultorio ='$consultorio'"
        );
        $result = $this->row($data);
        return $result;
    }

    public function getMedico($datos)
    {

        $cod_turnos      = $datos['cod_turnos'];
        $num_consultorio = $datos['num_consultorio'];

        $data   = array(
            'tabla'     => 'consultorio_horario AS  ch,consultorio_medico AS cm',
            'campos'    => 'COUNT(cm.cedula_pm) AS total, IFNULL(ch.cod_turno,0) AS  cod_turno',
            'condicion' => "ch.cod_consu_horario=cm.cod_consu_horario AND ch.num_consultorio = $num_consultorio AND ch.cod_turno IN($cod_turnos)"
        );
        $result = $this->row($data);
        //$result = $this->get('consultorio_horario AS  ch,consultorio_medico AS cm','COUNT(cm.cedula_pm) AS total',"ch.cod_consu_horario=cm.cod_consu_horario AND ch.num_consultorio = $num_consultorio AND ch.cod_turno IN($cod_turnos)");
        return $result;
    }

    public function getConsultorioAll()
    {

        $data   = array(
            'tabla'  => "consultorio AS c",
            'campos' => "c.num_consultorio,c.consultorio,c.manana,c.tarde,(SELECT especialidad FROM especialidad WHERE cod_especialidad=c.cod_especialidad) AS especialidad"
        );
        $result = $this->select($data, FALSE);
        return $result;
    }

    /* public function getEspecialidad()
      {
      $data   = array('tabla' => 'especialidad', 'campos' => "cod_especialidad,especialidad");
      $result = $this->select($data, FALSE);
      return $result;
      } */

    public function getTurno()
    {
        $data   = array('tabla' => 'turno', 'campos' => "cod_turno,turno");
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getHora()
    {
        $data   = array('tabla' => 'hora', 'campos' => "cod_hora,hora");
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getHorario($datos)
    {
        $condicion = $datos['condicion'];
        if ($condicion == 'turno') {
            $codigo   = $datos['cod_turno'];
            $condicio = "cod_turno = $codigo";
        } else {
            $cod_hora  = $datos['cod_hora'];
            $cod_turno = $datos['cod_turno'];
            $condicio  = "cod_hora > $cod_hora AND cod_turno = $cod_turno";
        }
        $data   = array(
            'tabla'     => 'hora',
            'campos'    => "cod_hora AS codigo,hora AS descripcion",
            'condicion' => $condicio
        );
        $result = $this->select($data, FALSE);
        return $result;
    }

}
