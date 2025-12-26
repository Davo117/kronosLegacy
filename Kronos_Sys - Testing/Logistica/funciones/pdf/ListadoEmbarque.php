<?php
  include '../../../fpdf/fpdf.php';
  include '../../../Database/db.php';
  
if (isset($_GET['numEmbarque'])){ 

  $vntsEmbarqueSelect = $MySQLiconn->query("SELECT $embarq.numEmbarque,
             $embarq.referencia, $impresion.descripcionImpresion,
             $embarq.empaque, $embarq.diaEmb,
             $embarq.sucEmbFK, $embarq.transpEmb,
             $embarq.producto
        FROM $embarq inner join  $impresion on $embarq.producto = $impresion.id
       WHERE $embarq.numEmbarque = '".$_GET['numEmbarque']."'");

  $regVntsEmbarque = $vntsEmbarqueSelect->fetch_array();
  if ($regVntsEmbarque['producto']!=null) {
    
    $vntsEmbarque_cdgembarque = $regVntsEmbarque['numEmbarque'];
    $vntsEmbarque_referencia = $regVntsEmbarque['referencia'];
    $vntsEmbarque_producto = $regVntsEmbarque['descripcionImpresion'];
    $vntsEmbarque_productoID = $regVntsEmbarque['producto'];
    $vntsEmbarque_cdgempaque = $regVntsEmbarque['empaque'];
    $vntsEmbarque_fchembarque = $regVntsEmbarque['diaEmb'];
      
    $vntsEmbarque_transporte = $regVntsEmbarque['transpEmb'];
    $vntsEmbarque_cdgsucursal = $regVntsEmbarque['sucEmbFK'];

    $vntsSucursalSelect = $MySQLiconn->query("
      SELECT suc.idcliFKS, suc.nombresuc, suc.domiciliosuc, suc.coloniasuc, suc.cpsuc, suc.telefonosuc, 
      cli.nombrecli
          FROM $tablasucursal as suc inner join $tablacli as cli on cli.ID= suc.idcliFKS  WHERE idsuc='".$vntsEmbarque_cdgsucursal."'");

    $regVntsSucursal = $vntsSucursalSelect->fetch_array();
    $vntsEmbarque_cliente = $regVntsSucursal['nombrecli'];
    $vntsEmbarque_sucursal = $regVntsSucursal['nombresuc'];
    $vntsEmbarque_domicilio = $regVntsSucursal['domiciliosuc'];
    $vntsEmbarque_colonia = $regVntsSucursal['coloniasuc'];
    $vntsEmbarque_cdgpostal = $regVntsSucursal['cpsuc'];
    $vntsEmbarque_telefono = $regVntsSucursal['telefonosuc']; 

    if ($vntsEmbarque_cdgempaque == 'caja'){ 
      $vntsEmbarque_codigodoc = 'EM-FR01';
      $vntsEmbarque_nombredoc = 'Lista de embarque Caja';
      $vntsEmbarque_versiondoc = '3.0';
      $vntsEmbarque_revisiondoc = 'Junio 08, 2015';
    }
    else{ 
      $vntsEmbarque_codigodoc = 'EM-FR02';
      $vntsEmbarque_nombredoc = 'Lista de embarque Rollos';
      $vntsEmbarque_versiondoc = '4.0';
      $vntsEmbarque_revisiondoc = 'Junio 08, 2015';
    }
    class PDF extends FPDF{ 
      function Header(){ 
        global $vntsEmbarque_codigodoc;
        global $vntsEmbarque_nombredoc;
        global $vntsEmbarque_versiondoc;
        global $vntsEmbarque_revisiondoc;

        global $vntsEmbarque_cdgembarque;
        global $vntsEmbarque_referencia;
        global $vntsEmbarque_cdgempaque;
        global $vntsEmbarque_producto;
        global $vntsEmbarque_fchembarque;
        global $vntsEmbarque_cliente;
        global $vntsEmbarque_sucursal;
        global $vntsEmbarque_domicilio;
        global $vntsEmbarque_colonia;
        global $vntsEmbarque_cdgpostal;
        global $vntsEmbarque_telefono;
        global $vntsEmbarque_transporte;

        $this->SetFont('Arial','B',8);
        $this->SetFillColor(224,7,8);

        if (file_exists('../../../pictures/lolo.jpg')==true)
        { $this->Image('../../../pictures/lolo.jpg',10,10,0,20); }

        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode($vntsEmbarque_nombredoc),0,1,'L');

        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Código'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode($vntsEmbarque_codigodoc),0,1,'L');

        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Versión'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode($vntsEmbarque_versiondoc),0,1,'L');

        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Revisión'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode($vntsEmbarque_revisiondoc),0,1,'L');

        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Responsable'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode('Coordinador de Logística y Atención al Cliente'),0,1,'L'); 

        $this->Ln(4); }
      }
      $pdf = new PDF('P','mm','letter');
      $pdf->AliasNbPages();
      //$pdf->SetDisplayMode(real, continuous);
      $pdf->AddPage();

      $pdf->SetFillColor(224,7,8);

      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(15,4,utf8_decode('Cliente'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(20,4,$vntsEmbarque_cliente,0,0,'L');

      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(90,4,utf8_decode('Embarque'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(80,4,utf8_decode($vntsEmbarque_cdgembarque),0,1,'L');


      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(15,4,utf8_decode('Sucursal'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(20,4,utf8_decode($vntsEmbarque_sucursal),0,0,'L');

      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(90,4,utf8_decode('Fecha'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(60,4,$vntsEmbarque_fchembarque,0,1,'L');


      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(15,4,utf8_decode('Domicilio'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(20,4,utf8_decode($vntsEmbarque_domicilio),0,0,'L');

      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(90,4,utf8_decode('Producto'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(60,4,utf8_decode($vntsEmbarque_producto),0,1,'L');


      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(15,4,utf8_decode('Colonia'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(20,4,utf8_decode($vntsEmbarque_colonia),0,0,'L');

      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(90,4,utf8_decode('Transporte'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(60,4,utf8_decode($vntsEmbarque_transporte),0,1,'L');


      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(15,4,utf8_decode('Código postal'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(20,4,utf8_decode($vntsEmbarque_cdgpostal),0,0,'L');

      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(90,4,utf8_decode('Referencia'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(60,4,utf8_decode($vntsEmbarque_referencia),0,1,'L');


      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(15,4,utf8_decode('Teléfono'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(20,4,utf8_decode($vntsEmbarque_telefono),0,0,'L');

      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(90,4,utf8_decode('Fecha de recibido'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(60,4,utf8_decode('__________________'),0,1,'L');

      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(125.5,4,utf8_decode('Nombre y firma'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(40,4,utf8_decode('______________________________________'),0,1,'L');


      $pdf->Ln(4);
      $pdf->SetFillColor(237,125,49);
      $pdf->Cell(4,4,'',0,0,'L');
      $pdf->SetFont('arial','',10);
      $pdf->Cell(24,4,'Empaque',1,0,'C',true);
      $pdf->Cell(24,4,'Millares',1,0,'C',true);
      $pdf->Cell(24,4,'Peso bruto',1,0,'C',true);

      if ($vntsEmbarque_cdgempaque == 'rollo'){
        $pdf->Cell(24,4,'Bobina',1,0,'C',true);
        $pdf->Cell(24,4,'Millares',1,0,'C',true); 
      }
      $pdf->Ln();
      if ($vntsEmbarque_cdgempaque == 'caja') {
        $SLQ=$MySQLiconn->query("SELECT * from caja where producto='".$regVntsEmbarque['producto']."' && cdgEmbarque='".$_GET['numEmbarque']."' && baja='3'");
        $i=1;
        $item=1;

        while ($row=$SLQ->fetch_array()) {
          if ($item%2 > 0) { $pdf->SetFillColor(248,215,205); } 
          else { $pdf->SetFillColor(252,236,232); }
          $pdf->SetFont('arial','',6.5);
          $pdf->Cell(4,8,$i,0,0,'R');
          $pdf->SetFont('arial','B',14);
          $pdf->Cell(24,7,$row['referencia'],1,0,'C',true); 
          $pdf->SetFont('arial','',9.5);
          $pdf->Cell(24,7,$row['piezas'],1,0,'C',true); 
          $pdf->Cell(24,7,$row['peso'],1,1,'C',true); 
          $item++;
          $i++;
        }
      $suma=$MySQLiconn->query("SELECT sum(piezas) as pieza, sum(peso) as pesaje from caja where producto='".$regVntsEmbarque['producto']."' && cdgEmbarque='".$_GET['numEmbarque']."' && baja='3'");
      $rows=$suma->fetch_array();
    }
    if ($vntsEmbarque_cdgempaque == 'rollo') {
      $SLQ=$MySQLiconn->query("SELECT * from rollo where producto='".$regVntsEmbarque['producto']."' && baja='3' && cdgEmbarque='".$_GET['numEmbarque']."' ");
      $i=1;
      $item=1;

      while ($row=$SLQ->fetch_array()) {
        if ($item%2 > 0) { $pdf->SetFillColor(248,215,205); } 
        else { $pdf->SetFillColor(252,236,232); }
  
        $pdf->SetFont('arial','',6.5);
        $pdf->Cell(4,8,$i,0,0,'R');
        $pdf->SetFont('arial','B',14);
        $pdf->Cell(24,5,$row['referencia'],1,0,'C',true); 
        $pdf->SetFont('arial','',9.5);
        $pdf->Cell(24,5,$row['piezas'],1,0,'C',true); 
        $pdf->Cell(24,5,$row['peso'],1,0,'C',true); 
  
        $bobina=$MySQLiconn->query("SELECT * from ensambleempaques where referencia='".$row['referencia']."' && cdgEmbarque='".$_GET['numEmbarque']."' ORDER BY referencia");
        $posicionX = $pdf->GetX();
        $inicial=1;
        while ($rowBobina=$bobina->fetch_array()) {
          $letras= "";//explode('Q', $rowBobina['referencia']);

          
          $pdf->SetFont('arial','B',9);
          if ($pdf->GetX() == $posicionX){ $pdf->Cell(24,5,'R'.$rowBobina['refEnsamble'].' ',1,0,'R', true);  } 
          else{ 
            $pdf->Cell(($posicionX-10),4,'',0,0,'R');
            $pdf->Cell(24,5,'R'.$rowBobina['refEnsamble'].' ',1,0,'R', true); 
          }
          $pdf->SetFont('arial','',9);
          $pdf->Cell(24,5,$rowBobina['piezas'],1,1,'R', true);           
          $inicial++;
        }
        $item++;
        $i++;
      }
      $suma=$MySQLiconn->query("SELECT sum(piezas) as pieza, sum(peso) as pesaje from rollo where producto='".$regVntsEmbarque['producto']."' && cdgEmbarque='".$_GET['numEmbarque']."' && baja='3'");
      $rows=$suma->fetch_array();
    }
$pdf->Ln(-5);
    $pdf->SetFont('arial','B',14);
    $pdf->Cell(28,8,'',0,1,'R');
    $pdf->Cell(28,8,'Totales:',0,0,'R');
      
  
    $pdf->SetFont('arial','B',11);
    $pdf->Cell(24,7,'Millares',1,0,'C',true); 
    $pdf->Cell(24,7,'Peso Bruto',1,1,'C',true); 
 
    $pdf->Cell(28,8,'',0,0,'R');
    $pdf->Cell(24,7,number_format($rows['pieza'],3,'.',','),1,0,'C',true); 
    $pdf->Cell(24,7,number_format($rows['pesaje'],3,'.',','),1,1,'C',true); 
    

    $pdf->Output('Lista de embarque '.$vntsEmbarque_cdgembarque.' '.$vntsEmbarque_sucursal.'.pdf', 'D');
  }
  else{
    echo '<!DOCTYPE html>
            <html>
              <head>
                <meta charset="utf-8">
                <title>Lista de embarque</title>
                <link rel="stylesheet" type="text/css" href="/css/2014.css">
              </head>
              <body>
                <div id="contenedor"><h1>Lista de embarque no encontrada</h1></div>
              </body>
            </html>';
  }
}  ?>