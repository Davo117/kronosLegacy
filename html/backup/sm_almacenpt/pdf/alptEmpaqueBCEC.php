<?php

  include '../../datos/mysql.php';	
  require('../../fpdf/fpdflbl.php');

  if ($_GET['cdgempaque'])
  { $link_mysqli = conectar();    
    $packingListSelect = $link_mysqli->query("
      SELECT alptempaque.noempaque,
        pdtoimpresion.impresion,
        COUNT(prodpaquete.cdgpaquete) AS paquetes,
        alptempaque.peso,
        SUM(prodpaquete.cantidad) AS piezas  
      FROM prodlote,
        prodbobina,
        prodrollo,
        prodpaquete,
        alptempaque,
        pdtoimpresion
      WHERE prodlote.cdglote = prodbobina.cdglote AND
        prodbobina.cdgbobina = prodrollo.cdgbobina AND
        prodrollo.cdgrollo = prodpaquete.cdgrollo AND
        prodpaquete.cdgempaque = alptempaque.cdgempaque AND
        alptempaque.cdgempaque  = '".$_GET['cdgempaque']."' AND
        prodpaquete.cdgproducto = pdtoimpresion.cdgimpresion"); }
  else
  { $link_mysqli = conectar();    
    $packingListSelect = $link_mysqli->query("
      SELECT alptempaque.cdgempaque,
        alptempaque.noempaque,
        pdtoimpresion.impresion,
        COUNT(prodpaquete.cdgpaquete) AS paquetes,
        alptempaque.peso,
        SUM(prodpaquete.cantidad) AS piezas  
      FROM prodlote,
        prodbobina,
        prodrollo,
        prodpaquete,
        alptempaque,
        pdtoimpresion
      WHERE prodlote.cdglote = prodbobina.cdglote AND
        prodbobina.cdgbobina = prodrollo.cdgbobina AND
        prodrollo.cdgrollo = prodpaquete.cdgrollo AND
        prodpaquete.cdgempaque = alptempaque.cdgempaque AND
        prodpaquete.cdgproducto = pdtoimpresion.cdgimpresion AND
        alptempaque.cdgproducto  = '".$_GET['cdgimpresion']."' AND
        alptempaque.sttempaque = '1'
      GROUP BY alptempaque.noempaque"); }

  if ($packingListSelect->num_rows > 0)
  { $pdf=new FPDF('P','mm','lbl4x2'); 
    $pdf->SetDisplayMode(real, continuous);
    $pdf->AddFont('3of9','','free3of9.php');

    while ($regPackingList = $packingListSelect->fetch_object())
    { if ($alptEmpaque_noempaque == '')
      { $alptEmpaque_noempaque = $regPackingList->noempaque; }

      if ($alptEmpaque_impresion == '')
      { $alptEmpaque_impresion = $regPackingList->impresion; }
      
      $pdf->AddPage();

    $pdf->Cell(15,3,'',0,1,'L');
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(4,5,'',0,0,'L');
    $pdf->Cell(18,12,'Empaque ',0,0,'L');
    $pdf->SetFont('Arial','B',36);
    $pdf->Cell(40,12,'C'.$regPackingList->noempaque,0,1,'R'); 

    $pdf->SetY(15);
    //$pdf->Cell(4,4,'',0,0,'L');
   // $pdf->SetFont('Arial','B',10);
   // $pdf->Cell(18,4,'Producto',0,1,'L');
    $pdf->SetFont('Arial','B',18);
    $pdf->Cell(4,5,'',0,0,'L');
    $pdf->Cell(94,5,$regPackingList->impresion,0,1,'L');
    $pdf->Ln(1);

    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(58,4,$regPackingList->paquetes.' Paquetes',0,1,'R');    
    
    //$pdf->Cell(4,4,'',0,0,'L');
    //$pdf->SetFont('Arial','B',9);
    //$pdf->Cell(18,4,'Peso ',0,0,'L');
    //$pdf->SetFont('Arial','B',11);
    //$pdf->Cell(40,4,number_format($regPackingList->peso,3).' Kgs',0,1,'R');   

    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(18,4,'Piezas aprox ',0,0,'L');
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(40,4,$regPackingList->piezas,0,1,'R');

    $pdf->Ln(4);

    $pdf->Ln(4);

    $pdf->SetFont('3of9','',26);

    if ($_GET['cdgempaque'])
    { $pdf->Cell(98,6,'*'.$_GET['cdgempaque'].'*',0,1,'C'); } 
    else
    { $pdf->Cell(98,6,'*'.$regPackingList->cdgempaque.'*',0,1,'C'); }

    $pdf->Ln(2);

    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->Cell(90,4,'Temperatura de almacenamiento menor a 25 grados.',1,1,'C');

      // Imagen LOGOTIPO
      if (file_exists('../../img_sistema/logo.jpg')==true) 
      { $pdf->Image('../../img_sistema/logo.jpg',4,34,14);
        $pdf->Image('../../img_sistema/logo.jpg',83,4,14); }       
  }

    $pdf->Output(); //$pdf->Output($alptEmpaque_impresion.' Empaque C'.$alptEmpaque_noempaque.' '.$_GET['cdgempaque'].'.pdf', 'I');
  } else
  { echo '<html>
  <head>    
    <link rel="stylesheet" type="text/css" href="../../css/global.css" media="screen" /> 
  </head>
  <body>
  </body>
</html>';}  		 
  
?>
