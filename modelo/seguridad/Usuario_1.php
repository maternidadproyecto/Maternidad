<?php
$path = dirname(__FILE__);
require_once "$path/Seguridad.php";
class Usuario extends Seguridad
{
    public function __construct() {
        
    }
    
    public function clave($clave)
    {
        $resultado = parent::LoginUser($clave);
        return $resultado;
    }
}