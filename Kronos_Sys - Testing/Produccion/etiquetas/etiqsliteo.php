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
  { $pdf=new FPDF('P','mm','lbl4x0.5'); 
    $pdf->AddFont('3of9','','free3of9.php');
    
      if($limit>1)
{
  $contador=1;
}
else
{
  $contador=0;
}

    while ($regProdDisco = $consul->fetch_object())
    { $pdf->AddPage();
      $idLote=sendIdLote($regProdDisco->noop,$producto,$MySQLiconn);
      $pdf->SetY(-18);
      $pdf->SetFont('Arial','',8);
      $pdf->Cell(2,3,'',0,0,'L');
      $pdf->Cell(12,3,'Producto',0,1,'L');
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(2,3,'',0,0,'L');
      $pdf->Cell(80,3,$regProdDisco->descripcionDisenio,0,1,'L');
       $pdf->SetY(7);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(2,3,'',0,0,'L');
      $pdf->Cell(80,3,$regProdDisco->descripcionImpresion,0,1,'L');


      $pdf->SetFont('Arial','',8);
      $pdf->Cell(2,3,'',0,0,'L');
      $pdf->Cell(15,3,'Medida',0,0,'R');
      $pdf->Cell(20,3,'Metros',0,0,'R');
      $pdf->Cell(15,3,'Kilos',0,1,'R');

      $pdf->SetFont('Arial','B',9);
      $pdf->Cell(2,3,'',0,0,'L');
      $pdf->Cell(15,3,$regProdDisco->anchuraBS.'mm',0,0,'R');
      $pdf->Cell(20,3,number_format($regProdDisco->longitud,2,'.',','),0,0,'R');
      $pdf->Cell(15,3,number_format($regProdDisco->peso,3,'.',','),0,1,'R');    
     /*  $divisiones=0;
      if($procesoSiguiente=="revision")
      {
        $divisiones=1;
      }
      else
      {
       // $divisiones = ceil((($regPackingList->longitud/$regPackingList->alturaEtiqueta)/$regPackingList->millaresPorRollo));
      }    */

//////////////////////////CodigoBarras///////////////
      $codigoBarras=get_code($regProdDisco->noop,$tipo,$MySQLiconn);

      /////////////////////////////////////////
      $pdf->SetY(-8);      
      $pdf->SetFont('Arial','',7);
      $pdf->Cell(98,3,$codigoBarras,0,1,'R');      
      $pdf->SetFont('Arial','B',9);
      $pdf->Cell(98,3,'noop '.$regProdDisco->noop,0,1,'R');

   
    
     

      // Código de barras
         //  $codigoBarras=generarCodigoBarras($idProceso,$cadena,$noProces,$idLote,$divisiones,$regPackingList->noop);//Llama ala funci´´on para generar el codigo
            /////

      $pdf->SetY(-16);
      $pdf->SetFont('3of9','',31);
      $pdf->Cell(104,5,'*'.$codigoBarras.'*',0,1,'R');

      $contador++;
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