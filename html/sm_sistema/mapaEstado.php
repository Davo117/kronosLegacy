<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Estados</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
<?php

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_sistema();

  $sistModulo_cdgmodulo = '9ME00';
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
        <form id="login" action="mapaEstado.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>';

      exit; }

    // Obtener los valores datos en la captura
    $mapaEstado_cdgEstado = trim($_POST['hideCdgEstado']);
    $mapaEstado_idEstado = trim($_POST['textIdEstado']);
    $mapaEstado_estado = trim($_POST['textEstado']);
    $mapaEstado_pais = trim($_POST['textPais']);

    if ($_GET['cdgestado']) { $mapaEstado_cdgEstado = $_GET['cdgestado']; }

    if ($_POST['bttnSalvar'])
    { if (substr($sistModulo_permiso,0,2) != 'rw')
      { $msg_alert = 'No cuentas con permisos de escritura en este modulo.';
      } else 
      { $mapaEstadoSelect = $link->query("
          SELECT * FROM mapaestado
           WHERE cdgestado = '".$mapaEstado_cdgEstado."' OR idestado = '".$mapaEstado_idEstado."'");

        if ($mapaEstadoSelect->num_rows > 0)
        { if ($mapaEstado_cdgEstado != '')
          { $link->query("
              UPDATE mapaestado
                 SET idestado = '".$mapaEstado_idEstado."',
                     estado = '".$mapaEstado_estado."',
                     pais = '".$mapaEstado_pais."'
               WHERE cdgestado = '".$mapaEstado_cdgEstado."' AND
                     sttestado = '1'");

            if ($link->affected_rows > 0)
            { $msg_alert = 'El registro fue ACTUALIZADO satisfactoriamente. (CR)'; }
            else
            { $msg_alert = 'El registro NO fue actualizado. (CR) \nNo presento cambios o su status no permite modificaciones'; }
          } else
          { $link->query("
              UPDATE mapaestado
                 SET estado = '".$mapaEstado_estado."',
                     pais = '".$mapaEstado_pais."'
               WHERE idestado = '".$mapaEstado_idEstado."' AND
                     sttestado = '1'");

            if ($link->affected_rows > 0)
            { $msg_alert = 'El registro fue ACTUALIZADO satisfactoriamente.'; }
            else
            { $msg_alert = 'El registro NO fue actualizado. \nNo presento cambios o su status no permite modificaciones.'; }
          }
        } else
        { for ($item = 100; $item <= 1000; $item++)
          { $mapaEstado_cdgEstado = str_pad($item,3,'0',STR_PAD_LEFT);

            $link->query("
              INSERT INTO mapaestado
                (idestado, estado, pais, cdgestado)
              VALUES
                ('".$mapaEstado_idEstado."','".$mapaEstado_estado."','".$mapaEstado_pais."','".$mapaEstado_cdgEstado."')");

            if ($link->affected_rows > 0)
            { $msg_alert = 'El registro fue INSERTADO satisfactoriamente.';
              break; }
          }
        }
      }

      $mapaEstado_cdgEstado = ''; }

    if (substr($sistModulo_permiso,0,1) == 'r')
    { // Consultar un registros
      if ($_GET['cdgestado'])
      { $mapaEstadoSelect = $link->query("
          SELECT * FROM mapaestado
           WHERE cdgestado = '".$mapaEstado_cdgEstado."'");

        if ($mapaEstadoSelect->num_rows > 0)
        { $regMapaEstado = $mapaEstadoSelect->fetch_object();

          $mapaEstado_idEstado = $regMapaEstado->idestado;
          $mapaEstado_estado = $regMapaEstado->estado;
          $mapaEstado_pais = $regMapaEstado->pais;
          $mapaEstado_cdgEstado = $regMapaEstado->cdgestado;
          $mapaEstado_sttestado = $regMapaEstado->sttestado; }
      }    
      // Cambiar el status de un registro
      if ($_GET['proceso'] == 'update')
      { if (substr($sistModulo_permiso,0,2) != 'rw')
        { $msg_alert = 'No cuentas con permisos de reescritura en este modulo.'; 
        } else
        { if ($mapaEstado_sttestado == '1')
          { $mapaEstado_newsttestado = '0'; }

          if ($mapaEstado_sttestado == '0')
          { $mapaEstado_newsttestado = '1'; }

          if ($mapaEstado_sttestado == '9')
          { $mapaEstado_newsttestado = '8'; }

          if ($mapaEstado_sttestado == '8')
          { $mapaEstado_newsttestado = '9'; }

          if ($mapaEstado_newsttestado != '')
          { $link->query("
              UPDATE mapaestado 
                 SET sttestado = '".$mapaEstado_newsttestado."'
               WHERE cdgestado = '".$mapaEstado_cdgEstado."'");

            if ($link->affected_rows > 0)
            { $msg_alert = 'El registro fue ACTUALIZADO satisfactoriamente.'; 
            } else
            { $msg_alert = 'El registro NO fue ACTUALIZADO.'; }
          } else
          { $msg_alert = 'El registro que seleccionaste, no tiene permitido cambiar de status.'; }
        }
      }

      // Remover un registro
      if ($_GET['proceso'] == 'delete')
      { if (substr($sistModulo_permiso,0,3) != 'rwx')
        { $msg_alert = 'No cuentas con permisos para remover en este modulo.'; 
        } else
        { $mapaCiudadSelect = $link->query("
            SELECT * FROM mapaciudad
             WHERE cdgestado = '".$mapaEstado_cdgEstado."'");

          if ($mapaCiudadSelect->num_rows > 0)
          { $msg_alert = 'El registro NO fue eliminado por que existen ciudades asociadas.'; }
          else
          { $link->query("
              DELETE FROM mapaestado
               WHERE cdgestado = '".$mapaEstado_cdgEstado."' AND
                     sttestado = '0'");

            if ($link->affected_rows > 0)
            { $msg_alert = 'El registro fue ELIMINADO satisfactoriamente.'; 
            } else
            { $msg_alert = 'El registro NO fue eliminado.'; }
          }
        }
      }

      // Filtrar de Estados
      $mapaEstadoSelect = $link->query("
        SELECT * FROM mapaestado
      ORDER BY sttestado DESC,
               pais,
               estado");

      if ($mapaEstadoSelect->num_rows > 0)
      { $item = 0;

        while ($regMapaEstado = $mapaEstadoSelect->fetch_object())
        { $item++;

          $mapaEstados_idEstado[$item] = $regMapaEstado->idestado;
          $mapaEstados_estado[$item] = $regMapaEstado->estado;
          $mapaEstados_pais[$item] = $regMapaEstado->pais;
          $mapaEstados_cdgEstado[$item] = $regMapaEstado->cdgestado;
          $mapaEstados_sttEstado[$item] = $regMapaEstado->sttestado; }
          
        $nEstados = $item; }
    }    

    // Armado del módulo
    echo '
      <div class="bloque">
        <form id="formMapaEstado" name="formMapaEstado" method="POST" action="mapaEstado.php" />
          <input type="hidden" id="hideCdgEstado" name="hideCdgEstado" value="'.$mapaEstado_cdgEstado.'" />

          <article class="subbloque">
            <label class="modulo_nombre">Estados</label>
          </article>

          <section class="subbloque">
            <article>
              <label>Abreviatura</label><br/>
              <input type="text" id="textIdEstado" name="textIdEstado" value="'.$mapaEstado_idEstado.'" required />
            </article>

            <article>
              <label>Estado</label><br/>
              <input type="text" id="textEstado" name="textEstado" value="'.$mapaEstado_estado.'" required />
            </article>

            <article>
              <label>País</label><br/>
              <input type="text" id="textPais" name="textPais" value="'.$mapaEstado_pais.'" required  />
            </article>
            
            <article><br>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>
          </section>
        </form>
      </div>';

    if ($nEstados > 0)
    { echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Catálogo de estados</label>
        </article>
        <label>[<b>'.$nEstados.'</b>] Encontrado(s)</label>';

      for ($item = 1; $item <= $nEstados; $item++)
      { echo '
        <section class="listado">
          <article>';

        if ($mapaEstados_sttEstado[$item] == '1' OR $mapaEstados_sttEstado[$item] == '9')
        { echo '
            <a href="mapaEstado.php?cdgestado='.$mapaEstados_cdgEstado[$item].'">'.$_search.'</a>
            <a href="mapaCiudad.php?cdgestado='.$mapaEstados_cdgEstado[$item].'">'.$_link.'</a>
            <a href="mapaEstado.php?cdgestado='.$mapaEstados_cdgEstado[$item].'&proceso=update">'.$_power_blue.'</a></tr>'; }

        if ($mapaEstados_sttEstado[$item] == '8')
        { echo '
            <a href="mapaEstado.php?cdgestado='.$mapaEstados_cdgEstado[$item].'&proceso=update">'.$_power_black.'</a></tr>'; }
        
        if ($mapaEstados_sttEstado[$item] == '0')
        { echo '
            <a href="mapaEstado.php?cdgestado='.$mapaEstados_cdgEstado[$item].'&proceso=delete">'.$_recycle_bin.'</a>
            <a href="mapaEstado.php?cdgestado='.$mapaEstados_cdgEstado[$item].'&proceso=update">'.$_power_black.'</a>'; }

        echo '
          </article>

          <article style="text-align:right">
            <label>Abreviatura</label><br/>
            <label>Estado</label>
          </article>

          <article>
            <label class="textId">'.$mapaEstados_idEstado[$item].' '.$mapaEstados_pais[$item].'</label><br/>
            <label class="textNombre">'.$mapaEstados_estado[$item].'</label>
          </article>
        </section>'; }

      echo '
      </div>'; }

    if ($msg_alert != '')
    { echo '
      <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
      <div align="center"><h1>Módulo no encontrado o bloqueado.</h1></div>'; }
?>

    </div>
  </body>
</html>