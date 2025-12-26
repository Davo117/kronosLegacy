<?php

  include '../../datos/mysql.php';	
  require('../../fpdf/fpdflbl.php');

  if ($_GET['cdgempaque'])
  { $link_mysqli = conectar();    
    $packingListSelect = $link_mysqli->query("
      SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) AS noop,
        pdtoproyecto.proyecto,
        pdtoimpresion.idimpresion,
        pdtoimpresion.impresion,
        pdtoimpresion.corte,
        pdtoimpresion.cdgproducto,
        pdtomezcla.cdgmezcla,
        (prodrollo.longitud/pdtoimpresion.corte) AS cantidad,
        prodrollo.longitud,
        prodrollo.amplitud,
        prodrollo.peso,
        prodrollo.cdgrollo,
        alptempaque.noempaque,
        alptempaque.fchempaque,
        alptempaque.peso AS pbruto,
        alptempaquer.nocontrol
      FROM prodlote,
        prodbobina,
        prodrollo,
        pdtoimpresion,
        pdtoproyecto,
        pdtomezcla,
        alptempaquer,
        alptempaque
      WHERE (prodlote.cdglote = prodbobina.cdglote AND
        prodbobina.cdgbobina = prodrollo.cdgbobina) AND
       (prodlote.cdgmezcla = pdtomezcla.cdgmezcla) AND
       (prodrollo.cdgproducto = pdtoimpresion.cdgimpresion AND
        pdtoimpresion.cdgproyecto = pdtoproyecto.cdgproyecto) AND
        prodrollo.cdgempaque = '".$_GET['cdgempaque']."' AND
        prodrollo.cdgrollo = alptempaquer.cdgrollo AND
        alptempaquer.cdgempaque = alptempaque.cdgempaque AND
        sttempaque = '1'
      ORDER BY alptempaquer.nocontrol"); } 

  if ($packingListSelect->num_rows > 0)
  { $pdf=new FPDF('P','mm','lbl4x2'); 
    $pdf->SetDisplayMode(real, continuous);
    $pdf->AddFont('3of9','','free3of9.php');
    
    while ($regPackingList = $packingListSelect->fetch_object())
    { if ($alptEmpaque_noempaque == '')
      { $alptEmpaque_noempaque = $regPackingList->noempaque; }

      if ($alptEmpaque_idimpresion == '')
      { $alptEmpaque_idimpresion = $regPackingList->idimpresion; }

      if ($alptEmpaque_impresion == '')
      { $alptEmpaque_impresion = $regPackingList->impresion; }

      if ($alptEmpaque_pbruto == '')
      { $alptEmpaque_pbruto = $regPackingList->pbruto; }

      if ($alptEmpaque_fchempaque == '')
      { $alptEmpaque_fchempaque = $regPackingList->fchempaque; }    

      $alptEmpaque_longitud += $regPackingList->longitud;
      $alptEmpaque_peso += $regPackingList->peso;
      $alptEmpaque_piezas += $regPackingList->cantidad;

      $pdf->AddPage();

      $pdf->Cell(15,3,'',0,0,'L');      
      $pdf->SetFont('Arial','B',18);
      $pdf->Cell(33,3,'',0,1,'R'); 

      $pdf->Cell(2,6,'',0,0,'L');      
      $pdf->SetFont('Arial','B',18);
      $pdf->Cell(46,6,'Q'.$regPackingList->noempaque,0,1,'R'); 

      $pdf->Cell(2,6,'',0,0,'L');      
      $pdf->SetFont('Arial','B',18);
      $pdf->Cell(46,6,'R'.$regPackingList->nocontrol,0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Lote',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(28,4,$regPackingList->cdgmezcla,0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'ITEM',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(28,4,$regPackingList->cdgproducto,0,1,'R'); 

      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(51,4,'Sabor/Presentacion',0,1,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(46,4,$regPackingList->impresion,0,1,'R');

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Longitud',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(28,4,number_format($regPackingList->longitud,2).' mts',0,1,'R'); 

      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(18,4,'Piezas aprox',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(28,4,number_format($regPackingList->cantidad*1000),0,1,'R'); 
      $pdf->Ln(2);

      $pdf->SetFont('3of9','',22);            
      $pdf->Cell(51,5,'*'.$regPackingList->cdgrollo.'*',0,1,'C');
      $pdf->SetFont('Arial','',8);       
      $pdf->Cell(51,3,$regPackingList->cdgrollo,0,1,'C');  

      //DUPLICADO

      $pdf->SetY(0);

      $pdf->Cell(66,3,'',0,0,'L');      
      $pdf->SetFont('Arial','B',18);
      $pdf->Cell(33,3,'',0,1,'R');       

      $pdf->Cell(53,6,'',0,0,'L');      
      $pdf->SetFont('Arial','B',18);
      $pdf->Cell(46,6,'Q'.$regPackingList->noempaque,0,1,'R'); 

      $pdf->Cell(53,6,'',0,0,'L');      
      $pdf->SetFont('Arial','B',18);
      $pdf->Cell(46,6,'R'.$regPackingList->nocontrol,0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(53,4,'',0,0,'L');
      $pdf->Cell(18,4,'Lote',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(28,4,$regPackingList->cdgmezcla,0,1,'R'); 

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(53,4,'',0,0,'L');
      $pdf->Cell(18,4,'ITEM',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(28,4,$regPackingList->cdgproducto,0,1,'R'); 

      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(53,4,'',0,0,'L');
      $pdf->Cell(51,4,'Sabor/Presentacion',0,1,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(53,4,'',0,0,'L');
      $pdf->Cell(46,4,$regPackingList->impresion,0,1,'R');      

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(53,4,'',0,0,'L');
      $pdf->Cell(18,4,'Longitud',0,0,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(28,4,number_format($regPackingList->longitud,2).' mts',0,1,'R'); 

      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(53,4,'',0,0,'L');
      $pdf->Cell(18,4,'Piezas aprox',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(28,4,number_format($regPackingList->cantidad*1000),0,1,'R'); 
      $pdf->Ln(2);

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
      if (file_exists('../../img_sistema/logo.jpg')==true) 
      { $pdf->Image('../../img_sistema/logo.jpg',2,2,17);
        $pdf->Image('../../img_sistema/logo.jpg',53,2,17); } 

      $pdf->SetY(11);
      $pdf->SetFont('Arial','B',8);

      $pdf->Cell(2,4,'',0,0,'L');
      $pdf->Cell(51,4,$alptEmpaque_fchempaque,0,0,'L');
      $pdf->Cell(51,4,$alptEmpaque_fchempaque,0,1,'L');        
    }

    $pdf->AddPage();

    $pdf->Cell(15,3,'',0,1,'L');
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(32,3,'',0,0,'L');
    $pdf->Cell(18,3,'Empaque ',0,0,'L');
    $pdf->SetFont('Arial','B',36);
    $pdf->Cell(46,10,'Q'.$alptEmpaque_noempaque,0,1,'R'); 

    $pdf->SetY(14);
    //$pdf->Cell(4,4,'',0,0,'L');
   // $pdf->SetFont('Arial','B',10);
   // $pdf->Cell(18,4,'Producto',0,1,'L');
    $pdf->SetFont('Arial','B',18);
    $pdf->Cell(4,5,'',0,0,'L');
    $pdf->Cell(92,5,$alptEmpaque_impresion,0,1,'R');
    $pdf->Ln(1);

    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(18,4,'Peso Bruto',0,0,'L');
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(40,4,number_format($alptEmpaque_pbruto,3).' Kgs',0,1,'R');    
    
    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(18,4,'Longitud ',0,0,'L');
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(40,4,number_format($alptEmpaque_longitud,2).' Mts',0,1,'R');

    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(18,4,'Piezas aprox ',0,0,'L');
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(40,4,number_format($alptEmpaque_piezas*1000),0,1,'R');

    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(28,4,'Planta Yuriria, contenido '.$packingListSelect->num_rows.' Rollos',0,1,'L');    

    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(18,4,'Contacto ',0,0,'L');
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(50,4,'(+52 477) 717 1812 / 211 2700',0,1,'L');
    
    $pdf->Ln(2);

    $pdf->SetFont('3of9','',26);
    $pdf->Cell(98,6,'*'.$_GET['cdgempaque'].'*',0,1,'C');

      // Imagen LOGOTIPO
      if (file_exists('../../img_sistema/logo.jpg')==true) 
      { $pdf->Image('../../img_sistema/logo.jpg',4,2,20); }     

    $pdf->Output();
  } else
  { echo '<html>
  <head>    
    <link rel="stylesheet" type="text/css" href="../../css/global.css" media="screen" /> 
  </head>
  <body>
    No fue encontrado el paquete
  </body>
</html>';}  		 
  
?>
