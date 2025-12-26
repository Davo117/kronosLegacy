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

  $sistModulo_cdgmodulo = '74007';
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
        <form id="login" action="inspGBLRollo.php" method="POST">';

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

      $inspGBLRollo_producto = $regPdtoImpresion->impresion;
      $inspGBLRollo_cdgproducto = $regPdtoImpresion->cdgimpresion;

      //Deshacer la operación LIBERACION de rollos
      if ($_GET['cdgrollo'])
      { if (substr($sistModulo_permiso,0,3) == 'rwx')
        { $link->query("
            UPDATE prodrollo
               SET obs = CONCAT('".date('Y-m-d H:i:s')."\nOperación de liberación deshecha por ".$_SESSION['usuario']."\n', obs),
                   sttrollo = '6'
             WHERE cdgrollo = '".$_GET['cdgrollo']."' AND
                   sttrollo = '7'");
          if ($link->affected_rows > 0)
          { $link->query("
              UPDATE prodrolloope
                 SET cdgoperacion = CONCAT(cdgoperacion, 'C ".date('Y-m-d H:i:s')." ".$_SESSION['cdgusuario']."')
               WHERE cdgrollo = '".$_GET['cdgrollo']."' AND
                     cdgoperacion = '40007'"); 
          } else
          { $msg_alert = "Oops! Este rollo ya no se encuentra en esta ubicación"; }
        } else
        { $msg_alert = $msg_nodelete; }
      }

      // Filtrar los registros actuales
      $prodRolloSelect = $link->query("
        SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) AS noop,
               prodrollo.fchmovimiento,
               prodrollo.cdgrollo
          FROM prodlote,
               prodbobina,
               prodrollo
         WHERE prodlote.cdglote = prodbobina.cdglote AND
               prodbobina.cdgbobina = prodrollo.cdgbobina AND
               prodrollo.cdgproducto = '".$inspGBLRollo_cdgproducto."' AND 
               prodrollo.sttrollo = '7'");

      if ($prodRolloSelect->num_rows > 0)
      { $item = 0;

        while ($regProdRollo = $prodRolloSelect->fetch_object())
        { $item++;

          $inspProdRollo_noop[$item] = $regProdRollo->noop;
          $inspProdRollo_fchmovimiento[$item] = $regProdRollo->fchmovimiento;
          $inspProdRollo_cdgrollo[$item] = $regProdRollo->cdgrollo; }

        $nRollos = $prodRolloSelect->num_rows; }

      echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Producto <b><a href="inspBuscador.php?cdgproducto='.$inspGBLRollo_cdgproducto.'">'.$inspGBLRollo_producto.'</a></b></label>
        </article>
        <label class="modulo_listado"><b>'.$nRollos.'</b> Rollos liberados</label>

        <section class="subbloque">';

      if ($nRollos > 0)
      { echo '
          <table class="catalogo" align="center">
            <tr><th>NoOP</th>
              <th>Fecha de registro</th>
              <th>Deshacer</th></tr>
            <tr><td colspan="3"><hr></td></tr>';

        for ($item=1; $item <= $nRollos; $item++)
        { echo '
            <tr><td><b>'.$inspProdRollo_noop[$item].'</b></td>
              <td><i>'.$inspProdRollo_fchmovimiento[$item].'</i></td>
              <td align="center"><a href="inspGBLRollo.php?cdgrollo='.$inspProdRollo_cdgrollo[$item].'&cdgproducto='.$_GET['cdgproducto'].'">'.$_button_blue_repeat.'</a></td></tr>'; }

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