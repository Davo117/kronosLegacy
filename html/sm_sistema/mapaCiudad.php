<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Ciudades</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>    
    <div id="contenedor">
<?php

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_sistema();

  $sistModulo_cdgmodulo = '9MC00';
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
        <form id="login" action="mapaCiudad.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 

      exit; } 

    // Obtener los valores datos en la captura
    $mapaCiudad_cdgCiudad = trim($_POST['hideCdgCiudad']);
    $mapaCiudad_idCiudad = trim($_POST['textIdCiudad']);
    $mapaCiudad_ciudad = trim($_POST['textCiudad']);

    if ($_GET['cdgciudad']) { $mapaCiudad_cdgCiudad = $_GET['cdgciudad']; }
    if ($_GET['cdgestado']) { $mapaCiudad_cdgEstado = $_GET['cdgestado']; }
    if ($_POST['slctCdgEstado']) { $mapaCiudad_cdgEstado = $_POST['slctCdgEstado']; }
    
    if ($_POST['bttnSalvar'])
    { if (substr($sistModulo_permiso,0,2) != 'rw')
      { $msg_alert = 'No cuentas con permisos de escritura en este modulo.'; 
      } else
      { $mapaCiudadSelect = $link->query("
          SELECT * FROM mapaciudad
           WHERE cdgciudad = '".$mapaCiudad_cdgCiudad."' OR
                (cdgestado = '".$mapaCiudad_cdgEstado."' AND
                 idciudad = '".$mapaCiudad_idCiudad."')");

        if ($mapaCiudadSelect->num_rows > 0)
        { if ($mapaCiudad_cdgCiudad != '')
          { $link->query("
              UPDATE mapaciudad
                 SET idciudad = '".$mapaCiudad_idCiudad."',
                     ciudad = '".$mapaCiudad_ciudad."'
               WHERE cdgciudad = '".$mapaCiudad_cdgCiudad."' AND
                     sttciudad = '1'");

            if ($link->affected_rows > 0)
            { $msg_alert = 'La ciudad fue ACTUALIZADA satisfactoriamente. (CR)'; }
            else
            { $msg_alert = 'La ciudad NO fue actualizada. (CR) \nNo presento cambios o su status no permite modificaciones'; }
          } else
          { $link->query("
              UPDATE mapaciudad
                 SET ciudad = '".$mapaCiudad_ciudad."'
               WHERE cdgestado = '".$mapaCiudad_cdgEstado."' AND
                     idciudad = '".$mapaCiudad_idCiudad."' AND
                     sttciudad = '1'");

            if ($link->affected_rows > 0)
            { $msg_alert = 'La ciudad fue ACTUALIZADA satisfactoriamente.'; }
            else
            { $msg_alert = 'La ciudad NO fue actualizada. <br/> No presento cambios o su status no permite modificaciones.'; }
          }
        } else
        { for ($id = 1000; $id <= 10000; $id++)
          { $mapaCiudad_cdgCiudad = $mapaCiudad_cdgEstado.str_pad($id,4,'0',STR_PAD_LEFT);

            $link->query("
              INSERT INTO mapaciudad
                (cdgestado, idciudad, ciudad, cdgciudad)
              VALUES
                ('".$mapaCiudad_cdgEstado."','".$mapaCiudad_idCiudad."','".$mapaCiudad_ciudad."','".$mapaCiudad_cdgCiudad."')");

            if ($link->affected_rows > 0)
            { $msg_alert = 'La ciudad fue INSERTADA satisfactoriamente.';
              break; }  
          }
        }
      }

      $mapaCiudad_cdgCiudad = ''; }
    
    if (substr($sistModulo_permiso,0,1) == 'r')
    { // Búsqueda de un registro
      if ($_GET['cdgciudad'])
      { $mapaCiudadSelect = $link->query("
          SELECT * FROM mapaciudad
           WHERE cdgciudad = '".$mapaCiudad_cdgCiudad."'");

        if ($mapaCiudadSelect->num_rows > 0)
        { $regMapaCiudad = $mapaCiudadSelect->fetch_object();

          $mapaCiudad_cdgEstado = $regMapaCiudad->cdgestado;
          $mapaCiudad_idCiudad = $regMapaCiudad->idciudad;
          $mapaCiudad_ciudad = $regMapaCiudad->ciudad;
          $mapaCiudad_pais = $regMapaCiudad->pais;
          $mapaCiudad_cdgCiudad = $regMapaCiudad->cdgciudad;
          $mapaCiudad_sttCiudad = $regMapaCiudad->sttciudad; 
        }
      }

      // Cambiar de status un registro
      if ($_GET['proceso'] == 'update')
      { if (substr($sistModulo_permiso,0,2) != 'rw')
        { $msg_alert = 'No cuentas con permisos de reescritura en este modulo.'; 
        } else
        { if ($mapaCiudad_sttCiudad == '1')
          { $mapaCiudad_newsttciudad = '0'; }

          if ($mapaCiudad_sttCiudad == '0')
          { $mapaCiudad_newsttciudad = '1'; }

          if ($mapaCiudad_sttCiudad == '9')
          { $mapaCiudad_newsttciudad = '8'; }

          if ($mapaCiudad_sttCiudad == '8')
          { $mapaCiudad_newsttciudad = '9'; }

          if ($mapaCiudad_newsttciudad != '')
          { $vntsClienteSelect = $link->query("
            SELECT * FROM vntscliente
             WHERE cdgciudad = '".$mapaCiudad_cdgCiudad."'");

            if ($vntsClienteSelect->num_rows > 0)
            { $msg_alert = 'El ciudad NO fue actualizada por que existen clientes asociados.'; 
            } else
            { $vntsSucursalSelect = $link->query("
                SELECT * FROM vntssucursal
                 WHERE cdgciudad = '".$mapaCiudad_cdgCiudad."'");
              
              if ($vntsSucursalSelect->num_rows > 0)
              { $msg_alert = 'El ciudad NO fue actualizada por que existen sucursales asociadas.'; 
              } else
              { $link->query("
                  UPDATE mapaciudad
                     SET sttciudad = '".$mapaCiudad_newsttciudad."'
                   WHERE cdgciudad = '".$mapaCiudad_cdgCiudad."'");

                if ($link->affected_rows > 0)
                { $msg_alert = 'El ciudad fue ACTUALIZADO satisfactoriamente.'; 
                } else
                { $msg_alert = 'El ciudad NO fue ACTUALIZADO.'; }
              }
            }
          } else
          { $msg_alert = 'El ciudad que seleccionaste, no tiene permitido cambiar de status.'; }
        }
      }

      // Remover un registro
      if ($_GET['proceso'] == 'delete')
      { if (substr($sistModulo_permiso,0,3) != 'rwx')
        { $msg_alert = 'No cuentas con permisos para remover en este modulo.';
        } else
        { $vntsClienteSelect = $link->query("
            SELECT * FROM vntscliente
             WHERE cdgciudad = '".$mapaCiudad_cdgCiudad."'");

          if ($vntsClienteSelect->num_rows > 0)
          { $msg_alert = 'El ciudad NO fue eliminado por que existen clientes asociados.';
          } else
          { $vntsSucursalSelect = $link->query("
              SELECT * FROM vntssucursal
              WHERE cdgciudad = '".$mapaCiudad_cdgCiudad."'");
            
            if ($vntsSucursalSelect->num_rows > 0)
            { $msg_alert = 'El ciudad NO fue eliminado por que existen sucursales asociadas.';
            } else
            { $link->query("
                DELETE FROM mapaciudad
                 WHERE cdgciudad = '".$mapaCiudad_cdgCiudad."' AND
                       sttciudad = '0'");

              if ($link->affected_rows > 0)
              { $msg_alert = 'El ciudad fue ELIMINADO satisfactoriamente.'; }
              else
              { $msg_alert = 'El ciudad NO fue eliminado.'; }
            }
          }
        }
      }

      // Filtro de Estados
      $mapaEstadoSelect = $link->query("
        SELECT * FROM mapaestado
         WHERE sttestado = '1' OR sttestado = '9'
      ORDER BY sttestado DESC");
        
      if ($mapaEstadoSelect->num_rows > 0)
      { $item = 0;
        while ($regMapaEstado = $mapaEstadoSelect->fetch_object())
        { $item++;
        
          $mapaEstados_idEstado[$item] = $regMapaEstado->idestado;
          $mapaEstados_estado[$item] = $regMapaEstado->estado;
          $mapaEstados_cdgEstado[$item] = $regMapaEstado->cdgestado; }
      
        $nEstados = $item; }

      // Filtro de Ciudades por Estado
      $mapaCiudadSelect = $link->query("
        SELECT mapaciudad.ciudad,
               mapaestado.idestado,
               mapaciudad.cdgciudad,
               mapaciudad.sttciudad
          FROM mapaciudad,
               mapaestado
         WHERE mapaciudad.cdgestado = mapaestado.cdgestado AND
               mapaestado.cdgestado = '".$mapaCiudad_cdgEstado."'
      ORDER BY mapaciudad.sttciudad DESC,
               mapaciudad.ciudad");
      
      if ($mapaCiudadSelect->num_rows > 0)
      { $item = 0;
        while ($regMapaCiudad = $mapaCiudadSelect->fetch_object())
        { $item++;

           $mapaCiudades_ciudad[$item] = $regMapaCiudad->ciudad;
           $mapaCiudades_idEstado[$item] = $regMapaCiudad->idestado;
           $mapaCiudades_cdgCiudad[$item] = $regMapaCiudad->cdgciudad;
           $mapaCiudades_sttCiudad[$item] = $regMapaCiudad->sttciudad; }
      
        $nCiudades = $item; }
    }

    // Armado del módulo
    echo '
      <div class="bloque">
        <form id="formMapaCiudad" name="formMapaCiudad" method="POST" action="mapaCiudad.php" />
          <input type="hidden" id="hideCdgCiudad" name="hideCdgCiudad" value="'.$mapaCiudad_cdgCiudad.'" />

          <article class="subbloque">
            <label class="modulo_nombre">Ciudades por Estado</label>
          </article>

         <section class="subbloque">
            <article>
              <label><a href="mapaEstado.php?cdgestado='.$mapaCiudad_cdgEstado.'">Estado</a></label><br/>
              <select id="slctCdgEstado" name="slctCdgEstado" onchange="document.formMapaCiudad.submit()" required>
                <option value="">-</option>';

    for ($item = 1; $item <= $nEstados; $item++)
    { echo '
                <option value="'.$mapaEstados_cdgEstado[$item].'"';

      if ($mapaCiudad_cdgEstado == $mapaEstados_cdgEstado[$item])
      { echo ' selected="selected"'; }

      echo '>'.$mapaEstados_estado[$item].' | '.$mapaEstados_idEstado[$item].'</option>'; }

    echo '
              </select>
            </article>

            <article>
              <label>Código</label><br/>
              <input type="text" id="textIdCiudad" name="textIdCiudad" value="'.$mapaCiudad_idCiudad.'" required />
            </article>

            <article>            
              <label>Ciudad</label><br/>
              <input type="text" id="textCiudad" name="textCiudad" value="'.$mapaCiudad_ciudad.'" required  />
            </article>
            
            <article><br>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>
         </section>
        </form>
      </div>';

    if ($nCiudades > 0)
    { echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Catálogo de Ciudades por Estado</label>
        </article>
        <label>[<b>'.$nCiudades.'</b>] Encontrada(s)</label>';

      for ($item = 1; $item <= $nCiudades; $item++)
      { echo '
        <section class="listado">
          <article>';

        if ($mapaCiudades_sttCiudad[$item] == '1' OR $mapaCiudades_sttCiudad[$item] == '9')
        { echo '
            <a href="mapaCiudad.php?cdgciudad='.$mapaCiudades_cdgCiudad[$item].'">'.$_search.'</a>
            <a href="mapaCiudad.php?cdgciudad='.$mapaCiudades_cdgCiudad[$item].'&proceso=update">'.$_power_blue.'</a>'; }

        if ($mapaCiudades_sttCiudad[$item] == '8')
        { echo '
                        
            <a href="mapaCiudad.php?cdgciudad='.$mapaCiudades_cdgCiudad[$item].'&proceso=update">'.$_power_black.'</a>'; }

        if ($mapaCiudades_sttCiudad[$item] == '0')
        { echo '
            <a href="mapaCiudad.php?cdgciudad='.$mapaCiudades_cdgCiudad[$item].'&proceso=delete">'.$_recycle_bin.'</a>
            <a href="mapaCiudad.php?cdgciudad='.$mapaCiudades_cdgCiudad[$item].'&proceso=update">'.$_power_black.'</a>'; }
        
        echo '
          </article>

          <article>            
            <label class="textNombre">'.$mapaCiudades_ciudad[$item].', '.$mapaCiudades_idEstado[$item].'</label>
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