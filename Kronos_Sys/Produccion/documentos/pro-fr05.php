<?php
//linea 53 y 60, 46 y 174

  include '../../Database/db.php'; 
  include '../../fpdf/fpdf.php';
  //include("../../Database/SQLConnection.php");
  
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
  echo "SELECT * FROM juegoscilindros WHERE identificadorCilindro = '".$_GET['cdgjuego']."'";
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

    //no funciono
    $_SESSION['oplist_idjuego'] = $regPdtoJuego->IDCilindro;
    $_SESSION['oplist_cdgjuego'] = $regPdtoJuego->proveedor; 
  }


  $pdtoImpresionTntSelect = $MySQLiconn->query("SELECT pantonepcapa.descripcionPantone, impresiones.tintas,
           pantonepcapa.consumoPantone, pantonepcapa.disolvente FROM pantonepcapa, impresiones
     WHERE impresiones.codigoImpresion='".$_GET['cdgimpresion']."' AND impresiones.codigoImpresion=pantonepcapa.codigoImpresion");
 //$pdtoImpresionSelect 
  if ($pdtoImpresionTntSelect->num_rows > 0){ 
    $item = 0;
    while ($regImpresionTnt = $pdtoImpresionTntSelect->fetch_object()){ 
      //$lstSQL = sqlsrv_query($SQLconn,"SELECT p.CIDPRODUCTO from admProductos p WHERE p.cnombreproducto like '%".$regImpresionTnt->disolvente."%'");

      //$runi=sqlsrv_fetch_array($lstSQL,SQLSRV_FETCH_ASSOC);
      $runi["CIDPRODUCTO"] = 0;
      $yout=$MySQLiconn->query("SELECT consumo from consumos where elemento='".$runi["CIDPRODUCTO"]."'");
      $vao=$yout->fetch_object();
      $item++;
      $oplist_pantone[$item] = $regImpresionTnt->descripcionPantone;
      $oplist_notinta[$item] = $regImpresionTnt->tintas;      
      $oplist_consumo[$item] = number_format(($regImpresionTnt->consumoPantone*$_GET['millares']),3);
      $oplist_disolvente[$item] = $regImpresionTnt->disolvente; 
      $oplist_disconsumo[$item] = number_format($vao->consumo*($regImpresionTnt->consumoPantone*$_GET['millares']),2);
    }
    $nTintas = $pdtoImpresionTntSelect->num_rows; 
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
    $_SESSION['oplist_idjuego'] = $regPdtoJuego->id;
    $_SESSION['oplist_cdgjuego'] = $regPdtoJuego->num_dientes; 
    $_SESSION['oplist_codjuego'] = $regPdtoJuego->identificadorJuego; 
  }
  else
  {
    $pdtoJuegoSelect = $MySQLiconn->query("SELECT * FROM suaje WHERE identificadorSuaje = '".$_GET['cdgjuego']."'");
     $regPdtoJuego = $pdtoJuegoSelect->fetch_object();
    $_SESSION['oplist_idjuego'] = $regPdtoJuego->id;
    $_SESSION['oplist_cdgjuego'] = $regPdtoJuego->codigo; 
    $_SESSION['oplist_codjuego'] = $regPdtoJuego->identificadorSuaje; 

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

    $_SESSION['oplist_idjuego'] = $regPdtoJuego->IDCilindro;
    $_SESSION['oplist_cdgjuego'] = $regPdtoJuego->proveedor; 
    $_SESSION['oplist_codjuego'] = $regPdtoJuego->identificadorCilindro; 

  }
  }
}
else
{
    $_SESSION['oplist_idjuego'] = "No aplica";
    $_SESSION['oplist_cdgjuego'] = ""; 
}
  
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

    //comentado
    $_SESSION['oplist_idjuego'] = $regPdtoJuego->IDCilindro;
    $_SESSION['oplist_cdgjuego'] = $regPdtoJuego->proveedor; }

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




$prodLoteSelect = $MySQLiconn->query("SELECT lotes.tarima, lotes.numeroLote, lotes.referenciaLote, lotes.longitud, lotes.peso, lotes.noop FROM produccion, lotes
     WHERE lotes.juegoLote = produccion.juegoLotes AND
           produccion.nombreProducto = '".$_SESSION['oplist_impresion']."' AND
          (produccion.maquina = '".$_SESSION['oplist_maquina']."' AND 
           produccion.juegoCilindros = '".$_GET['cdgjuego']."' AND 
           produccion.fechaProduccion = '".$_GET['fchprograma']."')
  ORDER BY lotes.noop");
if(mysqli_num_rows($prodLoteSelect)==0)
{
  $prodLoteSelect = $MySQLiconn->query("SELECT lotes.tarima, lotes.numeroLote, lotes.referenciaLote, lotes.longitud, lotes.peso, lotes.noop FROM produccion, lotes
     WHERE lotes.juegoLote = produccion.juegoLotes AND
           produccion.nombreProducto = '".$_SESSION['oplist_impresion']."' AND
          (produccion.juegoCireles = '".$_GET['cdgjuego']."' AND 
           produccion.fechaProduccion = '".$_GET['fchprograma']."')
  ORDER BY lotes.noop");
 
}

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

      $this->SetFont('Arial','B',8);

      $this->SetFillColor(224,7,8);

      if (file_exists('../../pictures/lolo.jpg')==true)    { 
      $this->Image('../../pictures/lolo.jpg',10,10,0,20); }
     $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Orden de trabajo y trazabilidad de lotes'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('PRO-FR05'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Versión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('4.0'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Revisión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('03 de septiembre del 2019'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Responsable'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Impresor'),0,1,'L'); 

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Disponibilidad'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Físico/A PRUEBA'),0,1,'L'); 

    
      $this->Ln(4);

      $this->SetFont('3of9','',24);      
      $this->Cell(195,5,'*00'.str_pad($_SESSION['oplist_idjuego'],  11, "0", STR_PAD_LEFT).'*',0,1,'R');
      $this->SetFont('Arial','B',9);    
      $this->Cell(195,6,$_SESSION['oplist_codjuego'].'| '.$_SESSION['oplist_cdgjuego'],0,1,'R');

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
      $this->Cell(60,4,$_SESSION['oplist_codjuego'].' ('.$_SESSION['oplist_cdgjuego'].')',0,1,'L');

      $this->SetFont('Arial','I',8);
      $this->Cell(40,4,utf8_decode('Impresión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','B',8);
      $this->Cell(60,4,$_SESSION['oplist_impresion'],0,1,'L');

      $this->SetFont('Arial','I',8);
      $this->Cell(40,4,utf8_decode('Máquina de impresión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);  
      $this->SetFont('Arial','B',8);
      $this->Cell(60,4,$_SESSION['oplist_idmaquina'].' '.utf8_decode($_SESSION['oplist_maquina']),0,1,'L');

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
  $pdf->AddFont('3of9','','free3of9.php');
  $pdf->AliasNbPages();

  $pdf->AddPage();

  $pdf->SetFont('Arial','B',10);

  $pdf->Cell(8,8,'#',0,0,'C');  
  $pdf->Cell(40,8,'Lote',1,0,'C');
  $pdf->Cell(16,8,'NoOP',1,0,'C');
  
  $pdf->SetFont('Arial','B',7);

  $pdf->Cell(30,4,'Metros',1,0,'C');
  $pdf->Cell(45,4,'Kilos',1,0,'C');
  $pdf->Cell(30,8,utf8_decode('Hora inic./ Hora fin'),1,0,'C');
  $pdf->Cell(25,8,utf8_decode(''),1,1,'C');

  $pdf->Ln(-8);
  $pdf->Cell(169,4,'',0,0,'C');  
  $pdf->SetFont('Arial','B',7);
  $pdf->Cell(25,4,'El tono corresponde',0,0,'C');
  $pdf->Ln(8); 
   $pdf->Ln(-4);

  $pdf->SetFont('Arial','',7);
  $pdf->Cell(64,4,'',0,0,'C');  
  $pdf->Cell(15,4,'Recibidos',1,0,'C');
  $pdf->Cell(15,4,'Entregados',1,0,'C');
  $pdf->Cell(15,4,'Recibidos',1,0,'C');
  $pdf->Cell(30,4,'Entregados / Merma',1,0,'C');
  $pdf->Cell(25,4,'',0,0,'C');
  $pdf->SetFont('Arial','B',7);
  $pdf->Cell(34,4,'al estandar? (si/no)',0,1,'C');

  $pdf->SetFont('Arial','B',8);  

  for ($idLote = 1; $idLote <= $numLotes; $idLote++)
  { $pdf->SetFont('Arial','B',12);
    $pdf->Cell(8,8,$idLote,0,0,'C');

    $pdf->SetFont('Arial','',8);
    $pdf->Cell(40,4,$oplist_lote[$idLote],1,0,'L');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(16,8,$oplist_noop[$idLote],1,0,'C');
    $pdf->Cell(15,8,$oplist_longitud[$idLote],1,0,'R');
    $pdf->Cell(15,8,'',1,0,'L');
    $pdf->Cell(15,8,number_format($oplist_peso[$idLote],2),1,0,'R');
    $pdf->Cell(30,8,'/',1,0,'C');
    $pdf->Cell(30,8,'/',1,0,'C');
    $pdf->Cell(25,8,'',1,1,'L');
    
    $pdf->Ln(-4);

    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(8,4,'',0,0,'L');
    $pdf->Cell(40,4,$oplist_tarima[$idLote].' No. '.$oplist_idlote[$idLote],1,1,'L'); }


    $pdf->Ln(10);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(8,8,'#',0,0,'C'); 
    $pdf->Cell(50,6,'Necesidad de tinta',1,0,'C');

  $pdf->SetFont('Arial','',8);
  $pdf->Cell(25,6,'Requerido',1,0,'C');
  $pdf->Cell(25,6,'No. Lote',1,0,'C');
  $pdf->Cell(20,6,'Disolvente',1,0,'C');
  $pdf->Cell(25,6,'Requerido',1,0,'C');
  $pdf->Cell(21,6,'No. Lote',1,0,'C');
  $pdf->Cell(20,6,'Anillox',1,1,'C');

  for ($idTinta = 1; $idTinta <= $nTintas; $idTinta++)
  { $pdf->SetFont('Arial','B',12);    
    
    $pdf->Cell(7,6,$idTinta,0,0,'C');
    $pdf->Cell(1,6,'',0,0,'C');

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(50,8,$oplist_pantone[$idTinta],1,0,'L'); 

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(25,8,$oplist_consumo[$idTinta].' kgs',1,0,'R'); 
    $pdf->Cell(25,4,'',1,0,'C');
    
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(20,8,$oplist_disolvente[$idTinta],1,0,'C');
    $pdf->Cell(25,8,$oplist_disconsumo[$idTinta].' kgs',1,0,'C');
    $pdf->Cell(21,4,'',1,0,'C');
    $pdf->Cell(20,8,'',1,1,'C');
     }
     $pdf->Cell(8,8,'',0,0,'C');
     $pdf->Cell(90,4,'',T,0,'C');
     $pdf->Cell(85,4,'',T,0,'C');

  $_SESSION['oplist_diseno'] = '';
  $_SESSION['oplist_impresion'] = '';
  $_SESSION['oplist_idjuego'] = '';
  $_SESSION['oplist_cdgjuego'] = '';
  $_SESSION['oplist_idmaquina'] = '';
  $_SESSION['oplist_maquina'] = '';

  ////////////////////////////////////////////////
  $pdf->Output('PRO-FR05.pdf', 'I');
?>