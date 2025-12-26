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

    if ($_GET['cdglote'])
    { $vntsoclote_cdglote =  $_GET['cdglote']; }
    else      
    { if ($_POST['hidden_cdglote'])
      { $vntsoclote_cdglote = trim($_POST['hidden_cdglote']); }
      else
      { $vntsoclote_cdglote = ''; }
    }

    if ($_POST['select_cdgproducto']) { $vntsoclote_cdgproducto = $_POST['select_cdgproducto']; }

    if ($_POST['text_cantidad'])
    { $vntsoclote_cantidad = number_format($_POST['text_cantidad'],3,'.',''); 
    } else
    { $vntsoclote_cantidad = 0; } 

    if ($_POST['date_fchembarque'])
    { $vntsoclote_fchembarque = $_POST['date_fchembarque']; 
    } else
    { $vntsoclote_fchembarque = date('Y-m-d'); }

    if ($_POST['date_fchentrega']) 
    { $vntsoclote_fchentrega = $_POST['date_fchentrega']; 
    } else
    { $vntsoclote_fchentrega = date('Y-m-d'); }

    $vntsoc_fchembarque = ValidarFecha($vntsoc_fchembarque);
    $vntsoc_fchentrega = ValidarFecha($vntsoc_fchentrega);   

    if ($_POST['select_cdgempaque']) { $vntsoclote_cdgempaque = $_POST['select_cdgempaque']; }

    if ($_POST['text_referencia']) { $vntsoclote_referencia = $_POST['text_referencia']; }  

    // Generador de combo de productos
    $link_mysqli = conectar(); 
    $pdtoDisenoSelect = $link_mysqli->query("
      SELECT * FROM pdtodiseno
      WHERE sttdiseno = '1'
      ORDER BY diseno,
        iddiseno");
    
    $idDiseno = 1;
    while ($regPdtoDiseno = $pdtoDisenoSelect->fetch_object()) 
    { $pdtoDiseno_iddiseno[$idDiseno] = $regPdtoDiseno->iddiseno;
      $pdtoDiseno_diseno[$idDiseno] = $regPdtoDiseno->diseno;
      $pdtoDiseno_cdgdiseno[$idDiseno] = $regPdtoDiseno->cdgdiseno; 
      
      $link_mysqli = conectar(); 
      $pdtoImpresionSelect = $link_mysqli->query("
        SELECT * FROM pdtoimpresion
        WHERE cdgdiseno = '".$regPdtoDiseno->cdgdiseno."' AND 
          sttimpresion = '1'
        ORDER BY impresion,
          idimpresion");
      
      $id_impresion = 1;
      while ($regPdtoImpresion = $pdtoImpresionSelect->fetch_object()) 
      { $pdtoImpresion_idimpresion[$idDiseno][$id_impresion] = $regPdtoImpresion->idimpresion;
        $pdtoImpresion_impresion[$idDiseno][$id_impresion] = $regPdtoImpresion->impresion;
        $pdtoImpresion_ancho[$idDiseno][$id_impresion] = $regPdtoImpresion->ancho;
        $pdtoImpresion_alpaso[$idDiseno][$id_impresion] = $regPdtoImpresion->alpaso;
        $pdtoImpresion_ceja[$idDiseno][$id_impresion] = $regPdtoImpresion->ceja;
        $pdtoImpresion_tolerancia[$idDiseno][$id_impresion] = $regPdtoImpresion->tolerancia;      
        $pdtoImpresion_corte[$idDiseno][$id_impresion] = $regPdtoImpresion->corte;
        $pdtoImpresion_cdgimpresion[$idDiseno][$id_impresion] = $regPdtoImpresion->cdgimpresion;  

        $id_impresion++; }

      $numimpresiones[$idDiseno] = $pdtoImpresionSelect->num_rows;         

      $idDiseno++; } 

    $numDisenos = $pdtoDisenoSelect->num_rows;

    // Generador de combo de presentación
    $link_mysqli = conectar();
    $pdtoEmpaqueSelect = $link_mysqli->query("
      SELECT * FROM pdtoempaque 
      WHERE sttempaque = '1'
      ORDER BY idempaque");

    $idEmpaque = 1;
    while ($regPdtoEmpaque = $pdtoEmpaqueSelect->fetch_object())
    { $pdtoEmpaque_idempaque[$idEmpaque] = $regPdtoEmpaque->idempaque;
      $pdtoEmpaque_empaque[$idEmpaque] = $regPdtoEmpaque->empaque;
      $pdtoEmpaque_cdgempaque[$idEmpaque] = $regPdtoEmpaque->cdgempaque; 

      $idEmpaque++; }

    $numEmpaques = $pdtoEmpaqueSelect->num_rows;

    // Botón Salvar
    if ($_POST['submit_salvar'])
    { $msg_modulo = 'Salvando ...';

      if (substr($sistModulo_permiso,0,2) == 'rw')
      { if ($vntsoc_cdgoc != '')
        { if ($vntsoclote_cdglote != '')
          { $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE vntsoclote
              SET cantidad = '".$vntsoclote_cantidad."',
                fchembarque = '".$vntsoclote_fchembarque."',
                fchentrega = '".$vntsoclote_fchentrega."',
                cdgempaque = '".$vntsoclote_cdgempaque."',
                referencia = '".$vntsoclote_referencia."'
              WHERE cdglote = '".$vntsoclote_cdglote."' AND
                sttlote = '1'");

            if ($link_mysqli->affected_rows > 0)
            { $msg_modulo = 'El lote fue ACTUALIZADO.';
            } else
            { $msg_modulo = 'El lote NO reporto cambios.'; }
          } else
          { for ($id = 1; $id <= 999; $id++)
            { if ($id >= 1000)
              { $msg_modulo = 'Los codigos disponibles para armado del documento se han agotado.'; 
              } else
              { $link_mysqli = conectar();
                $vntsocloteSelect = $link_mysqli->query("
                  SELECT * FROM vntsoclote
                  WHERE cdgoc = '".$vntsoc_cdgoc."' AND
                    idlote = '".$id."'"); 

                $vntsoclote_cdglote = $vntsoc_cdgoc.str_pad($id,3,'0',STR_PAD_LEFT);

                if ($vntsocloteSelect->num_rows > 0)
                { // Codigo utilizado
                } else
                { $link_mysqli = conectar();
                  $link_mysqli->query("
                    INSERT vntsoclote
                      (cdgoc, idlote, cdgproducto, cantidad, fchembarque, fchentrega, cdgempaque, referencia, cdglote)
                    VALUES
                      ('".$vntsoc_cdgoc."', '".$id."', '".$vntsoclote_cdgproducto."', '".$vntsoclote_cantidad."', '".$vntsoclote_fchembarque."', '".$vntsoclote_fchentrega."', '".$vntsoclote_cdgempaque."', '".$vntsoclote_referencia."', '".$vntsoclote_cdglote."')");

                  if ($link_mysqli->affected_rows > 0)
                  { $msg_modulo = 'El lote fue insertado.'; 
                  } else
                  { $msg_modulo = 'El lote NO fue insertado.'; }

                  $id = 1000; 
                }                
              }
            } 
          }
        } else
        { $msg_modulo = 'No cuentas una Orden de Compra valida.'; }
      } else
      { $msg_modulo = 'No cuentas con permisos de escritura en este modulo.'; }

      $vntsoclote_cdglote = '';
    }

    if (substr($sistModulo_permiso,0,1) == 'r')
    { //if ($_GET['cdgoc']) { $vntsoc_cdgoc = $_GET['cdgoc']; 
      if ($_GET['cdglote']) 
      { $link_mysqli = conectar();
        $vntsocloteSelect = $link_mysqli->query("
          SELECT * FROM vntsoclote
          WHERE cdglote = '".$_GET['cdglote']."'");

        if ($vntsocloteSelect->num_rows > 0)
        { $msg_modulo = 'Registro encontrado y cargado';

          $regVntsOClote = $vntsocloteSelect->fetch_object();

          $vntsoclote_cdgoc = $regVntsOClote->cdgoc;
          $vntsoclote_cdgproducto = $regVntsOClote->cdgproducto;
          $vntsoclote_cantidad = number_format($regVntsOClote->cantidad,3,'.','');
          //$vntsoclote_surtido = number_format($regVntsOClote->surtido,3,'.','');
          $vntsoclote_fchembarque = $regVntsOClote->fchembarque;
          $vntsoclote_fchentrega = $regVntsOClote->fchentrega;
          $vntsoclote_cdgempaque = $regVntsOClote->cdgempaque;
          $vntsoclote_referencia = $regVntsOClote->referencia;
          $vntsoclote_cdglote = $regVntsOClote->cdglote; 
          $vntsoclote_sttlote = $regVntsOClote->sttlote; 

          //Actualizar status
          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($vntsoclote_sttlote == '1')
              { $vntsoclote_newsttlote = '0'; }
              
              if ($vntsoclote_sttlote == '0')
              { $vntsoclote_newsttlote = '1'; }
              
              if ($vntsoclote_newsttlote != '')
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE vntsoclote
                  SET sttlote = '".$vntsoclote_newsttlote."'
                  WHERE cdglote = '".$vntsoclote_cdglote."'");

                if ($link_mysqli->affected_rows > 0)
                { $msg_modulo = 'El Lote de la Orden de Compra fue ACTUALIZADO satisfactoriamente.'; }
                else
                { $msg_modulo = 'El Lote de la Orden de Compra NO fue ACTUALIZADO.'; }
                
              } else
              { $msg_modulo = 'El Lote de la Orden de Compra que seleccionaste, no tiene permitido cambiar de status.'; }
            } else
            { $msg_modulo = 'No cuentas con permisos de reescritura en este modulo.'; }
          }

          //Eliminar registro
          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $link_mysqli = conectar();
              $vntsSurtidoSelect = $link_mysqli->query("
                SELECT * FROM vntssurtido
                WHERE cdglote = '".$vntsoclote_cdglote."'");

              if ($vntsSurtidoSelect->num_rows > 0)
              { $msg_modulo = 'El Lote de la Orden de Compra NO fue eliminado por que existen embarques asociados.'; }
              else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  DELETE FROM vntsoclote
                  WHERE cdglote = '".$vntsoclote_cdglote."' AND
                    sttlote = '0'");

                if ($link_mysqli->affected_rows > 0)
                { $msg_modulo = 'El Lote de la Orden de Compra fue ELIMINADO satisfactoriamente.'; }
                else
                { $msg_modulo = 'El Lote de la Orden de Compra NO fue eliminado.'; }
              }
            } else
            { $msg_modulo = 'No cuentas con permisos para remover en este modulo.'; }
            
            $vntsoclote_cdglote = '';            
          }

          $link_mysqli = conectar();
          $vntsocSelect = $link_mysqli->query("
            SELECT * FROM vntsoc
            WHERE cdgoc = '".$vntsoclote_cdgoc."'");

          if ($vntsocSelect->num_rows > 0)
          { $regVntsOC = $vntsocSelect->fetch_object();

            $vntsoc_cdgsucursal = $regVntsOC->cdgsucursal;
            $vntsoc_oc = $regVntsOC->oc;
            $vntsoc_fchdocumento = $regVntsOC->fchdocumento;
            $vntsoc_fchrecepcion = $regVntsOC->fchrecepcion;
            $vntsoc_fchcaptura = $regVntsOC->fchcaptura;
            $vntsoc_observacion = $regVntsOC->observacion;
            $vntsoc_cdgoc = $regVntsOC->cdgoc;
            $vntsoc_sttoc = $regVntsOC->sttoc; 

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
          { $regVntsOC = $vntsocSelect->fetch_object();

            $vntsoc_cdgsucursal = $regVntsOC->cdgsucursal;
            $vntsoc_oc = $regVntsOC->oc;
            $vntsoc_fchdocumento = $regVntsOC->fchdocumento;
            $vntsoc_fchrecepcion = $regVntsOC->fchrecepcion;
            $vntsoc_fchcaptura = $regVntsOC->fchcaptura;
            $vntsoc_observacion = $regVntsOC->observacion;          
            $vntsoc_sttoc = $regVntsOC->sttoc; 

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
      $vntsocloteSelect = $link_mysqli->query("
        SELECT pdtoimpresion.impresion,
          pdtoimpresion.cdgimpresion,
          pdtoempaque.empaque,
          vntsoclote.cantidad,
          vntsoclote.surtido,
          vntsoclote.fchembarque,
          vntsoclote.fchentrega,
          vntsoclote.referencia,
          vntsoclote.cdglote,
          vntsoclote.sttlote
        FROM vntsoclote,
          pdtoimpresion,
          pdtoempaque
        WHERE vntsoclote.cdgoc = '".$vntsoc_cdgoc."' AND
          vntsoclote.cdgproducto = pdtoimpresion.cdgimpresion AND
          vntsoclote.cdgempaque = pdtoempaque.cdgempaque
        ORDER BY vntsoclote.cdglote,
          vntsoclote.sttlote DESC"); 

      if ($vntsocloteSelect->num_rows > 0)
      { $id_lote = 1;
        $id_producto = 0;
        while ($regVntsOClote = $vntsocloteSelect->fetch_object())
        { $vntsoclotes_producto[$id_lote] = $regVntsOClote->impresion;
          $vntsoclotes_cdgproducto[$id_lote] = $regVntsOClote->cdgimpresion;
          $vntsoclotes_empaque[$id_lote] = $regVntsOClote->empaque;
          $vntsoclotes_cantidad[$id_lote] = $regVntsOClote->cantidad;
          $vntsoclotes_surtido[$id_lote] = $regVntsOClote->surtido;
          $vntsoclotes_fchembarque[$id_lote] = $regVntsOClote->fchembarque;
          $vntsoclotes_fchentrega[$id_lote] = $regVntsOClote->fchentrega;
          $vntsoclotes_referencia[$id_lote] = $regVntsOClote->referencia;
          $vntsoclotes_cdglote[$id_lote] = $regVntsOClote->cdglote;
          $vntsoclotes_sttlote[$id_lote] = $regVntsOClote->sttlote;

          $vntsocProducto_cantidad[$regVntsOClote->cdgimpresion] += $regVntsOClote->cantidad;
          $vntsocProducto_surtido[$regVntsOClote->cdgimpresion] += $regVntsOClote->surtido;

          if ($vntsocProducto_producto[$regVntsOClote->cdgimpresion] == '')
          { $id_producto++; $vntsocProducto_cdgproducto[$id_producto] = $regVntsOClote->cdgimpresion; 
            $vntsocProducto_producto[$regVntsOClote->cdgimpresion] = $regVntsOClote->impresion; }

          $id_lote++; }

        $numproductos = $id_producto;
        $num_lotes = $vntsocloteSelect->num_rows; }

    } else
    { $msg_modulo = 'No cuentas con permisos de lectura en este modulo.'; }

    echo '
      <form id="form_ventas" name="form_ventas" method="POST" action="vntsOClote.php" />
        <input type="hidden" id="hidden_cdgoc" name="hidden_cdgoc" value="'.$vntsoc_cdgoc.'" />
        <input type="hidden" id="hidden_cdglote" name="hidden_cdglote" value="'.$vntsoclote_cdglote.'" />

        <table align="center">
          <thead>
            <tr><th colspan="4"><label for="label_modulo">'.$sistModulo_modulo.'</label></th></tr>
          </thead>
          <tbody>            
            <tr align="center"><td><label for="label_fchdocumento">Fecha documento<br/>
                  <strong>'.$vntsoc_fchdocumento.' </strong></label></td>
              <td><label for="label_fchrecepcion">Fecha recepci&oacute;n<br/>
                  <strong>'.$vntsoc_fchrecepcion.' </strong></label></td>
              <td><label for="label_fchcaptura">Fecha captura<br/>
                  <strong>'.$vntsoc_fchcaptura.' </strong></label></td>
              <th colspan="3" align="right"><strong><label for="label_ttloc">O.C.</label><br/>
                <label for="label_oc"><a href="vntsOC?cdgoc='.$vntsoc_cdgoc.'" style="color:red;">'.$vntsoc_oc.'</a></label></strong></th></tr>
            <tr><td colspan="4"><label for="label_cliente">Cliente<br/>
                  <strong>'.$vntsSucursal_cliente.' </strong> ('.$vntsSucursal_idcliente.')</label><br/><br/>
                <label for="label_sucursal">Sucursal<br/>
                  <strong>'.$vntsSucursal_sucursal.' </strong> ('.$vntsSucursal_idsucursal.')</label><br/><br/>
                <label for="label_domicilio">Domicilio<br/>
                  <strong>'.$vntsSucursal_domicilio.'</strong> C.P. <strong>'.$vntsSucursal_cdgpostal.'</strong><br/>
                  <strong>'.$vntsSucursal_ciudad.'</strong> Tel. <strong>'.$vntsSucursal_telefono.'</strong></label></td></tr>
            <tr><td colspan="4">
                <table align="center">
                  <thead>
                    <tr><th>Producto</th>
                      <th>Solicitado</th>
                      <th>Surtido</th>
                      <th>Pendiente</th></tr>
                  </thead>
                  <tbody>';
          
    for ($id_producto = 1; $id_producto <= $numproductos; $id_producto++) 
    { echo'
                    <tr><td>'.$vntsocProducto_producto[$vntsocProducto_cdgproducto[$id_producto]].'</td>
                      <td align="right"><b>'.number_format($vntsocProducto_cantidad[$vntsocProducto_cdgproducto[$id_producto]],3,'.',',').'</b></td>
                      <td align="right"><b>'.number_format($vntsocProducto_surtido[$vntsocProducto_cdgproducto[$id_producto]],3,'.',',').'</b></td>
                      <td align="right"><b>'.number_format(($vntsocProducto_cantidad[$vntsocProducto_cdgproducto[$id_producto]]-$vntsocProducto_surtido[$vntsocProducto_cdgproducto[$id_producto]]),3,'.',',').'</b></td></tr>'; }

    echo '
                  </tbody>
                </table>
              </td></tr>
          </tbody>
          <tfoot>
            <tr><td colspan="4"></td></tr>
          </tfoot>
        </table><br/>

        <table align="center">
          <thead>
            <tr><th colspan="3"><label for="lbl_submodulo">Cargador de productos</label></th></tr>
          </thead>
          <tbody>
            <tr><td><label for="lbl_cdgproducto"><strong><a href="../sm_producto/pdtoImpresion.php?cdgimpresion='.$vntsEmbarque_cdgproducto.'">Producto</a></strong></label><br/>
                <select style="width:140px" id="select_cdgproducto" name="select_cdgproducto" onchange="document.form_ventas.submit()">
                  <option value="">Selecciona una opcion</option>';
    
    for ($idDiseno = 1; $idDiseno <= $numDisenos; $idDiseno++) 
    { echo '
                  <optgroup label="'.$pdtoDiseno_diseno[$idDiseno].'">';

      for ($id_impresion = 1; $id_impresion <= $numimpresiones[$idDiseno]; $id_impresion++) 
      { echo '
                    <option value="'.$pdtoImpresion_cdgimpresion[$idDiseno][$id_impresion].'"';
            
        if ($vntsoclote_cdgproducto == $pdtoImpresion_cdgimpresion[$idDiseno][$id_impresion]) 
        { echo ' selected="selected"'; }

        echo '>'.$pdtoImpresion_impresion[$idDiseno][$id_impresion].'</option>'; }

      echo '
                  </optgroup>'; }
    
    echo '
                </select></td>
              <td><label for="lbl_cdgempaque"><strong>Empaque</strong></label><br/>
                <select style="width:140px" id="select_cdgempaque" name="select_cdgempaque" onchange="document.form_ventas.submit()">
                  <option value="">Selecciona una opcion</option>';

    for ($idEmpaque = 1; $idEmpaque <= $numEmpaques; $idEmpaque++) 
    { echo '
                    <option value="'.$pdtoEmpaque_cdgempaque[$idEmpaque].'"';
            
      if ($vntsoclote_cdgempaque == $pdtoEmpaque_cdgempaque[$idEmpaque]) 
      { echo ' selected="selected"'; }

      echo '>'.$pdtoEmpaque_empaque[$idEmpaque].'</option>'; }
    
    echo '
                </select></td>
              <td><label for="label_cantidad"><strong>Cantidad</strong></label><br/>
                <input type="text" id="text_cantidad" name="text_cantidad" value="'.$vntsoclote_cantidad.'" style="width:70px;  text-align:right;" required /></td></tr>
            <tr>
              <td><label for="label_fchembarque"><strong>Fecha embarque</strong></label><br/>
                <input type="date" id="date_fchembarque" name="date_fchembarque" value="'.$vntsoclote_fchembarque.'" style="width:140px;" required /></td>
              <td><label for="label_fchentrega"><strong>Fecha entrega</strong></label><br/>
                <input type="date" id="date_fchentrega" name="date_fchentrega" value="'.$vntsoclote_fchentrega.'" style="width:140px;" required /></td>
              <td><label for="label_referencia"><strong>Referencia</strong></label><br/>
                <input type="text" id="text_referencia" name="text_referencia" value="'.$vntsoclote_referencia.'" style="width:120px;" /></td></tr>
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
          <tr align="left">
            <th></th>
            <th><label for="lbl_ttldiseno">Producto</label></th>
            <th><label for="lbl_ttlcantidad">Cantidad</label></th>
            <th><label for="lbl_ttlsurtido">Surtido</label></th>
            <th><label for="lbl_ttlfchdocumento">Embarque</label></th>
            <th><label for="lbl_ttlfchrecepcion">Entrega</label></th>
            <th><label for="lbl_ttlrefdiseno">Empaque</label></th>
            <th><label for="lbl_ttlrefdiseno">Referencia</label></th>
            <th colspan="3"><label for="lbl_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';

    if ($num_lotes > 0)
    { for ($id_lote=1; $id_lote<=$num_lotes; $id_lote++)
      { echo '
          <tr align="center">
            <td><strong>'.$id_lote.'</strong></td>
            <td align="left"><strong>'.$vntsoclotes_producto[$id_lote].'</strong></td>
            <td align="right">'.$vntsoclotes_cantidad[$id_lote].'</td>
            <td align="right">'.$vntsoclotes_surtido[$id_lote].'</td>
            <td align="left">'.$vntsoclotes_fchembarque[$id_lote].'</td>
            <td align="left">'.$vntsoclotes_fchentrega[$id_lote].'</td>
            <td align="left">'.$vntsoclotes_empaque[$id_lote].'</td>
            <td align="left">'.$vntsoclotes_referencia[$id_lote].'</td>';

        if ((int)$vntsoclotes_sttlote[$id_lote] > 0)
        { echo '
            <td><a href="vntsOClote.php?cdglote='.$vntsoclotes_cdglote[$id_lote].'">'.$png_search.'</a></td>            
            <td><a href="vntsSurtido.php?cdglote='.$vntsoclotes_cdglote[$id_lote].'">'.$png_delivery.'</a></td>
            <td><a href="vntsOClote.php?cdglote='.$vntsoclotes_cdglote[$id_lote].'&proceso=update">'.$png_power_blue.'</a></td>'; }
        else
         { echo '
            <td><a href="vntsOClote.php?cdglote='.$vntsoclotes_cdglote[$id_lote].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td></td>
            <td><a href="vntsOClote.php?cdglote='.$vntsoclotes_cdglote[$id_lote].'&proceso=update">'.$png_power_black.'</a></td>'; }

        echo '</tr>';
      }      
    }

    echo '
        </tbody>
        <tfoot>
          <tr><td></td>
            <th colspan="10" align="right">
              <label for="lbl_ppgdatos">['.$num_lotes.'] Registros encontrados</label></th></tr>
        </tfoot>
      </table>
    </form>'; 

  } else
  { echo '
    <div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }  
  
 ?>

  </body>
</html>