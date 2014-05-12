<?php

define('BASEPATH', '');
require_once './tcpdf/spa.php';
require_once './tcpdf/MyClass.php';

require_once '../../modelo/paciente/Paciente.php';

$obj_reporte = new Paciente();

$pdf = new MyClass("L", "mm", "A4", true, 'UTF-8', false);

// Mostrar Cabecera de titulo en las hojas
$pdf->setPrintHeader(true);
// salto de linea
$pdf->Ln(50);
// Mostrar Cabecera de footer en las hoja
$pdf->setPrintFooter(true);
// mostrar numero de paginas
$pdf->SetAutoPageBreak(true);
//setear margenes 
$pdf->SetMargins(15, 20, 15);
// añadimos la pagina
$pdf->AddPage();


// titulo del listado
$titulo      = "MATERNIDAD INTEGRAL ARAGUA";

$pdf->Ln(5);
$pdf->SetX(80);
// fuente y tamaño de letra 
$pdf->SetFont('FreeSerif', 'B', 14);
// añadimos el titulo
$pdf->Ln(15);

/* * ********************************** */

$j            = 0;
// Cantidad maxima de registros a mostrar por pagina
$max          = 20;
$row_height   = 6;
$backup_group = "";

// width de las filas 

$w_numero      = 8;
$w_hora        = 10;
$w_nombres     = 32;
$w_edad        = 10;
$w_direccion   = 40;
$w_lugar       = 45;
$w_tension     = 20;
$w_fur         = 18;
$w_fpp         = 18;
$w_diagnostico = 40;
$w_medico      = 28;
$w_conducta    = 30;

$posicion_x = 100;
$poscion_xr = 5;

$pdf->Ln(5);
$pdf->SetX($posicion_x);
// fuente y tamaño de letra 
$pdf->SetFont('FreeSerif', 'B', 12);
// añadimos el titulo
$pdf->Cell(90, 0, $titulo, 0, 0, 'C', 0);

// Mover a la derecha 
$pdf->Ln(15);
// Color Cabecera de la tabla
$pdf->SetFillColor(39, 129, 213);
// Titulos de la Cabecera
$pdf->SetX($poscion_xr);
$pdf->SetFont('FreeSerif', 'B', 10);
$pdf->Cell($w_numero, $row_height, 'Nro', 1, 0, 'C', 1);
$pdf->Cell($w_hora, $row_height, 'Hora', 1, 0, 'C', 1);
$pdf->Cell($w_nombres, $row_height, 'Nombres y Apellidos', 1, 0, 'L', 1);
$pdf->Cell($w_edad, $row_height, 'Edad', 1, 0, 'L', 1);
$pdf->Cell($w_direccion, $row_height, 'Direccion', 1, 0, 'L', 1);
$pdf->Cell($w_lugar, $row_height, 'Lugar Control Prenatal', 1, 0, 'L', 1);
$pdf->Cell($w_tension, $row_height, 'Tension', 1, 0, 'L', 1);
$pdf->Cell($w_fur, $row_height, 'FUR', 1, 0, 'L', 1);
$pdf->Cell($w_fpp, $row_height, 'FPP', 1, 0, 'L', 1);
$pdf->Cell($w_diagnostico, $row_height, 'Diagnostico', 1, 0, 'L', 1);
$pdf->Cell($w_medico, $row_height, 'Medico', 1, 0, 'L', 1);
$pdf->Cell($w_conducta, $row_height, 'Conducta', 1, 1, 'L', 1);

$fecha = $_GET['fecha'];
$med   = $_GET['med'];
$ped   = $_GET['ped'];
$ane   = $_GET['ane'];

$fecha = $obj_reporte->formateaBD($fecha);

$sql = "SELECT 
              CONCAT_WS(' ',p.nombre,p.apellido)AS nombres, CONCAT_WS('-',c.nacionalidad,c.cedula_p) AS cedula_p, 
              (YEAR(CURDATE())-YEAR(p.fecha_nacimiento))- (RIGHT(CURDATE(),5)<RIGHT(p.fecha_nacimiento,5))AS edad,
              p.direccion, 
              p.lugar_control,
              c.tension,
              DATE_FORMAT(p.fur,'%d/%m/%Y') AS fur, 
              DATE_FORMAT(p.fpp,'%d/%m/%Y') AS fpp, 
              c.diagnostico, 
              CONCAT_WS(' ',pm.nombre,pm.apellido) AS medico
             FROM consulta AS c 
             INNER JOIN paciente AS p ON c.cedula_p=p.cedula_p 
             INNER JOIN personal_medico AS pm ON c.cedula_pm=pm.cedula_pm
             WHERE c.num_consultorio=1 
             AND c.fecha='$fecha' 
             AND CONCAT_WS('-',c.nacionalidad,c.cedula_pm)='$med'
             AND CONCAT_WS('-',c.nacionalidad,c.cedula_pm)='$ped'
             AND CONCAT_WS('-',c.nacionalidad,c.cedula_pm)='$ane'";
$result = $obj_reporte->ex_query($sql);
// Ciclo para crear los registros
for ($i = 0; $i < count($result); $i++) {


    // verificar que la variable $j no si es mayor se hace un salto de pagina
    if ($j > $max) {
        $pdf->AddPage();

        // color de la letra
        $pdf->SetFillColor(255, 255, 255);

        // salto de linea
        $pdf->Ln(15);
        /*         * ****Imagen del logo de las hojas que continua***** */
        $pdf->Image('imagenes/logo.png', 3, 18, 45, 15, 'PNG', FALSE);
        // Tipo de letra negrita tamaño 14
        $pdf->SetFont('FreeSerif', 'B', 10);

        $pdf->SetX(80);
        // Titulo del Reporte width:90 heigth:0 text:$titulo alineacion:C
        $pdf->Cell(90, 0, $titulo, 0, 0, 'C', 0);
        $pdf->Ln(15);

        // Color Cabecera de la tabla
        $pdf->SetFillColor(39, 129, 213);
        $pdf->SetX($poscion_xr);
        $pdf->Cell($w_numero, $row_height, 'Nro', 1, 0, 'C', 1);
        $pdf->Cell($w_hora, $row_height, 'Hora', 1, 0, 'C', 1);
        $pdf->Cell($w_nombres, $row_height, 'Nombres y Apellidos', 1, 0, 'L', 1);
        $pdf->Cell($w_edad, $row_height, 'Edad', 1, 0, 'L', 1);
        $pdf->Cell($w_direccion, $row_height, 'Direccion', 1, 0, 'L', 1);
        $pdf->Cell($w_lugar, $row_height, 'Lugar Control Prenatal', 1, 0, 'L', 1);
        $pdf->Cell($w_tension, $row_height, 'Tension', 1, 0, 'L', 1);
        $pdf->Cell($w_fur, $row_height, 'FUR', 1, 0, 'L', 1);
        $pdf->Cell($w_fpp, $row_height, 'FPP', 1, 0, 'L', 1);
        $pdf->Cell($w_diagnostico, $row_height, 'Diagnostico', 1, 0, 'L', 1);
        $pdf->Cell($w_medico, $row_height, 'Medico', 1, 0, 'L', 1);
        $pdf->Cell($w_conducta, $row_height, 'Conducta', 1, 1, 'L', 1);
        $j = 0;
    }

    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetFont('FreeSerif', '', 12);
    if ($i % 2 != 0) {
        $pdf->SetFillColor(204, 205, 206);
    }

    /* $pdf->SetTextColor(0, 0, 0);
      if ($id == 20 || $id == 40 || $id == 60) {
      $pdf->SetTextColor(255, 0, 0);
      } */

    // crear los registros a mostrar
    $pdf->SetFont('FreeSerif', '', 10);
    $pdf->SetX($poscion_xr);
    $pdf->Cell($w_numero, $row_height, $i, 1, 0, 'C', 1);
    $pdf->Cell($w_hora, $row_height,$i , 1, 0, 'L', 1);
    $pdf->Cell($w_nombres, $row_height, $result[$i]['nombres'], 1, 0, 'L', 1);    
    $pdf->Cell($w_edad, $row_height, $result[$i]['edad'], 1, 0, 'L', 1);
    $pdf->Cell($w_direccion, $row_height,$result[$i]['direccion'] , 1, 0, 'L', 1);
    $pdf->Cell($w_lugar, $row_height, $result[$i]['lugar_control'] , 1, 0, 'L', 1);
    $pdf->Cell($w_tension, $row_height,$result[$i]['tension'] , 1, 0, 'L', 1);
    $pdf->Cell($w_fur, $row_height,$result[$i]['fur'], 1, 0, 'L', 1);
    $pdf->Cell($w_fpp, $row_height, $result[$i]['fpp'] , 1, 0, 'L', 1);
    $pdf->Cell($w_diagnostico, $row_height,$result[$i]['diagnostico'] , 1, 0, 'L', 1);
    $pdf->Cell($w_medico, $row_height, $result[$i]['medico'], 1, 0, 'L', 1);
    $pdf->Cell($w_conducta, $row_height, $i, 1, 1, 'L', 1);
    $j++;
}
/* * *************Linea de fin de hoja con la cantidad total de registros********************* */
/*$pdf->setCellMargins(0, 0, 0, 0);
$linea = '------------------------------------------------------------------------------------------------------------------------------';
$pdf->Ln();
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(0, 0, $linea, 0, 0, 'L', 1);
$pdf->Ln(6);
//$pdf->Write(14, 'Registros:' . '' . $h);
$pdf->SetFont('FreeSerif', '', 10);
//$registros = 'Total de Registros:<span style="color:#FF0000;">' . $total . '</span>';
//$pdf->writeHTML($registros, true, false, true, false, 'R');*/
$pdf->Output('listado_estados.pdf', 'I');
