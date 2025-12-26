  <?php
  ob_start();
  session_start();
  //require('../fpdf/fpdf.php');
  require('../../fpdf/fpdflbl.php');
  include('../Controlador_produccion/db_produccion.php');
  include('../Controlador_produccion/functions.php');
  //echo $_SESSION['etiquetasInd'];
  if(!empty($_GET['etiquetasInd']))
  {
    $numero=$_GET['etiquetasInd'];



    $consul=$MySQLiconn->query("SELECT e.*,i.descripcionImpresion as producto,i.logproveedor,date(e.fechamov) as fecha,DATE(DATE_ADD(e.fechamov, INTERVAL 6 MONTH)) as caduca from rollo e inner join impresiones i
    on e.producto=i.id where e.codigo='".$numero."'");
  $contador=0;//Se inicializa un contador

  if ($consul->num_rows > 0)
  { 
    $pdf=new FPDF('P','mm','lbl4x2'); 
    $pdf->AddFont('3of9','','free3of9.php');

    while ($regPackingList = $consul->fetch_object())
    {
      $pdf->AddPage();

      $pdf->Cell(10,3,'',0,1,'L');
      $pdf->SetFont('Arial','B',12);
      $pdf->Cell(4,5,'',0,0,'L');
      $pdf->Cell(18,12,'Empaque ',0,0,'L');
      $pdf->SetFont('Arial','B',20);
      $pdf->Cell(40,12,''.$regPackingList->referencia,0,1,'R'); 

      $pdf->SetY(15);
      //$pdf->Cell(4,4,'',0,0,'L');
     // $pdf->SetFont('Arial','B',10);
     // $pdf->Cell(18,4,'Producto',0,1,'L');
      /*if(strlen($regPackingList->producto)<=40)
      {
        $pdf->SetFont('Arial','B',12);
      }
      else if(strlen($regPackingList->producto)>40)
      {
        $pdf->SetFont('Arial','B',9);
      }
      $pdf->Cell(4,5,'',0,0,'L');

      if(!empty($regPackingList->logproveedor))
      {
        $pdf->Cell(94,5,$regPackingList->producto.' /#'.$regPackingList->logproveedor,0,1,'L');
      }
      else
      {
        $pdf->Cell(94,5,$regPackingList->producto,0,1,'L');
      }

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'rollos',0,0,'L');
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
      $pdf->Cell(15,4,$regPackingList->longitud." mts",0,1,'L');
      if($regPackingList->peso>0)
      {
       $pdf->Cell(52,4,'',0,0,'L'); 
       $pdf->SetFont('Arial','',10); 
       $pdf->Cell(22,4,'Peso',0,0,'L');
       $pdf->SetFont('Arial','B',10); 
       $pdf->Cell(15,4,$regPackingList->peso." kgs",0,1,'L');
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
     $pdf->Cell(90,4,'Temperatura de almacenamiento menor a 30 grados.',1,1,'C');*/
      if(strlen($regPackingList->producto)<=40)
    {
    $pdf->SetFont('Arial','B',10);
  }
  else if(strlen($regPackingList->producto)>40)
  {
    $pdf->SetFont('Arial','B',8);
  }
    $pdf->Cell(4,5,'',0,0,'L');

if(!empty($regPackingList->logproveedor))
      {
        $pdf->Cell(94,5,$regPackingList->productoc.' /#'.$regPackingList->logproveedor,0,1,'L');
      }
      else
      {
        $pdf->Cell(94,5,$regPackingList->productoc,0,1,'L');
      }
    
    

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(4,4,'',0,0,'L');
      $pdf->Cell(10,4,'Rollos',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(40,4,$regPackingList->noElementos.'',0,0,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(4,4,'',0,0,'L'); 
      $pdf->Cell(20,4,'Fabricado:',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(20,4,$regPackingList->fecha,0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(4,4,'',0,0,'L'); 
      $pdf->Cell(28,4,'Piezas aprox',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(22,4,$regPackingList->piezas." Millares",0,0,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(4,4,'',0,0,'L'); 
      $pdf->Cell(20,4,'Caducidad:',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(20,4,$regPackingList->caduca,0,1,'R'); 
      

$pdf->SetY(21);
      

       $pdf->Cell(4,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(22,4,'Longitud',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(28,4,$regPackingList->longitud." mts",0,1,'R');
       if($regPackingList->peso>0)
        {
           $pdf->Cell(4,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(28,4,'Peso',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(22,4,$regPackingList->peso." kgs",0,1,'R');
        }

    $pdf->Ln(4);

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
