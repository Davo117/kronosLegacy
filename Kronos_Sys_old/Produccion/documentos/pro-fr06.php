<?php
include '../../Database/db.php'; 
  include '../../fpdf/fpdf.php';
  /*
  $_GET['cdgmaquina']
  $_GET['cdgjuego']
  $_GET['cdgimpresion']
$_GET['millares']
$_GET['fchprograma']
$_GET['cdgfchprograma']
  */
$pdtoImpresionSelect = $MySQLiconn->query("SELECT descripcionDisenio, tintas, descripcionImpresion, sustrato FROM impresiones WHERE codigoImpresion = '".$_GET['cdgimpresion']."'");

  if ($pdtoImpresionSelect->num_rows > 0){
    $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

    $_SESSION['oplist_diseno'] = $regPdtoImpresion->descripcionDisenio;
    $_SESSION['oplist_impresion'] = $regPdtoImpresion->descripcionImpresion;
    $oplist_sustrato = $regPdtoImpresion->sustrato; 
    $oplist_notintas = $regPdtoImpresion->tintas; 
  } 

  $prodMaquinaSelect = $MySQLiconn->query("SELECT descripcionMaq,codigo FROM maquinas WHERE codigo = '".$_GET['cdgmaquina']."'");
  
  if ($prodMaquinaSelect->num_rows > 0){ 
    $regProdMaquina = $prodMaquinaSelect->fetch_object();
    $_SESSION['oplist_idmaquina'] = $regProdMaquina->codigo;
    $_SESSION['oplist_maquina'] = $regProdMaquina->descripcionMaq; 
  }

  $pdtoJuegoSelect = $MySQLiconn->query("SELECT * FROM juegoscilindros WHERE identificadorCilindro = '".$_GET['cdgjuego']."'");
  
  if ($pdtoJuegoSelect->num_rows > 0){ 
    $regPdtoJuego = $pdtoJuegoSelect->fetch_object();

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
    $_SESSION['oplist_cdgjuego'] = $regPdtoJuego->proveedor; 
  }


  $pdtoImpresionTntSelect = $MySQLiconn->query("SELECT pantonepcapa.descripcionPantone, impresiones.tintas,
           pantonepcapa.consumoPantone, pantonepcapa.disolvente FROM pantonepcapa, impresiones
     WHERE impresiones.codigoImpresion='".$_GET['cdgimpresion']."' AND impresiones.codigoImpresion=pantonepcapa.codigoImpresion");

  if ($pdtoImpresionTntSelect->num_rows > 0){ 
    $item = 0;
    while ($regImpresionTnt = $pdtoImpresionTntSelect->fetch_object()){ 
      $item++;
      $oplist_pantone[$item] = $regImpresionTnt->descripcionPantone;
      $oplist_notinta[$item] = $regImpresionTnt->tintas;      
      $oplist_consumo[$item] = number_format(($regImpresionTnt->consumoPantone*$_GET['millares']),3);
      $oplist_disolvente[$item] = $regImpresionTnt->disolvente; 
    }
    $nTintas = $pdtoImpresionTntSelect->num_rows; 
  }

	class PDF extends FPDF{ // Cabecera de página
		function Header(){ 
      global $oplist_sustrato;

      $this->SetFont('Arial','B',8);
      $this->SetFillColor(224,7,8);

      if (file_exists('../../pictures/lolo.jpg')==true)    { 
      $this->Image('../../pictures/lolo.jpg',10,10,0,20); }

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

/*    Codigos de barra
      $this->Ln(4);

      $this->SetFont('3of9','',28);      
      $this->Cell(195,5,'*'.$_GET['cdgfchprograma'].$_GET['cdgimpresion'].$_GET['cdgjuego'].$_SESSION['oplist_idmaquina'].'*',0,1,'R');
      $this->SetFont('Arial','B',10);
      $this->Cell(195,7,$_GET['cdgfchprograma'].$_GET['cdgimpresion'].$_GET['cdgjuego'].$_SESSION['oplist_idmaquina'],0,1,'R');  

      $this->SetFont('3of9','',24);      
      $this->Cell(195,5,'*'.$_SESSION['oplist_cdgjuego'].'*',0,1,'R');
      $this->SetFont('Arial','B',9);    
      $this->Cell(195,6,$_SESSION['oplist_cdgjuego'],0,1,'R');
*/
      $this->Ln(29);
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
	//$pdf->SetDisplayMode(real, continuous);
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
    
    $pdf->Cell(9,7,$idTinta,0,0,'C');
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

  //////////////////////////////////////////////// para que serviran esos session xD para dejarlos vacios
	$pdf->Output('PRO-FR06.pdf', 'I');
?>