<?php

require_once './tcpdf/tcpdf.php';

class MyClass extends TCPDF
{

    public function Header()
    {
        $this->SetMargins(0, 0, 0);
        $tamano = $this->getPageWidth();
        $this->setJPEGQuality(90);
        $this->Image('imagenes/top.jpg', 0, 0,295, 40, 'JPG', FALSE);
    }

    public function Footer()
    {
        $tamano = $this->getPageWidth();
        date_default_timezone_set('America/Caracas');
        
        $fecha = "Fecha: " . date("d/m/Y h:i A");
        $this->SetY(-8);
        // Set font
        $this->SetFont('FreeSerif', '', 8);
        ///$style = array('width' => 0.30, 'cap' => 'butt', 'join' => 'miter', 'dash' => '2,2,2,2', 'phase' => 5, 'color' => array(0, 0, 0));
        $style = array('width' => 0.30, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        // Page number
        if ($tamano > 220) {
            
            $this->Line(5, 197, 290, 197, $style);
            $this->SetX(10);
            $this->Cell(30, 0, $fecha, 0, false, 'R', 0, '', 0, false, 'T', 'M');
            $this->Cell(265, 0, 'Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
        } else {
            
            $this->Line(5, 285, 300, 285, $style);
            $this->SetX(15);
            $this->Cell(35, 0, $fecha, 0, false, 'R', 0, '', 0, false, 'T', 'M');
            $this->Cell(160, 0, 'Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
        }
    }

}
