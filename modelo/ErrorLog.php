<?php

date_default_timezone_set('America/Caracas');

class ErrorLog
{

    private $_ruta;
    static protected $cod_error;
    private static $_instance;
    private $_mostrar = TRUE;

    private function __construct()
    {
    }

    private function __wakeup()
    {

    }

    public static function instaciar()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function activeError($mostrar = TRUE)
    {
        $this->_mostrar = $mostrar;
        ini_set('error_reporting', E_ALL | E_STRICT);
        ini_set('log_errors', TRUE);
        ini_set('html_errors', TRUE);
        ini_set('display_errors', $this->_mostrar);
    }

    private function _error($numero, $texto)
    {
        $this->_ruta = dirname(__FILE__) . '\errores\error_log.log';
        $ddf         = fopen($this->_ruta, "a+");
        fwrite($ddf, "[" . date("d/m/Y h:i A") . "] Error $numero:$texto");
        fclose($ddf);
    }

    protected function _chequearError($codi_error)
    {
        self::$cod_error = $codi_error;
        switch (self::$cod_error) {
            case 1049:
                $this->_error(self::$cod_error, "No se existe la Base de Datos\n");
            break;
            case 2005:
                $this->_error(self::$cod_error, "No se existe el servidor\n");
            break;
            case 1045:
                $this->_error(self::$cod_error, "Acceso denegado para el Usuario\n");
            break;
            case 1044:
                $this->_error(self::$cod_error, "Acceso denegado para el Usuario\n");
            break;
        }
    }
}