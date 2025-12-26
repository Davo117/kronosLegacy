<!DOCTYPE html>
<html>
  <head>
    <title>Producci&oacute;n Refilado</title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '60030';

  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    //Buscame los datos ingresados
    $prodRefilado_cdgempleado = trim($_POST['text_empleado']);
  	$prodRefilado_cdgmaquina = trim($_POST['text_maquina']);
  	$prodRefilado_cdglote = trim($_POST['text_lote']);

  	//Buscar Empleado
  	$link_mysql = conectar();
  	$rechEmpleadoSelect = $link_mysql->query("
  		SELECT * FROM rechempleado
  		WHERE (idempleado = '".$prodRefilado_cdgempleado."'
  		OR cdgempleado = '".$prodRefilado_cdgempleado."')
  	  AND sttempleado >= '1'");

    if ($rechEmpleadoSelect->num_rows > 0)
    { $regRechEmpleado = $rechEmpleadoSelect->fetch_object();

    	$prodRefilado_idempleado = $regRechEmpleado->idempleado;
    	$prodRefilado_empleado = $regRechEmpleado->empleado;
    	$prodRefilado_cdgempleado = $regRechEmpleado->cdgempleado;

      //Buscar Maquina Proceso 30 Refilado
      $link_mysql = conectar();
    	$prodMaquinaSelect = $link_mysql->query("
    		SELECT * FROM prodmaquina
    		WHERE (idmaquina = '".$prodRefilado_cdgmaquina."'
    		OR cdgmaquina = '".$prodRefilado_cdgmaquina."')
    	  AND cdgproceso = '30'
    	  AND sttmaquina >= '1'");

      if ($prodMaquinaSelect->num_rows > 0)
      { $regProdMaquina = $prodMaquinaSelect->fetch_object();

      	$prodRefilado_idmaquina = $regProdMaquina->idmaquina;
      	$prodRefilado_maquina = $regProdMaquina->maquina;
      	$prodRefilado_cdgmaquina = $regProdMaquina->cdgmaquina;

        //Buscar lote Proceso 30 Liberada
        $link_mysql = conectar();
  	  	$prodLoteSelect = $link_mysql->query("
  	  		SELECT proglote.idlote,
            proglote.lote,
            proglote.tarima,
            prodlote.noop,
            prodlote.cdgproducto,
            prodlote.longitud,
            prodlote.amplitud,
            prodlote.peso,
            prodlote.cdglote,
            pdtodiseno.alpaso,
            pdtodiseno.diseno,
            pdtoimpresion.impresion,
            pdtoimpresion.cdgimpresion
          FROM proglote,
            prodlote,
            pdtodiseno,
            pdtoimpresion
  	      WHERE proglote.cdglote = prodlote.cdglote AND 
          (prodlote.noop = '".$prodRefilado_cdglote."' OR prodlote.cdglote = '".$prodRefilado_cdglote."') AND 
          (prodlote.cdgproducto = pdtoimpresion.cdgimpresion AND pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno) AND 
           prodlote.sttlote = '1'");

  	    if ($prodLoteSelect->num_rows > 0)
  	    { $regProdLote = $prodLoteSelect->fetch_object();

          $prodRefilado_idlote = $regProdLote->idlote;
          $prodRefilado_lote = $regProdLote->lote;
          $prodRefilado_noop = $regProdLote->noop;
          $prodRefilado_longitud = $regProdLote->longitud;
          $prodRefilado_amplitud = $regProdLote->amplitud;
          $prodRefilado_peso = $regProdLote->peso;
          $prodRefilado_tarima = $regProdLote->tarima;
          $prodRefilado_proyecto = $regProdLote->diseno;
          $prodRefilado_impresion = $regProdLote->impresion;
          $prodRefilado_cdgimpresion = $regProdLote->cdgimpresion;
          $prodRefilado_numbobinas = $regProdLote->alpaso;
          $prodRefilado_cdgmezcla = $regProdLote->cdgproducto;
  	    	$prodRefilado_cdglote = $regProdLote->cdglote;

          $error_longitud = false;
          $error_amplitud = false;
          $error_peso = false;

          for ($id_bobina = 1; $id_bobina <= $prodRefilado_numbobinas; $id_bobina++)
          { if (is_numeric($_POST['text_longitud'.$id_bobina]))
            { $prodRefilado_newlongitud[$id_bobina] = $_POST['text_longitud'.$id_bobina]; }
            else
            { $error_longitud = true;
              $text_longitudes[$id_bobina] = 'autofocus';

              $id_bobina = $prodRefilado_numbobinas+1; }
          }

          if ($error_longitud == false)
          { for ($id_bobina = 1; $id_bobina <= $prodRefilado_numbobinas; $id_bobina++)
            { if (is_numeric($_POST['text_amplitud'.$id_bobina]))
              { $prodRefilado_newamplitud[$id_bobina] = $_POST['text_amplitud'.$id_bobina]; }
              else
              { $error_amplitud = true;
                $text_amplitudes[$id_bobina] = 'autofocus';

                $id_bobina = $prodRefilado_numbobinas+1; }
            }

            if ($error_amplitud == false)
            { for ($id_bobina = 1; $id_bobina <= $prodRefilado_numbobinas; $id_bobina++)
              { if (is_numeric($_POST['text_peso'.$id_bobina]))
                { $prodRefilado_newpeso[$id_bobina] = $_POST['text_peso'.$id_bobina]; }
                else
                { $error_peso = true;
                  $text_pesos[$id_bobina] = 'autofocus';

                  $id_bobina = $prodRefilado_numbobinas+1; }
              }
            }
          }

  			  if ($_POST['button_salvar'])
  			  { // Salvar
            $fchoperacion = date('Y-m-d');            

            if ($error_longitud == false)
            { if ($error_amplitud == false)
              { if ($error_peso == false)
                { $link_mysqli = conectar();
                  $link_mysqli->query("
                    INSERT INTO prodloteproc
                      (cdglote, cdgproceso, longitud, amplitud, peso, fchmovimiento)
                    VALUES
                      ('".$prodRefilado_cdglote."', '30', '".$prodRefilado_longitud."', '".$prodRefilado_amplitud."', '".$prodRefilado_peso."', NOW())"); 
                
                  $link_mysqli = conectar();
                  $link_mysqli->query("
                    INSERT INTO prodloteope
                      (cdglote, cdgoperacion, cdgempleado, cdgmaquina, longin, longitud, peso, fchoperacion, fchmovimiento)
                    VALUES
                      ('".$prodRefilado_cdglote."', '30001', '".$prodRefilado_cdgempleado."', '".$prodRefilado_cdgmaquina."', '".$prodRefilado_longitud."', '".$prodRefilado_longitud."', '".$prodRefilado_peso."', '".$fchoperacion."', NOW())");                
                
                  for ($id_bobina = 1; $id_bobina <= $prodRefilado_numbobinas; $id_bobina++)
                  { $prodRefilado_cdgbobina = substr($prodRefilado_cdglote,0,8).$id_bobina.'000';

                    $link_mysqli = conectar();
                    $link_mysqli->query("
                      INSERT INTO prodbobina
                      (cdglote, bobina, cdgproducto, cdgproceso, longitud, amplitud, peso, cdgbobina, fchmovimiento)
                      VALUES
                      ('".$prodRefilado_cdglote."', '".$id_bobina."', '".$prodRefilado_cdgimpresion."', '30', '".$prodRefilado_newlongitud[$id_bobina]."', '".$prodRefilado_newamplitud[$id_bobina]."', '".$prodRefilado_newpeso[$id_bobina]."', '".$prodRefilado_cdgbobina."', NOW())");

                    $link_mysqli = conectar();
                    $link_mysqli->query("
                      INSERT INTO prodbobinaope
                        (cdgbobina, cdgoperacion, cdgempleado, cdgmaquina, longitud, longitudfin, peso, pesofin, fchoperacion, fchmovimiento)
                      VALUES
                        ('".$prodRefilado_cdgbobina."', '30001', '".$prodRefilado_cdgempleado."', '".$prodRefilado_cdgmaquina."', '".$prodRefilado_longitud."', '".$prodRefilado_newlongitud[$id_bobina]."', '".($prodRefilado_peso/$prodRefilado_numbobinas)."', '".$prodRefilado_newpeso[$id_bobina]."', '".$fchoperacion."', NOW())");

                    $msg_alert .= 'Refilado '.$id_bobina.' \n'; }

                  $link_mysqli = conectar();
                  $link_mysqli->query("
                  UPDATE prodlote
                  SET sttlote = '9',
                    fchmovimiento = NOW()
                  WHERE cdglote = '".$prodRefilado_cdglote."'");

                  if ($link_mysqli->affected_rows > 0)
                  { $msg_alert .= 'Bobina afectada.'; }

                  $msg_modulo = '<a href="pdf/prodBobinasBC.php?cdglote='.$prodRefilado_cdglote.'" target="_blank">Generar etiquetas</a>';

                  $prodRefilado_cdglote = '';

                  $text_lote = 'autofocus';
                }
                else
                { $msg_alert = 'Informacion de NUEVOS pesos, incorrecta.'; }
              }
              else
              { $msg_alert = 'Informacion de NUEVAS amplitudes, incorrecta.'; }
            }
            else
            { $msg_alert = 'Informacion de NUEVAS longitudes, incorrecta.'; }
  			  }

  	    }
  	    else
  	    { $prodRefilado_cdglote = '';
          $msg_alert = 'Informacion de bobina, incorrecta.';
          $text_lote = 'autofocus'; }
      }
      else
      { $prodRefilado_cdgmaquina = '';
        $msg_alert = 'Informacion de maquina, incorrecta.';
        $text_maquina = 'autofocus'; }
    }
    else
    { $prodRefilado_cdgempleado = '';
      $msg_alert = 'Informacion de empleado, incorrecta.';
      $text_empleado = 'autofocus'; }

    if ($prodRefilado_cdgempleado == '' OR $prodRefilado_cdgmaquina == '' OR $prodRefilado_cdglote == '')
    { echo '
    <form id="form_prodrefilado" name="form_prodrefilado" method="post" action="prodRefilado.php"/>
      <table align="center">
        <thead>
          <tr>
            <th>'.$sistModulo_modulo.'</th>
          </tr>
        </thead>
        <tbody>
          <tr><td><label for="label_ttlempleado">Empleado</label><br/>
              <input type="text" style="width:120px" id="text_empleado" name="text_empleado" value="'.$prodRefilado_idempleado.'" '.$text_empleado.' required/></td></tr>
          <tr><td><label for="label_ttlmaquina">M&aacute;quina</label><br/>
              <input type="text" style="width:120px" id="text_maquina" name="text_maquina" value="'.$prodRefilado_idmaquina.'" '.$text_maquina.' required/></td></tr>
          <tr><td><label for="label_ttllote">Lote</label><br/>
              <input type="text" style="width:120px" id="text_lote" name="text_lote" value="'.$prodRefilado_cdglote.'" '.$text_lote.' required/></td></tr>
        </tbody>
        <tfoot>
          <tr><th align="right"><input type="submit" id="button_buscar" name="button_buscar" value="Buscar" /></th></tr>
        </tfoot>
      </table>
    </form>'; }
    else
    { echo '
    <form id="form_prodrefilado" name="form_prodrefilado" method="post" action="prodRefilado.php"/>
      <table align="center">
        <thead>
          <tr>
            <th colspan="3">'.$sistModulo_modulo.'</th>
          </tr>
        </thead>
        <tbody>
          <tr><td colspan="3"><label for="label_ttlempleado">Empleado</label><br/>
              <label for="label_empleado">'.$prodRefilado_idempleado.' '.$prodRefilado_empleado.'</label><br/>
              <input type="hidden" id="text_empleado" name="text_empleado" value="'.$prodRefilado_cdgempleado.'" /></td></tr>
          <tr><td colspan="3"><label for="label_ttlmaquina">M&aacute;quina</label><br/>
              <label for="label_maquina">'.$prodRefilado_idmaquina.' '.$prodRefilado_maquina.'</label><br/>
              <input type="hidden" id="text_maquina" name="text_maquina" value="'.$prodRefilado_cdgmaquina.'" /></td></tr>
          <tr><td colspan="3">
              <table align="center">
                <thead>
                  <tr><th colspan="3">Informaci&oacute;n</th></tr>
                </thead>
                <tbody>
                  <tr><td>No. Lote</td>
                    <td colspan="2">'.$prodRefilado_lote.'</td></tr>
                  <tr><td>Tarima/Lote</td>
                    <td colspan="2">'.$prodRefilado_tarima.'/'.$prodRefilado_idlote.'</td></tr>
                  <tr><td>Longitud</td>
                    <td align="right">'.number_format($prodRefilado_longitud,3).'</td>
                    <td>Metros</td></tr>
                  <tr><td>Ancho plano</td>
                    <td align="right">'.number_format($prodRefilado_amplitud).'</td>
                    <td>Milimetros</td></tr>
                  <tr><td>Peso</td>
                    <td align="right">'.number_format($prodRefilado_peso,3).'</td>
                    <td>Kilogramos</td></tr>
                </tbody>
                <tfoot align="left">
                  <tr><th>Proyecto</th>
                    <th colspan="2">'.$prodRefilado_proyecto.'</th></tr>
                  <tr><th>Impresi&oacute;n</th>
                    <th colspan="2">'.$prodRefilado_impresion.'</th></tr>
                  <tr><th>Mezcla</th>
                    <th colspan="2">'.$prodRefilado_idmezcla.'<br/>'.$prodRefilado_mezcla.'</th></tr>
                  <tr><th><h1>NoOP</h1></th>
                    <th colspan="2" align="right"><h1>'.$prodRefilado_noop.'</h1></th></tr>
                </tfoot>
              </table>
              <input type="hidden" id="text_lote" name="text_lote" value="'.$prodRefilado_cdglote.'" '.$text_lote.' required/>
            </td>
          </tr>';

      for ($id_bobina = 1; $id_bobina <= $prodRefilado_numbobinas; $id_bobina++)
      { echo '
          <tr><td>Longitud ['.$id_bobina.'] <br/>
              <input type="text" id="text_longitud'.$id_bobina.'" name="text_longitud'.$id_bobina.'" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodRefilado_newlongitud[$id_bobina].'" title="Nueva longitud de lote/bobina refilado '.$id_bobina.'" '.$text_longitudes[$id_bobina].' required/></td>
            <td>Amplitud ['.$id_bobina.'] <br/>
              <input type="text" id="text_amplitud'.$id_bobina.'" name="text_amplitud'.$id_bobina.'" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodRefilado_newamplitud[$id_bobina].'" title="Nueva amplitud de lote/bobina refilado '.$id_bobina.'" '.$text_amplitudes[$id_bobina].' required/></td>
            <td><label for="ttl_kilogramos">Kilogramos ['.$id_bobina.']</label><br/>
              <input type="text" id="text_peso'.$id_bobina.'" name="text_peso'.$id_bobina.'" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodRefilado_newpeso[$id_bobina].'" title="Nuevo peso de lote/bobina refilado '.$id_bobina.'" '.$text_pesos[$id_bobina].' required/></tr>'; }

      echo '
        </tbody>
        <tfoot>
          <tr><th colspan="3" align="right"><input type="submit" id="button_salvar" name="button_salvar" value="Salvar" /></th></tr>
        </tfoot>
      </table>
    </form><br/>'; }

    if ($msg_modulo != '')
    { echo '
      <div align="center"><strong>'.$msg_modulo.'</strong></div><br/>'; }
      
    if ($msg_alert != '')
    { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
    <div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
  ?>
  
  </body>
</html>
