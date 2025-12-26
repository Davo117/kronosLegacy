<?php
  require('fpdf/fpdf.php');

	class PDF extends FPDF
	{ // ENcabezado de página
    function Header()
    { $this->SetFillColor(255,153,0);

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Nombramiento del Representante de la Dirección'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('A-01-MC-5.5.2'),0,1,'L');

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
      $this->Cell(75,4,utf8_decode('Representante de la Dirección'),0,1,'L'); }

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
  $pdf->SetFont('Arial','',10);
  $pdf->Ln(12);
  $pdf->Cell(10,4,'',0,0,'R');
  $pdf->MultiCell(170,4,utf8_decode('En mi carácter de Director del Sistema de Gestión de Calidad de Grupo Labro nombro como mi representante al Ing. Sánchez Martínez Miguel Angel quien tiene las competencias suficientes para implementar, actualizar, optimizar e informar la efectividad, adecuación y avances del Sistema de Gestión de Calidad.'));
  
  $pdf->Ln(4);
  $pdf->Cell(10,4,'',0,0,'R');
  $pdf->MultiCell(150,4,utf8_decode('Entre sus responsabilidades se encuentran:'));
  $pdf->Ln(6);
  $pdf->Cell(20,4,'-',0,0,'R');
  $pdf->MultiCell(150,4,utf8_decode('Asegurarse que el Sistema de Calidad se establece de acuerdo a los requerimientos de la norma ISO 9001:2008, mediante el Manual de Calidad (MC) donde se establecen los compromisos del Sistema de Calidad.'));
  $pdf->Ln(6);
  $pdf->Cell(20,4,'-',0,0,'R');
  $pdf->MultiCell(150,4,utf8_decode('Asimismo, a través de las Auditorías Internas de calidad, se revisa la adecuación del mismo a los requerimientos de la norma ISO 9001:2008.'));
  $pdf->Ln(6);
  $pdf->Cell(20,4,'-',0,0,'R');
  $pdf->MultiCell(150,4,utf8_decode('Asegurar la implementación de los procedimientos documentados del Sistema de Calidad mediante la ejecución de los mismos y la generación de evidencia objetiva por los responsables.'));
  $pdf->Ln(6);
  $pdf->Cell(20,4,'-',0,0,'R');
  $pdf->MultiCell(150,4,utf8_decode('Para asegurar que el Sistema de Calidad se mantiene de acuerdo a los requerimientos de la norma declarada, recopila y analiza información referente a: número de no conformidades generadas en los procesos y el Sistema de Calidad, no conformidades potenciales, satisfacción de clientes mediante la encuesta de opinión, quejas y/o reclamaciones de los clientes,  resultados de las actividades de auditorías internas, avance y efectividad de acciones correctivas y preventivas y acuerdos generados en las mismas revisiones de la Dirección al Sistema de Calidad.'));
  $pdf->Ln(6);
  $pdf->Cell(20,4,'-',0,0,'R');
  $pdf->MultiCell(150,4,utf8_decode('Establecer las revisiones periódicas de la Dirección, donde se informa el desempeño de todos los procesos del sistema y el Director define cualquier oportunidad de mejora necesaria. '));
  $pdf->Ln(6);
  $pdf->Cell(20,4,'-',0,0,'R');
  $pdf->MultiCell(150,4,utf8_decode('Asegurar  que se promueva la toma de conciencia de los requisitos del cliente en todos los niveles de la organización.'));
  $pdf->Ln(6);
  $pdf->Cell(20,4,'-',0,0,'R');
  $pdf->MultiCell(150,4,utf8_decode('Garantizar la promoción de la toma de conciencia dentro la Dirección, vigila el cumplimiento de los procesos y de los cursos de capacitación que son impartidos al personal que labora dentro de la empresa.'));
  
  $pdf->Ln(4);
  $pdf->Cell(10,4,'',0,0,'R');
  $pdf->MultiCell(170,4,utf8_decode('La responsabilidad del Representante de la Dirección incluye también el enlace con organizaciones externas en asuntos relacionados con el Sistema de Calidad, siendo su principal actividad la de ser contacto con el organismo certificador para actividades de auditoría de mantenimiento del sistema.'));
  
  $pdf->Ln(12);
  $pdf->MultiCell(0,4,utf8_decode('Atentamente'),0,'C');
  $pdf->Ln(25);
  $pdf->Line(73,$pdf->GetY(),143,$pdf->GetY());
  $pdf->SetFont('Arial','I',10);
  $pdf->MultiCell(0,4,utf8_decode('C.P. Oscar Eduardo Labiaga Orozco'),0,'C');
  $pdf->SetFont('Arial','B',10);
  $pdf->MultiCell(0,4,utf8_decode('Director General de Grupo Labro'),0,'C');
  

  ////////////////////////////////////////////////
	$pdf->Output('A-01-MC-5.5.2.pdf', 'D');
?>