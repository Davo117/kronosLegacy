<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Impresiones de sello de seguridad</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
<?php

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_producto();

  $sistModulo_cdgmodulo = '20020';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { if ($_GET['mode']=='logout') { cl0s3(); }

    if ($_POST['textusername'] AND $_POST['textpassword']) 
    { val1dat3($_POST['textusername'], $_POST['textpassword']); }

    if ($_SESSION['cdgusuario'])
    { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

      ma1n(); 
    } else
    { echo '
      <div id="loginform">
        <form id="login" action="pdtoImpresion.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 

      exit; } 
    
    $pdtoImpresion_cdgdiseno = $_POST['slctCdgDiseno'];
    $pdtoImpresion_idimpresion = trim($_POST['textIdImpresion']);
    $pdtoImpresion_impresion = trim($_POST['textImpresion']);
    $pdtoImpresion_cdgproducto = $_POST['slctCdgProducto'];
    // Información para impresión
    $pdtoImpresion_ancho = $_POST['textAnchura'];
    $pdtoImpresion_alto = $_POST['textAltura'];
    $pdtoImpresion_cdgsustrato = $_POST['slctCdgSustrato'];
    $pdtoImpresion_notintas = $_POST['textNoTintas'];
    // Información para fusión
    $pdtoImpresion_anchof = $_POST['textAnchuraF'];
    $pdtoImpresion_empalme = $_POST['textEmpalme'];
    $pdtoImpresion_cdgbanda = $_POST['slctCdgBanda'];
    // Información para empaque
    $pdtoImpresion_rollo = $_POST['textRollo'];
    $pdtoImpresion_tolerancia = $_POST['textTolerancia'];
    $pdtoImpresion_paquete = $_POST['textPaquete'];
    
    if ($_GET['cdgimpresion']) { $pdtoImpresion_cdgimpresion = $_GET['cdgimpresion']; }
    if ($_GET['cdgdiseno']) { $pdtoImpresion_cdgdiseno = $_GET['cdgdiseno']; }

    if ($_POST['bttnSalvar'])
    { if ($pdtoImpresion_ancho <= 0) {
        $msg_alert = 'El ancho de la pelicula debe ser mayor a Cero'; 
      } else if ($pdtoImpresion_alto <= 0) {
        $msg_alert = 'El alto debe ser mayor a Cero'; 
      } else if ($pdtoImpresion_anchof <= 0) {
        $msg_alert = 'El ancho de la etiqueta debe ser mayor a Cero'; 
      } else if ($pdtoImpresion_empalme <= 0) {
        $msg_alert = 'El espacio para fusion debe ser mayor a Cero'; 
      } else if ($pdtoImpresion_rollo <= 0) {
        $msg_alert = 'Lo millares por rollo deben de mayor a cero'; 
      } else if (substr($sistModulo_permiso,0,2) == 'rw')
      { if (strlen($pdtoImpresion_cdgdiseno) > 0 AND strlen($pdtoImpresion_idimpresion) > 0)
        { // Buscar coincidencias
          $pdtoImpresionSelect = $link->query("
            SELECT * FROM pdtoimpresion
             WHERE cdgdiseno = '".$pdtoImpresion_cdgdiseno."' AND 
                   idimpresion = '".$pdtoImpresion_idimpresion."'");
            
          if ($pdtoImpresionSelect->num_rows > 0)
          { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

            $pdtoImpresion_cdgimpresion = $regPdtoImpresion->cdgimpresion;
            
            $link->query("
              UPDATE pdtoimpresion
                 SET impresion = '".$pdtoImpresion_impresion."',
                     rollo = '".$pdtoImpresion_rollo."',
                     tolerancia = '".$pdtoImpresion_tolerancia."',
                     paquete = '".$pdtoImpresion_paquete."'
               WHERE cdgimpresion = '".$pdtoImpresion_cdgimpresion."' AND 
                     sttimpresion = '1'");
                
            if ($link->affected_rows > 0) 
            { $msg_alert = 'La impresion fue actualizada con exito.\n\n'; }
            else
            { $msg_alert = 'La impresion NO fue actualizado.\n\n'; }

            for ($item = 1; $item <= $regPdtoImpresion->notintas; $item++)
            { $link->query("
                INSERT INTO pdtoimpresiontnt
                  (cdgimpresion, notinta, cdgtinta, consumo, disolvente)
                VALUES
                  ('".$pdtoImpresion_cdgimpresion."', '".$item."', '".$_POST['slctCdgPantone'.$item]."', '".$_POST['textConsumo'.$item]."', '".$_POST['textDisolvente'.$item]."')");

              if ($link->affected_rows > 0) 
              { $msg_alert .= 'La tinta '.$item.' fue insertada.\n'; 

                  $link->query("
                    INSERT INTO krdxconsumo
                        (cdgdiseno, cdgsubproceso, cdgelemento, consumo, cdgconsumo, cdgusuario, operacion, fchmovimiento)
                      VALUES
                        ('".$pdtoImpresion_cdgimpresion."', '001', '".$_POST['slctCdgPantone'.$item]."', '".$_POST['textConsumo'.$item]."', '".$pdtoImpresion_cdgimpresion.$_POST['slctCdgPantone'.$item]."', '".$_SESSION['cdgusuario']."', 'INSERT', NOW())");
              } else
              { $link->query("
                  UPDATE pdtoimpresiontnt
                     SET cdgtinta = '".$_POST['slctCdgPantone'.$item]."',
                         consumo = '".$_POST['textConsumo'.$item]."',
                         disolvente = '".$_POST['textDisolvente'.$item]."'
                   WHERE cdgimpresion = '".$pdtoImpresion_cdgimpresion."' AND
                         notinta = '".$item."'");
                
                if ($link->affected_rows > 0)
                { $msg_alert .= 'La tinta '.$item.' fue actualizada.\n'; 

                  $link->query("
                    INSERT INTO krdxconsumo
                        (cdgdiseno, cdgsubproceso, cdgelemento, consumo, cdgconsumo, cdgusuario, operacion, fchmovimiento)
                      VALUES
                        ('".$pdtoImpresion_cdgimpresion."', '001', '".$_POST['slctCdgPantone'.$item]."', '".$_POST['textConsumo'.$item]."', '".$pdtoImpresion_cdgimpresion.$_POST['slctCdgPantone'.$item]."', '".$_SESSION['cdgusuario']."', 'UPDATE', NOW())");

                } else
                { $msg_alert .= 'La tinta '.$item.' no presento cambios.\n'; }
              }             
            }
          } else
          { for ($cdgimpresion = 1; $cdgimpresion <= 100; $cdgimpresion++)
            { $pdtoImpresion_cdgimpresion = $pdtoImpresion_cdgdiseno.'S'.str_pad($cdgimpresion,2,'0',STR_PAD_LEFT);
              
              if ($cdgimpresion > 99)
              { $msg_alert = 'La impresion NO fue insertado, se ha alcanzado el tope de impresiones para este diseno.'; }
              else
              { $link->query("
                  INSERT INTO pdtoimpresion
                    (cdgdiseno, idimpresion, impresion, cdgproducto, ancho, alto, cdgsustrato, notintas, anchof, empalme, cdgbanda, rollo, tolerancia, paquete, cdgimpresion)
                  VALUES
                    ('".$pdtoImpresion_cdgdiseno."', '".$pdtoImpresion_idimpresion."', '".$pdtoImpresion_impresion."', '".$pdtoImpresion_cdgproducto."', '".$pdtoImpresion_ancho."', '".$pdtoImpresion_alto."', '".$pdtoImpresion_cdgsustrato."', '".$pdtoImpresion_notintas."', '".$pdtoImpresion_anchof."', '".$pdtoImpresion_empalme."', '".$pdtoImpresion_cdgbanda."', '".$pdtoImpresion_rollo."', '".$pdtoImpresion_tolerancia."', '".$pdtoImpresion_paquete."', '".$pdtoImpresion_cdgimpresion."')");
                
                if ($link->affected_rows > 0) 
                { $msg_alert = 'La impresion fue insertada con exito.'; 

                  break; }
              }
            }
          }
        }
      } else
      { $msg_alert = $msg_norewrite; }
    }

    if (substr($sistModulo_permiso,0,1) == 'r')
    { if ($_GET['cdgimpresion'])
      { $pdtoImpresionSelect = $link->query("
          SELECT pdtoimpresion.cdgdiseno,
                 pdtoimpresion.idimpresion,
                 pdtoimpresion.impresion,
                 pdtoimpresion.cdgproducto,
                 pdtoimpresion.ancho,
                 pdtoimpresion.alto,
                 pdtoimpresion.cdgsustrato,
                 pdtoimpresion.notintas,
                 pdtoimpresion.anchof,
                 pdtoimpresion.empalme,
                 pdtoimpresion.cdgbanda,
                 pdtoimpresion.rollo,
                 pdtoimpresion.tolerancia,
                 pdtoimpresion.paquete,
                 pdtoimpresion.cdgimpresion,
                 pdtoimpresion.sttimpresion
            FROM pdtodiseno,
                 pdtoimpresion
          WHERE (pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno) AND
                 pdtoimpresion.cdgimpresion = '".$pdtoImpresion_cdgimpresion."'");
        
        if ($pdtoImpresionSelect->num_rows > 0)
        { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

          $pdtoImpresion_cdgdiseno = $regPdtoImpresion->cdgdiseno;
          $pdtoImpresion_idimpresion = $regPdtoImpresion->idimpresion;
          $pdtoImpresion_impresion = $regPdtoImpresion->impresion;
          $pdtoImpresion_cdgproducto = $regPdtoImpresion->cdgproducto;
          $pdtoImpresion_cdgimpresion = $regPdtoImpresion->cdgimpresion;
          $pdtoImpresion_sttimpresion = $regPdtoImpresion->sttimpresion;
          // Información para impresión
          $pdtoImpresion_ancho = $regPdtoImpresion->ancho;
          $pdtoImpresion_alto = $regPdtoImpresion->alto;
          $pdtoImpresion_cdgsustrato = $regPdtoImpresion->cdgsustrato;
          $pdtoImpresion_notintas = $regPdtoImpresion->notintas;
          // Información para fusión
          $pdtoImpresion_anchof = $regPdtoImpresion->anchof;
          $pdtoImpresion_empalme = $regPdtoImpresion->empalme;
          $pdtoImpresion_cdgbanda = $regPdtoImpresion->cdgbanda;
          // Información para empaque
          $pdtoImpresion_rollo = $regPdtoImpresion->rollo;
          $pdtoImpresion_tolerancia = $regPdtoImpresion->tolerancia;
          $pdtoImpresion_paquete = $regPdtoImpresion->paquete;

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($pdtoImpresion_sttimpresion == '1')
              { $pdtoImpresion_newsttimpresion = '0'; }
            
              if ($pdtoImpresion_sttimpresion == '0')
              { $pdtoImpresion_newsttimpresion = '1'; }
              
              if ($pdtoImpresion_newsttimpresion != '')
              { $link->query("
                  UPDATE pdtoimpresion
                     SET sttimpresion = '".$pdtoImpresion_newsttimpresion."'
                   WHERE cdgimpresion = '".$pdtoImpresion_cdgimpresion."'");
                  
                if ($link->affected_rows > 0)
                { $msg_alert = 'La impresion fue actualizada en su status.';
                } else
                { $msg_alert = 'La impresion NO fue actualizada (status).'; }
              }
            } else
            { $msg_alert = $msg_norewrite; }
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $prodLoteSelect = $link->query("
                SELECT * FROM prodlote
                 WHERE cdgproducto = '".$pdtoImpresion_cdgimpresion."'");
                
              if ($prodLoteSelect->num_rows > 0)
              { $msg_alert = 'La impresion cuenta con Lotes vinculados, no debe ser eliminada.'; 
              } else
              { $link->query("
                  DELETE FROM pdtoimpresion
                   WHERE cdgimpresion = '".$pdtoImpresion_cdgimpresion."' AND 
                         sttimpresion = '0'");
                  
                if ($link->affected_rows > 0)
                { $link->query("
                    DELETE FROM pdtoimpresiontnt
                     WHERE cdgimpresion = '".$pdtoImpresion_cdgimpresion."'");

                  $msg_alert = 'La impresion fue eliminada con éxito.'; 
                } else
                { $msg_alert = 'La impresion NO fue eliminada.'; }
              }
            } else
            { $msg_alert = $msg_nodelete; }              
          }
        }
      }

    // Filtro de registros (Diseños)
      $pdtoDisenoSelect = $link->query("
        SELECT * FROM pdtodiseno
         WHERE sttdiseno = '1'
      ORDER BY diseno,
               iddiseno");

      if ($pdtoDisenoSelect->num_rows > 0)
      { $item = 0;
        
        while ($regPdtoDiseno = $pdtoDisenoSelect->fetch_object()) 
        { $item++;

          $pdtoDisenos_iddiseno[$item] = $regPdtoDiseno->iddiseno;
          $pdtoDisenos_diseno[$item] = $regPdtoDiseno->diseno;
          //$pdtoDisenos_numTintas[$item] = $regPdtoDiseno->notintas;
          $pdtoDisenos_cdgdiseno[$item] = $regPdtoDiseno->cdgdiseno; }

        $nDisenos = $item; }
      // Final del filtro de registros  

      // Filtro de sustratos
        $pdtoSustratoSelect = $link->query("
          SELECT pdtosustrato.idsustrato,
                 pdtosustrato.sustrato,
                 pdtosustrato.cdgsustrato
            FROM pdtosustrato
        ORDER BY pdtosustrato.idsustrato");

        if ($pdtoSustratoSelect->num_rows > 0)
        { $item = 0;

          while ($regPdtoSustrato = $pdtoSustratoSelect->fetch_object())
          { $item++;

            $pdtoSustrato_idsustrato[$item] = $regPdtoSustrato->idsustrato;
            $pdtoSustrato_sustrato[$item] = $regPdtoSustrato->sustrato; 
            $pdtoSustrato_cdgsustrato[$item] = $regPdtoSustrato->cdgsustrato;

            $pdtoSustratos_sustrato[$regPdtoSustrato->cdgsustrato] = $regPdtoSustrato->sustrato; }

          $nSustratos = $item; }
      // Final del filtro de sustratos 

      // Filtro de diseños
      $pdtoBandaSelect = $link->query("
        SELECT * FROM pdtobanda
      ORDER BY sttbanda DESC,
               idbanda");

      if ($pdtoBandaSelect->num_rows > 0)
      { $item = 0;

        while ($regPdtoBanda = $pdtoBandaSelect->fetch_object())
        { $item++;

          $pdtoBandas_banda[$item] = $regPdtoBanda->banda;
          $pdtoBandas_cdgbanda[$item] = $regPdtoBanda->cdgbanda;

          $pdtoBandas_bandas[$regPdtoBanda->cdgbanda] = $regPdtoBanda->banda; }

        $nBandas = $item; }
      // Final del filtro de bandas          

      // Filtro de registros (Productos del cliente)
      $pdtoProductoSelect = $link->query("
        SELECT * FROM pdtoproducto
         WHERE sttproducto = '1'
      ORDER BY idproducto,
               producto");
      
      if ($pdtoProductoSelect->num_rows > 0)
      { $item = 0;

        while ($regpdtoProducto = $pdtoProductoSelect->fetch_object()) 
        { $item++;

          $vntsproductos_idproducto[$item] = $regpdtoProducto->idproducto;
          $vntsproductos_producto[$item] = $regpdtoProducto->producto;
          $vntsproductos_cdgproducto[$item] = $regpdtoProducto->cdgproducto; }

        $nProductos = $item; }
      // Final del filtro de registros

      // Filtro de rgistros (Pantones)
      $pdtoPantoneSelect = $link->query("
        SELECT * FROM pdtopantone
      ORDER BY pantone");

      if ($pdtoPantoneSelect->num_rows > 0)
      { $item = 0;
        
        while ($regPdtoPantone = $pdtoPantoneSelect->fetch_object())
        { $item++;

          $pdtoPantone_pantone[$item] = $regPdtoPantone->pantone;
          $pdtoPantone_HTML[$item] = $regPdtoPantone->HTML;
          $pdtoPantone_cdgpantone[$item] = $regPdtoPantone->cdgpantone;
          $pdtoPantone_sttpantone[$item] = $regPdtoPantone->sttpantone;
          
          $pdtoPantones_pantone[$regPdtoPantone->cdgpantone] = $regPdtoPantone->pantone; 
          $pdtoPantones_HTML[$regPdtoPantone->cdgpantone] = $regPdtoPantone->HTML; 
          $pdtoPantones_cdgpantone[$regPdtoPantone->cdgpantone] = $regPdtoPantone->cdgpantone; }

        $nPantones = $item; } 
        // Final del filtro de registros          

      // Filtro de registros
      if ($_POST['chckVerTodo'])
      { $vertodo = 'checked'; 
        // Filtrado completo
        $pdtoImpresionSelect = $link->query("
          SELECT * FROM pdtoimpresion
           WHERE cdgdiseno = '".$pdtoImpresion_cdgdiseno."'
        ORDER BY sttimpresion DESC,        
                 idimpresion"); }
      else
      { // Buscar coincidencias
        $pdtoImpresionSelect = $link->query("
          SELECT * FROM pdtoimpresion
           WHERE cdgdiseno = '".$pdtoImpresion_cdgdiseno."' AND
                 sttimpresion = '1'
        ORDER BY idimpresion"); }

      if ($pdtoImpresionSelect->num_rows > 0)
      { $item = 0;
        
        while ($regPdtoImpresion = $pdtoImpresionSelect->fetch_object())
        { $item++;

          $pdtoImpresiones_idimpresion[$item] = $regPdtoImpresion->idimpresion;
          $pdtoImpresiones_impresion[$item] = $regPdtoImpresion->impresion;
          $pdtoImpresiones_notintas[$item] = $regPdtoImpresion->notintas;
          $pdtoImpresiones_cdgsustrato[$item] = $regPdtoImpresion->cdgsustrato;
          $pdtoImpresiones_cdgproducto[$item] = $regPdtoImpresion->cdgproducto;
          $pdtoImpresiones_cdgimpresion[$item] = $regPdtoImpresion->cdgimpresion;
          $pdtoImpresiones_sttimpresion[$item] = $regPdtoImpresion->sttimpresion; }

        $nImpresiones = $item; }
        // Final del filtro de registros

      if ($pdtoImpresion_cdgimpresion != '')
      { $pdtoImpresionTntSelect = $link->query("
          SELECT pdtoimpresiontnt.cdgimpresion, 
pdtoimpresiontnt.notinta, pdtoimpresiontnt.cdgtinta, pdtoimpresiontnt.consumo, pdtoimpresiontnt.disolvente,
pdtopantone.HTML 
FROM pdtoimpresiontnt LEFT JOIN pdtopantone ON pdtopantone.cdgpantone = pdtoimpresiontnt.cdgtinta
           WHERE pdtoimpresiontnt.cdgimpresion = '".$pdtoImpresion_cdgimpresion."'");

        if ($pdtoImpresionTntSelect->num_rows > 0)
        { while ($regPdtoImpresionTnt = $pdtoImpresionTntSelect->fetch_object())
          { $pdtoImpresionTnt_HTML[$regPdtoImpresionTnt->cdgimpresion][$regPdtoImpresionTnt->notinta] = $regPdtoImpresionTnt->HTML;
            $pdtoImpresionTnt_cdgtinta[$regPdtoImpresionTnt->cdgimpresion][$regPdtoImpresionTnt->notinta] = $regPdtoImpresionTnt->cdgtinta;
            $pdtoImpresionTnt_consumo[$regPdtoImpresionTnt->cdgimpresion][$regPdtoImpresionTnt->notinta] = $regPdtoImpresionTnt->consumo;
            $pdtoImpresionTnt_disolvente[$regPdtoImpresionTnt->cdgimpresion][$regPdtoImpresionTnt->notinta] = $regPdtoImpresionTnt->disolvente; }
        }
      }
    }

    echo '
      <form id="formPdtoImpresion" name="formPdtoImpresion" method="POST" action="pdtoImpresion.php">
        <div class="bloque">
          <article class="subbloque">
            <label class="modulo_nombre">Impresiones</label><br>
          </article>
          <input type="checkbox" id="chckVerTodo" name="chckVerTodo" onclick="document.formPdtoImpresion.submit()" '.$vertodo.'><label>ver todo</label>
          <a href="ayuda.php#Impresiones">'.$_help_blue.'</a>

          <section class="subbloque">
            <article>
              <label><a href="../sm_producto/pdtoDiseno.php?cdgdiseno='.$pdtoImpresion_cdgdiseno.'">Diseño</a></label><br>
              <select id="slctCdgDiseno" name="slctCdgDiseno" onchange="document.formPdtoImpresion.submit()">
                <option>-</option>';
      
    for ($item = 1; $item <= $nDisenos; $item++) 
    { echo '
                <option value="'.$pdtoDisenos_cdgdiseno[$item].'"';
              
      if ($pdtoImpresion_cdgdiseno == $pdtoDisenos_cdgdiseno[$item]) { echo ' selected="selected"'; }
      echo '>'.$pdtoDisenos_diseno[$item].'</option>'; }

    echo '
              </select>
            </article>

            <article>
              <label>Código</label><br>
              <input type="text" id="textIdImpresion" name="textIdImpresion" value="'.$pdtoImpresion_idimpresion.'" autofocus="autofocus" required/>
            </article>

            <article>
              <label>Descripción</label><br>
              <input type="text" id="textImpresion" name="textImpresion" value="'.$pdtoImpresion_impresion.'" required/>
            </article>

            <article>
              <label>Código cliente</label><br/>
              <select id="slctCdgProducto" name="slctCdgProducto">';
      
    for ($item = 1; $item <= $nProductos; $item++) 
    { echo '
                <option value="'.$vntsproductos_cdgproducto[$item].'"';
              
      if ($pdtoImpresion_cdgproducto == $vntsproductos_cdgproducto[$item]) { echo ' selected="selected"'; }
      echo '>'.$vntsproductos_idproducto[$item].' '.$vntsproductos_producto[$item].'</option>'; }

    echo '
              </select>
            </article>
          </section>

          <section class="listado">
            <article>
              <label>Ancho película mm</label><br>
              <input type="text" class="input_numero" id="textAnchura" name="textAnchura" value="'.$pdtoImpresion_ancho.'" placeholder="milímetros" required/>
            </article>

            <article>
              <label>Altura de etiqueta mm</label><br>
              <input type="text" class="input_numero" id="textAltura" name="textAltura" value="'.$pdtoImpresion_alto.'" placeholder="milímetros" required/>
            </article>

            <article>
              <label>Sustrato</label><br/>
              <select id="slctCdgSustrato" name="slctCdgSustrato">';

    if ($nSustratos > 0)
    { for ($item=1; $item<=$nSustratos; $item++)
      { echo '
                <option value="'.$pdtoSustrato_cdgsustrato[$item].'"';

        if ($pdtoImpresion_cdgsustrato == $pdtoSustrato_cdgsustrato[$item]) 
        { echo ' selected="selected"'; }

        echo '>'.$pdtoSustrato_sustrato[$item].'</option>'; }              
    }

    echo '
              </select>
            </article>

            <article>
              <label>Número de tintas</label><br/>
              <input type="text" class="input_numero" id="textNoTintas" name="textNoTintas" value="'.$pdtoImpresion_notintas.'" placeholder="#" required/>
            </article>
          </section>

          <section class="listado">
            <article>
              <label>Ancho de etiqueta mm</label><br/>
              <input type="text" class="input_numero" id="textAnchuraF" name="textAnchuraF" value="'.$pdtoImpresion_anchof.'" placeholder="milímetros" required/>
            </article>

            <article>
              <label>Espacio para fusión mm</label><br/>
              <input type="text" class="input_numero" id="textEmpalme" name="textEmpalme" value="'.$pdtoImpresion_empalme.'" placeholder="milímetros" required/>          
            </article>

            <article>
              <label>Banda de seguridad</label><br/>
              <select id="slctCdgBanda" name="slctCdgBanda">';

    for ($item = 1; $item <= $nBandas; $item++)
    { echo '
                <option value="'.$pdtoBandas_cdgbanda[$item].'"';

      if ($pdtoImpresion_cdgbanda == $pdtoBandas_cdgbanda[$item]) { echo ' selected="selected"'; }
      echo '>'.$pdtoBandas_banda[$item].'</option>'; }

    echo '
              </select>
            </article>
          </section>

          <section class="listado">
            <article>
              <label>Millares por rollo</label><br>
              <input type="text" class="input_numero" id="textRollo" name="textRollo" value="'.$pdtoImpresion_rollo.'" placeholder="millares" required/>
            </article>

            <article>
              <label>% +/- millares por rollo</label><br/>
              <input type="text" class="input_numero" id="textTolerancia" name="textTolerancia" value="'.$pdtoImpresion_tolerancia.'" placeholder="+/- %" required/>
            </article>

            <article>
              <label>Millares por paquete</label><br/>
              <input type="text" class="input_numero" id="textPaquete" name="textPaquete" value="'.$pdtoImpresion_paquete.'" placeholder="millares" required/>
            </article> 

            <article><br>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>
          </section>
        </div>';

    if ($pdtoImpresion_notintas > 0)
    { echo '
        <div class="bloque">
          <article class="subbloque">
            <label class="modulo_listado">Asignación de Pantone por capa</label>
          </article>'; 

      for ($item = 1; $item <= $pdtoImpresion_notintas; $item++)
      { echo '
          <section class="listado">
            <article><label>Asignar pantone <i>Capa #'.$item.'</i></label><br/>                
              <select id="slctCdgPantone'.$item.'" name="slctCdgPantone'.$item.'" style="background:#'.$pdtoImpresionTnt_HTML[$pdtoImpresion_cdgimpresion][$item].'">';
    
        for ($nPantone = 1; $nPantone <= $nPantones; $nPantone++) 
        { if ($pdtoPantone_sttpantone[$nPantone] == '1')
          { echo '
                <option value="'.$pdtoPantone_cdgpantone[$nPantone].'"';
              
          if ($pdtoImpresionTnt_cdgtinta[$pdtoImpresion_cdgimpresion][$item] == $pdtoPantone_cdgpantone[$nPantone]) 
          { echo ' selected="selected"'; }
            
          echo ' style="background:#'.$pdtoPantone_HTML[$nPantone].'">'.$pdtoPantone_pantone[$nPantone].'</option>'; }
        }

        echo '
              </select>
            </article>

            <article>
              <label>Consumo</label><br/>
              <input type="text" class="input_numero" style="width:60px" id="textConsumo'.$item.'" name="textConsumo'.$item.'" value="'.$pdtoImpresionTnt_consumo[$pdtoImpresion_cdgimpresion][$item].'" placeholder="Kilos"'.$text_amplitud[$item].' required/>
            </article>

            <article>
              <label>Disolvente</label><br/>
              <input type="text" id="textDisolvente'.$item.'" name="textDisolvente'.$item.'" value="'.$pdtoImpresionTnt_disolvente[$pdtoImpresion_cdgimpresion][$item].'" placeholder="Nombre" required/>
            </article>
          </section>'; }

        echo '      
        </div>'; }

    echo '
      </form>';

    if ($nImpresiones > 0)
    { echo '
      <div class="bloque">      
        <article class="subbloque">
          <label class="modulo_listado">Catálogo de impresiones por diseño</label>
        </article>
        <label><b>'.$nImpresiones.'</b> Encontrado(s)</label>';
    
      for ($item=1; $item<=$nImpresiones; $item++)
      { echo '
        <section class="listado">
          <article>';

        if ((int)$pdtoImpresiones_sttimpresion[$item] > 0)
        { echo '
            <a href="pdtoImpresion.php?cdgimpresion='.$pdtoImpresiones_cdgimpresion[$item].'">'.$_search.'</a>
            <a href="pdtoJuego.php?cdgimpresion='.$pdtoImpresiones_cdgimpresion[$item].'">'.$_tools.'</a>
            <a href="pdtoImpresion.php?cdgimpresion='.$pdtoImpresiones_cdgimpresion[$item].'&proceso=update">'.$_power_blue.'</a>'; 
        } else
        { echo '
            <a href="pdtoImpresion.php?cdgimpresion='.$pdtoImpresiones_cdgimpresion[$item].'&proceso=delete">'.$_recycle_bin.'</a>
            <a href="pdtoImpresion.php?cdgimpresion='.$pdtoImpresiones_cdgimpresion[$item].'&proceso=update">'.$_power_black.'</a>'; }

        echo '
          </article>

          <article>
            <label class="textId"><b>'.$pdtoImpresiones_idimpresion[$item].'</b><label>
            <label><i>'.$pdtoImpresiones_periodo[$item].'</i></label><br>
            <label class="textNombre">'.$pdtoImpresiones_impresion[$item].'</label>
          </article>
        </section>'; }

      echo '
      </div>'; }

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
