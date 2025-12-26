<!DOCTYPE html>
<html>
  <head>
    <title>Producci&oacute;n asignaci&oacute;n de rollos a corte</title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '60050';

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

    if ($_POST['date_tablero'])
    { $prodProduccion_diatablero = $_POST['date_tablero']; }
    else
    { $prodProduccion_diatablero = $prodDestajo_fchfinal; }    
    
    ////////////////////////////////////////////

    //Buscame los datos ingresados
    $prodCorte_cdgempleado = trim($_POST['text_empleado']);
    $prodCorte_cdgmaquina = trim($_POST['text_maquina']);
    $prodCorte_cdgrollo = trim($_POST['text_rollo']);

    if ($_GET['cdgempleado'])
    { $prodCorte_cdgempleado = $_GET['cdgempleado']; }

    if ($_GET['cdgmaquina'])
    { $prodCorte_cdgmaquina = $_GET['cdgmaquina']; }

    //Buscar Empleado
    $link_mysql = conectar();
    $rechEmpleadoSelect = $link_mysql->query("
      SELECT * FROM rechempleado
      WHERE (idempleado = '".$prodCorte_cdgempleado."' OR cdgempleado = '".$prodCorte_cdgempleado."') AND 
        sttempleado >= '1'");

    if ($rechEmpleadoSelect->num_rows > 0)
    { $regRechEmpleado = $rechEmpleadoSelect->fetch_object();

      $prodCorte_idempleado = $regRechEmpleado->idempleado;
      $prodCorte_empleado = $regRechEmpleado->empleado;
      $prodCorte_cdgempleado = $regRechEmpleado->cdgempleado;

      //Buscar Maquina
      $link_mysql = conectar();
      $prodMaquinaSelect = $link_mysql->query("
        SELECT * FROM prodmaquina
        WHERE (idmaquina = '".$prodCorte_cdgmaquina."' OR cdgmaquina = '".$prodCorte_cdgmaquina."') AND 
          cdgproceso = '50' AND 
          sttmaquina >= '1'");

      if ($prodMaquinaSelect->num_rows > 0)
      { $regProdMaquina = $prodMaquinaSelect->fetch_object();

        $prodCorte_idmaquina = $regProdMaquina->idmaquina;
        $prodCorte_maquina = $regProdMaquina->maquina;
        $prodCorte_cdgmaquina = $regProdMaquina->cdgmaquina;

        //Buscar rollo
        $link_mysql = conectar();
        $prodRolloSelect = $link_mysql->query("
          SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) AS noop,
            prodrollo.cdgproducto,
            prodlote.cdgmezcla,
            prodrollo.longitud,
            prodrollo.amplitud,
            prodrollo.peso,
            prodrollo.cdgrollo,
            prodrollo.sttrollo
          FROM prodrollo,
            prodbobina,
            prodlote
          WHERE (prodrollo.sttrollo BETWEEN '1' AND '9') AND
            prodrollo.cdgbobina = prodbobina.cdgbobina AND
            prodbobina.cdglote = prodlote.cdglote AND
           (prodrollo.cdgrollo = '".$prodCorte_cdgrollo."' OR CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) = '".$prodCorte_cdgrollo."')");

        if ($prodRolloSelect->num_rows > 0)
        { $regProdRollo = $prodRolloSelect->fetch_object();

          if ($regProdRollo->sttrollo == '9')
          { $msg_answer = "Este rollo ya ha sido asignado a un empaque (Queso).";
            $prodCorte_cdgrollo = ''; }
          else
          { $prodCorte_noop = $regProdRollo->noop;
            $prodCorte_cdgproducto = $regProdRollo->cdgproducto;
            $prodCorte_cdgmezcla = $regProdRollo->cdgmezcla;
            $prodCorte_longitud = $regProdRollo->longitud;
            $prodCorte_amplitud = $regProdRollo->amplitud;
            $prodCorte_peso = $regProdRollo->peso;
            $prodCorte_cdgrollo = $regProdRollo->cdgrollo;
          
            $link_mysqli = conectar();
            $pdtoImpresionSelect = $link_mysqli->query("
              SELECT * FROM pdtoimpresion
              WHERE pdtoimpresion.cdgimpresion = '".$prodCorte_cdgproducto."'");

            if ($pdtoImpresionSelect->num_rows > 0)
            { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

              //$prodCorte_cdgimpresion = $regPdtoImpresion->cdgimpresion;
              $prodCorte_impresion = $regPdtoImpresion->impresion;
              $prodCorte_corte = $regPdtoImpresion->corte; 

              $text_peso = 'autofocus';

              // Salvar y generar etiquetas
              if ($_POST['button_salvar'])
              { if (is_numeric($_POST['text_peso']))
                { $prodCorte_peso = number_format($_POST['text_peso']); }
                $num_paquete = number_format($_POST['text_numpaquete']);

                if ($prodCorte_peso > 0)
                { if ($num_paquete > 0)
                  { $fchoperacion = date('Y-m-d');

                    $link_mysqli = conectar();
                    $link_mysqli->query("
                      INSERT INTO prodrolloope
                        (cdgrollo, cdgoperacion, cdgempleado, cdgmaquina, fchoperacion, fchmovimiento)
                      VALUES
                        ('".$prodCorte_cdgrollo."', '50001', '".$prodCorte_cdgempleado."', '".$prodCorte_cdgmaquina."', '".$fchoperacion."', NOW())");

                    for ($id_paquete = 1; $id_paquete <= $num_paquete; $id_paquete++)
                    { $prodCorte_cdgpaquete = substr($prodCorte_cdgrollo,0,10).str_pad($id_paquete,2,'0',STR_PAD_LEFT);

                      $link_mysqli = conectar();
                      $link_mysqli->query("
                        INSERT INTO prodpaquete
                          (cdgrollo, paquete, cdgproducto, fchmovimiento, cdgpaquete)
                        VALUES
                          ('".$prodCorte_cdgrollo."', '".$id_paquete."', '".$prodCorte_cdgproducto."', NOW(), '".$prodCorte_cdgpaquete."')");

                      $link_mysqli = conectar();
                      $link_mysqli->query("
                        INSERT INTO prodpaqueteope
                          (cdgpaquete, cdgoperacion, cdgempleado, cdgmaquina, fchoperacion, fchmovimiento)
                        VALUES
                          ('".$prodCorte_cdgpaquete."', '50001', '".$prodCorte_cdgempleado."', '".$prodCorte_cdgmaquina."', '".$fchoperacion."', NOW())"); }

                    $link_mysqli = conectar();
                    $link_mysqli->query("
                      UPDATE prodrollo 
                      SET peso = '".$prodCorte_peso."',
                        fchmovimiento = NOW(),
                        sttrollo = '5'
                      WHERE cdgrollo = '".$prodCorte_cdgrollo."'");

                    $msg_answer = 'Se generaron '.$num_paquete.' <a href="pdf/prodPaqueteBC.php?cdgrollo='.$prodCorte_cdgrollo.'" target="_blank">'.$png_barcode.'</a> paquetes. <br/>';






                    $link_mysqli = conectar();
                    $prodPaqueteOpe_num_paquetes = $link_mysqli->query("
                      SELECT cdgpaquete
                      FROM prodpaqueteope
                      WHERE cdgempleado = '".$prodCorte_cdgempleado."' AND
                        fchoperacion = '".$prodDestajo_fchfinal."'");
                    
                    $msg_answer .= 'En el d&iacute;a '.$fchoperacion.' llevas un total de <h2>'.($prodPaqueteOpe_num_paquetes->num_rows-$num_paquete).'</h2> + '.$num_paquete.' paquetes generados.<br/>';
                    $msg_answer .= '¿? h&aacute;biles. <br/><br/>';


                    $link_mysqli = conectar();
                    $prodPaqueteOpe_num_paquetes = $link_mysqli->query("
                      SELECT cdgpaquete
                      FROM prodpaqueteope
                      WHERE cdgempleado = '".$prodCorte_cdgempleado."' AND
                        fchoperacion BETWEEN '".$prodDestajo_fchinicial."' AND '".$prodDestajo_fchfinal."'");

                    $msg_answer .= 'La semana laboral es de Viernes a Jueves<br/>';
                    $msg_answer .= 'Se tomara el jueves de la semana anterior para calculo de lo productividad, debido al d&iacute;a de pago<br/> <br/>';
                    $msg_answer .= 'Producci&oacute;n del '.$prodDestajo_fchinicial.' al '.$prodDestajo_fchfinal.' >>> <h3>'.($prodPaqueteOpe_num_paquetes->num_rows-$num_paquete).'</h3> + '.$num_paquete.' paquetes generados.';






                    $prodCorte_cdgrollo = '';
                    $text_rollo = 'autofocus'; 
                  } else
                  { $msg_answer = 'El n&uacute;mero de paquetes no puede ser CERO.';
                    $text_numpaquete = 'autofocus'; } 
                } else
                { $msg_answer = 'El peso del rollo no puede ser CERO.';
                  $text_peso = 'autofocus';}
              }
            } else
            { $prodCorte_cdgrollo = '';
              $msg_answer = 'El producto ligado al C&oacute;digo/NoOP no pudo ser encontrado.<br/>ERROR: Reportar a soporte t&eacute;cnico.'; }
          }

        } else
        { $prodCorte_cdgrollo = '';
          $msg_answer = 'Informacion de rollo, incorrecta.';
          $text_rollo = 'autofocus'; }
      } else
      { $prodCorte_cdgmaquina = '';
        $msg_answer = 'Informacion de maquina, incorrecta.';
        $text_maquina = 'autofocus'; }
    } else
    { $prodCorte_cdgempleado = '';
      $msg_answer = 'Informacion de empleado, incorrecta.';
      $text_empleado = 'autofocus'; }

    echo '
    <form id="form_produccion" name="form_produccion" method="post" action="prodCorte.php"/>';

    if ($prodCorte_cdgempleado == '' OR $prodCorte_cdgmaquina == '' OR $prodCorte_cdgrollo == '')
    { echo '
      <table align="center">
        <thead>
          <tr><th>'.$sistModulo_modulo.'</th></tr>
        </thead>
        <tbody>
          <tr align="center"><td><label for="label_ttlempleado"><strong>Empleado</strong></label><br/>
              <input type="text" style="width:120px" id="text_empleado" name="text_empleado" value="'.$prodCorte_idempleado.'" '.$text_empleado.' required/></td></tr>
          <tr align="center"><td><label for="label_ttlmaquina"><strong>M&aacute;quina</strong></label><br/>
              <input type="text" style="width:120px" id="text_maquina" name="text_maquina" value="'.$prodCorte_idmaquina.'" '.$text_maquina.' required/></td></tr>
          <tr align="center"><td colspan="2"><label for="label_ttllote"><strong>C&oacute;digo/NoOP</strong></label><br/>
              <input type="text" style="width:120px" id="text_rollo" name="text_rollo" value="'.$prodCorte_cdgrollo.'" '.$text_rollo.' required/></td></tr>
        </tbody>
        <tfoot>
          <tr><th align="right"><input type="submit" id="button_buscar" name="button_buscar" value="Buscar" /></th></tr>
        </tfoot>
      </table>'; }
    else
    { echo '
      <input type="hidden" id="text_empleado" name="text_empleado" value="'.$prodCorte_cdgempleado.'" />
      <input type="hidden" id="text_maquina" name="text_maquina" value="'.$prodCorte_cdgmaquina.'" />
      <input type="hidden" id="text_rollo" name="text_rollo" value="'.$prodCorte_cdgrollo.'" />

      <table align="center">
        <thead>
          <tr><th colspan="2">'.$sistModulo_modulo.'</th></tr>
        </thead>
        <tbody>
          <tr><td><label for="label_ttlempleado">Empleado</label><br/>
              <label for="label_empleado">[<strong>'.$prodCorte_idempleado.'</strong>] '.$prodCorte_empleado.'</label></td>
            <td><label for="label_ttlmaquina">M&aacute;quina</label><br/>
              <label for="label_maquina">[<strong>'.$prodCorte_idmaquina.'</strong>] '.$prodCorte_maquina.'</label></td></tr>
          <tr><td><table align="center">
                <thead>
                  <tr><th colspan="3">Informaci&oacute;n</th></tr>
                </thead>
                <tbody>
                  <tr><td colspan="3">Impresi&oacute;n<br/>
                    <strong>'.$prodCorte_impresion.'</strong><br/>
                    </td></tr>
                </tbody>
                <tfoot align="left">
                  <tr><th><h1><h1>NoOP</h1></h1></th>
                    <th colspan="2" align="right"><a href="prodCorte.php?cdgempleado='.$prodCorte_idempleado.'&cdgmaquina='.$prodCorte_idmaquina.'" target="cargador"><h1>'.$prodCorte_noop.'</h1></a></th></tr>
                  <tr><td>Longitud</td>
                    <td align="right">'.number_format($prodCorte_longitud,2).'</td>
                    <td>Metros</td></tr>
                  <tr><td>Ancho plano</td>
                    <td align="right">'.number_format($prodCorte_amplitud).'</td>
                    <td>Milimetros</td></tr>
                  <tr><td>Peso</td>
                    <td align="right">'.number_format($prodCorte_peso,3).'</td>
                    <td>Kilogramos</td></tr>
                  <tr><td>Piezas aprox</td>
                    <td align="right">'.number_format(($prodCorte_longitud/$prodCorte_corte),3).'</td>
                    <td>Millares</td></tr>
                </tfoot>
              </table></td>
            <td><table align="center">
                <thead>
                  <tr><th>Captura</th></tr>
                </thead>
                <tbody>
                  <tr><td>Peso<br/>
                      <input type="text" id="text_peso" name="text_peso" style="width:140px; text-align:right;" maxlenght="2" value="'.$prodCorte_peso.'" title="Peso de la bobina" '.$text_peso.' required/></td></tr>
                  <tr><td>N&uacute;mero de paquetes<br/>
                      <input type="text" id="text_numpaquete" name="text_numpaquete" style="width:140px; text-align:right;" maxlenght="2" value="'.number_format((($prodCorte_longitud/$prodCorte_corte)/0.5)).'" title="Numero de paquetes" '.$text_numpaquete.' readonly/></td></tr>
                </tbody>
                <tfoot>
                  <tr><th align="right"><input type="submit" id="button_salvar" name="button_salvar" value="Salvar" /></th></tr>
                </tfoot>
              </table> 
            </td></tr>
        </tbody>
      </table>'; }

    echo '
    </form>

    <div align="center"><br/><strong>'.$msg_answer.'</strong></div>';  

     // Filtro de empleados por jornada
    //////////////////////////////////////
    $link_mysqli = conectar();
    $prodPaqueteEmpleados = $link_mysqli->query("
      SELECT prodpaqueteope.cdgempleado, rechempleado.empleado
      FROM prodpaqueteope, rechempleado
      WHERE cdgoperacion = '50001' AND
        prodpaqueteope.cdgempleado = rechempleado.cdgempleado AND
        prodpaqueteope.fchmovimiento LIKE '".$prodProduccion_diatablero."%'
      GROUP BY prodpaqueteope.cdgempleado
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
      FROM prodpaqueteope
      WHERE cdgoperacion = '50001' AND
        fchmovimiento LIKE '".$prodProduccion_diatablero."%'
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
      SELECT cdgempleado, CONCAT(SUBSTRING(fchmovimiento,12,3),'00') AS hora, COUNT(cdgpaquete) AS paquetes
      FROM prodpaqueteope
      WHERE cdgoperacion = '50001' AND
        fchmovimiento LIKE '".$prodProduccion_diatablero."%'
      GROUP BY cdgempleado, SUBSTRING(fchmovimiento,1,14)");

    while ($regProdPaquetes = $prodPaqueteProduccion->fetch_object())
    { $prodProduccion_paquetes[$regProdPaquetes->cdgempleado][$regProdPaquetes->hora] = $regProdPaquetes->paquetes;
      $prodProduccion_paquetesxempleado[$regProdPaquetes->cdgempleado] += $regProdPaquetes->paquetes;
      $prodProduccion_paquetesxhora[$regProdPaquetes->hora] += $regProdPaquetes->paquetes;
      $prodProduccion_paquetesxdia += $regProdPaquetes->paquetes; }

     // Filtro de paquetes anulados por jornada
    ////////////////////////////////////  
    $link_mysqli = conectar();
    $prodPaqueteProduccion_ = $link_mysqli->query("
      SELECT prodpaqueteope.cdgempleado, CONCAT(SUBSTRING(prodpaqueteope.fchmovimiento,12,3),'00') AS hora, COUNT(prodpaqueteope.cdgpaquete) AS paquetes
      FROM prodpaqueteope, prodpaquete
      WHERE prodpaqueteope.cdgpaquete = prodpaquete.cdgpaquete AND 
        prodpaquete.sttpaquete = '0' AND
        prodpaqueteope.cdgoperacion = '50001' AND 
        prodpaqueteope.fchmovimiento LIKE '".$prodProduccion_diatablero."%'
      GROUP BY prodpaqueteope.cdgempleado, SUBSTRING(prodpaqueteope.fchmovimiento,1,14)");

    while ($regProdPaquetes = $prodPaqueteProduccion_->fetch_object())
    { $prodProduccion_paquetes_[$regProdPaquetes->cdgempleado][$regProdPaquetes->hora] = $regProdPaquetes->paquetes;
      $prodProduccion_paquetesxempleado_[$regProdPaquetes->cdgempleado] += $regProdPaquetes->paquetes;
      $prodProduccion_paquetesxhora_[$regProdPaquetes->hora] += $regProdPaquetes->paquetes;
      $prodProduccion_paquetesxdia_ += $regProdPaquetes->paquetes; } //*/

    echo '
    <br/>

    <form id="form_prodtablero" name="form_prodtablero" method="post" action="prodCorte.php"/>

    <table align="center">
      <thead>
        <tr>
          <th></th>
          <td colspan="'.($num_empleados*2).'">Tablero del d&iacute;a: <input type="date" id="date_tablero" name="date_tablero" value="'.$prodProduccion_diatablero.'" onchange="document.form_prodtablero.submit()"></td>
          <th colspan="2"></th>
        </tr>
        <tr align="center"><td><b>Horarios</b></td>';

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
        <tr align="center">
          <th>'.$prodProduccion_hora[$id_hora].'-'.substr($prodProduccion_hora[$id_hora],0,3).'59</th>';

      for ($id_empleado=1; $id_empleado<=$num_empleados; $id_empleado++)
      { if ($prodProduccion_paquetes[$prodProduccion_cdgempleado[$id_empleado]][$prodProduccion_hora[$id_hora]] > 0)
        { echo '
          <td><b>'.$prodProduccion_paquetes[$prodProduccion_cdgempleado[$id_empleado]][$prodProduccion_hora[$id_hora]].'</td><td>-'.$prodProduccion_paquetes_[$prodProduccion_cdgempleado[$id_empleado]][$prodProduccion_hora[$id_hora]].'</b></td>'; }
        else
        { echo '
          <th colspan="2">? = 0</th>';}
      }

      echo '
          <th>'.$prodProduccion_paquetesxhora[$prodProduccion_hora[$id_hora]].'</th><th>-'.$prodProduccion_paquetesxhora_[$prodProduccion_hora[$id_hora]].'</th>
        <tr>'; } 

    echo '
        <tr align="center"><td><b>Suma por <br/>empleado</b></td>';

    for ($id_empleado=1; $id_empleado<=$num_empleados; $id_empleado++)
    { echo '
          <th>'.$prodProduccion_paquetesxempleado[$prodProduccion_cdgempleado[$id_empleado]].'</th><th>-'.$prodProduccion_paquetesxempleado_[$prodProduccion_cdgempleado[$id_empleado]].'</th>'; }

    echo '
          <td><b>'.$prodProduccion_paquetesxdia.'</b></td><td><b>-'.$prodProduccion_paquetesxdia_.'</b></td>
        </tr>
      </tbody>
    </table>

    </form>';

    if ($msg_alert != '')
    { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
  ?>

  </body>
</html>
