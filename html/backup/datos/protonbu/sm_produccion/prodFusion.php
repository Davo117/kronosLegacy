<!DOCTYPE html>
<html>
  <head>
    <title>Producci&oacute;n Fusion</title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '60040';

  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

     // Delimitación de semanas
    ////////////////////////////////////////

    $prodDestajo_hstano = date("Y");
    $prodDestajo_hstmes = date("n");
    $prodDestajo_hstdia = date("j");
    $prodDestajo_diasmn = date("w");
        
    if ($prodDestajo_diasmn == 4) { $prodDestajo_dianmn = 0; } //Jueves 
    if ($prodDestajo_diasmn == 5) { $prodDestajo_dianmn = 1; } //Viernes
    if ($prodDestajo_diasmn == 6) { $prodDestajo_dianmn = 2; } //Sabado
    if ($prodDestajo_diasmn == 0) { $prodDestajo_dianmn = 3; } //Domingo
    if ($prodDestajo_diasmn == 1) { $prodDestajo_dianmn = 4; } //Lunes
    if ($prodDestajo_diasmn == 2) { $prodDestajo_dianmn = 5; } //Martes
    if ($prodDestajo_diasmn == 3) { $prodDestajo_dianmn = 6; } //Miercoles         
    
    if (($prodDestajo_hstdia-$prodDestajo_dianmn) > 0) 
    { $prodDestajo_dsdmes = $prodDestajo_hstmes;
      $prodDestajo_dsdano = $prodDestajo_hstano;        
      $prodDestajo_dsddia = ($prodDestajo_hstdia-$prodDestajo_dianm); 
    }
    else 
    { $prodDestajo_dsdmes = $prodDestajo_hstmes-1;
          
      if($prodDestajo_dsdmes == 0) 
      { $prodDestajo_dsdmes = 12; 
        $prodDestajo_dsdano = $prodDestajo_hstano-1; 
      } 
      else 
      { $prodDestajo_dsdano = $prodDestajo_hstano; }
        
      $prodDestajo_diasmes = date("t", mktime(0,0,0,($prodDestajo_dsdmes),1,$prodDestajo_dsdano)); 
      $prodDestajo_dsddia = ($prodDestajo_hstdia-$prodDestajo_dianmn)+$prodDestajo_diasmes; 
    }
        
    $prodDestajo_fchinicial = $prodDestajo_dsdano.'-'.str_pad($prodDestajo_dsdmes,2,'0',STR_PAD_LEFT).'-'.str_pad($prodDestajo_dsddia,2,'0',STR_PAD_LEFT);
    $prodDestajo_fchfinal = date("Y-m-d");
    ////////////////////////////////////////////

    $prodFusion_cdgempleado = trim($_POST['text_empleado']);
    $prodFusion_cdgmaquina = trim($_POST['text_maquina']);
    $prodFusion_cdgbobina = trim($_POST['text_bobina']);
    
    if ($_GET['cdgempleado'])
    { $prodFusion_cdgempleado = $_GET['cdgempleado']; }

    if ($_GET['cdgmaquina'])
    { $prodFusion_cdgmaquina = $_GET['cdgmaquina']; }

    //Buscar Empleado
    $link_mysql = conectar();
    $rechEmpleadoSelect = $link_mysql->query("
      SELECT * FROM rechempleado
      WHERE (idempleado = '".$prodFusion_cdgempleado."' OR cdgempleado = '".$prodFusion_cdgempleado."') AND
        sttempleado >= '1'");

    // Filtra al empleado
    $regRechEmpleado = $rechEmpleadoSelect->fetch_object();

    $prodFusion_idempleado = $regRechEmpleado->idempleado;
    $prodFusion_empleado = $regRechEmpleado->empleado;
    $prodFusion_cdgempleado = $regRechEmpleado->cdgempleado;

    $rechEmpleadoSelect->close;

    if ($rechEmpleadoSelect->num_rows > 0)
    { //Buscar Maquina Proceso 40 Fusion
      $link_mysql = conectar();
      $prodMaquinaSelect = $link_mysql->query("
        SELECT * FROM prodmaquina
        WHERE (idmaquina = '".$prodFusion_cdgmaquina."' OR cdgmaquina = '".$prodFusion_cdgmaquina."') AND
          cdgproceso = '40' AND
          sttmaquina >= '1'");

      // Filtra la maquina
      $regProdMaquina = $prodMaquinaSelect->fetch_object();

      $prodFusion_idmaquina = $regProdMaquina->idmaquina;
      $prodFusion_maquina = $regProdMaquina->maquina;
      $prodFusion_cdgmaquina = $regProdMaquina->cdgmaquina;

      $prodMaquinaSelect->close;

      if ($prodMaquinaSelect->num_rows > 0)
      { //Buscar lote Proceso
        $link_mysql = conectar();
        $prodBobinaSelect = $link_mysql->query("
            SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina) AS noop,
              prodlote.cdglote,              
              pdtoproyecto.proyecto,
              pdtoimpresion.impresion,
              pdtoimpresion.cdgimpresion,
              prodbobina.longitud,
              prodbobina.amplitud,
              prodbobina.peso,
              prodbobina.cdgbobina
            FROM proglote,
              prodlote,
              prodbobina,
              pdtoimpresion,
              pdtoproyecto
            WHERE (proglote.cdglote = prodlote.cdglote AND prodlote.cdglote = prodbobina.cdglote) AND
            ((prodbobina.cdgbobina = '".$prodFusion_cdgbobina."') OR (CONCAT(prodlote.noop,'-',prodbobina.bobina) = '".$prodFusion_cdgbobina."')) AND
             (prodbobina.cdgproducto = pdtoimpresion.cdgimpresion AND pdtoimpresion.cdgproyecto = pdtoproyecto.cdgproyecto) AND
              (prodbobina.sttbobina = '7' OR prodbobina.sttbobina = '9')");

        if ($prodBobinaSelect->num_rows > 0)
        { 
          
          // Filtra la bobina
          $regProdLote = $prodBobinaSelect->fetch_object();

          $prodFusion_idlote = $regProdLote->idlote;
          $prodFusion_lote = $regProdLote->lote;
          $prodFusion_cdglote = $regProdLote->cdglote;
          $prodFusion_noop = $regProdLote->noop;
          $prodFusion_bobina = $regProdLote->bobina;
          $prodFusion_longitud = $regProdLote->longitud;
          $prodFusion_amplitud = $regProdLote->amplitud;
          $prodFusion_peso = $regProdLote->peso;
          $prodFusion_tarima = $regProdLote->tarima;
          $prodFusion_proyecto = $regProdLote->proyecto;
          $prodFusion_impresion = $regProdLote->impresion;
          //$prodFusion_idmezcla = $regProdLote->idmezcla;
          //$prodFusion_mezcla = $regProdLote->mezcla;
          $prodFusion_cdgimpresion = $regProdLote->cdgimpresion;
          $prodFusion_cdgmezcla = $regProdLote->cdgmezcla;
          $prodFusion_cdgbobina = $regProdLote->cdgbobina;

          $longitud = $regProdLote->longitud; 

          $prodFusion_numrollos = 1;
          while ($longitud >= 500)
          { $longitud = $longitud-500;
            $prodFusion_numrollos++; } 

          $prodBobinaSelect->close;
        } else
        { $prodFusion_cdgbobina = '';
          $msg_info = 'Ingresar informacion de bobina.';
          $text_bobina = 'autofocus'; }
      } else
      { $prodFusion_cdgmaquina = '';
        $msg_alert = 'Ingresar informacion de maquina.';
        $text_maquina = 'autofocus'; }
    } else
    { $prodFusion_cdgempleado = '';
      $msg_alert = 'Ingresar informacion de empleado.';
      $text_empleado = 'autofocus'; }

    if ($prodFusion_cdgempleado == '' OR $prodFusion_cdgmaquina == '' OR $prodFusion_cdgbobina == '')
    { echo '
    <form id="form_produccion" name="form_produccion" method="post" action="prodFusion.php"/>
      <table align="center">
        <thead>
          <tr>
            <th>'.$sistModulo_modulo.'</th>
          </tr>
        </thead>
        <tbody>
          <tr><td><label for="lbl_ttlempleado">Empleado</label><br/>
              <input type="text" style="width:120px" id="text_empleado" name="text_empleado" value="'.$prodFusion_idempleado.'" '.$text_empleado.' required/></td></tr>
          <tr><td><label for="lbl_ttlmaquina">M&aacute;quina</label><br/>
              <input type="text" style="width:120px" id="text_maquina" name="text_maquina" value="'.$prodFusion_idmaquina.'" '.$text_maquina.' required/></td></tr>
          <tr><td><label for="lbl_ttlbobina">Bobina</label><br/>
              <input type="text" style="width:120px" id="text_bobina" name="text_bobina" value="'.$prodFusion_cdgbobina.'" '.$text_bobina.' required/></td></tr>
        </tbody>
        <tfoot>
          <tr><th align="right"><input type="submit" id="button_buscar" name="button_buscar" value="Buscar" /></th></tr>
        </tfoot>
      </table><br/>'.$msg_info.'

    </form>'; }
    else
    { $text_longitud[1] = 'autofocus';

      $error_longitud = false;
      $error_amplitud = false;
      $error_peso = false;

      for ($id_rollo = 1; $id_rollo <= $prodFusion_numrollos; $id_rollo++)
      { if (is_numeric($_POST['text_longitud'.$id_rollo]))
        { $prodFusion_longitudrollo[$id_rollo] = $_POST['text_longitud'.$id_rollo]; }
        else
        { $error_longitud = true;
          $text_longitud[$id_rollo] = 'autofocus';

          $id_rollo = $prodFusion_numrollos+1; }
      }

      if ($error_longitud == false)
      { for ($id_rollo = 1; $id_rollo <= $prodFusion_numrollos; $id_rollo++)
        { if (is_numeric($_POST['text_amplitud'.$id_rollo]))
          { $prodFusion_amplitudrollo[$id_rollo] = $_POST['text_amplitud'.$id_rollo]; }
          else
          { $error_amplitud = true;
            $text_amplitud[$id_rollo] = 'autofocus';

            $id_rollo = $prodFusion_numrollos+1; }
        }

        if ($error_amplitud == false)
        { for ($id_rollo = 1; $id_rollo <= $prodFusion_numrollos; $id_rollo++)
          { if (is_numeric($_POST['text_peso'.$id_rollo]))
            { $prodFusion_pesorollo[$id_rollo] = $_POST['text_peso'.$id_rollo]; }
            else
            { $error_peso = true;
              $text_peso[$id_rollo] = 'autofocus';

              $id_rollo = $prodFusion_numrollos+1; }
          }

          if ($error_peso == false)
          { for ($id_rollo = 1; $id_rollo <= $prodFusion_numrollos; $id_rollo++)
            { if (is_numeric($_POST['text_bandera'.$id_rollo]))
              { $prodFusion_banderarollo[$id_rollo] = $_POST['text_bandera'.$id_rollo]; }
              else
              { $error_bandera = true;
                $text_bandera[$id_rollo] = 'autofocus';

                $id_rollo = $prodFusion_numrollos+1; }
            }
          }

        }
      }

      if ($error_longitud == false)
      { if ($error_amplitud == false)
        { if ($error_peso == false)
          { if ($error_bandera == false)
            { if ($_POST['button_salvar'])
              { $fchoperacion = date('Y-m-d');

                $link_mysqli = conectar();
                $link_mysqli->query("
                  INSERT INTO prodbobinaproc
                    (cdgbobina, cdgproceso, longitud, amplitud, peso, fchmovimiento)
                  VALUES
                    ('".$prodFusion_cdgbobina."', '40', '".$prodFusion_longitud."', '".$prodFusion_amplitud."', '".$prodFusion_peso."', NOW())");

                $link_mysqli = conectar();
                $link_mysqli->query("

                  INSERT INTO prodbobinaope
                    (cdgbobina, cdgoperacion, cdgempleado, cdgmaquina, fchoperacion, fchmovimiento)
                  VALUES
                    ('".$prodFusion_cdgbobina."', '40001', '".$prodFusion_cdgempleado."', '".$prodFusion_cdgmaquina."', '".$fchoperacion."', NOW())");

                for ($id_rollo = 1; $id_rollo <= $prodFusion_numrollos; $id_rollo++)
                { $prodFusion_cdgrollo = substr($prodFusion_cdgbobina,0,9).$id_rollo.'00';

                  if ($prodFusion_longitudrollo[$id_rollo] > 0)
                  { $link_mysqli = conectar();
                    $link_mysqli->query("
                      INSERT INTO prodrollo
                      (cdgbobina, rollo, cdgproducto, cdgproceso, longitud, amplitud, peso, bandera, cdgrollo, fchmovimiento)
                      VALUES
                      ('".$prodFusion_cdgbobina."', '".$id_rollo."', '".$prodFusion_cdgimpresion."', '50', '".$prodFusion_longitudrollo[$id_rollo]."', '".$prodFusion_amplitudrollo[$id_rollo]."', '".$prodFusion_pesorollo[$id_rollo]."', '".$prodFusion_banderarollo[$id_rollo]."', '".$prodFusion_cdgrollo."', NOW())");

                    $msg_alert .= 'Fusion de rollo '.$id_rollo.' \n'; 

                    $link_mysqli = conectar();
                    $prodRolloOpeInsert = $link_mysqli->query("
                      INSERT INTO prodrolloope
                        (cdgrollo, cdgoperacion, cdgempleado, cdgmaquina, longitudini, longitudfin, pesoini, pesofin, numbandera, fchoperacion, fchmovimiento)
                      VALUES
                        ('".$prodFusion_cdgrollo."', '40001', '".$prodFusion_cdgempleado."', '".$prodFusion_cdgmaquina."', '".$prodFusion_longitudrollo[$id_rollo]."', '".$prodFusion_longitudrollo[$id_rollo]."', '".$prodFusion_pesorollo[$id_rollo]."', '".$prodFusion_pesorollo[$id_rollo]."', '".$prodFusion_banderarollo[$id_rollo]."', '".$fchoperacion."', NOW())");
                  }
                }

                $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE prodbobina
                  SET sttbobina = '0',
                    fchmovimiento = NOW()
                  WHERE cdgbobina = '".$prodFusion_cdgbobina."'");

                if ($link_mysqli->affected_rows > 0)
                { $msg_alert .= 'Bobina afectada.'; }
              }
            } else
            { $msg_alert = 'Favor de ingresar la informacion de BANDERAS, de forma correcta.'; }
          } else
          { $msg_alert = 'Favor de ingresar la informacion de los NUEVOS pesos, de forma correcta.'; }
        } else
        { $msg_alert = 'Favor de ingresar la informacion de los NUEVOS anchos planos, de forma correcta.'; }
      } else
      { $msg_info = 'Favor de ingresar la informacion de las NUEVAS longitudes.'; }
      

      echo '
    <form id="form_produccion" name="form_produccion" method="post" action="prodFusion.php"/>
      <input type="hidden" id="text_empleado" name="text_empleado" value="'.$prodFusion_cdgempleado.'" />
      <input type="hidden" id="text_maquina" name="text_maquina" value="'.$prodFusion_cdgmaquina.'" />
      <input type="hidden" id="text_bobina" name="text_bobina" value="'.$prodFusion_cdgbobina.'" />
      
      <table align="center">
        <thead>
          <tr><th colspan="2">'.$sistModulo_modulo.'</th></tr>
        </thead>
        <tbody>
          <tr><td><label for="label_ttlempleado">Empleado</label><br/>
              <label for="label_empleado">'.$prodFusion_idempleado.' '.$prodFusion_empleado.'</label></td>
            <td><label for="label_ttlmaquina">M&aacute;quina</label><br/>
              <label for="label_maquina">'.$prodFusion_idmaquina.' '.$prodFusion_maquina.'</label></td></tr>
          <tr><td align="right">
              <table>
                <thead>
                  <tr><th colspan="3">Informaci&oacute;n</th></tr>
                </thead>
                <tbody>
                  <tr><td>Proyecto</td>
                    <td colspan="2">'.$prodFusion_proyecto.'</td></tr>
                  <tr><td>Impresi&oacute;n</td>
                    <td colspan="2">'.$prodFusion_impresion.'</td></tr>
                  <tr><td><h1>NoOP</h1></td>
                    <td colspan="2" align="right"><a href="prodFusion.php?cdgempleado='.$prodFusion_idempleado.'&cdgmaquina='.$prodFusion_idmaquina.'" target="cargador">Cambio de bobina</a><br/>
                      <h1>'.$prodFusion_noop.'</h1></td></tr>
                </tbody>
                <tfoot align="left">
                  <tr><td>Longitud</td>
                    <td align="right">'.number_format($prodFusion_longitud,3).'</td>
                    <td>Metros</td></tr>
                  <tr><td>Ancho plano</td>
                    <td align="right">'.number_format($prodFusion_amplitud).'</td>
                    <td>Milimetros</td></tr>
                  <tr><td>Peso</td>
                    <td align="right">'.number_format($prodFusion_peso,3).'</td>
                    <td>Kilogramos</td></tr>
                </tfoot>
              </table><br/>              
            </td>
            <td align="left">
              <table>
                <thead>
                  <tr><th colspan="5">Captura</th></tr>
                </thead>
                <tbody>';

      for ($id_rollo = 1; $id_rollo <= $prodFusion_numrollos; $id_rollo++)
      { echo '
                  <tr><td>Rollo #'.$id_rollo.' <br/></td>
                    <td><label for="ttl_longitud'.$id_rollo.'">Longitud</label><br/>
                      <input type="text" id="text_longitud'.$id_rollo.'" name="text_longitud'.$id_rollo.'" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodFusion_longitudrollo[$id_rollo].'" title="Longitud del rollo '.$id_rollo.' (mts)" '.$text_longitud[$id_rollo].' required/></td>
                    <td><label for="ttl_amplitud'.$id_rollo.'">Ancho plano</label><br/>
                      <input type="text" id="text_amplitud'.$id_rollo.'" name="text_amplitud'.$id_rollo.'" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodFusion_amplitudrollo[$id_rollo].'" title="Amplitud del rollo '.$id_rollo.' (mm)" '.$text_amplitud[$id_rollo].' required/></td>
                    <td><label for="ttl_bandera'.$id_rollo.'">Banderas</label><br/>
                      <input type="text" id="text_bandera'.$id_rollo.'" name="text_bandera'.$id_rollo.'" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodFusion_banderarollo[$id_rollo].'" title="Banderas del rollo '.$id_rollo.'" '.$text_bandera[$id_rollo].' required/></td>
                    <td><label for="ttl_peso'.$id_rollo.'">Kilogramos</label><br/>
                      <input type="text" id="text_peso'.$id_rollo.'" name="text_peso'.$id_rollo.'" style="width:80px; text-align:right;" maxlenght="16" value="0" title="Peso del rollo '.$id_rollo.' (kgs)" '.$text_peso[$id_rollo].' required/></td></tr>'; }

      $id_rollo++;

      echo '
                </tbody>
                <tfoot>';
      /*
      echo '      <tr><td>Merma <br/></td>
                    <td>Longitud <br/>
                      <input type="text" id="text_longitud'.$id_rollo.'" name="text_longitud'.$id_rollo.'" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodFusion_longitudrollo[$id_rollo].'" title="Longitud del nuevo rollo" '.$text_longitud[$id_rollo].' disabled/></td>
                    <td>Amplitud <br/>
                      <input type="text" id="text_amplitud'.$id_rollo.'" name="text_amplitud'.$id_rollo.'" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodFusion_amplitudrollo[$id_rollo].'" title="Amplitud del nuevo rollo" '.$text_amplitud[$id_rollo].' disabled/></td>
                    <td><label for="ttl_kilogramos">Kilogramos</label><br/>
                      <input type="text" id="text_peso'.$id_rollo.'" name="text_peso'.$id_rollo.'" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodFusion_pesorollo[$id_rollo].'" title="Nuevo peso de lote/bobina" '.$text_peso[$id_rollo].' disabled/></tr>'; //*/
      echo '
                </tfoot>
              </table><br/>
              <stron>'.$msg_info.'</stron><br/> 
              <a href="pdf/prodRolloBC.php?cdgbobina='.$prodFusion_cdgbobina.'" target="_blank">Generar etiquetas</a>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr><th colspan="2" align="right">
              <input type="submit" id="button_salvar" name="button_salvar" value="Salvar" /></th></tr>
        </tfoot>
      </table>
    </form>'; }

   // Filtro de empleados por jornada
    //////////////////////////////////////
    $link_mysqli = conectar();
    $prodPaqueteEmpleados = $link_mysqli->query("
      SELECT prodrolloope.cdgempleado, rechempleado.empleado
      FROM prodrolloope, rechempleado
      WHERE cdgoperacion = '40001' AND
        prodrolloope.cdgempleado = rechempleado.cdgempleado AND
        prodrolloope.fchmovimiento LIKE '".$prodDestajo_fchfinal."%'
      GROUP BY prodrolloope.cdgempleado
      ORDER BY rechempleado.empleado");

    $id_empleado = 1;
    while ($regProdEmpleado = $prodPaqueteEmpleados->fetch_object())
    { $prodProduccion_cdgempleado[$id_empleado] = $regProdEmpleado->cdgempleado;
      $prodProduccion_empleado[$id_empleado] = $regProdEmpleado->empleado; 

      $id_empleado++; }

    $num_empleados = $prodPaqueteEmpleados->num_rows;

     // Filtro de horas por jornada
    //////////////////////////////////////
    $link_mysqli = conectar();
    $prodPaqueteHorarios = $link_mysqli->query("
      SELECT CONCAT(SUBSTRING(fchmovimiento,12,3),'00') AS horas
      FROM prodrolloope
      WHERE cdgoperacion = '40001' AND
        fchmovimiento LIKE '".$prodDestajo_fchfinal."%'
      GROUP BY SUBSTRING(fchmovimiento,1,14)");

    $id_hora = 1;
    while ($regProdHorario = $prodPaqueteHorarios->fetch_object())
    { $prodProduccion_hora[$id_hora] = $regProdHorario->horas; 

      $id_hora++; }

    $num_horas = $prodPaqueteHorarios->num_rows; 

     // Filtro de produccion por jornada
    //////////////////////////////////////
    $link_mysqli = conectar();
    $prodPaqueteProduccion = $link_mysqli->query("
      SELECT cdgempleado, CONCAT(SUBSTRING(fchmovimiento,12,3),'00') AS hora, SUM(longitudfin) AS paquetes, SUM(numbandera) AS banderas
      FROM prodrolloope
      WHERE cdgoperacion = '40001' AND
        fchmovimiento LIKE '".$prodDestajo_fchfinal."%'
      GROUP BY cdgempleado, SUBSTRING(fchmovimiento,1,14)");

    while ($regProdPaquetes = $prodPaqueteProduccion->fetch_object())
    { $prodProduccion_paquetes[$regProdPaquetes->cdgempleado][$regProdPaquetes->hora] = $regProdPaquetes->paquetes;
      $prodProduccion_paquetesxempleado[$regProdPaquetes->cdgempleado] += $regProdPaquetes->paquetes;
      $prodProduccion_paquetesxhora[$regProdPaquetes->hora] += $regProdPaquetes->paquetes;
      $prodProduccion_paquetesxdia += $regProdPaquetes->paquetes;

      $prodProduccion_banderas[$regProdPaquetes->cdgempleado][$regProdPaquetes->hora] = $regProdPaquetes->banderas;
      $prodProduccion_banderasxempleado[$regProdPaquetes->cdgempleado] += $regProdPaquetes->banderas;
      $prodProduccion_banderasxhora[$regProdPaquetes->hora] += $regProdPaquetes->banderas;
      $prodProduccion_banderasxdia += $regProdPaquetes->banderas; }

    echo '
    <br/>
    <table align="center">
      <thead>
        <tr align="right"><td><b>Horarios</b></td>';

    for ($id_empleado=1; $id_empleado<=$num_empleados; $id_empleado++)
    { echo '
          <th colspan="2">'.$prodProduccion_empleado[$id_empleado].'</th>'; }

    echo '
          <td colspan="2"><b>Suma por <br/>hora</b><td>
        </tr>
      </thead>
      <tbody>';

    for ($id_hora=1; $id_hora<=$num_horas; $id_hora++)
    { echo '
        <tr align="right">
          <th>'.$prodProduccion_hora[$id_hora].'</th>';

      for ($id_empleado=1; $id_empleado<=$num_empleados; $id_empleado++)
      { if ($prodProduccion_paquetes[$prodProduccion_cdgempleado[$id_empleado]][$prodProduccion_hora[$id_hora]] > 0)
        { echo '
          <td><b>'.$prodProduccion_paquetes[$prodProduccion_cdgempleado[$id_empleado]][$prodProduccion_hora[$id_hora]].'</b></td> <td>B <b>'.$prodProduccion_banderas[$prodProduccion_cdgempleado[$id_empleado]][$prodProduccion_hora[$id_hora]].'</b></td>'; }
        else
        { echo '
          <th colspan="2">? = 0</th>';}
      }

      echo '
          <th>'.$prodProduccion_paquetesxhora[$prodProduccion_hora[$id_hora]].'</th>
        <tr>'; } 

    echo '
        <tr align="right"><td><b>Suma por <br/>empleado</b></td>';

    for ($id_empleado=1; $id_empleado<=$num_empleados; $id_empleado++)
    { echo '
          <th>'.$prodProduccion_paquetesxempleado[$prodProduccion_cdgempleado[$id_empleado]].'</th> <th>B '.$prodProduccion_banderasxempleado[$prodProduccion_cdgempleado[$id_empleado]].'</th>'; }

    echo '
          <td><b>'.$prodProduccion_paquetesxdia.'</b></td><td>B <b>'.$prodProduccion_banderasxdia.'</b></td>
        </tr>
      </tbody>
    </table>';        

    if ($msg_alert != '')
    { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
  ?>

  </body>
</html>
