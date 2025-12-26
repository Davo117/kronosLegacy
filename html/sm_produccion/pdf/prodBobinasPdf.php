<?php
  require('../../fpdf/fpdf.php');
  require('../../datos/mysql.php');
  $link = conectar();

  $pdtoImpresionSelect = $link->query("
    SELECT pdtodiseno.diseno,
           pdtoimpresion.impresion,
           pdtoimpresion.cdgimpresion
      FROM pdtodiseno,
           pdtoimpresion
     WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
           pdtoimpresion.cdgimpresion = '".$_GET['cdgproducto']."'");

  if ($pdtoImpresionSelect->num_rows > 0)
  { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

    $_SESSION['prodbobina_diseno'] = $regPdtoImpresion->diseno;
    $_SESSION['prodbobina_impresion'] = $regPdtoImpresion->impresion;   
    $pdtoImpresion_cdgimpresion = $regPdtoImpresion->cdgimpresion; }

  if ($_GET['sttbobina'] == '1') { $_SESSION['status_bobina'] = 'Refilado'; }  
  if ($_GET['sttbobina'] == '7') { $_SESSION['status_bobina'] = 'Liberado'; }
  if ($_GET['sttbobina'] == '8') { $_SESSION['status_bobina'] = 'Transferido'; }
  if ($_GET['sttbobina'] == '9') { $_SESSION['status_bobina'] = 'Fusionado'; }
  
  $prodBobinaSelect = $link->query("
    SELECT prodbobina.cdgbobina,
           prodbobina.longitud,
           prodbobina.amplitud,
           prodbobina.peso,
           prodbobina.fchmovimiento,
          (prodbobina.longitud/pdtojuego.altura) AS cantidad,
    CONCAT(prodlote.noop,'-',prodbobina.bobina) AS noop
      FROM prodlote,
           prodloteope,
           prodbobina,
           pdtoimpresion,
           pdtojuego,
           pdtodiseno
    WHERE (prodlote.cdglote = prodbobina.cdglote AND
           prodbobina.cdgproducto = '".$pdtoImpresion_cdgimpresion."' AND
           prodbobina.sttbobina = '".$_GET['sttbobina']."') AND
          (prodlote.cdglote = prodloteope.cdglote AND
           prodloteope.cdgoperacion = '10001' AND
           pdtojuego.cdgjuego = prodloteope.cdgjuego) AND  
          (pdtoimpresion.cdgimpresion = pdtojuego.cdgimpresion AND
           pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno AND
           prodlote.cdgproducto = pdtoimpresion.cdgimpresion)
  ORDER BY prodlote.noop,
           prodbobina.bobina");

  if ($prodBobinaSelect->num_rows > 0)
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
        $this->Cell(75,4,utf8_decode('Listado de bobinas por producto'),0,1,'L');

        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Diseño'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode($_SESSION['prodbobina_diseno']),0,1,'L');
      
        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Impresión'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode($_SESSION['prodbobina_impresion']),0,1,'L');
      
        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Status'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode($_SESSION['status_bobina']),0,1,'L');
       
        $this->Ln(4.15);

        $this->SetFillColor(180,180,180);
        $this->SetFont('arial','B',8);

        $this->SetFont('arial','B',8);
        $this->Cell(5,4,'',0,0,'L');
        $this->Cell(20,4,'NoOP',1,0,'L',true);
        $this->Cell(20,4,'Amplitud',1,0,'L',true);
        $this->Cell(20,4,'Metros',1,0,'L',true);
        $this->Cell(20,4,'Kilos',1,0,'L',true);
        $this->Cell(20,4,'Millares',1,0,'L',true);
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
    while ($regDataPdf = $prodBobinaSelect->fetch_object())
    { $item++;

      $pdf->SetFont('arial','',5);
      $pdf->Cell(5,4,$item,0,0,'R');

      $pdf->SetFont('arial','',8);
      $pdf->Cell(20,4,$regDataPdf->noop,1,0,'R');
      $pdf->Cell(20,4,$regDataPdf->amplitud,1,0,'R');
      $pdf->Cell(20,4,number_format($regDataPdf->longitud,2),1,0,'R');
      $pdf->Cell(20,4,number_format($regDataPdf->peso,3),1,0,'R');
      $pdf->Cell(20,4,number_format($regDataPdf->cantidad,3),1,0,'R');
      $pdf->Cell(30,4,$regDataPdf->fchmovimiento,1,0,'C');
      $pdf->Cell(25,4,$regDataPdf->cdgbobina,1,0,'C');
      $pdf->Cell(5,4,'',1,1,'R');

      $dataPdf_longitud += number_format($regDataPdf->longitud,2);
      $dataPdf_peso += number_format($regDataPdf->peso,3);
      $dataPdf_cantidad += number_format($regDataPdf->cantidad,3); }

    $pdf->SetFillColor(180,180,180);

    $pdf->Cell(5,4,'',0,0,'R');
    $pdf->SetFont('arial','B',8);
    $pdf->Cell(40,4,'Totales',1,0,'R',true);
    $pdf->Cell(20,4,number_format($dataPdf_longitud,2),1,0,'R');
    $pdf->Cell(20,4,number_format($dataPdf_peso,3),1,0,'R');
    $pdf->Cell(20,4,number_format($dataPdf_cantidad,3),1,1,'R');

    $pdf->Output('Listado de bobinas de '.$_SESSION['prodbobina_impresion'].' en '.$_SESSION['status_bobina'].' '.date('Y-m-d G:i:s').'.pdf', 'I');
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