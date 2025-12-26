<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '40111';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    $vntsSucursalCntc_cdgsucursal = $_POST['select_cdgsucursal'];
    $vntsSucursalCntc_idcontacto = trim($_POST['text_idcontacto']);
    $vntsSucursalCntc_contacto = trim($_POST['text_contacto']);
    $vntsSucursalCntc_telefono = trim($_POST['text_telefono']);
    $vntsSucursalCntc_movil = trim($_POST['text_movil']);
    $vntsSucursalCntc_email = trim($_POST['text_email']);

    if ($_GET['cdgsucursal'])
    { $vntsSucursalCntc_cdgsucursal = $_GET['cdgsucursal']; }

    if ($_POST['submit_salvar'])
    { $msg_modulo = 'Salvando ...';
      if (substr($sistModulo_permiso,0,2) == 'rw')
      { $vntsSucursalCntc_cdgcontacto = trim($_POST['hidden_cdgcontacto']);

        if ($vntsSucursalCntc_cdgsucursal != '') 
        { if ($vntsSucursalCntc_idcontacto != '')
          { if ($vntsSucursalCntc_contacto != '')
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
                  { $msg_modulo = 'Los codigos disponibles para contactos para este sucursal se han agotado.'; }
                  else
                  { $link_mysqli = conectar();
                    $vntsSucursalSelect = $link_mysqli->query("
                      SELECT *  FROM  vntscontacto
                      WHERE cdgcontacto = '".$vntsSucursalCntc_cdgcontacto."'");

                    if ($vntsSucursalSelect->num_rows == 0)
                    { $link_mysqli = conectar();
                      $link_mysqli->query("
                        INSERT INTO vntscontacto
                          (cdgcliente, idcontacto, contacto, telefono, movil, email, cdgcontacto)
                        VALUES
                          ('".$vntsSucursalCntc_cdgsucursal."','".$vntsSucursalCntc_idcontacto."','".$vntsSucursalCntc_contacto."','".$vntsSucursalCntc_telefono."','".$vntsSucursalCntc_movil."','".$vntsSucursalCntc_email."','".$vntsSucursalCntc_cdgcontacto."')");

                      if ($link_mysqli->affected_rows > 0)
                      { $msg_modulo = 'El contacto fue INSERTADO satisfactoriamente.'; }
                      else
                      { $msg_modulo = 'El contacto NO fue insertado'; }

                      $id = 10;
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
                      telefono = '".$vntsSucursalCntc_telefono."',
                      movil = '".$vntsSucursalCntc_movil."',
                      email = '".$vntsSucursalCntc_email."'
                    WHERE cdgcontacto = '".$vntsSucursalCntc_cdgcontacto."' AND
                      sttcontacto = '1'");

                  if ($link_mysqli->affected_rows > 0)
                  { $msg_modulo = 'El contacto fue ACTUALIZADO satisfactoriamente. (CR)'; }
                  else
                  { $msg_modulo = 'El contacto NO fue actualizado. (CR) <br/> No presento cambios o su status no permite modificaciones'; }
                } else
                { $link_mysqli = conectar();
                  $link_mysqli->query("
                    UPDATE vntscontacto
                    SET contacto = '".$vntsSucursalCntc_contacto."',
                      telefono = '".$vntsSucursalCntc_telefono."',
                      movil = '".$vntsSucursalCntc_movil."',
                      email = '".$vntsSucursalCntc_email."'
                    WHERE cdgsucursal = '".$vntsSucursalCntc_cdgsucursal."' AND
                      idcontacto = '".$vntsSucursalCntc_idcontacto."' AND
                      sttcontacto = '1'");

                  if ($link_mysqli->affected_rows > 0)
                  { $msg_modulo = 'El contacto fue ACTUALIZADO satisfactoriamente.'; }
                  else
                  { $msg_modulo = 'El contacto NO fue actualizado. <br/> No presento cambios o su status no permite modificaciones.'; }
                }
              }

              $vntsSucursalCntc_cdgcontacto = '';
            } else
            { $msg_modulo = 'El nombre del contacto no puede estar vac&iacute;o.';
              $text_contacto = 'autofocus'; }
          } else
          { $msg_modulo = 'La referencia del contacto no puede estar vac&iacute;o.';
            $text_idcontacto = 'autofocus'; }
        } else
        { $msg_modulo = 'Es necesario indicar a que sucursal pertenece el contacto.'; 
          $select_cdgsucursal = 'autofocus'; }
      } else
      { $msg_modulo = 'No cuentas con permisos de escritura en este modulo.'; }
    }

    if ($_GET['cdgcontacto'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $link_mysqli = conectar();
        $vntsContactoSelect = $link_mysqli->query("
          SELECT * FROM vntscontacto
          WHERE cdgcontacto = '".$_GET['cdgcontacto']."'");
          
        if ($vntsContactoSelect->num_rows > 0)
        { $msg_modulo = 'Registro encontrado y cargado';
          
          $regVntsContacto = $vntsContactoSelect->fetch_object();

          $vntsSucursalCntc_cdgsucursal = $regVntsContacto->cdgcliente;
          $vntsSucursalCntc_idcontacto = $regVntsContacto->idcontacto;
          $vntsSucursalCntc_contacto = $regVntsContacto->contacto;
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
                WHERE cdgcontacto = '".$vntsSucursalCntc_cdgcontacto."' AND
                  sttcontacto = '0'");
                
              if ($link_mysqli->affected_rows > 0)
              { $msg_modulo = 'El contacto fue ELIMINADO satisfactoriamente.'; }
              else
              { $msg_modulo = 'El contacto NO fue eliminado.'; }              
            } else
            { $msg_modulo = 'No cuentas con permisos para remover en este modulo.'; }           
          }
        }
      } else
      { $msg_modulo = 'No cuentas con permisos de lectura en este modulo.'; }
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

      $num_contactos = $vntsContactoSelect->num_rows;

      if ($num_contactos > 0)
      { while ($regVntsContacto = $vntsContactoSelect->fetch_object())
        { $id_contacto++;

          $vntsContactos_idcontacto[$id_contacto] = $regVntsContacto->idcontacto;
          $vntsContactos_contacto[$id_contacto] = $regVntsContacto->contacto;
          $vntsContactos_cdgcontacto[$id_contacto] = $regVntsContacto->cdgcontacto;
          $vntsContactos_sttcontacto[$id_contacto] = $regVntsContacto->sttcontacto; }

      } else
      { $msg_modulo = 'No cuentas con contactos para esta Sucursal.'; }
    } else
    { $msg_modulo = 'No cuentas con permisos de lectura en este modulo.'; }

    echo '
    <form id="form_ventas" name="form_ventas" method="POST" action="vntsSucursalCntc.php" />
      <input type="hidden" id="hidden_cdgcontacto" name="hidden_cdgcontacto" value="'.$vntsSucursalCntc_cdgcontacto.'" />
      
      <table align="center">
        <thead>
          <tr><th colspan="2"><label for="label_modulo">'.$sistModulo_modulo.'</label></th></tr>
        </thead>
        <tbody>
          <tr><td colspan="2"><a href="vntsSucursal.php?cdgsucursal='.$vntsSucursalCntc_cdgsucursal.'"><strong>Sucursal</strong></a><br/>
              <select id="select_cdgsucursal" name="select_cdgsucursal" onchange="document.form_ventas.submit()" style="width:360px;" '.$select_cdgsucursal.' required >
                <option value=""> Elije un sucursal </option>';

    for ($id_cliente = 1; $id_cliente <= $num_clientes; $id_cliente++)
    { echo '
                  <optgroup label="'.$vntsCliente_cliente[$id_cliente].'">';

      for ($id_sucursal = 1; $id_sucursal <= $num_sucursales[$id_cliente]; $id_sucursal++)
      { echo '
                    <option value="'.$vntsSucursal_cdgsucursal[$id_cliente][$id_sucursal].'"';

        if ($vntsSucursalCntc_cdgsucursal == $vntsSucursal_cdgsucursal[$id_cliente][$id_sucursal])
        { echo ' selected="selected"'; }

          echo '>'.$vntsSucursal_sucursal[$id_cliente][$id_sucursal].'</option>'; }

      echo '
                  </optgroup>'; }

    echo '
              </select></td></tr>
          <tr><td><label for="label_contacto"><strong>Nombre</strong></label><br/>
              <input type="text" id="text_contacto" name="text_contacto" value="'.$vntsSucursalCntc_contacto.'" style="width:240px;" '.$text_contacto.' required /></td>
            <td><label for="label_idcontacto"><strong>Referencia</strong></label><br/>
              <input type="text" id="text_idcontacto" name="text_idcontacto" value="'.$vntsSucursalCntc_idcontacto.'" style="width:110px;" '.$text_idcontacto.' required /></td></tr>
          <tr><td><label for="label_telefono"><strong>Tel&eacute;fono</strong></label><br/>
              <input type="text" id="text_telefono" name="text_telefono" value="'.$vntsSucursalCntc_telefono.'" style="width:120px;" '.$text_telefono.' required /></td>
            <td><label for="label_movil"><strong>M&oacute;vil</strong></label><br/>
              <input type="text" id="text_movil" name="text_movil" value="'.$vntsSucursalCntc_movil.'" style="width:110px;" '.$text_movil.' required /></td></tr>
          <tr><td colspan="2"><label for="label_email"><strong>e-M@il</strong></label><br/>
              <input type="text" id="text_email" name="text_email" value="'.$vntsSucursalCntc_email.'" style="width:280px;" '.$text_email.' required /></td></tr>
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
          <tr><th>Nombre</th>
            <th>Referencia</th>
            <th colspan="2">Operaciones</th></tr>
        </thead>
        <tbody>';

      for ($id_contacto = 1; $id_contacto <= $num_contactos; $id_contacto++)
      { echo '
          <tr align="center">
            <td align="left">'.$vntsContactos_contacto[$id_contacto].'</td>
            <td align="left"><strong>'.$vntsContactos_idcontacto[$id_contacto].'</strong></td>';
            
        if ($vntsContactos_sttcontacto[$id_contacto] == '1')
        { echo '
            <td><a href="vntsSucursalCntc.php?cdgcontacto='.$vntsContactos_cdgcontacto[$id_contacto].'">'.$png_search.'</a></td>
            <td><a href="vntsSucursalCntc.php?cdgcontacto='.$vntsContactos_cdgcontacto[$id_contacto].'&proceso=update">'.$png_power_blue.'</a></td></tr>'; }
        
        if ($vntsContactos_sttcontacto[$id_contacto] == '0')
        { echo '
            <td><a href="vntsSucursalCntc.php?cdgcontacto='.$vntsContactos_cdgcontacto[$id_contacto].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td><a href="vntsSucursalCntc.php?cdgcontacto='.$vntsContactos_cdgcontacto[$id_contacto].'&proceso=update">'.$png_power_black.'</a></td></tr>'; }
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