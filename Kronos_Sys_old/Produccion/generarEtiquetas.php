<?php
ob_start();
session_start();
//require('../fpdf/fpdf.php');
require('../fpdf/fpdflbl.php');
include('Controlador_produccion/db_produccion.php');
include('Controlador_produccion/functions.php');
$numero=$_SESSION['lotesImprimir'];



$CK=$MySQLiconn->query("SELECT tipo,nombreProducto from produccion where juegoLotes='$numero'");
$Rl=$CK->fetch_object();
$tipo=$Rl->tipo;
$producto=$Rl->nombreProducto;
$numeroProceso=0;
if($tipo!="BS")
{
$cons=$MySQLiconn->query("call getNodoProceso('".$producto."','".$numeroProceso."')");
$consRo=$cons->fetch_array();
$procesoSiguiente=$consRo['siguiente'];//el proceso siguiente para colocarlo en la etiqueta
$procesoActual=$consRo['actual'];
$MySQLiconn->next_result();
  $consul=$MySQLiconn->query("SELECT lotes.*,impresiones.id,impresiones.descripcionDisenio,impresiones.descripcionImpresion,impresiones.anchoPelicula,impresiones.anchoEtiqueta,impresiones.espaciofusion from lotes inner join impresiones where lotes.juegoLote='$numero' and impresiones.descripcionImpresion=(SELECT nombreProducto from produccion where juegoLotes='$numero') and lotes.estado=1");
}
else
{
  $cons=$MySQLiconn->query("call getNodoBS('".$producto."','".$numeroProceso."')");
$consRo=$cons->fetch_array();
$procesoSiguiente=$consRo['siguiente'];//el proceso siguiente para colocarlo en la etiqueta
$procesoActual=$consRo['actual'];
$MySQLiconn->next_result();
$consul=$MySQLiconn->query("SELECT lotes.idLote,lotes.longitud,lotes.anchuraBloque,lotes.peso,(lotes.unidades/1000) as unidades,lotes.noop,lotes.tarima,lotes.numeroLote,lotes.referenciaLote,produccion.juegoLotes,bandaspp.idBSPP as id,bandaspp.identificadorBS as descripcionDisenio,bandaspp.nombreBSPP as descripcionImpresion,bandaspp.anchuraLaminado as anchura  from produccion inner join lotes on produccion.juegoLotes=lotes.juegoLote inner join bandaspp on produccion.nombreProducto=bandaspp.nombreBSPP where produccion.nombreproducto='$producto' and produccion.estado=2 and lotes.estado=1 and lotes.tipo='$tipo' and lotes.juegoLote='$numero'");
}
if($tipo=="BS")
{
  $decision="Metros aprox.";
  $marcador="BS.";
}
else
{
  $decision="Piezas aprox.";
  $marcador=substr($tipo,0,3);
  $marcador=strtoupper($marcador).".";
}



 if ($consul->num_rows > 0)
  { $pdf=new FPDF('P','mm','lbl4x2'); 
    $pdf->AddFont('3of9','','free3of9.php');
    
    while ($regPackingList = $consul->fetch_object())
      
    { $pdf->AddPage();

      $pdf->SetY(9.5);
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(48,4,'Producto',0,1,'L');
      $pdf->SetFont('Arial','B',11);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(98,4,$regPackingList->descripcionDisenio,0,1,'L'); 
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(98,4,$regPackingList->descripcionImpresion,0,1,'L');       
      
      $pdf->Ln(0.5);
      //Aqui empieza a concatenar datos para hacer el codigo de barras
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Informacion',0,1,'L');

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Longitud',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,number_format($regPackingList->longitud,2).' mts',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Anchura',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,$regPackingList->anchuraBloque.' mm',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Peso',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,number_format($regPackingList->peso,3).' kgs',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L'); 
      $pdf->Cell(28,4,$decision,0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(20,4,number_format($regPackingList->unidades*1000),0,1,'R'); 

      $pdf->SetY(-7);     
      $pdf->Cell(2,5,'',0,0,'L');      
      $pdf->SetFont('Arial','B',18);
      $pdf->Cell(68,5,'NoOP:'.$marcador.$regPackingList->noop,0,1,'R');
      //Codigo de barras
     


      // Información
      $pdf->SetY(22);
      
      
       $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->Cell(15,4,utf8_decode($procesoSiguiente),0,1,'L');
      
    include('etiquetas/armado_etiquetas.php');
     
   
$divisiones=sendDivisions($tipo,$procesoSiguiente,$procesoActual,$producto,0,$regPackingList->noop);
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(52,4,'',0,0,'L');
      $pdf->Cell(46,4,$regPackingList->tarima.' | '.$regPackingList->numeroLote,0,1,'C');
      $pdf->Cell(52,4,'',0,0,'L');
      $pdf->Cell(46,4,$regPackingList->referenciaLote,0,1,'C');
      

      // Código de barras

           $codigoBarras=generarCodigoBarras("20","0",$regPackingList->idLote,$divisiones,$regPackingList->noop);
           //Llama ala funci´´on para generar el codigo
            /////
       $pdf->SetY(4);
      $pdf->SetFont('3of9','',28);      
      $pdf->Cell(80,5,'',0,0,'R'); 
      $pdf->Cell(16,5,'*'.$codigoBarras.'*',0,1,'R');
      $pdf->Ln(1);
      $pdf->SetFont('Arial','',8); 
      $pdf->Cell(50,3,'',0,0,'R'); 
      $pdf->Cell(46,3,$codigoBarras,0,1,'C'); 

      // Imagen LOGOTIPO
      if (file_exists('../pictures/logo-labro.jpg')==true) 
      { $pdf->Image('../pictures/logo-labro.jpg',2,2,14); }
    }

    $pdf->Output();

  } else
  { echo '<html>
  <head>    
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" /> 
  </head>
  <body>
    <p class="message">Referencia inválida</p>
  </body>
</html>'; }
?>
<?php

ob_end_flush();
?>


