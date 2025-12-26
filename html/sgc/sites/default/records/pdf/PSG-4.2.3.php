<?php
  require('fpdf/fpdf.php');

  class PDF extends FPDF
  { // Cabecera de página
    function Header()
    { if (file_exists('../images/logonew.png')==true) 
      { $this->Image('../images/logonew.png',10,10,0,15); }

      $this->SetFillColor(255,153,0);
      
      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Control de documentos'),0,1,'L');
        
      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('PSG-4.2.3'),0,1,'L');
        
      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Versión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('1.0'),0,1,'L');
        
      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Fecha de revisión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Abril 21, 2014'),0,1,'L');
        
      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Responsable'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Representante de la dirección'),0,1,'L'); 
      
      $this->Ln(4.15); }

    // Pie de página
    function Footer()
    {  }
  }
  
  $pdf = new PDF('P','mm','letter');
  $pdf->SetDisplayMode(real, continuous);
  $pdf->AliasNbPages();

  $pdf->AddPage();
  
  ////////////////////////////////////////////////
  $pdf->Output('RC-03-PSG-6.3.pdf', 'D');
?>