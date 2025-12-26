<!DOCTYPE html>
<html>
  <head>
    <title>Producci&oacute;n Revisado</title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '80110';

  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    $alptPesaje_cdgempleado = trim($_POST['text_empleado']);    
    $alptPesaje_cdgrollo = trim($_POST['text_rollo']);
    
    if ($_GET['cdgempleado'])
    { $alptPesaje_cdgempleado = $_GET['cdgempleado']; }

    //Buscar Empleado
    $link_mysql = conectar();
    $rechEmpleadoSelect = $link_mysql->query("
      SELECT * FROM rechempleado
      WHERE (idempleado = '".$alptPesaje_cdgempleado."' OR cdgempleado = '".$alptPesaje_cdgempleado."') AND
        sttempleado >= '1'");

    if ($rechEmpleadoSelect->num_rows > 0)
    { // Filtra al empleado
      $regRechEmpleado = $rechEmpleadoSelect->fetch_object();

      $alptPesaje_idempleado = $regRechEmpleado->idempleado;
      $alptPesaje_empleado = $regRechEmpleado->empleado;
      $alptPesaje_cdgempleado = $regRechEmpleado->cdgempleado;

      $rechEmpleadoSelect->close;

        //Buscar lote Proceso
        $link_mysqli = conectar();
        $prodRolloSelect = $link_mysqli->query("
          SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) AS noop,
            prodrollo.longitud, prodrollo.amplitud, prodrollo.peso, prodrollo.cdgrollo, prodrollo.sttrollo,
            pdtoimpresion.idimpresion, pdtoimpresion.impresion, pdtoimpresion.corte, pdtoimpresion.cdgimpresion,
            pdtomezcla.idmezcla, pdtomezcla.mezcla, pdtomezcla.cdgmezcla, prodrollo.bandera
          FROM prodlote, prodbobina, prodrollo,
            pdtomezcla, pdtoimpresion
          WHERE prodlote.cdglote = prodbobina.cdglote AND
            prodbobina.cdgbobina = prodrollo.cdgbobina AND
           (prodrollo.cdgrollo = '".$alptPesaje_cdgrollo."' OR CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) = '".$alptPesaje_cdgrollo."') AND
            prodrollo.cdgempaque = '' AND
            prodlote.cdgmezcla = pdtomezcla.cdgmezcla AND
            pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion
            AND prodrollo.sttrollo = '7'");

        if ($prodRolloSelect->num_rows > 0)
        { // Filtra la bobina
          $regProdRollo = $prodRolloSelect->fetch_object();

          $alptPesaje_noop = $regProdRollo->noop;
          $alptPesaje_longitud = $regProdRollo->longitud;
          $alptPesaje_amplitud = $regProdRollo->amplitud;
          $alptPesaje_peso = $regProdRollo->peso;
          $alptPesaje_bandera = $regProdRollo->bandera;
          $alptPesaje_cdgrollo = $regProdRollo->cdgrollo;
          $alptPesaje_sttrollo = $regProdRollo->sttrollo;
          
          $alptPesaje_impresion = $regProdRollo->impresion;

          $prodRolloSelect->close; 


      $text_longitud = 'autofocus';
      $msg_info = 'Ingresar nueva longitud.';

      if (is_numeric($_POST['text_peso']))
      { $alptPesaje_pesorollo = $_POST['text_peso']; 
  
   
          
          if ($_POST['button_salvar'])
          { $fchoperacion = date('Y-m-d');

            $link_mysqli = conectar();
            $prodRolloOpeInsert = $link_mysqli->query("
              INSERT INTO prodrolloope
                (cdgrollo, cdgoperacion, cdgempleado, cdgmaquina, longitudini, longitudfin, pesoini, pesofin, numbandera, fchoperacion, fchmovimiento)
              VALUES
                ('".$alptPesaje_cdgrollo."', '40017', '".$alptPesaje_cdgempleado."', '00000', '".$alptPesaje_longitud."', '".$alptPesaje_longitud."', '".$alptPesaje_peso."', '".$alptPesaje_pesorollo."', '".$alptPesaje_bandera."', '".$fchoperacion."', NOW())");

            $link_mysqli->close;

            $link_mysqli = conectar();
            $prodRolloProcInsert = $link_mysqli->query("
              INSERT INTO prodrolloproc
                (cdgrollo, cdgproceso, peso, fchmovimiento)
              VALUES
                ('".$alptPesaje_cdgrollo."', '48', '".$alptPesaje_pesorollo."', NOW())");                 

            $link_mysqli->close;

            $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE prodrollo
              SET sttrollo = '8',
                peso = '".$alptPesaje_pesorollo."',                
                fchmovimiento = NOW()
              WHERE cdgrollo = '".$alptPesaje_cdgrollo."' AND
                sttrollo = '7'");

            if ($link_mysqli->affected_rows > 0) 
            { $msg_info = 'Rollo actualizado, '.$alptPesaje_noop.' PESADO.'; }
            else
            { $msg_info = 'Rollo no fue afectado en su peso.'; }

            $alptPesaje_cdgrollo = '';

            $text_rollo = 'autofocus';
          }

      } else
      { $alptPesaje_pesorollo = '';
        $msg_info = 'Ingresar nuevo peso.';
        $text_peso = 'autofocus'; } 

          
        } else
        { $alptPesaje_cdgrollo = '';
          $msg_info = 'Ingresar informacion de bobina correcta.';
          $text_rollo = 'autofocus'; } 
    } else
    { $alptPesaje_cdgempleado = '';
      $msg_info = 'Ingresar informacion de empleado.';
      $text_empleado = 'autofocus'; }

    if ($alptPesaje_cdgempleado == '' OR $alptPesaje_cdgrollo == '')
    { echo '
    <form id="form_produccion" name="form_produccion" method="post" action="alptPesajeR.php"/>
      <table align="center">
        <thead>
          <tr>
            <th>'.$sistModulo_modulo.'</th>
          </tr>
        </thead>
        <tbody>
          <tr><td><label for="lbl_ttlempleado">Empleado</label><br/>
              <input type="text" style="width:120px" id="text_empleado" name="text_empleado" value="'.$alptPesaje_cdgempleado.'" '.$text_empleado.' required/></td></tr>
          <tr><td><label for="lbl_ttlrollo">Rollo</label><br/>
              <input type="text" style="width:120px" id="text_rollo" name="text_rollo" value="'.$alptPesaje_cdgrollo.'" '.$text_rollo.' required/></td></tr>
        </tbody>
        <tfoot>
          <tr><th align="right"><input type="submit" id="button_buscar" name="button_buscar" value="Buscar" /></th></tr>
        </tfoot>
      </table>
    </form>'; }
    else
    { 

      echo '
    <form id="form_produccion" name="form_produccion" method="post" action="alptPesajeR.php"/>
      <input type="hidden" id="text_empleado" name="text_empleado" value="'.$alptPesaje_cdgempleado.'" />      
      <input type="hidden" id="text_rollo" name="text_rollo" value="'.$alptPesaje_cdgrollo.'" />
      
      <table align="center">
        <thead>
          <tr><th colspan="2">'.$sistModulo_modulo.'</th></tr>
        </thead>
        <tbody>
          <tr><td><label for="label_ttlempleado">Empleado</label><br/>
              <label for="label_empleado">'.$alptPesaje_idempleado.' '.$alptPesaje_empleado.'</label></td>
            <td></td></tr>
          <tr><td align="left">
              <table>
                <thead>
                  <tr><th colspan="3">Informaci&oacute;n</th></tr>
                </thead>
                <tbody>
                  <tr><td>Impresi&oacute;n</td>
                    <td colspan="2">'.$alptPesaje_impresion.'</td></tr>
                  <tr><td><h1>NoOP</h1></td>                    
                      <td><h1>'.$alptPesaje_noop.'</h1></td></tr>
                  <tr><td colspan="3" align="right"><a href="alptPesajeR.php?cdgempleado='.$alptPesaje_idempleado.'&cdgmaquina='.$alptPesaje_idmaquina.'" target="cargador">Cambio de rollo</a><br/></td></tr>
                </tbody>
                <tfoot align="left">
                  <tr><td>Longitud</td>
                    <td align="right">'.number_format($alptPesaje_longitud,3).'</td>
                    <td>Metros</td></tr>
                  <tr><td>Ancho plano</td>
                    <td align="right">'.number_format($alptPesaje_amplitud).'</td>
                    <td>Milimetros</td></tr>
                  <tr><td>Peso</td>
                    <td align="right">'.number_format($alptPesaje_peso,3).'</td>
                    <td>Kilogramos</td></tr>
                </tfoot>
              </table><br/>              
            </td>
            <td align="left">
              <table>
                <thead>
                  <tr><th>Captura</th></tr>
                </thead>
                <tbody>
                  <tr><td><label for="ttl_peso">Kilogramos</label><br/>
                      <input type="text" id="text_peso" name="text_peso" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodRollo_pesorollo.'" title="peso del nuevo rollo" '.$text_peso.' required/></td></tr>
                </tbody>
                <tfoot>
                  <tr><th align="right">
                    <input type="submit" id="button_salvar" name="button_salvar" value="Salvar" /></th></tr>
                </tfoot>
              </table>
            </td>
          </tr>
        </tbody>
        <tfoot>
          
        </tfoot>
      </table>
    </form>'; }

    echo '<br/>
    <div align="center"><strong>'.$msg_info.'</strong></div>';

    if ($msg_alert != '')
    { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
  ?>

  </body>
</html>
