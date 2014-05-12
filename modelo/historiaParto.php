<?php

if (!defined('BASEPATH'))
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");

require_once 'MySQL.php';
require_once 'Validacion.php';

class historiaParto extends Validacion
{

    private $_mensaje;
    private $_cod_msg;

    public function __construct()
    {
        $this->_mysql = MySQL::crear();
    }

    public function addhistoriaParto($cedula_p, $fecha_parto, $hora_parto, $sexo, $peso, $tamano, $observacion)
    {
        $exi_ced = $this->_mysql->existeReg("historiaParto", "cedula_p='" . $cedula_p . "'");

        try {
            if ($this->validar($cedula_p, 'cedula') === FALSE) {
                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else if ($exi_ced === TRUE) {
                $this->_mensaje = 'La Cédula se encuentra Registrada';
                throw new Exception($this->_mensaje, 400);
            } else {

                $fecha_parto = $this->formateaBD($fecha_parto);

                $data = array(
                    "cedula_p"    => $cedula_p,
                    "fecha_parto" => "$fecha_parto",
                    "hora_parto"  => "$hora_parto",
                    "sexo"        => "$sexo",
                    "peso"        => "$peso",
                    "tamano"      => "$tamano",
                    "observacion" => "$observacion"
                );

                $insert = $this->_mysql->insert('historiaParto', $data);
                if ($insert === TRUE) {
                    $this->_mensaje = 'Registro con exito';
                    $this->_cod_msg = 500;
                }
                throw new Exception($this->_mensaje, $this->_cod_msg);
            }
        } catch (Exception $e) {
            echo json_encode(array('error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage()));
        }
    }

    public function gethistoriaParto($cedula_p)
    {
        $data   = array('tabla' => 'historiaParto', 'campos' => 'cedula_p,fecha_parto,hora_parto,sexo,tamano,observacion', 'condicion' => "cedula_p =$cedula_p");
        $result = $this->_mysql->row($data);
        return $result;
    }

    public function edithistoriaParto($cedula_p, $fecha_parto, $hora_parto, $sexo, $peso, $tamano, $observacion)
    {
        try {

            if ($this->validar($fecha_parto, 'fecha_parto') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else if ($this->validar($hora_parto, 'hora') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else if ($this->validar($sexo, 'sexo') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else if ($this->validar($peso, 'peso') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else if ($this->validar($tamano, 'tamano') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else {
                $data = array('fecha_parto' => $fecha_parto, 'fecha_parto' => $hora_parto, 'hora' => $sexo, 'sexo' => $peso, 'peso' => $tamano, 'tamano');
                $where = "cedula_p='$cedula_p'";

                $update = (boolean) $this->_mysql->update('historiaParto', $data, $where);

                if ($update === TRUE) {
                    $this->_mensaje = 'Modificacion con exito';
                    $this->_cod_msg = 501;
                    throw new Exception($this->_mensaje, $this->_cod_msg);
                }
            }
        } catch (Exception $e) {
            echo json_encode(array('error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage()));
        }
    }

    public function deletehistoriaParto($cedula_p)
    {
        $delete = $this->_mysql->delete("historiaParto", "cedula_p=$cedula_p");

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

    public function gethistoriaPartoAll()
    {
        $data   = array('tabla' => 'historiaParto', 'campos' => "DATE_FORMAT(fecha_parto, '%d/%m/%Y'),hora_parto,sexo,peso,tamano,");
        $result = $this->_mysql->select($data, FALSE);
        return $result;
    }
}