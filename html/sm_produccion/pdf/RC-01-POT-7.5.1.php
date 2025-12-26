<?php
  include '../../datos/mysql.php';	
  require('../../fpdf/fpdf.php');

	$link_mysqli = conectar();
	$pdtoImpresionSelect = $link_mysqli->query("
	  SELECT pdtodiseno.diseno,
	    pdtodiseno.notintas,
	    pdtoimpresion.impresion,
	    pdtosustrato.sustrato
	  FROM pdtodiseno,
	    pdtoimpresion,
	    pdtosustrato
	  WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
	    pdtoimpresion.cdgimpresion = '".$_GET['cdgimpresion']."' AND
	    pdtodiseno.cdgsustrato = pdtosustrato.cdgsustrato");

	if ($pdtoImpresionSelect->num_rows > 0)
	{ $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

	  $_SESSION['oplist_diseno'] = $regPdtoImpresion->diseno;
	  $oplist_notintas = $regPdtoImpresion->notintas;
	  $_SESSION['oplist_impresion'] = $regPdtoImpresion->impresion;
	  $oplist_sustrato = $regPdtoImpresion->sustrato; } 

  $link_mysqli = conectar();
  $prodMaquinaSelect = $link_mysqli->query("
  	SELECT * FROM prodmaquina 
  	WHERE cdgmaquina = '".$_GET['cdgmaquina']."' AND 
      cdgproceso = '20' AND 
      sttmaquina >= '1'");
  
  if ($prodMaquinaSelect->num_rows > 0)
  { $regProdMaquina = $prodMaquinaSelect->fetch_object();

    $_SESSION['oplist_idmaquina'] = $regProdMaquina->idmaquina;
    $_SESSION['oplist_maquina'] = $regProdMaquina->maquina; }

  $link_mysqli = conectar();
  $pdtoJuegoSelect = $link_mysqli->query("
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

  $link_mysqli = conectar();
  $prodLoteSelect = $link_mysqli->query("
    SELECT proglote.tarima,
		  proglote.idlote,
		  proglote.lote,
		  proglote.longitud,
		  proglote.peso,
		  prodlote.noop
		FROM proglote, prodlote, prodloteope
		WHERE proglote.cdglote = prodlote.cdglote AND
		  prodlote.cdglote = prodloteope.cdglote AND
		  prodlote.cdgproducto = '".$_GET['cdgimpresion']."' AND
		 (prodloteope.cdgoperacion = '10001' AND 
		 	prodloteope.cdgmaquina = '".$_GET['cdgmaquina']."' AND 
		 	prodloteope.cdgjuego = '".$_GET['cdgjuego']."' AND 
			prodlote.fchprograma = '".$_GET['fchprograma']."')");

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

  $link_mysqli = conectar();
  $pdtoImpresionTntSelect = $link_mysqli->query("
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
		{ $this->SetFont('Arial','B',8);
			$this->SetFillColor(255,153,0);

      if (file_exists('../../img_sistema/logonew.jpg')==true)
      { $this->Image('../../img_sistema/logonew.jpg',10,0,0,32); }

			$this->SetFont('Arial','B',8);
			$this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
			$this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Orden de trabajo para Impresión con Huecograbado'),0,1,'L');

      $this->SetFont('Arial','B',8);
			$this->Cell(125,4,utf8_decode('Código'),0,0,'R');
			$this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('PRO-FR-05'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Versión'),0,0,'R');
			$this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('1.0'),0,1,'L');

      $this->SetFont('Arial','B',8);
			$this->Cell(125,4,utf8_decode('Revisión'),0,0,'R');
			$this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Agosto 05, 2014'),0,1,'L');

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

      $this->Ln(-21);

      $this->SetFont('Arial','I',8);
      $this->SetFillColor(255,153,0);

      $this->Cell(40,4,utf8_decode('Fecha de la orden de trabajo'),0,0,'R');
      $this->Cell(0.5,4,'',0,1,'R',true);
      $this->Cell(40,4,utf8_decode('Diseño'),0,0,'R');
      $this->Cell(0.5,4,'',0,1,'R',true);
      $this->Cell(40,4,utf8_decode('Juego de cilindros'),0,0,'R');
      $this->Cell(0.5,4,'',0,1,'R',true);
      $this->Cell(40,4,utf8_decode('Impresión'),0,0,'R');
      $this->Cell(0.5,4,'',0,1,'R',true);
      $this->Cell(40,4,utf8_decode('Máquina de impresión'),0,0,'R');
      $this->Cell(0.5,4,'',0,1,'R',true);

      $this->Ln(-20);
      $this->SetFont('Arial','B',8);
      $this->Cell(40.5,4,'',0,0,'R');
      $this->Cell(40,4,$_GET['fchprograma'],0,1,'L');
      $this->Cell(40.5,4,'',0,0,'R');
      $this->Cell(60,4,$_SESSION['oplist_diseno'],0,1,'L');
      $this->Cell(40.5,4,'',0,0,'R');
      $this->Cell(60,4,$_SESSION['oplist_idjuego'].' ('.$_SESSION['oplist_cdgjuego'].')',0,1,'L');
      $this->Cell(40.5,4,'',0,0,'R');
      $this->Cell(60,4,$_SESSION['oplist_impresion'],0,1,'L');
      $this->Cell(40.5,4,'',0,0,'R');
      $this->Cell(60,4,$_SESSION['oplist_idmaquina'].' '.$_SESSION['oplist_maquina'],0,1,'L');
    }

		// Pie de página
		function Footer()
		{ $this->SetY(-20);

	    $this->SetFont('Arial','B',8);
	    $this->Cell(195.9,4,'______________________________________________________',0,1,'C');
	    $this->Cell(195.9,4,utf8_decode('Nombre y firma de quien realizo la impresión'),0,1,'C');

		  $this->SetY(-10);
		  $this->SetFont('arial','B',8);
		  $this->Cell(0,6,'Consultado el '.date("Y-m-d").' a las '.date("G:i:s").'',0,1,'R'); 
		  $this->SetFont('arial','',8);
		  $this->SetY(-7.5);
		  $this->Cell(0,6,utf8_decode('Grupo Labro | Página '.$this->PageNo().' de {nb}'),0,1,'R'); 	    
		}
	}

	$pdf = new PDF('P','mm','letter');
	$pdf->SetDisplayMode(real, continuous);
  $pdf->AddFont('3of9','','free3of9.php');
  $pdf->AliasNbPages();

	$pdf->AddPage();

	$pdf->SetFont('Arial','I',8);
	$pdf->SetFillColor(255,153,0);

  $pdf->Cell(40,4,utf8_decode('Sustrato'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,1,'R',true);

  $pdf->Ln(-4);
  $pdf->SetFont('Arial','B',8);
	$pdf->Cell(40.5,4,'',0,0,'R');
  $pdf->Cell(60,4,$oplist_sustrato,0,1,'L');

  $pdf->Ln(6);

  $pdf->SetFont('Arial','B',10);

  $pdf->Cell(10,8,'#',0,0,'C');  
  $pdf->Cell(45,8,'Lote',1,0,'C');
  $pdf->Cell(20,8,'NoOP',1,0,'C');
  
  $pdf->SetFont('Arial','B',8);

  $pdf->Cell(40,4,'Metros',1,0,'C');
  $pdf->Cell(55,4,'Kilos',1,0,'C');
  $pdf->Cell(25,8,utf8_decode('Fecha impresión'),1,1,'C');

  $pdf->Ln(-4);

  $pdf->SetFont('Arial','',8);
  $pdf->Cell(75,4,'',0,0,'C');  
  $pdf->Cell(20,4,'Recibidos',1,0,'C');
  $pdf->Cell(20,4,'Entregados',1,0,'C');
  $pdf->Cell(20,4,'Recibidos',1,0,'C');
  $pdf->Cell(35,4,'Entregados / Merma',1,1,'C');

  $pdf->SetFont('Arial','B',8);  

  for ($idLote = 1; $idLote <= $numLotes; $idLote++)
  { $pdf->SetFont('Arial','B',12);
    $pdf->Cell(10,8,$idLote,0,0,'C');

    $pdf->SetFont('Arial','',8);
  	$pdf->Cell(45,4,$oplist_lote[$idLote],1,0,'L');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(20,8,$oplist_noop[$idLote],1,0,'C');
    $pdf->Cell(20,8,$oplist_longitud[$idLote],1,0,'R');
    $pdf->Cell(20,8,'',1,0,'L');
    $pdf->Cell(20,8,number_format($oplist_peso[$idLote],2),1,0,'R');
    $pdf->Cell(35,8,'/',1,0,'C');
    $pdf->Cell(25,8,'',1,1,'L');
    
    $pdf->Ln(-4);

    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(10,4,'',0,0,'L');
    $pdf->Cell(45,4,$oplist_tarima[$idLote].' No. '.$oplist_idlote[$idLote],1,1,'L'); }

	$pdf->AddPage();

  $pdf->Ln(6);

  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(60,6,utf8_decode('Parametros de operación'),0,1,'C');

  $pdf->SetFont('Arial','I',8);
  $pdf->SetFillColor(153,153,153);

  $pdf->Cell(40,4,utf8_decode('Viscosidad'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,1,'R',true);
  $pdf->Cell(40,4,utf8_decode('Temperatura'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,1,'R',true);
  $pdf->Cell(40,4,utf8_decode('Velocidad'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,1,'R',true);
  $pdf->Cell(40,4,utf8_decode('Presión en gomas'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,1,'R',true);
  $pdf->Cell(40,4,utf8_decode('Presión en cilindros'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,1,'R',true);
  $pdf->Cell(40,4,utf8_decode('Presión en rasquetas'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,1,'R',true);

  $pdf->Ln(-24);
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(40.5,4,'',0,0,'R');
  $pdf->Cell(40,4,$oplist_jviscosidad.' tolerancia +/- '.$oplist_jtviscosidad.' seg',0,1,'L');
  $pdf->Cell(40.5,4,'',0,0,'R');
  $pdf->Cell(60,4,$oplist_jtemperatura.' tolerancia +/- '.$oplist_jttemperatura.utf8_decode(' °C'),0,1,'L');
  $pdf->Cell(40.5,4,'',0,0,'R');
  $pdf->Cell(60,4,$oplist_jvelocidad.' tolerancia +/- '.$oplist_jtvelocidad.' mts/min',0,1,'L');
  $pdf->Cell(40.5,4,'',0,0,'R');
  $pdf->Cell(60,4,$oplist_jpsigoma.' tolerancia +/- '.$oplist_jtpsigoma.' psi',0,1,'L');
  $pdf->Cell(40.5,4,'',0,0,'R');
  $pdf->Cell(60,4,$oplist_jpsicilindro.' tolerancia +/- '.$oplist_jtpsicilindro.' psi',0,1,'L');
  $pdf->Cell(40.5,4,'',0,0,'R');
  $pdf->Cell(60,4,$oplist_jpsirasqueta.' tolerancia +/- '.$oplist_jtpsirasqueta.' psi',0,1,'L');

  $pdf->Ln(6);

  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(60,6,'Necesidades de tinta',0,0,'C');

  $pdf->SetFont('Arial','',8);
  $pdf->Cell(25,6,'Requerido',1,0,'C');
  $pdf->Cell(25,6,'Recibido',1,0,'C'); 
  $pdf->Cell(25,6,'No. Lote',1,0,'C');
  $pdf->Cell(25,6,'Disolvente',1,1,'C');

  for ($idTinta = 1; $idTinta <= $numTintas; $idTinta++)
  { $pdf->SetFont('Arial','B',12);
    $pdf->SetFillColor($oplist_R[$idTinta], $oplist_G[$idTinta], $oplist_B[$idTinta]);
    
    $pdf->Cell(9,7,$oplist_notinta[$idTinta],0,0,'C',true);
    $pdf->Cell(1,7,'',0,0,'C');

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(50,8,$oplist_pantone[$idTinta],1,0,'L'); 

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(25,8,$oplist_consumo[$idTinta].' kgs',1,0,'R'); 
    $pdf->Cell(25,8,'',1,0,'R');
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