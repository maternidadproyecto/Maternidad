<?php
$path = dirname(__FILE__);
require_once "$path/Modulo.php";
class SubModulo extends Modulo
{
    
    private $_cod_modulo;
    private $_cod_submodulo;
    protected $_id_usuario;
    protected $_menu = 1;
    protected $_ultimo = array();
    private $_where = FALSE;
    private $_ac_sql = FALSE;
    protected $_sql;
    public function __construct()
    {
        $this->table = 's_sub_modulo';
    }
    
    private function _addSubModulo()
    {
        $this->bitacora = TRUE;
        $resultado   = parent::add();
        return $resultado;
    }

    public function addSubModulo($datos)
    {   

        $where_mod   = "sub_modulo='" . $datos['sub_modulo'] . "' AND cod_modulo=".$datos['cod_modulo']."";
        $where_pos   = "cod_modulo='". $datos['cod_modulo'] ."' AND posicion='" . $datos['posicion'] . "'";

        $exis_mod = parent::recordExists($this->table,$where_mod);
        $exis_pos = parent::recordExists($this->table,$where_pos);
        try {
            if ($exis_mod === TRUE) {
               $this->_cod_msg = 15;
               $this->_mensaje = "<span style='color:#FF0000'>El Nombre del Sub Modulo ya existe</span>";
            }else if($exis_pos === TRUE){
               $this->_cod_msg = 16;
               $this->_mensaje = "<span style='color:#FF0000'>La Posici&oacute;n del Sub Moduo en el Men&uacute; ya existe</span>";
            }else{
               
                $this->a_datos = $datos;
                $this->camp_auto = 'cod_submodulo';
                $this->increment = TRUE;

                $resultado = $this->_addSubModulo(); 
                if($resultado === TRUE){
                    $this->_cod_msg = 21;
                    $this->_mensaje = "El Registro ha sido Guardado Exitosamente";
                }
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
           return array('error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage());
        }
    }
    
    private function _editSubModulo()
    {
        $resultado   = parent::mod();
        return $resultado;
    }
    
    public function editSubModulo($datos)
    {
        
        try {
            $cod_submodulo = array_shift($datos);
            $this->where   = "cod_submodulo=$cod_submodulo";
            $this->a_datos = $datos;
            $resultado     = $this->_editSubModulo();
            if ($resultado === TRUE || $resultado > 0) {
                $this->_cod_msg   = 22;
                $this->_mensaje   = "El Registro ha sido Modificado Exitosamente";
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
            return array('error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage());
        }
    }

    private function _delSubModulo()
    {
        $resultado   = parent::del();
        return $resultado;
    }
    public function delSubModulo($data)
    {
        
        try {
            $this->where   = "cod_submodulo=".$data['cod_submodulo'];
            $this->r_affec = TRUE;
            $resultado = $this->_delSubModulo();
            if ($resultado === TRUE || $resultado > 0) {
                $this->_cod_msg   = 23;
                $this->_mensaje   = "El Registro ha sido Eliminado Exitosamente";
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
            return array('error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage());
        }
    }
    
    protected function getSubModulos()
    {
        
        if($this->_menu === 1){ 
        $data = array(
            'tabla'     => 's_perfil_privilegio AS spp,s_usuario AS su,s_sub_modulo AS ssm',
            'campos'    => 'ssm.cod_submodulo,ssm.cod_modulo,ssm.sub_modulo,ssm.ruta',
            'condicion' => "su.id_usuario=$this->_id_usuario AND  ssm.cod_modulo=$this->_cod_modulo AND ssm.activo = 1 AND spp.codigo_perfil=su.codigo_perfil AND spp.cod_submodulo=ssm.cod_submodulo GROUP BY ssm.sub_modulo,ssm.cod_modulo",
            'ordenar'   => 'ssm.posicion,ssm.cod_submodulo ASC'
            );
        }else{
            $data = array('tabla' => 's_sub_modulo','campos'=>  $this->_campos,'condicion'=>$this->_where);
        }
        
        if($this->_ac_sql == TRUE){
            $result = parent::ex_query($this->_sql);
        } else {
            $result = parent::select($data, FALSE);
            parent::autoIncremet($this->table, 'cod_submodulo');
        }
        if($result === 0){
            return $this->auto_incremenet;
        }else {
            $this->_ultimo = array('ul_codsubmodulo'=>$this->auto_incremenet);
            return $result;
        }
    }
    public function getSubModulo($datos)
    {   

        $default       = array('campos' => '*');
        $options       = array_merge($default, $datos);
        $this->_campos = $options['campos'];
        if(!empty($datos['id_usuario'])){
            $this->_id_usuario = $datos['id_usuario'];
        }
        if(isset($datos['menu'])){
            $this->_menu = $datos['menu'];
        }   
        if(!empty($datos['cod_modulo'])){
            $this->_cod_modulo = $datos['cod_modulo'];
            $this->_where = 'cod_modulo='.$this->_cod_modulo;
        }
        if(!empty($datos['cod_submodulo'])){
            $this->_cod_submodulo = $datos['cod_submodulo'];
            $this->_where         = 'cod_submodulo=' . $this->_cod_submodulo;
        }
        if(isset($datos['sql'])){
            $this->_ac_sql = TRUE;
            $this->_sql    = $datos['sql'];
        }
        $result =  $this->getSubModulos();
        $es_array  = is_array($result) ? TRUE : FALSE;
        $es_int    = is_int($result) ? TRUE : FALSE;
        
        
        if ($this->_menu == 0) {
            if($es_array == TRUE && $es_int == FALSE){
                $result = array_merge($result, array('ultimo' => $this->auto_incremenet));
            }else{
                $result = $this->auto_incremenet;
            }
            return $result;
        } else {
            return $result;
        }
    }
}
