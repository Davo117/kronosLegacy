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
$pdf->Cell(75.9,4,utf8_decode('Hoja de Inspección Quindio'),0,1,'L');
$pdf->Cell(127,4,'',0,0,'R');
$pdf->Cell(0.5,4,'',0,0,'R',true);
$pdf->Cell(75.9,4,utf8_decode('RC-01-POT-7.5.1-Quindio'),0,1,'L');
$pdf->Cell(127,4,'',0,0,'R');
$pdf->Cell(0.5,4,'',0,0,'R',true);
$pdf->Cell(75.9,4,utf8_decode('1.0'),0,1,'L');
$pdf->Cell(127,4,'',0,0,'R');
$pdf->Cell(0.5,4,'',0,0,'R',true);
$pdf->Cell(75.9,4,utf8_decode('22 Enero 2014'),0,1,'L');
$pdf->Cell(127,4,'',0,0,'R');
$pdf->Cell(0.5,4,'',0,0,'R',true);
$pdf->Cell(75.9,4,utf8_decode('Jefe de Calidad'),0,1,'L');

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

 /*
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
*/

//$pdf->SetFont('Arial','B',8);
//$pdf->Cell(121,10,utf8_decode("Elaboró: (Representante de la Dirección)"),1,0,'T');
//$pdf->Cell(138,10,utf8_decode("Revisó y Aprobó: (Dirección)"),1,1,'T');

/*
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
*/
$pdf->Output('RC-01-POT-7.5.1-Quindio.pdf', 'D');
?>