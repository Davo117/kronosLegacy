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

    $pdtoProyecto_idproyecto = trim($_POST['txt_idproyecto']);
    $pdtoProyecto_proyecto = trim($_POST['txt_proyecto']);  
    
    if ($_GET['cdgproyecto'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $link_mysqli = conectar();
        $pdtoProyectoSelect = $link_mysqli->query("
          SELECT * FROM pdtoproyecto
          WHERE cdgproyecto = '".$_GET['cdgproyecto']."'");
        
        if ($pdtoProyectoSelect->num_rows > 0)
        { $regPdtoProyecto = $pdtoProyectoSelect->fetch_object();

          $pdtoProyecto_idproyecto = $regPdtoProyecto->idproyecto;
          $pdtoProyecto_proyecto = $regPdtoProyecto->proyecto;      
          $pdtoProyecto_cdgproyecto = $regPdtoProyecto->cdgproyecto;
          $pdtoProyecto_sttproyecto = $regPdtoProyecto->sttproyecto;

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($pdtoProyecto_sttproyecto == '1')
              { $pdtoProyecto_newsttproyecto = '0'; }
            
              if ($pdtoProyecto_sttproyecto == '0')
              { $pdtoProyecto_newsttproyecto = '1'; }
              
              $link_mysqli = conectar();
              $link_mysqli->query("
                UPDATE pdtodiseno
                SET sttdiseno = '".$pdtoProyecto_newsttproyecto."' 
                WHERE cdgdiseno = '".$pdtoProyecto_cdgproyecto."'");

              $link_mysqli = conectar();
              $link_mysqli->query("
                UPDATE pdtoproyecto
                SET sttproyecto = '".$pdtoProyecto_newsttproyecto."' 
                WHERE cdgproyecto = '".$pdtoProyecto_cdgproyecto."'");
                
              if ($link_mysqli->affected_rows > 0)
              { $msg_alert = 'El proyecto fue actualizado en su status.'; }
              else
              { $msg_alert = 'El proyecto NO fue actualizado (status).'; }
            } else
            { $msg_alert = $msg_norewrite; }
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $link_mysqli = conectar();
              $pdtoImpresionSelect = $link_mysqli->query("
                SELECT * FROM pdtoimpresion
                WHERE cdgproyecto = '".$pdtoProyecto_cdgproyecto."'");

              if ($pdtoImpresionSelect->num_rows > 0)
              { $msg_alert = 'El proyecto no esta vacío, tiene impresiones ligadas y no pudo ser eliminado.'; }
              else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  DELETE FROM pdtodiseno
                  WHERE cdgdiseno = '".$pdtoProyecto_cdgproyecto."'
                  AND sttdiseno = '0'");

                $link_mysqli = conectar();
                $link_mysqli->query("
                  DELETE FROM pdtoproyecto
                  WHERE cdgproyecto = '".$pdtoProyecto_cdgproyecto."'
                  AND sttproyecto = '0'");                
                  
                if ($link_mysqli->affected_rows > 0)
                { $msg_alert = 'El proyecto fue eliminado con exito.'; }
                else
                { $msg_alert = 'El proyecto NO fue eliminado.'; }
              } 

              $pdtoImpresionSelect->close;
            } else
            { $msg_alert = $msg_nodelete; } 
          }
        }

        $pdtoProyectoSelect->close;
      } else
      { $msg_alert = $msg_noread; }
    } 

    if ($_POST['btn_submit'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if (strlen($pdtoProyecto_idproyecto) > 0 AND strlen($pdtoProyecto_proyecto) > 0)
        { $link_mysqli = conectar();
          $pdtoProyectoSelect = $link_mysqli->query("
            SELECT * FROM pdtoproyecto
            WHERE idproyecto = '".$pdtoProyecto_idproyecto."'");
            
          if ($pdtoProyectoSelect->num_rows > 0)
          {	$regPdtoProyecto = $pdtoProyectoSelect->fetch_object();
            
            $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE pdtodiseno
              SET diseno = '".$pdtoProyecto_proyecto."'
              WHERE cdgdiseno = '".$regPdtoProyecto->cdgproyecto."'
              AND sttdiseno = '1'");            

            $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE pdtoproyecto
              SET proyecto = '".$pdtoProyecto_proyecto."'
              WHERE cdgproyecto = '".$regPdtoProyecto->cdgproyecto."'
              AND sttproyecto = '1'");
              
            if ($link_mysqli->affected_rows > 0) 
            { $msg_alert = 'El proyecto fue actualizado con exito.'; }
            else
            { $msg_alert = 'El proyecto NO fue actualizado.'; }
          }
          else
          { for ($cdgproyecto = 1; $cdgproyecto <= 1000; $cdgproyecto++)
            { $pdtoProyecto_cdgproyecto = str_pad($cdgproyecto,3,'0',STR_PAD_LEFT);
              
              if ($cdgproyecto > 999)
              { $msg_alert = 'El proyecto NO fue insertado, se ha alcanzado el tope de proyectos.'; }
              else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  INSERT INTO pdtodiseno
                    (iddiseno, diseno, cdgdiseno)
                  VALUES
                    ('".$pdtoProyecto_idproyecto."', '".$pdtoProyecto_proyecto."', '".$pdtoProyecto_cdgproyecto."')");

                $link_mysqli = conectar();
                $link_mysqli->query("
                  INSERT INTO pdtoproyecto
                    (idproyecto, proyecto, cdgproyecto)
                  VALUES
                    ('".$pdtoProyecto_idproyecto."', '".$pdtoProyecto_proyecto."', '".$pdtoProyecto_cdgproyecto."')");
                
                if ($link_mysqli->affected_rows > 0) 
                { $msg_alert = 'El proyecto fue insertado con exito.';
                  $cdgproyecto = 1000; }
              }
            }
          }

          $pdtoProyectoSelect->close; }
      } else
      { $msg_alert = $msg_norewrite; }
    }
    
    if (substr($sistModulo_permiso,0,1) == 'r')
    { if ($_POST['chk_vertodos'])
      { $vertodo = 'checked'; 
        // Filtrado completo
        $link_mysqli = conectar();
        $pdtoProyectoSelect = $link_mysqli->query("
        SELECT * FROM pdtoproyecto
        ORDER BY sttproyecto DESC,        
          proyecto,
          idproyecto"); }
      else
      { // Buscar coincidencias
        $link_mysqli = conectar();
        $pdtoProyectoSelect = $link_mysqli->query("
        SELECT * FROM pdtoproyecto
        WHERE sttproyecto = '1'
        ORDER BY proyecto,
          idproyecto"); }
    
      if ($pdtoProyectoSelect->num_rows > 0)
      { $id_proyecto = 1;
        while ($regPdtoProyecto = $pdtoProyectoSelect->fetch_object())
        { $pdtoProyectos_idproyecto[$id_proyecto] = $regPdtoProyecto->idproyecto;
          $pdtoProyectos_proyecto[$id_proyecto] = $regPdtoProyecto->proyecto;
          $pdtoProyectos_cdgproyecto[$id_proyecto] = $regPdtoProyecto->cdgproyecto;
          $pdtoProyectos_sttproyecto[$id_proyecto] = $regPdtoProyecto->sttproyecto; 

          $id_proyecto++; }

        $numproyectos = $pdtoProyectoSelect->num_rows; }
        
      $pdtoProyectoSelect->close; }

    echo '
    <form id="frm_pdtoproyecto" name="frm_pdtoproyecto" method="POST" action="pdtoProyecto.php">
      <table align="center">
        <thead>
          <tr><th align="left">'.$sistModulo_modulo.'</th></tr>
        </thead>
        <tbody>
          <tr><td>
              <label for="lbl_idproyecto">Dise&ntilde;o</label><br/>
              <input type="text" style="width:140px;" maxlength="24" id="txt_idproyecto" name="txt_idproyecto" value="'.$pdtoProyecto_idproyecto.'" title="Identificador de proyecto" autofocus="autofocus" required/></td></tr>
          <tr><td>
              <label for="lbl_proyecto">Descripci&oacute;n</label><br/>
              <input type="text" style="width:320px;" maxlength="80" id="txt_proyecto" name="txt_proyecto" value="'.$pdtoProyecto_proyecto.'" title="Descripcion del proyecto" required/></td></tr>
        <tbody>
        <tfoot>
          <tr><td align="right"><input type="submit" id="btn_submit" name="btn_submit" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>

      <table align="center">
        <thead>
          <tr><td colspan="2"></td>
            <th colspan="3" align="right">
              <input type="checkbox" name="chk_vertodos" id="chk_vertodos" onclick="document.frm_pdtoproyecto.submit()" '.$vertodo.'>
              <label for="lbl_vertodo">Ver todo</label></th></tr>
          <tr align="left">
            <th><label for="lbl_ttlproyecto">Dise&ntilde;o</label></th>
            <th><label for="lbl_ttlrefproyecto">Descripci&oacute;n</label></th>            
            <th colspan="3"><label for="lbl_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';

    if ($numproyectos > 0)
    { for ($id_proyecto=1; $id_proyecto<=$numproyectos; $id_proyecto++)
      { echo '
          <tr align="center">
            <td align="left"><strong>'.$pdtoProyectos_idproyecto[$id_proyecto].'</strong></td>
            <td align="left">'.$pdtoProyectos_proyecto[$id_proyecto].'</td>';

        if ((int)$pdtoProyectos_sttproyecto[$id_proyecto] > 0)
        { echo '
            <td><a href="pdtoProyecto.php?cdgproyecto='.$pdtoProyectos_cdgproyecto[$id_proyecto].'">'.$png_search.'</a></td>
            <td><a href="pdtoImpresion.php?cdgproyecto='.$pdtoProyectos_cdgproyecto[$id_proyecto].'">'.$png_link.'</a></td>            
            <td><a href="pdtoProyecto.php?cdgproyecto='.$pdtoProyectos_cdgproyecto[$id_proyecto].'&proceso=update">'.$png_power_blue.'</a></td>'; }
        else
         { echo '
            <td><a href="pdtoProyecto.php?cdgproyecto='.$pdtoProyectos_cdgproyecto[$id_proyecto].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td>&nbsp;</td>
            <td><a href="pdtoProyecto.php?cdgproyecto='.$pdtoProyectos_cdgproyecto[$id_proyecto].'&proceso=update">'.$png_power_black.'</a></td>'; }

        echo '</tr>';
      }      
    }
    
    unset($pdtoProyectos_idproyecto);
    unset($pdtoProyectos_proyecto);
    unset($pdtoProyectos_cdgproyecto);
    unset($pdtoProyectos_sttproyecto);

    echo '
        </tbody>
        <tfoot>
          <tr><th colspan="5" align="right">
              <label for="lbl_ppgdatos">['.$numproyectos.'] Registros encontrados</label></th></tr>
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
