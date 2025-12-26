<?php
ob_start();
session_start();
//require('../fpdf/fpdf.php');
require('../../fpdf/fpdflbl.php');
$welcome;
include('../Controlador_produccion/db_produccion.php');
include('../Controlador_produccion/functions.php');
//echo $_SESSION['etiquetasInd'];

if(!empty($_GET['etiquetasInd']))
{
  $numero=$_GET['etiquetasInd'];
$proceso=explode("|", $numero,3);
    $oldCodigo=$proceso[0];
    $Nodoc=$proceso[1];
    
$arrayDatos=parsearCodigo($oldCodigo);
$arrayDatos=explode("|",$arrayDatos);
$producto=$arrayDatos[0];
$proceso=$arrayDatos[1];
$noProces=$arrayDatos[3]+1;
$noop=$arrayDatos[4];
$tipo=$arrayDatos[6];
  //Divisiones te dice en cuantos pdf se va a dividir el documento,o bueno,cuantos registros se mostrarán

$cons=$MySQLiconn->query("call getNodoProceso('".$producto."','".$noProces."')");
$consRo=$cons->fetch_array();
$idProceso=$consRo['id'];//El id del proceso actual
$numeroProceso=$consRo['numeroProceso'];//numero del proceso que se esta registrando
$procesoSiguiente=$consRo['siguiente'];//el proceso siguiente para colocarlo en la etiqueta
$procesoActual=$consRo['actual'];//la descripcion del proceso que se esta registrando
$MySQLiconn->next_result();

$consul=$MySQLiconn->query("SELECT lotes.*,impresiones.id as newid,proimpresion.longitud as newLong,proimpresion.peso as newPeso,proimpresion.fecha as fecha,proimpresion.unidades as newUnidades,impresiones.id,impresiones.descripcionDisenio,impresiones.descripcionImpresion,impresiones.anchoPelicula,impresiones.anchoEtiqueta,impresiones.espaciofusion from lotes inner join impresiones inner join proimpresion  where lotes.noop like '$noop' and impresiones.descripcionImpresion='$producto' and proimpresion.noop like '$noop' and lotes.tipo='$tipo' and proimpresion.tipo='$tipo'");

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
      $pdf->Cell(30,4,number_format($regPackingList->newLong,2).' mts',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Anchura',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,$regPackingList->anchuraBloque.' mm',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Peso',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,number_format($regPackingList->newPeso,3).' kgs',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L'); 
      $pdf->Cell(28,4,'Piezas',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(20,4,number_format($regPackingList->newUnidades*1000),0,1,'R'); 

      $pdf->SetY(-7);     
      $pdf->Cell(2,5,'',0,0,'L');      
      $pdf->SetFont('Arial','B',20);
      $pdf->Cell(68,5,'NoOP:'.$marcador.$regPackingList->noop,0,1,'R');
     

      // Información
      $pdf->SetY(22);
      
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->Cell(15,4,$procesoSiguiente,0,1,'L');
     
include('armado_etiquetas.php');
    

      $divisiones=sendDivisions($tipo,$procesoSiguiente,$procesoActual,$producto,$oldCodigo,$regPackingList->noop);
             
      
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(52,4,'',0,0,'L');
      $pdf->Cell(46,4,$regPackingList->tarima.' | '.$regPackingList->numeroLote,0,1,'C');
      $pdf->Cell(52,4,'',0,0,'L');
      $pdf->Cell(46,4,$regPackingList->referenciaLote,0,1,'C');
      
       // Código de barras
           $codigoBarras=generarCodigoBarras($idProceso,$noProces,$regPackingList->idLote,$divisiones,$regPackingList->noop);//Llama ala funci´´on para generar el codigo
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
      if (file_exists('../../pictures/logo-labro.jpg')==true) 
      { $pdf->Image('../../pictures/logo-labro.jpg',2,2,14); }
    }

    $pdf->Output();

  } else
  { echo '<html>
  <head>    
    <link rel="stylesheet" type="text/css" href="../../css/global.css" media="screen" /> 
  </head>
  <body>
    <p>Referencia inválida</p>
  </body>
</html>'; }
}
else
{
  echo '<html>
  <head>    
    <link rel="stylesheet" type="text/css" href="../../css/global.css" media="screen" /> 
  </head>
  <body>
    <p class="message">Tiempo de espera agotado,vuelva a ingresar el código</p>
  </body>
</html>';
}

ob_end_flush();
?>

