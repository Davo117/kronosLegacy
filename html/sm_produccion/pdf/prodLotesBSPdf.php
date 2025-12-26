<?php
  require('../../fpdf/fpdf.php');
  require('../../datos/mysql.php');
  $link = conectar();

  $pdtoBandaPSelect = $link->query("
    SELECT pdtobanda.banda,
           pdtobandap.bandap,
           pdtobandap.cdgbandap
      FROM pdtobanda,
           pdtobandap
     WHERE pdtobanda.cdgbanda = pdtobandap.cdgbanda AND
           pdtobandap.cdgbandap = '".$_GET['cdgproducto']."'");

  if ($pdtoBandaPSelect->num_rows > 0)
  { $regPdtoBandaP = $pdtoBandaPSelect->fetch_object();

    $_SESSION['prodlote_banda'] = $regPdtoBandaP->banda;
    $_SESSION['prodlote_bandap'] = $regPdtoBandaP->bandap;
    $pdtoBandaP_cdgbandap = $regPdtoBandaP->cdgbandap; }

  if ($_GET['sttlote'] == 'A') { $_SESSION['status_lote'] = 'Programado'; }
  if ($_GET['sttlote'] == '1') { $_SESSION['status_lote'] = 'Embosado'; }

  $prodLoteSelect = $link->query("
    SELECT prodlote.cdglote,
           prodlote.longitud,
           prodlote.amplitud,
           prodlote.peso,           
         ((prodlote.amplitud/pdtobanda.anchura)*prodlote.longitud) AS cantidad,
           prodlote.fchmovimiento,
    CONCAT(prodlote.serie,'.',prodlote.noop) AS noop
      FROM prodlote,
           pdtobanda,
           pdtobandap
    WHERE (prodlote.cdgproducto = '".$pdtoBandaP_cdgbandap."' AND
           prodlote.sttlote = '".$_GET['sttlote']."') AND
          (pdtobandap.cdgbanda = pdtobanda.cdgbanda AND
           prodlote.cdgproducto = pdtobandap.cdgbandap)
  ORDER BY prodlote.serie,
           prodlote.noop");

  if ($prodLoteSelect->num_rows > 0)
  { 
    class PDF extends FPDF
    { // Cabeza de página
      function Header()
      { if ($_SESSION['usuario'] == '')
        { $_SESSION['usuario'] = 'Invitado'; }

        if (file_exists('../../img_sistema/logo.jpg')==true) 
        { $this->Image('../../img_sistema/logo.jpg',15,10,0,12); }

        $this->SetFillColor(255,153,0);

        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode('Listado de lotes de banda de seguridad'),0,1,'L');

        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Banda'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode($_SESSION['prodlote_banda']),0,1,'L');
      
        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Producto'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode($_SESSION['prodlote_bandap']),0,1,'L');
      
        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Status'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode($_SESSION['status_lote']),0,1,'L');
       
        $this->Ln(4.15);

        $this->SetFillColor(180,180,180);
        $this->SetFont('arial','B',8);

        $this->SetFont('arial','B',8);
        $this->Cell(5,4,'',0,0,'L');
        $this->Cell(30,4,'Serie.NoOP',1,0,'L',true);
        $this->Cell(20,4,'Amplitud',1,0,'L',true);
        $this->Cell(20,4,'Metros',1,0,'L',true);
        $this->Cell(20,4,'Kilos',1,0,'L',true);
        $this->Cell(20,4,'Rendimiento',1,0,'L',true);
        $this->Cell(30,4,'Movimiento',1,0,'L',true);        
        $this->Cell(25,4,utf8_decode('Código de barras'),1,1,'L',true); } 

      function Footer()
      { $this->SetFont('arial','',9);
        $this->SetY(-8);
        $this->Cell(0,3,'Usuario: '.utf8_decode($_SESSION['usuario']).' | '.utf8_decode('página '.$this->PageNo().' de {nb}'),0,1,'R');
        $this->SetFont('arial','I',8);
        $this->Cell(0,3,'Consultado el '.date("Y-m-d").' a las '.date("G:i:s"),0,1,'R'); }      
    }

    $pdf = new PDF('P','mm','letter');
    $pdf->AliasNbPages();
    $pdf->SetDisplayMode(real, continuous);
    $pdf->AddPage();

    $item = 0;
    while ($regDataPdf = $prodLoteSelect->fetch_object())
    { $item++;

      $pdf->SetFont('arial','',5);
      $pdf->Cell(5,4,$item,0,0,'R');

      $pdf->SetFont('arial','',8);
      $pdf->Cell(30,4,$regDataPdf->noop,1,0,'R');
      $pdf->Cell(20,4,$regDataPdf->amplitud,1,0,'R');
      $pdf->Cell(20,4,number_format($regDataPdf->longitud,2,'.',','),1,0,'R');
      $pdf->Cell(20,4,number_format($regDataPdf->peso,3,'.',','),1,0,'R');
      $pdf->Cell(20,4,number_format($regDataPdf->cantidad,2,'.',','),1,0,'R');
      $pdf->Cell(30,4,$regDataPdf->fchmovimiento,1,0,'C');
      $pdf->Cell(25,4,$regDataPdf->cdglote,1,0,'C');
      $pdf->Cell(5,4,'',1,1,'R');

      $dataPdf_longitud += $regDataPdf->longitud;
      $dataPdf_peso += $regDataPdf->peso;
      $dataPdf_cantidad += $regDataPdf->cantidad; }

    $pdf->SetFillColor(180,180,180);

    $pdf->Cell(5,4,'',0,0,'R');
    $pdf->SetFont('arial','B',8);
    $pdf->Cell(50,4,'Totales',1,0,'R',true);
    $pdf->Cell(20,4,number_format($dataPdf_longitud,2,'.',','),1,0,'R');
    $pdf->Cell(20,4,number_format($dataPdf_peso,3,'.',','),1,0,'R');
    $pdf->Cell(20,4,number_format($dataPdf_cantidad,2,'.',','),1,1,'R');

    $pdf->Output('Listado de lotes de '.$_SESSION['prodlote_banda'].' en '.$_SESSION['status_lote'].' '.date('Y-m-d G:i:s').'.pdf', 'I');
  } else
  { echo '<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Tablero de control</title>
    <link rel="stylesheet" type="text/css" href="../../css/2014.css" media="screen" />
  </head>
  <body>
    <div id="contenedor">';

    m3nu_produccion2();

    echo '
      <h1>Listado de bobinas por producto, sin resutados que mostrar.</h1>
    </div>
  </body>
</html>'; }
  
?>