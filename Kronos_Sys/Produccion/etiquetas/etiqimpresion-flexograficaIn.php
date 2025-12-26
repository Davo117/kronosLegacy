<?php
require('../../fpdf/fpdflbl.php');
/*include('../Controlador_produccion/db_produccion.php');
include('../Controlador_produccion/functions.php');
if(!empty($_GET['etiquetasInd']))
{
$numero=$_GET['etiquetasInd'];
$proceso=explode("|", $numero,3);
$oldCodigo=$proceso[0];
$arrayDatos=parsearCodigo($oldCodigo);
$arrayDatos=explode("|",$arrayDatos);
$producto=$arrayDatos[0];
$proceso=$arrayDatos[1];
$noProces=$arrayDatos[3]+1;
$noop=$arrayDatos[4];
$idLote=$arrayDatos[7];
$tipo=$arrayDatos[6];
$idProducto=$arrayDatos[8];
$idtipo=$arrayDatos[9];

//Divisiones te dice en cuantos pdf se va a dividir el documento,o bueno,cuantos registros se mostrarán
$MySQLiconn->query("SET @p0='".$producto."',@p1='".$noProces."'");
$cons=$MySQLiconn->query("call getNodoProceso(@p0,@p1)");
$consRo=$cons->fetch_array();
$idProceso=$consRo['id'];//El id del proceso actual
$numeroProceso=$consRo['numeroProceso'];//numero del proceso que se esta registrando
$procesoSiguiente=$consRo['siguiente'];//el proceso siguiente para colocarlo en la etiqueta
$procesoActual=$consRo['actual'];//la descripcion del proceso que se esta registrando
$procesoAnterior=$consRo['anterior'];

$MySQLiconn->next_result();
$consul=$MySQLiconn->query("SELECT lotes.*,impresiones.id as newid,`proimpresion-flexografica`.longitud as newLong,`proimpresion-flexografica`.peso as newPeso,`proimpresion-flexografica`.fecha as fecha,`proimpresion-flexografica`.unidades as newUnidades,impresiones.id,impresiones.descripcionDisenio,impresiones.descripcionImpresion,impresiones.anchoPelicula,impresiones.anchoEtiqueta,impresiones.espaciofusion from lotes inner join impresiones inner join `proimpresion-flexografica`  where lotes.noop like '$noop' and impresiones.descripcionImpresion='$producto' and `proimpresion-flexografica`.noop like '$noop' and lotes.tipo='$tipo' and `proimpresion-flexografica`.tipo='$tipo'");
*/
$regPackingList = base64_decode($_GET['etiquetasInd']);
$idtipo=$_GET['idtipo'];
    /* Deshacemos el trabajo hecho por 'serialize' */
$regPackingList = unserialize($regPackingList);
if($idtipo=="1")
{
  $decision="Metros aprox.";
  $marcador="BS.";
}
else
{
  $decision="Piezas aprox.";
  $marcador=$idtipo;
  $marcador=strtoupper($marcador).".";
}


 if (count($regPackingList) > 0)
  { $pdf=new FPDF('P','mm','lbl4x2'); 
    $pdf->AddFont('3of9','','free3of9.php');
    
   for($i=0;$i<count($regPackingList);$i++)
    {  $pdf->AddPage();

      $pdf->SetY(9.5);
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(48,4,'Producto',0,1,'L');
      $pdf->SetFont('Arial','B',11);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(98,4,utf8_decode($regPackingList[$i]['descripcionDisenio']),0,1,'L'); 
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(98,4,$regPackingList[$i]['descripcionImpresion'],0,1,'L');       
      
      $pdf->Ln(0.5);
      //Aqui empieza a concatenar datos para hacer el codigo de barras
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Informacion',0,1,'L');

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Longitud',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,number_format($regPackingList[$i]['newLong'],2).' mts',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Anchura',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,$regPackingList[$i]['anchuraBloque'].' mm',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Peso',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,number_format($regPackingList[$i]['newPeso'],3).' kgs',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L'); 
      $pdf->Cell(28,4,'Piezas',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(20,4,number_format($regPackingList[$i]['newUnidades']*1000),0,1,'R'); 

      $pdf->SetY(-7);     
      $pdf->Cell(2,5,'',0,0,'L');      
      $pdf->SetFont('Arial','B',20);
      $pdf->Cell(68,5,'NoOP:'.$marcador.$regPackingList[$i]['noop'],0,1,'R');
     

      // Información
      $pdf->SetY(22);
      
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->Cell(15,4,$regPackingList['procesoSiguiente'],0,1,'L');
      $procesoSiguiente=$regPackingList[$i]['procesoSiguiente'];
      include('armado_etiquetas.php');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(52,4,'',0,0,'L');
      $pdf->Cell(46,4,$regPackingList[$i]['tarima'].' | '.$regPackingList[$i]['numeroLote'],0,1,'C');
      $pdf->Cell(52,4,'',0,0,'L');
      $pdf->Cell(46,4,$regPackingList[$i]['referenciaLote'],0,1,'C');
      $pdf->SetY(4);
      $pdf->SetFont('3of9','',34);      
      $pdf->Cell(80,5,'',0,0,'R'); 
      $pdf->Cell(16,5,'*'.$regPackingList[$i]['codigoBarras'].'*',0,1,'R');
      $pdf->Ln(1);
      $pdf->SetFont('Arial','',8); 
      $pdf->Cell(50,3,'',0,0,'R'); 
      $pdf->Cell(46,3,$regPackingList[$i]['codigoBarras'],0,1,'C'); 
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

?>

