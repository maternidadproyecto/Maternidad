<?php

if (!defined('BASEPATH')) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
}


$path   = dirname(__FILE__);
$modulo = 'mantenimientos';
$fin    = strpos($path, $modulo);
$path   = substr($path, 0, $fin);
require_once $path.'seguridad/Seguridad.php';

class Sector extends Seguridad
{

    private $_mensaje;
    private $_cod_msg;
    private $_tipoerror;

    public function __construct()
    {

    }

    public function addSector($data)
    {

       
        try {
            $municipio = $data['cod_municipio'];
            $sector    = $data['sector'];

            $exi_sect = $this->recordExists("sector", "sector='" . $sector . "' AND cod_municipio=$municipio");
            if ($exi_sect === TRUE) {
                $this->_cod_msg = 15;
                $this->_mensaje = '<span style="color#FF0000">El Sector se encuentra Registrado</span>';
            } else {

                $this->cod_sector = $this->autoIncremet('sector', 'cod_sector');
                $cod_sector = array('cod_sector'=>$this->auto_incremenet);
                $data       = array_merge($cod_sector,$data);

                $insert = $this->insert('sector', $data);
                if ($insert === TRUE) {
                    $this->_cod_msg = 21;
                    $this->_mensaje = "El Registro ha sido Guardado Exitosamente";
                }
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
              return array('error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage());
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
            'order'      => 's.cod_sector '
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