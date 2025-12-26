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

  $sistModulo_cdgmodulo = '73007';
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
        <form id="login" action="pdtoImpresion.php" method="POST">';

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

      $inspGBLBobina_producto = $regPdtoImpresion->impresion;
      $inspGBLBobina_cdgproducto = $regPdtoImpresion->cdgimpresion;

      //Deshacer la operación LIBERACION de rollos
      if ($_GET['cdgbobina'])
      { if (substr($sistModulo_permiso,0,3) == 'rwx')
        { $link->query("
            UPDATE prodbobina
               SET obs = CONCAT('".date('Y-m-d H:i:s')."\nOperación de liberación deshecha por ".$_SESSION['usuario']."\n', obs),
                   sttbobina = '1'
             WHERE cdgbobina = '".$_GET['cdgbobina']."' AND
                   sttbobina = '6'");
          if ($link->affected_rows > 0)
          { $link->query("
              UPDATE prodbobinaope
                 SET cdgoperacion = CONCAT(cdgoperacion, 'C ".date('Y-m-d H:i:s')." ".$_SESSION['cdgusuario']."')
               WHERE cdgbobina = '".$_GET['cdgbobina']."' AND
                     cdgoperacion = '30007'"); 
          } else
          { $msg_alert = "Oops! Esta bobina ya no se encuentra en esta ubicación"; }
        } else
        { $msg_alert = $msg_nodelete; }
      }

      // Filtrar los registros actuales
      $prodBobinaSelect = $link->query("
        SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina) AS noop,
               prodbobina.fchmovimiento,
               prodbobina.cdgbobina
          FROM prodlote,
               prodbobina
         WHERE prodlote.cdglote = prodbobina.cdglote AND
               prodbobina.cdgproducto = '".$_GET['cdgproducto']."' AND 
               prodbobina.sttbobina = '6')");

      if ($prodBobinaSelect->num_rows > 0)
      { $item = 0;

        while ($regProdBobina = $prodBobinaSelect->fetch_object())
        { $item++;

          $inspProdBobina_noop[$item] = $regProdBobina->noop;
          $inspProdBobina_fchmovimiento[$item] = $regProdBobina->fchmovimiento;
          $inspProdBobina_cdgbobina[$item] = $regProdBobina->cdgbobina; }

        $nBobinas = $prodBobinaSelect->num_rows; }

      echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Producto <b><a href="inspBuscador.php?cdgproducto='.$_GET['cdgproducto'].'">'.$regPdtoImpresion->impresion.'</a></b></label>
        </article>
        <label class="modulo_listado"><b>'.$nBobinas.'</b> Bobinas liberadas</label>

        <section class="subbloque">';

      if ($nBobinas > 0)
      { echo '
          <table class="catalogo" align="center">
            <tr><th>NoOP</th>
              <th>Fecha de registro</th>
              <th>Deshacer</th></tr>
            <tr><td colspan="3"><hr></td></tr>';

        for ($item=1; $item <= $nBobinas; $item++)
        { echo '
            <tr><td>'.$inspProdBobina_noop[$item].'</td>
              <td>'.$inspProdBobina_fchmovimiento[$item].'</td>
              <td align="center">'.$_button_blue_repeat.'</td></tr>'; }

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