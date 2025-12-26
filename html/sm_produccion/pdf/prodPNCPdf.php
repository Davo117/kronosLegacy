<?php

  include('../../datos/mysql.php');
  require('../../fpdf/fpdf.php');
  
  // Catalogo de procesos
  $link_mysqli = conectar();
  $prodOperacionSelect = $link_mysqli->query("
    SELECT * FROM prodproceso
    ORDER BY idproceso");

  if ($prodProcesoSelect->num_rows > 0)
  { while ($regProdProceso = $prodProcesoSelect->fetch_object())
    { //$prodPNC_procesos[$regProdProceso->cdgproceso] = $regProdProceso->proceso;

      if ($_GET['cdgproceso'] == $regProdProceso->cdgproceso)
      { $_SESSION['prodPNC_proceso'] = $regProdProceso->proceso;
          
        $prodPNC_proceso = $regProdProceso->proceso;
        $prodPNC_rccode = $regProdProceso->rccode;
        $prodPNC_cdgproceso = $regProdProceso->cdgproceso; }
    }
  }

  class PDF extends FPDF
  { function Header()
    { if ($_SESSION['usuario'] == '')
      { $_SESSION['usuario'] = 'Invitado'; }

      if (file_exists('../../img_sistema/logo.jpg')==true)
      { $this->Image('../../img_sistema/logo.jpg',10,7,0,10); }

      $this->SetY(5);
      $this->SetFont('arial','B',8);

      $this->Cell(0,4,'Usuario: '.$_SESSION['usuario'],0,1,'R');
      $this->Cell(0,4,utf8_decode('Reporte de Producto No Conforme en producción del '.$_GET['dsdfecha'].' al '.$_GET['hstfecha']),0,1,'R');


      $this->Cell(0,4,utf8_decode('Proceso: '.$_SESSION['prodPNC_proceso']),0,1,'R');
      $this->Ln(2);
    }
  }

  $pdf=new PDF('L','mm','letter');
  $pdf->AliasNbPages();
  $pdf->SetDisplayMode(real, continuous);
  
  $pdf->SetFillColor(180,180,180);
  $pdf->AddPage();

 
 
 
 
  // Catalogo de operaciones
  $link_mysqli = conectar();
  $prodOperacionSelect = $link_mysqli->query("
    SELECT * FROM prodoperacion
    ORDER BY idoperacion");

  if ($prodOperacionSelect->num_rows > 0)
  { while ($regProdOperacion = $prodOperacionSelect->fetch_object())
    { $prodPNC_operacion[$regProdOperacion->cdgoperacion] = $regProdOperacion->operacion; }
  }

  // Catalogo de maquinas
  $link_mysqli = conectar();
  $prodMaquinaSelect = $link_mysqli->query("
    SELECT * FROM prodmaquina
    ORDER BY idmaquina");

  if ($prodMaquinaSelect->num_rows > 0)
  { while ($regProdMaquina = $prodMaquinaSelect->fetch_object())
    { $prodPNC_maquinas[$regProdMaquina->cdgmaquina] = $regProdMaquina->maquina; }
  }

  // Catalogo de empleados
  $link_mysqli = conectar();
  $rechEmpleadoSelect = $link_mysqli->query("
    SELECT * FROM rechempleado
    ORDER BY empleado");

  if ($rechEmpleadoSelect->num_rows > 0)
  { while ($regRechEmpleado = $rechEmpleadoSelect->fetch_object())
    { $prodPNC_empleados[$regRechEmpleado->cdgempleado] = $regRechEmpleado->empleado; }
  }

  // Catalogo de productos (Impresion)
  $link_mysqli = conectar();
  $pdtoImpresionSelect = $link_mysqli->query("
    SELECT * FROM pdtoimpresion
    ORDER BY cdgimpresion");

  if ($pdtoImpresionSelect->num_rows > 0)
  { while ($regPdtoImpresion = $pdtoImpresionSelect->fetch_object())
    { $prodPNC_productos[$regPdtoImpresion->cdgimpresion] = $regPdtoImpresion->impresion; }
  }

      if ($_SESSION['prodPNC_proceso'] != '')
      { // Proceso Activo
        if ($_SESSION['prodPNC_empleado'] != '')
        { // Empleado Activo
          if ($_SESSION['prodPNC_producto'] != '')
          { // Producto Activo
            $_SESSION['prodPNC_subtitulo'] = 'Proceso: '.$_SESSION['prodPNC_proceso'].' / Empleado: '.$_SESSION['prodPNC_empleado'].' / Producto: '.$_SESSION['prodPNC_producto'];

            // Buscar bobinas
            $link_mysqli = conectar();
            $prodCalendarioSelect= $link_mysqli->query("
              SELECT SUBSTR(prodbobinaope.fchmovimiento,1,10) AS fchmovimiento
              FROM prodbobina,
                prodbobinaope,
                prodoperacion
              WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                prodbobinaope.cdgempleado = '".$_GET['cdgempleado']."' AND
                prodbobina.cdgproducto = '".$_GET['cdgproducto']."' AND
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
                    prodbobina.cdgproducto = '".$_GET['cdgproducto']."' AND
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
                        prodbobinaope.cdgmaquina,
                 CONCAT(prodlote.noop,'-',prodbobina.bobina) AS noop,
                        pdtoimpresion.impresion AS producto,
                        prodbobina.longitud,
                       (prodbobina.longitud/pdtodiseno.alto) AS millares
                      FROM prodlote,
                        prodbobina,
                        prodbobinaope,
                        prodoperacion,
                        pdtoimpresion,
                        pdtodiseno
                      WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                        prodbobinaope.cdgempleado = '".$_GET['cdgempleado']."' AND
                        prodbobina.cdgproducto = '".$_GET['cdgproducto']."' AND
                 SUBSTR(prodbobinaope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                        prodbobinaope.cdgoperacion = '".$regProdOperacion->cdgoperacion."' AND
                        prodlote.cdglote = prodbobina.cdglote AND
                        prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
                        prodbobinaope.cdgoperacion = prodoperacion.cdgoperacion AND
                        prodbobina.cdgproducto = pdtoimpresion.cdgimpresion AND
                        pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno
                      ORDER BY prodbobinaope.fchmovimiento");

                      $pdf->SetFont('arial','B',8);

                    if ($prodBobinaSelect->num_rows > 0)
                    {
                      $pdf->Cell(4,4,'',0,0,'C');
                      $pdf->Cell(20,4,'NoOP',1,0,'C',true);
                      $pdf->Cell(53,4,'Producto',1,0,'C',true);
                      $pdf->Cell(53,4,'Maquina',1,0,'C',true);
                      $pdf->Cell(20,4,'Metros',1,0,'C',true);
                      $pdf->Cell(20,4,'Millares',1,0,'C',true);
                      $pdf->Cell(28,4,'Movimiento',1,1,'C',true);

                      $id_contador = 1;
                      $metrosBobina = 0;
                      $millaresBobina = 0;
                      while($regProdBobina = $prodBobinaSelect->fetch_object())
                      { $pdf->SetFont('arial','B',5);
                        $pdf->Cell(4,4,$id_contador,0,0,'R');

                        $pdf->SetFont('arial','',8);
                        $pdf->Cell(20,4,$regProdBobina->noop,1,0,'R');
                        $pdf->Cell(53,4,$regProdBobina->producto,1,0,'L');
                        $pdf->Cell(53,4,$prodPNC_maquinas[$regProdBobina->cdgmaquina],1,0,'L');
                        $pdf->Cell(20,4,number_format($regProdBobina->longitud,2),1,0,'R');
                        $pdf->Cell(20,4,number_format($regProdBobina->millares,3),1,0,'R');
                        $pdf->Cell(28,4,$regProdBobina->fchmovimiento,1,1,'L');

                        $metrosBobina += $regProdBobina->longitud;
                        $millaresBobina += $regProdBobina->millares;

                        $id_contador++; }

                      $pdf->SetFont('arial','B',8);

                      $pdf->Cell(4,4,'',0,0,'C');
                      $pdf->Cell(126,4,'Operacion: '.$prodPNC_operacion[$regProdOperacion->cdgoperacion],0,0,'L');
                      $pdf->Cell(20,4,number_format($metrosBobina,2),1,0,'R',true);
                      $pdf->Cell(20,4,number_format($millaresBobina,2),1,1,'R',true);

                      $pdf->Ln(2);
                    }
                  }
                }
              }
            }

            // Buscar Rollos
            $link_mysqli = conectar();
            $prodCalendarioSelect= $link_mysqli->query("
              SELECT SUBSTR(prodrolloope.fchmovimiento,1,10) AS fchmovimiento,
                SUM(prodrollo.longitud) AS metros
              FROM prodrollo,
                prodrolloope,
                prodoperacion
              WHERE prodrolloope.cdgempleado = '".$_GET['cdgempleado']."' AND
                prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                prodrollo.cdgproducto = '".$_GET['cdgproducto']."' AND
               (SUBSTR(prodrolloope.fchmovimiento,1,10) BETWEEN '".$_GET['dsdfecha']."' AND '".$_GET['hstfecha']."') AND
                prodrollo.cdgrollo = prodrolloope.cdgrollo AND
                prodrolloope.cdgoperacion = prodoperacion.cdgoperacion
              GROUP BY SUBSTR(prodrolloope.fchmovimiento,1,10),
                prodoperacion.cdgproceso");

            if ($prodCalendarioSelect->num_rows > 0)
            { while($regProdCalendario = $prodCalendarioSelect->fetch_object())
              { $link_mysqli = conectar();
                $prodOperacionSelect = $link_mysqli->query("
                  SELECT SUBSTR(prodrolloope.fchmovimiento,1,10) AS fchmovimiento,
                    prodoperacion.cdgoperacion
                  FROM prodrollo,
                    prodrolloope,
                    prodoperacion
                  WHERE prodrolloope.cdgempleado = '".$_GET['cdgempleado']."' AND
                    prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                    prodrollo.cdgproducto = '".$_GET['cdgproducto']."' AND
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
                        prodrolloope.cdgmaquina,
                 CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) AS noop,
                        pdtoimpresion.impresion AS producto,
                        prodrollo.longitud,
                       (prodrollo.longitud/pdtodiseno.alto) AS millares
                      FROM prodlote,
                        prodbobina,
                        prodrollo,
                        prodrolloope,
                        prodoperacion,
                        pdtoimpresion,
                        pdtodiseno
                      WHERE prodrolloope.cdgempleado = '".$_GET['cdgempleado']."' AND
                        prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                        prodrollo.cdgproducto = '".$_GET['cdgproducto']."' AND
                 SUBSTR(prodrolloope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                        prodrolloope.cdgoperacion = '".$regProdOperacion->cdgoperacion."' AND
                        prodlote.cdglote = prodbobina.cdglote AND
                        prodbobina.cdgbobina = prodrollo.cdgbobina AND
                        prodrollo.cdgrollo = prodrolloope.cdgrollo AND
                        prodrolloope.cdgoperacion = prodoperacion.cdgoperacion AND
                        prodrollo.cdgproducto = pdtoimpresion.cdgimpresion AND
                        pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno
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
                      $millaresRollo = 0;
                      while($regProdRollo = $prodRolloSelect->fetch_object())
                      { if (number_format($regProdRollo->longitud,2) > 0)
                        { $pdf->SetFont('arial','B',5);
                          $pdf->Cell(4,4,$id_contador,0,0,'R');

                          $pdf->SetFont('arial','',8);
                          $pdf->Cell(20,4,$regProdRollo->noop,1,0,'R');
                          $pdf->Cell(53,4,$regProdRollo->producto,1,0,'L');
                          $pdf->Cell(20,4,number_format($regProdRollo->longitud,2),1,0,'R');
                          $pdf->Cell(20,4,number_format($regProdRollo->millares,3),1,0,'R');
                          $pdf->Cell(28,4,$regProdRollo->fchmovimiento,1,1,'L');

                          $metrosRollo += $regProdRollo->longitud;
                          $millaresRollo += $regProdRollo->millares;

                          $id_contador++; }
                      }

                      $pdf->SetFont('arial','B',8);
                      $pdf->Cell(4,4,'',0,0,'C');
                      $pdf->Cell(73,4,'Operacion: '.$prodPNC_operacion[$regProdOperacion->cdgoperacion],0,0,'L');
                      $pdf->Cell(20,4,number_format($metrosRollo,2),1,0,'R',true);
                      $pdf->Cell(20,4,number_format($millaresRollo,2),1,1,'R',true);

                      $pdf->Ln(2);
                    }
                  }
                }
              }
            }

          } else
          { // Producto Nulo
            $_SESSION['prodPNC_subtitulo'] = 'Proceso: '.$_SESSION['prodPNC_proceso'].' / Empleado: '.$_SESSION['prodPNC_empleado'];

            $link_mysqli = conectar();
            $prodOperacionSelect = $link_mysqli->query("
              SELECT SUBSTR(prodloteope.fchmovimiento,1,10) AS fchmovimiento,
                prodoperacion.cdgoperacion
              FROM prodlote,
                prodloteope,
                prodoperacion
              WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                prodloteope.cdgempleado = '".$_GET['cdgempleado']."' AND
                     (SUBSTR(prodloteope.fchmovimiento,1,10) BETWEEN '".$_GET['dsdfecha']."' AND '".$_GET['hstfecha']."') AND
                prodlote.cdglote = prodloteope.cdglote AND
                prodloteope.cdgoperacion = prodoperacion.cdgoperacion
              GROUP BY SUBSTR(prodloteope.fchmovimiento,1,10),
                prodoperacion.cdgoperacion
                    ORDER BY prodoperacion.cdgoperacion,
                      SUBSTR(prodloteope.fchmovimiento,1,10)");

  if ($prodOperacionSelect->num_rows > 0)
  { while($regProdOperacion = $prodOperacionSelect->fetch_object())
    { $link_mysqli = conectar();
            $prodCalendarioSelect= $link_mysqli->query("
              SELECT SUBSTR(prodloteope.fchmovimiento,1,10) AS fchmovimiento
              FROM prodlote,
                prodloteope,
                prodoperacion
              WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                prodloteope.cdgempleado = '".$_GET['cdgempleado']."' AND
     SUBSTR(prodloteope.fchmovimiento,1,10) = '".$regProdOperacion->fchmovimiento."' AND
                prodlote.cdglote = prodloteope.cdglote AND
                prodloteope.cdgoperacion = '".$regProdOperacion->cdgoperacion."'
              GROUP BY SUBSTR(prodloteope.fchmovimiento,1,10)");

            if ($prodCalendarioSelect->num_rows > 0)
            { while($regProdCalendario = $prodCalendarioSelect->fetch_object())
              { $link_mysqli = conectar();
                    $prodLoteSelect = $link_mysqli->query("
                      SELECT prodloteope.fchmovimiento,
                        prodloteope.cdgoperacion,
                        prodlote.noop,
                        pdtoimpresion.impresion AS producto,
                        prodlote.longitud,
                      ((prodlote.longitud*pdtodiseno.alpaso)/pdtodiseno.alto) AS millares
                      FROM prodlote,
                        prodloteope,
                        prodoperacion,
                        pdtoimpresion,
                        pdtodiseno
                      WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                        prodloteope.cdgempleado = '".$_GET['cdgempleado']."' AND
                 SUBSTR(prodloteope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                        prodloteope.cdgoperacion = '".$regProdOperacion->cdgoperacion."' AND
                        prodlote.cdglote = prodloteope.cdglote AND
                        prodloteope.cdgoperacion = prodoperacion.cdgoperacion AND
                        prodlote.cdgproducto = pdtoimpresion.cdgimpresion AND
                        pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno
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
                      $millaresLote = 0;
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
                        $millaresLote += $regProdLote->millares;

                        $id_contador++;
                      }

                      $pdf->SetFont('arial','B',8);

                      $pdf->Cell(4,4,'',0,0,'C');
                      $pdf->Cell(73,4,'Operacion: '.$prodPNC_operacion[$regProdOperacion->cdgoperacion],0,0,'L');
                      $pdf->Cell(20,4,number_format($metrosLote,2),1,0,'R',true);
                      $pdf->Cell(20,4,number_format($millaresLote,2),1,1,'R',true);

                      $pdf->Ln(2);
                    }
                  }
                }
              }
            }
            /*
            $pdf->SetFont('arial','B',8);

            for ($id=1;$id<=$idoperacion;$id++) {
              $pdf->Cell(4,4,'',0,0,'C');
              $pdf->Cell(73,4,'De '.$sumaMetrosDscRptIn[$idoperacion].' a '.$sumaMetrosDscRptOut[$idoperacion+1],0,0,'L');
              $pdf->Cell(20,4,number_format($sumaMetrosRptIn[$idoperacion],2),1,0,'R',true);
              $pdf->Cell(20,4,number_format($sumaMetrosRptOut[$idoperacion],2),1,1,'R',true); }

            $pdf->Ln(2); //*/

            // Buscar bobinas
            $link_mysqli = conectar();
                $prodOperacionSelect = $link_mysqli->query("
                  SELECT SUBSTR(prodbobinaope.fchmovimiento,1,10) AS fchmovimiento,
                    prodoperacion.cdgoperacion
                  FROM prodbobina,
                    prodbobinaope,
                    prodoperacion
                  WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                    prodbobinaope.cdgempleado = '".$_GET['cdgempleado']."' AND
               (SUBSTR(prodbobinaope.fchmovimiento,1,10) BETWEEN '".$_GET['dsdfecha']."' AND '".$_GET['hstfecha']."') AND
                    prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
                    prodbobinaope.cdgoperacion = prodoperacion.cdgoperacion
                  GROUP BY SUBSTR(prodbobinaope.fchmovimiento,1,10),
                    prodoperacion.cdgoperacion
                  ORDER BY prodoperacion.cdgoperacion,
                    SUBSTR(prodbobinaope.fchmovimiento,1,10)");

                if ($prodOperacionSelect->num_rows > 0)
                { while($regProdOperacion = $prodOperacionSelect->fetch_object())
                  { $link_mysqli = conectar();
            $prodCalendarioSelect= $link_mysqli->query("
              SELECT SUBSTR(prodbobinaope.fchmovimiento,1,10) AS fchmovimiento
              FROM prodbobina,
                prodbobinaope,
                prodoperacion
              WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                prodbobinaope.cdgempleado = '".$_GET['cdgempleado']."' AND
             SUBSTR(prodbobinaope.fchmovimiento,1,10) = '".$regProdOperacion->fchmovimiento."' AND
                prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
                prodbobinaope.cdgoperacion = '".$regProdOperacion->cdgoperacion."'
              GROUP BY SUBSTR(prodbobinaope.fchmovimiento,1,10)");

            if ($prodCalendarioSelect->num_rows > 0)
            { while($regProdCalendario = $prodCalendarioSelect->fetch_object())
              { $link_mysqli = conectar();
                    $prodBobinaSelect = $link_mysqli->query("
                      SELECT prodbobinaope.fchmovimiento,
                        prodbobinaope.cdgoperacion,
                 CONCAT(prodlote.noop,'-',prodbobina.bobina) AS noop,
                        pdtoimpresion.impresion AS producto,
                        prodbobina.longitud,
                       (prodbobina.longitud/pdtodiseno.alto) AS millares,
                        prodbobinaope.peso,
                        prodbobinaope.merma
                      FROM prodlote,
                        prodbobina,
                        prodbobinaope,
                        prodoperacion,
                        pdtoimpresion,
                        pdtodiseno
                      WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                        prodbobinaope.cdgempleado = '".$_GET['cdgempleado']."' AND
                 SUBSTR(prodbobinaope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                        prodbobinaope.cdgoperacion = '".$regProdOperacion->cdgoperacion."' AND
                        prodlote.cdglote = prodbobina.cdglote AND
                        prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
                        prodbobinaope.cdgoperacion = prodoperacion.cdgoperacion AND
                        prodbobina.cdgproducto = pdtoimpresion.cdgimpresion AND
                        pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno
                      ORDER BY prodbobinaope.fchmovimiento");

                      $pdf->SetFont('arial','B',8);

                    if ($prodBobinaSelect->num_rows > 0)
                    { $pdf->Cell(4,4,'',0,0,'C');
                      $pdf->Cell(20,4,'NoOP',1,0,'C',true);
                      $pdf->Cell(53,4,'Producto',1,0,'C',true);
                      $pdf->Cell(20,4,'Metros',1,0,'C',true);
                      $pdf->Cell(20,4,'Millares',1,0,'C',true);
                      $pdf->Cell(20,4,'Peso',1,0,'C',true);
                      $pdf->Cell(20,4,'Merma',1,0,'C',true);
                      $pdf->Cell(28,4,'Movimiento',1,1,'C',true);

                      $id_contador = 1;
                      $metrosBobina = 0;
                      $millaresBobina = 0;
                      while($regProdBobina = $prodBobinaSelect->fetch_object())
                      { $pdf->SetFont('arial','B',5);
                        $pdf->Cell(4,4,$id_contador,0,0,'R');

                        $pdf->SetFont('arial','',8);
                        $pdf->Cell(20,4,$regProdBobina->noop,1,0,'R');
                        $pdf->Cell(53,4,$regProdBobina->producto,1,0,'L');
                        $pdf->Cell(20,4,number_format($regProdBobina->longitud,2),1,0,'R');
                        $pdf->Cell(20,4,number_format($regProdBobina->millares,3),1,0,'R');
                        $pdf->Cell(20,4,number_format($regProdBobina->peso,3),1,0,'R');
                        $pdf->Cell(20,4,number_format($regProdBobina->merma,3),1,0,'R');
                        $pdf->Cell(28,4,$regProdBobina->fchmovimiento,1,1,'L');

                        $metrosBobina += $regProdBobina->longitud;
                        $millaresBobina += $regProdBobina->millares;
                        $pesoBobinas += $regProdBobina->peso;
                        $mermaBobinas += $regProdBobina->merma;

                        $sumaOperacionB[$regProdOperacion->cdgoperacion] += $regProdBobina->longitud;

                        $id_contador++;
                    }

                      $pdf->SetFont('arial','B',8);

                      $pdf->Cell(4,4,'',0,0,'C');
                      $pdf->Cell(73,4,'Operacion: '.$prodPNC_operacion[$regProdOperacion->cdgoperacion],0,0,'L');
                      $pdf->Cell(20,4,number_format($metrosBobina,2),1,0,'R',true);
                      $pdf->Cell(20,4,number_format($millaresBobina,3),1,0,'R',true);
                      $pdf->Cell(20,4,number_format($pesoBobinas,3),1,0,'R',true);
                      $pdf->Cell(20,4,number_format($mermaBobinas,3),1,0,'R',true);
                      $pdf->Cell(28,4,number_format((($mermaBobinas*100)/$pesoBobinas),3).' %',1,1,'R');

                      $pdf->Ln(2);
                    }
                  }
                }

                $pdf->Cell(20,4,number_format($sumaOperacionB[$regProdOperacion->cdgoperacion],2),1,1,'R',true);
              }
            }

            // Buscar rollos
            $link_mysqli = conectar();
                $prodOperacionSelect = $link_mysqli->query("
                  SELECT SUBSTR(prodrolloope.fchmovimiento,1,10) AS fchmovimiento,
                    prodoperacion.cdgoperacion
                  FROM prodrollo,
                    prodrolloope,
                    prodoperacion
                  WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                    prodrolloope.cdgempleado = '".$_GET['cdgempleado']."' AND
        (SUBSTR(prodrolloope.fchmovimiento,1,10) BETWEEN '".$_GET['dsdfecha']."' AND '".$_GET['hstfecha']."') AND
                    prodrollo.cdgrollo = prodrolloope.cdgrollo AND
                    prodrolloope.cdgoperacion = prodoperacion.cdgoperacion
                  GROUP BY SUBSTR(prodrolloope.fchmovimiento,1,10),
                    prodoperacion.cdgoperacion
                  ORDER BY prodoperacion.cdgoperacion,
                    SUBSTR(prodrolloope.fchmovimiento,1,10)");

                if ($prodOperacionSelect->num_rows > 0)
                { while($regProdOperacion = $prodOperacionSelect->fetch_object())
                  { $link_mysqli = conectar();
            $prodCalendarioSelect= $link_mysqli->query("
              SELECT SUBSTR(prodrolloope.fchmovimiento,1,10) AS fchmovimiento
              FROM prodrollo,
                prodrolloope,
                prodoperacion
              WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                prodrolloope.cdgempleado = '".$_GET['cdgempleado']."' AND
             SUBSTR(prodrolloope.fchmovimiento,1,10) = '".$regProdOperacion->fchmovimiento."' AND
                prodrollo.cdgrollo = prodrolloope.cdgrollo AND
                prodrolloope.cdgoperacion = '".$regProdOperacion->cdgoperacion."'
              GROUP BY SUBSTR(prodrolloope.fchmovimiento,1,10)");

            if ($prodCalendarioSelect->num_rows > 0)
            { while($regProdCalendario = $prodCalendarioSelect->fetch_object())
              { $link_mysqli = conectar();
                    $prodRolloSelect = $link_mysqli->query("
                      SELECT prodrolloope.fchmovimiento,
                        prodrolloope.cdgoperacion,
                 CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) AS noop,
                        pdtoimpresion.impresion AS producto,
                        prodrollo.longitud,
                       (prodrollo.longitud/pdtodiseno.alto) AS millares,
                        prodrolloope.peso
                      FROM prodlote,
                        prodbobina,
                        prodrollo,
                        prodrolloope,
                        prodoperacion,
                        pdtoimpresion,
                        pdtodiseno
                      WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                        prodrolloope.cdgempleado = '".$_GET['cdgempleado']."' AND
                 SUBSTR(prodrolloope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                        prodrolloope.cdgoperacion = '".$regProdOperacion->cdgoperacion."' AND
                        prodlote.cdglote = prodbobina.cdglote AND
                        prodbobina.cdgbobina = prodrollo.cdgbobina AND
                        prodrollo.cdgrollo = prodrolloope.cdgrollo AND
                        prodrolloope.cdgoperacion = prodoperacion.cdgoperacion AND
                        prodrollo.cdgproducto = pdtoimpresion.cdgimpresion AND
                        pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno
                      ORDER BY prodrolloope.fchmovimiento");

                    if ($prodRolloSelect->num_rows > 0)
                    { $pdf->SetFont('arial','B',8);

                      $pdf->Cell(4,4,'',0,0,'C');
                      $pdf->Cell(20,4,'NoOP',1,0,'C',true);
                      $pdf->Cell(53,4,'Producto',1,0,'C',true);
                      $pdf->Cell(20,4,'Metros',1,0,'C',true);
                      $pdf->Cell(20,4,'Millares',1,0,'C',true);
                      $pdf->Cell(20,4,'Peso',1,0,'C',true);
                      $pdf->Cell(20,4,'Merma',1,0,'C',true);
                      $pdf->Cell(28,4,'Movimiento',1,1,'C',true);

                      $id_contador = 1;
                      $metrosRollo = 0;
                      $millaresRollo = 0;
                      while($regProdRollo = $prodRolloSelect->fetch_object())
                      { $pdf->SetFont('arial','B',5);
                        $pdf->Cell(4,4,$id_contador,0,0,'R');

                        $pdf->SetFont('arial','',8);
                        $pdf->Cell(20,4,$regProdRollo->noop,1,0,'R');
                        $pdf->Cell(53,4,$regProdRollo->producto,1,0,'L');
                        $pdf->Cell(20,4,number_format($regProdRollo->longitud,2),1,0,'R');
                        $pdf->Cell(20,4,number_format($regProdRollo->millares,3),1,0,'R');
                        $pdf->Cell(20,4,number_format($regProdRollo->peso,3),1,0,'R');
                        $pdf->Cell(20,4,number_format($regProdRollo->merma,3),1,0,'R');
                        $pdf->Cell(28,4,$regProdRollo->fchmovimiento,1,1,'L');

                        $metrosRollo += $regProdRollo->longitud;
                        $millaresRollo += $regProdRollo->millares;
                        $pesoRollos += $regProdRollo->peso;
                        $mermaRollos += $regProdRollo->merma;


                        $sumaOperacionR[$regProdOperacion->cdgoperacion] += $regProdRollo->longitud;

                        $id_contador++; }

                      $pdf->SetFont('arial','B',8);
                      $pdf->Cell(4,4,'',0,0,'C');
                      $pdf->Cell(73,4,'Operacion: '.$prodPNC_operacion[$regProdOperacion->cdgoperacion],0,0,'L');
                      $pdf->Cell(20,4,number_format($metrosRollo,2),1,0,'R',true);
                      $pdf->Cell(20,4,number_format($millaresRollo,2),1,0,'R',true);
                      $pdf->Cell(20,4,number_format($pesoRollos,3),1,0,'R',true);
                      $pdf->Cell(20,4,number_format($mermaRollos,3),1,0,'R',true);
                      $pdf->Cell(28,4,number_format((($mermaRollos*100)/$pesoRollos),3).' %',1,1,'R');

                      $pdf->Ln(2);
                    }
                  }
                }
                  $pdf->Cell(20,4,number_format($sumaOperacionR[$regProdOperacion->cdgoperacion],2),1,1,'R',true);
              }
            }


          }
        } else
        { // Empleado Nulo
          if ($_SESSION['prodPNC_producto'] != '')
          { // Producto Activo
            $_SESSION['prodPNC_subtitulo'] = 'Proceso: '.$_SESSION['prodPNC_proceso'].' / Producto: '.$_SESSION['prodPNC_producto'];

            $link_mysqli = conectar();
            $prodLoteOpe_RangoFechas = $link_mysqli->query("
              SELECT SUBSTR(prodloteope.fchmovimiento,1,10) AS fchmovimiento
              FROM prodlote,
                prodloteope,
                prodoperacion
              WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                prodlote.cdgproducto = '".$_GET['cdgproducto']."' AND
        (SUBSTR(prodloteope.fchmovimiento,1,10) BETWEEN '".$_GET['dsdfecha']."' AND '".$_GET['hstfecha']."') AND
                prodlote.cdglote = prodloteope.cdglote AND
                prodloteope.cdgoperacion = prodoperacion.cdgoperacion
              GROUP BY SUBSTR(prodloteope.fchmovimiento,1,10)");

            if ($prodLoteOpe_RangoFechas->num_rows > 0)
            { $id_fecha = 1;
              while ($regFchMovimiento = $prodLoteOpe_RangoFechas->fetch_object())
              { $rptProduccion_fchmovimiento[$id_fecha] = $regFchMovimiento->fchmovimiento;

                $id_fecha++; }

              $num_fechas = $prodLoteOpe_RangoFechas->num_rows;

              $link_mysqli = conectar();
              $prodLoteOpe_Operaciones = $link_mysqli->query("
                SELECT prodoperacion.cdgoperacion
                FROM prodlote,
                  prodloteope,
                  prodoperacion
                WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                  prodlote.cdgproducto = '".$_GET['cdgproducto']."' AND
          (SUBSTR(prodloteope.fchmovimiento,1,10) BETWEEN '".$_GET['dsdfecha']."' AND '".$_GET['hstfecha']."') AND
                  prodlote.cdglote = prodloteope.cdglote AND
                  prodloteope.cdgoperacion = prodoperacion.cdgoperacion
                GROUP BY prodoperacion.cdgoperacion");

              if ($prodLoteOpe_Operaciones->num_rows > 0)
              { while($regOperacion = $prodLoteOpe_Operaciones->fetch_object())
                { $id_fecha = 0;

                  $metrosorigenOperacion = 0;
                  $millaresorigenOperacion = 0;
                  $metrosOperacion = 0;
                  $millaresOperacion = 0;

                  for ($id_fecha = 1; $id_fecha <= $num_fechas; $id_fecha++)
                  { $link_mysqli = conectar();
                    $prodLoteSelect = $link_mysqli->query("
                      SELECT prodloteope.fchmovimiento,
                        prodloteope.cdgoperacion,
                        prodlote.noop,
                        pdtoimpresion.impresion AS producto,
                        proglote.peso AS peso,
                        proglote.longitud AS longitud_origen,
                      ((proglote.longitud*pdtodiseno.alpaso)/pdtodiseno.alto) AS millares_origen,
                        prodlote.longitud,
                      ((prodlote.longitud*pdtodiseno.alpaso)/pdtodiseno.alto) AS millares,
                        rechempleado.empleado
                      FROM proglote,
                        prodlote,
                        prodloteope,
                        prodoperacion,
                        pdtoimpresion,
                        pdtodiseno,
                        rechempleado
                      WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                        prodlote.cdgproducto = '".$_GET['cdgproducto']."' AND
                        prodloteope.cdgempleado = rechempleado.cdgempleado AND
                 SUBSTR(prodloteope.fchmovimiento,1,10) = '".$rptProduccion_fchmovimiento[$id_fecha]."' AND
                        prodloteope.cdgoperacion = '".$regOperacion->cdgoperacion."' AND
                       (proglote.cdglote = prodlote.cdglote) AND
                        prodlote.cdglote = prodloteope.cdglote AND
                        prodloteope.cdgoperacion = prodoperacion.cdgoperacion AND
                        prodlote.cdgproducto = pdtoimpresion.cdgimpresion AND
                        pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno
                      ORDER BY prodloteope.fchmovimiento");

                    if ($prodLoteSelect->num_rows > 0)
                    { $pdf->SetFont('arial','B',8);

                      $pdf->Cell(4,4,'',0,0,'C');
                      $pdf->Cell(20,4,'NoOP',1,0,'C',true);
                      $pdf->Cell(43,4,'Producto',1,0,'C',true);
                      $pdf->Cell(20,4,'Kilogramos',1,0,'C',true);
                      $pdf->Cell(20,4,'Metros',1,0,'C',true);
                      $pdf->Cell(20,4,'Millares',1,0,'C',true);
                      $pdf->Cell(20,4,'Metros',1,0,'C',true);
                      $pdf->Cell(20,4,'Millares',1,0,'C',true);
                      $pdf->Cell(50,4,'Empleado',1,0,'C',true);
                      $pdf->Cell(28,4,'Movimiento',1,1,'C',true);

                      $id_contador = 1;
                      $kilogramosorigenLote = 0;
                      $metrosorigenLote = 0;
                      $millaresorigenLote = 0;
                      $metrosLote = 0;
                      $millaresLote = 0;
                      while($regProdLote = $prodLoteSelect->fetch_object())
                      { $pdf->SetFont('arial','B',5);
                        $pdf->Cell(4,4,$id_contador,0,0,'R');

                        $pdf->SetFont('arial','',8);
                        $pdf->Cell(20,4,$regProdLote->noop,1,0,'R');
                        $pdf->Cell(43,4,$regProdLote->producto,1,0,'L');
                        $pdf->Cell(20,4,number_format($regProdLote->peso,3),1,0,'R');
                        $pdf->Cell(20,4,number_format($regProdLote->longitud_origen,2),1,0,'R');
                        $pdf->Cell(20,4,number_format($regProdLote->millares_origen,3),1,0,'R');
                        $pdf->Cell(20,4,number_format($regProdLote->longitud,2),1,0,'R');
                        $pdf->Cell(20,4,number_format($regProdLote->millares,3),1,0,'R');
                        $pdf->Cell(50,4,$regProdLote->empleado,1,0,'L');
                        $pdf->Cell(28,4,$regProdLote->fchmovimiento,1,1,'L');

                        $kilogramosorigenLote += $regProdLote->peso;
                        $metrosorigenLote += $regProdLote->longitud_origen;
                        $millaresorigenLote += $regProdLote->millares_origen;
                        $metrosLote += $regProdLote->longitud;
                        $millaresLote += $regProdLote->millares;

                        $kilogramosorigenOperacion += $regProdLote->peso;
                        $metrosorigenOperacion += $regProdLote->longitud_origen;
                        $millaresorigenOperacion += $regProdLote->millares_origen;
                        $metrosOperacion += $regProdLote->longitud;
                        $millaresOperacion += $regProdLote->millares;

                        $id_contador++; }

                      $pdf->SetFont('arial','B',8);

                      $pdf->Cell(4,4,'',0,0,'C');
                      $pdf->Cell(63,4,'Operacion: '.$prodPNC_operacion[$regOperacion->cdgoperacion],0,0,'L');
                      $pdf->Cell(20,4,number_format($kilogramosorigenLote,3),1,0,'R',true);
                      $pdf->Cell(20,4,number_format($metrosorigenLote,2),1,0,'R',true);
                      $pdf->Cell(20,4,number_format($millaresorigenLote,3),1,0,'R',true);
                      $pdf->Cell(20,4,number_format($metrosLote,2),1,0,'R',true);
                      $pdf->Cell(20,4,number_format($millaresLote,3),1,1,'R',true);

                      $pdf->Ln(2);
                    }
                  }

                  $pdf->SetFont('arial','B',10);

                  $pdf->Cell(4,4,'',0,0,'C');
                  $pdf->Cell(63,4,'Operacion: '.$prodPNC_operacion[$regOperacion->cdgoperacion],0,0,'L');
                  $pdf->Cell(20,4,number_format($kilogramosorigenOperacion,3),1,0,'R',true);
                  $pdf->Cell(20,4,number_format($metrosorigenOperacion,2),1,0,'R',true);
                  $pdf->Cell(20,4,number_format($millaresorigenOperacion,3),1,0,'R',true);
                  $pdf->Cell(20,4,number_format($metrosOperacion,2),1,0,'R',true);
                  $pdf->Cell(20,4,number_format($millaresOperacion,3),1,1,'R',true);
                }
              }
              //Fin del armado
            } else
            { $num_fechas = 0; }

            // Buscar bobinas
            $link_mysqli = conectar();
            $prodCalendarioSelect= $link_mysqli->query("
              SELECT SUBSTR(prodbobinaope.fchmovimiento,1,10) AS fchmovimiento
              FROM prodbobina,
                prodbobinaope,
                prodoperacion
              WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                prodbobina.cdgproducto = '".$_GET['cdgproducto']."' AND
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
                    prodbobina.cdgproducto = '".$_GET['cdgproducto']."' AND
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
                       (prodbobina.longitud/pdtodiseno.alto) AS millares,
                        rechempleado.empleado
                      FROM prodlote,
                        prodbobina,
                        prodbobinaope,
                        prodoperacion,
                        pdtodiseno,
                        pdtoimpresion,
                        rechempleado
                      WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                        prodbobina.cdgproducto = '".$_GET['cdgproducto']."' AND
                        prodbobinaope.cdgempleado = rechempleado.cdgempleado AND
                 SUBSTR(prodbobinaope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                        prodbobinaope.cdgoperacion = '".$regProdOperacion->cdgoperacion."' AND
                        prodlote.cdglote = prodbobina.cdglote AND
                        prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
                        prodbobinaope.cdgoperacion = prodoperacion.cdgoperacion AND
                        prodbobina.cdgproducto = pdtoimpresion.cdgimpresion AND
                        pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno
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
                      $millaresBobina = 0;
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
                        $millaresBobina += $regProdBobina->millares;

                        $id_contador++; }

                      $pdf->SetFont('arial','B',8);

                      $pdf->Cell(4,4,'',0,0,'C');
                      $pdf->Cell(73,4,'Operacion: '.$prodPNC_operacion[$regProdOperacion->cdgoperacion],0,0,'L');
                      $pdf->Cell(20,4,number_format($metrosBobina,2),1,0,'R',true);
                      $pdf->Cell(20,4,number_format($millaresBobina,3),1,1,'R',true);

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
                prodrollo.cdgproducto = '".$_GET['cdgproducto']."' AND
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
                    prodrollo.cdgproducto = '".$_GET['cdgproducto']."' AND
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
                       (prodrollo.longitud/pdtodiseno.alto) AS millares,
                        rechempleado.empleado
                      FROM prodlote,
                        prodbobina,
                        prodrollo,
                        prodrolloope,
                        prodoperacion,
                        pdtodiseno,
                        pdtoimpresion,
                        rechempleado
                      WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                        prodrollo.cdgproducto = '".$_GET['cdgproducto']."' AND
                        prodrolloope.cdgempleado = rechempleado.cdgempleado AND
                 SUBSTR(prodrolloope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                        prodrolloope.cdgoperacion = '".$regProdOperacion->cdgoperacion."' AND
                        prodlote.cdglote = prodbobina.cdglote AND
                        prodbobina.cdgbobina = prodrollo.cdgbobina AND
                        prodrollo.cdgrollo = prodrolloope.cdgrollo AND
                        prodrolloope.cdgoperacion = prodoperacion.cdgoperacion AND
                        prodrollo.cdgproducto = pdtoimpresion.cdgimpresion AND
                        pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno
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
                      $millaresRollo = 0;
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
                        $millaresRollo += $regProdRollo->millares;

                        $id_contador++; }

                      $pdf->SetFont('arial','B',8);

                      $pdf->Cell(4,4,'',0,0,'C');
                      $pdf->Cell(73,4,'Operacion: '.$prodPNC_operacion[$regProdOperacion->cdgoperacion],0,0,'L');
                      $pdf->Cell(20,4,number_format($metrosRollo,2),1,0,'R',true);
                      $pdf->Cell(20,4,number_format($millaresRollo,3),1,1,'R',true);

                      $pdf->Ln(2);
                    }
                  } // Ciclo
                }
              } // Ciclo
            }


          } else
          { // Producto Nulo
            $_SESSION['prodPNC_subtitulo'] = 'Proceso: '.$_SESSION['prodPNC_proceso'];

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
                      ((prodlote.longitud*pdtodiseno.alpaso)/pdtodiseno.alto) AS millares,
                        rechempleado.empleado
                      FROM prodlote,
                        prodloteope,
                        prodoperacion,
                        pdtodiseno,
                        pdtoimpresion,
                        rechempleado
                      WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                        prodloteope.cdgempleado = rechempleado.cdgempleado AND
                 SUBSTR(prodloteope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                        prodloteope.cdgoperacion = '".$regProdOperacion->cdgoperacion."' AND
                        prodlote.cdglote = prodloteope.cdglote AND
                        prodloteope.cdgoperacion = prodoperacion.cdgoperacion AND
                        prodlote.cdgproducto = pdtoimpresion.cdgimpresion AND
                        pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno
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
                      $millaresLote = 0;
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
                        $millaresLote += $regProdLote->millares;

                        $id_contador++; }

                      $pdf->SetFont('arial','B',8);

                      $pdf->Cell(4,4,'',0,0,'C');
                      $pdf->Cell(73,4,'Operacion: '.$prodPNC_operacion[$regProdOperacion->cdgoperacion],0,0,'L');
                      $pdf->Cell(20,4,number_format($metrosLote,2),1,0,'R',true);
                      $pdf->Cell(20,4,number_format($millaresLote,2),1,1,'R',true);

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
                       (prodbobina.longitud/pdtodiseno.alto) AS millares,
                        rechempleado.empleado
                      FROM prodlote,
                        prodbobina,
                        prodbobinaope,
                        prodoperacion,
                        pdtodiseno,
                        pdtoimpresion,
                        rechempleado
                      WHERE prodoperacion.cdgproceso = '".$_GET['cdgproceso']."' AND
                        prodbobinaope.cdgempleado = rechempleado.cdgempleado AND
                 SUBSTR(prodbobinaope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                        prodbobinaope.cdgoperacion = '".$regProdOperacion->cdgoperacion."' AND
                        prodlote.cdglote = prodbobina.cdglote AND
                        prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
                        prodbobinaope.cdgoperacion = prodoperacion.cdgoperacion AND
                        prodbobina.cdgproducto = pdtoimpresion.cdgimpresion AND
                        pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno
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
                      $millaresBobina = 0;
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
                        $millaresBobina += $regProdBobina->millares;

                        $id_contador++; }

                      $pdf->SetFont('arial','B',8);

                      $pdf->Cell(4,4,'',0,0,'C');
                      $pdf->Cell(73,4,'Operacion: '.$prodPNC_operacion[$regProdOperacion->cdgoperacion],0,0,'L');
                      $pdf->Cell(20,4,number_format($metrosBobina,2),1,0,'R',true);
                      $pdf->Cell(20,4,number_format($millaresBobina,2),1,1,'R',true);

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
                       (prodrollo.longitud/pdtodiseno.alto) AS millares,
                        rechempleado.empleado
                      FROM prodlote,
                        prodbobina,
                        prodrollo,
                        prodrolloope,
                        prodoperacion,
                        pdtodiseno,
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
                        prodrollo.cdgproducto = pdtoimpresion.cdgimpresion AND
                        pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno
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
                      $millaresRollo = 0;
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
                        $millaresRollo += $regProdRollo->millares;

                        $id_contador++; }

                      $pdf->SetFont('arial','B',8);

                      $pdf->Cell(4,4,'',0,0,'C');
                      $pdf->Cell(73,4,'Operacion: '.$prodPNC_operacion[$regProdOperacion->cdgoperacion],0,0,'L');
                      $pdf->Cell(20,4,number_format($metrosRollo,2),1,0,'R',true);
                      $pdf->Cell(20,4,number_format($millaresRollo,2),1,1,'R',true);

                      $pdf->Ln(2);
                    }
                  } // Ciclo
                }
              } // Ciclo
            }


          }
        }
      } else
      { // Proceso Nulo
        if ($_SESSION['prodPNC_empleado'] != '')
        { // Empleado Activo
          if ($_SESSION['prodPNC_producto'] != '')
          { // Producto Activo
            $_SESSION['prodPNC_subtitulo'] = 'Empleado: '.$_SESSION['prodPNC_empleado'].' / Producto: '.$_SESSION['prodPNC_producto'];
          } else
          { // Producto Nulo
            $_SESSION['prodPNC_subtitulo'] = 'Empleado: '.$_SESSION['prodPNC_empleado'];

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
                  ((prodlote.longitud*pdtodiseno.alpaso)/pdtodiseno.alto) AS millares,
                    prodmaquina.maquina
                  FROM prodlote,
                    prodloteope,
                    prodmaquina,
                    pdtodiseno,
                    pdtoimpresion
                  WHERE prodloteope.cdgempleado = '".$_GET['cdgempleado']."' AND
             SUBSTR(prodloteope.fchmovimiento,1,10) = '".$regProdCalendario->fchmovimiento."' AND
                    prodloteope.cdgoperacion = '".$regProdOperacion->cdgoperacion."' AND
                    prodlote.cdglote = prodloteope.cdglote AND
                    prodloteope.cdgmaquina = prodmaquina.cdgmaquina AND
                    prodlote.cdgproducto = pdtoimpresion.cdgimpresion AND
                    pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno
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
                  $millaresLote = 0;
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
                    $millaresLote += $regProdLote->millares;

                    $id_contador++; }

                  $pdf->SetFont('arial','B',8);

                  $pdf->Cell(4,4,'',0,0,'R');
                  $pdf->Cell(73,4,'Operacion: '.$prodPNC_operacion[$regProdOperacion->cdgoperacion],0,0,'L');
                  $pdf->Cell(20,4,number_format($metrosLote,2),1,0,'R',true);
                  $pdf->Cell(20,4,number_format($millaresLote,3),1,1,'R',true);

                  $pdf->Ln(2);
                }
              } // Ciclo
            }
          } // Ciclo
        }

        // Buscar bobinas
        $link_mysqli = conectar();
        $prodCalendarioSelect= $link_mysqli->query("
          SELECT prodbobinaope.fchoperacion
          FROM prodbobina,
            prodbobinaope
          WHERE prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
            prodbobinaope.cdgempleado = '".$_GET['cdgempleado']."' AND
           (prodbobinaope.fchoperacion BETWEEN '".$_GET['dsdfecha']."' AND '".$_GET['hstfecha']."')
          GROUP BY prodbobinaope.fchoperacion");

        if ($prodCalendarioSelect->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelect->fetch_object())
          { $link_mysqli = conectar();
            $prodOperacionSelect = $link_mysqli->query("
              SELECT prodbobinaope.fchoperacion,
                prodbobinaope.cdgoperacion
              FROM prodbobina,
                prodbobinaope
              WHERE prodbobinaope.cdgempleado = '".$_GET['cdgempleado']."' AND
                prodbobinaope.fchoperacion = '".$regProdCalendario->fchoperacion."' AND
                prodbobina.cdgbobina = prodbobinaope.cdgbobina
              GROUP BY prodbobinaope.fchoperacion,
                prodbobinaope.cdgoperacion");

            if ($prodOperacionSelect->num_rows > 0)
            { while($regProdOperacion = $prodOperacionSelect->fetch_object())
              { $link_mysqli = conectar();
                $prodBobinaSelect = $link_mysqli->query("
                  SELECT prodbobinaope.fchmovimiento,
                    prodbobinaope.cdgoperacion,
             CONCAT(prodlote.noop,'-',prodbobina.bobina) AS noop,
                    pdtoimpresion.impresion AS producto,
                    prodbobinaope.longitud,
                   (prodbobinaope.longitud/pdtodiseno.alto) AS millares,
                    prodbobinaope.cdgmaquina
                  FROM prodlote,
                    prodbobina,
                    prodbobinaope,
                    pdtoimpresion,
                    pdtodiseno
                  WHERE prodlote.cdglote = prodbobina.cdglote AND
                    prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
                    prodbobinaope.cdgempleado = '".$_GET['cdgempleado']."' AND
                    prodbobinaope.fchoperacion = '".$regProdCalendario->fchoperacion."' AND
                    prodbobinaope.cdgoperacion = '".$regProdOperacion->cdgoperacion."' AND
                    prodbobina.cdgproducto = pdtoimpresion.cdgimpresion AND
                    pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno
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
                  $millaresBobina = 0;
                  while($regProdBobina = $prodBobinaSelect->fetch_object())
                  { $pdf->SetFont('arial','B',5);
                    $pdf->Cell(4,4,$id_contador,0,0,'R');

                    $pdf->SetFont('arial','',8);
                    $pdf->Cell(20,4,$regProdBobina->noop,1,0,'R');
                    $pdf->Cell(53,4,$regProdBobina->producto,1,0,'L');
                    $pdf->Cell(20,4,number_format($regProdBobina->longitud,2),1,0,'R');
                    $pdf->Cell(20,4,number_format($regProdBobina->millares,3),1,0,'R');
                    $pdf->Cell(50,4,$dataAux_maquina[$regProdBobina->cdgmaquina],1,0,'L');
                    $pdf->Cell(28,4,$regProdBobina->fchmovimiento,1,1,'L');

                    $metrosBobina += $regProdBobina->longitud;
                    $millaresBobina += $regProdBobina->millares;

                    $id_contador++; }

                  $pdf->SetFont('arial','B',8);

                  $pdf->Cell(4,4,'',0,0,'R');
                  $pdf->Cell(73,4,'Operacion: '.$prodPNC_operacion[$regProdOperacion->cdgoperacion],0,0,'L');
                  $pdf->Cell(20,4,number_format($metrosBobina,2),1,0,'R',true);
                  $pdf->Cell(20,4,number_format($millaresBobina,3),1,1,'R',true);

                  $pdf->Ln(2);
                }
              } // Ciclo
            }
          } // Ciclo
        }

        // Buscar rollos
        $link_mysqli = conectar();
        $prodCalendarioSelect = $link_mysqli->query("
          SELECT prodrolloope.fchoperacion
          FROM prodrollo,
            prodrolloope
          WHERE prodrollo.cdgrollo = prodrolloope.cdgrollo AND
            prodrolloope.cdgempleado = '".$_GET['cdgempleado']."' AND
           (prodrolloope.fchoperacion BETWEEN '".$_GET['dsdfecha']."' AND '".$_GET['hstfecha']."')
          GROUP BY prodrolloope.fchoperacion");

        if ($prodCalendarioSelect->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelect->fetch_object())
          { $link_mysqli = conectar();
            $prodOperacionSelect = $link_mysqli->query("
              SELECT prodrolloope.fchoperacion,
                prodrolloope.cdgoperacion
              FROM prodrollo,
                prodrolloope
              WHERE prodrollo.cdgrollo = prodrolloope.cdgrollo AND
                prodrolloope.cdgempleado = '".$_GET['cdgempleado']."' AND
                prodrolloope.fchoperacion = '".$regProdCalendario->fchoperacion."'
              GROUP BY prodrolloope.fchoperacion,
                prodrolloope.cdgoperacion");

            if ($prodOperacionSelect->num_rows > 0)
            { while($regProdOperacion = $prodOperacionSelect->fetch_object())
              { $link_mysqli = conectar();
                $prodRolloSelect = $link_mysqli->query("
                  SELECT prodrolloope.fchmovimiento,
                    prodrolloope.cdgoperacion,
             CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) AS noop,
                    pdtoimpresion.impresion AS producto,
                    prodrolloope.longitud,
                   (prodrolloope.longitud/pdtodiseno.alto) AS millares,
                    prodrolloope.cdgmaquina
                  FROM prodlote,
                    prodbobina,
                    prodrollo,
                    prodrolloope,
                    pdtoimpresion,
                    pdtodiseno
                  WHERE prodlote.cdglote = prodbobina.cdglote AND
                    prodbobina.cdgbobina = prodrollo.cdgbobina AND
                    prodrollo.cdgrollo = prodrolloope.cdgrollo AND
                    prodrolloope.cdgempleado = '".$_GET['cdgempleado']."' AND
                    prodrolloope.fchoperacion = '".$regProdCalendario->fchoperacion."' AND
                    prodrolloope.cdgoperacion = '".$regProdOperacion->cdgoperacion."' AND
                    prodrollo.cdgproducto = pdtoimpresion.cdgimpresion AND
                    pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno
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
                  $millaresRollo = 0;
                  while($regProdRollo = $prodRolloSelect->fetch_object())
                  { $pdf->SetFont('arial','B',5);
                    $pdf->Cell(4,4,$id_contador,0,0,'R');

                    $pdf->SetFont('arial','',8);
                    $pdf->Cell(20,4,$regProdRollo->noop,1,0,'R');
                    $pdf->Cell(53,4,$regProdRollo->producto,1,0,'L');
                    $pdf->Cell(20,4,number_format($regProdRollo->longitud,2),1,0,'R');
                    $pdf->Cell(20,4,number_format($regProdRollo->millares,3),1,0,'R');
                    $pdf->Cell(50,4,$dataAux_maquina[$regProdRollo->cdgmaquina],1,0,'L');
                    $pdf->Cell(28,4,$regProdRollo->fchmovimiento,1,1,'L');

                    $metrosRollo += $regProdRollo->longitud;
                    $millaresRollo += $regProdRollo->millares;

                    $id_contador++; }

                  $pdf->SetFont('arial','B',8);

                  $pdf->Cell(4,4,'',0,0,'R');
                  $pdf->Cell(73,4,'Operacion: '.$prodPNC_operacion[$regProdOperacion->cdgoperacion],0,0,'L');
                  $pdf->Cell(20,4,number_format($metrosRollo,2),1,0,'R',true);
                  $pdf->Cell(20,4,number_format($millaresRollo,3),1,1,'R',true);

                  $pdf->Ln(2);
                }
              } // Ciclo
            }
          } // Ciclo
        }

          }
        } else
        { // Empleado Nulo
          if ($_SESSION['prodPNC_producto'] != '')
          { // Producto Activo
            $_SESSION['prodPNC_subtitulo'] = 'Producto: '.$_SESSION['prodPNC_producto'];
          } else
          { // Producto Nulo
            $_SESSION['prodPNC_subtitulo'] = 'Informe general';
          }
        }
      }


  $pdf->Output('Reporte de produccion del '.$_GET['dsdfecha'].' al '.$_GET['hstfecha'].' '.$_SESSION['prodPNC_subtitulo'].'.pdf', 'D');

?>
