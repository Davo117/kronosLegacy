<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Bandas de seguridad por proceso</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
<?php

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_producto();

  $sistModulo_cdgmodulo = '20031';
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
        <form id="login" action="pdtoBandaP.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 

      exit; } 
    
    $pdtoBandaP_preembosado = '0';
    $pdtoBandaP_cdgbanda = $_POST['slctCdgBanda'];
    $pdtoBandaP_idbandap = trim($_POST['textIdBandaP']);
    $pdtoBandaP_bandap = trim($_POST['textBandaP']);
    $pdtoBandaP_alaminado = trim($_POST['textALaminado']);
    $pdtoBandaP_cdgsustrato = $_POST['slctCdgSustrato'];

    if ($_POST['chckEmbosado']) { $pdtoBandaP_preembosado = '1'; }      
    if ($_GET['cdgbandap']) { $pdtoBandaP_cdgbandap = $_GET['cdgbandap']; }    
    if ($_GET['cdgbanda']) { $pdtoBandaP_cdgbanda = $_GET['cdgbanda']; }
    
    // Si doy click en algún registro
    if ($_GET['cdgbandap'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $querySelect = $link->query("
          SELECT pdtobandap.cdgbanda,
                 pdtobandap.idbandap,
                 pdtobandap.bandap,
                 pdtobandap.alaminado,
                 pdtobandap.cdgsustrato,
                 pdtobandap.preembosado,
                 pdtobandap.cdgbandap,
                 pdtobandap.sttbandap
            FROM pdtobanda,
                 pdtobandap
           WHERE pdtobanda.cdgbanda = pdtobandap.cdgbanda AND
                 pdtobandap.cdgbandap = '".$pdtoBandaP_cdgbandap."'");
        
        if ($querySelect->num_rows > 0)
        { $regQuery = $querySelect->fetch_object();

          $pdtoBandaP_cdgbanda = $regQuery->cdgbanda;
          $pdtoBandaP_idbandap = $regQuery->idbandap;
          $pdtoBandaP_bandap = $regQuery->bandap;
          $pdtoBandaP_alaminado = $regQuery->alaminado;
          $pdtoBandaP_cdgsustrato = $regQuery->cdgsustrato;
          $pdtoBandaP_preembosado = $regQuery->preembosado;
          $pdtoBandaP_cdgbandap = $regQuery->cdgbandap;
          $pdtoBandaP_sttbandap = $regQuery->sttbandap;

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($pdtoBandaP_sttbandap == '1')
              { $pdtoBandaP_newsttbandap = '0'; }
            
              if ($pdtoBandaP_sttbandap == '0')
              { $pdtoBandaP_newsttbandap = '1'; }
              
              if ($pdtoBandaP_newsttbandap != '')
              { $link->query("
                  UPDATE pdtobandap
                     SET sttbandap = '".$pdtoBandaP_newsttbandap."' 
                   WHERE cdgbandap = '".$pdtoBandaP_cdgbandap."'");
                  
                if ($link->affected_rows > 0)
                { $msg_alert = 'La Banda de seguridad por proceso fue actualizada en su status.'; }
                else
                { $msg_alert = 'La Banda de seguridad por proceso NO fue actualizada (status).'; }
              }
            } else
            { $msg_alert = $msg_norewrite; }            
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $prodLoteSelect = $link->query("
                SELECT * FROM prodlote
                 WHERE cdgproducto = '".$pdtoBandaP_cdgbandap."'");
                
              if ($prodLoteSelect->num_rows > 0)
              { $msg_alert = 'La Banda de seguridad por proceso cuenta con Lotes ligados, no debe ser eliminada.'; }
              else
              { $link->query("
                  DELETE FROM pdtobandap
                   WHERE cdgbandap = '".$pdtoBandaP_cdgbandap."' AND 
                         sttbandap = '0'");
                  
                if ($link->affected_rows > 0)
                { $msg_alert = 'La Banda de seguridad por proceso fue eliminada con éxito.'; }
                else
                { $msg_alert = 'La Banda de seguridad por proceso NO fue eliminada.'; }
              }
            } else
            { $msg_alert = $msg_nodelete; }
          }
        }
      } else
      { $msg_alert = $msg_noread; }
    }

    // Botón salvar //     
    if ($_POST['bttnSalvar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if (strlen($pdtoBandaP_cdgbanda) > 0 AND strlen($pdtoBandaP_idbandap) > 0 AND strlen($pdtoBandaP_cdgsustrato) > 0)
        { // Validar que la medida del sustrato sea mayor a la medida de laminado (Anchuras)

          // Buscar coincidencias
          $querySelect = $link->query("
            SELECT * FROM pdtobandap
             WHERE pdtobandap.cdgbanda = '".$pdtoBandaP_cdgbanda."' AND 
                   pdtobandap.idbandap = '".$pdtoBandaP_idbandap."'");
            
          if ($querySelect->num_rows > 0)
          { $regQuery = $querySelect->fetch_object();
            
            $link->query("
              UPDATE pdtobandap
                 SET bandap = '".$pdtoBandaP_bandap."'
               WHERE cdgbandap = '".$regQuery->cdgbandap."' AND 
                     sttbandap = '1'");
                
            if ($link->affected_rows > 0) 
            { $msg_alert = 'El nombre de la banda de seguridad por proceso fue actualizado con exito.\n\n'; }
            else
            { $msg_alert = 'El nombre de la banda de seguridad por proceso NO fue actualizado.\n\n'; } 
          } 
          else
          { for ($item = 1; $item <= 100; $item++)
            { $pdtoBandaP_cdgbandap = $pdtoBandaP_cdgbanda.'B'.str_pad($item,2,'0',STR_PAD_LEFT);
              
              if ($item > 99)
              { $msg_alert .= 'La Banda de seguridad por proceso NO fue insertado, se ha alcanzado el tope de bandas de seguridad por diseno.'; }
              else
              { $link->query("
                  INSERT INTO pdtobandap
                    (cdgbanda, idbandap, bandap, alaminado, cdgsustrato, preembosado, cdgbandap)
                  VALUES
                    ('".$pdtoBandaP_cdgbanda."', '".$pdtoBandaP_idbandap."', '".$pdtoBandaP_bandap."', '".$pdtoBandaP_alaminado."', '".$pdtoBandaP_cdgsustrato."', '".$pdtoBandaP_preembosado."', '".$pdtoBandaP_cdgbandap."')");
                
                if ($link->affected_rows > 0) 
                { $msg_alert = 'La Banda de seguridad por proceso fue insertada con exito.'; 

                  break; }
              }
            }
          } 
        }
      } else
      { $msg_alert = $msg_norewrite; }
    }

    // Filtro de Bandas de seguridad    
    $pdtoBandaSelect = $link->query("
      SELECT * FROM pdtobanda
       WHERE sttbanda = '1'
    ORDER BY banda,
             idbanda");
    
    if ($pdtoBandaSelect->num_rows > 0)
    { $item = 0;

      while ($regPdtoBanda = $pdtoBandaSelect->fetch_object()) 
      { $item++;

        $pdtoBandas_idbanda[$item] = $regPdtoBanda->idbanda;
        $pdtoBandas_banda[$item] = $regPdtoBanda->banda;
        $pdtoBandas_anchura[$item] = $regPdtoBanda->anchura;
        $pdtoBandas_cdgbanda[$item] = $regPdtoBanda->cdgbanda; }

      $nBandas = $item; }
    // Fin del filtro de bandas de seguridad
     
    // Filtro de tipos de materia prima
    $pdtoSustratoSelect = $link->query("
      SELECT * FROM pdtosustrato
    ORDER BY sttsustrato DESC,
             idsustrato");

    if ($pdtoSustratoSelect->num_rows > 0)
    { $item = 0;

      while ($regPdtoSustrato = $pdtoSustratoSelect->fetch_object())
      { $item++;

        $pdtoSustrato_sustrato[$item] = $regPdtoSustrato->sustrato;
        $pdtoSustrato_cdgsustrato[$item] = $regPdtoSustrato->cdgsustrato; }

      $nSustratos = $item; }
    // Fin del filtro de tipos de materia prima
  
    // Filtrar a la tabla
    if ($_POST['chckVerTodo'])
    { $vertodo = 'checked'; 

      $pdtoBandaPSelect = $link->query("
        SELECT pdtobandap.cdgbanda,
               pdtobandap.idbandap,
               pdtobandap.bandap,
               pdtosustrato.sustrato,
               pdtobandap.preembosado,
               pdtobandap.cdgbandap,
               pdtobandap.sttbandap
          FROM pdtosustrato,
               pdtobandap
         WHERE pdtosustrato.cdgsustrato = pdtobandap.cdgsustrato AND
               pdtobandap.cdgbanda = '".$pdtoBandaP_cdgbanda."'
      ORDER BY pdtobandap.sttbandap DESC,
               pdtobandap.idbandap"); }
    else
    { $pdtoBandaPSelect = $link->query("
        SELECT pdtobandap.cdgbanda,
               pdtobandap.idbandap,
               pdtobandap.bandap,
               pdtosustrato.sustrato,
               pdtobandap.preembosado,
               pdtobandap.cdgbandap,
               pdtobandap.sttbandap
          FROM pdtosustrato,
               pdtobandap
         WHERE pdtosustrato.cdgsustrato = pdtobandap.cdgsustrato AND
               pdtobandap.cdgbanda = '".$pdtoBandaP_cdgbanda."' AND
               pdtobandap.sttbandap = '1'
      ORDER BY pdtobandap.idbandap"); }

    if ($pdtoBandaPSelect->num_rows > 0)
    { $item = 0;

      while ($regPdtoBandaP = $pdtoBandaPSelect->fetch_object())
      { $item++;

        $pdtoBandaPs_idbandap[$item] = $regPdtoBandaP->idbandap;
        $pdtoBandaPs_bandap[$item] = $regPdtoBandaP->bandap;    
        $pdtoBandaPs_sustrato[$item] = $regPdtoBandaP->sustrato;
        $pdtoBandaPs_preembosado[$item] = $regPdtoBandaP->preembosado;
        $pdtoBandaPs_cdgbandap[$item] = $regPdtoBandaP->cdgbandap;
        $pdtoBandaPs_sttbandap[$item] = $regPdtoBandaP->sttbandap; }

      $nBandaPs = $item; }

    echo '
      <div class="bloque">
        <form id="formPdtoBandaP" name="formPdtoBandaP" method="POST" action="pdtoBandaP.php">        
          <article class="subbloque">
            <label class="modulo_nombre">Banda de seguridad por proceso</label><br>
          </article>
          <input type="checkbox" id="chckVerTodo" name="chckVerTodo" onclick="document.formPdtoBandaP.submit()" '.$vertodo.'><label>ver todo</label>
          <a href="ayuda.php#BandaP">'.$_help_blue.'</a>

          <section class="subbloque">
            <article>
              <label><a href="../sm_producto/pdtoBanda.php?cdgbanda='.$pdtoBandaP_cdgbanda.'">Banda de seguridad</a></label><br>
              <select id="slctCdgBanda" name="slctCdgBanda" onchange="document.formPdtoBandaP.submit()">';
      
    for ($item = 1; $item <= $nBandas; $item++) 
    { echo '
                <option value="'.$pdtoBandas_cdgbanda[$item].'"';
              
      if ($pdtoBandaP_cdgbanda == $pdtoBandas_cdgbanda[$item]) { echo ' selected="selected"'; }
      
      echo '>'.$pdtoBandas_banda[$item].'</option>'; }

    echo '
              </select>
            </article>

            <article>
              <label>Identificador</label><br>
              <input type="text" id="textIdBandaP" name="textIdBandaP" value="'.$pdtoBandaP_idbandap.'" placeholder="Nombre corto" required/>
            </article>

            <article>
              <label>Nombre</label><br>
              <input type="text" id="textBandaP" name="textBandaP" value="'.$pdtoBandaP_bandap.'" placeholder="Nombre completo" required/>
            </article>

            <article>
              <label>Anchura para laminado</label><br>
              <input type="text" class="input_numero" id="textALaminado" name="textALaminado" value="'.$pdtoBandaP_alaminado.'" placeholder="milímetros" required/>
            </article>

            <article>
              <label>Sustrato</label><input type="checkbox" id="chckEmbosado" name="chckEmbosado"><label>pre-embosado</label><br/>
              <select id="slctCdgSustrato" name="slctCdgSustrato">';
      
              
    for ($item = 1; $item <= $nSustratos; $item++)
    { echo '
              <option value="'.$pdtoSustrato_cdgsustrato[$item].'"';

      if ($pdtoBandaP_cdgsustrato == $pdtoSustrato_cdgsustrato[$item])
      { echo ' selected="selected"'; }
      
      echo '>'.$pdtoSustrato_sustrato[$item].'</option>';
    }

    echo '
              </select>
            </article>

            <article><br>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>
          </section>
        </form>
      </div>';

    if ($nBandas > 0)
    { echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Catálogo de bandas de seguridad por proceso</label>
        </article>
        <label><b>'.$nBandaPs.'</b> Encontradas</label>
        
        <section class="listado">
          <ul>';

      for ($item=1; $item<=$nBandaPs; $item++)
      { echo '
            <li><article>';
        
        if ((int)$pdtoBandaPs_sttbandap[$item] > 0)
        { echo '
                <a href="pdtoBandaP.php?cdgbandap='.$pdtoBandaPs_cdgbandap[$item].'">'.$_search.'</a>                
                <a href="pdtoBandaP.php?cdgbandap='.$pdtoBandaPs_cdgbandap[$item].'&proceso=update">'.$_power_blue.'</a>'; 
        } else
        { echo '
                <a href="pdtoBandaP.php?cdgbandap='.$pdtoBandaPs_cdgbandap[$item].'&proceso=delete">'.$_recycle_bin.'</a>                
                <a href="pdtoBandaP.php?cdgbandap='.$pdtoBandaPs_cdgbandap[$item].'&proceso=update">'.$_power_black.'</a>'; }

        echo '
                <article>
                  <label class="textId">'.$pdtoBandaPs_idbandap[$item].'</label> | <label class="textNombre">'.$pdtoBandaPs_bandap[$item].'</label><br>
                  <label>'.$pdtoBandaPs_sustrato[$item].'</label><br>
                  <label>';

        if ($pdtoBandaPs_preembosado[$item] == '1')
        { echo 'Pre-embosado';
        } else
        { echo 'Por embosar'; }

        echo '</label>                  
                </article>
              </article></li>'; }

      echo '
          </ul>
        </section>
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