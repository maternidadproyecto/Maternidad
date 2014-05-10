<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Validacion
 *
 * @author Josue
 */
class Validacion {

    public function __construct() {
        
    }
    public function validar($campo, $tipo) {
        switch ($tipo) {
            case 'letras':
                return $this->letras($campo);
            break;
            case 'orden':
                return $this->orden($campo);
            break;
        }
    }
    private function letras($campo) {
        $patron = '/^(:?[0-9a-zA-ZáéíóúÁÉÍÓÚüÜñÑ]|[0-9a-zA-ZáéíóúÁÉÍÓÚüÜñÑ]\s? [0-9a-zA-ZáéíóúÁÉÍÓÚüÜñÑ]){2,20}$/i';
        if (preg_match($patron, $campo)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    private function orden($campo) {
        $patron = '/^[0-9]{1,2}$/i';
        if (preg_match($patron, $campo)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function formateaBD($fecha) {
        $fechaesp = explode('/', $fecha);
        $revertirfecha = array_reverse($fechaesp);
        $fechabd = implode('-', $revertirfecha);
        return $fechabd;
    }
}
