<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '60037';

  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);   

    if ($_SESSION['cdgusuario'] != '')
    { $prodBobina_cdgbobina = trim($_POST['text_bobina']);
      if (trim($_POST['text_bobina']) != '')
      { $link_mysqli = conectar();
        $prodBobinaSelect = $link_mysqli->query("
          SELECT proglote.lote,
                 proglote.tarima,
                 proglote.idlote,
                 prodlote.cdglote,
                 prodlote.noop,
                 prodbobina.bobina,
                 pdtodiseno.diseno,
                 pdtoimpresion.impresion,
                 prodbobina.longitud,
                 prodbobina.amplitud,
                 prodbobina.peso,
                 prodbobina.cdgbobina
            FROM proglote,
                 prodlote, 
                 prodbobina,
                 pdtoimpresion,
                 pdtodiseno
          WHERE (proglote.cdglote = prodlote.cdglote AND 
                 prodlote.cdglote = prodbobina.cdglote) AND 
               ((prodbobina.cdgbobina = '".$prodBobina_cdgbobina."') OR (CONCAT(prodlote.noop,'-',prodbobina.bobina) = '".$prodBobina_cdgbobina."')) AND 
                (prodlote.cdgproducto = pdtoimpresion.cdgimpresion AND 
                 pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno) AND 
                 prodbobina.sttbobina = '1'"); 

        if ($prodBobinaSelect->num_rows > 0)
        { $regProdBobina = $prodBobinaSelect->fetch_object();

          $prodBobina_lote = $regProdBobina->lote;
          $prodBobina_tarima = $regProdBobina->tarima;
          $prodBobina_idlote = $regProdBobina->idlote;
          $prodBobina_noop = $regProdBobina->noop;
          $prodBobina_proyecto = $regProdBobina->proyecto;
          $prodBobina_impresion = $regProdBobina->impresion;
          $prodBobina_mezcla = $regProdBobina->mezcla;
          $prodBobina_idmezcla = $regProdBobina->idmezcla;
          $prodBobina_bobina = $regProdBobina->bobina;
          $prodBobina_infolongitud = $regProdBobina->longitud;
          $prodBobina_longitud = $regProdBobina->longitud;
          $prodBobina_infoamplitud = $regProdBobina->amplitud;
          $prodBobina_amplitud = $regProdBobina->amplitud;
          $prodBobina_infopeso = $regProdBobina->peso;
          $prodBobina_peso = $regProdBobina->peso;
          $prodBobina_cdgbobina = $regProdBobina->cdgbobina;

          $text_amplitud = 'autofocus';

          if ($_POST['button_salvar'])
          { $text_longitud = 'autofocus';

            if (is_numeric($_POST['text_longitud']))
            { $prodBobina_longitud = $_POST['text_longitud'];

              $text_amplitud = 'autofocus';

              if (is_numeric($_POST['text_amplitud']))
              { $prodBobina_amplitud = $_POST['text_amplitud'];

                $text_peso = 'autofocus';

                if (is_numeric($_POST['text_peso']))
                { $prodBobina_peso = $_POST['text_peso']; 
                
                  $fchoperacion = date('Y-m-d');                

                  $link_mysqli = conectar();
                  $link_mysqli->query("
                    INSERT INTO prodbobinaope
                      (cdgbobina, cdgoperacion, cdgempleado, cdgmaquina, longitud, peso, fchoperacion, fchmovimiento)
                    VALUES
                      ('".$prodBobina_cdgbobina."', '30007', '".$_SESSION['cdgusuario']."', '00001', '".$prodBobina_infolongitud."', '".$prodBobina_infopeso."', '".$fchoperacion."', NOW())");

                  if ($link_mysqli->affected_rows > 0) 
                  { $msg_alert .= 'Operacion de liberacion en Bobina, '.$prodBobina_noop.'-'.$prodBobina_bobina.', realizada. \n';

                    $link_mysqli = conectar();
                    $link_mysqli->query("
                      UPDATE prodbobina
                         SET sttbobina = '7',
                             longitud = '".$prodBobina_longitud."',
                             amplitud = '".$prodBobina_amplitud."',
                             peso = '".$prodBobina_peso."',
                             fchmovimiento = NOW()                          
                       WHERE cdgbobina = '".$prodBobina_cdgbobina."'");

                    if ($link_mysqli->affected_rows > 0) 
                    { $msg_alert .= 'Bobina actualizada, disponible como producto terminado.'; }
                  }

                  $prodBobina_cdglote = '';
                  $prodBobina_noop = '';
                  $prodBobina_bobina = '';
                  $prodBobina_infolongitud = '';
                  $prodBobina_longitud = '';
                  $prodBobina_infoamplitud = '';
                  $prodBobina_amplitud = '';
                  $prodBobina_infopeso = '';                 
                  $prodBobina_peso = '';
                  $prodBobina_cdgbobina = '';  
                  
                  $txt_lote = 'autofocus';
                  $txt_longitud = ''; 
                  $txt_amplitud = ''; 
                  $txt_peso = '';
                }
                else
                { $msg_alert = 'Informacion de NUEVO peso, incorrecta.';
                  $text_peso = 'autofocus'; }                   
              }
              else
              { $msg_alert = 'Informacion de NUEVA amplitud, incorrecta.';
                $text_amplitud = 'autofocus'; } 
            }
            else
            { $msg_alert = 'Informacion de NUEVA longitud, incorrecta.';
              $text_longitud = 'autofocus'; } 
          } 

          $text_bobina = 'autofocus'; } 
        else 
        { $msg_alert = 'Informacion de Bobina, incorrecta.';
          $prodBobina_cdgbobina = '';
          $text_bobina = 'autofocus'; }

        $prodBobinaSelect->close; 
      }
    }

    echo '
    <form id="form_prodLiberacion" name="form_prodLiberacion" method="post" action="prodLiberacionB.php"/>
      <table align="center">
        <thead>
          <tr>
            <th colspan="3">'.$sistModulo_modulo.'</th>
          </tr>
        </thead>
        <tbody>
          <tr><td colspan="3"><label for="ttl_empleado"><strong>ID </strong>'.$_SESSION['idusuario'].'</label><br/>              
              <label for="info_empleado"><strong>Nombre </strong>'.$_SESSION['usuario'].'</label></td></tr>';

    if ($prodBobina_cdgbobina == '')
    { echo '
          <tr><td colspan="3"><label for="label_ttllote">Bobina '.$prodBobina_noop.'-'.$prodBobina_bobina.'</label><br/>
              <input type="text" style="width:120px" id="text_bobina" maxlength="12" name="text_bobina" value="'.$prodBobina_cdgbobina.'" '.$text_bobina.' required /></td></tr>              
        </tbody>
        <tfoot>
          <tr><th colspan="3" align="right"><input type="submit" id="button_buscar" name="button_buscar" value="Buscar" /></th></tr>
        </tfoot>
      </table>
    </form>'; }
    else
    { echo '
          <tr><td colspan="3">
              <table align="center">
                <thead>
                  <tr><th colspan="3">Informaci&oacute;n</th></tr>
                </thead>
                <tbody>
                  <tr><td>No. Lote</td>
                    <td colspan="2">'.$prodBobina_lote.'</td></tr>
                  <tr><td>Tarima/Lote</td>
                    <td colspan="2">'.$prodBobina_tarima.'/'.$prodBobina_idlote.'</td></tr>
                  <tr><td>Longitud</td>
                    <td align="right">'.number_format($prodBobina_infolongitud,3).'</td>
                    <td>Metros</td></tr> 
                  <tr><td>Ancho plano</td>
                    <td align="right">'.number_format($prodBobina_infoamplitud).'</td>
                    <td>Milimetros</td></tr>
                  <tr><td>Peso</td>
                    <td align="right">'.number_format($prodBobina_infopeso,3).'</td>
                    <td>Kilogramos</td></tr>
                </tbody>
                <tfoot align="left">
                  <tr><th>Proyecto</th>
                    <th colspan="2">'.$prodBobina_proyecto.'</th></tr>
                  <tr><th>Impresi&oacute;n</th>
                    <th colspan="2">'.$prodBobina_impresion.'</th></tr>
                  <tr><th>Mezcla</th>
                    <th colspan="2">'.$prodBobina_idmezcla.'<br/>'.$prodBobina_mezcla.'</th></tr>
                  <tr><th><h1>NoOP</h1></th>
                    <th colspan="2" align="right"><h1>'.$prodBobina_noop.'-'.$prodBobina_bobina.'</h1></th></tr>
                </tfoot>
              </table>
              <input type="hidden" id="text_bobina" name="text_bobina" value="'.$prodBobina_cdgbobina.'" '.$text_bobina.' required/>
            </td>
          </tr>
          <tr><td>Longitud final<br/>
              <input type="text" id="text_longitud" name="text_longitud" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodBobina_longitud.'" title="Nueva longitud de lote/bobina" '.$text_longitud.' required/></td>
            <td>Amplitud final<br/>
              <input type="text" id="text_amplitud" name="text_amplitud" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodBobina_amplitud.'" title="Nueva longitud de lote/bobina" '.$text_amplitud.' required/></td>              
            <td><label for="ttl_kilogramos">Kilogramos</label><br/>
              <input type="text" id="text_peso" name="text_peso" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodBobina_peso.'" title="Nuevo peso de lote/bobina" '.$text_peso.' required/></tr>
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
