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

  m3nu_produccion();

  $sistModulo_cdgmodulo = '60110';
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
        <form id="login" action="/sm_produccion/pdtoImpresionFilter.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 

      exit; }

    $pdtoImpresionSelect = $link->query("
      SELECT pdtodiseno.diseno,
             pdtodiseno.proyecto,
             pdtoimpresion.impresion,
             pdtoimpresion.periodo,
             pdtoimpresion.cdgimpresion,
             pdtoimpresion.sttimpresion
        FROM pdtoimpresion,
             pdtodiseno
       WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno
    ORDER BY pdtodiseno.sttdiseno DESC,
             pdtoimpresion.sttimpresion DESC,
             pdtoimpresion.periodo DESC,
             pdtoimpresion.impresion");

    if ($pdtoImpresionSelect->num_rows > 0)
    { $item = 1;
      while ($regPdtoImpresion = $pdtoImpresionSelect->fetch_object())
      { $filterProducto_diseno[$item] = $regPdtoImpresion->diseno;
        $filterProducto_proyecto[$item] = $regPdtoImpresion->proyecto;
        $filterProducto_impresion[$item] = $regPdtoImpresion->impresion;
        $filterProducto_periodo[$item] = $regPdtoImpresion->periodo;
        $filterProducto_cdgimpresion[$item] = $regPdtoImpresion->cdgimpresion;
        $filterProducto_sttimpresion[$item] = $regPdtoImpresion->sttimpresion;

        $item++; }

      $nImpresiones = $pdtoImpresionSelect->num_rows; 
    }

    if ($_POST['btn_salvar'])
    { if (substr($sistModulo_permiso,0,3) == 'rwx')
      { $link->query("
          DELETE FROM pdtoimpresionfilter
           WHERE cdgusuario = '".$_SESSION['cdgusuario']."'");

        for ($item=1; $item <= $nImpresiones; $item++)
        { if ($_POST['chkboxProducto'.$filterProducto_cdgimpresion[$item]] == true) 
          { $link->query("
              INSERT INTO pdtoimpresionfilter
                (cdgusuario, cdgimpresion)
              VALUES
                ('".$_SESSION['cdgusuario']."', '".$filterProducto_cdgimpresion[$item]."')");

            $filterProducto_checked[$item] = 'checked'; }
        }
      } 
    } else
    { $pdtoImpresionFilter = $link->query("
        SELECT * FROM pdtoimpresionfilter
         WHERE cdgusuario = '".$_SESSION['cdgusuario']."'");

      if ($pdtoImpresionFilter->num_rows > 0)
      { $item=1;
        while ($regPdtoImpresion = $pdtoImpresionFilter->fetch_object())
        { $filterProducto_cdgimpresiones[$item] = $regPdtoImpresion->cdgimpresion; 

          $item++; }

        $nItems = $pdtoImpresionFilter->num_rows;

        for ($item=1; $item <= $nItems; $item++)
        { for ($subItem=1; $subItem <= $nImpresiones; $subItem++)
          { if ($filterProducto_cdgimpresiones[$item] == $filterProducto_cdgimpresion[$subItem])
            { $filterProducto_checked[$subItem] = 'checked'; }
          }
        } 
      } 
    } //*/
    
    echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_nombre">Filtro de productos para tablero de control</label>
        </article>
        <form id="formulario" name="formulario" method="POST" action="pdtoImpresionFilter.php">';

    for ($item=1; $item <= $nImpresiones; $item++)
    { echo '
          <section class="subbloque">
            <article align="right">
              <img src="/img_sistema/print.png" height="40px" /><br>
              <input type="checkbox" id="chkboxProducto'.$filterProducto_cdgimpresion[$item].'" name="chkboxProducto'.$filterProducto_cdgimpresion[$item].'" '.$filterProducto_checked[$item].' />
            </article>
            <article align="right">
              <label>Proyecto</label><br/>
              <label>Periodo</label><br/>
              <label>Diseño</label><br/>
              <label>Impresión</label>
            </article>
            <article>
              <label><b><i>'.$filterProducto_proyecto[$item].'</i></b></label><br/>
              <label><b><i>'.$filterProducto_periodo[$item].'</i></b></label><br/>
              <label><b><i>'.$filterProducto_diseno[$item].'</i></b></label><br/>
              <label><b>'.$filterProducto_impresion[$item].'</b></label>
            </article>          
          </section>'; }

    echo '
          <section class="subbloque">
            <article>
              <input type="submit" id="btn_salvar" name="btn_salvar" value="Salvar" />
            </article>
          </section>
        </form>
      </div>';

    echo '
    <section id="modulo_flotante" align="right">
      <a href="prodTableroFilter.php"><img src="/img_sistema/filter.png" height="32" der="0"/></a>
    <seccion>';        

    if ($msg_alert != '')
    { echo '
      <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
      <div><h1>Módulo no encontrado o bloqueado.</h1></div>'; }
?> 

    </div>
  </body>
</html>