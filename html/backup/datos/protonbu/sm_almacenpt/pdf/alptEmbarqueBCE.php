<?php

  include '../../datos/mysql.php';	
  require('../../fpdf/fpdflbl.php');

  if ($_GET['cdgimpresion'])
  { $link_mysqli = conectar();    
    $packingListSelect = $link_mysqli->query("
      SELECT alptempaque.noempaque,
        COUNT(prodrollo.cdgrollo) AS rollos,
        pdtoimpresion.impresion,
        SUM(prodrollo.longitud) AS longitud,
        SUM(prodrollo.peso) AS peso,
        alptempaque.peso AS pbruto,
       (SUM(prodrollo.longitud)/pdtoimpresion.corte) AS piezas,
        alptempaque.cdgempaque
      FROM prodlote,
        prodbobina,
        prodrollo,
        pdtoimpresion,
        alptempaque,
        alptempaquer
      WHERE (prodlote.cdglote = prodbobina.cdglote AND
        prodbobina.cdgbobina = prodrollo.cdgbobina AND
        prodrollo.cdgproducto = pdtoimpresion.cdgimpresion AND
        pdtoimpresion.cdgimpresion = '".$_GET['cdgimpresion']."' AND
        prodrollo.sttrollo = '9') AND
       (alptempaque.cdgempaque = alptempaquer.cdgempaque AND
        alptempaquer.cdgrollo = prodrollo.cdgrollo) AND
        sttempaque = '1'
      GROUP BY alptempaque.cdgempaque
      ORDER BY noempaque"); } 

  if ($packingListSelect->num_rows > 0)
  { $pdf=new FPDF('P','mm','lbl4x2'); 
    $pdf->SetDisplayMode(real, continuous);
    $pdf->AddFont('3of9','','free3of9.php');
    
    while ($regPackingList = $packingListSelect->fetch_object())
    { $pdf->AddPage();

      $pdf->Cell(15,3,'',0,1,'L');
      $pdf->SetFont('Arial','B',12);
      $pdf->Cell(4,5,'',0,0,'L');
      $pdf->Cell(18,12,'Empaque ',0,0,'L');
      $pdf->SetFont('Arial','B',36);
      $pdf->Cell(40,12,'Q'.$regPackingList->noempaque,0,1,'R'); 

      $pdf->SetY(14);
      $pdf->SetFont('Arial','B',18);
      $pdf->Cell(4,5,'',0,0,'L');
      $pdf->Cell(94,5,$regPackingList->impresion,0,1,'L');
      $pdf->Ln(1);

      $pdf->Cell(4,4,'',0,0,'L');
      $pdf->SetFont('Arial','B',11);
      $pdf->Cell(58,4,$regPackingList->rollos.' Rollos',0,1,'R');    
      
      $pdf->Cell(4,4,'',0,0,'L');
      $pdf->SetFont('Arial','B',9);
      $pdf->Cell(18,4,'Longitud ',0,0,'L');
      $pdf->SetFont('Arial','B',11);
      $pdf->Cell(40,4,number_format($regPackingList->longitud,2).' Mts',0,1,'R');
/*
      $pdf->Cell(4,4,'',0,0,'L');
      $pdf->SetFont('Arial','B',9);
      $pdf->Cell(18,4,'Peso ',0,0,'L');
      $pdf->SetFont('Arial','B',11);
      $pdf->Cell(40,4,number_format($regPackingList->peso,3).' Kgs',0,1,'R'); //*/

      //$pdf->Cell(4,4,'',0,0,'L');
      //$pdf->SetFont('Arial','B',9);
      //$pdf->Cell(18,4,'Peso Bruto',0,0,'L');
      //$pdf->SetFont('Arial','B',11);
      //$pdf->Cell(40,4,number_format($regPackingList->pbruto,3).' Kgs',0,1,'R');

      $pdf->Cell(4,4,'',0,0,'L');
      $pdf->SetFont('Arial','B',9);
      $pdf->Cell(18,4,'Piezas aprox ',0,0,'L');
      $pdf->SetFont('Arial','B',11);
      $pdf->Cell(40,4,number_format($regPackingList->piezas*1000),0,1,'R');

      $pdf->Ln(4); 

      $pdf->SetFont('3of9','',26);
      $pdf->Cell(98,6,'*'.$regPackingList->cdgempaque.'*',0,1,'C');     
      
      $pdf->Ln(2); 

      $pdf->SetFont('Arial','B',9);
      $pdf->Cell(4,4,'',0,0,'L');
      $pdf->Cell(90,4,'Temperatura de almacenamiento menor a 25 grados.',1,1,'C');

      // Imagen LOGOTIPO
      if (file_exists('../../img_sistema/logo.jpg')==true) 
      { $pdf->Image('../../img_sistema/logo.jpg',4,34,14);
        $pdf->Image('../../img_sistema/logo.jpg',83,4,14); }        
    }

    $pdf->Output();
  } else
  { echo '<html>
  <head>
    <title>Etiquetas de Embarque</title>
    <link rel="stylesheet" type="text/css" href="../../css/global.css" media="screen" /> 
  </head>
  <body>
  </body>
</html>';}  		 
  
?>
