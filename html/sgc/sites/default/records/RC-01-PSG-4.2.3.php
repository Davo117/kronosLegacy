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
      $this->Cell(75,4,utf8_decode('Solicitud de emisión o cambio a documentos'),0,1,'L');
      
      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('RC-01-PSG-4.2.3'),0,1,'L');
      
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
      $this->Cell(75,4,utf8_decode('Representante de la dirección'),0,1,'L'); 
    
      $this->Ln(4); }

    // Pie de página
    function Footer()
    { $this->SetFont('arial','',8);
      $this->SetY(-7.5);
      $this->Cell(0,6,utf8_decode('Grupo Labro | Sistema de Gestión de la Calidad, página '.$this->PageNo().' de {nb}'),0,1,'R'); }
  }

  $pdf = new PDF('P','mm','letter');
  $pdf->SetDisplayMode(real, continuous);
  $pdf->AliasNbPages();

  $pdf->AddPage();

  $pdf->SetFont('Arial','I',8);
  $pdf->SetFillColor(255,153,0);

  $pdf->Cell(30.5,4.15,utf8_decode('Solicitante'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,0,'R',true);

  $pdf->Cell(90.5,4.15,utf8_decode('Documento'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,1,'R',true);

  $pdf->Line(42,$pdf->GetY(),110,$pdf->GetY());
  $pdf->Line(133,$pdf->GetY(),196,$pdf->GetY());

  $pdf->Cell(30.5,4.15,utf8_decode('Puesto'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,0,'R',true);

  $pdf->Cell(90.5,4.15,utf8_decode('Código'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,1,'R',true);

  $pdf->Line(42,$pdf->GetY(),110,$pdf->GetY());
  $pdf->Line(133,$pdf->GetY(),176,$pdf->GetY());

  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(30.5,4.15,utf8_decode('Tipo de solicitud'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,0,'R',true);
  $pdf->Cell(5,4.15,'',0,0,'R');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(24,4.15,utf8_decode('Creación'),0,0,'L');
  $pdf->Cell(20,4.15,utf8_decode('Cambio'),0,0,'L');

  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(41.5,4.15,utf8_decode('Versión actual'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,1,'R',true);

  $pdf->Line(42,$pdf->GetY(),46,$pdf->GetY());
  $pdf->Line(66,$pdf->GetY(),70,$pdf->GetY());
  $pdf->Line(133,$pdf->GetY(),160,$pdf->GetY());

  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(30.5,4.15,utf8_decode('Afecta al personal'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,0,'R',true);
  $pdf->Cell(5,4.15,'',0,0,'R');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(24,4.15,utf8_decode('Si'),0,0,'L');
  $pdf->Cell(20,4.15,utf8_decode('No'),0,0,'L'); //*/

  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(41.5,4.15,utf8_decode('Fecha'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,1,'R',true);
  
  $pdf->Line(42,$pdf->GetY(),46,$pdf->GetY());
  $pdf->Line(66,$pdf->GetY(),70,$pdf->GetY());
  $pdf->Line(133,$pdf->GetY(),160,$pdf->GetY());

  $pdf->Ln(4.15);  

  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(30.5,4.15,utf8_decode('Tipo de documento'),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,0,'R',true);
  $pdf->Cell(5,4.15,'',0,0,'R');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(38,4.15,utf8_decode("MC: Manual de Calidad"),0,0,'L');
  $pdf->Cell(55,4.15,utf8_decode("IT: Instrucciones de Trabajo"),0,0,'L');
  $pdf->Cell(50,4.15,utf8_decode("POT: Procedimientos Operativos de Trabajo"),0,1,'L');

  $pdf->Line(42,$pdf->GetY(),46,$pdf->GetY());
  $pdf->Line(80,$pdf->GetY(),84,$pdf->GetY());
  $pdf->Line(135,$pdf->GetY(),139,$pdf->GetY());
  
  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(30.5,4.15,utf8_decode(''),0,0,'R');
  $pdf->Cell(0.5,4.15,'',0,0,'R',true);
  $pdf->Cell(5,4.15,'',0,0,'R');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(38,4.15,utf8_decode("RC: Registro"),0,0,'L');
  $pdf->Cell(55,4.15,utf8_decode("PC: Planes de Calidad o Inspección"),0,0,'L');
  $pdf->Cell(50,4.15,utf8_decode("PSG: Procedimiento del Sistema de Gestión"),0,1,'L');

  $pdf->Line(42,$pdf->GetY(),46,$pdf->GetY());
  $pdf->Line(80,$pdf->GetY(),84,$pdf->GetY());
  $pdf->Line(135,$pdf->GetY(),139,$pdf->GetY());
  
  $pdf->Ln(4.15);
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(196,4.5,utf8_decode("Situación actual"),0,1,'L');
  for ($id=1;$id<=5;$id++) 
  { $pdf->Ln(4.15);
    $pdf->Line(20,$pdf->GetY(),200,$pdf->GetY()); }

  $pdf->Ln(4.15);
  
  $pdf->Cell(196,4.15,utf8_decode("Situación deseada y/o motivo del cambio"),0,1,'L');
  for ($id=1;$id<=5;$id++) 
  { $pdf->Ln(4.15);
    $pdf->Line(20,$pdf->GetY(),200,$pdf->GetY()); }

  $pdf->Ln(4.15);
  
  $pdf->Cell(196,4.15,utf8_decode("Partes afectadas"),0,1,'L');
  for ($id=1;$id<=5;$id++) 
  { $pdf->Ln(4.15);
    $pdf->Line(20,$pdf->GetY(),200,$pdf->GetY()); }

  $pdf->Ln(4.15);

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(196,4.15,utf8_decode("Revisión por parte del responsable de control de documentos"),0,1,'L');

  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(5,4.15,utf8_decode(""),0,0,'C');
  $pdf->Cell(165,4.15,utf8_decode("La emisión o cambio está de acuerdo a la estructura documental"),0,0,'L');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(14,4.15,utf8_decode('Si'),0,0,'L');
  $pdf->Cell(14,4.15,utf8_decode('No'),0,1,'L');

  $pdf->Line(176,$pdf->GetY(),180,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),194,$pdf->GetY());
  
  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(5,4.15,utf8_decode(""),0,0,'C');
  $pdf->Cell(165,4.15,utf8_decode("Se tienen las firmas de elaboración, revisión y aprobación"),0,0,'L');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(14,4.15,utf8_decode('Si'),0,0,'L');
  $pdf->Cell(14,4.15,utf8_decode('No'),0,1,'L');

  $pdf->Line(176,$pdf->GetY(),180,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),194,$pdf->GetY());
  
  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(5,4.15,utf8_decode(""),0,0,'C');
  $pdf->Cell(165,4.15,utf8_decode("La creación o cambio se ha realizado por las mismas funciones originales"),0,0,'L');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(14,4.15,utf8_decode('Si'),0,0,'L');
  $pdf->Cell(14,4.15,utf8_decode('No'),0,1,'L');

  $pdf->Line(176,$pdf->GetY(),180,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),194,$pdf->GetY());
  
  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(5,4.15,utf8_decode(""),0,0,'C');
  $pdf->Cell(165,4.15,utf8_decode("Se ha identificado la naturaleza de los cambios en el documento"),0,0,'L');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(14,4.15,utf8_decode('Si'),0,0,'L');
  $pdf->Cell(14,4.15,utf8_decode('No'),0,1,'L');

  $pdf->Line(176,$pdf->GetY(),180,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),194,$pdf->GetY());
  
  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(5,4.15,utf8_decode(""),0,0,'C');
  $pdf->Cell(165,4.15,utf8_decode("Se ha identificado como obsoleto el documento que se retendrá"),0,0,'L');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(14,4.15,utf8_decode('Si'),0,0,'L');
  $pdf->Cell(14,4.15,utf8_decode('No'),0,1,'L');

  $pdf->Line(176,$pdf->GetY(),180,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),194,$pdf->GetY());
  
  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(5,4.15,utf8_decode(""),0,0,'C');
  $pdf->Cell(165,4.15,utf8_decode("Se ha actualizado la nueva revisión en la lista maestra de documentos"),0,0,'L');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(14,4.15,utf8_decode('Si'),0,0,'L');
  $pdf->Cell(14,4.15,utf8_decode('No'),0,1,'L');

  $pdf->Line(176,$pdf->GetY(),180,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),194,$pdf->GetY());
  
  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(5,4.15,utf8_decode(""),0,0,'C');
  $pdf->Cell(165,4.15,utf8_decode("Se ha realizado difusión de la emisión o cambio si ésta fue necesaria"),0,0,'L');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(14,4.15,utf8_decode('Si'),0,0,'L');
  $pdf->Cell(14,4.15,utf8_decode('No'),0,1,'L');

  $pdf->Line(176,$pdf->GetY(),180,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),194,$pdf->GetY());
  
  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(5,4.15,utf8_decode(""),0,0,'C');
  $pdf->Cell(165,4.15,utf8_decode("La emisión o cambio está de acuerdo con los requerimientos del sistema de calidad"),0,0,'L');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(14,4.15,utf8_decode('Si'),0,0,'L');
  $pdf->Cell(14,4.15,utf8_decode('No'),0,1,'L');

  $pdf->Line(176,$pdf->GetY(),180,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),194,$pdf->GetY());
  
  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(5,4.15,utf8_decode(""),0,0,'C');
  $pdf->Cell(165,4.15,utf8_decode("La emisión o cambio garantiza compatibilidad entre la parte operativa y administrativa del S.G.C."),0,0,'L');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(14,4.15,utf8_decode('Si'),0,0,'L');
  $pdf->Cell(14,4.15,utf8_decode('No'),0,1,'L');

  $pdf->Line(176,$pdf->GetY(),180,$pdf->GetY());
  $pdf->Line(190,$pdf->GetY(),194,$pdf->GetY());

  $pdf->Ln(4.15);

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(196,4.15,utf8_decode("Si la difusión de la emisión o cambio fue necesaria, favor de recabar las firmas de los involucrados"),0,1,'L');
  for ($id=1;$id<=3;$id++)
  { $pdf->Ln(13); 

    $pdf->Cell(98,4.15,utf8_decode("Nombre y firma"),0,0,'C');
    $pdf->Cell(98,4.15,utf8_decode("Nombre y firma"),0,1,'C'); 

    $pdf->Ln(-4.15); 

      $pdf->Line(20,$pdf->GetY(),98,$pdf->GetY());
      $pdf->Line(118,$pdf->GetY(),196,$pdf->GetY());
  }

  $pdf->Ln(4.15);

  $pdf->SetY(-20);

  $pdf->Line(20,$pdf->GetY(),80,$pdf->GetY());
  $pdf->Line(88,$pdf->GetY(),148,$pdf->GetY());
  $pdf->Line(156,$pdf->GetY(),196,$pdf->GetY());

  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(10,4.15,utf8_decode(''),0,0,'C');
  $pdf->Cell(60,4.15,utf8_decode('Solicitante'),0,0,'C');
  $pdf->Cell(8,4.15,utf8_decode(''),0,0,'C');
  $pdf->Cell(60,4.15,utf8_decode('Representante de la dirección'),0,0,'C');
  $pdf->Cell(8,4.15,utf8_decode(''),0,0,'C');
  $pdf->Cell(40,4.15,utf8_decode('Fecha de aprobación'),0,1,'C');

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(10,4.15,utf8_decode(''),0,0,'C');
  $pdf->Cell(60,4.15,utf8_decode('Elaboró'),0,0,'C');
  $pdf->Cell(8,4.15,utf8_decode(''),0,0,'C');
  $pdf->Cell(60,4.15,utf8_decode('Revisó'),0,1,'C');
  
  $pdf->Output('RC-01-PSG-4.2.3.pdf', 'D');
?>