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
$idLote=$arrayDatos[7];
$tipo=$arrayDatos[6];
$lote=$arrayDatos[2];

  //Divisiones te dice en cuantos pdf se va a dividir el documento,o bueno,cuantos registros se mostrarán

$OPS=$MySQLiconn->query("call getNodoProceso('$producto','$noProces')");
$consRo=$OPS->fetch_array();
$idProceso=$consRo['id'];//El id del proceso actual
$numeroProceso=$consRo['numeroProceso'];//numero del proceso que se esta registrando
$procesoSiguiente=$consRo['siguiente'];//el proceso siguiente para colocarlo en la etiqueta
$procesoActual=$consRo['actual'];//la descripcion del proceso que se esta registrando
$procesoAnterior=$consRo['anterior'];

$MySQLiconn->next_result();


$consul=$MySQLiconn->query("SELECT `pro".$procesoActual."`.*,impresiones.id as newid,impresiones.descripcionDisenio,impresiones.descripcionImpresion,impresiones.anchoPelicula,impresiones.anchoEtiqueta,impresiones.espaciofusion,impresiones.alturaEtiqueta,impresiones.millaresPorRollo,impresiones.millaresPorPaquete from `pro".$procesoActual."` inner join impresiones   where `pro".$procesoActual."`.noop like '$noop' and impresiones.descripcionImpresion='$producto' and rollo_padre=1 order by `pro".$procesoActual."`.noop DESC");

$jue=$MySQLiconn->query("SELECT alturaReal as altJuego from juegoscilindros where descripcionImpresion='$producto' and identificadorCilindro=(SELECT juegoCilindros from produccion where juegoLotes=(SELECT juegoLote from lotes where referenciaLote='$lote'))");
if(!$jue)
{
  $jue=$MySQLiconn->query("SELECT juegoCireles.alturaReal as altJuego FROM juegoscireles where juegoscireles.descripcionImpresion='$producto' and identificadorJuego=(SELECT juegoCireles from produccion where juegoLotes=(SELECT juegoLote from lotes where referenciaLote='$lote'))");
}
$or=$jue->fetch_object();
$estado=0;
$mpp=0;

if ($consul->num_rows > 0)
  { $pdf=new FPDF('P','mm','lbl4x1');
$pdf->AddFont('3of9','','free3of9.php');

$contador=1;
$nPaquetes=0;
$regCodePaquete = $consul->fetch_object();
$altJuego=$or->altJuego;
$codFat=$regCodePaquete->rollo;

if($regCodePaquete->total==0)
{
  $estado=1;
}

$unidades=$regCodePaquete->unidades*1000;
$mpp= $regCodePaquete->millaresPorPaquete*1000;
$nCodigos=round(($unidades/$mpp));//Anteriormente estaba ceil en lugar de round
          // concatenacion de Codigo de barras
$codePaquete_cdgpaquete = array($nCodigos);
for($i=1;$i<=$nCodigos;$i++)
{
  $opFinal=$regCodePaquete->noop."-".$i;

 
 // Código de barras
           $codigoBarras=generarCodigoBarras($idProceso,$noProces,$idLote,0,$opFinal);//Llama ala funci´´on para generar el codigo
$es=$MySQLiconn->query("SELECT noop FROM procorte where noop='$opFinal'");
if(mysqli_num_rows($es)>0)
{
  $codePaquete_cdgpaquete[$i] = $codigoBarras; 
      $codePaquete_paquete[$i] = $codigoBarras;//$regCodePaquete->alturaEtiqueta;//.$regCodePaquete->anchoEtiqueta;
}

;

    }

    $nPaquetes=round((($unidades/$mpp)/2));//Antes estaba number_format
    $nPac=round($unidades/$mpp);//Ants estaba number_format
$mtrsOut=((($mpp/1000)*($altJuego)))*$nPac;
$newUnidades=$mtrsOut/$altJuego;
        $MySQLiconn->query("UPDATE merma set longOut='".$mtrsOut."',unidadesOut='$newUnidades' where codigo='".$codFat."'");
        //echo "UPDATE merma set longOut='".$mtrsOut."',unidadesOut='$newUnidades' where codigo='".$codFat."'";
   // $nPaquetes = number_format($prodPaqueteSelect->num_rows/2);
   
      for ($i =1; $i <= ceil($nPac); $i++)
    { 
      $noopCon=$arrayDatos[4]."-".$i;
      $unidades=$mpp/1000; 
       if($estado==0)
      {
        if(!empty($codePaquete_cdgpaquete[$i]))
        {

       $MySQLiconn->query("UPDATE `pro".$procesoActual."` set rollo='".$codePaquete_cdgpaquete[$i]."',unidades='".$unidades."' where noop='".$noopCon."'");

        $MySQLiconn->query("UPDATE codigosbarras set noop='".$noopCon."' where codigo='".$codePaquete_cdgpaquete[$i]."'");
       }
     }
    }
    for ($item = 1; $item <= $nPaquetes; $item++)
    { 

     
     
     $pdf->AddPage();

      // Código de barras
     $pdf->SetY(10);
     $pdf->SetX(6);
     $pdf->SetFont('3of9','',24);
     $pdf->Cell(39,6,'*'.$codePaquete_cdgpaquete[(($item*2)-1)].'*',0,0,'C'); 
     
     if(!empty($codePaquete_cdgpaquete[(($item*2))]))
     {
      $pdf->Cell(10,6,'',0,0,'C');
      $pdf->Cell(50,6,'*'.$codePaquete_cdgpaquete[($item*2)].'*',0,1,'C');

    }
    else
    {
      $pdf->ln();
    }
    $pdf->SetFont('Arial','',9); 
    $pdf->Cell(50,2,$codePaquete_paquete[(($item*2)-1)],0,0,'C');


    if(!empty($codePaquete_paquete[(($item*2))]))
    {
     
      $pdf->Cell(5,6,'',0,0,'C');
      $pdf->Cell(50,2,$codePaquete_paquete[($item*2)],0,1,'C');
    }
  }
  $contador++;

  $pdf->Output();
  $MySQLiconn->query("UPDATE `pro".$procesoActual."` set total=0 where noop='".$arrayDatos[4]."'");
}

else
  { echo '<html>
<head>
<meta charset="UTF-8" />
<link rel="stylesheet" type="text/css" href="../../css/global.css" /> 
</head>
<body>
<h1>No hay códigos disponibles.</h1>
</body>
</html>'; } 
}
else
{
  echo '<html>
<head>
<meta charset="UTF-8" />
<link rel="stylesheet" type="text/css" href="../../css/global.css" /> 
</head>
<body>
<p class="message">Tiempo de espera agotado,vuelva a generar los códigos</p>
</body>
</html>';
}



?>
