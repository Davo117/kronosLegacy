<?php
  require('../../fpdf/fpdflbl.php');
  require('../../datos/mysql.php');

  $link = conectar();

  if ($_GET['fchprograma'])
  { // Etiquetas por programación
    $prodLoteSelect = $link->query("
      SELECT proglote.lote,
             proglote.tarima,
             proglote.idlote,
             prodlote.serie,
             prodlote.noop,
             proglote.longitud,
             pdtosustrato.anchura,
             proglote.peso,
	     prodlote.observacion,
           ((prodloteope.longitud*pdtojuego.alpaso)/pdtojuego.altura) AS cantidad,
             pdtoimpresion.idimpresion,
             pdtoimpresion.impresion,
             pdtodiseno.diseno,
             pdtoimpresion.anchof,
             pdtoimpresion.empalme,
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
             pdtojuego.cdgimpresion = pdtoimpresion.cdgimpresion) AND
            (prodlote.cdgproducto = '".$_GET['cdgproducto']."' AND
             prodloteope.cdgjuego = '".$_GET['cdgjuego']."' AND
             prodloteope.cdgmaquina = '".$_GET['cdgmaquina']."' AND
             prodlote.fchprograma = '".$_GET['fchprograma']."' AND
             prodlote.sttlote = 'A')"); 
  } else
  { if ($_GET['sttlote'] == 'A')
    { // Etiquetas por producto y estatus
      $prodLoteSelect = $link->query("
        SELECT proglote.lote,
               proglote.tarima,
               proglote.idlote,
               prodlote.serie,
               prodlote.noop,
               proglote.longitud,
               pdtosustrato.anchura,
               proglote.peso,
	       prodlote.observacion,
             ((prodloteope.longitud*pdtojuego.alpaso)/pdtojuego.altura) AS cantidad,
               pdtoimpresion.idimpresion,
               pdtoimpresion.impresion,
               pdtodiseno.diseno,
               pdtoimpresion.anchof,
               pdtoimpresion.empalme,
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
               pdtojuego.cdgimpresion = pdtoimpresion.cdgimpresion) AND
              (prodlote.cdgproducto = '".$_GET['cdgproducto']."' AND
               prodlote.sttlote = 'A')");
    } else
    { // Etiquetas por producto y estatus
//Codigo modificado por Erik,para que tome el juego de cilindros correcto
      $prodLoteSelect = $link->query("
        SELECT distinct proglote.lote,
               proglote.tarima,
               proglote.idlote,
               prodlote.serie,
               prodlote.noop,
               prodlote.longitud,
               pdtosustrato.anchura,
               prodlote.peso,
               prodlote.observacion,
             ((prodlote.longitud*pdtojuego.alpaso)/pdtojuego.altura) AS cantidad,
               pdtoimpresion.idimpresion,
               pdtoimpresion.impresion,
               pdtodiseno.diseno,
               pdtoimpresion.anchof,
               pdtoimpresion.empalme,
               pdtojuego.registro,
               pdtojuego.alpaso,
               prodlote.cdglote
          FROM progbloque,
               proglote,
               prodlote,
               pdtodiseno,
               pdtoimpresion,
               pdtojuego,
               pdtosustrato,
               prodloteope
        WHERE (pdtosustrato.cdgsustrato = progbloque.cdgsustrato AND
               progbloque.cdgbloque = proglote.cdgbloque AND
               proglote.cdglote = prodlote.cdglote) AND 
              (prodlote.cdgproducto = pdtoimpresion.cdgimpresion AND
               pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno AND
               pdtoimpresion.cdgimpresion = pdtojuego.cdgimpresion) AND
              (prodlote.cdgproducto = '".$_GET['cdgproducto']."' AND 
               prodlote.sttlote = '".$_GET['sttlote']."') AND prodloteope.cdglote=prodlote.cdglote and prodloteope.cdgjuego=pdtojuego.cdgjuego and pdtojuego.cdgimpresion='".$_GET['cdgproducto']."' "); } 
  }

  if ($prodLoteSelect->num_rows > 0)
  { $pdf=new FPDF('P','mm','lbl4x2'); 
    $pdf->SetDisplayMode(real, continuous);
    $pdf->AddFont('3of9','','free3of9.php');
    
    while ($regProdLote = $prodLoteSelect->fetch_object())
    { $pdf->AddPage();

      $pdf->SetY(12);
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(6,4,'',0,0,'L');
      $pdf->Cell(48,4,'Producto',0,1,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(6,4,'',0,0,'L');
      $pdf->Cell(80,4,$regProdLote->diseno.' | '.$regProdLote->impresion,0,1,'L');       
      $pdf->Ln(2);

      if($regProdLote->diseno!="Comex")
      {
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(6,4,'',0,0,'L');
      $pdf->Cell(18,4,utf8_decode('Información'),0,1,'L');
      }
      else
      {
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(6,4,'',0,0,'L');
      $pdf->Cell(18,4,'BGN',$regProdLote->observacion,0,1,'L');
      }

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(6,4,'',0,0,'L');
      $pdf->Cell(18,4,'Longitud',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(24,4,number_format($regProdLote->longitud,2).' mts',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(6,4,'',0,0,'L');
      $pdf->Cell(18,4,'Ancho plano',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(24,4,$regProdLote->anchura.' mm',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(6,4,'',0,0,'L');
      $pdf->Cell(18,4,'Peso',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(24,4,number_format($regProdLote->peso,3).' kgs',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(6,4,'',0,0,'L'); 
      $pdf->Cell(18,4,'Piezas aprox',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(24,4,number_format($regProdLote->cantidad*1000),0,1,'R'); 

      $pdf->SetY(-8);     
      $pdf->Cell(10,5,'',0,0,'L');      
      $pdf->SetFont('Arial','B',20);
      $pdf->Cell(80,5,'NoOP '.$regProdLote->serie.'.'.$regProdLote->noop,0,1,'C');

      // Información
      $pdf->SetY(22);
      
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->Cell(15,4,'Refilado',0,1,'L');
      
      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(18,4,'Ancho plano',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(25,4,(($regProdLote->anchof*2)+$regProdLote->empalme).' mm',0,1,'R');
      
      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(18,4,'Bobinas',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(25,4,$regProdLote->alpaso,0,1,'R');
       
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(52,4,'',0,0,'L');
      $pdf->Cell(43,4,$regProdLote->tarima.' | '.$regProdLote->idlote,0,1,'R');
      $pdf->Cell(52,4,'',0,0,'L');
      $pdf->Cell(43,4,$regProdLote->lote,0,1,'R');
      
      // Código de barras
      $pdf->SetY(6);
      $pdf->SetFont('3of9','',28);      
      $pdf->Cell(10,5,'',0,0,'R'); 
      $pdf->Cell(80,5,'*'.$regProdLote->cdglote.'*',0,1,'C');
      $pdf->Ln(1);
      $pdf->SetFont('Arial','',8); 
      $pdf->Cell(10,3,'',0,0,'R'); 
      $pdf->Cell(80,3,$regProdLote->cdglote,0,1,'C'); }

    $pdf->Output();
  } else
  { echo '<html>
  <head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="../../css/2014.css" /> 
  </head>
  <body>
    <h1>No se encontraron coincidencias.</1>
  </body>
</html>'; }
?>
