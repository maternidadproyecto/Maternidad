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
        $this->Image('imagenes/top.jpg', 0, 0, 210, 35, 'JPG', FALSE);
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
        $this->Line(5, 287, 202, 287, $style);
        $this->Cell(30, 0, $fecha, 0, false, 'R', 0, '', 0, false, 'T', 'M');
        $this->Cell(255, 0, 'Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }

}

if (isset($_GET['cedula_p'])) {
    $datos['cedula_p'] = $_GET['cedula_p'];
    $cedula_p          = $_GET['cedula_p'];
}

$pdf = new MyClass("P", "mm", "A4", true, 'UTF-8', false);

$pdf->AddPage();
// Mostrar Cabecera de titulo en las hojas
$pdf->setPrintHeader(true);
// salto de linea
// Mostrar Cabecera de footer en las hoja
$pdf->setPrintFooter(true);
// mostrar numero de paginas
$pdf->SetAutoPageBreak(true);
//setear margenes 
$pdf->SetMargins(10, 5, 10);
// añadimos la pagina


/* * ******Imagen del logo en la primera hoja********* */
//$pdf->Image('imagenes/logo.png', 3, 18, 45, 15, 'PNG', FALSE);


 $data['sql'] = "SELECT 
                    p.nombre,
                    p.apellido,
                    p.historia,
                    DATE_FORMAT(p.fecha_nacimiento,'%d-%m-%Y') AS fecha_nacimiento,
                    (YEAR(CURDATE())-YEAR(p.fecha_nacimiento)) - (RIGHT(CURDATE(),5)<RIGHT(p.fecha_nacimiento,5)) AS edad,
                    DATE_FORMAT(p.fpp,'%d-%m-%Y')AS fpp,
                    DATE_FORMAT(p.fur,'%d-%m-%Y') AS fur,
                    p.ps,
                    p.lugar_control
                FROM paciente AS p
                WHERE CONCAT_WS('-',p.nacionalidad,p.cedula_p) = '$cedula_p';";

$result_paci = $obj->getPaciente($data);

$nombres          = $result_paci[0]['nombre'] . ' ' . $result_paci[0]['apellido'];
$historia         = $result_paci[0]['historia'];
$fecha_nacimiento = $result_paci[0]['fecha_nacimiento'];
$edad             = $result_paci[0]['edad'];
$fpp              = $result_paci[0]['fpp'];
$fur              = $result_paci[0]['fur'];
$ps               = $result_paci[0]['ps'];
$lugar_control    = $result_paci[0]['lugar_control'];

$titulo = "MATERNIDAD INTEGRAL DE ARAGUA";
$pdf->Ln(20);
$pdf->SetX(80);
$pdf->SetFont('FreeSerif', 'B', 10);
// añadimos el titulo
$pdf->Cell(50, 0, $titulo, 0, 0, 'C', 0);


$titulo1 = "HISTORIA MEDICA";
$pdf->Ln(10);
$pdf->SetX(80);
$pdf->SetFont('FreeSerif', 'B', 10);
$pdf->Cell(50, 0, $titulo1, 0, 1, 'C', 0);

$pdf->SetMargins(0, 0, 0);
$pdf->Ln(20);
$pdf->SetFont('FreeSerif', '', 10);

$pdf->writeHTMLCell(0, 0, 10, '', '<span style="font-weight:bold ">Cédula:</span>'.$cedula_p, 0, 0, 0, 0, '', 0);


$pdf->writeHTMLCell(0, 0, 160, '', '<span style="font-weight:bold;">Nro de Historia:</span><span style="color:#FF0000">'.$historia.'</span>', 0, 1, 0, true, '', true);
$pdf->Ln(5);

$pdf->SetFont('FreeSerif', '', 15);
$pdf->writeHTMLCell(0, 0, 65, '', '<span style="font-weight:bold ">Nombre y Apellido:</span>'.ucwords($nombres), 0, 1, 0, 0, '', 0);

$pdf->SetFont('FreeSerif', '', 10);
$pdf->Ln(10);
$pdf->writeHTMLCell(0, 0, 25, '', '<span style="font-weight:bold ">Fecha de Nacimiento:</span>'.$fecha_nacimiento, 0, 0, 0, 0, '', 0);
$pdf->writeHTMLCell(0, 0, 85, '', '<span style="font-weight:bold ">Edad:</span>'.$edad, 0, 0, 0, 0, '', 0);
$pdf->writeHTMLCell(0, 0, 105, '', '<span style="font-weight:bold ">FPP:</span>'.$fpp, 0, 0, 0, 0, '', 0);
$pdf->writeHTMLCell(0, 0, 135, '', '<span style="font-weight:bold ">FUR:</span>'.$fur, 0, 0, 0, 0, '', 0);
$pdf->writeHTMLCell(0, 0, 165, '', '<span style="font-weight:bold ">Paciente:</span>'.$ps, 0, 1, 0, 0, '', 0);
$pdf->Ln(7);
$pdf->writeHTMLCell(0, 0, 25, '', '<span style="font-weight:bold ">Lugar de Control:</span>'.$lugar_control, 0,1, 0, 0, '', 0);

$pdf->Ln(15);
$pdf->SetX(80);
$pdf->SetFont('FreeSerif', 'B', 12);
$pdf->Cell(50, 0, 'Consultas', 0, 1, 'C', 0);


$data_con['tabla']     = 'consulta AS co';
$data_con['campos']    = "DATE_FORMAT(co.fecha,'%d-%m-%Y') AS fecha,
                        (SELECT consultorio FROM consultorio WHERE num_consultorio=co.num_consultorio) AS consultorio,
                        IF(co.turno=1,'Mañana','Tarde') AS turno,
                        (SELECT CONCAT_WS(' ',nombre,apellido) FROM personal_medico WHERE cedula_pm=co.cedula_pm)AS medico,
                        co.tamano,
                        co.peso,
                        co.tension,
                        co.observacion_medica";
$data_con['condicion'] = "CONCAT_WS('-',co.nacionalidad,co.cedula_p) = '$cedula_p'";
$data_con['ordenar']   = 'fecha DESC';
$result_cons = $obj->select($data_con);




$j            = 0;
// Cantidad maxima de registros a mostrar por pagina
$max          = 35;
$row_height   = 6;
$backup_group = "";


// width de las filas 

$posicion_x    = 25;

$w_fecha       = 29;
$w_consultorio = 35;
$w_turno       = 15;
$w_medico      = 30;
$w_tamano      = 17;
$w_peso        = 17;
$w_tension    = 20;

$pdf->Ln(8);
$pdf->SetFillColor(230, 154, 210);
$pdf->SetX($posicion_x);
$pdf->Cell($w_fecha, $row_height, 'Fecha Consulta', 1, 0, 'C', 1);
$pdf->Cell($w_consultorio, $row_height, 'Consultorio', 1, 0, 'C', 1);
$pdf->Cell($w_turno, $row_height, 'Turno', 1, 0, 'C', 1);
$pdf->Cell($w_medico, $row_height, 'Médico', 1, 0, 'C', 1);
$pdf->Cell($w_tamano, $row_height, 'Tamaño', 1, 0, 'C', 1);
$pdf->Cell($w_peso, $row_height, 'Peso', 1, 0, 'C', 1);
$pdf->Cell($w_tension, $row_height, 'Tensión', 1, 1, 'C', 1);

// Ciclo para crear los registros
for ($i = 0; $i < count($result_cons); $i++) {

    // Asignarle variables a los registros
   
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
        
        $pdf->SetX(60);
        // Titulo del Reporte width:90 heigth:0 text:$titulo alineacion:C
        $pdf->Cell(90, 0, $titulo, 0, 0, 'C', 0);
        $pdf->Ln(15);
        
        // Color Cabecera de la tabla
        $pdf->SetFillColor(230, 154, 210);
        $pdf->SetX($posicion_x);
        $pdf->Cell($w_fecha, $row_height, 'Fecha Consulta', 1, 0, 'C', 1);
        $pdf->Cell($w_consultorio, $row_height, 'Consultorio', 1, 0, 'C', 1);
        $pdf->Cell($w_turno, $row_height, 'Turno', 1, 0, 'C', 1);
        $pdf->Cell($w_medico, $row_height, 'Médico', 1, 1, 'C', 1);
        $pdf->Cell($w_tamano, $row_height, 'Tamaño', 1, 0, 'C', 1);
        $pdf->Cell($w_peso, $row_height, 'Peso', 1, 1, 'C', 1);
        $pdf->Cell($w_tension, $row_height, 'Tensión', 1, 1, 'C', 1);
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
    
    
 $fecha       = $result_cons[$i]['fecha'];
    $consultorio = $result_cons[$i]['consultorio'];
    $turno       = $result_cons[$i]['turno'];
    $medico      = $result_cons[$i]['medico'];
    $tamano      = $result_cons[$i]['tamano'];
    $peso        = $result_cons[$i]['peso'];
    $tension     = $result_cons[$i]['tension'];

    // crear los registros a mostrar
    $pdf->SetFont('FreeSerif', '', 12);
    $pdf->SetX($posicion_x);
    $pdf->Cell($w_fecha, $row_height, $fecha, 1, 0, 'C', 1);
    $pdf->Cell($w_consultorio, $row_height, $consultorio, 1, 0, 'C', 1);
    $pdf->Cell($w_turno, $row_height, $turno, 1, 0, 'C', 1);
    $pdf->Cell($w_medico, $row_height, $medico, 1, 0, 'C', 1);
    $pdf->Cell($w_tamano, $row_height, $tamano, 1, 0, 'C', 1);
    $pdf->Cell($w_peso, $row_height, $peso, 1, 0, 'C', 1);
    $pdf->Cell($w_tension, $row_height, $tension, 1, 1, 'C', 1);
    $j++;
}
$pdf->Output('listado_citas.pdf', 'I');
