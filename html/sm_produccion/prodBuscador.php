<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
    <title>Buscador (Rastreador de codigos)</title>
  </head>
  <body><br/>
    <div style="position:absolute; left:20px;"><?php

  include '../datos/mysql.php';

  if ($_POST['searchCode'])
  { $prodBuscador_codigo = $_POST['searchCode']; }
  else
  { if ($_GET['cdgempaque'])
    { $prodBuscador_codigo = $_GET['cdgempaque']; }
  }

  echo '
    <form id="formBusca" name="formBusca" method="POST" action="prodBuscador.php"/>
      <section>
        <article>
          <dl>
            <dt><b>'.utf8_decode('Rastreador de códigos').'</b></dt>
            <dt><label for="labelCodigo"><b>'.utf8_decode('Código').'</b></label>
              <input type="search" id="searchCode" name="searchCode" value="'.$prodBuscador_codigo.'" placeholder="NoOP / Codigo" required="required" autofocus /><br>
              <label for="labelMensaje">'.utf8_decode('Selecciona el tipo de código a rastrear').'</label></dt>
            <dd>
              <dl>
                <dt><input type="radio" name="radioCode" id="radioCode" value="0" /> '.utf8_decode('Producto en proceso').'</dt>
                <dt><input type="radio" name="radioCode" id="radioCode" value="1" /> '.utf8_decode('Producto').'</dt>
                <dt><input type="radio" name="radioCode" id="radioCode" value="2" /> '.utf8_decode('Empaque').'</dt>
                <dt><input type="radio" name="radioCode" id="radioCode" value="3" /> '.utf8_decode('Envío').'</dt>
              </dl>
            </dd>
            <dt>
            </dt>
          </dl>
          <input type="submit" id="button_buscar" name="button_buscar" value="Buscar" />
        </article>
      </section>
    </form><br/>';

  if ($prodBuscador_codigo != '')
  { switch ($_POST['radioCode']) {
      default: 
        //Buscar coincidencias en el producto que se encuentra en proceso
          // Catálogo de empleados
        $link_mysqli = conectar();
        $querySelect = $link_mysqli->query("
        SELECT idempleado,
               empleado,
               cdgempleado
          FROM rechempleado");

        while ($regQuery = $querySelect->fetch_object())
        { $prodBuscador_idempleado[$regQuery->cdgempleado] = $regQuery->idempleado;
          $prodBuscador_empleado[$regQuery->cdgempleado] = $regQuery->empleado; }

          // Catálogo de maquinas
        $link_mysqli = conectar();
        $querySelect = $link_mysqli->query("
        SELECT idmaquina,
               maquina,
               cdgmaquina
          FROM prodmaquina");

        while ($regQuery = $querySelect->fetch_object())
        { $prodBuscador_idmaquina[$regQuery->cdgmaquina] = $regQuery->idmaquina;
          $prodBuscador_maquina[$regQuery->cdgmaquina] = $regQuery->maquina; }

          //Buscar coincidencias como paquete
        $link_mysqli = conectar();
        $querySelectPaquete = $link_mysqli->query("
          SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo,'-',prodpaquete.paquete) AS noop,
                 prodrollo.amplitud,
                 prodpaquete.cdgpaquete,
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
                 prodbobina.cdglote = prodlote.cdglote) AND
                (prodpaquete.cdgpaquete = '".$prodBuscador_codigo."' OR 
          CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo,'-',prodpaquete.paquete) = '".$prodBuscador_codigo."') AND
                (prodlote.cdgproducto = pdtoimpresion.cdgimpresion)");

        if ($querySelectPaquete->num_rows > 0)
        { $regQueryPaquete = $querySelectPaquete->fetch_object();

          $prodBuscador_noop = $regQueryPaquete->noop;
          $prodBuscador_impresion = $regQueryPaquete->impresion;
          $prodBuscador_amplitud = $regQueryPaquete->amplitud;
          $prodBuscador_cdglote = $regQueryPaquete->cdglote;
          $prodBuscador_cdgbobina = $regQueryPaquete->cdgbobina;
          $prodBuscador_cdgrollo = $regQueryPaquete->cdgrollo;
          $prodBuscador_cdgpaquete = $regQueryPaquete->cdgpaquete;
        } else
        {   //Buscar coincidencias como rollo
          $link_mysqli = conectar();
          $querySelectRollo = $link_mysqli->query("
            SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) AS noop,
                   prodrollo.amplitud,
                   prodrollo.cdgrollo,
                   prodbobina.cdgbobina,
                   prodlote.cdglote,
                   pdtoimpresion.impresion
              FROM prodrollo,
                   prodbobina,
                   prodlote,
                   pdtoimpresion
            WHERE (prodrollo.cdgbobina = prodbobina.cdgbobina AND
                   prodbobina.cdglote = prodlote.cdglote) AND
                  (prodrollo.cdgrollo = '".$prodBuscador_codigo."' OR 
            CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) = '".$prodBuscador_codigo."') AND
                  (prodlote.cdgproducto = pdtoimpresion.cdgimpresion)"); 

          if ($querySelectRollo->num_rows > 0)
          { $regQueryRollo = $querySelectRollo->fetch_object();

            $prodBuscador_noop = $regQueryRollo->noop;
            $prodBuscador_impresion = $regQueryRollo->impresion;
            $prodBuscador_amplitud = $regQueryRollo->amplitud;
            $prodBuscador_cdglote = $regQueryRollo->cdglote;
            $prodBuscador_cdgbobina = $regQueryRollo->cdgbobina;
            $prodBuscador_cdgrollo = $regQueryRollo->cdgrollo;
          } else
          {   //Buscar coincidencias como bobina
            $link_mysqli = conectar();
            $querySelectBobina = $link_mysqli->query("
              SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina) AS noop,
                     prodbobina.amplitud,
                     prodbobina.cdgbobina,
                     prodlote.cdglote,
                     pdtoimpresion.impresion
                FROM prodbobina,
                     prodlote,
                     pdtoimpresion
              WHERE (prodbobina.cdglote = prodlote.cdglote) AND
                    (prodbobina.cdgbobina = '".$prodBuscador_codigo."' OR 
              CONCAT(prodlote.noop,'-',prodbobina.bobina) = '".$prodBuscador_codigo."') AND
                    (prodlote.cdgproducto = pdtoimpresion.cdgimpresion)");

            if ($querySelectBobina->num_rows > 0)
            { $regQueryBobina = $querySelectBobina->fetch_object();

              $prodBuscador_noop = $regQueryBobina->noop;
              $prodBuscador_impresion = $regQueryBobina->impresion;
              $prodBuscador_amplitud = $regQueryBobina->amplitud;
              $prodBuscador_cdglote = $regQueryBobina->cdglote;
              $prodBuscador_cdgbobina = $regQueryBobina->cdgbobina;
            } else
            {   //Buscar coincidencias como lote
              $link_mysqli = conectar();
              $querySelectLote = $link_mysqli->query("
                SELECT prodlote.noop,
                       prodlote.amplitud,
                       prodlote.cdglote,
                       pdtoimpresion.impresion
                  FROM prodlote,
                       pdtoimpresion
                WHERE (prodlote.cdglote = '".$prodBuscador_codigo."' OR 
                       prodlote.noop = '".$prodBuscador_codigo."') AND
                      (prodlote.cdgproducto = pdtoimpresion.cdgimpresion)");

              if ($querySelectLote->num_rows > 0)
              { $regQueryLote = $querySelectLote->fetch_object();

                $prodBuscador_noop = $regQueryLote->noop;
                $prodBuscador_impresion = $regQueryLote->impresion;
                $prodBuscador_amplitud = $regQueryLote->amplitud;
                $prodBuscador_cdglote = $regQueryLote->cdglote;
              } else
              { $msg_modulo = 'C&oacute;digo sin coinciencias';}
            }
          }
        }

        $link_mysqli = conectar();
        $querySelectPaqueteOpe = $link_mysqli->query("
          SELECT * FROM prodpaqueteope
           WHERE cdgpaquete = '".$prodBuscador_cdgpaquete."'
        ORDER BY fchmovimiento DESC");

        if ($querySelectPaqueteOpe->num_rows > 0)
        { while ($regQueryPaqueteOpe = $querySelectPaqueteOpe->fetch_object())
          { $idPaqueteOpe++;

            $prodBuscador_idempleadopaquete[$idPaqueteOpe] = $prodBuscador_idempleado[$regQueryPaqueteOpe->cdgempleado];
            $prodBuscador_empleadopaquete[$idPaqueteOpe] = $prodBuscador_empleado[$regQueryPaqueteOpe->cdgempleado];
            $prodBuscador_idmaquinapaquete[$idPaqueteOpe] = $prodBuscador_idmaquina[$regQueryPaqueteOpe->cdgmaquina];
            $prodBuscador_maquinapaquete[$idPaqueteOpe] = $prodBuscador_maquina[$regQueryPaqueteOpe->cdgmaquina];
            $prodBuscador_longitudpaquete[$idPaqueteOpe] = $regQueryPaqueteOpe->longitud;
            $prodBuscador_movimientopaquete[$idPaqueteOpe] = $regQueryPaqueteOpe->fchmovimiento; }

          $nPaqueteOpes = $querySelectPaqueteOpe->num_rows;
        } else
        { $nPaqueteOpes = 0; }

        $link_mysqli = conectar();
        $querySelectRolloOpe = $link_mysqli->query("
          SELECT * FROM prodrolloope
           WHERE cdgrollo = '".$prodBuscador_cdgrollo."'
        ORDER BY fchmovimiento DESC");

        if ($querySelectRolloOpe->num_rows > 0)
        { while ($regQueryRolloOpe = $querySelectRolloOpe->fetch_object())
          { $idRolloOpe++;

            $prodBuscador_idempleadorollo[$idRolloOpe] = $prodBuscador_idempleado[$regQueryRolloOpe->cdgempleado];
            $prodBuscador_empleadorollo[$idRolloOpe] = $prodBuscador_empleado[$regQueryRolloOpe->cdgempleado];
            $prodBuscador_idmaquinarollo[$idRolloOpe] = $prodBuscador_idmaquina[$regQueryRolloOpe->cdgmaquina];
            $prodBuscador_maquinarollo[$idRolloOpe] = $prodBuscador_maquina[$regQueryRolloOpe->cdgmaquina];
            $prodBuscador_longitudrollo[$idRolloOpe] = $regQueryRolloOpe->longitudfin;
            $prodBuscador_movimientorollo[$idRolloOpe] = $regQueryRolloOpe->fchmovimiento; }

          $nRolloOpes = $querySelectRolloOpe->num_rows;
        } else
        { $nRolloOpes = 0; }

        $link_mysqli = conectar();
        $querySelectBobinaOpe = $link_mysqli->query("
          SELECT * FROM prodbobinaope
           WHERE cdgbobina = '".$prodBuscador_cdgbobina."'
        ORDER BY fchmovimiento DESC");

        if ($querySelectBobinaOpe->num_rows > 0)
        { while ($regQueryBobinaOpe = $querySelectBobinaOpe->fetch_object())
          { $idBobinaOpe++;

            $prodBuscador_idempleadobobina[$idBobinaOpe] = $prodBuscador_idempleado[$regQueryBobinaOpe->cdgempleado];
            $prodBuscador_empleadobobina[$idBobinaOpe] = $prodBuscador_empleado[$regQueryBobinaOpe->cdgempleado];
            $prodBuscador_idmaquinabobina[$idBobinaOpe] = $prodBuscador_idmaquina[$regQueryBobinaOpe->cdgmaquina];
            $prodBuscador_maquinabobina[$idBobinaOpe] = $prodBuscador_maquina[$regQueryBobinaOpe->cdgmaquina];
            $prodBuscador_longitudbobina[$idBobinaOpe] = $regQueryBobinaOpe->longitud;
            $prodBuscador_movimientobobina[$idBobinaOpe] = $regQueryBobinaOpe->fchmovimiento; }

          $nBobinaOpes = $querySelectBobinaOpe->num_rows;
        } else
        { $nBobinaOpes = 0; }

        $link_mysqli = conectar();
        $querySelectLoteOpe = $link_mysqli->query("
          SELECT * FROM prodloteope
           WHERE cdglote = '".$prodBuscador_cdglote."'
        ORDER BY fchmovimiento DESC");

        if ($querySelectLoteOpe->num_rows > 0)
        { while ($regQueryLoteOpe = $querySelectLoteOpe->fetch_object())
          { $idLoteOpe++;

            $prodBuscador_idempleadolote[$idLoteOpe] = $prodBuscador_idempleado[$regQueryLoteOpe->cdgempleado];
            $prodBuscador_empleadolote[$idLoteOpe] = $prodBuscador_empleado[$regQueryLoteOpe->cdgempleado];            
            $prodBuscador_idmaquinalote[$idLoteOpe] = $prodBuscador_idmaquina[$regQueryLoteOpe->cdgmaquina];
            $prodBuscador_maquinalote[$idLoteOpe] = $prodBuscador_maquina[$regQueryLoteOpe->cdgmaquina];
            $prodBuscador_longitudlote[$idLoteOpe] = $regQueryLoteOpe->longout;
            $prodBuscador_movimientolote[$idLoteOpe] = $regQueryLoteOpe->fchmovimiento; }

          $nLoteOpes = $querySelectLoteOpe->num_rows;
        } else
        { $nLoteOpes = 0; }

        if ($querySelectLote->num_rows > 0 OR $querySelectBobina->num_rows > 0 OR $querySelectRollo->num_rows > 0 OR $querySelectPaquete->num_rows > 0)
        { echo '
    <section>
      <article>
        <dl>
          <dt>'.utf8_decode('Número de Orden de Producción').' <b>'.$prodBuscador_noop.'</b></dt>
          <dt>Producto <b>'.$prodBuscador_impresion.'</b></dt>
          <dt>Amplitud <b>'.$prodBuscador_amplitud.'</b>mm</dt>
        </dl>
      </article>
    </section>'; }

        if ($nLoteOpes > 0 OR $nBobinaOpes > 0 OR $nRolloOpes > 0 OR $nPaqueteOpes > 0)
        { echo '
    <table align="center">
      <thead>
        <tr><td colspan="5" align="center"><strong>Trazabilidad</strong></td></tr>
        <tr><th colspan="2">Empleado</th>
          <th colspan="2">Maquina</th>
          <th colspan="2">Movimiento</th></tr>
      </thead>
      <tbody>';

          if ($nPaqueteOpes > 0)
          { for ($idPaqueteOpe = 1; $idPaqueteOpe <= $nPaqueteOpes; $idPaqueteOpe++)
            { echo '
        <tr><td>[<strong>'.$prodBuscador_idempleadorollo[$idPaqueteOpe].'</strong>]</td>
          <td> '.$prodBuscador_empleadopaquete[$idPaqueteOpe].'</td>
          <td>[<strong>'.$prodBuscador_idmaquinapaquete[$idPaqueteOpe].'</strong>]</td>
          <td> '.$prodBuscador_maquinapaquete[$idPaqueteOpe].'</td>
          <td>'.$prodBuscador_movimientopaquete[$idPaqueteOpe].'</td>
          <td>'.$prodBuscador_longitudpaquete[$idPaqueteOpe].' mts</td></tr>'; }
        
            echo '
        <tr><td colspan="4" align="center"></td><th>Paquete</th></tr>'; }

          if ($nRolloOpes > 0)
          { for ($idRolloOpe = 1; $idRolloOpe <= $nRolloOpes; $idRolloOpe++)
            { echo '
        <tr><td>[<strong>'.$prodBuscador_idempleadorollo[$idRolloOpe].'</strong>]</td>
          <td> '.$prodBuscador_empleadorollo[$idRolloOpe].'</td>
          <td>[<strong>'.$prodBuscador_idmaquinarollo[$idRolloOpe].'</strong>]</td>
          <td> '.$prodBuscador_maquinarollo[$idRolloOpe].'</td>
          <td>'.$prodBuscador_movimientorollo[$idRolloOpe].'</td>
          <td>'.$prodBuscador_longitudrollo[$idRolloOpe].' mts</td></tr>'; }
          
            echo '
        <tr><td colspan="4" align="center"></td><th>Rollo</th></tr>'; }

          if ($nBobinaOpes > 0)
          { for ($idBobinaOpe = 1; $idBobinaOpe <= $nBobinaOpes; $idBobinaOpe++)
            { echo '
        <tr><td>[<strong>'.$prodBuscador_idempleadobobina[$idBobinaOpe].'</strong>]</td>
          <td> '.$prodBuscador_empleadobobina[$idBobinaOpe].'</td>
          <td>[<strong>'.$prodBuscador_idmaquinabobina[$idBobinaOpe].'</strong>]</td>
          <td> '.$prodBuscador_maquinabobina[$idBobinaOpe].'</td>
          <td>'.$prodBuscador_movimientobobina[$idBobinaOpe].'</td>
          <td>'.$prodBuscador_longitudbobina[$idBobinaOpe].' mts</td></tr>'; }
        
            echo '
        <tr><td colspan="4" align="center"></td><th>Bobina</th></tr>'; }

          if ($nLoteOpes > 0)
          { for ($idLoteOpe = 1; $idLoteOpe <= $nLoteOpes; $idLoteOpe++)
            { echo '
        <tr><td>[<strong>'.$prodBuscador_idempleadolote[$idLoteOpe].'</strong>]</td>
          <td> '.$prodBuscador_empleadolote[$idLoteOpe].'</td>
          <td>[<strong>'.$prodBuscador_idmaquinalote[$idLoteOpe].'</strong>]</td>
          <td> '.$prodBuscador_maquinalote[$idLoteOpe].'</td>
          <td>'.$prodBuscador_movimientolote[$idLoteOpe].'</td>
          <td>'.$prodBuscador_longitudlote[$idLoteOpe].'</td></tr>'; }
        
            echo '
        <tr><td colspan="4" align="center"></td><th>Lote</th></tr>'; }

          echo '
      </tbody>
    </table>'; }
      break;
      case 1: 
        // Buscar coincidencias en el catálogo de productos
        $link_mysqli = conectar();
        $querySelect = $link_mysqli->query("
          SELECT pdtodiseno.anchof,
                 pdtodiseno.altof,
                 pdtoimpresion.impresion 
            FROM pdtodiseno,
                 pdtoimpresion
           WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
                 pdtoimpresion.cdgimpresion = '".$prodBuscador_codigo."'");
          
        if ($querySelect->num_rows > 0)
        { $link_mysqli = conectar();
          $alptEmpaqueSelect = $link_mysqli->query("
            SELECT * FROM alptempaque
             WHERE cdgembarque = '' AND
                   cdgproducto = '".$prodBuscador_codigo."' AND (tpoempaque ='C' OR tpoempaque ='Q')
          ORDER BY tpoempaque, 
                   noempaque");

          if ($alptEmpaqueSelect->num_rows > 0)
          { while ($regAlptEmpaque = $alptEmpaqueSelect->fetch_object())
            { $nEmpaque++;

              $prodBuscador_cdgempaque[$nEmpaque] = $regAlptEmpaque->cdgempaque;
              $prodBuscador_tpoempaque[$nEmpaque] = $regAlptEmpaque->tpoempaque;
              $prodBuscador_empaque[$nEmpaque] = $regAlptEmpaque->tpoempaque.$regAlptEmpaque->noempaque; }

            $nEmpaques = $alptEmpaqueSelect->num_rows;
            $nColumnas = number_format(sqrt($nEmpaques),0);

            if ($nColumnas > 9) { $nColumnas = 9; }

            $nRenglones = number_format(($nEmpaques/$nColumnas),0);

            if ($nEmpaques > ($nColumnas*$nRenglones)) { $nRenglones++; }

            $regQuery = $querySelect->fetch_object(); }
          
            echo '
    <section>
      <article>
        <dl>
          <dt>Producto <b>'.$regQuery->impresion.'</b></dt>
          <dt>Ancho final <b>'.$regQuery->anchof.'</b>mm</dt>
          <dt>Alto final <b>'.$regQuery->altof.'</b>mm</dt>
          <dt>Empaques <b>'.$nEmpaques.'</b></dt>
        </dl>
      </article>
    </section>'; }

        echo '
      <table align="center">
        <thead>
          <tr><th colspan="'.$nColumnas.'">Empaques disponibles por producto</th></tr>
        </thead>
        <tbody>';

        for ($nRenglon = 1; $nRenglon <= $nRenglones; $nRenglon++)
        { echo '
          <tr>';

          for ($nColumna = 1; $nColumna <= $nColumnas; $nColumna++)
          { $nPack++;

            if ($prodBuscador_empaque[$nPack] != '')
            { echo '
            <td align="right"><b>'.$prodBuscador_empaque[$nPack].'</b> ';

              if ($prodBuscador_tpoempaque[$nEmpaque] == 'Q')
              { echo '
              <a href="prodBuscador.php?cdgempaque='.$prodBuscador_cdgempaque[$nEmpaque].'" target="_blank">'.$png_search.'</a>
              <a href="../sm_almacenpt/pdf/alptEmpaqueBCE.php?cdgempaque='.$prodBuscador_cdgempaque[$nEmpaque].'" target="_blank">'.$png_barcode.'</a>'; }

              if ($prodBuscador_tpoempaque[$nEmpaque] == 'C')
              { echo '
              <a href="prodBuscador.php?cdgempaque='.$prodBuscador_cdgempaque[$nEmpaque].'" target="_blank">'.$png_search.'</a>
              <a href="../sm_almacenpt/pdf/alptEmpaqueBCEC.php?cdgempaque='.$prodBuscador_cdgempaque[$nEmpaque].'" target="_blank">'.$png_barcode.'</a>'; }

              echo '</td>'; 
            }
          }

          echo '
          </tr>'; }

        echo '
        </tbody>
      </table>'; 
      break;
      case 2: 
        $link_mysqli = conectar();
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
    <section>
      <article>
        <dl>
          <dt>Producto <b>'.$regAlptEmpaque->impresion.'</b></dt>
          <dt>Empaque <b>'.$regAlptEmpaque->tpoempaque.$regAlptEmpaque->noempaque.'</b></dt>
          <dt>Empacador <b>'.$regAlptEmpaque->empleado.'</b></dt>
        </dl>
      </article>
    </section>';        
        
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
                prodbobina.cdglote = prodlote.cdglote)"); }
          
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
          <td>'.$regContenidoEmpaque->codigo.'</td></tr>'; }

            echo '
      </tbody>
      <tfoot>
        <tr><td colspan="4"></td></tr>
      </tfoot>
    </table><br/>'; }              
        } 
      break;
      case 3: 
        $link_mysqli = conectar();
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
           WHERE vntsembarque.cdgembarque = '".$prodBuscador_codigo."' AND
                 vntsembarque.cdgsucursal = vntssucursal.cdgsucursal AND
                 vntssucursal.cdgcliente = vntscliente.cdgcliente AND
                 vntsembarque.cdgproducto = pdtoimpresion.cdgimpresion");

        if ($vntsEmbarqueSelect->num_rows > 0)
        { $regVntsEmbarque = $vntsEmbarqueSelect->fetch_object();

          echo '
    <section>
      <article>
        <dl>
          <dt>Producto <b>'.$regVntsEmbarque->impresion.'</b></dt>
          <dt>Embarque <b>'.$regVntsEmbarque->cdgembarque.'</b></dt>
          <dt>Cliente <b>'.$regVntsEmbarque->cliente.'</b></dt>
          <dt>Sucursal <b>'.$regVntsEmbarque->sucursal.'</b></dt>
          <dt>Fecha <b>'.$regVntsEmbarque->fchembarque.'</b></dt>
          <dt>Referencia <b>'.$regVntsEmbarque->referencia.'</b></dt>
        </dl>
      </article>
    </section>';

          $link_mysqli = conectar();
          $alptEmpaqueSelect = $link_mysqli->query("
            SELECT * FROM alptempaque
             WHERE cdgembarque = '".$regVntsEmbarque->cdgembarque."'");

          if ($alptEmpaqueSelect->num_rows > 0)
          { $nEmpaque = 1;

            while ($regAlptEmpaque = $alptEmpaqueSelect->fetch_object())
            { $prodBuscador_cdgempaque[$nEmpaque] = $regAlptEmpaque->cdgempaque;
              $prodBuscador_tpoempaque[$nEmpaque] = $regAlptEmpaque->tpoempaque;
              $prodBuscador_empaque[$nEmpaque] = $regAlptEmpaque->tpoempaque.$regAlptEmpaque->noempaque;

              $nEmpaque++; }

            $nEmpaques = $alptEmpaqueSelect->num_rows;
            $nColumnas = number_format(sqrt($nEmpaques),0);

            if ($nColumnas >= 9) { $nColumnas = 9; }

            $nRenglones = number_format(($nEmpaques/$nColumnas),0);

            if ($nEmpaques > ($nColumnas*$nRenglones)) { $nRenglones++; }

            echo '
    <table align="center">
      <thead>
        <tr><th colspan="'.$nColumnas.'">Detalle de los empaques por embarque</th></tr>
      </thead>
      <tbody>';

            $nEmpaque = 1;
            for ($nRenglon = 1; $nRenglon <= $nRenglones; $nRenglon++)
            { echo '
        <tr>';

              for ($nColumna = 1; $nColumna <= $nColumnas; $nColumna++)
              { if ($prodBuscador_empaque[$nEmpaque] != '')
                { echo '
          <td align="right"><b>'.$prodBuscador_empaque[$nEmpaque].'</b> ';

                  if ($prodBuscador_tpoempaque[$nEmpaque] == 'Q')
                  { echo '
            <a href="prodBuscador.php?cdgempaque='.$prodBuscador_cdgempaque[$nEmpaque].'" target="_blank">'.$png_search.'</a>
            <a href="../sm_almacenpt/pdf/alptEmpaqueBCE.php?cdgempaque='.$prodBuscador_cdgempaque[$nEmpaque].'" target="_blank">'.$png_barcode.'</a>'; }

                  if ($prodBuscador_tpoempaque[$nEmpaque] == 'C')
                  { echo '
            <a href="prodBuscador.php?cdgempaque='.$prodBuscador_cdgempaque[$nEmpaque].'" target="_blank">'.$png_search.'</a>
            <a href="../sm_almacenpt/pdf/alptEmpaqueBCEC.php?cdgempaque='.$prodBuscador_cdgempaque[$nEmpaque].'" target="_blank">'.$png_barcode.'</a>'; }

                  echo '</td>'; }

                $nEmpaque++; }

              echo '
        </tr>'; }

            echo '
      </tbody>
    </table>'; }
        }
      break; }
  }
?>
    </div>
  </body>
</html>