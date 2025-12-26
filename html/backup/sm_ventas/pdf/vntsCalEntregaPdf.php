<?php

  include '../../datos/mysql.php';	
  require('../../fpdf/fpdf.php');

  if ($_GET['cdgsucursal'])
  { $link_mysqli = conectar();
    $vntsSucursalSelect = $link_mysqli->query("
      SELECT * FROM vntssucural
      WHERE cdgsucursal = '".$_GET['cdgsucursal']."'"); 

    if ($vntsSucursalSelect->num_rows > 0)
    { $regVntsSucursal = $vntsSucursalSelect->fetch_object();
      
      $_SESSION['vntscalentrega_sucursal'] = $regVntsSucursal->sucursal;
      $vntsCalEntrega_sucursal = $regVntsSucursal->sucursal;
      $vntsCalEntrega_cdgsucursal = $regVntsSucursal->cdgsucursal;
    } else
    { $_SESSION['vntscalentrega_sucursal'] = ''; }
  } else
  { $_SESSION['vntscalentrega_sucursal'] = ''; }

  if ($_GET['cdgproducto'])
  { $link_mysqli = conectar();
    $pdtoImpresionSelect = $link_mysqli->query("
      SELECT * FROM pdtoimpresion
      WHERE cdgimpresion = '".$_GET['cdgproducto']."'"); 

    if ($pdtoImpresionSelect->num_rows > 0)
    { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

      $_SESSION['vntscalentrega_producto'] = $regPdtoImpresion->impresion;
      $vntsCalEntrega_producto = $regPdtoImpresion->impresion;
      $vntsCalEntrega_cdgproducto = $regPdtoImpresion->cdgimpresion;
    } else
    { $_SESSION['vntscalentrega_producto'] = ''; }
  } else
  { $_SESSION['vntscalentrega_producto'] = ''; }


  class PDF extends FPDF
  { function Header()
    { if ($_SESSION['usuario'] == '')
      { $_SESSION['usuario'] = 'Invitado'; }

      if (file_exists('../../img_sistema/logo.jpg')==true)
      { $this->Image('../../img_sistema/logo.jpg',10,7,0,10); }

      $this->SetY(5);
      $this->SetFont('arial','B',8);

      $this->Cell(0,4,'Usuario: '.$_SESSION['usuario'],0,1,'R');          
      $this->Cell(0,4,'(VENTAS) Programa de entregas del '.$_GET['dsdfecha'].' al '.$_GET['hstfecha'],0,1,'R');

      if ($_SESSION['vntscalentrega_sucursal'] != '' AND $_SESSION['vntscalentrega_producto'] != '')
      { $_SESSION['vntscalentrega_subtitulo'] = 'Sucursal: '.$_SESSION['vntscalentrega_sucursal'].', Producto: '.$_SESSION['vntscalentrega_producto']; }
      else
      { if ($_SESSION['vntscalentrega_sucursal'] == '' AND $_SESSION['vntscalentrega_producto'] == '')
        { $_SESSION['vntscalentrega_subtitulo'] = 'Informe general'; }
        else
        { if ($_SESSION['vntscalentrega_sucursal'] != '')
          { $_SESSION['vntscalentrega_subtitulo'] = 'Sucursal: '.$_SESSION['vntscalentrega_sucursal']; }

          if ($_SESSION['vntscalentrega_producto'] != '')
          { $_SESSION['vntscalentrega_subtitulo'] = 'Producto: '.$_SESSION['vntscalentrega_producto']; }
        }
      }

      $this->Cell(0,4,$_SESSION['vntscalentrega_subtitulo'],0,1,'R');          
      $this->Ln(2);       
    }
  }

  $pdf=new PDF('P','mm','letter');
  $pdf->AliasNbPages();
  $pdf->SetDisplayMode(real, continuous);    
  $pdf->AddPage();

  $pdf->SetFillColor(180,180,180);

  // Catalogo de sucursales
  $link_mysqli = conectar();
  $vntsSucursalSelect = $link_mysqli->query("
    SELECT * FROM vntssucursal
    ORDER BY sucursal");

  if ($vntsSucursalSelect->num_rows > 0)
  { while ($regVntsSucursal = $vntsSucursalSelect->fetch_object())
    { $vntsCalEntrega_sucursales[$regVntsSucursal->cdgsucursal] = $regVntsSucursal->sucursal; }
  }

  // Catalogo de productos
  $link_mysqli = conectar();
  $pdtoImpresionSelect = $link_mysqli->query("
    SELECT * FROM pdtoimpresion
    ORDER BY impresion");

  if ($pdtoImpresionSelect->num_rows > 0)
  { $numproductos = $pdtoImpresionSelect->num_rows;

    $id_producto = 1;
    while ($regPdtoImpresion = $pdtoImpresionSelect->fetch_object())
    { $prodEntrega_producto[$id_producto] = $regPdtoImpresion->impresion;
      $prodEntrega_cdgproducto[$id_producto] = $regPdtoImpresion->cdgimpresion;

      $vntsCalEntrega_productos[$regPdtoImpresion->cdgimpresion] = $regPdtoImpresion->impresion; 

      $id_producto++; }
  }

  // Catalogo de tipos de empaque
  $link_mysqli = conectar();
  $pdtoEmpaqueSelect = $link_mysqli->query("
    SELECT * FROM pdtoempaque
    ORDER BY empaque");

  if ($pdtoEmpaqueSelect->num_rows > 0)
  { $numempaques = $pdtoEmpaqueSelect->num_rows;

    $id_empaque = 1;
    while ($regProdEmpaque = $pdtoEmpaqueSelect->fetch_object())
    { $prodEntrega_empaque[$id_empaque] = $regProdEmpaque->empaque;
      $prodEntrega_cdgempaque[$id_empaque] = $regProdEmpaque->cdgempaque;

      $vntsCalEntrega_empaques[$regProdEmpaque->cdgempaque] = $regProdEmpaque->empaque; 

      $id_empaque++; }
  }

  if ($vntsCalEntrega_cdgsucursal != '' AND $vntsCalEntrega_cdgproducto != '')
  { // Busqueda de compromisos por fecha embarque 
    $link_mysqli = conectar();
    $vntsCalEntregaSelect= $link_mysqli->query("
      SELECT vntsoclote.fchentrega,
    SUM(vntsoclote.cantidad) AS cantidad,
    SUM(vntsoclote.surtido) AS surtido   
      FROM vntsoc,
        vntsoclote
      WHERE vntsoc.cdgoc = vntsoclote.cdgoc AND
        vntsoc.cdgsucursal = '".$vntsCalEntrega_cdgsucursal."' AND
        vntsoclote.cdgproducto = '".$vntsCalEntrega_cdgproducto."' AND        
        vntsoclote.fchentrega BETWEEN '".$_GET['dsdfecha']."' AND '".$_GET['hstfecha']."'
      GROUP BY vntsoclote.fchentrega");

      if ($vntsCalEntregaSelect->num_rows > 0)
      { while($regVntsCalEntrega_fechas = $vntsCalEntregaSelect->fetch_object())
        { $vntsFchCalEntrega[$regVntsCalEntrega_fechas->fchentrega] = $regVntsCalEntrega_fechas->cantidad; }
      } 
  } else
  { if ($vntsCalEntrega_cdgsucursal == '' AND $vntsCalEntrega_cdgproducto == '')
    { // Busqueda de compromisos por fecha embarque 
      $link_mysqli = conectar();
      $vntsCalEntregaSelect= $link_mysqli->query("
        SELECT vntsoclote.fchentrega
        FROM vntsoclote
        WHERE vntsoclote.fchentrega BETWEEN '".$_GET['dsdfecha']."' AND '".$_GET['hstfecha']."'
        GROUP BY vntsoclote.fchentrega");

      if ($vntsCalEntregaSelect->num_rows > 0)
      { //if ($vntsCalEntregaSelect->num_rows > 1)
        //{ $imprime_fechas = true; }

        while($regVntsCalEntrega_fechas = $vntsCalEntregaSelect->fetch_object())
        { //if ($imprime_fechas == true)
          //{ $pdf->Cell(20,4,'Fecha '.$regVntsCalEntrega_fechas->fchentrega,0,1,'L'); }
          $pdf->Cell(20,4,'Fecha '.$regVntsCalEntrega_fechas->fchentrega,0,1,'L'); 

          $link_mysqli = conectar();
          $vntsCalEntregaSelectSucursal= $link_mysqli->query("
            SELECT vntsoc.cdgsucursal
            FROM vntsoc,
              vntsoclote
            WHERE vntsoc.cdgoc = vntsoclote.cdgoc AND              
              vntsoclote.fchentrega = '".$regVntsCalEntrega_fechas->fchentrega."'
            GROUP BY vntsoc.cdgsucursal");

          if ($vntsCalEntregaSelectSucursal->num_rows > 0)
          { while($regVntsCalEntrega_sucursales = $vntsCalEntregaSelectSucursal->fetch_object())
            { $pdf->Cell(20,4,'Sucursal '.$vntsCalEntrega_sucursales[$regVntsCalEntrega_sucursales->cdgsucursal],0,1,'L');

              $link_mysqli = conectar();
              $vntsCalEntregaSelectLote = $link_mysqli->query("
                SELECT vntsoc.oc,
                  vntsoclote.idlote,
                  vntsoclote.cdgproducto,
                SUM(vntsoclote.cantidad) AS cantidad,
                SUM(vntsoclote.surtido) AS surtido,
                  vntsoclote.referencia,
                  vntsoclote.cdgempaque,
                  vntsoc.fchrecepcion,
         DATEDIFF(vntsoclote.fchentrega, vntsoc.fchrecepcion) AS fchplazo,
                  vntsoclote.sttlote
                FROM vntsoc,
                  vntsoclote
                WHERE vntsoc.cdgoc = vntsoclote.cdgoc AND
                  vntsoc.cdgsucursal = '".$regVntsCalEntrega_sucursales->cdgsucursal."' AND
                  vntsoclote.fchentrega = '".$regVntsCalEntrega_fechas->fchentrega."'
                GROUP BY vntsoc.oc,
                  vntsoclote.idlote");

              if ($vntsCalEntregaSelectLote->num_rows > 0)
              { $pdf->SetFont('arial','B',8);

                $pdf->Cell(4,4,'',0,0,'C');
                $pdf->Cell(20,4,'Lote',1,0,'L',true);
                $pdf->Cell(25,4,'Recepcion',1,0,'L',true);
                $pdf->Cell(55,4,'Producto',1,0,'L',true);
                $pdf->Cell(20,4,'Cantidad',1,0,'L',true);
                $pdf->Cell(30,4,'Empaque',1,0,'L',true);                
                $pdf->Cell(38,4,'Referencia',1,1,'L',true);
                
                $millaresLote = 0;
                while($regVntsCalEntrega_lotes = $vntsCalEntregaSelectLote->fetch_object())
                { $pdf->SetFont('arial','B',5); 
                  $pdf->Cell(4,4,'',0,0,'R');

                  $pdf->SetFont('arial','',8); 
                  $pdf->Cell(20,4,$regVntsCalEntrega_lotes->oc.'-'.$regVntsCalEntrega_lotes->idlote,1,0,'R');
                  $pdf->Cell(25,4,$regVntsCalEntrega_lotes->fchrecepcion.'  ('.$regVntsCalEntrega_lotes->fchplazo.')',1,0,'L'); 
                  $pdf->Cell(55,4,$vntsCalEntrega_productos[$regVntsCalEntrega_lotes->cdgproducto],1,0,'L');
                  $pdf->Cell(20,4,number_format($regVntsCalEntrega_lotes->cantidad,3),1,0,'R'); 
                  $pdf->Cell(30,4,$vntsCalEntrega_empaques[$regVntsCalEntrega_lotes->cdgempaque],1,0,'L'); 
                  $pdf->Cell(38,4,$regVntsCalEntrega_lotes->referencia,1,1,'L'); 

                  
                  $prodEntrega_cantidad[$regVntsCalEntrega_lotes->cdgempaque][$regVntsCalEntrega_lotes->cdgproducto] = $prodEntrega_cantidad[$regVntsCalEntrega_lotes->cdgempaque][$regVntsCalEntrega_lotes->cdgproducto]+$regVntsCalEntrega_lotes->cantidad;
                  
                  $millaresLote += $regVntsCalEntrega_lotes->cantidad; } 

                $pdf->SetFont('arial','B',8);

                $pdf->Cell(4,4,'',0,0,'C');
                $pdf->Cell(100,4,'',0,0,'L');
                $pdf->Cell(20,4,number_format($millaresLote,3),1,1,'R',true);
                
                $pdf->Ln(2); 
              }
            }
          } 
        }
      }

      $pdf->Ln(4);

      //Aqui van los totales
      for ($id_empaque = 1; $id_empaque <= $numempaques; $id_empaque++)
      { $pdf->Cell(65,4,$prodEntrega_empaque[$id_empaque],1,1,'L',true); 
        
        for ($id_producto = 1; $id_producto <= $numproductos; $id_producto++)
        { if ($prodEntrega_cantidad[$prodEntrega_cdgempaque[$id_empaque]][$prodEntrega_cdgproducto[$id_producto]] > 0) 
          { $pdf->SetFont('arial','',8);
            $pdf->Cell(45,4,$prodEntrega_producto[$id_producto],1,0,'L');

            $pdf->SetFont('arial','B',8);
            $pdf->Cell(20,4,number_format($prodEntrega_cantidad[$prodEntrega_cdgempaque[$id_empaque]][$prodEntrega_cdgproducto[$id_producto]],3),1,1,'R');

            $prodEntrega_sumaempaque += $prodEntrega_cantidad[$prodEntrega_cdgempaque[$id_empaque]][$prodEntrega_cdgproducto[$id_producto]];
          }
        }

        $pdf->Cell(45,4,'Suma',1,0,'R');
        $pdf->Cell(20,4,number_format($prodEntrega_sumaempaque,3),1,1,'R');

        $prodEntrega_sumaempaque = 0;

        $pdf->Ln(2); 
      }

    } else
    { if ($vntsCalEntrega_cdgsucursal != '')
      { $link_mysqli = conectar();
        $vntsCalEntregaSelect= $link_mysqli->query("
          SELECT vntsoclote.fchentrega,
        SUM(vntsoclote.cantidad) AS cantidad,
        SUM(vntsoclote.surtido) AS surtido            
          FROM vntsoc,
            vntsoclote
          WHERE vntsoc.cdgoc = vntsoclote.cdgoc AND
            vntsoc.cdgsucursal = '".$vntsCalEntrega_cdgsucursal."' AND            
            vntsoclote.fchentrega BETWEEN '".$_GET['dsdfecha']."' AND '".$_GET['hstfecha']."'
          GROUP BY vntsoclote.fchentrega");

          if ($vntsCalEntregaSelect->num_rows > 0)
          { while($regVntsCalEntrega_fechas = $vntsCalEntregaSelect->fetch_object())
            { $pdf->Cell(20,4,'Fecha '.$regVntsCalEntrega_fechas->fchentrega,0,1,'L');

              $link_mysqli = conectar();
              $vntsCalEntregaSelectLote = $link_mysqli->query("
                SELECT vntsoc.oc,
                  vntsoc.cdgsucursal,
                  vntsoclote.idlote,
                  vntsoclote.cdgproducto,
              SUM(vntsoclote.cantidad) AS cantidad,
              SUM(vntsoclote.surtido) AS surtido
                  vntsoclote.referencia,
                  vntsoclote.cdgempaque,
                  vntsoc.fchrecepcion,
           DATEDIFF(vntsoclote.fchentrega, vntsoc.fchrecepcion) AS fchplazo,
                  vntsoclote.sttlote
                FROM vntsoc,
                  vntsoclote
                WHERE vntsoc.cdgoc = vntsoclote.cdgoc AND
                  vntsoc.cdgsucursal = '".$vntsCalEntrega_cdgsucursal."' AND
                  vntsoclote.fchentrega = '".$regVntsCalEntrega_fechas->fchentrega."'                  
                GROUP BY vntsoc.oc,
                  vntsoclote.idlote");

              if ($vntsCalEntregaSelectLote->num_rows > 0)
              { $pdf->SetFont('arial','B',8);

                $pdf->Cell(4,4,'',0,0,'C');
                $pdf->Cell(20,4,'Lote',1,0,'L',true);
                $pdf->Cell(25,4,'Recepcion',1,0,'L',true);
                $pdf->Cell(55,4,'Sucursal',1,0,'L',true);
                $pdf->Cell(20,4,'Cantidad',1,0,'L',true);
                $pdf->Cell(30,4,'Empaque',1,0,'L',true);
                $pdf->Cell(38,4,'Referencia',1,1,'L',true);
                
                $millaresLote = 0;
                while($regVntsCalEntrega_lotes = $vntsCalEntregaSelectLote->fetch_object())
                { $pdf->SetFont('arial','B',5); 
                  $pdf->Cell(4,4,'',0,0,'R');

                  $pdf->SetFont('arial','',8); 
                  $pdf->Cell(20,4,$regVntsCalEntrega_lotes->oc.'-'.$regVntsCalEntrega_lotes->idlote,1,0,'R');
                  $pdf->Cell(25,4,$regVntsCalEntrega_lotes->fchrecepcion.'  ('.$regVntsCalEntrega_lotes->fchplazo.')',1,0,'L'); 
                  $pdf->Cell(55,4,$vntsCalEntrega_sucursales[$regVntsCalEntrega_lotes->cdgsucursal],1,0,'L');
                  $pdf->Cell(20,4,number_format($regVntsCalEntrega_lotes->cantidad,3),1,0,'R'); 
                  $pdf->Cell(30,4,$vntsCalEntrega_empaques[$regVntsCalEntrega_lotes->cdgempaque],1,0,'L'); 
                  $pdf->Cell(38,4,$regVntsCalEntrega_lotes->referencia,1,1,'L');

                  $prodEntrega_cantidad[$regVntsCalEntrega_lotes->cdgempaque][$regVntsCalEntrega_lotes->cdgproducto] = $prodEntrega_cantidad[$regVntsCalEntrega_lotes->cdgempaque][$regVntsCalEntrega_lotes->cdgproducto]+$regVntsCalEntrega_lotes->cantidad;

                  $millaresLote += $regVntsCalEntrega_lotes->cantidad; } 

                $pdf->SetFont('arial','B',8);

                $pdf->Cell(4,4,'',0,0,'C');
                $pdf->Cell(100,4,'',0,0,'L');
                $pdf->Cell(20,4,number_format($millaresLote,3),1,1,'R',true);
                
                $pdf->Ln(2); 
              }

            }
          } 
      } else
      {
      if ($vntsCalEntrega_cdgproducto != '')
      { // Busqueda de compromisos por fecha embarque 
        $link_mysqli = conectar();
        $vntsCalEntregaSelect= $link_mysqli->query("
          SELECT vntsoclote.fchentrega
          FROM vntsoclote
          WHERE vntsoclote.cdgproducto = '".$vntsCalEntrega_cdgproducto."' AND            
            vntsoclote.fchentrega BETWEEN '".$_GET['dsdfecha']."' AND '".$_GET['hstfecha']."'
          GROUP BY vntsoclote.fchentrega");

        if ($vntsCalEntregaSelect->num_rows > 0)
        { while($regVntsCalEntrega_fechas = $vntsCalEntregaSelect->fetch_object())
          { $pdf->Cell(20,4,'Fecha '.$regVntsCalEntrega_fechas->fchentrega,0,1,'L');

            $link_mysqli = conectar();
            $vntsCalEntregaSelectLote = $link_mysqli->query("
              SELECT vntsoc.oc,
                vntsoc.cdgsucursal,
                vntsoclote.idlote,
                vntsoclote.cdgproducto,
            SUM(vntsoclote.cantidad) AS cantidad,
                vntsoclote.referencia,
                vntsoclote.cdgempaque,
                vntsoc.fchrecepcion,
         DATEDIFF(vntsoclote.fchentrega, vntsoc.fchrecepcion) AS fchplazo,
                vntsoclote.sttlote
              FROM vntsoc,
                vntsoclote
              WHERE vntsoc.cdgoc = vntsoclote.cdgoc AND
                vntsoclote.cdgproducto = '".$vntsCalEntrega_cdgproducto."' AND                    
                vntsoclote.fchentrega = '".$regVntsCalEntrega_fechas->fchentrega."'                
              GROUP BY vntsoc.oc,
                vntsoclote.idlote");

            if ($vntsCalEntregaSelectLote->num_rows > 0)
            { $pdf->SetFont('arial','B',8);

              $pdf->Cell(4,4,'',0,0,'C');
              $pdf->Cell(20,4,'Lote',1,0,'L',true);
              $pdf->Cell(25,4,'Recepcion',1,0,'L',true);
              $pdf->Cell(55,4,'Sucursal',1,0,'L',true);
              $pdf->Cell(20,4,'Cantidad',1,0,'L',true);
              $pdf->Cell(30,4,'Empaque',1,0,'L',true);
              $pdf->Cell(38,4,'Referencia',1,1,'L',true);
              
              $millaresLote = 0;
              while($regVntsCalEntrega_lotes = $vntsCalEntregaSelectLote->fetch_object())
              { $pdf->SetFont('arial','B',5); 
                $pdf->Cell(4,4,'',0,0,'R');

                $pdf->SetFont('arial','',8); 
                $pdf->Cell(20,4,$regVntsCalEntrega_lotes->oc.'-'.$regVntsCalEntrega_lotes->idlote,1,0,'R');
                $pdf->Cell(25,4,$regVntsCalEntrega_lotes->fchrecepcion.'  ('.$regVntsCalEntrega_lotes->fchplazo.')',1,0,'L'); 
                $pdf->Cell(55,4,$vntsCalEntrega_sucursales[$regVntsCalEntrega_lotes->cdgsucursal],1,0,'L');
                $pdf->Cell(20,4,number_format($regVntsCalEntrega_lotes->cantidad,3),1,0,'R'); 
                $pdf->Cell(30,4,$vntsCalEntrega_empaques[$regVntsCalEntrega_lotes->cdgempaque],1,0,'L'); 
                $pdf->Cell(38,4,$regVntsCalEntrega_lotes->referencia,1,1,'L');

                $prodEntrega_cantidad[$regVntsCalEntrega_lotes->cdgempaque][$regVntsCalEntrega_lotes->cdgproducto] = $prodEntrega_cantidad[$regVntsCalEntrega_lotes->cdgempaque][$regVntsCalEntrega_lotes->cdgproducto]+$regVntsCalEntrega_lotes->cantidad;

                $millaresLote += $regVntsCalEntrega_lotes->cantidad; } 

              $pdf->SetFont('arial','B',8);

              $pdf->Cell(4,4,'',0,0,'C');
              $pdf->Cell(100,4,'',0,0,'L');
              $pdf->Cell(20,4,number_format($millaresLote,3),1,1,'R',true);
              
              $pdf->Ln(2); 
            }
          }

          $pdf->Ln(4);

          //Aqui van los totales
          for ($id_empaque = 1; $id_empaque <= $numempaques; $id_empaque++)
          { $pdf->Cell(65,4,$prodEntrega_empaque[$id_empaque],1,1,'L',true); 
            
            for ($id_producto = 1; $id_producto <= $numproductos; $id_producto++)
            { if ($prodEntrega_cantidad[$prodEntrega_cdgempaque[$id_empaque]][$prodEntrega_cdgproducto[$id_producto]] > 0) 
              { $pdf->SetFont('arial','',8);
                $pdf->Cell(45,4,$prodEntrega_producto[$id_producto],1,0,'L');

                $pdf->SetFont('arial','B',8);
                $pdf->Cell(20,4,number_format($prodEntrega_cantidad[$prodEntrega_cdgempaque[$id_empaque]][$prodEntrega_cdgproducto[$id_producto]],3),1,1,'R');

                $prodEntrega_sumaempaque += $prodEntrega_cantidad[$prodEntrega_cdgempaque[$id_empaque]][$prodEntrega_cdgproducto[$id_producto]];
              }          
            }
        
            $pdf->Cell(45,4,'Suma',1,0,'R');
            $pdf->Cell(20,4,number_format($prodEntrega_sumaempaque,3),1,1,'R');

            $prodEntrega_sumaempaque = 0;

            $pdf->Ln(2); 
          }
        }
      }
      }
    }
  }

  $pdf->Output('Programa de entregas del '.$_GET['dsdfecha'].' al '.$_GET['hstfecha'].' '.$_SESSION['vntscalentrega_subtitulo'].'.pdf', 'D');
  
?>
