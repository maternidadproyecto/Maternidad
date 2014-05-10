<?php
if (!defined('BASEPATH')) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
}
$path = dirname(__FILE__);
require_once "$path/Usuario.php";

class Login extends Usuario
{

    public function __construct()
    {
        
    }

    public function loginUser($datos)
    {
        session_start();
        $resultado = parent::loginUser($datos);
        // si el usuario existe en la base de datos
        if ($resultado !== FALSE) {
            $activo = (boolean) $resultado['activo'];
            // si el usuario esta activo
            if ($activo === TRUE) {
                
                $this->_id_usuario = $resultado['id_usuario'];
                $crear             = $this->crearSession();
                
                if ($crear === TRUE) {
                    $_SESSION['usuario']    = $this->_usuario;
                    $_SESSION['id_usuario'] = $this->_id_usuario;
                    $_SESSION['session_id'] = $this->_session_id;
                    $_SESSION['perfil']     = $resultado['codigo_perfil'];

                    $this->actividad     = 'Inici&oacute; Sesi&oacute;';
                    $this->cod_submodulo = 3;
                    $this->prefijo       = 'L';
                    parent::bitacoraUsuario();
                    return TRUE;
                }
            } else {
                return 12;
            }
        } else {
            return 14;
        }
    }

    public function logoutUsuario($id_usuario)
    {
        $this->_id_usuario = $_SESSION['id_usuario'];
       
        if (isset($_SESSION)) {
            
            $this->actividad     = 'Cerr&oacute; Sesi&oacute;';
            $this->cod_submodulo = 3;
            $this->prefijo       = 'L';
            parent::bitacoraUsuario();
            session_unset($_SESSION);
            session_destroy();
            $session_name = session_name();
            if (isset($_COOKIE[$session_name])) {
                setcookie(session_name(), '', time() - 3600, '/');
                parent::borrarSession($id_usuario);
                //parent::sessionActiva($id_usuario, 0);
                return TRUE;
            }
        }
    }

}
