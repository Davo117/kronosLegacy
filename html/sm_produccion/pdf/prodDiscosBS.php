<?php
  require('../../fpdf/fpdflbl.php');
  require('../../datos/mysql.php');

  $link = conectar();

  if ($_GET['cdglote'])
  { $prodDiscoSelect = $link->query("
      SELECT prodlote.serie,
      CONCAT(prodlote.noop,'-',proddisco.disco) AS noop,
             pdtobanda.banda,
             pdtobandap.bandap,
             proddisco.longitud,
             proddisco.amplitud,
             proddisco.peso,
             proddisco.fchmovimiento,
             proddisco.cdgdisco
        FROM prodlote,
             proddisco,
             pdtobanda,
             pdtobandap
      WHERE (pdtobandap.cdgbanda = pdtobanda.cdgbanda AND
             prodlote.cdgproducto = pdtobandap.cdgbandap) AND
            (prodlote.cdglote = proddisco.cdgbobina AND
             proddisco.cdgbobina = '".$_GET['cdglote']."' AND            
             proddisco.longitud > 0 AND
             proddisco.sttdisco = '1')");
  } 

  if ($_GET['cdgbobina'])
  { $prodDiscoSelect = $link->query("
      SELECT prodlote.serie,
      CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',proddisco.disco) AS noop,
             pdtobanda.banda,
             pdtobandap.bandap,
             proddisco.longitud,
             proddisco.amplitud,
             proddisco.peso,
             proddisco.fchmovimiento,
             proddisco.cdgdisco
        FROM prodlote,
             prodbobina,
             proddisco,
             pdtobanda,
             pdtobandap
      WHERE (pdtobandap.cdgbanda = pdtobanda.cdgbanda AND
             prodlote.cdgproducto = pdtobandap.cdgbandap) AND
            (prodlote.cdglote = prodbobina.cdglote AND
             prodbobina.cdgbobina = proddisco.cdgbobina AND
             proddisco.cdgbobina = '".$_GET['cdgbobina']."' AND
             proddisco.longitud > 0 AND
             proddisco.sttdisco = '1')"); }

  if ($_GET['cdgproducto'])
  { if ($_GET['preembosado'] == '1')
    { $prodDiscoSelect = $link->query("
        SELECT prodlote.serie,
        CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',proddisco.disco) AS noop,
               pdtobanda.banda,
               pdtobandap.bandap,
               proddisco.longitud,
               proddisco.amplitud,
               proddisco.peso,
               proddisco.fchmovimiento,
               proddisco.cdgdisco
          FROM prodlote,
               prodbobina,
               proddisco,
               pdtobanda,
               pdtobandap
        WHERE (pdtobandap.cdgbanda = pdtobanda.cdgbanda AND
               prodlote.cdgproducto = pdtobandap.cdgbandap) AND
              (prodlote.cdglote = prodbobina.cdglote AND
               prodbobina.cdgbobina = proddisco.cdgbobina AND
               proddisco.cdgproducto = '".$_GET['cdgproducto']."' AND
               proddisco.longitud > 0 AND
               proddisco.sttdisco = '1')");
    } else
    { $prodDiscoSelect = $link->query("
        SELECT prodlote.serie,
        CONCAT(prodlote.noop,'-',proddisco.disco) AS noop,
               pdtobanda.banda,
               pdtobandap.bandap,
               proddisco.longitud,
               proddisco.amplitud,
               proddisco.peso,
               proddisco.fchmovimiento,
               proddisco.cdgdisco
          FROM prodlote,
               proddisco,
               pdtobanda,
               pdtobandap
        WHERE (pdtobandap.cdgbanda = pdtobanda.cdgbanda AND
               prodlote.cdgproducto = pdtobandap.cdgbandap) AND
              (prodlote.cdglote = proddisco.cdgbobina AND
               proddisco.cdgproducto = '".$_GET['cdgproducto']."' AND
               proddisco.longitud > 0 AND
               proddisco.sttdisco = '1')"); }
  }
  

  if ($prodDiscoSelect->num_rows > 0)
  { $pdf=new FPDF('P','mm','lbl4x0.5'); 
    $pdf->SetDisplayMode(real, continuous);
    $pdf->AddFont('3of9','','free3of9.php');
    
    while ($regProdDisco = $prodDiscoSelect->fetch_object())
    { $pdf->AddPage();

      $pdf->SetY(-18);
      $pdf->SetFont('Arial','',8);
      $pdf->Cell(2,3,'',0,0,'L');
      $pdf->Cell(12,3,'Producto',0,1,'L');
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(2,3,'',0,0,'L');
      $pdf->Cell(80,3,$regProdDisco->banda.' | '.$regProdDisco->bandap,0,1,'L');

      $pdf->SetFont('Arial','',8);
      $pdf->Cell(2,3,'',0,0,'L');
      $pdf->Cell(15,3,'Medida',0,0,'R');
      $pdf->Cell(20,3,'Metros',0,0,'R');
      $pdf->Cell(15,3,'Kilos',0,1,'R');

      $pdf->SetFont('Arial','B',9);
      $pdf->Cell(2,3,'',0,0,'L');
      $pdf->Cell(15,3,$regProdDisco->amplitud.'mm',0,0,'R');
      $pdf->Cell(20,3,number_format($regProdDisco->longitud,2,'.',','),0,0,'R');
      $pdf->Cell(15,3,number_format($regProdDisco->peso,3,'.',','),0,1,'R');      

      $pdf->SetY(-12);      
      $pdf->SetFont('Arial','',7);
      $pdf->Cell(98,3,$regProdDisco->cdgdisco,0,1,'R');      
      $pdf->SetFont('Arial','B',9);
      $pdf->Cell(98,3,'NoOP '.$regProdDisco->serie.'.'.$regProdDisco->noop,0,1,'R');

      // Código de barras
      $pdf->SetY(-17);
      $pdf->SetFont('3of9','',20);
      $pdf->Cell(98,5,'*'.$regProdDisco->cdgdisco.'*',0,1,'R');
    }

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