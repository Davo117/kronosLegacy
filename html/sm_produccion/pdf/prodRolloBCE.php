<?php

  include '../../datos/mysql.php';	
  require('../../fpdf/fpdflbl.php');

  if ($_GET['cdgimpresion'])
  { $link_mysqli = conectar();    
    $packingListSelect = $link_mysqli->query("
      SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) AS noop,
        pdtoproyecto.proyecto,
        pdtoimpresion.idimpresion,
        pdtoimpresion.impresion,
        pdtoimpresion.corte,
        (prodrollo.longitud/pdtoimpresion.corte) AS cantidad,
        pdtomezcla.idmezcla,
        pdtomezcla.mezcla,
        prodrollo.longitud,
        prodrollo.amplitud,
        prodrollo.peso,
        prodrollo.cdgrollo,
        alptempaque.noempaque,
        alptempaquer.nocontrol
      FROM prodlote,
        prodbobina,
        prodrollo,
        pdtomezcla,
        pdtoimpresion,
        pdtoproyecto,
        alptempaquer,
        alptempaque
      WHERE (prodlote.cdglote = prodbobina.cdglote AND
        prodbobina.cdgbobina = prodrollo.cdgbobina) AND 
       (prodlote.cdgmezcla = pdtomezcla.cdgmezcla AND
        pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion AND
        pdtoimpresion.cdgproyecto = pdtoproyecto.cdgproyecto) AND
        pdtoimpresion.cdgimpresion = '".$_GET['cdgimpresion']."' AND
        prodrollo.sttrollo = '".$_GET['sttrollo']."' AND        
       (alptempaque.cdgempaque = alptempaquer.cdgempaque AND
        alptempaquer.cdgrollo = prodrollo.cdgrollo) AND
        sttempaque = '1'
      ORDER BY alptempaquer.nocontrol"); } 

  if ($packingListSelect->num_rows > 0)
  { $pdf=new FPDF('P','mm','lbl4x2'); 
    $pdf->SetDisplayMode(real, continuous);
    $pdf->AddFont('3of9','','free3of9.php');
    
    while ($regPackingList = $packingListSelect->fetch_object())
    { $pdf->AddPage();

      $pdf->Cell(15,4,'',0,0,'L');      
      $pdf->SetFont('Arial','B',18);
      $pdf->Cell(33,4,'',0,1,'R'); 

      $pdf->Cell(2,6,'',0,0,'L');      
      $pdf->SetFont('Arial','B',18);
      $pdf->Cell(46,6,'P'.$regPackingList->noempaque,0,1,'R'); 

      $pdf->Cell(2,6,'',0,0,'L');      
      $pdf->SetFont('Arial','B',18);
      $pdf->Cell(46,6,'R'.$regPackingList->nocontrol,0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Longitud',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(28,4,number_format($regPackingList->longitud,2).' mts',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Ancho plano',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(28,4,$regPackingList->amplitud.' mm',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Peso',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(28,4,number_format($regPackingList->peso,3).' K',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Producto',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(28,4,$regPackingList->idimpresion,0,1,'R'); 

      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Corte',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(28,4,$regPackingList->corte.' mm',0,1,'R');
      
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Piezas aprox',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(28,4,number_format($regPackingList->cantidad*1000),0,1,'R'); 
      $pdf->Ln(1);

      $pdf->SetFont('3of9','',22);            
      $pdf->Cell(51,5,'*'.$regPackingList->cdgrollo.'*',0,1,'C');
      $pdf->SetFont('Arial','',8);       
      $pdf->Cell(51,3,$regPackingList->cdgrollo,0,1,'C');      

      //DUPLICADO

      $pdf->SetY(0);

      $pdf->Cell(66,4,'',0,0,'L');      
      $pdf->SetFont('Arial','B',18);
      $pdf->Cell(33,4,'',0,1,'R');       

      $pdf->Cell(53,6,'',0,0,'L');      
      $pdf->SetFont('Arial','B',18);
      $pdf->Cell(46,6,'P'.$regPackingList->noempaque,0,1,'R'); 

      $pdf->Cell(53,6,'',0,0,'L');      
      $pdf->SetFont('Arial','B',18);
      $pdf->Cell(46,6,'R'.$regPackingList->nocontrol,0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(53,4,'',0,0,'L');
      $pdf->Cell(18,4,'Longitud',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(28,4,number_format($regPackingList->longitud,2).' mts',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(53,4,'',0,0,'L');
      $pdf->Cell(18,4,'Ancho plano',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(28,4,$regPackingList->amplitud.' mm',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(53,4,'',0,0,'L');
      $pdf->Cell(18,4,'Peso',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(28,4,number_format($regPackingList->peso,3).' K',0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(53,4,'',0,0,'L');
      $pdf->Cell(18,4,'Producto',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(28,4,$regPackingList->idimpresion,0,1,'R'); 

      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(53,4,'',0,0,'L');
      $pdf->Cell(18,4,'Corte',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(28,4,$regPackingList->corte.' mm',0,1,'R');
      
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(53,4,'',0,0,'L');
      $pdf->Cell(18,4,'Piezas aprox',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(28,4,number_format($regPackingList->cantidad*1000),0,1,'R'); 
      $pdf->Ln(1);

      $pdf->SetFont('3of9','',22);  
      $pdf->Cell(51,4,'',0,0,'L');          
      $pdf->Cell(51,5,'*'.$regPackingList->cdgrollo.'*',0,1,'C');
      $pdf->SetFont('Arial','',8);
      $pdf->Cell(51,4,'',0,0,'L');       
      $pdf->Cell(51,3,$regPackingList->cdgrollo,0,1,'C');

      $pdf->SetY(0);
      $pdf->Line(50.5,2.5,51,4);
      $pdf->Line(50.5,5.5,51,7);
      $pdf->Line(50.5,8.5,51,10);
      $pdf->Line(50.5,11.5,51,13);
      $pdf->Line(50.5,14.5,51,16);
      $pdf->Line(50.5,17.5,51,19);
      $pdf->Line(50.5,20.5,51,22);
      $pdf->Line(50.5,23.5,51,25);
      $pdf->Line(50.5,26.5,51,28);
      $pdf->Line(50.5,29.5,51,31);
      $pdf->Line(50.5,32.5,51,34);
      $pdf->Line(50.5,35.5,51,37);
      $pdf->Line(50.5,38.5,51,40);
      $pdf->Line(50.5,41.5,51,43);
      $pdf->Line(50.5,44.5,51,46);
      $pdf->Line(50.5,47.5,51,49);      

      // Imagen LOGOTIPO
      /*if (file_exists('../../img_sistema/logo.jpg')==true) 
      { $pdf->Image('../../img_sistema/logo.jpg',2,2,14);
        $pdf->Image('../../img_sistema/logo.jpg',53,2,14); } //*/
    }

    $pdf->Output();
  } else
  { echo '<html>
  <head>    
    <link rel="stylesheet" type="text/css" href="../../css/global.css" media="screen" /> 
  </head>
  <body>
  </body>
</html>';}  		 
  
?>
