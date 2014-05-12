<?php

define('BASEPATH', '');
require_once './tcpdf/spa.php';
require_once './tcpdf/tcpdf.php';

require_once '../../modelo/cita/Asignar.php';
$obj = new Asignar();

class MyClass extends TCPDF
{

    public function Header()
    {
        $tamano = $this->getPageWidth();
        $this->setJPEGQuality(90);
        $this->Image('imagenes/top.jpg', 0, 0, 140, 25, 'JPG', FALSE);
    }

    public function Footer()
    {
        $tamano = $this->getPageWidth();
        date_default_timezone_set('America/Caracas');
        $fecha  = "Fecha: " . date("d/m/Y h:i A");
        $this->SetY(-8);
        // Set font
        $this->SetFont('FreeSerif', '', 8);
        ///$style = array('width' => 0.30, 'cap' => 'butt', 'join' => 'miter', 'dash' => '2,2,2,2', 'phase' => 5, 'color' => array(0, 0, 0));
        $style  = array('width' => 0.30, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        // Page number
        $this->Line(5, 200, 142, 200, $style);
        $this->Cell(30, 0, $fecha, 0, false, 'R', 0, '', 0, false, 'T', 'M');
        $this->Cell(255, 0, 'Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }

}

if (isset($_GET['cedula_p'])) {
    $datos['cedula_p'] = $_GET['cedula_p'];
    $cedula_p          = $_GET['cedula_p'];
}

$pdf = new MyClass("P", "mm", "A5", true, 'UTF-8', false);

$pdf->AddPage();
// Mostrar Cabecera de titulo en las hojas
$pdf->setPrintHeader(true);
// salto de linea
// Mostrar Cabecera de footer en las hoja
$pdf->setPrintFooter(true);
// mostrar numero de paginas
$pdf->SetAutoPageBreak(true);
//setear margenes 
$pdf->SetMargins(15, 5, 15);
// añadimos la pagina


/* * ******Imagen del logo en la primera hoja********* */
//$pdf->Image('imagenes/logo.png', 3, 18, 45, 15, 'PNG', FALSE);


$data['sql'] = "SELECT 
                    p.nombre,
                    p.apellido,
                    p.historia,
                    CONCAT_WS('-', CONCAT('0',(SELECT codigo FROM codigo_telefono WHERE cod_telefono=p.cod_telefono)),p.telefono) AS telefono,
                    CONCAT_WS('-', CONCAT('0',(SELECT codigo FROM codigo_telefono WHERE cod_telefono=p.cod_celular)),p.celular) AS celular
                FROM paciente AS p
                WHERE CONCAT_WS('-',p.nacionalidad,p.cedula_p) = '$cedula_p'";
$result_paci = $obj->getPaciente($data);

$datos['sql'] = "SELECT 
                    DATE_FORMAT(c.fecha,'%d/%m/%Y') AS fecha,
                    (SELECT consultorio FROM consultorio WHERE num_consultorio=c.num_consultorio) AS consultorio,
                    c.turno 
                FROM cita AS c
                WHERE CONCAT_WS('-',c.nacionalidad,c.cedula_p) = '$cedula_p' ORDER BY 1 LIMIT 1";
$resultado    = $obj->getCita($datos);


$nombres   = $result_paci[0]['nombre'] . ' ' . $result_paci[0]['apellido'];
$telefonos = $result_paci[0]['telefono'] . ' / ' . $result_paci[0]['celular'];
$historia  = $result_paci[0]['historia'];


$fecha       = $resultado[0]['fecha'];
$consultorio = $resultado[0]['consultorio'];
$turno       = $resultado[0]['fecha'];
$turno       = $turno == 1 ? 'MA&Ntilde;ANA' : 'TARDE';

$titulo = "MATERNIDAD INTEGRAL DE ARAGUA";
$pdf->Ln(20);
$pdf->SetX(30);
$pdf->SetFont('FreeSerif', 'B', 10);
// añadimos el titulo
$pdf->Cell(80, 0, $titulo, 0, 0, 'C', 0);


$titulo1 = "CONTROL DE CITAS";
$pdf->Ln(10);
$pdf->SetX(30);
$pdf->SetFont('FreeSerif', 'B', 10);
$pdf->Cell(80, 0, $titulo1, 0, 1, 'C', 0);


$pdf->Ln(20);
$pdf->SetFont('FreeSerif', '', 10);
$pdf->writeHTMLCell(0, 0, '', '', '<span style="font-weight:bold ">Nombre y Apellido:</span>'.$nombres, 0, 1, 0, true, '', true);


$pdf->Ln(5);
$pdf->SetFont('FreeSerif', '', 10);
$pdf->writeHTMLCell(0, 0, '', '', '<span style="font-weight:bold ">Cédula:</span>'.$cedula_p, 0, 1, 0, true, '', true);



$pdf->Ln(5);
$pdf->SetFont('FreeSerif', '', 10);
$pdf->writeHTMLCell(0, 0, '', '', '<span style="font-weight:bold ">Nro de Historia:</span>'.$historia, 0, 1, 0, true, '', true);



$pdf->Ln(10);
$pdf->Cell(40, 6, 'Fecha de Consulta', 0, 0, 'C', 0);
$pdf->Cell(40, 6, 'Consultorio', 0, 0, 'C', 0);
$pdf->Cell(30, 6, 'Turno', 0, 1, 'C', 0);

$pdf->Ln(5);

$pdf->Cell(40, 6, $fecha, 0, 0, 'C', 0);
$pdf->Cell(40, 6, $consultorio, 0, 0, 'C', 0);
$pdf->Cell(30, 6, $turno, 0, 1, 'C', 0);


$style6 = array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => '0.5', 'color' => array(0, 0, 0));


$start = 115;
$end   = $start + 7;
$j     = $start;
for ($i = $start; $i < $end ;$i++) {
    $j = $j+10;
    $pdf->Line(20,$j,50,$j,$style6);
    $pdf->Line(60,$j,90,$j,$style6);
    $pdf->Line(100,$j,130,$j,$style6);
}

//$pdf->Line(40,148,72,148,$style6);
//$pdf->Line(105,148,140,148,$style6);
//$pdf->Line(150,148,180,148,$style6);
//
//$pdf->Line(40,160,72,160,$style6);
//$pdf->Line(105,160,140,160,$style6);
//$pdf->Line(150,160,180,160,$style6);


$pdf->Output('listado_citas.pdf', 'I');
