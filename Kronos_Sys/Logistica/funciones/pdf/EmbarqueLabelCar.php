<?php

include '../../../Database/db.php';
include '../../../fpdf/fpdflbl.php';
if (isset($_GET['numEmbarque'])){ 
  $packingListSelect = $MySQLiconn->query("SELECT $embarq.idorden,$embarq.numEmbarque,(SELECT orden from ordencompra where idorden=(SELECT ordenConfi from confirmarprod where idConfi=embarque.idorden)) as orden, $embarq.producto, $embarq.empaque, $tablasucursal.nombresuc, $tablasucursal.domiciliosuc, $tablasucursal.coloniasuc,
             $tablasucursal.cpsuc, $tablasucursal.telefonosuc, $impresion.descripcionImpresion,$impresion.logproveedor,
             $embarq.transpEmb, $tablasucursal.ciudadsuc
          FROM $embarq inner join $impresion on ($embarq.prodEmbFK=$impresion.ID)
          inner join   $tablasucursal on ($embarq.sucEmbFK=$tablasucursal.idsuc)
       WHERE $embarq.numEmbarque='".$_GET['numEmbarque']."'"); 
} 

if ($packingListSelect->num_rows>0){ 
 
  $pdf=new FPDF('P','mm','lbl4x2'); 
  // $pdf->SetDisplayMode(real, continuous);
  $pdf->AddFont('3of9','','free3of9.php');

  $regPackingList = $packingListSelect->fetch_object();

  $packingList_cdgembarque = $regPackingList->numEmbarque;
  $packingList_productoDes = $regPackingList->descripcionImpresion;
  $packingList_productoProv = $regPackingList->logproveedor;
  $packingList_productoID = $regPackingList->producto;
  $packingList_referencia = $regPackingList->empaque;
  $packingList_transporte = $regPackingList->transpEmb;
  $packingList_sucursal = $regPackingList->nombresuc;
  $packingList_domicilio = $regPackingList->domiciliosuc;
  $packingList_colonia = $regPackingList->coloniasuc;
  $packingList_cdgpostal = $regPackingList->cpsuc;
  $packingList_telefono = $regPackingList->telefonosuc;
  $packingList_ciudad = $regPackingList->ciudadsuc;
  $packingList_orden=$regPackingList->orden;
  if ($packingList_referencia=='caja') { $variable= 'caja'; $tipo='Caja';  }
  if ($packingList_referencia=='rollo') { $variable= 'rollo'; $tipo="Rollo";}
//ECastillo anteriormente tenía Order by Referencia
  $SQL=$MySQLiconn->query("SELECT * from $variable where baja='3' && cdgEmbarque='".$_GET['numEmbarque']."' && producto='".$packingList_productoID."'");
  echo "SELECT * from $variable where baja='3' && cdgEmbarque='".$_GET['numEmbarque']."' && producto='".$packingList_productoID."'";
    $contador=1;




$canti=$MySQLiconn->query("SELECT sum(piezas) as total, count(referencia) as refe, sum(peso) as bruto from $variable where baja='3' && cdgEmbarque='".$_GET['numEmbarque']."' && producto='".$packingList_productoID."'");

  $row1=$canti->fetch_array();
  


  while($row=$SQL->fetch_array()){
    $pdf->AddPage();



    $pdf->SetY(13);
      
    $pdf->SetFont('Arial','B',13);
    $pdf->Cell(8,4,'',0,0,'L');
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(94,4,'Destino: '.$packingList_sucursal,0,1,'L');
      
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(8,4,'',0,0,'L');
    $pdf->Cell(94,4,utf8_decode($packingList_domicilio).', Col. '.utf8_decode($packingList_colonia),0,1,'L');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(8,4,'',0,0,'L');
    $pdf->Cell(90,4,utf8_decode($packingList_ciudad),0,1,'L');
        
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(8,4,'',0,0,'L');
    $pdf->Cell(60,4,'C.P. '.$packingList_cdgpostal.' Tel. '.$packingList_telefono,0,0,'L');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(20,4,'OC:'.$packingList_orden,0,1,'R');

    if(!empty($packingList_productoProv))
    {
      //
      $pdf->SetFont('Arial','B',9);
    $pdf->Cell(8,4,'',0,0,'L');
    $pdf->Cell(10,4,'Producto                                             P.C.:'.'#:'.$packingList_productoProv,0,1,'L');
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(8,4,'',0,0,'L');
    $pdf->Cell(60,4,$packingList_productoDes,0,0,'L');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(60,4,'',0,1,'L');
    }
    else
    {
      $pdf->SetFont('Arial','B',9);
    $pdf->Cell(8,4,'',0,0,'L');
    $pdf->Cell(10,4,'Producto',0,1,'L');
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(8,4,'',0,0,'L');
    $pdf->Cell(10,4,$packingList_productoDes,0,1,'L');
    }
    
    
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(8,4,'',0,0,'L');
    $pdf->Cell(15,4,'Paquete',0,0,'L');
    $pdf->Cell(20,4,'Referencia',0,0,'L');      
    $pdf->Cell(25,4,'Millares aprox',0,0,'L');
    $pdf->Cell(20,4,'Peso Bruto',0,1,'L');
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(8,4,'',0,0,'L');
    

    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(3,4,'',0,0,'L');
    $pdf->Cell(15,4,$contador.'/'.$row1['refe'],0,0,'L'); 
    $pdf->Cell(20,4,$row['referencia'],0,0,'L');      
    $pdf->Cell(25,4,number_format($row['piezas'],3),0,0,'L');
    $pdf->Cell(20,4,number_format($row['peso'],3),0,1,'L');
     //ECastillo 27/01/2022

            $pdf->SetFont('Arial','',6);
            $pdf->Cell(10,4,'',0,0,'L');
            $pdf->Cell(10,4,'Grupo Labro/Grupo Ceyla',0,1,'L');
            //
    $contador++;
    $pdf->SetY(4);
    $pdf->SetX(10);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(20,4,'Embarque',0,1,'R');
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(35,4,$packingList_cdgembarque,0,1,'R');
  
    $pdf->SetY(4);
    $pdf->SetX(50);
    $pdf->SetFont('3of9','',24);
    $pdf->Cell(10,6,'*'.$row['codigo'].'*',0,0,'L');
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(15,14,$row['codigo'],0,1,'L');


  } 
  $pdf->AddPage();

  $pdf->SetY(13);      
  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(8,4,'',0,0,'L');
  $pdf->Cell(94,4,'Destino: '.$packingList_sucursal,0,1,'L');
      
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(8,4,'',0,0,'L');
  $pdf->Cell(94,4,utf8_decode($packingList_domicilio),0,1,'L');
  $pdf->Cell(8,4,'',0,0,'L');
  $pdf->Cell(94,4,'Col. '.utf8_decode($packingList_colonia),0,1,'L');
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(8,4,'',0,0,'L');
  $pdf->Cell(94,4,utf8_decode($packingList_ciudad),0,1,'L');
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(8,4,'',0,0,'L');
  $pdf->Cell(60,4,'C.P. '.$packingList_cdgpostal.' Tel. '.$packingList_telefono,0,0,'L');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(20,4,'OC:'.$packingList_orden,0,1,'R');

  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(8,4,'',0,0,'L');
  $pdf->Cell(10,4,'Producto',0,1,'L');
  $pdf->SetFont('Arial','',10.5);
  $pdf->Cell(8,4,'',0,0,'L');
  $pdf->Cell(10,4,$packingList_productoDes,0,1,'L');
  

  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(8,4,'',0,0,'L');
  $pdf->Cell(12,4,'Tipo',0,0,'L');      
  $pdf->Cell(20,4,'Empaques',0,0,'L');  
  $pdf->Cell(25,4,'Millares aprox',0,0,'L');
  $pdf->Cell(25,4,'Peso Bruto',0,1,'L');

  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(9,4,'',0,0,'L');
  $pdf->Cell(30,4,$tipo."   ".$row1['refe'],0,0,'L');      
  $pdf->Cell(30,4,number_format($row1['total'],3),0,0,'L');
  $pdf->Cell(25,4,number_format($row1['bruto'],3),0,1,'L');

  $pdf->SetY(4);
  $pdf->SetX(10);
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(20,4,'Embarque',0,1,'R');
  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(35,4,$packingList_cdgembarque,0,1,'R');
  
  $pdf->SetY(2);
  $pdf->SetX(50);
  $pdf->SetFont('Arial','',11.5);
  $pdf->Cell(10,6,'Transporte',0,0,'L');

  $pdf->SetY(6);
  $pdf->SetX(50);
  $pdf->SetFont('Arial','B',11.5);
  $pdf->Cell(10,6,$packingList_transporte,0,0,'L');
  $pdf->Output();
}
else{
  echo '<html>
  <head>
    <title>Etiquetas de Embarque</title>
    <link rel="stylesheet" type="text/css" href="../../css/2014.css" media="screen" />
  </head>
  <body>
    <div><h1>El embarque no fue encontrado</h1></div>
    <body>
</html>';
} ?>