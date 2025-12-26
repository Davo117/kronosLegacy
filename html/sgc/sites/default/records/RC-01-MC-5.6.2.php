<?php
  require('fpdf/fpdf.php');

  class PDF extends FPDF
  { // Cabecera de página
    function Header()
    { $this->SetFillColor(255,153,0);

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Información de entrada para la revisión'),0,1,'L');
  
      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('RC-01-MC-5.6.2'),0,1,'L');

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
      $this->Cell(75,4,utf8_decode('Representante de la Dirección'),0,1,'L'); 

      $this->Ln(4); }

    // Pie de página
    function Footer()
    { $this->SetFont('arial','',8);
      $this->SetY(-7.5);
      $this->Cell(0,6,utf8_decode('Grupo Labro | Sistema de Gestión de Calidad, página '.$this->PageNo().' de {nb}'),0,1,'R'); }
  }

  $pdf = new PDF('P','mm','letter');
  $pdf->SetDisplayMode(real, continuous);
  $pdf->AliasNbPages();

  $pdf->AddPage();
  $pdf->SetFillColor(255,153,0);

  $pdf->SetFont('Arial','I',8);

  $pdf->Cell(45,4.15,utf8_decode('Fecha'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,1,'R',true);
  $pdf->Line(56,$pdf->GetY(),100,$pdf->GetY());
  $pdf->Ln(4.15);

  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(102,4.15,utf8_decode('Elementos de entrada'),0,1,'L');

  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(102,4.15,utf8_decode('1) Los resultados de auditorias internas y externas'),0,1,'L');
  $pdf->Ln(4.15);

  for ($_row = 1; $_row <= 24; $_row++)
  { $pdf->Line(15,$pdf->GetY(),206,$pdf->GetY());
    $pdf->Ln(4.15); }

  $pdf->Cell(102,4.15,utf8_decode('2) La retroalimentación del cliente'),0,1,'L');
  $pdf->Ln(4.15);

  for ($_row = 1; $_row <= 24; $_row++)
  { $pdf->Line(15,$pdf->GetY(),206,$pdf->GetY());
    $pdf->Ln(4.15); }

  $pdf->AddPage();
  $pdf->Cell(102,4.15,utf8_decode('3) El desempeño de los procesos y la conformidad del producto'),0,1,'L');
  $pdf->Ln(4.15);

  for ($_row = 1; $_row <= 27; $_row++)
  { $pdf->Line(15,$pdf->GetY(),206,$pdf->GetY());
    $pdf->Ln(4.15); }

  $pdf->Cell(102,4.15,utf8_decode('4) El estado de las acciones correctivas y preventivas'),0,1,'L');
  $pdf->Ln(4.15);

  for ($_row = 1; $_row <= 24; $_row++)
  { $pdf->Line(15,$pdf->GetY(),206,$pdf->GetY());
    $pdf->Ln(4.15); }

  $pdf->AddPage();
  $pdf->Cell(102,4.15,utf8_decode('5) Las acciones de seguimiento de revisiones por la dirección previas'),0,1,'L');
  $pdf->Ln(4.15);

  for ($_row = 1; $_row <= 27; $_row++)
  { $pdf->Line(15,$pdf->GetY(),206,$pdf->GetY());
    $pdf->Ln(4.15); }

  $pdf->Cell(102,4.15,utf8_decode('6) Los cambios que podrían afectar al Sistema de Gestión de Calidad'),0,1,'L');
  $pdf->Ln(4.15);

  for ($_row = 1; $_row <= 24; $_row++)
  { $pdf->Line(15,$pdf->GetY(),206,$pdf->GetY());
    $pdf->Ln(4.15); }

  $pdf->AddPage();
  $pdf->Cell(102,4.15,utf8_decode('7) Las recomendaciones para la mejora'),0,1,'L');
  $pdf->Ln(4.15);

  for ($_row = 1; $_row <= 27; $_row++)
  { $pdf->Line(15,$pdf->GetY(),206,$pdf->GetY());
    $pdf->Ln(4.15); }

  $pdf->Cell(196,4.15,utf8_decode('8) Información relevante para la dirección, acciones a tomar'),0,1,'L');
  $pdf->Ln(4.15);

  for ($_row = 1; $_row <= 20; $_row++)
  { $pdf->Line(15,$pdf->GetY(),206,$pdf->GetY());
    $pdf->Ln(4.15); }

  $pdf->Ln(12.45);
  $pdf->Cell(24,4.15,utf8_decode('Elaboró'),0,0,'R');
  $pdf->Cell(0.5,4.15,utf8_decode(''),0,0,'R',true);
  $pdf->Cell(50.5,4.15,utf8_decode(''),0,0,'C');
  $pdf->Cell(35,4.15,utf8_decode('Aprobó'),0,0,'R');
  $pdf->Cell(0.5,4.15,utf8_decode(''),0,1,'R',true);

  $pdf->Line(35,$pdf->GetY(),95,$pdf->GetY());
  $pdf->Line(121,$pdf->GetY(),181,$pdf->GetY());
  
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(30,4.15,utf8_decode(''),0,0,'L');
  $pdf->Cell(50,4.15,utf8_decode('Representante de la dirección'),0,0,'C');
  $pdf->Cell(36,4.15,utf8_decode(''),0,0,'L');
  $pdf->Cell(50,4.15,utf8_decode('Director del Sistema de Gestión de la Calidad'),0,0,'C');
  
////////////////////////////////////////////////
  $pdf->Output('RC-01-MC-5.6.2.pdf', 'D');
?>