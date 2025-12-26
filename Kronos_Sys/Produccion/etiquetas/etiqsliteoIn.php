<?php
require('../../fpdf/fpdflbl.php');
if(!empty($_GET['etiquetasInd']))
{
  $regPackingList = base64_decode($_GET['etiquetasInd']);
  $idtipo=$_GET['idtipo'];
      /* Deshacemos el trabajo hecho por 'serialize' */
  $regPackingList = unserialize($regPackingList);
/*
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

$OPS=$MySQLiconn->query("call getNodoBS('$producto','$noProces')");
$consRo=$OPS->fetch_array();
$idProceso=$consRo['id'];//El id del proceso actual
$numeroProceso=$consRo['numeroProceso'];//numero del proceso que se esta registrando
$procesoSiguiente=$consRo['siguiente'];//el proceso siguiente para colocarlo en la etiqueta
$procesoActual=$consRo['actual'];//la descripcion del proceso que se esta registrando
$MySQLiconn->next_result();
$consul=$MySQLiconn->query("SELECT `pro".$procesoActual."`.*,bandaspp.idBSPP  as newid,bandaspp.identificadorBS as descripcionDisenio,(SELECT anchura from bandaseguridad where nombrebanda=(SELECT identificadorBS from bandaspp where nombreBSPP='$producto')) as anchuraBS, bandaspp.nombreBSPP as descripcionImpresion,bandaspp.anchuraLaminado as ancho  from `pro".$procesoActual."` inner join bandaspp inner join codigosbarras on `pro".$procesoActual."`.lote=codigosbarras.codigo  where `pro".$procesoActual."`.noop like '$noop-%' and codigosbarras.lote='$lote'  and `pro".$procesoActual."`.total=1 and  bandaspp.nombreBSPP='$producto'  order by `pro".$procesoActual."`.noop DESC");//Esta consulta tiene longitud,carece de dinamicidad
$contador=0;//Se inicializa un contador,que,dependiendo de si el documento tendrá divisiones o no,este va a ser uno,para mostrar el registro unico,o se inicializará en 1,para no mostrar el ultimo registro,que es el registro padre de los modulos que se dividen*/
if($idtipo=="1")
{
  $decision="Metros aprox.";
  $marcador="BS.";
}
else
{
  $decision="Piezas aprox.";
  $marcador=$idtipo;
}

$limit=count($regPackingList);


 if ($limit > 0)
  { 
    if($limit%2!=0)
    {
      $limit=$limit+1;
    }
    
    $pdf=new FPDF('P','mm','lbl4x1');
    $pdf->AddFont('3of9','','free3of9.php');

   $nPaquetes=$limit/2;
for ($item = 1; $item <= $nPaquetes; $item++)
  { $pdf->AddPage();
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(53,7,utf8_decode($regPackingList[1]['descripcionImpresion']),0,0,'C');
      // Código de barras
    $pdf->SetY(7);
    $pdf->SetX(6);

    
    if(!empty($regPackingList[(($item*2)-1)]['codigoBarras']))
    {
      $pdf->SetFont('3of9','',26);
      $pdf->Cell(39,6,'*'.$regPackingList[(($item*2)-1)]['codigoBarras'].'*',0,0,'C'); 
    }
    else
    {
      $pdf->ln();
    }
    if(!empty($regPackingList[(($item*2))]['codigoBarras']))
    {
        $pdf->SetFont('Arial','',7);
      $pdf->Cell(63,-7,utf8_decode($regPackingList[1]['descripcionImpresion']),0,0,'C');
      $pdf->SetFont('3of9','',26);
      $pdf->Cell(-23,6,'',0,0,'C');
      $pdf->Cell(-17,6,'*'.$regPackingList[($item*2)]['codigoBarras'].'*',0,1,'C');
    }
    else
    {
      $pdf->ln();
    }
    if(!empty($regPackingList[(($item*2)-1)]['codigoBarras']))
    {
      $pdf->SetFont('Arial','',7); 
      $pdf->Cell(50,4,date('d').date('Gi').$regPackingList[(($item*2)-1)]['codigoBarras'],0,0,'C');
      if(empty($regPackingList[(($item*2))]['codigoBarras']))
    {
      $pdf->ln();
      $pdf->Cell(50,3,"cont.:".$regPackingList[(($item*2)-1)]['longitud'].'mts'.' / '.$regPackingList[(($item*2)-1)]['peso'].'kgs',0,1,'C'); 
      $pdf->SetFont('Arial','B',7); 
      $pdf->Cell(50,3,"NoOP:".$regPackingList[(($item*2)-1)]['noop'],0,1,'C'); 
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
    if(!empty($regPackingList[(($item*2))]['codigoBarras']))
    {
     
      $pdf->Cell(4,6,'',0,0,'C');
      $pdf->Cell(50,4,date('d').date('Gi').$regPackingList[($item*2)]['codigoBarras'],0,1,'C');
      $pdf->Cell(50,3,"cont.:".$regPackingList[($item*2)]['longitud'].'mts'.' / '.$regPackingList[($item*2)]['peso'].'kgs',0,0,'C'); 
      $pdf->Cell(50,3,"cont.:".$regPackingList[(($item*2)-1)]['longitud'].'mts'.' / '.$regPackingList[(($item*2)-1)]['peso'].'kgs',0,1,'C');
      $pdf->SetFont('Arial','B',7); 
      $pdf->Cell(50,3,"NoOP:".$regPackingList[($item*2)]['noop'],0,0,'C'); 
      $pdf->Cell(50,3,"NoOP:".$regPackingList[(($item*2)-1)]['noop'],0,1,'C'); 
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