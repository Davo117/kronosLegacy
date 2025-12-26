<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Perfiles de Usuario</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>    
    <div id="contenedor">
<?php

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_sistema();

  $sistModulo_cdgmodulo = '90030';
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
        <form id="login" action="sistPerfil.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 

      exit; }

    $sistPerfil_idperfil = $_POST['textIdPerfil'];
    $sistPerfil_perfil = $_POST['textPerfil'];

    if ($_GET['cdgperfil']) { $sistPerfil_cdgperfil = $_GET['cdgperfil']; }



    if (substr($sistModulo_permiso,0,1) == 'r')
    { // Filtro de módulos
      $sistModuloSelect = $link->query("
        SELECT * FROM sistmodulo
         WHERE sttmodulo != '9'
      ORDER BY cdgmodulo");
        
      if ($sistModuloSelect->num_rows > 0)
      { $item = 0;
        while ($regSistModulo = $sistModuloSelect->fetch_object())      
        { $item++;

          $sistPerfil_idmodulo[$item] = $regSistModulo->idmodulo;
          $sistPerfil_modulo[$item] = $regSistModulo->modulo;
          $sistPerfil_cdgmodulo[$item] = $regSistModulo->cdgmodulo; }
          
        $nModulos = $sistModuloSelect->num_rows; }

      // Búsqueda de un registro
      if ($_GET['cdgperfil'])
      { $sistPerfilSelect = $link->query("
          SELECT * FROM sistperfil
           WHERE cdgperfil = '".$sistPerfil_cdgperfil."'");
            
        if ($sistPerfilSelect->num_rows > 0)
        { $regSistPerfil = $sistPerfilSelect->fetch_object();
            
          $sistPerfil_idperfil = $regSistPerfil->idperfil;
          $sistPerfil_perfil = $regSistPerfil->perfil;
          $sistPerfil_cdgperfil = $regSistPerfil->cdgperfil;
          $sistPerfil_sttperfil = $regSistPerfil->sttperfil;
                
          $sistPermisoSelect = $link->query("
            SELECT * FROM sistpermiso
             WHERE cdgperfil = '".$sistPerfil_cdgperfil."'");
              
          if ($sistPermisoSelect->num_rows > 0)
          { while ($regSistPermiso = $sistPermisoSelect->fetch_object())
            { $sistPerfil_permiso[$regSistPermiso->cdgmodulo] = $regSistPermiso->permiso; }
          }
        } else
        { $msg_alert = 'El perfil que buscas no pudo ser encontrado.'; }       
      }    

      // Cambiar de status un registro
      if ($_GET['proceso'] == 'update')
      { if (substr($sistModulo_permiso,0,2) != 'rw')
        { $msg_alert = 'No cuentas con permisos de reescritura en este modulo.'; 
        } else
        { if ($sistPerfil_sttperfil == '0')
          { $sistPerfil_newsttperfil = '1'; }
        
          if ($sistPerfil_sttperfil == '1')
          { $sistPerfil_newsttperfil = '0'; }
          
          if ($sistPerfil_newsttperfil != '')
          { $link->query("
              UPDATE sistperfil
                 SET sttperfil = '".$sistPerfil_newsttperfil."'
               WHERE cdgperfil = '".$sistPerfil_cdgperfil."'");
            
            if ($link->affected_rows > 0)
            { $msg_alert = "El registro fue actualizado exitosamente en su status."; }
            else
            { $msg_alert = "El registro NO fue actualizado en su status."; } 
          } 
          else
          { $msg_alert = 'Este perfil no puede ser afectado en su status.'; }              
        }
      }

      // Remover un registro    
      if ($_GET['proceso'] == 'delete')
      { if (substr($sistModulo_permiso,0,3) != 'rwx')
        { $msg_alert = 'No cuentas con permisos para remover en este modulo.'; 
        } else
        { $sistPermisoSelect = $link->query("
            SELECT * FROM sistusuario
             WHERE cdgperfil = '".$sistPerfil_cdgperfil."'");
          
          if ($sistPermisoSelect->num_rows > 0)  
          { $msg_alert = "El registro NO fue desechado por que se encuentra asignado."; }
          else
          { $link->query("
              DELETE FROM sistperfil                  
               WHERE cdgperfil = '".$sistPerfil_cdgperfil."' AND
                     sttperfil = '0'");
              
            if ($link->affected_rows > 0)
            { $msg_alert = "El perfil '".$sistPerfil_cdgperfil."' fue desechado exitosamente."; 

              $link->query("
                DELETE FROM sistpermiso
                 WHERE cdgperfil = '".$sistPerfil_cdgperfil."'");
            } else
            { $msg_alert = "El perfil '".$sistPerfil_cdgperfil."' NO fue desechado."; }               
          }
        }
      }

    if ($_POST['bttnSalvar'])
    { if (substr($sistModulo_permiso,0,2) != 'rw')
      { $msg_alert = 'No cuentas con permisos de escritura en este modulo.'; 
      } else
      { $sistPerfilSelect = $link->query("
          SELECT * FROM sistperfil
          WHERE idperfil = '".$sistPerfil_idperfil."'");
        
        if ($sistPerfilSelect->num_rows > 0)
        { $regSistPerfil = $sistPerfilSelect->fetch_object();
          $sistPerfil_cdgperfil = $regSistPerfil->cdgperfil;
          
          $link->query("
            UPDATE sistperfil
               SET perfil = '".$sistPerfil_perfil."'
             WHERE idperfil = '".$sistPerfil_idperfil."' AND
                   sttperfil = '1'");
          
          if ($link->affected_rows > 0)
          { $msg_alert = "El registro fue actualizado exitosamente."; }
          else
          { $msg_alert = "El registro NO fue actualizado."; }                  
        } else
        { for ($id_indice = 1; $id_indice <= 99; $id_indice++) 
          { $sistPerfil_cdgperfil = str_pad($id_indice,2,'0',STR_PAD_LEFT);
            
            $link->query("
              INSERT INTO sistperfil
                (idperfil, perfil, cdgperfil)
              VALUES
                ('".$sistPerfil_idperfil."', '".$sistPerfil_perfil."', '".$sistPerfil_cdgperfil."')");
                
            if ($link->affected_rows > 0)
            { $msg_alert = "El registro fue insertado exitosamente."; 
              break; }  
          }                  
        }
        
        // Permisos
        for ($item = 1; $item <= $nModulos; $item++)
        { $sistPerfil_permiso = $_POST['rdioCdgModulo'.$sistPerfil_cdgmodulo[$item]];
          
          if ($sistPerfil_permiso != '')
          { $sistPermidoInsert = $link->query("
              INSERT INTO sistpermiso
                (cdgperfil, cdgmodulo, permiso)
              VALUES
                ('".$sistPerfil_cdgperfil."', '".$sistPerfil_cdgmodulo[$item]."', '".$sistPerfil_permiso."')");
                
            if ($link->affected_rows > 0)
            { $msg_alert .= '\nModulo '.$sistPerfil_idmodulo[$item].' permiso INSERTADO.'; }                    
            else
            { $sistPermisoUpdate = $link->query("
                UPDATE sistpermiso
                   SET permiso = '".$sistPerfil_permiso."'
                 WHERE cdgperfil = '".$sistPerfil_cdgperfil."' AND
                       cdgmodulo = '".$sistPerfil_cdgmodulo[$item]."'");

              if ($link->affected_rows > 0)
              { $msg_alert .= '\nModulo '.$sistPerfil_idmodulo[$item].' permiso ACTUALIZADO.'; } 
            }
          } else
          { $sistPermisoDelete = $link->query("
              DELETE FROM sistpermiso
               WHERE cdgperfil = '".$sistPerfil_cdgperfil."' AND
                     cdgmodulo = '".$sistPerfil_cdgmodulo[$item]."'");
                
            if ($link->affected_rows > 0)
            { $msg_alert .= '\nModulo '.$sistPerfil_idmodulo[$item].' permiso REMOVIDO.'; }
          }              
        }                  
      }     
    }

      // Filtro de perfiles
      $sistPerfilSelect = $link->query("
        SELECT * FROM sistperfil
      ORDER BY sttperfil DESC,
               idperfil"); 
        
      if ($sistPerfilSelect->num_rows > 0)
      { $item = 0;
        while ($regSistPerfil = $sistPerfilSelect->fetch_object())
        { $item++;

          $sistPerfiles_idperfil[$item] = $regSistPerfil->idperfil;
          $sistPerfiles_perfil[$item] = $regSistPerfil->perfil;
          $sistPerfiles_cdgperfil[$item] = $regSistPerfil->cdgperfil; 
          $sistPerfiles_sttperfil[$item] = $regSistPerfil->sttperfil; }
          
        $nPerfiles = $item; }
    }
    
    // Armado del módulo
    echo '
      <form id="formSistPerfil" name="formSistPerfil" method="POST" action="sistPerfil.php" />
        <div class="bloque">
          <article class="subbloque">
            <label class="modulo_nombre">Perfiles de Usuario</label>
          </article>
           
          <section class="subbloque">
            <article>
              <label>Perfil</label><br/>
              <input type="text" id="textIdPerfil" name="textIdPerfil" value="'.$sistPerfil_idperfil.'" required/>            
            </article>

            <article>
              <label>Descripción</label><br/>
              <input type="text" id="textPerfil" name="textPerfil" value="'.$sistPerfil_perfil.'" required/>            
            </article>

            <article><br>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>            
          </section>
        </div>

        <div class="bloque">
          <article class="subbloque">
            <label class="modulo_listado">Catálogo de Módulos | Definición de permisos</label><br/>
            <label><b>---</b> Bloqueado | <b>r--</b> Lectura | <b>rw-</b> Lectura y escritura | <b>rwx</b> Lectura, escritura y remoción </label>
          </article>';
      
    for ($item = 1; $item <= $nModulos; $item++)      
    { if ($sistPerfil_permiso[$sistPerfil_cdgmodulo[$item]] != '')
      { if ($sistPerfil_permiso[$sistPerfil_cdgmodulo[$item]] == 'r')
        { $prmR = 'checked="checked"'; }

        if ($sistPerfil_permiso[$sistPerfil_cdgmodulo[$item]] == 'rw')
        { $prmRW = 'checked="checked"'; }

        if ($sistPerfil_permiso[$sistPerfil_cdgmodulo[$item]] == 'rwx')
        { $prmRWX = 'checked="checked"'; }
      } else
      { $prm = 'checked="checked"'; }

        echo '
          <section class="listado">
            <article>
              <input type="radio" name="rdioCdgModulo'.$sistPerfil_cdgmodulo[$item].'" value="" '.$prm.' />
              <label><b>---</b></label>
            </article>

            <article>              
              <input type="radio" name="rdioCdgModulo'.$sistPerfil_cdgmodulo[$item].'" value="r" '.$prmR.' />
              <label><b>r--</b></label>
            </article>

            <article>              
              <input type="radio" name="rdioCdgModulo'.$sistPerfil_cdgmodulo[$item].'" value="rw" '.$prmRW.'  />
              <label><b>rw--</b></label>
            </article>

            <article>              
              <input type="radio" name="rdioCdgModulo'.$sistPerfil_cdgmodulo[$item].'" value="rwx" '.$prmRWX.' />
              <label><b>rwx</b></label>
            </article>

            <article>
              <label class="textNombre">| <i>'.$sistPerfil_modulo[$item].'</i></label>
            </article>
          </section>'; 
            
        $prm = '';
        $prmR = '';
        $prmRW = '';
        $prmRWX = ''; }
      
    echo '
        </div>
      </form>';

    if ($nPerfiles > 0 )
    { echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Catálogo de Perfiles</label><br/>
        </article>
        <label>[<b>'.$nPerfiles.'</b>] Encontrado(s)</label>';

      for ($item = 1; $item <= $nPerfiles; $item++)
      { echo '
        <section class="listado">
          <article>';
            
        if ($sistPerfiles_sttperfil[$item] == 9)
        { echo '
            <a href="sistPerfil.php?cdgperfil='.$sistPerfiles_cdgperfil[$item].'">'.$_search.'</a>'; }

        if ($sistPerfiles_sttperfil[$item] == 1)
        { echo '
            <a href="sistPerfil.php?cdgperfil='.$sistPerfiles_cdgperfil[$item].'">'.$_search.'</a>
            <a href="sistPerfil.php?cdgperfil='.$sistPerfiles_cdgperfil[$item].'&proceso=update">'.$_power_blue.'</a>'; } 
        
        if ($sistPerfiles_sttperfil[$item] == 0)
        { echo '
            <a href="sistPerfil.php?cdgperfil='.$sistPerfiles_cdgperfil[$item].'&proceso=delete">'.$_recycle_bin.'</a>
            <a href="sistPerfil.php?cdgperfil='.$sistPerfiles_cdgperfil[$item].'&proceso=update">'.$_power_black.'</a>'; }

        echo '
          </article>

          <article>
            <label class="textNombre">'.$sistPerfiles_perfil[$item].'</label>
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
