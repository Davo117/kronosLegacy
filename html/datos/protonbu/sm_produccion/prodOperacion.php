<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  // captura de valores 
  $prodOperacion_cdgproceso = $_POST['slc_cdgproceso'];
  $prodOperacion_idoperacion = trim($_POST['txt_idoperacion']);
  $prodOperacion_operacion = trim($_POST['txt_operacion']);

  if ($_GET['cdgproceso'])
  { $prodOperacion_cdgproceso = $_GET['cdgproceso'];}

  // Operaciones con registros existentes  
  if ($_GET['cdgoperacion'])
  { $link_mysqli = conectar();
    $prodOperacionSelect = $link_mysqli->query("
      SELECT * FROM prodoperacion
      WHERE cdgoperacion = '".$_GET['cdgoperacion']."'");
    
    if ($prodOperacionSelect->num_rows > 0)
    { $regProdOperacion = $prodOperacionSelect->fetch_object();

      $prodOperacion_cdgproceso = $regProdOperacion->cdgproceso;
      $prodOperacion_idoperacion = $regProdOperacion->idoperacion;
      $prodOperacion_operacion = $regProdOperacion->operacion;      
      $prodOperacion_cdgoperacion = $regProdOperacion->cdgoperacion;
      $prodOperacion_sttoperacion = $regProdOperacion->sttoperacion;

      if ($_GET['proceso'] == 'update')
      { if ($prodOperacion_sttoperacion == '1')
        { $prodOperacion_newsttoperacion = '0'; }
      
        if ($prodOperacion_sttoperacion == '0')
        { $prodOperacion_newsttoperacion = '1'; }
        
        $link_mysqli = conectar();
        $link_mysqli->query("
          UPDATE prodoperacion
          SET sttoperacion = '".$prodOperacion_newsttoperacion."' 
          WHERE cdgoperacion = '".$prodOperacion_cdgoperacion."'");
          
        if ($link_mysqli->affected_rows > 0)
        { $aviso = 'La Operacion fue actualizada en su status.'; }
        else
        { $aviso = 'La Operacion NO fue actualizada (status).'; }
      }

      if ($_GET['proceso'] == 'delete')
      { $link_mysqli = conectar();
        $prodLoteOpeSelect = $link_mysqli->query("
          SELECT * FROM prodloteope
          WHERE cdgoperacion = '".$prodOperacion_cdgoperacion."'");
          
        if ($prodLoteOpeSelect->num_rows > 0)
        { $aviso = 'La Operacion cuenta con registros ligados en produccion, no pudo ser eliminada.'; }
        else
        { $link_mysqli = conectar();
          $link_mysqli->query("
            DELETE FROM prodoperacion
            WHERE cdgoperacion = '".$prodOperacion_cdgoperacion."'
            AND sttoperacion = '0'");
            
          if ($link_mysqli->affected_rows > 0)
          { $aviso = 'La Operacion fue eliminada con exito.'; }
          else
          { $aviso = 'La Operacion NO fue eliminada.'; }
        }

        $prodLoteOpeSelect->close;
      }
    }

    $prodOperacionSelect->close;
  } 
  ///////////////////////////////////////////////////////////////

  // Operaciones con el boton SUBMIT
  if ($_POST['btn_submit'])
  { if (strlen($prodOperacion_cdgproceso) > 0 AND strlen($prodOperacion_idoperacion) > 0)
    { // Buscar coincidencias
      $link_mysqli = conectar();
      $prodOperacionSelect = $link_mysqli->query("
        SELECT * FROM prodoperacion
        WHERE cdgproceso = '".$prodOperacion_cdgproceso."' 
        AND idoperacion = '".$prodOperacion_idoperacion."'");
        
      if ($prodOperacionSelect->num_rows > 0)
      {	$regProdOperacion = $prodOperacionSelect->fetch_object();
        
        // Actualizar un registro
        $link_mysqli = conectar();
        $link_mysqli->query("
          UPDATE prodoperacion
          SET operacion = '".$prodOperacion_operacion."'
          WHERE cdgoperacion = '".$regProdOperacion->cdgoperacion."'
          AND sttoperacion = '1'");
          
        if ($link_mysqli->affected_rows > 0) 
        { $aviso = 'La Operacion fue actualizada con exito.'; }
        else
        { $aviso = 'La Operacion NO fue actualizado.'; }      
      } 
      else
      { for ($cdgoperacion = 1; $cdgoperacion <= 1000; $cdgoperacion++)
        { $prodOperacion_cdgoperacion = $prodOperacion_cdgproceso.str_pad($cdgoperacion,3,'0',STR_PAD_LEFT);
          
          if ($cdgoperacion > 999)
          { $aviso = 'La Operacion NO fue insertado, se ha alcanzado el tope de operacion por proceso.'; }
          else
          { // Insertar un registro
            $link_mysqli = conectar();
            $link_mysqli->query("
              INSERT INTO prodoperacion
                (cdgproceso, idoperacion, operacion, cdgoperacion)
              VALUES
                ('".$prodOperacion_cdgproceso."', '".$prodOperacion_idoperacion."', '".$prodOperacion_operacion."', '".$prodOperacion_cdgoperacion."')");
            
            if ($link_mysqli->affected_rows > 0) 
            { $aviso = 'La Operacion fue insertada con exito.'; 
              $cdgoperacion = 1000; }      
          }
        }
      }

      $prodOperacionSelect->close;
    }
  }
  ///////////////////////////////////////////////////////////////

  // Filtrado de procesos  
  $link_mysqli = conectar(); 
  $prodProcesoSelect = $link_mysqli->query("
    SELECT * FROM prodproceso
    WHERE sttproceso = '1'
    ORDER BY cdgproceso");
  
  $id_proceso = 1;
  while ($regPdtoproceso = $prodProcesoSelect->fetch_object()) 
  { $prodProceso_idproceso[$id_proceso] = $regPdtoproceso->idproceso;
    $prodProceso_proceso[$id_proceso] = $regPdtoproceso->proceso;
    $prodProceso_cdgproceso[$id_proceso] = $regPdtoproceso->cdgproceso; 

     $id_proceso++; }

  $numprocesos = $prodProcesoSelect->num_rows;
  $prodProcesoSelect->close; 
  ///////////////////////////////////////////////////////////////

  echo '
    <form id="frm_prodoperacion" name="frm_prodoperacion" method="POST" action="prodOperacion.php">
      <table align="center">
        <thead>
          <tr><th colspan="5" align="left">Captura de Operacion por proceso</th></tr>
        </thead>
        <tbody>
          <tr><td>
              <label for="lbl_cdgbloque"><a href="prodProceso.php?cdgproceso='.$prodOperacion_cdgproceso.'">Proceso</a></label><br/>
              <select style="width:160px" id="slc_cdgproceso" name="slc_cdgproceso" onchange="document.frm_prodoperacion.submit()">
                <option value="">Selecciona una opcion</option>';
    
  for ($id_proceso = 1; $id_proceso <= $numprocesos; $id_proceso++) 
  { echo '
                <option value="'.$prodProceso_cdgproceso[$id_proceso].'"';
            
    if ($prodOperacion_cdgproceso == $prodProceso_cdgproceso[$id_proceso]) { echo ' selected="selected"'; }
    echo '>'.$prodProceso_proceso[$id_proceso].'</option>'; }
    
  echo '
              </select></td>
            <td><label for="lbl_idoperacion">Operaci&oacute;n</label><br/>
              <input type="text" style="width:100px;" maxlength="24" id="txt_idoperacion" name="txt_idoperacion" value="'.$prodOperacion_idoperacion.'" title="Identificador operacion" required/></td></tr>
          <tr><td colspan="2">
              <label for="lbl_operacion">Descripci&oacute;n</label><br/>
              <input type="text" style="width:300px;" maxlength="80" id="txt_operacion" name="txt_operacion" value="'.$prodOperacion_operacion.'" title="Descripcion de operacion" required/></td></tr>
        <tbody>
        <tfoot>
          <tr><td colspan="2" align="right"><input type="submit" id="btn_submit" name="btn_submit" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>';

  // Filtrado de registros 
  if ($_POST['chk_vertodos'])
  { $prodOperacion_vertodos = 'checked'; 
  
    $link_mysqli = conectar();
    $prodOperacionSelect = $link_mysqli->query("
      SELECT * FROM prodoperacion
      WHERE cdgproceso = '".$prodOperacion_cdgproceso."'
      ORDER BY sttoperacion DESC,        
        idoperacion"); }
  else
  { $link_mysqli = conectar();
    $prodOperacionSelect = $link_mysqli->query("
      SELECT * FROM prodoperacion
      WHERE cdgproceso = '".$prodOperacion_cdgproceso."'
      AND sttoperacion = '1'
      ORDER BY idoperacion"); }
  
  if ($prodOperacionSelect->num_rows > 0)
  { $id_operacion = 1;
    while ($regProdOperacion = $prodOperacionSelect->fetch_object())
    { $prodOperacions_idoperacion[$id_operacion] = $regProdOperacion->idoperacion;
      $prodOperacions_operacion[$id_operacion] = $regProdOperacion->operacion;    
      $prodOperacions_cdgoperacion[$id_operacion] = $regProdOperacion->cdgoperacion;
      $prodOperacions_sttoperacion[$id_operacion] = $regProdOperacion->sttoperacion; 

      $id_operacion++; }

    $numoperacions = $prodOperacionSelect->num_rows; }

  $prodOperacionSelect->close;  
  ///////////////////////////////////////////////////////////////

  echo '    
      <table align="center">
        <thead>      
          <tr align="center">
            <td colspan="2"></td>            
            <th colspan="3"><input type="checkbox" name="chk_vertodos" id="chk_vertodos" onclick="document.frm_prodoperacion.submit()" '.$prodOperacion_vertodos.'>
              <label for="lbl_vertodo">Ver todo</label></th>
          </tr>
          <tr align="left">
            <th><label for="lbl_ttlidoperacion">operacion</label></th>
            <th><label for="lbl_ttloperacion">Descripcion</label></th>
            <th colspan="3"><label for="lbl_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';

  if ($numoperacions > 0)
  { for ($id_operacion=1; $id_operacion<=$numoperacions; $id_operacion++)
    { echo '
          <tr align="center">
            <td align="left"><strong>'.$prodOperacions_idoperacion[$id_operacion].'</strong></td>
            <td align="left">'.$prodOperacions_operacion[$id_operacion].'</td>';

      if ((int)$prodOperacions_sttoperacion[$id_operacion] > 0)
      { echo '
            <td><a href="prodOperacion.php?cdgoperacion='.$prodOperacions_cdgoperacion[$id_operacion].'">'.$png_search.'</a></td>            
            <td><a href="prodOperacionImagen.php?cdgoperacion='.$prodOperacions_cdgoperacion[$id_operacion].'">'.$png_camera.'</a></td>
            <td><a href="prodOperacion.php?cdgoperacion='.$prodOperacions_cdgoperacion[$id_operacion].'&proceso=update">'.$png_power_blue.'</a></td>'; }
      else
       { echo '
            <td><a href="prodOperacion.php?cdgoperacion='.$prodOperacions_cdgoperacion[$id_operacion].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td></td>
            <td><a href="prodOperacion.php?cdgoperacion='.$prodOperacions_cdgoperacion[$id_operacion].'&proceso=update">'.$png_power_black.'</a></td>'; }

      echo '</tr>';
    }      
  }
  
  echo '
        </tbody>
        <tfoot>
          <tr><th colspan="5" align="right">
              <label for="lbl_ppgdatos">['.$numoperacions.'] Registros encontrados</label></th></tr>
        </tfoot>
      </table>
    </form>'; 

  if ($aviso != '')
  { echo '
    <script type="text/javascript"> alert("'.$aviso.'"); </script>'; }

  unset($prodProceso_idproceso);
  unset($prodProceso_proceso);
  unset($prodProceso_cdgproceso);
  unset($prodProceso_sttproceso);

  unset($prodOperacions_idoperacion);
  unset($prodOperacions_operacion);
  unset($prodOperacions_cdgoperacion);
  unset($prodOperacions_sttoperacion);

?>

  </body>	
</html>