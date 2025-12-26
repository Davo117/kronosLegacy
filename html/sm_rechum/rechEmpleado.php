<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
<?php
  include '../datos/mysql.php';
  $link = conectar();

  m3nu_r3chum();

  $sistModulo_cdgmodulo = '20010';
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
        <form id="login" action="rechEmpleado.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 

      exit; } 

    $rechEmpleado_idempleado = $_POST['txt_idempleado'];
    $rechEmpleado_empleado = $_POST['txt_empleado'];
    $rechEmpleado_telefono = $_POST['txt_telefono'];

    if ($_GET['cdgempleado']) 
    { if (substr($sistModulo_permiso,0,1) != 'r')
      { $msg_alert = $msg_noread; }
      else
      { $rechEmpleadoSelect = $link->query("
          SELECT * FROM rechempleado 
           WHERE cdgempleado = '".$_GET['cdgempleado']."'");

        if ($rechEmpleadoSelect->num_rows > 0) 
        { $regRechEmpleado = $rechEmpleadoSelect->fetch_object();

          $rechEmpleado_idempleado = $regRechEmpleado->idempleado;
          $rechEmpleado_empleado = $regRechEmpleado->empleado;
          $rechEmpleado_telefono = $regRechEmpleado->telefono;
          $rechEmpleado_cdgempleado = $regRechEmpleado->cdgempleado;
          $rechEmpleado_sttempleado = $regRechEmpleado->sttempleado; }

        if ($_GET['empleado'] == 'update') 
        { if (substr($sistModulo_permiso,0,2) != 'rw')
          { $msg_alert = $msg_norewrite; }
          else
          { if ($rechEmpleado_sttempleado == '1') 
            { $rechEmpleado_newsttempleado = '0'; }
          
            if ($rechEmpleado_sttempleado == '0') 
            { $rechEmpleado_newsttempleado = '1'; }

            if ($rechEmpleado_newsttempleado != '')
            { $link->query("
                UPDATE rechempleado
                   SET sttempleado = '".$rechEmpleado_newsttempleado."'
                 WHERE cdgempleado = '".$rechEmpleado_cdgempleado."'");

              if ($link->affected_rows > 0) 
              { $msg_alert = 'El empleado fue afectado en su status.'; }
              else 
              { $msg_alert = 'El empleado NO fue afectado.'; }
            }
          }
        }        

        if ($_GET['empleado'] == 'delete') 
        { if (substr($sistModulo_permiso,0,3) != 'rwx')
          { $msg_alert = $msg_nodelete; }  
          else
          { $prodLoteOpeSelect = $link->query("
              SELECT * FROM prodloteope
               WHERE cdgempleado = '".$rechEmpleado_cdgempleado."'");

            if ($prodLoteOpeSelect->num_rows > 0) 
            { $msg_alert = 'El empleado tiene información ligada en produccion, NO fue posible eliminarlo.'; }
            else 
            { $prodBobinaOpeSelect = $link->query("
                SELECT * FROM prodbobinaope
                 WHERE cdgempleado = '".$rechEmpleado_cdgempleado."'");

              if ($prodBobinaOpeSelect->num_rows > 0) 
              { $msg_alert = 'El empleado tiene información ligada en produccion, NO fue posible eliminarlo.'; }
              else 
              { $prodRolloOpeSelect = $link->query("
                  SELECT * FROM prodrolloope
                   WHERE cdgempleado = '".$rechEmpleado_cdgempleado."'");

                if ($prodRolloOpeSelect->num_rows > 0) 
                { $msg_alert = 'El empleado tiene información ligada en produccion, NO fue posible eliminarlo.'; }
                else 
                { $link->query("
                    DELETE FROM rechempleado
                     WHERE cdgempleado = '".$rechEmpleado_cdgempleado."' AND 
                           sttempleado = '0'");

                  if ($link->affected_rows > 0) 
                  { $msg_alert = 'El empleado fue eliminado con exito.'; }
                  else 
                  { $msg_alert = 'El empleado NO se encuentra registrado.'; }
                }
              }
            }
          }
        }        
      }
    }
  
    if ($_POST['btn_salvar'])
    { if (substr($sistModulo_permiso,0,2) != 'rw')
      { $msg_alert = $msg_norewrite; }
      else
      { $rechEmpleadoSelect = $link->query("
          SELECT * FROM rechempleado
           WHERE idempleado = '".$rechEmpleado_idempleado."'");

        if ($rechEmpleadoSelect->num_rows > 0)
        { $regRecHEmpleado = $rechEmpleadoSelect->fetch_object();
          $rechEmpleado_cdgempleado = $regRecHEmpleado->cdgempleado;

          $link->query("
        	  UPDATE rechempleado
        	     SET empleado = '".$rechEmpleado_empleado."',
                   telefono = '".$rechEmpleado_telefono."'
        	   WHERE cdgempleado = '".$rechEmpleado_cdgempleado."' AND 
                   sttempleado = '1'"); 

          if ($link->affected_rows > 0) 
          { $msg_alert = 'El empleado fue actualizado.'; }
          else 
          { $msg_alert = 'El empleado NO fue actualizado.'; }
        } else
        { for ($cdgempleado = 1; $cdgempleado <= 9999; $cdgempleado++) 
          { $rechEmpleado_cdgempleado = str_pad($cdgempleado,4,'0',STR_PAD_LEFT);
            
            $link->query("
              INSERT INTO rechempleado
                (idempleado, empleado, telefono, cdgempleado)
              VALUES
                ('".$rechEmpleado_idempleado."', '".$rechEmpleado_empleado."', '".$rechEmpleado_telefono."', '".$rechEmpleado_cdgempleado."')");
          
            if ($link->affected_rows > 0) 
            { $msg_alert = 'El empleado fue registrado con exito.'; 
              break; }
          }
        }

        $cdgusuario = '../img_rechum/'.$rechEmpleado_cdgempleado.'.jpg';

        if(($_FILES['fileupload']['type'] == "image/pjpeg") or ($_FILES['fileupload']['type'] == "image/jpeg"))
        { if (move_uploaded_file($_FILES['fileupload']['tmp_name'], $cdgusuario))
          { $msg_alert += '\n Imagen asignada'; }
          else
          { $msg_alert += '\n Imagen sin cambios.'; }
        } else
        { $msg_alert += '\n El formato debe ser JPG.'; }

      }
    }

    if ($_POST['chk_vertodo'])
    { $vertodo = 'checked'; 

      $rechEmpleadoSelect = $link->query("
        SELECT * FROM rechempleado 
         WHERE sttempleado != '9'
      ORDER BY sttempleado DESC,
               empleado"); 
    } else
    { $rechEmpleadoSelect = $link->query("
        SELECT * FROM rechempleado
         WHERE sttempleado = '1'
      ORDER BY sttempleado DESC,
               empleado"); }

    if ($rechEmpleadoSelect->num_rows > 0) 
    { $item = 0;
      while($regRechEmpleado = $rechEmpleadoSelect->fetch_object()) 
      { $item++;

        $rechEmpleados_idempleado[$item] = $regRechEmpleado->idempleado; 
        $rechEmpleados_empleado[$item] = $regRechEmpleado->empleado; 
        $rechEmpleados_telefono[$item] = $regRechEmpleado->telefono; 
        $rechEmpleados_cdgempleado[$item] = $regRechEmpleado->cdgempleado; 
        $rechEmpleados_sttempleado[$item] = $regRechEmpleado->sttempleado; }

      $nEmpleados = $rechEmpleadoSelect->num_rows; }

    echo '
      <div class="bloque">
        <form id="formulario" name="formulario" method="POST" action="rechEmpleado.php" enctype="multipart/form-data">
          <article class="subbloque">
            <label class="modulo_nombre">Empleados</label>
          </article>
          <input type="checkbox" id="chk_vertodo" name="chk_vertodo" onclick="document.formulario.submit()" '.$vertodo.'><label>ver todo</label>
          <a href="ayuda.php#Empleados"><img id="imagen_ayuda" src="../img_sistema/help_blue.png"/></a>

          <section class="subbloque">
            <article>
              <label>No. Empleado</label><br>
              <input type="text" class="input_numero" id="txt_idempleado" name="txt_idempleado" value="'.$rechEmpleado_idempleado.'" placeholder="#" autofocus required/><br>
            </article>

            <article>
              <label>Nombre</label><br>
              <input type="text" id="txt_empleado" name="txt_empleado" value="'.$rechEmpleado_empleado.'" placeholder="Nombre del empleado" required/><br>
            </article>

            <article>
              <label>Teléfono</label><br>
              <input type="text" id="txt_telefono" name="txt_telefono" value="'.$rechEmpleado_telefono.'" placeholder="Teléfono de contacto" required/><br>
            </article>

            <article>
              <label>Foto </label><br>              
              <input type="file" id="fileupload" name="fileupload" accept="image/jpeg" />
            </article>

            <article><br>
              <input type="submit" id="btn_salvar" name="btn_salvar" value="Salvar" />
            </article>
          </section>
        </form>
      </div>';

    if ($_GET['cdgempleado'] AND file_exists("../img_rechum/".$rechEmpleado_cdgempleado.".jpg"))
    { echo '
      <div class="bloque">
        <article class="subbloque">
          <label style="font-size:14px">Ficha empleado</label>
        </article>
        <section class="subbloque">
          <article>
            <img class="imagen_empleado" src="../img_rechum/'.$rechEmpleado_cdgempleado.'.jpg" />
          </article>
          <article style="vertical-align:top; padding-left:4px;">
            <label>Empleado</label><br>
            <label style="font-size:32px">&nbsp;'.$rechEmpleado_idempleado.'</label><br>
            <label>Nombre</label><br>
            <label style="font-size:16px">&nbsp;'.$rechEmpleado_empleado.'</label><br>
            <label>Teléfono de contacto</label><br>
            <label style="font-size:16px">&nbsp;'.$rechEmpleado_telefono.'</label><br>
          </article>
        </section>
      </div>'; }

    if ($nEmpleados > 0) 
    { echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Catálogo de empleados</label>
        </article>
        <label><b>'.$nEmpleados.'</b> Registros encontrados</label>

        <section class="listado">
          <ul>';

      for ($item=1; $item<=$nEmpleados; $item++) 
      { echo '
            <li><article>';

        if ($rechEmpleados_sttempleado[$item] > 0) 
        { echo '
                <a href="rechEmpleado.php?cdgempleado='.$rechEmpleados_cdgempleado[$item].'">'.$_search.'</a>
                <a href="rechEmpleado.php?cdgempleado='.$rechEmpleados_cdgempleado[$item].'&empleado=update">'.$_power_blue.'</a>'; }
        else 
        { echo '
                <a href="rechEmpleado.php?cdgempleado='.$rechEmpleados_cdgempleado[$item].'&empleado=delete">'.$_recycle_bin.'</a>            
                <a href="rechEmpleado.php?cdgempleado='.$rechEmpleados_cdgempleado[$item].'&empleado=update">'.$_power_black.'</a>'; }

          echo '
              </article>

              <article>            
                <label class="textId"><b>'.$rechEmpleados_idempleado[$item].'</b></label>
                <label>'.$rechEmpleados_telefono[$item].'</label><br/>
                <label class="textNombre">'.$rechEmpleados_empleado[$item].'</label>                
              </article></li>';


      }

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