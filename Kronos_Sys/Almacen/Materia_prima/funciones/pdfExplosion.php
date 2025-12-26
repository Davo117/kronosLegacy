<?php
  require('../../fpdf/fpdf.php');
  require('../../Database/db.php');

  $producto='';
  $oc=''; 
  $prodC='Todos';
  $ocC='Todos';

if ($_GET==true) {
  $producto=$_GET['prod'];
  $oc=$_GET['oc'];
  if ($producto!="ALL") {  $prodC=$producto; }
  if ($oc!='0') { $ocC=$oc; } 
}


class PDF extends FPDF{ // Cabecera de página

  function Header()  { 
    global $prodC;
    global $ocC;

    $this->SetFont('Arial','B',8);
    $this->SetFillColor(224,7,8);   

    if (file_exists('../../pictures/lolo.jpg')==true)    { 
      $this->Image('../../pictures/lolo.jpg',10,10,0,20); 
    }
    $this->SetFont('Arial','B',8);
    $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
    $this->Cell(0.5,4,'',0,0,'R',true);
    $this->SetFont('Arial','I',8);
    $this->Cell(75,4,utf8_decode('Explosión de materiales'),0,1,'L');

    $this->SetFont('Arial','B',8);
    $this->Cell(125,4,utf8_decode('Producto'),0,0,'R');
    $this->Cell(0.5,4,'',0,0,'R',true);
    $this->SetFont('Arial','I',8);
    $this->Cell(75,4,utf8_decode($prodC),0,1,'L'); 

    $this->SetFont('Arial','B',8);
    $this->Cell(125,4,utf8_decode('Pedido/Confirmación'),0,0,'R');
    $this->Cell(0.5,4,'',0,0,'R',true);
    $this->SetFont('Arial','I',8);
    $this->Cell(75,4,utf8_decode($ocC),0,1,'L');
     
    $this->Ln(15); 
  }
  // Pie de página
  function Footer()  { 
    $this->SetY(-20);
    $this->SetY(-10);
    $this->SetFont('arial','B',8);
    $this->Cell(0,6,'Consultado el '.date("Y-m-d").' a las '.date("G:i:s").'',0,1,'R'); 
    $this->SetFont('arial','',8);
    $this->SetY(-10);
    $this->Cell(0,6,utf8_decode('*La información filtrada toma como referencia los pedidos activos '),0,1,'L');
    $this->SetY(-7.5);
    $this->Cell(0,6,utf8_decode('Unidad de medida NO disponibles'),0,1,'L');

    $this->SetY(-7.5);
    $this->Cell(0,6,utf8_decode('Grupo Labro | Página '.$this->PageNo().' de {nb}'),0,1,'R'); 
  }
}

  $pdf = new PDF('P','mm','letter');
  $pdf->AliasNbPages();

  $pdf->AddPage();
  $pdf->SetFillColor(237,125,49);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(130,4,utf8_decode('Elemento'),0,0,'L',true);
  $pdf->Cell(30,4,utf8_decode('Cantidad Unitaria'),0,0,'C',true);
  $pdf->Cell(30,4,utf8_decode('Total'),0,1,'C',true);
  $pdf->Cell(190,0.3,'',1,1);
  

if ($codigo!="ALL") {
  $primera= $MySQLiconn->query("SELECT nombreBanda, descripcionImpresion FROM impresiones where baja=1 && descripcionDisenio='$producto' && codigoImpresion='$codigo'");  
  $row1 = $primera->fetch_array();
    
  $array='';
  if ($oc!='0') { $array="&& ordenConfi='$oc'"; }

  $segunda= $MySQLiconn->query("SELECT prodConfi, cantidadConfi FROM confirmarprod where bajaConfi=1  && prodConfi='".$row1['descripcionImpresion']."' $array");
  while($row2 = $segunda->fetch_array()){

    $pdf->SetFont('Arial','B',9);
    $pdf->SetFillColor(255,104,63);
    $pdf->Cell(150,4,utf8_decode($producto.' ~ '.$row1['descripcionImpresion']),0,0,'C',true);
    $pdf->Cell(40,4,utf8_decode($row2['cantidadConfi'].' pzs'),0,1,'C',true);

    $pdf->SetFont('Arial','',9);
    $banda= $MySQLiconn->query("SELECT nombreBanda, anchura FROM bandaseguridad where baja=1 && nombreBanda='".$row1['nombreBanda']."'");
    $bs = $banda->fetch_array();
    $valorBS= $bs["anchura"]* $row2['cantidadConfi'];
    //Traemos la tabla y la imprimimos donde pertenece 
    $item=1;
      
    if ($item%2 > 0) { $pdf->SetFillColor(248,215,205); } 
    else { $pdf->SetFillColor(252,236,232); }
        
    $pdf->Cell(130,4,utf8_decode($bs["nombreBanda"]),0,0,'L',true);
    $pdf->Cell(30,4,number_format($bs["anchura"],3,'.',','),0,0,'R',true);
    $pdf->Cell(30,4,number_format($valorBS,3,'.',','),0,1,'R',true);

    $tercera= $MySQLiconn->query("SELECT elemento, consumo FROM consumos where baja=1 && producto='$producto'");

    while ($row3 = $tercera->fetch_array()) {
      $item++;
      if ($item%2 > 0) { $pdf->SetFillColor(248,215,205); } 
      else { $pdf->SetFillColor(252,236,232); }
      
      $valor= $row3["consumo"]* $row2['cantidadConfi'];
      //Traemos la tabla y la imprimimos donde pertenece 
      $pdf->Cell(130,4,utf8_decode($row3["elemento"]),0,0,'L',true);
      $pdf->Cell(30,4,number_format($row3["consumo"],3,'.',','),0,0,'R',true);
      $pdf->Cell(30,4,number_format($valor,3,'.',','),0,1,'R',true);
    }

      $cuarta=$MySQLiconn->query("SELECT consumoPantone, descripcionPantone FROM pantonepcapa where estado=1 && codigoImpresion='$codigo'");
      while ($row4 = $cuarta->fetch_array()) { 
        $item++;
        if ($item%2 > 0) { $pdf->SetFillColor(248,215,205); } 
        else { $pdf->SetFillColor(252,236,232); }
         
        $cantidadPP=$row2['cantidadConfi']*$row4["consumoPantone"];
        $pdf->Cell(130,4,utf8_decode($row4["descripcionPantone"]),0,0,'L',true);
        $pdf->Cell(30,4,number_format($row4["consumoPantone"],3,'.',','),0,0,'R',true);
        $pdf->Cell(30,4,number_format($cantidadPP,3,'.',','),0,1,'R',true);
      }
    }
  }
  elseif ($oc!='0') {
    $primera=$MySQLiconn->query("SELECT prodConfi, cantidadConfi FROM confirmarprod where bajaConfi=1 && ordenConfi='$oc'");
    while ($row1 = $primera->fetch_array()) {
        
      $segunda=$MySQLiconn->query("SELECT descripcionDisenio, nombreBanda, codigoImpresion FROM impresiones where baja=1 && descripcionImpresion='".$row1['prodConfi']."'");
      while ($row2 = $segunda->fetch_array()) {
        /////          

        $pdf->SetFont('Arial','B',9);
        $pdf->SetFillColor(255,104,63);
        $pdf->Cell(150,4,utf8_decode($row2['descripcionDisenio'].' ~ '.$row1['prodConfi']),0,0,'C',true);
        $pdf->Cell(40,4,utf8_decode($row1['cantidadConfi'].' pzs'),0,1,'C',true);

        $pdf->SetFont('Arial','',9);
        $banda= $MySQLiconn->query("SELECT nombreBanda, anchura FROM bandaseguridad where baja=1 && nombreBanda='".$row2['nombreBanda']."'");
        $bs = $banda->fetch_array();
         $item=1;
      
          if ($item%2 > 0) { $pdf->SetFillColor(248,215,205); } 
          else { $pdf->SetFillColor(252,236,232); }
        $valorBS= $bs["anchura"]* $row1['cantidadConfi'];
        //Traemos la tabla y la imprimimos donde pertenece 
        $pdf->Cell(130,4,utf8_decode($bs["nombreBanda"]),0,0,'L',true);
        $pdf->Cell(30,4,number_format($bs["anchura"],3,'.',','),0,0,'R',true);
        $pdf->Cell(30,4,number_format($valorBS,3,'.',','),0,1,'R',true);

        $tercera= $MySQLiconn->query("SELECT consumo, elemento FROM consumos where baja=1 && producto='".$row2['descripcionDisenio']."'");
        while ($row3=$tercera->fetch_array()) {
          $item++;
          
          if ($item%2 > 0) { $pdf->SetFillColor(248,215,205); } 
          else { $pdf->SetFillColor(252,236,232); }
          $valor= $row3["consumo"]* $row1['cantidadConfi'];
          //Traemos la tabla y la imprimimos donde pertenece 
          $pdf->Cell(130,4,utf8_decode($row3["elemento"]),0,0,'L',true);
          $pdf->Cell(30,4,number_format($row3["consumo"],3,'.',','),0,0,'R',true);
          $pdf->Cell(30,4,number_format($valor,3,'.',','),0,1,'R',true);
        }
        
        $cuarta=$MySQLiconn->query("SELECT consumoPantone, descripcionPantone FROM pantonepcapa where estado=1 && codigoImpresion='".$row2['codigoImpresion']."'");
        while ($row4 = $cuarta->fetch_array()) {
          $item++;
          if ($item%2 > 0) { $pdf->SetFillColor(248,215,205); } 
          else { $pdf->SetFillColor(252,236,232); }
          $cantidadPP=$row1['cantidadConfi']*$row4["consumoPantone"];

          $pdf->Cell(130,4,utf8_decode($row4["descripcionPantone"]),0,0,'L',true);
          $pdf->Cell(30,4,number_format($row4["consumoPantone"],3,'.',','),0,0,'R',true);
          $pdf->Cell(30,4,number_format($cantidadPP,3,'.',','),0,1,'R',true);
        }
      }
    }
  }
  elseif ($oc=='0' && $codigo=='ALL') {
    $primera=$MySQLiconn->query("SELECT prodConfi, cantidadConfi FROM confirmarprod where bajaConfi=1 ");
    while ($row1 = $primera->fetch_array()) {

      $segunda=$MySQLiconn->query("SELECT descripcionDisenio, nombreBanda, codigoImpresion FROM impresiones where baja=1 && descripcionImpresion='".$row1['prodConfi']."'");
      while ($row2 = $segunda->fetch_array()) {

        $banda= $MySQLiconn->query("SELECT nombreBanda, anchura FROM bandaseguridad where baja=1 && nombreBanda='".$row2['nombreBanda']."'");
        $bs = $banda->fetch_array();

        $pdf->SetFont('Arial','B',9);
        $pdf->SetFillColor(255,104,63);
        $pdf->Cell(150,4,utf8_decode($row2['descripcionDisenio'].' ~ '.$row1['prodConfi']),0,0,'C',true);
        $pdf->Cell(40,4,utf8_decode($row1['cantidadConfi'].' pzs'),0,1,'C',true);
        
        $item=1;
      
        if ($item%2 > 0) { $pdf->SetFillColor(248,215,205); }
        else { $pdf->SetFillColor(252,236,232); }
        
        $valorBS= $bs["anchura"]* $row1['cantidadConfi'];
        //Traemos la tabla y la imprimimos donde pertenece
        $pdf->Cell(130,4,utf8_decode($bs["nombreBanda"]),0,0,'L',true);
        $pdf->Cell(30,4,number_format($bs["anchura"],3,'.',','),0,0,'R',true);
        $pdf->Cell(30,4,number_format($valorBS,3,'.',','),0,1,'R',true);
    
        $tercera= $MySQLiconn->query("SELECT consumo, elemento FROM consumos where baja=1 && producto='".$row2['descripcionDisenio']."'");
        
        while ($row3 = $tercera->fetch_array()) {
          $item++;
          
          if ($item%2 > 0) { $pdf->SetFillColor(248,215,205); }
          else { $pdf->SetFillColor(252,236,232); }

          $valor= $row3["consumo"]* $row1['cantidadConfi'];
          //Traemos la tabla y la imprimimos donde pertenece
          $pdf->Cell(130,4,utf8_decode($row3["elemento"]),0,0,'L',true);
          $pdf->Cell(30,4,number_format($row3["consumo"],3,'.',','),0,0,'R',true);
          $pdf->Cell(30,4,number_format($valor,3,'.',','),0,1,'R',true);
        }

        $cuarta=$MySQLiconn->query("SELECT consumoPantone,descripcionPantone, estado FROM pantonepcapa where estado=1 && codigoImpresion='".$row2['codigoImpresion']."'");
        
        while ($row4 = $cuarta->fetch_array()) {
          $item++;
          if ($item%2 > 0) { $pdf->SetFillColor(248,215,205); }
          else { $pdf->SetFillColor(252,236,232); }
          $cantidadPP=$row1['cantidadConfi']*$row4["consumoPantone"];
          $pdf->Cell(130,4,utf8_decode($row4["descripcionPantone"]),0,0,'L',true);
          $pdf->Cell(30,4,number_format($row4["consumoPantone"],3,'.',','),0,0,'R',true);
          $pdf->Cell(30,4,number_format($cantidadPP,3,'.',','),0,1,'R',true);
        }
      }
    }
  }
  ////////////////////////////////////////////////
  $pdf->Output('Explosion de Materiales.pdf', 'I'); ?>