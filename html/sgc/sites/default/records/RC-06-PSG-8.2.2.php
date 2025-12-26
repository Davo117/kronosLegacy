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
$pdf->Cell(259,8,utf8_decode("Informe final de auditoria"),1,1,'C',true);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(120,8,utf8_decode("Responsable de auditoria: __________________________________________"),0,0,'R');
$pdf->Cell(85,8,utf8_decode("Fecha de apertura: ______________"),0,0,'R');
$pdf->Cell(54,8,utf8_decode("Fecha de auditoria: ______________"),0,1,'R');

$pdf->Cell(120,6,utf8_decode(""),0,0,'R');
$pdf->Cell(85,6,utf8_decode("Fecha de cierre: ______________"),0,0,'R');
$pdf->Cell(54,6,utf8_decode("No. de auditoria: ______________"),0,1,'R');

$pdf->Cell(10,5,utf8_decode("Objetivo:"),0,1,'L');
$pdf->Ln(-5);
$pdf->Cell(259,12,utf8_decode(""),1,1,'L');

$pdf->Cell(10,5,utf8_decode("Alcalce:"),0,1,'L');
$pdf->Ln(-5);
$pdf->Cell(259,12,utf8_decode(""),1,1,'L');

$pdf->Ln(1);

$pdf->Cell(259,5,utf8_decode("Resultados de la auditoria"),1,1,'C',true);

$pdf->Cell(159,8,utf8_decode(""),1,0,'C');
$pdf->Cell(25,8,utf8_decode(""),1,0,'C');
$pdf->Cell(25,8,utf8_decode(""),1,0,'C');
$pdf->Cell(25,8,utf8_decode(""),1,0,'C');
$pdf->Cell(25,8,utf8_decode(""),1,1,'C');

$pdf->Cell(159,5,utf8_decode(""),1,0,'C');
$pdf->Cell(25,5,utf8_decode(""),1,0,'C');
$pdf->Cell(25,5,utf8_decode(""),1,0,'C');
$pdf->Cell(25,5,utf8_decode(""),1,0,'C');
$pdf->Cell(25,5,utf8_decode(""),1,1,'C');

$pdf->Cell(159,5,utf8_decode(""),1,0,'C');
$pdf->Cell(25,5,utf8_decode(""),1,0,'C');
$pdf->Cell(25,5,utf8_decode(""),1,0,'C');
$pdf->Cell(25,5,utf8_decode(""),1,0,'C');
$pdf->Cell(25,5,utf8_decode(""),1,1,'C');

$pdf->Cell(159,5,utf8_decode(""),1,0,'C');
$pdf->Cell(25,5,utf8_decode(""),1,0,'C');
$pdf->Cell(25,5,utf8_decode(""),1,0,'C');
$pdf->Cell(25,5,utf8_decode(""),1,0,'C');
$pdf->Cell(25,5,utf8_decode(""),1,1,'C');

$pdf->Cell(159,5,utf8_decode(""),1,0,'C');
$pdf->Cell(25,5,utf8_decode(""),1,0,'C');
$pdf->Cell(25,5,utf8_decode(""),1,0,'C');
$pdf->Cell(25,5,utf8_decode(""),1,0,'C');
$pdf->Cell(25,5,utf8_decode(""),1,1,'C');

$pdf->Cell(159,5,utf8_decode(""),1,0,'C');
$pdf->Cell(25,5,utf8_decode(""),1,0,'C');
$pdf->Cell(25,5,utf8_decode(""),1,0,'C');
$pdf->Cell(25,5,utf8_decode(""),1,0,'C');
$pdf->Cell(25,5,utf8_decode(""),1,1,'C');

$pdf->Cell(159,5,utf8_decode("Totales: "),0,0,'R');
$pdf->Cell(25,5,utf8_decode(""),1,0,'C');
$pdf->Cell(25,5,utf8_decode(""),1,0,'C');
$pdf->Cell(25,5,utf8_decode(""),1,0,'C');
$pdf->Cell(25,5,utf8_decode(""),1,1,'C');

$pdf->Ln(1);

$pdf->Cell(259,5,utf8_decode("Hallazgos"),1,1,'C',true);

$pdf->Cell(10,8,utf8_decode(""),1,0,'C');
$pdf->Cell(45,8,utf8_decode(""),1,0,'C');
$pdf->Cell(40,8,utf8_decode(""),1,0,'C');
$pdf->Cell(74,8,utf8_decode(""),1,0,'C');
$pdf->Cell(25,8,utf8_decode(""),1,0,'C');
$pdf->Cell(25,8,utf8_decode(""),1,0,'C');
$pdf->Cell(40,8,utf8_decode(""),1,1,'C');

$pdf->Cell(10,8,utf8_decode(""),1,0,'C');
$pdf->Cell(45,8,utf8_decode(""),1,0,'C');
$pdf->Cell(40,8,utf8_decode(""),1,0,'C');
$pdf->Cell(74,8,utf8_decode(""),1,0,'C');
$pdf->Cell(25,8,utf8_decode(""),1,0,'C');
$pdf->Cell(25,8,utf8_decode(""),1,0,'C');
$pdf->Cell(40,8,utf8_decode(""),1,1,'C');

$pdf->Cell(10,8,utf8_decode(""),1,0,'C');
$pdf->Cell(45,8,utf8_decode(""),1,0,'C');
$pdf->Cell(40,8,utf8_decode(""),1,0,'C');
$pdf->Cell(74,8,utf8_decode(""),1,0,'C');
$pdf->Cell(25,8,utf8_decode(""),1,0,'C');
$pdf->Cell(25,8,utf8_decode(""),1,0,'C');
$pdf->Cell(40,8,utf8_decode(""),1,1,'C');

$pdf->Cell(10,8,utf8_decode(""),1,0,'C');
$pdf->Cell(45,8,utf8_decode(""),1,0,'C');
$pdf->Cell(40,8,utf8_decode(""),1,0,'C');
$pdf->Cell(74,8,utf8_decode(""),1,0,'C');
$pdf->Cell(25,8,utf8_decode(""),1,0,'C');
$pdf->Cell(25,8,utf8_decode(""),1,0,'C');
$pdf->Cell(40,8,utf8_decode(""),1,1,'C');

$pdf->Cell(10,8,utf8_decode(""),1,0,'C');
$pdf->Cell(45,8,utf8_decode(""),1,0,'C');
$pdf->Cell(40,8,utf8_decode(""),1,0,'C');
$pdf->Cell(74,8,utf8_decode(""),1,0,'C');
$pdf->Cell(25,8,utf8_decode(""),1,0,'C');
$pdf->Cell(25,8,utf8_decode(""),1,0,'C');
$pdf->Cell(40,8,utf8_decode(""),1,1,'C');

$pdf->Cell(10,8,utf8_decode(""),1,0,'C');
$pdf->Cell(45,8,utf8_decode(""),1,0,'C');
$pdf->Cell(40,8,utf8_decode(""),1,0,'C');
$pdf->Cell(74,8,utf8_decode(""),1,0,'C');
$pdf->Cell(25,8,utf8_decode(""),1,0,'C');
$pdf->Cell(25,8,utf8_decode(""),1,0,'C');
$pdf->Cell(40,8,utf8_decode(""),1,1,'C');

$pdf->Ln(1);

$pdf->Cell(259,5,utf8_decode("Conclusiones de la auditoria / Comentarios generales (Fortalezas y áreas de oportunidad)"),1,1,'C',true);
$pdf->Cell(259,9,utf8_decode(""),1,1,'C');

$pdf->Ln(1);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(121,10,utf8_decode("Elaboró: (Representante de la Dirección)"),1,0,'L');
$pdf->Cell(138,10,utf8_decode("Revisó y Aprobó: (Dirección)"),1,1,'L');

$pdf->SetXY(10,87);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(159,8,utf8_decode("Proceso"),1,0,'C');

$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,8,utf8_decode("No conformidad"),0,0,'C');
$pdf->Cell(25,8,utf8_decode("Observaciones"),0,0,'C');
$pdf->Cell(25,4,utf8_decode("Recomendación"),0,0,'C');
$pdf->Cell(25,8,utf8_decode("Total"),0,1,'C');

$pdf->Ln(-4.5);
$pdf->Cell(209,4,utf8_decode(""),0,0,'C');
$pdf->Cell(25,4,utf8_decode("de mejora"),0,1,'C');

$pdf->SetXY(10,131);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(10,8,utf8_decode("#"),0,0,'C');

$pdf->SetFont('Arial','B',8);
$pdf->Cell(45,8,utf8_decode("Proceso"),0,0,'C');
$pdf->Cell(40,8,utf8_decode("Responsable"),0,0,'C');
$pdf->Cell(74,8,utf8_decode("Descripción "),0,0,'C');
$pdf->Cell(25,4,utf8_decode("Clasificación"),0,0,'C');
$pdf->Cell(25,8,utf8_decode("# RAC/RAP"),0,0,'C');
$pdf->Cell(40,4,utf8_decode("Observaciones"),0,1,'C');
$pdf->Ln(-0.5);
$pdf->Cell(169,4,utf8_decode(""),0,0,'C');
$pdf->Cell(25,4,utf8_decode("del hallazgo"),0,0,'C');
$pdf->Cell(25,4,utf8_decode(""),0,0,'C');
$pdf->Cell(40,4,utf8_decode("Seguimiento"),0,1,'C');

if (file_exists('logo.png')==true)
{ $pdf->Image('logo.png',12,12,0,20); }

$pdf->Output('RC-06-PSG-8.2.2.pdf', 'D');
?>