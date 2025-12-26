<?php
//require('../fpdf/fpdf.php');
require('../../fpdf/fpdflbl.php');
$regPackingList = base64_decode($_GET['labels']);
$regPackingList = unserialize($regPackingList);

 $pdf=new FPDF('P','mm','lbl4x2'); 
    $pdf->AddFont('3of9','','free3of9.php');
 for ($i=0;$i<count($regPackingList);$i++)
    { 
      $pdf->AddPage();
      $pdf->SetFont('Arial','',8);
      $pdf->Cell(30,10,utf8_decode($regPackingList[$i]['nombre']),0,0,'L');
      // Código de barras
      $pdf->SetY(10);
      $pdf->SetX(6);
      $pdf->SetFont('3of9','',30);
      $pdf->Cell(39,6,'*'.$regPackingList[($i*2)-1]['codigo'].'*',0,0,'C'); 
     
     if(!empty($regPackingList[($i*2)]['codigo']))
      {
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(35,-10,utf8_decode($regPackingList[$i]['nombre']),0,0,'R');
      $pdf->SetFont('3of9','',30);
      $pdf->Cell(-15,6,'',0,0,'C');
      $pdf->Cell(30,6,'*'.$regPackingList[($i*2)]['codigo'].'*',0,1,'C');
      }
      else
      {
      $pdf->ln();
      }
      $pdf->SetFont('Arial','',9); 
      $pdf->Cell(50,4,$regPackingList[($i*2)-1]['codigo'],0,0,'C');


      if(!empty($regPackingList[($i*2)]['codigo']))
      {
     
      $pdf->Cell(5,6,'',0,0,'C');
      $pdf->Cell(50,4,$regPackingList[($i*2)]['codigo'],0,1,'C');

      }
    }
 $pdf->Output();