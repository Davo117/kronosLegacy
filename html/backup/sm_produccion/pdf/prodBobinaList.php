<?php
  include '../../datos/mysql.php';

  $link_mysqli = conectar();
  $prodBobinaList = $link_mysqli->query("
    SELECT proglote.lote,
      proglote.tarima,
      proglote.idlote,
      prodlote.noop,
      prodbobina.bobina,
      pdtoproyecto.proyecto,
      pdtoimpresion.impresion,
      pdtoimpresion.corte,
      pdtomezcla.idmezcla,
      pdtomezcla.mezcla,
      prodbobina.longitud,
      prodbobina.amplitud,
      prodbobina.peso,
      prodbobina.cdgbobina
    FROM proglote,
      prodlote,
      prodbobina,
      pdtomezcla,
      pdtoimpresion,
      pdtoproyecto
    WHERE (proglote.cdglote = prodlote.cdglote 
      AND prodlote.cdglote = prodbobina.cdglote)
    AND (prodlote.cdgmezcla = pdtomezcla.cdgmezcla
      AND pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion
      AND pdtoimpresion.cdgproyecto = pdtoproyecto.cdgproyecto)
    AND prodbobina.sttbobina = '".$_GET['sttbobina']."'
    AND prodlote.cdgmezcla = '".$_GET['cdgmezcla']."'
    ORDER BY prodlote.noop,
      prodbobina.bobina");

  if ($prodBobinaList->num_rows > 0)
  { include '../../fpdf/fpdf.php';

    class PDF extends FPDF
    { function Header()
      { if ($_SESSION['usuario'] == '')
        { $_SESSION['usuario'] = 'Invitado'; }

        if (file_exists('../../img_sistema/logo.jpg')==true)
        { $this->Image('../../img_sistema/logo.jpg',10,7,0,10); }

        $this->SetY(5);
        $this->SetFont('arial','B',8);
        $this->Cell(0,4,'Usuario: '.$_SESSION['usuario'],0,0,'R');
        $this->Ln();
        $this->Cell(0,4,'Listado de bobinas',0,1,'R');
           
        $this->Ln(6);

        $this->SetFillColor(180,180,180);
        $this->SetFont('arial','B',8);
/*
  echo '
    <table>
      <tr><th>No Lote</th>        
        <th>Lote</th>        
        <th>NoOP</th>        
        <th>Proyecto</th>
        <th>Impresion</th>
        <th>Mezcla</th>
        <th>Desc</th>
        <th>Longitud</th>
        <th>Amplitud</th>
        <th>Peso</th>
        <th>Codigo</th></tr>'; //*/

        $this->SetFont('arial','B',8);
        $this->Cell(24,4,'  Lote',0,0,'L',true);
        $this->Cell(40,4,'No. Lote',0,0,'L',true);
        $this->Cell(40,4,'',0,0,'L',true);
        $this->Cell(20,4,'Bobina',0,0,'L',true); //NoOP
        $this->Cell(22,4,'Longitud (mts)',0,0,'L',true);
        $this->Cell(22,4,'Amplitud (mm)',0,0,'L',true);
        $this->Cell(27,4,'Peso (kgs)',0,0,'L',true);
        $this->Ln(); }
    }

    $pdf=new PDF('P','mm','letter');
    $pdf->AliasNbPages();
    $pdf->SetDisplayMode(real, continuous);
    $pdf->AddPage();


    $id_bobina = 1;            
    while ($regProdBobina = $prodBobinaList->fetch_object())
    { $pdf->SetFont('arial','',5);
      $pdf->Cell(4,4,$id_bobina,0,0,'R');
      $pdf->SetFont('arial','',8);
      $pdf->Cell(20,4,$regProdBobina->tarima.'/'.$regProdBobina->idlote,0,0,'L');
      $pdf->Cell(40,4,$regProdBobina->lote,0,0,'R');
      $pdf->Cell(40,4,'',0,0,'L');
      $pdf->Cell(20,4,$regProdBobina->noop.'-'.$regProdBobina->bobina,0,0,'R','','prodBobinaBC.php');
      $pdf->Cell(22,4,number_format($regProdBobina->longitud,2),0,0,'R');
      $pdf->Cell(22,4,$regProdBobina->amplitud,0,0,'R');
      $pdf->Cell(22,4,number_format($regProdBobina->peso,3),0,0,'R');
      $pdf->Cell(5,4,'',1,0,'R');
      $pdf->Ln();

      $prodBobinaList_longitud += $regProdBobina->longitud;
      $prodBobinaList_peso += $regProdBobina->peso;

      $prodBobinaList_proyecto = $regProdBobina->proyecto;
      $prodBobinaList_impresion = $regProdBobina->impresion;
      $prodBobinaList_corte = $regProdBobina->corte;
      $prodBobinaList_mezcla = $regProdBobina->mezcla;

      $id_bobina++; }

      $pdf->SetFont('arial','B',8);
      $pdf->Cell(104,4,'',0,0,'R');
      $pdf->Cell(86,4,number_format($prodBobinaList_longitud,2).' metros   '.number_format($prodBobinaList_longitud/$prodBobinaList_corte,3).' millares   '.number_format($prodBobinaList_peso,3).' kilos',1,0,'R');
      $pdf->Cell(5,4,'',1,1,'R',true);

    $pdf->Cell(195,4,'Proyecto: '.$prodBobinaList_proyecto.'    Impresion: '.$prodBobinaList_impresion.'    Mezcla: '.$prodBobinaList_mezcla.'   ['.$prodBobinaList->num_rows.'] Bobinas',0,1,'R');
    $pdf->Ln(2);  //*/

    $pdf->Output('Documento de transferencia de bobinas '.$_SESSION['prodBobinaList_iddocumento'].'.pdf', 'I'); 
  } 

  $prodBobinaList->close;

?>
