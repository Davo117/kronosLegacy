<?php

include '../../../Database/db.php'; 
include '../../../fpdf/fpdf.php';

if ($_GET==true) {
  $suc=$_GET['suc'];
  $prod=$_GET['prod'];
  //INICIO Parte Encabezado
  $sucE=$suc;
  $prodE=$prod;
  if ($sucE!='--') {
    $consulta= $MySQLiconn->query("SELECT nombresuc FROM $tablasucursal WHERE  idsuc='".$_GET['suc']."' "); 
    $primera=$consulta->fetch_array();
    $sucE= $primera['nombresuc'];
  }else {       $sucE="Todas";     }
  if ($prodE!='--') {
    $consulta1= $MySQLiconn->query("SELECT d.descripcion, i.descripcionDisenio, i.descripcionImpresion,i.codigoImpresion FROM $impresion as i inner join producto d on d.ID=i.descripcionDisenio WHERE  i.ID='".$_GET['prod']."' "); 
    $primera1=$consulta1->fetch_array();
    $prodE= "[".$primera1['descripcion']."]~".$primera1['descripcionImpresion'];
  }else {   $prodE="Todos";   }
  //FIN Parte Encabezado
}

class PDF extends FPDF { 
  function Header(){ 
    global $sucE;
    global $prodE;
      
    if (file_exists('../../../pictures/lolo.jpg')==true)    { 
      $this->Image('../../../pictures/lolo.jpg',10,10,0,20); 
    }   

    $this->SetFillColor(224,7,8);
    $this->SetFont('Arial','B',8);
    $this->Cell(115,4,utf8_decode('Documento'),0,0,'R');
    $this->Cell(0.5,4,'',0,0,'R',true);
    $this->SetFont('Arial','I',8);
    $this->Cell(75,4,utf8_decode('Listado de entregas'),0,1,'L');

    $this->SetFont('Arial','B',8);
    $this->Cell(115,4,utf8_decode('Sucursal'),0,0,'R');
    $this->Cell(0.5,4,'',0,0,'R',true);
    $this->SetFont('Arial','I',8);
    $this->Cell(75,4,utf8_decode($sucE),0,1,'L');

    $this->SetFont('Arial','B',8);
    $this->Cell(115,4,utf8_decode('Producto'),0,0,'R');
    $this->Cell(0.5,4,'',0,0,'R',true);
    $this->SetFont('Arial','I',8);
    $this->Cell(75,4,utf8_decode($prodE),0,1,'L');

    $this->SetFont('Arial','B',8);
    $this->Cell(115,4,utf8_decode('Rango de fechas'),0,0,'R');
    $this->Cell(0.5,4,'',0,0,'R',true);
    $this->SetFont('Arial','I',8);
    $this->Cell(75,4,utf8_decode($_GET['dsd'].' - '.$_GET['hst']),0,1,'L');      

    $this->Ln(4.15); 
  }
}

$pdf=new PDF('P','mm','letter');
$pdf->AliasNbPages();
//$pdf->SetDisplayMode(real, continuous);    
$pdf->AddPage();
$pdf->SetFillColor(180,180,180);


$confirmar = $MySQLiconn->query("SELECT * FROM $confirProd WHERE enlaceEmbarque=0 && bajaConfi='1' && (embarqueConfi  BETWEEN '".$_GET['dsd']."' AND '".$_GET['hst']."') ORDER BY embarqueConfi ASC, ordenConfi ASC, prodConfi ASC;");    
$fechas=''; $fechar=''; $suc1=''; $suc2=''; $producto=''; $sucursal=''; $array=''; $cuentaC=0; $cuentaR=0;


while ($confirmado=$confirmar->fetch_array()){
  
  if($_GET['suc']!="--"){
    $primero=$MySQLiconn->query("SELECT nombresuc FROM $tablasucursal WHERE idsuc='".$_GET['suc']."'");
    $primera=$primero->fetch_array();
    
    if($primera['nombresuc']!=''){
      $array="&& sucFK='".$_GET['suc']."'";
    }

    $segundo=$MySQLiconn->query("SELECT idorden, orden, sucFK, recepcion FROM $tablaOrden WHERE bajaOrden='1' $array");

    while($segunda=$segundo->fetch_array()){
      $sucursal=$segunda['idorden'];
      if ($sucursal==$confirmado['ordenConfi']) {
        $fechas=$confirmado['embarqueConfi'];
        if($_GET['prod']!="--"){

          if ($confirmado['prodConfi']==$_GET['prod']) {
            $segundo=$MySQLiconn->query("SELECT * FROM $tablaOrden where bajaOrden=1 && idorden=(SELECT ordenConfi from $confirProd where bajaConfi=1 && idConfi= ".$confirmado['idConfi'].")");
            $row1=$segundo->fetch_array();

            if ($fechar!=$fechas) {        
              $fechar=$fechas; 
              $pdf->Cell(17,4,'',0,1,'L');
              $pdf->SetFont('arial','',12);
              $pdf->Cell(20,4,$confirmado['embarqueConfi'],0,1,'L');               
              $pdf->SetFont('arial','',8);
              $pdf->SetFillColor(237,125,49);
              $pdf->SetFont('arial','B',8);
              $pdf->Cell(4,4,'',0,0,'C');
              $pdf->Cell(28,4,'O.C.',1,0,'L',true);
              $pdf->Cell(20,4,'Recepcion',1,0,'L',true);
              //$pdf->Cell(90,4,'Producto',1,0,'L',true);
              $pdf->Cell(17,4,'Empaque',1,0,'L',true);
              $pdf->Cell(18,4,'Cantidad',1,1,'L',true);
              $pdf->SetFont('arial','B',5); 
      
              $ultima=$MySQLiconn->query("SELECT sum(cantidadConfi) as cantidad FROM $confirProd where bajaConfi='1' && ordenConfi='".$row1['idorden']."' && embarqueConfi='".$confirmado['embarqueConfi']."'");
              $ultimo=$ultima->fetch_array();

              $pdf->SetFont('arial','B',8); 
              //$pdf->Cell(18,4,number_format($ultimo['cantidad'],3),1,1,'R',true);            
              $pdf->Cell(4,4,'',0,0,'R'); 
              $item=1;
            }
            else {$pdf->Cell(4,4,'',0,0,'C');}
            if ($item%2 > 0) { $pdf->SetFillColor(248,215,205); } 
            else { $pdf->SetFillColor(252,236,232); }
            $item++;
            $pdf->SetFont('arial','',8); 
            $pdf->Cell(28,4,$segunda['orden'],1,0,'R',true);
            $pdf->Cell(20,4,$segunda['recepcion'],1,0,'L',true);
            //$pdf->Cell(90,4,$confirmado['prodConfi'],1,0,'L',true);
            $pdf->Cell(17,4,$confirmado['empaqueConfi'],1,0,'L',true);
            $pdf->Cell(18,4,number_format($confirmado['cantidadConfi'],3),1,1,'R',true);

          
            if ($confirmado['empaqueConfi']=='caja') {
              $cuentaC+=$confirmado['cantidadConfi'];
            }
            if ($confirmado['empaqueConfi']=='rollo') {
              $cuentaR+=$confirmado['cantidadConfi'];
            }
          }
        }
        //if producto==--
        else{
          $primera=$MySQLiconn->query("SELECT idorden, orden,documento FROM $tablaOrden where bajaOrden=1 && idorden=(SELECT ordenConfi from $confirProd where bajaConfi=1 && idConfi='".$confirmado['idConfi']."')");
          $row1=$primera->fetch_array();
          $segundas=$MySQLiconn->query("SELECT descripcionImpresion, id FROM $impresion where id=".$confirmado['prodConfi']);
          $row2 = $segundas->fetch_array();
          if ($fechar!=$fechas) {
            $fechar=$fechas;
            $pdf->Cell(17,4,'',0,1,'L');
            $pdf->SetFont('arial','',12);
            $pdf->Cell(20,4,$confirmado['embarqueConfi'],0,1,'L');
            $pdf->SetFont('arial','',8);

            $pdf->SetFillColor(237,125,49);
            $pdf->SetFont('arial','B',8);
            $pdf->Cell(4,4,'',0,0,'C');
            $pdf->Cell(28,4,'O.C.',1,0,'L',true);
            $pdf->Cell(20,4,'Recepcion',1,0,'L',true);
            $pdf->Cell(65,4,'Producto',1,0,'L',true);
            $pdf->Cell(25,4,utf8_decode('Código'),1,0,'L',true);
            $pdf->Cell(17,4,'Empaque',1,0,'L',true);
            $pdf->Cell(18,4,'Cantidad',1,1,'L',true);
            $pdf->SetFont('arial','B',5);
      
            $ultima=$MySQLiconn->query("SELECT sum(cantidadConfi) as cantidad FROM $confirProd where bajaConfi='1' && ordenConfi='".$row1['idorden']."' && embarqueConfi='".$confirmado['embarqueConfi']."'");
            $ultimo=$ultima->fetch_array();
            $pdf->SetFont('arial','B',8); 
            //$pdf->Cell(18,4,number_format($ultimo['cantidad'],3),1,1,'R',true);            
            $pdf->Cell(4,4,'',0,0,'R');
            $item=1;
          }
          else {$pdf->Cell(4,4,'',0,0,'C');}
          if ($item%2 > 0) { $pdf->SetFillColor(248,215,205); } 
          else { $pdf->SetFillColor(252,236,232); }
          $item++;
          $pdf->SetFont('arial','',8); 
          $pdf->Cell(28,4,$row1['orden'],1,0,'R',true);
          $pdf->Cell(20,4,$segunda['recepcion'],1,0,'L',true);
          $pdf->Cell(65,4,$row2['descripcionImpresion'],1,0,'L',true);
          $pdf->Cell(25,4,$row2['codigoImpresion'],1,0,'L',true);
          $pdf->Cell(17,4,$confirmado['empaqueConfi'],1,0,'L',true);
          $pdf->Cell(18,4,number_format($confirmado['cantidadConfi'],3),1,1,'R',true);   
          if ($confirmado['empaqueConfi']=='caja') {
            $cuentaC+=$confirmado['cantidadConfi'];
          }
          if ($confirmado['empaqueConfi']=='rollo') {
            $cuentaR+=$confirmado['cantidadConfi'];
          }        
        }
      }  
    }
  }




  elseif($_GET['prod']!="--"){
    $primero= $MySQLiconn->query("SELECT ID, descripcionImpresion FROM $impresion WHERE  ID='".$_GET['prod']."' "); 
    $primera=$primero->fetch_array();

    if ($confirmado['prodConfi']==$primera['ID']) {
      
      $primera=$MySQLiconn->query("SELECT o.idorden, o.recepcion, o.orden, s.nombresuc FROM $tablaOrden o inner join $tablasucursal as s on s.idsuc=o.sucFK where o.bajaOrden=1 && idorden=(SELECT ordenConfi from $confirProd where bajaConfi=1 && idConfi=".$confirmado['idConfi'].")");
      $row1=$primera->fetch_array();
      $fechas=$confirmado['embarqueConfi'];
  
      if ($fechar!=$fechas) {  
        $fechar=$fechas;
        $suc2=$suc1=$row1['nombresuc'];  
        $pdf->Cell(17,4,'',0,1,'L');
        $pdf->SetFont('arial','',12);
        $pdf->Cell(20,4,$confirmado['embarqueConfi'],0,1,'L'); 
        $pdf->SetFont('arial','',8);
        $pdf->Cell(20,4,'Sucursal '.$suc1,0,1,'L');


        $pdf->SetFillColor(237,125,49);
        $pdf->SetFont('arial','B',8);
        $pdf->Cell(4,4,'',0,0,'C');
        $pdf->Cell(28,4,'O.C.',1,0,'L',true);
        $pdf->Cell(20,4,'Recepcion',1,0,'L',true);
        //$pdf->Cell(90,4,'Producto',1,0,'L',true);
        $pdf->Cell(17,4,'Empaque',1,0,'L',true);
        $pdf->Cell(18,4,'Cantidad',1,1,'L',true);
        $pdf->SetFont('arial','B',5); 
      
        $ultima=$MySQLiconn->query("SELECT sum(cantidadConfi) as cantidad FROM $confirProd where bajaConfi='1' && ordenConfi='".$row1['idorden']."' && embarqueConfi='".$confirmado['embarqueConfi']."'");
        $ultimo=$ultima->fetch_array();
        $pdf->SetFont('arial','B',8); 
        //$pdf->Cell(18,4,number_format($ultimo['cantidad'],3),1,1,'R',true);            
        $pdf->Cell(4,4,'',0,0,'R');
        $item=1;
      }
      else {
        $suc1=$row1['nombresuc'];
        if ($suc1!=$suc2) {  
        $suc2=$suc1;
        $pdf->Cell(4,4,'',0,1,'R');
        $pdf->SetFont('arial','',8);
        $pdf->Cell(20,4,'Sucursal '.$suc2,0,1,'L');
        } 
        $pdf->Cell(4,4,'',0,0,'C');
      }
      if ($item%2 > 0) { $pdf->SetFillColor(248,215,205); } 
      else { $pdf->SetFillColor(252,236,232); }
      $item++;
      $pdf->SetFont('arial','',8); 
      $pdf->Cell(28,4,$row1['orden'],1,0,'R',true);
      $pdf->Cell(20,4,$row1['recepcion'],1,0,'L',true);
      //$pdf->Cell(90,4,$confirmado['prodConfi'],1,0,'L',true);
      $pdf->Cell(17,4,$confirmado['empaqueConfi'],1,0,'L',true);
      $pdf->Cell(18,4,number_format($confirmado['cantidadConfi'],3),1,1,'R',true); 


      if ($confirmado['empaqueConfi']=='caja') {
        $cuentaC+=$confirmado['cantidadConfi'];
      }
      if ($confirmado['empaqueConfi']=='rollo') {
        $cuentaR+=$confirmado['cantidadConfi'];
      }
    }
  }
  elseif ($_GET['prod']=='--' && $_GET['suc']=='--') {

    $fechas=$confirmado['embarqueConfi'];
    $primera=$MySQLiconn->query("SELECT o.idorden, o.orden , o.recepcion, s.nombresuc FROM $tablaOrden o inner join $tablasucursal as s on o.sucFK=s.idsuc where o.bajaOrden=1 && o.idorden=(SELECT ordenConfi from $confirProd where bajaConfi=1 && idConfi= ".$confirmado['idConfi'].")");
    $row1=$primera->fetch_array();
    $query=$MySQLiconn->query("SELECT descripcionImpresion,codigoImpresion from impresiones where id='".$confirmado['prodConfi']."'");
    $producto=$query->fetch_array();
      if ($fechar!=$fechas) { 
      $suc2=$suc1=$row1['nombresuc']; 
        $fechar=$fechas; 
        $pdf->Cell(17,4,'',0,1,'L');
        $pdf->SetFont('arial','B',12);
        $pdf->Cell(20,4,$confirmado['embarqueConfi'],0,1,'L'); 
        $pdf->SetFont('arial','',8);
        $pdf->Cell(20,4,'Sucursal '.$suc1,0,1,'L');


        $pdf->SetFillColor(237,125,49);
        $pdf->SetFont('arial','B',8);
        $pdf->Cell(4,4,'',0,0,'C');
        $pdf->Cell(28,4,'O.C.',1,0,'L',true);
        $pdf->Cell(20,4,'Recepcion',1,0,'L',true);
        $pdf->Cell(65,4,'Producto',1,0,'L',true);
        $pdf->Cell(25,4,utf8_decode('Código'),1,0,'L',true);
        $pdf->Cell(17,4,'Empaque',1,0,'L',true);
        $pdf->Cell(18,4,'Cantidad',1,1,'L',true);
        $pdf->SetFont('arial','B',5); 
        
        $ultima=$MySQLiconn->query("SELECT sum(cantidadConfi) as cantidad FROM $confirProd where bajaConfi='1' && ordenConfi='".$row1['idorden']."' && embarqueConfi='".$confirmado['embarqueConfi']."'");
        $ultimo=$ultima->fetch_array();
        $pdf->SetFont('arial','B',8); 
      // $pdf->Cell(18,4,number_format($ultimo['cantidad'],3),1,1,'R',true);            
        $pdf->Cell(4,4,'',0,0,'R');
        $item=1;
      }
      else {  
        $suc1=$row1['nombresuc'];
        if ($suc1!=$suc2) {  
          $suc2=$suc1;
          $pdf->Cell(4,4,'',0,1,'R');
          $pdf->SetFont('arial','',8);
          $pdf->Cell(20,4,'Sucursal  '.$suc2,0,1,'L');
        } 
        $pdf->Cell(4,4,'',0,0,'C');      
      } 
    
    $pdf->SetFont('arial','',8); 
    if ($item%2 > 0) { $pdf->SetFillColor(248,215,205); } 
    else { $pdf->SetFillColor(252,236,232); }
    $item++;
    $pdf->Cell(28,4,$row1['orden'].'',1,0,'R',true);
    $pdf->Cell(20,4,$row1['recepcion'],1,0,'L',true);
    //$pdf->Cell(90,4,$confirmado['prodConfi'],1,0,'L',true);
    $pdf->Cell(65,4,$producto['descripcionImpresion'],1,0,'L',true);
    $pdf->Cell(25,4,$producto['codigoImpresion'],1,0,'L',true);
    $pdf->Cell(17,4,$confirmado['empaqueConfi'],1,0,'L',true);
    $pdf->Cell(18,4,number_format($confirmado['cantidadConfi'],3),1,1,'R',true); 


    if ($confirmado['empaqueConfi']=='caja') {
      $cuentaC+=$confirmado['cantidadConfi'];
    }
    if ($confirmado['empaqueConfi']=='rollo') {
      $cuentaR+=$confirmado['cantidadConfi'];
    }
  }
} 

$pdf->Ln(10);
$pdf->SetFillColor(237,125,49);
$pdf->Cell(4,4,'',0,0,'C');
$pdf->SetFont('arial','B',8);
$pdf->Cell(50,4,'Empaques Totales:',1,0,'L',true);
$pdf->SetFillColor(248,215,205);
$pdf->SetFont('arial','I',8);
$pdf->Cell(17,4,'Rollo',1,0,'L',true);
$pdf->SetFont('arial','',8);
$pdf->Cell(17,4,number_format($cuentaR,3),1,1,'L',true);
$pdf->Cell(54,4,'',0,0,'L');
$pdf->SetFillColor(252,236,232);
$pdf->SetFont('arial','I',8);
$pdf->Cell(17,4,'Caja',1,0,'L',true);
$pdf->SetFont('arial','',8);
$pdf->Cell(17,4,number_format($cuentaC,3),1,1,'L',true);
   
$pdf->Output('Calendario de Entregas del '.$_GET['dsd'].' al '.$_GET['hst'].'.pdf', 'I'); ?>