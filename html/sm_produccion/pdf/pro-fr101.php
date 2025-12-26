<?php
  include '../../datos/mysql.php';  
  require('../../fpdf/fpdf.php');

  if ($_GET['cdgproducto'])
  { $link = conectar();
    
    // Búsqueda de la banda de seguridad a producir
    $pdtoBandaPSelect = $link->query("
      SELECT pdtobanda.banda,
             pdtobanda.anchura,
             pdtobandap.bandap,
             pdtobandap.alaminado,
             pdtosustrato.anchura AS anchuras,
             pdtosustrato.sustrato AS sustrato
        FROM pdtobanda,
             pdtobandap,
             pdtosustrato
      WHERE (pdtobanda.cdgbanda = pdtobandap.cdgbanda AND
             pdtobandap.cdgbandap = '".$_GET['cdgproducto']."') AND
            (pdtosustrato.cdgsustrato = pdtobandap.cdgsustrato)");

    if ($pdtoBandaPSelect->num_rows > 0)
    { $regPdtoImpresion = $pdtoBandaPSelect->fetch_object();

      $formato_banda = $regPdtoImpresion->banda;
      $formato_anchura = $regPdtoImpresion->anchura;
      $formato_bandap = $regPdtoImpresion->bandap;
      $formato_alaminado = $regPdtoImpresion->alaminado;
      $formato_sustrato = $regPdtoImpresion->sustrato;
      $formato_anchuras = $regPdtoImpresion->anchuras;
      $formato_cdgproceso = $regPdtoImpresion->cdgproceso; }
    // Fin de la búsqueda de la banda se seguridad a producir

    // Búsqueda de la máquina asignada para la programación
    $prodMaquinaSelect = $link->query("
      SELECT CONCAT(idmaquina, ', ', maquina) AS maquina
        FROM prodmaquina 
       WHERE cdgmaquina = '".$_GET['cdgmaquina']."'");

    if ($prodMaquinaSelect->num_rows > 0)
    { $regProdMaquina = $prodMaquinaSelect->fetch_object();

      $formato_maquina = $regProdMaquina->idmaquina.' '.$regProdMaquina->maquina; }
    // Fin de la búsqueda de la máquina asignada para la programación

    // Búsqueda de lotes en la programación
    $prodLoteSelect = $link->query("
      SELECT prodlote.serie,
             prodlote.noop,
             proglote.tarima,
             proglote.idlote,
             proglote.lote,
             proglote.longitud,
             pdtosustrato.anchura,
             proglote.peso,
             prodlote.cdglote,
             prodlote.sttlote
        FROM progbloque,
             proglote,
             prodlote,
             prodloteope,
             pdtosustrato
      WHERE (progbloque.cdgbloque = proglote.cdgbloque AND
             proglote.cdglote = prodlote.cdglote AND
             prodlote.cdglote = prodloteope.cdglote) AND
            (progbloque.cdgsustrato = pdtosustrato.cdgsustrato) AND
            (prodlote.fchprograma = '".$_GET['fchprograma']."' AND
             prodlote.cdgproducto = '".$_GET['cdgproducto']."') AND
            (prodloteope.cdgoperacion = '10001' AND 
             prodloteope.cdgmaquina = '".$_GET['cdgmaquina']."')");

    if ($prodLoteSelect->num_rows > o)
    { $item = 0;

      while ($regProdLote = $prodLoteSelect->fetch_object())
      { $item++;

        $prodLote_serie[$item] = $regProdLote->serie;
        $prodLote_noop[$item] = $regProdLote->noop;
        $prodLote_tarima[$item] = $regProdLote->tarima;
        $prodLote_idlote[$item] = $regProdLote->idlote;
        $prodLote_lote[$item] = $regProdLote->lote;
        $prodLote_longitud[$item] = $regProdLote->longitud;
        $prodLote_anchura[$item] = $regProdLote->anchura;
        $prodLote_peso[$item] = $regProdLote->peso;
        $prodLote_cdglote[$item] = $regProdLote->cdglote;
        $prodLote_sttlote[$item] = $regProdLote->sttlote;

        $prodLote_programado[$item] = (floor($regProdLote->anchura/$formato_anchura)*$regProdLote->longitud); }

      $nLotes = $item; } //*/

    if ($nLotes > 0)
    { class PDF extends FPDF
      { // Cabecera de página
        function Header()
        { global $formato_banda;
          global $formato_bandap;
          global $formato_sustrato;
          global $formato_anchuras;
          global $formato_alaminado;
          global $formato_maquina;          

          $this->SetFont('Arial','B',8);
          $this->SetFillColor(255,153,0);

          if (file_exists('../../img_sistema/logonew.jpg')==true)
          { $this->Image('../../img_sistema/logonew.jpg',10,0,0,32); }

          $this->SetFont('Arial','B',8);
          $this->Cell(185,4,utf8_decode('Documento'),0,0,'R');
          $this->Cell(0.5,4,'',0,0,'R',true);
          $this->SetFont('Arial','I',8);
          $this->Cell(75,4,utf8_decode('Embosado de Banda de Seguridad'),0,1,'L');

          $this->SetFont('Arial','B',8);
          $this->Cell(185,4,utf8_decode('Código'),0,0,'R');
          $this->Cell(0.5,4,'',0,0,'R',true);
          $this->SetFont('Arial','I',8);
          $this->Cell(75,4,utf8_decode('PRO-FRXX'),0,1,'L');

          $this->SetFont('Arial','B',8);
          $this->Cell(185,4,utf8_decode('Versión'),0,0,'R');
          $this->Cell(0.5,4,'',0,0,'R',true);
          $this->SetFont('Arial','I',8);
          $this->Cell(75,4,utf8_decode('0.0'),0,1,'L');

          $this->SetFont('Arial','B',8);
          $this->Cell(185,4,utf8_decode('Revisión'),0,0,'R');
          $this->Cell(0.5,4,'',0,0,'R',true);
          $this->SetFont('Arial','I',8);
          $this->Cell(75,4,utf8_decode('Abril 30, 2015'),0,1,'L');

          $this->SetFont('Arial','B',8);
          $this->Cell(185,4,utf8_decode('Responsable'),0,0,'R');
          $this->Cell(0.5,4,'',0,0,'R',true);
          $this->SetFont('Arial','I',8);
          $this->Cell(75,4,utf8_decode('Operador'),0,1,'L'); 

          $this->Ln(4);

          $this->SetFont('3of9','',28);
          $this->Cell(255,5,'*'.$_GET['cdgfchprograma'].$_GET['cdgproducto'].$_GET['cdgmaquina'].'*',0,1,'R');
          $this->SetFont('Arial','B',10);
          $this->Cell(255,7,$_GET['cdgfchprograma'].$_GET['cdgproducto'].$_GET['cdgmaquina'],0,1,'R'); 

          $this->Ln(-16);

          $this->SetFont('Arial','I',8);
          $this->Cell(40,4,utf8_decode('Fecha del programa'),0,0,'R');
          $this->Cell(0.5,4,'',0,0,'R',true);
          $this->SetFont('Arial','B',8);
          $this->Cell(40,4,$_GET['fchprograma'],0,1,'L');

          $this->SetFont('Arial','I',8);
          $this->Cell(40,4,utf8_decode('Banda'),0,0,'R');
          $this->Cell(0.5,4,'',0,0,'R',true);
          $this->SetFont('Arial','B',8);
          $this->Cell(60,4,$formato_banda,0,1,'L');

          $this->SetFont('Arial','I',8);
          $this->Cell(40,4,utf8_decode('Producto'),0,0,'R');
          $this->Cell(0.5,4,'',0,0,'R',true);
          $this->SetFont('Arial','B',8);
          $this->Cell(60,4,$formato_bandap,0,1,'L');

          $this->SetFont('Arial','I',8);
          $this->Cell(40,4,utf8_decode('Máquina'),0,0,'R');
          $this->Cell(0.5,4,'',0,0,'R',true);  
          $this->SetFont('Arial','B',8);
          $this->Cell(60,4,$formato_maquina,0,1,'L');

          $this->SetFont('Arial','I',8);
          $this->Cell(40,4,utf8_decode('Sustrato'),0,0,'R');
          $this->Cell(0.5,4,'',0,0,'R',true);
          $this->SetFont('Arial','B',8);
          $this->Cell(60,4,$formato_sustrato,0,1,'L');      

          $this->Ln(4); }

        // Pie de página
        function Footer()
        { $this->SetY(-10);
          $this->SetFont('arial','B',8);
          $this->Cell(0,6,'Consultado el '.date("Y-m-d").' a las '.date("G:i:s").'',0,1,'R'); 
          $this->SetFont('arial','',8);
          $this->SetY(-7.5);
          $this->Cell(0,6,utf8_decode('Grupo Labro | Página '.$this->PageNo().' de {nb}'),0,1,'R'); }
      }

      $pdf = new PDF('L','mm','letter');
      $pdf->SetDisplayMode(real, continuous);
      $pdf->AddFont('3of9','','free3of9.php');
      $pdf->AliasNbPages();

      $pdf->AddPage();
      
      $pdf->SetFont('Arial','B',10);

      $pdf->Cell(8,8,'#',0,0,'C');  
      $pdf->Cell(42,8,'Lote',1,0,'C');
      $pdf->Cell(30,8,'NoOP',1,0,'C');
      
      $pdf->SetFont('Arial','B',8);

      $pdf->Cell(40,4,'Metros',1,0,'C');
      $pdf->Cell(60,4,'Kilogramos',1,0,'C');
      $pdf->Cell(10,8,utf8_decode('Flags'),1,0,'C');
      $pdf->Cell(35,8,utf8_decode('Operador'),1,0,'C');
      $pdf->Cell(30,8,utf8_decode('Fecha y hora'),1,1,'C');

      $pdf->Ln(-4);

      $pdf->SetFont('Arial','',7);
      $pdf->Cell(80,4,'',0,0,'C');  
      $pdf->Cell(20,4,'Recibidos',1,0,'C');
      $pdf->Cell(20,4,'Entregados',1,0,'C');
      $pdf->Cell(20,4,'Recibidos',1,0,'C');
      $pdf->Cell(20,4,'Entregados',1,0,'C');
      $pdf->Cell(20,4,'Scrap',1,1,'C');

      $pdf->SetFont('Arial','B',8);  

      for ($idLote = 1; $idLote <= $nLotes; $idLote++)
      { $pdf->SetFont('Arial','B',12);
        $pdf->Cell(8,8,$idLote,0,0,'C');

        $pdf->SetFont('Arial','',8);
        $pdf->Cell(42,4,$prodLote_lote[$idLote],1,0,'L');
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,8,$prodLote_serie[$idLote].'.'.$prodLote_noop[$idLote],1,0,'C');          
        $pdf->Cell(20,8,number_format($prodLote_longitud[$idLote],2),1,0,'R');
        $pdf->Cell(20,8,'',1,0,'L');
        $pdf->Cell(20,8,number_format($prodLote_peso[$idLote],3),1,0,'R');          
        $pdf->Cell(20,8,'',1,0,'C');
        $pdf->Cell(20,8,'',1,0,'C');
        $pdf->Cell(10,8,'',1,0,'C');
        $pdf->Cell(35,8,'',1,0,'L');          
        $pdf->Cell(30,8,'',1,1,'L');
        
        $pdf->Ln(-4);

        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(8,4,'',0,0,'L');
        $pdf->Cell(42,4,$prodLote_tarima[$idLote].'|'.$prodLote_idlote[$idLote],1,1,'L'); }    

      $pdf->Output('PRO-FRXX.pdf', 'I'); 
    }            
  } else
  { echo 'Hello!!!'; }
?>
