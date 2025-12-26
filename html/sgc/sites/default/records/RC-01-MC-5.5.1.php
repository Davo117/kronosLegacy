<?php
  require('fpdf/fpdf.php');

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
      $this->Cell(125.5,4,'',0,0,'R');
      $this->Cell(75,4,utf8_decode('Matriz de responsabilidades'),0,1,'L');
      $this->Cell(125.5,4,'',0,0,'R');
      $this->Cell(75,4,utf8_decode('RC-01-MC-5.5.1'),0,1,'L');
      $this->Cell(125.5,4,'',0,0,'R');
      $this->Cell(75,4,utf8_decode('1.0'),0,1,'L');
      $this->Cell(125.5,4,'',0,0,'R');
      $this->Cell(75,4,utf8_decode('Abril 21, 2014'),0,1,'L');
      $this->Cell(125.5,4,'',0,0,'R');
      $this->Cell(75,4,utf8_decode('Representante de la Dirección'),0,1,'L');

      $this->Ln(4);
      $this->SetFont('Arial','',10);
      $this->Cell(90,4,'Requisito de la Norma ISO 9001:2008',0,0,'L');
      $this->Cell(65,4,'Puesto del responsable',0,1,'L');
    }

    // Pie de página
		function Footer()
		{ $this->SetY(-10);
		  $this->SetFont('arial','B',8);
		  $this->Cell(0,6,'',0,1,'R'); 
		  $this->SetFont('arial','',8);
		  $this->SetY(-7.5);
		  $this->Cell(0,6,utf8_decode('Grupo Labro | Sistema de Gestión de Calidad, página '.$this->PageNo().' de {nb}'),0,1,'R'); 	    
		}
	}

	$pdf = new PDF('P','mm','letter');
	$pdf->SetDisplayMode(real, continuous);
  $pdf->AliasNbPages();
  $pdf->SetFillColor(255,153,0);
  
  $pdf->AddPage();
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(155,5,utf8_decode('4.0 Sistema de Gestión de la Calidad'),1,1,'L',true);

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4,'4.1',0,0,'L');
  $pdf->Cell(80,4,utf8_decode('Requisitos generales'),0,1,'L');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4,'4.2',0,0,'L');
  $pdf->Cell(80,4,utf8_decode('Requisitos de la documentación'),0,1,'L');
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(10,4,'4.2.1',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Generalidades'),0,1,'L');
  $pdf->Cell(10,4,'4.2.2',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Manual de la calidad'),0,1,'L');
  $pdf->Cell(10,4,'4.2.3',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Control de los documentos'),0,1,'L');
  $pdf->Cell(10,4,'4.2.3',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Control de los registros'),0,1,'L');

  $pdf->SetY($pdf->GetY()-24);
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(90,24,'',1,0,'L');
  $pdf->Cell(65,24,utf8_decode('Representante de la Dirección'),1,1,'C');

  $pdf->AddPage();
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(155,5,utf8_decode('5.0 Responsabilidad de la dirección'),1,1,'L',true);

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4,'5.1',0,0,'L');
  $pdf->Cell(80,4,utf8_decode('Compromiso de la dirección'),0,1,'L');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4,'5.2',0,0,'L');
  $pdf->Cell(80,4,utf8_decode('Enfoque al cliente'),0,1,'L');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4,'5.3',0,0,'L');
  $pdf->Cell(80,4,utf8_decode('Política de la calidad'),0,1,'L');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4,'5.4',0,0,'L');
  $pdf->Cell(80,4,utf8_decode('Planificación'),0,1,'L');
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(10,4,'5.4.1',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Objetivos de calidad'),0,1,'L');
  $pdf->Cell(10,4,'5.4.2',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Planificación del sistema de gestión de la calidad'),0,1,'L');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4,'5.5',0,0,'L');
  $pdf->Cell(80,4,utf8_decode('Responsabilidad, autoridad y comunicación'),0,1,'L');
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(10,4,'5.5.1',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Responsabilidad y autoridad'),0,1,'L');
  $pdf->Cell(10,4,'5.5.2',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Representante de la dirección'),0,1,'L');
  $pdf->Cell(10,4,'5.5.3',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Comunicación interna'),0,1,'L');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4,'5.6',0,0,'L');
  $pdf->Cell(80,4,utf8_decode('Revisión por la dirección'),0,1,'L');
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(10,4,'5.6.1',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Generalidades'),0,1,'L');
  $pdf->Cell(10,4,'5.6.2',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Información de entrada para la revisión'),0,1,'L');
  $pdf->Cell(10,4,'5.6.3',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Resultados de la revisión'),0,1,'L');

  $pdf->SetY($pdf->GetY()-56);
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(90,56,'',1,0,'L');
  $pdf->Cell(65,56,utf8_decode('Director del Sistema de Gestión de la Calidad'),1,1,'C');

  $pdf->AddPage();
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(155,5,utf8_decode('6.0 Gestión de recursos'),1,1,'L',true);

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4,'6.1',0,0,'L');
  $pdf->Cell(80,4,utf8_decode('Provisión de recursos'),0,1,'L');

  $pdf->SetY($pdf->GetY()-4);
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(90,4,'',1,0,'L');
  $pdf->Cell(65,4,utf8_decode('Director del Sistema de Gestión de la Calidad'),1,1,'C');
  
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4,'6.2',0,0,'L');
  $pdf->Cell(80,4,utf8_decode('Recursos humanos'),0,0,'L');
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(65,4,utf8_decode('Jefe de Recursos Humanos'),0,1,'C');
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(10,4,'6.2.1',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Generalidades'),0,1,'L');
  $pdf->Cell(10,4,'6.2.2',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Competencia, formación y toma de conciencia'),0,1,'L');
  
  $pdf->SetY($pdf->GetY()-12);
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(90,12,'',1,0,'L');
  $pdf->Cell(65,12,utf8_decode(''),1,1,'C');

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4,'6.3',0,0,'L');
  $pdf->Cell(80,4,utf8_decode('Infraestructura'),0,1,'L');
  
  $pdf->SetY($pdf->GetY()-4);
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(90,4,'',1,0,'L');
  $pdf->Cell(65,4,utf8_decode('Jefe de Mantenimiento'),1,1,'C');

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4,'6.4',0,0,'L');
  $pdf->Cell(80,4,utf8_decode('Ambiente de trabajo'),0,1,'L');

  $pdf->SetY($pdf->GetY()-4);
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(90,4,'',1,0,'L');
  $pdf->Cell(65,4,utf8_decode('Jefe de Recursos Humanos'),1,1,'C');

  $pdf->AddPage();
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(155,5,utf8_decode('7.0 Realización del producto'),1,1,'L',true);

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4,'7.1',0,0,'L');
  $pdf->Cell(80,4,utf8_decode('Planificación de la realizacion del producto'),0,1,'L');
  
  $pdf->SetY($pdf->GetY()-4);
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(90,4,'',1,0,'L');
  $pdf->Cell(65,4,utf8_decode('Director del Sistema de Gestión de la Calidad'),1,1,'C');

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4,'7.2',0,0,'L');
  $pdf->Cell(80,4,utf8_decode('Procesos relacionados con el cliente'),0,0,'L');
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(65,4,utf8_decode('Gerente Administrativo y Comercial'),0,1,'C');
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(10,4,'7.2.1',0,0,'R');
  
  $pdf->SetFillColor(255,0,0);
  $pdf->Cell(80,4,utf8_decode('Determinación de los requisitos relacionados con el producto'),0,0,'L',true);
    $pdf->Cell(63,4,utf8_decode('Vendedor'),0,1,'C',true);  
  $pdf->SetFillColor(255,153,0);

  $pdf->SetFont('Arial','',8);
  $pdf->Cell(10,4,'7.2.2',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Revisión de los requisitos relacionados con el producto'),0,0,'L',true);
    $pdf->Cell(60,4,utf8_decode('Encargado de Logistica y atención al cliente'),0,0,'C',true);
    $pdf->SetFillColor(255,0,0);
      $pdf->Cell(3,4,utf8_decode(''),0,1,'C',true);
    $pdf->SetFillColor(255,153,0);
  $pdf->Cell(10,4,'7.2.3',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Comunicación con el cliente'),0,0,'L',true);
    $pdf->Cell(60,4,utf8_decode(''),0,0,'C',true);
    $pdf->SetFillColor(255,0,0);
      $pdf->Cell(3,4,utf8_decode(''),0,1,'C',true);
    $pdf->SetFillColor(255,153,0);
  $pdf->SetY($pdf->GetY()-16);
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(90,16,'',1,0,'L');  
  $pdf->Cell(65,16,utf8_decode(''),1,1,'C');

/*$pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4,'7.3',0,0,'L');
  $pdf->Cell(80,4,utf8_decode('Diseño y desarrollo'),0,1,'L');
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(10,4,'7.3.1',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Planificación del diseño y desarrollo'),0,1,'L');
  $pdf->Cell(10,4,'7.3.2',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Elementos de entrada para el diseño y desarrollo'),0,1,'L');
  $pdf->Cell(10,4,'7.3.3',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Resultados del diseño y desarrollo'),0,1,'L');
  $pdf->Cell(10,4,'7.3.4',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Revisión del diseño y desarrollo'),0,1,'L');
  $pdf->Cell(10,4,'7.3.5',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Verificación del diseño y desarrollo'),0,1,'L');
  $pdf->Cell(10,4,'7.3.6',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Validación del diseño y desarrollo'),0,1,'L');
  $pdf->Cell(10,4,'7.3.7',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Control de los cambios del diseño y desarrollo'),0,1,'L'); //*/
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4,'7.4',0,0,'L');
  $pdf->Cell(80,4,utf8_decode('Compras'),0,0,'L');
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(65,4,utf8_decode('Encargado de Compras'),0,1,'C');
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(10,4,'7.4.1',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Proceso de compras'),0,1,'L');
  $pdf->Cell(10,4,'7.4.2',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Información de las compras'),0,1,'L');

  $pdf->Cell(10,4,'7.4.3',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Verificación de los productos comprados'),0,0,'L',true);

  $pdf->SetFont('Arial','',8);
  $pdf->Cell(60,4,utf8_decode('Almacenista(s)'),0,0,'C',true);
  $pdf->Cell(5,4,utf8_decode(''),0,1,'C');

  $pdf->SetY($pdf->GetY()-16);
  $pdf->Cell(90,16,'',1,0,'L');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(65,16,utf8_decode(''),1,1,'C');

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4,'7.5',0,0,'L');
  $pdf->Cell(80,4,utf8_decode('Producción y prestación del servicio'),0,0,'L');
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(60,4,utf8_decode('Gerente de Operaciones'),0,1,'C');
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(10,4,'7.5.1',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Control de la producción y de la prestación del servicio'),0,1,'L');


/*$pdf->Cell(10,4,'7.5.2',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Validación de los procesos de la producción y de la prestación del servicio'),0,1,'L'); //*/

  $pdf->SetFont('Arial','',8);
  $pdf->Cell(10,4,'7.5.3',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Identificación y trazabilidad'),0,0,'L',true);
    $pdf->Cell(60,4,utf8_decode('Supervisor(es) de producción'),0,1,'C',true);
  $pdf->Cell(10,4,'7.5.4',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Propiedad del cliente'),0,0,'L',true);
    $pdf->Cell(60,4,utf8_decode(''),0,1,'C',true);
  $pdf->Cell(10,4,'7.5.5',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Preservación del producto'),0,0,'L',true);
    $pdf->Cell(60,4,utf8_decode(''),0,1,'C',true);

  $pdf->SetY($pdf->GetY()-20);
  $pdf->Cell(90,20,'',1,0,'L');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(65,20,utf8_decode(''),1,1,'C');

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4,'7.6',0,0,'L');
  $pdf->Cell(80,4,utf8_decode('Control de los equipos de seguimiento y medición'),0,1,'L');

  $pdf->SetY($pdf->GetY()-4);
  $pdf->Cell(90,4,'',1,0,'L');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(65,4,utf8_decode('Inspector de calidad'),1,1,'C');

  $pdf->AddPage();
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(155,5,utf8_decode('8.0 Medición, análisis y mejora'),1,1,'L',true);

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4,'8.1',0,0,'L');
  $pdf->Cell(80,4,utf8_decode('Generalidades'),0,0,'L');
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(65,4,utf8_decode('Representante de la Dirección'),0,1,'C');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4,'8.2',0,0,'L');
  $pdf->Cell(80,4,utf8_decode('Seguimiento y medición'),0,1,'L');
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(10,4,'8.2.1',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Satisfacción del cliente'),0,0,'L',true);
    $pdf->Cell(60,4,utf8_decode('Encargado de logistica y atención a cliente'),0,1,'C',true);
  $pdf->Cell(10,4,'8.2.2',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Auditoria interna'),0,1,'L');
  $pdf->Cell(10,4,'8.2.3',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Seguimiento y medición de los procesos'),0,1,'L');
  $pdf->Cell(10,4,'8.2.4',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Seguimiento y medición del producto'),0,0,'L',true);
    $pdf->Cell(60,4,utf8_decode('Gerente de Operaciones'),0,1,'C',true);
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4,'8.3',0,0,'L');
  $pdf->Cell(80,4,utf8_decode('Control del producto no conforme'),0,0,'L',true);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(60,4,utf8_decode('Inspector de calidad, Almacenista(s)'),0,1,'C',true);
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4,'8.4',0,0,'L');
  $pdf->Cell(80,4,utf8_decode('Análisis de datos'),0,1,'L');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4,'8.5',0,0,'L');
  $pdf->Cell(80,4,utf8_decode('Mejora'),0,1,'L');
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(10,4,'8.5.1',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Mejora continua'),0,1,'L');
  $pdf->Cell(10,4,'8.5.2',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Acción correctiva'),0,1,'L');
  $pdf->Cell(10,4,'8.5.3',0,0,'R');
  $pdf->Cell(80,4,utf8_decode('Acción preventiva'),0,1,'L');

  $pdf->SetY($pdf->GetY()-48);
  $pdf->Cell(90,48,'',1,0,'L');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(65,48,utf8_decode(''),1,1,'C');

  ////////////////////////////////////////////////
	$pdf->Output('RC-01-MC-5.5.1.pdf', 'D');
?>