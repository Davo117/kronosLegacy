<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{ function Header()
  { if (file_exists('logo.png')==true)
    { $this->Image('logo.png',12,12,0,20); }

    $this->SetFont('Arial','B',12);
    $this->Cell(139,24,'',1,0,'C');
    $this->Cell(50,5,utf8_decode('Fecha de versión'),1,0,'C');
    $this->Cell(70,5,utf8_decode('Responsable'),1,1,'C');

    $this->SetFont('Arial','',10);
    $this->Cell(139,7,'',0,0,'C');
    $this->Cell(50,7,utf8_decode('16 Octubre 2013'),1,0,'C');
    $this->Cell(70,7,utf8_decode('Representante de la dirección'),1,1,'C');

    $this->SetFont('Arial','B',12);
    $this->Cell(139,5,'',0,0,'C');
    $this->Cell(50,5,utf8_decode('Versión'),1,0,'C');
    $this->Cell(70,5,utf8_decode('Código'),1,1,'C');

    $this->SetFont('Arial','',10);
    $this->Cell(139,7,'',0,0,'C');
    $this->Cell(50,7,utf8_decode('1.0'),1,0,'C');
    $this->Cell(70,7,utf8_decode('RC-01-PSG-8.5'),1,1,'C');

    $this->Ln(1);
  }
}

$pdf = new PDF('L','mm','letter');
$pdf->AddPage();
$pdf->AliasNbPages();

$pdf->SetFont('Arial','B',14);
$pdf->SetFillColor(200,220,255);
$pdf->Cell(259,8,utf8_decode("Reporte de acciones correctivas y preventivas"),1,1,'C',true);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(179,6,utf8_decode("Quien levanta la acción (Puesto):"),1,0,'T');
$pdf->Cell(80,6,utf8_decode("Fecha de la acción:"),1,1,'T');
$pdf->Cell(179,6,utf8_decode("Responsable de la acción:"),1,0,'T');
$pdf->Cell(80,6,utf8_decode("Tipo de acción:  (     ) Correctiva   (     ) Preventiva"),1,1,'T');
$pdf->Cell(179,6,utf8_decode("Origen de la acción:"),1,0,'T');
$pdf->Cell(80,6,utf8_decode("Consecutivo:"),1,1,'T');
$pdf->SetFont('Arial','',8);
$pdf->Ln(1);
$pdf->Cell(179,4,utf8_decode(""),0,0,'C');
$pdf->Cell(36,4,utf8_decode("Seis digitos: (AX-XX-XX) Tipo de acción-Año-Consecutivo"),0,1,'L');

$pdf->Ln(-2);
$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(200,220,255);
$pdf->Cell(259,4,utf8_decode("Descripción de la no conformidad (real o potencial):"),0,1,'L');
$pdf->Cell(259,1,utf8_decode(""),1,1,'C',true);
$pdf->Cell(259,5,utf8_decode(""),1,1,'L');
$pdf->Cell(259,5,utf8_decode(""),1,1,'L');
$pdf->Cell(259,5,utf8_decode(""),1,1,'L');
$pdf->Cell(259,5,utf8_decode(""),1,1,'L');
$pdf->Cell(259,5,utf8_decode(""),1,1,'L');

$pdf->Ln(2);
$pdf->Cell(259,4,utf8_decode("Plan de acción inmediata"),0,1,'L');
$pdf->Ln(-4);
$pdf->SetFont('Arial','',8);
$pdf->Cell(40,4,utf8_decode(""),0,0,'L');
$pdf->Cell(129,4,utf8_decode("(Indicar se si realizarán acciones para detener el problema de momento)"),0,0,'L');
$pdf->Cell(80,4,utf8_decode("(     ) Aplicable   (     ) No aplicable"),0,1,'L');

$pdf->SetFont('Arial','B',8);
$pdf->Cell(159,5,utf8_decode("Actividad de contención"),1,0,'C',true);
$pdf->Cell(70,5,utf8_decode("Responsable"),1,0,'C',true);
$pdf->Cell(30,5,utf8_decode("Fecha"),1,1,'C',true);
$pdf->Cell(159,20,utf8_decode(""),1,0,'L');
$pdf->Cell(70,20,utf8_decode(""),1,0,'L');
$pdf->Cell(30,20,utf8_decode(""),1,1,'L');

$pdf->Ln(1);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(259,4,utf8_decode("Estudio de las causas de la no conformidad (real o potencial)"),0,1,'L');

$pdf->Cell(43,5,utf8_decode("(    ) Método de trabajo"),1,0,'L',true);
$pdf->Cell(43,5,utf8_decode("(    ) Mano / mente de obra"),1,0,'L',true);
$pdf->Cell(43,5,utf8_decode("(    ) Materiales o insumos"),1,0,'L',true);
$pdf->Cell(43,5,utf8_decode("(    ) Maquinaria o equipo"),1,0,'L',true);
$pdf->Cell(44,5,utf8_decode("(    ) Medio ambiente o entorno"),1,0,'L',true);
$pdf->Cell(43,5,utf8_decode("(    ) Medición o seguimiento"),1,1,'L',true);

$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(44,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,1,'L');
$pdf->Ln(-5);
$pdf->Cell(43,14,utf8_decode(""),1,0,'L');
$pdf->Cell(43,14,utf8_decode(""),1,0,'L');
$pdf->Cell(43,14,utf8_decode(""),1,0,'L');
$pdf->Cell(43,14,utf8_decode(""),1,0,'L');
$pdf->Cell(44,14,utf8_decode(""),1,0,'L');
$pdf->Cell(43,14,utf8_decode(""),1,1,'L');

$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(44,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,1,'L');
$pdf->Ln(-5);
$pdf->Cell(43,14,utf8_decode(""),1,0,'L');
$pdf->Cell(43,14,utf8_decode(""),1,0,'L');
$pdf->Cell(43,14,utf8_decode(""),1,0,'L');
$pdf->Cell(43,14,utf8_decode(""),1,0,'L');
$pdf->Cell(44,14,utf8_decode(""),1,0,'L');
$pdf->Cell(43,14,utf8_decode(""),1,1,'L');

$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(44,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,1,'L');
$pdf->Ln(-5);
$pdf->Cell(43,14,utf8_decode(""),1,0,'L');
$pdf->Cell(43,14,utf8_decode(""),1,0,'L');
$pdf->Cell(43,14,utf8_decode(""),1,0,'L');
$pdf->Cell(43,14,utf8_decode(""),1,0,'L');
$pdf->Cell(44,14,utf8_decode(""),1,0,'L');
$pdf->Cell(43,14,utf8_decode(""),1,1,'L');

$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(44,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,1,'L');
$pdf->Ln(-5);
$pdf->Cell(43,14,utf8_decode(""),1,0,'L');
$pdf->Cell(43,14,utf8_decode(""),1,0,'L');
$pdf->Cell(43,14,utf8_decode(""),1,0,'L');
$pdf->Cell(43,14,utf8_decode(""),1,0,'L');
$pdf->Cell(44,14,utf8_decode(""),1,0,'L');
$pdf->Cell(43,14,utf8_decode(""),1,1,'L');

$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(44,5,utf8_decode("¿Por qué?"),0,0,'L');
$pdf->Cell(43,5,utf8_decode("¿Por qué?"),0,1,'L');
$pdf->Ln(-5);
$pdf->Cell(43,14,utf8_decode(""),1,0,'L');
$pdf->Cell(43,14,utf8_decode(""),1,0,'L');
$pdf->Cell(43,14,utf8_decode(""),1,0,'L');
$pdf->Cell(43,14,utf8_decode(""),1,0,'L');
$pdf->Cell(44,14,utf8_decode(""),1,0,'L');
$pdf->Cell(43,14,utf8_decode(""),1,1,'L');

//Hoja dos
$pdf->Ln(1);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(259,4,utf8_decode("Plan de acción definitivo"),0,1,'L');
$pdf->Ln(-4);
$pdf->SetFont('Arial','',8);
$pdf->Cell(40,4,utf8_decode(""),0,0,'L');
$pdf->Cell(129,4,utf8_decode("(Señale las acciones que se tomarán para resolver la causa raíz del problema y que no se vuelva a presentar)"),0,1,'L');

$pdf->SetFont('Arial','B',8);
$pdf->Cell(129,5,utf8_decode("Acciones"),1,0,'C',true);
$pdf->Cell(50,5,utf8_decode("Responsable(s) de ejecución"),1,0,'C',true);
$pdf->Cell(50,5,utf8_decode("Responsable(s) de verificación"),1,0,'C',true);
$pdf->Cell(30,5,utf8_decode("Fecha compromiso"),1,1,'C',true);

$pdf->Cell(129,10,utf8_decode(""),1,0,'L');
$pdf->Cell(50,10,utf8_decode(""),1,0,'L');
$pdf->Cell(50,10,utf8_decode(""),1,0,'L');
$pdf->Cell(30,10,utf8_decode(""),1,1,'L');

$pdf->Cell(129,10,utf8_decode(""),1,0,'L');
$pdf->Cell(50,10,utf8_decode(""),1,0,'L');
$pdf->Cell(50,10,utf8_decode(""),1,0,'L');
$pdf->Cell(30,10,utf8_decode(""),1,1,'L');

$pdf->Cell(129,10,utf8_decode(""),1,0,'L');
$pdf->Cell(50,10,utf8_decode(""),1,0,'L');
$pdf->Cell(50,10,utf8_decode(""),1,0,'L');
$pdf->Cell(30,10,utf8_decode(""),1,1,'L');

$pdf->Cell(129,10,utf8_decode(""),1,0,'L');
$pdf->Cell(50,10,utf8_decode(""),1,0,'L');
$pdf->Cell(50,10,utf8_decode(""),1,0,'L');
$pdf->Cell(30,10,utf8_decode(""),1,1,'L');

$pdf->Ln(1);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(259,4,utf8_decode("Verificación de la implementación"),0,1,'L');

$pdf->Cell(30,5,utf8_decode("Fecha de acción"),1,0,'C',true);
$pdf->Cell(129,5,utf8_decode("Descripción del avance encontrado en el plan de acción"),1,0,'C',true);
$pdf->Cell(40,5,utf8_decode("Fecha de programación"),1,0,'C',true);
$pdf->Cell(60,5,utf8_decode("Firma del responsable de verificación"),1,1,'C',true);

$pdf->Cell(30,10,utf8_decode(""),1,0,'L');
$pdf->Cell(129,10,utf8_decode(""),1,0,'L');
$pdf->Cell(40,10,utf8_decode(""),1,0,'L');
$pdf->Cell(60,10,utf8_decode(""),1,1,'L');

$pdf->Cell(30,10,utf8_decode(""),1,0,'L');
$pdf->Cell(129,10,utf8_decode(""),1,0,'L');
$pdf->Cell(40,10,utf8_decode(""),1,0,'L');
$pdf->Cell(60,10,utf8_decode(""),1,1,'L');

$pdf->Cell(30,10,utf8_decode(""),1,0,'L');
$pdf->Cell(129,10,utf8_decode(""),1,0,'L');
$pdf->Cell(40,10,utf8_decode(""),1,0,'L');
$pdf->Cell(60,10,utf8_decode(""),1,1,'L');

$pdf->Cell(30,10,utf8_decode(""),1,0,'L');
$pdf->Cell(129,10,utf8_decode(""),1,0,'L');
$pdf->Cell(40,10,utf8_decode(""),1,0,'L');
$pdf->Cell(60,10,utf8_decode(""),1,1,'L');

$pdf->Ln(1);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(259,4,utf8_decode("Verificación de la eficacia de la acción"),0,1,'L');
$pdf->Ln(-4);
$pdf->SetFont('Arial','',8);
$pdf->Cell(60,4,utf8_decode(""),0,0,'L');
$pdf->Cell(129,4,utf8_decode("(Verificar que el problema no se ha vuelto a presentar en un periodo de tiempo razonable)"),0,1,'L');

$pdf->SetFont('Arial','B',8);
$pdf->Cell(40,5,utf8_decode("Fecha de verificación"),1,0,'C',true);
$pdf->Cell(159,5,utf8_decode("Evidencias que demuestran la eficacia de la acción"),1,0,'C',true);
$pdf->Cell(60,5,utf8_decode("Firma del responsable de verificación"),1,1,'C',true);

$pdf->Cell(40,10,utf8_decode(""),1,0,'L');
$pdf->Cell(159,10,utf8_decode(""),1,0,'L');
$pdf->Cell(60,10,utf8_decode(""),1,1,'L');

$pdf->Cell(40,10,utf8_decode(""),1,0,'L');
$pdf->Cell(159,10,utf8_decode(""),1,0,'L');
$pdf->Cell(60,10,utf8_decode(""),1,1,'L');

$pdf->Cell(40,10,utf8_decode(""),1,0,'L');
$pdf->Cell(159,10,utf8_decode(""),1,0,'L');
$pdf->Cell(60,10,utf8_decode(""),1,1,'L');

$pdf->Cell(40,10,utf8_decode(""),1,0,'L');
$pdf->Cell(159,10,utf8_decode(""),1,0,'L');
$pdf->Cell(60,10,utf8_decode(""),1,1,'L');

$pdf->Ln(1);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(259,4,utf8_decode("Cierre de la acción correctiva"),0,1,'L');
$pdf->Cell(48,15,utf8_decode(""),1,0,'L');
$pdf->Cell(42,15,utf8_decode(""),1,0,'L');
$pdf->Cell(60,15,utf8_decode(""),1,0,'L');
$pdf->Cell(60,15,utf8_decode(""),1,0,'L');
$pdf->Cell(49,15,utf8_decode(""),1,1,'L');

$pdf->Ln(-15);
$pdf->Cell(48,5,utf8_decode("Estado de la acción correctiva:"),0,0,'L');
$pdf->Cell(42,5,utf8_decode("Fecha de Cierre:"),0,0,'L');
$pdf->SetFont('Arial','',8);
$pdf->Cell(60,5,utf8_decode("En caso de no haber sido efectiva, mencionar"),0,0,'L');
$pdf->Cell(60,5,utf8_decode(""),0,0,'L');
$pdf->Cell(49,5,utf8_decode(""),0,1,'L');

$pdf->SetFont('Arial','',8);
$pdf->Cell(48,5,utf8_decode("Cerrada efectivamente"),0,0,'L');
$pdf->Cell(42,5,utf8_decode("_____________________"),0,0,'C');
$pdf->Cell(60,5,utf8_decode("el número de la nueva acción correctiva."),0,0,'L');
$pdf->Cell(60,5,utf8_decode("_________________________________"),0,0,'C');
$pdf->Cell(49,5,utf8_decode("_______________________"),0,1,'C');

$pdf->SetFont('Arial','',8);
$pdf->Cell(48,5,utf8_decode("Cerrada como no efectiva"),0,0,'L');
$pdf->Cell(42,5,utf8_decode("(Hasta demostrarse efectividad)"),0,0,'L');
$pdf->Cell(60,5,utf8_decode("_________________________"),0,0,'C');
$pdf->Cell(60,5,utf8_decode("Firma del responsable de la acción"),0,0,'C');
$pdf->Cell(49,5,utf8_decode("Firma del Director"),0,1,'C');

$pdf->Output('RC-01-PSG-8.5.pdf', 'I');
?>