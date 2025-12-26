<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  // captura de valores 
  $prodMaquina_cdgproceso = $_POST['slc_cdgproceso'];
  $prodMaquina_idmaquina = trim($_POST['txt_idmaquina']);
  $prodMaquina_maquina = trim($_POST['txt_maquina']);

  if ($_GET['cdgproceso'])
  { $prodMaquina_cdgproceso = $_GET['cdgproceso'];}

  // Operaciones con registros existentes  
  if ($_GET['cdgmaquina'])
  { $link_mysqli = conectar();
    $prodMaquinaSelect = $link_mysqli->query("
      SELECT * FROM prodmaquina
      WHERE cdgmaquina = '".$_GET['cdgmaquina']."'");
    
    if ($prodMaquinaSelect->num_rows > 0)
    { $regProdMaquina = $prodMaquinaSelect->fetch_object();

      $prodMaquina_cdgproceso = $regProdMaquina->cdgproceso;
      $prodMaquina_idmaquina = $regProdMaquina->idmaquina;
      $prodMaquina_maquina = $regProdMaquina->maquina;      
      $prodMaquina_cdgmaquina = $regProdMaquina->cdgmaquina;
      $prodMaquina_sttmaquina = $regProdMaquina->sttmaquina;

      if ($_GET['proceso'] == 'update')
      { if ($prodMaquina_sttmaquina == '1')
        { $prodMaquina_newsttmaquina = '0'; }
      
        if ($prodMaquina_sttmaquina == '0')
        { $prodMaquina_newsttmaquina = '1'; }
        
        if ($prodMaquina_newsttmaquina != '')
        { $link_mysqli = conectar();
          $link_mysqli->query("
            UPDATE prodmaquina
            SET sttmaquina = '".$prodMaquina_newsttmaquina."' 
            WHERE cdgmaquina = '".$prodMaquina_cdgmaquina."'"); 

          if ($link_mysqli->affected_rows > 0)
          { $aviso = 'La Maquina fue actualizada en su status.'; }
          else
          { $aviso = 'La Maquina NO fue actualizada (status).'; }
        }
      }

      if ($_GET['proceso'] == 'delete')
      { $link_mysqli = conectar();
        $prodLoteOpeSelect = $link_mysqli->query("
          SELECT * FROM prodloteope
          WHERE cdgmaquina = '".$prodMaquina_cdgmaquina."'");
          
        if ($prodLoteOpeSelect->num_rows > 0)
        { $aviso = 'La Maquina cuenta con registros ligados en produccion, no pudo ser eliminada.'; }
        else
        { $link_mysqli = conectar();
          $link_mysqli->query("
            DELETE FROM prodmaquina
            WHERE cdgmaquina = '".$prodMaquina_cdgmaquina."'
            AND sttmaquina = '0'");
            
          if ($link_mysqli->affected_rows > 0)
          { $aviso = 'La Maquina fue eliminada con exito.'; }
          else
          { $aviso = 'La Maquina NO fue eliminada.'; }
        }

        $prodLoteOpeSelect->close;
      }
    }

    $prodMaquinaSelect->close;
  } 
  ///////////////////////////////////////////////////////////////

  // Operaciones con el boton SUBMIT
  if ($_POST['btn_submit'])
  { if (strlen($prodMaquina_cdgproceso) > 0 AND strlen($prodMaquina_idmaquina) > 0)
    { // Buscar coincidencias
      $link_mysqli = conectar();
      $prodMaquinaSelect = $link_mysqli->query("
        SELECT * FROM prodmaquina
        WHERE cdgproceso = '".$prodMaquina_cdgproceso."' 
        AND idmaquina = '".$prodMaquina_idmaquina."'");
        
      if ($prodMaquinaSelect->num_rows > 0)
      {	$regProdMaquina = $prodMaquinaSelect->fetch_object();
        
        // Actualizar un registro
        $link_mysqli = conectar();
        $link_mysqli->query("
          UPDATE prodmaquina
          SET maquina = '".$prodMaquina_maquina."'
          WHERE cdgmaquina = '".$regProdMaquina->cdgmaquina."'
          AND sttmaquina = '1'");
          
        if ($link_mysqli->affected_rows > 0) 
        { $aviso = 'La Maquina fue actualizada con exito.'; }
        else
        { $aviso = 'La Maquina NO fue actualizado.'; }      
      } 
      else
      { for ($cdgmaquina = 1; $cdgmaquina <= 1000; $cdgmaquina++)
        { $prodMaquina_cdgmaquina = $prodMaquina_cdgproceso.str_pad($cdgmaquina,3,'0',STR_PAD_LEFT);
          
          if ($cdgmaquina > 999)
          { $aviso = 'La Maquina NO fue insertado, se ha alcanzado el tope de maquina por proceso.'; }
          else
          { // Insertar un registro
            $link_mysqli = conectar();
            $link_mysqli->query("
              INSERT INTO prodmaquina
                (cdgproceso, idmaquina, maquina, cdgmaquina)
              VALUES
                ('".$prodMaquina_cdgproceso."', '".$prodMaquina_idmaquina."', '".$prodMaquina_maquina."', '".$prodMaquina_cdgmaquina."')");
            
            if ($link_mysqli->affected_rows > 0) 
            { $aviso = 'La Maquina fue insertada con exito.'; 
              $cdgmaquina = 1000; }      
          }
        }
      }

      $prodMaquinaSelect->close;
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
    <form id="frm_prodmaquina" name="frm_prodmaquina" method="POST" action="prodMaquina.php">
      <table align="center">
        <thead>
          <tr><th colspan="5" align="left">Captura de Maquina por proceso</th></tr>
        </thead>
        <tbody>
          <tr><td>
              <label for="lbl_cdgbloque"><a href="prodProceso.php?cdgproceso='.$prodMaquina_cdgproceso.'">proceso</a></label><br/>
              <select style="width:160px" id="slc_cdgproceso" name="slc_cdgproceso" onchange="document.frm_prodmaquina.submit()">
                <option value="">Selecciona una opcion</option>';
    
  for ($id_proceso = 1; $id_proceso <= $numprocesos; $id_proceso++) 
  { echo '
                <option value="'.$prodProceso_cdgproceso[$id_proceso].'"';
            
    if ($prodMaquina_cdgproceso == $prodProceso_cdgproceso[$id_proceso]) { echo ' selected="selected"'; }
    echo '>'.$prodProceso_proceso[$id_proceso].'</option>'; }
    
  echo '
              </select></td>
            <td><label for="lbl_idmaquina">Maquina</label><br/>
              <input type="text" style="width:100px;" maxlength="24" id="txt_idmaquina" name="txt_idmaquina" value="'.$prodMaquina_idmaquina.'" title="Identificador maquina" required/></td></tr>
          <tr><td colspan="2">
              <label for="lbl_maquina">Descripci&oacute;n</label><br/>
              <input type="text" style="width:300px;" maxlength="80" id="txt_maquina" name="txt_maquina" value="'.$prodMaquina_maquina.'" title="Descripcion de maquina" required/></td></tr>
        <tbody>
        <tfoot>
          <tr><td colspan="2" align="right"><input type="submit" id="btn_submit" name="btn_submit" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>';

  // Filtrado de registros 
  if ($_POST['chk_vertodos'])
  { $prodMaquina_vertodos = 'checked'; 
  
    $link_mysqli = conectar();
    $prodMaquinaSelect = $link_mysqli->query("
      SELECT * FROM prodmaquina
      WHERE cdgproceso = '".$prodMaquina_cdgproceso."'
      ORDER BY sttmaquina DESC,        
        idmaquina"); }
  else
  { $link_mysqli = conectar();
    $prodMaquinaSelect = $link_mysqli->query("
      SELECT * FROM prodmaquina
      WHERE cdgproceso = '".$prodMaquina_cdgproceso."'
      AND sttmaquina = '1'
      ORDER BY idmaquina"); }
  
  if ($prodMaquinaSelect->num_rows > 0)
  { $id_maquina = 1;
    while ($regProdMaquina = $prodMaquinaSelect->fetch_object())
    { $prodMaquinas_idmaquina[$id_maquina] = $regProdMaquina->idmaquina;
      $prodMaquinas_maquina[$id_maquina] = $regProdMaquina->maquina;    
      $prodMaquinas_cdgmaquina[$id_maquina] = $regProdMaquina->cdgmaquina;
      $prodMaquinas_sttmaquina[$id_maquina] = $regProdMaquina->sttmaquina; 

      $id_maquina++; }

    $nummaquinas = $prodMaquinaSelect->num_rows; }

  $prodMaquinaSelect->close;  
  ///////////////////////////////////////////////////////////////

  echo '    
      <table align="center">
        <thead>      
          <tr align="center">
            <td colspan="2"></td>            
            <th colspan="3"><input type="checkbox" name="chk_vertodos" id="chk_vertodos" onclick="document.frm_prodmaquina.submit()" '.$prodMaquina_vertodos.'>
              <label for="lbl_vertodo">Ver todo</label></th>
          </tr>
          <tr align="left">
            <th><label for="lbl_ttlidmaquina">maquina</label></th>
            <th><label for="lbl_ttlmaquina">Descripcion</label></th>
            <th colspan="3"><label for="lbl_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';

  if ($nummaquinas > 0)
  { for ($id_maquina=1; $id_maquina<=$nummaquinas; $id_maquina++)
    { echo '
          <tr align="center">
            <td align="left"><strong>'.$prodMaquinas_idmaquina[$id_maquina].'</strong></td>
            <td align="left">'.$prodMaquinas_maquina[$id_maquina].'</td>';

      if ((int)$prodMaquinas_sttmaquina[$id_maquina] > 0)
      { echo '
            <td><a href="prodMaquina.php?cdgmaquina='.$prodMaquinas_cdgmaquina[$id_maquina].'">'.$png_search.'</a></td>            
            <td><a href="prodMaquinaImagen.php?cdgmaquina='.$prodMaquinas_cdgmaquina[$id_maquina].'">'.$png_camera.'</a></td>
            <td><a href="prodMaquina.php?cdgmaquina='.$prodMaquinas_cdgmaquina[$id_maquina].'&proceso=update">'.$png_power_blue.'</a></td>'; }
      else
       { echo '
            <td><a href="prodMaquina.php?cdgmaquina='.$prodMaquinas_cdgmaquina[$id_maquina].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td></td>
            <td><a href="prodMaquina.php?cdgmaquina='.$prodMaquinas_cdgmaquina[$id_maquina].'&proceso=update">'.$png_power_black.'</a></td>'; }

      echo '</tr>';
    }      
  }
  
  echo '
        </tbody>
        <tfoot>
          <tr><th colspan="5" align="right">
              <label for="lbl_ppgdatos">['.$nummaquinas.'] Registros encontrados</label></th></tr>
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

  unset($prodMaquinas_idmaquina);
  unset($prodMaquinas_maquina);
  unset($prodMaquinas_cdgmaquina);
  unset($prodMaquinas_sttmaquina);

?>

  </body>	
</html>