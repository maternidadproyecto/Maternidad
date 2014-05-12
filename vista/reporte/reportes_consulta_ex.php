<?php
define('BASEPATH', '');
require_once './tcpdf/spa.php';
require_once './tcpdf/MyClass.php';


require_once '../../modelo/paciente/Paciente.php';

$obj_reporte = new Paciente();

$pdf = new MyClass("L", "mm", "A4", true, 'UTF-8', false, 'hola');

// Mostrar Cabecera de titulo en las hojas
$pdf->setPrintHeader(true);
// salto de linea
// Mostrar Cabecera de footer en las hoja
$pdf->setPrintFooter(true);
// mostrar numero de paginas
$pdf->SetAutoPageBreak(true);
//setear margenes 
$pdf->SetMargins(3, 20, 15);
// añadimos la pagina
$pdf->AddPage();
$pdf->Ln(30);



// titulo del listado
$titulo      = "MATERNIDAD INTEGRAL ARAGUA";
$sub_titulo1 = "MORBILIDAD DIARIA";
$sub_titulo2 = "CONSULTA EXTERNA";

$j            = 0;
// Cantidad maxima de registros a mostrar por pagina
$max          = 20;
$row_height   = 6;
$backup_group = "";


// width de las filas 

$w_numero      = 10;
$w_nombres     = 40;
$w_cedula      = 25;
$w_edad        = 12;
$w_direccion   = 60;
$w_ps          = 10;
$w_dx          = 60;
$w_observacion = 70;

$posicion_x = 100;
$poscion_xr = 5;

$pdf->Ln(5);
$pdf->SetX($posicion_x);
// fuente y tamaño de letra 
$pdf->SetFont('FreeSerif', 'B', 12);
// añadimos el titulo
$pdf->Cell(90, 0, $titulo, 0, 0, 'C', 0);

$pdf->Ln(5);
$pdf->SetX($posicion_x);
$pdf->Cell(90, 0, $sub_titulo1, 0, 0, 'C', 0);
$pdf->Ln(5);
$pdf->SetX($posicion_x);
$pdf->Cell(90, 0, $sub_titulo2, 0, 0, 'C', 0);
$pdf->Ln(15);
// Mover a la derecha 
$pdf->SetX($poscion_xr);
// Color Cabecera de la tabla
$pdf->SetFillColor(39, 129, 213);
// Titulos de la Cabecera
$pdf->Cell($w_numero, $row_height, 'Nro', 1, 0, 'C', 1);
$pdf->Cell($w_nombres, $row_height, 'Nombres y Apellidos', 1, 0, 'L', 1);
$pdf->Cell($w_cedula, $row_height, 'CI', 1, 0, 'L', 1);
$pdf->Cell($w_edad, $row_height, 'Edad', 1, 0, 'L', 1);
$pdf->Cell($w_direccion, $row_height, 'Direccion', 1, 0, 'L', 1);
$pdf->Cell($w_ps, $row_height, 'PS', 1, 0, 'L', 1);
$pdf->Cell($w_dx, $row_height, 'Dx', 1, 0, 'L', 1);
$pdf->Cell($w_observacion, $row_height, 'Observacion', 1, 1, 'L', 1);

$cons      = $_GET['cons'];
$fecha     = $_GET['fecha'];
$cedula_pm = $_GET['cedula_pm'];

$fecha = $obj_reporte->formateaBD($fecha);

$sql = "SELECT 
            CONCAT_WS(' ',p.nombre,p.apellido)AS nombres,
            CONCAT_WS('-',c.nacionalidad,c.cedula_p) AS cedula_p, 
            (YEAR(CURDATE())-YEAR(p.fecha_nacimiento))- (RIGHT(CURDATE(),5)<RIGHT(p.fecha_nacimiento,5))AS edad,
            p.direccion,
            p.ps,
            c.diagnostico,
            c.observacion
        FROM consulta AS c
        INNER JOIN paciente AS p ON c.cedula_p=p.cedula_p
        WHERE c.num_consultorio=$cons AND c.fecha='$fecha' AND CONCAT_WS('-',c.nacionalidad,c.cedula_pm)='$cedula_pm'";

$result = $obj_reporte->ex_query($sql);

$k = 1;
// Ciclo para crear los registros
for ($i = 0; $i  < count($result); $i++) {


    // verificar que la variable $j no si es mayor se hace un salto de pagina
    if ($j > $max) {
        $pdf->AddPage();

        // color de la letra
        $pdf->SetFillColor(255, 255, 255);

        // salto de linea
        $pdf->Ln(15);
  
        // Tipo de letra negrita tamaño 14
        $pdf->SetFont('FreeSerif', 'B', 14);

        $pdf->SetX($posicion_x);
        // Titulo del Reporte width:90 heigth:0 text:$titulo alineacion:C
        $pdf->Cell(90, 0, $titulo, 0, 0, 'C', 0);
        $pdf->Ln(15);

        // Color Cabecera de la tabla
        $pdf->SetFillColor(39, 129, 213);
        $pdf->SetX($poscion_xr);
        $pdf->Cell($w_numero, $row_height, 'Nro', 1, 0, 'C', 1);
        $pdf->Cell($w_nombres, $row_height, 'Nombres y Apellidos', 1, 0, 'L', 1);
        $pdf->Cell($w_cedula, $row_height, 'Cedula', 1, 0, 'L', 1);
        $pdf->Cell($w_edad, $row_height, 'Edad', 1, 0, 'L', 1);
        $pdf->Cell($w_direccion, $row_height, 'Direccion', 1, 0, 'L', 1);
        $pdf->Cell($w_ps, $row_height, 'PS', 1, 0, 'L', 1);
        $pdf->Cell($w_dx, $row_height, 'Dx', 1, 0, 'L', 1);
        $pdf->Cell($w_observacion, $row_height, 'Observacion', 1, 1, 'L', 1);
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
    $pdf->SetFont('FreeSerif', '', 12);
    $pdf->SetX($poscion_xr);
    $pdf->Cell($w_numero, $row_height, $k++, 1, 0, 'C', 1);
    $pdf->Cell($w_nombres, $row_height, $result[$i]['nombres'], 1, 0, 'L', 1);
    $pdf->Cell($w_cedula, $row_height, $result[$i]['cedula_p'], 1, 0, 'L', 1);
    $pdf->Cell($w_edad, $row_height, $result[$i]['edad'], 1, 0, 'L', 1);
    $pdf->Cell($w_direccion, $row_height, $result[$i]['direccion'], 1, 0, 'L', 1);
    $pdf->Cell($w_ps, $row_height, $result[$i]['ps'], 1, 0, 'L', 1);
    $pdf->Cell($w_dx, $row_height, $result[$i]['diagnostico'], 1, 0, 'L', 1);
    $pdf->Cell($w_observacion, $row_height,$result[$i]['observacion'], 1, 1, 'L', 1);
    $j++;
    $k++;
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
