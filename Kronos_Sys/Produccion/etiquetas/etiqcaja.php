<?php
ob_start();
session_start();
//require('../fpdf/fpdf.php');
require('../../fpdf/fpdflbl.php');
$welcome;
include('../Controlador_produccion/db_produccion.php');
include('../Controlador_produccion/functions.php');
//echo $_SESSION['etiquetasInd'];
$numero=$_SESSION['etiquetas'];
$proceso=explode("|", $numero,3);
    $producto=$proceso[1];
    $proceso=$proceso[0];


$consul=$MySQLiconn->query("SELECT e.*, i.descripcionImpresion as prod,i.haslote,i.logproveedor,date(e.fechamov) as fecha,DATE(DATE_ADD(e.fechamov, INTERVAL 6 MONTH)) as caduca from caja e inner join impresiones i
    on e.producto=i.id  where e.producto='$producto' and e.baja!=3 order by e.id desc");

  if ($consul->num_rows > 0)
  { 
    $pdf=new FPDF('P','mm','lbl4x2'); 
    $pdf->AddFont('3of9','','free3of9.php');

    while ($regPackingList = $consul->fetch_object())
    {

      $pdf->AddPage();

    $pdf->Cell(15,3,'',0,1,'L');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(4,5,'',0,0,'L');
    $pdf->Cell(18,5,'Empaque ',0,0,'L');
    $pdf->SetFont('Arial','B',20);
    $pdf->Cell(32,5,''.$regPackingList->referencia,0,1,'R'); 

    //$pdf->Cell(4,4,'',0,0,'L');
   // $pdf->SetFont('Arial','B',10);
   // $pdf->Cell(18,4,'Producto',0,1,'L');
      if(strlen($regPackingList->prod)<=40)
    {
    $pdf->SetFont('Arial','B',10);
  }
  else if(strlen($regPackingList->prod)>40)
  {
    $pdf->SetFont('Arial','B',8);
  }
    $pdf->Cell(4,5,'',0,0,'L');
    if(!empty($regPackingList->logproveedor))
    {
      $pdf->Cell(94,5,$regPackingList->prod.' /#'.$regPackingList->logproveedor,0,1,'L');
    }
    else
    {
      $pdf->Cell(94,5,$regPackingList->prod,0,1,'L');
    }
    
    $pdf->Ln(1);

    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(28,4,'Contenido',0,0,'L');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(22,4,$regPackingList->noElementos.' paquetes',0,0,'R');    
    
    $pdf->SetFont('Arial','',10);
      $pdf->Cell(4,4,'',0,0,'L'); 
      $pdf->Cell(20,4,'Fabricado:',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(20,4,$regPackingList->fecha,0,1,'R'); 
    //$pdf->Cell(4,4,'',0,0,'L');
    //$pdf->SetFont('Arial','B',9);
    //$pdf->Cell(18,4,'Peso ',0,0,'L');
    //$pdf->SetFont('Arial','B',11);
    //$pdf->Cell(40,4,number_format($regPackingList->peso,3).' Kgs',0,1,'R');   

    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(18,4,'Piezas aprox ',0,0,'L');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(32,4,$regPackingList->piezas.' millares',0,0,'R');

    $pdf->SetFont('Arial','',10);
      $pdf->Cell(4,4,'',0,0,'L'); 
      $pdf->Cell(20,4,'Caducidad:',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(20,4,$regPackingList->caduca,0,1,'R'); 
    if($regPackingList->peso>0)
    {
       $pdf->SetY(21);
      
      
      
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(4,6,'',0,0,'L'); 
      $pdf->Cell(15,6,'Peso',0,0,'L');
      $pdf->SetFont('Arial','B',10);  
      $pdf->Cell(12,6,$regPackingList->peso." kgs",0,0,'R');
    }
    if($regPackingList->haslote==1)
    {
      $SECU=$MySQLiconn->query("SELECT e.codigo,(select lote from codigosbarras where codigo=e.codigo) as lote from caja ka inner join ensambleempaques e on e.codEmpaque=ka.codigo WHERE ka.codigo='".$regPackingList->codigo."' group by lote limit 1");
      $rowe=$SECU->fetch_object();
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(4,6,'',0,0,'L'); 
      $pdf->Cell(35,6,'Lote:',0,0,'L');
      $pdf->SetFont('Arial','B',9); 
      $pdf->Cell(20,6,$rowe->lote,0,1,'R'); 
      $pdf->Ln(4);
    }
    else
    {
      $pdf->Ln(8);
    }
    

    
$codigo=$regPackingList->codigo;
    $pdf->SetFont('3of9','',34);

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
}
  else
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
     setTimeout("window.close()", 1500);
//-->
</script>
</html>';}       
  
?>
