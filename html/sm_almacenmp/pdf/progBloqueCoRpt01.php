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

    $progBloque_idbloque = $regQuery->idbloque;
    $progBloque_idsustrato = $regQuery->idsustrato;
    $progBloque_sustrato = $regQuery->sustrato; }
  
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
          $progLote_amplitud[$item][$subItem] = $regSubQuery->amplitud;
          $progLote_calibre[$item][$subItem] = $regSubQuery->calibre;
          $progLote_encogimiento[$item][$subItem] = $regSubQuery->encogimiento;
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
    {  if ($_SESSION['usuario'] == '')
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
      $this->Cell(125,4,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('CO-RPT01'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Versión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('1.0'),0,1,'L');
      
      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Fecha de revisión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Marzo 05, 2015'),0,1,'L');
      
      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Responsable'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Jefe de almacén'),0,1,'L');
      
      $this->Ln(4.15);

      $this->SetFillColor(180,180,180);
      $this->SetFont('arial','B',8);
      
      $this->SetFont('arial','B',8);
      $this->Cell(10,4,'Lote',0,0,'L',true);
      $this->Cell(55,4,'Referencia',0,0,'L',true);
      $this->Cell(25,4,'Longitud (m)',0,0,'L',true);
      $this->Cell(25,4,'Amplitud (mm)',0,0,'L',true);
      $this->Cell(25,4,'Calibre (µm)',0,0,'L',true);
      $this->Cell(25,4,'Encogimiento (%)',0,0,'L',true);
      $this->Cell(25,4,'Peso (Kg)',0,1,'L',true);
    }

    // Pie de página
    function Footer()
    { $this->SetFont('arial','',9);
      $this->SetY(-8);
      $this->Cell(0,3,'Usuario: '.utf8_decode($_SESSION['usuario']).' | '.utf8_decode('página '.$this->PageNo().' de {nb}'),0,1,'R');
      $this->SetFont('arial','I',8);
      $this->Cell(0,3,'Consultado el '.date("Y-m-d").' a las '.date("G:i:s"),0,1,'R');
    }
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

    $pdf->SetFont('arial','',9);
    for ($subItem = 1; $subItem <= $nLotes[$item]; $subItem++)
    { $pdf->Cell(10,5,$progLote_idlote[$item][$subItem].' ',0,0,'R');
      $pdf->Cell(55,5,$progLote_lote[$item][$subItem].' ',0,0,'R');
      $pdf->Cell(25,5,number_format($progLote_longitud[$item][$subItem]).' ',0,0,'R');
      $pdf->Cell(25,5,$progLote_amplitud[$item][$subItem].' ',0,0,'R');
      $pdf->Cell(25,5,$progLote_calibre[$item][$subItem].' ',0,0,'R');
      $pdf->Cell(25,5,$progLote_encogimiento[$item][$subItem].' ',0,0,'R');
      $pdf->Cell(25,5,number_format($progLote_peso[$item][$subItem],3).' ',0,0,'R');
      
      if ($progLote_sttlote[$item][$subItem] == 1)
      { $pdf->Cell(5,5,' ',1,1,'R'); }
      else
      { $pdf->Cell(5,5,' ',0,1,'R'); }

      $progLote_longitudes += $progLote_longitud[$item][$subItem];
      $progLote_pesos += $progLote_peso[$item][$subItem];
      $progLote_pesototal += $progLote_peso[$item][$subItem];
    }

    $pdf->SetFont('arial','B',9);
    $pdf->Cell(65,4,'Tarima '.$progLote_tarima[$item],0,0,'L',true);
    $pdf->Cell(25,4,number_format($progLote_longitudes).' ',0,0,'R',true);
    $pdf->Cell(75,4,'',0,0,'R',true);
    $pdf->Cell(25,4,number_format($progLote_pesos,3).' ',0,0,'R',true);
    $pdf->Cell(5,4,' ',0,1,'R');
  }

  $pdf->SetFillColor(180,180,180);
  $pdf->SetFont('arial','B',9);
  $pdf->Cell(190,4,'Sustrato '.$progBloque_idsustrato.' | '.$progBloque_sustrato,0,1,'L');
  $pdf->Cell(190,4,'Bloque '.$progBloque_idbloque.' | '.$nTarimas.' Tarimas ',0,1,'L',true);  

  $pdf->Cell(165,4,'Devuelto',0,0,'R');  //0 Devuelto 
  $pdf->Cell(25,4,number_format($progLote_sumSttlote[0],3),0,1,'R');  //0 Devuelto
  $pdf->Cell(165,4,'Activo',0,0,'R');  //0 Devuelto 
  $pdf->Cell(25,4,number_format($progLote_sumSttlote[1],3),0,1,'R');  //1 Activo
  $pdf->Cell(165,4,'Liberado',0,0,'R');  //0 Devuelto 
  $pdf->Cell(25,4,number_format($progLote_sumSttlote[7],3),0,1,'R');  //7 Liberado
  $pdf->Cell(165,4,'Programado',0,0,'R');  //0 Devuelto 
  $pdf->Cell(25,4,number_format($progLote_sumSttlote[8],3),0,1,'R');  //8 Programado
  $pdf->Cell(165,4,'Procesado',0,0,'R');  //0 Devuelto 
  $pdf->Cell(25,4,number_format($progLote_sumSttlote[9],3),0,1,'R');  //9 Procesado
  $pdf->Cell(165,4,'Total',0,0,'R',true);  //0 Devuelto 
  $pdf->Cell(25,4,number_format($progLote_pesototal,3),0,1,'R',true);

  $pdf->Output('Listado de lotes por bloque '.$progBloque_idbloque.'.pdf', 'I');
?>