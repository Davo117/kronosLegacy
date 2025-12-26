<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';
  
  $sistModulo_cdgmodulo = '20010';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']); 

    $vntsproducto_idproducto = trim($_POST['textid']);
    $vntsproducto_producto = trim($_POST['textnombre']);  
    
    if ($_GET['cdgproducto'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $link_mysqli = conectar();
        $vntsproductoSelect = $link_mysqli->query("
          SELECT * FROM vntsproducto
          WHERE cdgproducto = '".$_GET['cdgproducto']."'");
        
        if ($vntsproductoSelect->num_rows > 0)
        { $regvntsproducto = $vntsproductoSelect->fetch_object();

          $vntsproducto_idproducto = $regvntsproducto->idproducto;
          $vntsproducto_producto = $regvntsproducto->producto;      
          $vntsproducto_cdgproducto = $regvntsproducto->cdgproducto;
          $vntsproducto_sttproducto = $regvntsproducto->sttproducto;

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($vntsproducto_sttproducto == '1')
              { $vntsproducto_newsttproducto = '0'; }
            
              if ($vntsproducto_sttproducto == '0')
              { $vntsproducto_newsttproducto = '1'; }

              $link_mysqli = conectar();
              $link_mysqli->query("
                UPDATE vntsproducto
                SET sttproducto = '".$vntsproducto_newsttproducto."' 
                WHERE cdgproducto = '".$vntsproducto_cdgproducto."'");
                
              if ($link_mysqli->affected_rows > 0)
              { $msg_alert = 'El producto fue actualizado en su status.'; }
              else
              { $msg_alert = 'El producto NO fue actualizado (status).'; }
            } else
            { $msg_alert = $msg_norewrite; }
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $link_mysqli = conectar();
              $pdtoImpresionSelect = $link_mysqli->query("
                SELECT * FROM pdtoimpresion
                WHERE cdgproducto = '".$vntsproducto_cdgproducto."'");

              if ($pdtoImpresionSelect->num_rows > 0)
              { $msg_alert = 'El producto no esta vacío, tiene impresiones ligadas y no pudo ser eliminado.'; }
              else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  DELETE FROM vntsproducto
                  WHERE cdgproducto = '".$vntsproducto_cdgproducto."'
                  AND sttproducto = '0'");                
                  
                if ($link_mysqli->affected_rows > 0)
                { $msg_alert = 'El producto fue eliminado con exito.'; }
                else
                { $msg_alert = 'El producto NO fue eliminado.'; }
              } 

              //$pdtoImpresionSelect->close;
            } else
            { $msg_alert = $msg_nodelete; } 
          }
        }

        //$vntsproductoSelect->close;
      } else
      { $msg_alert = $msg_noread; }
    } 

    if ($_POST['submit'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if (strlen($vntsproducto_idproducto) > 0 AND strlen($vntsproducto_producto) > 0)
        { $link_mysqli = conectar();
          $vntsproductoSelect = $link_mysqli->query("
            SELECT * FROM vntsproducto
            WHERE idproducto = '".$vntsproducto_idproducto."'");
            
          if ($vntsproductoSelect->num_rows > 0)
          {	$regvntsproducto = $vntsproductoSelect->fetch_object();

            $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE vntsproducto
              SET producto = '".$vntsproducto_producto."'
              WHERE cdgproducto = '".$regvntsproducto->cdgproducto."'
              AND sttproducto = '1'");
              
            if ($link_mysqli->affected_rows > 0) 
            { $msg_alert = 'El producto fue actualizado con exito.'; }
            else
            { $msg_alert = 'El producto NO fue actualizado.'; }
          }
          else
          { for ($cdgproducto = 1; $cdgproducto <= 1000; $cdgproducto++)
            { $vntsproducto_cdgproducto = str_pad($cdgproducto,3,'0',STR_PAD_LEFT);
              
              if ($cdgproducto > 999)
              { $msg_alert = 'El producto NO fue insertado, se ha alcanzado el tope de productos.'; }
              else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  INSERT INTO vntsproducto
                    (idproducto, producto, cdgproducto)
                  VALUES
                    ('".$vntsproducto_idproducto."', '".$vntsproducto_producto."', '".$vntsproducto_cdgproducto."')");
                
                if ($link_mysqli->affected_rows > 0) 
                { $msg_alert = 'El producto fue insertado con exito.';
                  break; }
              }
            }
          }

          //$vntsproductoSelect->close; 
        }
      } else
      { $msg_alert = $msg_norewrite; }
    }
    
    if (substr($sistModulo_permiso,0,1) == 'r')
    { 
        // Filtrado completo
        $link_mysqli = conectar();
        $vntsproductoSelect = $link_mysqli->query("
        SELECT * FROM vntsproducto
        ORDER BY sttproducto DESC,        
          producto,
          idproducto");
      
    
      if ($vntsproductoSelect->num_rows > 0)
      { $item = 1;
        while ($regvntsproducto = $vntsproductoSelect->fetch_object())
        { $vntsproductos_idproducto[$item] = $regvntsproducto->idproducto;
          $vntsproductos_producto[$item] = $regvntsproducto->producto;
          $vntsproductos_cdgproducto[$item] = $regvntsproducto->cdgproducto;
          $vntsproductos_sttproducto[$item] = $regvntsproducto->sttproducto; 

          $item++; }

        $numproductos = $vntsproductoSelect->num_rows; 
      }
        
      //$vntsproductoSelect->close; 
    }

    echo '
    <form id="formulario" name="formulario" method="POST" action="vntsproducto.php">
      <table align="center">
        <thead>
          <tr><th align="left">'.('Catálogo de productos').'</th></tr>
        </thead>
        <tbody>
          <tr>
            <td><label for="labelid">idProducto</label><br/>
              <input type="text" style="width:140px;" maxlength="24" id="textid" name="textid" value="'.$vntsproducto_idproducto.'" placeholder="Nombre corto" autofocus="autofocus" required/></td></tr>
          <tr>
            <td><label for="labelnombre">Producto</label><br/>
              <input type="text" style="width:320px;" maxlength="60" id="textnombre" name="textnombre" value="'.$vntsproducto_producto.'" placeholder="Nombre" required/></td></tr>
        <tbody>
        <tfoot>
          <tr><td align="right"><input type="submit" id="submit" name="submit" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>

      <table align="center">
        <thead>
          <tr align="left">
            <th><label for="lbl_ttlproducto">idProducto</label></th>
            <th><label for="lbl_ttlrefproducto">Nombre del Producto</label></th>
            <th colspan="3"><label for="lbl_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';

    if ($numproductos > 0)
    { for ($item=1; $item<=$numproductos; $item++)
      { echo '
          <tr align="center">
            <td align="left"><strong>'.$vntsproductos_idproducto[$item].'</strong></td>
            <td align="left">'.$vntsproductos_producto[$item].'</td>';

        if ((int)$vntsproductos_sttproducto[$item] > 0)
        { echo '
            <td><a href="vntsproducto.php?cdgproducto='.$vntsproductos_cdgproducto[$item].'">'.$png_search.'</a></td>
            <td><a href="pdtoImpresion.php?cdgproducto='.$vntsproductos_cdgproducto[$item].'">'.$png_link.'</a></td>            
            <td><a href="vntsproducto.php?cdgproducto='.$vntsproductos_cdgproducto[$item].'&proceso=update">'.$png_power_blue.'</a></td>'; }
        else
         { echo '
            <td><a href="vntsproducto.php?cdgproducto='.$vntsproductos_cdgproducto[$item].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td>&nbsp;</td>
            <td><a href="vntsproducto.php?cdgproducto='.$vntsproductos_cdgproducto[$item].'&proceso=update">'.$png_power_black.'</a></td>'; }

        echo '</tr>';
      }      
    }

    echo '
        </tbody>
        <tfoot>
          <tr><th colspan="5" align="right">
              <label for="lbl_ppgdatos">['.$numproductos.'] Registros encontrados</label></th></tr>
        </tfoot>
      </table>
    </form>'; 

    if ($msg_alert != '')
    { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }      
?>

  </body>	
</html>
