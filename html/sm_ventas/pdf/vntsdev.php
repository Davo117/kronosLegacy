<?php 
  include '../../datos/mysql.php';	
  require('../../fpdf/fpdf.php');

  $link = conectar();
  $queryselectdevolucion = $link->query("
    SELECT pdtoimpresion.impresion,
           pdtoempaque.empaque,
           vntsdev.cdgembarque,
           vntsdev.tpoempaque,
           vntsdev.fchrecepcion,
           vntsdev.fchdocumento,
           vntsdev.observacion,
           vntscontacto.contacto,
           vntscontacto.puesto,
           vntsdev.cdgdev,
           vntsdev.sttdev,
           vntssucursal.sucursal,
           vntssucursal.cdgsucursal,
           vntsoc.oc
      FROM pdtoimpresion,
           pdtoempaque,
           vntsdev,
           vntsembarque,
           vntssucursal,
           vntscontacto,
           vntsoc,
           vntsoclote
     WHERE (pdtoimpresion.cdgimpresion = vntsdev.cdgproducto AND
            pdtoempaque.cdgempaque = vntsdev.tpoempaque) AND
           (vntsdev.cdgembarque = vntsembarque.cdgembarque AND
            vntsembarque.cdgsucursal = vntssucursal.cdgsucursal) AND
           (vntsdev.cdgcontacto = vntscontacto.cdgcontacto) AND
           (vntsembarque.cdglote = vntsoclote.cdglote AND
            vntsoclote.cdgoc = vntsoc.cdgoc) AND
            vntsdev.cdgdev = '".$_GET[cdgdev]."'
   ORDER BY vntsdev.cdgdev");

    if ($queryselectdevolucion->num_rows > 0)
    { $regdevolucion = $queryselectdevolucion->fetch_object(); 

      $vntsdev_fchdocumento = $regdevolucion->fchdocumento; 
      $vntsdev_producto = $regdevolucion->impresion;
      $vntsdev_cdgembarque = $regdevolucion->cdgembarque;
      $vntsdev_empaque = $regdevolucion->empaque;
      $vntsdev_sucursal = $regdevolucion->sucursal;
      $vntsdev_contacto = $regdevolucion->contacto.' '.$regdevolucion->puesto;
      $vntsdev_observacion = $regdevolucion->observacion; 

      if ($regdevolucion->tpoempaque == 'C') 
      { $imgempaque = 'src="../img_sistema/box.png"'; 
          //Detalle de la devolución 
        $queryselectempaque = $link->query("
          SELECT alptempaque.tpoempaque,
                 alptempaque.noempaque,
             SUM(alptempaquep.dev) AS dev
            FROM alptempaque,
                 alptempaquep
           WHERE alptempaque.cdgdev = '".$regdevolucion->cdgdev."' AND
                (alptempaque.cdgempaque = alptempaquep.cdgempaque AND
                 alptempaque.cdgdev = alptempaquep.cdgdev)
        GROUP BY alptempaque.cdgempaque");

        $queryselectcantidaddev = $link->query("
          SELECT SUM(alptempaquep.dev) AS dev
            FROM alptempaquep
           WHERE alptempaquep.cdgdev = '".$regdevolucion->cdgdev."'"); }
      
      if ($regdevolucion->tpoempaque == 'Q') 
      { $imgempaque = 'src="../img_sistema/database_active.png"'; 
          // Detalle de la devolución  
        $queryselectempaque = $link->query("
          SELECT alptempaque.tpoempaque,
                 alptempaque.noempaque,
             SUM(alptempaquer.dev) AS dev
            FROM alptempaque,
                 alptempaquer
           WHERE alptempaque.cdgdev = '".$regdevolucion->cdgdev."' AND
                (alptempaque.cdgempaque = alptempaquer.cdgempaque AND
                 alptempaque.cdgdev = alptempaquer.cdgdev)
        GROUP BY alptempaque.cdgempaque");

        $queryselectcantidaddev = $link->query("
          SELECT SUM(alptempaquer.dev) AS dev
            FROM alptempaquer
           WHERE alptempaquer.cdgdev = '".$regdevolucion->cdgdev."'"); }

      if ($queryselectcantidaddev->num_rows > 0)
      { $regcantidaddev = $queryselectcantidaddev->fetch_object(); 

        $vntsdev_devolucion = $regcantidaddev->dev; }

    }

  class PDF extends FPDF
  { function Header()
    { global $vntsdev_fchdocumento;
      global $vntsdev_producto;
      global $vntsdev_empaque;

      if ($_SESSION['usuario'] == '') { $_SESSION['usuario'] = 'Invitado'; }

      if (file_exists('../../img_sistema/logonew.jpg')==true)
      { $this->Image('../../img_sistema/logonew.jpg',10,0,0,32); }

      $this->SetFillColor(255,153,0);

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Queja o reclamo'),0,1,'L');
  
      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Folio'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,$_GET[cdgdev],0,1,'L');     

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Fecha'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,$vntsdev_fchdocumento,0,1,'L');  

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Producto'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,$vntsdev_producto,0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Presentación'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,$vntsdev_empaque,0,1,'L');     

      $this->Ln(4.15);    
    }
  }

  $pdf = new PDF('P','mm','letter');
  $pdf->AliasNbPages();
  $pdf->SetDisplayMode(real, continuous);    
  $pdf->AddPage();

  $pdf->SetFillColor(180,180,180);

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(25,4,utf8_decode('Embarque'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,0,'R',true);
  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(75,4,utf8_decode($vntsdev_cdgembarque),0,1,'L');

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(25,4,utf8_decode('Origen'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,0,'R',true);
  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(75,4,utf8_decode($vntsdev_sucursal),0,1,'L');

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(25,4,utf8_decode('Devolución'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,0,'R',true);
  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(75,4,utf8_decode($vntsdev_devolucion).' mlls',0,1,'L');

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(25,4,utf8_decode('Contacto'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,0,'R',true);
  $pdf->SetFont('Arial','I',8);
  $pdf->Cell(75,4,utf8_decode($vntsdev_contacto),0,1,'L');  

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(25,4,utf8_decode('Causa'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,0,'R',true);
  $pdf->SetFont('Arial','I',8);
  $pdf->MultiCell(0,4.15,utf8_decode($vntsdev_observacion),0,'J');    

  if ($queryselectempaque->num_rows > 0)
  { while ($regempaque = $queryselectempaque->fetch_object())
    { $vntsdev_detalle .= $regempaque->tpoempaque.$regempaque->noempaque.' '.$regempaque->dev.' mlls, '; }
  }

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(25,4,utf8_decode('Detalle'),0,0,'R');
  $pdf->Cell(0.5,4,'',0,0,'R',true);
  $pdf->SetFont('Arial','I',8);
  $pdf->MultiCell(0,4.15,utf8_decode($vntsdev_detalle),0,'J');    
  
  for ($row = 1; $row <= 3; $row++)
  { for ($col = 1; $col <= 3; $col++)
    { 
    
    }
  }

/*
  for ($item=1; $item<=9; $item++)
  { if (file_exists('../img_vnts/'.$regdevolucion->cdgdev.$item.'.jpg'))
    { if ($regdevolucion->sttdev == 1)
      { if (substr($sistModuloPermiso,0,3) == 'rwx')
        { echo '
          <a href="vntsdev.php?proceso=photo&cdgdev='.$regdevolucion->cdgdev.'&delimage='.$regdevolucion->cdgdev.$item.'"><img src="../img_sistema/recycle_bin.png" width="24" /></a><br>'; }
      }
      
      echo'
       <img id="logo" src="../img_vnts/'.$regdevolucion->cdgdev.$item.'.jpg" /><br>';
    } 
  }//*/

  $pdf->Output('Queja o reclamo '.$_GET['cdgdev'].'.pdf', 'D');
  
?>
