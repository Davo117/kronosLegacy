<!DOCTYPE html>
<html>
  <head>
    <title>Producci&oacute;n Revisado</title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '60045';

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

    $prodRevisado_cdgempleado = trim($_POST['text_empleado']);
    $prodRevisado_cdgmaquina = trim($_POST['text_maquina']);
    $prodRevisado_cdgrollo = trim($_POST['text_rollo']);
    
    if ($_GET['cdgempleado'])
    { $prodRevisado_cdgempleado = $_GET['cdgempleado']; }

    if ($_GET['cdgmaquina'])
    { $prodRevisado_cdgmaquina = $_GET['cdgmaquina']; }

    //Buscar Empleado
    $link_mysql = conectar();
    $rechEmpleadoSelect = $link_mysql->query("
      SELECT * FROM rechempleado
      WHERE (idempleado = '".$prodRevisado_cdgempleado."' OR cdgempleado = '".$prodRevisado_cdgempleado."') AND
        sttempleado >= '1'");

    if ($rechEmpleadoSelect->num_rows > 0)
    { // Filtra al empleado
      $regRechEmpleado = $rechEmpleadoSelect->fetch_object();

      $prodRevisado_idempleado = $regRechEmpleado->idempleado;
      $prodRevisado_empleado = $regRechEmpleado->empleado;
      $prodRevisado_cdgempleado = $regRechEmpleado->cdgempleado;

      $rechEmpleadoSelect->close;

      //Buscar Maquina Proceso 40 Fusion
      $link_mysql = conectar();
      $prodMaquinaSelect = $link_mysql->query("
        SELECT * FROM prodmaquina
        WHERE (idmaquina = '".$prodRevisado_cdgmaquina."' OR cdgmaquina = '".$prodRevisado_cdgmaquina."') AND
          cdgproceso = '45' AND
          sttmaquina >= '1'");

      if ($prodMaquinaSelect->num_rows > 0)
      { // Filtra la maquina
        $regProdMaquina = $prodMaquinaSelect->fetch_object();

        $prodRevisado_idmaquina = $regProdMaquina->idmaquina;
        $prodRevisado_maquina = $regProdMaquina->maquina;
        $prodRevisado_cdgmaquina = $regProdMaquina->cdgmaquina;

        $prodMaquinaSelect->close;

        //Buscar lote Proceso
        $link_mysqli = conectar();
        $prodRolloSelect = $link_mysqli->query("
          SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) AS noop,
            prodrollo.longitud, prodrollo.amplitud, prodrollo.peso, prodrollo.cdgrollo, prodrollo.sttrollo,
            pdtoimpresion.idimpresion, pdtoimpresion.impresion, pdtoimpresion.corte, pdtoimpresion.cdgimpresion,
            pdtomezcla.idmezcla, pdtomezcla.mezcla, pdtomezcla.cdgmezcla
          FROM prodlote, prodbobina, prodrollo,
            pdtomezcla, pdtoimpresion
          WHERE prodlote.cdglote = prodbobina.cdglote AND
            prodbobina.cdgbobina = prodrollo.cdgbobina AND
           (prodrollo.cdgrollo = '".$prodRevisado_cdgrollo."' OR CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) = '".$prodRevisado_cdgrollo."') AND
            prodrollo.cdgempaque = '' AND
            prodlote.cdgmezcla = pdtomezcla.cdgmezcla AND
            pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion
            AND prodrollo.sttrollo = '1'");

        if ($prodRolloSelect->num_rows > 0)
        { // Filtra la bobina
          $regProdRollo = $prodRolloSelect->fetch_object();

          $prodRevisado_noop = $regProdRollo->noop;
          $prodRevisado_longitud = $regProdRollo->longitud;
          $prodRevisado_amplitud = $regProdRollo->amplitud;
          $prodRevisado_peso = $regProdRollo->peso;          
          $prodRevisado_cdgrollo = $regProdRollo->cdgrollo;
          $prodRevisado_sttrollo = $regProdRollo->sttrollo;
          
          $prodRevisado_impresion = $regProdRollo->impresion;

          $prodRolloSelect->close; 


      $text_longitud = 'autofocus';
      $msg_info = 'Ingresar nueva longitud.';

      if (is_numeric($_POST['text_longitud']))
      { $prodRevisado_longitudrollo = $_POST['text_longitud']; 
  
        if (is_numeric($_POST['text_bandera']))
        { $prodRevisado_banderarollo = $_POST['text_bandera'];    
          
          if (is_numeric($_POST['text_longitud']))
          { $prodRevisado_pesorollo = $_POST['text_peso']; }
          else
          { $prodRevisado_pesorollo = 0; }

          if ($_POST['button_salvar'])
          { $fchoperacion = date('Y-m-d');

            $link_mysqli = conectar();
            $prodRolloOpeInsert = $link_mysqli->query("
              INSERT INTO prodrolloope
                (cdgrollo, cdgoperacion, cdgempleado, cdgmaquina, longitudini, longitudfin, pesoini, pesofin, numbandera, fchoperacion, fchmovimiento)
              VALUES
                ('".$prodRevisado_cdgrollo."', '40006', '".$prodRevisado_cdgempleado."', '".$prodRevisado_cdgmaquina."', '".$prodRevisado_longitud."', '".$prodRevisado_longitudrollo."', '".$prodRevisado_peso."', '".$prodRevisado_pesorollo."', '".$prodRevisado_banderarollo."', '".$fchoperacion."', NOW())");

            $link_mysqli->close;

            $link_mysqli = conectar();
            $prodRolloProcInsert = $link_mysqli->query("
              INSERT INTO prodrolloproc
                (cdgrollo, cdgproceso, longitud, fchmovimiento)
              VALUES
                ('".$prodRevisado_cdgrollo."', '46', '".$prodRevisado_longitud."', NOW())");                 

            $link_mysqli->close;

            $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE prodrollo
              SET sttrollo = '6',
                longitud = '".$prodRevisado_longitudrollo."',
                bandera = '".$prodRevisado_banderarollo."',
                fchmovimiento = NOW()
              WHERE cdgrollo = '".$prodRevisado_cdgrollo."' AND
                sttrollo = '1'");

            if ($link_mysqli->affected_rows > 0) 
            { $msg_info = 'Rollo actualizado, '.$prodRevisado_noop.' REVISADO.'; }
            else
            { $msg_info = 'Rollo Revisado anteriormente.'; }

            $prodRevisado_cdgrollo = '';
          }
        } else
        { $prodRollo_banderarollo = '';
          $msg_info = 'Ingresar cantidad de banderas.';
          $text_bandera = 'autofocus'; }
      } else
      { $prodRevisado_longitudrollo = '';
        $msg_info = 'Ingresar nueva longitud.';
        $text_longitud = 'autofocus'; } 

          $text_rollo = 'autofocus';

        } else
        { $prodRevisado_cdgrollo = '';
          $msg_info = 'Ingresar informacion de bobina correcta.';
          $text_rollo = 'autofocus'; } 
      } else
      { $prodRevisado_cdgmaquina = '';
        $msg_info = 'Ingresar informacion de maquina.';
        $text_maquina = 'autofocus'; }
    } else
    { $prodRevisado_cdgempleado = '';
      $msg_info = 'Ingresar informacion de empleado.';
      $text_empleado = 'autofocus'; }

    if ($prodRevisado_cdgempleado == '' OR $prodRevisado_cdgmaquina == '' OR $prodRevisado_cdgrollo == '')
    { echo '
    <form id="form_produccion" name="form_produccion" method="post" action="prodRevisado.php"/>
      <table align="center">
        <thead>
          <tr>
            <th>'.$sistModulo_modulo.'</th>
          </tr>
        </thead>
        <tbody>
          <tr><td><label for="lbl_ttlempleado">Empleado</label><br/>
              <input type="text" style="width:120px" id="text_empleado" name="text_empleado" value="'.$prodRevisado_idempleado.'" '.$text_empleado.' required/></td></tr>
          <tr><td><label for="lbl_ttlmaquina">M&aacute;quina</label><br/>
              <input type="text" style="width:120px" id="text_maquina" name="text_maquina" value="'.$prodRevisado_idmaquina.'" '.$text_maquina.' required/></td></tr>
          <tr><td><label for="lbl_ttlrollo">Rollo</label><br/>
              <input type="text" style="width:120px" id="text_rollo" name="text_rollo" value="'.$prodRevisado_cdgrollo.'" '.$text_rollo.' required/></td></tr>
        </tbody>
        <tfoot>
          <tr><th align="right"><input type="submit" id="button_buscar" name="button_buscar" value="Buscar" /></th></tr>
        </tfoot>
      </table>
    </form>'; }
    else
    { 

      echo '
    <form id="form_produccion" name="form_produccion" method="post" action="prodRevisado.php"/>
      <input type="hidden" id="text_empleado" name="text_empleado" value="'.$prodRevisado_cdgempleado.'" />
      <input type="hidden" id="text_maquina" name="text_maquina" value="'.$prodRevisado_cdgmaquina.'" />
      <input type="hidden" id="text_rollo" name="text_rollo" value="'.$prodRevisado_cdgrollo.'" />
      
      <table align="center">
        <thead>
          <tr><th colspan="2">'.$sistModulo_modulo.'</th></tr>
        </thead>
        <tbody>
          <tr><td><label for="label_ttlempleado">Empleado</label><br/>
              <label for="label_empleado">'.$prodRevisado_idempleado.' '.$prodRevisado_empleado.'</label></td>
            <td><label for="label_ttlmaquina">M&aacute;quina</label><br/>
              <label for="label_maquina">'.$prodRevisado_idmaquina.' '.$prodRevisado_maquina.'</label></td></tr>
          <tr><td align="left">
              <table>
                <thead>
                  <tr><th colspan="3">Informaci&oacute;n</th></tr>
                </thead>
                <tbody>
                  <tr><td>Impresi&oacute;n</td>
                    <td colspan="2">'.$prodRevisado_impresion.'</td></tr>
                  <tr><td>Mezcla</td>
                    <td colspan="2">'.$prodRevisado_idmezcla.'<br/>'.$prodRevisado_mezcla.'</td></tr>
                  <tr><td><h1>NoOP</h1></td>                    
                      <td><h1>'.$prodRevisado_noop.'</h1></td></tr>
                  <tr><td colspan="3" align="right"><a href="prodRevisado.php?cdgempleado='.$prodRevisado_idempleado.'&cdgmaquina='.$prodRevisado_idmaquina.'" target="cargador">Cambio de rollo</a><br/></td></tr>
                </tbody>
                <tfoot align="left">
                  <tr><td>Longitud</td>
                    <td align="right">'.number_format($prodRevisado_longitud,3).'</td>
                    <td>Metros</td></tr>
                  <tr><td>Ancho plano</td>
                    <td align="right">'.number_format($prodRevisado_amplitud).'</td>
                    <td>Milimetros</td></tr>
                  <tr><td>Peso</td>
                    <td align="right">'.number_format($prodRevisado_peso,3).'</td>
                    <td>Kilogramos</td></tr>
                </tfoot>
              </table><br/>              
            </td>
            <td align="left">
              <table>
                <thead>
                  <tr><th colspan="4">Captura</th></tr>
                </thead>
                <tbody>
                  <tr><td>Rollo #'.$id_rollo.' <br/></td>
                    <td><label for="ttl_longitud">Longitud</label><br/>
                      <input type="text" id="text_longitud" name="text_longitud" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodRollo_longitudrollo.'" title="Nueva Longitud del rollo" '.$text_longitud.' required/></td>
                    <td><label for="ttl_banderas">Banderas</label><br/>
                      <input type="text" id="text_bandera" name="text_bandera" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodRollo_banderarollo.'" title="Cantidad de banderas por rollo" '.$text_bandera.' required/></td>
                    <td><label for="ttl_peso">Peso</label><br/>
                      <input type="text" id="text_peso" name="text_peso" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodRollo_pesorollo.'" title="Nuevo Peso del rollo" '.$text_peso.' required/></td></tr>
                </tbody>
                <tfoot>
                </tfoot>
              </table><br/>              
              <a href="pdf/prodRolloBC.php?cdgrollo='.$prodRevisado_cdgrollo.'" target="_blank">Generar etiquetas</a>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr><th colspan="2" align="right">
              <input type="submit" id="button_salvar" name="button_salvar" value="Salvar" /></th></tr>
        </tfoot>
      </table>
    </form>'; }

    echo '<br/>
    <div align="center"><strong>'.$msg_info.'</strong></div>';

     // Filtro de empleados por jornada
    //////////////////////////////////////
    $link_mysqli = conectar();
    $prodPaqueteEmpleados = $link_mysqli->query("
      SELECT prodrolloope.cdgempleado, rechempleado.empleado
      FROM prodrolloope, rechempleado
      WHERE cdgoperacion = '40006' AND
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
      WHERE cdgoperacion = '40006' AND
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
      SELECT cdgempleado, 
        CONCAT(SUBSTRING(fchmovimiento,12,3),'00') AS hora, 
        SUM(longitudini) AS metrosin, 
        SUM(longitudfin) AS metrosout, 
        SUM(numbandera) AS banderas
      FROM prodrolloope
      WHERE cdgoperacion = '40006' AND
        fchmovimiento LIKE '".$prodDestajo_fchfinal."%'
      GROUP BY cdgempleado, SUBSTRING(fchmovimiento,1,14)");

    while ($regProdMetros = $prodPaqueteProduccion->fetch_object())
    { $prodProduccion_metrosin[$regProdMetros->cdgempleado][$regProdMetros->hora] = $regProdMetros->metrosin;
      $prodProduccion_metrosinxempleado[$regProdMetros->cdgempleado] += $regProdMetros->metrosin;
      $prodProduccion_metrosinxhora[$regProdMetros->hora] += $regProdMetros->metrosin;
      $prodProduccion_metrosinxdia += $regProdMetros->metrosin;

      $prodProduccion_metrosout[$regProdMetros->cdgempleado][$regProdMetros->hora] = $regProdMetros->metrosout;
      $prodProduccion_metrosoutxempleado[$regProdMetros->cdgempleado] += $regProdMetros->metrosout;
      $prodProduccion_metrosoutxhora[$regProdMetros->hora] += $regProdMetros->metrosout;
      $prodProduccion_metrosoutxdia += $regProdMetros->metrosout;

      $prodProduccion_banderas[$regProdMetros->cdgempleado][$regProdMetros->hora] = $regProdMetros->banderas;
      $prodProduccion_banderasxempleado[$regProdMetros->cdgempleado] += $regProdMetros->banderas;
      $prodProduccion_banderasxhora[$regProdMetros->hora] += $regProdMetros->banderas;
      $prodProduccion_banderasxdia += $regProdMetros->banderas; }

    echo '
    <br/>
    <table align="center">
      <thead>
        <tr align="right"><td><b>Horarios</b></td>';

    for ($id_empleado=1; $id_empleado<=$num_empleados; $id_empleado++)
    { echo '
          <th colspan="3">'.$prodProduccion_empleado[$id_empleado].'</th>'; }

    echo '
          <td colspan="3"><b>Suma por hora</b><td>
        </tr>
      </thead>
      <tbody>';

    for ($id_hora=1; $id_hora<=$num_horas; $id_hora++)
    { echo '
        <tr align="right">
          <th>'.$prodProduccion_hora[$id_hora].'</th>';

      for ($id_empleado=1; $id_empleado<=$num_empleados; $id_empleado++)
      { echo '
          <td><b>'.$prodProduccion_metrosin[$prodProduccion_cdgempleado[$id_empleado]][$prodProduccion_hora[$id_hora]].'</b></td> 
          <td><b>'.$prodProduccion_metrosout[$prodProduccion_cdgempleado[$id_empleado]][$prodProduccion_hora[$id_hora]].'</b></td> 
          <td>B <b>'.$prodProduccion_banderas[$prodProduccion_cdgempleado[$id_empleado]][$prodProduccion_hora[$id_hora]].'</b></td>'; }

      echo '
          <th>'.$prodProduccion_metrosinxhora[$prodProduccion_hora[$id_hora]].'</th>
          <th>'.$prodProduccion_metrosoutxhora[$prodProduccion_hora[$id_hora]].'</th>
          <th>B '.$prodProduccion_banderasxhora[$prodProduccion_hora[$id_hora]].'</th>
        <tr>'; } 

    echo '
        <tr align="right"><td><b>Suma por <br/>empleado</b></td>';

    for ($id_empleado=1; $id_empleado<=$num_empleados; $id_empleado++)
    { echo '
          <th>'.$prodProduccion_metrosinxempleado[$prodProduccion_cdgempleado[$id_empleado]].'</th> 
          <th>'.$prodProduccion_metrosoutxempleado[$prodProduccion_cdgempleado[$id_empleado]].'</th> 
          <th>B '.$prodProduccion_banderasxempleado[$prodProduccion_cdgempleado[$id_empleado]].'</th>'; }

    echo '
          <td><b>'.$prodProduccion_metrosinxdia.'</b></td>
          <td><b>'.$prodProduccion_metrosoutxdia.'</b></td>
          <td>B <b>'.$prodProduccion_banderasxdia.'</b></td>
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
