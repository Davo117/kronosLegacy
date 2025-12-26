<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '40220';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    // Lote de la orden de compra
    if ($_GET['cdglote'])
    { $vntsOClote_cdglote =  $_GET['cdglote']; }
    else      
    { $vntsOClote_cdglote = trim($_POST['hidden_cdglote']); }

    // Embarque seleccionado para surtir
    if ($_POST['select_cdgembarque'])
    { $vntsOClote_cdgembarque = $_POST['select_cdgembarque']; }

    $link_mysqli = conectar();
    $vntsOCloteSelect = $link_mysqli->query("
      SELECT vntsoclote.cdgoc,
        vntsoclote.cdgproducto,
        vntsoclote.cantidad,
        vntsoclote.surtido,
        vntsoclote.fchembarque,
        vntsoclote.cdgempaque,
        vntsoclote.fchentrega,
        vntsoclote.referencia,
        vntsoclote.cdglote,
        vntsoclote.sttlote,
        pdtoimpresion.impresion
      FROM vntsoclote,
        pdtoimpresion
      WHERE vntsoclote.cdgproducto = pdtoimpresion.cdgimpresion AND
        vntsoclote.cdglote = '".$vntsOClote_cdglote."'");

    if ($vntsOCloteSelect->num_rows > 0)
    { $msg_modulo .= "Lote ubicado. <br/>";

      $regVntsOClote = $vntsOCloteSelect->fetch_object();
      // Datos
      $vntsOClote_cdgoc = $regVntsOClote->cdgoc;
      $vntsOClote_cdgproducto = $regVntsOClote->cdgproducto;
      $vntsOClote_cantidad = number_format($regVntsOClote->cantidad,3,'.','');
      $vntsOClote_surtido = number_format($regVntsOClote->surtido,3,'.','');
      $vntsOClote_fchembarque = $regVntsOClote->fchembarque;
      $vntsOClote_fchentrega = $regVntsOClote->fchentrega;
      $vntsOClote_referencia = $regVntsOClote->referencia;
      $vntsOClote_tpoempaque = $regVntsOClote->cdgempaque; 
      $vntsOClote_cdglote = $regVntsOClote->cdglote; 
      $vntsOClote_sttlote = $regVntsOClote->sttlote;
      $vntsOClote_producto = $regVntsOClote->impresion;

      $_SESSION['vntsSurtido_cdgproducto'] = $regVntsOClote->cdgproducto;

      $link_mysqli = conectar();
      $vntsOCSelect = $link_mysqli->query("
        SELECT * FROM vntsoc
        WHERE cdgoc = '".$vntsOClote_cdgoc."'");

      if ($vntsOCSelect->num_rows > 0)
      { $regVntsOC = $vntsOCSelect->fetch_object();
        // Datos          
        $vntsOC_cdgsucursal = $regVntsOC->cdgsucursal;
        $vntsOC_oc = $regVntsOC->oc;
        $vntsOC_fchdocumento = $regVntsOC->fchdocumento;
        $vntsOC_fchrecepcion = $regVntsOC->fchrecepcion;
        $vntsOC_fchcaptura = $regVntsOC->fchcaptura;
        $vntsOC_observacion = $regVntsOC->observacion;          
        $vntsOC_sttoc = $regVntsOC->sttoc;       

        $link_mysqli = conectar();
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
            vntssucursal.cdgsucursal = '".$vntsOC_cdgsucursal."' AND
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
          $vntsSucursal_ciudad = $regVntsSucursal->ciudad; 

          
          if ($vntsOClote_tpoempaque == 'Q')
          {
            // QUESOS
            $link_mysqli = conectar();
            $vntsEmbarqueSelect = $link_mysqli->query("
              SELECT vntsembarque.cdgembarque,
                SUM(alptempaquer.cantidad) AS cantidad 
              FROM vntsembarque,
                alptempaque,
                alptempaquer
              WHERE vntsembarque.cdgsucursal = '".$vntsOC_cdgsucursal."' AND
                vntsembarque.cdgproducto = '".$vntsOClote_cdgproducto."' AND
               (vntsembarque.cdgembarque = alptempaque.cdgembarque AND
                alptempaque.cdgempaque = alptempaquer.cdgempaque) AND
                vntsembarque.cdglote = ''
              GROUP BY vntsembarque.cdgembarque");
            /*$vntsEmbarqueSelect = $link_mysqli->query("
              SELECT vntsembarque.cdgembarque,
                SUM(alptempaquer.cantidad) AS cantidad 
              FROM vntsembarque,
                alptempaque,
                alptempaquer
              WHERE vntsembarque.cdgsucursal = '".$vntsOC_cdgsucursal."' AND
                vntsembarque.cdgproducto = '".$vntsOClote_cdgproducto."' AND
               (vntsembarque.cdgembarque = alptempaque.cdgembarque AND
                alptempaque.cdgempaque = alptempaquer.cdgempaque) AND
                vntsembarque.cdgembarque NOT IN (SELECT DISTINCT vntssurtido.cdgembarque FROM vntssurtido)
              GROUP BY vntsembarque.cdgembarque"); //*/

            if ($vntsEmbarqueSelect->num_rows > 0)
            { $num_embarques = $vntsEmbarqueSelect->num_rows;

              $id_embarque = 1;
              while ($regVntsEmbarque = $vntsEmbarqueSelect->fetch_object())
              { $vntsEmbarque_cdgembarque[$id_embarque] = $regVntsEmbarque->cdgembarque;
                $vntsEmbarque_cantidad[$id_embarque] = $regVntsEmbarque->cantidad;
                $vntsEmbarque_presentacion[$id_embarque] = 'Q';

                $id_embarque++; }
            } // Generador de embarques en Queso //*/

          }
          else
          {
            // CAJAS 
            $link_mysqli = conectar();
            $vntsEmbarqueSelect = $link_mysqli->query("
              SELECT vntsembarque.cdgembarque,
                SUM(alptempaquep.cantidad) AS cantidad 
              FROM vntsembarque,
                alptempaque,
                alptempaquep
              WHERE vntsembarque.cdgsucursal = '".$vntsOC_cdgsucursal."' AND
                vntsembarque.cdgproducto = '".$vntsOClote_cdgproducto."' AND
               (vntsembarque.cdgembarque = alptempaque.cdgembarque AND
                alptempaque.cdgempaque = alptempaquep.cdgempaque) AND
                vntsembarque.cdglote = ''
              GROUP BY vntsembarque.cdgembarque");
            /*$vntsEmbarqueSelect = $link_mysqli->query("
              SELECT vntsembarque.cdgembarque,
                SUM(alptempaquep.cantidad) AS cantidad 
              FROM vntsembarque,
                alptempaque,
                alptempaquep
              WHERE vntsembarque.cdgsucursal = '".$vntsOC_cdgsucursal."' AND
                vntsembarque.cdgproducto = '".$vntsOClote_cdgproducto."' AND
               (vntsembarque.cdgembarque = alptempaque.cdgembarque AND
                alptempaque.cdgempaque = alptempaquep.cdgempaque) AND
                vntsembarque.cdgembarque NOT IN (SELECT DISTINCT vntssurtido.cdgembarque FROM vntssurtido)
              GROUP BY vntsembarque.cdgembarque"); //*/

            if ($vntsEmbarqueSelect->num_rows > 0)
            { $num_embarquesc = $vntsEmbarqueSelect->num_rows;

              $id_embarque = $num_embarques+1;
              while ($regVntsEmbarque = $vntsEmbarqueSelect->fetch_object())
              { $vntsEmbarque_cdgembarque[$id_embarque] = $regVntsEmbarque->cdgembarque;
                $vntsEmbarque_cantidad[$id_embarque] = $regVntsEmbarque->cantidad;
                $vntsEmbarque_presentacion[$id_embarque] = 'C';

                $id_embarque++; }

              $num_embarques = $num_embarques+$num_embarquesc;
            } // Generador de embarques en Caja //*/          
            
          }

        } else
        { $msg_modulo .= "No fue posible ubicar el destino de la Orden de Compra seleccionada. <br/>"; }        
      } else
      { $msg_modulo .= "No fue posible ubicar la Orden de Compra seleccionada. <br/>"; }
    } else
    { $msg_modulo .= "No fue posible ubicar el lote seleccionado. <br/>"; }

    
    if ($_POST['submit_salvar'])
    { $msg_modulo = 'Salvando ...';

      if (substr($sistModulo_permiso,0,2) == 'rw')
      {   
        if ($vntsOClote_cdglote != '')
        { if ($vntsOClote_cdgembarque != '')
          { /*$link_mysqli = conectar();
            $link_mysqli->query("
              INSERT vntssurtido
                (cdglote, cdgembarque, cdgproducto, cantidad, fchmovimiento)
              VALUES
                ('".$vntsOClote_cdglote."', '".$_SESSION['vntsoclote_cdgembarque']."', '".$_SESSION['vntsSurtido_cdgproducto']."', '".$_SESSION['vntsoclote_cantidad']."', NOW())");*/

              /*

              $link_mysqli = conectar();
              $vntsSurtidoSelect = $link_mysqli->query("
                SELECT SUM(cantidad) AS surtido
                FROM vntsembarque
                WHERE cdglote = '".$vntsOClote_cdglote."'");

              if ($vntsSurtidoSelect->num_rows > 0)
              { $regVntsSurtido = $vntsSurtidoSelect->fetch_object();

                $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE vntsoclote
                  SET surtido = ".$regVntsSurtido->surtido."
                  WHERE cdglote = '".$vntsOClote_cdglote."' AND
                    sttlote = '1'");

                if ($link_mysqli->affected_rows > 0)
                { $msg_modulo = 'El lote fue surtido.';
                } else
                { $msg_modulo = 'El lote NO reporto cambios. (Cantidad)'; }                
              } else
              { $msg_modulo = 'El lote NO reporto cambios. (Surtido)'; }  //*/


            $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE vntsembarque
              SET cdglote = '".$vntsOClote_cdglote."',
                cantidad = '".$_SESSION['vntsoclote_cantidad']."',
                fchmovimiento = NOW()
              WHERE cdgembarque = '".$_SESSION['vntsoclote_cdgembarque']."' AND
                cdgproducto = '".$_SESSION['vntsSurtido_cdgproducto']."'");

            if ($link_mysqli->affected_rows > 0)
            { $link_mysqli = conectar();
              $vntsSurtidoSelect = $link_mysqli->query("
                SELECT SUM(cantidad) AS surtido
                FROM vntsembarque
                WHERE cdglote = '".$vntsOClote_cdglote."'");

              if ($vntsSurtidoSelect->num_rows > 0)
              { $regVntsSurtido = $vntsSurtidoSelect->fetch_object();

                $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE vntsoclote
                  SET surtido = ".$regVntsSurtido->surtido."
                  WHERE cdglote = '".$vntsOClote_cdglote."' AND
                    sttlote = '1'");

                if ($link_mysqli->affected_rows > 0)
                { $msg_modulo = 'El lote fue surtido.';
                } else
                { $msg_modulo = 'El lote NO reporto cambios. (Cantidad)'.$regVntsSurtido->surtido; }                
              } else
              { $msg_modulo = 'El lote NO reporto cambios. (Surtido)'; }              
            } else
            { $msg_modulo = 'El lote NO reporto cambios.'; }

            $vntsOClote_cdglote = '';
            //$_SESSION['vntsoclote_cdglote'] = '';
          } else
          { $msg_modulo = 'No fue posible referenciar el embarque a la O.C. (Embarque).'; }
        } else
        { $msg_modulo = 'No fue posible referenciar el embarque a la O.C. (O.C.).'; }
      } else
      { $msg_modulo = 'No cuentas con permisos de escritura en este modulo.'; }
    }

    echo '
      <form id="form_ventas" name="form_ventas" method="POST" action="vntsSurtido.php" />
        <input type="hidden" id="hidden_cdgoc" name="hidden_cdgoc" value="'.$vntsOClote_cdgoc.'" />
        <input type="hidden" id="hidden_cdglote" name="hidden_cdglote" value="'.$vntsOClote_cdglote.'" />

        <table align="center">
          <thead>
            <tr><th colspan="4"><label for="label_modulo">'.$sistModulo_modulo.'</label></th></tr>
          </thead>
          <tbody>            
            <tr align="center"><td><label for="label_fchdocumento">Fecha documento<br/>
                  <strong>'.$vntsOC_fchdocumento.' </strong></label></td>
              <td><label for="label_fchrecepcion">Fecha recepci&oacute;n<br/>
                  <strong>'.$vntsOC_fchrecepcion.' </strong></label></td>
              <td><label for="label_fchcaptura">Fecha captura<br/>
                  <strong>'.$vntsOC_fchcaptura.' </strong></label></td>
              <th colspan="3" align="right"><strong><label for="label_ttloc">O.C.</label><br/>
                <label for="label_oc"><a href="vntsOClote?cdgoc='.$vntsOClote_cdgoc.'" style="color:red;">'.$vntsOC_oc.'</a></label></strong></th></tr>
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
            <tr align="left">
              <th><label for="lbl_ttlproyecto">Producto</label></th>
              <th><label for="lbl_ttlcantidad">Cantidad</label></th>
              <th><label for="lbl_ttlsurtido">Surtido</label></th>
              <th><label for="lbl_ttlfchdocumento">Embarque</label></th>
              <th><label for="lbl_ttlfchrecepcion">Entrega</label></th>
              <th><label for="lbl_ttlrefproyecto">Referencia</label></th></tr>
          </thead>
          <tbody>
            <tr align="center">
              <td align="left"><strong>'.$vntsOClote_producto.'</strong></td>
              <td align="right">'.$vntsOClote_cantidad.'</td>
              <td align="right">'.$vntsOClote_surtido.'</td>
              <td align="left">'.$vntsOClote_fchembarque.'</td>
              <td align="left">'.$vntsOClote_fchentrega.'</td>
              <td align="left">'.$vntsOClote_referencia.'</td></tr>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="6" align="right"></th></tr>
          </tfoot>
        </table><br/>';

    if ($msg_modulo != '')
    { echo '

        <div align="center"><strong>Aviso</strong><br/>'.$msg_modulo.'</div><br/>'; }

    echo '

        <table align="center">
          <thead>
            <tr><th><label for="lbl_submodulo">Cargador de embarques compatibles</label></th></tr>
          </thead>
          <tbody>
            <tr><td><label for="lbl_cdgbloque"><strong>Embarque: <a href="vntsEmbarqueMaker.php?cdgembarque='.$vntsOClote_cdgembarque.'">'.$vntsOClote_cdgembarque.'</a></strong></label><br/>
                <select id="select_cdgembarque" name="select_cdgembarque" onchange="document.form_ventas.submit()" style="width:300px" >
                  <option value="">Selecciona una opcion</option>';
    
    if ($num_embarques > 0)
    { for ($id_embarque = 1; $id_embarque <= $num_embarques; $id_embarque++) 
      { echo '
                    <option value="'.$vntsEmbarque_cdgembarque[$id_embarque].'"';
            
        if ($vntsOClote_cdgembarque == $vntsEmbarque_cdgembarque[$id_embarque]) 
        { $_SESSION['vntsoclote_cdgembarque'] = $vntsEmbarque_cdgembarque[$id_embarque];
          $_SESSION['vntsoclote_cantidad'] = $vntsEmbarque_cantidad[$id_embarque];

          echo ' selected="selected"'; }

        echo '>'.$vntsEmbarque_presentacion[$id_embarque].$vntsEmbarque_cdgembarque[$id_embarque].' / '.$vntsEmbarque_cantidad[$id_embarque].' millares</option>'; }
    }
    
    echo '
                </select></td></tr>
          </tbody>
          <tfoot>
            <tr><td align="right"><input type="submit" id="submit_salvar" name="submit_salvar" value="Salvar" /></td></tr>
          </tfoot>
        </table><br/>

      </form>'; 

  } else
  { echo '
    <div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }  
  
 ?>

  </body>
</html>