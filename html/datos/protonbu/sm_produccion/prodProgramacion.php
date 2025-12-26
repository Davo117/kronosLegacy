<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '60010';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);
    
    // Captura de parametros para programación
    $prodProgramacion_cdgmezcla = $_POST['slc_cdgmezcla'];
    $prodProgramacion_fchprograma = trim($_POST['txt_fchprograma']);
    $prodProgramacion_amplitud = $_POST['text_amplitud'];
    $prodProgramacion_tolerancia = $_POST['text_tolerancia'];
    //////////////////////////////////////////
    
    if ($prodProgramacion_fchprograma == '')
    { $prodProgramacion_fchprograma = date('Y-m-d'); }
    else
    { $fchprograma = str_replace("-", "", $prodProgramacion_fchprograma);

      $dia = str_pad(substr($fchprograma,6,2),2,'0',STR_PAD_LEFT);
      $mes = str_pad(substr($fchprograma,4,2),2,'0',STR_PAD_LEFT);
      $ano = str_pad(substr($fchprograma,0,4),4,'0',STR_PAD_LEFT);

      if (checkdate((int)$mes,(int)$dia,(int)$ano))
      { $prodProgramacion_fchprograma = $ano.'-'.$mes.'-'.$dia; }
      else
      { $prodProgramacion_fchprograma = date('Y-m-d'); }
    }

    if ($_GET['cdglote'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $link_mysqli = conectar();
        $prodLoteSelect = $link_mysqli->query("
          SELECT * FROM prodlote
          WHERE cdglote = '".$_GET['cdglote']."'");

        if ($prodLoteSelect->num_rows > 0)
        { $regProdLote = $prodLoteSelect->fetch_object();

          $prodProgramacion_cdglote = $regProdLote->cdglote;
          $prodProgramacion_fchprograma = $regProdLote->fchprograma;
          $prodProgramacion_longitud = $regProdLote->longitud;
          $prodProgramacion_noop = $regProdLote->noop;
          $prodProgramacion_cdgmezcla = $regProdLote->cdgmezcla;
          $prodProgramacion_sttlote = $regProdLote->sttlote;

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($prodProgramacion_sttlote == '1')
              { $prodProgramacion_newsttlote = '0'; }

              if ($prodProgramacion_sttlote == '0')
              { $prodProgramacion_newsttlote = '1'; }

              if ($prodProgramacion_newsttlote != '')
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE prodlote
                  SET sttlote = '".$prodProgramacion_newsttlote."'
                  WHERE cdglote = '".$prodProgramacion_cdglote."'");

                if ($link_mysqli->affected_rows > 0)
                { $msg_alert .= 'El NoOP '.$prodProgramacion_noop.' fue actualizado en su status.'; }
              } else
              { $msg_alert .= 'El NoOP '.$prodProgramacion_noop.' NO fue actualizado en su status, ya se encuentra en otro proceso.'; }
            } else
            { $msg_alert = $msg_norewrite.' (Status)'; }
          }

          if ($_GET['proceso'] == 'consumo')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { $link_mysqli = conectar();
              $prodLoteOpeSelect = $link_mysqli->query("
                SELECT * FROM prodloteope
                WHERE cdglote = '".$prodProgramacion_cdglote."'
                AND cdgoperacion NOT LIKE '00090'");

              if ($prodLoteOpeSelect->num_rows > 0)
              { $msg_alert = 'El NoOP '.$prodProgramacion_noop.' NO fue recalculado en sus consumos por que ya fue afectado en Produccion.'; }
              else
              { /*$link_mysqli = conectar();
                $link_mysqli->query("
                  DELETE FROM prodconsumo
                  WHERE cdglote = '".$prodProgramacion_cdglote."'");

                $link_mysqli = conectar();
                $pdtoImpresionSelect = $link_mysqli->query("
                  SELECT pdtoimpresion.corte,
                    pdtoimpresion.alpaso
                  FROM pdtoimpresion,
                    pdtomezcla
                  WHERE pdtoimpresion.cdgimpresion = pdtomezcla.cdgimpresion
                  AND pdtomezcla.cdgmezcla = '".$prodProgramacion_cdgmezcla."'");

                if ($pdtoImpresionSelect->num_rows > 0)
                { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();
                  $pdtoImpresion_corte = $regPdtoImpresion->corte;
                  $pdtoImpresion_alpaso = $regPdtoImpresion->alpaso; }

                $pdtoImpresionSelect->close;
                
                $link_mysqli = conectar();
                $pdtoConsumoSelect = $link_mysqli->query("
                  SELECT * FROM pdtoconsumo
                  WHERE cdgmezcla = '".$prodProgramacion_cdgmezcla."'");

                if ($pdtoConsumoSelect->num_rows > 0)
                { while ($regPdtoConsumo = $pdtoConsumoSelect->fetch_object())
                  { $pdtoConsumo_cdgproceso = $regPdtoConsumo->cdgproceso;
                    $pdtoConsumo_cdgmateria = $regPdtoConsumo->cdgmateria;
                    $pdtoConsumo_consumo = $regPdtoConsumo->consumo;
                    $pdtoConsumo_cdgelemento = $regPdtoConsumo->cdgelemento;

                    $link_mysqli = conectar();
                    $link_mysqli->query("
                    INSERT INTO prodconsumo
                      (cdglote, cdgelemento, cdgmezcla, cdgproceso, cdgmateria, consumo)
                    VALUES
                       ('".$prodProgramacion_cdglote."', '".$pdtoConsumo_cdgelemento."', '".$prodProgramacion_cdgmezcla."', '".$pdtoConsumo_cdgproceso."', '".$pdtoConsumo_cdgmateria."', '".((($prodProgramacion_longitud*$pdtoImpresion_alpaso)/$pdtoImpresion_corte)*$pdtoConsumo_consumo)."')"); }
                }

                $pdtoConsumoSelect->close;    //*/            

                $msg_alert = 'El NoOP '.$prodProgramacion_noop.' fue recalculado en sus consumos.';
              }
              
              $prodLoteOpeSelect->close;
            } else
            { $msg_alert = $msg_norewrite.' (Consumos)'; }
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $link_mysqli = conectar();
              $prodLoteOpeSelect = $link_mysqli->query("
                SELECT * FROM prodloteope
                WHERE cdglote = '".$prodProgramacion_cdglote."'
                AND cdgoperacion NOT LIKE '00090'");

              if ($prodLoteOpeSelect->num_rows > 0)
              { $msg_alert = 'El NoOP '.$prodProgramacion_noop.' NO fue eliminado de la programacion por que ya fue afectado en Produccion.'; }
              else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  DELETE FROM prodlote
                  WHERE cdglote = '".$prodProgramacion_cdglote."'
                  AND sttlote = '0'");

                if ($link_mysqli->affected_rows > 0)
                { $link_mysqli = conectar();
                  $link_mysqli->query("
                    DELETE FROM prodloteope
                    WHERE cdglote = '".$prodProgramacion_cdglote."'");
                    
                  $link_mysqli = conectar();
                  $link_mysqli->query("
                    DELETE FROM prodloteproc
                    WHERE cdglote = '".$prodProgramacion_cdglote."'");

                  $link_mysqli = conectar();
                  $link_mysqli->query("
                    UPDATE proglote
                    SET sttlote = '8'
                    WHERE cdglote = '".$prodProgramacion_cdglote."'");

                  if ($link_mysqli->affected_rows > 0)
                  { $msg_alert = 'El NoOp '.$prodProgramacion_noop.' fue eliminado \n'; }                  
                } else
                { $msg_alert = 'El NoOp '.$prodProgramacion_noop.' NO fue eliminado \n'; }
              }
            } else
            { $msg_alert = $msg_nodelete; }
          }
        } else
        { $msg_alert = "El NoOP no existe, o fue cancelado."; }

        $prodLoteSelect->close;
      } else
      { $msg_alert = $msg_noread; }
    }

    if ($_POST['btn_submit'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { $link_mysqli = conectar();
        $progLoteSelect = $link_mysqli->query("
          SELECT proglote.idlote,
            proglote.lote,
            proglote.amplitud,
            proglote.longitud,
            proglote.espesor,
            proglote.encogimiento,
            proglote.peso,
            proglote.tarima,
            proglote.cdglote
          FROM progbloque,
            proglote
          WHERE progbloque.cdgbloque = proglote.cdgbloque
          AND (proglote.amplitud BETWEEN ".$prodProgramacion_amplitud." 
            AND ".$prodProgramacion_tolerancia.")
          AND proglote.sttlote = '8'
          ORDER BY proglote.amplitud,
            progbloque.fchbloque,
            proglote.tarima,
            proglote.idlote");

        if ($progLoteSelect->num_rows > 0)
        { $link_mysqli = conectar();
          $pdtoImpresionSelect = $link_mysqli->query("
            SELECT pdtoimpresion.corte,
              pdtoimpresion.alpaso,
              pdtoimpresion.cdgimpresion
            FROM pdtoimpresion,
              pdtomezcla
            WHERE pdtoimpresion.cdgimpresion = pdtomezcla.cdgimpresion
            AND pdtomezcla.cdgmezcla = '".$prodProgramacion_cdgmezcla."'");

          if ($pdtoImpresionSelect->num_rows > 0)
          { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();
            $pdtoImpresion_corte = $regPdtoImpresion->corte;
            $pdtoImpresion_alpaso = $regPdtoImpresion->alpaso;
            $pdtoImpresion_cdgproducto = $regPdtoImpresion->cdgimpresion; }

          $pdtoImpresionSelect->close;
          //////////////////////////////////////////////////////////////
/*
          // Consumos de la impresión
          $link_mysqli = conectar();
          $pdtoConsumoSelect = $link_mysqli->query("
            SELECT * FROM pdtoconsumo
            WHERE cdgmezcla = '".$prodProgramacion_cdgmezcla."'");

          if ($pdtoConsumoSelect->num_rows > 0)
          { $id_consumo = 1;
            while ($regPdtoConsumo = $pdtoConsumoSelect->fetch_object())
            { $pdtoConsumo_cdgproceso[$id_consumo] = $regPdtoConsumo->cdgproceso;
              $pdtoConsumo_cdgmateria[$id_consumo] = $regPdtoConsumo->cdgmateria;
              $pdtoConsumo_consumo[$id_consumo] = $regPdtoConsumo->consumo;
              $pdtoConsumo_cdgelemento[$id_consumo] = $regPdtoConsumo->cdgelemento;

              $id_consumo++; }

            $num_consumos = $pdtoConsumoSelect->num_rows; }
          ////////////////////////////////////////////////////////////// //*/

          while ($regProgLote = $progLoteSelect->fetch_object())
          { if (isset($_REQUEST['chk_'.$regProgLote->cdglote]))
            { $prodProgramacion_cdglote = $regProgLote->cdglote;
              $prodProgramacion_longitud = $regProgLote->longitud;
              $prodProgramacion_amplitud = $regProgLote->amplitud;
              $prodProgramacion_peso = $regProgLote->peso;

              $link_mysqli = conectar();
              $progLoteSelectMax = $link_mysqli->query("
                SELECT MAX(noop) AS noopmax,
                COUNT(noop) AS noopnum
                FROM prodlote");

              $regProdLoteMax = $progLoteSelectMax->fetch_object();
              $prodProgramacion_noopmax = (int)$regProdLoteMax->noopmax;
              $prodProgramacion_noopnum = (int)$regProdLoteMax->noopnum;
/*
              // Depurar consumos por lote
              $link_mysqli = conectar();
              $link_mysqli->query("
                DELETE FROM prodconsumo
                WHERE cdglote = '".$prodProgramacion_cdglote."'");
              ////////////////////////////////////////////////////////////// //*/

              // Insertar bobina en la programación
              /* Queda pendiente agregar la operacion de programacion en la tabla prodloteope */
              
              if ($prodProgramacion_noopmax == $prodProgramacion_noopnum)
              { $prodProgramacion_noop = $prodProgramacion_noopmax+1;

                $link_mysqli = conectar();
                $link_mysqli->query("
                  INSERT INTO prodloteproc
                    (cdglote, cdgproceso, longitud, amplitud, peso, fchmovimiento)
                  VALUES
                    ('".$prodProgramacion_cdglote."', '10', '".$prodProgramacion_longitud."', '".$prodProgramacion_amplitud."', '".$prodProgramacion_peso."', NOW())");

                $link_mysqli = conectar();
                $link_mysqli->query("
                  INSERT INTO prodlote
                    (cdglote, noop, cdgproducto, cdgmezcla, longitud, amplitud, peso, cdgproceso, fchprograma, fchmovimiento)
                  VALUES
                    ('".$prodProgramacion_cdglote."', '".$prodProgramacion_noop."', '".$pdtoImpresion_cdgproducto."', '".$prodProgramacion_cdgmezcla."', '".$prodProgramacion_longitud."', '".$prodProgramacion_amplitud."', '".$prodProgramacion_peso."', '10', '".$prodProgramacion_fchprograma."', NOW())");
              }
              else
              { for ($noop = 1; $noop < $prodProgramacion_noopmax; $noop++)
                { $prodProgramacion_noop = $noop;
                    
                  $link_mysqli = conectar();
                  $link_mysqli->query("
                  INSERT INTO prodloteproc
                    (cdglote, cdgproceso, longitud, amplitud, peso, fchmovimiento)
                  VALUES
                    ('".$prodProgramacion_cdglote."', '10', '".$prodProgramacion_longitud."', '".$prodProgramacion_amplitud."', '".$prodProgramacion_peso."', NOW())");

                  $link_mysqli = conectar();
                  $link_mysqli->query("
                  INSERT INTO prodlote
                    (cdglote, noop, cdgproducto, cdgmezcla, longitud, amplitud, peso, cdgproceso, fchprograma, fchmovimiento)
                  VALUES
                    ('".$prodProgramacion_cdglote."', '".$prodProgramacion_noop."', '".$pdtoImpresion_cdgproducto."', '".$prodProgramacion_cdgmezcla."', '".$prodProgramacion_longitud."', '".$prodProgramacion_amplitud."', '".$prodProgramacion_peso."', '10', '".$prodProgramacion_fchprograma."', NOW())");

                  if ($link_mysqli->affected_rows > 0)
                  { $noop = $prodProgramacion_noopmax; }
                }
              }
              //////////////////////////////////////////////////////////////

              if ($link_mysqli->affected_rows > 0)
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE proglote
                  SET sttlote = '9'
                  WHERE cdglote = '".$prodProgramacion_cdglote."'");

                if ($link_mysqli->affected_rows > 0)
                { $msg_alert .= 'El NoOp '.$prodProgramacion_noop.' fue generado. \n';
/*
                  for ($id_consumo = 1; $id_consumo <= $num_consumos; $id_consumo++)
                  { $link_mysqli = conectar();
                    $link_mysqli->query("
                    INSERT INTO prodconsumo
                      (cdglote, cdgelemento, cdgmezcla, cdgproceso, cdgmateria, consumo)
                    VALUES
                      ('".$prodPrograacion_cdglote."', '".$pdtoConsumo_cdgelemento[$id_consumo]."', '".$prodProgramacion_cdgmezcla."', '".$pdtoConsumo_cdgproceso[$id_consumo]."', '".$pdtoConsumo_cdgmateria[$id_consumo]."', '".((($prodProgramacion_longitud*$pdtoImpresion_alpaso)/$pdtoImpresion_corte)*$pdtoConsumo_consumo[$id_consumo])."')");
                  } //*/
                } else
                { $link_mysqli = conectar();
                  $link_mysqli->query("
                    DELETE FROM prodproclote
                    WHERE cdglote = '".$prodProgramacion_cdglote."'");
                  
                  $link_mysqli = conectar();
                  $link_mysqli->query("
                    DELETE * FROM prodlote
                    WHERE cdglote = '".$prodProgramacion_cdglote."'"); 
                    
                  $msg_alert .= 'El NoOp '.$prodProgramacion_noop.' NO fue generado (Lote). \n'; }
              } else
              { $msg_alert .= 'El NoOp '.$prodProgramacion_noop.' NO fue generado. \n'; } 
            }
          }
        }
      } else
      { $msg_alert = $msg_norewrite; }
    }

  if (substr($sistModulo_permiso,0,1) == 'r')
  {    
    
    // Filtrado de mezclas por impresión
    $link_mysqli = conectar();
    $pdtoProyectoSelect = $link_mysqli->query("
      SELECT * FROM pdtoproyecto
      WHERE sttproyecto = '1'
      ORDER BY proyecto,
        idproyecto");

    $idproyecto = 1;
    while ($regPdtoProyecto = $pdtoProyectoSelect->fetch_object())
    { $pdtoProyecto_idproyecto[$idproyecto] = $regPdtoProyecto->idproyecto;
      $pdtoProyecto_proyecto[$idproyecto] = $regPdtoProyecto->proyecto;
      $pdtoProyecto_cdgproyecto[$idproyecto] = $regPdtoProyecto->cdgproyecto;

      $link_mysqli = conectar();
      $pdtoImpresionSelect = $link_mysqli->query("
        SELECT * FROM pdtoimpresion
        WHERE cdgproyecto = '".$regPdtoProyecto->cdgproyecto."'
        AND sttimpresion = '1'
        ORDER BY impresion,
          idimpresion");

      $idimpresion = 1;
      while ($regPdtoImpresion = $pdtoImpresionSelect->fetch_object())
      { $pdtoImpresion_idimpresion[$idproyecto][$idimpresion] = $regPdtoImpresion->idimpresion;
        $pdtoImpresion_impresion[$idproyecto][$idimpresion] = $regPdtoImpresion->impresion;
        $pdtoImpresion_ncorte[$idproyecto][$idimpresion] = $regPdtoImpresion->corte;
        $pdtoImpresion_nalpaso[$idproyecto][$idimpresion] = $regPdtoImpresion->alpaso;
        $pdtoImpresion_cdgimpresion[$idproyecto][$idimpresion] = $regPdtoImpresion->cdgimpresion;
        $pdtoImpresion_amplitud[$idproyecto][$idimpresion] = (($regPdtoImpresion->ancho+$regPdtoImpresion->ceja)*$regPdtoImpresion->alpaso);
        $pdtoImpresion_tolerancia[$idproyecto][$idimpresion] = $pdtoImpresion_amplitud[$idproyecto][$idimpresion]+$regPdtoImpresion->tolerancia;

        $link_mysqli = conectar();
        $pdtoMezclaSelect = $link_mysqli->query("
          SELECT * FROM pdtomezcla
          WHERE cdgimpresion = '".$regPdtoImpresion->cdgimpresion."'
          AND sttmezcla = '1'
          ORDER BY mezcla,
            idmezcla");

        $idmezcla = 1;
        while ($regPdtoMezcla = $pdtoMezclaSelect->fetch_object())
        { $pdtoMezcla_idmezcla[$idproyecto][$idimpresion][$idmezcla] = $regPdtoMezcla->idmezcla;
          $pdtoMezcla_mezcla[$idproyecto][$idimpresion][$idmezcla] = $regPdtoMezcla->mezcla;
          $pdtoMezcla_cdgmezcla[$idproyecto][$idimpresion][$idmezcla] = $regPdtoMezcla->cdgmezcla;

          if ($prodProgramacion_cdgmezcla == $pdtoMezcla_cdgmezcla[$idproyecto][$idimpresion][$idmezcla])
          { $prodProgramacion_amplitud = $pdtoImpresion_amplitud[$idproyecto][$idimpresion];
            $prodProgramacion_tolerancia = $pdtoImpresion_tolerancia[$idproyecto][$idimpresion]; }
          
          $idmezcla++; }

        $idsmezcla[$idproyecto][$idimpresion] = $pdtoMezclaSelect->num_rows;
        $pdtoMezclaSelect->close;

        $idimpresion++; }

      $idsimpresion[$idproyecto] = $pdtoImpresionSelect->num_rows;
      $pdtoImpresionSelect->close;

      $idproyecto++; }

    $idsproyecto = $pdtoProyectoSelect->num_rows;
    $pdtoProyectoSelect->close;
    //////////////////////////////////////////////////

    $link_mysqli = conectar();
    $prodLoteSelect = $link_mysqli->query("
      SELECT proglote.idlote,
        proglote.lote,
        proglote.tarima,
        proglote.amplitud,
        prodlote.longitud,
        proglote.espesor,
        proglote.encogimiento,
        prodlote.peso,
        prodlote.noop,
        pdtoproyecto.idproyecto,
        pdtoimpresion.idimpresion,
        pdtoimpresion.corte,
        pdtoimpresion.alpaso,
        pdtomezcla.idmezcla,
        prodlote.cdglote,
        prodlote.sttlote
      FROM prodlote,
        pdtoproyecto,
        pdtoimpresion,
        pdtomezcla,
        proglote
      WHERE prodlote.cdgmezcla = pdtomezcla.cdgmezcla
      AND pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion
      AND pdtoimpresion.cdgproyecto = pdtoproyecto.cdgproyecto
      AND prodlote.cdglote = proglote.cdglote
      AND prodlote.fchprograma = '".$prodProgramacion_fchprograma."'
      ORDER BY prodlote.noop");

    if ($prodLoteSelect->num_rows > 0)
    { $id_lote = 1;
      while ($regProdLote = $prodLoteSelect->fetch_object())
      { $prodProgramacions_idlote[$id_lote] = $regProdLote->idlote;
        $prodProgramacions_lote[$id_lote] = $regProdLote->lote;
        $prodProgramacions_tarima[$id_lote] = $regProdLote->tarima;
        $prodProgramacions_amplitud[$id_lote] = $regProdLote->amplitud;
        $prodProgramacions_longitud[$id_lote] = $regProdLote->longitud;
        $prodProgramacions_espesor[$id_lote] = $regProdLote->espesor;
        $prodProgramacions_encogimiento[$id_lote] = $regProdLote->encogimiento;
        $prodProgramacions_peso[$id_lote] = $regProdLote->peso;
        $prodProgramacions_noop[$id_lote] = $regProdLote->noop;
        $prodProgramacions_idproyecto[$id_lote] = $regProdLote->idproyecto;
        $prodProgramacions_idimpresion[$id_lote] = $regProdLote->idimpresion;
        $prodProgramacions_corte[$id_lote] = $regProdLote->corte;
        $prodProgramacions_alpaso[$id_lote] = $regProdLote->alpaso;
        $prodProgramacions_idmezcla[$id_lote] = $regProdLote->idmezcla;
        $prodProgramacions_cdglote[$id_lote] = $regProdLote->cdglote;
        $prodProgramacions_sttlote[$id_lote] = $regProdLote->sttlote;

        $id_lote++; }

      $numprodlotes = $prodLoteSelect->num_rows; }
    $prodLoteSelect->close;
    
    $link_mysqli = conectar();
    $progLoteSelect = $link_mysqli->query("
      SELECT proglote.idlote,
        proglote.lote,
        proglote.amplitud,
        proglote.longitud,
        proglote.espesor,
        proglote.encogimiento,
        proglote.peso,
        proglote.tarima,
        proglote.cdglote
      FROM progbloque,
        proglote
      WHERE progbloque.cdgbloque = proglote.cdgbloque
      AND (proglote.amplitud BETWEEN ".$prodProgramacion_amplitud." AND ".$prodProgramacion_tolerancia.")
      AND proglote.sttlote = '8'
      ORDER BY proglote.amplitud,
        progbloque.fchbloque,
        proglote.tarima,
        proglote.idlote");

    if ($progLoteSelect->num_rows > 0)
    { $idlote = 1;
      while ($regProgLote = $progLoteSelect->fetch_object())
      { $progLotes_idlote[$idlote] = $regProgLote->idlote;
        $progLotes_tarima[$idlote] = $regProgLote->tarima;
        $progLotes_lote[$idlote] = $regProgLote->lote;
        $progLotes_amplitud[$idlote] = $regProgLote->amplitud;
        $progLotes_longitud[$idlote] = $regProgLote->longitud;
        $progLotes_espesor[$idlote] = $regProgLote->espesor;
        $progLotes_encogimiento[$idlote] = $regProgLote->encogimiento;
        $progLotes_peso[$idlote] = $regProgLote->peso;
        $progLotes_cdglote[$idlote] = $regProgLote->cdglote;

        $idlote++; }

      $numproglotes = $progLoteSelect->num_rows; }    
    $progLoteSelect->close;    
  } else
  { $msg_alert = $msg_noread; }
  
  echo '
      <form id="form_prodprogramacion" name="form_prodprogramacion" method="POST" action="prodProgramacion.php">
        <table align="center">
          <thead>
            <tr><th colspan="2" align="left">'.$sistModulo_modulo.'</th></tr>
          </thead>
            <tr><td>
                <label for="lbl_cdgmezcla"><a href="../sm_producto/pdtoMezcla.php?cdgmezcla='.$prodProgramacion_cdgmezcla.'">Mezcla</a></label><br/>
                <select style="width:240px" id="slc_cdgmezcla" name="slc_cdgmezcla" onchange="document.form_prodprogramacion.submit()">';

    for ($idproyecto = 1; $idproyecto <= $idsproyecto; $idproyecto++)
    { echo '
                  <optgroup label="'.$pdtoProyecto_idproyecto[$idproyecto].'">';

      for ($idimpresion = 1; $idimpresion <= $idsimpresion[$idproyecto]; $idimpresion++)
      { for ($idmezcla = 1; $idmezcla <= $idsmezcla[$idproyecto][$idimpresion]; $idmezcla++)
        { echo '
                    <option value="'.$pdtoMezcla_cdgmezcla[$idproyecto][$idimpresion][$idmezcla].'"';

          if ($prodProgramacion_cdgmezcla == $pdtoMezcla_cdgmezcla[$idproyecto][$idimpresion][$idmezcla])
          { $prodProgramacion_corte = $pdtoImpresion_ncorte[$idproyecto][$idimpresion];
            $prodProgramacion_alpaso = $pdtoImpresion_nalpaso[$idproyecto][$idimpresion];

            echo ' selected="selected"'; }

          echo '>'.$pdtoImpresion_impresion[$idproyecto][$idimpresion].' '.$pdtoMezcla_mezcla[$idproyecto][$idimpresion][$idmezcla].' ('.$pdtoMezcla_idmezcla[$idproyecto][$idimpresion][$idmezcla].')</option>';
        }
      }

      echo '
                  </optgroup>';
    }

    echo '
                </select></td>
              <td><label for="lbl_fchprograma">Programa</label><br/>
                <input type="date" id="txt_fchprograma" name="txt_fchprograma" value="'.$prodProgramacion_fchprograma.'" title="Fecha de asignacion" onchange="document.form_prodprogramacion.submit()" style="width:140px;" required/>
                <input type="hidden" id="text_amplitud" name="text_amplitud" value="'.$prodProgramacion_amplitud.'" />
                <input type="hidden" id="text_tolerancia" name="text_tolerancia" value="'.$prodProgramacion_tolerancia.'" /></td></tr>
            <tr><td colspan="2"><label for="lbl_tolerancia">Rango de amplitud: <strong>'.$prodProgramacion_amplitud.' / '.$prodProgramacion_tolerancia.'</strong></label></td></tr>
          <tbody>
          <tfoot>
            <tr><td colspan="2" align="right"><input type="submit" id="btn_submit" name="btn_submit" value="Salvar" /></td></tr>
          </tfoot>
        </table><br/>';

    if ($numprodlotes > 0)
    { echo '
        <table align="center">
          <thead>
            <tr><th colspan="15">Bobinas asignadas a la fecha</th></tr>
            <tr><th colspan="2"><label for="lbl_ttltarimalote">Tarima : No. Lote</label></th>
              <th><label for="lbl_ttllote">Referencia</label></th>
              <th><label for="lbl_ttlamplitud">Amplitud</label></th>
              <th><label for="lbl_ttllongitud">Longitud</label></th>
              <th><label for="lbl_ttlespesor">Espesor</label></th>
              <th><label for="lbl_ttlencogimiento">Encogimiento</label></th>
              <th><label for="lbl_ttlpeso">Peso</label></th>
              <th><label for="lbl_ttlpeso">NoOP</label></th>
              <th colspan="3"><label for="lbl_ttlpeso">Impresi&oacute;n</label></th>
              <th><label for="lbl_ttlmillares">Millares</label></th>
              <th colspan="2"><label for="lbl_ttloperaciones">Operaciones</label></th></tr>
          </thead>
          <tbody>';

      for ($id_lote=1; $id_lote<=$numprodlotes; $id_lote++)
      { echo '
            <tr align="right">
              <td><strong>'.$prodProgramacions_tarima[$id_lote].'</strong></td>
              <td><strong>'.$prodProgramacions_idlote[$id_lote].'</strong></td>
              <td><strong>'.$prodProgramacions_lote[$id_lote].'</strong></td>
              <td>'.$prodProgramacions_amplitud[$id_lote].' <strong>mm</strong></td>
              <td>'.number_format($prodProgramacions_longitud[$id_lote],2).' <strong>mts</strong></td>
              <td>'.$prodProgramacions_espesor[$id_lote].' <strong>micras</strong></td>
              <td>'.$prodProgramacions_encogimiento[$id_lote].' <strong>%</strong></td>
              <td>'.number_format($prodProgramacions_peso[$id_lote],3).' <strong>kgs</strong></td>
              <td>'.$prodProgramacions_noop[$id_lote].'</td>
              <td align="left">'.$prodProgramacions_idproyecto[$id_lote].'</td>
              <td align="left">'.$prodProgramacions_idimpresion[$id_lote].'</td>
              <td align="left">'.$prodProgramacions_idmezcla[$id_lote].'</td>
              <td align="left">'.number_format((($prodProgramacions_longitud[$id_lote]*$prodProgramacions_alpaso[$id_lote])/$prodProgramacions_corte[$id_lote]),3).'</td>';
              
        if ((int)$prodProgramacions_sttlote[$id_lote] > 0)
        { echo '
              <td align="center"><a href="prodProgramacion.php?cdglote='.$prodProgramacions_cdglote[$id_lote].'&proceso=consumo">'.$png_button_blue_repeat.'</a></td>
              <td align="center"><a href="prodProgramacion.php?cdglote='.$prodProgramacions_cdglote[$id_lote].'&proceso=update">'.$png_power_blue.'</a></td>'; }
        else
        { echo '
              <td align="center"><a href="prodProgramacion.php?cdglote='.$prodProgramacions_cdglote[$id_lote].'&proceso=delete">'.$png_recycle_bin.'</a></td>
              <td align="center"><a href="prodProgramacion.php?cdglote='.$prodProgramacions_cdglote[$id_lote].'&proceso=update">'.$png_power_black.'</a></td>'; }

        echo '</tr>';
      }
    
      echo '
          </tbody>
          <tfoot>
            <tr><th colspan="15" align="right"><label for="lbl_ppgdatos">['.$numprodlotes.'] Bobinas programadas</label></th></tr>
          </tfoot>
        </table><br/>'; }
    
    if ($numproglotes > 0)
    { echo '        
        <table align="center">
          <thead>
            <tr><th colspan="10">Bobinas compatibles disponibles</th></tr>
            <tr align="left">
              <th colspan="3"><label for="lbl_ttlidlote">Tarima : No. Lote</label></th>
              <th><label for="lbl_ttllote">Referencia</label></th>
              <th><label for="lbl_ttlamplitud">Amplitud</label></th>
              <th><label for="lbl_ttllongitud">Longitud</label></th>
              <th><label for="lbl_ttlespesor">Espesor</label></th>
              <th><label for="lbl_ttlencogimiento">Encogimiento</label></th>
              <th><label for="lbl_ttlpeso">Peso</label></th>
              <th><label for="lbl_ttlmillares">Millares</label></th></tr>
          </thead>
          <tbody>';

      for ($idlote=1; $idlote<=$numproglotes; $idlote++)
      { echo '
            <tr align="right">
              <td><input type="checkbox" id="chk_'.$progLotes_cdglote[$idlote].'" name="chk_'.$progLotes_cdglote[$idlote].'" '.$chkbox[$progLotes_cdglote[$idlote]].'/></td>
              <td><strong>'.$progLotes_tarima[$idlote].'</strong></td>
              <td><strong>'.$progLotes_idlote[$idlote].'</strong></td>
              <td><strong>'.$progLotes_lote[$idlote].'</strong></td>
              <td>'.number_format($progLotes_amplitud[$idlote]).' <strong>mm</strong></td>
              <td>'.number_format($progLotes_longitud[$idlote],2).' <strong>mts</strong></td>
              <td>'.number_format($progLotes_espesor[$idlote]).' <strong>micras</strong></td>
              <td>'.$progLotes_encogimiento[$idlote].' <strong>%</strong></td>
              <td>'.number_format($progLotes_peso[$idlote],3).' <strong>kgs</strong></td>
              <td>'.number_format((($progLotes_longitud[$idlote]*$prodProgramacion_alpaso)/$prodProgramacion_corte),3).'</td>';
      }
    
    echo '
          </tbody>
          <tfoot>
            <tr><th colspan="10" align="right"><label for="lbl_ppgdisponibilidad">['.$numproglotes.'] Bobinas disponibles</label></th></tr>
          </tfoot>
        </table>'; }

    echo '
      </form>';

    if ($msg_alert != '')
    { echo '
      <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  }
  else
  { echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
?>

  </body>
</html>
