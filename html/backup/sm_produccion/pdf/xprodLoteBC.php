<?php

  include '../../datos/mysql.php';	
  require('../../fpdf/fpdflbl.php');

	$link_mysqli = conectar();		
	$packingListSelect = $link_mysqli->query("
    SELECT proglote.lote,
      proglote.tarima,
      proglote.idlote,
      prodlote.noop,
      prodlote.longitud,
      prodlote.amplitud,
      prodlote.peso,
      pdtoproyecto.proyecto,
      pdtoimpresion.idimpresion,
      pdtoimpresion.impresion,
      pdtoimpresion.ancho,
      pdtoimpresion.ceja,      
      pdtoimpresion.alpaso,
      pdtoimpresion.tolerancia,
      pdtomezcla.idmezcla,
      pdtomezcla.mezcla,
      prodlote.cdglote
    FROM prodlote, proglote,
      pdtomezcla, pdtoimpresion, pdtoproyecto
    WHERE prodlote.fchprograma = '".$_GET['fchprograma']."' AND 
      prodlote.cdgmezcla = '".$_GET['cdgmezcla']."' AND 
      prodlote.cdgproceso = '".$_GET['cdgproceso']."' AND 
      proglote.cdglote = prodlote.cdglote AND 
      prodlote.cdgmezcla = pdtomezcla.cdgmezcla AND 
      pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion AND 
      pdtoimpresion.cdgproyecto = pdtoproyecto.cdgproyecto AND 
      prodlote.sttlote = '1'"); 

  if ($packingListSelect->num_rows > 0)
  { $pdf=new FPDF('P','mm','lbl4x2'); 
    $pdf->SetDisplayMode(real, continuous);
    $pdf->AddFont('3of9','','free3of9.php');
    
    while ($regPackingList = $packingListSelect->fetch_object())
    { $pdf->AddPage();

      $pdf->SetY(12);
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Longitud',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,number_format($regPackingList->longitud,3),0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Ancho lote',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,number_format($regPackingList->amplitud,3),0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Peso',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,number_format($regPackingList->peso,3),0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Producto',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,$regPackingList->idimpresion,0,1,'R'); 
      $pdf->Cell(3,4,'',0,0,'L');
      $pdf->Cell(48,4,$regPackingList->impresion,0,1,'L'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Formula',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,$regPackingList->idmezcla,0,1,'R'); 
      $pdf->Cell(3,4,'',0,0,'L');
      $pdf->Cell(48,4,$regPackingList->mezcla,0,1,'L'); 
     
      $pdf->SetY(-7);     
      $pdf->Cell(2,5,'',0,0,'L');      
      $pdf->SetFont('Arial','B',20);
      $pdf->Cell(68,5,'NoOP '.$regPackingList->noop,0,1,'R');

      // Información
      $pdf->SetY(15);
      
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->Cell(15,4,'Refilado',0,1,'L');
      
      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(28,4,'Ancho plano',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(18,4,($regPackingList->ancho+$regPackingList->ceja),0,1,'R');
      
      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(28,4,'Bobinas',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(18,4,$regPackingList->alpaso,0,1,'R');
      
      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(28,4,'Tolerancia',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(18,4,$regPackingList->amplitud-(($regPackingList->ancho+$regPackingList->ceja)*$regPackingList->alpaso),0,1,'R');

      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(28,4,'Piezas aprox',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(18,4,number_format($regPackingList->cantidad*1000),0,1,'R');            

      // Código de barras
      $pdf->SetY(4);
      $pdf->SetFont('3of9','',24);      
      $pdf->Cell(78,5,'',0,0,'R'); 
      $pdf->Cell(22,5,'*'.$regPackingList->cdglote.'*',0,1,'R'); 
      $pdf->SetFont('Arial','',8); 
      $pdf->Cell(52,3,'',0,0,'R'); 
      $pdf->Cell(50,3,$regPackingList->cdglote,0,1,'C');

      // Imagen LOGOTIPO
      if (file_exists('../../img_sistema/logo.jpg')==true) 
      { $pdf->Image('../../img_sistema/logo.jpg',2,2,14); }
    }

    $pdf->Output();
  } else
  { echo '<html>
  <head>    
    <link rel="stylesheet" type="text/css" href="../../css/global.css" media="screen" /> 
  </head>
  <body>
  </body>
</html>';}  		 
  
?>
