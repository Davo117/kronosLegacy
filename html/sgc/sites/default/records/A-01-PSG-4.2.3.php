<?php
  require('fpdf/fpdf.php');

	class PDF extends FPDF
	{ // ENcabezado de página
    function Header()
    { if (file_exists('../images/logo.jpg')==true) 
      { $this->Image('../images/logo.jpg',10,5,0,25); }

      $this->SetFillColor(255,153,0);

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Control de los documentos'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('A-01-PSG-4.2.3'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Versión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('1.0'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Fecha de revisión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Mayo 15, 2014'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Responsable'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Representante de la dirección'),0,1,'L'); }

    // Pie de página
		function Footer()
		{ $this->SetFont('arial','',8);
		  $this->SetY(-7.5);
		  $this->Cell(0,6,utf8_decode('Grupo Labro | Sistema de Gestión de Calidad, página '.$this->PageNo().' de {nb}'),0,1,'R');
		}
	}

	$pdf = new PDF('P','mm','letter');
	$pdf->SetDisplayMode(real, continuous);
  $pdf->AliasNbPages();

	$pdf->AddPage();

  if (file_exists('../images/A-01-PSG-4.2.3.png')==true) 
  { $pdf->Image('../images/A-01-PSG-4.2.3.png',20,35,175,0); }

   /*$pdf->SetY(-40.6);
   $pdf->SetFont('Arial','',9);
   $pdf->Cell(10,5,utf8_decode('Acceso a documentos y registros'),0,1,'L');
   
   $pdf->SetFont('Arial','B',8);
   $pdf->Cell(25,4,'PSG-6.3',0,0,'R');
   $pdf->SetFont('Arial','',8);
   $pdf->Cell(30,4,utf8_decode('Infraestructura'),0,1,'L');

   $pdf->SetFont('Arial','B',8);
   $pdf->Cell(25,4,'RC-01-PSG-6.3',0,0,'R');
   $pdf->SetFont('Arial','',8);
   $pdf->Cell(30,4,utf8_decode('Estación de trabajo'),0,1,'L');
   
   $pdf->SetFont('Arial','B',8);
   $pdf->Cell(25,4,'RC-02-PSG-6.3',0,0,'R');
   $pdf->SetFont('Arial','',8);
   $pdf->Cell(30,4,utf8_decode('Programa anual de mantenimiento'),0,1,'L');
   
   $pdf->SetFont('Arial','B',8);
   $pdf->Cell(25,4,'RC-03-PSG-6.3',0,0,'R');
   $pdf->SetFont('Arial','',8);
   $pdf->Cell(30,4,utf8_decode('Solicitud de mantenimiento'),0,1,'L');
   
   $pdf->SetFont('Arial','B',8);
   $pdf->Cell(25,4,'RC-04-PSG-6.3',0,0,'R');
   $pdf->SetFont('Arial','',8);
   $pdf->Cell(30,4,utf8_decode('Reporte de mantenimiento'),0,1,'L');

   $pdf->SetFont('Arial','B',8);
   $pdf->Cell(25,4,'RC-01-PSG-7.4',0,0,'R');
   $pdf->SetFont('Arial','',8);
   $pdf->Cell(30,4,utf8_decode('Requerimiento'),0,1,'L');

   $pdf->Link(10,320,25,4,"192.168.1.80/sgc/?q=node/317");
   $pdf->Link(10,324,25,4,"192.168.1.80/sgc/sites/default/records/RC-01-PSG-6.3.php");
   $pdf->Link(10,328,25,4,"192.168.1.80/sgc/sites/default/records/RC-02-PSG-6.3.php");
   $pdf->Link(10,332,25,4,"192.168.1.80/sgc/sites/default/records/RC-03-PSG-6.3.php");
   $pdf->Link(10,336,25,4,"192.168.1.80/sgc/sites/default/records/RC-04-PSG-6.3.php");
   $pdf->Link(10,340,25,4,"192.168.1.80/sgc/sites/default/records/RC-01-PSG-7.4.php");
//*/
  ////////////////////////////////////////////////
	$pdf->Output('A-01-PSG-4-2.3.pdf', 'D');
?>