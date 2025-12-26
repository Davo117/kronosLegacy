<?php
  require('fpdf/fpdf.php');

	class PDF extends FPDF
	{ // Pie de página
		function Footer()
		{ $this->SetY(-10);
		  $this->SetFont('arial','B',8);
		  $this->Cell(0,6,'',0,1,'R'); 
		  $this->SetFont('arial','',8);
		  $this->SetY(-7.5);
		  $this->Cell(0,6,utf8_decode('Grupo Labro | Sistema de Gestión de Calidad / Página '.$this->PageNo().' de {nb}'),0,1,'R'); 	    
		}
	}

	$pdf = new PDF('P','mm','letter');
	$pdf->SetDisplayMode(real, continuous);
  $pdf->AliasNbPages();

	$pdf->AddPage();

  $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(255,153,0);

  $pdf->Cell(45,4,utf8_decode('Elaboró'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,1,'R',true);
  $pdf->Cell(45,4,utf8_decode('Revisó y Aprobó'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,1,'R',true);

  $pdf->Ln(-8);

  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(45.5,4,'',0,0,'R');
  $pdf->Cell(75,4,utf8_decode('Representante de la Dirección'),0,1,'L');
  $pdf->Cell(45.5,4,'',0,0,'R');
  $pdf->Cell(75,4,utf8_decode('Dirección'),0,1,'L');

  $pdf->Ln(-8);

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(125,4,utf8_decode('Documento'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,1,'R',true);
  $pdf->Cell(125,4,utf8_decode('Código'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,1,'R',true);
  $pdf->Cell(125,4,utf8_decode('Versión'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,1,'R',true);
  $pdf->Cell(125,4,utf8_decode('Fecha de revisión'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,1,'R',true);
  $pdf->Cell(125,4,utf8_decode('Responsable'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,1,'R',true);

  $pdf->Ln(-20);

  $pdf->SetFont('Arial','I',8);
  $pdf->SetFillColor(255,153,0);

  $pdf->Cell(125.5,4,'',0,0,'R');
  $pdf->Cell(75,4,utf8_decode('Compromiso de la Dirección'),0,1,'L');
  $pdf->Cell(125.5,4,'',0,0,'R');
  $pdf->Cell(75,4,utf8_decode('A-01-MC'),0,1,'L');
  $pdf->Cell(125.5,4,'',0,0,'R');
  $pdf->Cell(75,4,utf8_decode('1.0'),0,1,'L');
  $pdf->Cell(125.5,4,'',0,0,'R');
  $pdf->Cell(75,4,utf8_decode('Abril 21, 2014'),0,1,'L');
  $pdf->Cell(125.5,4,'',0,0,'R');
  $pdf->Cell(75,4,utf8_decode('Representante de la Dirección'),0,1,'L'); 

  $pdf->SetFont('Arial','',10);
  $pdf->Ln(25);
  $pdf->Cell(10,4,'',0,0,'R');
  $pdf->MultiCell(170,4,utf8_decode('En mi carácter de Director del Sistema de Gestión de la Calidad de Grupo Labro, manifiesto el Compromiso para la implementación, desarrollo y mejora continua de la eficacia del Sistema de Gestión de la Calidad, a través de las siguientes actividades:'));
  
  $pdf->SetFont('Arial','',10.3);
  $pdf->Ln(8);
  $pdf->Cell(20,4,'a)',0,0,'R');
  $pdf->MultiCell(150,4,utf8_decode('Comunicando a la organización la importancia de satisfacer tanto los requisitos del cliente como los legales y reglamentarios aplicables.'));
  $pdf->Ln(6);
  $pdf->Cell(20,4,'b)',0,0,'R');
  $pdf->MultiCell(150,4,utf8_decode('Estableciendo la política de la calidad.'));
  $pdf->Ln(6);
  $pdf->Cell(20,4,'c)',0,0,'R');
  $pdf->MultiCell(150,4,utf8_decode('Estableciendo los objetivos de la calidad.'));
  $pdf->Ln(6);
  $pdf->Cell(20,4,'d)',0,0,'R');
  $pdf->MultiCell(150,4,utf8_decode('Realizando las revisiones por la dirección.'));
  $pdf->Ln(6);
  $pdf->Cell(20,4,'e)',0,0,'R');
  $pdf->MultiCell(150,4,utf8_decode('Asegurando la disponibilidad de recursos.'));
  $pdf->Ln(6);
  $pdf->Cell(20,4,'f)',0,0,'R');
  $pdf->MultiCell(150,4,utf8_decode('Monitoreando los procesos de elaboración de los productos y de soporte, asegurando su efectividad y eficiencia.'));
  
  $pdf->SetFont('Arial','',10);
  $pdf->Ln(8);
  $pdf->Cell(10,4,'',0,0,'R');
  $pdf->MultiCell(170,4,utf8_decode('Todo lo anterior derivado del proceso de certificación en la norma ISO 9001:2008 que a partir de Abril 21, de 2014 comenzó de manera general en nuestra planta.'));
  
  $pdf->Ln(25);
  $pdf->MultiCell(0,4,utf8_decode('Atentamente'),0,'C');
  $pdf->Ln(25);
  $pdf->MultiCell(0,4,utf8_decode('__________________________________________'),0,'C');
  $pdf->SetFont('Arial','I',10);
  $pdf->MultiCell(0,4,utf8_decode('C.P. Oscar Eduardo Labiaga Orozco'),0,'C');
  $pdf->SetFont('Arial','B',10);
  $pdf->MultiCell(0,4,utf8_decode('Director General de Grupo Labro'),0,'C');
  

  ////////////////////////////////////////////////
	$pdf->Output('A-01-MC.pdf', 'D');
?>