<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Confirmaciones de producto</title>    
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
<?php

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_ventas();

  $sistModulo_cdgmodulo = '40201';
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

    if ($_GET['cdgoc'])
    { $vntsoclote_cdgoc =  $_GET['cdgoc']; }
    else      
    { $vntsoclote_cdgoc = trim($_POST['slctCdgOC']); }

    if ($_POST['slctCdgProducto']) { $vntsoclote_cdgproducto = $_POST['slctCdgProducto']; }
    if ($_POST['textCantidad']) { $vntsoclote_cantidad = $_POST['textCantidad']; }
    if ($_POST['dateFchEmbarque']) { $vntsoclote_fchembarque = $_POST['dateFchEmbarque']; }
    if ($_POST['dateFchEntrega']) { $vntsoclote_fchentrega = $_POST['dateFchEntrega']; }
    if ($_POST['slctCdgEmpaque']) { $vntsoclote_cdgempaque = $_POST['slctCdgEmpaque']; }
    if ($_POST['textReferencia']) { $vntsoclote_referencia = $_POST['textReferencia']; }  

    $vntsOC_fchembarque = ValidarFecha($vntsOC_fchembarque);
    $vntsOC_fchentrega = ValidarFecha($vntsOC_fchentrega);   

    // Botón Salvar
    if ($_POST['bttnSalvar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if ($_POST['hddnCdgLote'] != '')
        { $link->query("
            UPDATE vntsoclote
               SET cantidad = '".$vntsoclote_cantidad."',
                   fchembarque = '".$vntsoclote_fchembarque."',
                   fchentrega = '".$vntsoclote_fchentrega."',
                   referencia = '".$vntsoclote_referencia."'
             WHERE cdglote = '".$_POST['hddnCdgLote']."' AND
                   sttlote = '1'");

          if ($link->affected_rows > 0)
          { $msg_alert = 'La confirmación fue actualizada.';

            $link->query("
              INSERT INTO krdxoclote
                (cdglote, cdgproducto, cantidad, fchembarque, fchentrega, cdgempaque, referencia, cdgusuario, operacion, fchmovimiento)
              VALUES
                ('".$_POST['hddnCdgLote']."', '".$vntsoclote_cdgproducto."', '".$vntsoclote_cantidad."', '".$vntsoclote_fchembarque."', '".$vntsoclote_fchentrega."', '".$vntsoclote_cdgempaque."', '".$vntsoclote_referencia."', '".$_SESSION['cdgusuario']."', 'UPDATE', NOW())");
          } else
          { $msg_alert = 'La confirmación no presento cambios.'; }
        } else
        { for ($item = 1; $item <= 999; $item++)
          { $vntsoclote_cdglote = $vntsoclote_cdgoc.str_pad($item,3,'0',STR_PAD_LEFT);

            $link->query("
              INSERT vntsoclote
                (cdgoc, idlote, cdgproducto, cantidad, fchcaptura, fchembarque, fchentrega, cdgempaque, referencia, cdglote)
              VALUES
                ('".$vntsoclote_cdgoc."', '".$item."', '".$vntsoclote_cdgproducto."', '".$vntsoclote_cantidad."', NOW(), '".$vntsoclote_fchembarque."', '".$vntsoclote_fchentrega."', '".$vntsoclote_cdgempaque."', '".$vntsoclote_referencia."', '".$vntsoclote_cdglote."')");

            if ($link->affected_rows > 0)
            { $link->query("
                INSERT INTO krdxoclote
                  (cdglote, cdgproducto, cantidad, fchembarque, fchentrega, cdgempaque, referencia, cdgusuario, operacion, fchmovimiento)
                VALUES
                  ('".$vntsoclote_cdglote."', '".$vntsoclote_cdgproducto."', '".$vntsoclote_cantidad."', '".$vntsoclote_fchembarque."', '".$vntsoclote_fchentrega."', '".$vntsoclote_cdgempaque."', '".$vntsoclote_referencia."', '".$_SESSION['cdgusuario']."', 'INSERT', NOW())");

              for ($subItem = 1; $subItem <= 999; $subItem++)
              { $progreque_cdgreque = date('ymd').str_pad($subItem,3,'0',STR_PAD_LEFT);

                $progRequeSelect = $link->query("
                  SELECT * FROM progreque
                   WHERE cdgreque = '".$progreque_cdgreque."'");

                if ($progRequeSelect->num_rows > 0)
                { //Sigue buscando un hueco
                } else
                { $link->query("
                    INSERT INTO progreque
                      (cdgproducto, cantidad, cdgempaque, fchreque, cdglote, cdgreque)
                    VALUES
                      ('".$vntsoclote_cdgproducto."', '".$vntsoclote_cantidad."', '".$vntsoclote_cdgempaque."', NOW(), '".$vntsoclote_cdglote."', '".$progreque_cdgreque."')");

                  if ($link->affected_rows > 0)                
                  { // Proceso de Boom de materia prima
                    /////////////////////////////////////////////////////////////

                    // Búsqueda del diseño al que pertenece el producto
                    // cdgimpresion -> Código del producto
                    $pdtoImpresionSelect = $link->query("
                      SELECT pdtoimpresion.cdgdiseno,
                             pdtosustrato.cdgsustrato,
                          (((pdtoimpresion.ancho*pdtoimpresion.alto)/1000)/pdtosustrato.rendimiento) AS consumo,
                             pdtoimpresion.cdgbanda,
                             pdtoimpresion.alto
                        FROM pdtoimpresion,
                             pdtosustrato
                       WHERE pdtoimpresion.cdgimpresion = '".$vntsoclote_cdgproducto."' AND
                             pdtosustrato.cdgsustrato = pdtoimpresion.cdgsustrato");

                    if ($pdtoImpresionSelect->num_rows > 0)
                    { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

                      $vntsoclote_cdgdiseno = $regPdtoImpresion->cdgdiseno;
                      
                      // Boom de sustrato
                      // Cadenas de 8 caracteres (XXXXXXXX)
                      $link->query("
                        INSERT INTO progrequeboom
                          (cdgreque, cdglote, cdgsubproceso, cdgelemento, consumo, requerido, fchmovimiento)
                        VALUES
                          ('".$progreque_cdgreque."', '".$vntsoclote_cdglote."', '001', '".$regPdtoImpresion->cdgsustrato."', '".$regPdtoImpresion->consumo."', '".($regPdtoImpresion->consumo*$vntsoclote_cantidad)."', NOW())");
                      // Fin del Boom de sustrato

                      // Boom de banda de seguridad
                      // Cadenas de 5 caracteres (BSXXX)
                      $link->query("
                        INSERT INTO progrequeboom
                          (cdgreque, cdglote, cdgsubproceso, cdgelemento, consumo, requerido, fchmovimiento)
                        VALUES
                          ('".$progreque_cdgreque."', '".$vntsoclote_cdglote."', '003', '".$regPdtoImpresion->cdgbanda."', '".$regPdtoImpresion->alto."', '".($regPdtoImpresion->alto*$vntsoclote_cantidad)."', NOW())");
                      // Fin del boom de banda de seguridad

                      // Boom de elementos varios
                      // Cadenas de 4 caracteres (XXXX)
                      $pdtoConsumoSelect = $link->query("
                        SELECT * FROM pdtoconsumo
                         WHERE cdgdiseno = '".$vntsoclote_cdgdiseno."' AND
                              (cdgsubproceso = '001' OR cdgsubproceso = '002' OR cdgsubproceso = '003')");

                      if ($pdtoConsumoSelect->num_rows > 0)
                      { while($regPdtoConsumo = $pdtoConsumoSelect->fetch_object())
                        { $link->query("
                          INSERT INTO progrequeboom
                            (cdgreque, cdglote, cdgsubproceso, cdgelemento, consumo, requerido, fchmovimiento)
                          VALUES
                            ('".$progreque_cdgreque."', '".$vntsoclote_cdglote."', '".$regPdtoConsumo->cdgsubproceso."', '".$regPdtoConsumo->cdgelemento."', '".$regPdtoConsumo->consumo."', '".($regPdtoConsumo->consumo*$vntsoclote_cantidad)."', NOW())"); }
                      }
                      // Fin de Boom de elementos varios

                      if ($vntsoclote_cdgempaque == 'Q')
                      { // Cadenas de 4 caracteres (XXXX)
                        $pdtoConsumoSelect = $link->query("
                          SELECT * FROM pdtoconsumo
                           WHERE cdgdiseno = '".$vntsoclote_cdgdiseno."' AND
                                (cdgsubproceso = '004' OR cdgsubproceso = '009Q')");

                        if ($pdtoConsumoSelect->num_rows > 0)
                        { while($regPdtoConsumo = $pdtoConsumoSelect->fetch_object())
                          { $link->query("
                            INSERT INTO progrequeboom
                              (cdgreque, cdglote, cdgsubproceso, cdgelemento, consumo, requerido, fchmovimiento)
                            VALUES
                              ('".$progreque_cdgreque."', '".$vntsoclote_cdglote."', '".$regPdtoConsumo->cdgsubproceso."', '".$regPdtoConsumo->cdgelemento."', '".$regPdtoConsumo->consumo."', '".($regPdtoConsumo->consumo*$vntsoclote_cantidad)."', NOW())"); }
                        }
                        // Fin de Boom de elementos para rollos
                      }

                      if ($vntsoclote_cdgempaque == 'C')
                      { // Cadenas de 4 caracteres (XXXX)
                        $pdtoConsumoSelect = $link->query("
                          SELECT * FROM pdtoconsumo
                           WHERE cdgdiseno = '".$vntsoclote_cdgdiseno."' AND
                                (cdgsubproceso = '005' OR cdgsubproceso = '009C')");

                        if ($pdtoConsumoSelect->num_rows > 0)
                        { while($regPdtoConsumo = $pdtoConsumoSelect->fetch_object())
                          { $link->query("
                            INSERT INTO progrequeboom
                              (cdgreque, cdglote, cdgsubproceso, cdgelemento, consumo, requerido, fchmovimiento)
                            VALUES
                              ('".$progreque_cdgreque."', '".$vntsoclote_cdglote."', '".$regPdtoConsumo->cdgsubproceso."', '".$regPdtoConsumo->cdgelemento."', '".$regPdtoConsumo->consumo."', '".($regPdtoConsumo->consumo*$vntsoclote_cantidad)."', NOW())"); }
                        }
                        // Fin de Boom de elementos para paquetes
                      }

                      // Fin de Boom de elementos varios
                    } // Fin de la búsqueda del diseño al que pertenece el producto
                   
                    // Boom de tintas
                    // Cadenas de 6 caracteres (XXXXXX)
                    $pdtoImpresionTntSelect = $link->query("
                      SELECT * FROM pdtoimpresiontnt
                       WHERE cdgimpresion = '".$vntsoclote_cdgproducto."'");

                    if ($pdtoImpresionTntSelect->num_rows > 0)
                    { while ($regPdtoImpresionTnt = $pdtoImpresionTntSelect->fetch_object())
                      { $link->query("
                          INSERT INTO progrequeboom
                            (cdgreque, cdglote, cdgsubproceso, cdgelemento, consumo, requerido, fchmovimiento)
                          VALUES
                            ('".$progreque_cdgreque."', '".$vntsoclote_cdglote."', '001', '".$regPdtoImpresionTnt->cdgtinta."', '".$regPdtoImpresionTnt->consumo."', '".($regPdtoImpresionTnt->consumo*$vntsoclote_cantidad)."', NOW())"); }
                    } // Fin del Boom de tintas
                  } // Fin del proceso de Boom de materia prima

                  break; } // Final de la busqueda
              }

              $msg_alert = 'La confirmación fue insertada.';

              break; }
          }

          $vntsoclote_cdglote = '';        
        }
      } else
      { $msg_alert = 'No cuentas con permisos de escritura en este modulo.'; }
    }

    if (substr($sistModulo_permiso,0,1) == 'r')
    { // Busqueda de tipos de empaque
      $vntsEmpaqueSelect = $link->query("
        SELECT * FROM vntsempaque 
         WHERE sttempaque = '1'
      ORDER BY idempaque");

      if ($vntsEmpaqueSelect->num_rows > 0)
      { $item = 0;

        while ($regPdtoEmpaque = $vntsEmpaqueSelect->fetch_object())
        { $item++;

          $vntsEmpaque_idempaque[$item] = $regPdtoEmpaque->idempaque;
          $vntsEmpaque_empaque[$item] = $regPdtoEmpaque->empaque;
          $vntsEmpaque_cdgempaque[$item] = $regPdtoEmpaque->cdgempaque; 

          $vntsEmpaques_empaque[$regPdtoEmpaque->cdgempaque] = $regPdtoEmpaque->empaque; }

        $nEmpaques = $vntsEmpaqueSelect->num_rows; }
      // Fin de la busqueda de tipos de empaque

      // Busqueda de ordenes de compra activas
      $vntsOCSelect = $link->query("
        SELECT vntsoc.oc,
               vntssucursal.sucursal,
               vntsoc.cdgoc
          FROM vntsoc,
               vntssucursal
         WHERE vntsoc.sttoc = '1' AND
               vntssucursal.cdgsucursal = vntsoc.cdgsucursal
      ORDER BY vntsoc.cdgoc DESC");

      if ($vntsOCSelect->num_rows > 0)
      { $item = 0;

        while ($regVntsOC = $vntsOCSelect->fetch_object())
        { $item++;

          $vntsOCs_oc[$item] = $regVntsOC->oc;
          $vntsOCs_sucursal[$item] = $regVntsOC->sucursal;
          $vntsOCs_cdgoc[$item] = $regVntsOC->cdgoc; 

          $vntsOC_oc[$regVntsOC->cdgoc] = $regVntsOC->oc; }

        $nOCs = $vntsOCSelect->num_rows; }
      // Fin de la busqueda de ordenes de compra activas  

      if ($_GET['cdglote']) 
      { // Buscar información de una confirmación de producto
        // cdglote -> Código de la confirmación de producto
        $vntsOCloteSelect = $link->query("
          SELECT * FROM vntsoclote
          WHERE cdglote = '".$_GET['cdglote']."'");

        if ($vntsOCloteSelect->num_rows > 0)
        { $regVntsOClote = $vntsOCloteSelect->fetch_object();

          $vntsoclote_cdgoc = $regVntsOClote->cdgoc;
          $vntsoclote_cdgproducto = $regVntsOClote->cdgproducto;
          $vntsoclote_cantidad = $regVntsOClote->cantidad;
          $vntsoclote_surtido = $regVntsOClote->surtido;
          $vntsoclote_fchembarque = $regVntsOClote->fchembarque;
          $vntsoclote_fchentrega = $regVntsOClote->fchentrega;
          $vntsoclote_cdgempaque = $regVntsOClote->cdgempaque;
          $vntsoclote_referencia = $regVntsOClote->referencia;
          $vntsoclote_cdglote = $regVntsOClote->cdglote; 
          $vntsoclote_sttlote = $regVntsOClote->sttlote; 

          // Actualizar el status de una confirmación de producto          
          // cdglote -> Código de la confirmación de producto
          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($vntsoclote_sttlote == '1')
              { $vntsoclote_newsttlote = '0'; }
              
              if ($vntsoclote_sttlote == '0')
              { $vntsoclote_newsttlote = '1'; }
              
              if ($vntsoclote_newsttlote != '')
              { 
                $link->query("
                  UPDATE vntsoclote
                     SET sttlote = '".$vntsoclote_newsttlote."'
                   WHERE cdglote = '".$vntsoclote_cdglote."'");

                if ($link->affected_rows > 0)
                { $msg_alert = 'La confirmación de la Orden de Compra fue ACTUALIZADA satisfactoriamente.'; 

                  $link->query("
                    INSERT INTO krdxoclote
                      (cdglote, cdgproducto, cantidad, fchembarque, fchentrega, cdgempaque, referencia, cdgusuario, operacion, fchmovimiento)
                    VALUES
                      ('".$vntsoclote_cdglote."', '".$vntsoclote_cdgproducto."', '".$vntsoclote_cantidad."', '".$vntsoclote_fchembarque."', '".$vntsoclote_fchentrega."', '".$vntsoclote_cdgempaque."', '".$vntsoclote_referencia."', '".$_SESSION['cdgusuario']."', 'UPDAT".$vntsoclote_newsttlote."', NOW())");              
                } else
                { $msg_alert = 'La confirmación de la Orden de Compra NO fue ACTUALIZADA.'; }
                
              } else
              { $msg_alert = 'La confirmación de la Orden de Compra que seleccionaste, no tiene permitido cambiar de status.'; }
            } else
            { $msg_alert = 'No cuentas con permisos de reescritura en este modulo.'; }
          } // Fin de la actualización del status de una confirmación de producto

          // Eliminar una confirmación de producto
          // cdglote -> Código de la confirmación de producto 
          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $vntsEmbarqueSelect = $link->query("
                SELECT * FROM vntsembarque
                 WHERE cdglote = '".$vntsoclote_cdglote."'");

              if ($vntsEmbarqueSelect->num_rows > 0)
              { $msg_alert = 'La confirmación de la Orden de Compra NO fue eliminada por que existen embarques asociados.'; }
              else
              { $link->query("
                  DELETE FROM vntsoclote
                   WHERE cdglote = '".$vntsoclote_cdglote."' AND
                         sttlote = '0'");

                if ($link->affected_rows > 0)
                { $msg_alert = 'La confirmación de la Orden de Compra fue ELIMINADA satisfactoriamente.'; 

                  $link->query("
                    INSERT INTO krdxoclote
                      (cdglote, cdgproducto, cantidad, fchembarque, fchentrega, cdgempaque, referencia, cdgusuario, operacion, fchmovimiento)
                    VALUES
                      ('".$vntsoclote_cdglote."', '".$vntsoclote_cdgproducto."', '".$vntsoclote_cantidad."', '".$vntsoclote_fchembarque."', '".$vntsoclote_fchentrega."', '".$vntsoclote_cdgempaque."', '".$vntsoclote_referencia."', '".$_SESSION['cdgusuario']."', 'DELETE', NOW())");
                } else
                { $msg_alert = 'La confirmación de la Orden de Compra NO fue eliminada.'; }
              }
            } else
            { $msg_alert = 'No cuentas con permisos para remover en este modulo.'; }
            
            $vntsoclote_cdglote = ''; } 
          // Fin de la eliminación de una confirmación de producto
        } // Fin de la busqueda de una confirmación de producto
      }

      // Busqueda de productos compatibles con la orden de compra seleccionada
      $pdtoImpresionSelect = $link->query("
        SELECT pdtodiseno.diseno,
               pdtoimpresion.impresion, 
               pdtoimpresion.cdgimpresion
          FROM pdtodiseno, 
               pdtoimpresion, 
               pdtoproducto,
               vntsocreque
         WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
              (pdtodiseno.sttdiseno = '1' AND
               pdtoimpresion.sttimpresion = '1') AND
              (pdtoimpresion.cdgproducto = pdtoproducto.cdgproducto AND
               pdtoimpresion.cdgproducto = vntsocreque.cdgproducto AND
               vntsocreque.cdgoc = '".$vntsoclote_cdgoc."')");

      if ($pdtoImpresionSelect->num_rows > 0)
      { $item = 0;
        while ($regQuery = $pdtoImpresionSelect->fetch_object())
        { $item++;
          
          $pdtoImpresion_cdgimpresion[$item] = $regQuery->cdgimpresion; 
          $pdtoImpresion_impresion[$item] = $regQuery->diseno.' | '.$regQuery->impresion;

          $pdtoImpresiones_impresion[$regQuery->cdgimpresion] = $regQuery->diseno.' | '.$regQuery->impresion; }

         $nProductos = $pdtoImpresionSelect->num_rows; }
      // Fin de la busqueda de productos compatibles con la orden de compra seleccionada

      // Busqueda de requerimientos contenidos en la orden de compra
      // cdgoc -> Código de la orden de compra
      $vntsOCRequeSelect = $link->query("
        SELECT pdtoproducto.cdgproducto,
               pdtoproducto.producto,
               vntsocreque.cantidad,
               vntsocreque.referencia,
               vntsocreque.cdgreque,
               vntsocreque.sttreque
          FROM pdtoproducto,
               vntsocreque
         WHERE pdtoproducto.cdgproducto = vntsocreque.cdgproducto AND
               vntsocreque.cdgoc = '".$vntsoclote_cdgoc."'"); 

      if ($vntsOCRequeSelect->num_rows > 0)
      { $item = 0;

        while ($regvntsocreque = $vntsOCRequeSelect->fetch_object())
        { $item++;

          $vntsocreques_producto[$item] = $regvntsocreque->producto;
          $vntsocreques_cdgproducto[$item] = $regvntsocreque->cdgproducto;
          $vntsocreques_empaque[$item] = $regvntsocreque->empaque;
          $vntsocreques_cantidad[$item] = $regvntsocreque->cantidad;
          $vntsocreques_saldo[$item] = $regvntsocreque->cantidad;
          $vntsocreques_referencia[$item] = $regvntsocreque->referencia;
          $vntsocreques_cdgreque[$item] = $regvntsocreque->cdgreque;
          $vntsocreques_sttreque[$item] = $regvntsocreque->sttreque; 

          // Busqueda de lotes confirmados en la orden de compra
          // cdgoc -> Código de la orden de compra
          // cdgproducto -Código del producto
          $vntsOCLoteSelect = $link->query("
            SELECT vntsoclote.cdgproducto,
                   vntsoclote.cantidad,
                   vntsoclote.surtido,
                   vntsoclote.cdgempaque,
                   vntsoclote.fchembarque,
                   vntsoclote.fchentrega,
                   vntsoclote.cdglote,
                   vntsoclote.sttlote
              FROM pdtoproducto,
                   vntsocreque,
                   vntsoclote,
                   pdtoimpresion
             WHERE pdtoproducto.cdgproducto = vntsocreque.cdgproducto AND
                   vntsocreque.cdgoc = vntsoclote.cdgoc AND
                   vntsoclote.cdgoc = '".$vntsoclote_cdgoc."' AND
                   vntsocreque.cdgproducto = pdtoimpresion.cdgproducto AND
                   pdtoimpresion.cdgimpresion = vntsoclote.cdgproducto AND
                   pdtoproducto.cdgproducto = '".$vntsocreques_cdgproducto[$item]."'");

          if ($vntsOCLoteSelect->num_rows > 0)
          { $subItem = 0;

            while ($regVntsOCLote = $vntsOCLoteSelect->fetch_object())
            { $subItem++;

              $vntsoclotes_cdgproducto[$item][$subItem] = $regVntsOCLote->cdgproducto;
              $vntsoclotes_cantidad[$item][$subItem] = $regVntsOCLote->cantidad;
              $vntsoclotes_surtido[$item][$subItem] = $regVntsOCLote->surtido;
              $vntsoclotes_cdgempaque[$item][$subItem] = $regVntsOCLote->cdgempaque;
              $vntsoclotes_fchembarque[$item][$subItem] = $regVntsOCLote->fchembarque;
              $vntsoclotes_fchentrega[$item][$subItem] = $regVntsOCLote->fchentrega;
              $vntsoclotes_cdglote[$item][$subItem] = $regVntsOCLote->cdglote;
              $vntsoclotes_sttlote[$item][$subItem] = $regVntsOCLote->sttlote; 

              $vntsoclotess_cantidad[$item] += $regVntsOCLote->cantidad; 

              $vntsocreques_saldo[$item] -= $regVntsOCLote->cantidad; }
            
            $nConfirmaciones[$item] = $subItem; }
          //Fin de la busqueda de lotes confirmados en la orden de compra
        }

        $nRequerimientos = $item; }
       // Fin de la busqueda de requerimientos de la orden de compra
    } else
    { $msg_alert = 'No cuentas con permisos de lectura en este modulo.'; }

    echo '
      <div class="bloque">
        <form id="formOCLote" name="formOCLote" method="POST" action="vntsOClote.php" />
          <input type="hidden" id="hddnCdgLote" name="hddnCdgLote" value="'.$vntsoclote_cdglote.'" />

          <article class="subbloque">
            <label class="modulo_nombre">Confirmaciones de producto por orden de compra</label>
          </article>
          <a href="ayuda.php#Confirmaciones">'.$_help_blue.'</a>

          <section class="subbloque">
            <article>
              <label ><a href="vntsOC.php?cdgoc='.$vntsoclote_cdgoc.'">O.C.</a> <b>'.$vntsOC_oc[$vntsoclote_cdgoc].'</b></label><br/>
              <select id="slctCdgOC" name="slctCdgOC" onchange="document.formOCLote.submit()">
                <option value="">-</option>';

    for ($item = 1; $item <= $nOCs; $item++) 
    { echo '
                <option value="'.$vntsOCs_cdgoc[$item].'"';
          
      if ($vntsoclote_cdgoc == $vntsOCs_cdgoc[$item]) 
      { echo ' selected="selected"'; }

      echo '>'.$vntsOCs_sucursal[$item].' | '.$vntsOCs_oc[$item].'</option>'; }
    
    echo '
              </select>
            </article>

            <article>
              <label><a href="../sm_producto/pdtoImpresion.php?cdgimpresion='.$vntsoclote_cdgproducto.'">Producto</a></label><br/>
              <select id="slctCdgProducto" name="slctCdgProducto">
                <option value="">Selecciona una opcion</option>';

    // Generador de combo de productos    
    for ($item=1; $item<=$nProductos; $item++) 
    { echo '
                <option value="'.$pdtoImpresion_cdgimpresion[$item].'"';
            
      if ($vntsoclote_cdgproducto == $pdtoImpresion_cdgimpresion[$item]) 
      { echo ' selected="selected"'; }

      echo '>'.$pdtoImpresion_impresion[$item].'</option>'; }
    
    echo '
              </select>
            </article>

            <article>
              <label>Empaque</label><br/>
              <select id="slctCdgEmpaque" name="slctCdgEmpaque">';

    for ($idEmpaque = 1; $idEmpaque <= $nEmpaques; $idEmpaque++) 
    { echo '
                <option value="'.$vntsEmpaque_cdgempaque[$idEmpaque].'"';
            
      if ($vntsoclote_cdgempaque == $vntsEmpaque_cdgempaque[$idEmpaque]) 
      { echo ' selected="selected"'; }

      echo '>'.$vntsEmpaque_empaque[$idEmpaque].'</option>'; }
    
    echo '
              </select>
            </article>

            <article>
              <label>Cantidad</label><br/>
              <input type="text" class="input_numero" id="textCantidad" name="textCantidad" value="'.$vntsoclote_cantidad.'" required />
            </article>

            <article>
              <label>Referencia</label><br/>
                <input type="text" id="textReferencia" name="textReferencia" value="'.$vntsoclote_referencia.'" />
            </article>

            <article>
              <label>Fecha embarque</label><br/>
              <input type="date" id="dateFchEmbarque" name="dateFchEmbarque" value="'.$vntsoclote_fchembarque.'" required /></td>
            </article>

            <article>
              <label>Fecha entrega</label><br/>
              <input type="date" id="dateFchEntrega" name="dateFchEntrega" value="'.$vntsoclote_fchentrega.'" required />
            </article>

            <article><br/>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>
          </section>

          <label for="label_oclote"></label>
        </form>
      </div>';

    if ($nRequerimientos > 0)
    { for ($item=1; $item<=$nRequerimientos; $item++)
      { echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado"><a href="vntsOCreque.php?cdgreque='.$vntsocreques_cdgreque[$item].'">'.$vntsocreques_producto[$item].'</a></label>
        </article>
        <label>Requerido <strong>'.number_format($vntsocreques_cantidad[$item],3,'.','').'</strong> | Saldo <strong>'.number_format($vntsocreques_saldo[$item],3,'.','').'</strong></label>';

        for ($subItem=1; $subItem<=$nConfirmaciones[$item]; $subItem++)
        { echo '
        <section class="listado">
          <article style="vertical-align:top">';

          if ((int)$vntsoclotes_sttlote[$item][$subItem] > 0)
          { echo '
            <a href="vntsOClote.php?cdglote='.$vntsoclotes_cdglote[$item][$subItem].'">'.$_search.'</a>
            <a href="vntsSurtido.php?cdglote='.$vntsoclotes_cdglote[$item][$subItem].'">'.$_delivery.'</a>
            <a href="vntsOClote.php?cdglote='.$vntsoclotes_cdglote[$item][$subItem].'&proceso=update">'.$_power_blue.'</a>'; }
          else
          { echo '
            <a href="vntsOClote.php?cdglote='.$vntsoclotes_cdglote[$item][$subItem].'&proceso=delete">'.$_recycle_bin.'</a>
            <a href="vntsOClote.php?cdglote='.$vntsoclotes_cdglote[$item][$subItem].'&proceso=update">'.$_power_black.'</a>'; }

          echo '
          </article>

          <article style="text-align:right">
            <label>Producto</label><br/>
            <label>Tipo de empaque</label><br/>
            <label><strong>'.$vntsoclotes_cantidad[$item][$subItem].'</strong></label><br/>
            <label><strong>'.$vntsoclotes_surtido[$item][$subItem].'</strong></label><br/>
            <label>Fecha de embarque</label><br/>
            <label>Fecha de entrega</label><br/>
          </article>

          <article>
            <label><em>'.$pdtoImpresiones_impresion[$vntsoclotes_cdgproducto[$item][$subItem]].'</em></label><br/>
            <label><em>'.$vntsEmpaques_empaque[$vntsoclotes_cdgempaque[$item][$subItem]].'</em></label><br/>
            <label>Confirmado</label><br/>
            <label>Surtido</label><br/>
            <label>'.$vntsoclotes_fchembarque[$item][$subItem].'</label><br/>
            <label>'.$vntsoclotes_fchentrega[$item][$subItem].'</label>
          </article>
        </section>'; }

        echo '
      </div>'; }  
    }

    if ($msg_alert != '')
    { echo '
      <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
      <div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }  

 ?>
    </div>
  </body>
</html>