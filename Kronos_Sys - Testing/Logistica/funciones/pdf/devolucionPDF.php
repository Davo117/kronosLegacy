<?php 

include '../../../fpdf/fpdf.php';
include '../../../Database/db.php';
  
$consultar=$MySQLiconn->query("SELECT * from $devolucion where baja='2' && id='".$_GET['cdgdev']."' ");
$row=$consultar->fetch_array();
$foli=$row['folio'];


$vntsdev_folio=$row['folio'];
$vntsdev_fchdocumento=$row['fechaDev'];
$vntsdev_producto=$row['producto'];
$vntsdev_empaque=$row['tipo'];
$vntsdev_cdgembarque=$row['codigo'];
$vntsdev_sucursal=$row['sucursal'];
$vntsdev_devolucion='NULO';
$vntsdev_observacion=$row['observaciones'];

$consultar1=$MySQLiconn->query("SELECT idconsuc, nombreconsuc, puestoconsuc from $tablaconsuc where bajaconsuc='1' && idconsuc='".$row['idresponsable']."' ");
$row1=$consultar1->fetch_array();      
if ($row1['idconsuc']=='') {
$vntsdev_contacto='NO DISPONIBLE';
}else{
$vntsdev_contacto=$row1['nombreconsuc'].' ['.$row1['puestoconsuc'].']'; }
class PDF extends FPDF{
  function Header(){
    global $vntsdev_fchdocumento;
    global $vntsdev_producto;
    global $vntsdev_empaque;
    global $vntsdev_folio;

    if (file_exists('../../../pictures/lolo.jpg')==true){ $this->Image('../../../pictures/lolo.jpg',10,10,0,20); }

    $this->SetFillColor(224,7,8);

    $this->SetFont('Arial','B',8);
    $this->Cell(115,4,utf8_decode('Documento'),0,0,'R');
    $this->Cell(0.5,4,'',0,0,'R',true);
    $this->SetFont('Arial','I',8);
    $this->Cell(75,4,utf8_decode('Queja o reclamo'),0,1,'L');
  
    $this->SetFont('Arial','B',8);
    $this->Cell(115,4,utf8_decode('Folio'),0,0,'R');
    $this->Cell(0.5,4,'',0,0,'R',true);
    $this->SetFont('Arial','I',8);
    $this->Cell(75,4,$vntsdev_folio,0,1,'L');     

    $this->SetFont('Arial','B',8);
    $this->Cell(115,4,utf8_decode('Fecha'),0,0,'R');
    $this->Cell(0.5,4,'',0,0,'R',true);
    $this->SetFont('Arial','I',8);
    $this->Cell(75,4,$vntsdev_fchdocumento,0,1,'L');  

    $this->SetFont('Arial','B',8);
    $this->Cell(115,4,utf8_decode('Producto'),0,0,'R');
    $this->Cell(0.5,4,'',0,0,'R',true);
    $this->SetFont('Arial','I',8);
    $this->Cell(75,4,$vntsdev_producto,0,1,'L');

    $this->SetFont('Arial','B',8);
    $this->Cell(115,4,utf8_decode('Tipo'),0,0,'R');
    $this->Cell(0.5,4,'',0,0,'R',true);
    $this->SetFont('Arial','I',8);
    $this->Cell(75,4,$vntsdev_empaque,0,1,'L');     

    $this->Ln(4.15);    
  }
}

$pdf = new PDF('P','mm','letter');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFillColor(224,7,8);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,4,utf8_decode('Código'),0,0,'R');
$pdf->Cell(0.5,4,'',0,0,'R',true);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(75,4,utf8_decode($vntsdev_cdgembarque),0,1,'L');

$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,4,utf8_decode('Origen'),0,0,'R');
$pdf->Cell(0.5,4,'',0,0,'R',true);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(75,4,utf8_decode($vntsdev_sucursal),0,1,'L');

$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,4,utf8_decode('Contacto'),0,0,'R');
$pdf->Cell(0.5,4,'',0,0,'R',true);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(75,4,utf8_decode($vntsdev_contacto),0,1,'L');  

$pdf->Ln(10);   
  
$pdf->SetFont('Arial','B',12);
$pdf->Cell(25,4,utf8_decode('Causa:'),0,1,'R');
$pdf->SetFont('Arial','I',10);
$pdf->Cell(25,4,utf8_decode(''),0,0,'R');
$pdf->MultiCell(0,4.15,utf8_decode($vntsdev_observacion),0,'J');    


$item=1;
$total=0;
$vntsdev_detalle=array();
if($vntsdev_empaque=='Embarque'){
  $consultar2=$MySQLiconn->query("SELECT * from ensambleempaques where cdgDev='$foli' && cdgEmbarque='".$row['codigo']."' GROUP by referencia");
  
  while ($row2=$consultar2->fetch_array()){
    if ($row2['tipoEmpaque']=='rollo') {
      $consultar3=$MySQLiconn->query("SELECT * from rollo where referencia='".$row2['referencia']."' && cdgEmbarque='".$row2['cdgEmbarque']."' && baja='5' && cdgDev='$foli'");
    }
    if ($row2['tipoEmpaque']=='caja') {
      $consultar3=$MySQLiconn->query("SELECT * from caja where referencia='".$row2['referencia']."' && cdgEmbarque='".$row2['cdgEmbarque']."' && cdgDev='$foli' && baja='5'");
    }
    while ($row3=$consultar3->fetch_array()){
      $vntsdev_detalle[$item]= $row3["referencia"].' '.$row3["piezas"].' mlls ';
      $total+=$row3["piezas"];
      $item++;
    }
  }
  $contador=count($vntsdev_detalle);

  $pdf->Ln(5);   

  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(25,4,utf8_decode('Detalles: '),0,1,'R');
  $pdf->SetFont('Arial','I',11);
  $pdf->SetX(35);
  for ($i=1; $i <=$contador ; $i++) { 
    $pdf->Cell(30,4,utf8_decode($vntsdev_detalle[$i]),0,0,'J');
    
    if ($i%5 > 0) { $pdf->Cell(5,4,'',0,0,'R');  } 
    else { $pdf->Cell(0,4,'',0,1,'J'); $pdf->Cell(0,4,'',0,1,'J'); $pdf->SetX(35); }
  }

  $pdf->Ln(8);   
  $pdf->SetX(25);

  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(25,4,utf8_decode('Millares Totales:  '),0,0,'L');
  $pdf->Cell(15,4,utf8_decode(' '),0,0,'C');
  $pdf->Cell(25,4,utf8_decode($total.'mlls'),0,0,'C');
}


if ($vntsdev_empaque=='Empaque') {
  $consultar2=$MySQLiconn->query("SELECT * from caja where codigo='".$row['codigo']."' && baja='5' && cdgDev='$foli'");
  $row2=$consultar2->fetch_array();

  if ($row2['id']=='') {
    $consultar2=$MySQLiconn->query("SELECT * from rollo where codigo='".$row['codigo']."' && baja='5' && cdgDev='$foli'");
    $row2=$consultar2->fetch_array();
  }

  
  $consultar3=$MySQLiconn->query("SELECT * from ensambleempaques where referencia='".$row2['referencia']."' && cdgEmbarque='".$row2['cdgEmbarque']."' && baja='5' && cdgDev='$foli'");
  while ($row3=$consultar3->fetch_array()){
    $vntsdev_detalle[$item]= $row3["referencia"].' '.$row3["piezas"].' mlls ';
    $total+=$row3['piezas'];
    $item++;
  }
  $contador=count($vntsdev_detalle);

  $pdf->Ln(5);   

  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(25,4,utf8_decode('Detalles: '),0,1,'R');
  $pdf->SetFont('Arial','I',11);
  $pdf->SetX(35);
  for ($i=1; $i <=$contador ; $i++) { 
    $pdf->Cell(30,4,utf8_decode($vntsdev_detalle[$i]),0,0,'J');
    if ($i%5 > 0) { $pdf->Cell(0.5,4,'',0,0,'R');  } 
    else { $pdf->Cell(0,4,'',0,1,'J'); $pdf->Cell(0,4,'',0,1,'J'); $pdf->SetX(35); }
  }
  $pdf->Ln(8);   
  $pdf->SetX(25);

  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(25,4,utf8_decode('Millares Totales:  '),0,0,'L');
  $pdf->Cell(15,4,utf8_decode(' '),0,0,'C');
  $pdf->Cell(25,4,utf8_decode($total.'mlls'),0,0,'C');
}

if ($vntsdev_empaque=='Paquete' OR $vntsdev_empaque=='Rollo') {
  # codigo para paquetitos o bobinas 

  //Al hacer una devolucion del paquetito, que es lo que pasa? se desarma el PAQUETE...... se desactiva tambien el paquete?  
  $consulta=$MySQLiconn->query("SELECT * from ensambleempaques where codigo='".$row['codigo']."' && baja='5' && cdgDev='$foli'");
  $row=$consulta->fetch_array();
  $pdf->Ln(5);   

  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(25,4,utf8_decode('Detalles: '),0,1,'R');

  $pdf->SetFont('Arial','I',11);
  $pdf->SetX(35);
  $pdf->Cell(30,4,utf8_decode($row["referencia"].' '.$row["piezas"].' mlls '),0,0,'J');

  $pdf->Ln(8);   
  $pdf->SetX(25);
  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(25,4,utf8_decode('Millares Totales:  '),0,0,'L');
  $pdf->Cell(15,4,utf8_decode(' '),0,0,'C');
  $pdf->Cell(25,4,utf8_decode($row["piezas"].'mlls'),0,0,'C');
}
$pdf->Output('Devolución'.$vntsdev_folio.'.pdf', 'I');
?>