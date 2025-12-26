<?php
require('../../fpdf/fpdflbl.php');
include("../../Database/SQLConnection.php");
include("../../Database/conexionphp.php");
error_reporting(0);



if(!empty($_GET['codigo']))
{
$SQL=$mysqli->query("SELECT codigoB,consec,metros,producto,porcion,fecha_alta,caja,datop from obelisco.pzcodes where codigoB='".$_GET['codigo']."'");

      /* Deshacemos el trabajo hecho por 'serialize' */
$nPaquetes=$SQL->num_rows;
   if ($nPaquetes > 0)
  {
    $row=$SQL->fetch_array();
    for($i=1;$i<=$row['consec'];$i++)
    {
      $sqlsr=sqlsrv_query($SQLconn,"SELECT CNOMBREPRODUCTO FROM admProductos where CIDPRODUCTO='".$row['producto']."'");
      $prod=sqlsrv_fetch_array($sqlsr,SQLSRV_FETCH_ASSOC);
      $arrayCod[$i]['codigo']=$row['codigoB'];
      $arrayCod[$i]['consec']=$row['consec'];
      $arrayCod[$i]['producto']=$prod['CNOMBREPRODUCTO'];
      $arrayCod[$i]['metros']=$row['metros'];
      $arrayCod[$i]['caja']=$row['caja'];
      //$arrayCod[$i]['kilos']=$row['porcion'];
      $provee=sqlsrv_query($SQLconn,"SELECT CRAZONSOCIAL as provee,CIDCLIENTEPROVEEDOR as codigo1 FROM ADMCLIENTES WHERE CIDCLIENTEPROVEEDOR='".$row['datop']."'");
      $row2=sqlsrv_fetch_array($provee,SQLSRV_FETCH_ASSOC);
      $arrayCod[$i]['datop']=$row2['provee'];
      $arrayCod[$i]['piezas']='Sin datos';
    }
   $pdf=new FPDF('P','mm','lbl4x1');
$pdf->AddFont('3of9','','free3of9.php');
    for ($item = 1; $item <= $row['consec']; $item++)
    { 
      echo $item;
      if($arrayCod[(($item*2)-1)]['producto']!="" or $arrayCod[(($item*2))]['producto']!="")
      {
         $pdf->AddPage();
      //Encabezado (Producto)
      $pdf->SetFont('Arial','',6);
      $pdf->Ln(0); 
      $pdf->Cell(50,6,$arrayCod[(($item*2)-1)]['producto'],0,0,'C');
      // Código de barras
      $pdf->SetY(10);
      $pdf->SetX(6);
      $pdf->SetFont('3of9','',24);
      $pdf->Cell(39,-3,'*'.$arrayCod[(($item*2)-1)]['codigo'].'*',0,0,'C'); 
     
     if(!empty($arrayCod[(($item*2))]['codigo']))
      {
      $pdf->SetFont('Arial','',6);
      $pdf->Ln(0); 
      //Encabezado (Producto)
      $pdf->SetX(28);
      $pdf->Cell(100,-14,$arrayCod[(($item*2))]['producto'],0,0,'C');
      $pdf->SetY(10);
      $pdf->SetX(28);
      $pdf->SetFont('3of9','',24);
      $pdf->Cell(100,-3,'*'.$arrayCod[(($item*2))]['codigo'].'*',0,1,'C');

      }
      else
      {
      $pdf->ln();
      }
      $pdf->SetFont('Arial','',7); 
      $pdf->Cell(50,12,$arrayCod[(($item*2)-1)]['codigo'],0,0,'C');
      $pdf->SetY(15);
      $pdf->SetX(3);
      $pdf->Cell(23,4,'Metros: '.number_format($arrayCod[(($item*2)-1)]['metros']),1,0,'C');
      $pdf->Cell(23,4,'Caja: '.number_format($arrayCod[(($item*2)-1)]['caja']),1,0,'C');
      $pdf->SetY(19);
      $pdf->SetX(3);
      $pdf->SetFont('Arial','',5.5);
      $pdf->Cell(46,4,'Prov.: '.$arrayCod[(($item*2)-1)]['datop'],1,0,'C');              
      //$pdf->Cell(23,4,'Kilos: '.number_format($arrayCod[(($item*2)-1)]['kilos']),1,0,'C');
      //$pdf->Cell(23,4,'Calibre: '."40",1,0,'C');

      if(!empty($arrayCod[(($item*2))]['codigo']))
      {
      $pdf->SetY(7);
      $pdf->SetX(47);
      $pdf->Cell(5,6,'',0,0,'C');
      $pdf->Cell(50,12,$arrayCod[(($item*2))]['codigo'],0,0,'C');
      $pdf->SetY(15);
      $pdf->SetX(55);
      $pdf->Cell(23,4,'Metros: '.number_format($arrayCod[(($item*2))]['metros']),1,0,'C');
      $pdf->Cell(23,4,'Caja: '.number_format($arrayCod[(($item*2))]['caja']),1,0,'C');
      $pdf->SetY(19);
      $pdf->SetX(55);
      $pdf->SetFont('Arial','',5.5);
     $pdf->Cell(46,4,'Prov.: '.$arrayCod[(($item*2)-1)]['datop'],1,0,'C');    
     // $pdf->Cell(23,4,'Kilos: '.number_format($arrayCod[(($item*2))]['kilos']),1,0,'C');
      //$pdf->Cell(23,4,'Calibre: '."40",1,0,'C');
      }
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
