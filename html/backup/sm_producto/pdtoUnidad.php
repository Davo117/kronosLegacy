<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '20909';

  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']); 

  $pdtoUnidad_idunidad = trim($_POST['txt_idunidad']);
  $pdtoUnidad_unidad = trim($_POST['txt_unidad']);  
  
  if ($_GET['cdgunidad'])
  { $link_mysqli = conectar();
    $pdtoUnidadSelect = $link_mysqli->query("
      SELECT * FROM pdtounidad
      WHERE cdgunidad = '".$_GET['cdgunidad']."'");
    
    if ($pdtoUnidadSelect->num_rows > 0)
    { $regPdtoUnidad = $pdtoUnidadSelect->fetch_object();

      $pdtoUnidad_idunidad = $regPdtoUnidad->idunidad;
      $pdtoUnidad_unidad = $regPdtoUnidad->unidad;      
      $pdtoUnidad_cdgunidad = $regPdtoUnidad->cdgunidad;
      $pdtoUnidad_sttunidad = $regPdtoUnidad->sttunidad;

      if ($_GET['proceso'] == 'update')
      { if ($pdtoUnidad_sttunidad == '1')
        { $pdtoUnidad_newsttunidad = '0'; }
      
        if ($pdtoUnidad_sttunidad == '0')
        { $pdtoUnidad_newsttunidad = '1'; }
        
        $link_mysqli = conectar();
        $link_mysqli->query("
          UPDATE pdtounidad
          SET sttunidad = '".$pdtoUnidad_newsttunidad."' 
          WHERE cdgunidad = '".$pdtoUnidad_cdgunidad."'");
          
        if ($link_mysqli->affected_rows > 0)
        { $aviso = 'La unidad fue actualizada en su status.'; }
      }

      if ($_GET['proceso'] == 'delete')
      { $link_mysqli = conectar();
        $progProgramaSelect = $link_mysqli->query("
          SELECT * FROM pdtomaterial
          WHERE cdgunidad = '".$pdtoUnidad_cdgunidad."'");
          
        if ($progProgramaSelect->num_rows > 0)
        { $aviso = 'La unidad tiene materia prima ligada y no pudo ser eliminada.'; }
        else
        { $link_mysqli = conectar();
          $link_mysqli->query("
            DELETE FROM pdtounidad
            WHERE cdgunidad = '".$pdtoUnidad_cdgunidad."'
            AND sttunidad = '0'");
            
          if ($link_mysqli->affected_rows > 0)
          { $aviso = 'La unidad fue eliminada con exito.'; }
          else
          { $aviso = 'La unidad no fue eliminada.'; }
        }
      }
    }
  } 

  if ($_POST['btn_submit'])
  { if (strlen($pdtoUnidad_idunidad) > 0 AND strlen($pdtoUnidad_unidad) > 0)
    { $link_mysqli = conectar();
      $pdtoUnidadSelect = $link_mysqli->query("
        SELECT * FROM pdtounidad
        WHERE idunidad = '".$pdtoUnidad_idunidad."'");
        
      if ($pdtoUnidadSelect->num_rows > 0)
      {	$regPdtoUnidad = $pdtoUnidadSelect->fetch_object();
        
        $link_mysqli = conectar();
        $link_mysqli->query("
          UPDATE pdtounidad
          SET unidad = '".$pdtoUnidad_unidad."'
          WHERE cdgunidad = '".$regPdtoUnidad->cdgunidad."'
          AND sttunidad = '1'");
          
        if ($link_mysqli->affected_rows > 0) 
        { $aviso = 'La unidad fue actualizada con exito.'; }
      }
      else
      { for ($cdgunidad = 1; $cdgunidad <= 99; $cdgunidad++)
        { $pdtoUnidad_cdgunidad = str_pad($cdgunidad,2,'0',STR_PAD_LEFT);
          
          $link_mysqli = conectar();
          $link_mysqli->query("
            INSERT INTO pdtounidad
              (idunidad, unidad, cdgunidad)
            VALUES
              ('".$pdtoUnidad_idunidad."', '".$pdtoUnidad_unidad."', '".$pdtoUnidad_cdgunidad."')");
          
          if ($link_mysqli->affected_rows > 0) 
          { $aviso = 'La unidad fue insertada con exito.'; 
            $cdgunidad = 100; }      
        }
      }
    }
  }
  
  if ($_POST['chk_vertodos'])
  { $pdtoUnidad_vertodos = 'checked'; 
  
    $link_mysqli = conectar();
    $pdtoUnidadSelect = $link_mysqli->query("
      SELECT * FROM pdtounidad
      ORDER BY sttunidad DESC,        
        unidad,
        idunidad"); }
  else
  { $link_mysqli = conectar();
    $pdtoUnidadSelect = $link_mysqli->query("
      SELECT * FROM pdtounidad
      WHERE sttunidad = '1'
      ORDER BY unidad,
        idunidad"); }
  
  $idunidad = 1;
  while ($regPdtoUnidad = $pdtoUnidadSelect->fetch_object())
  { $pdtoUnidads_idunidad[$idunidad] = $regPdtoUnidad->idunidad;
    $pdtoUnidads_unidad[$idunidad] = $regPdtoUnidad->unidad;
    $pdtoUnidads_cdgunidad[$idunidad] = $regPdtoUnidad->cdgunidad;
    $pdtoUnidads_sttunidad[$idunidad] = $regPdtoUnidad->sttunidad; 

    $idunidad++; }

  $idsunidad = $pdtoUnidadSelect->num_rows;

  $pdtoUnidadSelect->close;
    
  echo '
    <form id="frm_pdtounidad" name="frm_pdtounidad" method="POST" action="pdtoUnidad.php">
      <table align="center">
        <thead>
          <tr><th align="left">'.$sistModulo_modulo.'</th></tr>
        </thead>
        <tbody>
          <tr><td>
              <label for="lbl_idunidad">Unidad</label><br/>
              <input type="text" style="width:100px;" maxlength="24" id="txt_idunidad" name="txt_idunidad" value="'.$pdtoUnidad_idunidad.'" title="Identificador de unidad" required/></td></tr>
          <tr><td>
              <label for="lbl_unidad">Decripci&oacute;n</label><br/>
              <input type="text" style="width:240px;" maxlength="60" id="txt_unidad" name="txt_unidad" value="'.$pdtoUnidad_unidad.'" title="Descripcion de unidad" required/></td></tr>            
        <tbody>
        <tfoot>
          <tr><td align="right"><input type="submit" id="btn_submit" name="btn_submit" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>
    
      <table align="center">
        <thead>
          <tr align="left">
            <th><label for="lbl_ttlunidad">Unidad</label></th>
            <th><label for="lbl_ttlrefunidad">Decripci&oacute;n</label></th>            
            <th colspan="3"><label for="lbl_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';

  if ($idsunidad > 0)
  { for ($idunidad=1; $idunidad<=$idsunidad; $idunidad++)
    { echo '
          <tr align="center">
            <td align="left"><strong>'.$pdtoUnidads_idunidad[$idunidad].'</strong></td>
            <td align="left">'.$pdtoUnidads_unidad[$idunidad].'</td>';

      if ((int)$pdtoUnidads_sttunidad[$idunidad] > 0)
      { echo '
            <td><a href="pdtoUnidad.php?cdgunidad='.$pdtoUnidads_cdgunidad[$idunidad].'">'.$png_search.'</a></td>            
            <td><a href="pdtoUnidad.php?cdgunidad='.$pdtoUnidads_cdgunidad[$idunidad].'&proceso=update">'.$png_power_blue.'</a></td>'; }
      else
       { echo '
            <td><a href="pdtoUnidad.php?cdgunidad='.$pdtoUnidads_cdgunidad[$idunidad].'&proceso=delete">'.$png_recycle_bin.'</a></td>            
            <td><a href="pdtoUnidad.php?cdgunidad='.$pdtoUnidads_cdgunidad[$idunidad].'&proceso=update">'.$png_power_black.'</a></td>'; }

      echo '</tr>';
    }      
  }
  
  echo '
        </tbody>
        <tfoot>
          <tr><th colspan="4" align="right">
              <input type="checkbox" name="chk_vertodos" id="chk_vertodos" onclick="document.frm_pdtounidad.submit()" '.$pdtoUnidad_vertodos.'>
              <label for="lbl_ppgdatos">Ver todos ['.$idsunidad.'] Registros encontrados</label></th></tr>
        </tfoot>
      </table>
    </form>'; 

  if ($aviso != '')
  { echo '
    <script type="text/javascript"> alert("'.$aviso.'"); </script>'; }

  unset($pdtoUnidads_idunidad);
  unset($pdtoUnidads_unidad);
  unset($pdtoUnidads_cdgunidad);
  unset($pdtoUnidads_sttunidad);

  } else
  { echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
?>

  </body>	
</html>