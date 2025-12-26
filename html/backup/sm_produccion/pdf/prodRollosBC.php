<?php

  include '../../datos/mysql.php';	
  require('../../fpdf/fpdflbl.php');

  if ($_GET['cdgbobina'])
  { $link_mysqli = conectar();    
    $packingListSelect = $link_mysqli->query("
      SELECT proglote.lote,
        proglote.tarima,
        proglote.idlote,
        CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) AS noop,
        pdtodiseno.diseno,
        pdtoimpresion.idimpresion,
        pdtoimpresion.impresion,
        pdtodiseno.alto AS corte,
        (prodrollo.longitud/pdtodiseno.alto) AS cantidad,
        prodrollo.longitud,
        prodrollo.amplitud,
        prodrollo.peso,
        prodrollo.cdgrollo
      FROM proglote,
        prodlote,
        prodbobina,
        prodrollo,
        pdtodiseno,
        pdtoimpresion
      WHERE (proglote.cdglote = prodlote.cdglote AND 
        prodlote.cdglote = prodbobina.cdglote AND
        prodbobina.cdgbobina = prodrollo.cdgbobina AND
        prodrollo.cdgproducto = pdtoimpresion.cdgimpresion AND
        pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno) AND 
        prodrollo.cdgbobina = '".$_GET['cdgbobina']."' AND
       (prodrollo.longitud/pdtodiseno.alto) > 0
      ORDER BY prodlote.noop,
        prodbobina.bobina,
        prodrollo.rollo"); 
  } else
  { $link_mysqli = conectar();    
    $packingListSelect = $link_mysqli->query("
      SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) AS noop,
        pdtodiseno.diseno,
        pdtoimpresion.idimpresion,
        pdtoimpresion.impresion,
        pdtodiseno.alto AS corte,
       (prodrollo.longitud/pdtodiseno.alto) AS cantidad,
        prodrollo.longitud,
        prodrollo.amplitud,
        prodrollo.peso,
        prodrollo.cdgrollo,
        prodrollo.fchmovimiento
      FROM prodlote,
        prodbobina,
        prodrollo,
        pdtodiseno,
        pdtoimpresion
      WHERE (prodlote.cdglote = prodbobina.cdglote AND
        prodbobina.cdgbobina = prodrollo.cdgbobina AND
        prodrollo.cdgproducto = pdtoimpresion.cdgimpresion AND
        pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno) AND 
        prodrollo.sttrollo = '".$_GET['sttrollo']."' AND
        prodrollo.cdgproducto = '".$_GET['cdgproducto']."' AND
       (prodrollo.longitud/pdtodiseno.alto) > 0
      ORDER BY prodlote.noop,
        prodbobina.bobina,
        prodrollo.rollo"); }

  if ($packingListSelect->num_rows > 0)
  { $pdf=new FPDF('P','mm','lbl4x2'); 
    $pdf->SetDisplayMode(real, continuous);
    $pdf->AddFont('3of9','','free3of9.php');
    
    while ($regPackingList = $packingListSelect->fetch_object())
    { $pdf->AddPage();

      $pdf->SetY(9.5);
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(48,4,'Producto',0,1,'L');
      $pdf->SetFont('Arial','B',12);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(98,4,$regPackingList->diseno,0,1,'L'); 
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(98,4,$regPackingList->impresion,0,1,'L');       

      $pdf->Ln(0.5);

      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Informacion',0,1,'L');

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Longitud',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,number_format($regPackingList->longitud,2).' mts',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Ancho plano',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,$regPackingList->amplitud.' mm',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Peso',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,number_format($regPackingList->peso,3).' kgs',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L'); 
      $pdf->Cell(28,4,'Piezas aprox',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(20,4,number_format($regPackingList->cantidad*1000),0,1,'R'); 
     
      $pdf->SetY(-7);     
      $pdf->Cell(2,5,'',0,0,'L');      
      $pdf->SetFont('Arial','B',20);
      $pdf->Cell(68,5,'NoOP '.$regPackingList->noop,0,1,'R');

      // Información
      $pdf->SetY(22.5);
      
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->Cell(15,4,'Informacion',0,1,'L');
      
      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(28,4,'Corte',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(18,4,$regPackingList->corte.' mm',0,1,'R');
      
      $pdf->Ln(8);

      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',8);
      $pdf->Cell(46,3,$regPackingList->fchmovimiento,0,1,'R');  

      // Código de barras
      $pdf->SetY(4);
      $pdf->SetFont('3of9','',28);      
      $pdf->Cell(80,5,'',0,0,'R'); 
      $pdf->Cell(16,5,'*'.$regPackingList->cdgrollo.'*',0,1,'R');
      $pdf->Ln(1);
      $pdf->SetFont('Arial','',8); 
      $pdf->Cell(50,3,'',0,0,'R'); 
      $pdf->Cell(46,3,$regPackingList->cdgrollo,0,1,'C');

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
</html>'; }  		 
  
?>
