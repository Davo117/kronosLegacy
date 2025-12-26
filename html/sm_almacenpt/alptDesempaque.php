<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">      
<?php
  include '../datos/mysql.php';
  $link = conectar();

  m3nu_empaque();

  $sistModulo_cdgmodulo = '81015';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);
    
    if ($_GET['mode']=='logout') { cl0s3(); }

    if ($_POST['textusername'] AND $_POST['textpassword']) { val1dat3($_POST['textusername'], $_POST['textpassword']); }

    if ($_SESSION['cdgusuario'])
    { ma1n(); }
    else
    { echo '
      <div id="loginform">
        <form id="login" action="alptDesempaque.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 

      exit; }

    $alptDesempaque_cdgempaque = trim($_POST['text_cdgempaque']);

    if ($_POST['btn_buscar'])
    { if (substr($sistModulo_permiso,0,2) != 'rw')
      { $msg_alert = $msg_norewrite; } 
      else
      { if ($alptDesempaque_cdgempaque != '')
        { $alptEmpaqueSelect = $link->query("
              SELECT pdtoimpresion.impresion AS producto,
              CONCAT(alptempaque.tpoempaque,alptempaque.noempaque) AS empaque
                FROM pdtoimpresion,
                     alptempaque
               WHERE pdtoimpresion.cdgimpresion = alptempaque.cdgproducto AND
                     alptempaque.cdgempaque = '".$alptDesempaque_cdgempaque."' AND
                     alptempaque.sttempaque = '1'");

          if ($alptEmpaqueSelect->num_rows > 0)
          { $readonly = "readonly";

            $regAlptEmpaque = $alptEmpaqueSelect->fetch_object();

            $alptDesempaque_producto = $regAlptEmpaque->producto;
            $alptDesempaque_empaque = $regAlptEmpaque->empaque;
          } else
          { $msg_alert = 'El empaque ingresado NO existe o ya fue enviado, favor de verificar la información.'; }
        }
      }
    }

    if ($_POST['btn_desempacar'])
    { if (substr($sistModulo_permiso,0,3) != 'rwx')
      { $msg_alert .= $msg_nodelete; } 
      else
      { if ($alptDesempaque_cdgempaque != '')
        { $alptDesempaque_observacion = "Desempacado por ".$_SESSION['usuario']." el ".date('Y-m-d H:i:s')." del empaque ".$alptDesempaque_empaque." (".$alptDesempaque_cdgempaque.")";

          $alptEmpaqueSelect = $link->query("
            SELECT tpoempaque
              FROM alptempaque
             WHERE cdgempaque = '".$alptDesempaque_cdgempaque."' AND
                   sttempaque = '1'");

          if ($alptEmpaqueSelect->num_rows > 0)
          { $regAlptEmpaque = $alptEmpaqueSelect->fetch_object();

            $link->query("
                INSERT INTO alptempaqueope
                  (cdgempaque, cdgoperacion, cdgempleado, fchoperacion, fchmovimiento)
                VALUES
                  ('".$alptDesempaque_cdgempaque."', '60001C ".date('Y-m-d H:i:s')."', '".$_SESSION['cdgusuario']."', '".date('Y-m-d')."', NOW())");

            if ($link->affected_rows > 0)
            { $msg_alert .= "Registro de la eliminación creado. ";

              $link->query("
                DELETE FROM alptempaque
                 WHERE cdgempaque = '".$alptDesempaque_cdgempaque."' AND
                       sttempaque = '1'");

              if ($link->affected_rows > 0)
              { $msg_alert .= "Empaque desactivado.";

                if ($regAlptEmpaque->tpoempaque == 'C')
                { $link->query("
                    DELETE FROM alptempaquep
                     WHERE cdgempaque = '".$alptDesempaque_cdgempaque."'");

                  if ($link->affected_rows > 0)
                  { $msg_alert .= "Empaque desarmado. ";

                    $link->query("
                      UPDATE prodpaquete
                         SET cdgempaque = '',
                             obs = CONCAT('".$alptDesempaque_observacion." ', obs),
                             sttpaquete = '1'
                       WHERE cdgempaque = '".$alptDesempaque_cdgempaque."' AND
                             sttpaquete = '9'"); 

                    if ($link->affected_rows > 0)
                    { $msg_alert .= "Producto liberado. ";
                    } else
                    { $msg_alert .= "El producto NO fue liberado. "; }
                  } else
                  { $msg_alert .= "El empaque NO fue borrado totalmente. "; }
                } elseif ($regAlptEmpaque->tpoempaque == 'Q')
                { $link->query("
                    DELETE FROM alptempaquer
                     WHERE cdgempaque = '".$alptDesempaque_cdgempaque."'");

                  if ($link->affected_rows > 0)
                  { $msg_alert .= "Empaque desarmado. ";

                    $link->query("
                      UPDATE prodrollo
                         SET cdgempaque = '',
                             obs = CONCAT('".$alptDesempaque_observacion." ', obs),
                             sttrollo = '6'
                       WHERE cdgempaque = '".$alptDesempaque_cdgempaque."' AND
                             sttrollo = '9'"); 

                    if ($link->affected_rows > 0)
                    { $msg_alert .= "Producto liberado. ";
                    } else
                    { $msg_alert .= "El producto NO fue liberado. "; }
                  } else
                  { $msg_alert .= "El empaque NO fue borrado totalmente. "; }
                }
              } else
              { $msg_alert .= "El empaque NO fue borrado. "; }
            } else
            { $msg_alert = "Registro de la eliminación NO fue creado."; }
          } else
          { $msg_alert = 'No fue posible determinar el Tipo de empaque.'; } 

          $alptDesempaque_cdgempaque = '';
        } else
        { $msg_alert .= 'El empaque ingresado NO existe o ya fue enviado, favor de verificar la información. '; }
      }
    }
            
    echo '
      <div class="bloque">
        <form id="formulario" name="formulario" method="POST" action="alptDesempaque.php">
          <article class="subbloque">
            <label class="modulo_nombre">Desempaque</label><br>
          </article>';

    if ($alptDesempaque_empaque == '')
    { echo '
          <section class="subbloque">
            <article>
              <label>Empaque</label><br/>
              <input type="text" id="text_cdgempaque" name="text_cdgempaque" value="'.$alptDesempaque_cdgempaque.'" placeholder="Código" autofocus '.$readonly.' required/>
            </article>

            <article>
              <input type="submit" id="btn_buscar" name="btn_buscar" value="Buscar" />
            </article>
          </section>';
    } else
    { echo '
          <section class="subbloque">
            <article>
              <label class="modulo_listado">Empaque <b>'.$alptDesempaque_empaque.'</b></label><br>
              <label class="modulo_listado">Producto <b>'.$alptDesempaque_producto.'</b></label>
            </article><hr>
            
            <article>
              <input type="hidden" id="text_cdgempaque" name="text_cdgempaque" value="'.$alptDesempaque_cdgempaque.'" />
              <input type="submit" id="btn_desempacar" name="btn_desempacar" value="Desempacar" />
            </article>
          </section>'; }

    echo '
        </form>
      </div>'; 

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
