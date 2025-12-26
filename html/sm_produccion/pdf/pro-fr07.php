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

  $prodLoteSelect = $link->query("
    SELECT proglote.tarima,
		       proglote.idlote,
		       proglote.lote,
		       proglote.longitud,
		       proglote.peso,
		       prodlote.noop
		  FROM proglote, 
           prodlote, 
           prodloteope
		 WHERE proglote.cdglote = prodlote.cdglote AND
		       prodlote.cdglote = prodloteope.cdglote AND
		       prodlote.cdgproducto = '".$_GET['cdgimpresion']."' AND
		      (prodloteope.cdgoperacion = '10001' AND 
		 	     prodloteope.cdgmaquina = '".$_GET['cdgmaquina']."' AND 
		 	     prodloteope.cdgjuego = '".$_GET['cdgjuego']."' AND 
			     prodlote.fchprograma = '".$_GET['fchprograma']."')
  ORDER BY prodlote.noop");

  if ($prodLoteSelect->num_rows > 0)
  { $idLote = 1;
  	while ($regProdLote = $prodLoteSelect->fetch_object())
  	{ $oplist_tarima[$idLote] = $regProdLote->tarima;
      $oplist_idlote[$idLote] = $regProdLote->idlote;
      $oplist_lote[$idLote] = $regProdLote->lote;
      $oplist_longitud[$idLote] = $regProdLote->longitud;
      $oplist_peso[$idLote] = $regProdLote->peso;
      $oplist_noop[$idLote] = $regProdLote->noop;

      $idLote++; }

    $numLotes = $prodLoteSelect->num_rows;
  }

  $pdtoImpresionTntSelect = $link->query("
    SELECT R_SOLID, 
      G_SOLID, 
      B_SOLID, 
      notinta, 
      pantone, 
      consumo,
      disolvente,
      viscosidad,
      temperatura
    FROM pdtoimpresion, 
      pdtoimpresiontnt, 
      pantone
    WHERE pdtoimpresion.cdgimpresion = '".$_GET['cdgimpresion']."' AND
      pdtoimpresion.cdgimpresion = pdtoimpresiontnt.cdgimpresion AND
      pdtoimpresiontnt.cdgtinta = pantone.idpantone");

  if ($pdtoImpresionTntSelect->num_rows > 0)
  { $idTinta = 1;
    while ($regImpresionTnt = $pdtoImpresionTntSelect->fetch_object())
    { $oplist_R[$idTinta] = $regImpresionTnt->R_SOLID;
      $oplist_G[$idTinta] = $regImpresionTnt->G_SOLID;
      $oplist_B[$idTinta] = $regImpresionTnt->B_SOLID;
      $oplist_notinta[$idTinta] = $regImpresionTnt->notinta;
      $oplist_pantone[$idTinta] = $regImpresionTnt->pantone;
      $oplist_consumo[$idTinta] = number_format(($regImpresionTnt->consumo*$_GET['millares']),3);
      $oplist_disolvente[$idTinta] = $regImpresionTnt->disolvente;
      $oplist_viscosidad[$idTinta] = $regImpresionTnt->viscosidad;
      $oplist_temperatura[$idTinta] = $regImpresionTnt->temperatura;

      $idTinta++; }

    $numTintas = $pdtoImpresionTntSelect->num_rows;
  }

	class PDF extends FPDF
	{ // Cabecera de página
		function Header()
		{ global $oplist_sustrato;

      global $oplist_jviscosidad;
      global $oplist_jtemperatura;
      global $oplist_jvelocidad;
      global $oplist_jpsigoma;
      global $oplist_jpsicilindro;
      global $oplist_jpsirasqueta;

      global $oplist_jtviscosidad;
      global $oplist_jttemperatura;
      global $oplist_jtvelocidad;
      global $oplist_jtpsigoma;
      global $oplist_jtpsicilindro;
      global $oplist_jtpsirasqueta;

      $this->SetFont('Arial','B',8);
			$this->SetFillColor(255,153,0);

      if (file_exists('../../img_sistema/logonew.jpg')==true)
      { $this->Image('../../img_sistema/logonew.jpg',10,0,0,32); }

			$this->SetFont('Arial','B',8);
			$this->Cell(185,4,utf8_decode('Documento'),0,0,'R');
			$this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Verificación de parámetros'),0,1,'L');

      $this->SetFont('Arial','B',8);
			$this->Cell(185,4,utf8_decode('Código'),0,0,'R');
			$this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('PRO-FR07'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(185,4,utf8_decode('Versión'),0,0,'R');
			$this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('1.0'),0,1,'L');

      $this->SetFont('Arial','B',8);
			$this->Cell(185,4,utf8_decode('Revisión'),0,0,'R');
			$this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('22 de Septiembre de 2014'),0,1,'L');

      $this->SetFont('Arial','B',8);
			$this->Cell(185,4,utf8_decode('Responsable'),0,0,'R');
		  $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Impresor'),0,1,'L'); 

      $this->Ln(4);

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

      $this->Ln(-24);

      $this->SetFont('Arial','I',8);
      $this->Cell(210,4,utf8_decode('Viscosidad'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','B',8);
      $this->Cell(40,4,$oplist_jviscosidad.' tolerancia +/- '.$oplist_jtviscosidad.' seg',0,1,'L');

      $this->SetFont('Arial','I',8);
      $this->Cell(210,4,utf8_decode('Temperatura'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','B',8);
      $this->Cell(60,4,$oplist_jtemperatura.' tolerancia +/- '.$oplist_jttemperatura.utf8_decode(' °C'),0,1,'L');
      
      $this->SetFont('Arial','I',8);
      $this->Cell(210,4,utf8_decode('Velocidad'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','B',8);
      $this->Cell(60,4,$oplist_jvelocidad.' tolerancia +/- '.$oplist_jtvelocidad.' mts/min',0,1,'L');
      
      $this->SetFont('Arial','I',8);
      $this->Cell(210,4,utf8_decode('Presión en gomas'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','B',8);
      $this->Cell(60,4,$oplist_jpsigoma.' tolerancia +/- '.$oplist_jtpsigoma.' psi',0,1,'L');
      
      $this->SetFont('Arial','I',8);
      $this->Cell(210,4,utf8_decode('Presión en cilindros'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','B',8);
      $this->Cell(60,4,$oplist_jpsicilindro.' tolerancia +/- '.$oplist_jtpsicilindro.' psi',0,1,'L');
      
      $this->SetFont('Arial','I',8);
      $this->Cell(210,4,utf8_decode('Presión en rasquetas'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','B',8);
      $this->Cell(60,4,$oplist_jpsirasqueta.' tolerancia +/- '.$oplist_jtpsirasqueta.' psi',0,1,'L');
      
      $this->Ln(4);
    }

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
		  $this->Cell(0,6,utf8_decode('Grupo Labro | Página '.$this->PageNo().' de {nb}'),0,1,'R'); 	    
		}
	}

	$pdf = new PDF('L','mm','letter');
	$pdf->SetDisplayMode(real, continuous);
  $pdf->AddFont('3of9','','free3of9.php');
  $pdf->AliasNbPages();

	$pdf->AddPage();

  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(20,15,'Cabina',1,0,'C');
  $pdf->Cell(($numLotes*12),5,'NoOP',1,0,'C');
  $pdf->Ln();

  $pdf->SetX(30);

  for ($idLote = 1; $idLote <= $numLotes; $idLote++)
  { $pdf->Cell(12,5,$oplist_noop[$idLote],1,0,'C'); }
  
  $pdf->Ln();

  $pdf->SetFont('Arial','',8);
  $pdf->SetX(20);
  $pdf->Cell(10,5,'Hora',1,0,'C');
  for ($idLote = 1; $idLote <= $numLotes; $idLote++)
  { $pdf->Cell(12,5,':',1,0,'C'); }
  
  $pdf->Ln();

  for ($id = 1; $id <= 10; $id++)
  { $pdf->SetFont('Arial','B',11);
    $pdf->Cell(6,10,$id,1,0,'C');
    
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(14,5,'Viscosidad',1,0,'R');

    for ($idLote = 1; $idLote <= $numLotes; $idLote++)
    { $pdf->Cell(12,5,'',1,0,'C'); }
    $pdf->Ln();

    $pdf->SetX(16);
    $pdf->Cell(14,5,'Temperatura',1,0,'R');

    for ($idLote = 1; $idLote <= $numLotes; $idLote++)
    { $pdf->Cell(12,5,'',1,0,'C'); }
    $pdf->Ln();
  }

  $pdf->Cell(20,5,utf8_decode('Tensión inicial'),1,0,'R');  
    for ($idLote = 1; $idLote <= $numLotes; $idLote++)
    { $pdf->Cell(12,5,'',1,0,'C'); }
    $pdf->Ln();

  $pdf->Cell(20,5,utf8_decode('Tensión final'),1,0,'R');  
    for ($idLote = 1; $idLote <= $numLotes; $idLote++)
    { $pdf->Cell(12,5,'',1,0,'C'); }
    $pdf->Ln();

  $pdf->Cell(20,5,utf8_decode('Velocidad'),1,0,'R');  
    for ($idLote = 1; $idLote <= $numLotes; $idLote++)
    { $pdf->Cell(12,5,'',1,0,'C'); }
    $pdf->Ln();

  ////////////////////////////////////////////////
	$pdf->Output('PRO-FR07.pdf', 'I');
?>