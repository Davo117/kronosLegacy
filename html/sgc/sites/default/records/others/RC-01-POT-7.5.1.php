<?php
require('fpdf/fpdf.php');

$pdf = new FPDF('P','mm','letter');
$pdf->AddPage();
$pdf->AliasNbPages();

$pdf->SetFont('Arial','B',8);
$pdf->Cell(45,4,utf8_decode('Elaboró'),0,1,'R');
$pdf->Cell(45,4,utf8_decode('Revisó y Aprobó'),0,1,'R');

$pdf->Ln(-8);

$pdf->SetFont('Arial','I',8);
$pdf->SetFillColor(255,153,0);

$pdf->Cell(47,4,'',0,0,'R');
$pdf->Cell(0.5,4,'',0,0,'R',true);
$pdf->Cell(75.9,4,utf8_decode('Representante de la Dirección'),0,1,'L');
$pdf->Cell(47,4,'',0,0,'R');
$pdf->Cell(0.5,4,'',0,0,'R',true);
$pdf->Cell(75.9,4,utf8_decode('Dirección'),0,1,'L');

$pdf->Ln(-8);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(125,4,utf8_decode('Documento'),0,1,'R');
$pdf->Cell(125,4,utf8_decode('Código'),0,1,'R');
$pdf->Cell(125,4,utf8_decode('Versión'),0,1,'R');
$pdf->Cell(125,4,utf8_decode('Fecha de revisión'),0,1,'R');
$pdf->Cell(125,4,utf8_decode('Responsable'),0,1,'R');

$pdf->Ln(-20);

$pdf->SetFont('Arial','I',8);
$pdf->SetFillColor(255,153,0);

$pdf->Cell(127,4,'',0,0,'R');
$pdf->Cell(0.5,4,'',0,0,'R',true);
$pdf->Cell(75.9,4,utf8_decode('Hoja de Inspección Tanque de electroformado'),0,1,'L');
$pdf->Cell(127,4,'',0,0,'R');
$pdf->Cell(0.5,4,'',0,0,'R',true);
$pdf->Cell(75.9,4,utf8_decode('RC-01-POT-7.5.1'),0,1,'L');
$pdf->Cell(127,4,'',0,0,'R');
$pdf->Cell(0.5,4,'',0,0,'R',true);
$pdf->Cell(75.9,4,utf8_decode('1.0'),0,1,'L');
$pdf->Cell(127,4,'',0,0,'R');
$pdf->Cell(0.5,4,'',0,0,'R',true);
$pdf->Cell(75.9,4,utf8_decode('22 Enero 2014'),0,1,'L');
$pdf->Cell(127,4,'',0,0,'R');
$pdf->Cell(0.5,4,'',0,0,'R',true);
$pdf->Cell(75.9,4,utf8_decode('Laboratorio'),0,1,'L');

$pdf->Ln(10);

$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(204,204,204);

$pdf->Cell(30,4,utf8_decode('Fecha de inspección'),0,0,'L');
$pdf->Cell(40,4,'',0,1,'R',true);

$pdf->Ln(3);

$pdf->Cell(23,4,utf8_decode('Rango de folios'),0,0,'L');
$pdf->Cell(47,4,'',0,1,'R',true);

$pdf->Ln(8);

$pdf->Cell(40,5,utf8_decode(''),0,0,'L');
$pdf->Cell(15,5,utf8_decode('Pass'),0,0,'L');
$pdf->Cell(20,5,utf8_decode('Fail'),0,0,'L');
$pdf->Cell(30,5,utf8_decode('Primer Inspección'),1,1,'L',true);
$pdf->Cell(30,12,utf8_decode('Lectura de QR'),0,1,'L');
$pdf->Cell(30,12,utf8_decode('Lectura de Molécula'),0,1,'L');

$pdf->Ln(6);

$pdf->Cell(40,5,utf8_decode(''),0,0,'L');
$pdf->Cell(15,5,utf8_decode('Pass'),0,0,'L');
$pdf->Cell(20,5,utf8_decode('Fail'),0,0,'L');
$pdf->Cell(30,5,utf8_decode('Segunda Inspección'),1,1,'L',true);
$pdf->Cell(30,12,utf8_decode('Lectura de QR'),0,1,'L');
$pdf->Cell(30,12,utf8_decode('Lectura de Molécula'),0,1,'L');

$pdf->Ln(6);

$pdf->Cell(40,5,utf8_decode(''),0,0,'L');
$pdf->Cell(15,5,utf8_decode('Pass'),0,0,'L');
$pdf->Cell(20,5,utf8_decode('Fail'),0,0,'L');
$pdf->Cell(30,5,utf8_decode('Tercer Inspección'),1,1,'L',true);
$pdf->Cell(30,12,utf8_decode('Lectura de QR'),0,1,'L');
$pdf->Cell(30,12,utf8_decode('Lectura de Molécula'),0,1,'L');

$pdf->Ln(6);

$pdf->Cell(40,5,utf8_decode(''),0,0,'L');
$pdf->Cell(15,5,utf8_decode('Pass'),0,0,'L');
$pdf->Cell(20,5,utf8_decode('Fail'),0,0,'L');
$pdf->Cell(30,5,utf8_decode('Cuarta Inspección'),1,1,'L',true);
$pdf->Cell(30,12,utf8_decode('Lectura de QR'),0,1,'L');
$pdf->Cell(30,12,utf8_decode('Lectura de Molécula'),0,1,'L');

$pdf->Ln(6);

$pdf->Cell(40,5,utf8_decode(''),0,0,'L');
$pdf->Cell(15,5,utf8_decode('Pass'),0,0,'L');
$pdf->Cell(20,5,utf8_decode('Fail'),0,0,'L');
$pdf->Cell(30,5,utf8_decode('Quinta Inspección'),1,1,'L',true);
$pdf->Cell(30,12,utf8_decode('Lectura de QR'),0,1,'L');
$pdf->Cell(30,12,utf8_decode('Lectura de Molécula'),0,1,'L');

$pdf->Rect(85, 64, 120, 24);

$pdf->Rect(50, 66, 8, 8);
$pdf->Rect(65, 66, 8, 8);

$pdf->Rect(50, 78, 8, 8);
$pdf->Rect(65, 78, 8, 8);

$pdf->Rect(85, 99, 120, 24);

$pdf->Rect(50, 101, 8, 8);
$pdf->Rect(65, 101, 8, 8);

$pdf->Rect(50, 113, 8, 8);
$pdf->Rect(65, 113, 8, 8);

$pdf->Rect(85, 134, 120, 24);

$pdf->Rect(50, 136, 8, 8);
$pdf->Rect(65, 136, 8, 8);

$pdf->Rect(50, 148, 8, 8);
$pdf->Rect(65, 148, 8, 8);

$pdf->Rect(85, 169, 120, 24);

$pdf->Rect(50, 171, 8, 8);
$pdf->Rect(65, 171, 8, 8);

$pdf->Rect(50, 183, 8, 8);
$pdf->Rect(65, 183, 8, 8);

$pdf->Rect(85, 204, 120, 24);

$pdf->Rect(50, 206, 8, 8);
$pdf->Rect(65, 206, 8, 8);

$pdf->Rect(50, 218, 8, 8);
$pdf->Rect(65, 218, 8, 8);

$pdf->Ln(17);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(195.9,4,'______________________________________________________',0,1,'C');
$pdf->Cell(195.9,4,'Nombre y firma de quien realizo las inspecciones',0,1,'C');

$pdf->Ln(8);

$pdf->Output('RC-01-POT-7.5.1.pdf', 'D');
?>