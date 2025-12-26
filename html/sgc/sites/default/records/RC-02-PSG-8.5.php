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
$pdf->Cell(70,7,utf8_decode('RC-02-PSG-8.5'),1,1,'C');

$pdf->Ln(1);

$pdf->SetFont('Arial','B',14);
$pdf->SetFillColor(200,220,255);
$pdf->Cell(259,8,utf8_decode("Plan de auditorias internas"),1,1,'C',true);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(181,6,utf8_decode("Objetivo:"),1,0,'T');
$pdf->Cell(78,6,utf8_decode("Fecha de reunión de apertura:"),1,1,'T');
$pdf->Cell(181,6,utf8_decode("Alcance:"),1,0,'T');
$pdf->Cell(78,6,utf8_decode("Fecha de reunión de cierre:"),1,1,'T');
$pdf->Cell(181,6,utf8_decode("Periodo que cubre:"),1,0,'T');
$pdf->Cell(78,12,utf8_decode(""),1,1,'T');
$pdf->SetXY(10,61);
$pdf->Cell(181,6,utf8_decode("Criterio de la auditoria:"),1,1,'T');

$pdf->Ln(1);

$pdf->Cell(30,5,utf8_decode("Nombre del proceso"),1,0,'C',true);
$pdf->Cell(39,5,utf8_decode("Referencia de documentos"),1,0,'C',true);
$pdf->Cell(42,5,utf8_decode("RAC/RAP previas que revisar"),1,0,'C',true);
$pdf->Cell(33,5,utf8_decode("Nombre del auditado"),1,0,'C',true);
$pdf->Cell(33,5,utf8_decode("Nombre del auditor"),1,0,'C',true);
$pdf->Cell(16,5,utf8_decode("Fecha"),1,0,'C',true);
$pdf->Cell(16,5,utf8_decode("Hora"),1,0,'C',true);
$pdf->Cell(25,5,utf8_decode("Firma auditado"),1,0,'C',true);
$pdf->Cell(25,5,utf8_decode("Firma auditor"),1,1,'C',true);

$pdf->Cell(30,12,utf8_decode(""),1,0,'C');
$pdf->Cell(39,12,utf8_decode(""),1,0,'C');
$pdf->Cell(42,12,utf8_decode(""),1,0,'C');
$pdf->Cell(33,12,utf8_decode(""),1,0,'C');
$pdf->Cell(33,12,utf8_decode(""),1,0,'C');
$pdf->Cell(16,12,utf8_decode(""),1,0,'C');
$pdf->Cell(16,12,utf8_decode(""),1,0,'C');
$pdf->Cell(25,12,utf8_decode(""),1,0,'C');
$pdf->Cell(25,12,utf8_decode(""),1,1,'C');

$pdf->Cell(30,12,utf8_decode(""),1,0,'C');
$pdf->Cell(39,12,utf8_decode(""),1,0,'C');
$pdf->Cell(42,12,utf8_decode(""),1,0,'C');
$pdf->Cell(33,12,utf8_decode(""),1,0,'C');
$pdf->Cell(33,12,utf8_decode(""),1,0,'C');
$pdf->Cell(16,12,utf8_decode(""),1,0,'C');
$pdf->Cell(16,12,utf8_decode(""),1,0,'C');
$pdf->Cell(25,12,utf8_decode(""),1,0,'C');
$pdf->Cell(25,12,utf8_decode(""),1,1,'C');

$pdf->Cell(30,12,utf8_decode(""),1,0,'C');
$pdf->Cell(39,12,utf8_decode(""),1,0,'C');
$pdf->Cell(42,12,utf8_decode(""),1,0,'C');
$pdf->Cell(33,12,utf8_decode(""),1,0,'C');
$pdf->Cell(33,12,utf8_decode(""),1,0,'C');
$pdf->Cell(16,12,utf8_decode(""),1,0,'C');
$pdf->Cell(16,12,utf8_decode(""),1,0,'C');
$pdf->Cell(25,12,utf8_decode(""),1,0,'C');
$pdf->Cell(25,12,utf8_decode(""),1,1,'C');

$pdf->Cell(30,12,utf8_decode(""),1,0,'C');
$pdf->Cell(39,12,utf8_decode(""),1,0,'C');
$pdf->Cell(42,12,utf8_decode(""),1,0,'C');
$pdf->Cell(33,12,utf8_decode(""),1,0,'C');
$pdf->Cell(33,12,utf8_decode(""),1,0,'C');
$pdf->Cell(16,12,utf8_decode(""),1,0,'C');
$pdf->Cell(16,12,utf8_decode(""),1,0,'C');
$pdf->Cell(25,12,utf8_decode(""),1,0,'C');
$pdf->Cell(25,12,utf8_decode(""),1,1,'C');

$pdf->Cell(30,12,utf8_decode(""),1,0,'C');
$pdf->Cell(39,12,utf8_decode(""),1,0,'C');
$pdf->Cell(42,12,utf8_decode(""),1,0,'C');
$pdf->Cell(33,12,utf8_decode(""),1,0,'C');
$pdf->Cell(33,12,utf8_decode(""),1,0,'C');
$pdf->Cell(16,12,utf8_decode(""),1,0,'C');
$pdf->Cell(16,12,utf8_decode(""),1,0,'C');
$pdf->Cell(25,12,utf8_decode(""),1,0,'C');
$pdf->Cell(25,12,utf8_decode(""),1,1,'C');

$pdf->Cell(30,12,utf8_decode(""),1,0,'C');
$pdf->Cell(39,12,utf8_decode(""),1,0,'C');
$pdf->Cell(42,12,utf8_decode(""),1,0,'C');
$pdf->Cell(33,12,utf8_decode(""),1,0,'C');
$pdf->Cell(33,12,utf8_decode(""),1,0,'C');
$pdf->Cell(16,12,utf8_decode(""),1,0,'C');
$pdf->Cell(16,12,utf8_decode(""),1,0,'C');
$pdf->Cell(25,12,utf8_decode(""),1,0,'C');
$pdf->Cell(25,12,utf8_decode(""),1,1,'C');

$pdf->Cell(30,12,utf8_decode(""),1,0,'C');
$pdf->Cell(39,12,utf8_decode(""),1,0,'C');
$pdf->Cell(42,12,utf8_decode(""),1,0,'C');
$pdf->Cell(33,12,utf8_decode(""),1,0,'C');
$pdf->Cell(33,12,utf8_decode(""),1,0,'C');
$pdf->Cell(16,12,utf8_decode(""),1,0,'C');
$pdf->Cell(16,12,utf8_decode(""),1,0,'C');
$pdf->Cell(25,12,utf8_decode(""),1,0,'C');
$pdf->Cell(25,12,utf8_decode(""),1,1,'C');

$pdf->Cell(30,12,utf8_decode(""),1,0,'C');
$pdf->Cell(39,12,utf8_decode(""),1,0,'C');
$pdf->Cell(42,12,utf8_decode(""),1,0,'C');
$pdf->Cell(33,12,utf8_decode(""),1,0,'C');
$pdf->Cell(33,12,utf8_decode(""),1,0,'C');
$pdf->Cell(16,12,utf8_decode(""),1,0,'C');
$pdf->Cell(16,12,utf8_decode(""),1,0,'C');
$pdf->Cell(25,12,utf8_decode(""),1,0,'C');
$pdf->Cell(25,12,utf8_decode(""),1,1,'C');

$pdf->Cell(30,12,utf8_decode(""),1,0,'C');
$pdf->Cell(39,12,utf8_decode(""),1,0,'C');
$pdf->Cell(42,12,utf8_decode(""),1,0,'C');
$pdf->Cell(33,12,utf8_decode(""),1,0,'C');
$pdf->Cell(33,12,utf8_decode(""),1,0,'C');
$pdf->Cell(16,12,utf8_decode(""),1,0,'C');
$pdf->Cell(16,12,utf8_decode(""),1,0,'C');
$pdf->Cell(25,12,utf8_decode(""),1,0,'C');
$pdf->Cell(25,12,utf8_decode(""),1,1,'C');

$pdf->Cell(30,12,utf8_decode(""),1,0,'C');
$pdf->Cell(39,12,utf8_decode(""),1,0,'C');
$pdf->Cell(42,12,utf8_decode(""),1,0,'C');
$pdf->Cell(33,12,utf8_decode(""),1,0,'C');
$pdf->Cell(33,12,utf8_decode(""),1,0,'C');
$pdf->Cell(16,12,utf8_decode(""),1,0,'C');
$pdf->Cell(16,12,utf8_decode(""),1,0,'C');
$pdf->Cell(25,12,utf8_decode(""),1,0,'C');
$pdf->Cell(25,12,utf8_decode(""),1,1,'C');

$pdf->Ln(1);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(121,10,utf8_decode("Elaboró: (Representante de la Dirección)"),1,0,'T');
$pdf->Cell(138,10,utf8_decode("Revisó y Aprobó: (Dirección)"),1,1,'T');

$pdf->SetXY(191,55);
$pdf->Cell(36,7,utf8_decode("No. de auditoria:"),0,0,'L');

$pdf->SetXY(191,61);
$pdf->Cell(36,7,utf8_decode("Cuatro digitos: (XX-XX) Año-Consecutivo"),0,0,'L');

if (file_exists('logo.png')==true)
{ $pdf->Image('logo.png',12,12,0,20); }

$pdf->Output('RC-02-PSG-8.5.pdf', 'D');
?>