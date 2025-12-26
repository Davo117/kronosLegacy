<?php

  include '../../datos/mysql.php';
  require('../../fpdf/fpdflbl.php');

  $link = conectar();

  if ($_GET['fchprograma'])
  { // Etiquetas por programación
    $packingListSelect = $link->query("
      SELECT proglote.lote,
             proglote.tarima,
             proglote.idlote,
             prodlote.serie,
             prodlote.noop,
             proglote.longitud,
             pdtosustrato.anchura AS anchural,
             proglote.peso,
             pdtobandap.idbandap,
             pdtobandap.bandap,
             pdtobanda.banda,
             pdtobanda.anchura,
             prodlote.cdglote
        FROM progbloque,
             proglote,
             prodlote,
             prodloteope,
             pdtobanda, 
             pdtobandap,
             pdtosustrato
      WHERE (pdtosustrato.cdgsustrato = progbloque.cdgsustrato AND
             progbloque.cdgbloque = proglote.cdgbloque AND
             proglote.cdglote = prodlote.cdglote AND
             prodlote.cdglote = prodloteope.cdglote AND 
             prodloteope.cdgoperacion = '10001') AND 
            (prodlote.cdgproducto = pdtobandap.cdgbandap AND
             pdtobandap.cdgbanda = pdtobanda.cdgbanda) AND
            (prodlote.cdgproducto = '".$_GET['cdgproducto']."' AND
             prodloteope.cdgmaquina = '".$_GET['cdgmaquina']."' AND
             prodlote.fchprograma = '".$_GET['fchprograma']."' AND
             prodlote.sttlote = 'A')"); 
  } else
  { if ($_GET['sttlote'] == 'A')
    { // Etiquetas por producto y estatus
      $packingListSelect = $link->query("
        SELECT proglote.lote,
               proglote.tarima,
               proglote.idlote,
               prodlote.serie,
               prodlote.noop,
               proglote.longitud,
               pdtosustrato.anchura,
               proglote.peso,
             ((prodloteope.longin*pdtojuego.alpaso)/pdtojuego.altura) AS cantidad,
               pdtoimpresion.idimpresion,
               pdtoimpresion.impresion,
               pdtodiseno.diseno,
               pdtodiseno.anchof,
               pdtodiseno.empalme,
               pdtojuego.registro,
               pdtojuego.alpaso,
               prodlote.cdglote
          FROM progbloque,
               proglote,
               prodlote,
               prodloteope,
               pdtodiseno, 
               pdtoimpresion,
               pdtojuego,
               pdtosustrato
        WHERE (pdtosustrato.cdgsustrato = progbloque.cdgsustrato AND
               progbloque.cdgbloque = proglote.cdgbloque AND
               proglote.cdglote = prodlote.cdglote AND
               prodlote.cdglote = prodloteope.cdglote AND 
               prodloteope.cdgoperacion = '10001') AND 
              (prodlote.cdgproducto = pdtoimpresion.cdgimpresion AND
               pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno) AND
              (prodloteope.cdgjuego = pdtojuego.cdgjuego AND
               pdtojuego.cdgdiseno = pdtodiseno.cdgdiseno) AND
              (prodlote.cdgproducto = '".$_GET['cdgproducto']."' AND
               prodlote.sttlote = 'A')");
    } else
    { // Etiquetas por producto y estatus
      $packingListSelect = $link->query("
        SELECT proglote.lote,
               proglote.tarima,
               proglote.idlote,
               prodlote.noop,
               prodlote.longitud,
               pdtosustrato.anchura,
               prodlote.peso,
             ((prodlote.longitud*pdtojuego.alpaso)/pdtojuego.altura) AS cantidad,
               pdtoimpresion.idimpresion,
               pdtoimpresion.impresion,
               pdtodiseno.diseno,
               pdtodiseno.anchof,
               pdtodiseno.empalme,
               pdtojuego.registro,
               pdtojuego.alpaso,
               prodlote.cdglote
          FROM progbloque,
               proglote,
               prodlote,
               pdtodiseno,
               pdtoimpresion,
               pdtojuego,
               pdtosustrato
        WHERE (pdtosustrato.cdgsustrato = progbloque.cdgsustrato AND
               progbloque.cdgbloque = proglote.cdgbloque AND
               proglote.cdglote = prodlote.cdglote) AND
              (prodlote.cdgproducto = pdtoimpresion.cdgimpresion AND
               pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno AND
               pdtodiseno.cdgdiseno = pdtojuego.cdgdiseno) AND
              (prodlote.cdgproducto = '".$_GET['cdgproducto']."' AND
               prodlote.sttlote = '".$_GET['sttlote']."')");
    }
  }

  if ($packingListSelect->num_rows > 0)
  { $pdf=new FPDF('P','mm','lbl4x2'); 
    $pdf->SetDisplayMode(real, continuous);
    $pdf->AddFont('3of9','','free3of9.php');
    
    while ($regPackingList = $packingListSelect->fetch_object())
    { $pdf->AddPage();

      // Código de barras
      $pdf->SetY(4);
      $pdf->SetX(35);
      $pdf->SetFont('3of9','',28);
      
      $pdf->Cell(55,5,'*'.$regPackingList->cdglote.'*',0,1,'R');
      $pdf->SetFont('Arial','',8); 
      $pdf->SetX(35);
      $pdf->Cell(55,3.8,$regPackingList->cdglote,0,1,'R');

      $pdf->SetY(8);
      $pdf->SetX(10);
      $pdf->SetFont('Arial','I',10);
      $pdf->Cell(48,4,'Producto',0,1,'L');
      
      $pdf->SetX(10);
      $pdf->SetFont('Arial','B',12);
      $pdf->Cell(98,4,$regPackingList->bandap,0,1,'L');

      $pdf->SetX(10);
      $pdf->SetFont('Arial','',11);
      $pdf->Cell(98,4,$regPackingList->banda.' ('.$regPackingList->anchura.' mm)',0,1,'L');
      $pdf->Ln(1);

      $pdf->SetX(10);
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(18,4,utf8_decode('Información'),0,1,'L');
      
      $pdf->SetX(10);
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(10,4,'Longitud',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(22,4,number_format($regPackingList->longitud,2),0,0,'R');
      $pdf->Cell(10,4,' mts',0,1,'L'); 

      $pdf->SetX(10);
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(10,4,'Anchura lote',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(22,4,$regPackingList->anchural,0,0,'R'); 
      $pdf->Cell(10,4,' mm',0,1,'L'); 

      $pdf->SetX(10);
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(10,4,'Peso',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(22,4,number_format($regPackingList->peso,3),0,0,'R'); 
      $pdf->Cell(10,4,' kgs',0,1,'L');

      $pdf->SetX(10);
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(20,4,'Metros aprox.',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(19,4,number_format((floor($regPackingList->anchural/$regPackingList->anchura)*$regPackingList->longitud),2),0,1,'R');

      $pdf->SetY(26);
      $pdf->SetX(52);
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(28,4,'Lote',0,1,'L');
       
      $pdf->SetX(52);
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(46,4,$regPackingList->tarima.'/'.$regPackingList->idlote,0,1,'L');
      
      $pdf->SetX(52);
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(28,4,'Tarima',0,1,'L');

      $pdf->SetX(52);
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(46,4,$regPackingList->lote,0,1,'L');

      $pdf->SetY(-7);
      $pdf->SetX(10);
      $pdf->SetFont('Arial','B',14);
      $pdf->Cell(22,5,'Serie '.$regPackingList->serie.' | NoOP '.$regPackingList->noop,0,1,'L'); }

    $pdf->Output();
  } else
  { echo '<html>
  <head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="../../css/2014.css" media="screen" /> 
  </head>
  <body>
    <h1>No se encontraron conincidencias.</1>
  </body>
</html>'; }
?>