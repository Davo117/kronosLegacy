<?php
require('../fpdf.php');

$pdf=new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
for($x=0 ; $x<25 ; x++)
{
$pdf->Cell(40,10,'¡Hola, Mundo!');}

$pdf->Output();
?>
