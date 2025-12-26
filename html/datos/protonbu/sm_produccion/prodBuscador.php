<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
    <title>Buscador (Rastreador de codigos)</title>
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  if ($_POST['text_codigo'])
  { $prodBuscador_codigo = $_POST['text_codigo']; }
  else
  { if ($_GET['cdgempaque'])
    { $prodBuscador_codigo = $_GET['cdgempaque']; }
  }

  echo '
    <form id="form_prodBuscador" name="form_prodBuscador" method="post" action="prodBuscador.php"/>
      <table align="center">
        <thead>
          <tr><th>Rastreador de c&oacute;digos</th></tr>
        </thead>
        <tbody>
          <tr><td align="center">C&oacute;digo <br/>
              <input type="text" style="width:120px" maxlength="12" id="text_codigo" name="text_codigo" value="'.$prodBuscador_codigo.'" autofocus required /></td></tr>
        </tbody>
        </tfoot>
          <tr><th align="right"><input type="submit" id="button_buscar" name="button_buscar" value="Buscar" /></th></tr>
        <tfoot>
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
            prodrollo.cdgrollo,
            prodbobina.cdgbobina,
            prodlote.cdglote,
            pdtoimpresion.impresion,
            pdtomezcla.mezcla
          FROM prodpaquete,
            prodrollo,
            prodbobina,
            prodlote,
            pdtomezcla,
            pdtoimpresion
          WHERE (prodpaquete.cdgrollo = prodrollo.cdgrollo AND
            prodrollo.cdgbobina = prodbobina.cdgbobina AND
            prodbobina.cdglote = prodlote.cdglote AND
            prodpaquete.cdgpaquete = '".$prodBuscador_codigo."') AND
           (prodlote.cdgmezcla = pdtomezcla.cdgmezcla AND
            pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion)");

        if ($prodPaqueteSelect->num_rows > 0)
        { $regProdPaquete = $prodPaqueteSelect->fetch_object();

          $prodBuscador_noop = $regProdPaquete->noop;
          $prodBuscador_impresion = $regProdPaquete->impresion;
          $prodBuscador_mezcla = $regProdPaquete->mezcla;
          $prodBuscador_amplitud = $regProdPaquete->amplitud;
          $prodBuscador_cdglote = $regProdPaquete->cdglote;
          $prodBuscador_cdgbobina = $regProdPaquete->cdgbobina;
          $prodBuscador_cdgrollo = $regProdPaquete->cdgrollo;
          $prodBuscador_cdgpaquete = $regProdPaquete->cdgpaquete;
        } else
        { $link_mysqli = conectar();
          $prodRolloSelect = $link_mysqli->query("
            SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) AS noop,
              prodrollo.amplitud,
              prodrollo.cdgrollo,
              prodbobina.cdgbobina,
              prodlote.cdglote,
              pdtoimpresion.impresion,
              pdtomezcla.mezcla
            FROM prodrollo,
              prodbobina,
              prodlote,
              pdtomezcla,
              pdtoimpresion
            WHERE (prodrollo.cdgbobina = prodbobina.cdgbobina AND
              prodbobina.cdglote = prodlote.cdglote AND
              prodrollo.cdgrollo = '".$prodBuscador_codigo."') AND
             (prodlote.cdgmezcla = pdtomezcla.cdgmezcla AND
              pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion)");

          if ($prodRolloSelect->num_rows > 0)
          { $regProdRollo = $prodRolloSelect->fetch_object();

            $prodBuscador_noop = $regProdRollo->noop;
            $prodBuscador_impresion = $regProdRollo->impresion;
            $prodBuscador_mezcla = $regProdRollo->mezcla;
            $prodBuscador_amplitud = $regProdRollo->amplitud;
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
                pdtoimpresion.impresion,
                pdtomezcla.mezcla
              FROM prodbobina,
                prodlote,
                pdtomezcla,
                pdtoimpresion
              WHERE (prodbobina.cdglote = prodlote.cdglote AND
                prodbobina.cdgbobina = '".$prodBuscador_codigo."') AND
               (prodlote.cdgmezcla = pdtomezcla.cdgmezcla AND
                pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion)");

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
                  pdtoimpresion.impresion,
                  pdtomezcla.mezcla
                FROM prodlote,
                  pdtomezcla,
                  pdtoimpresion
                WHERE (prodlote.cdglote = '".$prodBuscador_codigo."') AND
                 (prodlote.cdgmezcla = pdtomezcla.cdgmezcla AND
                  pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion)");

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
            $prodBuscador_longitudfin[$id_operollo] = $regProdRolloOpe->longitudfin;
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
            //$prodBuscador_operacionbobina[$id_opebobina] = $regProdBobinaOpe->cdgoperacion;
            $prodBuscador_idmaquinabobina[$id_opebobina] = $prodBuscador_idmaquina[$regProdBobinaOpe->cdgmaquina];
            $prodBuscador_maquinabobina[$id_opebobina] = $prodBuscador_maquina[$regProdBobinaOpe->cdgmaquina];
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
            $prodBuscador_operacionlote[$id_opelote] = $regProdLoteOpe->cdgoperacion;
            $prodBuscador_idmaquinalote[$id_opelote] = $prodBuscador_idmaquina[$regProdLoteOpe->cdgmaquina];
            $prodBuscador_maquinalote[$id_opelote] = $prodBuscador_maquina[$regProdLoteOpe->cdgmaquina];
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
        <tr><td>NoOP: <b>'.$prodBuscador_noop.'</b></td></tr>
        <tr><td>Producto: <b>'.$prodBuscador_impresion.'</b></td></tr>
        <tr><td>Mezcla: <b>'.$prodBuscador_mezcla.'</b></td></tr>
        <tr><td>Ancho plano: <b>'.$prodBuscador_amplitud.'</b> mm</td></tr>
      </tbody>
    </table><br/>

    <div align="center">'.$msg_modulo.'</div>'; }

        if ($regProdLoteOpe->num_rows > 0 OR $prodBobinaOpeSelect->num_rows > 0 OR $prodRolloOpeSelect->num_rows > 0)
        { echo '

    <table align="center">
      <thead>
        <tr><td colspan="5" align="center"><strong>Operaciones</strong></td></tr>
        <tr><th colspan="2">Empleado</th>
          <th colspan="2">Maquina</th>
          <th colspan="2">Movimiento</th></tr>
      </thead>
      <tbody>
        <tr><td colspan="4" align="center"></td><th>Rollo</th></tr>';

          if ($num_operollo > 0)
          { for ($id_operollo = 1; $id_operollo <= $num_operollo; $id_operollo++)
            { echo '
        <tr><td>[<strong>'.$prodBuscador_idempleadorollo[$id_operollo].'</strong>]</td>
          <td> '.$prodBuscador_empleadorollo[$id_operollo].'</td>
          <td>[<strong>'.$prodBuscador_idmaquinarollo[$id_operollo].'</strong>]</td>
          <td> '.$prodBuscador_maquinarollo[$id_operollo].'</td>
          <td>'.$prodBuscador_movimientorollo[$id_operollo].'</td>
          <td>'.$prodBuscador_longitudfin[$id_operollo].' mts</td></tr>'; }
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
          <td>'.$prodBuscador_movimientobobina[$id_opebobina].'</td></tr>'; }
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
          <td>'.$prodBuscador_movimientolote[$id_opelote].'</td></tr>'; }
          }

          echo '
      </tbody>
    </table><br/>';
      
        }            
      }
    }
  }

?>

  </body>
</html>
