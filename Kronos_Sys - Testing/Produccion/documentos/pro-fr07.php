<?php
  include '../../Database/db.php'; 
  include '../../fpdf/fpdf.php';


$pdtoImpresionSelect = $MySQLiconn->query("
    SELECT (select descripcion from producto where ID=i.descripcionDisenio) as descripcionDisenio, i.tintas, i.descripcionImpresion,(SELECT descripcionSustrato from sustrato where idSustrato=i.sustrato) as sustrato
      FROM impresiones i WHERE i.codigoImpresion = '".$_GET['cdgimpresion']."'");

  if ($pdtoImpresionSelect->num_rows > 0){
    $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

    $_SESSION['oplist_diseno'] = $regPdtoImpresion->descripcionDisenio;
    $_SESSION['oplist_impresion'] = $regPdtoImpresion->descripcionImpresion;
    $oplist_sustrato = $regPdtoImpresion->sustrato; 
    $oplist_notintas = $regPdtoImpresion->tintas; 
	} 

  $prodMaquinaSelect = $MySQLiconn->query("
  	SELECT descripcionMaq,codigo FROM maquinas 
  	 WHERE codigo = '".$_GET['cdgmaquina']."'");
  
  if ($prodMaquinaSelect->num_rows > 0)
  { $regProdMaquina = $prodMaquinaSelect->fetch_object();

    $_SESSION['oplist_idmaquina'] = $regProdMaquina->codigo;
    $_SESSION['oplist_maquina'] = $regProdMaquina->descripcionMaq; }

  $pdtoJuegoSelect = $MySQLiconn->query("
  	SELECT * FROM juegoscilindros 
  	 WHERE identificadorCilindro = '".$_GET['cdgjuego']."'");
  
  if ($pdtoJuegoSelect->num_rows > 0)
  { $regPdtoJuego = $pdtoJuegoSelect->fetch_object();

    $oplist_jviscosidad = $regPdtoJuego->viscosidad;
    $oplist_jtemperatura = $regPdtoJuego->temperatura;
    $oplist_jvelocidad = $regPdtoJuego->velocidad;
    $oplist_jpsigoma = $regPdtoJuego->presionGoma;
    $oplist_jpsicilindro = $regPdtoJuego->presionCilindro;
    $oplist_jpsirasqueta = $regPdtoJuego->presionRasqueta;

    $oplist_jtviscosidad = $regPdtoJuego->tolViscosidad;
    $oplist_jttemperatura = $regPdtoJuego->tolTemperatura;
    $oplist_jtvelocidad = $regPdtoJuego->tolVelocidad;
    $oplist_jtpsigoma = $regPdtoJuego->tolGoma;
    $oplist_jtpsicilindro = $regPdtoJuego->tolCilindro;
    $oplist_jtpsirasqueta = $regPdtoJuego->tolRasqueta;

    $_SESSION['oplist_idjuego'] = $regPdtoJuego->identificadorCilindro;
    $_SESSION['oplist_cdgjuego'] = $regPdtoJuego->proveedor; }
    else
    {
      $pdtoJuegoSelect = $MySQLiconn->query("
    SELECT * FROM juegoscireles 
     WHERE identificadorJuego= '".$_GET['cdgjuego']."'");
      $regPdtoJuego = $pdtoJuegoSelect->fetch_object();
      $_SESSION['oplist_idjuego'] = $regPdtoJuego->identificadorJuego;
      $_SESSION['oplist_cdgjuego'] = $regPdtoJuego->num_dientes." D.";
    }

  $prodLoteSelect = $MySQLiconn->query("
    SELECT lotes.tarima,
		       lotes.numeroLote,
		       lotes.referenciaLote,
		       lotes.longitud,
		       lotes.peso,
		       lotes.noop
		  FROM produccion, lotes
		 WHERE lotes.juegoLote = produccion.juegoLotes AND
		       produccion.nombreProducto = '".$_SESSION['oplist_impresion']."' AND
		      (produccion.maquina = '".$_SESSION['oplist_maquina']."' AND 
			     produccion.fechaProduccion = '".$_GET['fchprograma']."')
  ORDER BY lotes.noLote");

  if ($prodLoteSelect->num_rows > 0)
  { $idLote = 1;
  	while ($regProdLote = $prodLoteSelect->fetch_object())
  	{ $oplist_tarima[$idLote] = $regProdLote->tarima;
      $oplist_idlote[$idLote] = $regProdLote->numeroLote;
      $oplist_lote[$idLote] = $regProdLote->referenciaLote;
      $oplist_longitud[$idLote] = $regProdLote->longitud;
      $oplist_peso[$idLote] = $regProdLote->peso;
      $oplist_noop[$idLote] = $regProdLote->noop;

      $idLote++; }

    $numLotes=$prodLoteSelect->num_rows;
  }


	class PDF extends FPDF
	{ // Cabecera de página
		function Header()
		{ global $oplist_sustrato;

      global $oplist_jviscosidad, $oplist_jtemperatura;
      global $oplist_jvelocidad, $oplist_jpsigoma;
      global $oplist_jpsicilindro, $oplist_jpsirasqueta;
      global $oplist_jtviscosidad, $oplist_jttemperatura;
      global $oplist_jtvelocidad, $oplist_jtpsigoma;
      global $oplist_jtpsicilindro, $oplist_jtpsirasqueta;

      $this->SetFont('Arial','B',8);
		
      $this->SetFillColor(224,7,8);

          if (file_exists('../../pictures/lolo.jpg')==true)    { 
      $this->Image('../../pictures/lolo.jpg',10,10,0,20); }

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
      $this->Cell(75,4,utf8_decode('2.0'),0,1,'L');

      $this->SetFont('Arial','B',8);
			$this->Cell(185,4,utf8_decode('Revisión'),0,0,'R');
			$this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('03 de septiembre del 2019'),0,1,'L');

      $this->SetFont('Arial','B',8);
			$this->Cell(185,4,utf8_decode('Responsable'),0,0,'R');
		  $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Impresor'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(185,4,utf8_decode('Disponibilidad'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Físico/A PRUEBA'),0,1,'L'); 

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
      $this->Cell(40,4,utf8_decode('Juego de cilindros/Cirel'),0,0,'R');
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
      $this->Cell(60,4,utf8_decode($_SESSION['oplist_idmaquina'].' '.$_SESSION['oplist_maquina']),0,1,'L');

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
		{ $this->SetY(-10);

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
	//$pdf->SetDisplayMode(real, continuous);
  $pdf->AddFont('3of9','','free3of9.php');
  $pdf->AliasNbPages();

	$pdf->AddPage();

  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(23,15,'Cabina',1,0,'C');
  $pdf->Cell(($numLotes*12),5,'noop',1,0,'C');
  $pdf->Ln();

  $pdf->SetX(33);

  for ($idLote = 1; $idLote <= $numLotes; $idLote++)
  { $pdf->Cell(12,5,$oplist_noop[$idLote],1,0,'C'); }
  
  $pdf->Ln();

  $pdf->SetFont('Arial','',8);
  $pdf->SetX(21);
  $pdf->Cell(12,5,'Hora',1,0,'C');
  for ($idLote = 1; $idLote <= $numLotes; $idLote++)
  { $pdf->Cell(12,5,':',1,0,'C'); }
  
  $pdf->Ln();

  for ($id = 1; $id <= 10; $id++)
  { $pdf->SetFont('Arial','B',11);
    $pdf->Cell(6,10,$id,1,0,'C');
    
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(17,5,'Viscosidad/pH',1,0,'R');

    for ($idLote = 1; $idLote <= $numLotes; $idLote++)
    { $pdf->Cell(12,5,'',1,0,'C'); }
    $pdf->Ln();

    $pdf->SetX(16);
    $pdf->Cell(17,5,'Temperatura',1,0,'R');

    for ($idLote = 1; $idLote <= $numLotes; $idLote++)
    { $pdf->Cell(12,5,'',1,0,'C'); }
    $pdf->Ln();
  }

  $pdf->Cell(23,5,utf8_decode('Tensión inicial'),1,0,'R');  
    for ($idLote = 1; $idLote <= $numLotes; $idLote++)
    { $pdf->Cell(12,5,'',1,0,'C'); }
    $pdf->Ln();

  $pdf->Cell(23,5,utf8_decode('Tensión final'),1,0,'R');  
    for ($idLote = 1; $idLote <= $numLotes; $idLote++)
    { $pdf->Cell(12,5,'',1,0,'C'); }
    $pdf->Ln();

  $pdf->Cell(23,5,utf8_decode('Velocidad'),1,0,'R');  
    for ($idLote = 1; $idLote <= $numLotes; $idLote++)
    { $pdf->Cell(12,5,'',1,0,'C'); }
    $pdf->Ln();

    $pdf->Cell(23,5,utf8_decode('Tensión del holograma'),1,0,'R');  
    for ($idLote = 1; $idLote <= $numLotes; $idLote++)
    { $pdf->Cell(12,5,'',1,0,'C'); }
    $pdf->Ln();


  ////////////////////////////////////////////////
	$pdf->Output('PRO-FR07.pdf', 'I');
?>