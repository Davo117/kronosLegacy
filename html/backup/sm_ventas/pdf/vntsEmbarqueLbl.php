<?php

  include '../../datos/mysql.php';	
  require('../../fpdf/fpdflbl.php');

  if ($_GET['cdgembarque'])  
  { $link_mysqli = conectar();    
    $packingListSelect = $link_mysqli->query("
      SELECT vntsembarque.cdgembarque,
        vntsembarque.cdgproducto,
        vntsembarque.referencia,
        vntssucursal.sucursal,
        vntssucursal.domicilio,
        vntssucursal.colonia,
        vntssucursal.cdgpostal,
        vntssucursal.telefono,
        mapaciudad.ciudad,
        mapaestado.estado,
        pdtoimpresion.impresion,
        vntsembarque.transporte
      FROM vntsembarque,
        pdtoimpresion,
        vntssucursal,
        mapaciudad,
        mapaestado
      WHERE vntsembarque.cdgembarque = '".$_GET['cdgembarque']."' AND
        vntsembarque.cdgproducto = pdtoimpresion.cdgimpresion AND
        vntsembarque.cdgsucursal = vntssucursal.cdgsucursal AND
        vntssucursal.cdgciudad = mapaciudad.cdgciudad AND
        mapaciudad.cdgestado = mapaestado.cdgestado"); } 

  if ($packingListSelect->num_rows > 0)
  { $pdf=new FPDF('P','mm','lbl4x2'); 
    $pdf->SetDisplayMode(real, continuous);
    $pdf->AddFont('3of9','','free3of9.php');

    $regPackingList = $packingListSelect->fetch_object();

    $packingList_cdgembarque = $regPackingList->cdgembarque;
    $packingList_producto = $regPackingList->impresion;
    $packingList_referencia = $regPackingList->referencia;
    $packingList_transporte = $regPackingList->transporte;
    $packingList_sucursal = $regPackingList->sucursal;
    $packingList_domicilio = $regPackingList->domicilio;
    $packingList_colonia = $regPackingList->colonia;
    $packingList_cdgpostal = $regPackingList->cdgpostal;
    $packingList_telefono = $regPackingList->telefono;
    $packingList_ciudad = $regPackingList->ciudad;
    $packingList_estado = $regPackingList->estado;

    $link_mysqli = conectar();    
    $packingListSelect_Detalle = $link_mysqli->query("
      SELECT alptempaque.cdgempaque, 
        alptempaque.peso,
        alptempaque.tpoempaque,
        alptempaque.noempaque,
    SUM(prodrollo.longitud/pdtodiseno.alto) AS cantidad 
      FROM alptempaque,
        alptempaquer,
        prodrollo,
        pdtodiseno,
        pdtoimpresion
      WHERE alptempaque.cdgembarque = '".$_GET['cdgembarque']."' AND
        alptempaque.cdgempaque = alptempaquer.cdgempaque AND
       (alptempaquer.cdgrollo = prodrollo.cdgrollo AND
        prodrollo.cdgproducto = pdtoimpresion.cdgimpresion AND
        pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno)
      GROUP BY alptempaque.cdgempaque");

    if ($packingListSelect_Detalle->num_rows > 0)
    { $packingList_tpoempaque = "Paquetes"; }
    else
    { $packingList_tpoempaque = "Cajas";

      $link_mysqli = conectar();    
      $packingListSelect_Detalle = $link_mysqli->query("
        SELECT alptempaque.cdgempaque, 
          alptempaque.peso,
          alptempaque.tpoempaque,
          alptempaque.noempaque,
      SUM(alptempaquep.cantidad) AS cantidad 
        FROM alptempaque,
          alptempaquep
        WHERE alptempaque.cdgembarque = '".$_GET['cdgembarque']."' AND
          alptempaque.cdgempaque = alptempaquep.cdgempaque
        GROUP BY alptempaque.cdgempaque"); }
    
    $num_empaques = $packingListSelect_Detalle->num_rows;

    $id_empaque = 1;
    while ($regPackingList_Detalle = $packingListSelect_Detalle->fetch_object())
    { $packingList_cantidadpqt = 0;
      if ($packingList_tpoempaque == "Paquetes") 
      { $link_mysqli = conectar();
        $alptEmpaqueRSelect = $link_mysqli->query("
          SELECT alptempaquer.nocontrol, alptempaquer.cdgrollo,
            (prodrollo.longitud/pdtodiseno.alto) AS cantidad,
            prodrollo.longitud, prodrollo.peso, prodrollo.bandera
          FROM alptempaquer, prodrollo, pdtodiseno, pdtoimpresion
          WHERE alptempaquer.cdgrollo = prodrollo.cdgrollo AND
            alptempaquer.cdgproducto = pdtoimpresion.cdgimpresion AND
            pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno AND
            alptempaquer.cdgempaque = '".$regPackingList_Detalle->cdgempaque."'
          ORDER BY alptempaquer.nocontrol");
        
        while ($regAlptEmpaqueR = $alptEmpaqueRSelect->fetch_object())
        { $packingList_sumamillares += number_format($regAlptEmpaqueR->cantidad,3); 

          $packingList_cantidadpqt += number_format($regAlptEmpaqueR->cantidad,3); } 
      } else
      { $packingList_cantidadpqt = number_format($regPackingList_Detalle->cantidad,3);
        $packingList_sumamillares += number_format($regPackingList_Detalle->cantidad,3); }

      $pdf->AddPage();

      $pdf->SetY(13);
      
      $pdf->SetFont('Arial','B',12);
      $pdf->Cell(4,4,'',0,0,'L');
      $pdf->Cell(94,4,'Destino: '.$packingList_sucursal,0,1,'L');
      
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(4,4,'',0,0,'L');
      $pdf->Cell(94,4,$packingList_domicilio,0,1,'L');
      $pdf->Cell(4,4,'',0,0,'L');
      $pdf->Cell(94,4,$packingList_colonia,0,1,'L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Cell(4,4,'',0,0,'L');
      $pdf->Cell(94,4,$packingList_ciudad.', '.$packingList_estado,0,1,'L');
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(4,4,'',0,0,'L');
      $pdf->Cell(94,4,'C.P. '.$packingList_cdgpostal.' Tel. '.$packingList_telefono,0,1,'L');

      $pdf->SetFont('Arial','B',9);
      $pdf->Cell(4,4,'',0,0,'L');
      $pdf->Cell(4,4,'Producto',0,1,'L');
      $pdf->SetFont('Arial','B',12);
      $pdf->Cell(4,4,'',0,0,'L');
      $pdf->Cell(4,4,$packingList_producto,0,1,'L');
      $pdf->SetFont('Arial','B',9);
      $pdf->Cell(4,4,'',0,0,'L');
      $pdf->Cell(43,4,'Referencia',0,0,'L');      
      $pdf->Cell(25,4,'Millares aprox',0,0,'L');
      $pdf->Cell(25,4,'Peso Bruto',0,1,'L');
      $pdf->SetFont('Arial','B',12);
      $pdf->Cell(4,4,'',0,0,'L');
      $pdf->Cell(43,4,$regPackingList_Detalle->tpoempaque.$regPackingList_Detalle->noempaque.'  '.$id_empaque.'/'.$num_empaques,0,0,'C');
      $pdf->Cell(25,4,number_format($packingList_cantidadpqt,3),0,0,'C');
      $pdf->Cell(25,4,number_format($regPackingList_Detalle->peso,3),0,1,'C');

      $packingList_sumapesos += $regPackingList_Detalle->peso;
      
      // Imagen LOGOTIPO
      if (file_exists('../../img_sistema/logo.jpg')==true) 
      { $pdf->Image('../../img_sistema/logo.jpg',3,2,20); }  

      $pdf->SetY(3);
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(98,4,'Embarque',0,1,'R');
      $pdf->SetFont('Arial','B',12);
      $pdf->Cell(98,4,$packingList_cdgembarque,0,1,'R');


      $pdf->SetY(6);

      $pdf->SetFont('3of9','',24);
      $pdf->Cell(103,6,'*'.$regPackingList_Detalle->cdgempaque.'*',0,1,'C'); 

      $id_empaque++;
    } 

    $pdf->AddPage();

    $pdf->SetY(13);
    
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->Cell(94,4,'Destino: '.$packingList_sucursal,0,1,'L');
    
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->Cell(94,4,$packingList_domicilio,0,1,'L');
    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->Cell(94,4,$packingList_colonia,0,1,'L');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->Cell(94,4,$packingList_ciudad.', '.$packingList_estado,0,1,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->Cell(94,4,'C.P. '.$packingList_cdgpostal.' Tel. '.$packingList_telefono,0,1,'L');

    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->Cell(4,4,'Producto',0,1,'L');
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->Cell(4,4,$packingList_producto,0,1,'L');
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->Cell(43,4,'Empaques',0,0,'L');      
    $pdf->Cell(25,4,'Millares aprox',0,0,'L');
    $pdf->Cell(25,4,'Peso Bruto',0,1,'L');
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->Cell(43,4,($id_empaque-1).' '.$packingList_tpoempaque,0,0,'C');
    $pdf->Cell(25,4,number_format($packingList_sumamillares,3,'.',','),0,0,'C');
    $pdf->Cell(25,4,number_format($packingList_sumapesos,3,'.',','),0,1,'C');

    // Imagen LOGOTIPO
    if (file_exists('../../img_sistema/logo.jpg')==true) 
    { $pdf->Image('../../img_sistema/logo.jpg',3,2,20); }     

    $pdf->SetY(3);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(25,4,'',0,0,'C');
    $pdf->Cell(45,4,'Transporte',0,0,'L');
    $pdf->Cell(18,4,'Embarque',0,1,'L');
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(25,4,'',0,0,'C');
    $pdf->Cell(45,4,$packingList_transporte,0,0,'L');
    $pdf->Cell(18,4,$packingList_cdgembarque,0,1,'L');

    $pdf->Output();
  } else
  { echo '<html>
  <head>
    <title>Etiquetas de Embarque</title>
    <link rel="stylesheet" type="text/css" href="../../css/global.css" media="screen" /> 
  </head>
  <body>
  </body>
</html>';}  		 
  
?>
