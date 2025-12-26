<?php
  include '../../datos/mysql.php';
	include '../../fpdf/fpdf.php';
	
  $progLotePdf_cdgbloque = $_GET['cdgbloque'];
  
  $link_mysqli = conectar();
  $progBloqueSelect = $link_mysqli->query("
    SELECT * FROM progbloque
    WHERE cdgbloque = '".$progLotePdf_cdgbloque."'");

  if ($progBloqueSelect->num_rows > 0)
  { $regProgBloque = $progBloqueSelect->fetch_object();

    $_SESSION['progLotePdf_idbloque'] = $regProgBloque->idbloque; }

  $progBloqueSelect->close;

  $link_mysqli = conectar();
  $progLoteSelect = $link_mysqli->query("
    SELECT tarima 
    FROM proglote
    WHERE cdgbloque = '".$progLotePdf_cdgbloque."'
    GROUP BY tarima
    ORDER BY tarima");

  if ($progLoteSelect->num_rows > 0)
  { $id_tarima = 1;
    while ($regProgLote = $progLoteSelect->fetch_object())
    { $progLotePdf_tarima[$id_tarima] = $regProgLote->tarima; 
      
      $link_mysqli = conectar();
      $progLoteSelectByTarima = $link_mysqli->query("
        SELECT * FROM proglote
        WHERE cdgbloque = '".$progLotePdf_cdgbloque."'
        AND tarima = '".$progLotePdf_tarima[$id_tarima]."'
        ORDER BY cdglote");

      if ($progLoteSelectByTarima->num_rows > 0)
      { $id_lote = 1;
        while ($regProgLoteByTarima = $progLoteSelectByTarima->fetch_object())
        { $progLotePdf_idlote[$id_tarima][$id_lote] = $regProgLoteByTarima->idlote;
          $progLotePdf_lote[$id_tarima][$id_lote] = $regProgLoteByTarima->lote;
          $progLotePdf_longitud[$id_tarima][$id_lote] = $regProgLoteByTarima->longitud;
          $progLotePdf_amplitud[$id_tarima][$id_lote] = $regProgLoteByTarima->amplitud;
          $progLotePdf_espesor[$id_tarima][$id_lote] = $regProgLoteByTarima->espesor;
          $progLotePdf_encogimiento[$id_tarima][$id_lote] = $regProgLoteByTarima->encogimiento;
          $progLotePdf_peso[$id_tarima][$id_lote] = $regProgLoteByTarima->peso;

          $id_lote++; }

        $numlotes[$id_tarima]  = $progLoteSelectByTarima->num_rows;
        $progLoteSelectByTarima->close;
      }

      $id_tarima++; }

    $numtarimas = $progLoteSelect->num_rows;
    $progLoteSelect->close;
  }
  
	class PDF extends FPDF
	{ function Header()
		{	if ($_SESSION['usuario'] == '')
      { $_SESSION['usuario'] = 'Invitado'; }

      if (file_exists('../../img_sistema/logo.jpg')==true) 
      { $this->Image('../../img_sistema/logo.jpg',10,7,0,10); }    //*/  
      
      $this->SetY(5);
      $this->SetFont('arial','B',8);
			$this->Cell(0,4,'Usuario: '.$_SESSION['usuario'],0,0,'R');
      $this->Ln(); // Salto de Linea
      $this->Cell(0,4,'Listado de lotes por tarima del bloque '.$_SESSION['progLotePdf_idbloque'],0,0,'R');
      $this->Ln(); // Salto de Linea
      $this->Ln(4);

			$this->SetFillColor(180,180,180);
			$this->SetFont('arial','B',8);      
      
      $this->SetFont('arial','B',8);
			$this->Cell(10,4,'Lote',0,0,'L',true);
      $this->Cell(45,4,'No. Lote',0,0,'L',true);
      $this->Cell(30,4,'Longitud en metros',0,0,'L',true);
      $this->Cell(25,4,'Amplitud en mm',0,0,'L',true);
      $this->Cell(30,4,'Espesor en micras',0,0,'L',true);
      $this->Cell(30,4,'Encogimiento en %',0,0,'L',true);
      $this->Cell(20,4,'Peso en kilos',0,0,'L',true);
      $this->Cell(5,4,' ',1,0,'L',true);
      
      $this->Ln(); // Salto de Linea 
		}
	}							
		
	$pdf=new PDF('P','mm','letter');				
	$pdf->AliasNbPages();
	$pdf->SetDisplayMode(real, continuous); 
  $pdf->AddPage();

  $pdf->SetFillColor(210,210,210);

  for ($id_tarima = 1; $id_tarima <= $numtarimas; $id_tarima++)
  { $progLotePdf_longitudes = 0;
    $progLotePdf_pesos = 0;

    $pdf->SetFont('arial','',9);
    for ($id_lote = 1; $id_lote <= $numlotes[$id_tarima]; $id_lote++)
    { $pdf->Cell(10,5,$progLotePdf_idlote[$id_tarima][$id_lote].' ',0,0,'R');      
      $pdf->Cell(45,5,$progLotePdf_lote[$id_tarima][$id_lote].' ',0,0,'R');
      $pdf->Cell(30,5,number_format($progLotePdf_longitud[$id_tarima][$id_lote]).' ',0,0,'R');
      $pdf->Cell(25,5,$progLotePdf_amplitud[$id_tarima][$id_lote].' ',0,0,'R');
      $pdf->Cell(30,5,$progLotePdf_espesor[$id_tarima][$id_lote].' ',0,0,'R');
      $pdf->Cell(30,5,$progLotePdf_encogimiento[$id_tarima][$id_lote].' ',0,0,'R');
      $pdf->Cell(20,5,number_format($progLotePdf_peso[$id_tarima][$id_lote],3).' ',0,0,'R');
      $pdf->Cell(5,5,' ',1,0,'R');
      $pdf->Ln();

      $progLotePdf_longitudes += $progLotePdf_longitud[$id_tarima][$id_lote];
      $progLotePdf_pesos += $progLotePdf_peso[$id_tarima][$id_lote];
      $progLotePdf_pesototal += $progLotePdf_peso[$id_tarima][$id_lote];
    }

    $pdf->SetFont('arial','B',9);
    $pdf->Cell(55,4,'Tarima '.$progLotePdf_tarima[$id_tarima],0,0,'L',true);
    $pdf->Cell(30,4,number_format($progLotePdf_longitudes).' ',0,0,'R',true);
    $pdf->Cell(85,4,'',0,0,'R',true);
    $pdf->Cell(20,4,number_format($progLotePdf_pesos,3).' ',0,0,'R',true);
    $pdf->Cell(5,4,' ',1,0,'R',true);
    $pdf->Ln();
  }

  $pdf->SetFillColor(180,180,180);
  $pdf->SetFont('arial','B',9);
  $pdf->Cell(140,4,'',0,0,'R');
  $pdf->Cell(30,4,$numtarimas.' Tarimas ',0,0,'R',true);  
  $pdf->Cell(20,4,number_format($progLotePdf_pesototal,3).' ',0,0,'R',true);
  $pdf->Cell(5,4,' ',1,0,'R',true);
  $pdf->Ln();  

	$pdf->Output('Listado de lotes por tarima del bloque '.$_SESSION['progLotePdf_idbloque'].'.pdf', 'I'); 
?>
