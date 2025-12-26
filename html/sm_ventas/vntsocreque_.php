<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '40201';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    if ($_GET['cdgoc'])
    { $vntsoc_cdgoc =  $_GET['cdgoc']; }
    else      
    { $vntsoc_cdgoc = trim($_POST['hidden_cdgoc']); }

    if ($_GET['cdgreque'])
    { $vntsocreque_cdgreque =  $_GET['cdgreque']; }
    else      
    { if ($_POST['hidden_cdgreque'])
      { $vntsocreque_cdgreque = trim($_POST['hidden_cdgreque']); }
      else
      { $vntsocreque_cdgreque = ''; }
    }

    if ($_POST['select_cdgproducto']) { $vntsocreque_cdgproducto = $_POST['select_cdgproducto']; }

    if ($_POST['text_cantidad'])
    { $vntsocreque_cantidad = number_format($_POST['text_cantidad'],3,'.',''); 
    } else
    { $vntsocreque_cantidad = 0; } 

    if ($_POST['text_referencia']) { $vntsocreque_referencia = $_POST['text_referencia']; }  

    // Generador de combo de productos
    $link = conectar();
    $querySelect = $link->query("
      SELECT idproducto,
             producto,
             cdgproducto
        FROM pdtoproducto
       WHERE sttproducto = '1'
    ORDER BY producto, 
             idproducto");

    if ($querySelect->num_rows > 0)
    { $item = 0;
      while ($regQuery = $querySelect->fetch_object())
      { $item++;

        $pdtoproductos_idproducto[$item] = $regQuery->idproducto; 
        $pdtoproductos_producto[$item] = $regQuery->producto; 
        $pdtoproductos_cdgproducto[$item] = $regQuery->cdgproducto; }

      $nproductos = $querySelect->num_rows; }

    // Botón Salvar
    if ($_POST['submit'])
    { $msg_modulo = 'Salvando ...';

      if (substr($sistModulo_permiso,0,2) == 'rw')
      { if ($vntsoc_cdgoc != '')
        { if ($vntsocreque_cdgreque != '')
          { $link = conectar();
            $link->query("
              UPDATE vntsocreque
              SET cantidad = '".$vntsocreque_cantidad."',
                referencia = '".$vntsocreque_referencia."'
              WHERE cdgreque = '".$vntsocreque_cdgreque."' AND
                sttreque = '1'");

            if ($link->affected_rows > 0)
            { $msg_modulo = 'El requerimiento fue ACTUALIZADO.';
            } else
            { $msg_modulo = 'El requerimiento NO reporto cambios.'; }
          } else
          { $msg_modulo = 'El requerimiento NO fue insertado.';

            for ($id = 1; $id <= 999; $id++)
            { $vntsocreque_cdgreque = $vntsoc_cdgoc.str_pad($id,3,'0',STR_PAD_LEFT);

              $link = conectar();
              $link->query("
                INSERT vntsocreque
                  (cdgoc, idreque, cdgproducto, cantidad, referencia, cdgreque)
                VALUES
                  ('".$vntsoc_cdgoc."', '".$id."', '".$vntsocreque_cdgproducto."', '".$vntsocreque_cantidad."', '".$vntsocreque_referencia."', '".$vntsocreque_cdgreque."')");

              if ($link->affected_rows > 0)
              { $msg_modulo = 'El requerimiento fue insertado.'; 
                break; } 
            } 
          }
        } else
        { $msg_modulo = 'No cuentas una Orden de Compra valida.'; }
      } else
      { $msg_modulo = 'No cuentas con permisos de escritura en este modulo.'; }

      $vntsocreque_cdgreque = '';
    }

    if (substr($sistModulo_permiso,0,1) == 'r')
    { //if ($_GET['cdgoc']) { $vntsoc_cdgoc = $_GET['cdgoc']; 
      if ($_GET['cdgreque']) 
      { $link_mysqli = conectar();
        $vntsocrequeSelect = $link_mysqli->query("
          SELECT * FROM vntsocreque
          WHERE cdgreque = '".$_GET['cdgreque']."'");

        if ($vntsocrequeSelect->num_rows > 0)
        { $msg_modulo = 'Registro encontrado y cargado';

          $regvntsocreque = $vntsocrequeSelect->fetch_object();

          $vntsocreque_cdgoc = $regvntsocreque->cdgoc;
          $vntsocreque_cdgproducto = $regvntsocreque->cdgproducto;
          $vntsocreque_cantidad = number_format($regvntsocreque->cantidad,3,'.','');
          $vntsocreque_referencia = $regvntsocreque->referencia;
          $vntsocreque_cdgreque = $regvntsocreque->cdgreque; 
          $vntsocreque_sttreque = $regvntsocreque->sttreque; 

          //Actualizar status
          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($vntsocreque_sttreque == '1')
              { $vntsocreque_newsttreque = '0'; }
              
              if ($vntsocreque_sttreque == '0')
              { $vntsocreque_newsttreque = '1'; }
              
              if ($vntsocreque_newsttreque != '')
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE vntsocreque
                  SET sttreque = '".$vntsocreque_newsttreque."'
                  WHERE cdgreque = '".$vntsocreque_cdgreque."'");

                if ($link_mysqli->affected_rows > 0)
                { $msg_modulo = 'El requerimiento de la Orden de Compra fue ACTUALIZADO satisfactoriamente.'; }
                else
                { $msg_modulo = 'El requerimiento de la Orden de Compra NO fue ACTUALIZADO.'; }
                
              } else
              { $msg_modulo = 'El requerimiento de la Orden de Compra que seleccionaste, no tiene permitido cambiar de status.'; }
            } else
            { $msg_modulo = 'No cuentas con permisos de reescritura en este modulo.'; }
          }

          //Eliminar registro
          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $link_mysqli = conectar();
              $vntsSurtidoSelect = $link_mysqli->query("
                SELECT vntsoclote.cdgproducto 
                  FROM vntsoclote, 
                       vntsocreque, 
                       pdtoimpresion
                 WHERE vntsocreque.cdgproducto = pdtoimpresion.cdgproducto AND
                       pdtoimpresion.cdgimpresion = vntsoclote.cdgproducto AND
                       vntsoclote.cdgoc = vntsocreque.cdgoc AND
                       vntsocreque.cdgreque = '".$vntsocreque_cdgreque."'");

              if ($vntsSurtidoSelect->num_rows > 0)
              { $msg_modulo = 'El requerimiento de la Orden de Compra NO fue eliminado por que existen confirmaciones asociados.'; }
              else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  DELETE FROM vntsocreque
                   WHERE cdgreque = '".$vntsocreque_cdgreque."' AND
                         sttreque = '0'");

                if ($link_mysqli->affected_rows > 0)
                { $msg_modulo = 'El requerimiento de la Orden de Compra fue ELIMINADO satisfactoriamente.'; }
                else
                { $msg_modulo = 'El requerimiento de la Orden de Compra NO fue eliminado.'; }
              }
            } else
            { $msg_modulo = 'No cuentas con permisos para remover en este modulo.'; }
            
            $vntsocreque_cdgreque = '';            
          }

          $link_mysqli = conectar();
          $vntsocSelect = $link_mysqli->query("
            SELECT * FROM vntsoc
            WHERE cdgoc = '".$vntsocreque_cdgoc."'");

          if ($vntsocSelect->num_rows > 0)
          { $regvntsoc = $vntsocSelect->fetch_object();

            $vntsoc_cdgsucursal = $regvntsoc->cdgsucursal;
            $vntsoc_oc = $regvntsoc->oc;
            $vntsoc_fchdocumento = $regvntsoc->fchdocumento;
            $vntsoc_fchrecepcion = $regvntsoc->fchrecepcion;
            $vntsoc_fchcaptura = $regvntsoc->fchcaptura;
            $vntsoc_observacion = $regvntsoc->observacion;
            $vntsoc_cdgoc = $regvntsoc->cdgoc;
            $vntsoc_sttoc = $regvntsoc->sttoc; 

            $vntsSucursalSelect = $link_mysqli->query("
              SELECT vntscliente.idcliente,
                vntscliente.cliente,
                vntssucursal .idsucursal,
                vntssucursal .sucursal,
         CONCAT(vntssucursal.domicilio,', ',vntssucursal.colonia) AS domicilio,
                vntssucursal.cdgpostal,
                vntssucursal.telefono,
         CONCAT(mapaciudad.ciudad,', ',mapaestado.idestado) AS ciudad
              FROM vntscliente,
                vntssucursal,
                mapaestado,
                mapaciudad
              WHERE vntscliente.cdgcliente = vntssucursal.cdgcliente AND
                vntssucursal.cdgsucursal = '".$vntsoc_cdgsucursal."' AND
                vntssucursal.cdgciudad = mapaciudad.cdgciudad AND
                mapaciudad.cdgestado = mapaestado.cdgestado");

            if ($vntsSucursalSelect->num_rows > 0)
            { $regVntsSucursal = $vntsSucursalSelect->fetch_object();

              $vntsSucursal_idcliente = $regVntsSucursal->idcliente;
              $vntsSucursal_cliente = $regVntsSucursal->cliente;
              $vntsSucursal_idsucursal = $regVntsSucursal->idsucursal;
              $vntsSucursal_sucursal = $regVntsSucursal->sucursal;
              $vntsSucursal_domicilio = $regVntsSucursal->domicilio;
              $vntsSucursal_cdgpostal = $regVntsSucursal->cdgpostal;
              $vntsSucursal_telefono = $regVntsSucursal->telefono;
              $vntsSucursal_ciudad = $regVntsSucursal->ciudad; }
          }
        }                
      } else
      { if ($vntsoc_cdgoc != '')
        { $link_mysqli = conectar();
          $vntsocSelect = $link_mysqli->query("
            SELECT * FROM vntsoc
            WHERE cdgoc = '".$vntsoc_cdgoc."'");

          if ($vntsocSelect->num_rows > 0)
          { $regvntsoc = $vntsocSelect->fetch_object();

            $vntsoc_cdgsucursal = $regvntsoc->cdgsucursal;
            $vntsoc_oc = $regvntsoc->oc;
            $vntsoc_fchdocumento = $regvntsoc->fchdocumento;
            $vntsoc_fchrecepcion = $regvntsoc->fchrecepcion;
            $vntsoc_fchcaptura = $regvntsoc->fchcaptura;
            $vntsoc_observacion = $regvntsoc->observacion;          
            $vntsoc_sttoc = $regvntsoc->sttoc; 

            $vntsSucursalSelect = $link_mysqli->query("
              SELECT vntscliente.idcliente,
                vntscliente.cliente,
                vntssucursal .idsucursal,
                vntssucursal .sucursal,
         CONCAT(vntssucursal.domicilio,', ',vntssucursal.colonia) AS domicilio,
                vntssucursal.cdgpostal,
                vntssucursal.telefono,
         CONCAT(mapaciudad.ciudad,', ',mapaestado.idestado) AS ciudad
              FROM vntscliente,
                vntssucursal,
                mapaestado,
                mapaciudad
              WHERE vntscliente.cdgcliente = vntssucursal.cdgcliente AND
                vntssucursal.cdgsucursal = '".$vntsoc_cdgsucursal."' AND
                vntssucursal.cdgciudad = mapaciudad.cdgciudad AND
                mapaciudad.cdgestado = mapaestado.cdgestado");

            if ($vntsSucursalSelect->num_rows > 0)
            { $regVntsSucursal = $vntsSucursalSelect->fetch_object();

              $vntsSucursal_idcliente = $regVntsSucursal->idcliente;
              $vntsSucursal_cliente = $regVntsSucursal->cliente;
              $vntsSucursal_idsucursal = $regVntsSucursal->idsucursal;
              $vntsSucursal_sucursal = $regVntsSucursal->sucursal;
              $vntsSucursal_domicilio = $regVntsSucursal->domicilio;
              $vntsSucursal_cdgpostal = $regVntsSucursal->cdgpostal;
              $vntsSucursal_telefono = $regVntsSucursal->telefono;
              $vntsSucursal_ciudad = $regVntsSucursal->ciudad; }
          }
        } 
      }

      $link_mysqli = conectar();
      $vntsocrequeSelect = $link_mysqli->query("
        SELECT pdtoproducto.cdgproducto,
               pdtoproducto.producto,
               vntsocreque.cantidad,
               vntsocreque.referencia,
               vntsocreque.cdgreque,
               vntsocreque.sttreque
          FROM pdtoproducto,
               vntsocreque
         WHERE pdtoproducto.cdgproducto = vntsocreque.cdgproducto AND
               vntsocreque.cdgoc = '".$vntsoc_cdgoc."'"); 

      if ($vntsocrequeSelect->num_rows > 0)
      { $item = 0;
        while ($regvntsocreque = $vntsocrequeSelect->fetch_object())
        { $item++;

          $vntsocreques_producto[$item] = $regvntsocreque->producto;
          $vntsocreques_cdgproducto[$item] = $regvntsocreque->cdgproducto;
          $vntsocreques_empaque[$item] = $regvntsocreque->empaque;
          $vntsocreques_cantidad[$item] = $regvntsocreque->cantidad;
          $vntsocreques_referencia[$item] = $regvntsocreque->referencia;
          $vntsocreques_cdgreque[$item] = $regvntsocreque->cdgreque;
          $vntsocreques_sttreque[$item] = $regvntsocreque->sttreque; 

          $link = conectar();
          $querySelectConfirmado = $link->query("
            SELECT pdtoproducto.cdgproducto,
               SUM(vntsoclote.cantidad) AS confirmado
              FROM pdtoproducto,
                   vntsocreque,
                   vntsoclote,
                   pdtoimpresion
             WHERE pdtoproducto.cdgproducto = vntsocreque.cdgproducto AND
                   vntsocreque.cdgoc = vntsoclote.cdgoc AND
                   vntsoclote.cdgoc = '".$vntsoc_cdgoc."' AND
                   vntsocreque.cdgproducto = pdtoimpresion.cdgproducto AND
                   pdtoimpresion.cdgimpresion = vntsoclote.cdgproducto AND
                   pdtoproducto.cdgproducto = '".$vntsocreques_cdgproducto[$item]."'
          GROUP BY vntsocreque.cdgproducto"); 

          if ($querySelectConfirmado->num_rows > 0)
          { $regQueryConfirmado = $querySelectConfirmado->fetch_object();

            $vntsocreques_confirmado[$item] = $regQueryConfirmado->confirmado; }
        }

        $num_reques = $vntsocrequeSelect->num_rows; }

    } else
    { $msg_modulo = 'No cuentas con permisos de lectura en este modulo.'; }

    echo '
      <form id="form_ventas" name="form_ventas" method="POST" action="vntsocreque.php" />
        <input type="hidden" id="hidden_cdgoc" name="hidden_cdgoc" value="'.$vntsoc_cdgoc.'" />
        <input type="hidden" id="hidden_cdgreque" name="hidden_cdgreque" value="'.$vntsocreque_cdgreque.'" />

        <table align="center">
          <thead>
            <tr><th colspan="4"><label for="label_modulo">Requerimientos</label></th></tr>
          </thead>
          <tbody>            
            <tr align="center"><td><label for="label_fchdocumento">Fecha documento<br/>
                  <strong>'.$vntsoc_fchdocumento.' </strong></label></td>
              <td><label for="label_fchrecepcion">Fecha recepci&oacute;n<br/>
                  <strong>'.$vntsoc_fchrecepcion.' </strong></label></td>
              <td><label for="label_fchcaptura">Fecha captura<br/>
                  <strong>'.$vntsoc_fchcaptura.' </strong></label></td>
              <th colspan="3" align="right"><label for="label_ttloc">O.C. </label>
                <label for="label_oc"><a href="vntsOC.php?cdgoc='.$vntsoc_cdgoc.'" style="color:red;"><b>'.$vntsoc_oc.'</b></a></label><br>
                <label for="label_oclote"><a href="vntsOClote.php?cdgoc='.$vntsoc_cdgoc.'" style="color:green;"><b>Confirmaciones</b></a></label></strong></th></tr>
            <tr><td colspan="4"><label for="label_cliente">Cliente<br/>
                  <strong>'.$vntsSucursal_cliente.' </strong> ('.$vntsSucursal_idcliente.')</label><br/><br/>
                <label for="label_sucursal">Sucursal<br/>
                  <strong>'.$vntsSucursal_sucursal.' </strong> ('.$vntsSucursal_idsucursal.')</label><br/><br/>
                <label for="label_domicilio">Domicilio<br/>
                  <strong>'.$vntsSucursal_domicilio.'</strong> C.P. <strong>'.$vntsSucursal_cdgpostal.'</strong><br/>
                  <strong>'.$vntsSucursal_ciudad.'</strong> Tel. <strong>'.$vntsSucursal_telefono.'</strong></label></td></tr>
          </tbody>
        </table><br/>

        <table align="center">
          <thead>
            <tr><th colspan="3"><label for="lbl_submodulo">Cargador de productos</label></th></tr>
          </thead>
          <tbody>
            <tr><td><label for="lbl_cdgproducto"><strong><a href="../sm_producto/pdtoProducto.php?cdgproducto='.$vntsEmbarque_cdgproducto.'">Producto</a></strong></label><br/>
                <select style="width:140px" id="select_cdgproducto" name="select_cdgproducto" onchange="document.form_ventas.submit()">
                  <option value="">Selecciona una opcion</option>';

    for ($item = 1; $item <= $nproductos; $item++) 
    { echo '
                  <option value="'.$pdtoproductos_cdgproducto[$item].'"';
          
      if ($vntsocreque_cdgproducto == $pdtoproductos_cdgproducto[$item]) 
      { echo ' selected="selected"'; }

      echo '>'.$pdtoproductos_idproducto[$item].' '.$pdtoproductos_producto[$item].'</option>'; }
    
    echo '
                </select></td>
              <td><label for="label_cantidad"><strong>Cantidad</strong></label><br/>
                <input type="text" id="text_cantidad" name="text_cantidad" value="'.$vntsocreque_cantidad.'" style="width:70px;  text-align:right;" required /></td>
              <td><label for="label_referencia"><strong>Referencia</strong></label><br/>
                <input type="text" id="text_referencia" name="text_referencia" value="'.$vntsocreque_referencia.'" style="width:120px;" /></td></tr>
          </tbody>
          <tfoot>
            <tr><td colspan="3" align="right"><input type="submit" id="submit" name="submit" value="Salvar" /></td></tr>
          </tfoot>
        </table><br/>';

    if ($msg_modulo != '')
    { echo '

      <div align="center"><strong>Aviso</strong><br/>'.$msg_modulo.'</div><br/>'; }

    echo '
        <table align="center">
        <thead>
          <tr align="left">
            <th></th>
            <th><label for="labelttlproducto">Producto</label></th>
            <th><label for="labelttlrequerido">Requerido</label></th>
            <th><label for="labelttlconfirmado">Confirmado</label></th>
            <th><label for="labelttlreferencia">Referencia</label></th>
            <th colspan="2"><label for="labelttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';

    if ($num_reques > 0)
    { for ($item=1; $item<=$num_reques; $item++)
      { echo '
          <tr align="center">
            <td><strong>'.$item.'</strong></td>
            <td align="left"><strong>'.$vntsocreques_producto[$item].'</strong></td>
            <td align="right">'.$vntsocreques_cantidad[$item].'</td>
            <td align="right">'.$vntsocreques_confirmado[$item].'</td>
            <td align="left">'.$vntsocreques_referencia[$item].'</td>';

        if ((int)$vntsocreques_sttreque[$item] > 0)
        { echo '
            <td><a href="vntsocreque.php?cdgreque='.$vntsocreques_cdgreque[$item].'">'.$png_search.'</a></td>
            <td><a href="vntsocreque.php?cdgreque='.$vntsocreques_cdgreque[$item].'&proceso=update">'.$png_power_blue.'</a></td>'; }
        else
         { echo '
            <td><a href="vntsocreque.php?cdgreque='.$vntsocreques_cdgreque[$item].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td><a href="vntsocreque.php?cdgreque='.$vntsocreques_cdgreque[$item].'&proceso=update">'.$png_power_black.'</a></td>'; }

        echo '</tr>';
      }      
    }

    echo '
        </tbody>
        <tfoot>
          <tr><td></td>
            <th colspan="6" align="right">
              <label for="lbl_ppgdatos">['.$num_reques.'] Registros encontrados</label></th></tr>
        </tfoot>
      </table>
    </form>'; 

  } else
  { echo '
    <div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }  
  
 ?>

  </body>
</html>
