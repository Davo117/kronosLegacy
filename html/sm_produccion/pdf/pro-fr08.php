<?php
  include '../../datos/mysql.php';
  require('../../fpdf/fpdf.php');

  $link = conectar();

  $pdtoImpresionSelect = $link->query("
    SELECT pdtodiseno.diseno,
           pdtoimpresion.impresion,
           pdtoimpresion.ancho
      FROM pdtodiseno,
           pdtoimpresion
     WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
           pdtoimpresion.cdgimpresion = '".$_GET['cdgimpresion']."'");

  if ($pdtoImpresionSelect->num_rows > 0)
  { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

    $_SESSION['oplist_diseno'] = $regPdtoImpresion->diseno;
    $_SESSION['oplist_impresion'] = $regPdtoImpresion->impresion;
    $oplist_ancho = $regPdtoImpresion->ancho; } 

  // Buscar los lotes
  $prodLoteSelect = $link->query("
    SELECT prodlote.noop,
           pdtojuego.alpaso
      FROM proglote,
           prodlote,
           prodloteope,           
           pdtojuego,
           pdtoimpresion
    WHERE (prodloteope.cdgmaquina = '".$_GET['cdgmaquina']."' AND
           prodloteope.cdgjuego = '".$_GET['cdgjuego']."' AND
           prodlote.fchprograma = '".$_GET['fchprograma']."' AND
           prodlote.cdgproducto = '".$_GET['cdgimpresion']."') AND
          (proglote.cdglote = prodlote.cdglote AND
           prodlote.cdglote = prodloteope.cdglote AND
           prodloteope.cdgoperacion = '10001' AND
           pdtojuego.cdgjuego = prodloteope.cdgjuego) AND
          (pdtoimpresion.cdgimpresion = pdtojuego.cdgimpresion AND
           prodlote.cdgproducto = pdtoimpresion.cdgimpresion)
  ORDER BY prodlote.noop");

  if ($prodLoteSelect->num_rows > 0)
  { $item = 0;
    while ($regProdLote = $prodLoteSelect->fetch_object())
    { $item++;

      $oplist_noop[$item] = $regProdLote->noop;
      $oplist_alpaso[$item] = $regProdLote->alpaso; }

    $nLotes = $item; }

  // Reformar las clases
  class PDF extends FPDF
  { // Cabecera de página
    function Header()
    { global $oplist_ancho;

      $this->SetFont('Arial','B',8);
      $this->SetFillColor(255,153,0);

      if (file_exists('../../img_sistema/logonew.jpg')==true)
      { $this->Image('../../img_sistema/logonew.jpg',10,0,0,32); }

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Medición y control de refilado'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('PRO-FR08'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Versión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('2.0'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Revisión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('23 de Abril de 2015'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Responsable'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Refilador'),0,1,'L'); 

      $this->Ln(4);

      $this->SetFont('Arial','I',8);
      $this->Cell(40,4,utf8_decode('Fecha de la orden'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','B',8);
      $this->Cell(40,4,$_GET['fchprograma'],0,1,'L');

      $this->SetFont('Arial','I',8);
      $this->Cell(40,4,utf8_decode('Diseño'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','B',8);
      $this->Cell(60,4,$_SESSION['oplist_diseno'],0,1,'L');

      $this->SetFont('Arial','I',8);
      $this->Cell(40,4,utf8_decode('Impresión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','B',8);
      $this->Cell(60,4,$_SESSION['oplist_impresion'],0,1,'L');

      $this->SetFont('Arial','I',8);
      $this->Cell(40,4,utf8_decode('Ancho plano'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','B',8);
      $this->Cell(60,4,$oplist_ancho.' mm',0,1,'L');

      $this->Ln(4); }

    // Pie de página
    function Footer()
    { $this->SetY(-10);
      $this->SetFont('arial','',8);
      $this->Cell(0,3,utf8_decode('Página '.$this->PageNo().' de {nb}'),0,1,'R');
      $this->SetFont('arial','B',8);
      $this->Cell(0,3,'Consultado el '.date("Y-m-d").' a las '.date("G:i:s"),0,1,'R'); }
  }

  // Generar el archivo
  $pdf = new PDF('P','mm','letter');
  $pdf->SetDisplayMode(real, continuous);
  $pdf->AddFont('3of9','','free3of9.php');
  $pdf->AliasNbPages();

  $pdf->AddPage();

  for ($item = 1; $item <= $nLotes; $item++)
  { $pdf->Cell(5,4,$item,0,0,'C');
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(10,4,'NoOP',0,0,'R');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(20,4,$oplist_noop[$item],0,0,'L');
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(12,4,'Operador',0,0,'R');
    $pdf->Cell(40,4,'_______________________',0,0,'L');
    $pdf->Cell(12,4,utf8_decode('Máquina'),0,0,'R');
    $pdf->Cell(40,4,'_______________________',0,0,'L');
    $pdf->Cell(16,4,'Fecha y hora',0,0,'R');
    $pdf->Cell(40,4,'________________________',0,1,'L');
    
    $pdf->Ln(1);

    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(15,4,'Bobinas',0,0,'R');
    $pdf->Cell(20,4,'Ancho plano',1,0,'C');
    $pdf->Cell(20,4,'Longitud (m)',1,0,'C');
    $pdf->Cell(20,4,'Peso (kg)',1,0,'C');    
    $pdf->Cell(20,4,'No. Banderas',1,0,'C');
    
    $pdf->Cell(18,4,'Velocidad',0,0,'R');
    $pdf->Cell(20,4,'__________',0,0,'L');
    $pdf->Cell(18,4,utf8_decode('Tensión inicial'),0,0,'R');
    $pdf->Cell(20,4,'__________',0,0,'L');
    $pdf->Cell(6,4,utf8_decode('final'),0,0,'R');
    $pdf->Cell(20,4,'__________',0,1,'L');

    for ($subItem = 1; $subItem <= $oplist_alpaso[$item]; $subItem++)
    { $pdf->Cell(15,4,'',0,0,'C');
      $pdf->Cell(20,4,'',1,0,'C');
      $pdf->Cell(20,4,'',1,0,'C');
      $pdf->Cell(20,4,'',1,0,'C');
      $pdf->Cell(20,4,'',1,1,'C'); }

    $pdf->Ln(2); }

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(24,4,'Observaciones',0,1,'L');
  $pdf->Cell(0,12,'',1,1,'C');

  $pdf->SetY(-20);

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(0,4,'______________________________________________________',0,1,'C');
  $pdf->Cell(0,4,utf8_decode('Validó Supervisor'),0,1,'C');
  ////////////////////////////////////////////////
  $pdf->Output('PRO-FR08.pdf', 'I');
?>