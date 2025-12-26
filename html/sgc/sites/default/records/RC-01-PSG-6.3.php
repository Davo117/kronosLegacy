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
      $this->Cell(75,4,utf8_decode('Estación de trabajo'),0,1,'L');
      
      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('RC-01-PSG-6.3'),0,1,'L');
      
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
      $this->Cell(75,4,utf8_decode('Responsables de Mantenimiento o TI'),0,1,'L'); 

      $this->Ln(4.15); }

    // Pie de página
    function Footer()
    { $this->SetY(-15);
      $this->Line(69,$this->GetY(),147,$this->GetY());
      
      $this->SetFont('Arial','B',8);
      $this->Cell(0,4.5,utf8_decode('Nombre y firma de quien realiza el registro'),0,1,'C');
      
      $this->SetY(-7.5);
      $this->SetFont('Arial','',8);
      $this->Cell(0,6,utf8_decode('Grupo Labro | Sistema de Gestión de Calidad, página '.$this->PageNo().' de {nb}'),0,1,'R'); }
  }

  $pdf = new PDF('P','mm','letter');
  $pdf->SetDisplayMode(real, continuous);
  $pdf->AliasNbPages();

  $pdf->AddPage();
  $pdf->SetFillColor(255,153,0);

  $pdf->Cell(30,4.15,utf8_decode('Código'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,1,'R',true);
  
  $pdf->Line(41,$pdf->GetY(),81,$pdf->GetY());
  
  $pdf->Cell(30,4.15,utf8_decode('Estación de trabajo'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,1,'R',true);
  
  $pdf->Line(41,$pdf->GetY(),125,$pdf->GetY());
  
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(40,8.3,utf8_decode('Caracteristicas'),0,1,'L');
  
  for ($idLn = 0; $idLn<=21; $idLn++)
  { $pdf->Ln(4.15);  
    $pdf->Line(20,$pdf->GetY(),105,$pdf->GetY());
    $pdf->Line(120,$pdf->GetY(),205,$pdf->GetY()); }

  $pdf->Ln(4.15);
  
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(40,8.3,utf8_decode('Componentes'),0,1,'L');
  
  for ($idLn = 0; $idLn<=16; $idLn++)
  { $pdf->Ln(4.15);  
    $pdf->Line(20,$pdf->GetY(),105,$pdf->GetY());
    $pdf->Line(120,$pdf->GetY(),205,$pdf->GetY()); }
  
  ////////////////////////////////////////////////
  $pdf->Output('RC-01-PSG-6.3.pdf', 'D');
?>