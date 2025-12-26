<?php
  
  echo '<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/>';

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '20010';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);
    
    $pdtoDiseno_iddiseno = trim($_POST['txt_iddiseno']);
    $pdtoDiseno_diseno = trim($_POST['txt_diseno']);
    $pdtoDiseno_proyecto = trim($_POST['txt_proyecto']);
    $pdtoDiseno_notintas = trim($_POST['txt_notintas']);
    
    if ($_GET['cdgdiseno'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $link_mysqli = conectar();
        $pdtoDisenoSelect = $link_mysqli->query("
          SELECT * FROM pdtodiseno
          WHERE cdgdiseno = '".$_GET['cdgdiseno']."'");

        if ($pdtoDisenoSelect->num_rows > 0)
        { $regPdtoDiseno = $pdtoDisenoSelect->fetch_object();

          $pdtoDiseno_iddiseno = $regPdtoDiseno->iddiseno;
          $pdtoDiseno_diseno = $regPdtoDiseno->diseno;
          $pdtoDiseno_proyecto = $regPdtoDiseno->proyecto;
          $pdtoDiseno_notintas = $regPdtoDiseno->notintas;
          $pdtoDiseno_cdgdiseno = $regPdtoDiseno->cdgdiseno;
          $pdtoDiseno_sttdiseno = $regPdtoDiseno->sttdiseno;

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($pdtoDiseno_sttdiseno == '1')
              { $pdtoDiseno_newsttdiseno = '0'; }

              if ($pdtoDiseno_sttdiseno == '0')
              { $pdtoDiseno_newsttdiseno = '1'; }

              $link_mysqli = conectar();
              $link_mysqli->query("
                UPDATE pdtodiseno
                SET sttdiseno = '".$pdtoDiseno_newsttdiseno."'
                WHERE cdgdiseno = '".$pdtoDiseno_cdgdiseno."'");

              if ($link_mysqli->affected_rows > 0)
              { $msg_windows = 'El diseno fue actualizado en su status.'; }
              else
              { $msg_windows = 'El diseno NO fue actualizado (status).'; }
            } else
            { $msg_windows = $msg_norewrite; }
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $link_mysqli = conectar();
              $pdtoImpresionSelect = $link_mysqli->query("
                SELECT * FROM pdtoimpresion
                WHERE cdgdiseno = '".$pdtoDiseno_cdgdiseno."'");

              if ($pdtoImpresionSelect->num_rows > 0)
              { $msg_windows = 'El diseno no esta vacío, tiene impresiones ligadas y no pudo ser eliminado.'; }
              else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  DELETE FROM pdtodiseno
                  WHERE cdgdiseno = '".$pdtoDiseno_cdgdiseno."'
                  AND sttdiseno = '0'");

                if ($link_mysqli->affected_rows > 0)
                { $msg_windows = 'El diseno fue eliminado con exito.'; }
                else
                { $msg_windows = 'El diseno NO fue eliminado.'; }
              }
            } 
            else
            { $msg_windows = $msg_nodelete; }
          }
        }
      } 
      else
      { $msg_windows = $msg_noread; }
    }

    if ($_POST['submit_submit'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if (strlen($pdtoDiseno_iddiseno) > 0 AND strlen($pdtoDiseno_diseno) > 0)
        { $link_mysqli = conectar();
          $pdtoDisenoSelect = $link_mysqli->query("
            SELECT * FROM pdtodiseno
            WHERE iddiseno = '".$pdtoDiseno_iddiseno."'");

          if ($pdtoDisenoSelect->num_rows > 0)
          {	$regPdtoDiseno = $pdtoDisenoSelect->fetch_object();

            $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE pdtodiseno
              SET diseno = '".$pdtoDiseno_diseno."',
                proyecto = '".$pdtoDiseno_proyecto."',
                notintas = '".$pdtoDiseno_notintas."'
              WHERE cdgdiseno = '".$regPdtoDiseno->cdgdiseno."'
              AND sttdiseno = '1'");
          
            if ($link_mysqli->affected_rows > 0)
            { $msg_windows .= 'El diseno fue actualizado con exito.'; }
            else
            { $msg_windows .= 'El diseno NO fue actualizado.'; }
          }
          else
          { for ($cdgdiseno = 1; $cdgdiseno <= 1000; $cdgdiseno++)
            { $pdtoDiseno_cdgdiseno = str_pad($cdgdiseno,3,'0',STR_PAD_LEFT);

              if ($cdgdiseno > 999)
              { $msg_windows = 'El diseno NO fue insertado, se ha alcanzado el tope de disenos.'; }
              else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  INSERT INTO pdtodiseno
                    (iddiseno, diseno, proyecto, notintas, cdgdiseno)
                  VALUES
                    ('".$pdtoDiseno_iddiseno."', '".$pdtoDiseno_diseno."', '".$pdtoDiseno_proyecto."', '".$pdtoDiseno_notintas."','".$pdtoDiseno_cdgdiseno."')");
              }
            }
          }

        }
      } 
      else
      { $msg_windows = $msg_norewrite; }
    }

    if (substr($sistModulo_permiso,0,1) == 'r')
    { if ($_POST['checkbox_vertodos'])
      { $vertodo = 'checked';
        // Filtrado completo
        $link_mysqli = conectar();
        $pdtoDisenoSelect = $link_mysqli->query("
        SELECT * FROM pdtodiseno
        ORDER BY sttdiseno DESC,
          iddiseno,
          diseno"); }
      else
      { // Buscar coincidencias
        $link_mysqli = conectar();
        $pdtoDisenoSelect = $link_mysqli->query("
        SELECT * FROM pdtodiseno
        WHERE sttdiseno = '1'
        ORDER BY iddiseno,
          diseno"); }

      if ($pdtoDisenoSelect->num_rows > 0)
      { $id_diseno = 1;
        while ($regPdtoDiseno = $pdtoDisenoSelect->fetch_object())
        { $pdtoDisenos_iddiseno[$id_diseno] = $regPdtoDiseno->iddiseno;
          $pdtoDisenos_diseno[$id_diseno] = $regPdtoDiseno->diseno;
          $pdtoDisenos_proyecto[$id_diseno] = $regPdtoDiseno->proyecto;
          $pdtoDisenos_notintas[$id_diseno] = $regPdtoDiseno->notintas;
          $pdtoDisenos_cdgdiseno[$id_diseno] = $regPdtoDiseno->cdgdiseno;
          $pdtoDisenos_sttdiseno[$id_diseno] = $regPdtoDiseno->sttdiseno;

          $id_diseno++; }

        $numDisenos = $pdtoDisenoSelect->num_rows; 
      }
    }

    echo '
    <form id="frm_pdtodiseno" name="frm_pdtodiseno" method="POST" action="pdtoDiseno.php">
      <table align="center">
        <thead>
          <tr><th colspan="2" align="left">'.$sistModulo_modulo.'</th></tr>
        </thead>
        <tbody>
          <tr><td><label for="lbl_iddiseno">Dise&ntilde;o</label><br/>
              <input type="text" style="width:120px;" maxlength="24" id="txt_iddiseno" name="txt_iddiseno" value="'.$pdtoDiseno_iddiseno.'" title="Identificador de dise&nacute;o" autofocus="autofocus" required/></td>
            <td><label for="lbl_proyecto">Proyecto</label><br/>
              <input type="text" style="width:120px;" maxlength="24" id="txt_proyecto" name="txt_proyecto" value="'.$pdtoDiseno_proyecto.'" title="Proyecto al que pertenece" required/></td></tr>
          <tr><td colspan="2">
              <label for="lbl_diseno">Descripci&oacute;n</label><br/>
              <input type="text" style="width:256px;" maxlength="60" id="txt_diseno" name="txt_diseno" value="'.$pdtoDiseno_diseno.'" title="Descripcion del diseno" required/></td></tr>
          <tr><td><label for="lbl_sustrato">No. Tintas</label><br/>
              <input type="text" style="width:65px;" maxlength="12" id="txt_notintas" name="txt_notintas" value="'.$pdtoDiseno_notintas.'" title="Número de tintas" required/></td>
            <td><label for="lbl_holograma">Holograma</label><br/>
              <input type="radio" name="holograma">Si
              <input type="radio" name="holograma">No</td></tr>
        <tbody>
        <tfoot>
          <tr><td colspan="2" align="right"><input type="submit" id="submit_submit" name="submit_submit" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>

      <table align="center">
        <thead>
          <tr><td colspan="3"></td>
            <th colspan="3" align="right">
              <input type="checkbox" name="checkbox_vertodos" id="checkbox_vertodos" onclick="document.frm_pdtodiseno.submit()" '.$vertodo.'>
              <label for="lbl_vertodo">Ver todo</label></th></tr>
          <tr align="left">
            <th><label for="lbl_ttldiseno">Dise&ntilde;o</label></th>
            <th><label for="lbl_ttlrefdiseno">Descripci&oacute;n</label></th>
            <th><label for="lbl_ttlnotintas">No. Tintas</label></th>
            <th colspan="3"><label for="lbl_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';

    if ($numDisenos > 0)
    { for ($idDiseno=1; $idDiseno<=$numDisenos; $idDiseno++)
      { echo '
          <tr align="center">
            <td align="left"><strong>'.$pdtoDisenos_iddiseno[$idDiseno].'</strong></td>
            <td align="left">'.$pdtoDisenos_diseno[$idDiseno].'</td>
            <td align="left">'.$pdtoDisenos_notintas[$idDiseno].'</td>';

        if ((int)$pdtoDisenos_sttdiseno[$idDiseno] > 0)
        { echo '
            <td><a href="pdtoDiseno.php?cdgdiseno='.$pdtoDisenos_cdgdiseno[$idDiseno].'">'.$png_search.'</a></td>
            <td><a href="pdtoImpresion.php?cdgdiseno='.$pdtoDisenos_cdgdiseno[$idDiseno].'">'.$png_link.'</a></td>
            <td><a href="pdtoDiseno.php?cdgdiseno='.$pdtoDisenos_cdgdiseno[$idDiseno].'&proceso=update">'.$png_power_blue.'</a></td>'; }
        else
         { echo '
            <td><a href="pdtoDiseno.php?cdgdiseno='.$pdtoDisenos_cdgdiseno[$idDiseno].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td>&nbsp;</td>
            <td><a href="pdtoDiseno.php?cdgdiseno='.$pdtoDisenos_cdgdiseno[$idDiseno].'&proceso=update">'.$png_power_black.'</a></td>'; }

        echo '</tr>';
      }
    }

    echo '
        </tbody>
        <tfoot>
          <tr><th colspan="6" align="right">
              <label for="lbl_ppgdatos">['.$numDisenos.'] Registros encontrados</label></th></tr>
        </tfoot>
      </table>
    </form>';

    if ($msg_windows != '')
    { echo '
    <script type="text/javascript"> alert("'.$msg_windows.'"); </script>'; }
  } else
  { echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }

  echo '
  </body>
</html>';
?>