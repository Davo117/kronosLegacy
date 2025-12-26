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
$pdf->Cell(70,7,utf8_decode('RC-04-PSG-8.2.2'),1,1,'C');

$pdf->Ln(1);

$pdf->SetFont('Arial','B',14);
$pdf->SetFillColor(200,220,255);
$pdf->Cell(259,8,utf8_decode("Lista de auditores internos"),1,1,'C',true);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(120,14,utf8_decode("Elaboró: ______________________________________________"),0,0,'R');
$pdf->Cell(120,14,utf8_decode("Fecha de actualización: ______________________________"),0,1,'R');

$pdf->Ln(1);

$pdf->Cell(59,10,utf8_decode(""),1,0,'C',true);
$pdf->Cell(50,10,utf8_decode(""),1,0,'C',true);
$pdf->Cell(50,10,utf8_decode(""),1,0,'C',true);
$pdf->Cell(50,10,utf8_decode(""),1,0,'C',true);
$pdf->Cell(50,10,utf8_decode(""),1,1,'C',true);

$pdf->Cell(59,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,1,'C');

$pdf->Cell(59,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,1,'C');

$pdf->Cell(59,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,1,'C');

$pdf->Cell(59,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,1,'C');

$pdf->Cell(59,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,1,'C');

$pdf->Cell(59,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,1,'C');

$pdf->Cell(59,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,1,'C');

$pdf->Cell(59,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,1,'C');

$pdf->Cell(59,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,0,'C');
$pdf->Cell(50,14,utf8_decode(""),1,1,'C');

$pdf->Ln(1);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(121,10,utf8_decode("Elaboró: (Representante de la Dirección)"),1,0,'T');
$pdf->Cell(138,10,utf8_decode("Revisó y Aprobó: (Dirección)"),1,1,'T');

$pdf->SetXY(10,59);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(59,8,utf8_decode("Nombre del auditor"),0,0,'C');
$pdf->Cell(50,8,utf8_decode("Puesto"),0,0,'C');
$pdf->Cell(50,4,utf8_decode("Número de auditorias"),0,0,'C');
$pdf->Cell(50,4,utf8_decode("Status"),0,0,'C');
$pdf->Cell(50,4,utf8_decode("Calificación de su última"),0,1,'C');

$pdf->Cell(59,4,utf8_decode(""),0,0,'C');
$pdf->Cell(50,4,utf8_decode(""),0,0,'C');
$pdf->Cell(50,4,utf8_decode("en las que ha participados"),0,0,'C');
$pdf->Cell(50,4,utf8_decode("(Entrenamienit, Auxiliar, Lider)"),0,0,'C');
$pdf->Cell(50,4,utf8_decode("evaluación de competencias"),0,1,'C');

if (file_exists('logo.png')==true)
{ $pdf->Image('logo.png',12,12,0,20); }

$pdf->Output('RC-04-PSG-8.2.2.pdf', 'D');
?>