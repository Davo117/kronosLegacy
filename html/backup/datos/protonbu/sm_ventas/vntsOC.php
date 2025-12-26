<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '40200';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    $vntsOC_cdgoc = trim($_POST['hidden_cdgoc']);

    if ($_GET['cdgsucursal'])
    { $vntsOC_cdgsucursal = $_GET['cdgsucursal']; }
    else
    { $vntsOC_cdgsucursal = $_POST['select_cdgsucursal']; }

    if ($_POST['text_oc'])
    { $vntsOC_oc = $_POST['text_oc']; }
    else
    { $text_oc = 'autofocus'; }

    if ($_POST['text_observacion'])
    { $vntsOC_observacion = $_POST['text_observacion']; }
    else
    { $vntsOC_observacion = 'Sin observaciones'; }  

    if ($_POST['date_fchdocumento']) { $vntsOC_fchdocumento = $_POST['date_fchdocumento']; }
    if ($_POST['date_fchrecepcion']) { $vntsOC_fchrecepcion = $_POST['date_fchrecepcion']; }

    $vntsOC_fchdocumento = ValidarFecha($vntsOC_fchdocumento);
    $vntsOC_fchrecepcion = ValidarFecha($vntsOC_fchrecepcion);

    if ($_POST['submit_salvar'])
    { $msg_modulo = 'Salvando ...';

      if (substr($sistModulo_permiso,0,2) == 'rw')
      { if($vntsOC_cdgsucursal != '')
        { if($vntsOC_oc != '')
          { // Verificar si el registro esta cargado
            if ($vntsOC_cdgoc != '')
            { $link_mysqli = conectar();
              $vntsOCSelect = $link_mysqli->query("
                SELECT * FROM vntsoc
                WHERE cdgoc = '".$vntsOC_cdgoc."'"); 
            } else
            { $link_mysqli = conectar();
              $vntsOCSelect = $link_mysqli->query("
                SELECT * FROM vntsoc 
                WHERE cdgsucursal = '".$vntsOC_cdgsucursal."' AND
                  oc = '".$vntsOC_oc."'"); }

            if ($vntsOCSelect->num_rows == 0)
            { $msg_modulo = 'Insertando ...';

              for ($id = 1; $id <= 999; $id++)
              { $vntsOC_cdgoc = date('ymd').str_pad($id,3,'0',STR_PAD_LEFT);

                if ($id >= 1000)
                { $msg_modulo = 'Los codigos disponibles para documentos se han agotado.'; }
                else
                { $link_mysqli = conectar();
                  $vntsOCSelect = $link_mysqli->query("
                    SELECT * FROM vntsoc
                    WHERE cdgoc = '".$vntsOC_cdgoc."'");

                  if ($vntsOCSelect->num_rows > 0)
                  { // Codigo utilizado 
                  } else
                  { $link_mysqli = conectar();
                    $link_mysqli->query("
                      INSERT vntsoc 
                        (cdgsucursal, oc, fchcaptura, fchdocumento, fchrecepcion, observacion, cdgoc)
                      VALUES
                        ('".$vntsOC_cdgsucursal."', '".$vntsOC_oc."', '".date('Y-m-d')."', '".$vntsOC_fchdocumento."', '".$vntsOC_fchrecepcion."', '".$vntsOC_observacion."', '".$vntsOC_cdgoc."')");

                    if ($link_mysqli->affected_rows > 0)
                    { $msg_modulo = 'La orden de compra fue insertada.'; 
                    } else
                    { $msg_modulo = 'La orden de compra NO fue insertada.'; }

                    $id = 1000; }
                }
              }
            } else
            { $msg_modulo = 'Actualizando ...';

              if ($vntsOC_cdgoc != '')
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE vntsoc 
                  SET oc = '".$vntsOC_oc."',
                    fchdocumento = '".$vntsOC_fchdocumento."',
                    fchrecepcion = '".$vntsOC_fchrecepcion."',
                    observacion = '".$vntsOC_observacion."'
                  WHERE cdgoc = '".$vntsOC_cdgoc."'");

                if ($link_mysqli->affected_rows > 0)
                { $msg_modulo = 'La Orden de Compra fue ACTUALIZADA.';
                } else
                { $msg_modulo = 'La Orden de Compra NO fue actualizada.'; }
              } else
              { $msg_modulo = 'La Orden de Compra ya existe, debe ser cargada previamente para ser modificada.'; }
            }

            $vntsOC_cdgoc = '';

          } else
          { $text_oc = 'autofocus'; 
            $msg_modulo = 'Es necesario indicar la referencia de O.C.'; }
        } else
        { $select_cdgsucursal = 'autofocus'; 
          $msg_modulo = 'Es necesario seleccionar una sucursal.'; }
      } else
      { $msg_modulo = 'No cuentas con permisos de escritura en este modulo.'; }
    }

    if ($_GET['cdgoc'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $link_mysqli = conectar();
        $vntsOCSelect = $link_mysqli->query("
          SELECT * FROM vntsoc
          WHERE cdgoc = '".$_GET['cdgoc']."'");

        if ($vntsOCSelect->num_rows >= 1)
        { $msg_modulo = 'Registro encontrado y cargado';

          $regVntsOC = $vntsOCSelect->fetch_object();

          $vntsOC_cdgsucursal = $regVntsOC->cdgsucursal;
          $vntsOC_oc = $regVntsOC->oc;
          $vntsOC_fchdocumento = $regVntsOC->fchdocumento;
          $vntsOC_fchrecepcion = $regVntsOC->fchrecepcion;
          $vntsOC_observacion = $regVntsOC->observacion;
          $vntsOC_cdgoc = $regVntsOC->cdgoc;
          $vntsOC_sttoc = $regVntsOC->sttoc;

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($vntsOC_sttoc == '1')
              { $vntsOC_newsttoc = '0'; }
              
              if ($vntsOC_sttoc == '0')
              { $vntsOC_newsttoc = '1'; }
              
              if ($vntsOC_newsttoc != '')
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE vntsoc
                  SET sttoc = '".$vntsOC_newsttoc."'
                  WHERE cdgoc = '".$vntsOC_cdgoc."'");

                if ($link_mysqli->affected_rows > 0)
                { $msg_modulo = 'La Orden de Compra fue ACTUALIZADA satisfactoriamente.'; }
                else
                { $msg_modulo = 'La Orden de Compra NO fue ACTUALIZADA.'; }
                
              } else
              { $msg_modulo = 'La Orden de Compra que seleccionaste, no tiene permitido cambiar de status.'; }
            } else
            { $msg_modulo = 'No cuentas con permisos de reescritura en este modulo.'; }
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $link_mysqli = conectar();
              $vntsOCSelect = $link_mysqli->query("
                SELECT * FROM vntsoclote
                WHERE cdgoc = '".$vntsOC_cdgoc."'");

              if ($vntsOCSelect->num_rows > 0)
              { $msg_modulo = 'La Orden de Compra NO fue eliminada por que existen registros asociados.'; }
              else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  DELETE FROM vntsoc
                  WHERE cdgoc = '".$vntsOC_cdgoc."' AND
                    sttoc = '0'");

                if ($link_mysqli->affected_rows > 0)
                { $msg_modulo = 'La Orden de Compra fue ELIMINADA satisfactoriamente.'; }
                else
                { $msg_modulo = 'La Orden de Compra NO fue eliminada.'; }
              }
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

      if ($_POST['chk_vertodos'] OR $_GET['vertodo'] == 'checked')
      { $vertodo = 'checked';
        // Filtrado completo
        $link_mysqli = conectar();
        $vntsOCSelect = $link_mysqli->query("
          SELECT * FROM vntsoc
          ORDER BY fchdocumento DESC,
            sttoc DESC,
            oc"); }
      else
      { // Buscar coincidencias
        $link_mysqli = conectar();
        $vntsOCSelect = $link_mysqli->query("
          SELECT * FROM vntsoc
          WHERE sttoc = '1'
          ORDER BY fchdocumento DESC,
            oc"); }

      if ($vntsOCSelect->num_rows > 0)
      { $id_documento = 1;
        while ($regVntsOC = $vntsOCSelect->fetch_object())
        { $vntsOCs_cdgsucursal[$id_documento] = $regVntsOC->cdgsucursal;
          $vntsOCs_oc[$id_documento] = $regVntsOC->oc;
          $vntsOCs_fchdocumento[$id_documento] = $regVntsOC->fchdocumento;
          $vntsOCs_fchrecepcion[$id_documento] = $regVntsOC->fchrecepcion;
          $vntsOCs_cdgoc[$id_documento] = $regVntsOC->cdgoc; 
          $vntsOCs_sttoc[$id_documento] = $regVntsOC->sttoc; 

          $id_documento++; }

        $num_documentos = $vntsOCSelect->num_rows; }

    } else
    { $msg_modulo = 'No cuentas con permisos de lectura en este modulo.'; }

    echo '
      <form id="form_ventas" name="form_ventas" method="POST" action="vntsOC.php" />
        <input type="hidden" id="hidden_cdgoc" name="hidden_cdgoc" value="'.$vntsOC_cdgoc.'" />

        <table align="center">
          <thead>
            <tr><th colspan="3"><label for="label_modulo">'.$sistModulo_modulo.'</label></th></tr>
          </thead>
          <tbody>
            <tr><td colspan="3">
                <a href="vntsSucursal.php?cdgsucursal='.$vntsOC_cdgsucursal.'"><strong>Sucursal</strong></a><br/>
                <select id="select_cdgsucursal" name="select_cdgsucursal" onchange="document.form_ventas.submit()" style="width:410px;" '.$select_cdgsucursal.' required >
                  <option value=""> Elije una sucursal </option>';

    for ($id_cliente = 1; $id_cliente <= $num_clientes; $id_cliente++)
    { echo '
                  <optgroup label="'.$vntsCliente_cliente[$id_cliente].'">';
      
      for ($id_sucursal = 1; $id_sucursal <= $num_sucursales[$id_cliente]; $id_sucursal++)
      { echo '
                    <option value="'.$vntsSucursal_cdgsucursal[$id_cliente][$id_sucursal].'"';

        if ($vntsOC_cdgsucursal == $vntsSucursal_cdgsucursal[$id_cliente][$id_sucursal])
        { echo ' selected="selected"'; }

          echo '>'.$vntsSucursal_sucursal[$id_cliente][$id_sucursal].'</option>'; } 
      
      echo '
                  </optgroup>'; }

    echo '
                </select></td></tr>
              <td><label for="label_oc"><strong>O.C.</strong></label><br/>
                <input type="text" id="text_oc" name="text_oc" value="'.$vntsOC_oc.'" style="width:80px;" '.$text_oc.' required /></td>
              <td><label for="label_fchdocumento"><strong>Fecha documento</strong></label><br/>
                <input type="date" id="date_fchdocumento" name="date_fchdocumento" value="'.$vntsOC_fchdocumento.'" style="width:140px;" '.$date_fchdocumento.' required /><br/>
                <label for="label_formatofchdocumento">AAAA-MM-DD</label></td>
              <td><label for="label_fchrecepcion"><strong>Fecha recepci&oacute;n</strong></label><br/>
                <input type="date" id="date_fchrecepcion" name="date_fchrecepcion" value="'.$vntsOC_fchrecepcion.'" style="width:140px;" '.$date_fchrecepcion.' required /><br/>
                <label for="label_formatofchrecepcion">AAAA-MM-DD</label></td></tr>
            <tr><td colspan="3"><label for="label_observacion"><strong>Obsevaciones</strong></label><br/>
                <textarea id="text_observacion" name="text_observacion" rows="3" style="width:410px;">'.$vntsOC_observacion.'</textarea></td></tr>
          </tbody>
          <tfoot>
            <tr><td colspan="3" align="right"><input type="submit" id="submit_salvar" name="submit_salvar" value="Salvar" /></td></tr>
          </tfoot>
        </table><br/>';

    if ($msg_modulo != '')
    { echo '

      <div align="center"><strong>Aviso</strong><br/>'.$msg_modulo.'</div><br/>'; }

    echo '

        <table align="center">
        <thead>
          <tr><td colspan="2"></td>
            <th colspan="2" align="center">Fechas</th>
            <th colspan="3" align="right">
              <input type="checkbox" name="chk_vertodos" id="chk_vertodos" onclick="document.form_ventas.submit()" '.$vertodo.'>
              <label for="lbl_vertodo">Ver todo</label></th></tr>
          <tr align="left">
            <th><label for="lbl_ttlproyecto">Sucursal</label></th>
            <th><label for="lbl_ttlrefproyecto">O.C.</label></th>
            <th><label for="lbl_ttlfchdocumento">Documento</label></th>
            <th><label for="lbl_ttlfchrecepcion">Recepci&oacute;n</label></th>
            <th colspan="3"><label for="lbl_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';

    if ($num_documentos > 0)
    { for ($id_documento=1; $id_documento<=$num_documentos; $id_documento++)
      { echo '
          <tr align="center">
            <td align="left"><strong>'.$vntsSucursales_sucursal[$vntsOCs_cdgsucursal[$id_documento]].'</strong></td>
            <td align="left">'.$vntsOCs_oc[$id_documento].'</td>
            <td align="left">'.$vntsOCs_fchdocumento[$id_documento].'</td>
            <td align="left">'.$vntsOCs_fchrecepcion[$id_documento].'</td>';

        if ((int)$vntsOCs_sttoc[$id_documento] > 0)
        { echo '
            <td><a href="vntsOC.php?cdgoc='.$vntsOCs_cdgoc[$id_documento].'&vertodo='.$vertodo.'">'.$png_search.'</a></td>
            <td><a href="vntsOClote.php?cdgoc='.$vntsOCs_cdgoc[$id_documento].'&vertodo='.$vertodo.'">'.$png_link.'</a></td>            
            <td><a href="vntsOC.php?cdgoc='.$vntsOCs_cdgoc[$id_documento].'&vertodo='.$vertodo.'&proceso=update">'.$png_power_blue.'</a></td>'; }
        else
         { echo '
            <td><a href="vntsOC.php?cdgoc='.$vntsOCs_cdgoc[$id_documento].'&vertodo='.$vertodo.'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td>&nbsp;</td>
            <td><a href="vntsOC.php?cdgoc='.$vntsOCs_cdgoc[$id_documento].'&vertodo='.$vertodo.'&proceso=update">'.$png_power_black.'</a></td>'; }

        echo '</tr>';
      }      
    }

    echo '
        </tbody>
        <tfoot>
          <tr><th colspan="7" align="right">
              <label for="lbl_ppgdatos">['.$num_documentos.'] Registros encontrados</label></th></tr>
        </tfoot>
      </table>
    </form>'; 

  } else
  { echo '
    <div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }  
  
 ?>

  </body>
</html>