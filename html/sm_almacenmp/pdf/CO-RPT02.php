<?php
  require('../../fpdf/fpdf.php');
  require('../../datos/mysql.php');

  $link = conectar();    

  if ($_GET['cdgproducto']) { $progReporteador_cdgproducto = $_GET['cdgproducto']; }
  if ($_GET['cdglote']) { $progReporteador_cdglote = $_GET['cdglote']; }
  if ($_GET['fchinicial']) { $progReporteador_fchinicial = $_GET['fchinicial']; }
  if ($_GET['fchfinal']) { $progReporteador_fchfinal = $_GET['fchfinal']; }

  $progReporteador_fchinicial = ValidarFecha($progReporteador_fchinicial);
  $progReporteador_fchfinal = ValidarFecha($progReporteador_fchfinal); 

  // Búsqueda de productos activos
  $pdtoImpresionSelect = $link->query("
    SELECT pdtoimpresion.idimpresion,
           pdtoimpresion.impresion,
           pdtoimpresion.cdgimpresion
      FROM pdtodiseno,
           pdtoimpresion
     WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
          (pdtoimpresion.sttimpresion = '1' AND
           pdtodiseno.sttdiseno = '1')
  ORDER BY pdtodiseno.proyecto,
           pdtoimpresion.periodo");

  if ($pdtoImpresionSelect->num_rows > 0)
  { $item = 0;
    
    while ($regPdtoImpresion = $pdtoImpresionSelect->fetch_object())
    { $item++;

      $pdtoImpresiones_idimpresion[$item] = $regPdtoImpresion->idimpresion;
      $pdtoImpresiones_impresion[$item] = $regPdtoImpresion->impresion;
      $pdtoImpresiones_cdgimpresion[$item] = $regPdtoImpresion->cdgimpresion; 

      $progReporteador_productos[$regPdtoImpresion->cdgimpresion] = $regPdtoImpresion->impresion; }

    $nImpresiones = $item; 
  } // Fin de la búsqueda de productos activos

  $progReporteador_producto = $progReporteador_productos[$progReporteador_cdgproducto];

  // Busqueda de ordenes de compra activas
  if ($progReporteador_cdgproducto != '')
  { $vntsOCLoteSelect = $link->query("
      SELECT vntsoc.oc,
             vntsoclote.idlote,
             vntssucursal.sucursal,
             vntsoclote.cdglote,
             vntsoclote.cdgproducto
        FROM vntsoc,
             vntsoclote,
             vntssucursal
       WHERE vntsoc.cdgoc = vntsoclote.cdgoc AND
             vntsoc.sttoc = '1' AND
             vntssucursal.cdgsucursal = vntsoc.cdgsucursal AND
             vntsoclote.cdgproducto = '".$progReporteador_cdgproducto."'
    ORDER BY vntsoc.cdgoc DESC,
             vntsoclote.cdglote"); 
  } else
  { $vntsOCLoteSelect = $link->query("
      SELECT vntsoc.oc,
             vntsoclote.idlote,
             vntssucursal.sucursal,
             vntsoclote.cdglote,
             vntsoclote.cdgproducto
        FROM vntsoc,
             vntsoclote,
             vntssucursal
       WHERE vntsoc.cdgoc = vntsoclote.cdgoc AND
             vntsoc.sttoc = '1' AND
             vntssucursal.cdgsucursal = vntsoc.cdgsucursal
    ORDER BY vntsoc.cdgoc DESC,
             vntsoclote.cdglote"); }

  if ($vntsOCLoteSelect->num_rows > 0)
  { $item = 0;

    while ($regVntsOCLote = $vntsOCLoteSelect->fetch_object())
    { $item++;

      $vntsOClotes_oc[$item] = $regVntsOCLote->oc;
      $vntsOClotes_idlote[$item] = $regVntsOCLote->idlote;
      $vntsOClotes_sucursal[$item] = $regVntsOCLote->sucursal;
      $vntsOClotes_cdglote[$item] = $regVntsOCLote->cdglote; 

      $progReporteador_lotes[$regVntsOCLote->cdglote] = $vntsOClotes_sucursal[$item].' ['.$vntsOClotes_oc[$item].'-'.$vntsOClotes_idlote[$item].']'; }

    $nOCLotes = $item; 
  } // Fin de la busqueda de ordenes de compra activas 

  $progReporteador_lote = $progReporteador_lotes[$progReporteador_cdglote];

  // Filtro de elementos
  $progElementoSelect = $link->query("
    SELECT progelemento.idelemento,
           progelemento.elemento,
           progelemento.cdgelemento,
           progunimed.idunimed
      FROM progelemento,
           progunimed
     WHERE progelemento.cdgunimed = progunimed.cdgunimed");

  if ($progElementoSelect->num_rows > 0)
  { while ($regProgElemento = $progElementoSelect->fetch_object())
    { $progReporteador_idelemento[$regProgElemento->cdgelemento] = $regProgElemento->idelemento;
      $progReporteador_elemento[$regProgElemento->cdgelemento] = $regProgElemento->elemento;
      $progReporteador_idunimed[$regProgElemento->cdgelemento] = $regProgElemento->idunimed; }
  } // Fin del filtro de elementos

  // Filtro de Pantones
  $pdtoPantoneSelect = $link->query("
    SELECT * FROM pdtopantone
  ORDER BY pantone");

  if ($pdtoPantoneSelect->num_rows > 0)
  { while ($regPdtoPantone = $pdtoPantoneSelect->fetch_object())
    { $progReporteador_idelemento[$regPdtoPantone->cdgpantone] = $regPdtoPantone->idpantone;      
      $progReporteador_elemento[$regPdtoPantone->cdgpantone] = $regPdtoPantone->pantone;
      $progReporteador_idunimed[$regPdtoPantone->cdgpantone] = 'kgs'; }
  } // Fin del filtro de Pantones

  // Filtro de diseños
  $pdtoBandaSelect = $link->query("
    SELECT * FROM pdtobanda
  ORDER BY sttbanda DESC,
           idbanda");

  if ($pdtoBandaSelect->num_rows > 0)
  { while ($regPdtoBanda = $pdtoBandaSelect->fetch_object())
    { $progReporteador_idelemento[$regPdtoBanda->cdgbanda] = $regPdtoBanda->idbanda; 
      $progReporteador_elemento[$regPdtoBanda->cdgbanda] = $regPdtoBanda->banda; 
      $progReporteador_idunimed[$regPdtoBanda->cdgbanda] = 'mts'; }
  } // Final del filtro de bandas
  
  // Filtro de tipos de materia prima de acuerdo al proceso 001 Sello de seguridad  
  $sistTipoMPSelect = $link->query("
    SELECT sisttipomp.idtipomp,
           sisttipomp.tipomp,
           sisttipomp.cdgtipomp
      FROM sistproceso,
           progtipo,
           sisttipomp
    WHERE (sisttipomp.cdgtipomp = progtipo.cdgtipomp AND
           progtipo.cdgproceso = sistproceso.cdgproceso AND
           sistproceso.cdgproceso = '001')
  ORDER BY sisttipomp.idtipomp");

  if ($sistTipoMPSelect->num_rows > 0)
  { while ($regSistTipoMP = $sistTipoMPSelect->fetch_object())
    { $progReporteador_idelemento[$regSistTipoMP->cdgtipomp] = $regSistTipoMP->idtipomp; 
      $progReporteador_elemento[$regSistTipoMP->cdgtipomp] = $regSistTipoMP->tipomp; 
      $progReporteador_idunimed[$regSistTipoMP->cdgtipomp] = 'kgs'; }
  }
  // Final del filtro de sustratos  

  // Generación del filtro
  if ($progReporteador_cdgproducto != '')
  { if ($progReporteador_cdglote != '')
    { //Ver un producto de un pedido

      $progReporteador_fchinicial = "No aplica";
      $progReporteador_fchfinal = "No aplica";

      $progReporteadorSelect = $link->query("
        SELECT progrequeboom.cdgelemento,
           SUM(requerido) AS requerido
          FROM vntsoc,
               vntsoclote,
               progreque,
               progrequeboom
        WHERE (vntsoc.cdgoc = vntsoclote.cdgoc AND
               vntsoclote.cdglote = progreque.cdglote) AND                  
              (progreque.cdgreque = progrequeboom.cdgreque AND
               progreque.cdglote = progrequeboom.cdglote) AND
              (vntsoclote.cdglote = '".$progReporteador_cdglote."' AND
               vntsoclote.cdgproducto = '".$progReporteador_cdgproducto."')
      GROUP BY progrequeboom.cdgelemento
      ORDER BY CHAR_LENGTH(progrequeboom.cdgelemento)");         
    } else
    { //Ver un producto en todos los pedidos
      
      $progReporteador_lote = "Todos"; 

      $progReporteadorSelect = $link->query("
        SELECT progrequeboom.cdgelemento,
           SUM(requerido) AS requerido
          FROM vntsoc,
               vntsoclote,
               progreque,
               progrequeboom
        WHERE (vntsoc.cdgoc = vntsoclote.cdgoc AND
               vntsoclote.cdglote = progreque.cdglote) AND
              (vntsoclote.fchembarque BETWEEN '".$progReporteador_fchinicial."' AND '".$progReporteador_fchfinal."') AND
              (progreque.cdgreque = progrequeboom.cdgreque AND
               progreque.cdglote = progrequeboom.cdglote) AND
              (vntsoclote.cdgproducto = '".$progReporteador_cdgproducto."')
      GROUP BY progrequeboom.cdgelemento
      ORDER BY CHAR_LENGTH(progrequeboom.cdgelemento)"); }
  } else
  { if ($progReporteador_cdglote != '')
    { //Ver pedido completo

      $progReporteador_producto = "Todos";
      $progReporteador_fchinicial = "No aplica";
      $progReporteador_fchfinal = "No aplica";

      $progReporteadorSelect = $link->query("
        SELECT progrequeboom.cdgelemento,
           SUM(requerido) AS requerido
          FROM vntsoc,
               vntsoclote,
               progreque,
               progrequeboom
        WHERE (vntsoc.cdgoc = vntsoclote.cdgoc AND
               vntsoclote.cdglote = progreque.cdglote) AND
              (progreque.cdgreque = progrequeboom.cdgreque AND
               progreque.cdglote = progrequeboom.cdglote) AND
              (vntsoclote.cdglote = '".$progReporteador_cdglote."')
      GROUP BY progrequeboom.cdgelemento
      ORDER BY CHAR_LENGTH(progrequeboom.cdgelemento)");        
    } else
    { //Ver productos y pedidos

      $progReporteador_producto = "Todos";
      $progReporteador_lote = "Todos";

      $progReporteadorSelect = $link->query("
        SELECT progrequeboom.cdgelemento,
           SUM(requerido) AS requerido
          FROM vntsoc,
               vntsoclote,
               progreque,
               progrequeboom
        WHERE (vntsoc.cdgoc = vntsoclote.cdgoc AND
               vntsoclote.cdglote = progreque.cdglote) AND
              (vntsoclote.fchembarque BETWEEN '".$progReporteador_fchinicial."' AND '".$progReporteador_fchfinal."') AND
              (progreque.cdgreque = progrequeboom.cdgreque AND
               progreque.cdglote = progrequeboom.cdglote)
      GROUP BY progrequeboom.cdgelemento
      ORDER BY CHAR_LENGTH(progrequeboom.cdgelemento)"); }
  }

  if ($progReporteadorSelect->num_rows > 0)
  { $item = 0;

    while ($regProgElemento = $progReporteadorSelect->fetch_object())
    { $item++;

      $progReporteador_cdgelemento[$item] = $regProgElemento->cdgelemento;
      $progReporteador_requerido[$item] = $regProgElemento->requerido; }

    $nElementos = $progReporteadorSelect->num_rows; }

  class PDF extends FPDF
  { // Cabecera de página
    function Header()
    { global $progReporteador_producto;
      global $progReporteador_lote;
      global $progReporteador_fchinicial;
      global $progReporteador_fchfinal;

      $this->SetFont('Arial','B',8);
      $this->SetFillColor(255,153,0);

      if (file_exists('../../img_sistema/logonew.jpg')==true)
      { $this->Image('../../img_sistema/logonew.jpg',10,0,0,32); }

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Explosión de materiales'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Producto'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode($progReporteador_producto),0,1,'L'); 

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Pedido/Confirmación'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode($progReporteador_lote),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Fecha inicial*'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode($progReporteador_fchinicial),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Fecha final*'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode($progReporteador_fchfinal),0,1,'L'); //*/

      $this->Ln(4); }

    // Pie de página
    function Footer()
    { $this->SetY(-20);

      $this->SetY(-10);
      $this->SetFont('arial','B',8);
      $this->Cell(0,6,'Consultado el '.date("Y-m-d").' a las '.date("G:i:s").'',0,1,'R'); 
      $this->SetFont('arial','',8);
      $this->SetY(-10);
      $this->Cell(0,6,utf8_decode('*La información filtrada toma como referencia la fecha para embarque'),0,1,'L');
      $this->SetY(-7.5);
      $this->Cell(0,6,utf8_decode('**Unidad de medida'),0,1,'L');

      $this->SetY(-7.5);
      $this->Cell(0,6,utf8_decode('Grupo Labro | Página '.$this->PageNo().' de {nb}'),0,1,'R'); }
  }

  $pdf = new PDF('P','mm','letter');
  $pdf->SetDisplayMode(real, continuous);
  $pdf->AddFont('3of9','','free3of9.php');
  $pdf->AliasNbPages();

  $pdf->AddPage();

  $pdf->SetFillColor(255,153,0);
/*
  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(30,4,utf8_decode('Producto'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,0,'R',true);
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(30,4,$progReporteador_producto,0,1,'L');

  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(30,4,utf8_decode('Lote/Confirmación'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,0,'R',true);
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(30,4,$progReporteador_lote,0,1,'L');

  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(30,4,utf8_decode('Fecha inicial*'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,0,'R',true);
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(30,4,$progReporteador_fchinicial,0,1,'L');

  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(30,4,utf8_decode('Fecha final*'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,0,'R',true);
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(30,4,$progReporteador_fchfinal,0,1,'L'); 

  $pdf->Ln(4); //*/

  if ($nElementos > 0)
  { $pdf->SetFont('Arial','B',9);

    $pdf->Cell(50,4,utf8_decode('Código'),0,0,'C',true);
    $pdf->Cell(105,4,utf8_decode('Descripción'),0,0,'C',true);
    $pdf->Cell(25,4,utf8_decode('Requerido'),0,0,'C',true);
    $pdf->Cell(16,4,utf8_decode('U.M.**'),0,1,'C',true);
    $pdf->Cell(0,0.2,'',1,1);

    for ($item=1; $item<=$nElementos; $item++)
    { $pdf->SetFont('Arial','',9);

      if ($item%2 > 0)
      { $pdf->SetFillColor(255,255,255); 
      } else 
      { $pdf->SetFillColor(200,200,200); }

      $pdf->Cell(50,4,utf8_decode($progReporteador_idelemento[$progReporteador_cdgelemento[$item]]),0,0,'L',true);
      $pdf->Cell(105,4,utf8_decode($progReporteador_elemento[$progReporteador_cdgelemento[$item]]),0,0,'L',true);
      $pdf->Cell(25,4,number_format($progReporteador_requerido[$item],4,'.',','),0,0,'R',true);
      $pdf->Cell(16,4,utf8_decode($progReporteador_idunimed[$progReporteador_cdgelemento[$item]]),0,1,'L',true); }
  }

  $pdf->Cell(0,0.2,'',1,1);

  ////////////////////////////////////////////////
  $pdf->Output('PRO-FR05.pdf', 'I');
?>