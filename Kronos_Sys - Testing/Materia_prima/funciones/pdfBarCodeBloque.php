<?php
  include '../../Database/db.php';
  include '../../fpdf/fpdflbl.php';
  
  $progLote_cdgbloque = $_GET['cdgbloque'];
  $querySelect = $MySQLiconn->query("SELECT idLote, bloque, numeroLote, referenciaLote, tarima, longitud, peso FROM lotes WHERE bloque='".$progLote_cdgbloque."' ORDER BY referenciaLote, numeroLote"); 

if ($querySelect->num_rows > 0){
  $pdf=new FPDF('P','mm','lbl4x2'); 
  //$pdf->SetDisplayMode(real, continuous);
  $pdf->AddFont('3of9','','free3of9.php');

  while ($regQuery = $querySelect->fetch_object()){
    $pdf->AddPage();

    $pdf->SetY(13);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(3,4,'',0,0,'L');
    $pdf->Cell(48,4,'Sustrato',0,1,'L');
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(3,4,'',0,0,'L');
    $pdf->Cell(98,4,$regQuery->bloque,0,1,'L');  

    $pdf->Ln(2);

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(3,4,'',0,0,'L');
    $pdf->Cell(18,4,utf8_decode('Información:'),0,1,'L');

    $pdf->SetFont('Arial','',10);
    $pdf->Cell(3,4,'',0,0,'L');
    $pdf->Cell(18,4,'Longitud',0,0,'L');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,4,number_format($regQuery->longitud,2).' m',0,1,'R'); 

    $pdf->SetFont('Arial','',10);
    $pdf->Cell(3,4,'',0,0,'L');
    $pdf->Cell(18,4,'Peso',0,0,'L');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,4,number_format($regQuery->peso,3).' kgs',0,1,'R');

    $pdf->SetFont('Arial','',10);
    $pdf->Cell(3,4,'',0,0,'L');
    $pdf->Cell(18,4,'Referencia',0,0,'L');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,4,$regQuery->tarima.'|'.$regQuery->numeroLote,0,1,'R');

    $pdf->SetY(-9);     
    $pdf->Cell(3,5,'',0,0,'L');      
    $pdf->SetFont('Arial','B',20);
    $pdf->Cell(0,5,$regQuery->referenciaLote,0,1,'C');
      
    // Código de barras
    $pad=str_pad('020',9,'0');
    $pdf->SetY(4);
    $pdf->SetFont('3of9','',28);      
    $pdf->Cell(80,5,'',0,0,'R'); 
    $pdf->Cell(16,5,'*'.$pad.$regQuery->idLote.'*',0,1,'R');
    $pdf->Ln(1);
    $pdf->SetFont('Arial','',8); 
    $pdf->Cell(50,3,'',0,0,'R'); 
    $pdf->Cell(46,3,$pad.''.$regQuery->idLote,0,1,'C'); 

    if (file_exists('../../pictures/lolo.jpg')==true){ $pdf->Image('../../pictures/lolo.jpg',4,4,27); }
  }
  $pdf->Output();
} 
else{
  echo '<!DOCTYPE html>
  <html>
    <head>
      <meta charset="UTF-8">
      <title>Etiqueta de identificación para lote de materia prima</title>   
    </head>
    <body>
      <label><h1>'.utf8_decode('Referencia inválida.').'</h1></label>
    </body>
  </html>'; 
} ?>