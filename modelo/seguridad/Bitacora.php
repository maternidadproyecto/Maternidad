<?php
date_default_timezone_set("America/Caracas");
$path = dirname(__FILE__);
require_once "$path/Conexion.php";
class Bitacora extends Conexion
{
    protected $prefijo;
    protected $actividad;
    protected $cod_submodulo;
    public function __construct()
    {
        
    }
    
    protected function bitacorasSql()
    {
       
        $this->id_usuario    = $_SESSION['id_usuario'];
        $this->cod_submodulo = $_SESSION['cod_modulo'];
        if ($this->_rows_affected > 0) {

            $buscar    = " ";
            $pos       = strpos($this->_sql, $buscar);
            $sentencia = substr($this->_sql, 0, $pos);
            $search    = array("INSERT", "UPDATE", "DELETE");
            $remplazar = array("AGREGAR", "MODIFICAR", "ELIMINAR");
            $this->actividad = str_replace($search, $remplazar, $sentencia);
            switch ($this->actividad) {
                case 'AGREGAR':
                    $resultado     = TRUE;
                    $this->prefijo = 'R';
                break;
                case 'MODIFICAR':
                    $resultado = $this->_rows_affected;
                    $this->prefijo = 'M';
                break;
                case 'ELIMINAR':
                    $resultado = $this->_rows_affected;
                    $this->prefijo = 'E';
                break;
            }
            $fecha = date("Y-m-d H:i:s");
            $registro = array('actividad'=>$this->actividad,'cod_submodulo'=>$this->cod_submodulo,'accion'=>$sentencia,'col_afec'=>$this->_rows_affected);
            $regis = json_encode($registro);
            $data = array(
                            'id_usuario'=>$this->id_usuario,
                            'actividad'=>  $this->actividad,
                            'cod_submodulo'=>$this->cod_submodulo,
                            'sql_query'=>$this->_sql,
                            'accion'=>$sentencia,
                            'columnas_afectadas'=>$this->_rows_affected,
                            'fecha_hora'=>$fecha,
                            'registros'=>$regis
                         );
            $resultado   = parent::insert('b_sistema', $data);
            if ($resultado === TRUE) {
                $this->bitacoraUsuario();
            }
        }
    }
    
    
    protected function bitacoraUsuario()
    {
        $this->id_usuario = $_SESSION['id_usuario'];
        $search    = array("AGREGAR", "MODIFICAR", "ELIMINAR");
        $remplazar = array("REGISTR&Oacute;", "MODIFIC&Oacute;", "ELIMIN&Oacute;");
        $this->actividad = str_replace($search, $remplazar, $this->actividad);
        $data_usuario = array('id_usuario'=>$this->id_usuario,'prefijo'=>$this->prefijo,'actividad'=>  $this->actividad,'fecha' => date("Y-m-d H:i:s"),'cod_submodulo'=>  $this->cod_submodulo);
        $resultado   = parent::insert('b_usuario', $data_usuario);
        return $resultado;
        
    }
    protected function updateRegistro()
    {
        $datos_fecha = array('fecha_actualizacion' => date("Y-m-d H:i:s"));
        $resultado   = parent::update($this->table, $datos_fecha, $this->where);
        return $resultado;
        
    }
}
