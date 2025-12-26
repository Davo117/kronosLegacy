<?php

  include '../../datos/mysql.php';	
  require('../../fpdf/fpdf.php');

  $link_mysqli = conectar();
  $pdtoImpresionSelect = $link_mysqli->query("
    SELECT * FROM pdtoimpresion
    WHERE cdgimpresion = '".$_GET['cdgproducto']."'");

  if ($pdtoImpresionSelect->num_rows > 0)
  { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

    $_SESSION['prodbobina_producto'] = $regPdtoImpresion->impresion;    
    $pdtoImpresion_corte = $regPdtoImpresion->corte;
    $pdtoImpresion_alpaso = $regPdtoImpresion->alpaso;
    $pdtoImpresion_cdgimpresion = $regPdtoImpresion->cdgimpresion; }

  if ($_GET['sttbobina'] == 1) { $_SESSION['status_bobina'] = 'Refilado'; }  
  if ($_GET['sttbobina'] == 7) { $_SESSION['status_bobina'] = 'Liberado'; }
  if ($_GET['sttbobina'] == 8) { $_SESSION['status_bobina'] = 'Transferido'; }
  if ($_GET['sttbobina'] == 9) { $_SESSION['status_bobina'] = 'Yuriria'; }

  $link_mysqli = conectar();    
  $prodRolloSelect = $link_mysqli->query("
    SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina) AS noop,        
      prodbobina.longitud,
      prodbobina.amplitud,
      prodbobina.peso,
      prodbobina.cdgbobina,
      prodbobina.fchmovimiento
    FROM prodlote,
      prodbobina
    WHERE (prodlote.cdglote = prodbobina.cdglote) AND     
      prodbobina.sttbobina = '".$_GET['sttbobina']."' AND
      prodbobina.cdgproducto = '".$pdtoImpresion_cdgimpresion."' AND
      prodbobina.longitud >= 1
    ORDER BY prodlote.noop,
      prodbobina.bobina");

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
        $this->Cell(0,4,'Inventario de bobinas por producto '.$_SESSION['prodbobina_producto'],0,1,'R');
        $this->Cell(0,4,'Status de bobina: '.$_SESSION['status_bobina'],0,1,'R');          
        $this->Ln(2); 

        $this->SetFillColor(180,180,180);
        $this->SetFont('arial','B',8);

        $this->SetFont('arial','B',8);
        $this->Cell(4,4,'',0,0,'L');
        $this->Cell(20,4,'Lote',0,0,'L',true);
        $this->Cell(40,4,'No. Lote',0,0,'L',true);
        $this->Cell(40,4,'',0,0,'L',true);
        $this->Cell(20,4,'Bobina',0,0,'L',true);
        $this->Cell(22,4,'Longitud (mts)',0,0,'L',true);
        $this->Cell(22,4,'Amplitud (mm)',0,0,'L',true);
        $this->Cell(27,4,'Peso (kgs)',0,1,'L',true);          

      }
    }

    $pdf=new PDF('P','mm','letter');
    $pdf->AliasNbPages();
    $pdf->SetDisplayMode(real, continuous);    
    $pdf->AddPage();

    $id_bobina = 1;
    $prodDocumento_longitud = 0;
    $prodDocumento_cantidad = 0;
    $prodDocumento_peso = 0;
    while ($regProdSubLote = $prodRolloSelect->fetch_object())
    { if ($prodDocumento_impresion == '')
      { $prodDocumento_impresion = $regProdSubLote->impresion; }

      if ($prodDocumento_mezcla == '')
      { $prodDocumento_mezcla = $regProdSubLote->mezcla; }

      $pdf->SetFont('arial','',5);
      $pdf->Cell(4,4,$id_bobina,0,0,'R');

      $pdf->SetFont('arial','',8);
      $pdf->Cell(20,4,$regProdSubLote->tarima.'/'.$regProdSubLote->idlote,1,0,'L');
      $pdf->Cell(40,4,$regProdSubLote->lote,1,0,'R');
      $pdf->Cell(40,4,$regProdSubLote->fchmovimiento,1,0,'L');
      $pdf->Cell(20,4,$regProdSubLote->noop,1,0,'R');
      $pdf->Cell(22,4,number_format($regProdSubLote->longitud,2),1,0,'R');
      $pdf->Cell(22,4,$regProdSubLote->amplitud,1,0,'R');
      $pdf->Cell(22,4,number_format($regProdSubLote->peso,3),1,0,'R');
      $pdf->Cell(5,4,'',1,1,'R');      

      $prodDocumento_longitud += $regProdSubLote->longitud;
      $prodDocumento_cantidad += ($regProdSubLote->longitud/$pdtoImpresion_corte);
      $prodDocumento_peso += $regProdSubLote->peso;

      $id_bobina++; }

      $pdf->SetFont('arial','B',8);
      $pdf->Cell(104,4,'',0,0,'R');
      $pdf->Cell(86,4,number_format($prodDocumento_longitud,2).' metros   '.number_format($prodDocumento_cantidad,3).' millares   '.number_format($prodDocumento_peso,3).' kilos',1,0,'R');
      $pdf->Cell(5,4,'',1,1,'R',true);

    $pdf->Cell(195,4,'Impresion: '.$pdtoImpresion_impresion.'   ['.$prodRolloSelect->num_rows.'] Rollos',0,1,'R');
    $pdf->Ln(2); 

    $pdf->Output('Inventario de bobinas '.$_SESSION['prodbobina_producto'].' en '.$_SESSION['status_bobina'].' '.date('Y-m-d').'.pdf', 'I');
  } else
  { echo '<html>
  <head>    
    <link rel="stylesheet" type="text/css" href="../../css/global.css" media="screen" /> 
  </head>
  <body>
  </body>
</html>'; }  		 
  
?>
