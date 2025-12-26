<?php
  include '../../datos/mysql.php';  
  require('../../fpdf/fpdf.php');
  $link = conectar();

  $cofr01_cdgreque = $_GET['cdgreque'];

  $progRequeSelect = $link->query("
    SELECT progreque.reque,
           pdtoimpresion.impresion,
           progreque.cantidad,
           vntsempaque.empaque,
           progreque.fchreque,         
           vntsoc.oc,
           vntssucursal.sucursal,
           vntsoclote.cdglote
      FROM progreque,
           pdtoimpresion,
           vntsempaque,
           vntsoc,
           vntsoclote,
           vntssucursal
    WHERE (progreque.cdgreque = '".$cofr01_cdgreque."' AND
           pdtoimpresion.cdgimpresion = progreque.cdgproducto AND
           vntsempaque.cdgempaque = progreque.cdgempaque) AND
          (vntssucursal.cdgsucursal = vntsoc.cdgsucursal AND
           vntsoc.cdgoc = vntsoclote.cdgoc AND
           vntsoclote.cdglote = progreque.cdglote)");

  if ($progRequeSelect->num_rows > 0)
  { $regProgReque = $progRequeSelect->fetch_object();

    $cofr01_reque = $regProgReque->reque;
    $cofr01_producto = $regProgReque->impresion;
    $cofr01_cantidad = $regProgReque->cantidad;
    $cofr01_empaque = $regProgReque->empaque;
    $cofr01_fchreque = $regProgReque->fchreque;
    $cofr01_oc = $regProgReque->oc;
    $cofr01_sucursal = $regProgReque->sucursal;
    $cofr01_cdglote = $regProgReque->cdglote;

    $progRequeBoomSelect = $link->query("
      SELECT cdgelemento,
         SUM(requerido) AS requerido
        FROM progrequeboom
       WHERE cdgreque = '".$cofr01_cdgreque."'
    GROUP BY cdgelemento
    ORDER BY LENGTH(cdgelemento)");

    if ($progRequeBoomSelect->num_rows > 0)
    { $item = 0;

      // Filtrado de elementos y requerimiento de materia prima
      while ($regRequeBoom = $progRequeBoomSelect->fetch_object())
      { $item++;

        $cofr01_cdgelemento[$item] = $regRequeBoom->cdgelemento;
        $cofr01_requerido[$item] = $regRequeBoom->requerido; }

      $nElementos = $item;
      // Fin del filtrado
      
      // Descripciones para tintas
      $progRequeBoomSelect = $link->query("
        SELECT progrequeboom.cdgelemento,
               pdtopantone.pantone
          FROM pdtopantone,
               progrequeboom
         WHERE pdtopantone.idpantone = progrequeboom.cdgelemento AND 
               progrequeboom.cdgreque = '".$cofr01_cdgreque."'");

      if ($progRequeBoomSelect->num_rows > 0)
      { while ($regRequeBoom = $progRequeBoomSelect->fetch_object())
        { $cofr01_elemento[$regRequeBoom->cdgelemento] = $regRequeBoom->pantone;
          $cofr01_unimed[$regRequeBoom->cdgelemento] = 'kilos'; }
      } // Fin de las descripciones para tintas

      // Descripciones para sustratos
      $progRequeBoomSelect = $link->query("
        SELECT progrequeboom.cdgelemento,
               progtipomp.tipomp
          FROM progtipomp,
               progrequeboom
         WHERE progtipomp.cdgtipomp = progrequeboom.cdgelemento AND 
               progrequeboom.cdgreque ='".$cofr01_cdgreque."'");

      if ($progRequeBoomSelect->num_rows > 0)
      { while ($regRequeBoom = $progRequeBoomSelect->fetch_object())
        { $cofr01_elemento[$regRequeBoom->cdgelemento] = $regRequeBoom->tipomp;
          $cofr01_unimed[$regRequeBoom->cdgelemento] = 'kilos'; }
      } // Fin de las descripciones de tipos de materia prima (Sustratos)

      // Descripciones para elementos
      $progRequeBoomSelect = $link->query("
        SELECT progelemento.elemento,
               progunimed.unimed,
               progelemento.cdgelemento
          FROM progelemento,
               progunimed,
               progrequeboom
         WHERE progunimed.cdgunimed = progelemento.cdgunimed AND
               progelemento.cdgelemento = progrequeboom.cdgelemento AND
               progrequeboom.cdgreque = '".$cofr01_cdgreque."'");

      if ($progRequeBoomSelect->num_rows > 0)
      { while ($regRequeBoom = $progRequeBoomSelect->fetch_object())
        { $cofr01_elemento[$regRequeBoom->cdgelemento] = $regRequeBoom->elemento;
          $cofr01_unimed[$regRequeBoom->cdgelemento] = $regRequeBoom->unimed; }
      } // Fin de las descripciones de tipos de materia prima (Elementos) //*/


      // Descripciones para bandas de seguridad
      $progRequeBoomSelect = $link->query("
        SELECT progrequeboom.cdgelemento,
               pdtobanda.banda
          FROM pdtobanda,
               progrequeboom
         WHERE pdtobanda.cdgbanda = progrequeboom.cdgelemento AND 
               progrequeboom.cdgreque ='".$cofr01_cdgreque."'");

      if ($progRequeBoomSelect->num_rows > 0)
      { while ($regRequeBoom = $progRequeBoomSelect->fetch_object())
        { $cofr01_elemento[$regRequeBoom->cdgelemento] = $regRequeBoom->banda;
          $cofr01_unimed[$regRequeBoom->cdgelemento] = 'metros'; }
      } // Fin de las descripciones de tipos de materia prima (Bandas de seguridad)
    }
  }

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
      $this->Cell(75,4,utf8_decode('Requisición de compra'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('CO-FR01'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Versión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('2.0'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Revisión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('06 de Abril de 2015'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Responsable'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Jefe de compras'),0,1,'L'); 

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
  $pdf->SetFillColor(200,200,200);

  $pdf->Line(170,38,205,38);
  
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(160,4,utf8_decode('Folio'),0,0,'R');
  $pdf->SetTextColor(220,0,0);
  $pdf->Cell(35,4,'P '.str_pad($cofr01_reque,4,'0',STR_PAD_LEFT),0,1,'R');  

  $pdf->Ln(1);

  $pdf->SetTextColor(0,0,0);
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(80,4,utf8_decode('Departamento'),1,0,'C',true);
  $pdf->Cell(80,4,utf8_decode('Solicita'),1,0,'C',true);
  $pdf->Cell(35,4,utf8_decode('Fecha pedido'),1,1,'C',true);
  $pdf->Cell(80,4,utf8_decode(''),1,0,'C');
  $pdf->Cell(80,4,utf8_decode(''),1,0,'C');
  $pdf->Cell(35,4,utf8_decode($cofr01_fchreque),1,1,'C');

  $pdf->Ln(1);

  $pdf->Cell(18,8,utf8_decode(''),1,0,'L',true);
  $pdf->Cell(112,8,utf8_decode(''),1,0,'C');
  $pdf->Cell(30,8,utf8_decode('Fecha de entrega'),1,0,'C',true);
  $pdf->Cell(35,8,utf8_decode(''),1,1,'C');
  $pdf->Ln(-8);
  $pdf->Cell(18,4,utf8_decode(' Proveedor'),0,1,'L');
  $pdf->Cell(18,4,utf8_decode(' sugerido'),0,1,'L');

  $pdf->Ln(1);

  $pdf->Cell(25,4,utf8_decode('Cantidad'),1,0,'C',true);
  $pdf->Cell(20,4,utf8_decode('U.M.'),1,0,'C',true);
  $pdf->Cell(115,4,utf8_decode('Descripción'),1,0,'C',true);
  $pdf->Cell(35,4,utf8_decode('Exclusivo compras'),1,1,'C',true);
  
  for ($item = 1; $item <= $nElementos; $item++)
  { $pdf->Cell(25,4,number_format($cofr01_requerido[$item],6),1,0,'R');
    $pdf->Cell(20,4,utf8_decode($cofr01_unimed[$cofr01_cdgelemento[$item]]),1,0,'L');
    $pdf->Cell(115,4,utf8_decode($cofr01_elemento[$cofr01_cdgelemento[$item]]),1,0,'L');
    $pdf->Cell(35,4,utf8_decode(''),1,1,'C'); }

  $pdf->Ln(1);

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(195,4,utf8_decode('Observaciones: Destino '.$cofr01_sucursal.' | O.C. '.$cofr01_oc.' | Código '.$cofr01_cdglote),1,1,'L');
  $pdf->Cell(195,4,utf8_decode('Producto '.$cofr01_producto.' | Cantidad '.$cofr01_cantidad.' millares | Empaque '.$cofr01_empaque),1,1,'L');
  $pdf->Cell(195,4,'',1,1,'L');

  $pdf->Ln(1);

  $pdf->Cell(50,4,'Solicita',0,0,'C');
  $pdf->Cell(55,4,'Vo.Bo. Jefe inmediato',0,0,'C');
  $pdf->Cell(40,4,'Recibe',0,0,'C');
  $pdf->Cell(50,4,utf8_decode('Autorización'),0,1,'C');
  $pdf->Ln(-4);
  $pdf->Cell(50,12,'',1,0,'C');
  $pdf->Cell(55,12,'',1,0,'C');
  $pdf->Cell(40,12,'',1,0,'C');
  $pdf->Cell(50,12,'',1,1,'C');
  
  ////////////////////////////////////////////////
  $pdf->Output('CO-FR01.pdf', 'I');
?>