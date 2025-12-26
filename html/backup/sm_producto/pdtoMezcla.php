<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '20030';

  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);   

    $pdtoMezcla_cdgimpresion = $_POST['slc_cdgimpresion'];
    $pdtoMezcla_idmezcla = trim($_POST['txt_idmezcla']);
    $pdtoMezcla_mezcla = trim($_POST['txt_mezcla']);

    if ($_GET['cdgimpresion'])
    { $pdtoMezcla_cdgimpresion = $_GET['cdgimpresion'];}
    
    if ($_GET['cdgmezcla'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $link_mysqli = conectar();
        $pdtoMezclaSelect = $link_mysqli->query("
          SELECT * FROM pdtomezcla
          WHERE cdgmezcla = '".$_GET['cdgmezcla']."'");
        
        if ($pdtoMezclaSelect->num_rows > 0)
        { $regPdtoMezcla = $pdtoMezclaSelect->fetch_object();

          $pdtoMezcla_cdgimpresion = $regPdtoMezcla->cdgimpresion;
          $pdtoMezcla_idmezcla = $regPdtoMezcla->idmezcla;
          $pdtoMezcla_mezcla = $regPdtoMezcla->mezcla;      
          $pdtoMezcla_cdgmezcla = $regPdtoMezcla->cdgmezcla;
          $pdtoMezcla_sttmezcla = $regPdtoMezcla->sttmezcla;

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($pdtoMezcla_sttmezcla == '1')
              { $pdtoMezcla_newsttmezcla = '0'; }
            
              if ($pdtoMezcla_sttmezcla == '0')
              { $pdtoMezcla_newsttmezcla = '1'; }
              
              $link_mysqli = conectar();
              $link_mysqli->query("
                UPDATE pdtomezcla
                SET sttmezcla = '".$pdtoMezcla_newsttmezcla."' 
                WHERE cdgmezcla = '".$pdtoMezcla_cdgmezcla."'");
                
              if ($link_mysqli->affected_rows > 0)
              { $msg_alert = 'La mezcla fue actualizada en su status.'; }
              else
              { $msg_alert = 'La mezcla NO fue actualizada (status).'; }
            } else
            { $msg_alert = $msg_norewrite; }             
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $link_mysqli = conectar();
              $pdtoConsumoSelect = $link_mysqli->query("
                SELECT * FROM pdtoconsumo
                WHERE cdgmezcla = '".$pdtoMezcla_cdgmezcla."'");

              if ($pdtoConsumoSelect->num_rows > 0)
              { $msg_alert = 'La mezcla cuenta con consumos, no pudo ser eliminada.'; }
              else
              { $link_mysqli = conectar();
                $pdtoConsumoSelect = $link_mysqli->query("
                  SELECT * FROM prodlote
                  WHERE cdgmezcla = '".$pdtoMezcla_cdgmezcla."'");

                if ($pdtoConsumoSelect->num_rows > 0)
                { $msg_alert = 'La mezcla cuenta con bobinas programadas, no pudo ser eliminada.'; }
                else
                { $link_mysqli = conectar();
                  $link_mysqli->query("
                    DELETE FROM pdtomezcla
                    WHERE cdgmezcla = '".$pdtoMezcla_cdgmezcla."'
                    AND sttmezcla = '0'");
                    
                  if ($link_mysqli->affected_rows > 0)
                  { $msg_alert = 'La mezcla fue eliminada con exito.'; }
                  else
                  { $msg_alert = 'La mezcla NO fue eliminada.'; }
                }
              }

              $pdtoConsumoSelect->close;
            } else
            { $msg_alert = $msg_nodelete; }               
          }
        }

        $pdtoMezclaSelect->close;   
      } else
      { $msg_alert = $msg_noread; } 
    } 

    if ($_POST['btn_salvar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if (strlen($pdtoMezcla_cdgimpresion) > 0 AND strlen($pdtoMezcla_idmezcla) > 0)
        { $link_mysqli = conectar();
          $pdtoMezclaSelect = $link_mysqli->query("
            SELECT * FROM pdtomezcla
            WHERE cdgimpresion = '".$pdtoMezcla_cdgimpresion."' 
            AND idmezcla = '".$pdtoMezcla_idmezcla."'");
            
          if ($pdtoMezclaSelect->num_rows > 0)
          {	$regPdtoMezcla = $pdtoMezclaSelect->fetch_object();
            
            $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE pdtomezcla
              SET mezcla = '".$pdtoMezcla_mezcla."'            
              WHERE cdgmezcla = '".$regPdtoMezcla->cdgmezcla."'
              AND sttmezcla = '1'");
              
            if ($link_mysqli->affected_rows > 0) 
            { $msg_alert = 'La mezcla fue actualizada con exito.'; }
            else
            { $msg_alert = 'La mezcla NO fue actualizada.'; }        
          } 
          else
          { for ($cdgmezcla = 1; $cdgmezcla <= 1000; $cdgmezcla++)
            { $pdtoMezcla_cdgmezcla = $pdtoMezcla_cdgimpresion.str_pad($cdgmezcla,3,'0',STR_PAD_LEFT);
              
              if ($cdgmezcla > 999)
              { $msg_alert = 'La mezcla NO fue insertada, se ha alcanzado el tope de mezclas por impresion.'; }
              else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  INSERT INTO pdtomezcla
                    (cdgimpresion, idmezcla, mezcla, cdgmezcla)
                  VALUES
                    ('".$pdtoMezcla_cdgimpresion."', '".$pdtoMezcla_idmezcla."', '".$pdtoMezcla_mezcla."', '".$pdtoMezcla_cdgmezcla."')");
                
                if ($link_mysqli->affected_rows > 0) 
                { $msg_alert = 'La mezcla fue insertada con exito.'; 
                  $cdgmezcla = 1000; }
              }
            }
          }

          $pdtoMezclaSelect->close; }
      } else
      { $msg_alert = $msg_norewrite; }           
    }
  
    if (substr($sistModulo_permiso,0,1) == 'r')
    { $link_mysqli = conectar(); 
      $pdtoProyectoSelect = $link_mysqli->query("
        SELECT * FROM pdtoproyecto
        WHERE sttproyecto = '1'
        ORDER BY proyecto,
          idproyecto");
      
      $id_proyecto = 1;
      while ($regPdtoProyecto = $pdtoProyectoSelect->fetch_object()) 
      { $pdtoProyecto_idproyecto[$id_proyecto] = $regPdtoProyecto->idproyecto;
        $pdtoProyecto_proyecto[$id_proyecto] = $regPdtoProyecto->proyecto;
        $pdtoProyecto_cdgproyecto[$id_proyecto] = $regPdtoProyecto->cdgproyecto; 
        
        $link_mysqli = conectar(); 
        $pdtoImpresionSelect = $link_mysqli->query("
          SELECT * FROM pdtoimpresion
          WHERE cdgproyecto = '".$regPdtoProyecto->cdgproyecto."'
          AND sttimpresion = '1'
          ORDER BY impresion,
            idimpresion");
        
        $id_impresion = 1;
        while ($regPdtoImpresion = $pdtoImpresionSelect->fetch_object()) 
        { $pdtoImpresion_idimpresion[$id_proyecto][$id_impresion] = $regPdtoImpresion->idimpresion;
          $pdtoImpresion_impresion[$id_proyecto][$id_impresion] = $regPdtoImpresion->impresion;
          $pdtoImpresion_ancho[$id_proyecto][$id_impresion] = $regPdtoImpresion->ancho;
          $pdtoImpresion_alpaso[$id_proyecto][$id_impresion] = $regPdtoImpresion->alpaso;
          $pdtoImpresion_ceja[$id_proyecto][$id_impresion] = $regPdtoImpresion->ceja;
          $pdtoImpresion_tolerancia[$id_proyecto][$id_impresion] = $regPdtoImpresion->tolerancia;      
          $pdtoImpresion_corte[$id_proyecto][$id_impresion] = $regPdtoImpresion->corte;
          $pdtoImpresion_cdgimpresion[$id_proyecto][$id_impresion] = $regPdtoImpresion->cdgimpresion;  

          $id_impresion++; }

        $numimpresiones[$id_proyecto] = $pdtoImpresionSelect->num_rows;
        $pdtoImpresionSelect->close;

        $id_proyecto++; } 

      $numproyectos = $pdtoProyectoSelect->num_rows;  
      $pdtoProyectoSelect->close; }

    if (substr($sistModulo_permiso,0,1) == 'r')
    { if ($_POST['chk_vertodos'])
      { $pdtoMezcla_vertodos = 'checked'; 
      
        $link_mysqli = conectar();
        $pdtoMezclaSelect = $link_mysqli->query("
          SELECT * FROM pdtomezcla
          WHERE cdgimpresion = '".$pdtoMezcla_cdgimpresion."'
          ORDER BY sttmezcla DESC,        
            idmezcla"); }
      else
      { $link_mysqli = conectar();
        $pdtoMezclaSelect = $link_mysqli->query("
          SELECT * FROM pdtomezcla
          WHERE cdgimpresion = '".$pdtoMezcla_cdgimpresion."'
          AND sttmezcla = '1'
          ORDER BY idmezcla"); }

      if ($pdtoMezclaSelect->num_rows > 0)
      { $id_mezcla = 1;
        while ($regPdtoMezcla = $pdtoMezclaSelect->fetch_object())
        { $pdtoMezclas_idmezcla[$id_mezcla] = $regPdtoMezcla->idmezcla;
          $pdtoMezclas_mezcla[$id_mezcla] = $regPdtoMezcla->mezcla;
          $pdtoMezclas_cdgmezcla[$id_mezcla] = $regPdtoMezcla->cdgmezcla;
          $pdtoMezclas_sttmezcla[$id_mezcla] = $regPdtoMezcla->sttmezcla; 

          $id_mezcla++; }

        $nummezclas = $pdtoMezclaSelect->num_rows; }

      $pdtoMezclaSelect->close; }  

    echo '
    <form id="frm_pdtomezcla" name="frm_pdtomezcla" method="POST" action="pdtoMezcla.php">
      <table align="center">
        <thead>
          <tr><th colspan="2" align="left">'.$sistModulo_modulo.'</th></tr>
        </thead>
        <tbody>
          <tr><td>
              <label for="lbl_cdgbloque"><a href="../sm_producto/pdtoImpresion.php?cdgimpresion='.$pdtoMezcla_cdgimpresion.'">Impresi&oacute;n</a></label><br/>
              <select style="width:160px" id="slc_cdgimpresion" name="slc_cdgimpresion" onchange="document.frm_pdtomezcla.submit()">
                <option value="">Selecciona una opcion</option>';
    
    for ($id_proyecto = 1; $id_proyecto <= $numproyectos; $id_proyecto++) 
    { echo '
                <optgroup label="'.$pdtoProyecto_idproyecto[$id_proyecto].'">';

      for ($id_impresion = 1; $id_impresion <= $numimpresiones[$id_proyecto]; $id_impresion++) 
      { echo '
                  <option value="'.$pdtoImpresion_cdgimpresion[$id_proyecto][$id_impresion].'"';
            
        if ($pdtoMezcla_cdgimpresion == $pdtoImpresion_cdgimpresion[$id_proyecto][$id_impresion]) 
        { echo ' selected="selected"'; }

        echo '>'.$pdtoImpresion_impresion[$id_proyecto][$id_impresion].' ('.$pdtoImpresion_idimpresion[$id_proyecto][$id_impresion].')</option>'; }

      echo '
                </optgroup>'; }                

    unset($pdtoImpresion_idimpresion);
    unset($pdtoImpresion_impresion);
    unset($pdtoImpresion_ancho);
    unset($pdtoImpresion_alpaso);
    unset($pdtoImpresion_ceja);
    unset($pdtoImpresion_tolerancia);
    unset($pdtoImpresion_corte);
    unset($pdtoImpresion_cdgimpresion);
    
    echo '
              </select></td>
            <td colspan="2">
              <label for="lbl_idmezcla">Mezcla</label><br/>
              <input type="text" style="width:100px;" maxlength="24" id="txt_idmezcla" name="txt_idmezcla" value="'.$pdtoMezcla_idmezcla.'" title="Identificador de mezcla por impresion" required/></td></tr>
          <tr><td colspan="2">
              <label for="lbl_mezcla">Descripci&oacute;n</label><br/>
              <input type="text" style="width:300px;" maxlength="80" id="txt_mezcla" name="txt_mezcla" value="'.$pdtoMezcla_mezcla.'" title="Descripcion de la mezcla" required/></td></tr>
        <tbody>
        <tfoot>
          <tr><td colspan="2" align="right"><input type="submit" id="btn_salvar" name="btn_salvar" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>

      <table align="center">
        <thead>
          <tr align="center">
            <td colspan="2"></td>
            <th colspan="4"><input type="checkbox" name="chk_vertodos" id="chk_vertodos" onclick="document.frm_pdtomezcla.submit()" '.$pdtoMezcla_vertodos.'>
              <label for="lbl_vertodo">Ver todo</label></th>
          <tr align="left">
            <th><label for="lbl_ttlidmezcla">Mezcla</label></th>
            <th><label for="lbl_ttlmezcla">Descripci&oacute;n</label></th>
            <th colspan="4"><label for="lbl_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';

    if ($nummezclas > 0)
    { for ($id_mezcla=1; $id_mezcla<=$nummezclas; $id_mezcla++)
      { echo '
          <tr align="center">
            <td align="left"><strong>'.$pdtoMezclas_idmezcla[$id_mezcla].'</strong></td>           
            <td align="left">'.$pdtoMezclas_mezcla[$id_mezcla].'</td>';

        if ((int)$pdtoMezclas_sttmezcla[$id_mezcla] > 0)
        { echo '
            <td><a href="pdtoMezcla.php?cdgmezcla='.$pdtoMezclas_cdgmezcla[$id_mezcla].'">'.$png_search.'</a></td>
            <td><a href="pdtoConsumo.php?cdgmezcla='.$pdtoMezclas_cdgmezcla[$id_mezcla].'">'.$png_puzzle.'</a></td>            
            <td><a href="pdtoMezclaImagen.php?cdgmezcla='.$pdtoMezclas_cdgmezcla[$id_mezcla].'">'.$png_camera.'</a></td>
            <td><a href="pdtoMezcla.php?cdgmezcla='.$pdtoMezclas_cdgmezcla[$id_mezcla].'&proceso=update">'.$png_power_blue.'</a></td>'; }
        else
         { echo '
            <td><a href="pdtoMezcla.php?cdgmezcla='.$pdtoMezclas_cdgmezcla[$id_mezcla].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td colspan="2">&nbsp;</td>
            <td><a href="pdtoMezcla.php?cdgmezcla='.$pdtoMezclas_cdgmezcla[$id_mezcla].'&proceso=update">'.$png_power_black.'</a></td>'; }

        echo '</tr>';
      }      
    }
  
    unset($pdtoMezclas_idmezcla);
    unset($pdtoMezclas_mezcla);
    unset($pdtoMezclas_cdgmezcla);
    unset($pdtoMezclas_sttmezcla);

    echo '
        </tbody>
        <tfoot>
          <tr><th colspan="6" align="right">              
              <label for="lbl_ppgdatos">['.$nummezclas.'] Registros encontrados</label></th></tr>
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