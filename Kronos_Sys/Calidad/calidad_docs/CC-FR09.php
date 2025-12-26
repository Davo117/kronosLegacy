<?php
include '../../Database/db.php'; 
include '../../fpdf/fpdf.php';

  class PDF extends FPDF
  { // Cabecera de página
    function Header()
    { global $oplist_ancho;

      $this->SetFont('Arial','B',7);
  
      if (file_exists('../../pictures/lolo.jpg')==true)    { 
      $this->Image('../../pictures/lolo.jpg',10,10,0,20); 
    }   


      $this->SetFillColor(224,7,8);
      $this->Cell(150,3.5,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,3.5,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,3.5,utf8_decode('Certificado de Calidad'),0,1,'L');

      $this->SetFont('Arial','B',7);
      $this->Cell(150,3.5,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,3.5,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,3.5,utf8_decode('CC-FR09'),0,1,'L');

      $this->SetFont('Arial','B',7);
      $this->Cell(150,3.5,utf8_decode('Versión'),0,0,'R');
      $this->Cell(0.5,3.5,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,3.5,utf8_decode('7'),0,1,'L');

      $this->SetFont('Arial','B',7);
      $this->Cell(150,3.5,utf8_decode('Revisión'),0,0,'R');
      $this->Cell(0.5,3.5,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,3.5,utf8_decode('23 de enero 2019'),0,1,'L');

      $this->SetFont('Arial','B',7);
      $this->Cell(150,3.5,utf8_decode('Responsable'),0,0,'R');
      $this->Cell(0.5,3.5,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,3.5,utf8_decode('Jefe de calidad'),0,1,'L'); 

      $this->SetFont('Arial','B',7);
      $this->Cell(150,3.5,utf8_decode('Disponibilidad'),0,0,'R');
      $this->Cell(0.5,3.5,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,3.5,utf8_decode('Físico/A prueba'),0,1,'L'); 


      $this->Ln(4); }

    // Pie de página
    function Footer()
    { 

      $this->SetY(-20);
      $this->SetX(70);
      $this->SetFont('Arial','B',8);
      $this->Cell(195.9,4,'_____________________________',0,1,'C');
      $this->SetX(70);
      $this->Cell(195.9,4,utf8_decode('Elaboró'),0,1,'C');
      $this->SetX(70);
      $this->Cell(195.9,4,utf8_decode('Control de calidad'),0,1,'C');
   }
  }

    $pdf = new PDF('P','mm','letter');
  //$pdf->SetDisplayMode(real, continuous);
  	$pdf->AddPage();
  	$pdf->SetFont('Arial','B',7);
  	$pdf->SetFillColor(241,241,241);
  	$pdf->Cell(35,3.5,'Cliente',0,0,'L',true);
  	$pdf->SetFont('Arial','',6);
  	$pdf->Cell(65,3.5,'___________________________',0,0,'L',false);
  	$pdf->SetFont('Arial','B',7);
    $pdf->Cell(40,3.5,'Folio',0,0,'L',true);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(65,3.5,'___________________________',0,1,'L',false);

    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(35,3.5,'Materiales',0,0,'L',true);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(65,3.5,'___________________________',0,0,'L',false);
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(40,3.5,utf8_decode('Presentación'),0,0,'L',true);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(65,3.5,'___________________________',0,1,'L',false);

    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(35,3.5,'Lote de embarque',0,0,'L',true);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(65,3.5,'___________________________',0,0,'L',false);
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(40,3.5,utf8_decode('Fecha de producción'),0,0,'L',true);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(65,3.5,'___________________________',0,1,'L',false);

    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(35,3.5,'Cantidad enviada',0,0,'L',true);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(65,3.5,'___________________________',0,0,'L',false);
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(40,3.5,utf8_decode('Fecha de envío'),0,0,'L',true);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(65,3.5,'___________________________',0,1,'L',false);

    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(35,3.5,'Orden de compra',0,0,'L',true);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(70,3.5,'___________________________',0,1,'L',false);
  	$pdf->ln(2);
  	$pdf->SetFont('Arial','B',7);
  	$pdf->SetFillColor(223,223,223);
  	$pdf->Cell(0,3.5,utf8_decode('Descripción del producto'),0,1,'C',true);
  	$pdf->ln(2);
  	$pdf->SetFont('Arial','B',7);
    $pdf->Cell(65,3.5,'Nombre del Producto',0,0,'C',true);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(150,3.5,'______________________________________________________',0,1,'L',false);
    $pdf->ln(2);
  	$pdf->SetFont('Arial','B',7);
    $pdf->Cell(45,3.5,utf8_decode('Impresión:'),0,0,'L',true);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(150,3.5,'___________________________',0,1,'L',false);

    $pdf->ln(2);

  	$pdf->SetFont('Arial','',6);
    $pdf->Cell(15,3.5,utf8_decode('Tinta 1'),0,0,'C',true);
    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(25,3.5,'',B,0,'L',false);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(15,3.5,utf8_decode('Tinta 2'),0,0,'C',true);
    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(25,3.5,'',B,0,'L',false);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(15,3.5,utf8_decode('Tinta 3'),0,0,'C',true);
    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(25,3.5,'',B,0,'L',false);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(15,3.5,utf8_decode('Tinta 4'),0,0,'C',true);
    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(25,3.5,'',B,0,'L',false);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(11,3.5,utf8_decode('Tinta 5'),0,0,'C',true);
    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(25,3.5,'',B,1,'L',false);


    $pdf->SetFont('Arial','',6);
    $pdf->Cell(15,3.5,utf8_decode('Tinta 6'),0,0,'C',true);
    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(25,3.5,'',B,0,'L',false);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(15,3.5,utf8_decode('Tinta 7'),0,0,'C',true);
    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(25,3.5,'',B,0,'L',false);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(15,3.5,utf8_decode('Tinta 8'),0,0,'C',true);
    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(25,3.5,'',B,0,'L',false);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(15,3.5,utf8_decode('Tinta 9'),0,0,'C',true);
    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(25,3.5,'',B,0,'L',false);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(11,3.5,utf8_decode('Tinta 10'),0,0,'C',true);
    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(25,3.5,'',B,1,'L',false);
    $pdf->ln(2);
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(10,3.5,utf8_decode('Nota:'),B,0,'C');
    $pdf->SetFont('Arial','I',7);
    $pdf->Cell(94,3.5,'Los valores de los pantones pueden variar de acuerdo al sustrato en el que se aplica.',B,1,'L',false);

    $pdf->ln(2);
  	$pdf->SetFont('Arial','B',7);
  	$pdf->SetFillColor(223,223,223);
  	$pdf->Cell(0,3.5,utf8_decode('Especifícaciones Físicas'),0,1,'C',true);
  	$pdf->ln(2);

  	$pdf->setX(60);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(16,3.5,utf8_decode('Máximo'),B,0,'L',true);
    $pdf->Cell(5,3.5,'',0,0,'L',true);
    $pdf->Cell(16,3.5,utf8_decode('Mínimo'),B,1,'L',true);
    $pdf->Cell(50,3.5,utf8_decode('Temperatura de encogimiento'),0,0,'R',false);
    $pdf->Cell(18,3.5,utf8_decode('95 C'),1,0,'R',false);
    $pdf->Cell(19,3.5,utf8_decode('90 C'),1,0,'R',false);

    $pdf->Cell(28,14,utf8_decode('Holograma'),0,0,'R',false);
    $pdf->Cell(28,14,'Cristal Gota',1,0,'C',false);

    $pdf->Cell(39,3.5,'Largo de fotocelda (spot)',0,0,'R',false);
    $pdf->Cell(14,3.5,'N/A',1,1,'R',false);
    $pdf->Cell(50,10.5,'Velocidad de encogimiento',0,0,'R',false);
    $pdf->Cell(18,3.5,utf8_decode('6°-10'),'T1L1R1',0,'C',false);
    $pdf->Cell(19,10.5,'N/A',1,0,'C',false);
    $pdf->Cell(95,10.5,'Ancho de la fotocelda (spot)',0,0,'R',false);
    $pdf->Cell(14,10.5,'N/A',1,0,'R',false);
    $pdf->Cell(14,3.5,'',0,1,'R',false);
    $pdf->setX(60);
    $pdf->Cell(18,3.5,utf8_decode('seg/Metro'),'L1R1',0,'C');
    $pdf->Cell(14,3.5,'',0,1,'R',false);
    $pdf->setX(60);
    $pdf->Cell(18,3.5,utf8_decode('lineal'),'L1R1B1',1,'C');
    $pdf->Cell(50,3.5,utf8_decode('Registro de impresión'),0,0,'R',false);
    $pdf->Cell(18,3.5,utf8_decode('1.3 mm'),1,0,'R',false);
    $pdf->Cell(19,3.5,utf8_decode('0.3 mm'),1,0,'R',false);
    $pdf->Cell(28,3.5,'Adhesivo',0,0,'R',false);
    $pdf->Cell(28,3.5,'N/A',1,1,'R',false);

    $pdf->Cell(50,3.5,utf8_decode('Encogimiento longitudinal'),0,0,'R',false);
    $pdf->Cell(18,3.5,utf8_decode('1.3 mm'),1,0,'R',false);
    $pdf->Cell(19,3.5,utf8_decode('0.3 mm'),1,0,'R',false);
    $pdf->Cell(28,3.5,'Core',0,0,'R',false);
    $pdf->Cell(28,3.5,'N/A',1,1,'R',false);

    $pdf->Cell(50,3.5,utf8_decode('Encogimiento transversal'),0,0,'R',false);
    $pdf->Cell(18,3.5,utf8_decode('1.3 mm'),1,0,'R',false);
    $pdf->Cell(19,3.5,utf8_decode('0.3 mm'),1,0,'R',false);
    $pdf->Cell(28,3.5,utf8_decode('Máximo de uniones'),0,0,'R',false);
    $pdf->Cell(28,3.5,'N/A',1,1,'R',false);
    $pdf->ln(1);
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(65,3.5,utf8_decode('Otras Especifícaciones'),0,1,'C',true);
    $pdf->ln(1);
    $pdf->Cell(0,10,'',1,1,'C');
    $pdf->ln(1);
    $pdf->Cell(0,3.5,utf8_decode('Métodos de identificación'),0,1,'C',true);
  	$pdf->ln(2);

  	$pdf->setX(30);
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(44,3.5,utf8_decode('Presentación'),1,0,'C',true);
    $pdf->Cell(35,3.5,utf8_decode('Tolerancia'),1,0,'C',true);
    $pdf->Cell(34,3.5,'',0,0,'L',false);
    $pdf->Cell(63,3.5,utf8_decode('Identificación en etiqueta de producto'),1,1,'C',true);

    $pdf->SetFont('Arial','',6);
    $pdf->Cell(20,7,utf8_decode('Corte'),0,0,'R');
    
    $pdf->setX(30);
    $pdf->Cell(44,3.5,utf8_decode('Empaque individual en bolsa de'),'T1L1R1',0,'C');
    $pdf->Cell(35,7,utf8_decode('(+/-) 5 piezas'),1,0,'C');
    $pdf->Cell(34,38,utf8_decode('Rollo/Caja'),0,0,'R');
    $pdf->Cell(63,3.5,utf8_decode('1-Nombre del producto'),'T1L1R1',1,'L');
    $pdf->setX(30);
    $pdf->Cell(44,3.5,utf8_decode('polietileno de 500 pz por paquete'),'B1L1R1',0,'C');
    $pdf->setX(143);
    $pdf->Cell(63,3.5,utf8_decode('2-Número de empaque'),'L1R1',0,'L');
    $pdf->Cell(5,3.5,utf8_decode(''),0,1,'R');

    $pdf->Cell(20,7,utf8_decode('Bobina'),0,0,'R');
    $pdf->setX(30);
    $pdf->Cell(44,3.5,utf8_decode('Empaque en bolsas de polietileno'),'T1L1R1',0,'C');
    $pdf->Cell(35,10.5,utf8_decode('(+/-) 10%'),1,0,'C');
    $pdf->setX(143);
    $pdf->Cell(63,3.5,utf8_decode('3-Número de lote interno'),'L1R1',1,'L');
    $pdf->setX(30);
    $pdf->Cell(44,3.5,utf8_decode('dentro de cartón corrugado single'),'L1R1',0,'C');
    $pdf->setX(143);
    $pdf->Cell(63,3.5,utf8_decode('4-Longitud'),L1R1,0,'L');
    $pdf->Cell(5,3.5,utf8_decode(''),0,1,'R');

    $pdf->setX(30);
    $pdf->Cell(44,3.5,utf8_decode('face 3 bobinas máximo por paquete'),'B1L1R1',0,'C');
    $pdf->setX(143);
    $pdf->Cell(63,3.5,utf8_decode('5-Ancho plano'),'L1R1',1,'L');

    $pdf->Cell(20,3.5,utf8_decode('Pedido'),0,0,'R');
    $pdf->setX(30);
    $pdf->Cell(44,7,utf8_decode('N/A'),1,0,'C');
    $pdf->Cell(35,7,utf8_decode('(+/-) 5%'),1,0,'C');
    $pdf->setX(143);
    $pdf->Cell(63,3.5,utf8_decode('6-Peso'),'L1R1',1,'L');
    $pdf->Cell(20,3.5,utf8_decode('Solicitado'),0,0,'R');
    $pdf->setX(143);
    $pdf->Cell(63,3.5,utf8_decode('7-Código del producto'),'L1R1',1,'L');
    $pdf->setX(143);
    $pdf->Cell(63,3.5,utf8_decode('8-Corte'),'L1R1',1,'L');
    $pdf->setX(143);
    $pdf->Cell(63,3.5,utf8_decode('9-Piezas aproximadas'),'L1R1',1,'L');
    $pdf->setX(143);
    $pdf->Cell(63,3.5,utf8_decode('10-Número de paquetes por caja/número de rollos por paquete'),'L1R1',1,'L');
    $pdf->setX(143);
    $pdf->Cell(63,3.5,utf8_decode('11-Temperatura de almacenamiento'),'B1L1R1',0,'L');
    $pdf->Cell(5,3.5,utf8_decode(''),0,1,'R');

    $pdf->ln(1);
    $pdf->Cell(0,3.5,utf8_decode('Condiciones de almacenamiento'),0,1,'C',true);
    $pdf->ln(2);
    $pdf->MultiCell(0,3,utf8_decode('Deberá almacenarse en un área higiénica,fresca y seca. Protegida contra la intemperie, humedad, luz solar o incandescente sobre tartimas separadas a 15 cm de la pared con una temperatura'."\n"."ambiente y lejos de cualquier fuente de calor."."\n"."El tiempo de almacenamiento bajo las condiciones mencionadas es de 1 año a partir de la emosión de este certificado y deberá almacenarse a temperatura ambiente ≤30°c lejos de cualquier fuente de calor."),1,'C');
    $pdf->ln(1);
    $pdf->ln(1);
    $pdf->Cell(0,3.5,utf8_decode('Condiciones de envío en caso de devolución'),0,1,'C',true);
    $pdf->ln(2);
    $pdf->MultiCell(0,2,utf8_decode("Para que una devolución sea aceptada, el material debe presentarse con los siguientes requisitos:"."\n"."
- Deben respetarse las condiciones de almacenaje"."\n"."
- El material debe contar con la protección con la que el cliente lo recibió (Empaque)"."\n"."
- Debe contener etiqueta de identificación en el core o centro de cartón"."\n"."
- Si la devolución es pieza cortada deberá contar con el código de la caja"),'L1R1T1','L');
    $pdf->Cell(10,3.5,utf8_decode('Garantía.'),'B1L1',0,'C');
    $pdf->SetFont('Arial','B',6);
    $pdf->Cell(185.9,3.5,utf8_decode('El tiempo de reposición del material es aceptado solo después de 30 días a partir del último lote recibido de la presentación. Además de que para hacer'),'B1R1',1,'L');
    $pdf->ln(1);
    $pdf->Cell(0,3.5,utf8_decode('Parámetros técnicos'),0,1,'C',true);
    $pdf->ln(2);
    $pdf->setX(40);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(15.7,3.5,utf8_decode('Ancho'),'T1L1R1',0,'C',true);
    $pdf->Cell(20,3.5,utf8_decode('Corte/Distancia'),'T1L1R1',0,'C',true);
    $pdf->Cell(15,3.5,utf8_decode('Diámetro'),'T1L1R1',0,'C',true);
    $pdf->Cell(15,10.5,utf8_decode('Calibre'),1,0,'C',true);
    $pdf->Cell(35,10.5,utf8_decode('Cantidad de etiquetas por bobina'),1,0,'C',true);
    $pdf->Cell(30,3.5,utf8_decode('Cantidad de etiquetas'),1,0,'C',true);
    $pdf->Cell(20,3.5,utf8_decode('Leyenda de'),'T1L1R1',0,'C',true);
    $pdf->Cell(15,3.5,utf8_decode('Tono'),'T1L1R1',1,'C',true);
    $pdf->setX(40);
    $pdf->Cell(15.7,3.5,utf8_decode('plano'),'B1L1R1',0,'C',true);
    $pdf->Cell(20,3.5,utf8_decode('promedio en'),'L1R1',0,'C',true);
    $pdf->Cell(15,3.5,utf8_decode('interno de'),'L1R1',0,'C',true);
    $pdf->setX(140.7);
    $pdf->Cell(30,3.5,utf8_decode('por paquetes'),'B1L1R1',0,'C',true);
    $pdf->Cell(20,3.5,utf8_decode('holograma'),'B1L1R1',0,'C',true);
    $pdf->Cell(15,3.5,utf8_decode('autorizado'),'B1L1R1',1,'C',true);
    $pdf->setX(40);
    $pdf->Cell(15.7,3.5,utf8_decode(''),'B1L1R1',0,'C',true);
    $pdf->Cell(20,3.5,utf8_decode('repeticiones'),'B1L1R1',0,'C',true);
    $pdf->Cell(15,3.5,utf8_decode('core'),'B1L1R1',0,'C',true);
    $pdf->setX(140.7);
    $pdf->Cell(30,3.5,utf8_decode(''),'B1L1R1',0,'C',true);
    $pdf->Cell(20,3.5,utf8_decode(''),'B1L1R1',0,'C',true);
    $pdf->Cell(15,3.5,utf8_decode(''),'B1L1R1',1,'C',true);
    $pdf->Cell(30,3.5,utf8_decode('Parámetros de control'),1,0,'C',true);
    $pdf->Cell(15.7,3.5,utf8_decode('107 ± 2'),1,0,'C');
    $pdf->Cell(20,3.5,utf8_decode('70  ± 2'),1,0,'C');
    $pdf->Cell(15,3.5,utf8_decode('N/A'),1,0,'C');
    $pdf->Cell(15,3.5,utf8_decode('50 ±  5µ'),1,0,'C');
    $pdf->Cell(35,3.5,utf8_decode('N/A'),1,0,'C');
    $pdf->Cell(30,3.5,utf8_decode('500  ±  5 Piezas'),1,0,'C');
    $pdf->Cell(20,3.5,utf8_decode('Cristal Gota'),1,0,'C');
    $pdf->Cell(15,3.5,utf8_decode('Carpeta Labro'),1,1,'C');
    $pdf->ln(2);
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(10,3.5,utf8_decode('Nota:'),B,0,'C');
    $pdf->SetFont('Arial','I',7);
    $pdf->Cell(90,3.5,'VAP (Valores aproximados dependiendo de la zona en la que se mida).',B,1,'L',false);

    $pdf->ln(2);

  	$pdf->Output('CC-FR09.pdf', 'I');
  ?>