<?php

  include '../../datos/mysql.php';	
  require('../../fpdf/fpdf.php');

  $link_mysqli = conectar();
  $pdtoImpresionSelect = $link_mysqli->query("
    SELECT pdtodiseno.diseno,
      pdtodiseno.alto,
      pdtodiseno.alpaso,
      pdtoimpresion.impresion,
      pdtoimpresion.cdgimpresion 
    FROM pdtodiseno,
      pdtoimpresion
    WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
      pdtoimpresion.cdgimpresion = '".$_GET['cdgproducto']."'");

  if ($pdtoImpresionSelect->num_rows > 0)
  { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

    $_SESSION['prodlote_diseno'] = $regPdtoImpresion->diseno;
    $_SESSION['prodlote_impresion'] = $regPdtoImpresion->impresion;
    $pdtoImpresion_alto = $regPdtoImpresion->alto;
    $pdtoImpresion_alpaso = $regPdtoImpresion->alpaso;
    $pdtoImpresion_cdgimpresion = $regPdtoImpresion->cdgimpresion; }

  if ($_GET['sttlote'] == 'A') { $_SESSION['status_lote'] = 'Programado'; }  
  if ($_GET['sttlote'] == '1') { $_SESSION['status_lote'] = 'Impreso'; }

  $link_mysqli = conectar();    
  $prodRolloSelect = $link_mysqli->query("
    SELECT proglote.tarima,
      proglote.idlote,
      proglote.lote,
      prodlote.noop,
      prodlote.longitud,
      prodlote.amplitud,
      prodlote.peso,
      prodlote.cdglote,
      prodlote.fchmovimiento
    FROM proglote,
      prodlote
    WHERE proglote.cdglote = prodlote.cdglote AND
      prodlote.sttlote = '".$_GET['sttlote']."' AND
      prodlote.cdgproducto = '".$pdtoImpresion_cdgimpresion."' AND
      prodlote.longitud >= 0
    ORDER BY prodlote.noop");

  if ($prodRolloSelect->num_rows > 0)
  { class PDF extends FPDF
    { function Header()
      { if ($_SESSION['usuario'] == '') { $_SESSION['usuario'] = 'Invitado'; }
        if (file_exists('../../img_sistema/logo.jpg')==true) { $this->Image('../../img_sistema/logo.jpg',10,7,0,10); }

        $this->SetY(5);
        $this->SetFont('arial','B',8);

        $this->Cell(0,4,'Usuario: '.$_SESSION['usuario'],0,1,'R');          
        $this->Cell(0,4,utf8_decode('Inventario en lotes: '.$_SESSION['prodlote_impresion'].' ('.$_SESSION['prodlote_diseno'].')'),0,1,'R');
        $this->Cell(0,4,'Status de lotes: '.$_SESSION['status_lote'],0,1,'R');          
        $this->Ln(2); 

        $this->SetFillColor(180,180,180);
        $this->SetFont('arial','B',8);

        $this->SetFont('arial','B',8);
        $this->Cell(4,4,'',0,0,'L');
        $this->Cell(35,4,'Lote',0,0,'L',true);
        $this->Cell(35,4,'No. Lote',0,0,'L',true);
        $this->Cell(30,4,'',0,0,'L',true);
        $this->Cell(20,4,'NoOP',0,0,'L',true);
        $this->Cell(22,4,'Longitud (mts)',0,0,'L',true);
        $this->Cell(22,4,'Amplitud (mm)',0,0,'L',true);
        $this->Cell(27,4,'Peso (kgs)',0,1,'L',true);          

      }
    }

    $pdf=new PDF('P','mm','letter'); 
    $pdf->AliasNbPages();
    $pdf->SetDisplayMode(real, continuous);    
    $pdf->AddPage();

    $id_lote = 1;
    $prodDocumento_longitud = 0;
    $prodDocumento_cantidad = 0;
    $prodDocumento_peso = 0;
    while ($regProdSubLote = $prodRolloSelect->fetch_object())
    { if ($prodDocumento_impresion == '')
      { $prodDocumento_impresion = $regProdSubLote->impresion; }

      if ($prodDocumento_mezcla == '')
      { $prodDocumento_mezcla = $regProdSubLote->mezcla; }

      $pdf->SetFont('arial','',5);
      $pdf->Cell(4,4,$id_lote,0,0,'R');

      $pdf->SetFont('arial','',8);
      $pdf->Cell(35,4,$regProdSubLote->tarima.'/'.$regProdSubLote->idlote,1,0,'L');
      $pdf->Cell(35,4,$regProdSubLote->lote,1,0,'R');
      $pdf->Cell(30,4,$regProdSubLote->fchmovimiento,1,0,'L');
      $pdf->Cell(20,4,$regProdSubLote->noop,1,0,'R');
      $pdf->Cell(22,4,number_format($regProdSubLote->longitud,2),1,0,'R');
      $pdf->Cell(22,4,$regProdSubLote->amplitud,1,0,'R');
      $pdf->Cell(22,4,number_format($regProdSubLote->peso,3),1,0,'R');
      $pdf->Cell(5,4,'',1,1,'R');      

      $prodDocumento_longitud += $regProdSubLote->longitud;
      $prodDocumento_cantidad += (($regProdSubLote->longitud*$pdtoImpresion_alpaso)/$pdtoImpresion_alto);
      $prodDocumento_peso += $regProdSubLote->peso;

      $id_lote++; }

      $pdf->SetFont('arial','B',8);
      $pdf->Cell(104,4,'',0,0,'R');
      $pdf->Cell(86,4,number_format($prodDocumento_longitud,2).' metros   '.number_format($prodDocumento_cantidad,3).' millares   '.number_format($prodDocumento_peso,3).' kilos',1,0,'R');
      $pdf->Cell(5,4,'',1,1,'R',true);

    $pdf->Cell(195,4,'Impresion: '.$pdtoImpresion_impresion.'   ['.$prodRolloSelect->num_rows.'] Rollos',0,1,'R');
    $pdf->Ln(2); 

    $pdf->Output('Inventario de lotes '.$_SESSION['prodlote_producto'].' en '.$_SESSION['status_lote'].' '.date('Y-m-d').'.pdf', 'I');
  } else
  { echo '<html>
  <head>    
    <link rel="stylesheet" type="text/css" href="../../css/global.css" media="screen" /> 
  </head>
  <body>
  </body>
</html>'; }  		 
  
?>
