<?php

define('BASEPATH', '');
require_once './tcpdf/spa.php';
require_once './tcpdf/MyClass.php';

$pdf = new MyClass("L", "mm", "A4", true, 'UTF-8', false);


require_once '../../modelo/medico/Medico.php';

$obj_reporte = new Medico();

class ReportePDF extends Conexion
{

    public function __construct()
    {
        //$this->firephp = FirePHP::getInstance(true);
    }

    public function getFeha($datos)
    {

        if (isset($datos['hoy'])) {
            $condicion = '= CURRENT_DATE()';
        }else if (isset($datos['desde']) && (!isset($datos['hasta']))) {
            $fecha     = $this->formateaBD($datos['desde']);
            $condicion = ">='" . $fecha . "'";
        }else if (!isset($datos['desde']) && isset($datos['hasta'])) {
            $fecha     = $this->formateaBD($datos['hasta']);
            $condicion = "<='" . $fecha . "'";
        }else if (isset($datos['desde']) && isset($datos['hasta'])) {
            $fecha_desde = $this->formateaBD($datos['desde']);
            $fecha_hasta = $this->formateaBD($datos['hasta']);
            $condicion   = " BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'";
        }
        $data   = array(
            'tabla'     => 'cita AS c, consultorio_horario AS ch, consultorio AS co,paciente AS p, turno AS t',
            'campos'    => "CONCAT_WS('-',c.nacionalidad,c.cedula_p) AS cedula_p,CONCAT_WS(' ',p.nombre,p.apellido) AS nombres,co.consultorio,t.turno,DATE_FORMAT(c.fecha,'%d-%m-%Y') AS fecha",
            'condicion' => "c.fecha $condicion AND c.cod_consu_horario=ch.cod_consu_horario AND ch.num_consultorio=co.num_consultorio AND c.cedula_p=p.cedula_p AND ch.cod_turno=t.cod_turno",
            'ordenar'   => 'c.fecha ASC'
        );
        $result = $this->select($data, FALSE);
        return $result;
    }

}

$fecha_actual = date("d-m-Y");
if (isset($_GET['hoy'])) {
    $datos['hoy'] = $_GET['hoy'];
    $texto_tipo  = "PARA HOY";
    $hoy         = 'hoy';
}else if (isset($_GET['desde']) && (!isset($_GET['hasta']))) {
    $datos['desde'] = $_GET['desde'];
    $texto_tipo    = "DESDE " . $_GET['desde'];
    $hoy           = '';
}else if (!isset($_GET['desde']) && isset($_GET['hasta'])) {
    $datos['hasta'] = $_GET['hasta'];
    $texto_tipo    = "DESDE " . $fecha_actual . ' HASTA ' . $_GET['hasta'];
    $hoy           = '';
}else if (isset($_GET['desde']) && isset($_GET['hasta'])) {
    $datos['desde'] = $_GET['desde'];
    $datos['hasta'] = $_GET['hasta'];
    $texto_tipo    = "DESDE " . $_GET['desde'] . ' HASTA ' . $_GET['hasta'];
    $hoy           = '';
}

$obj = new ReportePDF();

$result = $obj->getFeha($datos);

for ($i = 0; $i < count($result); $i++) {
    $arr  = array('cedula_p' => $result[$i]['cedula_p']);
    $arr1 = array('nombres' => utf8_decode($result[$i]['nombres']));
    $arr2 = array('consultorio' => utf8_decode($result[$i]['consultorio']));
    $arr3 = array('turno' => utf8_decode($result[$i]['turno']));
    $arr4 = array('fecha' => utf8_decode($result[$i]['fecha']));
    if ($hoy == "hoy") {
        $dtx = array_merge($arr, $arr1, $arr2, $arr3);
    } else {
        $dtx = array_merge($arr, $arr1, $arr2, $arr3, $arr4);
    }

    $data[] = $dtx;
}

if ($hoy == "hoy") {
    $titles = array(
        'cedula_p'    => utf8_decode('<b>Cédula</b>'),
        'nombres'     => utf8_decode('<b>Nombres</b>'),
        'consultorio' => utf8_decode('<b>Consultorio</b>'),
        'turno'       => utf8_decode('<b>Turno</b>'),
    );
} else {

    $titles = array(
        'cedula_p'    => utf8_decode('<b>Cédula</b>'),
        'nombres'     => utf8_decode('<b>Nombres</b>'),
        'consultorio' => utf8_decode('<b>Consultorio</b>'),
        'fecha'       => utf8_decode('<b>Fecha Cita</b>'),
    );
}

$title = "";
$pdf   = new Cezpdf('a4');
$pdf->selectFont('./fonts/Helvetica.afm');
$pdf->ezSetCmMargins(3, 1.65, 1.5, 1.5); // margenes
$pdf->ezStartPageNumbers(300, 5, 6, 'center', 'Pag:{PAGENUM} de {TOTALPAGENUM}', 1);


$all = $pdf->openObject();
$pdf->saveState();
$pdf->setStrokeColor(0, 0, 0, 1);
$pdf->addJpegFromFile('', 25, 760, 540, 'center');
$pdf->addJpegFromFile('', 25, 12, 540);

date_default_timezone_set('America/Caracas');
$pdf->addText(25, 35, 6, "Fecha:" . date("d/m/Y"));
$pdf->addText(75, 35, 6, "Hora:" . date("h:i A"));

$pdf->restoreState();
$pdf->closeObject();
$pdf->addObject($all, 'all');

$txttit = "<b>REPORTE DE CITAS $texto_tipo</b>\n";
//$textos = "Ejemplo de PDF con PHP y MYSQL \n";

$pdf->ezText($txttit, 10, array('justification' => 'center'));
$pdf->ezText("\n", 4);
//$pdf->ezText($textos, 10, array('justification' => 'center'));
//

$options = array(
    'shadeCol'     => array(0.9, 0.9, 0.9), // color de sombra (r, g, b)
    'shadeCol2'    => array(0, 0, 0.4),
    'ShowLines'    => 1, //
    'ShowHeadings' => 0, //
    'shaded'       => 1, // otra linea
    'lineCol'      => array(0.7, 0.7, 0.7), // color linea
    'fontSize'     => 10,
    'textCol'      => array(0.1, 0.1, 0.1), //color texto
    'rowGap'       => 2, // separacion de filas y letras
    'xOrientation' => 'center',
    'width'        => 500,
    'cols'         =>
    array(
        'total' => array('width' => 60, 'justification' => 'right'),
        'porc'  => array('width' => 50, 'justification' => 'right')
    )
);
$pdf->ezTable($data, $titles, "" . utf8_decode($title), $options);
$pdf->ezImage("", 120, 600, 'none', 'center');
$pdf->ezStream();
