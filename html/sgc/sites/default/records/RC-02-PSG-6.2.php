<?php
  require('fpdf/fpdf.php');

  class PDF extends FPDF
  { // Cabecera de página
    function Header()
    { if (file_exists('../images/logo.jpg')==true) 
      { $this->Image('../images/logo.jpg',10,5,0,25); }
      
      $this->SetFillColor(255,153,0);

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Perfíl y descripción de puesto'),0,1,'L');
      
      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('RC-02-PSG-6.2'),0,1,'L');      
      
      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Versión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('1.0'),0,1,'L');      
      
      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Fecha de revisión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Abril 21, 2014'),0,1,'L');      
      
      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Responsable'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Recursos Humanos'),0,1,'L');

      $this->Ln(4.15); }

    // Pie de página
    function Footer()
    { $this->SetFont('arial','',8);
      $this->SetY(-7.5);
      $this->Cell(0,6,utf8_decode('Grupo Labro | Sistema de Gestión de Calidad, página '.$this->PageNo().' de {nb}'),0,1,'R'); }
  }

  $pdf = new PDF('P','mm','letter');
  $pdf->SetDisplayMode(real, continuous);
  $pdf->AliasNbPages();

  $pdf->AddPage();
  $pdf->SetFillColor(255,153,0);

  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(10,8.3,utf8_decode('1 Identificación del puesto'),0,1,'L');

  $pdf->SetFont('Arial','',8);
  $pdf->Cell(40,4.15,utf8_decode('Nombre del puesto'),0,0,'R');  
  $pdf->Cell(0.5,4.15,utf8_decode(''),0,1,'C',true);
  $pdf->Line(51,$pdf->GetY(),115,$pdf->GetY());

  $pdf->Cell(40,4.15,utf8_decode('Departamento'),0,0,'R');  
  $pdf->Cell(0.5,4.15,utf8_decode(''),0,1,'C',true);  
  $pdf->Line(51,$pdf->GetY(),115,$pdf->GetY());

  $pdf->Cell(40,4.15,utf8_decode('Supervisión inmediata'),0,0,'R');  
  $pdf->Cell(0.5,4.15,utf8_decode(''),0,1,'C',true);  
  $pdf->Line(51,$pdf->GetY(),115,$pdf->GetY());

  $pdf->Cell(40,4.15,utf8_decode('Puesto(s) que supervisa'),0,0,'R');  
  $pdf->Cell(0.5,4.15,utf8_decode(''),0,1,'C',true);  
  $pdf->Line(51,$pdf->GetY(),115,$pdf->GetY());

  $pdf->Cell(40,4.15,utf8_decode('Edad'),0,0,'R');  
  $pdf->Cell(0.5,4.15,utf8_decode(''),0,1,'C',true);  
  $pdf->Line(51,$pdf->GetY(),85,$pdf->GetY());

  $pdf->Cell(40,4.15,utf8_decode('Sexo'),0,0,'R');  
  $pdf->Cell(0.5,4.15,utf8_decode(''),0,0,'C',true);  
  $pdf->Cell(2,4.15,utf8_decode(''),0,0,'C');
  $pdf->Cell(4,4.15,utf8_decode('__'),0,0,'C');  
  $pdf->Cell(15,4.15,utf8_decode('Femenino'),0,0,'L');  
  $pdf->Cell(4,4.15,utf8_decode('__'),0,0,'C');  
  $pdf->Cell(15,4.15,utf8_decode('Masculino'),0,0,'L');  
  $pdf->Cell(4,4.15,utf8_decode('__'),0,0,'C');  
  $pdf->Cell(15,4.15,utf8_decode('Indistinto'),0,1,'L');  

  $pdf->Ln(4.15);

  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(10,10,utf8_decode('2 Objetivo del puesto'),0,1,'L');

  for ($id=1;$id<=3;$id++) 
  { $pdf->Ln(4.15);
    $pdf->Line(20,$pdf->GetY(),200,$pdf->GetY()); }

  $pdf->Ln(4.15);

  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(10,10,utf8_decode('3 Ubicación en el organigrama'),0,1,'L');

  for ($id=1;$id<=1;$id++) 
  { $pdf->Ln(4.15);
    $pdf->Line(20,$pdf->GetY(),200,$pdf->GetY()); }

  $pdf->Ln(4.15);

  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(10,10,utf8_decode('4 Actividades del puesto'),0,1,'L');

  $pdf->SetFont('Arial','B',9);

  $pdf->Cell(126,4,utf8_decode(''),0,0,'L');
  $pdf->Cell(2,4,utf8_decode(''),0,0,'C');  
  $pdf->Cell(22,4,utf8_decode('Frecuencia'),1,0,'C',true);  
  $pdf->Cell(2,4,utf8_decode(''),0,0,'C'); 
  $pdf->Cell(38,8.5,utf8_decode('Indicador'),1,1,'C',true); 
  $pdf->Ln(-4);
  
  $pdf->Cell(5,4.15,utf8_decode(''),0,0,'L');
  $pdf->Cell(121,4.15,utf8_decode('4.1 Actividad o función'),0,0,'L');
  $pdf->Cell(2,4,utf8_decode(''),0,0,'C');  
  $pdf->Cell(4,3.5,utf8_decode('D'),1,0,'C',true);  
  $pdf->Cell(2,4,utf8_decode(''),0,0,'C');  
  $pdf->Cell(4,3.5,utf8_decode('S'),1,0,'C',true);  
  $pdf->Cell(2,4,utf8_decode(''),0,0,'C');  
  $pdf->Cell(4,3.5,utf8_decode('M'),1,0,'C',true);  
  $pdf->Cell(2,4,utf8_decode(''),0,0,'C');  
  $pdf->Cell(4,3.5,utf8_decode('A'),1,1,'C',true);   

  $pdf->Ln(1);
  $pdf->SetFont('Arial','',8);
  for ($id=1;$id<=20;$id++) 
  { $pdf->Cell(126,3.5,utf8_decode(''),0,0,'L');
    $pdf->Cell(2,4,utf8_decode(''),0,0,'C');  
    $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');  
    $pdf->Cell(2,4,utf8_decode(''),0,0,'C');  
    $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');  
    $pdf->Cell(2,4,utf8_decode(''),0,0,'C');  
    $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');  
    $pdf->Cell(2,4,utf8_decode(''),0,0,'C');  
    $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');   
    $pdf->Cell(2,4.15,utf8_decode(''),0,1,'C');  

    $pdf->Line(20,$pdf->GetY(),135,$pdf->GetY());
    $pdf->Line(161,$pdf->GetY(),200,$pdf->GetY()); }

  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(10,10,utf8_decode('5 Competencias técnicas del puesto'),0,1,'L');

  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(5,4.15,utf8_decode(''),0,0,'L');
  $pdf->Cell(50,4.15,utf8_decode('5.1 Educación'),0,0,'L');

  $pdf->SetFont('Arial','',8);
  $pdf->Cell(16,4.15,utf8_decode('Mínimo'),0,0,'C');
  $pdf->Cell(16,4.15,utf8_decode('Óptimo'),0,0,'C');

  $pdf->Cell(60,4.15,utf8_decode(''),0,0,'L');
  $pdf->Cell(16,4.15,utf8_decode('Mínimo'),0,0,'C');
  $pdf->Cell(16,4.15,utf8_decode('Óptimo'),0,1,'C');

  $pdf->Cell(10,4.15,utf8_decode(''),0,0,'C');
  $pdf->Cell(60,4.15,utf8_decode('Alfabetización'),0,0,'L');
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');
  $pdf->Cell(12,3.5,utf8_decode(''),0,0,'C');
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');
  $pdf->Cell(12,3.5,utf8_decode(''),0,0,'C');

  $pdf->Cell(60,4.15,utf8_decode('T.S.U. _____________________'),0,0,'L');
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');  
  $pdf->Cell(12,3.5,utf8_decode(''),0,0,'C');  
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');  
  $pdf->Cell(12,3.5,utf8_decode(''),0,0,'C');  
  $pdf->Cell(4,4.15,utf8_decode(''),0,1,'C');  

  $pdf->Cell(10,4.15,utf8_decode(''),0,0,'C');
  $pdf->Cell(60,4.15,utf8_decode('Primaria'),0,0,'L');
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');
  $pdf->Cell(12,3.5,utf8_decode(''),0,0,'C');
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');
  $pdf->Cell(12,3.5,utf8_decode(''),0,0,'C');

  $pdf->Cell(60,4.15,utf8_decode('Universidad trunca'),0,0,'L');
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');  
  $pdf->Cell(12,3.5,utf8_decode(''),0,0,'C');  
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');  
  $pdf->Cell(12,3.5,utf8_decode(''),0,0,'C');  
  $pdf->Cell(4,4.15,utf8_decode(''),0,1,'C');  

  $pdf->Cell(10,4.15,utf8_decode(''),0,0,'C');
  $pdf->Cell(60,4.15,utf8_decode('Secundaria'),0,0,'L');
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');
  $pdf->Cell(12,3.5,utf8_decode(''),0,0,'C');
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');
  $pdf->Cell(12,3.5,utf8_decode(''),0,0,'C');

  $pdf->Cell(60,4.15,utf8_decode('Universidad concluida (titulado/certificado)'),0,0,'L');
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');  
  $pdf->Cell(12,3.5,utf8_decode(''),0,0,'C');  
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');  
  $pdf->Cell(12,3.5,utf8_decode(''),0,0,'C');  
  $pdf->Cell(4,4.15,utf8_decode(''),0,1,'C');  

  $pdf->Cell(10,4.15,utf8_decode(''),0,0,'C');
  $pdf->Cell(60,4.15,utf8_decode('Preparatoria'),0,0,'L');
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');
  $pdf->Cell(12,3.5,utf8_decode(''),0,0,'C');
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');
  $pdf->Cell(12,3.5,utf8_decode(''),0,0,'C');

  $pdf->Cell(60,4.15,utf8_decode('Posgrado'),0,0,'L');
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');  
  $pdf->Cell(12,3.5,utf8_decode(''),0,0,'C');  
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');  
  $pdf->Cell(12,3.5,utf8_decode(''),0,0,'C');  
  $pdf->Cell(4,4.15,utf8_decode(''),0,1,'C');  

  $pdf->Cell(10,4.15,utf8_decode(''),0,0,'C');
  $pdf->Cell(60,4.15,utf8_decode('Estudios técnicos'),0,0,'L');
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');
  $pdf->Cell(12,3.5,utf8_decode(''),0,0,'C');
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');
  $pdf->Cell(12,3.5,utf8_decode(''),0,0,'C');

  $pdf->Cell(60,4.15,utf8_decode('Otro _____________________'),0,0,'L');
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');  
  $pdf->Cell(12,3.5,utf8_decode(''),0,0,'C');  
  $pdf->Cell(4,3.5,utf8_decode(''),1,0,'C');  
  $pdf->Cell(12,3.5,utf8_decode(''),0,0,'C');  
  $pdf->Cell(4,4.15,utf8_decode(''),0,1,'C');  

  $pdf->AddPage();

  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(5,4.15,utf8_decode(''),0,0,'L');
  $pdf->Cell(116,4.15,utf8_decode('5.2 Experiencia dentro y fuera de la organización'),0,1,'L');

  for ($id=1;$id<=4;$id++) 
  { $pdf->Ln(4.15);
    $pdf->Line(20,$pdf->GetY(),200,$pdf->GetY()); }

  $pdf->Ln(4.15);

  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(5,4.15,utf8_decode(''),0,0,'L');
  $pdf->Cell(116,4.15,utf8_decode('5.3 Conocimientos'),0,1,'L');

  for ($id=1;$id<=10;$id++) 
  { $pdf->Ln(4.15);
    $pdf->Line(20,$pdf->GetY(),200,$pdf->GetY()); }

  $pdf->Ln(4.15);

  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(5,4.15,utf8_decode(''),0,0,'L');
  $pdf->Cell(116,4.15,utf8_decode('5.4 Competencias'),0,1,'L');

  for ($id=1;$id<=4;$id++) 
  { $pdf->Ln(4.15);
    $pdf->Line(20,$pdf->GetY(),105,$pdf->GetY());
    $pdf->Line(110,$pdf->GetY(),200,$pdf->GetY()); }

  $pdf->Ln(4.15);

  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(10,10,utf8_decode('6 Necesidades de equipo y recursos'),0,1,'L');

  for ($id=1;$id<=8;$id++) 
  { $pdf->Ln(4.15);
    $pdf->Line(20,$pdf->GetY(),105,$pdf->GetY());
    $pdf->Line(110,$pdf->GetY(),200,$pdf->GetY()); }

  $pdf->Ln(4.15);

  $pdf->SetY(-20);

  $pdf->Line(10,$pdf->GetY(),70,$pdf->GetY());
  $pdf->Line(78,$pdf->GetY(),138,$pdf->GetY());
  $pdf->Line(146,$pdf->GetY(),206,$pdf->GetY());

  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(60,4.15,utf8_decode('Recursos Humanos'),0,0,'C');
  $pdf->Cell(8,4.15,utf8_decode(''),0,0,'C');
  $pdf->Cell(60,4.15,utf8_decode('Gerente Administrativo y Comercial'),0,0,'C');
  $pdf->Cell(8,4.15,utf8_decode(''),0,0,'C');
  $pdf->Cell(60,4.15,utf8_decode('Dirección'),0,1,'C');

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(60,4.15,utf8_decode('Elaboró'),0,0,'C');
  $pdf->Cell(8,4.15,utf8_decode(''),0,0,'C');
  $pdf->Cell(60,4.15,utf8_decode('Revisó'),0,0,'C');
  $pdf->Cell(8,4.15,utf8_decode(''),0,0,'C');  
  $pdf->Cell(60,4.15,utf8_decode('Aprobó'),0,1,'C');

  ////////////////////////////////////////////////
	$pdf->Output('RC-02-PSG-6.2.pdf', 'D');
?>