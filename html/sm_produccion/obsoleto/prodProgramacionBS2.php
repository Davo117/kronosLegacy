<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Programación de Banda de seguridad</title>    
    <link rel="stylesheet" type="text/css" href="../css/2014.css" media="screen" />
  </head>
  <body>
    <div id="contenedor">
<?php
  
  include '../datos/mysql.php';
  $link = conectar();

  m3nu_produccion();  

  $sistModulo_cdgmodulo = '61011';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { if ($_GET['mode']=='logout') { cl0s3(); }

    if ($_POST['textusername'] AND $_POST['textpassword']) { val1dat3($_POST['textusername'], $_POST['textpassword']); }

    if ($_SESSION['cdgusuario'])
    { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

      ma1n(); 
    } else
    { echo '
      <div id="loginform">
        <form id="login" action="prodProgramacionBS2.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 
     
      exit; }    
    // Captura de parametros para programación
    $prodProgramacion_cdgproducto = $_POST['slctCdgProducto'];
    $prodProgramacion_cdgmaquina = $_POST['slctCdgMaquina'];    
    $prodProgramacion_fchprograma = trim($_POST['dateFchPrograma']);    
    //////////////////////////////////////////

    /* Determinar que la fecha sea válida */
    if ($prodProgramacion_fchprograma == '')
    { $prodProgramacion_fchprograma = date('Y-m-d');
      $prodProgramacion_cdgfchprograma = date('ymd'); }
    else
    { $fchprograma = str_replace("-", "", $prodProgramacion_fchprograma);

      $dia = str_pad(substr($fchprograma,6,2),2,'0',STR_PAD_LEFT);
      $mes = str_pad(substr($fchprograma,4,2),2,'0',STR_PAD_LEFT);
      $ano = str_pad(substr($fchprograma,0,4),4,'0',STR_PAD_LEFT);
      $anos = str_pad(substr($fchprograma,2,2),2,'0',STR_PAD_LEFT);

      if (checkdate((int)$mes,(int)$dia,(int)$ano))
      { $prodProgramacion_fchprograma = $ano.'-'.$mes.'-'.$dia; 
        $prodProgramacion_cdgfchprograma = $anos.$mes.$dia;  }
      else
      { $prodProgramacion_fchprograma = date('Y-m-d');
        $prodProgramacion_cdgfchprograma = date('ymd'); }
    }
    // Final de la validación en la fecha

    // Trabajar con un lote especifico
    if ($_GET['cdglote'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $prodLoteSelect = $link->query("
          SELECT * FROM prodlote
           WHERE cdglote = '".$_GET['cdglote']."'");

        if ($prodLoteSelect->num_rows > 0)
        { $regProdLote = $prodLoteSelect->fetch_object();

          $prodProgramacion_cdglote = $regProdLote->cdglote;          
          $prodProgramacion_noop = $regProdLote->noop;
          $prodProgramacion_cdgproducto = $regProdLote->cdgproducto;
          $prodProgramacion_fchprograma = $regProdLote->fchprograma;               
          $prodProgramacion_sttlote = $regProdLote->sttlote;

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($prodProgramacion_sttlote == 'A')
              { $prodProgramacion_newsttlote = 'D'; }

              if ($prodProgramacion_sttlote == 'D')
              { $prodProgramacion_newsttlote = 'A'; }

              if ($prodProgramacion_newsttlote != '')
              { $link->query("
                  UPDATE prodlote
                     SET sttlote = '".$prodProgramacion_newsttlote."'
                   WHERE cdglote = '".$prodProgramacion_cdglote."'");

                if ($link->affected_rows > 0)
                { $msg_alert .= 'El NoOP '.$prodProgramacion_noop.' fue actualizado en su status.'; }
              } else
              { $msg_alert .= 'El NoOP '.$prodProgramacion_noop.' NO fue actualizado en su status, ya se encuentra en otro proceso.'; }
            } else
            { $msg_alert = $msg_norewrite.' (Status)'; }
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $prodLoteOpeSelect = $link->query("
                SELECT * FROM prodloteope
                 WHERE cdglote = '".$prodProgramacion_cdglote."' AND 
                      (cdgoperacion NOT LIKE '00090' AND cdgoperacion NOT LIKE '10001')");

              if ($prodLoteOpeSelect->num_rows > 0)
              { $msg_alert = 'El NoOP '.$prodProgramacion_noop.' NO fue eliminado de la programacion por que ya fue afectado en Produccion.'; }
              else
              { $link->query("
                  DELETE FROM prodlote
                   WHERE cdglote = '".$prodProgramacion_cdglote."' AND 
                         sttlote = 'D'");

                if ($link->affected_rows > 0)
                { $link->query("
                    DELETE FROM prodloteope
                     WHERE cdglote = '".$prodProgramacion_cdglote."' AND
                           cdgoperacion NOT LIKE '00090'");

                  $link->query("
                    UPDATE proglote
                       SET sttlote = '7'
                     WHERE cdglote = '".$prodProgramacion_cdglote."'");

                  if ($link->affected_rows > 0)
                  { $msg_alert = 'El NoOp '.$prodProgramacion_noop.' fue eliminado \n'; }                  
                } else
                { $msg_alert = 'El NoOp '.$prodProgramacion_noop.' NO fue eliminado \n'; }
              }
            } else
            { $msg_alert = $msg_nodelete; }
          }
        } else
        { $msg_alert = "El NoOP no existe, o fue cancelado."; }
      } else
      { $msg_alert = $msg_noread; }
    } 
    //////////////////////////////////////////

    if (substr($sistModulo_permiso,0,1) == 'r')
    { // Filtro de bandas de segurydad activas por banda
      $pdtoBandaPSelect = $link->query("
        SELECT pdtobandap.idbandap,
               pdtobandap.bandap,
               pdtobandap.cdgsustrato,
               pdtobandap.cdgbandap
          FROM pdtobandap
         WHERE pdtobandap.preembosado = '1' AND 
               pdtobandap.sttbandap = '1'
      ORDER BY pdtobandap.idbandap");

      if ($pdtoBandaPSelect->num_rows > 0)
      { $item = 0;

        while ($regPdtoBandaP = $pdtoBandaPSelect->fetch_object())
        { $item++;

          $pdtoBandaP_idbandap[$item] = $regPdtoBandaP->idbandap;
          $pdtoBandaP_bandap[$item] = $regPdtoBandaP->bandap;
          $pdtoBandaP_cdgbandap[$item] = $regPdtoBandaP->cdgbandap;

          $pdtoBandaPs_bandap[$regPdtoBandaP->cdgbandap] = $regPdtoBandaP->bandap; 
          
          if ($prodProgramacion_cdgproducto == $regPdtoBandaP->cdgbandap)
          { $prodProgramacion_cdgsustrato = $regPdtoBandaP->cdgsustrato; 
            $prodProgramacion_anchura = $regPdtoBanda->anchura; }
        }

        $nBandaPs = $item; }
      // Fin del filtro de bandas de seguridad por banda      

      // Filtro de máquinas
      $prodMaquinaSelect = $link->query("
        SELECT * FROM prodmaquina
         WHERE cdgsubproceso = '008'");

      if ($prodMaquinaSelect->num_rows > 0)
      { $item = 0;

        while ($regProdMaquina = $prodMaquinaSelect->fetch_object())
        { $item++;

          $prodMaquina_idmaquina[$item] = $regProdMaquina->idmaquina;
          $prodMaquina_maquina[$item] = $regProdMaquina->maquina;
          $prodMaquina_cdgmaquina[$item] = $regProdMaquina->cdgmaquina; 

          $prodMaquinas_maquina[$regProdMaquina->cdgmaquina] = $regProdMaquina->maquina; }

        $nMaquinas = $item; }
      // Fin del filtro de máquinas

      // Final del filtro de sustrato disponible para programar        
    } //else
    //{ $msg_alert = $msg_noread; }

    // Asignar lotes seleccionados
    if ($_POST['bttnSalvar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { $progLoteSelect = $link->query("
          SELECT proglote.idlote,
                 proglote.tarima,
                 proglote.lote,
                 proglote.longitud,
                 pdtosustrato.anchura,
                 proglote.peso,
                 proglote.cdglote
            FROM progbloque,
                 proglote,
                 pdtosustrato
          WHERE (progbloque.cdgbloque = proglote.cdgbloque AND
                 proglote.sttlote = '7') AND
                (progbloque.cdgsustrato = '".$prodProgramacion_cdgsustrato."') AND
                (pdtosustrato.cdgsustrato = progbloque.cdgsustrato)");

        if ($progLoteSelect->num_rows > 0)
        { while ($regProgLote = $progLoteSelect->fetch_object())
          { if (isset($_REQUEST['chck'.$regProgLote->cdglote]))
            { $prodProgramacion_cdglote = $regProgLote->cdglote;
              $prodProgramacion_longitud = $regProgLote->longitud;
              $prodProgramacion_peso = $regProgLote->peso;
              $prodProgramacion_anchural = $regProgLote->anchura;

              $prodProgramacion_programado = (($prodProgramacion_anchural/$prodProgramacion_anchura)*$prodProgramacion_longitud);

              $progLoteSelectMax = $link->query("
                SELECT MAX(noop) AS noopmax,
                       COUNT(noop) AS noopnum
                  FROM prodlote
                 WHERE serie = 'BS".date('y')."'");

              
              $regProdLoteMax = $progLoteSelectMax->fetch_object();
              $prodProgramacion_noopmax = (int)$regProdLoteMax->noopmax;
              $prodProgramacion_noopnum = (int)$regProdLoteMax->noopnum;

              // Insertar lote en la programación
              if ($prodProgramacion_noopmax == $prodProgramacion_noopnum)
              { $prodProgramacion_noop = $prodProgramacion_noopmax+1;

                $link->query("
                  INSERT INTO prodlote
                    (cdglote, serie, noop, cdgproducto, longitud, amplitud, peso, fchprograma, programado, fchmovimiento)
                  VALUES
                    ('".$prodProgramacion_cdglote."', 'BS".date('y')."', '".$prodProgramacion_noop."', '".$prodProgramacion_cdgproducto."', '".$prodProgramacion_longitud."', '".$prodProgramacion_anchural."', '".$prodProgramacion_peso."', '".$prodProgramacion_fchprograma."', '".$prodProgramacion_programado."', NOW())");
              } else
              { for ($noop = 1; $noop < $prodProgramacion_noopmax; $noop++)
                { $prodProgramacion_noop = $noop;
                    
                  $link->query("
                    INSERT INTO prodlote
                      (cdglote, serie, noop, cdgproducto, longitud, amplitud, peso, fchprograma, programado, fchmovimiento)
                    VALUES
                      ('".$prodProgramacion_cdglote."', 'BS".date('y')."', '".$prodProgramacion_noop."', '".$prodProgramacion_cdgproducto."', '".$prodProgramacion_longitud."', '".$prodProgramacion_anchural."', '".$prodProgramacion_peso."', '".$prodProgramacion_fchprograma."', '".$prodProgramacion_programado."', NOW())");

                  if ($link->affected_rows > 0)
                  { $noop = $prodProgramacion_noopmax; }
                }
              }

              // Registrar la operación
              $link->query("
                INSERT INTO prodloteope
                  (cdglote, cdgoperacion, cdgempleado, cdgmaquina, cdgjuego, longin, longout, fchoperacion, fchmovimiento)
                VALUES
                  ('".$prodProgramacion_cdglote."', '10001', '".$_SESSION['cdgusuario']."', '".$prodProgramacion_cdgmaquina."', '".$prodProgramacion_cdgjuego."', '".$prodProgramacion_longitud."', '".$prodProgramacion_longitud."', NOW(), NOW())");

              if ($link->affected_rows > 0)
              { $link->query("
                  UPDATE proglote
                     SET sttlote = '8'
                   WHERE cdglote = '".$prodProgramacion_cdglote."'");

                if ($link->affected_rows > 0)
                { $msg_alert .= 'El NoOp '.$prodProgramacion_noop.' fue generado. \n'; } 
                else
                { $link->query("
                    DELETE FROM prodloteope
                     WHERE cdglote = '".$prodProgramacion_cdglote."' AND
                           cdgoperacion = '10001'");
                  
                  $link->query("
                    DELETE * FROM prodlote
                     WHERE cdglote = '".$prodProgramacion_cdglote."'"); 
                    
                  $msg_alert .= 'El NoOp '.$prodProgramacion_noop.' NO fue generado (Lote). \n'; }
              } else
              { $msg_alert .= 'El NoOp '.$prodProgramacion_noop.' NO fue generado. \n'; }
              // Final del registro de la operación
            }
          }
        } 
      } else
      { $msg_alert = $msg_norewrite; }
    } 



      // Filtro de programaciones
      $progBloqueSelect = $link->query("
        SELECT prodlote.cdgproducto,
               pdtobanda.anchura,
               pdtobanda.cdgbanda,
         COUNT(prodlote.cdglote) AS nlotes,
               prodloteope.cdgmaquina,
           SUM(prodloteope.longout) AS longitud,
               pdtosustrato.anchura AS anchural
          FROM prodlote,
               prodloteope,
               pdtobanda,
               pdtobandap,
               pdtosustrato
        WHERE (prodlote.cdglote = prodloteope.cdglote AND
               prodloteope.cdgoperacion = '10001') AND
              (prodlote.cdgproducto = pdtobandap.cdgbandap AND
               pdtobandap.preembosado = '1' AND
               pdtobandap.cdgbanda = pdtobanda.cdgbanda AND
               pdtosustrato.cdgsustrato = pdtobandap.cdgsustrato) AND
               prodlote.fchprograma = '".$prodProgramacion_fchprograma."'
      GROUP BY prodlote.cdgproducto,
               prodloteope.cdgmaquina");

      if ($progBloqueSelect->num_rows > 0)
      { $item = 0;

        while ($regProgBloque = $progBloqueSelect->fetch_object())
        { $item++;

          $progBloque_cdgbanda[$item] = $regProgBloque->cdgbanda;
          $progBloque_cdgproducto[$item] = $regProgBloque->cdgproducto;            
          $progBloque_anchura[$item] = $regProgBloque->anchura;
          $progBloque_nlotes[$item] = $regProgBloque->nlotes;
          $progBloque_cdgmaquina[$item] = $regProgBloque->cdgmaquina;
          $progBloque_anchural[$item] = $regProgBloque->anchural;
          $progBloque_programado[$item] = (($regProgBloque->anchural/$regProgBloque->anchura)*$regProgBloque->longitud);

          $progLoteSelect = $link->query("
            SELECT prodlote.noop,
                   proglote.tarima,
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
                  (prodlote.fchprograma = '".$prodProgramacion_fchprograma."' AND
                   prodlote.cdgproducto = '".$regProgBloque->cdgproducto."') AND
                  (prodloteope.cdgoperacion = '10001' AND 
                   prodloteope.cdgmaquina = '".$regProgBloque->cdgmaquina."')");

          if ($progLoteSelect->num_rows > o)
          { $subItem = 0;

            while ($regProgLote = $progLoteSelect->fetch_object())
            { $subItem++;

              $progLote_noop[$item][$subItem] = $regProgLote->noop;
              $progLote_tarima[$item][$subItem] = $regProgLote->tarima;
              $progLote_lote[$item][$subItem] = $regProgLote->lote;
              $progLote_longitud[$item][$subItem] = $regProgLote->longitud;
              $progLote_anchural[$item][$subItem] = $regProgLote->anchura;
              $progLote_peso[$item][$subItem] = $regProgLote->peso;
              $progLote_cdglote[$item][$subItem] = $regProgLote->cdglote;
              $progLote_sttlote[$item][$subItem] = $regProgLote->sttlote;

              $progLote_programado[$item][$subItem] = (($regProgLote->anchura/$regProgBloque->anchura)*$regProgLote->longitud); }

            $nLotesProg[$item] = $subItem; }
        }

        $nProgramas = $item; }
      // Final del filtro de programaciones //*/

      // Filtro de sustrato disponible para programar
      $progLoteSelect = $link->query("
        SELECT proglote.idlote,
               proglote.tarima,
               proglote.lote,
               proglote.longitud,
               pdtosustrato.anchura,
               proglote.peso,
               proglote.cdglote
          FROM progbloque,
               proglote,
               pdtosustrato
        WHERE (progbloque.cdgbloque = proglote.cdgbloque AND
               proglote.sttlote = '7') AND
              (progbloque.cdgsustrato = '".$prodProgramacion_cdgsustrato."') AND
              (pdtosustrato.cdgsustrato = progbloque.cdgsustrato)");
      
      if ($progLoteSelect->num_rows > 0)
      { $item = 0;

        while ($regProgLote = $progLoteSelect->fetch_object())
        { $item++;

          $progLotes_idlote[$item] = $regProgLote->idlote;
          $progLotes_tarima[$item] = $regProgLote->tarima;
          $progLotes_lote[$item] = $regProgLote->lote;
          $progLotes_longitud[$item] = $regProgLote->longitud;
          $progLotes_amplitud[$item] = $regProgLote->anchura;
          $progLotes_peso[$item] = $regProgLote->peso;
          $progLotes_cdglote[$item] = $regProgLote->cdglote; }
        
        $nLotes = $item; }    
    //////////////////////////////////////////////////////////////    
  
    echo '
      <form id="frmProgramacion" name="frmProgramacion" method="POST" action="prodProgramacionBS2.php">
        <div class="bloque">
          <section>
            <a href="ayuda.php"><img id="imagen_ayuda" src="../img_sistema/help_blue.png" border="0"/></a>
            <Label><h1>Banda de seguridad</h1></label>
          </section>

          <article class="subbloque">
            <label class="modulo_nombre">Programación de sliteo</label>
          </article>         

          <section class="subbloque">
            <article>
              <label><a href="../sm_producto/pdtoBanda.php?cdgbanda='.$prodProgramacion_cdgproducto.'">Producto</a></label><br/>
              <select id="slctCdgProducto" name="slctCdgProducto" onchange="document.frmProgramacion.submit()">
                <option value="">-</option>';


      for ($item = 1; $item <= $nBandaPs; $item++)
      { echo '
                  <option value="'.$pdtoBandaP_cdgbandap[$item].'"';

        if ($prodProgramacion_cdgproducto == $pdtoBandaP_cdgbandap[$item])
        { echo ' selected="selected"'; }

      echo '>'.$pdtoBandaP_bandap[$item].'</option>'; }


    echo '
              </select>
            </article>';

    
    if ($nMaquinas > 0)
    { echo '
            <article>
              <label>Máquina</label><br/>
              <select id="slctCdgMaquina" name="slctCdgMaquina">';

      for ($item = 1; $item <= $nMaquinas; $item++)
      { echo '
                <option value="'.$prodMaquina_cdgmaquina[$item].'"';

        if ($prodProgramacion_cdgmaquina == $prodMaquina_cdgmaquina[$item])
        { echo ' selected="selected"'; }

        echo '>['.$prodMaquina_idmaquina[$item].'] '.$prodMaquina_maquina[$item].'</option>'; }

      echo '
              </select>
            </article>'; }

    echo '
            <article>
              <label>Fecha programación</label><br/>
                <input type="date" id="dateFchPrograma" name="dateFchPrograma" value="'.$prodProgramacion_fchprograma.'" title="Fecha de asignacion" onchange="document.frmProgramacion.submit()" style="width:140px;" required/>
            </article>

            <article><br>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>
          </section>
        </div>';

    if ($nProgramas > 0)
    { echo '
        <div class="bloque">
          <article class="subbloque">
            <label class="modulo_listado">Programación del día</article>
          </article>';

/*
            <article align="right">
              <label><a href="pdf/pro-fr05.php?cdgimpresion='.$programacion_cdgproducto[$item].'&cdgmaquina='.$programacion_cdgmaquina[$item].'&cdgjuego='.$programacion_cdgjuego[$item].'&fchprograma='.$prodProgramacion_fchprograma.'&cdgfchprograma='.$prodProgramacion_cdgfchprograma.'&millares='.number_format($programacion_programado[$item] ,3,'.','').'" target="_blank">PRO-<b>FR05</b></a></label><br/>
              <label><a href="pdf/pro-fr06.php?cdgimpresion='.$programacion_cdgproducto[$item].'&cdgmaquina='.$programacion_cdgmaquina[$item].'&cdgjuego='.$programacion_cdgjuego[$item].'&fchprograma='.$prodProgramacion_fchprograma.'&cdgfchprograma='.$prodProgramacion_cdgfchprograma.'&millares='.number_format($programacion_programado[$item] ,3,'.','').'" target="_blank">PRO-<b>FR06</b></a></label><br/>
              <label><a href="pdf/pro-fr07.php?cdgimpresion='.$programacion_cdgproducto[$item].'&cdgmaquina='.$programacion_cdgmaquina[$item].'&cdgjuego='.$programacion_cdgjuego[$item].'&fchprograma='.$prodProgramacion_fchprograma.'&cdgfchprograma='.$prodProgramacion_cdgfchprograma.'&millares='.number_format($programacion_programado[$item] ,3,'.','').'" target="_blank">PRO-<b>FR07</b></a></label><br/>
              <label><a href="pdf/pro-fr08.php?cdgimpresion='.$programacion_cdgproducto[$item].'&cdgmaquina='.$programacion_cdgmaquina[$item].'&cdgjuego='.$programacion_cdgjuego[$item].'&fchprograma='.$prodProgramacion_fchprograma.'&cdgfchprograma='.$prodProgramacion_cdgfchprograma.'&millares='.number_format($programacion_programado[$item] ,3,'.','').'" target="_blank">PRO-<b>FR08</b></a></label><br>
              <label><a href="../sm_inspeccion/pdf/ic-fr01.php?cdgimpresion='.$programacion_cdgproducto[$item].'&cdgmaquina='.$programacion_cdgmaquina[$item].'&cdgjuego='.$programacion_cdgjuego[$item].'&fchprograma='.$prodProgramacion_fchprograma.'&cdgfchprograma='.$prodProgramacion_cdgfchprograma.'&millares='.number_format($programacion_programado[$item] ,3,'.','').'" target="_blank">IC-<b>FR01</b></a></label>
            </article>
            */

      for ($item=1; $item<=$nProgramas; $item++)
      { echo '
          <section class="listado">
            <article align="right" style="vertical-align:top">
              <label><a href="pdf/pro-fr201.php?cdgproducto='.$progBloque_cdgproducto[$item].'&cdgmaquina='.$progBloque_cdgmaquina[$item].'&fchprograma='.$prodProgramacion_fchprograma.'&cdgfchprograma='.$prodProgramacion_cdgfchprograma.'" target="_blank">PRO-<b>FR00</b></a></label><br/>
            </article>

            <article style="vertical-align:top">
              <a href="pdf/prodLoteBS.php?cdgproducto='.$progBloque_cdgproducto[$item].'&cdgmaquina='.$progBloque_cdgmaquina[$item].'&fchprograma='.$prodProgramacion_fchprograma.'" target="_blank">'.$_barcode.'</a>
            </article>

            <article>
              <article align="right">                
                <label>Producto</label><br/>
                <label>Máquina</label><br/>
                <label>Lotes</label>
              </article>

              <article>                
                <label><b>'.$pdtoBandaPs_bandap[$progBloque_cdgproducto[$item]].'</b></label><br/>
                <label><i>'.$prodMaquinas_maquina[$progBloque_cdgmaquina[$item]].'</i></label><br/>
                <label><b>'.$progBloque_nlotes[$item].'</b> | <b>'.number_format($progBloque_programado[$item],2).'</b> <i>mtrs</i></label>
              </article>
            </article>
          </section>';

        for ($subItem=1; $subItem<=$nLotesProg[$item]; $subItem++)
        { echo '
          <section class="listado">
            <article style="vertical-align:top">';

          if ($progLote_sttlote[$item][$subItem] != '1')
          { if ($progLote_sttlote[$item][$subItem] == 'A')
            { echo '
              <a href="prodProgramacionBS2.php?cdglote='.$progLote_cdglote[$item][$subItem].'&proceso=update">'.$_power_blue.'</a>'; }
          
            if ($progLote_sttlote[$item][$subItem] == 'D')
            { echo '
              <a href="prodProgramacionBS2.php?cdglote='.$progLote_cdglote[$item][$subItem].'&proceso=delete">'.$_recycle_bin.'</a>
              <a href="prodProgramacionBS2.php?cdglote='.$progLote_cdglote[$item][$subItem].'&proceso=update">'.$_power_black.'</a>'; }

            if ($progLote_sttlote[$item][$subItem] == '9')
            { echo '
              <a href="#">'.$_power_black.'</a>';}
          } else
          { echo '
              <a href="#">'.$_gear.'</a>'; }

          echo '
            </article>

            <article>
              <label>NoOP <b>'.$progLote_noop[$item][$subItem].'</b></label><br/>
              <label class="textNombre">'.$progLote_lote[$item][$subItem].'</label><br/>
              <label><b>'.number_format($progLote_anchural[$item][$subItem]).'</b> mm</label>
            </article>

            <article>
              <article style="text-align:right">
                <label>Longitud</label><br/>
                <label>Peso</label><br/>
                <label><b></i>'.number_format($progLote_programado[$item][$subItem],2).'</i></b></label>
              </article>

              <article>
                <label><b>'.number_format($progLote_longitud[$item][$subItem],2).'</b> m</label><br/>
                <label><b>'.number_format($progLote_peso[$item][$subItem],3).'</b> kg</label><br/>
                <label>Metros aprox.</label>
              </article>
            </article>
          </section>'; }
      }

      echo '
        </div>'; }

    if ($nLotes > 0)
    { echo '
        <div class="bloque">
          <article class="subbloque">
            <label class="modulo_listado">Lotes compatibles</label>
          </article>
          <label><b>'.$nLotes.'</b> Entontrado(s)</label>';

      for ($item=1; $item<=$nLotes; $item++)
      { echo '
          <section class="listado">
            <article>
              <input type="checkbox" id="chck'.$progLotes_cdglote[$item].'" name="chck'.$progLotes_cdglote[$item].'" />
              <label>No. Lote <b>'.$progLotes_idlote[$item].'</b></label><br/>
              <label class="textNombre">'.$progLotes_lote[$item].'</label><br/>
              <label><b>'.number_format($progLotes_amplitud[$item]).'</b> mm</label>
            </article>

            <article>
              <article style="text-align:right">
                <label>Longitud</label><br/>
                <label>Peso</label><br/>
                <label><b></i>'.number_format((($progLotes_amplitud[$item]/$prodProgramacion_anchura)*$progLotes_longitud[$item]),2).'</i></b></label>
              </article>

              <article>
                <label><b>'.number_format($progLotes_longitud[$item],2).'</b> m</label><br/>
                <label><b>'.number_format($progLotes_peso[$item],3).'</b> kg</label><br/>
                <label>Metros aprox.</label>
              </article>
            </article>
          </section>'; }
    
      echo '
        </div>
      </form>'; }

    if ($msg_alert != '')
    { echo '
      <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
      <div><h1>Módulo no encontrado o bloqueado.</h1></div>'; }
  ?>

    </div>
  </body> 
</html>