<?php

  include '../../datos/mysql.php';	
  require('../../fpdf/fpdf.php');

  if ($_GET['cdgproceso'])
  { $link_mysqli = conectar();
    $prodProcesoSelect = $link_mysqli->query("
      SELECT * FROM prodproceso
      WHERE cdgproceso = '".$_GET['cdgproceso']."'"); 

    if ($prodProcesoSelect->num_rows > 0)
    { $regProdProceso = $prodProcesoSelect->fetch_object();
      
      $_SESSION['prodcalendario_proceso'] = $regProdProceso->proceso;

      $prodCalendario_proceso = $regProdProceso->proceso;
      $prodCalendario_cdgproceso = $regProdProceso->cdgproceso;
    } else
    { $_SESSION['prodcalendario_proceso'] = ''; }
  } else
  { $_SESSION['prodcalendario_proceso'] = ''; }

  if ($_GET['cdgempleado'])
  { $link_mysqli = conectar();
    $rechEmpleadoSelect = $link_mysqli->query("
      SELECT * FROM rechempleado
      WHERE cdgempleado = '".$_GET['cdgempleado']."'"); 

    if ($rechEmpleadoSelect->num_rows > 0)
    { $regRechEmpleado = $rechEmpleadoSelect->fetch_object();

      $_SESSION['prodcalendario_empleado'] = $regRechEmpleado->empleado;

      $prodCalendario_empleado = $regRechEmpleado->empleado;
    } else
    { $_SESSION['prodcalendario_empleado'] = ''; }
  } else
  { $_SESSION['prodcalendario_empleado'] = ''; }


  class PDF extends FPDF
  { function Header()
    { if ($_SESSION['usuario'] == '')
      { $_SESSION['usuario'] = 'Invitado'; }

      if (file_exists('../../img_sistema/logo.jpg')==true)
      { $this->Image('../../img_sistema/logo.jpg',10,7,0,10); }

      $this->SetY(5);
      $this->SetFont('arial','B',8);

      $this->Cell(0,4,'Usuario: '.$_SESSION['usuario'],0,1,'R');          
      $this->Cell(0,4,'Reporte de produccion del '.$_GET['dsdfecha'].' al '.$_GET['hstfecha'],0,1,'R');

      if ($_SESSION['prodcalendario_proceso'] != '' AND $_SESSION['prodcalendario_empleado'] != '')
      { $_SESSION['prodcalendario_subtitulo'] = 'Proceso: '.$_SESSION['prodcalendario_proceso'].', Empleado: '.$_SESSION['prodcalendario_empleado']; }
      else
      { if ($_SESSION['prodcalendario_proceso'] == '' AND $_SESSION['prodcalendario_empleado'] == '')
        { $_SESSION['prodcalendario_subtitulo'] = 'Informe general'; }
        else
        { if ($_SESSION['prodcalendario_proceso'] != '')
          { $_SESSION['prodcalendario_subtitulo'] = 'Proceso: '.$_SESSION['prodcalendario_proceso']; }

          if ($_SESSION['prodcalendario_empleado'] != '')
          { $_SESSION['prodcalendario_subtitulo'] = 'Empleado: '.$_SESSION['prodcalendario_empleado']; }
        }
      }

      $this->Cell(0,4,$_SESSION['prodcalendario_subtitulo'],0,1,'R');          
      $this->Ln(2);       
    }
  }

  $pdf=new PDF('P','mm','letter');
  $pdf->AliasNbPages();
  $pdf->SetDisplayMode(real, continuous);    
  $pdf->AddPage();

  $pdf->SetFillColor(180,180,180);

  // Catalogo de procesos
  $link_mysqli = conectar();
  $prodOperacionSelect = $link_mysqli->query("
    SELECT * FROM prodoperacion
    ORDER BY idproceso");

  if ($prodProcesoSelect->num_rows > 0)
  { while ($regProdProceso = $prodProcesoSelect->fetch_object())
    { $prodCalendario_procesos[$regProdProceso->cdgproceso] = $regProdProceso->proceso; }
  }

  // Catalogo de operaciones
  $link_mysqli = conectar();
  $prodOperacionSelect = $link_mysqli->query("
    SELECT * FROM prodoperacion    
    ORDER BY idoperacion");

  if ($prodOperacionSelect->num_rows > 0)
  { while ($regProdOperacion = $prodOperacionSelect->fetch_object())
    { $prodCalendario_operaciones[$regProdOperacion->cdgoperacion] = $regProdOperacion->operacion; }
  }

  // Catalogo de empleados
  $link_mysqli = conectar();
  $rechEmpleadoSelect = $link_mysqli->query("
    SELECT * FROM rechempleado
    ORDER BY empleado");

  if ($rechEmpleadoSelect->num_rows > 0)
  { while ($regRechEmpleado = $rechEmpleadoSelect->fetch_object())
    { $prodCalendario_empleados[$regRechEmpleado->cdgempleado] = $regRechEmpleado->empleado; }
  }

  if ($_SESSION['prodcalendario_proceso'] != '' AND $_SESSION['prodcalendario_empleado'] != '')
  { 
  // Buscar lotes
    $link_mysqli = conectar();
    $prodCalendarioSelect= $link_mysqli->query("
      SELECT SUBSTR(prodloteope.fchmovimiento,1,10) AS fchmovimiento
      FROM prodlote,
        prodloteope,
        prodoperacion
      WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
        prodloteope.cdgempleado = '".$_GET['cdgempleado']."' AND        
       (SUBSTR(prodloteope.fchmovimiento,1,10) BETWEEN '".$_GET['dsdfecha']."' AND '".$_GET['hstfecha']."') AND
        prodlote.cdglote = prodloteope.cdglote AND
        prodloteope.cdgoperacion = prodoperacion.cdgoperacion
      GROUP BY SUBSTR(prodloteope.fchmovimiento,1,10)");

    if ($prodCalendarioSelect->num_rows > 0)
    { while($regProdCalendario = $prodCalendarioSelect->fetch_object())
      { $link_mysqli = conectar();
        $prodOperacionSelect = $link_mysqli->query("
          SELECT SUBSTR(prodloteope.fchmovimiento,1,10) AS fchmovimiento,
            prodoperacion.cdgoperacion
          FROM prodlote,
            prodloteope,
            prodoperacion
          WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
            prodloteope.cdgempleado = '".$_GET['cdgempleado']."' AND
     SUBSTR(prodloteope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
            prodlote.cdglote = prodloteope.cdglote AND
            prodloteope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodloteope.fchmovimiento,1,10),
            prodoperacion.cdgoperacion");

        if ($prodOperacionSelect->num_rows > 0)
        { while($regProdOperacion = $prodOperacionSelect->fetch_object())
          { $link_mysqli = conectar();
            $prodLoteSelect = $link_mysqli->query("
              SELECT prodloteope.fchmovimiento,
                prodloteope.cdgoperacion,
                prodlote.noop,
                pdtoimpresion.impresion AS producto,
                prodlote.longitud,
              ((prodlote.longitud*pdtoimpresion.alpaso)/pdtoimpresion.corte) AS millares
              FROM prodlote,
                prodloteope,
                prodoperacion,
                pdtoimpresion
              WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                prodloteope.cdgempleado = '".$_GET['cdgempleado']."' AND
         SUBSTR(prodloteope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                prodloteope.cdgoperacion = '".$regProdOperacion->cdgoperacion."' AND
                prodlote.cdglote = prodloteope.cdglote AND
                prodloteope.cdgoperacion = prodoperacion.cdgoperacion AND
                prodlote.cdgproducto = pdtoimpresion.cdgimpresion
              ORDER BY prodloteope.fchmovimiento");

            if ($prodLoteSelect->num_rows > 0)
            { $pdf->SetFont('arial','B',8);

              $pdf->Cell(4,4,'',0,0,'C');
              $pdf->Cell(20,4,'NoOP',1,0,'C',true);
              $pdf->Cell(53,4,'Producto',1,0,'C',true);
              $pdf->Cell(20,4,'Metros',1,0,'C',true);
              $pdf->Cell(20,4,'Millares',1,0,'C',true);
              $pdf->Cell(28,4,'Movimiento',1,1,'C',true);
              
              $id_contador = 1;
              $metrosLote = 0;
              while($regProdLote = $prodLoteSelect->fetch_object())
              { $pdf->SetFont('arial','B',5); 
                $pdf->Cell(4,4,$id_contador,0,0,'R');

                $pdf->SetFont('arial','',8); 
                $pdf->Cell(20,4,$regProdLote->noop,1,0,'R');
                $pdf->Cell(53,4,$regProdLote->producto,1,0,'L');
                $pdf->Cell(20,4,number_format($regProdLote->longitud,2),1,0,'R');
                $pdf->Cell(20,4,number_format($regProdLote->millares,3),1,0,'R');
                $pdf->Cell(28,4,$regProdLote->fchmovimiento,1,1,'L'); 

                $metrosLote += $regProdLote->longitud;

                $id_contador++; }

              $pdf->SetFont('arial','B',8);

              $pdf->Cell(4,4,'',0,0,'C');
              $pdf->Cell(73,4,'Operacion: '.$prodCalendario_operaciones[$regProdOperacion->cdgoperacion],0,0,'L');
              $pdf->Cell(20,4,number_format($metrosLote,2),1,1,'R',true);
              
              $pdf->Ln(2); 
            }
          }
        }
      }
    }
    
    // Buscar bobinas
    $link_mysqli = conectar();
    $prodCalendarioSelect= $link_mysqli->query("
      SELECT SUBSTR(prodbobinaope.fchmovimiento,1,10) AS fchmovimiento
      FROM prodbobina,
        prodbobinaope,
        prodoperacion
      WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
        prodbobinaope.cdgempleado = '".$_GET['cdgempleado']."' AND        
       (SUBSTR(prodbobinaope.fchmovimiento,1,10) BETWEEN '".$_GET['dsdfecha']."' AND '".$_GET['hstfecha']."') AND
        prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
        prodbobinaope.cdgoperacion = prodoperacion.cdgoperacion
      GROUP BY SUBSTR(prodbobinaope.fchmovimiento,1,10)");

    if ($prodCalendarioSelect->num_rows > 0)
    { while($regProdCalendario = $prodCalendarioSelect->fetch_object())
      { $link_mysqli = conectar();
        $prodOperacionSelect = $link_mysqli->query("
          SELECT SUBSTR(prodbobinaope.fchmovimiento,1,10) AS fchmovimiento,
            prodoperacion.cdgoperacion
          FROM prodbobina,
            prodbobinaope,
            prodoperacion
          WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
            prodbobinaope.cdgempleado = '".$_GET['cdgempleado']."' AND
     SUBSTR(prodbobinaope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
            prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
            prodbobinaope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodbobinaope.fchmovimiento,1,10),
            prodoperacion.cdgoperacion");

        if ($prodOperacionSelect->num_rows > 0)
        { while($regProdOperacion = $prodOperacionSelect->fetch_object())
          { $link_mysqli = conectar();
            $prodBobinaSelect = $link_mysqli->query("
              SELECT prodbobinaope.fchmovimiento,
                prodbobinaope.cdgoperacion,
         CONCAT(prodlote.noop,'-',prodbobina.bobina) AS noop,
                pdtoimpresion.impresion AS producto,
                prodbobina.longitud,
               (prodbobina.longitud/pdtoimpresion.corte) AS millares
              FROM prodlote,
                prodbobina,
                prodbobinaope,
                prodoperacion,
                pdtoimpresion
              WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                prodbobinaope.cdgempleado = '".$_GET['cdgempleado']."' AND
         SUBSTR(prodbobinaope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                prodbobinaope.cdgoperacion = '".$regProdOperacion->cdgoperacion."' AND
                prodlote.cdglote = prodbobina.cdglote AND
                prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
                prodbobinaope.cdgoperacion = prodoperacion.cdgoperacion AND
                prodbobina.cdgproducto = pdtoimpresion.cdgimpresion
              ORDER BY prodbobinaope.fchmovimiento");

              $pdf->SetFont('arial','B',8);

            if ($prodBobinaSelect->num_rows > 0)
            { 
              $pdf->Cell(4,4,'',0,0,'C');  
              $pdf->Cell(20,4,'NoOP',1,0,'C',true);
              $pdf->Cell(53,4,'Producto',1,0,'C',true);
              $pdf->Cell(20,4,'Metros',1,0,'C',true);
              $pdf->Cell(20,4,'Millares',1,0,'C',true);
              $pdf->Cell(28,4,'Movimiento',1,1,'C',true);
              
              $id_contador = 1;
              $metrosBobina = 0;
              while($regProdBobina = $prodBobinaSelect->fetch_object())
              { $pdf->SetFont('arial','B',5); 
                $pdf->Cell(4,4,$id_contador,0,0,'R');

                $pdf->SetFont('arial','',8); 
                $pdf->Cell(20,4,$regProdBobina->noop,1,0,'R');
                $pdf->Cell(53,4,$regProdBobina->producto,1,0,'L');
                $pdf->Cell(20,4,number_format($regProdBobina->longitud,2),1,0,'R');
                $pdf->Cell(20,4,number_format($regProdBobina->millares,3),1,0,'R');
                $pdf->Cell(28,4,$regProdBobina->fchmovimiento,1,1,'L'); 

                $metrosBobina += $regProdBobina->longitud;
     
                $id_contador++; }

              $pdf->SetFont('arial','B',8);

              $pdf->Cell(4,4,'',0,0,'C');
              $pdf->Cell(73,4,'Operacion: '.$prodCalendario_operaciones[$regProdOperacion->cdgoperacion],0,0,'L');
              $pdf->Cell(20,4,number_format($metrosBobina,2),1,1,'R',true);

              $pdf->Ln(2);  
            } 
          }
        }
      }
    } 

    // Buscar rollos
    $link_mysqli = conectar();
    $prodCalendarioSelect= $link_mysqli->query("
      SELECT SUBSTR(prodrolloope.fchmovimiento,1,10) AS fchmovimiento
      FROM prodrollo,
        prodrolloope,
        prodoperacion
      WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
        prodrolloope.cdgempleado = '".$_GET['cdgempleado']."' AND        
(SUBSTR(prodrolloope.fchmovimiento,1,10) BETWEEN '".$_GET['dsdfecha']."' AND '".$_GET['hstfecha']."') AND
        prodrollo.cdgrollo = prodrolloope.cdgrollo AND
        prodrolloope.cdgoperacion = prodoperacion.cdgoperacion
      GROUP BY SUBSTR(prodrolloope.fchmovimiento,1,10)");

    if ($prodCalendarioSelect->num_rows > 0)
    { while($regProdCalendario = $prodCalendarioSelect->fetch_object())
      { $link_mysqli = conectar();
        $prodOperacionSelect = $link_mysqli->query("
          SELECT SUBSTR(prodrolloope.fchmovimiento,1,10) AS fchmovimiento,
            prodoperacion.cdgoperacion
          FROM prodrollo,
            prodrolloope,
            prodoperacion
          WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
            prodrolloope.cdgempleado = '".$_GET['cdgempleado']."' AND
     SUBSTR(prodrolloope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
            prodrollo.cdgrollo = prodrolloope.cdgrollo AND
            prodrolloope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodrolloope.fchmovimiento,1,10),
            prodoperacion.cdgoperacion");

        if ($prodOperacionSelect->num_rows > 0)
        { while($regProdOperacion = $prodOperacionSelect->fetch_object())
          { $link_mysqli = conectar();
            $prodRolloSelect = $link_mysqli->query("
              SELECT prodrolloope.fchmovimiento,
                prodrolloope.cdgoperacion,
         CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) AS noop,
                pdtoimpresion.impresion AS producto,
                prodrollo.longitud,
               (prodrollo.longitud/pdtoimpresion.corte) AS millares
              FROM prodlote,
                prodbobina,
                prodrollo,
                prodrolloope,
                prodoperacion,
                pdtoimpresion
              WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                prodrolloope.cdgempleado = '".$_GET['cdgempleado']."' AND
         SUBSTR(prodrolloope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                prodrolloope.cdgoperacion = '".$regProdOperacion->cdgoperacion."' AND
                prodlote.cdglote = prodbobina.cdglote AND
                prodbobina.cdgbobina = prodrollo.cdgbobina AND
                prodrollo.cdgrollo = prodrolloope.cdgrollo AND
                prodrolloope.cdgoperacion = prodoperacion.cdgoperacion AND
                prodrollo.cdgproducto = pdtoimpresion.cdgimpresion
              ORDER BY prodrolloope.fchmovimiento");

            if ($prodRolloSelect->num_rows > 0)
            { $pdf->SetFont('arial','B',8);

              $pdf->Cell(4,4,'',0,0,'C');
              $pdf->Cell(20,4,'NoOP',1,0,'C',true);
              $pdf->Cell(53,4,'Producto',1,0,'C',true);
              $pdf->Cell(20,4,'Metros',1,0,'C',true);
              $pdf->Cell(20,4,'Millares',1,0,'C',true);
              $pdf->Cell(28,4,'Movimiento',1,1,'C',true);
              
              $id_contador = 1;
              $metrosRollo = 0;
              while($regProdRollo = $prodRolloSelect->fetch_object())
              { $pdf->SetFont('arial','B',5); 
                $pdf->Cell(4,4,$id_contador,0,0,'R');

                $pdf->SetFont('arial','',8); 
                $pdf->Cell(20,4,$regProdRollo->noop,1,0,'R');
                $pdf->Cell(53,4,$regProdRollo->producto,1,0,'L');
                $pdf->Cell(20,4,number_format($regProdRollo->longitud,2),1,0,'R');
                $pdf->Cell(20,4,number_format($regProdRollo->millares,3),1,0,'R');
                $pdf->Cell(28,4,$regProdRollo->fchmovimiento,1,1,'L'); 

                $metrosRollo += $regProdRollo->longitud;

                $id_contador++; }

              $pdf->SetFont('arial','B',8); 
              $pdf->Cell(4,4,'',0,0,'C');
              $pdf->Cell(73,4,'Operacion: '.$prodCalendario_operaciones[$regProdOperacion->cdgoperacion],0,0,'L');
              $pdf->Cell(20,4,number_format($metrosRollo,2),1,1,'R',true);

              $pdf->Ln(2);
            }
          }
        }
      }
    } 
  } else
  { if ($_SESSION['prodcalendario_proceso'] == '' AND $_SESSION['prodcalendario_empleado'] == '')
    { $subtitulo = 'Informe general'; }
    else
    { if ($_SESSION['prodcalendario_proceso'] != '')
      { // Buscar lotes
        $link_mysqli = conectar();
        $prodCalendarioSelect= $link_mysqli->query("
          SELECT SUBSTR(prodloteope.fchmovimiento,1,10) AS fchmovimiento
          FROM prodlote,
            prodloteope,
            prodoperacion
          WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND        
    (SUBSTR(prodloteope.fchmovimiento,1,10) BETWEEN '".$_GET['dsdfecha']."' AND '".$_GET['hstfecha']."') AND
            prodlote.cdglote = prodloteope.cdglote AND
            prodloteope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodloteope.fchmovimiento,1,10)");

        if ($prodCalendarioSelect->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelect->fetch_object())
          { $link_mysqli = conectar();
            $prodOperacionSelect = $link_mysqli->query("
              SELECT SUBSTR(prodloteope.fchmovimiento,1,10) AS fchmovimiento,
                prodoperacion.cdgoperacion
              FROM prodlote,
                prodloteope,
                prodoperacion
              WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
         SUBSTR(prodloteope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                prodlote.cdglote = prodloteope.cdglote AND
                prodloteope.cdgoperacion = prodoperacion.cdgoperacion
              GROUP BY SUBSTR(prodloteope.fchmovimiento,1,10),
                prodoperacion.cdgoperacion");

            if ($prodOperacionSelect->num_rows > 0)
            { while($regProdOperacion = $prodOperacionSelect->fetch_object())
              { $link_mysqli = conectar();
                $prodLoteSelect = $link_mysqli->query("
                  SELECT prodloteope.fchmovimiento,
                    prodloteope.cdgoperacion,
                    prodlote.noop,
                    pdtoimpresion.impresion AS producto,
                    prodlote.longitud,
                  ((prodlote.longitud*pdtoimpresion.alpaso)/pdtoimpresion.corte) AS millares,
                    rechempleado.empleado
                  FROM prodlote,
                    prodloteope,
                    prodoperacion,
                    pdtoimpresion,
                    rechempleado
                  WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                    prodloteope.cdgempleado = rechempleado.cdgempleado AND
             SUBSTR(prodloteope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                    prodloteope.cdgoperacion = '".$regProdOperacion->cdgoperacion."' AND
                    prodlote.cdglote = prodloteope.cdglote AND
                    prodloteope.cdgoperacion = prodoperacion.cdgoperacion AND
                    prodlote.cdgproducto = pdtoimpresion.cdgimpresion
                  ORDER BY prodloteope.fchmovimiento");

                if ($prodLoteSelect->num_rows > 0)
                { $pdf->SetFont('arial','B',8);

                  $pdf->Cell(4,4,'',0,0,'C');
                  $pdf->Cell(20,4,'NoOP',1,0,'C',true);
                  $pdf->Cell(53,4,'Producto',1,0,'C',true);
                  $pdf->Cell(20,4,'Metros',1,0,'C',true);
                  $pdf->Cell(20,4,'Millares',1,0,'C',true);
                  $pdf->Cell(50,4,'Empleado',1,0,'C',true);
                  $pdf->Cell(28,4,'Movimiento',1,1,'C',true);
                  
                  $id_contador = 1;
                  $metrosLote = 0;
                  while($regProdLote = $prodLoteSelect->fetch_object())
                  { $pdf->SetFont('arial','B',5); 
                    $pdf->Cell(4,4,$id_contador,0,0,'R');

                    $pdf->SetFont('arial','',8); 
                    $pdf->Cell(20,4,$regProdLote->noop,1,0,'R');
                    $pdf->Cell(53,4,$regProdLote->producto,1,0,'L');
                    $pdf->Cell(20,4,number_format($regProdLote->longitud,2),1,0,'R');
                    $pdf->Cell(20,4,number_format($regProdLote->millares,3),1,0,'R');
                    $pdf->Cell(50,4,$regProdLote->empleado,1,0,'L');
                    $pdf->Cell(28,4,$regProdLote->fchmovimiento,1,1,'L'); 

                    $metrosLote += $regProdLote->longitud;

                    $id_contador++; }

                  $pdf->SetFont('arial','B',8);

                  $pdf->Cell(4,4,'',0,0,'C');
                  $pdf->Cell(73,4,'Operacion: '.$prodCalendario_operaciones[$regProdOperacion->cdgoperacion],0,0,'L');
                  $pdf->Cell(20,4,number_format($metrosLote,2),1,1,'R',true);

                  $pdf->Ln(2);
                }
              } // Ciclos
            }
          } // Ciclos
        }
            
        // Buscar bobinas
        $link_mysqli = conectar();
        $prodCalendarioSelect= $link_mysqli->query("
          SELECT SUBSTR(prodbobinaope.fchmovimiento,1,10) AS fchmovimiento
          FROM prodbobina,
            prodbobinaope,
            prodoperacion
          WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND        
           (SUBSTR(prodbobinaope.fchmovimiento,1,10) BETWEEN '".$_GET['dsdfecha']."' AND '".$_GET['hstfecha']."') AND
            prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
            prodbobinaope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodbobinaope.fchmovimiento,1,10)");

        if ($prodCalendarioSelect->num_rows > 0)
        { $pdf->SetFont('arial','',8);    

          while($regProdCalendario = $prodCalendarioSelect->fetch_object())
          { $link_mysqli = conectar();
            $prodOperacionSelect = $link_mysqli->query("
              SELECT SUBSTR(prodbobinaope.fchmovimiento,1,10) AS fchmovimiento,
                prodoperacion.cdgoperacion
              FROM prodbobina,
                prodbobinaope,
                prodoperacion
              WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND            
         SUBSTR(prodbobinaope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
                prodbobinaope.cdgoperacion = prodoperacion.cdgoperacion
              GROUP BY SUBSTR(prodbobinaope.fchmovimiento,1,10),
                prodoperacion.cdgoperacion");

            if ($prodOperacionSelect->num_rows > 0)
            { while($regProdOperacion = $prodOperacionSelect->fetch_object())
              { $link_mysqli = conectar();
                $prodBobinaSelect = $link_mysqli->query("
                  SELECT prodbobinaope.fchmovimiento,
                    prodbobinaope.cdgoperacion,
             CONCAT(prodlote.noop,'-',prodbobina.bobina) AS noop,
                    pdtoimpresion.impresion AS producto,
                    prodbobina.longitud,
                   (prodbobina.longitud/pdtoimpresion.corte) AS millares,
                    rechempleado.empleado
                  FROM prodlote,
                    prodbobina,
                    prodbobinaope,
                    prodoperacion,
                    pdtoimpresion,
                    rechempleado
                  WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                    prodbobinaope.cdgempleado = rechempleado.cdgempleado AND
             SUBSTR(prodbobinaope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                    prodbobinaope.cdgoperacion = '".$regProdOperacion->cdgoperacion."' AND
                    prodlote.cdglote = prodbobina.cdglote AND
                    prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
                    prodbobinaope.cdgoperacion = prodoperacion.cdgoperacion AND
                    prodbobina.cdgproducto = pdtoimpresion.cdgimpresion
                  ORDER BY prodbobinaope.fchmovimiento");

                if ($prodBobinaSelect->num_rows > 0)
                { $pdf->SetFont('arial','B',8);

                  $pdf->Cell(4,4,'',0,0,'C');  
                  $pdf->Cell(20,4,'NoOP',1,0,'C',true);
                  $pdf->Cell(53,4,'Producto',1,0,'C',true);
                  $pdf->Cell(20,4,'Metros',1,0,'C',true);
                  $pdf->Cell(20,4,'Millares',1,0,'C',true);
                  $pdf->Cell(50,4,'Empleado',1,0,'C',true);
                  $pdf->Cell(28,4,'Movimiento',1,1,'C',true);
                  
                  $id_contador = 1;
                  $metrosBobina = 0;
                  while($regProdBobina = $prodBobinaSelect->fetch_object())
                  { $pdf->SetFont('arial','B',5); 
                    $pdf->Cell(4,4,$id_contador,0,0,'R');

                    $pdf->SetFont('arial','',8); 
                    $pdf->Cell(20,4,$regProdBobina->noop,1,0,'R');
                    $pdf->Cell(53,4,$regProdBobina->producto,1,0,'L');
                    $pdf->Cell(20,4,number_format($regProdBobina->longitud,2),1,0,'R');
                    $pdf->Cell(20,4,number_format($regProdBobina->millares,3),1,0,'R');
                    $pdf->Cell(50,4,$regProdBobina->empleado,1,0,'L');
                    $pdf->Cell(28,4,$regProdBobina->fchmovimiento,1,1,'L'); 

                    $metrosBobina += $regProdBobina->longitud;

                    $id_contador++; }

                  $pdf->SetFont('arial','B',8);

                  $pdf->Cell(4,4,'',0,0,'C');
                  $pdf->Cell(73,4,'Operacion: '.$prodCalendario_operaciones[$regProdOperacion->cdgoperacion],0,0,'L');
                  $pdf->Cell(20,4,number_format($metrosBobina,2),1,1,'R',true);

                  $pdf->Ln(2); 
                }
              } // Ciclo
            }
          } // Ciclo
        }

        // Buscar rollos
        $link_mysqli = conectar();
        $prodCalendarioSelect= $link_mysqli->query("
          SELECT SUBSTR(prodrolloope.fchmovimiento,1,10) AS fchmovimiento
          FROM prodrollo,
            prodrolloope,
            prodoperacion,
            rechempleado
          WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
            prodrolloope.cdgempleado = rechempleado.cdgempleado AND        
    (SUBSTR(prodrolloope.fchmovimiento,1,10) BETWEEN '".$_GET['dsdfecha']."' AND '".$_GET['hstfecha']."') AND
            prodrollo.cdgrollo = prodrolloope.cdgrollo AND
            prodrolloope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodrolloope.fchmovimiento,1,10)");

        if ($prodCalendarioSelect->num_rows > 0)
        { $pdf->SetFont('arial','',8);    

          while($regProdCalendario = $prodCalendarioSelect->fetch_object())
          { $link_mysqli = conectar();
            $prodOperacionSelect = $link_mysqli->query("
              SELECT SUBSTR(prodrolloope.fchmovimiento,1,10) AS fchmovimiento,
                prodoperacion.cdgoperacion
              FROM prodrollo,
                prodrolloope,
                prodoperacion,
                rechempleado
              WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                prodrolloope.cdgempleado = rechempleado.cdgempleado AND        
         SUBSTR(prodrolloope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                prodrollo.cdgrollo = prodrolloope.cdgrollo AND
                prodrolloope.cdgoperacion = prodoperacion.cdgoperacion
              GROUP BY SUBSTR(prodrolloope.fchmovimiento,1,10),
                prodoperacion.cdgoperacion");

            if ($prodOperacionSelect->num_rows > 0)
            { while($regProdOperacion = $prodOperacionSelect->fetch_object())
              { $link_mysqli = conectar();
                $prodRolloSelect = $link_mysqli->query("
                  SELECT prodrolloope.fchmovimiento,
                    prodrolloope.cdgoperacion,
             CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) AS noop,
                    pdtoimpresion.impresion AS producto,
                    prodrollo.longitud,
                   (prodrollo.longitud/pdtoimpresion.corte) AS millares,
                    rechempleado.empleado
                  FROM prodlote,
                    prodbobina,
                    prodrollo,
                    prodrolloope,
                    prodoperacion,
                    pdtoimpresion,
                    rechempleado
                  WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                    prodrolloope.cdgempleado = rechempleado.cdgempleado AND        
             SUBSTR(prodrolloope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                    prodrolloope.cdgoperacion = '".$regProdOperacion->cdgoperacion."' AND
                    prodlote.cdglote = prodbobina.cdglote AND
                    prodbobina.cdgbobina = prodrollo.cdgbobina AND
                    prodrollo.cdgrollo = prodrolloope.cdgrollo AND
                    prodrolloope.cdgoperacion = prodoperacion.cdgoperacion AND
                    prodrollo.cdgproducto = pdtoimpresion.cdgimpresion
                  ORDER BY prodrolloope.fchmovimiento");

                if ($prodRolloSelect->num_rows > 0)
                { $pdf->SetFont('arial','B',8);

                  $pdf->Cell(4,4,'',0,0,'C');
                  $pdf->Cell(20,4,'NoOP',1,0,'C',true);
                  $pdf->Cell(53,4,'Producto',1,0,'C',true);
                  $pdf->Cell(20,4,'Metros',1,0,'C',true);
                  $pdf->Cell(20,4,'Millares',1,0,'C',true);
                  $pdf->Cell(50,4,'Empleado',1,0,'C',true);
                  $pdf->Cell(28,4,'Movimiento',1,1,'C',true);
                  
                  $id_contador = 1;
                  $metrosRollo = 0;
                  while($regProdRollo = $prodRolloSelect->fetch_object())
                  { $pdf->SetFont('arial','B',5); 
                    $pdf->Cell(4,4,$id_contador,0,0,'R');

                    $pdf->SetFont('arial','',8); 
                    $pdf->Cell(20,4,$regProdRollo->noop,1,0,'R');
                    $pdf->Cell(53,4,$regProdRollo->producto,1,0,'L');
                    $pdf->Cell(20,4,number_format($regProdRollo->longitud,2),1,0,'R');
                    $pdf->Cell(20,4,number_format($regProdRollo->millares,3),1,0,'R');
                    $pdf->Cell(50,4,$regProdRollo->empleado,1,0,'L');
                    $pdf->Cell(28,4,$regProdRollo->fchmovimiento,1,1,'L'); 

                    $metrosRollo += $regProdRollo->longitud;

                    $id_contador++; }

                  $pdf->SetFont('arial','B',8);

                  $pdf->Cell(4,4,'',0,0,'C');
                  $pdf->Cell(73,4,'Operacion: '.$prodCalendario_operaciones[$regProdOperacion->cdgoperacion],0,0,'L');
                  $pdf->Cell(20,4,number_format($metrosRollo,2),1,1,'R',true);

                  $pdf->Ln(2);
                }
              } // Ciclo
            }
          } // Ciclo
        }
      }

      if ($_SESSION['prodcalendario_empleado'] != '')
      { // Buscar lotes        
        $link_mysqli = conectar();
        $prodCalendarioSelect= $link_mysqli->query("
          SELECT SUBSTR(prodloteope.fchmovimiento,1,10) AS fchmovimiento
          FROM prodlote,
            prodloteope
          WHERE prodloteope.cdgempleado = '".$_GET['cdgempleado']."' AND
    (SUBSTR(prodloteope.fchmovimiento,1,10) BETWEEN '".$_GET['dsdfecha']."' AND '".$_GET['hstfecha']."') AND
            prodlote.cdglote = prodloteope.cdglote          
          GROUP BY SUBSTR(prodloteope.fchmovimiento,1,10)");

        if ($prodCalendarioSelect->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelect->fetch_object())
          { $link_mysqli = conectar();
            $prodOperacionSelect = $link_mysqli->query("
              SELECT SUBSTR(prodloteope.fchmovimiento,1,10) AS fchmovimiento,
                prodloteope.cdgoperacion
              FROM prodlote,
                prodloteope
              WHERE prodloteope.cdgempleado = '".$_GET['cdgempleado']."' AND
         SUBSTR(prodloteope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                prodlote.cdglote = prodloteope.cdglote                
              GROUP BY SUBSTR(prodloteope.fchmovimiento,1,10),
                prodloteope.cdgoperacion");

            if ($prodOperacionSelect->num_rows > 0)
            { while($regProdOperacion = $prodOperacionSelect->fetch_object())
              { $link_mysqli = conectar();
                $prodLoteSelect = $link_mysqli->query("
                  SELECT prodloteope.fchmovimiento,
                    prodloteope.cdgoperacion,
                    prodlote.noop,
                    pdtoimpresion.impresion AS producto,
                    prodlote.longitud,
                  ((prodlote.longitud*pdtoimpresion.alpaso)/pdtoimpresion.corte) AS millares,
                    prodmaquina.maquina
                  FROM prodlote,
                    prodloteope,
                    prodmaquina,
                    pdtoimpresion
                  WHERE prodloteope.cdgempleado = '".$_GET['cdgempleado']."' AND
             SUBSTR(prodloteope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                    prodloteope.cdgoperacion = '".$regProdOperacion->cdgoperacion."' AND
                    prodlote.cdglote = prodloteope.cdglote AND
                    prodloteope.cdgmaquina = prodmaquina.cdgmaquina AND
                    prodlote.cdgproducto = pdtoimpresion.cdgimpresion
                  ORDER BY prodloteope.fchmovimiento");

                if ($prodLoteSelect->num_rows > 0)
                { $pdf->SetFont('arial','B',8);

                  $pdf->Cell(4,4,'',0,0,'C');
                  $pdf->Cell(20,4,'NoOP',1,0,'C',true);
                  $pdf->Cell(53,4,'Producto',1,0,'C',true);
                  $pdf->Cell(20,4,'Metros',1,0,'C',true);
                  $pdf->Cell(20,4,'Millares',1,0,'C',true);
                  $pdf->Cell(50,4,'Maquina',1,0,'C',true);
                  $pdf->Cell(28,4,'Movimiento',1,1,'C',true);
                  
                  $id_contador = 1;
                  $metrosLote = 0;
                  while($regProdLote = $prodLoteSelect->fetch_object())
                  { $pdf->SetFont('arial','B',5); 
                    $pdf->Cell(4,4,$id_contador,0,0,'R');

                    $pdf->SetFont('arial','',8); 
                    $pdf->Cell(20,4,$regProdLote->noop,1,0,'R');
                    $pdf->Cell(53,4,$regProdLote->producto,1,0,'L');
                    $pdf->Cell(20,4,number_format($regProdLote->longitud,2),1,0,'R');
                    $pdf->Cell(20,4,number_format($regProdLote->millares,3),1,0,'R');
                    $pdf->Cell(50,4,$regProdLote->maquina,1,0,'L');
                    $pdf->Cell(28,4,$regProdLote->fchmovimiento,1,1,'L'); 

                    $metrosLote += $regProdLote->longitud;

                    $id_contador++; }

                  $pdf->SetFont('arial','B',8);

                  $pdf->Cell(4,4,'',0,0,'R');
                  $pdf->Cell(73,4,'Operacion: '.$prodCalendario_operaciones[$regProdOperacion->cdgoperacion],0,0,'L');
                  $pdf->Cell(20,4,number_format($metrosLote,2),1,1,'R',true);

                  $pdf->Ln(2);
                } 
              } // Ciclo
            }
          } // Ciclo
        } 
        
        // Buscar bobinas
        $link_mysqli = conectar();
        $prodCalendarioSelect= $link_mysqli->query("
          SELECT SUBSTR(prodbobinaope.fchmovimiento,1,10) AS fchmovimiento
          FROM prodbobina,
            prodbobinaope
          WHERE prodbobinaope.cdgempleado = '".$_GET['cdgempleado']."' AND        
           (SUBSTR(prodbobinaope.fchmovimiento,1,10) BETWEEN '".$_GET['dsdfecha']."' AND '".$_GET['hstfecha']."') AND
            prodbobina.cdgbobina = prodbobinaope.cdgbobina
          GROUP BY SUBSTR(prodbobinaope.fchmovimiento,1,10)");

        if ($prodCalendarioSelect->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelect->fetch_object())
          { $link_mysqli = conectar();
            $prodOperacionSelect = $link_mysqli->query("
              SELECT SUBSTR(prodbobinaope.fchmovimiento,1,10) AS fchmovimiento,
                prodbobinaope.cdgoperacion
              FROM prodbobina,
                prodbobinaope
              WHERE prodbobinaope.cdgempleado = '".$_GET['cdgempleado']."' AND
         SUBSTR(prodbobinaope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                prodbobina.cdgbobina = prodbobinaope.cdgbobina
              GROUP BY SUBSTR(prodbobinaope.fchmovimiento,1,10),
                prodbobinaope.cdgoperacion");

            if ($prodOperacionSelect->num_rows > 0)
            { while($regProdOperacion = $prodOperacionSelect->fetch_object())
              { $link_mysqli = conectar();
                $prodBobinaSelect = $link_mysqli->query("
                  SELECT prodbobinaope.fchmovimiento,
                    prodbobinaope.cdgoperacion,
             CONCAT(prodlote.noop,'-',prodbobina.bobina) AS noop,
                    pdtoimpresion.impresion AS producto,
                    prodbobina.longitud,
                   (prodbobina.longitud/pdtoimpresion.corte) AS millares,
                    prodmaquina.maquina
                  FROM prodlote,
                    prodbobina,
                    prodbobinaope,
                    prodmaquina,
                    pdtoimpresion
                  WHERE prodbobinaope.cdgempleado = '".$_GET['cdgempleado']."' AND
             SUBSTR(prodbobinaope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                    prodbobinaope.cdgoperacion = '".$regProdOperacion->cdgoperacion."' AND
                    prodlote.cdglote = prodbobina.cdglote AND
                    prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
                    prodbobinaope.cdgmaquina = prodmaquina.cdgmaquina AND
                    prodbobina.cdgproducto = pdtoimpresion.cdgimpresion
                  ORDER BY prodbobinaope.fchmovimiento"); 

                if ($prodBobinaSelect->num_rows > 0)
                { $pdf->SetFont('arial','B',8);

                  $pdf->Cell(4,4,'',0,0,'C');  
                  $pdf->Cell(20,4,'NoOP',1,0,'C',true);
                  $pdf->Cell(53,4,'Producto',1,0,'C',true);
                  $pdf->Cell(20,4,'Metros',1,0,'C',true);
                  $pdf->Cell(20,4,'Millares',1,0,'C',true);
                  $pdf->Cell(50,4,'Maquina',1,0,'C',true);
                  $pdf->Cell(28,4,'Movimiento',1,1,'C',true);
                  
                  $id_contador = 1;
                  $metrosBobina = 0;
                  while($regProdBobina = $prodBobinaSelect->fetch_object())
                  { $pdf->SetFont('arial','B',5); 
                    $pdf->Cell(4,4,$id_contador,0,0,'R');

                    $pdf->SetFont('arial','',8); 
                    $pdf->Cell(20,4,$regProdBobina->noop,1,0,'R');
                    $pdf->Cell(53,4,$regProdBobina->producto,1,0,'L');
                    $pdf->Cell(20,4,number_format($regProdBobina->longitud,2),1,0,'R');
                    $pdf->Cell(20,4,number_format($regProdBobina->millares,3),1,0,'R');
                    $pdf->Cell(50,4,$regProdBobina->maquina,1,0,'L');
                    $pdf->Cell(28,4,$regProdBobina->fchmovimiento,1,1,'L'); 

                    $metrosBobina += $regProdBobina->longitud;

                    $id_contador++; }
                  
                  $pdf->SetFont('arial','B',8);

                  $pdf->Cell(4,4,'',0,0,'R');
                  $pdf->Cell(73,4,'Operacion: '.$prodCalendario_operaciones[$regProdOperacion->cdgoperacion],0,0,'L');
                  $pdf->Cell(20,4,number_format($metrosBobina,2),1,1,'R',true);

                  $pdf->Ln(2);
                }
              } // Ciclo
            } 
          } // Ciclo
        }

        // Buscar rollos
        $link_mysqli = conectar();
        $prodCalendarioSelect = $link_mysqli->query("
          SELECT SUBSTR(prodrolloope.fchmovimiento,1,10) AS fchmovimiento
          FROM prodrollo,
            prodrolloope
          WHERE prodrollo.cdgrollo = prodrolloope.cdgrollo AND
            prodrolloope.cdgempleado = '".$_GET['cdgempleado']."' AND
    (SUBSTR(prodrolloope.fchmovimiento,1,10) BETWEEN '".$_GET['dsdfecha']."' AND '".$_GET['hstfecha']."')            
          GROUP BY SUBSTR(prodrolloope.fchmovimiento,1,10)");

        if ($prodCalendarioSelect->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelect->fetch_object())
          { $link_mysqli = conectar();
            $prodOperacionSelect = $link_mysqli->query("
              SELECT SUBSTR(prodrolloope.fchmovimiento,1,10) AS fchmovimiento,
                prodrolloope.cdgoperacion
              FROM prodrollo,
                prodrolloope
              WHERE prodrollo.cdgrollo = prodrolloope.cdgrollo AND
                prodrolloope.cdgempleado = '".$_GET['cdgempleado']."' AND
         SUBSTR(prodrolloope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."'
              GROUP BY SUBSTR(prodrolloope.fchmovimiento,1,10),
                prodrolloope.cdgoperacion");
            
            if ($prodOperacionSelect->num_rows > 0)
            { while($regProdOperacion = $prodOperacionSelect->fetch_object())
              { $link_mysqli = conectar();
                $prodRolloSelect = $link_mysqli->query("
                  SELECT prodrolloope.fchmovimiento,
                    prodrolloope.cdgoperacion,
             CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) AS noop,
                    pdtoimpresion.impresion AS producto,
                    prodrollo.longitud,
                   (prodrollo.longitud/pdtoimpresion.corte) AS millares,
                    prodmaquina.maquina
                  FROM prodlote,
                    prodbobina,
                    prodrollo,
                    prodrolloope,
                    prodmaquina,
                    pdtoimpresion
                  WHERE prodrolloope.cdgempleado = '".$_GET['cdgempleado']."' AND
             SUBSTR(prodrolloope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                    prodrolloope.cdgoperacion = '".$regProdOperacion->cdgoperacion."' AND
                    prodlote.cdglote = prodbobina.cdglote AND
                    prodbobina.cdgbobina = prodrollo.cdgbobina AND
                    prodrollo.cdgrollo = prodrolloope.cdgrollo AND
                    prodrolloope.cdgmaquina = prodmaquina.cdgmaquina AND
                    prodrollo.cdgproducto = pdtoimpresion.cdgimpresion
                  ORDER BY prodrolloope.fchmovimiento");

                if ($prodRolloSelect->num_rows > 0)
                { $pdf->SetFont('arial','B',8);

                  $pdf->Cell(4,4,'',0,0,'C');
                  $pdf->Cell(20,4,'NoOP',1,0,'C',true);
                  $pdf->Cell(53,4,'Producto',1,0,'C',true);
                  $pdf->Cell(20,4,'Metros',1,0,'C',true);
                  $pdf->Cell(20,4,'Millares',1,0,'C',true);
                  $pdf->Cell(50,4,'Maquina',1,0,'C',true);
                  $pdf->Cell(28,4,'Movimiento',1,1,'C',true);
                  
                  $id_contador = 1;
                  $metrosRollo = 0;
                  while($regProdRollo = $prodRolloSelect->fetch_object())
                  { $pdf->SetFont('arial','B',5); 
                    $pdf->Cell(4,4,$id_contador,0,0,'R');

                    $pdf->SetFont('arial','',8); 
                    $pdf->Cell(20,4,$regProdRollo->noop,1,0,'R');
                    $pdf->Cell(53,4,$regProdRollo->producto,1,0,'L');
                    $pdf->Cell(20,4,number_format($regProdRollo->longitud,2),1,0,'R');
                    $pdf->Cell(20,4,number_format($regProdRollo->millares,3),1,0,'R');
                    $pdf->Cell(50,4,$regProdRollo->maquina,1,0,'L');
                    $pdf->Cell(28,4,$regProdRollo->fchmovimiento,1,1,'L');

                    $metrosRollo += $regProdRollo->longitud;

                    $id_contador++; } 

                  $pdf->SetFont('arial','B',8); 

                  $pdf->Cell(4,4,'',0,0,'R');
                  $pdf->Cell(73,4,'Operacion: '.$prodCalendario_operaciones[$regProdOperacion->cdgoperacion],0,0,'L');
                  $pdf->Cell(20,4,number_format($metrosRollo,2),1,1,'R',true);
                  
                  $pdf->Ln(2);
                } 
              } // Ciclo
            }
          } // Ciclo
        }
      }
    }
  }

  $pdf->Output('Reporte de produccion del '.$_GET['dsdfecha'].' al '.$_GET['hstfecha'].' '.$_SESSION['prodcalendario_subtitulo'].'.pdf', 'D');
  
?>
