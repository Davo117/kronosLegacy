<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="/css/2014.css" />   
  </head>
  <body>
    <div id="contenedor">
<?php
  include '../datos/mysql.php';
  $link = conectar();

  m3nu_inspeccion();

  $sistModulo_cdgmodulo = '74001';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    if ($_GET['mode']=='logout') { cl0s3(); }

    if ($_POST['textusername'] AND $_POST['textpassword']) { val1dat3($_POST['textusername'], $_POST['textpassword']); }

    if ($_SESSION['cdgusuario'])
    { ma1n(); }
    else
    { echo '
      <div id="loginform">
        <form id="login" action="inspGBFusionado.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 

      exit; } 

    $pdtoImpresionSelect = $link->query("
      SELECT * FROM pdtoimpresion
       WHERE cdgimpresion = '".$_GET['cdgproducto']."'");

    if ($pdtoImpresionSelect->num_rows > 0)
    { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

      $inspGBFusionado_producto = $regPdtoImpresion->impresion;
      $inspGBFusionado_cdgproducto = $regPdtoImpresion->cdgimpresion;

      //Deshacer la operación REVISION de rollos
      if ($_GET['cdgrollo'])
      { if (substr($sistModulo_permiso,0,3) == 'rwx')
        { $prodPaqueteSelect = $link->query("
            SELECT cdgbobina, 
             COUNT(cdgrollo) AS rollos
              FROM prodrollo
             WHERE cdgbobina = '".$_GET['cdgbobina']."' AND
                   sttrollo != '1'
          GROUP BY cdgbobina");

          if ($prodPaqueteSelect->num_rows > 0)
          { $msg_alert = "Oops!\nAlgunos rollos de esta bobina ya fueron afectados."; }
          else
          { $link->query("
              DELETE FROM prodrollo
               WHERE cdgbobina = '".$_GET['cdgbobina']."' AND
                     sttrollo = '1'");

            if ($link->affected_rows > 0)
            { $prodBobinaOpeSelect = $link->query("
                SELECT * FROM prodbobinaope
                 WHERE cdgbobina = '".$_GET['cdgbobina']."'
                ORDER BY cdgoperacion");

              $inspGBFusionado_newstatus = '0';
              if ($prodBobinaOpeSelect->num_rows > 0)
              { while ($regProdBobinaOpe = $prodBobinaOpeSelect->fetch_object())
                { if ($regProdBobinaOpe->cdgoperacion == '30001')
                  { $inspGBFusionado_newstatus = '1'; }
                  elseif ($regProdBobinaOpe->cdgoperacion == '30006')
                  { $inspGBFusionado_newstatus = '6'; }
                  elseif ($regProdBobinaOpe->cdgoperacion == '30007')
                  { $inspGBFusionado_newstatus = '7'; }
                }

                $link->query("
                  UPDATE prodbobinaope
                     SET cdgoperacion = CONCAT(cdgoperacion, 'C ".date('Y-m-d H:i:s')." ".$_SESSION['cdgusuario']."')
                   WHERE cdgbobina = '".$_GET['cdgbobina']."' AND
                         cdgoperacion = '40001'");

                $link->query("
                  UPDATE prodbobina
                     SET obs = CONCAT('".date('Y-m-d H:i:s')."\nOperación de fusión deshecha por ".$_SESSION['usuario']."\n', obs),
                         sttbobina = '".$inspGBFusionado_newstatus."'
                   WHERE cdgbobina = '".$_GET['cdgbobina']."' AND
                         sttbobina = '9'");                
              }              
            }
          }
          
        } else
        { $msg_alert = $msg_nodelete; }
      } //*/

      // Filtrar los registros actuales
      $prodBobinaSelect = $link->query("
        SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina) AS noop,
               prodbobina.fchmovimiento,
               prodrollo.cdgbobina
          FROM prodlote,
               prodbobina,
               prodrollo
         WHERE prodlote.cdglote = prodbobina.cdglote AND
               prodbobina.cdgbobina = prodrollo.cdgbobina AND
               prodbobina.cdgproducto = '".$_GET['cdgproducto']."' AND 
               prodrollo.sttrollo = '1'
      GROUP BY prodrollo.cdgbobina
      ORDER BY prodbobina.fchmovimiento");

      if ($prodBobinaSelect->num_rows > 0)
      { $item = 0;

        while ($regProdRollo = $prodBobinaSelect->fetch_object())
        { $item++;

          $prodBobinas_noop[$item] = $regProdRollo->noop;
          $prodBobinas_fchmovimiento[$item] = $regProdRollo->fchmovimiento;
          $prodBobinas_cdgbobina[$item] = $regProdRollo->cdgbobina; }

        $nBobinas = $prodBobinaSelect->num_rows; }

      echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Producto <b><a href="inspBuscador.php?cdgproducto='.$inspGBFusionado_cdgproducto.'">'.$inspGBFusionado_producto.'</a></b></label>
        </article>
        <label class="modulo_listado"><b>'.$nBobinas.'</b> Bobinas fusionadas</label>

        <section class="subbloque">';

      if ($nBobinas > 0)
      { echo '
          <table class="catalogo" align="center">
            <tr><th>NoOP</th>
              <th>Fecha de registro</th>
              <th>Deshacer</th></tr>
            <tr><td colspan="3"><hr></td></tr>';

        for ($item=1; $item <= $nBobinas; $item++)
        { $prodRolloSelect = $link->query("
            SELECT cdgbobina, 
             COUNT(cdgrollo) AS rollos
              FROM prodrollo
             WHERE cdgbobina = '".$prodBobinas_cdgbobina[$item]."' AND
                   sttpaquete != '1'
          GROUP BY cdgbobina");

          echo '
            <tr><td><b>'.$prodBobinas_noop[$item].'</b></td>
              <td><i>'.$prodBobinas_fchmovimiento[$item].'</i></td>';

          if ($prodRolloSelect->num_rows > 0)
          { echo '
              <td></td>'; }
          else
          { echo '
              <td align="center"><a href="inspGBFusionado.php?cdgbobina='.$prodBobinas_cdgbobina[$item].'&cdgproducto='.$_GET['cdgproducto'].'">'.$_button_blue_repeat.'</a></td>'; }

          echo '</tr>';
        }

        echo '
          </table>';

      } else
      { echo '
          <article>
            <label><b><i>No fue posible localizar producto en este punto.</i></b></label>
          </article>'; }

      echo '
        </section>
      </div>';

      if ($msg_alert != '')
      { echo '
      <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
    
    } else
    { echo '
      <div><h1>No fue posible encontrar el producto seleccionado.</h1></div>'; }

  } else
  { echo '
      <div><h1>Módulo no encontrado o bloqueado.</h1></div>'; }
?>
    </div>
  </body>
</html>