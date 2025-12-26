<?php
  require('fpdf/fpdf.php');

	class PDF extends FPDF
	{ // Cabecera de página
		function Header()
		{ $this->SetFont('Arial','B',8);
			$this->SetFillColor(255,153,0);

			$this->Cell(45,4,utf8_decode('Elaboró'),0,0,'R');
			$this->Cell(0.5,4,'',0,1,'R',true);
			$this->Cell(45,4,utf8_decode('Revisó y aprobó'),0,0,'R');
			$this->Cell(0.5,4,'',0,1,'R',true);

			$this->Ln(-8);

			$this->SetFont('Arial','I',8);
			$this->Cell(45.5,4,'',0,0,'R');
			$this->Cell(75,4,utf8_decode('Representante de la Dirección'),0,1,'L');
			$this->Cell(45.5,4,'',0,0,'R');
			$this->Cell(75,4,utf8_decode('Dirección'),0,1,'L');

			$this->Ln(-8);

			$this->SetFont('Arial','B',8);
			$this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
			$this->Cell(0.5,4,'',0,1,'R',true);
			$this->Cell(125,4,utf8_decode('Código'),0,0,'R');
			$this->Cell(0.5,4,'',0,1,'R',true);
			$this->Cell(125,4,utf8_decode('Versión'),0,0,'R');
			$this->Cell(0.5,4,'',0,1,'R',true);
			$this->Cell(125,4,utf8_decode('Fecha de revisión'),0,0,'R');
			$this->Cell(0.5,4,'',0,1,'R',true);
			$this->Cell(125,4,utf8_decode('Responsable'),0,0,'R');
		  $this->Cell(0.5,4,'',0,1,'R',true);

			$this->Ln(-20);

			$this->SetFont('Arial','I',8);
			$this->SetFillColor(255,153,0);

			$this->Cell(125.5,4,'',0,0,'R');
			$this->Cell(75,4,utf8_decode('Combinación de tintas para impresión por diseño'),0,1,'L');
			$this->Cell(125.5,4,'',0,0,'R');
			$this->Cell(75,4,utf8_decode('RC-03-PSG-7.2'),0,1,'L');
			$this->Cell(125.5,4,'',0,0,'R');
			$this->Cell(75,4,utf8_decode('1.0'),0,1,'L');
			$this->Cell(125.5,4,'',0,0,'R');
			$this->Cell(75,4,utf8_decode('Abril 21, 2014'),0,1,'L');
			$this->Cell(125.5,4,'',0,0,'R');
			$this->Cell(75,4,utf8_decode('Gerente Administrativo y Comercial'),0,1,'L'); 

      $this->Ln(4);
    }

		// Pie de página
		function Footer()
		{ $this->SetY(-20);

	    $this->SetFont('Arial','B',8);
	    $this->Cell(195.9,4,'______________________________________________________',0,1,'C');
	    $this->Cell(195.9,4,utf8_decode('Nombre y firma de quien realizo el registro'),0,1,'C');

		  $this->SetY(-10);
		  $this->SetFont('arial','B',8);
		  $this->Cell(0,6,'Consultado el '.date("Y-m-d").' a las '.date("G:i:s").'',0,1,'R'); 
		  $this->SetFont('arial','',8);
		  $this->SetY(-7.5);
      $this->Cell(0,6,utf8_decode('Grupo Labro | Sistema de Gestión de Calidad, página '.$this->PageNo().' de {nb}'),0,1,'R');
		}
	}

	$pdf = new PDF('P','mm','letter');
	$pdf->SetDisplayMode(real, continuous);
  $pdf->AliasNbPages();

	$pdf->AddPage();
  $pdf->SetFillColor(255,153,0);

  $pdf->SetFont('Arial','B',10);

  $pdf->Cell(21,8,utf8_decode(''),0,0,'L');
  $pdf->Cell(40,8,utf8_decode('Información para la impresión'),0,1,'L');

	$pdf->SetFont('Arial','I',8);

  $pdf->Cell(40,4,utf8_decode('Código del diseño'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,0,'R',true);
  $pdf->Cell(50.5,4,'_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _',0,1,'L');

  $pdf->Cell(40,4,utf8_decode('Nombre del diseño'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,0,'R',true);
  $pdf->Cell(50.5,4,'_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _',0,1,'L');

  $pdf->Cell(40,4,utf8_decode('Código de la impresión'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,0,'R',true);
  $pdf->Cell(50.5,4,'_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _',0,1,'L');

  $pdf->Cell(40,4,utf8_decode('Nombre de la impresión'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,0,'R',true);
  $pdf->Cell(50.5,4,'_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _',0,1,'L');

  $pdf->SetFont('Arial','B',10);

  $pdf->Cell(21,8,utf8_decode(''),0,0,'L');
  $pdf->Cell(50,8,utf8_decode('Detalle de tintas'),0,1,'L');


  for ($idTinta = 1; $idTinta <= 10; $idTinta++)
  { $pdf->SetFont('Arial','B',8);
    $pdf->Cell(38,6,utf8_decode('Tinta'),0,0,'R');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(43,6,utf8_decode('#'.$idTinta),0,1,'L');
    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(40,4,utf8_decode('No. Pantone'),0,0,'R');
    $pdf->Cell(0.5,4,'',0,0,'R',true);
    $pdf->Cell(0.5,4,'_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _',0,1,'L'); }

  $pdf->Ln(4);
  
  $pdf->SetFont('Arial','',8);
  $pdf->MultiCell(0,5,utf8_decode('Anexo a este registro debe encontrarse una impresión a color del diseño respetando los pantones indicados, así como la cantidad de tintas a usar.'));
  
  ////////////////////////////////////////////////
	$pdf->Output('RC-03-PSG-7.2.pdf', 'D');
?>