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
$pdf->Cell(75.9,4,utf8_decode('Hoja de Inspección Impresión Quindio'),0,1,'L');
$pdf->Cell(127,4,'',0,0,'R');
$pdf->Cell(0.5,4,'',0,0,'R',true);
$pdf->Cell(75.9,4,utf8_decode('RC-01-POT-7.5.4 Quindio'),0,1,'L');
$pdf->Cell(127,4,'',0,0,'R');
$pdf->Cell(0.5,4,'',0,0,'R',true);
$pdf->Cell(75.9,4,utf8_decode('1.0'),0,1,'L');
$pdf->Cell(127,4,'',0,0,'R');
$pdf->Cell(0.5,4,'',0,0,'R',true);
$pdf->Cell(75.9,4,utf8_decode('30 Enero 2014'),0,1,'L');
$pdf->Cell(127,4,'',0,0,'R');
$pdf->Cell(0.5,4,'',0,0,'R',true);
$pdf->Cell(75.9,4,utf8_decode('Impresor en flexografia'),0,1,'L');

$pdf->Ln(10);

$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(204,204,204);

$pdf->Cell(40,4,utf8_decode('Fecha y hora de inspección'),0,0,'L');
$pdf->Cell(40,4,'',0,1,'R',true);

$pdf->Ln(8);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(45,5,utf8_decode('Sensor de molécula'),0,1,'L');

$pdf->SetFont('Arial','B',8);
$pdf->Cell(45,5,utf8_decode('[   ] HVX1500'),0,0,'L');
$pdf->Cell(25,5,utf8_decode('Número de serie'),0,0,'L');
$pdf->Cell(25,4,utf8_decode(''),0,1,'L',true);

$pdf->Ln(5);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(45,5,utf8_decode('Sustrato'),0,1,'L');

$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,utf8_decode('[   ]  BOPP'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('[   ]  Ancho 230 +/- 1 milímetros'),0,0,'L');
$pdf->Cell(25,5,utf8_decode('[   ]  Enhebrado'),0,0,'L');
$pdf->Cell(50,5,utf8_decode('[   ]  Tensión de alimentación 40 N'),0,0,'L');
$pdf->Cell(25,5,utf8_decode('[   ]  Tensión de recepción 50 N'),0,1,'L');


$pdf->Ln(5);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(45,5,utf8_decode('Holograma Quindio'),0,1,'L');

$pdf->SetFont('Arial','B',8);
$pdf->Cell(45,5,utf8_decode('[   ]  Tylex'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('[   ]  Ancho 80 +/- 1 milímetros '),0,0,'L');
$pdf->Cell(45,5,utf8_decode('[   ]  Embobinado y montado'),0,1,'L');

$pdf->Ln(5);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(25,5,utf8_decode('Unidad 1'),0,0,'L');

$pdf->SetFont('Arial','B',8);
$pdf->Cell(45,5,utf8_decode(' (Temperatura en operación 50 grados)'),0,1,'L');
$pdf->Cell(45,5,utf8_decode('[   ]  Anilox 700 lineas'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('[   ]  Cliché (1)'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('[   ]  Tinta Cyan'),0,1,'L');

$pdf->Ln(5);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(25,5,utf8_decode('Unidad 2'),0,0,'L');

$pdf->SetFont('Arial','B',8);
$pdf->Cell(45,5,utf8_decode(' (Temperatura en operación 100 grados)'),0,1,'L');
$pdf->Cell(45,5,utf8_decode('[   ]  Anilox 350 lineas'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('[   ]  Cliché (2)'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('[   ]  Tinta Magenta'),0,1,'L');

$pdf->Ln(5);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(25,5,utf8_decode('Unidad 3'),0,0,'L');

$pdf->SetFont('Arial','B',8);
$pdf->Cell(45,5,utf8_decode(' (Temperatura en operación 50 grados)'),0,1,'L');
$pdf->Cell(45,5,utf8_decode('[   ]  Anilox 800 lineas'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('[   ]  Cliché (3)'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('[   ]  Tinta Amarillo'),0,1,'L');

$pdf->Ln(5);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(25,5,utf8_decode('Unidad 4'),0,0,'L');

$pdf->SetFont('Arial','B',8);
$pdf->Cell(45,5,utf8_decode(' (Temperatura en operación 50 grados)'),0,1,'L');
$pdf->Cell(45,5,utf8_decode('[   ]  Anilox 900 lineas'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('[   ]  Cliché (4)'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('[   ]  Tinta Negro'),0,1,'L');

$pdf->Ln(5);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(25,5,utf8_decode('Unidad 5'),0,1,'L');

$pdf->SetFont('Arial','B',8);
$pdf->Cell(45,5,utf8_decode('[   ]  Anilox 250 lineas'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('[   ]  Cliché (5)'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('[   ]  Barniz UV'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('[   ]  Lampara UV High'),0,1,'L');

$pdf->Ln(5);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(45,5,utf8_decode('Unidad 6'),0,1,'L');

$pdf->SetFont('Arial','B',8);
$pdf->Cell(45,5,utf8_decode('[   ]  Rodillo Magnetico (80)'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('[   ]  Troquél Quindio'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('[   ]  Colocado / Verificar dirección'),0,1,'L');

$pdf->Ln(5);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(45,5,utf8_decode('*** La velocidad máxima de operación es de 20 metros por segundo, si la velocidad en menor o igual a 15 se recomienda bajar temperatura en hornos.'),0,0,'L');

$pdf->Ln(15);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(45,5,utf8_decode('Trabajos abalados con esta inspección.'),0,1,'L');
$pdf->Cell(45,5,utf8_decode('No 01 ____________________'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('No 02 ____________________'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('No 03 ____________________'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('No 04 ____________________'),0,1,'L');
$pdf->Cell(45,5,utf8_decode('No 05 ____________________'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('No 06 ____________________'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('No 07 ____________________'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('No 08 ____________________'),0,1,'L');
$pdf->Cell(45,5,utf8_decode('No 09 ____________________'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('No 10 ____________________'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('No 11 ____________________'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('No 12 ____________________'),0,1,'L');
$pdf->Cell(45,5,utf8_decode('No 13 ____________________'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('No 14 ____________________'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('No 15 ____________________'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('No 16 ____________________'),0,1,'L');
$pdf->Cell(45,5,utf8_decode('No 17 ____________________'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('No 18 ____________________'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('No 19 ____________________'),0,0,'L');
$pdf->Cell(45,5,utf8_decode('No 20 ____________________'),0,1,'L');

$pdf->Ln(17);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(195.9,4,'______________________________________________________',0,1,'C');
$pdf->Cell(195.9,4,'Nombre y firma de quien realizo las inspecciones',0,1,'C');

$pdf->Output('RC-01-POT-7.5.4 Quindio.pdf', 'D');
?>