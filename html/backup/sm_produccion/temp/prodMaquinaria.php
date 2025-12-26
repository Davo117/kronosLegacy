<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  // captura de valores 
  $prodMaquinaria_cdgproceso = $_POST['slc_cdgproceso'];
  $prodMaquinaria_idmaquinaria = trim($_POST['txt_idmaquinaria']);
  $prodMaquinaria_maquinaria = trim($_POST['txt_maquinaria']);

  if ($_GET['cdgproceso'])
  { $prodMaquinaria_cdgproceso = $_GET['cdgproceso'];}

  // Operaciones con registros existentes  
  if ($_GET['cdgmaquinaria'])
  { $link_mysqli = conectar();
    $prodMaquinariaSelect = $link_mysqli->query("
      SELECT * FROM prodmaquinaria
      WHERE cdgmaquinaria = '".$_GET['cdgmaquinaria']."'");
    
    if ($prodMaquinariaSelect->num_rows > 0)
    { $regProdMaquinaria = $prodMaquinariaSelect->fetch_object();

      $prodMaquinaria_cdgproceso = $regProdMaquinaria->cdgproceso;
      $prodMaquinaria_idmaquinaria = $regProdMaquinaria->idmaquinaria;
      $prodMaquinaria_maquinaria = $regProdMaquinaria->maquinaria;      
      $prodMaquinaria_cdgmaquinaria = $regProdMaquinaria->cdgmaquinaria;
      $prodMaquinaria_sttmaquinaria = $regProdMaquinaria->sttmaquinaria;

      if ($_GET['proceso'] == 'update')
      { if ($prodMaquinaria_sttmaquinaria == '1')
        { $prodMaquinaria_newsttmaquinaria = '0'; }
      
        if ($prodMaquinaria_sttmaquinaria == '0')
        { $prodMaquinaria_newsttmaquinaria = '1'; }
        
        $link_mysqli = conectar();
        $link_mysqli->query("
          UPDATE prodmaquinaria
          SET sttmaquinaria = '".$prodMaquinaria_newsttmaquinaria."' 
          WHERE cdgmaquinaria = '".$prodMaquinaria_cdgmaquinaria."'");
          
        if ($link_mysqli->affected_rows > 0)
        { $aviso = 'La Maquinaria fue actualizada en su status.'; }
        else
        { $aviso = 'La Maquinaria NO fue actualizada (status).'; }
      }

      if ($_GET['proceso'] == 'delete')
      { $link_mysqli = conectar();
        $prodLoteOpeSelect = $link_mysqli->query("
          SELECT * FROM prodloteope
          WHERE cdgmaquinaria = '".$prodMaquinaria_cdgmaquinaria."'");
          
        if ($prodLoteOpeSelect->num_rows > 0)
        { $aviso = 'La Maquinaria cuenta con registros ligados en produccion, no pudo ser eliminada.'; }
        else
        { $link_mysqli = conectar();
          $link_mysqli->query("
            DELETE FROM prodmaquinaria
            WHERE cdgmaquinaria = '".$prodMaquinaria_cdgmaquinaria."'
            AND sttmaquinaria = '0'");
            
          if ($link_mysqli->affected_rows > 0)
          { $aviso = 'La Maquinaria fue eliminada con exito.'; }
          else
          { $aviso = 'La Maquinaria NO fue eliminada.'; }
        }

        $prodLoteOpeSelect->close;
      }
    }

    $prodMaquinariaSelect->close;
  } 
  ///////////////////////////////////////////////////////////////

  // Operaciones con el boton SUBMIT
  if ($_POST['btn_submit'])
  { if (strlen($prodMaquinaria_cdgproceso) > 0 AND strlen($prodMaquinaria_idmaquinaria) > 0)
    { // Buscar coincidencias
      $link_mysqli = conectar();
      $prodMaquinariaSelect = $link_mysqli->query("
        SELECT * FROM prodmaquinaria
        WHERE cdgproceso = '".$prodMaquinaria_cdgproceso."' 
        AND idmaquinaria = '".$prodMaquinaria_idmaquinaria."'");
        
      if ($prodMaquinariaSelect->num_rows > 0)
      {	$regProdMaquinaria = $prodMaquinariaSelect->fetch_object();
        
        // Actualizar un registro
        $link_mysqli = conectar();
        $link_mysqli->query("
          UPDATE prodmaquinaria
          SET maquinaria = '".$prodMaquinaria_maquinaria."'
          WHERE cdgmaquinaria = '".$regProdMaquinaria->cdgmaquinaria."'
          AND sttmaquinaria = '1'");
          
        if ($link_mysqli->affected_rows > 0) 
        { $aviso = 'La Maquinaria fue actualizada con exito.'; }
        else
        { $aviso = 'La Maquinaria NO fue actualizado.'; }      
      } 
      else
      { for ($cdgmaquinaria = 1; $cdgmaquinaria <= 1000; $cdgmaquinaria++)
        { $prodMaquinaria_cdgmaquinaria = $prodMaquinaria_cdgproceso.str_pad($cdgmaquinaria,3,'0',STR_PAD_LEFT);
          
          if ($cdgmaquinaria > 999)
          { $aviso = 'La Maquinaria NO fue insertado, se ha alcanzado el tope de maquinaria por proceso.'; }
          else
          { // Insertar un registro
            $link_mysqli = conectar();
            $link_mysqli->query("
              INSERT INTO prodmaquinaria
                (cdgproceso, idmaquinaria, maquinaria, cdgmaquinaria)
              VALUES
                ('".$prodMaquinaria_cdgproceso."', '".$prodMaquinaria_idmaquinaria."', '".$prodMaquinaria_maquinaria."', '".$prodMaquinaria_cdgmaquinaria."')");
            
            if ($link_mysqli->affected_rows > 0) 
            { $aviso = 'La Maquinaria fue insertada con exito.'; 
              $cdgmaquinaria = 1000; }      
          }
        }
      }

      $prodMaquinariaSelect->close;
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

  $idsproceso = $prodProcesoSelect->num_rows;
  $prodProcesoSelect->close; 
  ///////////////////////////////////////////////////////////////

  echo '
    <form id="frm_prodmaquinaria" name="frm_prodmaquinaria" method="POST" action="prodMaquinaria.php">
      <table align="center">
        <thead>
          <tr><th colspan="5" align="left">Captura de Maquinaria por proceso</th></tr>
        </thead>
        <tbody>
          <tr><td colspan="3">
              <label for="lbl_cdgbloque"><a href="../sm_producto/pdtoproceso.php?cdgproceso='.$prodMaquinaria_cdgproceso.'">proceso</a></label><br/>
              <select style="width:160px" id="slc_cdgproceso" name="slc_cdgproceso" onchange="document.frm_prodmaquinaria.submit()">
                <option value="">Selecciona una opcion</option>';
    
  for ($id_proceso = 1; $id_proceso <= $idsproceso; $id_proceso++) 
  { echo '
                <option value="'.$prodProceso_cdgproceso[$id_proceso].'"';
            
    if ($prodMaquinaria_cdgproceso == $prodProceso_cdgproceso[$id_proceso]) { echo ' selected="selected"'; }
    echo '>'.$prodProceso_idproceso[$id_proceso].'</option>'; }
    
  echo '
              </select></td>
            <td colspan="2"><label for="lbl_idmpresion">maquinaria</label><br/>
              <input type="text" style="width:100px;" maxlength="24" id="txt_idmaquinaria" name="txt_idmaquinaria" value="'.$prodMaquinaria_idmaquinaria.'" title="Identificador maquinaria" required/></td></tr>
          <tr><td colspan="5">
              <label for="lbl_maquinaria">Descripci&oacute;n</label><br/>
              <input type="text" style="width:300px;" maxlength="80" id="txt_maquinaria" name="txt_maquinaria" value="'.$prodMaquinaria_maquinaria.'" title="Descripcion de maquinaria" required/></td></tr>
          <tr><td><label for="lbl_ancho">Ancho</label><br/>
              <input type="text" style="width:50px; text-align:right;" maxlength="12" id="txt_ancho" name="txt_ancho" value="'.$prodMaquinaria_ancho.'" title="Ancho en milimetros (Ancho plano)" required/></td>
            <td><label for="lbl_ceja">Ceja</label><br/>
              <input type="text" style="width:50px; text-align:right;" maxlength="12" id="txt_ceja" name="txt_ceja" value="'.$prodMaquinaria_ceja.'" title="Ancho en milimetros (Ancho plano)" required/></td>
            <td><label for="lbl_alpaso">Al paso</label><br/>
              <input type="text" style="width:50px; text-align:right;" maxlength="12" id="txt_alpaso" name="txt_alpaso" value="'.$prodMaquinaria_alpaso.'" title="maquinariaes al paso" required/></td>
            <td><label for="lbl_tolerancia">Tolerancia</label><br/>
              <input type="text" style="width:50px; text-align:right;" maxlength="12" id="txt_tolerancia" name="txt_tolerancia" value="'.$prodMaquinaria_tolerancia.'" title="Tolerancia de excedente" required/></td>
            <td><label for="lbl_corte">Corte</label><br/>
              <input type="text" style="width:50px; text-align:right;" maxlength="12" id="txt_corte" name="txt_corte" value="'.$prodMaquinaria_corte.'" title="Corte en milimetros" required/></td></tr>
        <tbody>
        <tfoot>
          <tr><td colspan="5" align="right"><input type="submit" id="btn_submit" name="btn_submit" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>';

  // Filtrado de registros 
  if ($_POST['chk_vertodos'])
  { $prodMaquinaria_vertodos = 'checked'; 
  
    $link_mysqli = conectar();
    $prodMaquinariaSelect = $link_mysqli->query("
      SELECT * FROM prodMaquinaria
      WHERE cdgproceso = '".$prodMaquinaria_cdgproceso."'
      ORDER BY sttmaquinaria DESC,        
        idmaquinaria"); }
  else
  { $link_mysqli = conectar();
    $prodMaquinariaSelect = $link_mysqli->query("
      SELECT * FROM prodMaquinaria
      WHERE cdgproceso = '".$prodMaquinaria_cdgproceso."'
      AND sttmaquinaria = '1'
      ORDER BY idmaquinaria"); }
  
  if ($prodMaquinariaSelect->num_rows > 0)
  { $idmaquinaria = 1;
    while ($regProdMaquinaria = $prodMaquinariaSelect->fetch_object())
    { $prodMaquinarias_idmaquinaria[$idmaquinaria] = $regProdMaquinaria->idmaquinaria;
      $prodMaquinarias_maquinaria[$idmaquinaria] = $regProdMaquinaria->maquinaria;    
      $prodMaquinarias_ancho[$idmaquinaria] = $regProdMaquinaria->ancho;
      $prodMaquinarias_ceja[$idmaquinaria] = $regProdMaquinaria->ceja;
      $prodMaquinarias_alpaso[$idmaquinaria] = $regProdMaquinaria->alpaso;
      $prodMaquinarias_tolerancia[$idmaquinaria] = $regProdMaquinaria->tolerancia;
      $prodMaquinarias_corte[$idmaquinaria] = $regProdMaquinaria->corte;
      $prodMaquinarias_cdgmaquinaria[$idmaquinaria] = $regProdMaquinaria->cdgmaquinaria;
      $prodMaquinarias_sttmaquinaria[$idmaquinaria] = $regProdMaquinaria->sttmaquinaria; 

      $idmaquinaria++; }

    $idsmaquinaria = $prodMaquinariaSelect->num_rows; }

  $prodMaquinariaSelect->close;  
  ///////////////////////////////////////////////////////////////

  echo '    
      <table align="center">
        <thead>      
          <tr align="center">
            <td colspan="2"></td>
            <th colspan="4">Milimetros</th>
            <th colspan="4"><input type="checkbox" name="chk_vertodos" id="chk_vertodos" onclick="document.frm_prodmaquinaria.submit()" '.$prodMaquinaria_vertodos.'>
              <label for="lbl_vertodo">Ver todo</label></th>
          </tr>
          <tr align="left">
            <th><label for="lbl_ttlidmaquinaria">maquinaria</label></th>
            <th><label for="lbl_ttlmaquinaria">Descripcion</label></th>
            <th colspan="3" align="center"><label for="lbl_ttlancho">Distribuci&oacute;n</label></th>
            <th><label for="lbl_ttlcorte">Corte</label></th>            
            <th colspan="3"><label for="lbl_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';

  if ($idsmaquinaria > 0)
  { for ($idmaquinaria=1; $idmaquinaria<=$idsmaquinaria; $idmaquinaria++)
    { echo '
          <tr align="center">
            <td align="left"><strong>'.$prodMaquinarias_idmaquinaria[$idmaquinaria].'</strong></td>
            <td align="left">'.$prodMaquinarias_maquinaria[$idmaquinaria].'</td>
            <td colspan="2" align="left">((('.$prodMaquinarias_ancho[$idmaquinaria].'+'.$prodMaquinarias_ceja[$idmaquinaria].')*'.$prodMaquinarias_alpaso[$idmaquinaria].')+'.$prodMaquinarias_tolerancia[$idmaquinaria].') =</td>
            <td align="right"><strong>'.((($prodMaquinarias_ancho[$idmaquinaria]+$prodMaquinarias_ceja[$idmaquinaria])*$prodMaquinarias_alpaso[$idmaquinaria])+$prodMaquinarias_tolerancia[$idmaquinaria]).'</strong></td>
            <td align="right"><strong>'.$prodMaquinarias_corte[$idmaquinaria].'</strong></td>';

      if ((int)$prodMaquinarias_sttmaquinaria[$idmaquinaria] > 0)
      { echo '
            <td><a href="prodMaquinaria.php?cdgmaquinaria='.$prodMaquinarias_cdgmaquinaria[$idmaquinaria].'">'.$png_search.'</a></td>            
            <td><a href="prodMaquinariaImagen.php?cdgmaquinaria='.$prodMaquinarias_cdgmaquinaria[$idmaquinaria].'">'.$png_camera.'</a></td>
            <td><a href="prodMaquinaria.php?cdgmaquinaria='.$prodMaquinarias_cdgmaquinaria[$idmaquinaria].'&proceso=update">'.$png_power_blue.'</a></td>'; }
      else
       { echo '
            <td><a href="prodMaquinaria.php?cdgmaquinaria='.$prodMaquinarias_cdgmaquinaria[$idmaquinaria].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td></td>
            <td><a href="prodMaquinaria.php?cdgmaquinaria='.$prodMaquinarias_cdgmaquinaria[$idmaquinaria].'&proceso=update">'.$png_power_black.'</a></td>'; }

      echo '</tr>';
    }      
  }
  
  echo '
        </tbody>
        <tfoot>
          <tr><th colspan="9" align="right">              
              <label for="lbl_ppgdatos">['.$idsmaquinaria.'] Registros encontrados</label></th></tr>
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

  unset($prodMaquinarias_idmaquinaria);
  unset($prodMaquinarias_maquinaria);  
  unset($prodMaquinarias_ancho);  
  unset($prodMaquinarias_alpaso);  
  unset($prodMaquinarias_tolerancia);  
  unset($prodMaquinarias_corte);  
  unset($prodMaquinarias_cdgmaquinaria);
  unset($prodMaquinarias_sttmaquinaria);

?>

  </body>	
</html>