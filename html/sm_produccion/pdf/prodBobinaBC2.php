<?php

  include '../../datos/mysql.php';  
  require('../../fpdf/fpdflbl.php');

  $pdf=new FPDF('P','mm','letter'); 
  $pdf->SetDisplayMode(real, continuous);


  $pdf->AddPage(); 

      $pdf->SetY(12);
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Longitud',0,0,'L');


      $pdf->Output(); 
/*
  $link_mysqli = conectar();    
  $packingListSelect = $link_mysqli->query("
    SELECT proglote.lote,
      proglote.tarima,
      proglote.idlote,
      CONCAT(prodlote.noop,'-',prodbobina.bobina) AS noop,
      pdtoproyecto.proyecto,
      pdtoimpresion.idimpresion,
      pdtoimpresion.impresion,
      pdtoimpresion.ancho,
      pdtoimpresion.ceja,      
      pdtoimpresion.alpaso,
      pdtoimpresion.tolerancia, 
      (prodbobina.longitud/pdtoimpresion.corte) AS cantidad,     
      pdtomezcla.idmezcla,
      pdtomezcla.mezcla,
      prodbobina.longitud,
      prodbobina.amplitud,
      prodbobina.peso,
      prodbobina.cdgbobina,
      prodbobina.fchmovimiento
    FROM proglote,
      prodlote,
      prodbobina,
      pdtomezcla,
      pdtoimpresion,
      pdtoproyecto
    WHERE (proglote.cdglote = prodlote.cdglote 
      AND prodlote.cdglote = prodbobina.cdglote)
    AND (prodlote.cdgmezcla = pdtomezcla.cdgmezcla
      AND pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion
      AND pdtoimpresion.cdgproyecto = pdtoproyecto.cdgproyecto)
    AND prodbobina.sttbobina = '".$_GET['sttbobina']."'
    AND prodlote.cdgmezcla = '".$_GET['cdgmezcla']."'
    ORDER BY prodlote.noop,
      prodbobina.bobina"); 

  if ($packingListSelect->num_rows > 0)
  { $pdf=new FPDF('P','mm','lbl4x2'); 
    $pdf->SetDisplayMode(real, continuous);
    $pdf->AddFont('3of9','','free3of9.php');
    
    while ($regPackingList = $packingListSelect->fetch_object())
    { $pdf->AddPage();

    /*echo '<html>
  <head>    
    <link rel="stylesheet" type="text/css" href="../../css/global.css" media="screen" /> 
  </head>
  <body>
    <div align="center"><h1>EN MANTENIMIENTO</h1></div>
  </body>
</html>';



    $pdf=new FPDF('P','mm','lbl4x2'); 
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
      $pdf->Cell(8,4,'',0,0,'L');
      $pdf->Cell(48,4,$regPackingList->impresion,0,1,'L'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Formula',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,$regPackingList->idmezcla,0,1,'R'); 
      $pdf->Cell(8,4,'',0,0,'L');
      $pdf->Cell(48,4,$regPackingList->mezcla,0,1,'L'); 
     
      $pdf->SetY(-7);     
      $pdf->Cell(2,5,'',0,0,'L');      
      $pdf->SetFont('Arial','B',20);
      $pdf->Cell(68,5,'NoOP '.$regPackingList->noop,0,1,'R');

      // Información
      $pdf->SetY(13);
      
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->Cell(15,4,'Fusion',0,1,'L');
      
      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(28,4,'Ancho plano',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(18,4,($regPackingList->ancho/2),0,1,'R');
      
      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(28,4,'Ceja',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(18,4,$regPackingList->ceja,0,1,'R');

      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(28,4,'Piezas aprox',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(18,4,number_format($regPackingList->cantidad*1000),0,1,'R');            

      // Código de barras
      $pdf->SetY(4);
      $pdf->SetFont('3of9','',28);      
      $pdf->Cell(80,5,'',0,0,'R'); 
      $pdf->Cell(16,5,'*'.$regPackingList->cdgbobina.'*',0,1,'R');
      $pdf->Ln(1);
      $pdf->SetFont('Arial','',8); 
      $pdf->Cell(50,3,'',0,0,'R'); 
      $pdf->Cell(46,3,$regPackingList->cdgbobina,0,1,'C');

      $pdf->Cell(50,3,'',0,0,'R'); 
      $pdf->Cell(46,3,$regPackingList->fchmovimiento,0,1,'R'); 

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
</html>'; }  		 //*/
  
?>
