<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $pdtoProducto_cdgimpresion = $_POST['slc_cdgimpresion'];
  $pdtoProducto_idproducto = trim($_POST['txt_idproducto']);
  $pdtoProducto_producto = trim($_POST['txt_producto']);
  $pdtoProducto_corte = trim($_POST['txt_corte']);

  if ($_GET['cdgimpresion'])
  { $pdtoProducto_cdgimpresion = $_GET['cdgimpresion']; }

  if ($_GET['cdgproducto'])
  { $link_mysqli = conectar();
    $pdtoProductoSelect = $link_mysqli->query("
      SELECT * FROM pdtoproducto
      WHERE cdgproducto = '".$_GET['cdgproducto']."'");
    
    if ($pdtoProductoSelect->num_rows > 0)
    { $regPdtoProducto = $pdtoProductoSelect->fetch_object();

      $pdtoProducto_cdgimpresion = $regPdtoProducto->cdgimpresion;
      $pdtoProducto_idproducto = $regPdtoProducto->idproducto;
      $pdtoProducto_producto = $regPdtoProducto->producto;
      $pdtoProducto_corte = $regPdtoProducto->corte;      
      $pdtoProducto_cdgproducto = $regPdtoProducto->cdgproducto;
      $pdtoProducto_sttproducto = $regPdtoProducto->sttproducto;

      if ($_GET['proceso'] == 'update')
      { if ($pdtoProducto_sttproducto == '1')
        { $pdtoProducto_newsttproducto = '0'; }
      
        if ($pdtoProducto_sttproducto == '0')
        { $pdtoProducto_newsttproducto = '1'; }
        
        $link_mysqli = conectar();
        $link_mysqli->query("
          UPDATE pdtoproducto
          SET sttproducto = '".$pdtoProducto_newsttproducto."' 
          WHERE cdgproducto = '".$pdtoProducto_cdgproducto."'");
          
        if ($link_mysqli->affected_rows > 0)
        { $aviso = 'El producto fue actualizado en su status.'; }
      }

      if ($_GET['proceso'] == 'delete')
      { $link_mysqli = conectar();
        $progProgramaSelect = $link_mysqli->query("
          SELECT * FROM prodlote
          WHERE cdgproducto = '".$pdtoProducto_cdgproducto."'");

        if ($progProgramaSelect->num_rows > 0)
        { $aviso = 'El producto tiene OPs ligadas y no puede ser eliminado.'; }
        else
        { $link_mysqli = conectar();
          $link_mysqli->query("
            DELETE FROM pdtoproducto
            WHERE cdgproducto = '".$pdtoProducto_cdgproducto."'");
              
          if ($link_mysqli->affected_rows > 0)
          { $aviso = 'El producto fue eliminado con exito.'; }
          else
          { $aviso = 'El producto no fue eliminado.'; }
        }
      }
    }
  } 

  if ($_POST['btn_submit'])
  { if (strlen($pdtoProducto_cdgimpresion) > 0 AND strlen($pdtoProducto_idproducto) > 0)
    { $link_mysqli = conectar();
      $pdtoProductoSelect = $link_mysqli->query("
        SELECT * FROM pdtoproducto
        WHERE cdgimpresion = '".$pdtoProducto_cdgimpresion."' 
        AND idproducto = '".$pdtoProducto_idproducto."'");
        
      if ($pdtoProductoSelect->num_rows > 0)
      {	$regPdtoProducto = $pdtoProductoSelect->fetch_object();
        
        $link_mysqli = conectar();
        $link_mysqli->query("
          UPDATE pdtoproducto
          SET producto = '".$pdtoProducto_producto."'                       
          WHERE cdgproducto = '".$regPdtoProducto->cdgproducto."'");
          
        if ($link_mysqli->affected_rows > 0) 
        { $aviso = 'El producto fue actualizado con exito.'; }
      }
      else
      { for ($cdgproducto = 1; $cdgproducto <= 999; $cdgproducto++)
        { $pdtoProducto_cdgproducto = $pdtoProducto_cdgimpresion.str_pad($cdgproducto,3,'0',STR_PAD_LEFT);
          
          $link_mysqli = conectar();
          $link_mysqli->query("
            INSERT INTO pdtoproducto
              (cdgimpresion, idproducto, producto, corte, cdgproducto)
            VALUES
              ('".$pdtoProducto_cdgimpresion."', '".$pdtoProducto_idproducto."', '".$pdtoProducto_producto."', '".$pdtoProducto_corte."', '".$pdtoProducto_cdgproducto."')");
        
          if ($link_mysqli->affected_rows > 0) 
          { $aviso = 'El producto fue insertado con exito.'; 
            $cdgproducto = 1000; }
        }
      }
    }
  }
  //*/

// Filtrado de diseños de impresión
  $link_mysqli = conectar(); 
  $pdtoProyectoSelect = $link_mysqli->query("
    SELECT * FROM pdtoproyecto
    WHERE sttproyecto = '1'
    ORDER BY proyecto,
      idproyecto");
  
  $idproyecto = 1;
  while ($regPdtoProyecto = $pdtoProyectoSelect->fetch_object()) 
  { $pdtoProductos_idproyecto[$idproyecto] = $regPdtoProyecto->idproyecto;
    $pdtoProductos_proyecto[$idproyecto] = $regPdtoProyecto->proyecto;
    $pdtoProductos_cdgproyecto[$idproyecto] = $regPdtoProyecto->cdgproyecto; 

    $link_mysqli = conectar(); 
    $pdtoImpresionSelect = $link_mysqli->query("
      SELECT * FROM pdtoimpresion
      WHERE cdgproyecto = '".$regPdtoProyecto->cdgproyecto."'
      AND sttimpresion = '1'
      ORDER BY impresion,
        idimpresion");
    
    $idimpresion = 1;
    while ($regPdtoImpresion = $pdtoImpresionSelect->fetch_object()) 
    { $pdtoProductos_idimpresion[$idproyecto][$idimpresion] = $regPdtoImpresion->idimpresion;
      $pdtoProductos_impresion[$idproyecto][$idimpresion] = $regPdtoImpresion->impresion;
      $pdtoProductos_cdgimpresion[$idproyecto][$idimpresion] = $regPdtoImpresion->cdgimpresion; 

       $idimpresion++; }

    $idsimpresion[$idproyecto] = $pdtoImpresionSelect->num_rows;
    $pdtoImpresionSelect->close;

    $idproyecto++; }

  $idsproyecto = $pdtoProyectoSelect->num_rows;  
  $pdtoProyectoSelect->close;

// Filtrado de datos
  if ($_POST['chk_vertodos'])
  { $pdtoProyecto_vertodos = 'checked'; 
  
    $link_mysqli = conectar();
    $pdtoProductoSelect = $link_mysqli->query("
      SELECT * FROM pdtoproducto
      WHERE pdtoproducto.cdgimpresion = '".$pdtoProducto_cdgimpresion."'
      ORDER BY producto,
        idproducto"); }
  else
  { $link_mysqli = conectar();
    $pdtoProductoSelect = $link_mysqli->query("
      SELECT * FROM pdtoproducto
      WHERE pdtoproducto.cdgimpresion = '".$pdtoProducto_cdgimpresion."'
      AND sttproducto = '1'
      ORDER BY producto,
        idproducto"); }

  $idproducto = 1;
  while ($regPdtoProducto = $pdtoProductoSelect->fetch_object())
  { $pdtoProductos_idproducto[$idproducto] = $regPdtoProducto->idproducto;
    $pdtoProductos_producto[$idproducto] = $regPdtoProducto->producto;
    $pdtoProductos_corte[$idproducto] = $regPdtoProducto->corte;    
    $pdtoProductos_cdgproducto[$idproducto] = $regPdtoProducto->cdgproducto;
    $pdtoProductos_sttproducto[$idproducto] = $regPdtoProducto->sttproducto;

    $idproducto++; }

  $idsproducto = $pdtoProductoSelect->num_rows;
  $pdtoProductoSelect->close;

  echo '
    <form id="frm_pdtoproducto" name="frm_pdtoproducto" method="POST" action="pdtoProducto.php">
      <table align="center">
        <thead>
          <tr><th colspan="2" align="left">Captura de Productos</th></tr>
        </thead>
        <tbody>
          <tr><td colspan="2">
              <label for="lbl_cdgbloque"><a href="pdtoImpresion.php?cdgimpresion='.$pdtoProducto_cdgimpresion.'">Impresi&oacute;n</a></label><br/>
              <select style="width:240px" id="slc_cdgimpresion" name="slc_cdgimpresion" onchange="document.frm_pdtoproducto.submit()">';

  for ($idproyecto = 1; $idproyecto <= $idsproyecto; $idproyecto++) 
  { echo '
                <optgroup label="'.$pdtoProductos_idproyecto[$idproyecto].'">';

    for ($idimpresion = 1; $idimpresion <= $idsimpresion[$idproyecto]; $idimpresion++) 
    { echo '
                  <option value="'.$pdtoProductos_cdgimpresion[$idproyecto][$idimpresion].'"';
            
      if ($pdtoProducto_cdgimpresion == $pdtoProductos_cdgimpresion[$idproyecto][$idimpresion]) { echo ' selected="selected"'; }
      echo '>'.$pdtoProductos_idimpresion[$idproyecto][$idimpresion].' ('.$pdtoProductos_impresion[$idproyecto][$idimpresion].')</option>'; 
    }

    echo '
                </optgroup>';
  }

  echo '
              </select></td></tr>
          <tr><td>
              <label for="lbl_idproducto">Producto</label><br/>
              <input type="text" style="width:100px;" maxlength="24" id="txt_idproducto" name="txt_idproducto" value="'.$pdtoProducto_idproducto.'" title="Identificador de producto" required/></td>
            <td>
              <label for="lbl_corte">Corte en milimetros</label><br/>
              <input type="text" style="width:100px; text-align:right;" maxlength="12" id="txt_corte" name="txt_corte" value="'.$pdtoProducto_corte.'" title="Corte en milimetro" required/></td></tr>
            <td colspan="2">
              <label for="lbl_producto">Decripci&oacute;n</label><br/>
              <input type="text" style="width:240px;" maxlength="60" id="txt_producto" name="txt_producto" value="'.$pdtoProducto_producto.'" title="Descripcion del producto" required/></td></tr>
        <tbody>
        <tfoot>
          <tr><td colspan="2" align="right"><input type="submit" id="btn_submit" name="btn_submit" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>

      <table align="center">
        <thead>
          <tr align="left">
            <th><label for="lbl_ttlidprograma">Producto</label></th>
            <th><label for="lbl_ttlprograma">Decripci&oacute;n</label></th>
            <th><label for="lbl_ttlcorte">Corte</label></th>
            <th colspan="2"><label for="lbl_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';

  if ($idsproducto > 0)
  { for ($idproducto=1; $idproducto<=$idsproducto; $idproducto++)
    { echo '
          <tr align="center">
            <td align="left"><strong>'.$pdtoProductos_idproducto[$idproducto].'</strong></td>
            <td align="left">'.$pdtoProductos_producto[$idproducto].'</td>
            <td align="right">'.$pdtoProductos_corte[$idproducto].' mm</td>';

      if ((int)$pdtoProductos_sttproducto[$idproducto] > 0)
      { echo '
            <td><a href="pdtoProducto.php?cdgproducto='.$pdtoProductos_cdgproducto[$idproducto].'">'.$png_search.'</a></td>                      
            <td><a href="pdtoProducto.php?cdgproducto='.$pdtoProductos_cdgproducto[$idproducto].'&proceso=update">'.$png_power_blue.'</a></td>'; }
      else
       { echo '
            <td><a href="pdtoProducto.php?cdgproducto='.$pdtoProductos_cdgproducto[$idproducto].'&proceso=delete">'.$png_recycle_bin.'</a></td>            
            <td><a href="pdtoProducto.php?cdgproducto='.$pdtoProductos_cdgproducto[$idproducto].'&proceso=update">'.$png_power_black.'</a></td>'; }

      echo '</tr>';
    }
  }

  echo '
        </tbody>
        <tfoot>
          <tr><th colspan="5" align="right">
              <input type="checkbox" name="chk_vertodos" id="chk_vertodos" onclick="document.frm_pdtoproducto.submit()" '.$pdtoProducto_vertodos.'>
              <label for="lbl_ppgdatos">Ver todos ['.$idsproducto.'] Registros encontrados</label></th></tr>
        </tfoot>
      </table>
    </form>';

  if ($aviso != '')
  { echo '
    <script type="text/javascript"> alert("'.$aviso.'"); </script>'; }

  unset($pdtoProductos_idproducto);
  unset($pdtoProductos_producto);
  unset($pdtoProductos_corte);
  unset($pdtoProductos_cdgproducto);
  unset($pdtoProductos_sttproducto);    

?>

  </body>	
</html>