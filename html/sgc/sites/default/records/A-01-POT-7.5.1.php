<?php
  require('fpdf/fpdf.php');

	class PDF extends FPDF
	{ // Pie de página
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

  $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(255,153,0);
/*
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

  $pdf->Ln(-8);//*/

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
  $pdf->Cell(75,4,utf8_decode('Identificación y almacenamiento'),0,1,'L');
  $pdf->Cell(125.5,4,'',0,0,'R');
  $pdf->Cell(75,4,utf8_decode('A-01-POT-7.5.1'),0,1,'L');
  $pdf->Cell(125.5,4,'',0,0,'R');
  $pdf->Cell(75,4,utf8_decode('1.0'),0,1,'L');
  $pdf->Cell(125.5,4,'',0,0,'R');
  $pdf->Cell(75,4,utf8_decode('Marzo 07, 2014'),0,1,'L');
  $pdf->Cell(125.5,4,'',0,0,'R');
  $pdf->Cell(75,4,utf8_decode('Inspector de Calidad'),0,1,'L'); 

  $pdf->Ln(2);
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(40,6,utf8_decode('Identificación y empaque'),0,1,'L');
  
  $pdf->Ln(2);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(40,4,utf8_decode('Presentación Manga'),0,1,'L');
  
  $pdf->Ln(2);
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(0,4,utf8_decode('Etiqueta de identificación por empaque (Queso):'),0,1,'L');
  $pdf->SetFont('Arial','I',8);  
  $pdf->Cell(0,4,utf8_decode('- Q+Número de empaque '),0,1,'L');
  $pdf->Cell(0,4,utf8_decode('- Producto '),0,1,'L');
  $pdf->Cell(0,4,utf8_decode('- Cantidad de rollos '),0,1,'L');
  $pdf->Cell(0,4,utf8_decode('- Total de metros en el empaque'),0,1,'L');
  $pdf->Cell(0,4,utf8_decode('- Aproximado de piezas en el empaque'),0,1,'L');
  $pdf->Cell(0,4,utf8_decode('- Código en formato de barras del empaque'),0,1,'L');
  $pdf->Cell(0,4,utf8_decode('- Información básica de almacenamiento'),0,1,'L');
  
  $pdf->Ln(2);
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(0,4,utf8_decode('Etiqueta de identificación por rollo:'),0,1,'L');
  $pdf->SetFont('Arial','I',8);  
  $pdf->Cell(0,4,utf8_decode('- Q+Número de empaque '),0,1,'L');
  $pdf->Cell(0,4,utf8_decode('- R+Número de rollo '),0,1,'L');
  $pdf->Cell(0,4,utf8_decode('- Producto '),0,1,'L');
  $pdf->Cell(0,4,utf8_decode('- Ancho '),0,1,'L');
  $pdf->Cell(0,4,utf8_decode('- Alto '),0,1,'L');
  $pdf->Cell(0,4,utf8_decode('- Cantidad de metros'),0,1,'L');
  $pdf->Cell(0,4,utf8_decode('- Aproximado de piezas'),0,1,'L');
  $pdf->Cell(0,4,utf8_decode('- Peso'),0,1,'L');
  $pdf->Cell(0,4,utf8_decode('- Código numérico y en formato de barras del rollo'),0,1,'L');

  $pdf->Ln(2);
  $pdf->SetFont('Arial','',8);
  $pdf->MultiCell(0,4,utf8_decode('Empaque con espuma foam de 5 milímetros, en bolsas de polietileno recubiertas con cartón corrugado Single face hasta un máximo de 3 rollos.'));
  
  $pdf->Ln(2);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(40,4,utf8_decode('Presentación Pieza cortada'),0,1,'L');
  
  $pdf->Ln(2);
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(0,4,utf8_decode('Etiqueta de identificación por empaque (Caja):'),0,1,'L');
  $pdf->SetFont('Arial','I',8);  
  $pdf->Cell(0,4,utf8_decode('- C+Número de empaque '),0,1,'L');
  $pdf->Cell(0,4,utf8_decode('- Producto '),0,1,'L');
  $pdf->Cell(0,4,utf8_decode('- Cantidad de paquetes '),0,1,'L');
  $pdf->Cell(0,4,utf8_decode('- Aproximado de piezas en el empaque'),0,1,'L');
  $pdf->Cell(0,4,utf8_decode('- Código en formato de barras del empaque'),0,1,'L');
  $pdf->Cell(0,4,utf8_decode('- Información básica de almacenamiento'),0,1,'L');

  $pdf->Ln(2);
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(0,4,utf8_decode('Etiqueta de identificación por paquete:'),0,1,'L');
  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(0,4,utf8_decode('- Código numérico y en formato de barras del paquete'),0,1,'L');

  $pdf->Ln(2);
  $pdf->SetFont('Arial','',8);
  $pdf->MultiCell(0,4,utf8_decode('Empaque con espuma foam de 4 milímetros, en bolsas de polietileno en cajas de cartón corrugado con el máximo que permitán las dimensiones del paquete de cada producto.'));

  $pdf->Ln(2);
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(40,6,utf8_decode('Condiciones de almacenamiento'),0,1,'L');

  $pdf->Ln(2);
  $pdf->SetFont('Arial','',8);
  $pdf->MultiCell(0,4,utf8_decode('Los empaques deben almacenarce en un área limpia fresca y seca, protegida de la intemperia, humedad y fuentes de calor, a una distancia de 15 cm del suelo preferente mente en tarimas y un metro de la pared. A una temperatura menor a los 25 grados centigrados.'));
  $pdf->Ln(1);
  $pdf->MultiCell(0,4,utf8_decode('El tiempo de almacenamiento máximo sugerido es de un año bajo las condiciones antes mencionadas, con una estiba máxima de 3 empaques.'));

  ////////////////////////////////////////////////
	$pdf->Output('A-01-POT-7.5.1.pdf', 'D');
?>