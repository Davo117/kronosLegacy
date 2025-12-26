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
      $this->Cell(75,4,utf8_decode('Evaluación del curso'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('RC-06-PSG-6.2'),0,1,'L');

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
      $this->Cell(75,4,utf8_decode('Jefe de Recursos Humanos'),0,1,'L');       

      $this->Ln(4.15); }

    // Pie de página
    function Footer()
    { $this->SetFont('Arial','I',8);
      $this->SetY(-20);
      $this->Cell(0,4.15,utf8_decode('"Gracias por tu asistencia y participación en el curso"'),0,0,'C');

      $this->SetFont('Arial','',9);
      $this->SetY(-7.5);
      $this->Cell(0,6,utf8_decode('Grupo Labro | Sistema de Gestión de Calidad, página '.$this->PageNo().' de {nb}'),0,1,'R'); }
  }

  $pdf = new PDF('P','mm','letter');
  $pdf->SetDisplayMode(real, continuous);
  $pdf->AliasNbPages();

  $pdf->AddPage();
  $pdf->SetFillColor(255,153,0);

  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(40.5,4.15,utf8_decode('Nombre del participante'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,0,'R',true);
  $pdf->Cell(49.5,4.15,utf8_decode(''),0,0,'R');
  $pdf->Cell(40,4.15,utf8_decode('Lugar'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,1,'R',true);

  $pdf->Line(52,$pdf->GetY(),120,$pdf->GetY());
  $pdf->Line(142,$pdf->GetY(),200,$pdf->GetY()); 

  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(40.5,4.15,utf8_decode('Nombre del curso'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,0,'R',true);
  $pdf->Cell(49.5,4.15,utf8_decode(''),0,0,'R');
  $pdf->Cell(40,4.15,utf8_decode('Fecha'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,1,'R',true);

  $pdf->Line(52,$pdf->GetY(),120,$pdf->GetY());
  $pdf->Line(142,$pdf->GetY(),180,$pdf->GetY());
  
  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(40.5,4.15,utf8_decode('Nombre del instructor'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,1,'R',true);
  
  $pdf->Line(52,$pdf->GetY(),120,$pdf->GetY());

  $pdf->Ln(4.15);

  $pdf->SetFont('Arial','I',8);
  $pdf->SetFillColor(255,153,0);

  $pdf->Ln(4.15);
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(130,4.15,utf8_decode('Evaluación del Instructor'),0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(20,4.15,utf8_decode("Excelente"),0,0,'C');
  $pdf->Cell(20,4.15,utf8_decode("Bueno"),0,0,'C');
  $pdf->Cell(20,4.15,utf8_decode("A mejorar"),0,1,'C');
  
  $pdf->SetFont('Arial','',9);
  $pdf->SetX(15);
  $pdf->Cell(130,6,utf8_decode("Dominio del tema que impartio"),0,1,'L');
  $pdf->Line(150,$pdf->GetY(),158,$pdf->GetY());
  $pdf->Line(170,$pdf->GetY(),178,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),198,$pdf->GetY());
  
  $pdf->SetX(15);
  $pdf->Cell(150,6,utf8_decode("Fomento la participación del grupo"),0,1,'L');
  $pdf->Line(150,$pdf->GetY(),158,$pdf->GetY());
  $pdf->Line(170,$pdf->GetY(),178,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),198,$pdf->GetY());
  
  $pdf->SetX(15);
  $pdf->Cell(150,6,utf8_decode("Inicio y finalizo puntualmente"),0,1,'L');
  $pdf->Line(150,$pdf->GetY(),158,$pdf->GetY());
  $pdf->Line(170,$pdf->GetY(),178,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),198,$pdf->GetY());
  
  $pdf->SetX(15);
  $pdf->Cell(150,6,utf8_decode("Ilustro el tema con casos prácticos"),0,1,'L');
  $pdf->Line(150,$pdf->GetY(),158,$pdf->GetY());
  $pdf->Line(170,$pdf->GetY(),178,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),198,$pdf->GetY());
  
  $pdf->SetX(15);
  $pdf->Cell(150,6,utf8_decode("Aclaro dudas de los participantes"),0,1,'L');
  $pdf->Line(150,$pdf->GetY(),158,$pdf->GetY());
  $pdf->Line(170,$pdf->GetY(),178,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),198,$pdf->GetY());  
  
  $pdf->Ln(4.15);
  $pdf->SetFont('Arial','B',10);  
  $pdf->Cell(130,4.15,utf8_decode('Evaluación del Curso'),0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(20,4.15,utf8_decode("Excelente"),0,0,'C');
  $pdf->Cell(20,4.15,utf8_decode("Bueno"),0,0,'C');
  $pdf->Cell(20,4.15,utf8_decode("A mejorar"),0,1,'C');
  
  $pdf->SetFont('Arial','',9);
  $pdf->SetX(15);
  $pdf->Cell(150,6,utf8_decode("Se alcanzo el objetivo del curso"),0,1,'L');
  $pdf->Line(150,$pdf->GetY(),158,$pdf->GetY());
  $pdf->Line(170,$pdf->GetY(),178,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),198,$pdf->GetY());
  
  $pdf->SetX(15);
  $pdf->Cell(150,6,utf8_decode("El conocimiento adquirido es aplicable al puesto"),0,1,'L');
  $pdf->Line(150,$pdf->GetY(),158,$pdf->GetY());
  $pdf->Line(170,$pdf->GetY(),178,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),198,$pdf->GetY());
  
  $pdf->SetX(15);
  $pdf->Cell(150,6,utf8_decode("Los temas contienen teoria-practica"),0,1,'L');
  $pdf->Line(150,$pdf->GetY(),158,$pdf->GetY());
  $pdf->Line(170,$pdf->GetY(),178,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),198,$pdf->GetY());
  
  $pdf->SetX(15);
  $pdf->Cell(150,6,utf8_decode("Los materiales y manuales empleados son suficientes"),0,1,'L');
  $pdf->Line(150,$pdf->GetY(),158,$pdf->GetY());
  $pdf->Line(170,$pdf->GetY(),178,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),198,$pdf->GetY());
  
  $pdf->SetX(15);
  $pdf->Cell(150,6,utf8_decode("El material entregado fue claro"),0,1,'L');
  $pdf->Line(150,$pdf->GetY(),158,$pdf->GetY());
  $pdf->Line(170,$pdf->GetY(),178,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),198,$pdf->GetY());  
  
  $pdf->Ln(4.15);
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(130,4.15,utf8_decode('Evaluación del Grupo'),0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(20,4.15,utf8_decode("Excelente"),0,0,'C');
  $pdf->Cell(20,4.15,utf8_decode("Bueno"),0,0,'C');
  $pdf->Cell(20,4.15,utf8_decode("A mejorar"),0,1,'C');
  
  $pdf->SetFont('Arial','',9);
  $pdf->SetX(15);
  $pdf->Cell(150,6,utf8_decode("Los participantes se mostraron interesados"),0,1,'L');
  $pdf->Line(150,$pdf->GetY(),158,$pdf->GetY());
  $pdf->Line(170,$pdf->GetY(),178,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),198,$pdf->GetY());  
  
  $pdf->SetX(15);
  $pdf->Cell(150,6,utf8_decode("Compartieron sus conococimientos y experiencias"),0,1,'L');
  $pdf->Line(150,$pdf->GetY(),158,$pdf->GetY());
  $pdf->Line(170,$pdf->GetY(),178,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),198,$pdf->GetY());
  
  $pdf->SetX(15);
  $pdf->Cell(150,6,utf8_decode("Se integraron con la intención de alcanzar el objetivo del curso"),0,1,'L');
  $pdf->Line(150,$pdf->GetY(),158,$pdf->GetY());
  $pdf->Line(170,$pdf->GetY(),178,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),198,$pdf->GetY());
  
  $pdf->SetX(15);
  $pdf->Cell(150,6,utf8_decode("La comunicación fue dinamica entre los miembros"),0,1,'L');
  $pdf->Line(150,$pdf->GetY(),158,$pdf->GetY());
  $pdf->Line(170,$pdf->GetY(),178,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),198,$pdf->GetY());
  
  $pdf->SetX(15);
  $pdf->Cell(150,6,utf8_decode("La actitud del grupo hacia el instructor fue cordial y respetuosa"),0,1,'L');  
  $pdf->Line(150,$pdf->GetY(),158,$pdf->GetY());
  $pdf->Line(170,$pdf->GetY(),178,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),198,$pdf->GetY());
  
  $pdf->Ln(4.15);
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(130,4.15,utf8_decode('Evaluación de la Coordinación y otros servicios'),0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(20,4.15,utf8_decode("Excelente"),0,0,'C');
  $pdf->Cell(20,4.15,utf8_decode("Bueno"),0,0,'C');
  $pdf->Cell(20,4.15,utf8_decode("A mejorar"),0,1,'C');
  
  $pdf->SetFont('Arial','',9);
  $pdf->SetX(15);
  $pdf->Cell(150,6,utf8_decode("La actividad anterior al curso fue adecuada"),0,1,'L');
  $pdf->Line(150,$pdf->GetY(),158,$pdf->GetY());
  $pdf->Line(170,$pdf->GetY(),178,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),198,$pdf->GetY());
  
  $pdf->SetFont('Arial','I',8);
  $pdf->SetX(15);
  $pdf->Cell(150,6,utf8_decode("(Inscripción, información, entrega de materiales y tramites en general)"),0,1,'L');
  
  $pdf->SetFont('Arial','',9);
  $pdf->SetX(15);
  $pdf->Cell(150,6,utf8_decode("El aula fue adecuada"),0,1,'L');
  $pdf->Line(150,$pdf->GetY(),158,$pdf->GetY());
  $pdf->Line(170,$pdf->GetY(),178,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),198,$pdf->GetY());
  
  $pdf->SetX(15);
  $pdf->Cell(150,6,utf8_decode("La entrega de diplomas fue oportuna"),0,1,'L');
  $pdf->Line(150,$pdf->GetY(),158,$pdf->GetY());
  $pdf->Line(170,$pdf->GetY(),178,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),198,$pdf->GetY());
  
  $pdf->Ln(4.15);
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(150,4.15,utf8_decode('Observaciones'),0,1,'L');
  
  $pdf->SetFont('Arial','B',8);
  for ($id=1;$id<=5;$id++)
  { $pdf->Ln(6);
    $pdf->Line(15,$pdf->GetY(),200,$pdf->GetY()); }
  
  ////////////////////////////////////////////////
  $pdf->Output('RC-06-PSG-6.2.pdf', 'D');
?>