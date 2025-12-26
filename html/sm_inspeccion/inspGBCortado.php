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

  $sistModulo_cdgmodulo = '75001';
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
        <form id="login" action="inspGBCortado.php" method="POST">';

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

      $inspGBCortado_producto = $regPdtoImpresion->impresion;
      $inspGBCortado_cdgproducto = $regPdtoImpresion->cdgimpresion;

      //Deshacer la operación REVISION de rollos
      if ($_GET['cdgrollo'])
      { if (substr($sistModulo_permiso,0,3) == 'rwx')
        { $prodPaqueteSelect = $link->query("
            SELECT cdgrollo, 
             COUNT(cdgpaquete) AS paquetes
              FROM prodpaquete
             WHERE cdgrollo = '".$_GET['cdgrollo']."' AND
                   sttpaquete != '1'
          GROUP BY cdgrollo");

          if ($prodPaqueteSelect->num_rows > 0)
          { $msg_alert = "Oops!\nAlgunos paquetes de este rollo ya fueron afectados."; }
          else
          { $link->query("
              DELETE FROM prodpaquete
               WHERE cdgrollo = '".$_GET['cdgrollo']."' AND
                     sttpaquete = '1'");

            if ($link->affected_rows > 0)
            { $prodRolloOpeSelect = $link->query("
                SELECT * FROM prodrolloope
                 WHERE cdgrollo = '".$_GET['cdgrollo']."'
                ORDER BY cdgoperacion");

              $inspGBCortado_newstatus = '0';
              if ($prodRolloOpeSelect->num_rows > 0)
              { while ($regProdRolloOpe = $prodRolloOpeSelect->fetch_object())
                { if ($regProdRolloOpe->cdgoperacion == '40001')
                  { $inspGBCortado_newstatus = '1'; }
                  elseif ($regProdRolloOpe->cdgoperacion == '40006')
                  { $inspGBCortado_newstatus = '6'; }
                  elseif ($regProdRolloOpe->cdgoperacion == '40007')
                  { $inspGBCortado_newstatus = '7'; }
                }

                $link->query("
                  UPDATE prodrolloope
                     SET cdgoperacion = CONCAT(cdgoperacion, 'C ".date('Y-m-d H:i:s')." ".$_SESSION['cdgusuario']."')
                   WHERE cdgrollo = '".$_GET['cdgrollo']."' AND
                         cdgoperacion = '50001'");

                $link->query("
                  UPDATE prodrollo
                     SET obs = CONCAT('".date('Y-m-d H:i:s')."\nOperación de corte deshecha por ".$_SESSION['usuario']."\n', obs),
                         sttrollo = '".$inspGBCortado_newstatus."'
                   WHERE cdgrollo = '".$_GET['cdgrollo']."' AND
                         sttrollo = '5'");                
              }              
            }
          }
          
        } else
        { $msg_alert = $msg_nodelete; }
      } //*/

      // Filtrar los registros actuales
      $prodRolloSelect = $link->query("
        SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) AS noop,
               prodrollo.fchmovimiento,
               prodpaquete.cdgrollo
          FROM prodlote,
               prodbobina,
               prodrollo,
               prodpaquete
         WHERE prodlote.cdglote = prodbobina.cdglote AND
               prodbobina.cdgbobina = prodrollo.cdgbobina AND
               prodrollo.cdgrollo = prodpaquete.cdgrollo AND
               prodrollo.cdgproducto = '".$_GET['cdgproducto']."' AND 
               prodpaquete.sttpaquete = '1'
      GROUP BY prodpaquete.cdgrollo
      ORDER BY prodrollo.fchmovimiento");

      if ($prodRolloSelect->num_rows > 0)
      { $item = 0;

        while ($regProdRollo = $prodRolloSelect->fetch_object())
        { $item++;

          $prodRollos_noop[$item] = $regProdRollo->noop;
          $prodRollos_fchmovimiento[$item] = $regProdRollo->fchmovimiento;
          $prodRollos_cdgrollo[$item] = $regProdRollo->cdgrollo; }

        $nRollos = $prodRolloSelect->num_rows; }

      echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Producto <b><a href="inspBuscador.php?cdgproducto='.$inspGBCortado_cdgproducto.'">'.$inspGBCortado_producto.'</a></b></label>
        </article>
        <label class="modulo_listado"><b>'.$nRollos.'</b> Rollos cortados</label>

        <section class="subbloque">';

      if ($nRollos > 0)
      { echo '
          <table class="catalogo" align="center">
            <tr><th>NoOP</th>
              <th>Fecha de registro</th>
              <th>Deshacer</th></tr>
            <tr><td colspan="3"><hr></td></tr>';

        for ($item=1; $item <= $nRollos; $item++)
        { $prodPaqueteSelect = $link->query("
            SELECT cdgrollo, 
             COUNT(cdgpaquete) AS paquetes
              FROM prodpaquete
             WHERE cdgrollo = '".$prodRollos_cdgrollo[$item]."' AND
                   sttpaquete != '1'
          GROUP BY cdgrollo");

          echo '
            <tr><td><b>'.$prodRollos_noop[$item].'</b></td>
              <td><i>'.$prodRollos_fchmovimiento[$item].'</i></td>';

          if ($prodPaqueteSelect->num_rows > 0)
          { echo '
              <td></td>'; }
          else
          { echo '
              <td align="center"><a href="inspGBCortado.php?cdgrollo='.$prodRollos_cdgrollo[$item].'&cdgproducto='.$_GET['cdgproducto'].'">'.$_button_blue_repeat.'</a></td>'; }

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