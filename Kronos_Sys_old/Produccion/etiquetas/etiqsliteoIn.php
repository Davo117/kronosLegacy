<?php
ob_start();
session_start();
//require('../fpdf/fpdf.php');
require('../../fpdf/fpdflbl.php');
$welcome;
include('../Controlador_produccion/db_produccion.php');
include('../Controlador_produccion/functions.php');
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

$OPS=$MySQLiconn->query("call getNodoBS('$producto','$noProces')");
$consRo=$OPS->fetch_array();
$idProceso=$consRo['id'];//El id del proceso actual
$numeroProceso=$consRo['numeroProceso'];//numero del proceso que se esta registrando
$procesoSiguiente=$consRo['siguiente'];//el proceso siguiente para colocarlo en la etiqueta
$procesoActual=$consRo['actual'];//la descripcion del proceso que se esta registrando

$MySQLiconn->next_result();
$consul=$MySQLiconn->query("SELECT `pro".$procesoActual."`.*,bandaspp.idBSPP  as newid,bandaspp.identificadorBS as descripcionDisenio,(SELECT anchura from bandaseguridad where nombrebanda=(SELECT identificadorBS from bandaspp where nombreBSPP='$producto')) as anchuraBS, bandaspp.nombreBSPP as descripcionImpresion,bandaspp.anchuraLaminado as ancho  from `pro".$procesoActual."` inner join bandaspp inner join codigosbarras on `pro".$procesoActual."`.lote=codigosbarras.codigo  where `pro".$procesoActual."`.noop like '$noop-%' and codigosbarras.lote='$lote'  and `pro".$procesoActual."`.total=1 and  bandaspp.nombreBSPP='$producto'  order by `pro".$procesoActual."`.noop DESC");//Esta consulta tiene longitud,carece de dinamicidad
$contador=0;//Se inicializa un contador,que,dependiendo de si el documento tendrá divisiones o no,este va a ser uno,para mostrar el registro unico,o se inicializará en 1,para no mostrar el ultimo registro,que es el registro padre de los modulos que se dividen
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
}
else
{
echo '<html>
  <head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="../css/global.css" /> 
  </head>
  <body>
    <p>Tiempo de espera agotado.</p>
  </body>
</html>'; 
}
?>