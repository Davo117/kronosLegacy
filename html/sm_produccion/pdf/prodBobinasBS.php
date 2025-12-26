<?php
  require('../../fpdf/fpdflbl.php');
  require('../../datos/mysql.php');

  $link = conectar();

  if ($_GET['cdglote'])
  { $prodBobinaSelect = $link->query("
      SELECT prodlote.serie,
      CONCAT(prodlote.noop,'-',prodbobina.bobina) AS noop,
             pdtobanda.banda,
             pdtobanda.anchura,
             pdtobandap.bandap,
             prodbobina.longitud,
             prodbobina.amplitud,
             prodbobina.peso,
           ((prodbobina.amplitud/pdtobanda.anchura)*prodbobina.longitud) AS cantidad,
             prodbobina.fchmovimiento,
             prodbobina.cdgbobina
        FROM prodlote,
             prodbobina,
             pdtobanda,
             pdtobandap
      WHERE (prodlote.cdglote = prodbobina.cdglote AND
             prodlote.cdglote = '".$_GET['cdglote']."') AND
            (pdtobandap.cdgbanda = pdtobanda.cdgbanda AND
             prodlote.cdgproducto = pdtobandap.cdgbandap)");
  } else
  { $prodBobinaSelect = $link->query("
      SELECT prodlote.serie,
      CONCAT(prodlote.noop,'-',prodbobina.bobina) AS noop,
             pdtobanda.banda,
             pdtobanda.anchura,
             pdtobandap.bandap,
             prodbobina.longitud,
             prodbobina.amplitud,
             prodbobina.peso,
           ((prodbobina.amplitud/pdtobanda.anchura)*prodbobina.longitud) AS cantidad,
             prodbobina.fchmovimiento,
             prodbobina.cdgbobina
        FROM prodlote,
             prodbobina,
             pdtobanda,
             pdtobandap
      WHERE (prodlote.cdglote = prodbobina.cdglote AND
             prodbobina.sttbobina = '".$_GET['sttbobina']."' AND
             prodlote.cdgproducto = '".$_GET['cdgproducto']."') AND
            (pdtobandap.cdgbanda = pdtobanda.cdgbanda AND
             prodlote.cdgproducto = pdtobandap.cdgbandap)"); }
  

  if ($prodBobinaSelect->num_rows > 0)
  { $pdf=new FPDF('P','mm','lbl4x2'); 
    $pdf->SetDisplayMode(real, continuous);
    $pdf->AddFont('3of9','','free3of9.php');
    
    while ($regProdBobina = $prodBobinaSelect->fetch_object())
    { $pdf->AddPage();

      $pdf->SetY(12);
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(6,4,'',0,0,'L');
      $pdf->Cell(48,4,'Producto',0,1,'L');
      $pdf->SetFont('Arial','B',12);
      $pdf->Cell(6,4,'',0,0,'L');
      $pdf->Cell(48,4,$regProdBobina->banda.' | '.$regProdBobina->bandap,0,1,'L');
      $pdf->Ln(2);

      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(6,4,'',0,0,'L');
      $pdf->Cell(18,4,'Informacion',0,1,'L');

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(6,4,'',0,0,'L');
      $pdf->Cell(18,4,'Longitud',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(24,4,number_format($regProdBobina->longitud,2).' mts',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(6,4,'',0,0,'L');
      $pdf->Cell(18,4,'Ancho plano',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(24,4,$regProdBobina->amplitud.' mm',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(6,4,'',0,0,'L');
      $pdf->Cell(18,4,'Peso',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(24,4,number_format($regProdBobina->peso,3).' kgs',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(6,4,'',0,0,'L'); 
      $pdf->Cell(18,4,'Metros aprox',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(24,4,number_format($regProdBobina->cantidad,2,'.',','),0,1,'R'); 

      $pdf->SetY(-8);
      $pdf->Cell(10,5,'',0,0,'L');      
      $pdf->SetFont('Arial','B',20);
      $pdf->Cell(80,5,'NoOP '.$regProdBobina->serie.'.'.$regProdBobina->noop,0,1,'C');

      // Código de barras
      $pdf->SetY(6);
      $pdf->SetFont('3of9','',28);      
      $pdf->Cell(10,5,'',0,0,'R'); 
      $pdf->Cell(80,5,'*'.$regProdBobina->cdgbobina.'*',0,1,'C');
      $pdf->Ln(1);
      $pdf->SetFont('Arial','',8); 
      $pdf->Cell(10,3,'',0,0,'R'); 
      $pdf->Cell(80,3,$regProdBobina->cdgbobina,0,1,'C'); }

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