<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';



  $progPrograma_cdgmezcla = $_POST['slc_cdgmezcla'];
  $progPrograma_fchprograma = trim($_POST['txt_fchprograma']);  

  if ($progPrograma_fchprograma == '')
  { $progPrograma_fchprograma = date('Y-m-d'); }
  else
  { $fchprograma = str_replace("-", "", $progPrograma_fchprograma);
    
    $dia = str_pad(substr($fchprograma,6,2),2,'0',STR_PAD_LEFT);
    $mes = str_pad(substr($fchprograma,4,2),2,'0',STR_PAD_LEFT);
    $ano = '20'.str_pad(substr($fchprograma,2,2),2,'0',STR_PAD_LEFT);
          
    if (checkdate((int)$mes,(int)$dia,(int)$ano)) 
    { $progPrograma_fchprograma = $ano.'-'.$mes.'-'.$dia; }
    else
    { $progPrograma_fchprograma = date('Y-m-d'); }
  }

  if ($_GET['cdglote'])
  { $link_mysqli = conectar();
    $prodLoteSelect = $link_mysqli->query("
      SELECT * FROM prodlote
      WHERE cdglote = '".$_GET['cdglote']."'");
    
    if ($prodLoteSelect->num_rows > 0)
    { $regProdLote = $prodLoteSelect->fetch_object();

      $progPrograma_cdglote = $regProdLote->cdglote;
      $progPrograma_fchprograma = $regProdLote->fchprograma;
      $progPrograma_longitud = $regProdLote->longitud;
      $progPrograma_noop = $regProdLote->noop;
      $progPrograma_cdgmezcla = $regProdLote->cdgmezcla;
      $progPrograma_sttlote = $regProdLote->sttlote;

      if ($_GET['proceso'] == 'update')
      { if ($progPrograma_sttlote == 'A')
        { $progPrograma_newsttlote = 'D'; }
      
        if ($progPrograma_sttlote == 'D')
        { $progPrograma_newsttlote = 'A'; }
        
        if ($progPrograma_newsttlote != '')
        { $link_mysqli = conectar();
          $link_mysqli->query("
            UPDATE prodlote
            SET sttlote = '".$progPrograma_newsttlote."' 
            WHERE cdglote = '".$progPrograma_cdglote."'");
          
          if ($link_mysqli->affected_rows > 0)
          { $msg_alert .= 'El lote fue actualizado en su status.'; }
        }
      }
/*
      if ($_GET['proceso'] == 'consumo')
      { $link_mysqli = conectar();
        $prodLoteOpeSelect = $link_mysqli->query("
          SELECT * FROM prodloteope
          WHERE cdglote = '".$progPrograma_cdglote."'
          AND cdgoperacion NOT LIKE '00090'");

        if ($prodLoteOpeSelect->num_rows > 0)
        { $msg_alert = 'El lote NO fue recalculado en sus consumos por que ya fue afectado en Produccion.'; }
        else
        { $link_mysqli = conectar();
          $link_mysqli->query("
            DELETE FROM prodconsumo
            WHERE cdglote = '".$progPrograma_cdglote."'");

          // Busqueda de la impresion
          $link_mysqli = conectar();
          $pdtoImpresionSelect = $link_mysqli->query("
            SELECT pdtoimpresion.corte,
              pdtoimpresion.alpaso
            FROM pdtoimpresion,
              pdtomezcla 
            WHERE pdtoimpresion.cdgimpresion = pdtomezcla.cdgimpresion
            AND pdtomezcla.cdgmezcla = '".$progPrograma_cdgmezcla."'");

          if ($pdtoImpresionSelect->num_rows > 0)
          { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();
            $pdtoImpresion_corte = $regPdtoImpresion->corte;
            $pdtoImpresion_alpaso = $regPdtoImpresion->alpaso; }

          $pdtoImpresionSelect->close;
          //////////////////////////////////////////////////////////////

          // Consumos de la impresión
          $link_mysqli = conectar();
          $pdtoConsumoSelect = $link_mysqli->query("
            SELECT * FROM pdtoconsumo 
            WHERE cdgmezcla = '".$progPrograma_cdgmezcla."'");

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
                 ('".$progPrograma_cdglote."', '".$pdtoConsumo_cdgelemento."', '".$progPrograma_cdgmezcla."', '".$pdtoConsumo_cdgproceso."', '".$pdtoConsumo_cdgmateria."', '".((($progPrograma_longitud*$pdtoImpresion_alpaso)/$pdtoImpresion_corte)*$pdtoConsumo_consumo)."')"); } 
          }
          //////////////////////////////////////////////////////////////
            
          $msg_alert = 'El lote fue recalculado en sus consumos.';
        }
      }      
//*/
      if ($_GET['proceso'] == 'delete')
      { $link_mysqli = conectar();
        $prodLoteOpeSelect = $link_mysqli->query("
          SELECT * FROM prodloteope
          WHERE cdglote = '".$progPrograma_cdglote."'
          AND cdgoperacion NOT LIKE '00090'");

        if ($prodLoteOpeSelect->num_rows > 0)
        { $msg_alert = 'La NoOP '.$progPrograma_noop.' NO fue eliminado de la programacion por que ya fue afectado en Produccion.'; }
        else
        { $link_mysqli = conectar();
          $link_mysqli->query("
            DELETE FROM prodlote
            WHERE cdglote = '".$progPrograma_cdglote."'
            AND sttlote = 'D'");

          if ($link_mysqli->affected_rows > 0)
          { $link_mysqli = conectar();
            $link_mysqli->query("
              DELETE FROM prodproclote
              WHERE cdglote = '".$progPrograma_cdglote."'");

            $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE proglote 
              SET sttlote = '8'
              WHERE cdglote = '".$progPrograma_cdglote."'");

            if ($link_mysqli->affected_rows > 0)
            { $msg_alert = 'NoOp '.$progPrograma_noop.' eliminada \n'; }
          }
        }
      }      
    } 
  } 

  if ($_POST['btn_submit'])
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
      AND (proglote.amplitud BETWEEN ".$_SESSION['progPrograma_amplitud']." AND ".$_SESSION['progPrograma_tolerancia'].")
      AND proglote.sttlote = '8'
      ORDER BY proglote.amplitud,
        progbloque.fchbloque,
        proglote.tarima,
        proglote.idlote"); 

    if ($progLoteSelect->num_rows > 0)
    { // Busqueda de la impresion
      $link_mysqli = conectar();
      $pdtoImpresionSelect = $link_mysqli->query("
        SELECT pdtoimpresion.corte,
          pdtoimpresion.alpaso
        FROM pdtoimpresion,
          pdtomezcla 
        WHERE pdtoimpresion.cdgimpresion = pdtomezcla.cdgimpresion
        AND pdtomezcla.cdgmezcla = '".$progPrograma_cdgmezcla."'");

      if ($pdtoImpresionSelect->num_rows > 0)
      { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();
        $pdtoImpresion_corte = $regPdtoImpresion->corte;
        $pdtoImpresion_alpaso = $regPdtoImpresion->alpaso; }

      $pdtoImpresionSelect->close;
      //////////////////////////////////////////////////////////////

      // Consumos de la impresión
      $link_mysqli = conectar();
      $pdtoConsumoSelect = $link_mysqli->query("
        SELECT * FROM pdtoconsumo 
        WHERE cdgmezcla = '".$progPrograma_cdgmezcla."'");

      if ($pdtoConsumoSelect->num_rows > 0)
      { $idconsumo = 1;
        while ($regPdtoConsumo = $pdtoConsumoSelect->fetch_object())
        { $pdtoConsumo_cdgproceso[$idconsumo] = $regPdtoConsumo->cdgproceso;
          $pdtoConsumo_cdgmateria[$idconsumo] = $regPdtoConsumo->cdgmateria;
          $pdtoConsumo_consumo[$idconsumo] = $regPdtoConsumo->consumo;
          $pdtoConsumo_cdgelemento[$idconsumo] = $regPdtoConsumo->cdgelemento;

          $idconsumo++; }

        $idsconsumo = $pdtoConsumoSelect->num_rows; }
      //////////////////////////////////////////////////////////////

      while ($regProgLote = $progLoteSelect->fetch_object())
      { if (isset($_REQUEST['chk_'.$regProgLote->cdglote]))
        { $progPrograma_cdglote = $regProgLote->cdglote;
          $progPrograma_longitud = $regProgLote->longitud;
          $progPrograma_peso = $regProgLote->peso;

          $link_mysqli = conectar();
          $progLoteSelectMax = $link_mysqli->query("
            SELECT MAX(noop) AS noopmax,
            COUNT(noop) AS noopnum
            FROM prodlote");

          $regProdLoteMax = $progLoteSelectMax->fetch_object();
          $progPrograma_noopmax = (int)$regProdLoteMax->noopmax;
          $progPrograma_noopnum = (int)$regProdLoteMax->noopnum;

          // Depurar consumos por lote
          $link_mysqli = conectar();
          $link_mysqli->query("
            DELETE FROM prodconsumo
            WHERE cdglote = '".$progPrograma_cdglote."'");
          //////////////////////////////////////////////////////////////

          // Insertar bobina en la programación
          if ($progPrograma_noopmax == $progPrograma_noopnum)
          { $progPrograma_noop = $progPrograma_noopmax+1;

            $link_mysqli = conectar();
            $link_mysqli->query("
              INSERT INTO prodproclote
                (cdglote, cdgproceso, longitud, peso, fchmovimiento)
              VALUES
                ('".$progPrograma_cdglote."', '00', '".$progPrograma_longitud."', '".$progPrograma_peso."', NOW())"); 

            $link_mysqli = conectar();
            $link_mysqli->query("
              INSERT INTO prodlote
                (cdglote, noop, cdgmezcla, longitud, peso, fchprograma, fchmovimiento)
              VALUES
                ('".$progPrograma_cdglote."', '".$progPrograma_noop."', '".$progPrograma_cdgmezcla."', '".$progPrograma_longitud."', '".$progPrograma_peso."', '".$progPrograma_fchprograma."', NOW())");
          } 
          else
          { for ($noop = 1; $noop < $progPrograma_noopmax; $noop++) 
            { $link_mysqli = conectar();
              $link_mysqli->query("
              INSERT INTO prodproclote
                (cdglote, cdgproceso, longitud, peso, fchmovimiento)
              VALUES
                ('".$progPrograma_cdglote."', '00', '".$progPrograma_longitud."', '".$progPrograma_peso."', NOW())"); 

              $link_mysqli = conectar();
              $link_mysqli->query("
              INSERT INTO prodlote
                (cdglote, noop, cdgmezcla, longitud, peso, fchprograma, fchmovimiento)
              VALUES
                ('".$progPrograma_cdglote."', '".$noop."', '".$progPrograma_cdgmezcla."', '".$progPrograma_longitud."', '".$progPrograma_peso."', '".$progPrograma_fchprograma."', NOW())");  

              if ($link_mysqli->affected_rows > 0)
              { $progPrograma_noop = $noop;
                $noop = $progPrograma_noopmax; }
            }
          }
          //////////////////////////////////////////////////////////////

          if ($link_mysqli->affected_rows > 0)
          { $link_mysqli = conectar(); 
            $link_mysqli->query("
              UPDATE proglote 
              SET sttlote = '9'
              WHERE cdglote = '".$progPrograma_cdglote."'"); 

            if ($link_mysqli->affected_rows > 0)
            { $msg_alert .= 'NoOp '.$progPrograma_noop.' generada. \n'; 

              for ($idconsumo = 1; $idconsumo <= $idsconsumo; $idconsumo++)
              { $link_mysqli = conectar();
                $link_mysqli->query("
                INSERT INTO prodconsumo
                  (cdglote, cdgelemento, cdgmezcla, cdgproceso, cdgmateria, consumo)
                VALUES
                  ('".$progPrograma_cdglote."', '".$pdtoConsumo_cdgelemento[$idconsumo]."', '".$progPrograma_cdgmezcla."', '".$pdtoConsumo_cdgproceso[$idconsumo]."', '".$pdtoConsumo_cdgmateria[$idconsumo]."', '".((($progPrograma_longitud*$pdtoImpresion_alpaso)/$pdtoImpresion_corte)*$pdtoConsumo_consumo[$idconsumo])."')"); }
            }
          }
          else
          { $msg_alert .= 'NoOp '.$progPrograma_noop.' NO generada. \n'; }
        }
      }
    } 
  } 
  
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

  echo '
    <form id="frm_progprograma" name="frm_progprograma" method="POST" action="progPrograma.php">
      <table align="center">
        <thead>
          <tr><th colspan="2" align="left">Asignaci&oacute;n de PVC por d&iacute;a</th></tr>
        </thead>
          <tr><td>
              <label for="lbl_cdgmezcla"><a href="../sm_producto/pdtoMezcla.php?cdgmezcla='.$progPrograma_cdgmezcla.'">Mezcla</a></label><br/>
              <select style="width:240px" id="slc_cdgmezcla" name="slc_cdgmezcla" onchange="document.frm_progprograma.submit()">';
    
  for ($idproyecto = 1; $idproyecto <= $idsproyecto; $idproyecto++) 
  { echo '
                <optgroup label="'.$pdtoProyecto_idproyecto[$idproyecto].'">';

    for ($idimpresion = 1; $idimpresion <= $idsimpresion[$idproyecto]; $idimpresion++) 
    { for ($idmezcla = 1; $idmezcla <= $idsmezcla[$idproyecto][$idimpresion]; $idmezcla++) 
      { echo '
                  <option value="'.$pdtoMezcla_cdgmezcla[$idproyecto][$idimpresion][$idmezcla].'"';
            
        if ($progPrograma_cdgmezcla == $pdtoMezcla_cdgmezcla[$idproyecto][$idimpresion][$idmezcla]) 
        { echo ' selected="selected"'; 

          $_SESSION['progPrograma_amplitud'] = $pdtoImpresion_amplitud[$idproyecto][$idimpresion]; 
          $_SESSION['progPrograma_tolerancia'] = $pdtoImpresion_tolerancia[$idproyecto][$idimpresion]; }

        echo '>'.$pdtoImpresion_impresion[$idproyecto][$idimpresion].' '.$pdtoMezcla_mezcla[$idproyecto][$idimpresion][$idmezcla].' ('.$pdtoMezcla_idmezcla[$idproyecto][$idimpresion][$idmezcla].')</option>'; 
      }
    }

    echo '
                </optgroup>';
  }
    
  echo '
              </select></td>
            <td><label for="lbl_fchprograma">Programa</label><br/>
              <input type="date" style="width:100px;" maxlength="24" id="txt_fchprograma" name="txt_fchprograma" value="'.$progPrograma_fchprograma.'" title="Fecha del programa" required/></td></tr>
          <tr><td colspan="2"><label for="lbl_tolerancia">Rango de amplitud: <strong>'.$_SESSION['progPrograma_amplitud'].' / '.$_SESSION['progPrograma_tolerancia'].'</strong></label></td></tr>
        <tbody>
        <tfoot>
          <tr><td colspan="2" align="right"><input type="submit" id="btn_submit" name="btn_submit" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>';

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
    AND prodlote.fchprograma = '".$progPrograma_fchprograma."'
    ORDER BY prodlote.sttlote DESC,
      prodlote.noop");

  if ($prodLoteSelect->num_rows > 0)
  { $id_lote = 1;
    while ($regProdLote = $prodLoteSelect->fetch_object())
    { $prodLotes_idlote[$id_lote] = $regProdLote->idlote;
      $prodLotes_lote[$id_lote] = $regProdLote->lote;
      $prodLotes_tarima[$id_lote] = $regProdLote->tarima;
      $prodLotes_amplitud[$id_lote] = $regProdLote->amplitud;
      $prodLotes_longitud[$id_lote] = $regProdLote->longitud;
      $prodLotes_espesor[$id_lote] = $regProdLote->espesor;
      $prodLotes_encogimiento[$id_lote] = $regProdLote->encogimiento;
      $prodLotes_peso[$id_lote] = $regProdLote->peso;
      $prodLotes_noop[$id_lote] = $regProdLote->noop;
      $prodLotes_idproyecto[$id_lote] = $regProdLote->idproyecto;
      $prodLotes_idimpresion[$id_lote] = $regProdLote->idimpresion;
      $prodLotes_idmezcla[$id_lote] = $regProdLote->idmezcla;
      $prodLotes_cdglote[$id_lote] = $regProdLote->cdglote;
      $prodLotes_sttlote[$id_lote] = $regProdLote->sttlote;

      $id_lote++; }

    $numlotes = $prodLoteSelect->num_rows; }
  else
  { $numlotes = 0; }

  $prodLoteSelect->close;

  echo '    
      <table align="center">
        <thead>
            <th colspan="2"><label for="lbl_ttltarimalote">Tarima : No. Lote</label></th>
            <th><label for="lbl_ttllote">Referencia</label></th>            
            <th><label for="lbl_ttlamplitud">Amplitud</label></th>
            <th><label for="lbl_ttllongitud">Longitud</label></th>
            <th><label for="lbl_ttlespesor">Espesor</label></th>
            <th><label for="lbl_ttlencogimiento">Encogimiento</label></th>
            <th><label for="lbl_ttlpeso">Peso</label></th>
            <th><label for="lbl_ttlpeso">NoOP</label></th>
            <th colspan="3"><label for="lbl_ttlpeso">Impresi&oacute;n</label></th>
            <th colspan="2"><label for="lbl_ttloperaciones">Operaciones</label></th></tr>
        </thead>
        <tbody>';

  if ($numlotes > 0)
  { for ($id_lote=1; $id_lote<=$numlotes; $id_lote++)
    { echo '
          <tr align="right">
            <td><strong>'.$prodLotes_tarima[$id_lote].'</strong></td>
            <td><strong>'.$prodLotes_idlote[$id_lote].'</strong></td>
            <td><strong>'.$prodLotes_lote[$id_lote].'</strong></td>            
            <td>'.$prodLotes_amplitud[$id_lote].' <strong>mm</strong></td>
            <td>'.$prodLotes_longitud[$id_lote].' <strong>mts</strong></td>
            <td>'.$prodLotes_espesor[$id_lote].' <strong>micras</strong></td>
            <td>'.$prodLotes_encogimiento[$id_lote].' <strong>%</strong></td>
            <td>'.$prodLotes_peso[$id_lote].' <strong>kgs</strong></td>
            <td>'.$prodLotes_noop[$id_lote].'</td>
            <td align="left">'.$prodLotes_idproyecto[$id_lote].'</td>
            <td align="left">'.$prodLotes_idimpresion[$id_lote].'</td>
            <td align="left">'.$prodLotes_idmezcla[$id_lote].'</td>';
      if ((int)$prodLotes_sttlote[$id_lote] > 0)
      { echo '
            <td align="center"><a href="progPrograma.php?cdglote='.$prodLotes_cdglote[$id_lote].'&proceso=consumo">'.$png_button_blue_repeat.'</a></td>
            <td align="center"><a href="progPrograma.php?cdglote='.$prodLotes_cdglote[$id_lote].'&proceso=update">'.$png_power_blue.'</a></td>'; }
      else
      { echo '
            <td align="center"><a href="progPrograma.php?cdglote='.$prodLotes_cdglote[$id_lote].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td align="center"><a href="progPrograma.php?cdglote='.$prodLotes_cdglote[$id_lote].'&proceso=update">'.$png_power_black.'</a></td>'; }
        
      echo '</tr>';
    }
  }

  echo '
        </tbody>
        <tfoot>
          <tr><th colspan="14" align="right"><label for="lbl_ppgdatos">['.$numlotes.'] Bobinas programadas</label></th></tr>
        </tfoot>
      </table><br/>';

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
    AND (proglote.amplitud BETWEEN ".$_SESSION['progPrograma_amplitud']." AND ".$_SESSION['progPrograma_tolerancia'].")
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

    $numlotes = $progLoteSelect->num_rows; }
  else
  { $numlotes = 0; }
  $progLoteSelect->close; 

  echo'    
      <table align="center">
        <thead>
          <tr align="left">
            <th colspan="3"><label for="lbl_ttlidlote">Tarima : No. Lote</label></th>
            <th><label for="lbl_ttllote">Referencia</label></th>            
            <th><label for="lbl_ttlamplitud">Amplitud</label></th>
            <th><label for="lbl_ttllongitud">Longitud</label></th>            
            <th><label for="lbl_ttlespesor">Espesor</label></th>
            <th><label for="lbl_ttlencogimiento">Encogimiento</label></th>
            <th><label for="lbl_ttlpeso">Peso</label></th></tr>
        </thead>
        <tbody>';

  if ($numlotes > 0)
  { for ($idlote=1; $idlote<=$numlotes; $idlote++)
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
            <td>'.number_format($progLotes_peso[$idlote],3).' <strong>kgs</strong></td>';
    }      
  }
  
  echo '
        </tbody>
        <tfoot>
          <tr><th colspan="9" align="right"><label for="lbl_ppgdisponibilidad">['.$numlotes.'] Bobinas disponibles</label></th></tr>
        </tfoot>
      </table>
    </form>'; 

  if ($msg_alert != '')
  { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }

?>

  </body>	
</html>