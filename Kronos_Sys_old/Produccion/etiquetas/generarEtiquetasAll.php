<?php
ob_start();
session_start();
//require('../fpdf/fpdf.php');
require('../../fpdf/fpdflbl.php');
$welcome;
include('../Controlador_produccion/db_produccion.php');
include('../Controlador_produccion/functions.php');
$numero=$_SESSION['etiquetas'];
$proceso=explode("|", $numero,3);
    $producto=$proceso[1];
    $proceso=$proceso[0];


       $SQL=$MySQLiconn->query("call getTipo('$producto')");
       $cso=$SQL->fetch_array();
       $tipo=$cso['alias'];//El id del proceso actual
       $MySQLiconn->next_result();

if($tipo!="BS")
{
   $SQL=$MySQLiconn->query("SELECT numeroProceso from juegoprocesos where identificadorJuego=(select juegoprocesos from tipoproducto where 
                    alias=(select tipo from producto where 
                          descripcion=(select descripcionDisenio from impresiones where descripcionImpresion='".$producto."'))) and descripcionProceso='".$proceso."'");
          $roa=$SQL->fetch_array();
           $noProces=$roa['numeroProceso'];
}
else
{
   $SQL=$MySQLiconn->query("SELECT numeroProceso from juegoprocesos where identificadorJuego=(select juegoprocesos from tipoproducto where 
                    alias='BS' and descripcionProceso='".$proceso."')");
          $roa=$SQL->fetch_array();
           $noProces=$roa['numeroProceso'];
}
    if($tipo!="BS")
    {
      $cons=$MySQLiconn->query("call getNodoProceso('".$producto."','".$noProces."')");
$consRo=$cons->fetch_array();
$idProceso=$consRo['id'];//El id del proceso actual
$numeroProceso=$consRo['numeroProceso'];//numero del proceso que se esta registrando
$procesoSiguiente=$consRo['siguiente'];//el proceso siguiente para colocarlo en la etiqueta
$procesoActual=$consRo['actual'];//la descripcion del proceso que se esta registrando
$MySQLiconn->next_result();

$consul=$MySQLiconn->query("SELECT `pro".$procesoActual."`.*,impresiones.id as newid,impresiones.descripcionDisenio,impresiones.descripcionImpresion,impresiones.anchoPelicula,impresiones.anchoEtiqueta,impresiones.espaciofusion,impresiones.alturaEtiqueta,impresiones.millaresPorRollo from `pro".$procesoActual."` inner join impresiones  where impresiones.descripcionImpresion='".$producto."' and `pro".$proceso."`.unidades!='' and `pro".$proceso."`.total=1 and `pro".$proceso."`.tipo='".$tipo."' and `pro".$proceso."`.producto='".$producto."' order by `pro".$procesoActual."`.noop DESC");
    }

if($tipo=="BS")
{
    $cons=$MySQLiconn->query("call getNodoBS('".$producto."','".$noProces."')");
$consRo=$cons->fetch_array();
$procesoSiguiente=$consRo['siguiente'];//el proceso siguiente para colocarlo en la etiqueta
$idProceso=$consRo['id'];//El id del proceso actual
$procesoActual=$consRo['actual'];//la descripcion del proceso que se esta registrando
$MySQLiconn->next_result();


$consul=$MySQLiconn->query("SELECT `pro".$procesoActual."`.*,bandaspp.idBSPP as newid,bandaspp.identificadorBS as descripcionDisenio,bandaspp.nombreBSPP as descripcionImpresion,bandaspp.anchuraLaminado as alturaEtiqueta from `pro".$procesoActual."` inner join bandaspp  where bandaspp.nombreBSPP='".$producto."' and `pro".$proceso."`.longitud!='' and `pro".$proceso."`.total=1 and `pro".$proceso."`.tipo='".$tipo."' and `pro".$proceso."`.producto='".$producto."' order by `pro".$procesoActual."`.noop DESC");
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
      
    { 
      $idLote=sendIdLote($regPackingList->noop,$producto);
      $pdf->AddPage();
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

      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Informacion',0,1,'L');

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Longitud',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,number_format($regPackingList->longitud,2).' mts',0,1,'R'); 
if(!empty($regPackingList->amplitud))
{
   $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Ancho plano',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,$regPackingList->amplitud.' mm',0,1,'R'); 
}
if(!empty($regPackingList->peso))
{
  if($procesoSiguiente!='')
  {
    
   $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Peso',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,$regPackingList->peso.' Kgs',0,1,'R'); 
  }
}
     

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L'); 
      $pdf->Cell(28,4,$decision,0,0,'L');
      $pdf->SetFont('Arial','B',10); 
       if($tipo!="BS")
      {
        $pdf->Cell(20,4,number_format($regPackingList->unidades*1000),0,1,'R'); 
      }
      else
      {
        $pdf->Cell(20,4,number_format($regPackingList->unidades),0,1,'R'); 
      }
     
      $pdf->SetY(-7);     
      $pdf->Cell(2,5,'',0,0,'L');      
      $pdf->SetFont('Arial','B',18);
      $pdf->Cell(68,5,'NoOP:'.$marcador.$regPackingList->noop,0,1,'R');

      // Información
      $pdf->SetY(22);
      
      
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->Cell(15,4,$procesoSiguiente,0,1,'L');

      //Coloca estos datos dependiendo del proceso que siga

include('armado_etiquetas.php');


     
      
      $pdf->Ln(8);

      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',8);
      $pdf->Cell(46,3,$regPackingList->fecha,0,1,'R');  

         // concatenacion de Codigo de barras

if($tipo!="BS")
{
  $divisiones = ceil((($regPackingList->longitud/$regPackingList->alturaEtiqueta)/$regPackingList->millaresPorRollo));
}
else
{
  $divisiones=ceil($regPackingList->amplitud/$regPackingList->alturaEtiqueta);
}
// Código de barras
           $codigoBarras=generarCodigoBarras($idProceso,$noProces,$idLote,$divisiones,$regPackingList->noop);//Llama ala funci´´on para generar el codigo

      // Código de barras
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
    <p  class="message">Sin códigos disponibles</p>
  </body>
  <script type="text/javascript">
<!--
     var ventana = window.self; 
     ventana.opener = window.self; 
     
//-->
</script>
</html>'; }
?>
<?php
//setTimeout("window.close()", 1500);
ob_end_flush();
?>

