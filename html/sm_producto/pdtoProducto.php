<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Productos del cliente</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
<?php 

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_producto();

  $sistModulo_cdgmodulo = '20025';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { if ($_GET['mode']=='logout') { cl0s3(); }

    if ($_POST['textusername'] AND $_POST['textpassword']) 
    { val1dat3($_POST['textusername'], $_POST['textpassword']); }

    if ($_SESSION['cdgusuario'])
    { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

      ma1n(); 
    } else
    { echo '
      <div id="loginform">
        <form id="login" action="pdtoProducto.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 

      exit; } 

    $pdtoProducto_idproducto = trim($_POST['txtId']);
    $pdtoProducto_producto = trim($_POST['txtNombre']);  
    
    if ($_GET['cdgproducto'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $pdtoProductoSelect = $link->query("
          SELECT * FROM pdtoproducto
           WHERE cdgproducto = '".$_GET['cdgproducto']."'");
        
        if ($pdtoProductoSelect->num_rows > 0)
        { $regPdtoProducto = $pdtoProductoSelect->fetch_object();

          $pdtoProducto_idproducto = $regPdtoProducto->idproducto;
          $pdtoProducto_producto = $regPdtoProducto->producto;      
          $pdtoProducto_cdgproducto = $regPdtoProducto->cdgproducto;
          $pdtoProducto_sttproducto = $regPdtoProducto->sttproducto;

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($pdtoProducto_sttproducto == '1')
              { $pdtoProducto_newsttproducto = '0'; }
            
              if ($pdtoProducto_sttproducto == '0')
              { $pdtoProducto_newsttproducto = '1'; }
          
              $link->query("
                UPDATE pdtoproducto
                   SET sttproducto = '".$pdtoProducto_newsttproducto."' 
                 WHERE cdgproducto = '".$pdtoProducto_cdgproducto."'");
                
              if ($link->affected_rows > 0)
              { $msg_alert = 'El producto fue actualizado en su status.'; }
              else
              { $msg_alert = 'El producto NO fue actualizado (status).'; }
            } else
            { $msg_alert = $msg_norewrite; }
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $pdtoImpresionSelect = $link->query("
                SELECT * FROM pdtoimpresion
                WHERE cdgproducto = '".$pdtoProducto_cdgproducto."'");

              if ($pdtoImpresionSelect->num_rows > 0)
              { $msg_alert = 'El producto no esta vacío, tiene impresiones ligadas y no pudo ser eliminado.'; }
              else
              { $link->query("
                  DELETE FROM pdtoproducto
                   WHERE cdgproducto = '".$pdtoProducto_cdgproducto."' AND 
                         sttproducto = '0'");                
                  
                if ($link->affected_rows > 0)
                { $msg_alert = 'El producto fue eliminado con exito.'; }
                else
                { $msg_alert = 'El producto NO fue eliminado.'; }
              } 
            } else
            { $msg_alert = $msg_nodelete; } 
          }
        }
      } else
      { $msg_alert = $msg_noread; }
    } 

    if ($_POST['btnSalvar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if (strlen($pdtoProducto_idproducto) > 0 AND strlen($pdtoProducto_producto) > 0)
        { $pdtoProductoSelect = $link->query("
            SELECT * FROM pdtoproducto
             WHERE idproducto = '".$pdtoProducto_idproducto."'");
            
          if ($pdtoProductoSelect->num_rows > 0)
          { $regPdtoProducto = $pdtoProductoSelect->fetch_object();
 
            $link->query("
              UPDATE pdtoproducto
                 SET producto = '".$pdtoProducto_producto."'
               WHERE cdgproducto = '".$regPdtoProducto->cdgproducto."' AND 
                     sttproducto = '1'");
              
            if ($link->affected_rows > 0) 
            { $msg_alert = 'El producto fue actualizado con exito.'; }
            else
            { $msg_alert = 'El producto NO fue actualizado.'; }
          }
          else
          { for ($cdgproducto = 1; $cdgproducto <= 1000; $cdgproducto++)
            { $pdtoProducto_cdgproducto = str_pad($cdgproducto,3,'0',STR_PAD_LEFT);
              
              if ($cdgproducto > 999)
              { $msg_alert = 'El producto NO fue insertado, se ha alcanzado el tope de productos.'; }
              else
              { $link->query("
                  INSERT INTO pdtoproducto
                    (idproducto, producto, cdgproducto)
                  VALUES
                    ('".$pdtoProducto_idproducto."', '".$pdtoProducto_producto."', '".$pdtoProducto_cdgproducto."')");
                
                if ($link->affected_rows > 0) 
                { $msg_alert = 'El producto fue insertado con exito.';
                  break; }
              }
            }
          }
        }
      } else
      { $msg_alert = $msg_norewrite; }
    }
    
    // Filtro de productos
    if ($_POST['chk_vertodos'])
    { $vertodo = 'checked';
      // Filtrado completo
      $pdtoProductoSelect = $link->query("
        SELECT * FROM pdtoproducto
      ORDER BY sttproducto DESC,
               idproducto,
               producto");
    } else
    { // Buscar coincidencias
      $pdtoProductoSelect = $link->query("
        SELECT * FROM pdtoproducto
         WHERE sttproducto = '1'
      ORDER BY idproducto,
               producto"); }

    if ($pdtoProductoSelect->num_rows > 0)
    { $item = 0;

      while ($regPdtoProducto = $pdtoProductoSelect->fetch_object())
      { $item++;

        $pdtoProductos_idproducto[$item] = $regPdtoProducto->idproducto;
        $pdtoProductos_producto[$item] = $regPdtoProducto->producto;
        $pdtoProductos_cdgproducto[$item] = $regPdtoProducto->cdgproducto;
        $pdtoProductos_sttproducto[$item] = $regPdtoProducto->sttproducto; 

      }

      $nProductos = $item;
    }
    // Final del filtro de productos

    echo '
      <div class="bloque">
        <form id="formulario" name="formulario" method="POST" action="pdtoProducto.php">
          <article class="subbloque">
            <label class="modulo_nombre">Productos cliente</label>
          </article>
          <input type="checkbox" id="chk_vertodos" name="chk_vertodos" onclick="document.formulario.submit()" '.$vertodo.'><label>ver todo</label>
          <a href="ayuda.php#Productos">'.$_help_blue.'</a>

          <section class="subbloque">
            <article>
              <label>Identificador</label><br>
              <input type="text" id="txtId" name="txtId" value="'.$pdtoProducto_idproducto.'" autofocus="autofocus" required/>          
            </article>            

            <article>
              <label>Nombre</label><br>
              <input type="text" id="txtNombre" name="txtNombre" value="'.$pdtoProducto_producto.'" required/>          
            </article>
            
            <article>
              <br><input type="submit" id="btnSalvar" name="btnSalvar" value="Salvar" /> 
            </article>
          </section>
        </form>
      </div>';

    if ($nProductos > 0)
    { echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Catálogo de productos cliente</label><br>
        </article>
        <label><b>'.$nProductos.'</b> Registros encontrados</label>

        <section class="listado">
          <ul>';

      for ($item=1; $item<=$nProductos; $item++)
      { echo '
            <li><article>';

        if ((int)$pdtoProductos_sttproducto[$item] > 0)
        { echo '
                <a href="pdtoProducto.php?cdgproducto='.$pdtoProductos_cdgproducto[$item].'">'.$_search.'</a>
                <a href="pdtoProducto.php?cdgproducto='.$pdtoProductos_cdgproducto[$item].'&proceso=update">'.$_power_blue.'</a>'; }
        else
         { echo '
                <a href="pdtoProducto.php?cdgproducto='.$pdtoProductos_cdgproducto[$item].'&proceso=delete">'.$_recycle_bin.'</a>
                <a href="pdtoProducto.php?cdgproducto='.$pdtoProductos_cdgproducto[$item].'&proceso=update">'.$_power_black.'</a>'; }

        echo '</article>

              <article>
                <label class="textId">'.$pdtoProductos_idproducto[$item].'</label><br>
                <label class="textNombre">'.$pdtoProductos_producto[$item].'</label>
              </article></li>'; }

      echo '
          </ul>
        </section>
      </div>'; } 

    if ($msg_alert != '')
    { echo '
      <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
      <div><h1>Módulo no disponible o bloqueado.</h1></div>'; }
?>

    </div>
  </body>
</html>