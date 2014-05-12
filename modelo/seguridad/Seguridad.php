<?php

date_default_timezone_set("America/Caracas");
$path = dirname(__FILE__);
require_once "$path/Bitacora.php";
class Seguridad extends Bitacora
{
    private $_siteKey = 'M@t3N1D@d1Nt3Gr@lAra6u@';
    private $_hashmac;
    protected $_clave;
    protected $_usuario;
    protected $_id_usuario;
    protected $_session_id ;
 
    protected $table;
    protected $a_datos;
    protected $increment = FALSE;
    protected $camp_auto;
    protected $datos;
    protected $where;
    protected $bitacora = FALSE;
    public function __construct()
    {
        $this->id_usuario = $_SESSION['id_usuario'];
    }
    
    protected function add()
    {

        session_start();
        $this->id_usuario    = $_SESSION['id_usuario'];
        $this->cod_submodulo = $_SESSION['cod_modulo'];
        $array_fecha = array('fecha_creacion' => date("Y-m-d H:i:s"), 'fecha_actualizacion' => date("Y-m-d H:i:s"),'id_usuario_f'=>$this->id_usuario);
        $data        = array_merge($this->a_datos, $array_fecha);
        $resultado   = parent::insert($this->table, $data);
 
        if($resultado === TRUE){
             $resul_bi = parent::bitacorasSql();
             if($resul_bi === TRUE){
                $this->prefijo = 'A';
                parent::bitacoraUsuario();
             }
        }
        return $resultado;
    }
    
    protected function mod()
    {
        session_start();
        $this->id_usuario    = $_SESSION['id_usuario'];
        $this->cod_submodulo = $_SESSION['cod_modulo'];

        $resultado   = parent::update($this->table, $this->a_datos,  $this->where);
        if($resultado > 0){
             $result_b = parent::bitacorasSql();
             if($result_b === TRUE){
                parent::bitacoraUsuario();
             }
             parent::updateRegistro();
        }
        return $resultado;
    }
    
    protected function del()
    {
        session_start();
        $this->id_usuario    = $_SESSION['id_usuario'];
        $this->cod_submodulo = $_SESSION['cod_modulo'];
        $resultado   = parent::delete($this->table, $this->where);
        if($resultado > 0){
             $result_b = parent::bitacorasSql();
             if($result_b === TRUE){
                parent::bitacoraUsuario();
             }
        }
        return $resultado;
    }
    
    private function _HahsClave()
    {
        $this->_hashmac = hash_hmac('whirlpool', $this->_clave, $this->_siteKey);
        return $this->_hashmac;
    }
    
    private function _searchSession()
    {
        //Buscar registros del usuario logueado

        $resultado = $this->numRows('s_sesion_activa', 'id_usuario',"id_usuario=$this->_id_usuario");
        return $resultado;
    }
    
    protected function loginUser($datos)
    {
        $usuario        = $datos['usuario']; 
        $this->_usuario = $usuario;
        $clave          = $datos['clave'];
        $this->_clave   = $this->clave($clave);
        // consulta a la base de datos
        $data = array("tabla" => "s_usuario","campos"=>"id_usuario,activo,codigo_perfil", "condicion" => "BINARY usuario = '" . $this->_usuario . "' AND clave = '" . $this->_clave . "'");
        $result = $this->row($data);
        return $result;
    }
    
    protected function clave($clave)
    {
        $this->_clave = $clave;
        return $this->_HahsClave();
    }

    
    private function _crearSession()
    {
            $this->_session_id = hash("sha1", md5(uniqid(rand(), true)));
            
            $ip = $_SERVER['REMOTE_ADDR'];
            
            $fecha_session = date('Y-m-d H:i');
            
            $data = array("id_usuario" => $this->_id_usuario, "session_id" => $this->_session_id ,  "fecha_session" => $fecha_session, "ip" => $ip);
            $insert = $this->insert('s_sesion_activa', $data);
            if($insert === TRUE){
                $data_ac = array('conectado'=>1); 
                $this->update('s_usuario',$data_ac , "id_usuario=$this->_id_usuario");
                return TRUE;
            }else{
                return FALSE;
            }
    }
    
    protected function crearSession()
    {
        
        
        //Buscar registros del usuario logueado
        $resultado = $this->_searchSession();
        if($resultado == 0){
           //Crear registros del usuario logueado
           $resultado =  $this->_crearSession();
        }else{
            //Borrar registros del usuario logueado
            $borrar = $this->_borrarSession();
            if ($borrar === TRUE) {
                //Crear registros del usuario logueado
                $resultado =  $this->_crearSession();
            }
        }
       return $resultado;
    }
    
    
     private function _sessionActiva($id_usuario, $conectado = TRUE)
    {

        $data = array('conectado' => $conectado);
        $where = "id_usuario='$id_usuario'";
        return $this->update('usuario', $data, $where);
    }

    protected function sessionActiva(){
        
    }


    private function _borrarSession()
    {
        //Eliminar registros del usuario logueado
        $delete = $this->delete("s_sesion_activa", "id_usuario='" . $this->_id_usuario . "'");
        return $delete;
    }
    
    protected function borrarSession(){
        $resul = $this->_borrarSession();
        return $resul;
    }

    public function url($url,$modulo)
    {
        
        $dividir_ruta         = explode("Maternidad/", $url);
        $ruta                 = $dividir_ruta[1];
        $_SESSION['url']      = $ruta;
        $_SESSION['s_modulo'] = $modulo;
    }
    public function formateaBD($fecha) {
        $fechaesp = preg_split("/[\-\/]/", $fecha);
        $revertirfecha = array_reverse($fechaesp);
        $fechabd = implode('-', $revertirfecha);
        return $fechabd;
    }
}