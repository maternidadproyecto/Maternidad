<?php
require_once './tcpdf/spa.php';
require_once './tcpdf/MyClass.php';

$pdf = new MyClass("L", "mm", "A4", true, 'UTF-8', false,'hola');

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

/********Imagen del logo en la primera hoja**********/
$pdf->Image('imagenes/logo.png', 3, 18, 45, 15, 'PNG', FALSE);


// titulo del listado
$titulo = "MATERNIDAD INTEGRAL ARAGUA";
$pdf->Ln(5);
$pdf->SetX(80);
// fuente y tamaño de letra 
$pdf->SetFont('FreeSerif', 'B', 14);
// añadimos el titulo
$pdf->Cell(90, 0, $titulo, 0, 0, 'C', 0);
$pdf->Ln(15);

/*************************************/



$j            = 0;
// Cantidad maxima de registros a mostrar por pagina
$max          = 20;
$row_height   = 6;
$backup_group = "";


// width de las filas 

$w_numero = 10;
$w_nombres   = 60;
$w_cedula   = 30;
$w_edad   = 15;
$w_direccion   = 60;
$w_ps  = 10;
$w_dx  = 10;
$w_observacion   = 70;

// Mover a la derecha 
$pdf->SetX(18);
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


// Ciclo para crear los registros
for ($i = 0; $i < 4; $i++) {
  
    
    // verificar que la variable $j no si es mayor se hace un salto de pagina
    if ($j > $max) {
        $pdf->AddPage();

        // color de la letra
        $pdf->SetFillColor(255, 255, 255);

        // salto de linea
        $pdf->Ln(15);
        /******Imagen del logo de las hojas que continua******/
        $pdf->Image('imagenes/logo.png', 3, 18, 45, 15, 'PNG', FALSE);
        // Tipo de letra negrita tamaño 14
        $pdf->SetFont('FreeSerif', 'B', 14);
        
        $pdf->SetX(80);
        // Titulo del Reporte width:90 heigth:0 text:$titulo alineacion:C
        $pdf->Cell(90, 0, $titulo, 0, 0, 'C', 0);
        $pdf->Ln(15);
        
        // Color Cabecera de la tabla
        $pdf->SetFillColor(39, 129, 213);
        $pdf->SetX(18);
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

    /*$pdf->SetTextColor(0, 0, 0);
    if ($id == 20 || $id == 40 || $id == 60) {
        $pdf->SetTextColor(255, 0, 0);
    }*/
    
    // crear los registros a mostrar
    $pdf->SetFont('FreeSerif', '', 12);
    $pdf->SetX(18);
    $pdf->Cell($w_numero, $row_height, $i, 1, 0, 'C', 1);
    $pdf->Cell($w_nombres, $row_height, $i, 1, 0, 'L', 1);
    $pdf->Cell($w_cedula, $row_height, $i, 1, 0, 'L', 1);
    $pdf->Cell($w_edad, $row_height, $i, 1, 0, 'L', 1);
    $pdf->Cell($w_direccion, $row_height, $i, 1, 0, 'L', 1);
    $pdf->Cell($w_ps, $row_height, $i, 1, 0, 'L', 1);
    $pdf->Cell($w_dx, $row_height, $i, 1, 0, 'L', 1);
    $pdf->Cell($w_observacion, $row_height, $i, 1, 1, 'L', 1);
    $j++;
}
/***************Linea de fin de hoja con la cantidad total de registros**********************/
$pdf->setCellMargins(0, 0,0,0);
$linea = '------------------------------------------------------------------------------------------------------------------------------';
$pdf->Ln();
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(0, 0, $linea, 0, 0, 'L', 1);
$pdf->Ln(6);
//$pdf->Write(14, 'Registros:' . '' . $h);
$pdf->SetFont('FreeSerif', '', 10);
//$registros = 'Total de Registros:<span style="color:#FF0000;">' . $total . '</span>';
//$pdf->writeHTML($registros, true, false, true, false, 'R');
$pdf->Output('listado_estados.pdf', 'I');