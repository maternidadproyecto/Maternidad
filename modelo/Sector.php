<?php

if (!defined('BASEPATH')) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
}


require_once 'Conexion.php';

class Sector extends Conexion
{

    private $_mensaje;
    private $_cod_msg;
    private $_tipoerror;

    public function __construct()
    {

    }

    public function addSector($data)
    {

        $municipio = $data['municipio'];
        $sector    = $data['sector'];

        $exi_sect  = $this->recordExists("sector", "sector='" . $sector . "'");

        try {
           if ($exi_sect === TRUE) {
                $this->_mensaje = 'El Sector se encuentra Registrado';
                throw new Exception($this->_mensaje, 400);
            } else {
                $this->cod_sector = $this->autoIncremet('sector', 'cod_sector');

                $data = array(
                    "cod_sector" => $this->cod_sector,
                    "sector" => $sector,
                    "cod_municipio" => $municipio
                );

                $insert = $this->insert('sector', $data);
                if ($insert === TRUE) {

                    $this->_tipoerror = 'success';
                    $this->_mensaje   = 'El Registro ha sido guardado con exito';
                    $this->_cod_msg   = 21;
                    throw new Exception($this->_mensaje, $this->_cod_msg);
                }
            }
        } catch (Exception $e) {
             echo json_encode(array(
                'tipo_error'       => $this->_tipoerror,
                'error_codmensaje' => $e->getCode(),
                'error_mensaje'    => $e->getMessage()
            ));
        }
    }


    public function editSector($data)
    {
        $variable = 1;

        $municipio  = $data['municipio'];
        $sector     = $data['sector'];
        $cod_sector = $data['cod_sector'];

        try {
            //if ($this->validar($fecha_nacimiento, 'fechanac') === FALSE) {
            if ($variable == 2) {
                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else {

                $data  = array('sector' => $sector, 'cod_municipio' => $municipio);
                $where = "cod_sector=$cod_sector";

                $update = (boolean) $this->update('sector', $data, $where);

                if ($update === TRUE) {
                    $this->_tipoerror = 'info';
                    $this->_mensaje   = 'El Registro ha sido  Modificado con exito';
                    $this->_cod_msg   = 22;
                    throw new Exception($this->_mensaje, $this->_cod_msg);
                } else {
                    $this->_tipoerror = 'error';
                    $this->_mensaje   = 'Ocurrio un error comuniquese con informatica';
                    $this->_cod_msg   = 16;
                    throw new Exception($this->_mensaje, $this->_cod_msg);
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

    public function deleteSector($datos)
    {

        $cod_sector = $datos['cod_sector'];

        $delete = $this->delete("sector", "cod_sector=$cod_sector");

        try {
            if ($delete === TRUE) {
                $this->_tipoerror = 'info';
                $this->_cod_msg   = 23;
                $this->_mensaje   = "El Registro ha sido Eliminado Exitosamente";
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

    public function getSectorAll()
    {
        $data   = array(
            'tabla'      => 'sector s, municipios m ',
            'campos'     => "m.codigo_municipio,m.municipio,s.cod_sector,s.sector",
            'condicion'  => 's.cod_municipio=m.codigo_municipio',
            'order'      => 'm.codigo_municipio'
        );
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getMunicipio()
    {
        $data   = array('tabla' => 'municipios', 'campos' => "codigo_municipio,municipio");
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getSector($codigo_municipio)
    {
        $data   = array('tabla' => 'sector', 'campos' => "cod_sector,sector", "condicion" => "cod_municipio='" . $codigo_municipio . "'");
        $result = $this->select($data, FALSE);
        return $result;
    }
}