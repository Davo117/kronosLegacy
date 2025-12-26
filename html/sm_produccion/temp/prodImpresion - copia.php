<!DOCTYPE html>
<html>
  <head>
    <title>Producci&oacute;n Impresi&oacute;n</title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '60020';

  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);   

    //Buscame los datos ingresados    
    $prodImpresion_cdgempleado = trim($_POST['text_empleado']);
  	$prodImpresion_cdgmaquina = trim($_POST['text_maquina']);
  	$prodImpresion_cdglote = trim($_POST['text_lote']);

    if (is_numeric($_POST['text_longitud']))
    { $prodImpresion_newlongitud = $_POST['text_longitud']; }

    if (is_numeric($_POST['text_peso']))
    { $prodImpresion_newpeso = $_POST['text_peso']; }

  	//Buscar Empleado
  	$link_mysql = conectar();
  	$rechEmpleadoSelect = $link_mysql->query("
  		SELECT * FROM rechempleado 
  		WHERE (idempleado = '".$prodImpresion_cdgempleado."'
  		OR cdgempleado = '".$prodImpresion_cdgempleado."')
  	  AND sttempleado >= '1'");
    
    if ($rechEmpleadoSelect->num_rows > 0)
    { $regRechEmpleado = $rechEmpleadoSelect->fetch_object();

    	$prodImpresion_idempleado = $regRechEmpleado->idempleado;
    	$prodImpresion_empleado = $regRechEmpleado->empleado;
    	$prodImpresion_cdgempleado = $regRechEmpleado->cdgempleado;

      //Buscar Maquina Proceso 20 Impresión
      $link_mysql = conectar();
    	$prodMaquinaSelect = $link_mysql->query("
    		SELECT * FROM prodmaquina 
    		WHERE (idmaquina = '".$prodImpresion_cdgmaquina."'
    		OR cdgmaquina = '".$prodImpresion_cdgmaquina."')
    	  AND cdgproceso = '20'
    	  AND sttmaquina >= '1'");
      
      if ($prodMaquinaSelect->num_rows > 0)
      { $regProdMaquina = $prodMaquinaSelect->fetch_object();

      	$prodImpresion_idmaquina = $regProdMaquina->idmaquina;
      	$prodImpresion_maquina = $regProdMaquina->maquina;
      	$prodImpresion_cdgmaquina = $regProdMaquina->cdgmaquina;

        //Buscar lote Proceso 10 Liberada
        $link_mysql = conectar();
  	  	$prodLoteSelect = $link_mysql->query("
  	  		SELECT proglote.idlote,
            proglote.lote,
            prodlote.noop,
            pdtoproyecto.proyecto,
            pdtoimpresion.impresion,
            prodlote.cdgmezcla,
            pdtomezcla.idmezcla, 
            pdtomezcla.mezcla,
            prodlote.longitud,
            proglote.amplitud,
            prodlote.peso,
            proglote.tarima,
            prodlote.cdglote
          FROM proglote,
            prodlote,
            pdtoproyecto,
            pdtoimpresion,
            pdtomezcla
  	  		WHERE proglote.cdglote = prodlote.cdglote
          AND (prodlote.noop = '".$prodImpresion_cdglote."'
  	  		OR prodlote.cdglote = '".$prodImpresion_cdglote."')
          AND (prodlote.cdgmezcla = pdtomezcla.cdgmezcla
          AND pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion
          AND pdtoimpresion.cdgproyecto = pdtoproyecto.cdgproyecto)
          AND prodlote.cdgproceso = '10'
  	  	  AND prodlote.sttlote >= '1'");
  	    
  	    if ($prodLoteSelect->num_rows > 0)
  	    { $regProdLote = $prodLoteSelect->fetch_object();

          $prodImpresion_idlote = $regProdLote->idlote;
          $prodImpresion_lote = $regProdLote->lote;
          $prodImpresion_noop = $regProdLote->noop;
          $prodImpresion_longitud = $regProdLote->longitud;
          $prodImpresion_amplitud = $regProdLote->amplitud;
          $prodImpresion_peso = $regProdLote->peso;
          $prodImpresion_tarima = $regProdLote->tarima;
          $prodImpresion_proyecto = $regProdLote->proyecto;
          $prodImpresion_impresion = $regProdLote->impresion;
          $prodImpresion_idmezcla = $regProdLote->idmezcla;
          $prodImpresion_mezcla = $regProdLote->mezcla;
          $prodImpresion_cdgmezcla = $regProdLote->cdgmezcla;          
  	    	$prodImpresion_cdglote = $regProdLote->cdglote;

  			  if ($_POST['button_salvar'])
  			  { // Salvar  			    
            $fchoperacion = date('Y-m-d');
            //$fchoperacion = '2012-09-07';

            if ($prodImpresion_newlongitud > 0)
            { if ($prodImpresion_newpeso > 0)
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  INSERT INTO prodproclote
                    (cdglote, cdgproceso, longitud, amplitud, peso, fchmovimiento)
                  VALUES
                    ('".$prodImpresion_cdglote."', '20', '".$prodImpresion_longitud."', '".$prodImpresion_amplitud."', '".$prodImpresion_peso."', NOW())"); 

                if ($link_mysqli->affected_rows > 0) 
                { $msg_alert .= 'Operacion registrada. \n'; 

                  $link_mysqli = conectar();
                  $link_mysqli->query("
                    INSERT INTO prodloteope
                      (cdglote, cdgoperacion, cdgempleado, cdgmaquina, fchoperacion, fchmovimiento)
                    VALUES
                      ('".$prodImpresion_cdglote."', '20001', '".$prodImpresion_cdgempleado."', '".$prodImpresion_cdgmaquina."', '".$fchoperacion."', NOW())");                       

                  if ($link_mysqli->affected_rows > 0) 
                  { $msg_alert .= 'Proceso registrado. \n'; 

                    $link_mysqli = conectar();
                    $link_mysqli->query("
                      UPDATE prodlote
                      SET cdgproceso = '30',
                        longitud = '".$prodImpresion_newlongitud."', 
                        peso = '".$prodImpresion_newpeso."', 
                        fchmovimiento = NOW()
                      WHERE cdglote = '".$prodImpresion_cdglote."'");            

                    if ($link_mysqli->affected_rows > 0) 
                    { $msg_alert .= 'Bobina afectada.'; }
                  }
                }
                
                $prodImpresion_cdglote = '';

                $text_lote = 'autofocus';
              }
              else
              { $msg_alert = 'Informacion de NUEVO peso, incorrecta.';
                $text_peso = 'autofocus'; } 
            }
            else 
            { $msg_alert = 'Informacion de NUEVA longitud, incorrecta.';
              $text_longitud = 'autofocus'; }           
  			  }
          else
          { $text_longitud = 'autofocus'; }  
  	    }
  	    else
  	    { $prodImpresion_cdglote = '';
          $msg_alert = 'Informacion de bobina, incorrecta.';
          $text_lote = 'autofocus'; }
      }
      else
      { $prodImpresion_cdgmaquina = '';
        $msg_alert = 'Informacion de maquina, incorrecta.';
        $text_maquina = 'autofocus'; }
    }
    else
    { $prodImpresion_cdgempleado = '';
      $msg_alert = 'Informacion de empleado, incorrecta.';
      $text_empleado = 'autofocus'; }

    if ($prodImpresion_cdgempleado == '' OR $prodImpresion_cdgmaquina == '' OR $prodImpresion_cdglote == '')
    { echo '
    <form id="form_prodimpresion" name="form_pdtoimpresion" method="post" action="prodImpresion.php"/>
      <table align="center">
        <thead>
          <tr>
            <th>'.$sistModulo_modulo.'</th>
          </tr>
        </thead>
        <tbody>
          <tr><td><label for="label_ttlempleado">Empleado</label><br/>
              <input type="text" style="width:120px" id="text_empleado" name="text_empleado" value="'.$prodImpresion_cdgempleado.'" '.$text_empleado.' required/></td></tr>
          <tr><td><label for="label_ttlmaquina">M&aacute;quina</label><br/>
              <input type="text" style="width:120px" id="text_maquina" name="text_maquina" value="'.$prodImpresion_cdgmaquina.'" '.$text_maquina.' required/></td></tr>
          <tr><td><label for="label_ttllote">Lote</label><br/>
              <input type="text" style="width:120px" id="text_lote" name="text_lote" value="'.$prodImpresion_cdglote.'" '.$text_lote.' required/></td></tr>              
        </tbody>
        <tfoot>
          <tr><th align="right"><input type="submit" id="button_buscar" name="button_buscar" value="Buscar" /></th></tr>
        </tfoot>
      </table>
    </form>'; }
    else
    { echo '
    <form id="form_prodimpresion" name="form_pdtoimpresion" method="post" action="prodImpresion.php"/>
      <table align="center">
        <thead>
          <tr>
            <th colspan="2">'.$sistModulo_modulo.'</th>
          </tr>
        </thead>
        <tbody>
          <tr><td colspan="2"><label for="label_ttlempleado">Empleado</label><br/>
              <label for="label_empleado">'.$prodImpresion_idempleado.' '.$prodImpresion_empleado.'</label><br/>
              <input type="hidden" id="text_empleado" name="text_empleado" value="'.$prodImpresion_cdgempleado.'" /></td></tr>
          <tr><td colspan="2"><label for="label_ttlmaquina">M&aacute;quina</label><br/>
              <label for="label_maquina">'.$prodImpresion_idmaquina.' '.$prodImpresion_maquina.'</label><br/>
              <input type="hidden" id="text_maquina" name="text_maquina" value="'.$prodImpresion_cdgmaquina.'" /></td></tr>
          <tr><td colspan="2">
              <table>
                <thead>
                  <tr><th colspan="3">Informaci&oacute;n</th></tr>
                </thead>
                <tbody>
                  <tr><td>No. Lote</td>
                    <td colspan="2">'.$prodImpresion_lote.'</td></tr>
                  <tr><td>Tarima/Lote</td>
                    <td colspan="2">'.$prodImpresion_tarima.'/'.$prodImpresion_idlote.'</td></tr>
                  <tr><td>Longitud</td>
                    <td align="right">'.number_format($prodImpresion_longitud,3).'</td>
                    <td>Metros</td></tr> 
                  <tr><td>Ancho plano</td>
                    <td align="right">'.number_format($prodImpresion_amplitud).'</td>
                    <td>Milimetros</td></tr>
                  <tr><td>Peso</td>
                    <td align="right">'.number_format($prodImpresion_peso,3).'</td>
                    <td>Kilogramos</td></tr>
                </tbody>
                <tfoot align="left">
                  <tr><th>Proyecto</th>
                    <th colspan="2">'.$prodImpresion_proyecto.'</th></tr>
                  <tr><th>Impresi&oacute;n</th>
                    <th colspan="2">'.$prodImpresion_impresion.'</th></tr>
                  <tr><th>Mezcla</th>
                    <th colspan="2">'.$prodImpresion_idmezcla.'<br/>'.$prodImpresion_mezcla.'</th></tr>
                  <tr><th><h1>NoOP</h1></th>
                    <th colspan="2" align="right"><h1>'.$prodImpresion_noop.'</h1></th></tr>
                </tfoot>
              </table>
              <input type="hidden" id="text_lote" name="text_lote" value="'.$prodImpresion_cdglote.'" '.$text_lote.' required/>
            </td>
          </tr>
          <tr><td>Longitud final<br/>
              <input type="text" id="text_longitud" name="text_longitud" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodImpresion_newlongitud.'" title="Nueva longitud de lote/bobina" '.$text_longitud.' required/></td>
            <td><label for="ttl_kilogramos">Kilogramos</label><br/>
              <input type="text" id="text_peso" name="text_peso" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodImpresion_newpeso.'" title="Nuevo peso de lote/bobina" '.$text_peso.' required/></tr>
        </tbody>
        <tfoot>
          <tr><th colspan="2" align="right"><input type="submit" id="button_salvar" name="button_salvar" value="Salvar" /></th></tr>
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