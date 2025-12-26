<?php
  include '../../datos/mysql.php';	
  require('../../fpdf/fpdf.php');

  $link = conectar();

	$pdtoImpresionSelect = $link->query("
    SELECT pdtodiseno.diseno,
           pdtoimpresion.notintas,
           pdtoimpresion.impresion,
           pdtosustrato.sustrato
      FROM pdtodiseno,
           pdtoimpresion,
           pdtosustrato
     WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
           pdtoimpresion.cdgimpresion = '".$_GET['cdgimpresion']."' AND
           pdtosustrato.cdgsustrato = pdtoimpresion.cdgsustrato");

	if ($pdtoImpresionSelect->num_rows > 0)
	{ $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

	  $_SESSION['oplist_diseno'] = $regPdtoImpresion->diseno;
	  $oplist_notintas = $regPdtoImpresion->notintas;
	  $_SESSION['oplist_impresion'] = $regPdtoImpresion->impresion;
	  $oplist_sustrato = $regPdtoImpresion->sustrato; } 

  $prodMaquinaSelect = $link->query("
  	SELECT * FROM prodmaquina 
  	 WHERE cdgmaquina = '".$_GET['cdgmaquina']."'");
  
  if ($prodMaquinaSelect->num_rows > 0)
  { $regProdMaquina = $prodMaquinaSelect->fetch_object();

    $_SESSION['oplist_idmaquina'] = $regProdMaquina->idmaquina;
    $_SESSION['oplist_maquina'] = $regProdMaquina->maquina; }

  $pdtoJuegoSelect = $link->query("
  	SELECT * FROM pdtojuego 
     WHERE cdgjuego = '".$_GET['cdgjuego']."'");
  
  if ($pdtoJuegoSelect->num_rows > 0)
  { $regPdtoJuego = $pdtoJuegoSelect->fetch_object();

    $oplist_jviscosidad = $regPdtoJuego->viscosidad;
    $oplist_jtemperatura = $regPdtoJuego->temperatura;
    $oplist_jvelocidad = $regPdtoJuego->velocidad;
    $oplist_jpsigoma = $regPdtoJuego->psigoma;
    $oplist_jpsicilindro = $regPdtoJuego->psicilindro;
    $oplist_jpsirasqueta = $regPdtoJuego->psirasqueta;

    $oplist_jtviscosidad = $regPdtoJuego->tviscosidad;
    $oplist_jttemperatura = $regPdtoJuego->ttemperatura;
    $oplist_jtvelocidad = $regPdtoJuego->tvelocidad;
    $oplist_jtpsigoma = $regPdtoJuego->tpsigoma;
    $oplist_jtpsicilindro = $regPdtoJuego->tpsicilindro;
    $oplist_jtpsirasqueta = $regPdtoJuego->tpsirasqueta;

    $_SESSION['oplist_idjuego'] = $regPdtoJuego->idjuego;
    $_SESSION['oplist_cdgjuego'] = $regPdtoJuego->cdgjuego; }
  
  $pdtoImpresionTntSelect = $link->query("
    SELECT pdtopantone.pantone, 
           pdtoimpresiontnt.notinta,
           pdtoimpresiontnt.consumo,
           pdtoimpresiontnt.disolvente
      FROM pdtopantone,
           pdtoimpresion,
           pdtoimpresiontnt
     WHERE pdtoimpresion.cdgimpresion = '".$_GET['cdgimpresion']."' AND
           pdtoimpresion.cdgimpresion = pdtoimpresiontnt.cdgimpresion AND
           pdtoimpresiontnt.cdgtinta = pdtopantone.cdgpantone");

  if ($pdtoImpresionTntSelect->num_rows > 0)
  { $item = 0;
    
    while ($regImpresionTnt = $pdtoImpresionTntSelect->fetch_object())
    { $item++;

      $oplist_pantone[$item] = $regImpresionTnt->pantone;
      $oplist_notinta[$item] = $regImpresionTnt->notinta;      
      $oplist_consumo[$item] = number_format(($regImpresionTnt->consumo*$_GET['millares']),3);
      $oplist_disolvente[$item] = $regImpresionTnt->disolvente; }

    $nTintas = $pdtoImpresionTntSelect->num_rows; }

	class PDF extends FPDF
	{ // Cabecera de página
		function Header()
		{ global $oplist_sustrato;

       $this->SetFont('Arial','B',8);
			$this->SetFillColor(255,153,0);

      if (file_exists('../../img_sistema/logonew.jpg')==true)
      { $this->Image('../../img_sistema/logonew.jpg',10,0,0,32); }

			$this->SetFont('Arial','B',8);
			$this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
			$this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Trazabilidad de lotes de tinta'),0,1,'L');

      $this->SetFont('Arial','B',8);
			$this->Cell(125,4,utf8_decode('Código'),0,0,'R');
			$this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('PRO-FR06'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Versión'),0,0,'R');
			$this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('1.0'),0,1,'L');

      $this->SetFont('Arial','B',8);
			$this->Cell(125,4,utf8_decode('Revisión'),0,0,'R');
			$this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('22 de Septiembre de 2014'),0,1,'L');

      $this->SetFont('Arial','B',8);
			$this->Cell(125,4,utf8_decode('Responsable'),0,0,'R');
		  $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Impresor'),0,1,'L'); 

      $this->Ln(4);

      $this->SetFont('3of9','',28);      
      $this->Cell(195,5,'*'.$_GET['cdgfchprograma'].$_GET['cdgimpresion'].$_GET['cdgjuego'].$_GET['cdgmaquina'].'*',0,1,'R');
      $this->SetFont('Arial','B',10);
      $this->Cell(195,7,$_GET['cdgfchprograma'].$_GET['cdgimpresion'].$_GET['cdgjuego'].$_GET['cdgmaquina'],0,1,'R');  

      $this->SetFont('3of9','',24);      
      $this->Cell(195,5,'*'.$_SESSION['oplist_cdgjuego'].'*',0,1,'R');
      $this->SetFont('Arial','B',9);    
      $this->Cell(195,6,$_SESSION['oplist_cdgjuego'],0,1,'R');

      $this->Ln(-25);

      $this->SetFont('Arial','I',8);
      $this->Cell(40,4,utf8_decode('Fecha de la orden'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','B',8);
      $this->Cell(40,4,$_GET['fchprograma'],0,1,'L');

      $this->SetFont('Arial','I',8);
      $this->Cell(40,4,utf8_decode('Diseño'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','B',8);
      $this->Cell(60,4,$_SESSION['oplist_diseno'],0,1,'L');

      $this->SetFont('Arial','I',8);
      $this->Cell(40,4,utf8_decode('Juego de cilindros'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','B',8);
      $this->Cell(60,4,$_SESSION['oplist_idjuego'].' ('.$_SESSION['oplist_cdgjuego'].')',0,1,'L');

      $this->SetFont('Arial','I',8);
      $this->Cell(40,4,utf8_decode('Impresión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','B',8);
      $this->Cell(60,4,$_SESSION['oplist_impresion'],0,1,'L');

      $this->SetFont('Arial','I',8);
      $this->Cell(40,4,utf8_decode('Máquina de impresión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);  
      $this->SetFont('Arial','B',8);
      $this->Cell(60,4,$_SESSION['oplist_idmaquina'].' '.$_SESSION['oplist_maquina'],0,1,'L');

      $this->SetFont('Arial','I',8);
      $this->Cell(40,4,utf8_decode('Sustrato'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','B',8);
      $this->Cell(60,4,$oplist_sustrato,0,1,'L');

      $this->Ln(4); }

		// Pie de página
		function Footer()
		{ $this->SetY(-20);

      $this->SetFont('Arial','B',8);
      $this->Cell(195.9,4,'______________________________________________________',0,1,'C');
      $this->Cell(195.9,4,utf8_decode('Nombre y firma del operador'),0,1,'C');

      $this->SetY(-10);
		  $this->SetFont('arial','B',8);
		  $this->Cell(0,6,'Consultado el '.date("Y-m-d").' a las '.date("G:i:s").'',0,1,'R'); 
		  $this->SetFont('arial','',8);
		  $this->SetY(-7.5);
		  $this->Cell(0,6,utf8_decode('Grupo Labro | Página '.$this->PageNo().' de {nb}'),0,1,'R'); }
	}

	$pdf = new PDF('P','mm','letter');
	$pdf->SetDisplayMode(real, continuous);
  $pdf->AddFont('3of9','','free3of9.php');
  $pdf->AliasNbPages();

	$pdf->AddPage();

  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(60,6,'Necesidades de tinta',0,0,'C');

  $pdf->SetFont('Arial','',8);
  $pdf->Cell(25,6,'Requerido',1,0,'C');
  $pdf->Cell(25,6,'No. Lote',1,0,'C');
  $pdf->Cell(25,6,'Disolvente',1,1,'C');

  for ($idTinta = 1; $idTinta <= $nTintas; $idTinta++)
  { $pdf->SetFont('Arial','B',12);    
    
    $pdf->Cell(9,7,$oplist_notinta[$idTinta],0,0,'C');
    $pdf->Cell(1,7,'',0,0,'C');

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(50,8,$oplist_pantone[$idTinta],1,0,'L'); 

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(25,8,$oplist_consumo[$idTinta].' kgs',1,0,'R'); 
    $pdf->Cell(25,8,'',1,0,'R');
    
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(25,8,$oplist_disolvente[$idTinta],1,1,'C'); }

  $_SESSION['oplist_diseno'] = '';
  $_SESSION['oplist_impresion'] = '';
  $_SESSION['oplist_idjuego'] = '';
  $_SESSION['oplist_cdgjuego'] = '';
  $_SESSION['oplist_idmaquina'] = '';
  $_SESSION['oplist_maquina'] = '';

  ////////////////////////////////////////////////
	$pdf->Output('PRO-FR06.pdf', 'I');
?>