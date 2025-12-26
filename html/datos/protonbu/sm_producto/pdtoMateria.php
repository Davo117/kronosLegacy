<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '20901';

  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);     

  $pdtoMateria_idmateria = trim($_POST['txt_idmateria']);
  $pdtoMateria_materia = trim($_POST['txt_materia']); 
  $pdtoMateria_cdgunidad = $_POST['slc_cdgunidad'];
  
  if ($_GET['cdgmateria'])
  { $link_mysqli = conectar();
    $pdtoMaterialSelect = $link_mysqli->query("
      SELECT * FROM pdtomateria
      WHERE cdgmateria = '".$_GET['cdgmateria']."'");
    
    if ($pdtoMaterialSelect->num_rows > 0)
    { $regPdtoMateria = $pdtoMaterialSelect->fetch_object();

      $pdtoMateria_idmateria = $regPdtoMateria->idmateria;
      $pdtoMateria_materia = $regPdtoMateria->materia;
      $pdtoMateria_cdgunidad = $regPdtoMateria->cdgunidad;
      $pdtoMateria_cdgmateria = $regPdtoMateria->cdgmateria;
      $pdtoMateria_sttmateria = $regPdtoMateria->sttmateria;

      if ($_GET['proceso'] == 'update')
      { if ($pdtoMateria_sttmateria == '1')
        { $pdtoMateria_newsttmateria = '0'; }
      
        if ($pdtoMateria_sttmateria == '0')
        { $pdtoMateria_newsttmateria = '1'; }
        
        $link_mysqli = conectar();
        $link_mysqli->query("
          UPDATE pdtomateria
          SET sttmateria = '".$pdtoMateria_newsttmateria."' 
          WHERE cdgmateria = '".$pdtoMateria_cdgmateria."'");
          
        if ($link_mysqli->affected_rows > 0)
        { $msg_alert = 'La materia prima fue actualizada en su status.'; }
      }

      if ($_GET['proceso'] == 'delete')
      { $link_mysqli = conectar();
        $progProgramaSelect = $link_mysqli->query("
          SELECT * FROM pdtoconsumo
          WHERE cdgmateria = '".$pdtoMateria_cdgmateria."'");
          
        if ($progProgramaSelect->num_rows > 0)
        { $msg_alert = 'La materia prima tiene consumos ligados y no pudo ser eliminada.'; }
        else
        { $link_mysqli = conectar();
          $link_mysqli->query("
            DELETE FROM pdtomateria
            WHERE cdgmateria = '".$pdtoMateria_cdgmateria."'
            AND sttmateria = '0'");
            
          if ($link_mysqli->affected_rows > 0)
          { $msg_alert = 'La materia prima fue eliminada con exito.'; }
          else
          { $msg_alert = 'La materia prima no fue eliminada.'; }
        }
      }
    }
  } 

  if ($_POST['btn_submit'])
  { if (strlen($pdtoMateria_idmateria) > 0 AND strlen($pdtoMateria_materia) > 0)
    { $link_mysqli = conectar();
      $pdtoMateriaSelect = $link_mysqli->query("
        SELECT * FROM pdtomateria
        WHERE idmateria = '".$pdtoMateria_idmateria."'");
        
      if ($pdtoMateriaSelect->num_rows > 0)
      {	$regPdtoMateria = $pdtoMateriaSelect->fetch_object();
        
        $link_mysqli = conectar();
        $link_mysqli->query("
          UPDATE pdtomateria
          SET materia = '".$pdtoMateria_materia."'
          WHERE cdgmateria = '".$regPdtoMateria->cdgmateria."'
          AND sttmateria = '1'");
          
        if ($link_mysqli->affected_rows > 0) 
        { $msg_alert = 'La materia prima fue actualizada con exito.'; }
      }
      else
      { for ($cdgmateria = 1; $cdgmateria <= 999999; $cdgmateria++)
        { $pdtoMateria_cdgmateria = str_pad($cdgmateria,6,'0',STR_PAD_LEFT);
          
          $link_mysqli = conectar();
          $link_mysqli->query("
            INSERT INTO pdtomateria
              (idmateria, materia, cdgunidad, cdgmateria)
            VALUES
              ('".$pdtoMateria_idmateria."', '".$pdtoMateria_materia."', '".$pdtoMateria_cdgunidad."', '".$pdtoMateria_cdgmateria."')");
          
          if ($link_mysqli->affected_rows > 0) 
          { $msg_alert = 'La materia prima fue insertada con exito.'; 
            $cdgmateria = 1000000; }      
        }
      }
    }
  }
  
  $link_mysqli = conectar(); 
  $pdtoUnidadSelect = $link_mysqli->query("
    SELECT * FROM pdtounidad
    WHERE sttunidad = '1'
    ORDER BY unidad,
      idunidad");
  
  $idunidad = 1;
  while ($regPdtoUnidad = $pdtoUnidadSelect->fetch_object()) 
  { $pdtoUnidads_idunidad[$idunidad] = $regPdtoUnidad->idunidad;
    $pdtoUnidads_unidad[$idunidad] = $regPdtoUnidad->unidad;
    $pdtoUnidads_cdgunidad[$idunidad] = $regPdtoUnidad->cdgunidad; 

     $idunidad++; }

  $idsunidad = $pdtoUnidadSelect->num_rows;
  $pdtoUnidadSelect->close; 

  if ($_POST['chk_vertodos'])
  { $pdtoMaterial_vertodos = 'checked'; 
  
    $link_mysqli = conectar();
    $pdtoMateriaSelect = $link_mysqli->query("
      SELECT pdtomateria.idmateria,
        pdtomateria.materia,
        pdtounidad.unidad,
        pdtomateria.cdgmateria,
        pdtomateria.sttmateria
      FROM pdtomateria,
        pdtounidad
      WHERE pdtomateria.cdgunidad = pdtounidad.cdgunidad
      ORDER BY sttmateria DESC,        
        materia,
        idmateria"); }
  else
  { $link_mysqli = conectar();
    $pdtoMateriaSelect = $link_mysqli->query("
      SELECT pdtomateria.idmateria,
        pdtomateria.materia,
        pdtounidad.unidad,
        pdtomateria.cdgmateria,
        pdtomateria.sttmateria
      FROM pdtomateria,
        pdtounidad
      WHERE pdtomateria.cdgunidad = pdtounidad.cdgunidad
      AND sttmateria = '1'
      ORDER BY materia,
        idmateria"); }
  
  $idmateria = 1;
  while ($regPdtoMateria = $pdtoMateriaSelect->fetch_object())
  { $pdtoMaterias_idmateria[$idmateria] = $regPdtoMateria->idmateria;
    $pdtoMaterias_materia[$idmateria] = $regPdtoMateria->materia;
    $pdtoMaterias_unidad[$idmateria] = $regPdtoMateria->unidad;
    $pdtoMaterias_cdgmateria[$idmateria] = $regPdtoMateria->cdgmateria;
    $pdtoMaterias_sttmateria[$idmateria] = $regPdtoMateria->sttmateria; 

    $idmateria++; }

  $idsmateria = $pdtoMateriaSelect->num_rows;

  $pdtoMateriaSelect->close;
    
  echo '
    <form id="frm_pdtomateria" name="frm_pdtomateria" method="POST" action="pdtoMateria.php">
      <table align="center">
        <thead>
          <tr><th colspan="2" align="left">'.$sistModulo_modulo.'</th></tr>
        </thead>
        <tbody>
          <tr><td>
              <label for="lbl_idmateria">Materia prima</label><br/>
              <input type="text" style="width:120px;" maxlength="24" id="txt_idmateria" name="txt_idmateria" value="'.$pdtoMateria_idmateria.'" title="Identificador de materia" required/></td>
            <td>
              <label for="lbl_cdgunidad"><a href="pdtoUnidad.php?cdgunidad='.$pdtoMateria_cdgunidad.'">Unidad de medida</a></label><br/>
              <select style="width:100px" id="slc_cdgunidad" name="slc_cdgunidad">
                <option value="">Selecciona una opcion</option>';
    
  for ($idunidad = 1; $idunidad <= $idsunidad; $idunidad++) 
  { echo '
                <option value="'.$pdtoUnidads_cdgunidad[$idunidad].'"';
            
    if ($pdtoMateria_cdgunidad == $pdtoUnidads_cdgunidad[$idunidad]) { echo ' selected="selected"'; }
    echo '>'.$pdtoUnidads_idunidad[$idunidad].' ('.$pdtoUnidads_unidad[$idunidad].')</option>'; }
    
  echo '
              </select></td></tr>
          <tr><td colspan="2">
              <label for="lbl_materia">Decripci&oacute;n</label><br/>
              <input type="text" style="width:240px;" maxlength="60" id="txt_materia" name="txt_materia" value="'.$pdtoMateria_materia.'" title="Descripcion de materia" required/></td></tr>
        <tbody>
        <tfoot>
          <tr><td colspan="2" align="right"><input type="submit" id="btn_submit" name="btn_submit" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>
    
      <table align="center">
        <thead>
          <tr align="left">
            <th><label for="lbl_ttlidmateria">materia</label></th>
            <th><label for="lbl_ttlmateria">Decripci&oacute;n</label></th>
            <th><label for="lbl_ttlunidad">Unidad de medida</label></th>            
            <th colspan="3"><label for="lbl_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';

  if ($idsmateria > 0)
  { for ($idmateria=1; $idmateria<=$idsmateria; $idmateria++)
    { echo '
          <tr align="center">
            <td align="left"><strong>'.$pdtoMaterias_idmateria[$idmateria].'</strong></td>
            <td align="left">'.$pdtoMaterias_materia[$idmateria].'</td>
            <td align="left">'.$pdtoMaterias_unidad[$idmateria].'</td>';

      if ((int)$pdtoMaterias_sttmateria[$idmateria] > 0)
      { echo '
            <td><a href="pdtoMateria.php?cdgmateria='.$pdtoMaterias_cdgmateria[$idmateria].'">'.$png_search.'</a></td>            
            <td><a href="pdtoMateria.php?cdgmateria='.$pdtoMaterias_cdgmateria[$idmateria].'&proceso=update">'.$png_power_blue.'</a></td>'; }
      else
       { echo '
            <td><a href="pdtoMateria.php?cdgmateria='.$pdtoMaterias_cdgmateria[$idmateria].'&proceso=delete">'.$png_recycle_bin.'</a></td>            
            <td><a href="pdtoMateria.php?cdgmateria='.$pdtoMaterias_cdgmateria[$idmateria].'&proceso=update">'.$png_power_black.'</a></td>'; }

      echo '</tr>';
    }      
  }
  
  echo '
        </tbody>
        <tfoot>
          <tr><th colspan="5" align="right">
              <input type="checkbox" name="chk_vertodos" id="chk_vertodos" onclick="document.frm_pdtomateria.submit()" '.$pdtoMateria_vertodos.'>
              <label for="lbl_ppgdatos">Ver todos ['.$idsmateria.'] Registros encontrados</label></th></tr>
        </tfoot>
      </table>
    </form>'; 

  if ($msg_alert != '')
  { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }

  unset($pdtoMaterias_idmateria);
  unset($pdtoMaterias_materia);
  unset($pdtoMaterias_cdgmateria);
  unset($pdtoMaterias_sttmateria);

  } else
  { echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
?>

  </body>	
</html>