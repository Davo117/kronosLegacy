<?php
ob_start();
session_start();
//require('../fpdf/fpdf.php');
//require('../../fpdf/fpdflbl.php');
require('../../fpdf/code128.php');
include('../Controlador_produccion/db_produccion.php');
include('../Controlador_produccion/functions.php');
$numero=$_GET['etiquetas'];
$proceso=explode("|", $numero,3);
$producto=$proceso[1];
$proceso=$proceso[0];
   $SQL=$MySQLiconn->query("SELECT numeroProceso from juegoprocesos where identificadorJuego=(select juegoprocesos from tipoproducto where 
                    id=(select tipo from producto where 
                          ID=(select descripcionDisenio from impresiones where id='".$producto."'))) and descripcionProceso='".$proceso."'");
    $roa=$SQL->fetch_array();
    $noProces=$roa['numeroProceso'];
    $cons=$MySQLiconn->query("SELECT id,descripcionProceso as actual,(SELECT descripcionProceso from juegoprocesos where identificadorJuego=(select juegoprocesos from tipoproducto where 
      id=(select tipo from producto where 
      ID=(select descripcionDisenio from impresiones where id='".$producto."'))) and numeroProceso='$noProces'+1 and baja=1) as siguiente,numeroProceso from juegoprocesos where numeroProceso=".$noProces." and 
      identificadorJuego=(select juegoprocesos from tipoproducto where 
        id=(select tipo from producto where 
          ID=(select descripcionDisenio from impresiones where id='".$producto."')))");

    $consRo=$cons->fetch_array();
$idProceso=$consRo['id'];//El id del proceso actual
$numeroProceso=$consRo['numeroProceso'];//numero del proceso que se esta registrando
$procesoSiguiente=$consRo['siguiente'];//el proceso siguiente para colocarlo en la etiqueta
$procesoActual=$consRo['actual'];//la descripcion del proceso que se esta registrando

$consul=$MySQLiconn->query("SELECT producto,rollo as codigo from `tbpro".$procesoActual."` where total=1 and producto='$producto' and rollo_padre=0");

if ($consul->num_rows > 0)
  { //$pdf=new FPDF('P','mm','lbl4x1');
$pdf=new PDF_Code128();
$pdf->AddFont('3of9','','free3of9.php');

$nPaquetes=0;
//$regCodePaquete = $consul->fetch_object();

$nCodigos=$consul->num_rows;
          //Se agregan los codigos al arreglo
$codePaquete_cdgpaquete = array($nCodigos);

for($i=1;$regCodePaquete=$consul->fetch_object();$i++)
{
  $Pem=$MySQLiconn->query("SELECT alturaEtiqueta,anchoEtiqueta,codigoImpresion FROM impresiones WHERE id='".$regCodePaquete->producto."'");
  $ras=$Pem->fetch_array();
  $ancho=$ras['anchoEtiqueta'];
  $alto=$ras['alturaEtiqueta'];
  $codigo=$ras['codigoImpresion'];
  $codePaquete_cdgpaquete[$i] =$regCodePaquete->codigo; 
  $codePaquete_paquete[$i] = $regCodePaquete->codigo;


}

$nPaquetes =ceil($nCodigos/2);
for ($item = 1; $item <= $nPaquetes; $item++)
  {  $pdf->AddPage();
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(30,10,$ancho.'x'.$alto.' '.$codigo,0,0,'L');
      // Código de barras
    $pdf->SetY(10);
    $pdf->SetX(6);

    
    if(!empty($codePaquete_cdgpaquete[(($item*2)-1)]))
    {
      
      $pdf->Code128(39,6,$codePaquete_cdgpaquete[(($item*2)-1)],50,8);
    $pdf->SetXY(50,95);
    $pdf->SetFont('3of9','',30);
      $pdf->Cell(39,6,'*'.$codePaquete_cdgpaquete[(($item*2)-1)].'*',0,0,'C'); 
    }
    else
    {
      $pdf->ln();
    }
    if(!empty($codePaquete_cdgpaquete[(($item*2))]))
    {
        $pdf->SetFont('Arial','',10);
      $pdf->Cell(35,-10,$ancho.'x'.$alto.' '.$codigo,0,0,'R');
      $pdf->SetFont('3of9','',30);
      $pdf->Cell(-15,6,'',0,0,'C');
      $pdf->Cell(30,6,'*'.$codePaquete_cdgpaquete[($item*2)].'*',0,1,'C');
    }
    else
    {
      $pdf->ln();
    }
    if(!empty($codePaquete_paquete[(($item*2)-1)]))
    {
      $pdf->SetFont('Arial','',9); 
      $pdf->Cell(50,4,$codePaquete_paquete[(($item*2)-1)],0,0,'C');
    }
    else
    {

      $pdf->ln();
    }
    if(!empty($codePaquete_paquete[(($item*2))]))
    {
     
      $pdf->Cell(4,6,'',0,0,'C');
      $pdf->Cell(50,4,$codePaquete_paquete[($item*2)],0,1,'C');
    }
  }

  $pdf->Output();
}
else
  { echo '<html>
<head>
<meta charset="UTF-8" />
<link rel="stylesheet" type="text/css" href="../../css/global.css" /> 
</head>
<body>
<p  class="message">Sin códigos disponibles</p>
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
