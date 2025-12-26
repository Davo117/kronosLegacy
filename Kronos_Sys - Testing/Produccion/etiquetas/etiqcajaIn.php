<?php
ob_start();
session_start();
//require('../fpdf/fpdf.php');
require('../../fpdf/fpdflbl.php');
include('../Controlador_produccion/db_produccion.php');
include('../Controlador_produccion/functions.php');
//echo $_SESSION['etiquetasInd'];

if(!empty('etiquetasInd'))
{
  $numero=$_GET['etiquetasInd'];

$consul=$MySQLiconn->query("SELECT*from caja where codigo='$numero'");//Esta consulta tiene longitud,carece de dinamicidad

$contador=0;//Se inicializa un contador

  if ($consul->num_rows > 0)
  { 
    $pdf=new FPDF('P','mm','lbl4x2'); 
    $pdf->AddFont('3of9','','free3of9.php');

    while ($regPackingList = $consul->fetch_object())
    {
      $pdf->AddPage();

    $pdf->Cell(15,3,'',0,1,'L');
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(4,5,'',0,0,'L');
    $pdf->Cell(18,12,'Empaque ',0,0,'L');
    $pdf->SetFont('Arial','B',36);
    $pdf->Cell(40,12,''.$regPackingList->referencia,0,1,'R'); 

    $pdf->SetY(15);
    //$pdf->Cell(4,4,'',0,0,'L');
   // $pdf->SetFont('Arial','B',10);
   // $pdf->Cell(18,4,'Producto',0,1,'L');
      if(strlen($regPackingList->producto)<=40)
    {
    $pdf->SetFont('Arial','B',12);
  }
  else if(strlen($regPackingList->producto)>40)
  {
    $pdf->SetFont('Arial','B',9);
  }
    $pdf->Cell(4,5,'',0,0,'L');
    $pdf->Cell(94,5,$regPackingList->producto,0,1,'L');
    $pdf->Ln(1);

    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(58,4,$regPackingList->noElementos.' Paquetes',0,1,'R');    
    
    //$pdf->Cell(4,4,'',0,0,'L');
    //$pdf->SetFont('Arial','B',9);
    //$pdf->Cell(18,4,'Peso ',0,0,'L');
    //$pdf->SetFont('Arial','B',11);
    //$pdf->Cell(40,4,number_format($regPackingList->peso,3).' Kgs',0,1,'R');   

    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(18,4,'Piezas aprox ',0,0,'L');
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(40,4,$regPackingList->piezas.'Millares',0,1,'R');
     if($regPackingList->peso>0)
    {
       $pdf->SetY(20);
      
      
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(70,6,'',0,0,'L'); 
      $pdf->Cell(15,6,$regPackingList->peso."kgs",0,1,'L');
      $pdf->Ln(1);
    }

    $pdf->Ln(4);

    $pdf->SetFont('3of9','',34);

     $pdf->Cell(98,6,'*'.$numero.'*',0,1,'C'); } 
     $pdf->Ln(1);
     $pdf->SetFont('Arial','',8); 
     $pdf->Cell(50,3,'',0,0,'R'); 
     $pdf->Cell(5,3,$numero,0,1,'C'); 

    $pdf->Ln(2);

    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->Cell(90,4,'Temperatura de almacenamiento menor a 30 grados.',1,1,'C');

      // Imagen LOGOTIPO
      if (file_exists('../../pictures/logo-labro.jpg')==true) 
      { $pdf->Image('../../pictures/logo-labro.jpg',4,34,14); }       
  
    $pdf->Output(); //$pdf->Output($alptEmpaque_impresion.' Empaque C'.$alptEmpaque_noempaque.' '.$_GET['cdgempaque'].'.pdf', 'I');
  } else
  { echo '<html>
  <head>    
    <link rel="stylesheet" type="text/css" href="../../css/global.css" media="screen" /> 
  </head>
  <body>
  </body>
</html>';}       
  


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
?>
