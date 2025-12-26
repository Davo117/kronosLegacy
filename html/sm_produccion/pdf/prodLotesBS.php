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
             pdtobanda.banda,
           ((pdtosustrato.anchura/pdtobanda.anchura)*proglote.longitud) AS cantidad,
             pdtobandap.bandap,
             pdtobandap.alaminado,
             prodlote.cdglote
        FROM progbloque,
             proglote,
             prodlote,
             prodloteope,
             pdtobanda, 
             pdtobandap,
             pdtosustrato
      WHERE (pdtosustrato.cdgsustrato = progbloque.cdgsustrato AND
             progbloque.cdgbloque = proglote.cdgbloque) AND
            (proglote.cdglote = prodlote.cdglote AND
             prodlote.cdglote = prodloteope.cdglote AND
             prodloteope.cdgoperacion = '10001') AND 
            (prodlote.cdgproducto = pdtobandap.cdgbandap AND
             pdtobandap.cdgbanda = pdtobanda.cdgbanda) AND
            (prodlote.cdgproducto = '".$_GET['cdgproducto']."' AND
             prodloteope.cdgmaquina = '".$_GET['cdgmaquina']."' AND
             prodlote.fchprograma = '".$_GET['fchprograma']."' AND
             prodlote.sttlote = 'A')");
  } else
  { // Etiquetas por producto y estatus
    $prodLoteSelect = $link->query("
      SELECT proglote.lote,
             proglote.tarima,
             proglote.idlote,
             prodlote.serie,
             prodlote.noop,
             prodlote.longitud,
             pdtosustrato.anchura,
             prodlote.peso,
             pdtobanda.banda,
           ((prodlote.amplitud/pdtobanda.anchura)*prodlote.longitud) AS cantidad,
             pdtobandap.bandap,
             pdtobandap.alaminado,
             prodlote.cdglote
        FROM progbloque,
             proglote,
             prodlote,
             pdtobanda,
             pdtobandap,
             pdtosustrato
      WHERE (pdtosustrato.cdgsustrato = progbloque.cdgsustrato AND
             progbloque.cdgbloque = proglote.cdgbloque AND
             proglote.cdglote = prodlote.cdglote) AND 
            (prodlote.cdgproducto = pdtobandap.cdgbandap AND
             pdtobandap.cdgbanda = pdtobanda.cdgbanda) AND
            (prodlote.cdgproducto = '".$_GET['cdgproducto']."' AND 
             prodlote.sttlote = '".$_GET['sttlote']."')"); }

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
      $pdf->SetFont('Arial','B',12);
      $pdf->Cell(6,4,'',0,0,'L');
      $pdf->Cell(48,4,$regProdLote->banda.' | '.$regProdLote->bandap,0,1,'L');
      $pdf->Ln(2);

      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(6,4,'',0,0,'L');
      $pdf->Cell(18,4,utf8_decode('Información'),0,1,'L');

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
      $pdf->Cell(18,4,'Metros aprox',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(24,4,number_format($regProdLote->cantidad,2,'.',','),0,1,'R'); 

      $pdf->SetY(-8);     
      $pdf->Cell(10,5,'',0,0,'L');      
      $pdf->SetFont('Arial','B',20);
      $pdf->Cell(80,5,'NoOP '.$regProdLote->serie.'.'.$regProdLote->noop,0,1,'C');
      
      // Información
      $pdf->SetY(22);
      
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->Cell(15,4,'Laminado',0,1,'L');
      
      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(18,4,'Anchura',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(25,4,$regProdLote->alaminado.' mm',0,1,'R');
      
      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(18,4,'',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(25,4,'',0,1,'R');
       
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