<?php
include '../../Database/db.php'; 
include '../../fpdf/fpdf.php';
  

  /*Son necesarios los siguientes GET (por ahora)
  $_GET['cdgimpresion']
  $_GET['cdgmaquina']
  $_GET['fchprograma']
  Checar desde la linea 55 a 70
*/  
  $pdtoImpresionSelect = $MySQLiconn->query("
    SELECT descripcionDisenio, tintas, descripcionImpresion, sustrato
      FROM impresiones WHERE codigoImpresion = '".$_GET['cdgimpresion']."'");

  if ($pdtoImpresionSelect->num_rows > 0)
  { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

    $_SESSION['oplist_diseno'] = $regPdtoImpresion->descripcionDisenio;
    $_SESSION['oplist_impresion'] = $regPdtoImpresion->descripcionImpresion;
    $_SESSION['oplist_sustrato'] = $regPdtoImpresion->sustrato; 

    $oplist_notintas = $regPdtoImpresion->tintas; } 

  // Buscar máquina de impresión
  $prodMaquinaSelect = $MySQLiconn->query("SELECT * FROM maquinas WHERE codigo='".$_GET['cdgmaquina']."'");
  
  if ($prodMaquinaSelect->num_rows > 0)
  { $regProdMaquina = $prodMaquinaSelect->fetch_object();

    $_SESSION['oplist_idmaquina'] = $regProdMaquina->codigo;
    $_SESSION['oplist_maquina'] = $regProdMaquina->descripcionMaq; }

  // Filtro de rgistros (Pantones)
  $pdtoPantoneSelect = $MySQLiconn->query("
    SELECT descripcionPantone
      FROM pantonepcapa
     WHERE codigoImpresion='".$_GET['cdgimpresion']."'
  ORDER BY codigoCapa");

  if ($pdtoPantoneSelect->num_rows > 0)
  { $item = 0;
    
    while ($regPdtoPantone = $pdtoPantoneSelect->fetch_object())
    { $item++;

      $pdtoPantone_pantone[$item] = $regPdtoPantone->descripcionPantone; }

    $nPantones = $item; }
  // Final del filtro de registros  




//REVISAR CONSULTA CON ERIK 
    //no se que tabla usar para saber que ya estan en programacion deberia decirme erik en esta parte que pedo, mientras tanto pondre 2 consultas que creo son las necesarias...

  // Buscar los lotes
  $prodLoteSelect=$MySQLiconn->query("SELECT noLote,noop,tipo FROM lotes where juegoLote='".$_GET['juegoLotes']."'");

  if ($prodLoteSelect->num_rows > 0)
  { $idLote = 1;
    while ($regProdLote = $prodLoteSelect->fetch_object())
    {

      $oplist_noop[$idLote] = $regProdLote->noop;
      $oplist_tipo[$idLote] = $regProdLote->tipo;
      $idLote++; 
    }

    $nLotes = $prodLoteSelect->num_rows; 
  }

  // Reformar las clases
  class PDF extends FPDF
  { // Cabecera de página
    function Header()
    { global $oplist_ancho;

      $this->SetFont('Arial','B',8);
  
      if (file_exists('../../pictures/lolo.jpg')==true)    { 
      $this->Image('../../pictures/lolo.jpg',10,10,0,20); 
    }   


      $this->SetFillColor(224,7,8);
      $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Inspección de Calidad de impresión'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('CC-FR01'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Versión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('1.0'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Revisión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('03 de Julio de 2015'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Responsable'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Control de Calidad'),0,1,'L'); 

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
      $this->Cell(40,4,utf8_decode('Impresión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','B',8);
      $this->Cell(60,4,$_SESSION['oplist_impresion'],0,1,'L');

      $this->SetFont('Arial','I',8);
      $this->Cell(40,4,utf8_decode('Máquina de impresión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);  
      $this->SetFont('Arial','B',8);
      $this->Cell(60,4,$_SESSION['oplist_maquina'],0,1,'L');

      $this->SetFont('Arial','I',8);
      $this->Cell(40,4,utf8_decode('Sustrato'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','B',8);
      $this->Cell(60,4,$_SESSION['oplist_sustrato'],0,1,'L');

      $this->Ln(4); }

    // Pie de página
    function Footer()
    { $this->SetY(-10);
      $this->SetFont('arial','',8);
      $this->Cell(0,3,utf8_decode('Página '.$this->PageNo().' de {nb}'),0,1,'R');
      $this->SetFont('arial','B',8);
      $this->Cell(0,3,'Consultado el '.date("Y-m-d").' a las '.date("G:i:s"),0,1,'R'); }
  }

  // Generar el archivo
  $pdf = new PDF('P','mm','letter');
  //$pdf->SetDisplayMode(real, continuous);
  $pdf->AddFont('3of9','','free3of9.php');
  $pdf->AliasNbPages();

  $pdf->AddPage();
  $pdf->SetFillColor(255,153,0);
  
  for ($item = 1; $item <= $nLotes; $item++)
  { $pdf->Cell(5,4,$item,0,0,'C');
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(10,4,'noop',0,0,'R');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(20,4,$oplist_noop[$item]." [".$oplist_tipo[$item]."]",0,1,'L');
    $pdf->SetX(45);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(20,4,'Supervisor',0,0,'R');
    $pdf->Cell(50,4,'________________________________',0,0,'L');
    $pdf->Cell(36,4,'Inspector de calidad',0,0,'R');
    $pdf->Cell(50,4,'________________________________',0,1,'L');

    $pdf->Ln(2);

    $pdf->SetFillColor(237,125,49);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(15,4,'Tintas',0,0,'R',true);
    $pdf->Cell(50,4,'Pantone',1,0,'C',true);
    $pdf->Cell(20,4,utf8_decode('Medición'),1,0,'C',true);
    $pdf->Cell(20,4,'Anclaje',1,0,'C',true);
    $pdf->Cell(90,4,'Nota',1,1,'C',true);

    $pdf->SetFont('Arial','I',8);
    for ($subItem = 1; $subItem <= $oplist_notintas; $subItem++)
    { $pdf->Cell(15,4,'',0,0,'C');
      $pdf->Cell(50,4,$pdtoPantone_pantone[$subItem],1,0,'L');
      $pdf->Cell(20,4,'',1,0,'C');
      $pdf->Cell(20,4,'',1,0,'C');
      $pdf->Cell(90,4,'',1,1,'C'); }

    $pdf->Ln(2);

    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(5,4,'',0,0,'C');
    //modificado xD
    $pdf->Cell(106,4,utf8_decode('Inspección visual general de impresión. Aprueba: Sí( ) No( )'),0,0,'L',true);   
    $pdf->Cell(22,4,'Fecha y hora',0,0,'R');
    $pdf->Cell(38,4,'_____________________',0,0,'L');
    $pdf->Cell(8,4,'Merma',0,0,'R');
    $pdf->Cell(20,4,'___________ (kg)',0,1,'L');    

    $pdf->Ln(6); }

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(24,4,'Observaciones',0,1,'L');
  $pdf->Cell(0,12,'',1,1,'C');

  ////////////////////////////////////////////////
  $pdf->Output('IC-FR01.pdf', 'I');
?>