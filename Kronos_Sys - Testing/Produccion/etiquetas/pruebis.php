<?php
require('../../fpdf/fpdflbl.php');
include('../Controlador_produccion/db_produccion.php');
if(!empty($_GET['codePaquete_cdgpaquete']))
{
$codePaquete_cdgpaquete = base64_decode($_GET['codePaquete_cdgpaquete']);
$codePaquete_paquete = base64_decode($_GET['codePaquete_paquete']);
      /* Deshacemos el trabajo hecho por 'serialize' */
$codePaquete_cdgpaquete = unserialize($codePaquete_cdgpaquete);
$codePaquete_paquete = unserialize($codePaquete_paquete);
$nPaquetes=$_GET['nPaquetes'];
$limit=count($codePaquete_paquete);
   if ($limit > 0)
  { $pdf=new FPDF('P','mm','lbl4x1');
$pdf->AddFont('3of9','','free3of9.php');
    for ($item = 1; $item <= $nPaquetes; $item++)
    { 
      $squir=$MySQLiconn->query("SELECT millaresPorPaquete as piezas,(SELECT referencia from ensambleempaques WHERE codigo='".$codePaquete_cdgpaquete[(($item*2)-1)]."') as referencia from impresiones where codigoImpresion='".$_GET['codigo']."'");

      $rowi=$squir->fetch_assoc();
  if(empty($rowi['referencia']))
  {
    $codePaquete_cdgpaquete[(($item*2)-1)] =$codePaquete_cdgpaquete[(($item*2)-1)];
    $mpp=$rowi['piezas']*1000;
  }
  else
  {
    $codePaquete_paquete[(($item*2)-1)]="";
    $codePaquete_cdgpaquete[(($item*2)-1)] ="";
  }

  $squor=$MySQLiconn->query("SELECT millaresPorPaquete as piezas,(SELECT referencia from ensambleempaques WHERE codigo='".$codePaquete_cdgpaquete[(($item*2))]."') as referencia from impresiones where codigoImpresion='".$_GET['codigo']."'");
      $rowe=$squor->fetch_assoc();
  if(empty($rowe['referencia']))
  {
    $codePaquete_cdgpaquete[($item*2)] =$codePaquete_cdgpaquete[($item*2)];
    $mpp=$rowe['piezas']*1000;
  }
  else
  {
    $codePaquete_cdgpaquete[($item*2)] ="";
    $codePaquete_paquete[($item*2)]="";
  }
      $pdf->AddPage();
      $pdf->SetFont('Arial','',9);
      $it=($item*2)-1;
      $pdf->Cell(53,10,$_GET['medida'].' '.$_GET['codigo'].' ['.$it.']',0,0,'C');
      // Código de barras
      $pdf->SetY(10);
      $pdf->SetX(6);
      $pdf->SetFont('3of9','',28);
      $pdf->Cell(39,6,'*'.$codePaquete_cdgpaquete[(($item*2)-1)].'*',0,0,'C'); 
     
     if(!empty($codePaquete_cdgpaquete[(($item*2))]))
      {
      $pdf->SetFont('Arial','',9);
      $pdf->Cell(53,-10,$_GET['medida'].' '.$_GET['codigo'].' ['.$item*'2'.']',0,0,'C');
      $pdf->SetFont('3of9','',28);
      $pdf->Cell(-15,6,'',0,0,'C');
      $pdf->Cell(-9,6,'*'.$codePaquete_cdgpaquete[($item*2)].'*',0,1,'C');

      }
      else
      {
      $pdf->ln();
      }
      $pdf->SetFont('Arial','',9); 
      $pdf->Cell(50,4,date('d').date('Gi').$codePaquete_paquete[(($item*2)-1)],0,0,'C');
        if(empty($codePaquete_paquete[(($item*2))]))
    {
      $pdf->ln();
      $pdf->Cell(25,3,"cont.:".$mpp."pzs",0,0,'C'); 
    }

      if(!empty($codePaquete_paquete[(($item*2))]))
      {
     
      $pdf->Cell(5,6,'',0,0,'C');
      $pdf->Cell(50,4,date('d').date('Gi').$codePaquete_paquete[($item*2)],0,1,'C');
      $pdf->Cell(25,3,"cont.:".$mpp."pzs",0,0,'C'); 
      $pdf->Cell(85,3,"cont.:".$mpp."pzs",0,0,'C'); 
      }
    }
/*echo '<html>
  <head>    
    <link rel="stylesheet" type="text/css" href="../../css/global.css" media="screen" /> 
  </head>
  <script type="text/javascript">
<!--
     var ventana = window.self; 
     ventana.opener = window.self; 
     setTimeout("window.close()", 1500);
//-->
</script>
</html>';*/
  $pdf->Output();
  
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
</html>'; 
}
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
