<?php

if (!defined('BASEPATH')) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/Maternidad/FirePHP/fb.php';

$path   = dirname(__FILE__);
$modulo = 'paciente';
$fin    = strpos($path, $modulo);
$path   = substr($path, 0, $fin);
require_once $path . 'cita/Asignar.php';

class Historia extends Paciente
{

    private $_mensaje;
    private $_cod_msg;

    public function __construct()
    {
        $this->firephp = FirePHP::getInstance(true);
    }

    public function addhistoria($data)
    {
        
        try {
            
            $nacionalidad    = $data['nacionalidad'];
            $dat_ced         = explode('-',$data['cedula_p']);
            $cedula_p        = $data['cedula_p'];
            $cedula_pm       = $data['cedula_pm'];
            $historia        = $data['historia'];
            $lugar_control   = $data['lugar_control'];
            $tamano          = $data['tamano'];
            $peso            = $data['peso'];
            $tension         = $data['tension'];
            $fur             = $data['fur'];
            $fpp             = $data['fpp'];
            $diagnostico     = $data['diagnostico'];
            $observacion     = $data['observacion'];
            $num_consultorio = $data['num_consultorio'];

            $cita_asistida = $this->numRows('consulta', 'cedula_p', "CONCAT_WS('-',nacionalidad,cedula_p)='$cedula_p' AND asistencia=1");
            $fur = $this->formateaBD($fur);
            $fpp = $this->formateaBD($fpp);
            if ($cita_asistida > 0) {
                $data_up['ps'] = 'S';
            } else {
                $data_up['fur'] = $fur;
                $data_up['fpp'] = $fpp;
            }
            $data_up['lugar_control'] = $lugar_control;
            $data_up['historia'] = $historia;
            $where   = "CONCAT_WS('-',nacionalidad,cedula_p) = '$cedula_p'";

            $resul_up = $this->update('paciente', $data_up, $where);
            if ($resul_up == 1) {
                $sql          = "INSERT INTO consulta(nacionalidad,cedula_p,fecha,num_consultorio,turno,tamano,peso,tension,diagnostico,observacion_medica,asistencia,cedula_pm)
                        SELECT nacionalidad,cedula_p,fecha,$num_consultorio,turno,'$tamano','$peso','$tension','$diagnostico','$observacion',1,$cedula_pm FROM cita WHERE CONCAT_WS('-',nacionalidad,cedula_p) = '$cedula_p'";
                $result_inser = $this->execute($sql);
                if ($result_inser == 1) {
                    $resul_del = $this->delete('cita', $where);
                    if ($resul_del === TRUE) {
                        $exis_hist = $this->recordExists('historia', "CONCAT_WS('-',nacionalidad,cedula_p) = '$cedula_p'");
                        if ($exis_hist === FALSE) {
                            $data_his['historia']     = $historia;
                            $data_his['nacionalidad'] = $dat_ced[0];
                            $data_his['cedula_p']     = $dat_ced[1];
                            $this->insert('historia', $data_his);
                        }
                        $this->_cod_msg = 21;
                        $this->_mensaje = "El Registro ha sido Guardado Exitosamente";
                    }
                }
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
            return array('error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage());
        }
    }

    public function BuscarDatos($data)
    {

        $cedula_p = $data['cedula_p'];
        $exi_ced  = $this->recordExists("paciente", "CONCAT_WS('-',nacionalidad,cedula_p)='" . $cedula_p . "'");

        if ($exi_ced === FALSE) {
            return FALSE;
        } else {
            
            $table_row     = ' paciente AS p';
            $campos_row    = "IFNULL((SELECT historia FROM historia WHERE CONCAT_WS('-',nacionalidad,cedula_p) =  CONCAT_WS('-',p.nacionalidad,p.cedula_p)),0) AS historia,
                                p.nombre,
                                p.apellido,
                                DATE_FORMAT(p.fecha_nacimiento,'%d-%m-%Y') AS fecha_nacimiento,
                                CONCAT(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(p.fecha_nacimiento)), '%Y')+0,' Años') AS edad,
                                IF(ISNULL(p.fur),NULL,DATE_FORMAT(p.fur,'%d-%m-%Y')) AS fur,
                                IF(p.fpp=NULL,NULL,DATE_FORMAT(p.fpp,'%d-%m-%Y')) AS fpp,
                                p.lugar_control, 
                                (SELECT COUNT(cedula_p) FROM consulta WHERE CONCAT_WS('-',nacionalidad,cedula_p) =  CONCAT_WS('-',p.nacionalidad,p.cedula_p)) AS total_consulta,
                                IF((
                                SELECT COUNT(cedula_p) FROM consulta WHERE CONCAT_WS('-',nacionalidad,cedula_p) =  CONCAT_WS('-',p.nacionalidad,p.cedula_p)) < 1,0,
                                IFNULL((SELECT historia FROM historia WHERE CONCAT_WS('-',nacionalidad,cedula_p) =  CONCAT_WS('-',p.nacionalidad,p.cedula_p)),0)
                                ) AS new_historia,
                                IF((SELECT fecha FROM cita WHERE CONCAT_WS('-',nacionalidad,cedula_p) = CONCAT_WS('-',p.nacionalidad,p.cedula_p))<= CURRENT_DATE,'menor','mayor') AS ultima_cita,
                                IFNULL((
                                SELECT CONCAT_WS(';',c.tamano,c.peso,c.tension,c.diagnostico,c.observacion_medica) FROM consulta AS c
                                WHERE CONCAT_WS('-',nacionalidad,cedula_p) =  CONCAT_WS('-',p.nacionalidad,p.cedula_p) ORDER BY c.fecha DESC LIMIT 1
                                ),0) AS datos,
                                DATE_FORMAT(IFNULL((
                                DATE_FORMAT((SELECT fecha FROM cita WHERE CONCAT_WS('-',nacionalidad,cedula_p) = CONCAT_WS('-',p.nacionalidad,p.cedula_p)),'%d-%m-%Y')
                                ),
                                (
                                SELECT c.fecha FROM consulta AS c
                                WHERE CONCAT_WS('-',nacionalidad,cedula_p) =  CONCAT_WS('-',p.nacionalidad,p.cedula_p) ORDER BY c.fecha DESC LIMIT 1
                                )
                                ),'%d-%m-%Y')AS fecha,
                                IFNULL((SELECT fecha FROM cita WHERE CONCAT_WS('-',nacionalidad,cedula_p) = CONCAT_WS('-',p.nacionalidad,p.cedula_p)),0)AS existe_cita
                                ";
            $condicion_row = "CONCAT_WS('-',p.nacionalidad,p.cedula_p) = '$cedula_p'";
            $ordenar_row   = "ORDER BY 1 LIMIT 1";

            $data = array('tabla' => $table_row, 'campos' => $campos_row, 'condicion' => $condicion_row,$ordenar_row);
            
            $result = $this->row($data);
            $historia      = '';
            if ($result['historia'] == 0 && $result['total_consulta'] == 1) {
                $data_his['tabla']   = 'historia';
                $data_his['campos']  = 'historia';
                $data_his['ordenar'] = 'historia DESC';

                $result_his = $this->row($data_his);

                $historia = str_pad((int) str_replace('-', '', $result_his['historia']) + 1, 6, '0', STR_PAD_LEFT);
                $historia = substr(chunk_split($historia, 2, '-'), 0, -1);
            }else if($result['historia'] == 0 && $result['total_consulta'] == 0){
                $historia = '';
            }else{
                $cita_asistida = 0;
                $historia      = $result['historia'];
            }
            $datos['historia'] = $historia;
            $datos['nombre'] = $result['nombre'];
            $datos['apellido'] = $result['apellido'];
            $datos['fecha_nacimiento'] = $result['fecha_nacimiento'];
            $datos['edad'] = $result['edad'];
            $datos['fur'] = $result['fur'];
            $datos['fpp'] = $result['fpp'];
            $datos['lugar_control'] = $result['lugar_control'];
            
            $datos['fecha_ultima_cita'] = $result['fecha'];
            $datos['datos'] = $result['datos'];
            $datos['ultima_cita'] = $result['ultima_cita'];
            $datos['existe_cita'] = $result['existe_cita'];
           
        }
           
        return $datos;
    }

    public function edithistoria($cedula_p, $historia, $lugar_control, $fur, $fpp, $diagnostico, $observacion)
    {
        try {

            if ($this->validar($lugar_control, 'lugarcontrol') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else if ($this->validar($fur, 'fur') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else if ($this->validar($fpp, 'fpp') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else if ($this->validar($diagnostico, 'diagnostico') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else if ($this->validar($observacion, 'observacion') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else {
                $data  = array('lugar_control' => $lugar_control, 'fur' => $fur, 'fpp' => $fpp, 'diagnistico' => $diagnostico, 'observacion' => $observacion);
                $where = "cedula_p='$cedula_p'";
                $update = (boolean) $this->update('historia', $data, $where);

                if ($update === TRUE) {
                    $this->_mensaje = 'Modificacion con exito';
                    $this->_cod_msg = 501;
                }
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
            echo json_encode(array('error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage()));
        }
    }

    public function deletehistoria($cedula_p)
    {
        $delete = $this->_mysql->delete("historia", "cedula_p=$cedula_p");

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

    public function gethistoriaAll()
    {
        $data   = array('tabla' => 'historia');
        $result = $this->_mysql->select($data, FALSE);
        return $result;
    }

}
