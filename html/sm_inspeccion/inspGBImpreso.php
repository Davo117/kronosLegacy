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

  $sistModulo_cdgmodulo = '72001';
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
        <form id="login" action="inspGBImpreso.php" method="POST">';

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

      $inspGBImpreso_producto = $regPdtoImpresion->impresion;
      $inspGBImpreso_cdgproducto = $regPdtoImpresion->cdgimpresion;

      //Deshacer la operación REVISION de rollos
      if ($_GET['cdglote'])
      { if (substr($sistModulo_permiso,0,3) == 'rwx')
        { $link->query("
            DELETE FROM prodlote
             WHERE cdglote = '".$_GET['cdglote']."' AND
                   sttlote = '1'");

          if ($link->affected_rows > 0)
          { $link->query("
              UPDATE prodloteope
                 SET cdgoperacion = CONCAT(cdgoperacion, 'C ".date('Y-m-d H:i:s')." ".$_SESSION['cdgusuario']."')
               WHERE cdglote = '".$_GET['cdglote']."' AND
                     cdgoperacion = '20001'");

            $link->query("
              UPDATE proglote
                 SET obs = CONCAT('".date('Y-m-d H:i:s')."\nOperación de impresión deshecha por ".$_SESSION['usuario']."\n', obs),
                     sttlote = '8'
               WHERE cdglote = '".$_GET['cdglote']."' AND
                     sttlote = '9'");
          }
        } else
        { $msg_alert = $msg_nodelete; }
      } //*/

      // Filtrar los registros actuales
      $prodLoteSelect = $link->query("
        SELECT prodlote.noop,
               proglote.fchmovimiento,
               prodlote.cdglote
          FROM proglote,
               prodlote
         WHERE proglote.cdglote = prodlote.cdglote AND
               prodlote.cdgproducto = '".$_GET['cdgproducto']."' AND 
               prodlote.sttlote = '1'
      GROUP BY prodlote.cdglote
      ORDER BY proglote.fchmovimiento");

      if ($prodLoteSelect->num_rows > 0)
      { $item = 0;

        while ($regProdLote = $prodLoteSelect->fetch_object())
        { $item++;

          $prodLotes_noop[$item] = $regProdLote->noop;
          $prodLotes_fchmovimiento[$item] = $regProdLote->fchmovimiento;
          $prodLotes_cdglote[$item] = $regProdLote->cdglote; }

        $nLotes = $prodLoteSelect->num_rows; }

      echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Producto <b><a href="inspBuscador.php?cdgproducto='.$inspGBImpreso_cdgproducto.'">'.$inspGBImpreso_producto.'</a></b></label>
        </article>
        <label class="modulo_listado"><b>'.$nLotes.'</b> Lotes impresos</label>

        <section class="subbloque">';

      if ($nLotes > 0)
      { echo '
          <table class="catalogo" align="center">
            <tr><th>NoOP</th>
              <th>Fecha de registro</th>
              <th>Deshacer</th></tr>
            <tr><td colspan="3"><hr></td></tr>';

        for ($item=1; $item <= $nLotes; $item++)
        { echo '
            <tr><td><b>'.$prodLotes_noop[$item].'</b></td>
              <td><i>'.$prodLotes_fchmovimiento[$item].'</i></td>
              <td align="center"><a href="inspGBImpreso.php?cdglote='.$prodLotes_cdglote[$item].'&cdgproducto='.$_GET['cdgproducto'].'">'.$_button_blue_repeat.'</a></td></tr>'; }

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