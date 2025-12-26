<?php
require('../../fpdflbl.php');

$pdf=new FPDF('P','mm','lbl4x2');
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,6,'¡Hola, Mundo!',0,1,'L');

$pdf->AddPage();

$pdf->Output();
?>
