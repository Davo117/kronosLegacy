<?php
  include '../../datos/mysql.php';	
  require('fpdf/fpdf.php');

	$link_mysqli = conectar();
	$prodLoteSelect = $link_mysqli->query("
      SELECT prodlote.noop,
        prodlote.longitud,
        prodlote.amplitud,
        prodlote.peso,
        pdtoimpresion.cdgimpresion,
        pdtoimpresion.idimpresion,
        pdtoimpresion.impresion,
        pdtodiseno.diseno,
        (pdtodiseno.ancho*2)+pdtodiseno.empalme AS ancho,
        pdtodiseno.registro,
        pdtodiseno.alpaso,
        pdtodiseno.notintas,
        prodlote.cdglote
      FROM prodlote, 
        pdtodiseno, 
        pdtoimpresion
      WHERE (prodlote.noop = '".$_POST['txt_lote']."' OR prodlote.cdglote = '".$_POST['txt_lote']."') AND 
        prodlote.cdgproducto = pdtoimpresion.cdgimpresion AND
        pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno");

	if ($prodLoteSelect->num_rows > 0)
	{ $regProdLote = $prodLoteSelect->fetch_object();

	  $_SESSION['oplist_noop'] = $regProdLote->noop;
    $_SESSION['oplist_diseno'] = $regProdLote->diseno;
	  $_SESSION['oplist_impresion'] = $regProdLote->impresion; 
    $_SESSION['oplist_cdglote'] = $regProdLote->cdglote; 
    $oplist_cdgimpresion = $regProdLote->cdgimpresion;
    $oplist_notintas = $regProdLote->notintas;
    $oplist_anchoplano = $regProdLote->ancho;
    $oplist_alpaso = $regProdLote->alpaso; } 

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
    WHERE pdtoimpresion.cdgimpresion = '".$oplist_cdgimpresion."' AND
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

			$this->Cell(45,4,utf8_decode('Elaboró'),0,0,'R');
			$this->Cell(0.5,4,'',0,1,'R',true);
			$this->Cell(45,4,utf8_decode('Revisó y Aprobó'),0,0,'R');
			$this->Cell(0.5,4,'',0,1,'R',true);

			$this->Ln(-8);

			$this->SetFont('Arial','I',8);
			$this->Cell(45.5,4,'',0,0,'R');
			$this->Cell(75,4,utf8_decode('Representante de la Dirección'),0,1,'L');
			$this->Cell(45.5,4,'',0,0,'R');
			$this->Cell(75,4,utf8_decode('Dirección'),0,1,'L');

			$this->Ln(-8);

			$this->SetFont('Arial','B',8);
			$this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
			$this->Cell(0.5,4,'',0,1,'R',true);
			$this->Cell(125,4,utf8_decode('Código'),0,0,'R');
			$this->Cell(0.5,4,'',0,1,'R',true);
			$this->Cell(125,4,utf8_decode('Versión'),0,0,'R');
			$this->Cell(0.5,4,'',0,1,'R',true);
			$this->Cell(125,4,utf8_decode('Fecha de revisión'),0,0,'R');
			$this->Cell(0.5,4,'',0,1,'R',true);
			$this->Cell(125,4,utf8_decode('Responsable'),0,0,'R');
		  $this->Cell(0.5,4,'',0,1,'R',true);

			$this->Ln(-20);

			$this->SetFont('Arial','I',8);
			$this->SetFillColor(255,153,0);

			$this->Cell(125.5,4,'',0,0,'R');
			$this->Cell(75,4,utf8_decode('Inspección en Impresión y Anclaje de tinta'),0,1,'L');
			$this->Cell(125.5,4,'',0,0,'R');
			$this->Cell(75,4,utf8_decode('RC-02-POT-7.5.1'),0,1,'L');
			$this->Cell(125.5,4,'',0,0,'R');
			$this->Cell(75,4,utf8_decode('1.0'),0,1,'L');
			$this->Cell(125.5,4,'',0,0,'R');
			$this->Cell(75,4,utf8_decode('24 Febrero 2014'),0,1,'L');
			$this->Cell(125.5,4,'',0,0,'R');
			$this->Cell(75,4,utf8_decode('Inspector de Calidad y Refilador'),0,1,'L'); 

      $this->Ln(4);

      $this->SetFont('3of9','',28);      
      $this->Cell(195,5,'*'.$_SESSION['oplist_cdglote'].'*',0,1,'R');
      $this->SetFont('Arial','B',12);
      $this->Cell(195,6,$_SESSION['oplist_cdglote'],0,1,'R');  

      $this->Ln(-9);

      $this->SetFont('Arial','I',8);
      $this->SetFillColor(255,153,0);

      $this->Cell(40,4,utf8_decode('NoOP'),0,0,'R');
      $this->Cell(0.5,4,'',0,1,'R',true);
      $this->Cell(40,4,utf8_decode('Fecha de inspección'),0,0,'R');
      $this->Cell(0.5,4,'',0,1,'R',true);
      $this->Cell(40,4,utf8_decode('Diseño'),0,0,'R');
      $this->Cell(0.5,4,'',0,1,'R',true);
      $this->Cell(40,4,utf8_decode('Impresión'),0,0,'R');
      $this->Cell(0.5,4,'',0,1,'R',true);

      $this->Ln(-16);
      $this->SetFont('Arial','B',8);
      $this->Cell(40.5,4,'',0,0,'R');
      $this->Cell(40,4,$_SESSION['oplist_noop'],0,1,'L');
      $this->Cell(40.5,4,'',0,0,'R');
      $this->Cell(40,4,'_______________________',0,1,'L');
      $this->Cell(40.5,4,'',0,0,'R');
      $this->Cell(60,4,$_SESSION['oplist_diseno'],0,1,'L');
      $this->Cell(40.5,4,'',0,0,'R');
      $this->Cell(60,4,$_SESSION['oplist_impresion'],0,1,'L');
    }

		// Pie de página
		function Footer()
		{ $this->SetY(-20);

	    $this->SetFont('Arial','B',8);
	    $this->Cell(195.9,4,'______________________________________________________',0,1,'C');
	    $this->Cell(195.9,4,utf8_decode('Nombre y firma de quien realizo la inspección'),0,1,'C');

		  $this->SetY(-10);
		  $this->SetFont('arial','B',8);
		  $this->Cell(0,6,'Consultado el '.date("Y-m-d").' a las '.date("G:i:s").'',0,1,'R'); 
		  $this->SetFont('arial','',8);
		  $this->SetY(-7.5);
		  $this->Cell(0,6,utf8_decode('Protón para Grupo Labro / Página '.$this->PageNo().' de {nb}'),0,1,'R'); 	    
		}
	}

	$pdf = new PDF('P','mm','letter');
	$pdf->SetDisplayMode(real, continuous);
  $pdf->AddFont('3of9','','free3of9.php');
  $pdf->AliasNbPages();

	$pdf->AddPage();
  $pdf->SetFillColor(255,153,0);

  $pdf->Ln(6);

  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(3,4,'',0,0,'C',true);
  $pdf->Cell(82,6,utf8_decode('Inspección de tintas'),0,0,'L');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(110,6,'Comentarios',0,1,'L');

  for ($idTinta = 1; $idTinta <= $numTintas; $idTinta++)
  { $pdf->SetFont('Arial','B',8);
    $pdf->SetFillColor($oplist_R[$idTinta], $oplist_G[$idTinta], $oplist_B[$idTinta]);
      
    $pdf->Cell(10,8,$oplist_notinta[$idTinta],0,0,'C',true); 
      
    $pdf->Cell(40,8,$oplist_pantone[$idTinta],0,0,'L'); 
    
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(35,8,'Test de color [  si  ] [  no  ]',0,0,'L'); 
    $pdf->Cell(110,8,'______________________________________________________________',0,1,'L'); 

    $pdf->Cell(85,4,'',0,0,'L'); 
    $pdf->Cell(110,4,'______________________________________________________________',0,1,'L'); 

    $pdf->Ln(4); }

  $pdf->AddPage();
  $pdf->SetFillColor(255,153,0);

  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(40,4,utf8_decode('Máquina de refilado'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,1,'R',true);
  $pdf->Cell(40,4,utf8_decode('Ancho plano'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,1,'R',true);

  $pdf->Ln(-8);
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(40.5,4,'',0,0,'R');
  $pdf->Cell(40,4,'_______________________',0,1,'L');
  $pdf->Cell(40.5,4,'',0,0,'R');
  $pdf->Cell(60,4,$oplist_anchoplano.' '.utf8_decode('mm'),0,1,'L');

  $pdf->Ln(6);

  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(3,4,'',0,0,'C',true);
  $pdf->Cell(82,6,utf8_decode('Inspección de Anclaje de tinta'),0,1,'L');

  $anchoBobina = 195/$oplist_alpaso;

  for ($idbobina = 1; $idbobina <= $oplist_alpaso; $idbobina++)
  { $pdf->Cell($anchoBobina,6,'Bobina: '.$_SESSION['oplist_noop'].'-'.$idbobina,0,0,'L'); }
     
  $pdf->Ln();

  $pdf->SetFont('Arial','B',8);

  for ($idinspeccion = 1; $idinspeccion <= 6; $idinspeccion++)
  { $pdf->Cell(3,2,'',0,0,'L',true);
    $pdf->Cell(10,5,utf8_decode('Inspección a _________ mtrs'),0,1,'L');  

    for ($idbobina = 1; $idbobina <= $oplist_alpaso; $idbobina++)
    { $pdf->Cell($anchoBobina,4,'Ancho plano ___________ mm',0,0,'L'); }
     
    $pdf->Ln();

    for ($idbobina = 1; $idbobina <= $oplist_alpaso; $idbobina++)
    { $pdf->Cell($anchoBobina,4,'Test de anclaje [  si  ] [  no  ]',0,0,'L'); }
     
    $pdf->Ln();

    $pdf->Ln(10);
  }
    
  $pdf->SetY(-64);

  $pdf->SetFont('Arial','B',8);

  $pdf->Cell(3,2,'',0,0,'L',true);   
  $pdf->Cell(10,4,utf8_decode('Recolección de datos para análisis'),0,1,'L'); 

  $pdf->SetFont('Arial','',8);

  $pdf->Cell(35,4,'No. Bandera/Metros',0,0,'L');
  $pdf->Cell(140,4,'Causa',0,0,'L');
  $pdf->Cell(20,4,'Kilos',0,1,'L');

  for ($idbandera = 1; $idbandera <= 5; $idbandera++)
  { $pdf->Cell(35,4,'___________________',0,0,'L');
    $pdf->Cell(140,4,'______________________________________________________________________________________',0,0,'L');
    $pdf->Cell(20,4,'____________',0,1,'L'); }

  $_SESSION['oplist_noop'] = '';
  $_SESSION['oplist_diseno'] = '';
  $_SESSION['oplist_impresion'] = '';  
  $_SESSION['oplist_cdglote'] = '';

  ////////////////////////////////////////////////
	$pdf->Output('RC-02-POT-7.5.1.pdf', 'D');
?>