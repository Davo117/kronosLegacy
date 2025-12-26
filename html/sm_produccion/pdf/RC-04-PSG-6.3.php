<?php
  require('../../fpdf/fpdf.php');

	class PDF extends FPDF
	{ // Cabecera de página
		function Header()
		{ $this->SetFont('Arial','B',8);
			$this->SetFillColor(255,153,0);

			$this->Cell(45,4,utf8_decode('Elaboró'),0,0,'R');
			$this->Cell(0.5,4,'',0,1,'R',true);
			$this->Cell(45,4,utf8_decode('Revisó y Aprobó'),0,0,'R');
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
			$this->Cell(75,4,utf8_decode('Reporte de mantenimiento'),0,1,'L');
			$this->Cell(125.5,4,'',0,0,'R');
			$this->Cell(75,4,utf8_decode('RC-04-PSG-6.3'),0,1,'L');
			$this->Cell(125.5,4,'',0,0,'R');
			$this->Cell(75,4,utf8_decode('1.0'),0,1,'L');
			$this->Cell(125.5,4,'',0,0,'R');
			$this->Cell(75,4,utf8_decode('04 Marzo 2014'),0,1,'L');
			$this->Cell(125.5,4,'',0,0,'R');
			$this->Cell(75,4,utf8_decode('Responsable de Mantenimiento o TI'),0,1,'L'); 

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
		  $this->Cell(0,6,utf8_decode('Grupo Labro Sistema de Gestión de Calidad / Página '.$this->PageNo().' de {nb}'),0,1,'R'); 	    
		}
	}

	$pdf = new PDF('P','mm','letter');
	$pdf->SetDisplayMode(real, continuous);
  $pdf->AliasNbPages();

	$pdf->AddPage();
  $pdf->SetFillColor(255,153,0);

  $pdf->SetFont('Arial','',8);  
  $pdf->Cell(195.8,4,utf8_decode('No. Solicitud de mantenimiento: _______________'),0,1,'R');

  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(40,8,utf8_decode('Reporte'),0,1,'L');

  $posicion = 50;
  for ($idLinea = 0; $idLinea<=30; $idLinea++)
  { $newposicion = ($posicion+($idLinea*6));

    $pdf->Line(10,$newposicion,205.8,$newposicion); }
  
  ////////////////////////////////////////////////
	$pdf->Output('RC-04-PSG-6.3.pdf', 'D');
?>