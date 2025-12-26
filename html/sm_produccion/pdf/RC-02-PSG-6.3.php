<?php
  require('../../fpdf/fpdf.php');

	class PDF extends FPDF
	{ // Cabecera de página

		// Pie de página
		function Footer()
		{ $this->SetY(-10);

		}
	}

	$pdf = new PDF('P','mm','letter');
	$pdf->SetDisplayMode(real, continuous);
  $pdf->AliasNbPages();

	$pdf->AddPage();
  $pdf->SetFillColor(255,153,0);

  $pdf->Line(5,138.5,210.8,138.5);
  $pdf->SetY(-280);

  for ($id = 0; $id<=1; $id++)
  {
  $pdf->Ln(10);

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
  $pdf->Cell(75,4,utf8_decode('Solicitud de mantenimiento'),0,1,'L');
  $pdf->Cell(125.5,4,'',0,0,'R');
  $pdf->Cell(75,4,utf8_decode('RC-02-PSG-6.3'),0,1,'L');
  $pdf->Cell(125.5,4,'',0,0,'R');
  $pdf->Cell(75,4,utf8_decode('1.0'),0,1,'L');
  $pdf->Cell(125.5,4,'',0,0,'R');
  $pdf->Cell(75,4,utf8_decode('04 Marzo 2014'),0,1,'L');
  $pdf->Cell(125.5,4,'',0,0,'R');
  $pdf->Cell(75,4,utf8_decode('Responsable de Mantenimiento o TI'),0,1,'L'); 

  $pdf->Ln(4);

  $pdf->SetFont('Arial','I',8);  
  $pdf->Cell(120,4,'',0,0);
  $pdf->Cell(45.8,4,utf8_decode('Fecha de solicitud'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,0,'R',true);
  $pdf->Cell(30,4,'_ _ _ _ _ _ _ _ _ _ _',0,1,'L');

  $pdf->Cell(120,4,'',0,0);
  $pdf->Cell(45.8,4,utf8_decode('No. Solicitud de mantenimiento'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,0,'R',true);
  $pdf->Cell(30,4,'_ _ _ _ _ _ _ _ _ _ _',0,1,'L');
  
  $pdf->Cell(120,4,'',0,0);
  $pdf->Cell(45.8,4,utf8_decode('Tipo de mantenimiento'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,0,'R',true);
  $pdf->Cell(30,4,'_ _ _ _ _ _ _ _ _ _ _',0,1,'L');
  $pdf->Ln(-12);

  $pdf->Cell(30,4,utf8_decode('Departamento'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,0,'R',true);
  $pdf->Cell(30,4,'_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _',0,1,'L');

  $pdf->Cell(30,4,utf8_decode('Solicitante'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,0,'R',true);
  $pdf->Cell(30,4,'_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _',0,1,'L');

  $pdf->Cell(30,4,utf8_decode('Equipo'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,0,'R',true);
  $pdf->Cell(30,4,'_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ ',0,1,'L');

  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(40,8,utf8_decode('Descripción de la solicitud'),0,1,'L');
  $pdf->Ln(5);

  $posicion = $pdf->GetY();

  for ($idLinea = 0; $idLinea<=9; $idLinea++)
  { $newposicion = ($posicion+($idLinea*6));

    $pdf->Line(10,$newposicion,205.8,$newposicion); }
  
  $pdf->SetY($newposicion+8);

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(98,4,'__________________________________________________',0,0,'C');
  $pdf->Cell(98,4,'__________________________________________________',0,1,'C');
  $pdf->Cell(98,4,utf8_decode('Nombre y firma del solicitante'),0,0,'C');
  $pdf->Cell(98,4,utf8_decode('Vo. Bo. del solicitante o su jefe inmediato'),0,1,'C');

  $pdf->Ln(11);
  }
  ////////////////////////////////////////////////
	$pdf->Output('RC-02-PSG-6.3.pdf', 'D');
?>