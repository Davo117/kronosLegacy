<?php
  require('fpdf/fpdf.php');

  class PDF extends FPDF
  { // Cabecera de página
    function Header()
    { if (file_exists('../images/logonew.png')==true) 
      { $this->Image('../images/logonew.png',10,10,0,15); }

      $this->SetFillColor(255,153,0);
      
      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Solicitud y reporte de mantenimiento'),0,1,'L');
        
      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('RC-03-PSG-6.3'),0,1,'L');
        
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
      $this->Cell(75,4,utf8_decode('Responsables de Mantenimiento o TI'),0,1,'L'); 
      
      $this->Ln(4.15); }

    // Pie de página
    function Footer()
    { $this->SetY(-20);
      $this->Line(20,$this->GetY(),98,$this->GetY());
      $this->Line(118,$this->GetY(),196,$this->GetY());

      $this->SetFont('Arial','B',8);
      $this->Cell(98,4,utf8_decode('Firma de quien realiza el mantenimiento'),0,0,'C');
      $this->Cell(98,4,utf8_decode('Vo. Bo. del solicitante o su jefe inmediato'),0,1,'C'); 

      $this->Ln(4.15);
      $this->Cell(0,4,'Consultado '.date("Y-m-d").' '.date("G:i:s").'',0,1,'R'); 
      $this->SetFont('arial','',8);      
      $this->Cell(0,4,utf8_decode('Grupo Labro | Sistema de Gestión de Calidad Pagina ').$this->PageNo().'/{nb}',0,1,'R'); }
  }
  
  $pdf = new PDF('P','mm','letter');
  $pdf->SetDisplayMode(real, continuous);
  $pdf->AliasNbPages();

  $pdf->AddPage();
  $pdf->SetFillColor(255,153,0);

  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(30,4,utf8_decode('Solicitante'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,0,'R',true);
  $pdf->Cell(104,4.155,'',0,0);
  $pdf->Cell(30,4,utf8_decode('Fecha de solicitud'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,1,'R',true);
  
  $pdf->Line(41,$pdf->GetY(),125,$pdf->GetY());
  $pdf->Line(175.5,$pdf->GetY(),205,$pdf->GetY());

  $pdf->Cell(30,4,utf8_decode('Departamento'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,0,'R',true);  
  $pdf->Cell(104,4,'',0,0);
  $pdf->Cell(30,4,utf8_decode('No. Solicitud de mantenimiento'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,1,'R',true);
  
  $pdf->Line(41,$pdf->GetY(),125,$pdf->GetY());
  $pdf->Line(175.5,$pdf->GetY(),205,$pdf->GetY());

  $pdf->Cell(30,4,utf8_decode('Estación de trabajo'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,1,'R',true);
  $pdf->Line(41,$pdf->GetY(),125,$pdf->GetY());

  $pdf->Ln(4.15);

  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(40,8,utf8_decode('Descripción de la solicitud'),0,1,'L');

  //$pdf->Line(20,$pdf->GetY(),60,$pdf->GetY());
  $pdf->Ln(50);  
  $pdf->Line(190,$pdf->GetY(),205,$pdf->GetY());

  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(40,8,utf8_decode('Reporte del mantenimiento'),0,1,'L');

  //$pdf->Line(20,$pdf->GetY(),60,$pdf->GetY());
  $pdf->Ln(90);
  $pdf->Line(190,$pdf->GetY(),205,$pdf->GetY());
  
  ////////////////////////////////////////////////
  $pdf->Output('RC-03-PSG-6.3.pdf', 'D');
?>