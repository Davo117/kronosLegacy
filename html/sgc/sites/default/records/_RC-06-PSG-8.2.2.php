<?php
require('fpdf/fpdf.php');

$pdf = new FPDF('L','mm','letter');
$pdf->AddPage();
$pdf->AliasNbPages();

$pdf->SetFont('Arial','B',12);
$pdf->Cell(139,24,'',1,0,'C');
$pdf->Cell(50,5,utf8_decode('Fecha de versión'),1,0,'C');
$pdf->Cell(70,5,utf8_decode('Responsable'),1,1,'C');

$pdf->SetFont('Arial','',10);
$pdf->Cell(139,7,'',0,0,'C');
$pdf->Cell(50,7,utf8_decode('08 Octubre 2013'),1,0,'C');
$pdf->Cell(70,7,utf8_decode('Representante de la dirección'),1,1,'C');

$pdf->SetFont('Arial','B',12);
$pdf->Cell(139,5,'',0,0,'C');
$pdf->Cell(50,5,utf8_decode('Versión'),1,0,'C');
$pdf->Cell(70,5,utf8_decode('Código'),1,1,'C');

$pdf->SetFont('Arial','',10);
$pdf->Cell(139,7,'',0,0,'C');
$pdf->Cell(50,7,utf8_decode('1.0'),1,0,'C');
$pdf->Cell(70,7,utf8_decode('RC-06-PSG-8.2.2'),1,1,'C');

$pdf->Ln(1);

$pdf->SetFont('Arial','B',14);
$pdf->SetFillColor(200,220,255);
$pdf->Cell(259,8,utf8_decode("Lista de asistencia a reuniones de apertura y cierre"),1,1,'C',true);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(120,9,utf8_decode("Reunión de apertura (fecha y hora): ______________________________"),0,0,'R');
$pdf->Cell(120,9,utf8_decode("No. de auditoria: ______________________________"),0,1,'R');
$pdf->Cell(120,5,utf8_decode("Reunión de cierre (fecha y hora): ______________________________"),0,1,'R');

$pdf->Ln(1);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(69,10,utf8_decode("Nombre"),1,0,'C',true);
$pdf->Cell(65,10,utf8_decode("Puesto"),1,0,'C',true);
$pdf->Cell(65,10,utf8_decode("Firma"),1,0,'C',true);
$pdf->Cell(60,10,utf8_decode("Tipo de reunión"),1,1,'C',true);

$pdf->Cell(69,14,utf8_decode(""),1,0,'C');
$pdf->Cell(65,14,utf8_decode(""),1,0,'C');
$pdf->Cell(65,7,utf8_decode(""),1,0,'C');
$pdf->Cell(40,7,utf8_decode("            Apertura"),0,1,'L');

$pdf->Cell(134,7,utf8_decode(""),0,0,'C');
$pdf->Cell(65,7,utf8_decode(""),1,0,'C');
$pdf->Cell(40,7,utf8_decode("            Cierre"),0,1,'L');

$pdf->Ln(-14);
$pdf->Cell(259,14,utf8_decode(""),1,1,'C');

$pdf->Cell(69,14,utf8_decode(""),1,0,'C');
$pdf->Cell(65,14,utf8_decode(""),1,0,'C');
$pdf->Cell(65,7,utf8_decode(""),1,0,'C');
$pdf->Cell(40,7,utf8_decode("            Apertura"),0,1,'L');

$pdf->Cell(134,7,utf8_decode(""),0,0,'C');
$pdf->Cell(65,7,utf8_decode(""),1,0,'C');
$pdf->Cell(40,7,utf8_decode("            Cierre"),0,1,'L');

$pdf->Ln(-14);
$pdf->Cell(259,14,utf8_decode(""),1,1,'C');

$pdf->Cell(69,14,utf8_decode(""),1,0,'C');
$pdf->Cell(65,14,utf8_decode(""),1,0,'C');
$pdf->Cell(65,7,utf8_decode(""),1,0,'C');
$pdf->Cell(40,7,utf8_decode("            Apertura"),0,1,'L');

$pdf->Cell(134,7,utf8_decode(""),0,0,'C');
$pdf->Cell(65,7,utf8_decode(""),1,0,'C');
$pdf->Cell(40,7,utf8_decode("            Cierre"),0,1,'L');

$pdf->Ln(-14);
$pdf->Cell(259,14,utf8_decode(""),1,1,'C');

$pdf->Cell(69,14,utf8_decode(""),1,0,'C');
$pdf->Cell(65,14,utf8_decode(""),1,0,'C');
$pdf->Cell(65,7,utf8_decode(""),1,0,'C');
$pdf->Cell(40,7,utf8_decode("            Apertura"),0,1,'L');

$pdf->Cell(134,7,utf8_decode(""),0,0,'C');
$pdf->Cell(65,7,utf8_decode(""),1,0,'C');
$pdf->Cell(40,7,utf8_decode("            Cierre"),0,1,'L');

$pdf->Ln(-14);
$pdf->Cell(259,14,utf8_decode(""),1,1,'C');

$pdf->Cell(69,14,utf8_decode(""),1,0,'C');
$pdf->Cell(65,14,utf8_decode(""),1,0,'C');
$pdf->Cell(65,7,utf8_decode(""),1,0,'C');
$pdf->Cell(40,7,utf8_decode("            Apertura"),0,1,'L');

$pdf->Cell(134,7,utf8_decode(""),0,0,'C');
$pdf->Cell(65,7,utf8_decode(""),1,0,'C');
$pdf->Cell(40,7,utf8_decode("            Cierre"),0,1,'L');

$pdf->Ln(-14);
$pdf->Cell(259,14,utf8_decode(""),1,1,'C');

$pdf->Cell(69,14,utf8_decode(""),1,0,'C');
$pdf->Cell(65,14,utf8_decode(""),1,0,'C');
$pdf->Cell(65,7,utf8_decode(""),1,0,'C');
$pdf->Cell(40,7,utf8_decode("            Apertura"),0,1,'L');

$pdf->Cell(134,7,utf8_decode(""),0,0,'C');
$pdf->Cell(65,7,utf8_decode(""),1,0,'C');
$pdf->Cell(40,7,utf8_decode("            Cierre"),0,1,'L');

$pdf->Ln(-14);
$pdf->Cell(259,14,utf8_decode(""),1,1,'C');

$pdf->Cell(69,14,utf8_decode(""),1,0,'C');
$pdf->Cell(65,14,utf8_decode(""),1,0,'C');
$pdf->Cell(65,7,utf8_decode(""),1,0,'C');
$pdf->Cell(40,7,utf8_decode("            Apertura"),0,1,'L');

$pdf->Cell(134,7,utf8_decode(""),0,0,'C');
$pdf->Cell(65,7,utf8_decode(""),1,0,'C');
$pdf->Cell(40,7,utf8_decode("            Cierre"),0,1,'L');

$pdf->Ln(-14);
$pdf->Cell(259,14,utf8_decode(""),1,1,'C');

$pdf->Cell(69,14,utf8_decode(""),1,0,'C');
$pdf->Cell(65,14,utf8_decode(""),1,0,'C');
$pdf->Cell(65,7,utf8_decode(""),1,0,'C');
$pdf->Cell(40,7,utf8_decode("            Apertura"),0,1,'L');

$pdf->Cell(134,7,utf8_decode(""),0,0,'C');
$pdf->Cell(65,7,utf8_decode(""),1,0,'C');
$pdf->Cell(40,7,utf8_decode("            Cierre"),0,1,'L');

$pdf->Ln(-14);
$pdf->Cell(259,14,utf8_decode(""),1,1,'C');

$pdf->Cell(69,14,utf8_decode(""),1,0,'C');
$pdf->Cell(65,14,utf8_decode(""),1,0,'C');
$pdf->Cell(65,7,utf8_decode(""),1,0,'C');
$pdf->Cell(40,7,utf8_decode("            Apertura"),0,1,'L');

$pdf->Cell(134,7,utf8_decode(""),0,0,'C');
$pdf->Cell(65,7,utf8_decode(""),1,0,'C');
$pdf->Cell(40,7,utf8_decode("            Cierre"),0,1,'L');

$pdf->Ln(-14);
$pdf->Cell(259,14,utf8_decode(""),1,1,'C');

$pdf->Ln(1);

$pdf->Rect(215,69.5,5,5);
$pdf->Rect(215,75.5,5,5);

$pdf->Rect(215,83.5,5,5);
$pdf->Rect(215,89.5,5,5);

$pdf->Rect(215,97.5,5,5);
$pdf->Rect(215,103.5,5,5);

$pdf->Rect(215,111.5,5,5);
$pdf->Rect(215,117.5,5,5);

$pdf->Rect(215,125.5,5,5);
$pdf->Rect(215,131.5,5,5);

$pdf->Rect(215,139.5,5,5);
$pdf->Rect(215,145.5,5,5);

$pdf->Rect(215,153.5,5,5);
$pdf->Rect(215,159.5,5,5);

$pdf->Rect(215,167.5,5,5);
$pdf->Rect(215,173.5,5,5);

$pdf->Rect(215,181.5,5,5);
$pdf->Rect(215,187.5,5,5);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(121,10,utf8_decode("Elaboró: (Representante de la Dirección)"),1,0,'T');
$pdf->Cell(138,10,utf8_decode("Revisó y Aprobó: (Dirección)"),1,1,'T');

if (file_exists('logo.png')==true)
{ $pdf->Image('logo.png',12,12,0,20); }

$pdf->Output('RC-06-PSG-8.2.2.pdf', 'D');
?>