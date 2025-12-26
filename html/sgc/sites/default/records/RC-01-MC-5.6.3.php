<?php
  require('fpdf/fpdf.php');

  class PDF extends FPDF
  { // Cabecera de página
    function Header()
    { $this->SetFillColor(255,153,0);

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Resultados de la revisión'),0,1,'L');
  
      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('RC-01-MC-5.6.3'),0,1,'L');

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
      $this->Cell(75,4,utf8_decode('Representante de la Dirección'),0,1,'L'); 

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

  $pdf->SetFillColor(255,153,0);
  $pdf->AddPage();

  $pdf->SetFont('Arial','I',8);

  $pdf->Cell(45,4.15,utf8_decode('Fecha en que se realizó'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,1,'R',true);
  $pdf->Line(56,$pdf->GetY(),100,$pdf->GetY());
  $pdf->Ln(4.15);

  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(102,4.15,utf8_decode('Asistentes'),0,1,'L');

  $pdf->SetFont('Arial','I',8);
  $pdf->Ln(4.15);
  
  for ($_row = 1; $_row <= 5; $_row++)
  { $pdf->Ln(4.15);
  
    $pdf->Line(15,$pdf->GetY(),65,$pdf->GetY());
    $pdf->Line(70,$pdf->GetY(),108,$pdf->GetY());
    $pdf->Line(113,$pdf->GetY(),163,$pdf->GetY());
    $pdf->Line(168,$pdf->GetY(),206,$pdf->GetY());

    $pdf->SetX(15);
    $pdf->Cell(50,4.15,utf8_decode('Nombre'),0,0,'C');
    $pdf->SetX(70);
    $pdf->Cell(38,4.15,utf8_decode('Puesto'),0,0,'C');
    $pdf->SetX(113);
    $pdf->Cell(50,4.15,utf8_decode('Nombre'),0,0,'C');
    $pdf->SetX(168);
    $pdf->Cell(38,4.15,utf8_decode('Puesto'),0,1,'C'); }

  $pdf->Ln(4.15);

  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(102,4.15,utf8_decode('Puntos discutidos en la revisión de la dirección'),0,1,'L');

  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(102,4.15,utf8_decode('1) Los resultados de auditorias internas y externas'),0,1,'L');
  $pdf->Ln(4.15);

  for ($_row = 1; $_row <= 3; $_row++)
  { $pdf->Line(15,$pdf->GetY(),206,$pdf->GetY());
    $pdf->Ln(4.15); }

  $pdf->Cell(102,4.15,utf8_decode('2) La retroalimentación del cliente'),0,1,'L');
  $pdf->Ln(4.15);

  for ($_row = 1; $_row <= 3; $_row++)
  { $pdf->Line(15,$pdf->GetY(),206,$pdf->GetY());
    $pdf->Ln(4.15); }

  $pdf->Cell(102,4.15,utf8_decode('3) El desempeño de los procesos y la conformidad del producto'),0,1,'L');
  $pdf->Ln(4.15);

  for ($_row = 1; $_row <= 3; $_row++)
  { $pdf->Line(15,$pdf->GetY(),206,$pdf->GetY());
    $pdf->Ln(4.15); }

  $pdf->Cell(102,4.15,utf8_decode('4) El estado de las acciones correctivas y preventivas'),0,1,'L');
  $pdf->Ln(4.15);

  for ($_row = 1; $_row <= 3; $_row++)
  { $pdf->Line(15,$pdf->GetY(),206,$pdf->GetY());
    $pdf->Ln(4.15); }

  $pdf->Cell(102,4.15,utf8_decode('5) Las acciones de seguimiento de revisiones por la dirección previas'),0,1,'L');
  $pdf->Ln(4.15);

  for ($_row = 1; $_row <= 3; $_row++)
  { $pdf->Line(15,$pdf->GetY(),206,$pdf->GetY());
    $pdf->Ln(4.15); }

  $pdf->Cell(102,4.15,utf8_decode('6) Los cambios que podrían afectar al Sistema de Gestión de Calidad'),0,1,'L');
  $pdf->Ln(4.15);

  for ($_row = 1; $_row <= 3; $_row++)
  { $pdf->Line(15,$pdf->GetY(),206,$pdf->GetY());
    $pdf->Ln(4.15); }

  $pdf->Cell(102,4.15,utf8_decode('7) Las recomendaciones para la mejora'),0,1,'L');
  $pdf->Ln(4.15);

  for ($_row = 1; $_row <= 3; $_row++)
  { $pdf->Line(15,$pdf->GetY(),206,$pdf->GetY());
    $pdf->Ln(4.15); }

  $pdf->Cell(196,4.15,utf8_decode('8) Información relevante para la dirección, acciones a tomar'),0,1,'L');
  $pdf->Ln(4.15);

  for ($_row = 1; $_row <= 3; $_row++)
  { $pdf->Line(15,$pdf->GetY(),206,$pdf->GetY());
    $pdf->Ln(4.15); }

  $pdf->AddPage();

  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(102,4.15,utf8_decode('Conclusiones y Acuerdos realizados'),0,1,'L');

  $pdf->Ln(8.3);
  $pdf->SetFont('Arial','I',8);
  for ($_item = 1; $_item <= 10; $_item++)
  { for ($_row = 1; $_row <= 3; $_row++)
    { $pdf->Line(15,$pdf->GetY(),206,$pdf->GetY());
      $pdf->Ln(4.15); }

    $pdf->Line(30,$pdf->GetY(),96,$pdf->GetY());
    $pdf->Line(101,$pdf->GetY(),151,$pdf->GetY());
    $pdf->Line(156,$pdf->GetY(),206,$pdf->GetY());

    $pdf->SetX(30);
    $pdf->Cell(66,4.15,utf8_decode('Responsable'),0,0,'C');
    $pdf->SetX(101);
    $pdf->Cell(50,4.15,utf8_decode('Fecha compromiso'),0,0,'C');
    $pdf->SetX(156);
    $pdf->Cell(50,4.15,utf8_decode('Documento AC/AP/PM'),0,1,'C'); 

    $pdf->Ln(4.15); }

  $pdf->AddPage();
  
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(102,4.15,utf8_decode('Detección de necesidades'),0,1,'L');
  
  $pdf->SetFont('Arial','',8);
  $pdf->SetX(15);
  $pdf->Cell(80,4.15,utf8_decode('Recursos Humanos'),0,1,'L');
  $pdf->Ln(4.15);
  $pdf->Line(20,$pdf->GetY(),206,$pdf->GetY());
  $pdf->Ln(4.15);
  $pdf->Line(20,$pdf->GetY(),206,$pdf->GetY());  
  $pdf->Ln(4.15);
  $pdf->Line(20,$pdf->GetY(),151,$pdf->GetY());  
  $pdf->Line(156,$pdf->GetY(),206,$pdf->GetY());
  $pdf->SetX(156);
  $pdf->Cell(50,4.15,utf8_decode('Fecha compromiso'),0,1,'C');

  $pdf->SetFont('Arial','',8);
  $pdf->SetX(15);
  $pdf->Cell(80,4.15,utf8_decode('Infraestructura'),0,1,'L');
  $pdf->Ln(4.15);
  $pdf->Line(20,$pdf->GetY(),206,$pdf->GetY());
  $pdf->Ln(4.15);
  $pdf->Line(20,$pdf->GetY(),206,$pdf->GetY());  
  $pdf->Ln(4.15);
  $pdf->Line(20,$pdf->GetY(),151,$pdf->GetY());  
  $pdf->Line(156,$pdf->GetY(),206,$pdf->GetY());
  $pdf->SetX(156);
  $pdf->Cell(50,4.15,utf8_decode('Fecha compromiso'),0,1,'C');  

  $pdf->SetFont('Arial','',8);
  $pdf->SetX(15);
  $pdf->Cell(80,4.15,utf8_decode('Ambiente de trabajo'),0,1,'L');
  $pdf->Ln(4.15);
  $pdf->Line(20,$pdf->GetY(),206,$pdf->GetY());
  $pdf->Ln(4.15);
  $pdf->Line(20,$pdf->GetY(),206,$pdf->GetY());  
  $pdf->Ln(4.15);
  $pdf->Line(20,$pdf->GetY(),151,$pdf->GetY());  
  $pdf->Line(156,$pdf->GetY(),206,$pdf->GetY());
  $pdf->SetX(156);
  $pdf->Cell(50,4.15,utf8_decode('Fecha compromiso'),0,1,'C');  


  $pdf->SetFont('Arial','',8);
  $pdf->SetX(15);
  $pdf->Cell(80,4.15,utf8_decode('La politica de calidad sigue siendo adecuada?'),0,1,'L');
  $pdf->Ln(4.15);
  $pdf->Line(20,$pdf->GetY(),151,$pdf->GetY());
  $pdf->Line(156,$pdf->GetY(),206,$pdf->GetY());
  $pdf->SetX(156);
  $pdf->Cell(50,4.15,utf8_decode('Documento AC/AP/PM'),0,1,'C');
  
  $pdf->SetX(15);
  $pdf->Cell(80,4.15,utf8_decode('Los objetivos de calidad se encuentran dentro de los limites?'),0,1,'L');
  $pdf->Ln(4.15);
  $pdf->Line(20,$pdf->GetY(),151,$pdf->GetY());
  $pdf->Line(156,$pdf->GetY(),206,$pdf->GetY());
  $pdf->SetX(156);
  $pdf->Cell(50,4.15,utf8_decode('Documento AC/AP/PM'),0,1,'C');
  
  $pdf->SetX(15);
  $pdf->Cell(80,4.15,utf8_decode('Los objetivos de calidad siguen siendo congruentes con la politica de calidad?'),0,1,'L');
  $pdf->Ln(4.15);
  $pdf->Line(20,$pdf->GetY(),151,$pdf->GetY());
  $pdf->Line(156,$pdf->GetY(),206,$pdf->GetY());
  $pdf->SetX(156);
  $pdf->Cell(50,4.15,utf8_decode('Documento AC/AP/PM'),0,1,'C');

  $pdf->SetX(15);
  $pdf->Cell(80,4.15,utf8_decode('Los resultados de auditorias están de acuerdo a lo planeado?'),0,1,'L');
  $pdf->Ln(4.15);
  $pdf->Line(20,$pdf->GetY(),151,$pdf->GetY());
  $pdf->Line(156,$pdf->GetY(),206,$pdf->GetY());
  $pdf->SetX(156);
  $pdf->Cell(50,4.15,utf8_decode('Documento AC/AP/PM'),0,1,'C');
  
  $pdf->SetX(15);
  $pdf->Cell(80,4.15,utf8_decode('El Sistema de Gestión de la Calidad sigue siendo conveniente, adecuado y eficaz?'),0,1,'L');
  $pdf->Ln(4.15);
  $pdf->Line(20,$pdf->GetY(),151,$pdf->GetY());
  $pdf->Line(156,$pdf->GetY(),206,$pdf->GetY());
  $pdf->SetX(156);
  $pdf->Cell(50,4.15,utf8_decode('Documento AC/AP/PM'),0,1,'C');
  
  $pdf->SetX(15);
  $pdf->Cell(80,4.15,utf8_decode('Se mantiene el cumplimiento en general con la norma ISO declarada?'),0,1,'L');
  $pdf->Ln(4.15);
  $pdf->Line(20,$pdf->GetY(),151,$pdf->GetY());
  $pdf->Line(156,$pdf->GetY(),206,$pdf->GetY());
  $pdf->SetX(156);
  $pdf->Cell(50,4.15,utf8_decode('Documento AC/AP/PM'),0,1,'C');
  
  $pdf->SetX(15);
  $pdf->Cell(80,4.15,utf8_decode('Se ha mejorado la eficacia del Sistema de Gestión de la Calidad?'),0,1,'L');
  $pdf->Ln(4.15);
  $pdf->Line(20,$pdf->GetY(),151,$pdf->GetY());
  $pdf->Line(156,$pdf->GetY(),206,$pdf->GetY());
  $pdf->SetX(156);
  $pdf->Cell(50,4.15,utf8_decode('Documento AC/AP/PM'),0,1,'C');
  
  $pdf->SetX(15);
  $pdf->Cell(80,4.15,utf8_decode('Se ha mejorado el servicio y/o producto con relación a los requisitos del cliente?'),0,1,'L');
  $pdf->Ln(4.15);
  $pdf->Line(20,$pdf->GetY(),151,$pdf->GetY());
  $pdf->Line(156,$pdf->GetY(),206,$pdf->GetY());
  $pdf->SetX(156);
  $pdf->Cell(50,4.15,utf8_decode('Documento AC/AP/PM'),0,1,'C');
  
  $pdf->Ln(12.45);
  $pdf->Cell(24,4.15,utf8_decode('Elaboró'),0,0,'R');
  $pdf->Cell(0.5,4.15,utf8_decode(''),0,0,'R',true);
  $pdf->Cell(50.5,4.15,utf8_decode(''),0,0,'C');
  $pdf->Cell(35,4.15,utf8_decode('Aprobó'),0,0,'R');
  $pdf->Cell(0.5,4.15,utf8_decode(''),0,1,'R',true);

  $pdf->Line(35,$pdf->GetY(),95,$pdf->GetY());
  $pdf->Line(121,$pdf->GetY(),181,$pdf->GetY());
  
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(30,4.15,utf8_decode(''),0,0,'L');
  $pdf->Cell(50,4.15,utf8_decode('Representante de la dirección'),0,0,'C');
  $pdf->Cell(36,4.15,utf8_decode(''),0,0,'L');
  $pdf->Cell(50,4.15,utf8_decode('Director del Sistema de Gestión de la Calidad'),0,0,'C');
  
////////////////////////////////////////////////
  $pdf->Output('RC-01-MC-5.6.3.pdf', 'D');
?>