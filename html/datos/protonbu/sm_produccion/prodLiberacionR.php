<!DOCTYPE html>
<html>
  <head>
    <title>Liberacion de rollos</title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '60047';

  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);   

    if ($_SESSION['cdgusuario'] != '')
    { if (trim($_POST['text_rollo']) != '')
      { $prodRollo_cdgrollo = trim($_POST['text_rollo']);

        $link_mysqli = conectar();
        $prodRolloSelect = $link_mysqli->query("
          SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) AS noop,
            pdtoproyecto.idproyecto, pdtoproyecto.proyecto, pdtoproyecto.cdgproyecto,
            pdtoimpresion.idimpresion, pdtoimpresion.impresion, pdtoimpresion.cdgimpresion, 
            pdtomezcla.idmezcla, pdtomezcla.mezcla, pdtomezcla.cdgmezcla,
            prodrollo.longitud, prodrollo.amplitud, prodrollo.peso, prodrollo.bandera,
            prodlote.cdglote, prodbobina.cdgbobina, prodrollo.cdgrollo
          FROM proglote,
            prodlote, prodbobina, prodrollo,
            pdtoproyecto, pdtoimpresion, pdtomezcla
          WHERE (proglote.cdglote = prodlote.cdglote AND prodlote.cdglote = prodbobina.cdglote AND prodbobina.cdgbobina = prodrollo.cdgbobina) AND 
           (prodrollo.cdgrollo = '".$prodRollo_cdgrollo."' OR CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) = '".$prodRollo_cdgrollo."') AND 
           (prodlote.cdgmezcla = pdtomezcla.cdgmezcla AND pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion AND pdtoimpresion.cdgproyecto = pdtoproyecto.cdgproyecto) AND 
            prodrollo.sttrollo = '6'"); 

        if ($prodRolloSelect->num_rows > 0)
        { $regProdRollo = $prodRolloSelect->fetch_object();

          $prodRollo_lote = $regProdRollo->lote;
          $prodRollo_idlote = $regProdRollo->idlote;
          $prodRollo_noop = $regProdRollo->noop;
          $prodRollo_proyecto = $regProdRollo->proyecto;
          $prodRollo_impresion = $regProdRollo->impresion;
          $prodRollo_cdgimpresion = $regProdRollo->cdgimpresion;
          $prodRollo_idmezcla = $regProdRollo->idmezcla;
          $prodRollo_mezcla = $regProdRollo->mezcla;
          $prodRollo_cdgmezcla = $regProdRollo->cdgmezcla;
          $prodRollo_longitud = $regProdRollo->longitud;
          $prodRollo_amplitud = $regProdRollo->amplitud;
          $prodRollo_peso = $regProdRollo->peso;
          $prodRollo_bandera = $regProdRollo->bandera;
          $prodRollo_cdgrollo = $regProdRollo->cdgrollo;

          $prodRollo_longitudrollo = $regProdRollo->longitud;
          $prodRollo_amplitudrollo = $regProdRollo->amplitud;
          $prodRollo_pesorollo = $regProdRollo->peso;
          $prodRollo_banderarollo = $regProdRollo->bandera;
        
          $text_longitud = 'autofocus';
          if (is_numeric($_POST['text_longitud']))
          { $prodRollo_longitudrollo = $_POST['text_longitud'];

            $text_amplitud = 'autofocus';
            if (is_numeric($_POST['text_amplitud']))
            { $prodRollo_amplitudrollo = $_POST['text_amplitud'];

              $text_peso = 'autofocus';
              if (is_numeric($_POST['text_peso']))
              { $prodRollo_pesorollo = $_POST['text_peso'];

                if ($_POST['button_salvar'])
                { $fchoperacion = date('Y-m-d');                

                  $link_mysqli = conectar();
                  $link_mysqli->query("
                    INSERT INTO prodrolloope
                      (cdgrollo, cdgoperacion, cdgempleado, cdgmaquina, longitudini, longitudfin, pesoini, pesofin, numbandera, fchoperacion, fchmovimiento)
                    VALUES
                      ('".$prodRollo_cdgrollo."', '40007', '".$_SESSION['cdgusuario']."', '00001', '".$prodRollo_longitud."', '".$prodRollo_longitudrollo."', '".$prodRollo_peso."', '".$prodRollo_pesorollo."', '".$prodRollo_banderarollo."', '".$fchoperacion."', NOW())");

                  $link_mysqli = conectar();
                  $link_mysqli->query("
                    INSERT INTO prodrolloproc
                      (cdgrollo, cdgproceso, longitud, amplitud, fchmovimiento)
                    VALUES
                      ('".$prodRollo_cdgrollo."', '47', '".$prodRollo_longitud."', '".$prodRollo_amplitud."', NOW())");                 

                  $link_mysqli = conectar();
                  $link_mysqli->query("
                    UPDATE prodrollo
                    SET sttrollo = '7',
                      longitud = '".$prodRollo_longitudrollo."',
                      amplitud = '".$prodRollo_amplitudrollo."',                      
                      peso = '".$prodRollo_pesorollo."', 
                      fchmovimiento = NOW()                          
                    WHERE cdgrollo = '".$prodRollo_cdgrollo."' AND 
                      sttrollo = '6'");

                  if ($link_mysqli->affected_rows > 0) 
                  { $msg_alert .= 'Rollo actualizado, '.$prodRollo_noop.' ('.$prodRollo_cdgrollo.') disponible como producto terminado.'; }

                  $prodRollo_cdgrollo = '';
                  
                  $txt_rollo = 'autofocus'; }                 
              } else
              { $msg_info = 'Favor de ingresar la informacion de NUEVO PESO.';
                $text_peso = 'autofocus'; } 
                          } else
            { $msg_info = 'Favor de ingresar la informacion de NUEVA AMPLITUD.';
              $text_amplitud = 'autofocus'; } 
          } else
          { $msg_info = 'Favor de ingresar la informacion de NUEVA LONGITUD.';
            $text_longitud = 'autofocus'; } 
        } 

        $text_rollo = 'autofocus'; 
      } else 
      { $msg_alert = 'Favor de ingresar la informacion de Rollo.';
        $prodRollo_cdgrollo = '';
        $text_rollo = 'autofocus'; }

      $prodRolloSelect->close; 
    }    

    echo '
    <form id="form_prodLiberacion" name="form_prodLiberacion" method="post" action="prodLiberacionR.php"/>
      <input type="hidden" id="text_rollo" name="text_rollo" value="'.$prodRollo_cdgrollo.'" required/>
      <table align="center">
        <thead>
          <tr>
            <th colspan="2">'.$sistModulo_modulo.'</th>
          </tr>
        </thead>
        <tbody>
          <tr><td colspan="2"><label for="ttl_empleado"><strong>ID </strong>'.$_SESSION['idusuario'].'</label><br/>
              <label for="info_empleado"><strong>Nombre </strong>'.$_SESSION['usuario'].'</label></td></tr>';

    if ($prodRollo_cdgrollo == '')
    { echo '
          <tr><td colspan="2"><label for="label_ttllote">Rollo</label><br/>
              <input type="text" style="width:120px" id="text_rollo" maxlength="12" name="text_rollo" value="'.$prodRollo_cdgrollo.'" '.$text_rollo.' required/></td></tr>
        </tbody>
        <tfoot>
          <tr><th colspan="2" align="right"><input type="submit" id="button_buscar" name="button_buscar" value="Buscar" /></th></tr>
        </tfoot>
      </table>
    </form>'; }
    else
    { echo '
          <tr><td>
              <table align="center">
                <thead>
                  <tr><th colspan="3">Informaci&oacute;n</th></tr>
                </thead>
                <tbody>
                  <tr><td colspan="3">Impresi&oacute;n: '.$prodRollo_impresion.'</td></tr>
                  <tr><td colspan="3" align="right"><h1>NoOP '.$prodRollo_noop.'</h1></td></tr>
                </tbody>
                <tfoot align="left">
                  <tr><td>Longitud</td>
                    <td align="right">'.number_format($prodRollo_longitud,3).'</td>
                    <td>Metros</td></tr> 
                  <tr><td>Ancho plano</td>
                    <td align="right">'.number_format($prodRollo_amplitud).'</td>
                    <td>Milimetros</td></tr>
                  <tr><td>Peso</td>
                    <td align="right">'.number_format($prodRollo_peso,3).'</td>
                    <td>Kilogramos</td></tr>
                </tfoot>
              </table>
            </td>
            <td align="right">
              <table align="center">
                <thead>
                  <tr><th colspan="4">Actualizaci&oacute;n de datos</th></tr>
                </thead>
                <tbody>
                  <tr><td><label for="ttl_longitud">Longitud</label><br/>
                      <input type="text" id="text_longitud" name="text_longitud" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodRollo_longitudrollo.'" title="Nueva longitud de rollo" '.$text_longitud.' required/></td>
                    <td><label for="ttl_ttlamplitud">Ancho plano</label><br/>
                      <input type="text" id="text_amplitud" name="text_amplitud" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodRollo_amplitudrollo.'" title="Nueva amplitud de rollo" '.$text_amplitud.' required/></td>
                    <td><label for="ttl_ttlpeso">Peso</label><br/>
                      <input type="text" id="text_peso" name="text_peso" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodRollo_pesorollo.'" title="Nuevo peso de rollo" '.$text_peso.' required/></td></tr>
                </tbody>
                <tfoot align="left">
                  <tr><th colspan="4" align="right"><input type="submit" id="button_salvar" name="button_salvar" value="Salvar" /></th></tr>
                </tfoot>
              </table>                         
              <a href="prodLiberacionR.php" target="cargador">Cambio de rollo</a><br/><br/><br/><br/>
              <a href="pdf/prodRolloBC.php?cdgrollo='.$prodRollo_cdgrollo.'" target="_blank">Generar etiquetas</a>
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
