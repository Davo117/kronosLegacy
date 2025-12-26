<!DOCTYPE html>
<html>
  <head>
    <title>Producci&oacute;n Fusion</title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '60050';

  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    //Buscame los datos ingresados
    $prodFusion_cdgempleado = trim($_POST['text_empleado']);
  	$prodFusion_cdgmaquina = trim($_POST['text_maquina']);
  	$prodFusion_cdgbobina = trim($_POST['text_bobina']);

  	//Buscar Empleado
  	$link_mysql = conectar();
  	$rechEmpleadoSelect = $link_mysql->query("
  		SELECT * FROM rechempleado
  		WHERE (idempleado = '".$prodFusion_cdgempleado."'
  		OR cdgempleado = '".$prodFusion_cdgempleado."')
  	  AND sttempleado >= '1'");

    if ($rechEmpleadoSelect->num_rows > 0)
    { $regRechEmpleado = $rechEmpleadoSelect->fetch_object();

    	$prodFusion_idempleado = $regRechEmpleado->idempleado;
    	$prodFusion_empleado = $regRechEmpleado->empleado;
    	$prodFusion_cdgempleado = $regRechEmpleado->cdgempleado;

      //Buscar Maquina Proceso 40 Fusion
      $link_mysql = conectar();
    	$prodMaquinaSelect = $link_mysql->query("
    		SELECT * FROM prodmaquina
    		WHERE (idmaquina = '".$prodFusion_cdgmaquina."'
    		OR cdgmaquina = '".$prodFusion_cdgmaquina."')
    	  AND cdgproceso = '40'
    	  AND sttmaquina >= '1'");

      if ($prodMaquinaSelect->num_rows > 0)
      { $regProdMaquina = $prodMaquinaSelect->fetch_object();

        $prodFusion_idmaquina = $regProdMaquina->idmaquina;
        $prodFusion_maquina = $regProdMaquina->maquina;
        $prodFusion_cdgmaquina = $regProdMaquina->cdgmaquina;

        //Buscar lote Proceso 40:9 Liberada
        $link_mysql = conectar();
        $prodLoteSelect = $link_mysql->query("
          SELECT proglote.lote,
            proglote.tarima,
            proglote.idlote,
            prodlote.cdglote,
            prodlote.noop,
            prodbobina.bobina,
            pdtoproyecto.proyecto,
            pdtoimpresion.impresion,
            pdtomezcla.mezcla,
            pdtomezcla.idmezcla,
            prodbobina.longitud,
            prodbobina.amplitud,
            prodbobina.peso,
            prodbobina.cdgbobina
          FROM proglote,
            prodlote,
            prodbobina,
            pdtomezcla,
            pdtoimpresion,
            pdtoproyecto
          WHERE (proglote.cdglote = prodlote.cdglote
            AND prodlote.cdglote = prodbobina.cdglote)
          AND ((prodbobina.cdgbobina = '".$prodFusion_cdgbobina."')
            OR (CONCAT(prodlote.noop,'-',prodbobina.bobina) = '".$prodFusion_cdgbobina."'))
          AND (prodlote.cdgmezcla = pdtomezcla.cdgmezcla
            AND pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion
            AND pdtoimpresion.cdgproyecto = pdtoproyecto.cdgproyecto)
          AND prodbobina.sttbobina = '9'");

  	    if ($prodLoteSelect->num_rows > 0)
  	    { $regProdLote = $prodLoteSelect->fetch_object();

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
          $prodFusion_idmezcla = $regProdLote->idmezcla;
          $prodFusion_mezcla = $regProdLote->mezcla;
          $prodFusion_cdgmezcla = $regProdLote->cdgmezcla;
  	    	$prodFusion_cdgbobina = $regProdLote->cdgbobina;
          //$prodFusion_numrollos = (round($regProdLote->longitud/500)+1); 

          $error_longitud = false;
          $error_amplitud = false;
          $error_peso = false;

          for ($id_rollo = 1; $id_rollo <= $prodFusion_numrollos; $id_rollo++)
          { if (is_numeric($_POST['text_longitud'.$id_rollo]))
            { $prodFusion_newlongitud[$id_rollo] = $_POST['text_longitud'.$id_rollo]; }
            else
            { $error_longitud = true;
              $text_longitudes[$id_rollo] = 'autofocus';

              $id_rollo = $prodFusion_numrollos+1; }
          }

          if ($error_longitud == false)
          { for ($id_rollo = 1; $id_rollo <= $prodFusion_numrollos; $id_rollo++)
            { if (is_numeric($_POST['text_amplitud'.$id_rollo]))
              { $prodFusion_newamplitud[$id_rollo] = $_POST['text_amplitud'.$id_rollo]; }
              else
              { $error_amplitud = true;
                $text_amplitudes[$id_rollo] = 'autofocus';

                $id_rollo = $prodFusion_numrollos+1; }
            }

            if ($error_amplitud == false)
            { for ($id_rollo = 1; $id_rollo <= $prodFusion_numrollos; $id_rollo++)
              { if (is_numeric($_POST['text_peso'.$id_rollo]))
                { $prodFusion_newpeso[$id_rollo] = $_POST['text_peso'.$id_rollo]; }
                else
                { $error_peso = true;
                  $text_pesos[$id_rollo] = 'autofocus';

                  $id_rollo = $prodFusion_numrollos+1; }
              }
            }
          }

  			  if ($_POST['button_salvar'])
  			  { // Salvar
            $fchoperacion = date('Y-m-d');

            if ($error_longitud == false)
            { if ($error_amplitud == false)
              { if ($error_peso == false)
                {
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

                    $link_mysqli = conectar();
                    $link_mysqli->query("
                      INSERT INTO prodrollo
                      (cdgbobina, rollo, cdgproceso, longitud, amplitud, peso, cdgrollo, fchmovimiento)
                      VALUES
                      ('".$prodFusion_cdgbobina."', '".$id_rollo."', '50', '".$prodFusion_newlongitud[$id_rollo]."', '".$prodFusion_newamplitud[$id_rollo]."', '".$prodFusion_newpeso[$id_rollo]."', '".$prodFusion_cdgrollo."', NOW())");

                    $msg_alert .= 'Fusion de rollo '.$id_rollo.' \n'; }

                  $link_mysqli = conectar();
                  $link_mysqli->query("
                    UPDATE prodbobina
                    SET sttbobina = '0',
                      fchmovimiento = NOW()
                    WHERE cdgbobina = '".$prodFusion_cdgbobina."'");

                  if ($link_mysqli->affected_rows > 0)
                  { $msg_alert .= 'Bobina afectada.'; }

                  $prodFusion_cdgbobina = '';

                  $text_bobina = 'autofocus';
                } else
                { $msg_alert = 'Informacion de NUEVOS pesos, incorrecta.'; }
              } else
              { $msg_alert = 'Informacion de NUEVAS amplitudes, incorrecta.'; }
            } else
            { $msg_alert = 'Informacion de NUEVAS longitudes, incorrecta.'; }
  			  }

  	    } else
  	    { $prodFusion_cdgbobina = '';
          $msg_alert = 'Informacion de bobina, incorrecta.';
          $text_bobina = 'autofocus'; }
      } else
      { $prodFusion_cdgmaquina = '';
        $msg_alert = 'Informacion de maquina, incorrecta.';
        $text_maquina = 'autofocus'; }
    } else
    { $prodFusion_cdgempleado = '';
      $msg_alert = 'Informacion de empleado, incorrecta.';
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
          <tr><td><label for="label_ttlempleado">Empleado</label><br/>
              <input type="text" style="width:120px" id="text_empleado" name="text_empleado" value="'.$prodFusion_cdgempleado.'" '.$text_empleado.' required/></td></tr>
          <tr><td><label for="label_ttlmaquina">M&aacute;quina</label><br/>
              <input type="text" style="width:120px" id="text_maquina" name="text_maquina" value="'.$prodFusion_cdgmaquina.'" '.$text_maquina.' required/></td></tr>
          <tr><td><label for="label_ttllote">Lote</label><br/>
              <input type="text" style="width:120px" id="text_bobina" name="text_bobina" value="'.$prodFusion_cdgbobina.'" '.$text_bobina.' required/></td></tr>
        </tbody>
        <tfoot>
          <tr><th align="right"><input type="submit" id="button_buscar" name="button_buscar" value="Buscar" /></th></tr>
        </tfoot>
      </table>
    </form>'; }
    else
    { echo '
    <form id="form_produccion" name="form_produccion" method="post" action="prodFusion.php"/>
      <table align="center">
        <thead>
          <tr>
            <th colspan="3">'.$sistModulo_modulo.'</th>
          </tr>
        </thead>
        <tbody>
          <tr><td colspan="3"><label for="label_ttlempleado">Empleado</label><br/>
              <label for="label_empleado">'.$prodFusion_idempleado.' '.$prodFusion_empleado.'</label><br/>
              <input type="hidden" id="text_empleado" name="text_empleado" value="'.$prodFusion_cdgempleado.'" /></td></tr>
          <tr><td colspan="3"><label for="label_ttlmaquina">M&aacute;quina</label><br/>
              <label for="label_maquina">'.$prodFusion_idmaquina.' '.$prodFusion_maquina.'</label><br/>
              <input type="hidden" id="text_maquina" name="text_maquina" value="'.$prodFusion_cdgmaquina.'" /></td></tr>
          <tr><td colspan="3">
              <table align="center">
                <thead>
                  <tr><th colspan="3">Informaci&oacute;n</th></tr>
                </thead>
                <tbody>
                  <tr><td>No. Lote</td>
                    <td colspan="2">'.$prodFusion_lote.'</td></tr>
                  <tr><td>Tarima/Lote</td>
                    <td colspan="2">'.$prodFusion_tarima.'/'.$prodFusion_idlote.'</td></tr>
                  <tr><td>Longitud</td>
                    <td align="right">'.number_format($prodFusion_longitud,3).'</td>
                    <td>Metros</td></tr>
                  <tr><td>Ancho plano</td>
                    <td align="right">'.number_format($prodFusion_amplitud).'</td>
                    <td>Milimetros</td></tr>
                  <tr><td>Peso</td>
                    <td align="right">'.number_format($prodFusion_peso,3).'</td>
                    <td>Kilogramos</td></tr>
                </tbody>
                <tfoot align="left">
                  <tr><th>Proyecto</th>
                    <th colspan="2">'.$prodFusion_proyecto.'</th></tr>
                  <tr><th>Impresi&oacute;n</th>
                    <th colspan="2">'.$prodFusion_impresion.'</th></tr>
                  <tr><th>Mezcla</th>
                    <th colspan="2">'.$prodFusion_idmezcla.'<br/>'.$prodFusion_mezcla.'</th></tr>
                  <tr><th><h1>NoOP</h1></th>
                    <th colspan="2" align="right"><h1>'.$prodFusion_noop.'-'.$prodFusion_bobina.'</h1></th></tr>
                </tfoot>
              </table>
              <input type="hidden" id="text_bobina" name="text_bobina" value="'.$prodFusion_cdgbobina.'" '.$text_bobina.' required/>
            </td>
          </tr>
          <tr><td>Longitud <br/>
              <input type="text" id="text_longitudrollo" name="text_longitudrollo" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodFusion_longitudrollo.'" title="Longitud del nuevo rollo Fusion" '.$text_longituderollo.' required/></td>
            <td>Amplitud <br/>
              <input type="text" id="text_amplitudrollo" name="text_amplitudrollo" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodFusion_amplitudrollo.'" title="Amplitud del nuevo rollo Fusion" '.$text_amplituderollo.' required/></td>
            <td><label for="ttl_kilogramos">Kilogramos</label><br/>
              <input type="text" id="text_pesorollo" name="text_pesorollo" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodFusion_pesorollo.'" title="Nuevo peso de lote/bobina Fusion" '.$text_pesorollo.' required/></tr>
        </tbody>
        <tfoot>
          <tr><th colspan="3" align="right"><input type="submit" id="button_salvar" name="button_salvar" value="Salvar" /></th></tr>
        </tfoot>
      </table>
    </form>'; }

    if ($msg_alert != '')
    { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
  ?>

  </body>
</html>
