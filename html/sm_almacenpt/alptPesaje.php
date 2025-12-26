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

  $sistModulo_cdgmodulo = '81010';
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
        <form id="login" action="alptPesaje.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 

      exit; } 

    $alptPesaje_cdgempaque = trim($_POST['text_cdgempaque']);
    $alptPesaje_peso = trim($_POST['text_peso']);

    if ($_POST['btn_buscar'])
    { if (substr($sistModulo_permiso,0,2) != 'rw')
      { $msg_alert = $msg_norewrite; } 
      else
      { if ($alptPesaje_cdgempaque != '')
        { $alptEmpaqueSelect = $link->query("
              SELECT pdtoimpresion.impresion AS producto,
              CONCAT(alptempaque.tpoempaque,alptempaque.noempaque) AS empaque
                FROM pdtoimpresion,
                     alptempaque
               WHERE pdtoimpresion.cdgimpresion = alptempaque.cdgproducto AND
                     alptempaque.cdgempaque = '".$alptPesaje_cdgempaque."' AND
                     alptempaque.sttempaque = '1'");

          if ($alptEmpaqueSelect->num_rows > 0)
          { $readonly = "readonly";

            $regAlptEmpaque = $alptEmpaqueSelect->fetch_object();

            $alptPesaje_producto = $regAlptEmpaque->producto;
            $alptPesaje_empaque = $regAlptEmpaque->empaque;
          } else
          { $msg_alert = 'El empaque ingresado NO existe o ya fue enviado, favor de verificar la información.'; }
        }
      }
    }

    if ($_POST['btn_salvar'])
    { if (substr($sistModulo_permiso,0,2) != 'rw')
      { $msg_alert = $msg_norewrite; } 
      else
      { if ($alptPesaje_cdgempaque != '')
        { if (is_numeric($alptPesaje_peso))
          { if ($alptPesaje_peso > 0)
            { // Actualizar el peso
              $link->query("
                UPDATE alptempaque
                   SET peso = '".$alptPesaje_peso."'
                 WHERE cdgempaque = '".$alptPesaje_cdgempaque."' AND
                       cdgembarque = ''"); 

              if ($link->affected_rows > 0)
              { $link->query("
                  INSERT INTO alptempaqueope
                    (cdgempaque, cdgoperacion, cdgempleado, fchoperacion, fchmovimiento)
                  VALUES
                    ('".$alptPesaje_cdgempaque."', '60002', '".$_SESSION['cdgusuario']."', '".date('Y-m-d')."', NOW())");

                if ($link->affected_rows > 0)
                { $msg_alert = "El peso fue registrado con exito. \n"; }
                else 
                { $link->query("
                    INSERT INTO alptempaqueope
                      (cdgempaque, cdgoperacion, cdgempleado, fchoperacion, fchmovimiento)
                    VALUES
                      ('".$alptPesaje_cdgempaque."', '60002 ".date('Y-m-d H:i:s')."', '".$_SESSION['cdgusuario']."', '".date('Y-m-d')."', NOW())"); }

                $msg_alert = "El peso fue actualizado con exito."; }
            } 

            $alptPesaje_cdgempaque = '';
          } else
          { $msg_alert = 'El peso ingresado NO es numérico.'; }
        } else
        { $msg_alert = 'El empaque ingresado NO existe o ya fue enviado, favor de verificar la información.'; }
      }
    }

    echo '
      <div class="bloque">
        <form id="formulario" name="formulario" method="POST" action="alptPesaje.php">
          <article class="subbloque">
            <label class="modulo_nombre">Pesaje de empaques</label><br>
          </article>';

    if ($alptPesaje_empaque == '')
    { echo '
          <section class="subbloque">
            <article>
              <label>Empaque</label><br/>
              <input type="text" id="text_cdgempaque" name="text_cdgempaque" value="'.$alptPesaje_cdgempaque.'" placeholder="Código" autofocus '.$readonly.' required/>
            </article>

            <article>
              <input type="submit" id="btn_buscar" name="btn_buscar" value="Buscar" />
            </article>
          </section>';
    } else
    { echo '
          <section class="subbloque">
            <article>
              <label class="modulo_listado">Empaque <b>'.$alptPesaje_empaque.'</b></label><br>
              <label class="modulo_listado">Producto <b>'.$alptPesaje_producto.'</b></label>
            </article><hr>

            <article>
              <label>Empaque</label><br/>
              <input type="text" class="input_numero" id="text_peso" name="text_peso" value="'.$alptPesaje_peso.'" placeholder="Kgs" autofocus required/>
            </article>
            
            <article>
              <input type="hidden" id="text_cdgempaque" name="text_cdgempaque" value="'.$alptPesaje_cdgempaque.'" />
              <input type="submit" id="btn_salvar" name="btn_salvar" value="Salvar" />
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
