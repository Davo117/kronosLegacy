<!DOCTYPE html>
<html>
  <head>
    <title>Producci&oacute;n Impresi&oacute;n</title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '60040';

  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);   

    if ($_SESSION['cdgusuario'] != '')
    { $prodSubLote_cdgsublote = trim($_POST['text_sublote']);
      if (trim($_POST['text_sublote']) != '')
      { $link_mysqli = conectar();
        $prodSubLoteSelect = $link_mysqli->query("
          SELECT proglote.lote,
            proglote.tarima,
            proglote.idlote,
            prodlote.cdglote,
            prodlote.noop,
            pdtoproyecto.proyecto,
            pdtoimpresion.impresion,
            pdtomezcla.mezcla,
            pdtomezcla.idmezcla,
            prodsublote.sublote,
            prodsublote.longitud,
            prodsublote.amplitud,
            prodsublote.peso,
            prodsublote.cdgsublote
          FROM proglote,
            prodlote, 
            prodsublote,
            pdtomezcla,
            pdtoimpresion,
            pdtoproyecto
          WHERE (proglote.cdglote = prodlote.cdglote
            AND prodlote.cdglote = prodsublote.cdglote)
          AND ((prodsublote.cdgsublote = '".$prodSubLote_cdgsublote."')
            OR (CONCAT(prodlote.noop,'-',prodsublote.sublote) = '".$prodSubLote_cdgsublote."'))
          AND (prodlote.cdgmezcla = pdtomezcla.cdgmezcla
            AND pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion
            AND pdtoimpresion.cdgproyecto = pdtoproyecto.cdgproyecto)
          AND prodsublote.sttsublote = '1'"); 

        if ($prodSubLoteSelect->num_rows > 0)
        { $regProdSubLote = $prodSubLoteSelect->fetch_object();

          $prodSubLote_lote = $regProdSubLote->lote;
          $prodSubLote_tarima = $regProdSubLote->tarima;
          $prodSubLote_idlote = $regProdSubLote->idlote;
          $prodSubLote_noop = $regProdSubLote->noop;
          $prodSubLote_proyecto = $regProdSubLote->proyecto;
          $prodSubLote_impresion = $regProdSubLote->impresion;
          $prodSubLote_mezcla = $regProdSubLote->mezcla;
          $prodSubLote_idmezcla = $regProdSubLote->idmezcla;
          $prodSubLote_sublote = $regProdSubLote->sublote;
          $prodSubLote_infolongitud = $regProdSubLote->longitud;
          $prodSubLote_longitud = $regProdSubLote->longitud;
          $prodSubLote_infoamplitud = $regProdSubLote->amplitud;
          $prodSubLote_amplitud = $regProdSubLote->amplitud;
          $prodSubLote_infopeso = $regProdSubLote->peso;
          $prodSubLote_peso = $regProdSubLote->peso;
          $prodSubLote_cdgsublote = $regProdSubLote->cdgsublote;

          if ($_POST['button_salvar'])
          { $text_longitud = 'autofocus';

            if (is_numeric($_POST['text_longitud']))
            { $prodSubLote_longitud = $_POST['text_longitud'];

              $text_amplitud = 'autofocus';

              if (is_numeric($_POST['text_amplitud']))
              { $prodSubLote_amplitud = $_POST['text_amplitud'];

                $text_peso = 'autofocus';

                if (is_numeric($_POST['text_peso']))
                { $prodSubLote_peso = $_POST['text_peso']; 
                
                  $fchoperacion = date('Y-m-d');                

                  $link_mysqli = conectar();
                  $link_mysqli->query("
                    INSERT INTO prodsubloteope
                      (cdgsublote, cdgoperacion, cdgempleado, cdgmaquina, fchoperacion, fchmovimiento)
                    VALUES
                      ('".$prodSubLote_cdgsublote."', '40001', '".$_SESSION['cdgusuario']."', '0000', '".$fchoperacion."', NOW())");

                  if ($link_mysqli->affected_rows > 0) 
                  { $msg_alert .= 'Operacion de liberacion en Bobina, '.$prodSubLote_noop.'-'.$prodSubLote_sublote.', realizada. \n';

                    $link_mysqli = conectar();
                    $link_mysqli->query("
                      INSERT INTO prodprocsublote
                        (cdgsublote, cdgproceso, longitud, amplitud, peso, fchmovimiento)
                      VALUES
                        ('".$prodSubLote_cdgsublote."', '40', '".$prodSubLote_infolongitud."', '".$prodSubLote_infoamplitud."', '".$prodSubLote_infopeso."', NOW())");                 

                    if ($link_mysqli->affected_rows > 0) 
                    { $msg_alert .= 'Proceso de liberacion en Bobina, '.$prodSubLote_noop.'-'.$prodSubLote_sublote.', registrado. \n'; 

                      $link_mysqli = conectar();
                      $link_mysqli->query("
                        UPDATE prodsublote
                        SET sttsublote = '7',
                          longitud = '".$prodSubLote_longitud."',
                          amplitud = '".$prodSubLote_amplitud."',
                          peso = '".$prodSubLote_peso."',
                          fchmovimiento = NOW()                          
                        WHERE cdgsublote = '".$prodSubLote_cdgsublote."'");

                      if ($link_mysqli->affected_rows > 0) 
                      { $msg_alert .= 'Bobina actualizada, disponible como producto terminado.'; }
                    }
                  }

                  $prodSubLote_cdglote = '';
                  $prodSubLote_noop = '';
                  $prodSubLote_sublote = '';
                  $prodSubLote_infolongitud = '';
                  $prodSubLote_longitud = '';
                  $prodSubLote_infoamplitud = '';
                  $prodSubLote_amplitud = '';
                  $prodSubLote_infopeso = '';                 
                  $prodSubLote_peso = '';
                  $prodSubLote_cdgsublote = '';  
                  
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

          $text_sublote = 'autofocus'; } 
        else 
        { $msg_alert = 'Informacion de Bobina, incorrecta.';

          $text_sublote = 'autofocus'; }

        $prodSubLoteSelect->close; 
      }
    }

    echo '
    <form id="form_prodLiberacionAPT" name="form_prodLiberacionAPT" method="post" action="prodLiberacionAPT.php"/>
      <table align="center">
        <thead>
          <tr>
            <th colspan="3">'.$sistModulo_modulo.'</th>
          </tr>
        </thead>
        <tbody>
          <tr><td colspan="3"><label for="ttl_empleado"><strong>ID </strong>'.$_SESSION['idusuario'].'</label><br/>              
              <label for="info_empleado"><strong>Nombre </strong>'.$_SESSION['usuario'].'</label></td></tr>';

    if ($prodSubLote_cdgsublote == '')
    { echo '
          <tr><td colspan="3"><label for="label_ttllote">SubLote '.$prodSubLote_noop.'-'.$prodSubLote_sublote.'</label><br/>
              <input type="text" style="width:120px" id="text_sublote" maxlength="12" name="text_sublote" value="0'.$prodSubLote_cdgsublote.'" '.$text_sublote.' required/></td></tr>              
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
                    <td colspan="2">'.$prodSubLote_lote.'</td></tr>
                  <tr><td>Tarima/Lote</td>
                    <td colspan="2">'.$prodSubLote_tarima.'/'.$prodSubLote_idlote.'</td></tr>
                  <tr><td>Longitud</td>
                    <td align="right">'.number_format($prodSubLote_infolongitud,3).'</td>
                    <td>Metros</td></tr> 
                  <tr><td>Ancho plano</td>
                    <td align="right">'.number_format($prodSubLote_infoamplitud).'</td>
                    <td>Milimetros</td></tr>
                  <tr><td>Peso</td>
                    <td align="right">'.number_format($prodSubLote_infopeso,3).'</td>
                    <td>Kilogramos</td></tr>
                </tbody>
                <tfoot align="left">
                  <tr><th>Proyecto</th>
                    <th colspan="2">'.$prodSubLote_proyecto.'</th></tr>
                  <tr><th>Impresi&oacute;n</th>
                    <th colspan="2">'.$prodSubLote_impresion.'</th></tr>
                  <tr><th>Mezcla</th>
                    <th colspan="2">'.$prodSubLote_idmezcla.'<br/>'.$prodSubLote_mezcla.'</th></tr>
                  <tr><th><h1>NoOP</h1></th>
                    <th colspan="2" align="right"><h1>'.$prodSubLote_noop.'-'.$prodSubLote_sublote.'</h1></th></tr>
                </tfoot>
              </table>
              <input type="hidden" id="text_sublote" name="text_sublote" value="'.$prodSubLote_cdgsublote.'" '.$text_sublote.' required/>
            </td>
          </tr>
          <tr><td>Longitud final<br/>
              <input type="text" id="text_longitud" name="text_longitud" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodSubLote_longitud.'" title="Nueva longitud de lote/bobina" '.$text_longitud.' required/></td>
            <td>Amplitud final<br/>
              <input type="text" id="text_amplitud" name="text_amplitud" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodSubLote_amplitud.'" title="Nueva longitud de lote/bobina" '.$text_longitud.' required/></td>              
            <td><label for="ttl_kilogramos">Kilogramos</label><br/>
              <input type="text" id="text_peso" name="text_peso" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodSubLote_peso.'" title="Nuevo peso de lote/bobina" '.$text_peso.' required/></tr>
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
