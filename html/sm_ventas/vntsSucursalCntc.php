<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Contactos por sucursal</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
<?php

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_ventas();

  $sistModulo_cdgmodulo = '40111';
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

    $vntsSucursalCntc_cdgsucursal = $_POST['slctCdgSucursal'];
    $vntsSucursalCntc_idcontacto = trim($_POST['textIdContacto']);
    $vntsSucursalCntc_contacto = trim($_POST['textContacto']);
    $vntsSucursalCntc_puesto = trim($_POST['textPuesto']);
    $vntsSucursalCntc_telefono = trim($_POST['textTelefono']);
    $vntsSucursalCntc_movil = trim($_POST['textMovil']);
    $vntsSucursalCntc_email = trim($_POST['textMail']);

    if ($_GET['cdgsucursal'])
    { $vntsSucursalCntc_cdgsucursal = $_GET['cdgsucursal']; }

    if ($_POST['bttnSalvar'])
    { $msg_alert = 'Salvando ...';
      if (substr($sistModulo_permiso,0,2) == 'rw')
      { $vntsSucursalCntc_cdgcontacto = trim($_POST['hidden_cdgcontacto']);

        if ($vntsSucursalCntc_cdgsucursal != '') 
        { if ($vntsSucursalCntc_idcontacto != '')
          { if ($vntsSucursalCntc_contacto != '' OR $vntsSucursalCntc_puesto != '')
            { if ($vntsSucursalCntc_cdgcontacto != '')
              { $link_mysqli = conectar();
                $vntsContactoSelect = $link_mysqli->query("
                  SELECT * FROM vntscontacto
                   WHERE cdgcontacto = '".$vntsSucursalCntc_cdgcontacto."'");
              } else
              { $link_mysqli = conectar();
                $vntsContactoSelect = $link_mysqli->query("
                  SELECT * FROM vntscontacto
                   WHERE cdgsucursal = '".$vntsSucursalCntc_cdgsucursal."' AND
                         idcontacto = '".$vntsSucursalCntc_idcontacto."'"); }
              
              if ($vntsContactoSelect->num_rows == 0)
              { for ($id = 1; $id <= 10; $id++)
                { $vntsSucursalCntc_cdgcontacto = $vntsSucursalCntc_cdgsucursal.str_pad($id,2,'0',STR_PAD_LEFT);

                  if ($id >= 10)
                  { $msg_alert = 'Los codigos disponibles para contactos para este sucursal se han agotado.'; }
                  else
                  { $link_mysqli = conectar();
                    $vntsSucursalSelect = $link_mysqli->query("
                      SELECT *  FROM  vntscontacto
                       WHERE cdgcontacto = '".$vntsSucursalCntc_cdgcontacto."'");

                    if ($vntsSucursalSelect->num_rows == 0)
                    { $link_mysqli = conectar();
                      $link_mysqli->query("
                        INSERT INTO vntscontacto
                          (cdgcliente, idcontacto, contacto, puesto, telefono, movil, email, cdgcontacto)
                        VALUES
                          ('".$vntsSucursalCntc_cdgsucursal."','".$vntsSucursalCntc_idcontacto."','".$vntsSucursalCntc_contacto."','".$vntsSucursalCntc_puesto."','".$vntsSucursalCntc_telefono."','".$vntsSucursalCntc_movil."','".$vntsSucursalCntc_email."','".$vntsSucursalCntc_cdgcontacto."')");

                      if ($link_mysqli->affected_rows > 0)
                      { $msg_alert = 'El contacto fue INSERTADO satisfactoriamente.'; }
                      else
                      { $msg_alert = 'El contacto NO fue insertado'; }

                      break;
                    }
                  }
                }
              } else
              { if ($vntsSucursalCntc_cdgcontacto != '')
                { $link_mysqli = conectar();
                  $link_mysqli->query("
                    UPDATE vntscontacto
                       SET idcontacto = '".$vntsSucursalCntc_idcontacto."',
                           contacto = '".$vntsSucursalCntc_contacto."',
                           puesto = '".$vntsSucursalCntc_puesto."',
                           telefono = '".$vntsSucursalCntc_telefono."',
                           movil = '".$vntsSucursalCntc_movil."',
                           email = '".$vntsSucursalCntc_email."'
                     WHERE cdgcontacto = '".$vntsSucursalCntc_cdgcontacto."' AND
                           sttcontacto = '1'");

                  if ($link_mysqli->affected_rows > 0)
                  { $msg_alert = 'El contacto fue ACTUALIZADO satisfactoriamente. (CR)'; }
                  else
                  { $msg_alert = 'El contacto NO fue actualizado. (CR) <br/> No presento cambios o su status no permite modificaciones'; }
                } else
                { $link_mysqli = conectar();
                  $link_mysqli->query("
                    UPDATE vntscontacto
                       SET contacto = '".$vntsSucursalCntc_contacto."',
                           puesto = '".$vntsSucursalCntc_puesto."',
                           telefono = '".$vntsSucursalCntc_telefono."',
                           movil = '".$vntsSucursalCntc_movil."',
                           email = '".$vntsSucursalCntc_email."'
                     WHERE cdgsucursal = '".$vntsSucursalCntc_cdgsucursal."' AND
                           idcontacto = '".$vntsSucursalCntc_idcontacto."' AND
                           sttcontacto = '1'");

                  if ($link_mysqli->affected_rows > 0)
                  { $msg_alert = 'El contacto fue ACTUALIZADO satisfactoriamente.'; }
                  else
                  { $msg_alert = 'El contacto NO fue actualizado. <br/> No presento cambios o su status no permite modificaciones.'; }
                }
              }

              $vntsSucursalCntc_cdgcontacto = '';
            } else
            { $msg_alert = 'El nombre y/o puesto del contacto no puede estar vac&iacute;o.';
              $textContacto = 'autofocus'; }
          } else
          { $msg_alert = 'La referencia del contacto no puede estar vac&iacute;o.';
            $textIdContacto = 'autofocus'; }
        } else
        { $msg_alert = 'Es necesario indicar a que sucursal pertenece el contacto.'; 
          $slctCdgSucursal = 'autofocus'; }
      } else
      { $msg_alert = 'No cuentas con permisos de escritura en este modulo.'; }
    }

    if ($_GET['cdgcontacto'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $link_mysqli = conectar();
        $vntsContactoSelect = $link_mysqli->query("
          SELECT * FROM vntscontacto
           WHERE cdgcontacto = '".$_GET['cdgcontacto']."'");
          
        if ($vntsContactoSelect->num_rows > 0)
        { $regVntsContacto = $vntsContactoSelect->fetch_object();

          $vntsSucursalCntc_cdgsucursal = $regVntsContacto->cdgcliente;
          $vntsSucursalCntc_idcontacto = $regVntsContacto->idcontacto;
          $vntsSucursalCntc_contacto = $regVntsContacto->contacto;
          $vntsSucursalCntc_puesto = $regVntsContacto->puesto;
          $vntsSucursalCntc_telefono = $regVntsContacto->telefono;
          $vntsSucursalCntc_movil = $regVntsContacto->movil;
          $vntsSucursalCntc_email = $regVntsContacto->email;
          $vntsSucursalCntc_cdgcontacto = $regVntsContacto->cdgcontacto;
          $vntsSucursalCntc_sttcontacto = $regVntsContacto->sttcontacto;

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($vntsSucursalCntc_sttcontacto == '1')
              { $vntsSucursalCntc_newsttcontacto = '0'; }
              
              if ($vntsSucursalCntc_sttcontacto == '0')
              { $vntsSucursalCntc_newsttcontacto = '1'; }
              
              if ($vntsSucursalCntc_newsttcontacto != '')
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE vntscontacto 
                     SET sttcontacto = '".$vntsSucursalCntc_newsttcontacto."'
                   WHERE cdgcontacto = '".$vntsSucursalCntc_cdgcontacto."'");
                  
                if ($link_mysqli->affected_rows > 0)
                { $msg_alert = 'El contacto fue ACTUALIZADO satisfactoriamente.'; }
                else
                { $msg_alert = 'El contacto NO fue ACTUALIZADO.'; }
                
              } else
              { $msg_alert = 'El contacto que seleccionaste, no tiene permitido cambiar de status.'; }
            } else
            { $msg_alert = 'No cuentas con permisos de reescritura en este modulo.'; }
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $link_mysqli = conectar();
              $queryselectdevolucion = $link_mysqli->query("
                SELECT * FROM vntsdev
                 WHERE cdgcontacto = '".$vntsSucursalCntc_cdgcontacto."'");

              if ($queryselectdevolucion->num_rows == 0)
              { $link_mysqli->query("
                  DELETE FROM vntscontacto
                   WHERE cdgcontacto = '".$vntsSucursalCntc_cdgcontacto."' AND
                         sttcontacto = '0'");
                  
                if ($link_mysqli->affected_rows > 0)
                { $msg_alert = 'El contacto fue ELIMINADO satisfactoriamente.'; }
                else
                { $msg_alert = 'El contacto NO fue eliminado.'; }              
              } else
              { $msg_alert = 'EL contacto cuenta con documentos ligados y no puede ser eliminado.'; }
            } else
            { $msg_alert = 'No cuentas con permisos para remover en este modulo.'; }           
          }
        }
      } else
      { $msg_alert = 'No cuentas con permisos de lectura en este modulo.'; }
    }      

    if (substr($sistModulo_permiso,0,1) == 'r')
    { $link_mysqli = conectar();
      $vntsClienteSelect = $link_mysqli->query("
        SELECT * FROM vntscliente
         WHERE sttcliente = '1'
      ORDER BY cliente");

      if ($vntsClienteSelect->num_rows > 0)
      { $id_cliente = 1;

        while ($regVntsCliente = $vntsClienteSelect->fetch_object())
        { $vntsCliente_idcliente[$id_cliente] = $regVntsCliente->idcliente;
          $vntsCliente_cliente[$id_cliente] = $regVntsCliente->cliente;
          $vntsCliente_cdgcliente[$id_cliente] = $regVntsCliente->cdgcliente;

          $link_mysqli = conectar();
          $vntsSucursalSelect = $link_mysqli->query("
            SELECT * FROM vntssucursal
             WHERE cdgcliente = '".$vntsCliente_cdgcliente[$id_cliente]."' AND
                   sttsucursal = '1'
          ORDER BY sucursal");

          if ($vntsSucursalSelect->num_rows > 0)
          { $id_sucursal = 1;

            while ($regVntsSucursal = $vntsSucursalSelect->fetch_object())
            { $vntsSucursal_idsucursal[$id_cliente][$id_sucursal] = $regVntsSucursal->idsucursal;
              $vntsSucursal_sucursal[$id_cliente][$id_sucursal] = $regVntsSucursal->sucursal;
              $vntsSucursal_cdgsucursal[$id_cliente][$id_sucursal] = $regVntsSucursal->cdgsucursal;

              $vntsSucursales_sucursal[$regVntsSucursal->cdgsucursal] = $regVntsSucursal->sucursal;

              $id_sucursal++; }

            $num_sucursales[$id_cliente] = $vntsSucursalSelect->num_rows; }

          $id_cliente++; }

        $num_clientes = $vntsClienteSelect->num_rows; }

      $link_mysqli = conectar();
      $vntsContactoSelect = $link_mysqli->query("
      SELECT * FROM vntscontacto
       WHERE cdgcliente = '".$vntsSucursalCntc_cdgsucursal."' 
    ORDER BY sttcontacto DESC,
             contacto");

      $nContactos = $vntsContactoSelect->num_rows;

      if ($nContactos > 0)
      { while ($regVntsContacto = $vntsContactoSelect->fetch_object())
        { $id_contacto++;

          $vntsContactos_idcontacto[$id_contacto] = $regVntsContacto->idcontacto;
          $vntsContactos_contacto[$id_contacto] = $regVntsContacto->contacto;
          $vntsContactos_puesto[$id_contacto] = $regVntsContacto->puesto;
          $vntsContactos_cdgcontacto[$id_contacto] = $regVntsContacto->cdgcontacto;
          $vntsContactos_sttcontacto[$id_contacto] = $regVntsContacto->sttcontacto; }

      } else
      { $msg_alert = 'No cuentas con contactos para esta Sucursal.'; }
    } else
    { $msg_alert = 'No cuentas con permisos de lectura en este modulo.'; }

    echo '
      <div class="bloque">
        <form id="form_ventas" name="form_ventas" method="POST" action="vntsSucursalCntc.php" />
          <input type="hidden" id="hidden_cdgcontacto" name="hidden_cdgcontacto" value="'.$vntsSucursalCntc_cdgcontacto.'" />
      
          <article class="subbloque">
            <label class="modulo_nombre">Contactos por sucursal</label>
          </article>
          <input type="checkbox" id="chk_vertodo" name="chk_vertodo" onclick="document.formulario.submit()" '.$vertodo.'><label>ver todo</label>
          <a href="ayuda.php#ContactosSucursal">'.$_help_blue.'</a>

          <section class="subbloque">
            <article>
              <a href="vntsSucursal.php?cdgsucursal='.$vntsSucursalCntc_cdgsucursal.'">Sucursal</a><br/>
              <select id="slctCdgSucursal" name="slctCdgSucursal" onchange="document.form_ventas.submit()" required >
                <option value=""> Elije un sucursal </option>';

    for ($item = 1; $item <= $num_clientes; $item++)
    { echo '
                  <optgroup label="'.$vntsCliente_cliente[$item].'">';

      for ($subItem = 1; $subItem <= $num_sucursales[$item]; $subItem++)
      { echo '
                    <option value="'.$vntsSucursal_cdgsucursal[$item][$subItem].'"';

        if ($vntsSucursalCntc_cdgsucursal == $vntsSucursal_cdgsucursal[$item][$subItem])
        { echo ' selected="selected"'; }

          echo '>'.$vntsSucursal_sucursal[$item][$subItem].'</option>'; }

      echo '
                  </optgroup>'; }

    echo '
              </select>
            </article>

            <article>
              <label>Identificador</label><br/>
              <input type="text" id="textIdContacto" name="textIdContacto" value="'.$vntsSucursalCntc_idcontacto.'" required />
            </article>

            <article>
              <label>Nombre</label><br/>
              <input type="text" id="textContacto" name="textContacto" value="'.$vntsSucursalCntc_contacto.'" required />
            </article>

            <article>
              <label>Puesto</label><br/>
              <input type="text" id="textPuesto" name="textPuesto" value="'.$vntsSucursalCntc_puesto.'" required />
            </article>

            <article>
              <label>Teléfono</label><br/>
              <input type="text" id="textTelefono" name="textTelefono" value="'.$vntsSucursalCntc_telefono.'" required />
            </article>

            <article>
              <label>Móvil</label><br/>
              <input type="text" id="textMovil" name="textMovil" value="'.$vntsSucursalCntc_movil.'" required />
            </article>

            <article>
              <label>e-M@il</label><br/>
              <input type="text" id="textMail" name="textMail" value="'.$vntsSucursalCntc_email.'" required />
            </article>

            <article><br/>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>
          </section>
        </form>
      </div>';

    if ($nContactos > 0)
    { echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Catálogo de contactos por sucursal</label>
        </article>
        <label>[<b>'.$nContactos.'</b>] Encontrado(s)</label>';

      for ($item = 1; $item <= $nContactos; $item++)
      { echo '
        <section class="listado">
          <article>';

        if ((int)$vntsContactos_sttcontacto[$item] > 0)
        { echo '
            <a href="vntsSucursalCntc.php?cdgcontacto='.$vntsContactos_cdgcontacto[$item].'">'.$_search.'</a>
            <a href="vntsSucursalCntc.php?cdgcontacto='.$vntsContactos_cdgcontacto[$item].'&proceso=update">'.$_power_blue.'</a>';
        } else
        { echo '
            <a href="vntsSucursalCntc.php?cdgcontacto='.$vntsContactos_cdgcontacto[$item].'&proceso=delete">'.$_recycle_bin.'</a>
            <a href="vntsSucursalCntc.php?cdgcontacto='.$vntsContactos_cdgcontacto[$item].'&proceso=update">'.$_power_black.'</a>'; }
      
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
            <label class="textNombre">'.$vntsContactos_puesto[$item].'</label>
          </article>
        </section>'; }

      echo'
      </div>'; }

    if ($msg_alert != '')
    { echo '
      <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
    <div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
?>

  </body>
</html>