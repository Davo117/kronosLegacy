<?php
  include '../../Database/db.php'; 
  include '../../fpdf/fpdf.php';

  



$pdtoImpresionSelect = $MySQLiconn->query("SELECT descripcionDisenio, tintas, descripcionImpresion, sustrato FROM impresiones WHERE codigoImpresion = '".$_GET['cdgimpresion']."'");

  if ($pdtoImpresionSelect->num_rows > 0){
    $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

    $_SESSION['oplist_diseno'] = $regPdtoImpresion->descripcionDisenio;
    $_SESSION['oplist_impresion'] = $regPdtoImpresion->descripcionImpresion;
    $oplist_sustrato = $regPdtoImpresion->sustrato; 
    $oplist_notintas = $regPdtoImpresion->tintas; 
  } 




$prodMaquinaSelect = $MySQLiconn->query("SELECT descripcionMaq, codigo FROM maquinas WHERE codigo = '".$_GET['cdgmaquina']."'");
  
  if ($prodMaquinaSelect->num_rows > 0){ 
    $regProdMaquina = $prodMaquinaSelect->fetch_object();
    $_SESSION['oplist_idmaquina'] = $regProdMaquina->codigo;
    $_SESSION['oplist_maquina'] = $regProdMaquina->descripcionMaq; 
  }

if(!empty($_GET['cdgjuego']))
{
  $pdtoJuegoSelect = $MySQLiconn->query("SELECT * FROM juegoscilindros WHERE identificadorCilindro = '".$_GET['cdgjuego']."'");

  if(mysqli_num_rows($pdtoJuegoSelect)==0)
  {
    $pdtoJuegoSelect = $MySQLiconn->query("SELECT * FROM juegoscireles WHERE identificadorJuego = '".$_GET['cdgjuego']."'");
     if ($pdtoJuegoSelect->num_rows > 0){ 
    $regPdtoJuego = $pdtoJuegoSelect->fetch_object();
     $_SESSION['oplist_idjuego'] = $regPdtoJuego->identificadorJuego;
    $_SESSION['oplist_cdgjuego'] = $regPdtoJuego->num_dientes; 
  }
  }
  else
  {
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
  }
}
else
{
    $_SESSION['oplist_idjuego'] = "No aplica";
    $_SESSION['oplist_cdgjuego'] = ""; 
}
  
  




$prodLoteSelect = $MySQLiconn->query("SELECT lotes.tarima, lotes.numerolote, lotes.referenciaLote, lotes.longitud, lotes.peso, lotes.noop FROM produccion, lotes
     WHERE lotes.juegoLote = produccion.juegoLotes AND
           produccion.nombreProducto = '".$_SESSION['oplist_impresion']."' AND
          (produccion.maquina = '".$_SESSION['oplist_maquina']."' AND 
           produccion.juegoCilindros = '".$_GET['cdgjuego']."' AND 
           produccion.fechaProduccion = '".$_GET['fchprograma']."' AND
           produccion.juegoLotes= '".$_GET['juegolotes']."')
  ORDER BY lotes.noop");
if(mysqli_num_rows($prodLoteSelect)==0)
{
  $prodLoteSelect = $MySQLiconn->query("SELECT lotes.tarima, lotes.numerolote, lotes.referenciaLote, lotes.longitud, lotes.peso, lotes.noop FROM produccion, lotes
     WHERE lotes.juegoLote = produccion.juegoLotes AND
           produccion.nombreProducto = '".$_SESSION['oplist_impresion']."' AND
          (produccion.maquina = '".$_SESSION['oplist_maquina']."' AND 
           produccion.juegoCireles = '".$_GET['cdgjuego']."' AND 
           produccion.fechaProduccion = '".$_GET['fchprograma']."'
         produccion.juegoLotes= '".$_GET['juegolotes']."')
  ORDER BY lotes.noop");
}

  if ($prodLoteSelect->num_rows > 0)
  { $idLote = 1;
    while ($regProdLote = $prodLoteSelect->fetch_object())
    { $oplist_tarima[$idLote] = $regProdLote->tarima;
      $oplist_idlote[$idLote] = $regProdLote->numerolote;
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

      $this->SetFont('Arial','B',8);

      $this->SetFillColor(224,7,8);

      if (file_exists('../../pictures/lolo.jpg')==true)    { 
      $this->Image('../../pictures/lolo.jpg',10,10,0,20); }
      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Orden de trabajo para Impresión con Huecograbado'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('PRO-FR05'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Versión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('2.0'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Revisión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('23 de Abril de 2015'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Responsable'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Impresor'),0,1,'L'); 

    /* Codigos de barra
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
      $this->Cell(40,4,utf8_decode('Juego de herramentales'),0,0,'R');
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

  $pdf->Cell(8,8,'#',0,0,'C');  
  $pdf->Cell(45,8,'Lote',1,0,'C');
  $pdf->Cell(16,8,'noop',1,0,'C');
  
  $pdf->SetFont('Arial','B',7);

  $pdf->Cell(30,4,'Metros',1,0,'C');
  $pdf->Cell(45,4,'Kilos',1,0,'C');
  $pdf->Cell(25,8,utf8_decode('Fecha impresión'),1,0,'C');
  $pdf->Cell(25,8,utf8_decode(''),1,1,'C');

  $pdf->Ln(-8);
  $pdf->Cell(169,4,'',0,0,'C');  
  $pdf->SetFont('Arial','B',7);
  $pdf->Cell(25,4,'El tono corresponde',0,0,'C');
  $pdf->Ln(8); 

  $pdf->Ln(-4);

  $pdf->SetFont('Arial','',7);
  $pdf->Cell(69,4,'',0,0,'C');  
  $pdf->Cell(15,4,'Recibidos',1,0,'C');
  $pdf->Cell(15,4,'Entregados',1,0,'C');
  $pdf->Cell(15,4,'Recibidos',1,0,'C');
  $pdf->Cell(30,4,'Entregados / Merma',1,0,'C');
  $pdf->Cell(25,4,'',0,0,'C');
  $pdf->SetFont('Arial','B',7);
  $pdf->Cell(25,4,'al estandar? (si/no)',0,1,'C');

  $pdf->SetFont('Arial','B',8);  

  for ($idLote = 1; $idLote <= $numLotes; $idLote++)
  { $pdf->SetFont('Arial','B',12);
    $pdf->Cell(8,8,$idLote,0,0,'C');

    $pdf->SetFont('Arial','',8);
    $pdf->Cell(45,4,$oplist_lote[$idLote],1,0,'L');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(16,8,$oplist_noop[$idLote],1,0,'C');
    $pdf->Cell(15,8,$oplist_longitud[$idLote],1,0,'R');
    $pdf->Cell(15,8,'',1,0,'L');
    $pdf->Cell(15,8,number_format($oplist_peso[$idLote],2),1,0,'R');
    $pdf->Cell(30,8,'/',1,0,'C');
    $pdf->Cell(25,8,'',1,0,'L');
    $pdf->Cell(25,8,'',1,1,'L');
    
    $pdf->Ln(-4);

    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(8,4,'',0,0,'L');
    $pdf->Cell(45,4,$oplist_tarima[$idLote].' No. '.$oplist_idlote[$idLote],1,1,'L'); }

  ////////////////////////////////////////////////
  $pdf->Output('PRO-FR05.pdf', 'I');
?>