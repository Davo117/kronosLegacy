<?php
ob_start();
session_start();
//Este documento no se utiliza
//require('../fpdf/fpdf.php');
require('../../fpdf/fpdflbl.php');
include('../Controlador_produccion/db_produccion.php');
include('../Controlador_produccion/functions.php');

$numero=$_GET['etiquetas'];
$proceso=explode("|", $numero,3);
    $producto=$proceso[1];
    $proceso=$proceso[0];
    $noProces=2;//Aqui es 1 porque esta etiqueta es para impresión,sin embargo se piensa hacer dinamico para que seleccione automacimnte el numero de proceso
$cons=$MySQLiconn->query("SELECT id,descripcionProceso as actual,(SELECT descripcionProceso from juegoprocesos where identificadorJuego=(select juegoprocesos from tipoproducto where 
                    alias=(select tipo from producto where 
                          descripcion=(select descripcionDisenio from impresiones where descripcionImpresion='".$producto."'))) and numeroProceso='$noProces'+1 and baja=1) as siguiente,numeroProceso from juegoprocesos where numeroProceso=".$noProces." and 
identificadorJuego=(select juegoprocesos from tipoproducto where 
                    alias=(select tipo from producto where 
                          descripcion=(select descripcionDisenio from impresiones where descripcionImpresion='".$producto."')))");

$consRo=$cons->fetch_array();
$idProceso=$consRo['id'];//El id del proceso actual
$numeroProceso=$consRo['numeroProceso'];//numero del proceso que se esta registrando
$procesoSiguiente=$consRo['siguiente'];//el proceso siguiente para colocarlo en la etiqueta
$procesoActual=$consRo['actual'];//la descripcion del proceso que se esta registrando


$consul=$MySQLiconn->query("SELECT `pro".$procesoActual."`.*,impresiones.id as newid,impresiones.descripcionDisenio,impresiones.descripcionImpresion,impresiones.anchoPelicula,impresiones.anchoEtiqueta,impresiones.espaciofusion,impresiones.alturaEtiqueta,impresiones.millaresPorRollo from `pro".$procesoActual."` inner join impresiones  where impresiones.descripcionImpresion='".$producto."' and `pro".$proceso."`.longitud!='' and `pro".$proceso."`.total=1 order by `pro".$procesoActual."`.noop DESC");

   $limit=count($regPackingList);
   if ($limit > 0)
  { $pdf=new FPDF('P','mm','lbl4x2'); 
    $pdf->AddFont('3of9','','free3of9.php');

if($limit>1)
{
  $contador=1;
}
else
{
  $contador=0;
}
   for($i=0;$i<count($regPackingList);$i++)
      { 
      $procesoSiguiente=$regPackingList[$i]['procesoSiguiente'];
      if($contador<$limit){
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

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L'); 
      $pdf->Cell(28,4,'Piezas aprox',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(20,4,number_format($regPackingList[$i]['unidades']*1000),0,1,'R'); 
     
      $pdf->SetY(-7);     
      $pdf->Cell(2,5,'',0,0,'L');      
      $pdf->SetFont('Arial','B',20);
      $pdf->Cell(68,5,'NoOP:'.$marcador.$regPackingList[$i]['noop'],0,1,'R');

      // Información
      $pdf->SetY(22);
      
      
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->Cell(15,4,$procesoSiguiente,0,1,'L');

      //Coloca estos datos dependiendo del proceso que siga

if($procesoSiguiente=="refilado")
{
  $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(28,4,'Ancho',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(18,4,($regPackingList->anchoEtiqueta*2)+$regPackingList->espaciofusion.' mm',0,1,'R');
      
     $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(28,4,'Bobinas totales',0,0,'L');
      $pdf->SetFont('Arial','B',12); 

      $pdf->Cell(18,4,round($regPackingList->anchuraBloque/$regPackingList->anchoPelicula),0,1,'R');
             
}
      else if($procesoSiguiente=="fusion")
      {
         $pdf->Cell(60,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(12,4,'Ancho',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(20,4,$regPackingList->anchoEtiqueta.' mm',0,1,'R');
      
      $pdf->Cell(60,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(12,4,'Empalme',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(20,4,$regPackingList->espaciofusion.' mm',0,1,'R');

      }
      else if($procesoSiguiente=="corte")
      {
           $pdf->Cell(60,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(12,4,'Altura',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(20,4,$regPackingList->alturaEtiqueta.' mm',0,1,'R');
      }
      else if($procesoSiguiente=="revision")
      {
         $pdf->Cell(60,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(12,4,'Producto para revision',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      }
      else if($procesoSiguiente=="")
      {
        $pdf->Cell(60,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(12,4,'Producto para empaque',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      }
     


     
      
      $pdf->Ln(8);

      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',8);
      $pdf->Cell(46,3,$regPackingList->fecha,0,1,'R');  

         // concatenacion de Codigo de barras
    $cadena="";
     $noop1=explode("-",$regPackingList->noop,2);
    if(count($noop1)>1)
    {
       $noop=$noop1[0];
     $cadena= $noop1[1];
     $cadena=explode("-",$cadena);
     $cadena=implode($cadena);
    }
    

       $codigoBarras=str_repeat("0",3 - strlen($regPackingList->newid)).$regPackingList->newid;
      $codigoBarras=$codigoBarras.str_repeat("0",3 - strlen($idProceso)).$idProceso;
      $codigoBarras=$codigoBarras.str_repeat("0",4 - strlen($cadena)).$cadena;
      $codigoBarras=$codigoBarras.str_repeat("0",2 - strlen($noProces)).$noProces;
      $codigoBarras=$codigoBarras.$noop;
 $divisiones=sendDivisions($tipo,$procesoSiguiente,$procesoActual,$producto,$oldCodigo,$regPackingList->noop);


      // Información     
      

      
      // Código de barras
       $pdf->SetY(4);
      $pdf->SetFont('3of9','',34);      
      $pdf->Cell(80,5,'',0,0,'R'); 
      $pdf->Cell(16,5,'*'.$codigoBarras.'*',0,1,'R');
      $pdf->Ln(1);
      $pdf->SetFont('Arial','',8); 
      $pdf->Cell(50,3,'',0,0,'R'); 
      $pdf->Cell(46,3,$codigoBarras,0,1,'C'); 
      

     registrarCdgBarras($codigoBarras,$divisiones,$regPackingList->noop); 

      
      

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
    <p class="message">Sin códigos disponibles</p>
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

