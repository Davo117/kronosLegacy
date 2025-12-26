<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
    <title>Buscador (Rastreador de codigos)</title>
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '50090';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    if ($_POST['button_buscar'])
    { if ($_POST['text_codigo']) 
      { $prodBuscador_codigo = $_POST['text_codigo']; }
    }

    if (substr($sistModulo_permiso,0,2) == 'rw')
    { if ($_POST['button_baja'])
      { if ($_POST['text_codigo'])
        { $prodBaja_codigo = $_POST['text_codigo']; 
          
          $link_mysqli = conectar();
          $link_mysqli->query("
            UPDATE prodpaquete
            SET sttpaquete = 'C',
              obs = CONCAT('Sobrante/Merma ',fchmovimiento,' ".$_POST['txt_observacion']." ', 'baja: ',NOW(), obs)
            WHERE cdgpaquete = '".$prodBaja_codigo."' AND
                  sttpaquete = '1' AND
                  cdgempaque = ''");

          if ($link_mysqli->affected_rows > 0)
          { $link_mysqli = conectar();
            $link_mysqli->query("
              INSERT INTO prodpaqueteope
               (cdgpaquete, cdgoperacion, cdgempleado, cdgmaquina, fchoperacion, fchmovimiento)
              VALUES
               ('".$prodBaja_codigo."', '5000C', '".$_SESSION['cdgusuario']."', '0000C', '".$fchoperacion."', NOW())");

                    echo '<script>alert("Paquete desactivado.");</script>'; }
          else
          { $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE prodrollo
              SET sttrollo = 'C',
                obs = CONCAT('Merma ',fchmovimiento,' ".$_POST['txt_observacion']." ', 'baja: ',NOW())
              WHERE cdgrollo = '".$prodBaja_codigo."' AND
                    (sttrollo != '5' OR sttrollo != '9') AND
                    cdgempaque = ''");

            if ($link_mysqli->affected_rows > 0)
            { $link_mysqli = conectar();
              $link_mysqli->query("
                INSERT INTO prodrolloope
                 (cdgrollo, cdgoperacion, cdgempleado, cdgmaquina, longitud, peso, fchoperacion, fchmovimiento)
                VALUES
                 ('".$prodBaja_codigo."', '4000C', '".$_SESSION['cdgusuario']."', '0000C', '".$prodCorte_longitud."', '".$prodCorte_peso."', '".$fchoperacion."', NOW())");

              echo '<script>alert("Rollo desactivado.");</script>'; }
          }
        }
      }
    } else
    { echo '<div align="center" style="align-text:justify"><h1>Este m&oacute;dulo esta reservado para usuarios autorizados.</h1></div>'; }

    if ($_GET['cdgempaque'])
    { $prodBuscador_codigo = $_GET['cdgempaque']; }

    echo '
      <form id="form_prodBuscador" name="form_prodBuscador" method="post" action="prodMerma.php"/>
        <table align="center">
          <thead>
            <tr><th>',utf8_decode('Cancelación de códigos').'</th></tr>
          </thead>
          <tbody>
            <tr><td align="center">C&oacute;digo <br/>
                <input type="text" style="width:120px" maxlength="12" id="text_codigo" name="text_codigo" value="'.$prodBuscador_codigo.'" autofocus required /></td></tr>
          </tbody>
          <tfoot>';

    if ($_POST['button_buscar'])
    { echo '
            <tr><th align="right"><input type="submit" id="button_baja" name="button_baja" value="Desactivar" autofocus /></th></tr>'; 
    } else
    { echo '
            <tr><th align="right"><input type="submit" id="button_buscar" name="button_buscar" value="Buscar" autofocus /></th></tr>'; }
    echo '
          <t/foot>
        </table>
      </form><br/>';

    if ($prodBuscador_codigo != '')
    { if (substr($prodBuscador_codigo,-1) == '*')
      { $link_mysqli = conectar();
        $vntsEmbarqueSelect = $link_mysqli->query("
          SELECT vntsembarque.cdgembarque,
            vntssucursal.sucursal,
            vntscliente.cliente,
            pdtoimpresion.impresion,
            vntsembarque.referencia,
            vntsembarque.fchembarque
          FROM vntsembarque,
            vntssucursal,
            vntscliente,
            pdtoimpresion
          WHERE vntsembarque.cdgembarque = '".substr($prodBuscador_codigo,0,-1)."' AND
            vntsembarque.cdgsucursal = vntssucursal.cdgsucursal AND
            vntssucursal.cdgcliente = vntscliente.cdgcliente AND
            vntsembarque.cdgproducto = pdtoimpresion.cdgimpresion");

        if ($vntsEmbarqueSelect->num_rows > 0)
        { $regVntsEmbarque = $vntsEmbarqueSelect->fetch_object();

          echo '
      <table align="center">
        <thead>
          <tr><th>Embarque encontrado</th></tr>
        </thead>
        <tbody>
          <tr><td>Embarque: <b>'.$regVntsEmbarque->cdgembarque.'</b></td></tr>
          <tr><td>Cliente: <b>'.$regVntsEmbarque->cliente.'</b></td></tr>
          <tr><td>Sucursal: <b>'.$regVntsEmbarque->sucursal.'</b></td></tr>
          <tr><td>Producto: <b>'.$regVntsEmbarque->impresion.'</b></td></tr>
          <tr><td>Fecha: <b>'.$regVntsEmbarque->fchembarque.'</b></td></tr>
          <tr><td>Referencia: <b>'.$regVntsEmbarque->referencia.'</b></td></tr>
        </tbody>
        <tfoot>
          <tr><td></td></tr>
        </tfoot>
      </table><br/>';

          $link_mysqli = conectar();
          $alptEmpaqueSelect = $link_mysqli->query("
            SELECT * FROM alptempaque
            WHERE cdgembarque = '".$regVntsEmbarque->cdgembarque."'");

          if ($alptEmpaqueSelect->num_rows > 0)
          { $num_empaque = 1;

            while ($regAlptEmpaque = $alptEmpaqueSelect->fetch_object())
            { $prodBuscador_cdgempaque[$num_empaque] = $regAlptEmpaque->cdgempaque;
              $prodBuscador_tpoempaque[$num_empaque] = $regAlptEmpaque->tpoempaque;
              $prodBuscador_empaque[$num_empaque] = $regAlptEmpaque->tpoempaque.$regAlptEmpaque->noempaque;

              $num_empaque++; }

            $num_empaques = $alptEmpaqueSelect->num_rows;
            $num_columnas = number_format(sqrt($num_empaques),0);

            if ($num_columnas > 9) { $num_columnas = 9; }

            $num_renglones = number_format(($num_empaques/$num_columnas),0);

            if ($num_empaques > ($num_columnas*$num_renglones)) { $num_renglones++; }

            echo '

      <table align="center">
        <thead>
          <tr><th colspan="'.$num_columnas.'">Detalle de los empaques por embarque</th></tr>
        </thead>
        <tbody>';

            $num_empaque = 1;
            for ($num_renglon = 1; $num_renglon <= $num_renglones; $num_renglon++)
            { echo '
          <tr>';

              for ($num_columna = 1; $num_columna <= $num_columnas; $num_columna++)
              { if ($prodBuscador_empaque[$num_empaque] != '')
                { echo '
            <td align="right"><b>'.$prodBuscador_empaque[$num_empaque].'</b> ';

                  if ($prodBuscador_tpoempaque[$num_empaque] == 'Q')
                  { echo '
              <a href="prodBuscador.php?cdgempaque='.$prodBuscador_cdgempaque[$num_empaque].'" target="_blank">'.$png_search.'</a>
              <a href="../sm_almacenpt/pdf/alptEmpaqueBCE.php?cdgempaque='.$prodBuscador_cdgempaque[$num_empaque].'" target="_blank">'.$png_barcode.'</a>'; }

                  if ($prodBuscador_tpoempaque[$num_empaque] == 'C')
                  { echo '
              <a href="prodBuscador.php?cdgempaque='.$prodBuscador_cdgempaque[$num_empaque].'" target="_blank">'.$png_search.'</a>
              <a href="../sm_almacenpt/pdf/alptEmpaqueBCEC.php?cdgempaque='.$prodBuscador_cdgempaque[$num_empaque].'" target="_blank">'.$png_barcode.'</a>'; }

                  echo '</td>'; }

                $num_empaque++;
              }

              echo '
          </tr>';
            }

            echo '
        </tbody>
        <tfoot>
          <tr><td colspan="'.$num_columnas.'"></td></tr>
        </tfoot>
      </table><br/>';

          }
        }
      } 
      else
      { if (strlen($prodBuscador_codigo) == 6)
        { $link_mysqli = conectar();
          $pdtoImpresionSelect = $link_mysqli->query("
            SELECT * FROM pdtoimpresion
            WHERE cdgimpresion = '".$prodBuscador_codigo."'");
          
          if ($pdtoImpresionSelect->num_rows > 0)
          { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();
          
            echo '
      <table align="center">
        <thead>
          <tr><th>Producto encontrado</th></tr>
        </thead>
        <tbody>        
          <tr><td>Producto: <b>'.$regPdtoImpresion->impresion.'</b></td></tr>        
        </tbody>
        <tfoot>
          <tr><td></td></tr>
        </tfoot>
      </table><br/>';
      
            $link_mysqli = conectar();
            $alptEmpaqueSelect = $link_mysqli->query("
              SELECT * FROM alptempaque
              WHERE cdgembarque = '' AND
                cdgproducto = '".$prodBuscador_codigo."'");

            if ($alptEmpaqueSelect->num_rows > 0)
            { $num_empaque = 1;

              while ($regAlptEmpaque = $alptEmpaqueSelect->fetch_object())
              { $prodBuscador_cdgempaque[$num_empaque] = $regAlptEmpaque->cdgempaque;
                $prodBuscador_tpoempaque[$num_empaque] = $regAlptEmpaque->tpoempaque;
                $prodBuscador_empaque[$num_empaque] = $regAlptEmpaque->tpoempaque.$regAlptEmpaque->noempaque;

                $num_empaque++; }

              $num_empaques = $alptEmpaqueSelect->num_rows;
              $num_columnas = number_format(sqrt($num_empaques),0);

              if ($num_columnas > 9) { $num_columnas = 9; }

              $num_renglones = number_format(($num_empaques/$num_columnas),0);

              if ($num_empaques > ($num_columnas*$num_renglones)) { $num_renglones++; }

              echo '

        <table align="center">
          <thead>
            <tr><th colspan="'.$num_columnas.'">Empaques disponibles por producto</th></tr>
          </thead>
          <tbody>';

              $num_empaque = 1;
              for ($num_renglon = 1; $num_renglon <= $num_renglones; $num_renglon++)
              { echo '
            <tr>';

                for ($num_columna = 1; $num_columna <= $num_columnas; $num_columna++)
                { if ($prodBuscador_empaque[$num_empaque] != '')
                  { echo '
              <td align="right"><b>'.$prodBuscador_empaque[$num_empaque].'</b> ';

                    if ($prodBuscador_tpoempaque[$num_empaque] == 'Q')
                    { echo '
                <a href="prodBuscador.php?cdgempaque='.$prodBuscador_cdgempaque[$num_empaque].'" target="_blank">'.$png_search.'</a>
                <a href="../sm_almacenpt/pdf/alptEmpaqueBCE.php?cdgempaque='.$prodBuscador_cdgempaque[$num_empaque].'" target="_blank">'.$png_barcode.'</a>'; }

                    if ($prodBuscador_tpoempaque[$num_empaque] == 'C')
                    { echo '
                <a href="prodBuscador.php?cdgempaque='.$prodBuscador_cdgempaque[$num_empaque].'" target="_blank">'.$png_search.'</a>
                <a href="../sm_almacenpt/pdf/alptEmpaqueBCEC.php?cdgempaque='.$prodBuscador_cdgempaque[$num_empaque].'" target="_blank">'.$png_barcode.'</a>'; }

                    echo '</td>'; }

                  $num_empaque++;
                }

                echo '
            </tr>';
              }

              echo '
          </tbody>
          <tfoot>
            <tr><td colspan="'.$num_columnas.'"></td></tr>
          </tfoot>
        </table><br/>';

            }      
          }
        }  
        
        if (strlen($prodBuscador_codigo) == 10)
        { $link_mysqli = conectar();
          $alptEmpaqueSelect = $link_mysqli->query("
            SELECT alptempaque.cdgempaque,
              alptempaque.tpoempaque,
              alptempaque.noempaque,
              pdtoimpresion.impresion,
              rechempleado.empleado
            FROM alptempaque, 
              pdtoimpresion,
              rechempleado
            WHERE alptempaque.cdgempaque = '".$prodBuscador_codigo."' AND
              alptempaque.cdgproducto = pdtoimpresion.cdgimpresion AND
              alptempaque.cdgempleado = rechempleado.cdgempleado");
            
          if ($alptEmpaqueSelect->num_rows > 0)
          { $regAlptEmpaque = $alptEmpaqueSelect->fetch_object(); 
          
            echo '
            
      <table align="center">
        <thead>
          <tr><th>Empaque encontrado</th></tr>
        </thead>
        <tbody>
          <tr><td>Empaque: <b>'.$regAlptEmpaque->cdgempaque.'</b></td></tr>
          <tr><td>Producto: <b>'.$regAlptEmpaque->impresion.'</b></td></tr>
          <tr><td>Empleado: <b>'.$regAlptEmpaque->empleado.'</b></td></tr>
        </tbody>
        <tfoot>
          <tr><td></td></tr>
        </tfoot>
      </table><br/>';        
          
            if ($regAlptEmpaque->tpoempaque == 'C')
            { $link_mysqli = conectar();
              $alptContenidoEmpaqueSelect = $link_mysqli->query("
                SELECT alptempaquep.cdgpaquete AS codigo,                
                  CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo,'-',prodpaquete.paquete) AS noop                
                FROM alptempaquep,
                  prodpaquete,
                  prodrollo,
                  prodbobina,
                  prodlote
                WHERE alptempaquep.cdgempaque = '".$regAlptEmpaque->cdgempaque."' AND
                 (alptempaquep.cdgpaquete = prodpaquete.cdgpaquete AND
                  prodpaquete.cdgrollo = prodrollo.cdgrollo AND
                  prodrollo.cdgbobina = prodbobina.cdgbobina AND
                  prodbobina.cdglote = prodlote.cdglote)");  }
            
            if ($regAlptEmpaque->tpoempaque == 'Q')
            { $link_mysqli = conectar();
              $alptContenidoEmpaqueSelect = $link_mysqli->query("
                SELECT alptempaquer.cdgrollo AS codigo,
                  CONCAT(' / R',alptempaquer.nocontrol) AS nocontrol,
                  CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) AS noop,
                  CONCAT(prodrollo.longitud,' mts') AS longitud,
                  CONCAT(prodrollo.peso,' kgs') AS peso
                FROM alptempaquer,
                  prodrollo,
                  prodbobina,
                  prodlote
                WHERE alptempaquer.cdgempaque = '".$regAlptEmpaque->cdgempaque."' AND
                 (alptempaquer.cdgrollo = prodrollo.cdgrollo AND
                  prodrollo.cdgbobina = prodbobina.cdgbobina AND
                  prodbobina.cdglote = prodlote.cdglote)"); 
            }
            
            if ($alptContenidoEmpaqueSelect->num_rows > 0)
            { echo '
      <table align="center">
        <thead>
          <tr><th colspan="4">Contenido del empaque  '.$regAlptEmpaque->tpoempaque.$regAlptEmpaque->noempaque.'</th></tr>
        </thead>
        <tbody>';
              
              while ($regContenidoEmpaque = $alptContenidoEmpaqueSelect->fetch_object())
              { echo '
          <tr align="right"><td>'.$regContenidoEmpaque->noop.' <b>'.$regContenidoEmpaque->nocontrol.'</b>
              <a href="prodBuscador.php?cdgempaque='.$regContenidoEmpaque->codigo.'" target="_blank">'.$png_search.'</a></td>          
            <td>'.$regContenidoEmpaque->longitud.'</td>
            <td>'.$regContenidoEmpaque->peso.'</td>
            <td>'.$regContenidoEmpaque->codigo.'</td></tr>';
                
              }

            echo '
        </tbody>
        <tfoot>
          <tr><td colspan="4"></td></tr>
        </tfoot>
      </table><br/>';
      
            }              
          }        
        }
        
        if (strlen($prodBuscador_codigo) == 12)
        { // Catalogo de empleados
          $link_mysqli = conectar();
          $rechEmpleadoSelect = $link_mysqli->query("
          SELECT idempleado,
            empleado,
            cdgempleado
          FROM rechempleado");

          while ($regRechEmpleado = $rechEmpleadoSelect->fetch_object())
          { $prodBuscador_idempleado[$regRechEmpleado->cdgempleado] = $regRechEmpleado->idempleado;
          $prodBuscador_empleado[$regRechEmpleado->cdgempleado] = $regRechEmpleado->empleado; }

          $rechEmpleadoSelect->close;
          // **** // **** // **** // **** // **** //

          // Catalogo de maquinas
          $link_mysqli = conectar();
          $prodMaquinaSelect = $link_mysqli->query("
          SELECT idmaquina,
            maquina,
            cdgmaquina
          FROM prodmaquina");

          while ($regProdMaquina = $prodMaquinaSelect->fetch_object())
          { $prodBuscador_idmaquina[$regProdMaquina->cdgmaquina] = $regProdMaquina->idmaquina;
          $prodBuscador_maquina[$regProdMaquina->cdgmaquina] = $regProdMaquina->maquina; }

          $prodMaquinaSelect->close;
          // **** // **** // **** // **** // **** //

          $link_mysqli = conectar();
          $prodPaqueteSelect = $link_mysqli->query("
            SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo,'-',prodpaquete.paquete) AS noop,
              prodrollo.amplitud,
              prodpaquete.cdgpaquete,
              prodpaquete.obs,
              prodrollo.cdgrollo,
              prodbobina.cdgbobina,
              prodlote.cdglote,
              pdtoimpresion.impresion
            FROM prodpaquete,
              prodrollo,
              prodbobina,
              prodlote,
              pdtoimpresion
            WHERE (prodpaquete.cdgrollo = prodrollo.cdgrollo AND
              prodrollo.cdgbobina = prodbobina.cdgbobina AND
              prodbobina.cdglote = prodlote.cdglote AND
              prodpaquete.cdgpaquete = '".$prodBuscador_codigo."') AND
             (prodlote.cdgproducto = pdtoimpresion.cdgimpresion)");

          if ($prodPaqueteSelect->num_rows > 0)
          { $regProdPaquete = $prodPaqueteSelect->fetch_object();

            $prodBuscador_noop = $regProdPaquete->noop;
            $prodBuscador_impresion = $regProdPaquete->impresion;
            $prodBuscador_mezcla = $regProdPaquete->mezcla;
            $prodBuscador_amplitud = $regProdPaquete->amplitud;
            $prodBuscador_cdglote = $regProdPaquete->cdglote;
            $prodBuscador_cdgbobina = $regProdPaquete->cdgbobina;
            $prodBuscador_cdgrollo = $regProdPaquete->cdgrollo;
            $prodBuscador_obs = $regProdPaquete->obs;
            $prodBuscador_cdgpaquete = $regProdPaquete->cdgpaquete;
          } else
          { $link_mysqli = conectar();
            $prodRolloSelect = $link_mysqli->query("
              SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) AS noop,
                prodrollo.amplitud,
                prodrollo.obs,
                prodrollo.cdgrollo,
                prodbobina.cdgbobina,
                prodlote.cdglote,
                pdtoimpresion.impresion
              FROM prodrollo,
                prodbobina,
                prodlote,
                pdtoimpresion
              WHERE (prodrollo.cdgbobina = prodbobina.cdgbobina AND
                prodbobina.cdglote = prodlote.cdglote AND
                prodrollo.cdgrollo = '".$prodBuscador_codigo."') AND
               (prodlote.cdgproducto = pdtoimpresion.cdgimpresion)");

            if ($prodRolloSelect->num_rows > 0)
            { $regProdRollo = $prodRolloSelect->fetch_object();

              $prodBuscador_noop = $regProdRollo->noop;
              $prodBuscador_impresion = $regProdRollo->impresion;
              $prodBuscador_mezcla = $regProdRollo->mezcla;
              $prodBuscador_amplitud = $regProdRollo->amplitud;
              $prodBuscador_obs = $regProdRollo->obs;
              $prodBuscador_cdglote = $regProdRollo->cdglote;
              $prodBuscador_cdgbobina = $regProdRollo->cdgbobina;
              $prodBuscador_cdgrollo = $regProdRollo->cdgrollo;
            } else
            { $link_mysqli = conectar();
              $prodBobinaSelect = $link_mysqli->query("
                SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina) AS noop,
                  prodbobina.amplitud,
                  prodbobina.cdgbobina,
                  prodlote.cdglote,
                  pdtoimpresion.impresion
                FROM prodbobina,
                  prodlote,
                  pdtoimpresion
                WHERE (prodbobina.cdglote = prodlote.cdglote AND
                  prodbobina.cdgbobina = '".$prodBuscador_codigo."') AND
                 (prodlote.cdgproducto = pdtoimpresion.cdgimpresion)");

              if ($prodBobinaSelect->num_rows > 0)
              { $regProdBobina = $prodBobinaSelect->fetch_object();

                $prodBuscador_noop = $regProdBobina->noop;
                $prodBuscador_impresion = $regProdBobina->impresion;
                $prodBuscador_mezcla = $regProdBobina->mezcla;
                $prodBuscador_amplitud = $regProdBobina->amplitud;
                $prodBuscador_cdglote = $regProdBobina->cdglote;
                $prodBuscador_cdgbobina = $regProdBobina->cdgbobina;
              } else
              { $link_mysqli = conectar();
                $prodLoteSelect = $link_mysqli->query("
                  SELECT prodlote.noop,
                    prodlote.amplitud,
                    prodlote.cdglote,
                    pdtoimpresion.impresion
                  FROM prodlote,
                    pdtoimpresion
                  WHERE (prodlote.cdglote = '".$prodBuscador_codigo."') AND
                   (prodlote.cdgproducto = pdtoimpresion.cdgimpresion)");

                if ($prodLoteSelect->num_rows > 0)
                { $regProdLote = $prodLoteSelect->fetch_object();

                  $prodBuscador_noop = $regProdLote->noop;
                  $prodBuscador_impresion = $regProdLote->impresion;
                  $prodBuscador_mezcla = $regProdLote->mezcla;
                  $prodBuscador_amplitud = $regProdLote->amplitud;
                  $prodBuscador_cdglote = $regProdLote->cdglote;
                } else
                { $msg_modulo = 'C&oacute;digo sin coinciencias';}
              }
            }
          }

        $link_mysqli = conectar();
        $prodPaqueteOpeSelect = $link_mysqli->query("
          SELECT * FROM prodpaqueteope
          WHERE cdgpaquete = '".$prodBuscador_cdgpaquete."'
          ORDER BY fchmovimiento DESC");

        if ($prodPaqueteOpeSelect->num_rows > 0)
        { while ($regProdPaqueteOpe = $prodPaqueteOpeSelect->fetch_object())
          { $id_opepaquete++;

            $prodBuscador_idempleadopaquete[$id_opepaquete] = $prodBuscador_idempleado[$regProdPaqueteOpe->cdgempleado];
            $prodBuscador_empleadopaquete[$id_opepaquete] = $prodBuscador_empleado[$regProdPaqueteOpe->cdgempleado];
            $prodBuscador_idmaquinapaquete[$id_opepaquete] = $prodBuscador_idmaquina[$regProdPaqueteOpe->cdgmaquina];
            $prodBuscador_maquinapaquete[$id_opepaquete] = $prodBuscador_maquina[$regProdPaqueteOpe->cdgmaquina];
            $prodBuscador_longitudpaquete[$id_opepaquete] = $regProdPaqueteOpe->longitud;
            $prodBuscador_movimientopaquete[$id_opepaquete] = $regProdPaqueteOpe->fchmovimiento; }

          $num_opepaquete = $prodPaqueteOpeSelect->num_rows;
        } else
        { $num_opepaquete = 0; }

          $link_mysqli = conectar();
          $prodRolloOpeSelect = $link_mysqli->query("
            SELECT * FROM prodrolloope
            WHERE cdgrollo = '".$prodBuscador_cdgrollo."'
            ORDER BY fchmovimiento DESC");

          if ($prodRolloOpeSelect->num_rows > 0)
          { while ($regProdRolloOpe = $prodRolloOpeSelect->fetch_object())
            { $id_operollo++;

              $prodBuscador_idempleadorollo[$id_operollo] = $prodBuscador_idempleado[$regProdRolloOpe->cdgempleado];
              $prodBuscador_empleadorollo[$id_operollo] = $prodBuscador_empleado[$regProdRolloOpe->cdgempleado];
              $prodBuscador_idmaquinarollo[$id_operollo] = $prodBuscador_idmaquina[$regProdRolloOpe->cdgmaquina];
              $prodBuscador_maquinarollo[$id_operollo] = $prodBuscador_maquina[$regProdRolloOpe->cdgmaquina];
              $prodBuscador_longitudrollo[$id_operollo] = $regProdRolloOpe->longitud;
              $prodBuscador_movimientorollo[$id_operollo] = $regProdRolloOpe->fchmovimiento; }

            $num_operollo = $prodRolloOpeSelect->num_rows;
          } else
          { $num_operollo = 0; }

          $link_mysqli = conectar();
          $prodBobinaOpeSelect = $link_mysqli->query("
            SELECT * FROM prodbobinaope
            WHERE cdgbobina = '".$prodBuscador_cdgbobina."'
            ORDER BY fchmovimiento DESC");

          if ($prodBobinaOpeSelect->num_rows > 0)
          { while ($regProdBobinaOpe = $prodBobinaOpeSelect->fetch_object())
            { $id_opebobina++;

              $prodBuscador_idempleadobobina[$id_opebobina] = $prodBuscador_idempleado[$regProdBobinaOpe->cdgempleado];
              $prodBuscador_empleadobobina[$id_opebobina] = $prodBuscador_empleado[$regProdBobinaOpe->cdgempleado];
              $prodBuscador_idmaquinabobina[$id_opebobina] = $prodBuscador_idmaquina[$regProdBobinaOpe->cdgmaquina];
              $prodBuscador_maquinabobina[$id_opebobina] = $prodBuscador_maquina[$regProdBobinaOpe->cdgmaquina];
              $prodBuscador_longitudbobina[$id_opebobina] = $regProdBobinaOpe->longitud;
              $prodBuscador_movimientobobina[$id_opebobina] = $regProdBobinaOpe->fchmovimiento; }

            $num_opebobina = $prodBobinaOpeSelect->num_rows;
          } else
          { $num_opebobina = 0; }

          $link_mysqli = conectar();
          $prodLoteOpeSelect = $link_mysqli->query("
            SELECT * FROM prodloteope
            WHERE cdglote = '".$prodBuscador_cdglote."'
            ORDER BY fchmovimiento DESC");

          if ($prodLoteOpeSelect->num_rows > 0)
          { while ($regProdLoteOpe = $prodLoteOpeSelect->fetch_object())
            { $id_opelote++;

              $prodBuscador_idempleadolote[$id_opelote] = $prodBuscador_idempleado[$regProdLoteOpe->cdgempleado];
              $prodBuscador_empleadolote[$id_opelote] = $prodBuscador_empleado[$regProdLoteOpe->cdgempleado];            
              $prodBuscador_idmaquinalote[$id_opelote] = $prodBuscador_idmaquina[$regProdLoteOpe->cdgmaquina];
              $prodBuscador_maquinalote[$id_opelote] = $prodBuscador_maquina[$regProdLoteOpe->cdgmaquina];
              $prodBuscador_longitudlote[$id_opelote] = $regProdLoteOpe->longitud;
              $prodBuscador_movimientolote[$id_opelote] = $regProdLoteOpe->fchmovimiento; }

            $num_opelote = $prodLoteOpeSelect->num_rows;
          } else
          { $num_opelote = 0; }

          if ($prodLoteSelect->num_rows > 0 OR $prodBobinaSelect->num_rows > 0 OR $prodRolloSelect->num_rows > 0 OR $prodPaqueteSelect->num_rows > 0)
          { echo '

      <table align="center">
        <thead>
          <tr><th>Informaci&oacute;n del c&oacute;digo</th></tr>
        </thead>
        <tbody>
          <tr><td><strong>NoOP <br /><em><h1>'.$prodBuscador_noop.'</h1></em></strong></td></tr>
          <tr><td><strong>Producto</strong> <br /><em>'.$prodBuscador_impresion.'</em></td></tr>
          <tr><td><strong>Ancho en manga</strong> <br /><em><strong>'.$prodBuscador_amplitud.'</strong> '.utf8_decode('Milímetros').'</em></td></tr>
          <tr><td><label for="lbl_observacion">Observaciones</label><br />
              <textarea name="txt_observacion" id="txt_observacion" rows="4" cols="36">'.$prodBuscador_obs.'</textarea></td></tr>
        </tbody>
      </table><br/>

      <div align="center">'.$msg_modulo.'</div>'; }

          if ($regProdLoteOpe->num_rows > 0 OR $prodBobinaOpeSelect->num_rows > 0 OR $prodRolloOpeSelect->num_rows > 0 OR $prodPaqueteOpeSelect->num_rows > 0)
          { echo '

      <table align="center">
        <thead>
          <tr><td colspan="5" align="center"><strong>Trazabilidad</strong></td></tr>
          <tr><th colspan="2">Empleado</th>
            <th colspan="2">Maquina</th>
            <th colspan="2">Movimiento</th></tr>
        </thead>
        <tbody>
          <tr><td colspan="4" align="center"></td><th>Paquete</th></tr>';

            if ($num_opepaquete > 0)
            { for ($id_opepaquete = 1; $id_opepaquete <= $num_opepaquete; $id_opepaquete++)
              { echo '
          <tr><td>[<strong>'.$prodBuscador_idempleadorollo[$id_opepaquete].'</strong>]</td>
            <td> '.$prodBuscador_empleadopaquete[$id_opepaquete].'</td>
            <td>[<strong>'.$prodBuscador_idmaquinapaquete[$id_opepaquete].'</strong>]</td>
            <td> '.$prodBuscador_maquinapaquete[$id_opepaquete].'</td>
            <td>'.$prodBuscador_movimientopaquete[$id_opepaquete].'</td>
            <td>'.$prodBuscador_longitudpaquete[$id_opepaquete].' mts</td></tr>'; }
            }

            echo '
          <tr><td colspan="4" align="center"></td><th>Rollo</th></tr>';

            if ($num_operollo > 0)
            { for ($id_operollo = 1; $id_operollo <= $num_operollo; $id_operollo++)
              { echo '
          <tr><td>[<strong>'.$prodBuscador_idempleadorollo[$id_operollo].'</strong>]</td>
            <td> '.$prodBuscador_empleadorollo[$id_operollo].'</td>
            <td>[<strong>'.$prodBuscador_idmaquinarollo[$id_operollo].'</strong>]</td>
            <td> '.$prodBuscador_maquinarollo[$id_operollo].'</td>
            <td>'.$prodBuscador_movimientorollo[$id_operollo].'</td>
            <td>'.$prodBuscador_longitudrollo[$id_operollo].' mts</td></tr>'; }
            }

            echo '
          <tr><td colspan="4" align="center"></td><th>Bobina</th></tr>';

            if ($num_opebobina > 0)
            { for ($id_opebobina = 1; $id_opebobina <= $num_opebobina; $id_opebobina++)
              { echo '
          <tr><td>[<strong>'.$prodBuscador_idempleadobobina[$id_opebobina].'</strong>]</td>
            <td> '.$prodBuscador_empleadobobina[$id_opebobina].'</td>
            <td>[<strong>'.$prodBuscador_idmaquinabobina[$id_opebobina].'</strong>]</td>
            <td> '.$prodBuscador_maquinabobina[$id_opebobina].'</td>
            <td>'.$prodBuscador_movimientobobina[$id_opebobina].'</td>
            <td>'.$prodBuscador_longitudbobina[$id_opebobina].' mts</td></tr>'; }
            }

            echo '
          <tr><td colspan="4" align="center"></td><th>Lote</th></tr>';

            if ($num_opelote > 0)
            { for ($id_opelote = 1; $id_opelote <= $num_opelote; $id_opelote++)
              { echo '
          <tr><td>[<strong>'.$prodBuscador_idempleadolote[$id_opelote].'</strong>]</td>
            <td> '.$prodBuscador_empleadolote[$id_opelote].'</td>
            <td>[<strong>'.$prodBuscador_idmaquinalote[$id_opelote].'</strong>]</td>
            <td> '.$prodBuscador_maquinalote[$id_opelote].'</td>
            <td>'.$prodBuscador_movimientolote[$id_opelote].'</td>
            <td>'.$prodBuscador_longitudlote[$id_opelote].'</td></tr>'; }
            }

            echo '
        </tbody>
      </table><br/>';
        
          }            
        }
      }
    }

  } else
  { echo '<div align="center" style="align-text:justify"><h1>Este m&oacute;dulo esta reservado para usuarios registrados en el sistema.</h1></div>'; }
?>

  </body>
</html>