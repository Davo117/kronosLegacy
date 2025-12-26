<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Surtido de confirmaciones</title>    
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
<?php

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_ventas();

  $sistModulo_cdgmodulo = '40220';
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
        <form id="login" action="vntsOClote.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 

      exit; }

    // Lote de la orden de compra
    if ($_GET['cdglote'])
    { $vntsOClote_cdglote =  $_GET['cdglote']; }
    else
    { $vntsOClote_cdglote = trim($_POST['textCdgLote']); }

    // Embarque seleccionado para surtir
    if ($_POST['slctCdgEmbarque'])
    { $vntsOClote_cdgembarque = $_POST['slctCdgEmbarque']; }

    //Búsqueda del lote seleccionado para surtir
    $vntsOCloteSelect = $link->query("
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
    { $msg_alert .= "Lote ubicado. \n";

      $regVntsOClote = $vntsOCloteSelect->fetch_object();

      // Lote encontrado, descarga la información encontrada
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

      // Guarda el código del producto en una variable global
      $_SESSION['vntsSurtido_cdgproducto'] = $regVntsOClote->cdgproducto;

      // Búsqueda de la orden de compra a la que pertenece el lote seleccionado para surtir
      $vntsOCSelect = $link->query("
        SELECT * FROM vntsoc
         WHERE cdgoc = '".$vntsOClote_cdgoc."'");

      if ($vntsOCSelect->num_rows > 0)
      { $regVntsOC = $vntsOCSelect->fetch_object();
        
        // Orden de compra encontrada, descarga la información encontrada
        $vntsOC_cdgsucursal = $regVntsOC->cdgsucursal;
        $vntsOC_oc = $regVntsOC->oc;
        $vntsOC_fchdocumento = $regVntsOC->fchdocumento;
        $vntsOC_fchrecepcion = $regVntsOC->fchrecepcion;
        $vntsOC_fchcaptura = $regVntsOC->fchcaptura;
        $vntsOC_observacion = $regVntsOC->observacion;
        $vntsOC_sttoc = $regVntsOC->sttoc;

        // Búsqueda de la sucursal/cliente que finco la orden de compra
        $vntsSucursalSelect = $link->query("
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

          // Sucursal encontrada, descarga la información encontrada
          $vntsSucursal_idcliente = $regVntsSucursal->idcliente;
          $vntsSucursal_cliente = $regVntsSucursal->cliente;
          $vntsSucursal_idsucursal = $regVntsSucursal->idsucursal;
          $vntsSucursal_sucursal = $regVntsSucursal->sucursal;
          $vntsSucursal_domicilio = $regVntsSucursal->domicilio;
          $vntsSucursal_cdgpostal = $regVntsSucursal->cdgpostal;
          $vntsSucursal_telefono = $regVntsSucursal->telefono;
          $vntsSucursal_ciudad = $regVntsSucursal->ciudad; 

          // Búsqueda de embarques compartibles con el producto y la sucursal/cliente
          if ($vntsOClote_tpoempaque == 'Q')
          { // Si el embarque es de rollos
            // Búsqueda de embarques de rollo compartibles
            $vntsEmbarqueSelect = $link->query("
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

            if ($vntsEmbarqueSelect->num_rows > 0)
            { $item = 0;
              // Embarque(s) compatible(s) encontrado(s), descarga de la información encontrada.
              while ($regVntsEmbarque = $vntsEmbarqueSelect->fetch_object())
              { $item++;

                $vntsEmbarque_cdgembarque[$item] = $regVntsEmbarque->cdgembarque;
                $vntsEmbarque_cantidad[$item] = $regVntsEmbarque->cantidad;
                $vntsEmbarque_presentacion[$item] = 'Q'; }

              $nEmbarques = $vntsEmbarqueSelect->num_rows;
            } // Final de la busqueda de embarques de rollo compatibles.
          } else
          { // Si el embarque es de paquetes.
            // Búsqueda de embarques de paquete compartibles.
            $vntsEmbarqueSelect = $link->query("
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

            if ($vntsEmbarqueSelect->num_rows > 0)
            { $item = $nEmbarques;
              // Embarque(s) compatible(s) encontrado(s), descarga de la información encontrada.
              while ($regVntsEmbarque = $vntsEmbarqueSelect->fetch_object())
              { $item++;

                $vntsEmbarque_cdgembarque[$item] = $regVntsEmbarque->cdgembarque;
                $vntsEmbarque_cantidad[$item] = $regVntsEmbarque->cantidad;
                $vntsEmbarque_presentacion[$item] = 'C'; }

              $nEmbarquesC = $vntsEmbarqueSelect->num_rows;
              $nEmbarques = $nEmbarques+$nEmbarquesC;
            } // Final de la búsqueda de embarques de paquete compatibles.
          } // Final de la búsqueta de embarques compatibles.
          // Final de la búsqueda de la sucursal/cliente que finco la orden de compra.
        } else
        { $msg_alert .= "No fue posible ubicar el destino de la Orden de Compra seleccionada. \n"; }
        // Final de la búsqueda de sucursal/destino.
      } else
      { $msg_alert .= "No fue posible ubicar la Orden de Compra seleccionada. \n"; }
      // Final de la búsqueda de la orden de compra.
    } else
    { $msg_alert .= "No fue posible ubicar el lote seleccionado. \n"; }
    // Final de la búsqueda del lote seleccionado.
    
    // Guardar cambios
    if ($_POST['bttnSalvar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if ($vntsOClote_cdglote != '')
        { if ($vntsOClote_cdgembarque != '')
          { $link->query("
              UPDATE vntsembarque
                 SET cdglote = '".$vntsOClote_cdglote."',
                     cantidad = '".$_SESSION['vntsoclote_cantidad']."',
                     fchmovimiento = NOW()
               WHERE cdgembarque = '".$_SESSION['vntsoclote_cdgembarque']."' AND
                     cdgproducto = '".$_SESSION['vntsSurtido_cdgproducto']."'");

            if ($link->affected_rows > 0)
            { $vntsSurtidoSelect = $link->query("
                SELECT SUM(cantidad) AS surtido
                  FROM vntsembarque
                  WHERE cdglote = '".$vntsOClote_cdglote."'");

              if ($vntsSurtidoSelect->num_rows > 0)
              { $regVntsSurtido = $vntsSurtidoSelect->fetch_object();

                $vntsOClote_surtido = number_format($regVntsSurtido->surtido,2,'.','');
              } else
              { $vntsOClote_surtido = 0; }

              $link->query("
                UPDATE vntsoclote
                   SET surtido = ".$vntsOClote_surtido."
                 WHERE cdglote = '".$vntsOClote_cdglote."' AND
                       sttlote = '1'");

              if ($link->affected_rows > 0)
              { $msg_alert = 'El lote fue surtido.';
              } else
              { $msg_alert = 'El lote NO reporto cambios. (Cantidad)'; }
                          
            } else
            { $msg_alert = 'El lote NO reporto cambios.'; }

            $vntsOClote_cdglote = '';
          } else
          { $msg_alert = 'No fue posible referenciar el embarque a la O.C. (Embarque).'; }
        } else
        { $msg_alert = 'No fue posible referenciar el embarque a la O.C. (O.C.).'; }
      } else
      { $msg_alert = 'No cuentas con permisos de escritura en este modulo.'; }
    }

    echo '
      <div class="bloque">
        <form id="formVntsSurtido" name="formVntsSurtido" method="POST" action="vntsSurtido.php" />
          <input type="hidden" id="textCdgOC" name="textCdgOC" value="'.$vntsOClote_cdgoc.'" />
          <input type="hidden" id="textCdgLote" name="textCdgLote" value="'.$vntsOClote_cdglote.'" />

          <article class="subbloque">
            <label class="modulo_nombre">Surtido de confirmaciones</label>
          </article>
          <a href="ayuda.php#Surtido">'.$_help_blue.'</a>

          <section class="subbloque">
            <article>
              <label><a href="vntsOClote.php?cdgoc='.$vntsOClote_cdgoc.'">O.C.</a></label><br/>
              <label><strong>'.$vntsOC_oc.'</strong></label>
            </article>

            <article>
              <label>Cliente</label><br/>
              <label><strong>'.$vntsSucursal_cliente.'</strong></label>
            </article>

            <article>
              <label>Sucursal</label><br/>
              <label><strong>'.$vntsSucursal_sucursal.'</strong></label>
            </article>
          </section>
          
          <section class="subbloque">
            <article>
              <label>Fecha documento</label><br/>
              <label><strong>'.$vntsOC_fchdocumento.'</strong></label>
            </article>

            <article>
              <label>Fecha recepción</label><br/>
              <label><strong>'.$vntsOC_fchrecepcion.'</strong></label>
            </article>

            <article>
              <label>Fecha captura</label><br/>
              <label><strong>'.$vntsOC_fchcaptura.'</strong></label>
            </article>          
          </section>          

          <section class="subbloque">
            <article>
              <label>Consignado a:</label><br/>
              <label><strong>'.$vntsSucursal_domicilio.'</strong> C.P. <strong>'.$vntsSucursal_cdgpostal.'</strong></label><br/>
              <label><strong>'.$vntsSucursal_ciudad.'</strong> Tel. <strong>'.$vntsSucursal_telefono.'</strong></label>
            </article>            
          </section>
        </div>

        <div class="bloque">
          <article class="subbloque">
            <label class="modulo_listado">Embarques compatibles</label>
          </article>

          <section class="subbloque">
            <article style="text-align:right">
              <label>Producto</label><br/>
              <label><strong>'.$vntsOClote_cantidad.'</strong></label><br/>
              <label><strong>'.$vntsOClote_surtido.'</strong></label><br/>
              <label>Fecha de embarque</label><br/>
              <label>Fecha de entrega</label><br/>
              <label>Referencia</label><br/>
              <label>Embarques <a href="vntsEmbarqueMaker.php?cdgembarque='.$vntsOClote_cdgembarque.'">'.$vntsOClote_cdgembarque.'</a></label>
            </article>

            <article>
              <label><em>'.$vntsOClote_producto.'</em></label><br/>
              <label>Confirmado</label><br/>
              <label>Surtido</label><br/>
              <label>'.$vntsOClote_fchembarque.'</label><br/>
              <label>'.$vntsOClote_fchentrega.'</label><br/>
              <label>'.$vntsOClote_referencia.'&nbsp;</label><br/>
              <select id="slctCdgEmbarque" name="slctCdgEmbarque" onchange="document.formVntsSurtido.submit()">
                  <option value="">Elige un embarque</option>';
    
    if ($nEmbarques > 0)
    { for ($item = 1; $item <= $nEmbarques; $item++) 
      { echo '
                  <option value="'.$vntsEmbarque_cdgembarque[$item].'"';
            
        if ($vntsOClote_cdgembarque == $vntsEmbarque_cdgembarque[$item]) 
        { $_SESSION['vntsoclote_cdgembarque'] = $vntsEmbarque_cdgembarque[$item];
          $_SESSION['vntsoclote_cantidad'] = $vntsEmbarque_cantidad[$item];

          echo ' selected="selected"'; }

        echo '>'.$vntsEmbarque_presentacion[$item].$vntsEmbarque_cdgembarque[$item].' | '.$vntsEmbarque_cantidad[$item].' millares</option>'; }
    }
    
    echo '
              </select>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>
          </section>
        </form>
      </div>';

    if ($msg_alert != '')
    { echo '
      <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
    <div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
?>

  </body>
</html>