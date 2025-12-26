<?php
  require('../../fpdf/fpdf.php');

  class PDF extends FPDF
  { // Cabecera de página
    function Header()
    { global $oplist_sustrato;

      $this->SetFont('Arial','B',8);
      $this->SetFillColor(255,153,0);

      if (file_exists('../../img_sistema/logonew.jpg')==true)
      { $this->Image('../../img_sistema/logonew.jpg',10,0,0,32); }

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Formato para alta de producto'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode(''),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Versión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode(''),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Revisión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Mayo 2015'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Responsable'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode(''),0,1,'L'); 

      $this->Ln(8); }

    // Pie de página
    function Footer()
    { $this->SetY(-20);

      $this->SetFont('Arial','B',8);
      $this->Cell(195.9,4,'______________________________________________________',0,1,'C');
      $this->Cell(195.9,4,utf8_decode('Firma del autorización'),0,1,'C');

      $this->SetY(-10);
      $this->SetFont('arial','B',8);
      $this->Cell(0,6,'Consultado el '.date("Y-m-d").' a las '.date("G:i:s").'',0,1,'R'); 
      $this->SetFont('arial','',8);
      $this->SetY(-7.5);
      $this->Cell(0,6,utf8_decode('Grupo Labro | Página '.$this->PageNo().' de {nb}'),0,1,'R'); }
  }

  $pdf = new PDF('P','mm','letter');
  $pdf->SetDisplayMode(real, continuous);
  $pdf->AddFont('3of9','','free3of9.php');
  $pdf->AliasNbPages();

  $pdf->AddPage();

  $pdf->SetFillColor(255,153,0);

  $pdf->SetFont('Arial','I',9);
  $pdf->Cell(55,5,utf8_decode('Diseño'),0,0,'R');
  $pdf->Cell(0.5,5,'',0,0,'R',true);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(60,5,'________________________________________________',0,1,'L');

  $pdf->SetFont('Arial','I',9);
  $pdf->Cell(55,5,utf8_decode('Código'),0,0,'R');
  $pdf->Cell(0.5,5,'',0,0,'R',true);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(60,5,'________________________',0,1,'L');

  $pdf->SetFont('Arial','I',9);
  $pdf->Cell(55,5,utf8_decode('Descripción'),0,0,'R');
  $pdf->Cell(0.5,5,'',0,0,'R',true);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(60,5,'________________________________________________',0,1,'L');

  $pdf->SetFont('Arial','I',9);
  $pdf->Cell(55,5,utf8_decode('Ancho de pelicula mm'),0,0,'R');
  $pdf->Cell(0.5,5,'',0,0,'R',true);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(60,5,'________________',0,1,'L');

  $pdf->SetFont('Arial','I',9);
  $pdf->Cell(55,5,utf8_decode('Tipo y medida de materia prima'),0,0,'R');
  $pdf->Cell(0.5,5,'',0,0,'R',true);  
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(60,5,'________________________________________________',0,1,'L');

  $pdf->SetFont('Arial','I',9);
  $pdf->Cell(55,5,utf8_decode('Tipo de banda de seguridad'),0,0,'R');
  $pdf->Cell(0.5,5,'',0,0,'R',true);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(60,5,'________________________________________________',0,1,'L');

  $pdf->SetFont('Arial','I',9);
  $pdf->Cell(55,5,utf8_decode('Ancho de etiqueta mm'),0,0,'R');
  $pdf->Cell(0.5,5,'',0,0,'R',true);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(60,5,'________________',0,1,'L');

  $pdf->SetFont('Arial','I',9);
  $pdf->Cell(55,5,utf8_decode('Altura de etiqueta mm'),0,0,'R');
  $pdf->Cell(0.5,5,'',0,0,'R',true);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(60,5,'________________',0,1,'L');

  $pdf->SetFont('Arial','I',9);
  $pdf->Cell(55,5,utf8_decode('Espacio para fusión mm'),0,0,'R');
  $pdf->Cell(0.5,5,'',0,0,'R',true);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(60,5,'________________',0,1,'L');  

  $pdf->SetFont('Arial','I',9);
  $pdf->Cell(55,5,utf8_decode('Millares por rollo'),0,0,'R');
  $pdf->Cell(0.5,5,'',0,0,'R',true);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(60,5,'________________',0,1,'L');

  $pdf->SetFont('Arial','I',9);
  $pdf->Cell(55,5,utf8_decode('Porcentaje +/- de millares por rollo'),0,0,'R');
  $pdf->Cell(0.5,5,'',0,0,'R',true);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(60,5,'________________',0,1,'L');  

  $pdf->SetFont('Arial','I',9);
  $pdf->Cell(55,5,utf8_decode('Millares por paquete'),0,0,'R');
  $pdf->Cell(0.5,5,'',0,0,'R',true);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(60,5,'________________',0,1,'L');

  $pdf->SetFont('Arial','I',9);
  $pdf->Cell(55,5,utf8_decode('Número de tintas'),0,0,'R');
  $pdf->Cell(0.5,5,'',0,0,'R',true);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(60,5,'________________',0,1,'L');

  $pdf->SetFont('Arial','I',9);
  $pdf->Cell(55,5,utf8_decode('Número de pantones'),0,0,'R');
  $pdf->Cell(0.5,5,'',0,0,'R',true);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(60,5,'________________________________________________',0,1,'L');

  $pdf->Ln(4);

  ////////////////////////////////////////////////
  $pdf->Output('PRO-FR05.pdf', 'I');
?>