<?php
include '../../Database/db.php'; 
include '../../fpdf/fpdf.php';

  class PDF extends FPDF
  { // Cabecera de página
    function Header()
    { global $oplist_ancho;

      $this->SetFont('Arial','B',8);
  
      if (file_exists('../../pictures/lolo.jpg')==true)    { 
      $this->Image('../../pictures/lolo.jpg',10,10,0,20); 
    }   


      $this->SetFillColor(224,7,8);
      $this->Cell(150,4,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Certificado de Calidad'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(150,4,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('CC-FR09'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(150,4,utf8_decode('Versión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('7'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(150,4,utf8_decode('Revisión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('23 de enero 2019'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(150,4,utf8_decode('Responsable'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Jefe de calidad'),0,1,'L'); 

      $this->SetFont('Arial','B',8);
      $this->Cell(150,4,utf8_decode('Disponibilidad'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Físico/A prueba'),0,1,'L'); 


      $this->Ln(4); }

    // Pie de página
    function Footer()
    { $this->SetY(-8);
      $this->SetFont('arial','',8);
      $this->Cell(0,3,utf8_decode('Página '.$this->PageNo().' de {nb}'),0,1,'R');
      $this->SetFont('arial','B',8);
      $this->Cell(0,3,'Consultado el '.date("Y-m-d").' a las '.date("G:i:s"),0,1,'R'); }
  }

    $pdf = new PDF('P','mm','letter');
  //$pdf->SetDisplayMode(real, continuous);
  	$pdf->AddPage();
  	$pdf->SetFont('Arial','B',8);
  	$pdf->SetFillColor(241,241,241);
  	$pdf->Cell(35,4,'Cliente',0,0,'L',true);
  	$pdf->SetFont('Arial','',8);
  	$pdf->Cell(65,4,'___________________________',0,0,'L',false);
  	$pdf->SetFont('Arial','B',8);
    $pdf->Cell(40,4,'Folio',0,0,'L',true);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(65,4,'___________________________',0,1,'L',false);

    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(35,4,'Materiales',0,0,'L',true);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(65,4,'___________________________',0,0,'L',false);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(40,4,utf8_decode('Presentación'),0,0,'L',true);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(65,4,'___________________________',0,1,'L',false);

    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(35,4,'Lote de embarque',0,0,'L',true);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(65,4,'___________________________',0,0,'L',false);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(40,4,utf8_decode('Fecha de producción'),0,0,'L',true);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(65,4,'___________________________',0,1,'L',false);

    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(35,4,'Cantidad enviada',0,0,'L',true);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(65,4,'___________________________',0,0,'L',false);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(40,4,utf8_decode('Fecha de envío'),0,0,'L',true);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(65,4,'___________________________',0,1,'L',false);

    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(35,4,'Orden de compra',0,0,'L',true);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(70,4,'___________________________',0,1,'L',false);
  	$pdf->ln(2);
  	$pdf->SetFont('Arial','B',8);
  	$pdf->SetFillColor(223,223,223);
  	$pdf->Cell(0,4,utf8_decode('Descripción del producto'),0,1,'C',true);
  	$pdf->ln(2);
  	$pdf->SetFont('Arial','B',8);
    $pdf->Cell(65,4,'Nombre del Producto',0,0,'C',true);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(150,4,'______________________________________________________',0,1,'L',false);
    $pdf->ln(2);
  	$pdf->SetFont('Arial','B',8);
    $pdf->Cell(45,4,utf8_decode('Impresión:'),0,0,'L',true);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(150,4,'___________________________',0,1,'L',false);

    $pdf->ln(2);

  	$pdf->SetFont('Arial','',8);
    $pdf->Cell(15,4,utf8_decode('Tinta 1'),0,0,'C',true);
    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(25,4,'',B,0,'L',false);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(15,4,utf8_decode('Tinta 2'),0,0,'C',true);
    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(25,4,'',B,0,'L',false);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(15,4,utf8_decode('Tinta 3'),0,0,'C',true);
    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(25,4,'',B,0,'L',false);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(15,4,utf8_decode('Tinta 4'),0,0,'C',true);
    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(25,4,'',B,0,'L',false);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(15,4,utf8_decode('Tinta 5'),0,0,'C',true);
    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(25,4,'',B,1,'L',false);


    $pdf->SetFont('Arial','',8);
    $pdf->Cell(15,4,utf8_decode('Tinta 6'),0,0,'C',true);
    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(25,4,'',B,0,'L',false);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(15,4,utf8_decode('Tinta 7'),0,0,'C',true);
    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(25,4,'',B,0,'L',false);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(15,4,utf8_decode('Tinta 8'),0,0,'C',true);
    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(25,4,'',B,0,'L',false);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(15,4,utf8_decode('Tinta 9'),0,0,'C',true);
    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(25,4,'',B,0,'L',false);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(15,4,utf8_decode('Tinta 10'),0,0,'C',true);
    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(25,4,'',B,1,'L',false);
    $pdf->ln(2);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(10,4,utf8_decode('Nota:'),B,0,'C');
    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(135,4,'Los valores de los pantones pueden variar de acuerdo al sustrato en el que se aplica.',B,1,'L',false);

    $pdf->ln(2);
  	$pdf->SetFont('Arial','B',8);
  	$pdf->SetFillColor(223,223,223);
  	$pdf->Cell(0,4,utf8_decode('Especifícaciones Físicas'),0,1,'C',true);
  	$pdf->ln(2);

  	$pdf->setX(60);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(16,4,utf8_decode('Máximo'),B,0,'L',true);
    $pdf->Cell(5,4,'',0,0,'L',true);
    $pdf->Cell(16,4,utf8_decode('Mínimo'),B,1,'L',true);
    $pdf->ln(1);
    $pdf->Cell(50,4,utf8_decode('Temperatura de encogimiento'),0,0,'R',false);
    $pdf->Cell(18,4,utf8_decode('95 C'),1,0,'R',false);
    $pdf->Cell(19,4,utf8_decode('90 C'),1,0,'R',false);

    $pdf->Cell(28,16,utf8_decode('Holograma'),0,0,'R',false);
    $pdf->Cell(28,17,'Cristal Gota',1,0,'C',false);

    $pdf->Cell(39,4,'Largo de fotocelda (spot)',0,0,'R',false);
    $pdf->Cell(14,4,'N/A',1,1,'R',false);
    $pdf->Cell(50,13,'Velocidad de encogimiento',0,0,'R',false);
    $pdf->Cell(18,13,'Cristal Gota',1,0,'C',false);
    $pdf->Cell(19,13,'Cristal Gota',1,0,'C',false);
    $pdf->Cell(95,12,'Ancho de la fotocelda (spot)',0,0,'R',false);
    $pdf->Cell(14,11,'N/A',1,1,'R',false);
	$pdf->ln(2);
    $pdf->Cell(50,4,utf8_decode('Registro de impresión'),0,0,'R',false);
    $pdf->Cell(18,4,utf8_decode('1.3 mm'),1,0,'R',false);
    $pdf->Cell(19,4,utf8_decode('0.3 mm'),1,0,'R',false);
    $pdf->Cell(28,4,'Adhesivo',0,0,'R',false);
    $pdf->Cell(28,4,'N/A',1,1,'R',false);

    $pdf->Cell(50,4,utf8_decode('Encogimiento longitudinal'),0,0,'R',false);
    $pdf->Cell(18,4,utf8_decode('1.3 mm'),1,0,'R',false);
    $pdf->Cell(19,4,utf8_decode('0.3 mm'),1,0,'R',false);
    $pdf->Cell(28,4,'Core',0,0,'R',false);
    $pdf->Cell(28,4,'N/A',1,1,'R',false);

    $pdf->Cell(50,4,utf8_decode('Encogimiento transversal'),0,0,'R',false);
    $pdf->Cell(18,4,utf8_decode('1.3 mm'),1,0,'R',false);
    $pdf->Cell(19,4,utf8_decode('0.3 mm'),1,0,'R',false);
    $pdf->Cell(28,4,utf8_decode('Máximo de uniones'),0,0,'R',false);
    $pdf->Cell(28,4,'N/A',1,1,'R',false);
    $pdf->ln(1);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(65,4,utf8_decode('Otras Especifícaciones'),0,1,'C',true);
    $pdf->ln(1);
    $pdf->Cell(0,10,'',1,1,'C');
    $pdf->ln(1);
    $pdf->Cell(0,4,utf8_decode('Métodos de identificación'),0,1,'C',true);
  	$pdf->ln(1);

  	$pdf->setX(30);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(35,4,utf8_decode('Presentación'),1,0,'C',true);
    $pdf->Cell(35,4,utf8_decode('Tolerancia'),1,0,'C',true);
    $pdf->Cell(46,4,'',0,0,'L',false);
    $pdf->Cell(55,4,utf8_decode('Identificación en etiqueta de producto'),1,1,'C',true);

    $pdf->SetFont('Arial','',8);
    $pdf->Cell(20,8,utf8_decode('Corte'),0,0,'C');
    $cadena="Empaque individual en bolsa de plastico";
    $longC1=strlen($cadena);
    $limite=25;
    if($longC1>$limite)
    {
      $counter=ceil($longC1/$limite);
      for($i=0;$i<$counter;$i++)
      {
        $inicio=$i*$limite;
        if($i==0)
        {
          $pdf->Cell(35,4,utf8_decode(substr($cadena,$inicio,$limite)),T1L1R1,0,'L',true);
          $pdf->Cell(35,4*$counter,utf8_decode('Tolerancia'),1,0,'C');
          $pdf->Cell(46,16,utf8_decode('Rollo/Caja'),0,0,'R');
          $pdf->setX(30);
        }
        else if($i==$counter-1)
        {
           $pdf->Cell(35,4,utf8_decode(substr($cadena,$inicio,$limite)),B1L1R1,1,'L',true);
        }
        else
        {
          $pdf->Cell(35,4,utf8_decode(substr($cadena,$inicio,$limite)),L1R1,1,'L',true);
          $pdf->setX(30);
        }
        
      }
    }
    else
    {
      $pdf->Cell(35,8,utf8_decode($cadena),1,0,'C');
    }

          $pdf->Cell(55 ,4,utf8_decode('1-Nombre del producto'),1,0,'L');
          $pdf->Cell(5,4,utf8_decode(''),0,1,'R');
          $pdf->setX(100);
          $pdf->Cell(55,4,utf8_decode('2-Número de empaque'),1,0,'L');
          $pdf->Cell(5,4,utf8_decode(''),0,1,'R');

    $pdf->Cell(35,8,utf8_decode('okabuenosi'),1,0,'C');
    $pdf->Cell(46,8,'',0,0,'L',false);
    $pdf->Cell(55,8,utf8_decode('Identificación en etiqueta de producto'),1,1,'C');

  	$pdf->Output('CC-FR09.pdf', 'I');
  ?>