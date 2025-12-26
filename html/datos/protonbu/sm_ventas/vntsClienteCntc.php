<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '40101';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    $vntsClienteCntc_cdgcliente = $_POST['select_cdgcliente'];
    $vntsClienteCntc_idcontacto = trim($_POST['text_idcontacto']);
    $vntsClienteCntc_contacto = trim($_POST['text_contacto']);
    $vntsClienteCntc_telefono = trim($_POST['text_telefono']);
    $vntsClienteCntc_movil = trim($_POST['text_movil']);
    $vntsClienteCntc_email = trim($_POST['text_email']);

    if ($_GET['cdgcliente'])
    { $vntsClienteCntc_cdgcliente = $_GET['cdgcliente']; }

    if ($_POST['submit_salvar'])
    { $msg_modulo = 'Salvando ...';
      if (substr($sistModulo_permiso,0,2) == 'rw')
      { $vntsClienteCntc_cdgcontacto = trim($_POST['hidden_cdgcontacto']);

        if ($vntsClienteCntc_cdgcliente != '') 
        { if ($vntsClienteCntc_idcontacto != '')
          { if ($vntsClienteCntc_contacto != '')
            { if ($vntsClienteCntc_cdgcontacto != '')
              { $link_mysqli = conectar();
                $vntsContactoSelect = $link_mysqli->query("
                  SELECT * FROM vntscontacto
                  WHERE cdgcontacto = '".$vntsClienteCntc_cdgcontacto."'");
              } else
              { $link_mysqli = conectar();
                $vntsContactoSelect = $link_mysqli->query("
                  SELECT * FROM vntscontacto
                  WHERE cdgcliente = '".$vntsClienteCntc_cdgcliente."' AND
                    idcontacto = '".$vntsClienteCntc_idcontacto."'"); }

              if ($vntsContactoSelect->num_rows == 0)
              { for ($id = 1; $id <= 10; $id++)
                { $vntsClienteCntc_cdgcontacto = $vntsClienteCntc_cdgcliente.str_pad($id,6,'0',STR_PAD_LEFT);

                  if ($id >= 10)
                  { $msg_modulo = 'Los codigos disponibles para contactos para este cliente se han agotado.'; }
                  else
                  { $link_mysqli = conectar();
                    $vntsSucursalSelect = $link_mysqli->query("
                      SELECT *  FROM  vntscontacto
                      WHERE cdgcontacto = '".$vntsClienteCntc_cdgcontacto."'");

                    if ($vntsSucursalSelect->num_rows == 0)
                    { $link_mysqli = conectar();
                      $link_mysqli->query("
                        INSERT INTO vntscontacto
                          (cdgcliente, idcontacto, contacto, telefono, movil, email, cdgcontacto)
                        VALUES
                          ('".$vntsClienteCntc_cdgcliente."','".$vntsClienteCntc_idcontacto."','".$vntsClienteCntc_contacto."','".$vntsClienteCntc_telefono."','".$vntsClienteCntc_movil."','".$vntsClienteCntc_email."','".$vntsClienteCntc_cdgcontacto."')");

                      if ($link_mysqli->affected_rows > 0)
                      { $msg_modulo = 'El contacto fue INSERTADO satisfactoriamente.'; }
                      else
                      { $msg_modulo = 'El contacto NO fue insertado'; }

                      $id = 10;

                      $vntsClienteCntc_cdgcontacto = '';
                    }
                  }
                }
              } else
              { if ($vntsClienteCntc_cdgcontacto != '')
                { $link_mysqli = conectar();
                  $link_mysqli->query("
                    UPDATE vntscontacto
                    SET idcontacto = '".$vntsClienteCntc_idcontacto."',
                      contacto = '".$vntsClienteCntc_contacto."',
                      telefono = '".$vntsClienteCntc_telefono."',
                      movil = '".$vntsClienteCntc_movil."',
                      email = '".$vntsClienteCntc_email."'
                    WHERE cdgcontacto = '".$vntsClienteCntc_cdgcontacto."' AND
                      sttcontacto = '1'");

                  if ($link_mysqli->affected_rows > 0)
                  { $msg_modulo = 'El contacto fue ACTUALIZADO satisfactoriamente. (CR)'; }
                  else
                  { $msg_modulo = 'El contacto NO fue actualizado. (CR) <br/> No presento cambios o su status no permite modificaciones'; }
                } else
                { $link_mysqli = conectar();
                  $link_mysqli->query("
                    UPDATE vntscontacto
                    SET contacto = '".$vntsClienteCntc_contacto."',
                      telefono = '".$vntsClienteCntc_telefono."',
                      movil = '".$vntsClienteCntc_movil."',
                      email = '".$vntsClienteCntc_email."'
                    WHERE cdgcliente = '".$vntsClienteCntc_cdgcliente."' AND
                      idcontacto = '".$vntsClienteCntc_idcontacto."' AND
                      sttcontacto = '1'");

                  if ($link_mysqli->affected_rows > 0)
                  { $msg_modulo = 'El contacto fue ACTUALIZADO satisfactoriamente.'; }
                  else
                  { $msg_modulo = 'El contacto NO fue actualizado. <br/> No presento cambios o su status no permite modificaciones.'; }
                }
              }
            } else
            { $msg_modulo = 'El nombre del contacto no puede estar vac&iacute;o.';
              $text_contacto = 'autofocus'; }
          } else
          { $msg_modulo = 'La referencia del contacto no puede estar vac&iacute;o.';
            $text_idcontacto = 'autofocus'; }
        } else
        { $msg_modulo = 'Es necesario indicar a que cliente pertenece el contacto.';
          $select_cdgcliente = 'autofocus'; }
      } else
      { $msg_modulo = 'No cuentas con permisos de escritura en este modulo.'; }
    }

    if (substr($sistModulo_permiso,0,1) == 'r')
    { if ($_GET['cdgcontacto'])
      { $link_mysqli = conectar();
        $vntsContactoSelect = $link_mysqli->query("
          SELECT * FROM vntscontacto
          WHERE cdgcontacto = '".$_GET['cdgcontacto']."'");

        if ($vntsContactoSelect->num_rows > 0)
        { $msg_modulo = 'Registro encontrado';

          $regVntsContacto = $vntsContactoSelect->fetch_object();

          $vntsClienteCntc_cdgcliente = $regVntsContacto->cdgcliente;
          $vntsClienteCntc_idcontacto = $regVntsContacto->idcontacto;
          $vntsClienteCntc_contacto = $regVntsContacto->contacto;
          $vntsClienteCntc_telefono = $regVntsContacto->telefono;
          $vntsClienteCntc_movil = $regVntsContacto->movil;
          $vntsClienteCntc_email = $regVntsContacto->email;
          $vntsClienteCntc_cdgcontacto = $regVntsContacto->cdgcontacto;
          $vntsClienteCntc_sttcontacto = $regVntsContacto->sttcontacto;

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($vntsClienteCntc_sttcontacto == '1')
              { $vntsClienteCntc_newsttcontacto = '0'; }
              
              if ($vntsClienteCntc_sttcontacto == '0')
              { $vntsClienteCntc_newsttcontacto = '1'; }

              if ($vntsClienteCntc_newsttcontacto != '')
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE vntscontacto 
                  SET sttcontacto = '".$vntsClienteCntc_newsttcontacto."'
                  WHERE cdgcontacto = '".$vntsClienteCntc_cdgcontacto."'");

                if ($link_mysqli->affected_rows > 0)
                { $msg_modulo = 'El contacto fue ACTUALIZADO satisfactoriamente.'; }
                else
                { $msg_modulo = 'El contacto NO fue ACTUALIZADO.'; }

              } else
              { $msg_modulo = 'El contacto que seleccionaste, no tiene permitido cambiar de status.'; }
            } else
            { $msg_modulo = 'No cuentas con permisos de reescritura en este modulo.'; }
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $link_mysqli = conectar();
              $link_mysqli->query("
                DELETE FROM vntscontacto
                WHERE cdgcontacto = '".$vntsClienteCntc_cdgcontacto."' AND
                  sttcontacto = '0'");

              if ($link_mysqli->affected_rows > 0)
              { $msg_modulo = 'El contacto fue ELIMINADO satisfactoriamente.'; }
              else
              { $msg_modulo = 'El contacto NO fue eliminado.'; }
            } else
            { $msg_modulo = 'No cuentas con permisos para remover en este modulo.'; }
          }
        }
      }

      $link_mysqli = conectar();
      $vntsClienteSelect = $link_mysqli->query("
      SELECT * FROM vntscliente
      WHERE sttcliente = '1'
      ORDER BY sttcliente DESC,
        cliente");

      $num_clientes = $vntsClienteSelect->num_rows;

      if ($num_clientes > 0)
      { while ($regVntsCliente = $vntsClienteSelect->fetch_object())
        { $id_cliente++;

          $vntsClientes_idcliente[$id_cliente] = $regVntsCliente->idcliente;
          $vntsClientes_cliente[$id_cliente] = $regVntsCliente->cliente;
          $vntsClientes_cdgcliente[$id_cliente] = $regVntsCliente->cdgcliente;
          $vntsClientes_sttcliente[$id_cliente] = $regVntsCliente->sttcliente; }
          
      } else
      { $msg_modulo = 'No cuentas con clientes para agregar contactos.'; }

      $link_mysqli = conectar();
      $vntsContactoSelect = $link_mysqli->query("
      SELECT * FROM vntscontacto
      WHERE cdgcliente = '".$vntsClienteCntc_cdgcliente."'
      ORDER BY sttcontacto DESC,
        contacto");

      $num_contactos = $vntsContactoSelect->num_rows;

      if ($num_contactos > 0)
      { while ($regVntsContacto = $vntsContactoSelect->fetch_object())
        { $id_contacto++;

          $vntsContactos_idcontacto[$id_contacto] = $regVntsContacto->idcontacto;
          $vntsContactos_contacto[$id_contacto] = $regVntsContacto->contacto;
          $vntsContactos_cdgcontacto[$id_contacto] = $regVntsContacto->cdgcontacto;
          $vntsContactos_sttcontacto[$id_contacto] = $regVntsContacto->sttcontacto; }

      } else
 	    { $msg_modulo = 'No cuentas con contactos para este Cliente.'; }
    } else
    { $msg_modulo = 'No cuentas con permisos de lectura en este modulo.'; }

    echo '
    <form id="form_ventas" name="form_ventas" method="POST" action="vntsClienteCntc.php" />
      <input type="hidden" id="hidden_cdgcontacto" name="hidden_cdgcontacto" value="'.$vntsClienteCntc_cdgcontacto.'" />

      <table align="center">
        <thead>
          <tr><th colspan="2"><label for="label_modulo">'.$sistModulo_modulo.'</label></th></tr>
        </thead>
        <tbody>
          <tr><td colspan="2"><a href="vntsCliente.php?cdgcliente='.$vntsClienteCntc_cdgcliente.'"><strong>Cliente</strong></a><br/>
              <select id="select_cdgcliente" name="select_cdgcliente" onchange="document.form_ventas.submit()" style="width:360px;" '.$select_cdgcliente.' required >
                <option value=""> Elije un cliente </option>';

    for ($id_cliente = 1; $id_cliente <= $num_clientes; $id_cliente++)
    { echo '
                <option value="'.$vntsClientes_cdgcliente[$id_cliente].'"';

      if ($vntsClienteCntc_cdgcliente == $vntsClientes_cdgcliente[$id_cliente])
      { echo ' selected="selected"'; }

      echo '>'.$vntsClientes_cliente[$id_cliente].' ('.$vntsClientes_idcliente[$id_cliente].')</option>'; }

    echo '
              </select></td></tr>
          <tr><td><label for="label_contacto"><strong>Nombre</strong></label><br/>
              <input type="text" id="text_contacto" name="text_contacto" value="'.$vntsClienteCntc_contacto.'" style="width:240px;" '.$text_contacto.' required /></td>
            <td><label for="label_idcontacto"><strong>Referencia</strong></label><br/>
              <input type="text" id="text_idcontacto" name="text_idcontacto" value="'.$vntsClienteCntc_idcontacto.'" style="width:110px;" '.$text_idcontacto.' required /></td></tr>
          <tr><td><label for="label_telefono"><strong>Tel&eacute;fono</strong></label><br/>
              <input type="text" id="text_telefono" name="text_telefono" value="'.$vntsClienteCntc_telefono.'" style="width:120px;" '.$text_telefono.' required /></td>
            <td><label for="label_movil"><strong>M&oacute;vil</strong></label><br/>
              <input type="text" id="text_movil" name="text_movil" value="'.$vntsClienteCntc_movil.'" style="width:110px;" '.$text_movil.' required /></td></tr>
          <tr><td colspan="2"><label for="label_email"><strong>e-m@il</strong></label><br/>
              <input type="text" id="text_email" name="text_email" value="'.$vntsClienteCntc_email.'" style="width:280px;" '.$text_email.' required /></td></tr>
        </tbody>
        <tfoot>
          <tr><td colspan="2" align="right"><input type="submit" id="submit_salvar" name="submit_salvar" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>';

    if ($msg_modulo != '')
    { echo '

      <div align="center"><strong>Aviso</strong><br/>'.$msg_modulo.'</div><br/>'; }

    if ($num_contactos > 0)
    { echo '

      <table align="center">
        <thead>
          <tr><th>Referencia</th>
            <th>Nombre</th>
            <th colspan="2">Operaciones</th></tr>
        </thead>
        <tbody>';

      for ($id_contacto = 1; $id_contacto <= $num_contactos; $id_contacto++)
      { echo '
          <tr align="center">
            <td align="left">'.$vntsContactos_idcontacto[$id_contacto].'</td>
            <td align="left">'.$vntsContactos_contacto[$id_contacto].'</td>';
            
        if ($vntsContactos_sttcontacto[$id_contacto] == '1')
        { echo '
            <td><a href="vntsClienteCntc.php?cdgcontacto='.$vntsContactos_cdgcontacto[$id_contacto].'">'.$png_search.'</a></td>
            <td><a href="vntsClienteCntc.php?cdgcontacto='.$vntsContactos_cdgcontacto[$id_contacto].'&proceso=update">'.$png_power_blue.'</a></td></tr>'; }
        
        if ($vntsContactos_sttcontacto[$id_contacto] == '0')
        { echo '
            <td><a href="vntsClienteCntc.php?cdgcontacto='.$vntsContactos_cdgcontacto[$id_contacto].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td><a href="vntsClienteCntc.php?cdgcontacto='.$vntsContactos_cdgcontacto[$id_contacto].'&proceso=update">'.$png_power_black.'</a></td></tr>'; }
      }

      echo'
        </tbody>
        <tfoot>
          <tr><td colspan="4" align="right">['.$num_contactos.'] registros encontrados</td></tr>
        </tfoot>
      </table>'; }

    echo '
    </form>';

  } else
  { echo '
    <div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
?>

  </body>
</html>