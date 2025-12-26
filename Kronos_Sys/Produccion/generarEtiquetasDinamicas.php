<?php
require('../fpdf/fpdflbl.php');
if(!empty($_GET['etiquetasInd']))
{
$regPackingList = base64_decode($_GET['etiquetasInd']);
$idtipo=$_GET['idtipo'];
$regPackingList = unserialize($regPackingList);
$proceso=$_GET['proceso'];
if($idtipo=="1")
{
  $decision="Metros aprox.";
  $marcador="BS.";
}
else
{
  $decision="Piezas aprox.";
  $marcador=substr($idtipo,0,3);
  $marcador=strtoupper($marcador).".";
}

$limit=count($regPackingList);
   if ($limit > 0)
  { $pdf=new FPDF('P','mm','lbl4x2'); 
    $pdf->AddFont('3of9','','free3of9.php');
    
   for($i=0;$i<count($regPackingList);$i++)
      { 
        $procesoSiguiente=$regPackingList[$i]['procesoSiguiente']; 
    /*   if($proceso=="programado")
    {
      $CAL=$MySQLiconn->query("SELECT unidades,longitud from `tbpro".$procesoActual."` where tipo='$idtipo' and noop='$regPackingList->noop'");
$set=$CAL->fetch_object();
    }*/

      $pdf->AddPage();

      $pdf->SetY(9.5);
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(48,4,'Producto',0,1,'L');
      $pdf->SetFont('Arial','B',11);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(98,4,$regPackingList[$i]['descripcionDisenio'],0,1,'L'); 
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(98,4,$regPackingList[$i]['descripcionImpresion'],0,1,'L');       
      
      $pdf->Ln(0.5);

      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Informacion',0,1,'L');

    if($procesoSiguiente!="")
  {
   $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Longitud',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,number_format($regPackingList[$i]['longitud'],2).' mts',0,1,'R'); 
    }

     
 if(!empty($regPackingList[$i]['amplitud']))
{
  $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Ancho plano',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,$regPackingList[$i]['amplitud'].' mm',0,1,'R'); 
}
if(!empty($regPackingList[$i]['referenciaLote']))
{
   $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(52,4,'',0,0,'L');
      $pdf->Cell(46,4,$regPackingList[$i]['tarima'].' | '.$regPackingList[$i]['numeroLote'],0,1,'C');
      $pdf->Cell(52,4,'',0,0,'L');
      $pdf->Cell(46,4,$regPackingList[$i]['referenciaLote'],0,1,'C');
}
if(!empty($regPackingList[$i]['newPeso']))
{
  if($procesoSiguiente!='')
  {
   $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Peso',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,number_format($regPackingList[$i]['newPeso'],3).' kgs',0,1,'R'); 
  }
}

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L'); 
      $pdf->Cell(28,4,$decision,0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      if($idtipo!="1")
      {
        $pdf->Cell(20,4,number_format($regPackingList[$i]['unidades']*1000,2),0,1,'R'); 
      }
      else
      {
        $pdf->Cell(20,4,number_format($regPackingList[$i]['unidades'],2),0,1,'R'); 
      }

     
      
     
      $pdf->SetY(-7);     
      $pdf->Cell(2,5,'',0,0,'L');      
      $pdf->SetFont('Arial','B',18);
      $pdf->Cell(68,5,'NoOP:'.$marcador.$regPackingList[$i]['noop'],0,1,'R');

      // Información
      $pdf->SetY(22);
      
      
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->Cell(15,4,$procesoSiguiente,0,1,'L');

      //Coloca estos datos dependiendo del proceso que siga

include('etiquetas/armado_etiquetas.php');


     
      
      $pdf->Ln(8);

      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',8);
      $pdf->Cell(46,3,$regPackingList[$i]['fecha'],0,1,'R');  

         // concatenacion de Codigo de barras
    
      //    $divisiones=sendDivisions($idtipo,$procesoSiguiente,$procesoActual,$idProducto,$oldCodigo,$regPackingList->noop);

 // Código de barras
        //   $codigoBarras=generarCodigoBarras($idProceso,$noProces,$idLote,$divisiones,$regPackingList->noop);//Llama ala funci´´on para generar el codigo
            /////
      
      // Código de barras
       $pdf->SetY(4);
      $pdf->SetFont('3of9','',34);      
      $pdf->Cell(80,5,'',0,0,'R'); 
      $pdf->Cell(16,5,'*'.$regPackingList[$i]['codigoBarras'].'*',0,1,'R');
      $pdf->Ln(1);
      $pdf->SetFont('Arial','',8); 
      $pdf->Cell(50,3,'',0,0,'R'); 
      $pdf->Cell(46,3,$regPackingList[$i]['codigoBarras'],0,1,'C'); 

      
      // Imagen LOGOTIPO
      if (file_exists('../pictures/logo-labro.jpg')==true) 
      { $pdf->Image('../pictures/logo-labro.jpg',2,2,14); }
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

