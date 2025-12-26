<?php
ob_start();
session_start();
require('../fpdf/fpdf.php');
require('../barcode-generator-2012-04-06/barcode.inc.php');
include('Controlador_Bloques/db_materiaPrima.php');
$numero=$_SESSION['lotesPBloqueImprimir'];
$consul=$MySQLiconn->query("SELECT bloquesmateriaprima.sustrato,lotes.referenciaLote as referenciaLote from lotes inner join bloquesmateriaprima on lotes.bloque=bloquesmateriaprima.nombreBloque where lotes.Bloque='$numero'");
$pdf = new FPDF('p','cm',array(5,4));
$pdf->SetFont('Courier','B',7);
while($row=$consul->fetch_array()){
	$pdf->Ln(); 
$llenar=$row['referenciaLote'];
$llenar=explode("-", $llenar, 3);
$llenar=implode($llenar,1);
$barras=new barCodeGenrator($llenar,1,'test.gif',240,80,true);
$informacion=$row['sustrato'];
$referenciaLote=$row['referenciaLote'];
$pdf->Cell( 10 ,5, $informacion."\n".$referenciaLote);
$pdf->Image('test.gif',3,0.1,3,1);
}
$pdf->Output();
?>
<?php
ob_end_flush();
?>