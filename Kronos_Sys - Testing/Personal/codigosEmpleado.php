<?php
  include ("../Database/db.php"); 
ob_start();
//require('../fpdf/fpdf.php');
require('../fpdf/fpdflbl.php');
$consul = $MySQLiconn->query("SELECT * FROM empleado where baja=1  ORDER BY ID DESC;");

if ($consul->num_rows > 0)
  { $pdf=new FPDF('P','mm','lbl4x1');
$pdf->AddFont('3of9','','free3of9.php');

$nPaquetes=0;
//$regCodePaquete = $consul->fetch_object();

$nCodigos=$consul->num_rows;
          //Se agregan los codigos al arreglo
$codePaquete_cdgpaquete = array($nCodigos);
for($i=1;$regCodePaquete=$consul->fetch_object();$i++)
{
  $codePaquete_cdgpaquete[$i] =$regCodePaquete->numemple; 
  $codePaquete_paquete[$i] = $regCodePaquete->Nombre.' '.$regCodePaquete->apellido;
  $codePaquete_paque[$i] = $regCodePaquete->numemple;
}

$nPaquetes =ceil($nCodigos/2);
for ($item = 1; $item <= $nPaquetes; $item++)
  { $pdf->AddPage();
      // Código de barras
    $pdf->SetFont('Arial','',10);
      $pdf->Cell(30,10,$codePaquete_paque[(($item*2)-1)],0,0,'L');
    $pdf->SetY(10);
    $pdf->SetX(6);

    
    if(!empty($codePaquete_cdgpaquete[(($item*2)-1)]))
    {
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
      $pdf->Cell(20,-10,$codePaquete_paque[($item*2)],0,0,'R');
      $pdf->SetFont('3of9','',30);
      $pdf->Cell(1,6,'',0,0,'C');
      $pdf->Cell(30,6,'*'.$codePaquete_cdgpaquete[($item*2)].'*',0,1,'C');
    }
    else
    {
      $pdf->ln();
    }
    if(!empty($codePaquete_paquete[(($item*2)-1)]))
    {
      $pdf->SetFont('Arial','',9); 
      $pdf->Cell(50,4,utf8_decode($codePaquete_paquete[(($item*2)-1)]),0,0,'C');
    }
    else
    {

      $pdf->ln();
    }
    if(!empty($codePaquete_paquete[(($item*2))]))
    {
     
      $pdf->Cell(4,6,'',0,0,'C');
      $pdf->Cell(50,4,utf8_decode($codePaquete_paquete[($item*2)]),0,1,'C');
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