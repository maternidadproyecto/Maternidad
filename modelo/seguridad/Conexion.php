<?php

class Conexion
{

    private static $_server   = 'localhost';
    private static $_user     = 'root';
    private static $_password = '';
    protected $bd            = 'maternidad';
    private $_conn            = NULL;
    private $_state_conn      = FALSE;
    private $_res             = NULL;
    private $_state_query     = FALSE;
    private static $_instancia;
    
    protected $auto_incremenet = 0;
    protected $r_affec = FALSE;
    protected $_rows_affected;
    protected $_sql = '';
    private function __construct()
    {

    }

    private function __wakeup()
    {

    }

    public static function crear()
    {
        if (!(self::$_instancia instanceof self)) {
            self::$_instancia = new self();
        }
        return self::$_instancia;
    }

    private function _open_conn()
    {
        // Activar los errores para mostrarlos por pantallas

        $this->_conn= @new mysqli(self::$_server, self::$_user, self::$_password, $this->bd);
        if ((int) $this->_conn->connect_errno > 0) {
            echo utf8_decode("<div style='color:#FF0000;text-align:center;margin:0 auto'>" . $this->_conn->connect_error . "</div>");
            exit(utf8_decode("<div style='color:#FF0000;text-align:center;margin:0 auto'>Ocurrió un Error Comuniquese con informatica</div>"));
        } else {
            $this->_state_conn = TRUE;
            $this->_conn->set_charset('utf8');
            return $this->_conn;
        }
    }
   
    protected function execute_query($sql, $tipo)
    {
        
        $buscar      = " ";
        $buscar_tipo = ":";
        $pos         = strpos($sql, $buscar);
        $sentencia   = substr($sql, 0, $pos);
        $ejecutar    = FALSE;
        $tipo        = trim($tipo);
        $count       = strlen($tipo);
        $existe_dos  = FALSE;
        $busqueda    = strstr($tipo, $buscar_tipo);
        $type        = MYSQLI_BOTH;
        $type_result = TRUE;
        if ($busqueda) {
            $existe_dos  = TRUE;
            $pos         = strpos($tipo, $buscar_tipo);
            $sen         = substr($tipo, 0, $pos);
            $tipo_select = substr($tipo, $pos + strlen($buscar_tipo));
        }
        if ($sentencia === 'INSERT' || $sentencia === 'UPDATE' || $sentencia === 'DELETE' || $sentencia === 'SELECT') {
            $tipo  = trim($tipo);
            $count = strlen($tipo);
            if ($count === 6) {
                if ($tipo === 'SELECT' && $sentencia === 'SELECT') {
                    $ejecutar = TRUE;
                    $exe      = 'select';
                }
            } else if ($count > 6 && $existe_dos == FALSE) {
                if ($tipo === 'EXECUTE' && ($sentencia === 'INSERT' || $sentencia === 'UPDATE' || $sentencia === 'DELETE')) {
                    $ejecutar = TRUE;
                    $exe      = 'execute';
                }
            }else{
                if($existe_dos === TRUE){
                    $count_type = strlen($tipo_select);
                    if($count_type > 2 && $sen === 'SELECT' && ($tipo_select === 'NUM' || $tipo_select === 'BOTH' || $tipo_select === 'ASSOC' )){

                        $ejecutar = TRUE;
                        $exe      = 'select';
                        $count    = strlen($sen);
                        if($tipo_select == 'ASSOC'){
                            $type = MYSQLI_ASSOC;
                        }else if($tipo_select == 'NUM'){
                            $type = MYSQLI_NUM;
                        }
                    }else{
                        if ($sen === 'EXECUTE' && ($sentencia === 'INSERT' || $sentencia === 'UPDATE' || $sentencia === 'DELETE')) {
                            if ($tipo_select === 'ROWS' || $tipo_select === 'ID') {
                                $ejecutar = TRUE;
                                $exe      = 'execute';
                            }
                        }
                    }
                }
            }
            if ($ejecutar === TRUE) {
                $this->_open_conn();
                $this->_state_query = $this->_conn->query($sql);
                if ($this->_state_conn && $this->_state_query) {
                    if ($exe === 'select' && $count === 6) {
                        $total = $this->_state_query->num_rows;
                        if ($total > 0) {
                            $total = $this->_state_query->num_rows;
                            if ($total > 0) {
                                for ($i = 0; $i < $total; $i++) {
                                    $this->_state_query->data_seek($i);
                                    $row[$i] = $this->_state_query->fetch_array($type);
                                }
                                $this->_state_query->free();
                                $this->_close_conn();
                                return $row;
                            }
                        }
                    }else{
                       if($tipo_select === 'ROWS'){
                           $type_result = $this->_conn->affected_rows;
                           $this->_rows_affected = $this->_conn->affected_rows;
                       }if($tipo_select === 'ID'){
                           $type_result = $this->_conn->insert_id;
                       }
                       $this->_state_query->free();
                       $this->_close_conn();
                       return $type_result;
                    }
                }
            }
        }
    }

    public function ex_query($sql)
    {

        $resultado = FALSE; 
        $this->_open_conn();
        $this->_state_query = $this->_conn->query($sql);
        if ($this->_state_conn && $this->_state_query) {
            $total = $this->_state_query->num_rows;
            if ($total > 0) {
                for ($i = 0; $i < $total; $i++) {
                    $this->_state_query->data_seek($i);
                    $row[$i] = $this->_state_query->fetch_assoc();
                }
                $this->_state_query->free();
                $resultado = $row;
            }
        }
        $this->_close_conn();
        return $resultado;
    }

    protected function execute($sql)
    {
        $this->_open_conn();
        $this->_state_query = $this->_conn->query($sql);
        if ($this->_state_conn && $this->_state_query) {
            $resultado = $this->_state_query;
        } else {
            $resultado = FALSE;
        }
        $this->_close_conn();
        return $resultado;
    }

    protected function multiples_querys($sql, $tipo = 0)
    {
        $resultado = FALSE;
        $this->_open_conn();
        $this->_res = $this->_conn->multi_query($sql);
        if ($this->_res) {
            if ($tipo == 'ROWS') {
                $resultado            = $this->_conn->affected_rows;
                $this->_rows_affected = $this->_conn->affected_rows;
            } else {
                $resultado = $this->_res;
            }
        }
        $this->_res->free();
        $this->_close_conn();
        return $resultado;
    }

    public function select($options, $type = 'BOTH')
    {
        switch ($type) {
            case 'ASSOC':
                $type = MYSQLI_ASSOC;
                break;
            case 'NUM':
                $type = MYSQLI_NUM;
                break;
            default:
                $type = MYSQLI_BOTH;
                break;
        }
        if (!is_array($options)) {
            return FAlSE;
        }
        $resultado = FALSE;
        $this->_open_conn();
        if ($this->_conn && $this->_state_conn) {
            $default            = array(
                'tabla'     => '',
                'campos'    => '*',
                'condicion' => '1',
                'ordenar'   => '1',
                'limite'    => 200
            );
            $options            = array_merge($default, $options);
            $sql                = "SELECT {$options['campos']} FROM {$options['tabla']} WHERE {$options['condicion']} ORDER BY {$options['ordenar']} LIMIT {$options['limite']}";
            $this->_state_query = $this->_conn->query($sql);
            if ($this->_state_conn && $this->_state_query && $this->_state_query->num_rows > 0) {
                $total = $this->_state_query->num_rows;
                for ($i = 0; $i < $total; $i++) {
                    $this->_state_query->data_seek($i);
                    $row[$i] = $this->_state_query->fetch_array($type);
                }
                $this->_state_query->free();
                $this->_close_conn();
                return $row;
            }else{
                return FALSE;
            }
            
        }
    }

    protected function row($options)
    {
        $this->_res = FALSE;
        if(!is_array($options)){
            return FAlSE;
        }
        
        $default = array('condicion' => '1','ordenar' => 1);
        $options = array_merge($default, $options);

        $sql     = "SELECT {$options['campos']} FROM {$options['tabla']} WHERE {$options['condicion']} ORDER BY {$options['ordenar']} LIMIT 1";
        $this->_open_conn();
        $this->_state_query = $this->_conn->query($sql);
        if ($this->_state_conn && $this->_state_query && $this->_state_query->num_rows > 0) {
            $this->_res = $this->_state_query->fetch_assoc();
            $this->_state_query->free();
        }

        $this->_close_conn();
        return $this->_res;
    }
    protected function get($table = NULL, $field = NULL, $conditions = '1')
    {

        if ($table === NULL || $field === NULL) {
            return FALSE;
        } else {
            $resultado = FALSE;
            $sql       = "SELECT $field FROM $table  WHERE $conditions ORDER BY 1 LIMIT 1";

            $this->_open_conn();
            $this->_state_query = $this->_conn->query($sql);
            if ($this->_state_conn && $this->_state_query && $this->_state_query->num_rows > 0) {
                $this->_state_query->data_seek(0);
                $row = $this->_state_query->fetch_row();
                $this->_state_query->free();
                $resultado =  $row[0];
            }
            $this->_close_conn();
            return $resultado;
        }
    }

    protected function first($table = NULL,  $conditions = '1')
    {

        if ($table === NULL) {
            return FALSE;
        } else {
            $resultado = FALSE;
            $sql       = "SELECT * FROM $table  WHERE $conditions ORDER BY 1 ASC LIMIT 1";

            $this->_open_conn();
            $this->_state_query = $this->_conn->query($sql);
            if ($this->_state_conn && $this->_state_query && $this->_state_query->num_rows > 0) {
                $this->_state_query->data_seek(0);
                $row = $this->_state_query->fetch_assoc();
                $this->_state_query->free();
                $resultado = $row;
            }
            $this->_close_conn();
            return $resultado;
        }
    }
    protected function last($table = NULL, $conditions = '1')
    {

        if ($table === NULL) {
            return FALSE;
        } else {
            $resultado = FALSE;
            $sql       = "SELECT * FROM $table WHERE $conditions ORDER BY 1 DESC LIMIT 1";

            $this->_open_conn();
            $this->_state_query = $this->_conn->query($sql);
            if ($this->_state_conn && $this->_state_query && $this->_state_query->num_rows > 0) {
                $this->_state_query->data_seek($this->_state_query->num_rows -1);
                $row = $this->_state_query->fetch_assoc();
                $this->_state_query->free();
                $resultado = $row;
            }
            $this->_close_conn();
            return $resultado;
        }
    }

    protected function numRows($table = NULL, $field = NULL, $conditions = '1')
    {
        if ($table === NULL || $field === NULL) {
            return FALSE;
        } else {
            $resultado = 0;
            $sql       = "SELECT $field FROM $table WHERE $conditions ORDER BY 1 DESC LIMIT 200";
            
            $this->_open_conn();
            $this->_state_query = $this->_conn->query($sql);
            if ($this->_state_conn && $this->_state_query && $this->_state_query->num_rows > 0) {
                $resultado = $this->_state_query->num_rows;
                $this->_state_query->free();
            }
            $this->_close_conn();
            return $resultado;
        }
    }

    protected function autoIncremet($table = NULL, $field = NULL)
    {
        if ($table === NULL || $field === NULL) {
            return FALSE;
        } else {

            $sql       = "SELECT $field FROM $table ORDER BY 1 DESC LIMIT 1";
            $this->_open_conn();
            $this->_state_query = $this->_conn->query($sql);
            if ($this->_state_conn && $this->_state_query && $this->_state_query->num_rows >= 0) {
                $this->_state_query->data_seek($this->_state_query->num_rows - 1);
                
                $row= $this->_state_query->fetch_row();
                
                $this->_state_query->free();
                $this->auto_incremenet = (int) $row[0]+1;
            }
            $this->_close_conn();
        }
    }

    protected function recordExists($table = NULL, $where = NULL)
    {
        if ($table === NULL || $where === NULL) {
            return FALSE;
        } else {
            $resultado          = FALSE;
            $sql                = "SELECT 1 FROM $table WHERE $where";
            $this->_open_conn();
            $this->_state_query = $this->_conn->query($sql);
            if ($this->_state_conn && $this->_state_query && $this->_state_query->num_rows > 0) {
                $this->_state_query->free();
                $resultado = TRUE;
            }
            $this->_close_conn();
            return $resultado;
        }
    }

    protected function insert($table = NULL, $array_of_values = array())
    {
        if ($table === NULL || empty($array_of_values) || !is_array($array_of_values)) {
            return FALSE;
        } else {
            $resultado = FALSE;
            $fields    = array();
            $values    = array();
            $this->_open_conn();
            foreach ($array_of_values as $id => $value) {
                $fields[] = $id;
                if (is_array($value) && !empty($value[0])) {
                    $values[] = $value[0];
                } else {
                    $values[] = "'" . $this->_conn->real_escape_string($value) . "'";
                }
            }
            $campos  = implode(',', $fields);
            $valores = implode(',', $values);
            
            $this->_sql = "INSERT INTO $table ($campos)VALUES($valores) ";
            if ($this->_state_conn) {
                $this->_state_query = $this->_conn->query($this->_sql);
                    $resul = $this->_conn->errno;
                    if ($resul === 0) {
                        $resultado = TRUE;
                        $this->_rows_affected = $this->_conn->affected_rows;
                    }else{
                        $resultado = $resul;
                }
            }
        }
        $this->_close_conn();
        return $resultado;
    }

    protected function update($table = NULL, $array_of_values = array(), $conditions = 1)
    {

        if ($table === NULL || empty($array_of_values)) {
            return FALSE;
        } else {
            $this->_state_conn= $this->_open_conn();
            $what_to_set = array();

            foreach ($array_of_values as $field => $value) {

                if (is_array($value) && !empty($value[0])) {

                    $what_to_set[] = "$field='{$value[0]}'";
                } else {
                    $what_to_set[] = "$field='" . $this->_state_conn->real_escape_string($value)  . "'";
                }
            }

            $what_to_set_string = implode(',', $what_to_set);

            $this->_sql           = "UPDATE $table SET $what_to_set_string WHERE $conditions;";
            $this->_state_query   = $this->_state_conn->query($this->_sql);
            $this->_rows_affected = $this->_state_conn->affected_rows;
            if($this->_state_query === TRUE){
                if($this->_rows_affected > 0){
                    return  $this->_rows_affected;
                }else{
                    return TRUE;
                }
            }else{
                return FALSE;
            }
        }
        $this->_close_conn();
    }

    protected function delete($table = NULL,$conditions = 'FALSE')
    {
        if ($table === FALSE){
            return FALSE;
        }
        $this->_state_conn = $this->_open_conn();
        
        if ($this->_state_conn) {
            $this->_sql = "DELETE FROM $table WHERE $conditions;";
            $this->_state_conn->query($this->_sql);
            $this->_rows_affected = $this->_state_conn->affected_rows;
            if ($this->_rows_affected > 0) {
                if($this->r_affec === FALSE){
                    return TRUE;
                }else{
                    return $this->_rows_affected;
                }
            } else {
                return FALSE;
            }
        }
       $this->_close_conn();
    }

    

    private function _close_conn()
    {
        if ($this->_state_conn=== TRUE) {
            $this->_conn->close();
            unset($this->_conn);
            $this->_state_conn= FALSE;
        }
    }
}
