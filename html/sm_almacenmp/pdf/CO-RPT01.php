<?php
  include '../../datos/mysql.php';
  include '../../fpdf/fpdf.php';
  
  $link = conectar();

  $progBloque_cdgbloque = $_GET['cdgbloque'];
  
  $querySelect = $link->query("
    SELECT progbloque.idbloque,
           pdtosustrato.idsustrato,
           pdtosustrato.sustrato
      FROM progbloque,
           pdtosustrato
     WHERE progbloque.cdgbloque = '".$progBloque_cdgbloque."' AND
           pdtosustrato.cdgsustrato = progbloque.cdgsustrato");

  if ($querySelect->num_rows > 0)
  { $regQuery = $querySelect->fetch_object();

    $_SESSION['progbloque_idbloque'] = $regQuery->idbloque;
    $progBloque_idsustrato = $regQuery->idsustrato;
    $_SESSION['progbloque_sustrato'] = $regQuery->sustrato; }
  
  $querySelect = $link->query("
    SELECT tarima 
      FROM proglote
     WHERE cdgbloque = '".$progBloque_cdgbloque."'
  GROUP BY tarima
  ORDER BY tarima");

  if ($querySelect->num_rows > 0)
  { $item = 0;

    while ($regQuery = $querySelect->fetch_object())
    { $item++;

      $progLote_tarima[$item] = $regQuery->tarima;

      $subQuerySelect = $link->query("
        SELECT * FROM proglote
         WHERE cdgbloque = '".$progBloque_cdgbloque."' AND
               tarima = '".$progLote_tarima[$item]."'
      ORDER BY cdglote");

      if ($subQuerySelect->num_rows > 0)
      { $subItem = 0;

        while ($regSubQuery = $subQuerySelect->fetch_object())
        { $subItem++;

          $progLote_idlote[$item][$subItem] = $regSubQuery->idlote;
          $progLote_lote[$item][$subItem] = $regSubQuery->lote;
          $progLote_longitud[$item][$subItem] = $regSubQuery->longitud;
          $progLote_peso[$item][$subItem] = $regSubQuery->peso;
          $progLote_sttlote[$item][$subItem] = $regSubQuery->sttlote;

          $progLote_sumSttlote[$regSubQuery->sttlote] += $regSubQuery->peso;
        }

        $nLotes[$item]  = $subItem;
      }
    }

    $nTarimas = $item;
  }
  
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
      $this->Cell(75,4,utf8_decode('Listado de lotes por bloque'),0,1,'L');
  
      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Bloque'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode($_SESSION['progbloque_idbloque']),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Sustrato'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode($_SESSION['progbloque_sustrato']),0,1,'L');
      
      $this->Ln(4.15);

      $this->SetFillColor(180,180,180);
      $this->SetFont('arial','B',8);

      $this->Cell(5,4,'',0,0,'L');
      $this->Cell(60,4,'Lote',1,0,'L',true);
      $this->Cell(20,4,'Metros',1,0,'L',true);
      $this->Cell(20,4,'Kilos',1,1,'L',true); }

    // Pie de página
    function Footer()
    { $this->SetFont('arial','',9);
      $this->SetY(-8);
      $this->Cell(0,3,'Usuario: '.utf8_decode($_SESSION['usuario']).' | '.utf8_decode('página '.$this->PageNo().' de {nb}'),0,1,'R');
      $this->SetFont('arial','I',8);
      $this->Cell(0,3,'Consultado el '.date("Y-m-d").' a las '.date("G:i:s"),0,1,'R'); }
  }

  $pdf=new PDF('P','mm','letter');
  $pdf->AliasNbPages();
  $pdf->SetDisplayMode(real, continuous);
  $pdf->AddPage();

  $pdf->SetFillColor(210,210,210);

  for ($item = 1; $item <= $nTarimas; $item++)
  { $progLote_longitudes = 0;
    $progLote_pesos = 0;
    $progLote_rendimientos = 0;

    $pdf->SetFont('arial','',8);
    for ($subItem = 1; $subItem <= $nLotes[$item]; $subItem++)
    { $pdf->Cell(5,4,'',0,0,'L');
      $pdf->Cell(10,4,$progLote_idlote[$item][$subItem].' ',1,0,'R');
      $pdf->Cell(50,4,$progLote_lote[$item][$subItem].' ',1,0,'L');
      $pdf->Cell(20,4,number_format($progLote_longitud[$item][$subItem],3).' ',1,0,'R');
      $pdf->Cell(20,4,number_format($progLote_peso[$item][$subItem],3).' ',1,0,'R');
      
      if ($progLote_sttlote[$item][$subItem] == 1)
      { $pdf->Cell(5,4,' ',1,1,'R'); }
      else
      { $pdf->Cell(5,4,' ',0,1,'R'); }

      $progLote_longitudes += $progLote_longitud[$item][$subItem];
      $progLote_pesos += $progLote_peso[$item][$subItem];
      $progLote_pesototal += $progLote_peso[$item][$subItem];
    }

    $pdf->SetFont('arial','B',8);
    $pdf->Cell(5,4,'',0,0,'L');
    $pdf->Cell(60,4,'Tarima '.$progLote_tarima[$item],1,0,'L',true);
    $pdf->Cell(20,4,number_format($progLote_longitudes,3).' ',1,0,'R',true);
    $pdf->Cell(20,4,number_format($progLote_pesos,3).' ',1,0,'R',true);
    $pdf->Cell(5,4,' ',0,1,'R');
  }

  $pdf->Ln(4.15);
  $pdf->SetFillColor(180,180,180);

  $pdf->Cell(85,4,'',0,0,'R');
  $pdf->SetFont('arial','B',8);
  $pdf->Cell(40,4,'Totales',1,1,'L',true);

  $pdf->Cell(85,4,'',0,0,'R');
  $pdf->SetFont('arial','B',8);
  $pdf->Cell(20,4,number_format($progLote_sumSttlote[0],3),1,0,'R');   
  $pdf->SetFont('arial','I',8);
  $pdf->Cell(20,4,'Devuelto',1,1,'L');

  $pdf->Cell(85,4,'',0,0,'R');
  $pdf->SetFont('arial','B',8);
  $pdf->Cell(20,4,number_format($progLote_sumSttlote[1],3),1,0,'R');   
  $pdf->SetFont('arial','I',8);
  $pdf->Cell(20,4,'Activo',1,1,'L');

  $pdf->Cell(85,4,'',0,0,'R');
  $pdf->SetFont('arial','B',8);
  $pdf->Cell(20,4,number_format($progLote_sumSttlote[7],3),1,0,'R');   
  $pdf->SetFont('arial','I',8);
  $pdf->Cell(20,4,'Liberado',1,1,'L');  

  $pdf->Cell(85,4,'',0,0,'R');
  $pdf->SetFont('arial','B',8);
  $pdf->Cell(20,4,number_format($progLote_sumSttlote[8],3),1,0,'R');   
  $pdf->SetFont('arial','I',8);
  $pdf->Cell(20,4,'Programados',1,1,'L');
  
  $pdf->Cell(85,4,'',0,0,'R');
  $pdf->SetFont('arial','B',8);
  $pdf->Cell(20,4,number_format($progLote_sumSttlote[9],3),1,0,'R');
  $pdf->SetFont('arial','I',8);
  $pdf->Cell(20,4,'Procesados',1,1,'L');
  
  $pdf->Cell(85,4,'',0,0,'R');
  $pdf->SetFont('arial','B',8);
  $pdf->Cell(20,4,number_format($progLote_pesototal,3),1,0,'R',true);
  $pdf->SetFont('arial','I',8);
  $pdf->Cell(20,4,'Netos',1,1,'L',true); 

  $pdf->SetFillColor(210,210,210);

  $pdf->Cell(85,4,'',0,0,'R');
  $pdf->SetFont('arial','B',8);
  $pdf->Cell(20,4,$nTarimas,1,0,'R',true);
  $pdf->SetFont('arial','I',8);
  $pdf->Cell(20,4,'Tarima(s)',1,1,'L',true);

  $pdf->Output('Listado de lotes por bloque '.$progBloque_idbloque.'.pdf', 'I');
?>