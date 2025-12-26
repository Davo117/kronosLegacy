<?php

  include '../../datos/mysql.php';	
  require('../../fpdf/fpdflbl.php');
//000070221200

  $link_mysql = conectar();
  $prodRolloSelect = $link_mysql->query("
    SELECT prodlote.cdgmezcla,
      prodrollo.amplitud
    FROM prodrollo,
      prodbobina,
      prodlote
    WHERE prodrollo.cdgbobina = prodbobina.cdgbobina AND
      prodbobina.cdglote = prodlote.cdglote AND
      prodrollo.cdgrollo = '".$_GET['cdgrollo']."'");

  if ($prodRolloSelect->num_rows > 0)
  { $regProdRollo = $prodRolloSelect->fetch_object(); 

    $codePaquete_cdgmezcla = $regProdRollo->cdgmezcla;
    $codePaquete_amplitud = $regProdRollo->amplitud;
          
    $link_mysqli = conectar();
    $pdtoImpresionSelect = $link_mysqli->query("
      SELECT pdtoimpresion.impresion,
        pdtoimpresion.corte
      FROM pdtomezcla,
        pdtoimpresion
      WHERE pdtomezcla.cdgmezcla = '".$codePaquete_cdgmezcla."' AND
        pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion");

    if ($pdtoImpresionSelect->num_rows > 0)
    { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

      $codePaquete_impresion = $regPdtoImpresion->impresion;
      $codePaquete_corte = $regPdtoImpresion->corte; 

      $link_mysqli = conectar();    
      $codePaqueteSelect = $link_mysqli->query("
        SELECT prodpaquete.cdgpaquete
        FROM prodpaquete,
          prodrollo,
          prodbobina,
          prodlote
        WHERE prodpaquete.cdgrollo = prodrollo.cdgrollo AND
          prodrollo.cdgbobina = prodbobina.cdgbobina AND
          prodbobina.cdglote = prodlote.cdglote AND
          prodrollo.cdgrollo = '".$_GET['cdgrollo']."'");
    }
  }

  if ($codePaqueteSelect->num_rows > 0)
  { $pdf=new FPDF('P','mm','lbl4x1'); 
    $pdf->SetDisplayMode(real, continuous);
    $pdf->AddFont('3of9','','free3of9.php');
    
    $id_paquete = 1;
    while ($regCodePaquete = $codePaqueteSelect->fetch_object())
    { $codePaquete_cdgpaquete[$id_paquete] = $regCodePaquete->cdgpaquete; 

      $id_paquete++; }

    $num_paquete = number_format($codePaqueteSelect->num_rows/2);

    for ($id_paquete = 1; $id_paquete <= $num_paquete; $id_paquete++)
    { $pdf->AddPage();

      // Código de barras
      $pdf->SetY(-12);
      $pdf->SetFont('Arial','B',9); 
      //$pdf->Cell(50.5,5,$codePaquete_amplitud.' mm',0,0,'C');
      //$pdf->Cell(50.5,5,$codePaquete_amplitud.' mm',0,1,'C');
      $pdf->Ln(1);
      $pdf->SetFont('3of9','',22);      
      $pdf->Cell(50.5,5,'*'.$codePaquete_cdgpaquete[(($id_paquete*2)-1)].'*',0,0,'C'); 
      $pdf->Cell(50.5,5,'*'.$codePaquete_cdgpaquete[($id_paquete*2)].'*',0,1,'C');
      $pdf->Ln(.5);
      $pdf->SetFont('Arial','',8); 
      $pdf->Cell(50.5,3,$codePaquete_cdgpaquete[(($id_paquete*2)-1)].$codePaquete_amplitud.$codePaquete_corte,0,0,'C');
      $pdf->Cell(50.5,3,$codePaquete_cdgpaquete[($id_paquete*2)].$codePaquete_amplitud.$codePaquete_corte,0,1,'C');

      // Imagen LOGOTIPO
      if (file_exists('../../img_sistema/logo.jpg')==true) 
      { $pdf->Image('../../img_sistema/logo.jpg',4,4,14); }

      if (file_exists('../../img_sistema/logo.jpg')==true) 
      { $pdf->Image('../../img_sistema/logo.jpg',54.5,4,14); }
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
