<?php
require('fpdf/fpdf.php');

$pdf = new FPDF('P','mm','letter');
$pdf->AddPage();
$pdf->AliasNbPages();

$pdf->SetFont('Arial','B',12);
$pdf->Cell(76,24,'',1,0,'C');
$pdf->Cell(50,5,utf8_decode('Fecha de versión'),1,0,'C');
$pdf->Cell(70,5,utf8_decode('Responsable'),1,1,'C');

$pdf->SetFont('Arial','',10);
$pdf->Cell(76,7,'',0,0,'C');
$pdf->Cell(50,7,utf8_decode('08 Octubre 2013'),1,0,'C');
$pdf->Cell(70,7,utf8_decode('Representante de la dirección'),1,1,'C');

$pdf->SetFont('Arial','B',12);
$pdf->Cell(76,5,'',0,0,'C');
$pdf->Cell(50,5,utf8_decode('Versión'),1,0,'C');
$pdf->Cell(70,5,utf8_decode('Código'),1,1,'C');

$pdf->SetFont('Arial','',10);
$pdf->Cell(76,7,'',0,0,'C');
$pdf->Cell(50,7,utf8_decode('1.0'),1,0,'C');
$pdf->Cell(70,7,utf8_decode('RC-03-PSG-8.2.2'),1,1,'C');

$pdf->Ln(1);

$pdf->SetFont('Arial','B',14);
$pdf->SetFillColor(200,220,255);
$pdf->Cell(196,8,utf8_decode("Competencia del auditor"),1,1,'C',true);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(180,3,utf8_decode(""),0,1,'R');
$pdf->Cell(180,7,utf8_decode("Fecha de evaluación: ______________________________"),0,1,'R');

$pdf->Ln(1);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(196,4,utf8_decode("Competencia del auditor"),1,1,'C',true);

$pdf->SetFont('Arial','B',7);
$pdf->Cell(196,6,utf8_decode("Nombre:"),1,1,'L');
$pdf->Cell(196,6,utf8_decode("Nivel educativo:"),1,1,'L');
$pdf->Cell(196,6,utf8_decode("Experiencia laboral total:"),1,1,'L');
$pdf->Cell(196,6,utf8_decode("Horas de formación como auditor:"),1,1,'L');
$pdf->Cell(196,6,utf8_decode("Experiencia en procesos de auditoria:"),1,1,'L');
$pdf->Cell(196,6,utf8_decode("Fecha de ultimo curso de formación de auditores:"),1,1,'L');
$pdf->Cell(196,6,utf8_decode("Calificación obtenida en el curso de formación de auditores:"),1,1,'L');

$pdf->Ln(1);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(132,8,utf8_decode("Evaluación cualitativa (atributos personales)"),1,0,'L',true);
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,1,'C');

$pdf->SetFont('Arial','B',7);
$pdf->Cell(132,8,utf8_decode("Ético (imparcial, sincero, honesto y discreto)"),1,0,'L');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,1,'C');

$pdf->Cell(132,8,utf8_decode("De mentalidad abierta (dispuesto a considerar ideas o puntos de vista alternativos"),1,0,'L');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,1,'C');

$pdf->Cell(132,8,utf8_decode("Diplomático (tacto en las relaciones con las personal)"),1,0,'L');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,1,'C');

$pdf->Cell(132,8,utf8_decode("Observador (consciente del entorno físico y las actividades)"),1,0,'L');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,1,'C');

$pdf->Cell(132,8,utf8_decode("Perceptivo (capaz de entender las situaciones)"),1,0,'L');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,1,'C');

$pdf->Cell(132,8,utf8_decode("Versátil (se adapta fácilmente a diferentes situaciones)"),1,0,'L');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,1,'C');

$pdf->Cell(132,8,utf8_decode("Tenaz (orientado hacia el logro de los objetivos)"),1,0,'L');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,1,'C');

$pdf->Cell(132,8,utf8_decode("Decidido (alcanza conclusones oportunas basadas en el análisis y razonamiento lógico)"),1,0,'L');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,1,'C');

$pdf->Cell(132,8,utf8_decode("Seguro de si mismo (actua y funciona de forma independiente a la vez que se relaciona eficazmente con otros)"),1,0,'L');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,0,'C');
$pdf->Cell(16,8,utf8_decode(""),1,1,'C');

$pdf->Ln(1);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(166,8,utf8_decode("Suma de puntajes: "),0,0,'R');
$pdf->Cell(32,8,utf8_decode(""),1,1,'C');

$pdf->Ln(1);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(196,4,utf8_decode("Parámetros de calificación"),1,1,'C',true);

$pdf->SetFont('Arial','B',7);
$pdf->Cell(38,5,utf8_decode("Suma de puntos entre 36 y 29:"),0,0,'L');
$pdf->SetFont('Arial','',8);
$pdf->Cell(156,5,utf8_decode("Sobrepasa los atributos deseados con su comportamiento."),0,1,'L');

$pdf->SetFont('Arial','B',7);
$pdf->Cell(38,5,utf8_decode("Suma de puntos entre 28 y 20:"),0,0,'L');
$pdf->SetFont('Arial','',8);
$pdf->Cell(156,5,utf8_decode("Cubre adecuadamente los atributos deseados con su comportamiento."),0,1,'L');

$pdf->SetFont('Arial','B',7);
$pdf->Cell(38,5,utf8_decode("Suma de puntos entre 19 y 15:"),0,0,'L');
$pdf->SetFont('Arial','',8);
$pdf->Cell(156,5,utf8_decode("Tiene deficiencia en alguno de los atributos deseados, requiere mejora."),0,1,'L');

$pdf->SetFont('Arial','B',7);
$pdf->Cell(38,5,utf8_decode("Suma de puntos menor a 15:"),0,0,'L');
$pdf->SetFont('Arial','',8);
$pdf->Cell(156,5,utf8_decode("Todos los atributos son diferentes, requiere capacitación."),0,1,'L');

$pdf->SetFont('Arial','B',8);
$pdf->Cell(196,4,utf8_decode("Conclusión de competencia del auditor"),1,1,'C',true);
$pdf->Cell(196,30,utf8_decode(""),1,1,'L');

$pdf->Ln(1);

$pdf->SetFont('Arial','B',7);
$pdf->Cell(196,9,utf8_decode(""),0,1,'C');
$pdf->Cell(98,5,utf8_decode("_____________________________________________________"),0,0,'C');
$pdf->Cell(98,5,utf8_decode("_____________________________________________________"),0,1,'C');
$pdf->Cell(98,5,utf8_decode("Nombre y firma del evaluador"),0,0,'C');
$pdf->Cell(98,5,utf8_decode("Nombre y firma del evaluado"),0,1,'C');

$pdf->SetXY(10,101);
$pdf->SetFont('Arial','B',7);
$pdf->Cell(132,8,utf8_decode(""),0,0,'C');
$pdf->Cell(16,4,utf8_decode("Excelente"),0,0,'C');
$pdf->Cell(16,4,utf8_decode("Bueno"),0,0,'C');
$pdf->Cell(16,4,utf8_decode("Regular"),0,0,'C');
$pdf->Cell(16,4,utf8_decode("Malo"),0,1,'C');

$pdf->Cell(132,4,utf8_decode(""),0,0,'C');
$pdf->Cell(16,4,utf8_decode("(4 puntos)"),0,0,'C');
$pdf->Cell(16,4,utf8_decode("(3 puntos)"),0,0,'C');
$pdf->Cell(16,4,utf8_decode("(2 puntos)"),0,0,'C');
$pdf->Cell(16,4,utf8_decode("(1 puntos)"),0,1,'C');

if (file_exists('logo.png')==true)
{ $pdf->Image('logo.png',12,12,0,20); }

$pdf->Output('RC-03-PSG-8.2.2.pdf', 'D');
?>