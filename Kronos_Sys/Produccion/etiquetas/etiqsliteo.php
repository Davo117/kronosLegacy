<?php
ob_start();
session_start();
//require('../fpdf/fpdf.php');
require('../../fpdf/fpdflbl.php');
include('../Controlador_produccion/db_produccion.php');
include('../Controlador_produccion/functions.php');
$numero=$_GET['etiquetas'];
$proceso=explode("|", $numero,3);
    $producto=$proceso[1];
    $proceso=$proceso[0];
    $tipo=$_GET['tipo'];
/*if($tipo!=1)
{
  $MySQLiconn->query("SET @p0='".$producto."'");
       $SQL=$MySQLiconn->query("call getTipo('$producto')");
       $cso=$SQL->fetch_array();
       $tipo=$cso['id'];//El id del proceso actual
       $MySQLiconn->next_result();
}*/
       

if($tipo!="1")
{
   $SQL=$MySQLiconn->query("SELECT numeroProceso from juegoprocesos where identificadorJuego=(select juegoprocesos from tipoproducto where 
                    id=(select tipo from producto where 
                          ID=(select descripcionDisenio from impresiones where id='".$producto."'))) and descripcionProceso='".$proceso."'");
          $roa=$SQL->fetch_array();
           $noProces=$roa['numeroProceso'];
}
else
{
   $SQL=$MySQLiconn->query("SELECT numeroProceso from juegoprocesos where identificadorJuego=(select juegoprocesos from tipoproducto where 
                    id=1 and descripcionProceso='".$proceso."')");
          $roa=$SQL->fetch_array();
           $noProces=$roa['numeroProceso'];
}
    if($tipo!="1")
    {
      $MySQLiconn->query("SET @p0='".$producto."',@p1='".$noProces."'");
    $cons=$MySQLiconn->query("call getNodoProceso(@p0,@p1)");
$consRo=$cons->fetch_array();
$idProceso=$consRo['id'];//El id del proceso actual
$numeroProceso=$consRo['numeroProceso'];//numero del proceso que se esta registrando
$procesoSiguiente=$consRo['siguiente'];//el proceso siguiente para colocarlo en la etiqueta
$procesoActual=$consRo['actual'];//la descripcion del proceso que se esta registrando
$MySQLiconn->next_result();

$consul=$MySQLiconn->query("SELECT `pro".$procesoActual."`.*,impresiones.id as newid,(SELECT descripcion from producto where ID=impresiones.descripcionDisenio) as descripcionDisenio,impresiones.descripcionImpresion,impresiones.anchoPelicula as anchuraBS,impresiones.anchoEtiqueta,impresiones.espaciofusion,impresiones.alturaEtiqueta,impresiones.millaresPorRollo from `tbpro".$procesoActual."` `pro".$procesoActual."` inner join impresiones  where impresiones.id='".$producto."' and `pro".$proceso."`.longitud!='' and `pro".$proceso."`.total=1 and `pro".$proceso."`.tipo='".$tipo."' and `pro".$proceso."`.producto='".$producto."' order by `pro".$procesoActual."`.id DESC");
echo "SELECT `pro".$procesoActual."`.*,impresiones.id as newid,(SELECT descripcion from producto where ID=impresiones.descripcionDisenio) as descripcionDisenio,impresiones.descripcionImpresion,impresiones.anchoPelicula as anchuraBS,impresiones.anchoEtiqueta,impresiones.espaciofusion,impresiones.alturaEtiqueta,impresiones.millaresPorRollo from `tbpro".$procesoActual."` `pro".$procesoActual."` inner join impresiones  where impresiones.id='".$producto."' and `pro".$proceso."`.longitud!='' and `pro".$proceso."`.total=1 and `pro".$proceso."`.tipo='".$tipo."' and `pro".$proceso."`.producto='".$producto."' order by `pro".$procesoActual."`.id DESC";
    }

if($tipo=="1")
{
  $MySQLiconn->query("SET @p0='".$producto."',@p1='".$noProces."'");
    $cons=$MySQLiconn->query("call getNodoBS(@p0,@p1)");
$consRo=$cons->fetch_array();
$procesoSiguiente=$consRo['siguiente'];//el proceso siguiente para colocarlo en la etiqueta
$idProceso=$consRo['id'];//El id del proceso actual
$procesoActual=$consRo['actual'];//la descripcion del proceso que se esta registrando
$MySQLiconn->next_result();


$consul=$MySQLiconn->query("SELECT distinct `tbpro".$procesoActual."`.*,bandaspp.idBSPP as newid,(SELECT nombrebanda FROM bandaseguridad WHERE IDBanda=bandaspp.identificadorBS) as descripcionDisenio,bandaspp.nombreBSPP as descripcionImpresion,(SELECT anchura from bandaseguridad where nombrebanda=(SELECT identificadorBS from bandaspp where nombreBSPP='".$producto."')) as anchuraBS,bandaspp.anchuraLaminado as alturaEtiqueta from `tbpro".$procesoActual."` inner join bandaspp 
  inner join tbcodigosbarras on tbcodigosbarras.noop=`tbpro".$procesoActual."`.noop where bandaspp.IdBSPP='".$producto."' and `tbpro".$proceso."`.longitud!='' and tbcodigosbarras.baja=1 and `tbpro".$proceso."`.total=1 and `tbpro".$proceso."`.tipo='".$tipo."' and `tbpro".$proceso."`.producto='".$producto."' and `tbpro".$procesoActual."`.rollo_padre=0  order by `tbpro".$procesoActual."`.noop DESC");
}

$contador=0;//Se inicializa un contador,que,dependiendo de si el documento tendrá divisiones o no,este va a ser uno,para mostrar el registro unico,o se inicializará en 1,para no mostrar el ultimo registro,que es el registro padre de los modulos que se dividen
if($tipo=="1")
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
$limit=$consul->num_rows;


if ($consul->num_rows > 0)
  { 
    $pdf=new FPDF('P','mm','lbl4x1');
    $pdf->AddFont('3of9','','free3of9.php');
    
      if($limit>1)
{
  $contador=1;
}
else
{
  $contador=0;
}

$codePaquete_cdgpaquete = array();

for($i=1;$regProdDisco = $consul->fetch_object();$i++)
{
  echo $regProdDisco->longitud;
  $idLote=sendIdLote($regProdDisco->noop,$producto,$MySQLiconn);
  $producto=$regProdDisco->descripcionImpresion;
  $codePaquete_cdgpaquete[$i]['ancho']=$regProdDisco->anchuraBS;
  $codePaquete_cdgpaquete[$i]['longitud']=number_format($regProdDisco->longitud);
  $codigo=$regProdDisco->descripcionDisenio;
  $codePaquete_cdgpaquete[$i]['noop']=$regProdDisco->noop;
  $codePaquete_cdgpaquete[$i]['peso']=number_format($regProdDisco->peso);
  $codigoBarras=get_code($regProdDisco->noop,$tipo,$MySQLiconn);
  $codePaquete_cdgpaquete[$i]['codigo']=$codigoBarras;
}

    $nPaquetes =ceil($limit/2);
for ($item = 1; $item <= $nPaquetes; $item++)
  { $pdf->AddPage();
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(53,7,utf8_decode($codigo),0,0,'C');
      // Código de barras
    $pdf->SetY(7);
    $pdf->SetX(6);

    
    if(!empty($codePaquete_cdgpaquete[(($item*2)-1)]['codigo']))
    {
      $pdf->SetFont('3of9','',26);
      $pdf->Cell(39,6,'*'.$codePaquete_cdgpaquete[(($item*2)-1)]['codigo'].'*',0,0,'C'); 
    }
    else
    {
      $pdf->ln();
    }
    if(!empty($codePaquete_cdgpaquete[(($item*2))]['codigo']))
    {
        $pdf->SetFont('Arial','',7);
      $pdf->Cell(63,-7,utf8_decode($codigo),0,0,'C');
      $pdf->SetFont('3of9','',26);
      $pdf->Cell(-23,6,'',0,0,'C');
      $pdf->Cell(-17,6,'*'.$codePaquete_cdgpaquete[($item*2)]['codigo'].'*',0,1,'C');
    }
    else
    {
      $pdf->ln();
    }
    if(!empty($codePaquete_cdgpaquete[(($item*2)-1)]['codigo']))
    {
      $pdf->SetFont('Arial','',7); 
      $pdf->Cell(50,4,date('d').date('Gi').$codePaquete_cdgpaquete[(($item*2)-1)]['codigo'],0,0,'C');
      if(empty($codePaquete_cdgpaquete[(($item*2))]['codigo']))
    {
      $pdf->ln();
      $pdf->Cell(50,3,"cont.:".$codePaquete_cdgpaquete[(($item*2)-1)]['longitud'].'mts'.' / '.$codePaquete_cdgpaquete[(($item*2)-1)]['peso'].'kgs',0,1,'C'); 
      $pdf->SetFont('Arial','B',7); 
      $pdf->Cell(50,3,"NoOP:".$codePaquete_cdgpaquete[(($item*2)-1)]['noop'],0,1,'C'); 
      /*if($haslote==1)
      {
        $pdf->Cell(50,3,"lote:".$idLote,0,1,'C'); 
      }*/ 
    }
    }
    else
    {

      $pdf->ln();
    }
    if(!empty($codePaquete_cdgpaquete[(($item*2))]['codigo']))
    {
     
      $pdf->Cell(4,6,'',0,0,'C');
      $pdf->Cell(50,4,date('d').date('Gi').$codePaquete_cdgpaquete[($item*2)]['codigo'],0,1,'C');
      $pdf->Cell(50,3,"cont.:".$codePaquete_cdgpaquete[($item*2)]['longitud'].'mts'.' / '.$codePaquete_cdgpaquete[($item*2)]['peso'].'kgs',0,0,'C'); 
      $pdf->Cell(50,3,"cont.:".$codePaquete_cdgpaquete[(($item*2)-1)]['longitud'].'mts'.' / '.$codePaquete_cdgpaquete[(($item*2)-1)]['peso'].'kgs',0,1,'C');
      $pdf->SetFont('Arial','B',7); 
      $pdf->Cell(50,3,"NoOP:".$codePaquete_cdgpaquete[($item*2)]['noop'],0,0,'C'); 
      $pdf->Cell(50,3,"NoOP:".$codePaquete_cdgpaquete[(($item*2)-1)]['noop'],0,1,'C'); 
      /*if($haslote==1)
      {
        $pdf->Cell(50,3,"lote:".$lotesin,0,0,'C'); 
        $pdf->Cell(50,3,"lote:".$lotesin,0,1,'C'); 
      } */
      
    }

  }

    $pdf->Output(); 
  } else
  { echo '<html>
  <head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="../css/global.css" /> 
  </head>
  <body>
    <p>No se encontraron coincidencias.</p>
  </body>
</html>'; }
?>