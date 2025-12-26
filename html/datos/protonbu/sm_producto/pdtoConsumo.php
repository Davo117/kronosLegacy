<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '20040';

  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);    

    $pdtoConsumo_cdgmezcla = $_POST['slc_cdgmezcla'];
    $pdtoConsumo_cdgproceso = trim($_POST['slc_cdgproceso']);
    $pdtoConsumo_cdgmateria = $_POST['slc_cdgmateria'];
    $pdtoConsumo_consumo = trim($_POST['txt_consumo']);

    if ($_GET['cdgmezcla'])
    { $pdtoConsumo_cdgmezcla = $_GET['cdgmezcla']; }

    if ($_GET['cdgelemento'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      {   
        $link_mysqli = conectar();
        $pdtoConsumoSelect = $link_mysqli->query("
          SELECT * FROM pdtoconsumo
          WHERE cdgelemento = '".$_GET['cdgelemento']."'");
        
        if ($pdtoConsumoSelect->num_rows > 0)
        { $regPdtoConsumo = $pdtoConsumoSelect->fetch_object();

          $pdtoConsumo_cdgmezcla = $regPdtoConsumo->cdgmezcla;
          $pdtoConsumo_cdgproceso = $regPdtoConsumo->cdgproceso;
          $pdtoConsumo_cdgmateria = $regPdtoConsumo->cdgmateria;
          $pdtoConsumo_consumo = $regPdtoConsumo->consumo;
          $pdtoConsumo_cdgelemento = $regPdtoConsumo->cdgelemento;

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $link_mysqli = conectar();
              $link_mysqli->query("
                DELETE FROM pdtoconsumo
                WHERE cdgelemento = '".$pdtoConsumo_cdgelemento."'");
                  
              if ($link_mysqli->affected_rows > 0)
              { $msg_alert = 'El consumo fue eliminado con exito.'; }
              else
              { $msg_alert = 'El consumo no fue eliminado.'; }
            } else
            { $msg_alert = $msg_nodelete; }             
          }
        }
      } else
      { $msg_alert = $msg_noread; } 
    } 

    if ($_POST['btn_submit'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if (strlen($pdtoConsumo_cdgmezcla) > 0 AND strlen($pdtoConsumo_cdgproceso) > 0 AND strlen($pdtoConsumo_cdgmateria) > 0)
        { $link_mysqli = conectar();
          $pdtoConsumoSelect = $link_mysqli->query("
            SELECT * FROM pdtoconsumo
            WHERE cdgmezcla = '".$pdtoConsumo_cdgmezcla."' 
            AND cdgproceso = '".$pdtoConsumo_cdgproceso."'
            AND cdgmateria = '".$pdtoConsumo_cdgmateria."'");
            
          if ($pdtoConsumoSelect->num_rows > 0)
          {	$regPdtoConsumo = $pdtoConsumoSelect->fetch_object();
            
            $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE pdtoconsumo
              SET consumo = '".$pdtoConsumo_consumo."'            
              WHERE cdgelemento = '".$regPdtoConsumo->cdgelemento."'");
              
            if ($link_mysqli->affected_rows > 0) 
            { $msg_alert = 'El consumo fue actualizado con exito.'; }
          }
          else
          { for ($cdgelemento = 1; $cdgelemento <= 1000; $cdgelemento++)
            { $pdtoConsumo_cdgelemento = $pdtoConsumo_cdgmezcla.str_pad($cdgelemento,3,'0',STR_PAD_LEFT);
              
              if ($cdgmezcla > 999)
              { $msg_alert = 'El consumo NO fue insertado, se ha alcanzado el tope de consumos por mezcla.'; }
              else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  INSERT INTO pdtoconsumo
                    (cdgmezcla, cdgproceso, cdgmateria, consumo, cdgelemento)
                  VALUES
                    ('".$pdtoConsumo_cdgmezcla."', '".$pdtoConsumo_cdgproceso."', '".$pdtoConsumo_cdgmateria."', '".$pdtoConsumo_consumo."', '".$pdtoConsumo_cdgelemento."')");
              
                if ($link_mysqli->affected_rows > 0) 
                { $msg_alert = 'El consumo fue insertado con exito.'; 
                  $cdgelemento = 1001; }
              }
            }        
          }
        }
      } else
      { $msg_alert = $msg_norewrite; }         
    }
     // Proyectos
    ////////////////
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

         // Impresiones
        ////////////////        
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
          $pdtoImpresion_cdgimpresion[$id_proyecto][$id_impresion] = $regPdtoImpresion->cdgimpresion;  

           // Mezclas
          ////////////////
          $link_mysqli = conectar(); 
          $pdtoMezclaSelect = $link_mysqli->query("
            SELECT * FROM pdtomezcla
            WHERE cdgimpresion = '".$regPdtoImpresion->cdgimpresion."'
            AND sttmezcla = '1'
            ORDER BY mezcla,
              idmezcla");
          
          $id_mezcla = 1;
          while ($regPdtoMezcla = $pdtoMezclaSelect->fetch_object()) 
          { $pdtoMezcla_idmezcla[$id_proyecto][$id_impresion][$id_mezcla] = $regPdtoMezcla->idmezcla;
            $pdtoMezcla_mezcla[$id_proyecto][$id_impresion][$id_mezcla] = $regPdtoMezcla->mezcla;
            $pdtoMezcla_cdgmezcla[$id_proyecto][$id_impresion][$id_mezcla] = $regPdtoMezcla->cdgmezcla;

            $id_mezcla++; }

          $nummezclas[$id_proyecto][$id_impresion] = $pdtoMezclaSelect->num_rows;
          $pdtoMezclaSelect->close;

          $id_impresion++; }

        $numimpresiones[$id_proyecto] = $pdtoImpresionSelect->num_rows;
        $pdtoImpresionSelect->close;

        $id_proyecto++; } 

      $numproyectos = $pdtoProyectoSelect->num_rows;  
      $pdtoProyectoSelect->close; }
    
     // Procesos
    ////////////////
    if (substr($sistModulo_permiso,0,1) == 'r')
    { $link_mysqli = conectar(); 
      $prodProcesoSelect = $link_mysqli->query("
        SELECT * FROM prodproceso
        ORDER BY cdgproceso");
      
      $id_proceso = 1; 
      while ($regProdProceso = $prodProcesoSelect->fetch_object())
      { $prodProceso_idproceso[$id_proceso] = $regProdProceso->idproceso;
        $prodProceso_proceso[$id_proceso] = $regProdProceso->proceso;
        $prodProceso_cdgproceso[$id_proceso] = $regProdProceso->cdgproceso; 

        $id_proceso++; }  

      $numprocesos = $prodProcesoSelect->num_rows;
      $prodProcesoSelect->close; }

     // Materias
    ////////////////
    if (substr($sistModulo_permiso,0,1) == 'r')
    { $link_mysqli = conectar();
      $pdtoMateriaSelect = $link_mysqli->query("
        SELECT pdtomateria.idmateria,
          pdtomateria.materia,
          pdtounidad.unidad,
          pdtomateria.cdgmateria 
        FROM pdtomateria,
          pdtounidad
        WHERE pdtomateria.cdgunidad = pdtounidad.cdgunidad
        AND pdtomateria.sttmateria = '1'
        ORDER BY pdtomateria.materia,
          pdtomateria.idmateria");

      $id_materia = 1;
      while ($regPdtoMateria = $pdtoMateriaSelect->fetch_object()) 
      { $pdtoMateria_idmateria[$id_materia] = $regPdtoMateria->idmateria;
        $pdtoMateria_materia[$id_materia] = $regPdtoMateria->materia;
        $pdtoMateria_unidad[$id_materia] = $regPdtoMateria->unidad;
        $pdtoMateria_cdgmateria[$id_materia] = $regPdtoMateria->cdgmateria; 

         $id_materia++; }

      $nummaterias = $pdtoMateriaSelect->num_rows;
      $pdtoMateriaSelect->close; }  

     // Consumos
    ////////////////
    if (substr($sistModulo_permiso,0,1) == 'r')
    { $link_mysqli = conectar();
      $pdtoConsumoSelect = $link_mysqli->query("
        SELECT prodproceso.proceso,
          pdtomateria.materia,
          pdtoconsumo.consumo,
          pdtounidad.unidad,
          pdtoconsumo.cdgelemento
        FROM prodproceso,
        pdtomateria,
        pdtoconsumo,
        pdtounidad
        WHERE prodproceso.cdgproceso = pdtoconsumo.cdgproceso
        AND pdtomateria.cdgmateria = pdtoconsumo.cdgmateria
        AND pdtounidad.cdgunidad = pdtomateria.cdgunidad
        AND pdtoconsumo.cdgmezcla = '".$pdtoConsumo_cdgmezcla."'
        ORDER BY pdtoconsumo.cdgproceso,
          pdtomateria.materia,
          pdtounidad.unidad");

      $id_consumo = 1;
      if ($pdtoConsumoSelect->num_rows > 0)
      { while ($regPdtoConsumo = $pdtoConsumoSelect->fetch_object())
        { $pdtoConsumos_proceso[$id_consumo] = $regPdtoConsumo->proceso;
          $pdtoConsumos_consumo[$id_consumo] = $regPdtoConsumo->consumo;    
          $pdtoConsumos_unidad[$id_consumo] = $regPdtoConsumo->unidad;
          $pdtoConsumos_materia[$id_consumo] = $regPdtoConsumo->materia;
          $pdtoConsumos_cdgelemento[$id_consumo] = $regPdtoConsumo->cdgelemento;    

          $id_consumo++; }

        $numconsumos = $pdtoConsumoSelect->num_rows; }

      $pdtoConsumoSelect->close; } 

      echo '
    <form id="frm_pdtoconsumo" name="frm_pdtoconsumo" method="POST" action="pdtoConsumo.php">
      <table align="center">
        <thead>
          <tr><th colspan="2" align="left">'.$sistModulo_modulo.'</th></tr>
        </thead>
        <tbody>
          <tr><td colspan="2">
              <label for="lbl_cdgmezcla"><a href="pdtoMezcla.php?cdgmezcla='.$pdtoConsumo_cdgmezcla.'">Mezcla</a></label><br/>
              <select style="width:240px" id="slc_cdgmezcla" name="slc_cdgmezcla" onchange="document.frm_pdtoconsumo.submit()">';
    
      for ($id_proyecto = 1; $id_proyecto <= $numproyectos; $id_proyecto++) 
      { echo '
                <optgroup label="'.$pdtoProyecto_idproyecto[$id_proyecto].'">';
                
        for ($id_impresion = 1; $id_impresion <= $numimpresiones[$id_proyecto]; $id_impresion++) 
        { for ($id_mezcla = 1; $id_mezcla <= $nummezclas[$id_proyecto][$id_impresion]; $id_mezcla++) 
          { echo '
                  <option value="'.$pdtoMezcla_cdgmezcla[$id_proyecto][$id_impresion][$id_mezcla].'"';
            
            if ($pdtoConsumo_cdgmezcla == $pdtoMezcla_cdgmezcla[$id_proyecto][$id_impresion][$id_mezcla]) 
            { echo ' selected="selected"'; }

          echo '>'.$pdtoImpresion_impresion[$id_proyecto][$id_impresion].' : '.$pdtoMezcla_mezcla[$id_proyecto][$id_impresion][$id_mezcla].' ('.$pdtoMezcla_idmezcla[$id_proyecto][$id_impresion][$id_mezcla].')</option>'; }
        }

        echo '
                </optgroup>'; }     
    
      unset($pdtoProyecto_idproyecto);
      unset($pdtoProyecto_proyecto);
      unset($pdtoProyecto_cdgproyecto);

      unset($pdtoImpresion_idimpresion);
      unset($pdtoImpresion_impresion);
      unset($pdtoImpresion_cdgimpresion);

      unset($pdtoMezcla_idmezcla);
      unset($pdtoMezcla_mezcla);
      unset($pdtoMezcla_cdgmezcla);

      echo '
              </select></td></tr>
          <tr><td colspan="2">
              <label for="lbl_cdgproceso"><a href="../sm_produccion/prodProceso.php?cdgproceso='.$pdtoConsumo_cdgproceso.'">Proceso</a></label><br/>
              <select style="width:240px" id="slc_cdgproceso" name="slc_cdgproceso" onchange="document.frm_pdtoconsumo.submit()">';
    
      for ($id_proceso = 1; $id_proceso <= $numprocesos; $id_proceso++) 
      { echo '
                <option value="'.$prodProceso_cdgproceso[$id_proceso].'"';
            
        if ($pdtoConsumo_cdgproceso == $prodProceso_cdgproceso[$id_proceso]) 
        { echo ' selected="selected"'; }

        echo '>'.$prodProceso_proceso[$id_proceso].' ('.$prodProceso_idproceso[$id_proceso].')</option>'; }
      
      unset($prodProceso_idproceso);
      unset($prodProceso_proceso);
      unset($prodProceso_cdgproceso);

      echo '
              </select></td></tr>             
          <tr><td colspan="2">
              <label for="lbl_cdgmateria"><a href="pdtoMateria.php?cdgmateria='.$pdtoConsumo_cdgmateria.'">Materia prima</a></label><br/>
              <select style="width:240px" id="slc_cdgmateria" name="slc_cdgmateria" onchange="document.frm_pdtoconsumo.submit()">';
    
      for ($id_materia = 1; $id_materia <= $nummaterias; $id_materia++) 
      { echo '
                <option value="'.$pdtoMateria_cdgmateria[$id_materia].'"';
            
        if ($pdtoConsumo_cdgmateria == $pdtoMateria_cdgmateria[$id_materia]) 
        { $pdtoConsumo_ttlunidad = $pdtoMateria_unidad[$id_materia];
          echo ' selected="selected"'; }

        echo '>'.$pdtoMateria_materia[$id_materia].' ('.$pdtoMateria_idmateria[$id_materia].')</option>'; }       
      
      unset($pdtoMateria_idmateria);
      unset($pdtoMateria_materia);
      unset($pdtoMateria_unidad);
      unset($pdtoMateria_cdgmateria); 

      echo '
              </select></td></tr>
          <tr><td>
              <label for="lbl_programa">Consumo x millar</label><br/>
              <input type="text" style="width:100px; text-align:right;" maxlength="12" id="txt_consumo" name="txt_consumo" value="'.$pdtoConsumo_consumo.'" title="Consumo por metros longitudinales" required/></td>
            <td>
              <label for="lbl_idunidad">Unidad de medida</label><br/>
              <label for="lbl_unidad"><strong>'.$pdtoConsumo_ttlunidad.'</strong></label></td></tr>
        <tbody>
        <tfoot>
          <tr><td colspan="2" align="right"><input type="submit" id="btn_submit" name="btn_submit" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>
    
      <table align="center">
        <thead>
          <tr align="left">
            <th><label for="lbl_ttlprograma">Proceso</label></th>            
            <th colspan="3"><label for="lbl_ttlproyecto">Consumo por millar</label></th>
            <th colspan="2"><label for="lbl_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';

    if ($numconsumos > 0)
    { for ($id_consumo=1; $id_consumo<=$numconsumos; $id_consumo++)
      { echo '
          <tr align="center">
            <td align="left"><strong>'.$pdtoConsumos_proceso[$id_consumo].'</strong></td>
            <td align="right">'.number_format($pdtoConsumos_consumo[$id_consumo],5).'</td>
            <td align="left">'.$pdtoConsumos_unidad[$id_consumo].'</td>
            <td align="left">'.$pdtoConsumos_materia[$id_consumo].'</td>
            <td><a href="pdtoConsumo.php?cdgelemento='.$pdtoConsumos_cdgelemento[$id_consumo].'">'.$png_search.'</a></td>
            <td><a href="pdtoConsumo.php?cdgelemento='.$pdtoConsumos_cdgelemento[$id_consumo].'&proceso=delete">'.$png_recycle_bin.'</a></td>';

        echo '</tr>';
      }      
    }

    unset($pdtoConsumos_proceso);
    unset($pdtoConsumos_consumo);
    unset($pdtoConsumos_unidad);
    unset($pdtoConsumos_materia); 
    unset($pdtoConsumos_cdgelemento);

    echo '
        </tbody>
        <tfoot>
          <tr><th colspan="6" align="right">
              <label for="lbl_ppgdatos"> ['.$numconsumos.'] Registros encontrados</label></th></tr>
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