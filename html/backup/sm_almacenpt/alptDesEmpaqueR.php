<!DOCTYPE html>
<html>
  <head>
    <title>Actualización de empaques</title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '81015';

  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    $alptEmpaque_cdgempaque = trim($_POST['text_empaque']);

      //Buscar empaque
    if ($_POST['button_buscar'])
    { $link_mysqli = conectar();
      $alptEmpaqueSelect = $link_mysqli->query("
        SELECT * FROM alptempaque 
        WHERE cdgempaque = '".$alptEmpaque_cdgempaque."'");

      if ($alptEmpaqueSelect->num_rows > 0)
      { $regAlptEmpaque = $alptEmpaqueSelect->fetch_object();

        $alptEmpaque_noempaque = $regAlptEmpaque->noempaque;
        $alptEmpaque_cdgproducto = $regAlptEmpaque->cdgproducto;

        $alptEmpaqueSelect->close;

        $link_mysqli = conectar();
        $pdtoImpresionSelect = $link_mysqli->query("
          SELECT * FROM pdtoimpresion
          WHERE cdgimpresion = '".$alptEmpaque_cdgproducto."'");

        if ($pdtoImpresionSelect->num_rows > 0)
        { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

          $alptEmpaque_impresion = $regPdtoImpresion->impresion;
          $alptEmpaque_corte = $regPdtoImpresion->corte;

          $pdtoImpresionSelect->close;

          $link_mysqli = conectar();
          $alptEmpaqueRSelect = $link_mysqli->query("
            SELECT alptempaquer.nocontrol,
              CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) AS noop,
              prodrollo.longitud,
              prodrollo.amplitud,
              prodrollo.peso,
              prodrollo.cdgrollo
            FROM alptempaquer, prodlote, prodbobina, prodrollo
            WHERE prodlote.cdglote = prodbobina.cdglote AND
              prodbobina.cdgbobina = prodrollo.cdgbobina AND 
              prodrollo.cdgrollo = alptempaquer.cdgrollo AND 
              alptempaquer.cdgempaque = '".$alptEmpaque_cdgempaque."'");

          if ($alptEmpaqueRSelect->num_rows > 0)
          { $id_rollo = 1;
            while ($regAlptEmpaqueR = $alptEmpaqueRSelect->fetch_object()) 
            { $alptEmpaque_nocontrol[$id_rollo] = $regAlptEmpaqueR->nocontrol; 
              $alptEmpaque_noop[$id_rollo] = $regAlptEmpaqueR->noop;
              $alptEmpaque_longitud[$id_rollo] = $regAlptEmpaqueR->longitud;
              $alptEmpaque_amplitud[$id_rollo] = $regAlptEmpaqueR->amplitud;
              $alptEmpaque_peso[$id_rollo] = $regAlptEmpaqueR->peso;
              $alptEmpaque_cdgrollo[$id_rollo] = $regAlptEmpaqueR->cdgrollo;

              $alptEmpaque_millares += number_format(($regAlptEmpaqueR->longitud/$alptEmpaque_corte),3);
              $alptEmpaque_pesos += $regAlptEmpaqueR->peso;

              $id_rollo++; }

              $num_rollos = $alptEmpaqueRSelect->num_rows;
              $alptEmpaqueRSelect->close;

          } else
          { $msg_info = "El contenido del empaque no pudo ser encontrado. <br/>
                       (ERROR grave, perdida de informaci&oacute;n)"; }
        } else
        { $msg_info = "El producto contenido en el empaque no pudo ser encontrado. <br/>
                       (ERROR grave, perdida de informaci&oacute;n)"; }
      } else
      { $msg_info = "El c&oacute;digo de paquete no fue encontrado."; }
    }
    
    if ($_POST['button_salvar'])
    { $fchoperacion = date('Y-m-d');

      $link_mysqli = conectar();
      $alptEmpaqueSelect = $link_mysqli->query("
        SELECT * FROM alptempaque
        WHERE cdgempaque = '".$alptEmpaque_cdgempaque."'");

      if ($alptEmpaqueSelect->num_rows > 0)
      { $regAlptDesEmpaqueR = $alptEmpaqueSelect->fetch_object();

        $alptDesEmpaqueR_cdgproducto = $regAlptDesEmpaqueR->cdgproducto; 

        $link_mysqli = conectar();
        $alptDesEmpaqueSelect = $link_mysqli->query("
          SELECT * FROM alptempaque
          WHERE cdgproducto = '".$alptDesEmpaqueR_cdgproducto."' AND
            tpoempaque = 'Q'
          ORDER BY noempaque DESC");

        if ($alptDesEmpaqueSelect->num_rows > 0)
        { $regAlptDesEmpaqueR = $alptDesEmpaqueSelect->fetch_object();

          $alptDesEmpaqueR_cdgempaque = $regAlptDesEmpaqueR->cdgempaque;

          if ($alptDesEmpaqueR_cdgempaque == $alptEmpaque_cdgempaque)
          { $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE alptempaque
              SET cdgempaque = CONCAT('X',cdgempaque), tpoempaque = 'XQ'
              WHERE cdgproducto = '".$alptDesEmpaqueR_cdgproducto."' AND 
                tpoempaque = 'Q' AND sttempaque = '1' AND
                cdgempaque = '".$alptEmpaque_cdgempaque."'");

            if ($link_mysqli->affected_rows > 0) 
            { $msg_alert = "Número de empaque desactivado";

              $link_mysqli = conectar();
              $link_mysqli->query("
                UPDATE alptempaquer
                SET cdgempaque = CONCAT('X',cdgempaque), cdgrollo = CONCAT('X',cdgrollo)
                WHERE cdgproducto = '".$alptDesEmpaqueR_cdgproducto."' AND 
                  cdgempaque = '".$alptEmpaque_cdgempaque."'");

              if ($link_mysqli->affected_rows > 0) 
              { $msg_alert .= "Contenido del empaque desactivado";

                $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE prodrollo
                  SET obs = CONCAT('Rollo desempacado por ".$_SESSION['idusuario']." el ".$fchoperacion." Empaque:',cdgempaque),
                    cdgempaque = '', 
                    sttrollo = '7'
                  WHERE cdgproducto = '".$alptDesEmpaqueR_cdgproducto."' AND 
                    cdgempaque = '".$alptEmpaque_cdgempaque."'");

                if ($link_mysqli->affected_rows > 0) 
                { $msg_alert .= "Rollos reactivados"; }
                else
                { $msg_alert .= "ERROR: Rollos NO reactivados"; }
              }
              else
              { $msg_alert .= "ERROR: Contenido del empaque NO desactivado"; }
            }
            else
            { $msg_alert = "ERROR: Número de empaque NO desactivado"; }
          }
          else
          { $msg_alert = "Este código no corresponde al ultimo empaque realizado (Queso)."; }
        }
        else
        { $msg_alert = "Favor de intentar nuevamente."; }
      } else
      { $msg_alert = "Favor de intentar nuevamente."; }
    }

    echo '
    <form id="form_empaque" name="form_empaque" method="POST" action="alptDesEmpaqueR.php"/>
      <table align="center">
        <thead>
          <tr><th colspan="2">'.$sistModulo_modulo.'</th></tr>
        </thead>';

    if ($alptEmpaque_noempaque != '')
    { echo '
        <tbody>
          <input type="hidden" id="text_empaque" name="text_empaque" value="'.$alptEmpaque_cdgempaque.'" />
          <tr><th rowspan="3">Empaque<br/>
              <h1>'.$alptEmpaque_noempaque.'</h1></th>
              <td><label for="lbl_ttlempleado">Usuario</label><br/>
              <label for="lbl_empleado">[<strong>'.$_SESSION['idusuario'].'</strong>] <strong>'.$_SESSION['usuario'].'</strong></label></td></tr>
          <tr><td><label for="lbl_ttlimpresion">Producto</label><br/>
              <label for="lbl_producto"><strong>'.$alptEmpaque_impresion.'</strong></label></td></tr>
          <tr><td colspan="2">
              <table align="center">
                <thead>
                  <tr><td colspan="6" align="center"><strong>Contenido del empaque</strong></td></tr>
                  <tr><th>No Control</th>
                    <th>NoOp</th>
                    <th>Longitud</th>
                    <th>Ancho Plano</th>
                    <th>Cantidad</th>
                    <th>Peso</th></tr>
                </thead>
                <tbody>';
              
              for ($id_rollo = 1; $id_rollo <= $num_rollos; $id_rollo++)
              { echo '
                  <tr align="right"><td align="center"><strong>'.$alptEmpaque_nocontrol[$id_rollo].'</strong></td>
                    <td>'.$alptEmpaque_noop[$id_rollo].'</td>
                    <td>'.$alptEmpaque_longitud[$id_rollo].' <strong>mts</strong></td>
                    <td>'.$alptEmpaque_amplitud[$id_rollo].' <strong>mm</strong></td>
                    <td>'.number_format(($alptEmpaque_longitud[$id_rollo]/$alptEmpaque_corte),3).' <strong>mlls</strong></td>
                    <td>'.number_format($alptEmpaque_peso[$id_rollo],3).'  <strong>kgs</strong></td></tr>'; }
                
              echo '
                  <tr align="right"><td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th><strong>'.number_format($alptEmpaque_millares,3).'</strong> mlls</th>
                    <th><strong>'.number_format($alptEmpaque_pesos,3).'</strong> kgs</th></tr>
                </tbody>
                <tfoot>
                </tfoot>
              </table>
            </td></tr>
        </tbody>
        <tfoot>
          <tr><th align="right" colspan="2"><input type="submit" id="button_salvar" name="button_salvar" value="Deshacer" autofocus /></th></tr>
        </tfoot>
      </table>
    </form>'; 
    } else
    { echo '
        <tbody>
          <tr><td><label for="lbl_ttlempaque">Empaque</label><br/>
              <input type="text" style="width:120px" id="text_empaque" name="text_empaque" value="'.$alptEmpaque_cdgempaque.'" autofocus required/></td></tr>
        </tbody>
        <tfoot>
          <tr><th align="right"><input type="submit" id="button_buscar" name="button_buscar" value="Buscar" /></th></tr>
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