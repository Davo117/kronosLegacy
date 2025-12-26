<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Contactos por cliente</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
<?php
  include '../datos/mysql.php';
  $link = conectar();

  m3nu_ventas();

  $sistModulo_cdgmodulo = '40101';
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
        <form id="login" action="vntsClienteCntc.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 

      exit; } 

    $vntsClienteCntc_cdgcliente = $_POST['slctCdgCliente'];
    $vntsClienteCntc_idcontacto = trim($_POST['textidContacto']);
    $vntsClienteCntc_contacto = trim($_POST['textContacto']);
    $vntsClienteCntc_puesto = trim($_POST['textPuesto']);
    $vntsClienteCntc_telefono = trim($_POST['textTelefono']);
    $vntsClienteCntc_movil = trim($_POST['textMovil']);
    $vntsClienteCntc_email = trim($_POST['textMail']);

    if ($_GET['cdgcliente']) { $vntsClienteCntc_cdgcliente = $_GET['cdgcliente']; }
    $vntsClienteCntc_cdgcontacto = trim($_POST['hidden_cdgcontacto']);

    if ($_GET['cdgcontacto'])
    { if (substr($sistModulo_permiso,0,1) != 'r')
      { $msg_alert = 'No cuentas con permisos de lectura en este modulo.'; }        
      else
      { $vntsContactoSelect = $link->query("
          SELECT * FROM vntscontacto
           WHERE cdgcontacto = '".$_GET['cdgcontacto']."'");

        if ($vntsContactoSelect->num_rows > 0)
        { $regVntsContacto = $vntsContactoSelect->fetch_object();

          $vntsClienteCntc_cdgcliente = $regVntsContacto->cdgcliente;
          $vntsClienteCntc_idcontacto = $regVntsContacto->idcontacto;
          $vntsClienteCntc_contacto = $regVntsContacto->contacto;
          $vntsClienteCntc_puesto = $regVntsContacto->puesto;
          $vntsClienteCntc_telefono = $regVntsContacto->telefono;
          $vntsClienteCntc_movil = $regVntsContacto->movil;
          $vntsClienteCntc_email = $regVntsContacto->email;
          $vntsClienteCntc_cdgcontacto = $regVntsContacto->cdgcontacto;
          $vntsClienteCntc_sttcontacto = $regVntsContacto->sttcontacto;

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) != 'rw')
            { $msg_alert = 'No cuentas con permisos para actualizar este registro.'; }
            else
            { if ($vntsClienteCntc_sttcontacto == '1')
              { $vntsClienteCntc_newsttcontacto = '0'; }
                
              if ($vntsClienteCntc_sttcontacto == '0')
              { $vntsClienteCntc_newsttcontacto = '1'; }

              if ($vntsClienteCntc_newsttcontacto != '')
              { $link->query("
                  UPDATE vntscontacto 
                     SET sttcontacto = '".$vntsClienteCntc_newsttcontacto."'
                   WHERE cdgcontacto = '".$vntsClienteCntc_cdgcontacto."'");

                if ($link->affected_rows > 0)
                { $msg_alert = 'El contacto fue ACTUALIZADO satisfactoriamente.'; }
                else
                { $msg_alert = 'El contacto NO fue ACTUALIZADO.'; }

              } else
              { $msg_alert = 'El contacto que seleccionaste, no tiene permitido cambiar de status.'; }
            }
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) != 'rwx')
            { $msg_alert = 'No cuentas con permisos para remover en este modulo.'; }
            else
            { $link->query("
                DELETE FROM vntscontacto
                 WHERE cdgcontacto = '".$vntsClienteCntc_cdgcontacto."' AND
                       sttcontacto = '0'");

              if ($link->affected_rows > 0)
              { $msg_alert = 'El contacto fue ELIMINADO satisfactoriamente.'; }
              else
              { $msg_alert = 'El contacto NO fue eliminado.'; }
            }
          }
        }
      }
    }

    if ($_POST['bttnSalvar'])
    { if (substr($sistModulo_permiso,0,2) != 'rw')
      { $msg_alert = 'No cuentas con permisos de escritura en este modulo.'; }
      else
      { $vntsContactoSelect = $link->query("
          SELECT * FROM vntscontacto
           WHERE cdgcontacto = '".$vntsClienteCntc_cdgcontacto."' OR 
                (cdgcliente = '".$vntsClienteCntc_cdgcliente."' AND
                 idcontacto = '".$vntsClienteCntc_idcontacto."')");

        if ($vntsContactoSelect->num_rows > 0)
        { if ($vntsClienteCntc_cdgcontacto != '')
          { $link->query("
              UPDATE vntscontacto
                 SET idcontacto = '".$vntsClienteCntc_idcontacto."',
                     contacto = '".$vntsClienteCntc_contacto."',
                     puesto = '".$vntsClienteCntc_puesto."',
                     telefono = '".$vntsClienteCntc_telefono."',
                     movil = '".$vntsClienteCntc_movil."',
                     email = '".$vntsClienteCntc_email."'
               WHERE cdgcontacto = '".$vntsClienteCntc_cdgcontacto."' AND
                     sttcontacto = '1'");
          } else
          { $link->query("
              UPDATE vntscontacto
                 SET contacto = '".$vntsClienteCntc_contacto."',
                     puesto = '".$vntsClienteCntc_puesto."',
                     telefono = '".$vntsClienteCntc_telefono."',
                     movil = '".$vntsClienteCntc_movil."',
                     email = '".$vntsClienteCntc_email."'
               WHERE cdgcliente = '".$vntsClienteCntc_cdgcliente."' AND
                     idcontacto = '".$vntsClienteCntc_idcontacto."' AND
                     sttcontacto = '1'"); }

            if ($link->affected_rows > 0)
            { $msg_alert = 'El contacto fue ACTUALIZADO satisfactoriamente.'; }
            else
            { $msg_alert = 'El contacto NO fue actualizado. \nNo presento cambios o su status no permite modificaciones.'; }
        } else
        { for ($id = 1; $id <= 10; $id++)
          { $vntsClienteCntc_cdgcontacto = $vntsClienteCntc_cdgcliente.str_pad($id,6,'0',STR_PAD_LEFT);

            $link->query("
              INSERT INTO vntscontacto
                (cdgcliente, idcontacto, contacto, puesto, telefono, movil, email, cdgcontacto)
              VALUES
                ('".$vntsClienteCntc_cdgcliente."','".$vntsClienteCntc_idcontacto."','".$vntsClienteCntc_contacto."','".$vntsClienteCntc_puesto."','".$vntsClienteCntc_telefono."','".$vntsClienteCntc_movil."','".$vntsClienteCntc_email."','".$vntsClienteCntc_cdgcontacto."')");

            if ($link->affected_rows > 0)
            { $msg_alert = 'El contacto fue INSERTADO satisfactoriamente.'; 
              break; }
          }
        }
      }

      $vntsClienteCntc_cdgcontacto = ''; }

    $vntsClienteSelect = $link->query("
      SELECT * FROM vntscliente
       WHERE sttcliente = '1'
    ORDER BY sttcliente DESC,
             cliente");

    if ($vntsClienteSelect->num_rows > 0)
    { $item = 0;
      while ($regVntsCliente = $vntsClienteSelect->fetch_object())
      { $item++;

        $vntsClientes_idcliente[$item] = $regVntsCliente->idcliente;
        $vntsClientes_cliente[$item] = $regVntsCliente->cliente;
        $vntsClientes_cdgcliente[$item] = $regVntsCliente->cdgcliente;
        $vntsClientes_sttcliente[$item] = $regVntsCliente->sttcliente; }
        
      $nClientes = $vntsClienteSelect->num_rows; }

    // Catalogo de clientes
    if ($_POST['chk_vertodo'])
    { $vertodo = 'checked'; 

      $vntsContactoSelect = $link->query("
        SELECT * FROM vntscontacto
         WHERE cdgcliente = '".$vntsClienteCntc_cdgcliente."'
      ORDER BY sttcontacto DESC,
               contacto");
    } else
    { $vntsContactoSelect = $link->query("
        SELECT * FROM vntscontacto
         WHERE cdgcliente = '".$vntsClienteCntc_cdgcliente."' AND
               sttcontacto = '1'
      ORDER BY sttcontacto DESC,
               contacto"); }

    if ($vntsContactoSelect->num_rows > 0)
    { $item = 0;
      while ($regVntsContacto = $vntsContactoSelect->fetch_object())
      { $item++;

        $vntsContactos_idcontacto[$item] = $regVntsContacto->idcontacto;
        $vntsContactos_contacto[$item] = $regVntsContacto->contacto;
        $vntsContactos_puesto[$item] = $regVntsContacto->puesto;
        $vntsContactos_cdgcontacto[$item] = $regVntsContacto->cdgcontacto;
        $vntsContactos_sttcontacto[$item] = $regVntsContacto->sttcontacto; }

      $nContactos = $vntsContactoSelect->num_rows; }

    echo '
      <div class="bloque">
        <form id="formulario" name="formulario" method="POST" action="vntsClienteCntc.php" />
          <input type="hidden" id="hidden_cdgcontacto" name="hidden_cdgcontacto" value="'.$vntsClienteCntc_cdgcontacto.'" />

          <article class="subbloque">
            <label class="modulo_nombre">Contactos por clientes</label>
          </article>
          <input type="checkbox" id="chk_vertodo" name="chk_vertodo" onclick="document.formulario.submit()" '.$vertodo.'><label>ver todo</label>
          <a href="ayuda.php#ContactosCliente">'.$_help_blue.'</a>

          <section class="subbloque">
            <article>
              <a href="vntsCliente.php?cdgcliente='.$vntsClienteCntc_cdgcliente.'">Cliente</a><br/>
              <select id="slctCdgCliente" name="slctCdgCliente" onchange="document.formulario.submit()" '.$slctCdgCliente.' required >
                <option value="">-Clientes-</option>';

    for ($item = 1; $item <= $nClientes; $item++)
    { echo '
                <option value="'.$vntsClientes_cdgcliente[$item].'"';

      if ($vntsClienteCntc_cdgcliente == $vntsClientes_cdgcliente[$item])
      { echo ' selected="selected"'; }

      echo '>'.$vntsClientes_cliente[$item].' ('.$vntsClientes_idcliente[$item].')</option>'; }

    echo '
              </select>            
            </article>

            <article>
              <label>Identificador</label><br/>
              <input type="text" id="textidContacto" name="textidContacto" value="'.$vntsClienteCntc_idcontacto.'" placeholder="Identificador" required />
            </article>

            <article>
              <label>Nombre</label><br/>
              <input type="text" id="textContacto" name="textContacto" value="'.$vntsClienteCntc_contacto.'" placeholder="Nombre de contacto" required />
            </article>

            <article>
              <label>Puesto</label><br/>
              <input type="text" id="textPuesto" name="textPuesto" value="'.$vntsClienteCntc_puesto.'" placeholder="Puesto" required />
            </article>

            <article>
              <label>Teléfono</label><br/>
              <input type="tel" id="textTelefono" name="textTelefono" value="'.$vntsClienteCntc_telefono.'" required />
            </article>

            <article>
              <label>Móvil</label><br/>
              <input type="tel" id="textMovil" name="textMovil" value="'.$vntsClienteCntc_movil.'" required />
            </article>

            <article>
              <label>eM@il</label><br/>
              <input type="email" id="textMail" name="textMail" value="'.$vntsClienteCntc_email.'" required />
            </article>

            <article>
              <br>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>
          </section>
        </form>
      </div>';

    if ($nContactos > 0)
    { echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Catálogo de contactos por cliente</label>
        </article>
        <label>[<b>'.$nContactos.'</b>] Encontrado(s)</label>';

      for ($item = 1; $item <= $nContactos; $item++)
      { echo '
        <section class="listado">
          <article>';

        if ((int)$vntsContactos_sttcontacto[$item] > 0)
        { echo '
            <a href="vntsClienteCntc.php?cdgcontacto='.$vntsContactos_cdgcontacto[$item].'">'.$_search.'</a>
            <a href="vntsClienteCntc.php?cdgcontacto='.$vntsContactos_cdgcontacto[$item].'&proceso=update">'.$_power_blue.'</a>'; 
        } else
        { echo '
            <a href="vntsClienteCntc.php?cdgcontacto='.$vntsContactos_cdgcontacto[$item].'&proceso=delete">'.$_recycle_bin.'</a>
            <a href="vntsClienteCntc.php?cdgcontacto='.$vntsContactos_cdgcontacto[$item].'&proceso=update">'.$_power_black.'</a>'; }
        
        echo '
          </article>

          <article style="text-align:right">
            <label>Identificador</label><br/>
            <label>Contacto</label><br/>
            <label>Puesto</label>
          </article>

          <article>
            <label class="textId">'.$vntsContactos_idcontacto[$item].'</label><br/>
            <label><b>'.$vntsContactos_contacto[$item].'</b></label><br/>
            <label class="textNOmbre">'.$vntsContactos_puesto[$item].'</label>
          </article>
        </section>'; }

      echo'
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