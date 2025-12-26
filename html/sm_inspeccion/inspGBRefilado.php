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

  $sistModulo_cdgmodulo = '73001';
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
        <form id="login" action="inspGBRefilado.php" method="POST">';

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

      $inspGBRefilado_producto = $regPdtoImpresion->impresion;
      $inspGBRefilado_cdgproducto = $regPdtoImpresion->cdgimpresion;

      //Deshacer la operación REVISION de rollos
      if ($_GET['cdglote'])
      { if (substr($sistModulo_permiso,0,3) == 'rwx')
        { $prodPaqueteSelect = $link->query("
            SELECT cdglote, 
             COUNT(cdgbobina) AS bobinas
              FROM prodbobina
             WHERE cdglote = '".$_GET['cdglote']."' AND
                   sttbobina != '1'
          GROUP BY cdglote");

          if ($prodPaqueteSelect->num_rows > 0)
          { $msg_alert = "Oops!\nAlguna(s) bobina(s) de esta lote ya fueron afectados."; }
          else
          { $link->query("
              DELETE FROM prodbobina
               WHERE cdglote = '".$_GET['cdglote']."' AND
                     sttbobina = '1'");

            if ($link->affected_rows > 0)
            { $prodLoteOpeSelect = $link->query("
                SELECT * FROM proloteope
                 WHERE cdglote = '".$_GET['cdglote']."'
                ORDER BY cdgoperacion");

              $inspGBRefilado_newstatus = '0';
              if ($prodLoteOpeSelect->num_rows > 0)
              { while ($regProdBobinaOpe = $prodLoteOpeSelect->fetch_object())
                { if ($regProdBobinaOpe->cdgoperacion == '20001')
                  { $inspGBRefilado_newstatus = '1'; }
                  elseif ($regProdBobinaOpe->cdgoperacion == '20006')
                  { $inspGBRefilado_newstatus = '6'; }
                  elseif ($regProdBobinaOpe->cdgoperacion == '20007')
                  { $inspGBRefilado_newstatus = '7'; }
                }

                $link->query("
                  UPDATE prodloteope
                     SET cdgoperacion = CONCAT(cdgoperacion, 'C ".date('Y-m-d H:i:s')." ".$_SESSION['cdgusuario']."')
                   WHERE cdglote = '".$_GET['cdglote']."' AND
                         cdgoperacion = '30001'");

                $link->query("
                  UPDATE prodlote
                     SET obs = CONCAT('".date('Y-m-d H:i:s')."\nOperación de refilado deshecha por ".$_SESSION['usuario']."\n', obs),
                         sttlote = '".$inspGBRefilado_newstatus."'
                   WHERE cdglote = '".$_GET['cdglote']."' AND
                         sttlote = '9'");                
              }              
            }
          }
          
        } else
        { $msg_alert = $msg_nodelete; }
      } //*/

      // Filtrar los registros actuales
      $prodLoteSelect = $link->query("
        SELECT prodlote.noop,
               prodlote.fchmovimiento,
               prodbobina.cdglote
          FROM prodlote,
               prodbobina
         WHERE prodlote.cdglote = prodbobina.cdglote AND
               prodbobina.cdgproducto = '".$_GET['cdgproducto']."' AND 
               prodbobina.sttbobina = '1'
      GROUP BY prodbobina.cdglote
      ORDER BY prodbobina.fchmovimiento");

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
          <label class="modulo_listado">Producto <b><a href="inspBuscador.php?cdgproducto='.$inspGBRefilado_cdgproducto.'">'.$inspGBRefilado_producto.'</a></b></label>
        </article>
        <label class="modulo_listado"><b>'.$nLotes.'</b> Lotes refilados</label>

        <section class="subbloque">';

      if ($nLotes > 0)
      { echo '
          <table class="catalogo" align="center">
            <tr><th>NoOP</th>
              <th>Fecha de registro</th>
              <th>Deshacer</th></tr>
            <tr><td colspan="3"><hr></td></tr>';

        for ($item=1; $item <= $nLotes; $item++)
        { $prodBobinaSelect = $link->query("
            SELECT cdglote,
             COUNT(cdgbobina) AS bobinas
              FROM prodbobina
             WHERE cdglote = '".$prodLotes_cdglote[$item]."' AND
                   sttpaquete != '1'
          GROUP BY cdgbobina");

          echo '
            <tr><td><b>'.$prodLotes_noop[$item].'</b></td>
              <td><i>'.$prodLotes_fchmovimiento[$item].'</i></td>';

          if ($prodBobinaSelect->num_rows > 0)
          { echo '
              <td></td>'; }
          else
          { echo '
              <td align="center"><a href="inspGBRefilado.php?cdglote='.$prodLotes_cdglote[$item].'&cdgproducto='.$_GET['cdgproducto'].'">'.$_button_blue_repeat.'</a></td>'; }

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