<?php
ob_start();
session_start();
//require('../fpdf/fpdf.php');
require('../../fpdf/fpdflbl.php');
$welcome;
include('../Controlador_produccion/db_produccion.php');
include('../Controlador_produccion/functions.php');
$numero=$_GET['etiquetas'];
$proceso=explode("|", $numero,3);
    $producto=$proceso[1];
    $proceso=$proceso[0];
    $SQL=$MySQLiconn->query("SELECT numeroProceso,(SELECT tipo from produccion where nombreProducto='$producto' limit 1) as tipo from juegoprocesos where identificadorJuego=(select juegoProcesos from tipoproducto where 
                    alias=(select tipo from producto where 
                          descripcion=(select descripcionDisenio from impresiones where descripcionImpresion='".$producto."'))) and descripcionProceso='".$proceso."'");
    $roa=$SQL->fetch_array();
    $noProces=$roa['numeroProceso'];
    $tipo=$roa['tipo'];

$cons=$MySQLiconn->query("call getNodoProceso('".$producto."','".$noProces."')");
$consRo=$cons->fetch_array();
$idProceso=$consRo['id'];//El id del proceso actual
$numeroProceso=$consRo['numeroProceso'];//numero del proceso que se esta registrando
$procesoSiguiente=$consRo['siguiente'];//el proceso siguiente para colocarlo en la etiqueta
$procesoActual=$consRo['actual'];//la descripcion del proceso que se esta registrando
$MySQLiconn->next_result();

//Se tenia 2 veces lotes.tarima
$consul=$MySQLiconn->query("SELECT  lotes.idLote,lotes.anchuraBloque,lotes.noop,lotes.tarima,lotes.referenciaLote,lotes.numeroLote,impresiones.id as newid,`pro".$proceso."`.longitud as newLong,`pro".$proceso."`.peso as newPeso,`pro".$proceso."`.fecha as fecha,`pro".$proceso."`.unidades as newUnidades,impresiones.id,impresiones.descripcionDisenio,impresiones.descripcionImpresion,impresiones.anchoPelicula,impresiones.anchoEtiqueta,impresiones.espaciofusion from impresiones  inner join `pro".$proceso."` on  impresiones.descripcionImpresion=`pro".$proceso."`.producto inner join lotes on `pro".$proceso."`.noop=lotes.noop where `pro".$proceso."`.producto='".$producto."' and lotes.tipo='$tipo' and `pro".$proceso."`.total=1 and `pro".$proceso."`.tipo='$tipo'");
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
    

         // concatenacion de Codigo de barras
    
      $divisiones=0;
      if($procesoSiguiente=="revision")
      {
        $divisiones=1;
      }
      else
      {
        $divisiones=$regPackingList->anchuraBloque/$regPackingList->anchoPelicula;
      }

      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(52,4,'',0,0,'L');
      $pdf->Cell(46,4,$regPackingList->tarima.' | '.$regPackingList->numeroLote,0,1,'C');
      $pdf->Cell(52,4,'',0,0,'L');
      $pdf->Cell(46,4,$regPackingList->referenciaLote,0,1,'C');

      // Código de barras
           $codigoBarras=generarCodigoBarras($idProceso,$noProces,$regPackingList->idLote,$divisiones,$regPackingList->noop);//Llama ala funci´´on para generar el codigo
            /////
      
      // Código de barras
       $pdf->SetY(4);
      $pdf->SetFont('3of9','',34);      
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
    <p  class="message">Sin códigos disponibles</p>
  </body>
  <script type="text/javascript">
<!--
     var ventana = window.self; 
     ventana.opener = window.self; 
     setTimeout("window.close()", 1500);
//-->
</script>
</html>'; }
?>
<?php

ob_end_flush();
?>
