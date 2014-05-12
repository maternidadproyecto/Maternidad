<?php

date_default_timezone_set("America/Caracas");
if (!defined('BASEPATH'))
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
/**
 * Clase para crear, modificar y eliminar
 * usuarios del sistema; autenticar y validar
 * la sesiones del usuario tanto como
 * inicio de sesión, como el cierre por
 * que la sessión expiro o el usuario
 * la cerró
 *
 * @autor Josue Aponte
 */
require_once 'Conexion.php';

class Autenticar  extends Conexion
{

    private $_siteKey;
    private $_id;
    private $_random;
    private $_salt;
    private $_clave;
    private $_password;
    private $_hashmac;
    private $_mysql;
    private $_token;
    private $_ip;
    private $_session_id;
    private $_fecha_session;
    private $_mensaje;
    private $_cod_msg;
    private $_ultimoid;
    private $_estatus;
    private $_perfil;

    public function __construct()
    {
        $this->_siteKey = 'M@t3N1D@d1Nt3Gr@lAra6u@';
    }

    // función para generar password aleatorios pra la validación de las sesiones
    // el tamaño por defecto es 50 aunque se puede cambiar el tamaño el llamar la funcion
    private function _randomString($length = 50)
    {

        // Iniciar con la variable password en blanco
        $password = "";

        // definir losposibles caracteres para generar el password aleatorio
        $possible = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ$%&#@";

        // iniciar el contador en 0
        $i = 0;

        // añadir caracteres aleatorios para el password($possible) hasta alcanzar la longitud ($length)
        while ($i < $length) {
            // pick a random character from the possible ones
            $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
            // we do not want this character if it\'s already in the password
            if (!strstr($password, $char)) {
                $password .= $char;
                $i++;
            }
        }
        // done!
        return $password;
    }

    // funcion que genera el hashid para el password
    private function _hashId($id)
    {
        $this->_id = hash("whirlpool", $id);
    }

    // generar la semilla para el password
    private function _hashSalt($clave)
    {
        $cad = "%*4!#$;.k~,-'(_@ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxys0123456789";
            $this->_salt = hash("sha224", $cad . $clave . $this->_id);
            return $this->_salt;
    }

// funcion para que encripta una cadena
    protected function hashClave($clave)
    {
        $this->_hashId($clave);
        $this->_hashSalt($this->_hashId($clave));
        $this->_clave   = $this->_id . $this->_salt . $clave;
        $this->_hashmac = hash_hmac('whirlpool', $this->_clave, $this->_siteKey);
        return $this->_hashmac;
    }

// funcion que encripta una cadena
    protected function hashData($data)
    {
        $data = trim($data);
        return hash_hmac('whirlpool', $data, $this->_siteKey);
    }

    // funcion de inicio de sesion de usuario
    public function loginUsuario($usuario, $clave)
    {
        /*if ($this->_isConectado($usuario) === TRUE) {

            // error de me avisa que el usuario ya esta conectado
            return 11;
        }*/
        $this->_password = $this->hashClave($clave);

        // consulta a la base de datos

        $data = array("tabla" => "usuario","campos"=>"id_usuario,activo,codigo_perfil", "condicion" => "BINARY usuario = '" . $usuario . "' AND clave = '" . $this->_password . "' AND salt_usuario = '" . $this->_salt . "'");
        $result = $this->row($data);
        // si el usuario existe en la base de datos
        if ($result !== FALSE) {

            $activo = (boolean) $result['activo'];
            // si el usuario esta activo
            if ($activo === TRUE) {

                $id_usuario = $result['id_usuario'];
                $crear = $this->_crearSession($id_usuario);

                //si el usuario creo la sesion
                if ($crear === TRUE) {
                    session_start();
                    //Setup sessions vars
                    $_SESSION['usuario']    = $usuario;
                    $_SESSION['id_usuario'] = $id_usuario;
                    $_SESSION['token']      = $this->_token;
                    $_SESSION['sesion_id']  = $this->_session_id;
                    $_SESSION['perfil']     = $result['codigo_perfil'];
                    return TRUE;
                } else {

                    // error que indica que no se crearon las sessiones para el usuario
                    return 13;
                }
            } else {
                // el usuario no esta activo
                return 12;
            }
        } else {
            // clave o usuario incorrecto
            return 14;
        }
    }

    private function _isConectado($usuario)
    {
        $conectado = $this->get("usuario", "conectado", "usuario = '" . $usuario . "'");
        return (boolean) $conectado;
    }

    public function logoutUsuario($id_usuario)
    {
        if (isset($_SESSION)) {
            session_unset($_SESSION);
            session_destroy();
            $session_name = session_name();
            if (isset($_COOKIE[$session_name])) {
                setcookie(session_name(), '', time() - 3600, '/');
                $this->_borrarSession($id_usuario);
                $this->_sessionActiva($id_usuario, 0);
                return TRUE;
            }
        }
    }

    private function _crearSession($id_usuario)
    {
        //Delete old logged_in_member records for user
        $delete = $this->_borrarSession($id_usuario);
        if ($delete === TRUE) {

            $this->_session_id = hash("ripemd128", md5(uniqid(rand(), true)));

            $this->_ip = $_SERVER['REMOTE_ADDR'];

            $this->_fecha_session = date('Y-m-d H:i');

            //First, generate a random string.
            $this->_random = $this->_randomString();

            //Build the token
            $this->_token = $_SERVER['HTTP_USER_AGENT'] . $this->_random;

            $this->_token = $this->hashData($this->_token);

            //Insert new logged_in_member record for user
            $data = array("id_usuario" => "$id_usuario", "session_id" => "$this->_session_id", "token" => "$this->_token", "fecha_session" => "$this->_fecha_session", "ip" => "$this->_ip");
            $insert = $this->insert('sesiones_activa', $data);

            if ($insert === TRUE) {
                return $this->_sessionActiva($id_usuario);
            }
        } else {
            return FALSE;
        }
    }

    private function _sessionActiva($id_usuario, $conectado = TRUE)
    {

        $data = array('conectado' => $conectado);
        $where = "id_usuario='$id_usuario'";
        return $this->update('usuario', $data, $where);
    }

    public function _verificarSesion($id_usuario)
    {

        if (!isset($_SESSION['sesion_id']) && !isset($_SESSION['token'])) {
            exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
        } else {
            $sesion_id = $_SESSION['sesion_id'];
            $token = $_SESSION['token'];
        }

        $fecha_session = $this->get("sesiones", "fecha_session", "id_usuario=$id_usuario AND session_id = '" . $sesion_id . "' AND token = '" . $token . "'");

        if ($fecha_session !== FALSE) {

            $fecha_session = date($fecha_session);
            $fecha_actual  = date('Y-m-d H:i');

            $diferencia = abs((strtotime($fecha_session) - strtotime($fecha_actual)) / 60);

            if ($diferencia >= 10 && $diferencia <= 20) {
                $update = $this->_actualizarSession($id_usuario);
                if ($update === TRUE) {
                    echo "actualizo";
                }
            } else if ($diferencia >= 20) {
                echo "borror y elimino session";
                $this->logoutUsuario($id_usuario);
            } else {
                echo "no ha pasado el tiempo limite";
            }
        } else {
            exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
        }
    }

    private function _actualizarSession($id_usuario)
    {

        $this->_session_id = hash("ripemd128", md5(uniqid(rand(), true)));

        $this->_ip = $_SERVER['REMOTE_ADDR'];

        $this->_fecha_session = date('Y-m-d H:i');

        //First, generate a random string.
        $this->_random = $this->_randomString();

        //Build the token
        $this->_token = $_SERVER['HTTP_USER_AGENT'] . $this->_random;

        $this->_token = $this->hashData($this->_token);

        //update the table (let's assume we have a pages table and we need to set the views of the page with + 1
        $data = array('session_id' => $this->_session_id, 'token' => $this->_token, 'fecha_session' => $this->_fecha_session, 'ip' => $this->_ip);

        $where = "id_usuario='$id_usuario'";

        $update = $this->update('sesiones', $data, $where);
        if ($update !== FALSE) {
            session_regenerate_id();
            $_SESSION['token'] = $this->_token;
            $_SESSION['sesion_id'] = $this->_session_id;
            return $update;
        }
    }

    private function _borrarSession($id_usuario)
    {

        //Delete old logged_in_member records for user
        $delete = $this->delete("sesiones_activa", "id_usuario='" . $id_usuario . "'");
        return $delete;
    }

    /*     * ********************************************************* */
 // validacion de Usuarios
    public function addUsuario($usuario, $clave, $perfil, $estatus = TRUE)
    {
        $usuario = strtolower($usuario);
        $existe = $this->recordExists("usuario", "usuario='" . $usuario . "'");

        try {
            if ($existe === TRUE) {
                // el usuario ya existe
                $this->_tipoerror = 'error';
                $this->_cod_msg   = 15;
                $this->_mensaje = 'Ya existe un Usuario con ese nombre';
            } else {
                $this->_ultimoid = $this->autoIncremet('usuario', 'id_usuario');
               
                $this->_password = $this->hashClave($clave);
                $this->_usuario  = $usuario;
                $this->_estatus  = (boolean) $estatus;
                $this->_perfil   = (int) $perfil;

                $sql = "INSERT INTO usuario(id_usuario,usuario,clave,salt_usuario,activo,codigo_perfil,fecha)VALUE($this->_ultimoid,'" . $this->_usuario . "','" . $this->_password . "','" . $this->_salt . "',$this->_estatus,$this->_perfil,CURRENT_DATE)";
                $resul = $this->execute($sql);

                if ($resul === TRUE) {
                    $this->_tipoerror = 'success';
                    $this->_cod_msg   = 21;
                    $this->_mensaje   = "El Registro ha sido Guardado Exitosamente";
                } else {
                    $this->_tipoerror = 'error';
                    $this->_cod_msg = 15;
                    $this->_mensaje = "Ocurrio un Error comuniquese con informatica";
                }
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
            return array('tipo_error' => $this->_tipoerror, 'error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage());
        }
    }

    public function getPerfil()
    {
        $data = array('tabla' => 'perfil', 'campos' => 'codigo_perfil,perfil');
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getModulos($id_usuario)
    {
        $data = array(
            'tabla'     => 'perfil p,perfil_priv_sub pps,sub_modulo sb ,modulo m,usuario u',
            'campos'    => 'm.cod_modulo,m.modulo',
            'condicion' => "u.id_usuario=$id_usuario AND m.activo=1 AND p.codigo_perfil=pps.codigo_perfil AND pps.cod_submodulo=sb.cod_submodulo AND sb.cod_modulo=m.cod_modulo AND p.codigo_perfil=u.codigo_perfil GROUP BY m.cod_modulo",
            'ordenar'   => 'm.posicion,m.cod_modulo ASC'
            );
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getSubModulos($id_usuarios, $cod_modulo)
    {
        $data = array(
            "tabla"     => "perfil p,perfil_priv_sub pps,sub_modulo sm ,modulo m,usuario u",
            "campos"    => "sm.sub_modulo,sm.ruta",
            "condicion" => "u.id_usuario=$id_usuarios AND sm.cod_modulo=$cod_modulo AND sm.activo=1 AND p.codigo_perfil=pps.codigo_perfil AND pps.cod_submodulo=sm.cod_submodulo AND sm.cod_modulo=m.cod_modulo AND p.codigo_perfil=u.codigo_perfil GROUP BY sm.sub_modulo,m.cod_modulo",
            "ordenar"   => "sm.posicion,sm.cod_submodulo"
            );
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getusuariosAll()
    {
        $data = array(
            "tabla"     => "usuario u,perfil p",
            "campos"    => "u.usuario,p.perfil,IF(u.activo=1,'Activo','Inactivo') AS estatus,DATE_FORMAT(u.fecha,'%d/%m/%Y') AS fecha",
            "condicion" => "u.codigo_perfil=p.codigo_perfil"
            );
        $result = $this->select($data, FALSE);
        return $result;
    }
}
?>