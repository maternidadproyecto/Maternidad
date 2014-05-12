<?php

$mysqli = new mysqli("localhost", "root", "", "maternidad");

/* verificar la conexión */
if (mysqli_connect_errno()) {
    printf("Conexión fallida: %s\n", mysqli_connect_error());
    exit();
}

$query = "SELECT cedula_p,nombre FROM paciente";
$result = $mysqli->query($query);

while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $nombres = explode(' ',$row['nombre']);
    $total = count($nombres);
    $nombre = $nombres[0];
    if($total == 2){
        $apellido = $nombres[1];
    }else if($total == 2){
        $apellido = $nombres[2];
    }

   $update = "UPDATE paciente SET nombre='$nombres[0]', apellido='$apellido' WHERE cedula_p=".$row['cedula_p'];
   $mysqli->query($update);
}


