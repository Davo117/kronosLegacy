<?php
  require('fpdf/fpdf.php');

  class PDF extends FPDF
  { // ENcabezado de página
    function Header()
    { if (file_exists('../images/logo.jpg')==true) 
      { $this->Image('../images/logo.jpg',10,5,0,25); }

      $this->SetFillColor(255,153,0);

      $this->SetFont('Arial','B',8);
      $this->Cell(189,4,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Programa anual de auditorias'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(189,4,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('RC-01-PSG-8.2.2'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(189,4,utf8_decode('Versión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('1.0'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(189,4,utf8_decode('Fecha de revisión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Mayo 20, 2014'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(189,4,utf8_decode('Responsable'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Representante de la dirección'),0,1,'L'); 

      $this->Ln(2);

      $this->SetFont('Arial','B',9);
      $this->Cell(53,5,utf8_decode("Requisitos"),1,0,'C',true);
      $this->Cell(53,5,utf8_decode("Proceso"),1,0,'C',true);
      $this->Cell(53,5,utf8_decode("Responsable"),1,0,'C',true);
      $this->Cell(8.5,5,utf8_decode("Ene"),1,0,'C',true);
      $this->Cell(8.5,5,utf8_decode("Feb"),1,0,'C',true);
      $this->Cell(8.5,5,utf8_decode("Mar"),1,0,'C',true);
      $this->Cell(8.5,5,utf8_decode("Abr"),1,0,'C',true);
      $this->Cell(8.5,5,utf8_decode("May"),1,0,'C',true);
      $this->Cell(8.5,5,utf8_decode("Jun"),1,0,'C',true);
      $this->Cell(8.5,5,utf8_decode("Jul"),1,0,'C',true);
      $this->Cell(8.5,5,utf8_decode("Ago"),1,0,'C',true);
      $this->Cell(8.5,5,utf8_decode("Sep"),1,0,'C',true);
      $this->Cell(8.5,5,utf8_decode("Oct"),1,0,'C',true);
      $this->Cell(8.5,5,utf8_decode("Nov"),1,0,'C',true);
      $this->Cell(8.5,5,utf8_decode("Dic"),1,1,'C',true); }

    // Pie de página
    function Footer()
    { $this->SetFont('arial','',8);
      $this->SetY(-7.5);
      $this->Cell(0,6,utf8_decode('Grupo Labro | Sistema de Gestión de Calidad, página '.$this->PageNo().' de {nb}'),0,1,'R');
    }
  }


$pdf = new PDF('L','mm','letter');
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFillColor(255,153,0);

  for ($row=1; $row<=72; $row++)
  { $pdf->Cell(53,4.15,utf8_decode(""),1,0,'C');
    $pdf->Cell(53,4.15,utf8_decode(""),1,0,'C');
    $pdf->Cell(53,4.15,utf8_decode(""),1,0,'C');
    $pdf->Cell(8.5,4.15,utf8_decode(""),1,0,'C');
    $pdf->Cell(8.5,4.15,utf8_decode(""),1,0,'C');
    $pdf->Cell(8.5,4.15,utf8_decode(""),1,0,'C');
    $pdf->Cell(8.5,4.15,utf8_decode(""),1,0,'C');
    $pdf->Cell(8.5,4.15,utf8_decode(""),1,0,'C');
    $pdf->Cell(8.5,4.15,utf8_decode(""),1,0,'C');
    $pdf->Cell(8.5,4.15,utf8_decode(""),1,0,'C');
    $pdf->Cell(8.5,4.15,utf8_decode(""),1,0,'C');
    $pdf->Cell(8.5,4.15,utf8_decode(""),1,0,'C');
    $pdf->Cell(8.5,4.15,utf8_decode(""),1,0,'C');
    $pdf->Cell(8.5,4.15,utf8_decode(""),1,0,'C');
    $pdf->Cell(8.5,4.15,utf8_decode(""),1,1,'C'); }


  $pdf->SetY(-20);

  $pdf->Line(20,$pdf->GetY(),80,$pdf->GetY());
  $pdf->Line(88,$pdf->GetY(),148,$pdf->GetY());
  $pdf->Line(156,$pdf->GetY(),196,$pdf->GetY());
  $pdf->Line(204,$pdf->GetY(),244,$pdf->GetY());

  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(10,4.15,utf8_decode(''),0,0,'C');
  $pdf->Cell(60,4.15,utf8_decode('Representante de la dirección'),0,0,'C');
  $pdf->Cell(8,4.15,utf8_decode(''),0,0,'C');
  $pdf->Cell(60,4.15,utf8_decode('Dirección'),0,0,'C');
  $pdf->Cell(8,4.15,utf8_decode(''),0,0,'C');
  $pdf->Cell(40,4.15,utf8_decode('Programa para el año'),0,0,'C');
  $pdf->Cell(8,4.15,utf8_decode(''),0,0,'C');
  $pdf->Cell(40,4.15,utf8_decode('Fecha de actualización'),0,1,'C');

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4.15,utf8_decode(''),0,0,'C');
  $pdf->Cell(60,4.15,utf8_decode('Elaboró'),0,0,'C');
  $pdf->Cell(8,4.15,utf8_decode(''),0,0,'C');
  $pdf->Cell(60,4.15,utf8_decode('Revisó y Aprobó'),0,1,'C');

  $pdf->Output('RC-01-PSG-8.2.2.pdf', 'D');
?>