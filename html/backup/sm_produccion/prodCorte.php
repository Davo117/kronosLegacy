<!DOCTYPE html>
<html>
  <head>
    <title>Producci&oacute;n asignaci&oacute;n de rollos a corte</title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $barCode = '<img alt="Codigo de barras" src="../img_sistema/barcode.png" height="16" border="0"/>';
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
    { $prodData_diatablero = $_POST['date_tablero']; }
    else
    { $prodData_diatablero = $prodDestajo_fchfinal; }    
    
    ////////////////////////////////////////////

    //Buscame los datos ingresados
    $prodCorte_cdgempleado = trim($_POST['text_empleado']);
    $prodCorte_cdgmaquina = trim($_POST['text_maquina']);
    $prodCorte_cdgrollo = trim($_POST['text_rollo']);
    $prodCorte_cdgdefecto = $_POST['rdo_defecto'];

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
            pdtodiseno.alto,
            pdtodiseno.paquete,
            pdtoimpresion.impresion,
            prodrollo.cdgproducto,
            prodrollo.longitud,
            prodrollo.amplitud,
            prodrollo.peso,
            prodrollo.cdgrollo,
            prodrollo.sttrollo
          FROM pdtodiseno,
            pdtoimpresion,
            prodrollo,
            prodbobina,
            prodlote
          WHERE (prodrollo.sttrollo BETWEEN '1' AND '9') AND
            prodrollo.cdgbobina = prodbobina.cdgbobina AND
            prodbobina.cdglote = prodlote.cdglote AND
           (prodrollo.cdgrollo = '".$prodCorte_cdgrollo."' OR CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) = '".$prodCorte_cdgrollo."') AND
            pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
            pdtoimpresion.cdgimpresion = prodrollo.cdgproducto");

        if ($prodRolloSelect->num_rows > 0)
        { $regProdRollo = $prodRolloSelect->fetch_object();

          if ($regProdRollo->sttrollo == '9')
          { $msg_alert = "Este rollo ya ha sido asignado a un empaque (Queso).";
            $prodCorte_cdgrollo = ''; }
          else
          { $prodCorte_noop = $regProdRollo->noop;
            $prodCorte_impresion = $regProdRollo->impresion;
            $prodCorte_alto = $regProdRollo->alto; 
            $prodCorte_paquete = $regProdRollo->paquete; 
            $prodCorte_cdgproducto = $regProdRollo->cdgproducto;
            $prodCorte_longitud = $regProdRollo->longitud;
            $prodCorte_amplitud = $regProdRollo->amplitud;
            $prodCorte_peso = $regProdRollo->peso;
            $prodCorte_cdgrollo = $regProdRollo->cdgrollo;

            // Generar etiquetas
            if ($regProdRollo->sttrollo != '5')
            { $prodCorte_numpaquetes = number_format((($prodCorte_longitud/$prodCorte_alto)/$prodCorte_paquete));
              if ($prodCorte_numpaquetes > 0)
              { $fchoperacion = date('Y-m-d');

                $link_mysqli = conectar();
                $link_mysqli->query("
                  INSERT INTO prodrolloope
                    (cdgrollo, cdgoperacion, cdgempleado, cdgmaquina, longitud, peso, fchoperacion, fchmovimiento)
                  VALUES
                    ('".$prodCorte_cdgrollo."', '50001', '".$prodCorte_cdgempleado."', '".$prodCorte_cdgmaquina."', '".$prodCorte_longitud."', '".$prodCorte_peso."', '".$fchoperacion."', NOW())");

                for ($id_paquete = 1; $id_paquete <= $prodCorte_numpaquetes; $id_paquete++)
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
                  SET fchmovimiento = NOW(),
                    sttrollo = '5'
                  WHERE cdgrollo = '".$prodCorte_cdgrollo."'");

                $barCode = '<a href="pdf/prodPaqueteBC.php?cdgrollo='.$prodCorte_cdgrollo.'"><img alt="Codigo de barras" src="../img_sistema/barcode.png" height="40" border="0" autofocus/></a>';

                $prodCorte_cdgrollo = '';
              } else
              { $prodCorte_cdgrollo = '';
                $msg_alert = "Longitud insuficiente."; }
            }
            else
            { if ($_SESSION['cdgusuario'] != '')
              { $text_merma = 'autofocus';

                if (substr($sistModulo_permiso,0,2) == 'rw')
                { // Registrar merma
                  if ($_POST['button_salvar'])
                  { if (is_numeric($_POST['text_merma']))
                    { $prodCorte_merma = $_POST['text_merma'];

                      $link_mysqli = conectar();
                      $link_mysqli->query("
                        UPDATE prodrolloope
                        SET merma = '".$prodCorte_merma."',
                          cdgdefecto = '".$prodCorte_cdgdefecto."'
                        WHERE cdgempleado = '".$prodCorte_cdgempleado."' AND
                          cdgmaquina = '".$prodCorte_cdgmaquina."' AND
                          cdgrollo = '".$prodCorte_cdgrollo."' AND
                          fchmovimiento LIKE '".$fchoperacion."%'"); 


                      if ($link_mysqli->affected_rows > 0)
                      { $msg_alert = "Merma registrada."; }
                      else
                      { $msg_alert = "Error es alguno de los datos solicitados o el movimiento esta fuera de fecha."; }
                    }

                    $prodCorte_cdgrollo = '';
                    $text_rollo = 'autofocus';
                  }
                } else
                { $msg_alert = "No cuentas con los permisos necesarios para realizar este movimiento."; }
              } else
              { $msg_alert = "Este rollo ya ha sido cortado o se encuentra bloqueado.";
                $barCode = '<a href="pdf/prodPaqueteBC.php?cdgrollo='.$prodCorte_cdgrollo.'"><img alt="Codigo de barras" src="../img_sistema/barcode.png" height="40" border="0" autofocus/></a>';
                $prodCorte_cdgrollo = ''; }
            }
          }
        } else
        { $prodCorte_cdgrollo = '';
          $msg_alert = 'Informacion de rollo, incorrecta.';
          $text_rollo = 'autofocus'; }
      } else
      { $prodCorte_cdgmaquina = '';
        $msg_alert = 'Informacion de maquina, incorrecta.';
        $text_maquina = 'autofocus'; }
    } else
    { $prodCorte_cdgempleado = '';
      $msg_alert = 'Informacion de empleado, incorrecta.';
      $text_empleado = 'autofocus'; }

    if ($prodCorte_cdgempleado == '' OR $prodCorte_cdgmaquina == '' OR $prodCorte_cdgrollo == '')
    { echo '
    <form id="form_produccion" name="form_produccion" method="post" action="prodCorte.php"/>
      <table align="center">
        <thead>
          <tr><th colspan="4" align="left">'.utf8_decode($sistModulo_modulo).'</th></tr>
        </thead>
        <tbody>
          <tr align="center">
            <td><label for="label_ttlempleado"><strong>Empleado</strong></label><br/>
              <input type="text" style="width:120px" id="text_empleado" name="text_empleado" value="'.$prodCorte_idempleado.'" '.$text_empleado.' required/></td>
            <td><label for="label_ttlmaquina"><strong>M&aacute;quina</strong></label><br/>
              <input type="text" style="width:120px" id="text_maquina" name="text_maquina" value="'.$prodCorte_idmaquina.'" '.$text_maquina.' required/></td>
            <td><label for="label_ttllote"><strong>C&oacute;digo/NoOP</strong></label><br/>
              <input type="text" style="width:120px" id="text_rollo" name="text_rollo" value="'.$prodCorte_cdgrollo.'" '.$text_rollo.' required/></td>
            <td>'.$barCode.'</td></tr>
        </tbody>
        <tfoot>
          <tr><th colspan="4" align="right"><input type="submit" id="button_buscar" name="button_buscar" value="Generar" /></th></tr>
        </tfoot>
      </table>
    </form>'; }
    else
    { echo '
    <form id="form_produccion" name="form_produccion" method="post" action="prodCorte.php"/>
      <input type="hidden" id="text_empleado" name="text_empleado" value="'.$prodCorte_cdgempleado.'" />
      <input type="hidden" id="text_maquina" name="text_maquina" value="'.$prodCorte_cdgmaquina.'" />
      <input type="hidden" id="text_rollo" name="text_rollo" value="'.$prodCorte_cdgrollo.'" />

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
              <label for="lbl_noop"><a href="prodCorte.php?cdgempleado='.$prodCorte_idempleado.'&cdgmaquina='.$prodCorte_idmaquina.'" target="cargador"><h1>'.$prodCorte_noop.'</h1></a></label><br />
              <label for="lbl_ttlimpresion"><strong>'.utf8_decode('Impresión').'</strong></label><br />
              <label for="lbl_impresion">&nbsp;<em><strong>'.$prodCorte_idimpresion.'</strong> '.$prodCorte_impresion.'</em></label><br />
              <label for="lbl_ttllongitudori"><strong>Longitud</strong></label><br />
              <label for="lbl_longitudori">&nbsp;<em><strong>'.number_format($prodCorte_longitud,2).'</strong>  Metros</em></label><br />
              <label for="lbl_ttlpesoori"><strong>Peso</strong></label><br />
              <label for="lbl_pesoori">&nbsp;<em><strong>'.number_format($prodCorte_peso,3).'</strong> Kilogramos</em></label><br />
              <label for="lbl_ttlanchoori"><strong>Ancho en manga</strong></label><br />
              <label for="lbl_anchoori">&nbsp;<em><strong>'.number_format($prodCorte_amplitud).'</strong> '.utf8_decode('Milímetros').'</em></label><br /></td>
            <td><label for="ttl_ttlmerma"><strong>Merma/Peso</strong></label><br />
              <input type="text" id="text_merma" name="text_merma" style="width:80px; text-align:right;" value="'.$prodCorte_merma.'" title="Merma del rollo" '.$text_merma.' required/></td>
            <td width="200">';

      $link_mysqli = conectar();
      $prodDefectoSelect = $link_mysqli->query("
        SELECT proddefecto.cdgdefecto,
          proddefecto.defecto,
          proddefectope.cdgoperacion,
          proddefectope.detalle
        FROM proddefecto, 
          proddefectope 
        WHERE proddefecto.cdgdefecto = proddefectope.cdgdefecto AND
          proddefectope.cdgoperacion = '50001'
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
            <label for="lbl_empleado">&nbsp;<em><strong>'.$prodCorte_idempleado.'</strong> '.$prodCorte_empleado.'</em></label><br />
            <label for="lbl_ttlmaquina">'.utf8_decode('Máquina').'</label><br />
            <label for="lbl_maquina">&nbsp;<em><strong>'.$prodCorte_idmaquina.'</strong> '.$prodCorte_maquina.'</em></label></td></tr>
        </tbody>
        <tfoot>
          <tr><th colspan="3" align="right">
              <input type="submit" id="button_salvar" name="button_salvar" value="Registrar" /></th></tr>
        </tfoot>
      </table>
    </form>'; }

    $link_mysqli = conectar();
    $prodRolloOpeMermaByCdgDefecto = $link_mysqli->query("
      SELECT prodrolloope.cdgdefecto, 
        proddefecto.defecto, 
        proddefectope.detalle, 
        SUM(merma) AS merma
      FROM prodrolloope,
        proddefecto,
        proddefectope
      WHERE (prodrolloope.fchoperacion = '".$prodDestajo_fchfinal."' AND
        prodrolloope.cdgoperacion = '50001') AND
        prodrolloope.cdgdefecto = proddefecto.cdgdefecto AND
       (proddefecto.cdgdefecto = proddefectope.cdgdefecto AND
        proddefectope.cdgoperacion = prodrolloope.cdgoperacion)
      GROUP BY prodrolloope.cdgdefecto, 
        proddefecto.defecto,
        proddefectope.detalle");

    if ($prodRolloOpeMermaByCdgDefecto->num_rows > 0)
    { echo '<br />
    <table align="center">
      <thead>
        <tr><th>Defecto</th><th><a href="prodMerma.php">Merma</a></th></tr>
      <thead>
      <tbody>';

      while ($regQuery = $prodRolloOpeMermaByCdgDefecto->fetch_object())
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
      SELECT prodpaqueteope.cdgempleado, 
        rechempleado.empleado,
        COUNT(prodpaqueteope.cdgpaquete)
      FROM prodpaqueteope, 
        rechempleado
      WHERE cdgoperacion = '50001' AND
        prodpaqueteope.cdgempleado = rechempleado.cdgempleado AND
        prodpaqueteope.fchmovimiento LIKE '".$prodData_diatablero."%'
      GROUP BY prodpaqueteope.cdgempleado
      ORDER BY COUNT(prodpaqueteope.cdgpaquete) DESC, rechempleado.empleado");

    $id_empleado = 1;
    while ($regProdEmpleado = $prodPaqueteEmpleados->fetch_object())
    { $prodData_cdgempleado[$id_empleado] = $regProdEmpleado->cdgempleado;
      $prodData_empleado[$id_empleado] = $regProdEmpleado->empleado; 

      $id_empleado++; }

    $num_empleados = $prodPaqueteEmpleados->num_rows;

     // Filtro de horas por jornada
    //////////////////////////////////////
    $link_mysqli = conectar();
    $prodPaqueteHorarios = $link_mysqli->query("
      SELECT CONCAT(SUBSTRING(fchmovimiento,12,3),'00') AS horas
      FROM prodpaqueteope
      WHERE cdgoperacion = '50001' AND
        fchmovimiento LIKE '".$prodData_diatablero."%'
      GROUP BY SUBSTRING(fchmovimiento,1,14)");

    $id_hora = 1;
    while ($regProdHorario = $prodPaqueteHorarios->fetch_object())
    { $prodData_hora[$id_hora] = $regProdHorario->horas; 

      $id_hora++; }

    $num_horas = $prodPaqueteHorarios->num_rows; 

     // Filtro de produccion por jornada
    //////////////////////////////////////
    $link_mysqli = conectar();
    $prodRolloOpeSelect = $link_mysqli->query("
      SELECT cdgempleado, 
        CONCAT(SUBSTRING(fchmovimiento,12,3),'00') AS hora, 
        SUM(longitud) AS longitud, 
        SUM(merma) AS merma
      FROM prodrolloope
      WHERE cdgoperacion = '50001' AND
        fchmovimiento LIKE '".$prodDestajo_fchfinal."%'
      GROUP BY cdgempleado, SUBSTRING(fchmovimiento,1,14)");

    while ($regQuery = $prodRolloOpeSelect->fetch_object())
    { $prodData_mtrs[$regQuery->cdgempleado][$regQuery->hora] = $regQuery->longitud;
      $prodData_mtrsxempleado[$regQuery->cdgempleado] += $regQuery->longitud;
      $prodData_mtrsxhora[$regQuery->hora] += $regQuery->longitud;
      $prodData_mtrsxdia += $regQuery->longitud;

      $prodData_merma[$regQuery->cdgempleado][$regQuery->hora] = $regQuery->merma;
      $prodData_mermaxempleado[$regQuery->cdgempleado] += $regQuery->merma;
      $prodData_mermaxhora[$regQuery->hora] += $regQuery->merma;
      $prodData_mermaxdia += $regQuery->merma; }

    $link_mysqli = conectar();
    $prodPaqueteOpeSelect = $link_mysqli->query("
      SELECT prodpaqueteope.cdgempleado, 
        pdtodiseno.cdgdiseno,
        CONCAT(SUBSTRING(prodpaqueteope.fchmovimiento,12,3),'00') AS hora, 
        ((COUNT(prodpaqueteope.cdgpaquete)*pdtodiseno.alto)*pdtodiseno.paquete) AS longitud,
        COUNT(prodpaqueteope.cdgpaquete) AS paquetes
      FROM prodpaqueteope,
        prodpaquete,
        pdtoimpresion,
        pdtodiseno
      WHERE prodpaqueteope.cdgoperacion = '50001' AND
        prodpaqueteope.fchmovimiento LIKE '".$prodData_diatablero."%' AND
       (prodpaqueteope.cdgpaquete = prodpaquete.cdgpaquete AND
        prodpaquete.sttpaquete != '0') AND
       (prodpaquete.cdgproducto = pdtoimpresion.cdgimpresion AND
        pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno)
      GROUP BY prodpaqueteope.cdgempleado, 
        pdtodiseno.cdgdiseno,
        SUBSTRING(prodpaqueteope.fchmovimiento,1,14)");

    while ($regQuery = $prodPaqueteOpeSelect->fetch_object())
    { $prodData_metros[$regQuery->cdgempleado][$regQuery->hora] = $regQuery->longitud;
      $prodData_metrosxempleado[$regQuery->cdgempleado] += $regQuery->longitud;
      $prodData_metrosxhora[$regQuery->hora] += $regQuery->longitud;
      $prodData_metrosxdia += $regQuery->longitud;

      $prodData_paquetes[$regQuery->cdgempleado][$regQuery->hora] = $regQuery->paquetes;
      $prodData_paquetesxempleado[$regQuery->cdgempleado] += $regQuery->paquetes;
      $prodData_paquetesxhora[$regQuery->hora] += $regQuery->paquetes;
      $prodData_paquetesxdia += $regQuery->paquetes; }

    echo '<br/>
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
          <th>Paquetes</th>'; }

    echo '
          <td colspan="2" align="center">Metros</td>
          <td>Merma</td>
          <td>Paquetes</td><td>
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
          <td align="center"><strong>'.$prodData_paquetes[$prodData_cdgempleado[$id_empleado]][$prodData_hora[$id_hora]].'</strong></td>'; }

      echo '
          <th>'.number_format($prodData_mtrsxhora[$prodData_hora[$id_hora]],2).'</th>
          <th>'.number_format($prodData_metrosxhora[$prodData_hora[$id_hora]],2).'</th>
          <th>'.number_format($prodData_mermaxhora[$prodData_hora[$id_hora]],3).'</th>
          <th align="center">'.$prodData_paquetesxhora[$prodData_hora[$id_hora]].'</th>
        <tr>'; } 

    echo '
        <tr align="center"><td>Totales</td>';

    for ($id_empleado=1; $id_empleado<=$num_empleados; $id_empleado++)
    { echo '
          <th>'.number_format($prodData_mtrsxempleado[$prodData_cdgempleado[$id_empleado]],2).'</th> 
          <th>'.number_format($prodData_metrosxempleado[$prodData_cdgempleado[$id_empleado]],2).'</th> 
          <th>'.number_format($prodData_mermaxempleado[$prodData_cdgempleado[$id_empleado]],3).'</th> 
          <th align="center">'.$prodData_paquetesxempleado[$prodData_cdgempleado[$id_empleado]].'</th>'; }

    echo '
          <td><strong>'.number_format($prodData_mtrsxdia,2).'</strong></td>
          <td><strong>'.number_format($prodData_metrosxdia,2).'</strong></td>
          <td><strong>'.number_format($prodData_mermaxdia,3).'</strong></td>
          <td align="center"><strong>'.$prodData_paquetesxdia.'</strong></td>
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