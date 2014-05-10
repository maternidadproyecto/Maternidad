<?php

define('BASEPATH', '');
require_once './tcpdf/spa.php';
require_once './tcpdf/MyClass.php';

require_once '../../modelo/medico/Medico.php';

$obj_reporte = new Medico();

$pdf = new MyClass("L", "mm", "A4", true, 'UTF-8', false);


// Mostrar Cabecera de titulo en las hojas
$pdf->setPrintHeader(true);
// salto de linea
$pdf->Ln(80);
// Mostrar Cabecera de footer en las hoja
$pdf->setPrintFooter(true);
// mostrar numero de paginas
$pdf->SetAutoPageBreak(true);
//setear margenes 
$pdf->SetMargins(15, 20, 15);
// añadimos la pagina


/* * ******Imagen del logo en la primera hoja********* */
//$pdf->Image('imagenes/logo.png', 3, 18, 45, 15, 'PNG', FALSE);


$where = 1;

if (isset($_GET['hoy'])) {
    $where .= ' AND ci.fecha=CURRENT_DATE';
}
if (isset($_GET['desde']) && (!isset($_GET['hasta']))) {
    $desde = $obj_reporte->formateaBD($_GET['desde']);
    $where .= " AND ci.fecha BETWEEN '$desde' AND DATE_ADD(CURRENT_DATE, INTERVAL 3 MONTH)";
}else if (isset($_GET['desde']) && isset($_GET['hasta'])) {
    $desde = $obj_reporte->formateaBD($_GET['desde']);
    $hasta = $obj_reporte->formateaBD($_GET['hasta']);
    $where .= " AND ci.fecha BETWEEN '$desde' AND '$hasta' ";
}
$sql = "SELECT
co.consultorio,
co.num_consultorio,
ci.turno,
IF(ci.turno=1,'MAÑANA','TARDE') AS tipo_turno
FROM cita AS ci
INNER JOIN consultorio AS co ON ci.num_consultorio=co.num_consultorio
WHERE $where
GROUP BY ci.num_consultorio,ci.turno";

$result = $obj_reporte->ex_query($sql);

// Cantidad maxima de registros a mostrar por pagina
$max          = 20;
$row_height   = 6;
$backup_group = "";

// width de las filas 

$w_numero = 10;
$w_fecha  = 25;
$w_cedula = 30;
$w_nombre = 100;
$w_tp     = 15;
$posicion_x = 45;
for ($i = 0; $i < count($result); $i++) {
    // titulo del listado
    $pdf->AddPage();
    $titulo = "MORBILIDAD DIARIA DE ADMISION";
    $pdf->Ln(40);
    $pdf->SetX(100);
// fuente y tamaño de letra 
    $pdf->SetFont('FreeSerif', 'B', 14);
// añadimos el titulo
    $pdf->Cell(90, 0,$titulo, 0, 0, 'C', 0);
    $pdf->Ln(10);
    $pdf->SetX(100);
    $pdf->Cell(90, 0, $result[$i]['consultorio'] .'    Turno:'.$result[$i]['tipo_turno'], 0, 1, 'C', 0);
    $pdf->Ln(5);
    $pdf->SetFillColor(170, 75, 151);
    // Titulos de la Cabecera
    $pdf->SetX($posicion_x);
    $pdf->Cell($w_numero, $row_height, 'Nro', 1, 0, 'C', 1);
    $pdf->Cell($w_fecha, $row_height, 'Fecha', 1, 0, 'C', 1);
    $pdf->Cell($w_cedula, $row_height, 'Cédula', 1, 0, 'C', 1);
    $pdf->Cell($w_nombre, $row_height, 'Nombres', 1, 0, 'C', 1);
    $pdf->Cell($w_cedula, $row_height, 'Télefono', 1, 0, 'C', 1);
    $pdf->Cell($w_tp, $row_height, 'Tipo', 1, 1, 'C', 1);
    // buscar pacientes
    
    $sql      = "SELECT 
                    IF(ci.fecha=CURRENT_DATE,1,0) AS hoy,
                    DATE_FORMAT(ci.fecha,'%d-%m-%Y') AS fecha, 
                    CONCAT_WS('-',p.nacionalidad,p.cedula_p) AS cedula_p,
                    CONCAT_WS(' ', p.nombre,p.apellido) AS nombres,
                    p.ps,
                    CONCAT('0',(SELECT CONCAT_WS('-',ctt.codigo,p.celular) FROM codigo_telefono AS ctt WHERE ctt.cod_telefono=p.cod_telefono)) AS telefono
                 FROM paciente AS p 
                 INNER JOIN cita AS ci ON p.cedula_p=ci.cedula_p 
                 WHERE ci.num_consultorio = " . $result[$i]['num_consultorio'] ." AND turno =".$result[$i]['turno']." AND ".$where." ORDER BY p.ps";
    $result_p = $obj_reporte->ex_query($sql);
    
    $p = 0;
    $s = 0;
    $k = 1;
    for ($j = 0; $j < count($result_p); $j++) {
        
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetX($posicion_x);
        // crear los registros a mostrar
        $pdf->SetFont('FreeSerif', '', 12);
        if ($j % 2 != 0) {
            $pdf->SetFillColor(204, 205, 206);
        }

        $pdf->SetTextColor(0, 0, 0);
         
        if($result_p[$j]['ps'] == 'P'){
            $p++;
        }else{
           $s++;
        }
        $pdf->Cell($w_numero, $row_height, $k, 1, 0, 'C', 1);
        if ($result_p[$j]['hoy'] == 1) {
            $pdf->SetTextColor(255, 0, 0);
        }
        $pdf->Cell($w_fecha, $row_height, $result_p[$j]['fecha'], 1, 0, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell($w_cedula, $row_height, $result_p[$j]['cedula_p'], 1, 0, 'C', 1);
        $pdf->Cell($w_nombre, $row_height, $result_p[$j]['nombres'], 1, 0, 'C', 1);
        $pdf->Cell($w_cedula, $row_height, $result_p[$j]['telefono'], 1, 0, 'C', 1);
        if ($result_p[$j]['ps'] == 'P') {
            $pdf->SetTextColor(255, 0, 0);
        }
        $pdf->Cell($w_tp, $row_height, $result_p[$j]['ps'], 1, 1, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $k++;
    }
    $pdf->Ln(2);
    $pdf->SetX(242);
    $prim = '<span style="color:#FF0000;">P ('.$p.')</span>';
    $pdf->writeHTML($prim, true, false, true, false, 'L');
    $pdf->SetX(242);
    $suc = '<span style="color:#000000;">S('.$s.')</span>';
    $pdf->writeHTML($suc, true, false, true, false, 'L');
    //$pdf->AddPage();
}

$pdf->Output('listado_citas.pdf', 'I');