<?php
  require('fpdf/fpdf.php');

  class PDF extends FPDF
  { // Cabecera de página
    function Header()
    { if (file_exists('../images/logo.jpg')==true) 
      { $this->Image('../images/logo.jpg',10,5,0,25); }
      
      $this->SetFillColor(255,153,0);

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Lista de asistencia'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('RC-05-PSG-6.2'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Versión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('1.0'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Fecha de revisión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Marzo 28, 2014'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Responsable'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Jefe de Recursos Humanos'),0,1,'L');       

      $this->Ln(4.15); }

    // Pie de página
    function Footer()
    { $this->SetFont('Arial','',8);
      $this->SetY(-7.5);
      $this->Cell(0,6,utf8_decode('Grupo Labro | Sistema de Gestión de Calidad, página '.$this->PageNo().' de {nb}'),0,1,'R'); }
  }

  $pdf = new PDF('P','mm','letter');
  $pdf->SetDisplayMode(real, continuous);
  $pdf->AliasNbPages();

  $pdf->AddPage();
  $pdf->SetFillColor(255,153,0);

  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(40.5,4.15,utf8_decode('Fecha'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,0,'R',true);
  $pdf->Cell(79,4.15,utf8_decode(''),0,0,'R');
  $pdf->Cell(40,4.15,utf8_decode('Tipo de evento'),0,1,'C');

  $pdf->Line(52,$pdf->GetY(),105,$pdf->GetY());  

  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(40.5,4.15,utf8_decode('Horario'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,0,'R',true);
  $pdf->Cell(79,4.15,utf8_decode(''),0,0,'R');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'L');
  $pdf->Cell(21,4.15,utf8_decode('Curso'),0,0,'L');
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'L');
  $pdf->Cell(21,4.15,utf8_decode('Difusión'),0,1,'L');

  $pdf->Line(52,$pdf->GetY(),105,$pdf->GetY());
  
  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(40.5,4.15,utf8_decode('No. de Horas'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,0,'R',true);
  $pdf->Cell(79,4.15,utf8_decode(''),0,0,'R');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'L');
  $pdf->Cell(21,4.15,utf8_decode('Aviso'),0,0,'L');
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'L');
  $pdf->Cell(21,4.15,utf8_decode('Otro'),0,1,'L');

  $pdf->Line(52,$pdf->GetY(),105,$pdf->GetY());

  $pdf->Ln(4.15);

  $pdf->SetFont('Arial','I',8);
  $pdf->SetFillColor(255,153,0);

  $pdf->Cell(40.5,4.15,utf8_decode('Evento'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,1,'R',true);
  $pdf->Line(52,$pdf->GetY(),205,$pdf->GetY());
  $pdf->Cell(40.5,4.15,utf8_decode('Imparte'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,1,'R',true);
  $pdf->Line(52,$pdf->GetY(),205,$pdf->GetY());
  $pdf->Cell(40.5,4.15,utf8_decode('Objetivo'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,1,'R',true);
  $pdf->Line(52,$pdf->GetY(),205,$pdf->GetY());

  $pdf->Ln(4.15);

  $pdf->Cell(67,4.15,utf8_decode('Nombre'),0,0,'C',true);
  $pdf->Cell(3,4,utf8_decode(''),0,0,'C');
  $pdf->Cell(37,4.15,utf8_decode('Proceso'),0,0,'C',true);
  $pdf->Cell(3,4,utf8_decode(''),0,0,'C');
  $pdf->Cell(37,4.15,utf8_decode('Puesto'),0,0,'C',true);
  $pdf->Cell(3,4,utf8_decode(''),0,0,'C');
  $pdf->Cell(45,4.15,utf8_decode('Firma'),0,1,'C',true);

  for ($id=1;$id<=20;$id++)
  { $pdf->Cell(5,8.30,$id.'.',0,1,'L'); 

    $pdf->Line(17,$pdf->GetY(),77,$pdf->GetY());
    $pdf->Line(80,$pdf->GetY(),117,$pdf->GetY());
    $pdf->Line(120,$pdf->GetY(),157,$pdf->GetY());
    $pdf->Line(160,$pdf->GetY(),205,$pdf->GetY()); }
    
  
  $pdf->Ln(20.75);

  $pdf->Line(70,$pdf->GetY(),146,$pdf->GetY());
  $pdf->SetFont('Arial','I',8);
  $pdf->MultiCell(0,4.15,utf8_decode('Nombre y firma de quien imparte'),0,'C');
  
  ////////////////////////////////////////////////
	$pdf->Output('RC-05-PSG-6.2.pdf', 'D');
?>