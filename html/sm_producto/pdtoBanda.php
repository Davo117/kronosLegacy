<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Bandas de seguridad</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>  
    <div id="contenedor">
<?php

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_producto();

  $sistModulo_cdgmodulo = '20030';
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
        <form id="login" action="pdtoBanda.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 
     
      exit; }

    $pdtoBanda_idbanda = trim($_POST['textIdBanda']);
    $pdtoBanda_banda = trim($_POST['textBanda']);
    $pdtoBanda_anchura = $_POST['textAnchura'];

    if ($_GET['cdgbanda'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $pdtoBandaSelect = $link->query("
          SELECT * FROM pdtobanda
           WHERE cdgbanda = '".$_GET['cdgbanda']."'");

        if ($pdtoBandaSelect->num_rows > 0)
        { $regPdtoBanda = $pdtoBandaSelect->fetch_object();

          $pdtoBanda_idbanda = $regPdtoBanda->idbanda;
          $pdtoBanda_banda = $regPdtoBanda->banda;
          $pdtoBanda_anchura = $regPdtoBanda->anchura;
          $pdtoBanda_cdgbanda = $regPdtoBanda->cdgbanda;
          $pdtoBanda_sttbanda = $regPdtoBanda->sttbanda;

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($pdtoBanda_sttbanda == '1')
              { $pdtoBanda_newsttbanda = '0'; }

              if ($pdtoBanda_sttbanda == '0')
              { $pdtoBanda_newsttbanda = '1'; }
              
              if ($pdtoBanda_newsttbanda != '')
              { $link->query("
                  UPDATE pdtobanda
                     SET sttbanda = '".$pdtoBanda_newsttbanda."'
                   WHERE cdgbanda = '".$pdtoBanda_cdgbanda."'");

                if ($link->affected_rows > 0)
                { $msg_alert = utf8_decode('La banda fue actualizada en su status.'); }
                else
                { $msg_alert = utf8_decode('La banda NO fue actualizada (status).'); }
              }
            } else
            { $msg_alert = $msg_norewrite; }
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $pdtoDisenoSelect = $link->query("
                SELECT * FROM pdtodiseno
                 WHERE cdgbanda = '".$pdtoBanda_cdgbanda."'");

              if ($pdtoDisenoSelect->num_rows > 0)
              { $msg_alert = utf8_decode('La banda no puede eliminarse, tiene registros vinculados (Diseños).'); }
              else
              { $pdtoBandaPSelect = $link->query("
                  SELECT * FROM pdtobandap
                   WHERE cdgbanda = '".$pdtoBanda_cdgbanda."'");

                if ($pdtoBandaPSelect->num_rows > 0)
                { $msg_alert = utf8_decode('La banda no puede eliminarse, tiene registros vinculados (Bandas por proceso).'); }
                else
                { $link->query("
                    DELETE FROM pdtobanda
                     WHERE cdgbanda = '".$pdtoBanda_cdgbanda."' AND
                           sttbanda = '0'");

                  if ($link->affected_rows > 0)
                  { $msg_alert = utf8_decode('La banda fue eliminada con exito.'); }
                  else
                  { $msg_alert = utf8_decode('La banda NO fue eliminada.'); }                  
                }
              }
            } else
            { $msg_alert = $msg_nodelete; }
          }
        }
      } else
      { $msg_alert = $msg_noread; }
    }

    if ($_POST['bttnSalvar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if (strlen($pdtoBanda_idbanda) > 0 AND strlen($pdtoBanda_banda) > 0)
        { // Buscar coincidencias
          $pdtoBandaSelect = $link->query("
            SELECT * FROM pdtobanda
             WHERE idbanda = '".$pdtoBanda_idbanda."'");

          if ($pdtoBandaSelect->num_rows > 0)
          { $regPdtoBanda = $pdtoBandaSelect->fetch_object();
            
            $pdtoBanda_cdgbanda = $regPdtoBanda->cdgbanda;

            // Buscar coincidencias
            $pdtoDisenoSelect->query("
              SELECT * FROM pdtodiseno
               WHERE cdgbanda = '".$pdtoBanda_cdgbanda."'");

            if ($pdtoDisenoSelect->num_rows > 0)
            { $msg_alert = 'La banda no debe ser modificacda, contiene información vinculada (Diseño).';
            } else
            { // Buscar coincidencias
              $pdtoBandaPSelect->query("
                SELECT * FROM pdtobandap
                 WHERE cdgbanda = '".$pdtoBanda_cdgbanda."'");

              if ($pdtoBandaPSelect->num_rows > 0)
              { $msg_alert = 'La banda no debe ser modificacda, contiene información vinculada (Banda de seguridad por proceso).';
              } else
              { $link->query("
                  UPDATE pdtobanda 
                     SET banda = '".$pdtoBanda_banda."',
                         anchura = '".$pdtoBanda_anchura."'
                   WHERE cdgbanda = '".$pdtoBanda_cdgbanda."' AND 
                         sttbanda = '1'");

                if ($link->affected_rows > 0)
                { $msg_alert .= utf8_decode('La banda fue actualizado con exito.'); }
                else
                { $msg_alert .= utf8_decode('La banda NO fue actualizado.'); }
              }
            }
          } else
          { for ($cdgbanda = 1; $cdgbanda <= 1000; $cdgbanda++)
            { $pdtoBanda_cdgbanda = str_pad($cdgbanda,3,'0',STR_PAD_LEFT);

              if ($cdgbanda > 999)
              { $msg_alert = utf8_decode('La banda NO fue insertado, contacta a soporte.'); }
              else
              { $link->query("
                  INSERT INTO pdtobanda
                    (idbanda, banda, anchura, cdgbanda)
                  VALUES
                    ('".$pdtoBanda_idbanda."', '".$pdtoBanda_banda."', '".$pdtoBanda_anchura."', '".$pdtoBanda_cdgbanda."')");

                if ($link->affected_rows > 0)
                { $msg_alert = utf8_decode('La banda fue insertado satisfactoriamente.');
                  break;
                } else
                { $msg_alert = utf8_decode('La banda NO fue insertado.'); }
              }
            }
          }
        }
      } else
      { $msg_alert = $msg_norewrite; }
    }      

    if (substr($sistModulo_permiso,0,1) == 'r')
    { if ($_POST['chckVerTodo'])
      { $vertodo = 'checked';
        // Filtrado completo
        $pdtoBandaSelect = $link->query("
          SELECT * FROM pdtobanda
           WHERE sttbanda != '9'
        ORDER BY sttbanda DESC,
                 idbanda,
                 banda");
      } else
      { // Buscar coincidencias
        $pdtoBandaSelect = $link->query("
          SELECT * FROM pdtobanda
           WHERE sttbanda = '1'
        ORDER BY idbanda,
                 banda"); }

      if ($pdtoBandaSelect->num_rows > 0)
      { $item = 0;

        while ($regPdtoBanda = $pdtoBandaSelect->fetch_object())
        { $item++;

          $pdtoBandas_idbanda[$item] = $regPdtoBanda->idbanda;
          $pdtoBandas_banda[$item] = $regPdtoBanda->banda;
          $pdtoBandas_anchura[$item] = $regPdtoBanda->anchura;
          $pdtoBandas_cdgbanda[$item] = $regPdtoBanda->cdgbanda;
          $pdtoBandas_sttbanda[$item] = $regPdtoBanda->sttbanda; }

        $nBandas = $item; }
    }
    // Fin del filtro de diseños

    echo '
      <div class="bloque">
        <form id="formPdtoBanda" name="formPdtoBanda" method="POST" action="pdtoBanda.php">
          <article class="subbloque">
            <label class="modulo_nombre">Banda de seguridad</label>
          </article>
          <input type="checkbox" id="chckVerTodo" name="chckVerTodo" onclick="document.formPdtoBanda.submit()" '.$vertodo.'><label>ver todo</label>
          <a href="ayuda.php#Bandas">'.$_help_blue.'</a>

          <section class="subbloque">
            <article>
              <label><b>id</b>entificador</label><br>
              <input type="text" id="textIdBanda" name="textIdBanda" value="'.$pdtoBanda_idbanda.'" placeholder="Nombre corto" required />
            </article>

            <article>
              <label>Nombre</label><br>
              <input type="text" id="textBanda" name="textBanda" value="'.$pdtoBanda_banda.'" placeholder="Nombre completo" required />
            </article>

            <article>
              <label>Anchura</label><br>
              <input type="text" class="input_numero" id="textAnchura" name="textAnchura" value="'.$pdtoBanda_anchura.'" placeholder="milímetros" required >
            </article>

            <article><br/>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>
          </section>
        </form>
      </div>';

    if ($nBandas > 0)
    { echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Catálogo de bandas de seguridad</label>
        </article>
        <label><b>'.$nBandas.'</b> Encontrada(s)</label>
        
        <section class="listado">
          <ul>';

      for ($item=1; $item<=$nBandas; $item++)
      { echo '
            <li><article>';
        
        if ((int)$pdtoBandas_sttbanda[$item] > 0)
        { echo '
                <a href="pdtoBanda.php?cdgbanda='.$pdtoBandas_cdgbanda[$item].'">'.$_search.'</a>
                <a href="pdtoBandaP.php?cdgbanda='.$pdtoBandas_cdgbanda[$item].'">'.$_link.'</a>
                <a href="pdtoBanda.php?cdgbanda='.$pdtoBandas_cdgbanda[$item].'&proceso=update">'.$_power_blue.'</a>'; 
        } else
        { echo '
                <a href="pdtoBanda.php?cdgbanda='.$pdtoBandas_cdgbanda[$item].'&proceso=delete">'.$_recycle_bin.'</a>                
                <a href="pdtoBanda.php?cdgbanda='.$pdtoBandas_cdgbanda[$item].'&proceso=update">'.$_power_black.'</a>'; }

        echo '</article>

              <article>
                <label class="textId">'.$pdtoBandas_idbanda[$item].'</label>
                <label><i><b>'.$pdtoBandas_anchura[$item].'</b>mm</i></label><br>
                <label class="textNombre">'.$pdtoBandas_banda[$item].'</label>
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
  <body>
</html>