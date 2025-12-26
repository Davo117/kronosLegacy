<?php

  include '../../datos/mysql.php';	
  require('../../fpdf/fpdflbl.php');

  $link = conectar();

  if ($_GET['cdgrollo'])
  { $prodPaqueteSelect = $link->query("
      SELECT pdtoimpresion.alto,
             prodrollo.amplitud,
             prodpaquete.cdgpaquete
        FROM prodpaquete,
             prodrollo,
             prodbobina,
             prodlote,
             pdtoimpresion,
             pdtodiseno
       WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
             pdtoimpresion.cdgimpresion = prodpaquete.cdgproducto AND
             prodpaquete.cdgrollo = prodrollo.cdgrollo AND
             prodrollo.cdgbobina = prodbobina.cdgbobina AND
             prodbobina.cdglote = prodlote.cdglote AND
             prodpaquete.sttpaquete = '1' AND
             prodrollo.cdgrollo = '".$_GET['cdgrollo']."'
    ORDER BY prodpaquete.cdgpaquete");
  } else
  { $prodPaqueteSelect = $link->query("
      SELECT pdtoimpresion.alto,
             prodrollo.amplitud,
             prodpaquete.cdgpaquete
        FROM prodpaquete,
             prodrollo,
             prodbobina,
             prodlote,
             pdtoimpresion,
             pdtodiseno
       WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
             pdtoimpresion.cdgimpresion = prodpaquete.cdgproducto AND
             prodpaquete.cdgrollo = prodrollo.cdgrollo AND
             prodrollo.cdgbobina = prodbobina.cdgbobina AND
             prodbobina.cdglote = prodlote.cdglote AND
             prodpaquete.sttpaquete = '".$_GET['sttpaquete']."' AND
             prodpaquete.cdgproducto = '".$_GET['cdgproducto']."'
    ORDER BY prodpaquete.fchmovimiento, prodpaquete.cdgpaquete"); }

  if ($prodPaqueteSelect->num_rows > 0)
  { $pdf=new FPDF('P','mm','lbl4x1'); 
    $pdf->SetDisplayMode(real, continuous);
    $pdf->AddFont('3of9','','free3of9.php');
    
    $item = 0;
    while ($regCodePaquete = $prodPaqueteSelect->fetch_object())
    { $item++;

      $codePaquete_cdgpaquete[$item] = $regCodePaquete->cdgpaquete; 
      $codePaquete_paquete[$item] = $regCodePaquete->cdgpaquete.$regCodePaquete->amplitud.$regCodePaquete->alto; }

    $nPaquetes = number_format($prodPaqueteSelect->num_rows/2);

    for ($item = 1; $item <= $nPaquetes; $item++)
    { $pdf->AddPage();

      // Código de barras
      $pdf->SetY(10);

      $pdf->SetFont('3of9','',24);
      $pdf->Cell(50,6,'*'.$codePaquete_cdgpaquete[(($item*2)-1)].'*',0,0,'C'); 
      $pdf->Cell(4,6,'',0,0,'C');
      $pdf->Cell(50,6,'*'.$codePaquete_cdgpaquete[($item*2)].'*',0,1,'C');
      $pdf->SetFont('Arial','',9); 
      $pdf->Cell(50,2,$codePaquete_paquete[(($item*2)-1)],0,0,'C');
      $pdf->Cell(4,6,'',0,0,'C');
      $pdf->Cell(50,2,$codePaquete_paquete[($item*2)],0,1,'C'); }

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
