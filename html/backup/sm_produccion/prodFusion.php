<!DOCTYPE html>
<html>
  <head>
    <title>Producci&oacute;n Fusion</title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $barCode = '<img alt="Codigo de barras" src="../img_sistema/barcode.png" height="16" border="0"/>';
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
    $prodFusion_cdgdefecto = $_POST['rdo_defecto'];    
    
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
              pdtodiseno.diseno,
              pdtodiseno.alto,
              pdtodiseno.rollo,
              pdtoimpresion.impresion,
              pdtoimpresion.cdgimpresion,
              prodbobina.longitud,
              prodbobina.amplitud,
              prodbobina.peso,
              prodbobina.cdgbobina,
              prodbobina.sttbobina
            FROM proglote,
              prodlote,
              prodbobina,
              pdtoimpresion,
              pdtodiseno
            WHERE (proglote.cdglote = prodlote.cdglote AND prodlote.cdglote = prodbobina.cdglote) AND
            ((prodbobina.cdgbobina = '".$prodFusion_cdgbobina."') OR (CONCAT(prodlote.noop,'-',prodbobina.bobina) = '".$prodFusion_cdgbobina."')) AND
             (prodbobina.cdgproducto = pdtoimpresion.cdgimpresion AND pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno)");

        if ($prodBobinaSelect->num_rows > 0)
        { // Filtra la bobina
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
          $prodFusion_proyecto = $regProdLote->diseno;
          $prodFusion_impresion = $regProdLote->impresion;
          $prodFusion_alto = $regProdLote->alto;
          $prodFusion_rollo = $regProdLote->rollo;
          $prodFusion_cdgimpresion = $regProdLote->cdgimpresion;
          $prodFusion_cdgmezcla = $regProdLote->cdgmezcla;
          $prodFusion_cdgbobina = $regProdLote->cdgbobina;
          $prodFusion_sttbobina = $regProdLote->sttbobina;

          $clcMillares = ($regProdLote->longitud/$prodFusion_alto); 

          $prodFusion_numrollos = number_format((($prodFusion_longitud/$prodFusion_alto)/$prodFusion_rollo));

          if ($prodFusion_sttbobina == '8')
          { $text_longitud[1] = 'autofocus';

            $error_longitud = false;

            for ($idRollo = 1; $idRollo <= $prodFusion_numrollos; $idRollo++)
            { if (is_numeric($_POST['text_longitud'.$idRollo]))
              { $prodFusion_longitudrollo[$idRollo] = $_POST['text_longitud'.$idRollo]; }
              else
              { $error_longitud = true;
                $text_longitud[$idRollo] = 'autofocus';

                $idRollo = $prodFusion_numrollos+1; }
            }

            if ($error_longitud == false)
            { $error_amplitud = false;
              
              for ($idRollo = 1; $idRollo <= $prodFusion_numrollos; $idRollo++)
              { if (is_numeric($_POST['text_amplitud'.$idRollo]))
                { $prodFusion_amplitudrollo[$idRollo] = $_POST['text_amplitud'.$idRollo]; }
                else
                { $error_amplitud = true;
                  $text_amplitud[$idRollo] = 'autofocus';

                  $idRollo = $prodFusion_numrollos+1; }
              }

              if ($error_amplitud == false)
              { $error_peso = false;
                
                for ($idRollo = 1; $idRollo <= $prodFusion_numrollos; $idRollo++)
                { if (is_numeric($_POST['text_peso'.$idRollo]))
                  { $prodFusion_pesorollo[$idRollo] = $_POST['text_peso'.$idRollo]; }
                  else
                  { $error_peso = true;
                    $text_peso[$idRollo] = 'autofocus';

                    $idRollo = $prodFusion_numrollos+1; }
                }

                if ($error_peso == false)
                { $error_bandera = false;

                  for ($idRollo = 1; $idRollo <= $prodFusion_numrollos; $idRollo++)
                  { if (is_numeric($_POST['text_bandera'.$idRollo]))
                    { $prodFusion_banderarollo[$idRollo] = $_POST['text_bandera'.$idRollo]; }
                    else
                    { $error_bandera = true;
                      $text_bandera[$idRollo] = 'autofocus';

                      $idRollo = $prodFusion_numrollos+1; }
                  }

                  if ($error_bandera == false)
                  { if ($_POST['button_salvar'])
                    { $fchoperacion = date('Y-m-d');
                      $link_mysqli = conectar();
                      $link_mysqli->query("

                        INSERT INTO prodbobinaope
                          (cdgbobina, cdgoperacion, cdgempleado, cdgmaquina, longitud, peso, merma, fchoperacion, fchmovimiento)
                        VALUES
                          ('".$prodFusion_cdgbobina."', '40001', '".$prodFusion_cdgempleado."', '".$prodFusion_cdgmaquina."', '".$prodFusion_longitud."', '".$prodFusion_peso."', '".$prodFusion_merma."', '".$fchoperacion."', NOW())");

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
                              (cdgrollo, cdgoperacion, cdgempleado, cdgmaquina, longitud, peso, numbandera, fchoperacion, fchmovimiento)
                            VALUES
                              ('".$prodFusion_cdgrollo."', '40001', '".$prodFusion_cdgempleado."', '".$prodFusion_cdgmaquina."', '".$prodFusion_longitudrollo[$id_rollo]."', '".$prodFusion_pesorollo[$id_rollo]."', '".$prodFusion_banderarollo[$id_rollo]."', '".$fchoperacion."', NOW())");
                        }
                      }

                      $link_mysqli = conectar();
                      $link_mysqli->query("
                        UPDATE prodbobina
                        SET sttbobina = '9',
                          fchmovimiento = NOW()
                        WHERE cdgbobina = '".$prodFusion_cdgbobina."'");

                      if ($link_mysqli->affected_rows > 0)
                      { $msg_alert .= 'Bobina afectada.'; 
                        $barCode = '<a href="pdf/prodRolloBC.php?cdgbobina='.$prodFusion_cdgbobina.'"><img alt="Codigo de barras" src="../img_sistema/barcode.png" height="40" border="0" autofocus/></a>';
                        $prodFusion_cdgbobina = ''; }
                    }
                  } else
                  { $msg_alert = 'Favor de ingresar la informacion de BANDERAS, de forma correcta.'; }
                } else
                { $msg_alert = 'Favor de ingresar la informacion de los NUEVOS pesos, de forma correcta.'; }
              } else
              { $msg_alert = 'Favor de ingresar la informacion de los NUEVOS anchos planos, de forma correcta.'; }
            } else
            { $msg_info = 'Favor de ingresar la informacion de las NUEVAS longitudes.'; }

          } else
          { if (substr($sistModulo_permiso,0,2) == 'rw' AND $prodFusion_sttbobina == '0')
            { if ($_POST['button_salvar'])
              { if (is_numeric($_POST['text_merma']))
                { $prodFusion_merma = $_POST['text_merma'];

                  $link_mysqli = conectar();
                  $link_mysqli->query("
                    UPDATE prodbobinaope
                    SET merma = '".$prodFusion_merma."',
                      cdgdefecto = '".$prodFusion_cdgdefecto."'
                    WHERE cdgempleado = '".$prodFusion_cdgempleado."' AND
                      cdgmaquina = '".$prodFusion_cdgmaquina."' AND
                      cdgbobina = '".$prodFusion_cdgbobina."' AND
                      fchoperacion = '".$fchoperacion."' AND
                      sttbobina = '9'"); 

                  if ($link_mysqli->affected_rows > 0)
                  { $msg_alert = "Merma registrada."; }
                  else
                  { $msg_alert = "Error es alguno de los datos solicitados o el movimiento esta fuera de fecha."; }
                }

                $prodFusion_cdgbobina = '';
                $text_bobina = 'autofocus';
              }
            } 
          }
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
            <th colspan="4" align="left">'.utf8_decode($sistModulo_modulo).'</th>
          </tr>
        </thead>
        <tbody>
          <tr align="center">
            <td><label for="lbl_ttlempleado"><strong>Empleado</strong></label><br/>
              <input type="text" style="width:120px" id="text_empleado" name="text_empleado" value="'.$prodFusion_idempleado.'" '.$text_empleado.' required/></td>
            <td><label for="lbl_ttlmaquina"><strong>M&aacute;quina</strong></label><br/>
              <input type="text" style="width:120px" id="text_maquina" name="text_maquina" value="'.$prodFusion_idmaquina.'" '.$text_maquina.' required/></td>
            <td><label for="lbl_ttlbobina"><strong>Bobina</strong></label><br/>
              <input type="text" style="width:120px" id="text_bobina" name="text_bobina" value="'.$prodFusion_cdgbobina.'" '.$text_bobina.' required/></td>
            <td>'.$barCode.'</td></tr>
        </tbody>
        <tfoot>
          <tr><th colspan="4" align="right"><input type="submit" id="button_buscar" name="button_buscar" value="Buscar" /></th></tr>
        </tfoot>
      </table>
    </form>'; }
    else
    { echo '
    <form id="form_produccion" name="form_produccion" method="post" action="prodFusion.php"/>
      <input type="hidden" id="text_empleado" name="text_empleado" value="'.$prodFusion_cdgempleado.'" />
      <input type="hidden" id="text_maquina" name="text_maquina" value="'.$prodFusion_cdgmaquina.'" />
      <input type="hidden" id="text_bobina" name="text_bobina" value="'.$prodFusion_cdgbobina.'" />
      
      <table align="center">
        <thead>
          <tr><th colspan="3">'.utf8_decode($sistModulo_modulo).'</th></tr>
        </thead>
        <tbody>
          <tr><th>'.utf8_decode('Información').'</th>
            <th>Captura</th>
            <th>Defecto</th></tr>          
          <tr><td>
              <label for="lbl_ttlnoop"><strong>NoOP</strong></label><br />
              <label for="lbl_noop"><a href="prodFusion.php?cdgempleado='.$prodFusion_idempleado.'&cdgmaquina='.$prodFusion_idmaquina.'" target="cargador"><h1>'.$prodFusion_noop.'</h1></a></label><br />
              <label for="lbl_ttlimpresion"><strong>'.utf8_decode('Impresión').'</strong></label><br />
              <label for="lbl_impresion">&nbsp;<em><strong>'.$prodFusion_idimpresion.'</strong> '.$prodFusion_impresion.'</em></label><br />
              <label for="lbl_ttllongitudori"><strong>Longitud</strong></label><br />
              <label for="lbl_longitudori">&nbsp;<em><strong>'.number_format($prodFusion_longitud,2).'</strong>  Metros</em></label><br />
              <label for="lbl_ttlpesoori"><strong>Peso</strong></label><br />
              <label for="lbl_pesoori">&nbsp;<em><strong>'.number_format($prodFusion_peso,3).'</strong> Kilogramos</em></label><br />
              <label for="lbl_ttlanchoori"><strong>Ancho en manga</strong></label><br />
              <label for="lbl_anchoori">&nbsp;<em><strong>'.number_format($prodFusion_amplitud).'</strong> '.utf8_decode('Milímetros').'</em></label><br /></td>
            <td align="left">
              <table>';

      for ($idRollo = 1; $idRollo <= $prodFusion_numrollos; $idRollo++)
      { echo '
                <tr><td>Rollo #'.$idRollo.' <br/></td>
                  <td><label for="ttl_longitud'.$idRollo.'">Longitud</label><br/>
                    <input type="text" id="text_longitud'.$idRollo.'" name="text_longitud'.$idRollo.'" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodFusion_longitudrollo[$idRollo].'" title="Longitud del rollo '.$idRollo.' (mts)" '.$text_longitud[$idRollo].' required/></td>
                  <td><label for="ttl_amplitud'.$idRollo.'">Ancho plano</label><br/>
                    <input type="text" id="text_amplitud'.$idRollo.'" name="text_amplitud'.$idRollo.'" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodFusion_amplitudrollo[$idRollo].'" title="Amplitud del rollo '.$idRollo.' (mm)" '.$text_amplitud[$idRollo].' required/></td>
                  <td><label for="ttl_bandera'.$idRollo.'">Banderas</label><br/>
                    <input type="text" id="text_bandera'.$idRollo.'" name="text_bandera'.$idRollo.'" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodFusion_banderarollo[$idRollo].'" title="Banderas del rollo '.$idRollo.'" '.$text_bandera[$idRollo].' required/></td>
                  <td><label for="ttl_peso'.$idRollo.'">Kilogramos</label><br/>
                    <input type="text" id="text_peso'.$idRollo.'" name="text_peso'.$idRollo.'" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodFusion_pesorollo[$idRollo].'" title="Peso del rollo '.$idRollo.' (kgs)" '.$text_peso[$idRollo].' required/></td></tr>'; }

      echo '
              </table>
            </td>
            <td width="200">
              <label for="ttl_ttlmerma"><strong>Merma</strong></label><br />
              <input type="text" id="text_merma" name="text_merma" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodFusion_merma.'" title="Cantidad de merma por bobina" '.$text_merma.' required/><br /><br />';

      $link_mysqli = conectar();
      $prodDefectoSelect = $link_mysqli->query("
        SELECT proddefecto.cdgdefecto,
          proddefecto.defecto,
          proddefectope.cdgoperacion,
          proddefectope.detalle
        FROM proddefecto, 
          proddefectope 
        WHERE proddefecto.cdgdefecto = proddefectope.cdgdefecto AND
          proddefectope.cdgoperacion = '40001'
        ORDER BY proddefecto.cdgdefecto");

      if ($prodDefectoSelect->num_rows > 0)
      { while ($regQuery = $prodDefectoSelect->fetch_object())
        { echo '
              <input type="radio" name="rdo_defecto" id="rdo_defecto" value="'.$regQuery->cdgdefecto.'" required />
              <label for="lbl_ttl'.$regQuery->cdgdefecto.'"><strong>'.$regQuery->defecto.'</strong></label><br />
              <label for="lbl_ttl'.$regQuery->cdgdefecto.$regQuery->cdgoperacion.'"><em>'.$regQuery->detalle.'</em></label><br /><br />'; }
      }

      echo '</td></tr>
          <tr><td colspan="3">
            <label for="lbl_ttlempleado"><strong>Empleado</strong></label><br />
            <label for="lbl_empleado"><em><strong>'.$prodFusion_idempleado.'</strong> '.$prodFusion_empleado.'</em></label><br />
            <label for="lbl_ttlmaquina"><strong>'.utf8_decode('Máquina').'</strong></label><br/>
            <label for="lbl_maquina"><em><strong>'.$prodFusion_idmaquina.'</strong> '.$prodFusion_maquina.'</em></label></td></tr>          
        </tbody>
        <tfoot>
          <tr><th colspan="3" align="right">
              <input type="submit" id="button_salvar" name="button_salvar" value="Salvar" /></th></tr>
        </tfoot>
      </table>
    </form>'; }

    $link_mysqli = conectar();
    $prodBobinaOpeMermaByCdgDefecto = $link_mysqli->query("
      SELECT prodbobinaope.cdgdefecto, 
        proddefecto.defecto, 
        proddefectope.detalle, 
        SUM(merma) AS merma
      FROM prodbobinaope,
        proddefecto,
        proddefectope
      WHERE (prodbobinaope.fchoperacion = '".$prodDestajo_fchfinal."' AND
        prodbobinaope.cdgoperacion = '40001') AND
        prodbobinaope.cdgdefecto = proddefecto.cdgdefecto AND
       (proddefecto.cdgdefecto = proddefectope.cdgdefecto AND
        proddefectope.cdgoperacion = prodbobinaope.cdgoperacion)
      GROUP BY prodbobinaope.cdgdefecto, 
        proddefecto.defecto,
        proddefectope.detalle");

    if ($prodBobinaOpeMermaByCdgDefecto->num_rows > 0)
    { echo '<br />
    <table align="center">
      <thead>
        <tr><th>Defecto</th><th>Merma</th></tr>
      <thead>
      <tbody>';

      while ($regQuery = $prodBobinaOpeMermaByCdgDefecto->fetch_object())
      { echo '
        <tr><td><strong>'.$regQuery->defecto.'</strong><br /><em>'.$regQuery->detalle.'</em></td>
          <td><strong>'.number_format($regQuery->merma,3).'</strong></td></tr>';}

      echo '
      </tbody>
    </table>'; }

     // Filtro de empleados por jornada
    //////////////////////////////////////
    $link_mysqli = conectar();
    $prodPaqueteEmpleados = $link_mysqli->query("
      SELECT prodrolloope.cdgempleado, 
        rechempleado.empleado
      FROM prodrolloope, 
        rechempleado
      WHERE cdgoperacion = '40001' AND
        prodrolloope.cdgempleado = rechempleado.cdgempleado AND
        prodrolloope.fchmovimiento LIKE '".$prodDestajo_fchfinal."%'
      GROUP BY prodrolloope.cdgempleado
      ORDER BY rechempleado.empleado");

    $id_empleado = 1;
    while ($regProdEmpleado = $prodPaqueteEmpleados->fetch_object())
    { $prodData_cdgempleado[$id_empleado] = $regProdEmpleado->cdgempleado;
      $prodData_empleado[$id_empleado] = $regProdEmpleado->empleado; 

      $id_empleado++; }

    $num_empleados = $prodPaqueteEmpleados->num_rows;

     // Filtro de horas por jornada
    //////////////////////////////////////
    $link_mysqli = conectar();
    $prodRolloOpeSelect = $link_mysqli->query("
      SELECT CONCAT(SUBSTRING(fchmovimiento,12,3),'00') AS horas
      FROM prodrolloope
      WHERE cdgoperacion = '40001' AND
        fchmovimiento LIKE '".$prodDestajo_fchfinal."%'
      GROUP BY SUBSTRING(fchmovimiento,1,14)");

    $id_hora = 1;
    while ($regProdHorario = $prodRolloOpeSelect->fetch_object())
    { $prodData_hora[$id_hora] = $regProdHorario->horas; 

      $id_hora++; }

    $num_horas = $prodRolloOpeSelect->num_rows; 

     // Filtro de produccion por jornada
    //////////////////////////////////////
    $link_mysqli = conectar();
    $prodBobinaOpeSelect = $link_mysqli->query("
      SELECT cdgempleado, 
        CONCAT(SUBSTRING(fchmovimiento,12,3),'00') AS hora, 
        SUM(longitud) AS longitud, 
        SUM(merma) AS merma
      FROM prodbobinaope
      WHERE cdgoperacion = '40001' AND
        fchmovimiento LIKE '".$prodDestajo_fchfinal."%'
      GROUP BY cdgempleado, SUBSTRING(fchmovimiento,1,14)");

    while ($regQuery = $prodBobinaOpeSelect->fetch_object())
    { $prodData_mtrs[$regQuery->cdgempleado][$regQuery->hora] = $regQuery->longitud;
      $prodData_mtrsxempleado[$regQuery->cdgempleado] += $regQuery->longitud;
      $prodData_mtrsxhora[$regQuery->hora] += $regQuery->longitud;
      $prodData_mtrsxdia += $regQuery->longitud;

      $prodData_merma[$regQuery->cdgempleado][$regQuery->hora] = $regQuery->merma;
      $prodData_mermaxempleado[$regQuery->cdgempleado] += $regQuery->merma;
      $prodData_mermaxhora[$regQuery->hora] += $regQuery->merma;
      $prodData_mermaxdia += $regQuery->merma; }

    $link_mysqli = conectar();
    $prodRolloOpeSelect = $link_mysqli->query("
      SELECT cdgempleado, 
        CONCAT(SUBSTRING(fchmovimiento,12,3),'00') AS hora, 
        SUM(longitud) AS longitud, 
        SUM(numbandera) AS bandera
      FROM prodrolloope
      WHERE cdgoperacion = '40001' AND
        fchmovimiento LIKE '".$prodDestajo_fchfinal."%'
      GROUP BY cdgempleado, SUBSTRING(fchmovimiento,1,14)");

    while ($regQuery = $prodRolloOpeSelect->fetch_object())
    { $prodData_metros[$regQuery->cdgempleado][$regQuery->hora] = $regQuery->longitud;
      $prodData_metrosxempleado[$regQuery->cdgempleado] += $regQuery->longitud;
      $prodData_metrosxhora[$regQuery->hora] += $regQuery->longitud;
      $prodData_metrosxdia += $regQuery->longitud;

      $prodData_banderas[$regQuery->cdgempleado][$regQuery->hora] = $regQuery->bandera;
      $prodData_banderasxempleado[$regQuery->cdgempleado] += $regQuery->bandera;
      $prodData_banderasxhora[$regQuery->hora] += $regQuery->bandera;
      $prodData_banderasxdia += $regQuery->bandera; }

    echo '
    <br/>
    <table align="center">
      <thead>
        <tr><td></td>';

    for ($id_empleado=1; $id_empleado<=$num_empleados; $id_empleado++)
    { echo '
          <td colspan="4"><strong>'.$prodData_empleado[$id_empleado].'</strong></td>'; }

    echo '
          <td colspan="4"><b>Suma por hora</b><td>
        <tr align="right"><td><b>Horarios</b></td>';

    for ($id_empleado=1; $id_empleado<=$num_empleados; $id_empleado++)
    { echo '
          <th colspan="2" align="center">Metros</th>
          <th>Merma</th>
          <th>Banderas</th>'; }

    echo '
          <td colspan="2" align="center">Metros</td>
          <td>Merma</td>
          <td>Banderas</td><td>
        </tr>
      </thead>
      <tbody>';

    for ($id_hora=1; $id_hora<=$num_horas; $id_hora++)
    { echo '
        <tr align="right">
          <th>'.$prodData_hora[$id_hora].'</th>';

      for ($id_empleado=1; $id_empleado<=$num_empleados; $id_empleado++)
      { echo '
          <td>'.number_format($prodData_mtrs[$prodData_cdgempleado[$id_empleado]][$prodData_hora[$id_hora]],2).'</td> 
          <td>'.number_format($prodData_metros[$prodData_cdgempleado[$id_empleado]][$prodData_hora[$id_hora]],2).'</td> 
          <td>'.number_format($prodData_merma[$prodData_cdgempleado[$id_empleado]][$prodData_hora[$id_hora]],3).'</td> 
          <td align="center">'.$prodData_banderas[$prodData_cdgempleado[$id_empleado]][$prodData_hora[$id_hora]].'</td>'; }

      echo '
          <th>'.number_format($prodData_mtrsxhora[$prodData_hora[$id_hora]],2).'</th>
          <th>'.number_format($prodData_metrosxhora[$prodData_hora[$id_hora]],2).'</th>
          <th>'.number_format($prodData_mermaxhora[$prodData_hora[$id_hora]],3).'</th>
          <th align="center">'.$prodData_banderasxhora[$prodData_hora[$id_hora]].'</th>
        <tr>'; } 

    echo '
        <tr align="right"><td>Totales</td>';

    for ($id_empleado=1; $id_empleado<=$num_empleados; $id_empleado++)
    { echo '
          <th>'.number_format($prodData_mtrsxempleado[$prodData_cdgempleado[$id_empleado]],2).'</th> 
          <th>'.number_format($prodData_metrosxempleado[$prodData_cdgempleado[$id_empleado]],2).'</th> 
          <th>'.number_format($prodData_mermaxempleado[$prodData_cdgempleado[$id_empleado]],3).'</th> 
          <th align="center">'.$prodData_banderasxempleado[$prodData_cdgempleado[$id_empleado]].'</th>'; }

    echo '
          <td><strong>'.number_format($prodData_mtrsxdia,2).'</strong></td>
          <td><strong>'.number_format($prodData_metrosxdia,2).'</strong></td>
          <td><strong>'.number_format($prodData_mermaxdia,3).'</strong></td>
          <td align="center"><strong>'.$prodData_banderasxdia.'</strong></td>
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