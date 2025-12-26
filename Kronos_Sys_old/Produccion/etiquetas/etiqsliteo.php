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

$consul=$MySQLiconn->query("SELECT `pro".$procesoActual."`.*,impresiones.id as newid,impresiones.descripcionDisenio,impresiones.descripcionImpresion,impresiones.anchoPelicula,impresiones.anchoEtiqueta,impresiones.espaciofusion,impresiones.alturaEtiqueta,impresiones.millaresPorRollo from `pro".$procesoActual."` inner join impresiones  where impresiones.descripcionImpresion='".$producto."' and `pro".$proceso."`.longitud!='' and `pro".$proceso."`.total=1 and `pro".$proceso."`.tipo='".$tipo."' and `pro".$proceso."`.producto='".$producto."' order by `pro".$procesoActual."`.noop DESC");
    }

if($tipo=="BS")
{
    $cons=$MySQLiconn->query("call getNodoBS('".$producto."','".$noProces."')");
$consRo=$cons->fetch_array();
$procesoSiguiente=$consRo['siguiente'];//el proceso siguiente para colocarlo en la etiqueta
$idProceso=$consRo['id'];//El id del proceso actual
$procesoActual=$consRo['actual'];//la descripcion del proceso que se esta registrando
$MySQLiconn->next_result();


$consul=$MySQLiconn->query("SELECT distinct `pro".$procesoActual."`.*,bandaspp.idBSPP as newid,bandaspp.identificadorBS as descripcionDisenio,bandaspp.nombreBSPP as descripcionImpresion,(SELECT anchura from bandaseguridad where nombrebanda=(SELECT identificadorBS from bandaspp where nombreBSPP='".$producto."')) as anchuraBS,bandaspp.anchuraLaminado as alturaEtiqueta from `pro".$procesoActual."` inner join bandaspp 
  inner join codigosbarras on codigosbarras.noop=`pro".$procesoActual."`.noop where bandaspp.nombreBSPP='".$producto."' and `pro".$proceso."`.longitud!='' and codigosbarras.baja=1 and `pro".$proceso."`.total=1 and `pro".$proceso."`.tipo='".$tipo."' and `pro".$proceso."`.producto='".$producto."' and rollo_padre=0  order by `pro".$procesoActual."`.noop DESC");
}

$contador=0;//Se inicializa un contador,que,dependiendo de si el documennfskhto tendrá divisiones o no,este va a ser uno,para mostrar el registro unico,o se inicializará en 1,para no mostrar el ultimo registro,que es el registro padre de los modulos que se dividen
if($tipo=="BS")
{
  $decision="Metros aprox.";
  $marcador="BS.";
}
else if($tipo=="Termoencogible") 
{
  $decision="Piezas aprox.";
  $marcador="TR.";
}
else if($tipo=="Flexografia")
{
  $decision="Piezas aprox.";
  $marcador="FX.";
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
      $idLote=sendIdLote($regProdDisco->noop,$producto);
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
       $divisiones=0;
      if($procesoSiguiente=="revision")
      {
        $divisiones=1;
      }
      else
      {
       // $divisiones = ceil((($regPackingList->longitud/$regPackingList->alturaEtiqueta)/$regPackingList->millaresPorRollo));
      }    

//////////////////////////CodigoBarras///////////////
      $codigoBarras=generarCodigoBarras($idProceso,$noProces,$idLote,$divisiones,$regProdDisco->noop);

      /////////////////////////////////////////
      $pdf->SetY(-12);      
      $pdf->SetFont('Arial','',7);
      $pdf->Cell(98,3,$codigoBarras,0,1,'R');      
      $pdf->SetFont('Arial','B',9);
      $pdf->Cell(98,3,'noop '.$regProdDisco->noop,0,1,'R');

   
    
     

      // Código de barras
         //  $codigoBarras=generarCodigoBarras($idProceso,$cadena,$noProces,$idLote,$divisiones,$regPackingList->noop);//Llama ala funci´´on para generar el codigo
            /////

      $pdf->SetY(-17);
      $pdf->SetFont('3of9','',20);
      $pdf->Cell(98,5,'*'.$codigoBarras.'*',0,1,'R');

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