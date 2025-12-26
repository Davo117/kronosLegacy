<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Liberación de rollos</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../css/global.css">
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
                 pdtodiseno.iddiseno,
                 pdtodiseno.diseno,
                 pdtodiseno.alto,
                 pdtodiseno.rollo,
                 pdtodiseno.cdgdiseno,
                 pdtoimpresion.idimpresion,
                 pdtoimpresion.impresion,
                 pdtoimpresion.cdgimpresion,
                 prodrollo.longitud,
                 prodrollo.amplitud,
                 prodrollo.peso,
                 prodrollo.bandera,
                 prodlote.cdglote,
                 prodbobina.cdgbobina,
                 prodrollo.cdgrollo
          FROM proglote,
               prodlote,
               prodbobina,
               prodrollo,
               pdtodiseno,
               pdtoimpresion
          WHERE (proglote.cdglote = prodlote.cdglote AND prodlote.cdglote = prodbobina.cdglote AND prodbobina.cdgbobina = prodrollo.cdgbobina) AND
                (prodrollo.cdgrollo = '".$prodRollo_cdgrollo."' OR CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) = '".$prodRollo_cdgrollo."') AND
                (prodlote.cdgproducto = pdtoimpresion.cdgimpresion AND pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno) AND
                 prodrollo.sttrollo = '6'");

        if ($prodRolloSelect->num_rows > 0)
        { $regProdRollo = $prodRolloSelect->fetch_object();

          $prodRollo_noop = $regProdRollo->noop;
          $prodRollo_minmlles = ($regProdRollo->rollo*0.9);
          $prodRollo_alto = $regProdRollo->alto;
          $prodRollo_maxmlles = ($regProdRollo->rollo*1.2);
          $prodRollo_impresion = $regProdRollo->impresion;
          $prodRollo_cdgimpresion = $regProdRollo->cdgimpresion;          
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

            if (($prodRollo_longitudrollo/$prodRollo_alto) < $prodRollo_minmlles) 
            { $answer = 'La longitud ingresada es menor a la Mínima (<b>'.($prodRollo_minmlles*$prodRollo_alto).'</b>) especificada para este producto.';
              $text_longitud = 'autofocus';
            } else
            { if (($prodRollo_longitudrollo/$prodRollo_alto) > $prodRollo_maxmlles) 
              { $answer = 'La longitud ingresada es mayor a la Máxima (<b>'.($prodRollo_maxmlles*$prodRollo_alto).'</b>) especificada para este producto.';
                $text_longitud = 'autofocus';
              } else
              { $text_amplitud = 'autofocus';
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
                          (cdgrollo, cdgoperacion, cdgempleado, cdgmaquina, longitud, peso, numbandera, fchoperacion, fchmovimiento)
                        VALUES
                          ('".$prodRollo_cdgrollo."', '40007', '".$_SESSION['cdgusuario']."', '00001', '".$prodRollo_longitudrollo."', '".$prodRollo_pesorollo."', '".$prodRollo_banderarollo."', '".$fchoperacion."', NOW())");

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
                      { $answer .= 'Rollo actualizado, '.$prodRollo_noop.' ('.$prodRollo_cdgrollo.') disponible como producto terminado.'; }

                      $prodRollo_cdgrollo = '';
                      
                      $txt_rollo = 'autofocus'; }                 
                  } else
                  { $answer = 'Favor de ingresar la información de NUEVO PESO.';
                    $text_peso = 'autofocus'; } 
                } else
                { $answer = 'Favor de ingresar la información de NUEVA AMPLITUD.';
                  $text_amplitud = 'autofocus'; } 
              }
            } 
          } else
          { $answer = 'Favor de ingresar la información de NUEVA LONGITUD.';
            $text_longitud = 'autofocus'; } 
        } 

        $text_rollo = 'autofocus'; 
      } else 
      { $answer = 'Favor de ingresar la información de Rollo.';
        $prodRollo_cdgrollo = '';
        $text_rollo = 'autofocus'; }
    } 

    echo ('
    <form id="form_prodLiberacion" name="form_prodLiberacion" method="post" action="prodLiberacionR.php"/>
      <input type="hidden" id="text_rollo" name="text_rollo" value="'.$prodRollo_cdgrollo.'" required/>
      <table align="center">
        <thead>
          <tr><th colspan="2">'.$sistModulo_modulo.'</th></tr>
        </thead>
        <tbody>
          <tr><td colspan="2"><label for="ttl_empleado"><strong>ID </strong>'.$_SESSION[idusuario].'</label><br/>
              <label for="info_empleado"><strong>Nombre </strong>'.$_SESSION[usuario].'</label></td></tr>');

    if ($prodRollo_cdgrollo == '')
    { echo ('
          <tr><td colspan="2"><label for="label_ttllote">Rollo</label><br/>
              <input type="text" style="width:120px" id="text_rollo" maxlength="12" name="text_rollo" value="'.$prodRollo_cdgrollo.'" '.$text_rollo.' required/></td></tr>
        </tbody>
        <tfoot>
          <tr><th colspan="2" align="right"><input type="submit" id="button_buscar" name="button_buscar" value="Buscar" /></th></tr>
        </tfoot>
      </table>
    </form>'); }
    else
    { echo utf8_decode('
          <tr><td>
              <table align="center">
                <thead>
                  <tr><th colspan="3">Información</th></tr>
                </thead>
                <tbody>
                  <tr><td colspan="3">Impresión: '.$prodRollo_impresion.'</td></tr>
                  <tr><td colspan="3" align="right"><h1><a href="prodLiberacionR.php" target="cargador">NoOP</a> '.$prodRollo_noop.'</h1></td></tr>
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
            </td>
          </tr>                     
        </tbody>
        <tfoot>          
        </tfoot>
      </table>
    </form>'); }

    echo ('
    <br/>
    <div align="center"><strong>'.utf8_decode($answer).'</strong></div>');
  } else
  { echo ('
    <br/>
    <div align="center"><h1>Módulo no encontrado o bloqueado.</h1></div>'); }
  ?>

  </body>
</html>
