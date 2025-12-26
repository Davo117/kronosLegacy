<?php
//require('../fpdf/fpdf.php');
require('../../fpdf/fpdflbl.php');
$welcome;
include('../../Database/SQLConnection.php');
$SQL=sqlsrv_query($SQLconn,"SELECT p.CIDPRODUCTO,(SELECT cnombreunidad FROM admunidadesmedidapeso where cidunidad=p.CIDUNIDADBASE) as unidad, CNOMBREPRODUCTO as nombre,CCODIGOPRODUCTO as codigo FROM admProductos p Where CSTATUSPRODUCTO=1");

 $pdf=new FPDF('P','mm','lbl4x2'); 
    $pdf->AddFont('3of9','','free3of9.php');
while($row= sqlsrv_fetch_array($SQL, SQLSRV_FETCH_ASSOC))
{
    $nombre=strtoupper($row['nombre']);
    $pdf->AddPage();

    $pdf->Cell(10,2,'',0,10,'C');
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(100,12,utf8_decode($nombre),0,1,'C'); 
    $pdf->Cell(100,12,utf8_decode($row['unidad']),0,1,'C'); 
    $pdf->SetY(15);
    //$pdf->Cell(4,4,'',0,0,'L');
   // $pdf->SetFont('Arial','B',10);
   // $pdf->Cell(18,4,'Producto',0,1,'L');
	$codigo=$row['codigo'];
       $pdf->SetY(30);
      $pdf->SetFont('3of9','',40);      
      $pdf->Cell(50,5,'',0,0,'R'); 
      $pdf->Cell(16,5,'*'.$codigo.'*',0,1,'R');
      $pdf->Ln(3);
      $pdf->SetFont('Arial','',8); 
      $pdf->Cell(50,5,'',0,0,'R'); 
      $pdf->Cell(2,3,$codigo,0,1,'C'); 

      $pdf->Ln(2);
         if (file_exists('../../pictures/logo-labro.jpg')==true) 
      { $pdf->Image('../../pictures/logo-labro.jpg',2,2,14); }
    
   
}
 $pdf->Output();