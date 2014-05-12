<?php

if (!defined('BASEPATH')){
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
}
require_once $_SERVER['DOCUMENT_ROOT'].'/Maternidad/FirePHP/fb.php';

$path   = dirname(__FILE__);
$modulo = 'cita';
$fin    = strpos($path, $modulo);
$path   = substr($path, 0, $fin);
require_once $path . 'paciente/Paciente.php';
class Asignar extends Paciente
{

    private $_mensaje;
    private $_cod_msg;
    private $_tipoerror;

    public function __construct()
    {
        $this->firephp = FirePHP::getInstance(true);
    }

    public function addAsignar($data)
    {

        try {
            $data['cedula_p'] = substr($data['cedula_p'],2);
            $fecha = $data['fecha'];
            $fecha = $this->formateaBD($fecha);
            $data['fecha'] = $fecha;
            $insert = $this->insert('cita', $data);
            if ($insert === TRUE) {
                $this->_cod_msg = 21;
                $this->_mensaje = "El Registro ha sido Guardado Exitosamente";
            }else{
                $this->_cod_msg   = 16;
                $this->_mensaje   = '<span style="color:#FF0000">Ocurrio un error comuniquese con informatica</span>';
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
            return array('error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage());
        }
    }

     public function BuscarDatos($datos)
     {
        $cedula_p = $datos['cedula_p'];
        $exi_ced  = $this->recordExists("paciente", "CONCAT_WS('-',nacionalidad,cedula_p)='" . $cedula_p . "'");
        if ($exi_ced === FALSE) {
            return $this->_cod_msg = FALSE;
        } else {

            $cant_citas = $this->get('consulta', 'COUNT(cedula_p)', "CONCAT_WS('-',nacionalidad,cedula_p) = '$cedula_p' AND fecha = CURRENT_DATE");

            if ($cant_citas == 1) {
                return 4;
            } else {
                $data['tabla']     = "paciente AS p";
                $data['campos']    = "p.nombre,p.apellido,CONCAT_WS('-', CONCAT('0',(SELECT codigo FROM codigo_telefono WHERE cod_telefono=p.cod_telefono)),p.telefono) AS telefono";
                $data['condicion'] = "CONCAT_WS('-',nacionalidad,cedula_p) = '$cedula_p'";
    
                $result       = $this->row($data);
                $tabla_row     = "(SELECT ci.nacionalidad,ci.cedula_p, ci.fecha FROM cita AS ci UNION SELECT co.nacionalidad,co.cedula_p,co.fecha FROM consulta AS co) AS ci";
                $campo_row     = "ci.fecha";
                $condicion_row = "CONCAT_WS('-',ci.nacionalidad,ci.cedula_p) = '$cedula_p'";
                $total         = $this->numRows($tabla_row, $campo_row, $condicion_row);
                $total =(int)$total;
                if ($total == 0) {
                    $result['total']      = 0;
                    $result['asistencia'] = 0;
                    $result['fech_max']   = 0;
                } else {
                    if ($total == 1) {
                        $cita = $this->get('cita', 'fecha', "CONCAT_WS('-',nacionalidad,cedula_p) = '$cedula_p'");
                        if($cita !== FALSE){
                            $asistencia = 0;
                        }else{
                            $asistencia = 1;
                        }
                        $result['total']      = $total;
                        $result['asistencia'] = $asistencia;
                        $result['fech_max']   = 0;
                    } else {
                        $tabla_row_tot = "(SELECT ci.nacionalidad,ci.cedula_p, ci.fecha FROM cita AS ci UNION SELECT co.nacionalidad,co.cedula_p,co.fecha FROM consulta AS co) AS ci";
                        $campo_row_tot = "ci.fecha";
                        $condicion_row = "CONCAT_WS('-',nacionalidad,cedula_p) = '$cedula_p' AND ci.fecha > CURRENT_DATE()";
                        $fech_total = $this->numRows($tabla_row_tot,$campo_row_tot , $condicion_row);
                        
                        $tabla_asi     = "consulta";
                        $campo_asi     = "asistencia";
                        $condicion_asi = "CONCAT_WS('-',nacionalidad,cedula_p) = '$cedula_p' AND asistencia = 1 AND fecha < CURRENT_DATE()";
                        
                        $asistencia = $this->numRows($tabla_asi, $campo_asi, $condicion_asi);
                        
                        $result['total']      = $total;
                        $result['asistencia'] = $asistencia;
                        $result['fech_max']   = $fech_total;
                    }
                }
                
                $sql = "SELECT 
                            DATEDIFF(ci.fecha,CURRENT_DATE) dias, 
                            DATE_FORMAT(ci.fecha,'%d-%m-%Y') AS fecha,
                            co.consultorio,
                            ci.turno,
                            ci.asistencia,
                            ci.observacion  
                        FROM 
                        (
                        SELECT ci.nacionalidad,ci.cedula_p,ci.fecha,num_consultorio,turno,'0' AS asistencia,'' AS observacion FROM cita AS ci 
                        UNION 
                        SELECT co.nacionalidad,co.cedula_p, co.fecha,num_consultorio,turno,co.asistencia,co.observacion FROM consulta AS co 
                        ) AS ci
                        INNER JOIN consultorio AS co ON ci.num_consultorio=co.num_consultorio
                        WHERE CONCAT_WS('-',nacionalidad,cedula_p)='$cedula_p' 
                        ORDER BY ci.fecha 
                        DESC LIMIT 200";
        
                $datos_resul = "";
                $result1 = $this->ex_query($sql);
                $es_array = is_array($result1) ? TRUE : FALSE;
                
                if ($es_array == TRUE) {
                    for ($j = 0; $j < count($result1); $j++) {
                        $datos_resul .= $result1[$j]['dias'] . ';' . $result1[$j]['asistencia'] . ';' . $result1[$j]['fecha'] . ';' . $result1[$j]['consultorio'] . ';' . $result1[$j]['turno'] . ';' . $result1[$j]['observacion'] . ',';
                    }
                    $datos_resul = substr($datos_resul, 0, -1);
                    $result['citas'] = $datos_resul;
                }else{
                    $result['citas'] = 0;
                }
                
                return $result;
            }
        }
    }
    
    public function BuscarCitas($datos)
    {  
        $cedula_p = $datos['cedula_p'];
        $sql = "SELECT 
                    DATEDIFF(ci.fecha,CURRENT_DATE) dias, 
                    DATE_FORMAT(ci.fecha,'%d-%m-%Y') AS fecha,
                    co.consultorio,
                    ci.turno,
                    ci.asistencia,
                    ci.observacion  
                FROM 
                (
                SELECT ci.cedula_p,ci.fecha,num_consultorio,turno,'0' AS asistencia,'' AS observacion FROM cita AS ci 
                UNION 
                SELECT co.cedula_p, co.fecha,num_consultorio,turno,co.asistencia,co.observacion FROM consulta AS co 
                ) AS ci
                INNER JOIN consultorio AS co ON ci.num_consultorio=co.num_consultorio
                WHERE ci.cedula_p=$cedula_p 
                ORDER BY ci.fecha 
                DESC LIMIT 200";
        
        $result = $this->ex_query($sql);
        return $result;
    }
    
    public function EliminarCita($datos)
    {
        $cedula_p = $datos['cedula_p'];
        $fecha = $datos['fecha'];
        $fecha = $this->formateaBD($fecha);
        
        $resultado = $this->delete('cita', "CONCAT_WS('-',nacionalidad,cedula_p)='$cedula_p' AND fecha='$fecha'");
        
        if($resultado === TRUE){
            return TRUE;
        }else{
            return FALSE;
        }
  
    }
    
    public function CancelarCita($datos)
    {
        $cedula_p    = $datos['cedula_p'];
        $observacion = $datos['observacion'];
        $sql         = "INSERT INTO consulta(nacionalidad,cedula_p,fecha,num_consultorio,observacion) 
                     SELECT nacionalidad,cedula_p,fecha,num_consultorio,'$observacion' FROM cita WHERE CONCAT_WS('-',nacionalidad,cedula_p)='$cedula_p'";
        $result      = $this->execute($sql);
     
        try {
            if ($result === TRUE) {
                $where     = "CONCAT_WS('-',nacionalidad,cedula_p)='$cedula_p'";
                $resul_del = $this->delete('cita', $where);
                if ($resul_del === TRUE) {
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

    public function editEspecialidad($cod_esp, $especialidad)
    {
        try {

            if ($this->validar($especialidad, 'especialidad') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else {
                $data  = array('especialidad' => $especialidad);
                $where = "cod_esp='$cod_esp'";

                $update = (boolean) $this->update('especialidad', $data, $where);

                if ($update === TRUE) {
                    $this->_tipoerror = 'info';
                    $this->_mensaje   = 'Modificacion con exito';
                    $this->_cod_msg   = 22;
                    throw new Exception($this->_mensaje, $this->_cod_msg);
                } else {
                    $this->_tipoerror = 'error';
                    $this->_mensaje   = 'Ocurrio un error comuniquese con informatica';
                    $this->_cod_msg   = 16;
                    throw new Exception($this->_mensaje, $this->_cod_msg);
                }
            }
        } catch (Exception $e) {
            echo json_encode(array('error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage()));
        }
    }


    public function getAsignarAll()
    {
        $data   = array(
            'tabla'  => 'cita',
            'campos' => "cedula_p, num_consultorio, fecha"
            );
        $result = $this->select($data, FALSE);
        return $result;
    }
    public function getConsultorio()
    {
        $data   = array(
            'tabla'  => 'consultorio c',
            'campos' => "c.num_consultorio,c.consultorio "
            );
        $result = $this->select($data, FALSE);
        return $result;
    }
    
//    public function getpaciente()
//    {
//        $data   = array(
//            'tabla'  => 'consultorio c',
//            'campos' => "c.num_consultorio,c.consultorio "
//            );
//        $result = $this->select($data, FALSE);
//        return $result;
//    }
    
    public function getCita($datos)
    {
        if(!isset($datos['sql'])){
            $cedula_p = $datos['cedula_p'];
            $data['tabla']     = "cita as c";
            $data['campos']    = "c.fecha,(SELECT consultorio FROM consultorio WHERE num_consultorio=c.num_consultorio) AS consultorio,c.turno";
            $data['condicion'] = "CONCAT_WS('-',c.nacionalidad,c.cedula_p) = '$cedula_p'";
            $result = $this->row($data);
        }else{
             $result = $this->ex_query($datos['sql']);
        }
        return $result;
    }
    public function getDatosCons($datos)
    {

        $num_consultorio = $datos['num_consultorio'];
        $data = array(
            'tabla'     => "consultorio",
            'campos'    => "manana,tarde",
            'condicion' => "num_consultorio=$num_consultorio"
        );
        $result = $this->row($data);
        return $result;
    }
}