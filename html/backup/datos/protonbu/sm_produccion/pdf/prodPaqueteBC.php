<?php

  include '../../datos/mysql.php';	
  require('../../fpdf/fpdflbl.php');

  if ($_GET['cdgrollo'])
  { $link_mysqli = conectar();    
    $prodPaqueteSelect = $link_mysqli->query("
      SELECT pdtoimpresion.corte,
        prodrollo.amplitud,
        prodpaquete.cdgpaquete
      FROM prodpaquete,
        prodrollo,
        prodbobina,
        prodlote,
        pdtoimpresion
      WHERE pdtoimpresion.cdgimpresion = prodpaquete.cdgproducto AND
        prodpaquete.cdgrollo = prodrollo.cdgrollo AND
        prodrollo.cdgbobina = prodbobina.cdgbobina AND
        prodbobina.cdglote = prodlote.cdglote AND
        prodpaquete.sttpaquete = '1' AND
        prodrollo.cdgrollo = '".$_GET['cdgrollo']."'
      ORDER BY prodpaquete.cdgpaquete");
  } else
  { $link_mysqli = conectar();    
    $prodPaqueteSelect = $link_mysqli->query("
      SELECT pdtoimpresion.corte,
        prodrollo.amplitud,
        prodpaquete.cdgpaquete
      FROM prodpaquete,
        prodrollo,
        prodbobina,
        prodlote,
        pdtoimpresion
      WHERE pdtoimpresion.cdgimpresion = prodpaquete.cdgproducto AND
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
    
    $id_paquete = 1;
    while ($regCodePaquete = $prodPaqueteSelect->fetch_object())
    { $codePaquete_cdgpaquete[$id_paquete] = $regCodePaquete->cdgpaquete; 
      $codePaquete_paquete[$id_paquete] = $regCodePaquete->cdgpaquete.$regCodePaquete->amplitud.$regCodePaquete->corte; 

      $id_paquete++; }

    $num_paquete = number_format($prodPaqueteSelect->num_rows/2);

    for ($id_paquete = 1; $id_paquete <= $num_paquete; $id_paquete++)
    { $pdf->AddPage();

      // Código de barras
      $pdf->SetY(-12);
      $pdf->SetFont('Arial','B',9); 
      $pdf->Ln(1);
      $pdf->SetFont('3of9','',22);      
      $pdf->Cell(50.5,5,'*'.$codePaquete_cdgpaquete[(($id_paquete*2)-1)].'*',0,0,'C'); 
      $pdf->Cell(50.5,5,'*'.$codePaquete_cdgpaquete[($id_paquete*2)].'*',0,1,'C');
      $pdf->Ln(.5);
      $pdf->SetFont('Arial','',8); 
      $pdf->Cell(50.5,3,$codePaquete_paquete[(($id_paquete*2)-1)],0,0,'C');
      $pdf->Cell(50.5,3,$codePaquete_paquete[($id_paquete*2)],0,1,'C');

      // Imagen LOGOTIPO
      if (file_exists('../../img_sistema/logo.jpg')==true) 
      { $pdf->Image('../../img_sistema/logo.jpg',4,4,14); 
        $pdf->Image('../../img_sistema/logo.jpg',54.5,4,14); }
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
