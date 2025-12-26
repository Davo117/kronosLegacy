<?php
ob_start();
session_start();
//require('../fpdf/fpdf.php');
require('../../fpdf/fpdflbl.php');
$welcome;
include('../Controlador_produccion/db_produccion.php');
include('../Controlador_produccion/functions.php');
//echo $_SESSION['etiquetasInd'];
$numero=$_GET['etiquetas'];
$proceso=explode("|", $numero,3);
    $producto=$proceso[1];
    $proceso=$proceso[0];

 $consul=$MySQLiconn->query("SELECT*from rollo where producto='$producto' and baja!=3");
$contador=0;//Se inicializa un contador

  if ($consul->num_rows > 0)
  { 
    $pdf=new FPDF('P','mm','lbl4x2'); 
    $pdf->AddFont('3of9','','free3of9.php');

    while ($regPackingList = $consul->fetch_object())
    {
      $codigo=$regPackingList->codigo;
      $pdf->AddPage();

    $pdf->Cell(10,3,'',0,1,'L');
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

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Rollos',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(30,4,$regPackingList->noElementos.'',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L'); 
      $pdf->Cell(28,4,'Piezas aprox',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(20,4,$regPackingList->piezas." Millares",0,1,'R'); 

$pdf->SetY(22);
      

       $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(22,4,'Longitud',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(10,4,$regPackingList->longitud." mts",0,1,'L');
       if($regPackingList->peso>0)
        {
           $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(28,4,'Peso',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(15,4,$regPackingList->peso." kgs",0,1,'L');
        }

    $pdf->Ln(4);

    $pdf->SetFont('3of9','',26);

     $pdf->Cell(98,6,'*'.$codigo.'*',0,1,'C');
     $pdf->Ln(1);
     $pdf->SetFont('Arial','',8); 
     $pdf->Cell(50,3,'',0,0,'R'); 
     $pdf->Cell(5,3,$codigo,0,1,'C'); 

    $pdf->Ln(2);

    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->Cell(90,4,'Temperatura de almacenamiento menor a 30 grados.',1,1,'C');

      // Imagen LOGOTIPO
      if (file_exists('../../pictures/logo-labro.jpg')==true) 
      { $pdf->Image('../../pictures/logo-labro.jpg',4,34,14); }       
  }
    $pdf->Output(); //$pdf->Output($alptEmpaque_impresion.' Empaque C'.$alptEmpaque_noempaque.' '.$_GET['cdgempaque'].'.pdf', 'I');
  } else
  { echo '<html>
  <head>    
    <link rel="stylesheet" type="text/css" href="../../css/global.css" media="screen" /> 
  </head>
  <p  class="message">No hay empaques disponibles</p>
  <body>
  </body>
  <script type="text/javascript">
<!--
     var ventana = window.self; 
     ventana.opener = window.self; 
     
//-->
</script>
</html>';}       
  
?>