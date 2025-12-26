<?php

  include '../../datos/mysql.php';	
  require('../../fpdf/fpdf.php');

  $link_mysqli = conectar();
  $pdtoImpresionSelect = $link_mysqli->query("
    SELECT * FROM pdtoimpresion
    WHERE cdgimpresion = '".$_GET['cdgproducto']."'");

  if ($pdtoImpresionSelect->num_rows > 0)
  { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

    $_SESSION['prodpaquete_producto'] = $regPdtoImpresion->impresion;    
    $pdtoImpresion_corte = $regPdtoImpresion->corte;
    $pdtoImpresion_cdgimpresion = $regPdtoImpresion->cdgimpresion; }

  if ($_GET['sttpaquete'] == 1) { $_SESSION['status_paquete'] = 'Cortado'; }  
  if ($_GET['sttpaquete'] == 7) { $_SESSION['status_paquete'] = 'Liberado'; }
  if ($_GET['sttpaquete'] == 9) { $_SESSION['status_paquete'] = 'Empacado'; }

  $link_mysqli = conectar();    
  $prodPaqueteSelect = $link_mysqli->query("
    SELECT SUM(cantidad) AS millares
    FROM prodpaquete
    WHERE prodpaquete.sttpaquete = '".$_GET['sttpaquete']."' AND
      prodpaquete.cdgproducto = '".$_GET['cdgproducto']."'");
      
  if ($prodPaqueteSelect->num_rows > 0)
  { $regProdPaquete = $prodPaqueteSelect->fetch_object(); 
  
    $prodPaquete_millares = $regProdPaquete->millares; }
  
  $link_mysqli = conectar();    
  $prodRolloSelect = $link_mysqli->query("
    SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo,'-',prodpaquete.paquete) AS noop,
      prodpaquete.cdgpaquete,
      prodpaquete.fchmovimiento
    FROM prodlote,
      prodbobina,
      prodrollo,
      prodpaquete
    WHERE (prodlote.cdglote = prodbobina.cdglote AND
      prodbobina.cdgbobina = prodrollo.cdgbobina AND
      prodrollo.cdgrollo = prodpaquete.cdgrollo) AND     
      prodpaquete.sttpaquete = '".$_GET['sttpaquete']."' AND
      prodpaquete.cdgproducto = '".$_GET['cdgproducto']."'
    ORDER BY prodpaquete.fchmovimiento,
      prodlote.noop,
      prodbobina.bobina,
      prodrollo.rollo,
      prodpaquete.paquete");

  if ($prodRolloSelect->num_rows > 0)
  { class PDF extends FPDF
    { function Header()
      { if ($_SESSION['usuario'] == '')
        { $_SESSION['usuario'] = 'Invitado'; }

        if (file_exists('../../img_sistema/logo.jpg')==true)
        { $this->Image('../../img_sistema/logo.jpg',10,7,0,10); }

        $this->SetY(5);
        $this->SetFont('arial','B',8);

        $this->Cell(0,4,'Usuario: '.$_SESSION['usuario'],0,1,'R');          
        $this->Cell(0,4,'Inventario de paquetes por producto '.$_SESSION['prodpaquete_producto'],0,1,'R');
        $this->Cell(0,4,'Status de paquete: '.$_SESSION['status_paquete'],0,1,'R');          
        $this->Ln(2); 

        $this->SetFillColor(180,180,180);
        $this->SetFont('arial','B',8);

        $this->SetFont('arial','B',8);
        $this->Cell(4,4,'',0,0,'L');
        $this->Cell(20,4,'Lote',0,0,'L',true);
        $this->Cell(40,4,'Codigo paquete',0,0,'L',true);
        $this->Cell(40,4,'',0,0,'L',true);
        $this->Cell(20,4,'Paquete',0,0,'L',true);
        $this->Cell(22,4,'Longitud (mts)',0,0,'L',true);
        $this->Cell(22,4,'Amplitud (mm)',0,0,'L',true);
        $this->Cell(27,4,'Peso (kgs)',0,1,'L',true);          

      }
    }

    $pdf=new PDF('P','mm','letter');
    $pdf->AliasNbPages();
    $pdf->SetDisplayMode(real, continuous);    
    $pdf->AddPage();

    $id_rollo = 1;
    $prodDocumento_longitud = 0;
    $prodDocumento_cantidad = 0;
    $prodDocumento_peso = 0;
    while ($regProdSubLote = $prodRolloSelect->fetch_object())
    { if ($prodDocumento_impresion == '')
      { $prodDocumento_impresion = $regProdSubLote->impresion; }

      if ($prodDocumento_mezcla == '')
      { $prodDocumento_mezcla = $regProdSubLote->mezcla; }

      $pdf->SetFont('arial','',5);
      $pdf->Cell(4,4,$id_rollo,0,0,'R');

      $pdf->SetFont('arial','',8);
      $pdf->Cell(20,4,$regProdSubLote->tarima.'/'.$regProdSubLote->idlote,1,0,'L');
      $pdf->Cell(40,4,$regProdSubLote->cdgpaquete,1,0,'R');
      $pdf->Cell(40,4,$regProdSubLote->fchmovimiento,1,0,'L');
      $pdf->Cell(20,4,$regProdSubLote->noop,1,0,'R');
      $pdf->Cell(22,4,number_format($regProdSubLote->longitud,2),1,0,'R');
      $pdf->Cell(22,4,$regProdSubLote->amplitud,1,0,'R');
      $pdf->Cell(22,4,number_format($regProdSubLote->peso,3),1,0,'R');
      $pdf->Cell(5,4,'',1,1,'R');      

      $prodDocumento_longitud += $regProdSubLote->longitud;
      //$prodDocumento_cantidad += ($regProdSubLote->longitud/$pdtoImpresion_corte);
      $prodDocumento_peso += $regProdSubLote->peso;

      $id_rollo++; }

      $pdf->SetFont('arial','B',8);
      $pdf->Cell(104,4,'',0,0,'R');
      $pdf->Cell(86,4,number_format($prodDocumento_longitud,2).' metros   '.number_format($prodPaquete_millares,3).' millares   '.number_format($prodDocumento_peso,3).' kilos',1,0,'R');
      $pdf->Cell(5,4,'',1,1,'R',true);

    $pdf->Cell(195,4,'Impresion: '.$pdtoImpresion_impresion.'   ['.$prodRolloSelect->num_rows.'] Paquetes',0,1,'R');
    $pdf->Ln(2); 

    $pdf->Output('Inventario de paquetes '.$_SESSION['prodpaquete_producto'].' en '.$_SESSION['status_paquete'].' '.date('Y-m-d').'.pdf', 'I');
  } else
  { echo '<html>
  <head>    
    <link rel="stylesheet" type="text/css" href="../../css/global.css" media="screen" /> 
  </head>
  <body>
?>
    No Body
  </body>
</html>'; }      
  
