<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '80020';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    $alptEmpaque_idempaque = $_POST['text_idempaque'];
    $alptEmpaque_fchempaque = $_POST['text_fchempaque'];
    $alptEmpaque_empaque = $_POST['textarea_empaque'];

    if ($alptEmpaque_fchempaque == '')
    { $alptEmpaque_subcdgempaque = date('ymd'); 
      $alptEmpaque_fchempaque = date('Y-m-d'); 
    } else
    { $fchempaque = str_replace("-", "", $alptEmpaque_fchempaque);

      $dia = str_pad(substr($fchempaque,6,2),2,'0',STR_PAD_LEFT);
      $mes = str_pad(substr($fchempaque,4,2),2,'0',STR_PAD_LEFT);
      $ano = str_pad(substr($fchempaque,2,2),2,'0',STR_PAD_LEFT);

      if (checkdate((int)$mes,(int)$dia,(int)$ano))
      { $alptEmpaque_subcdgempaque = $ano.$mes.$dia;
        $alptEmpaque_fchempaque = '20'.$ano.'-'.$mes.'-'.$dia; 
      } else
      { $alptEmpaque_subcdgempaque = date('ymd'); 
        $alptEmpaque_fchempaque = date('Y-m-d'); }
    }

    if ($_GET['cdgempaque'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $alptEmpaque_cdgempaque = $_GET['cdgempaque'];
        
        $link_mysqli = conectar();
        $alptEmpaqueSelect = $link_mysqli->query("
          SELECT * FROM alptempaque
          WHERE cdgempaque = '".$alptEmpaque_cdgempaque."'");
          
        if ($alptEmpaqueSelect->num_rows > 0)
        { $regAlptEmpaque = $alptEmpaqueSelect->fetch_object();        
    
          $alptEmpaque_idempaque = $regAlptEmpaque->idempaque;
          $alptEmpaque_fchempaque = $regAlptEmpaque->fchempaque;
          $alptEmpaque_empaque = $regAlptEmpaque->empaque;
          $alptEmpaque_cdgempaque = $regAlptEmpaque->cdgempaque;
          $alptEmpaque_sttempaque = $regAlptEmpaque->sttempaque; 
          
          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($alptEmpaque_sttempaque == '0')
              { $alptEmpaque_newsttempaque = '1'; }
            
              if ($alptEmpaque_sttempaque == '1')
              { $alptEmpaque_newsttempaque = '0'; }
              
              if ($alptEmpaque_newsttempaque != '')
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE alptempaque
                  SET sttempaque = '".$alptEmpaque_newsttempaque."'
                  WHERE cdgempaque = '".$alptEmpaque_cdgempaque."'");
                
                if ($link_mysqli->affected_rows > 0)
                { $msg_alert = "El empaque '".$alptEmpaque_idempaque."' fue actualizado exitosamente en su status."; 
                } else
                { $msg_alert = "El empaque '".$alptEmpaque_idempaque."' NO fue actualizado en su status."; } 
              } else
              { $msg_alert = 'Este empaque no puede ser afectado en su status.'; }              
            } else
            { $msg_alert = $msg_norewrite; }
          }
          
          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $link_mysqli = conectar();
              $alptSubLoteSelect = $link_mysqli->query("
                SELECT * FROM alptsublote
                WHERE cdgempaque = '".$alptEmpaque_cdgempaque."'");
              
              if ($alptSubLoteSelect->num_rows > 0)  
              { $msg_alert = "El empaque '".$alptEmpaque_idempaque."' NO fue desechado por que no esta vacio."; 
              } else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  DELETE FROM alptempaque                  
                  WHERE (cdgempaque = '".$alptEmpaque_cdgempaque."' AND
                    sttempaque = '0')");
                  
                if ($link_mysqli->affected_rows > 0)
                { $msg_alert = "El empaque '".$alptEmpaque_idempaque."' fue desechado exitosamente."; 
                } else
                { $msg_alert = "El empaque '".$alptEmpaque_idempaque."' NO fue desechado."; }               
              }                
            } else
            { $msg_alert = $msg_nodelete; }
          }          
        } else
        { $msg_alert = 'El empaque al que se hace referencia no existe, o fue eliminado.'; }
      } else
      { $msg_alert = $msg_noread; }
    }

    if ($_POST['submit_salvar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if ($alptEmpaque_idempaque != '')
        { if ($alptEmpaque_fchempaque != '')
          { if ($alptEmpaque_empaque != '')
            { $link_mysqli = conectar();
              $alptEmpaqueSelect = $link_mysqli->query("
                SELECT * FROM alptempaque
                WHERE idempaque = '".$alptEmpaque_idempaque."'");
              
              if ($alptEmpaqueSelect->num_rows > 0)
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE alptempaque
                  SET empaque = '".$alptEmpaque_empaque."'
                  WHERE (idempaque = '".$alptEmpaque_idempaque."' AND
                    sttempaque = '1')");
                
                if ($link_mysqli->affected_rows > 0)
                { $msg_alert = "El empaque '".$alptEmpaque_idempaque."' fue actualizado exitosamente."; 
                } else
                { $msg_alert = "El empaque '".$alptEmpaque_idempaque."' NO fue actualizado."; }                  
              } else
              { for ($id_indice = 1; $id_indice <= 999; $id_indice++) 
                { $alptEmpaque_cdgempaque = $alptEmpaque_subcdgempaque.str_pad($id_indice,3,'0',STR_PAD_LEFT);
                
                  $link_mysqli = conectar();
                  $alptEmpaqueSelect = $link_mysqli->query("
                    SELECT * FROM alptempaque
                    WHERE cdgempaque = '".$alptEmpaque_cdgempaque."'");
                    
                  if ($alptEmpaqueSelect->num_rows == 0)
                  { $link_mysqli = conectar();
                    $link_mysqli->query("
                      INSERT INTO alptempaque
                        (idempaque, fchempaque, empaque, cdgempaque)
                      VALUES
                        ('".$alptEmpaque_idempaque."', '".$alptEmpaque_fchempaque."', '".$alptEmpaque_empaque."', '".$alptEmpaque_cdgempaque."')");
                        
                    if ($link_mysqli->affected_rows > 0)
                    { $msg_alert = "El empaque '".$alptEmpaque_idempaque."' fue insertado exitosamente."; }
                    else
                    { $msg_alert = "El empaque '".$alptEmpaque_idempaque."' NO fue insertado."; }
                    
                    $id_indice = 1000; }
                  
                  $alptEmpaqueSelect->close;          
                }                  
              }
            } else
            { $msg_alert = 'Es necesario indicar alguna descripcion o referencia del documeno.';
              $autofocus_empaque = 'autofocus'; }                  
          } else
          { $msg_alert = 'Es necesario la fecha del empaque.';
            $autofocus_fchempaque = 'autofocus'; }               
        } else
        { $msg_alert = 'Es necesario indicar el folio del empaque.';
          $autofocus_idempaque = 'autofocus'; }        
      } else
      { $msg_alert = $msg_nowrite; }      
    }

    if (substr($sistModulo_permiso,0,1) == 'r')
    { if ($_POST['checkbox_vertodo'])
      { $vertodo = 'checked';

        $link_mysqli = conectar();
        $alptEmpaqueSelect = $link_mysqli->query("
          SELECT * FROM alptempaque
          ORDER BY sttempaque DESC,
            fchempaque,            
            idempaque"); 
      } else
      { $link_mysqli = conectar();
        $alptEmpaqueSelect = $link_mysqli->query("
          SELECT * FROM alptempaque
          WHERE sttempaque = '1'
          ORDER BY fchempaque,          
            idempaque"); }

      if ($alptEmpaqueSelect->num_rows > 0)
      { $id_empaque = 1;
        while ($regAlptEmpaque = $alptEmpaqueSelect->fetch_object())
        { $alptEmpaques_idempaque[$id_empaque] = $regAlptEmpaque->idempaque;
          $alptEmpaques_fchempaque[$id_empaque] = $regAlptEmpaque->fchempaque;          
          $alptEmpaques_cdgempaque[$id_empaque] = $regAlptEmpaque->cdgempaque;
          $alptEmpaques_sttempaque[$id_empaque] = $regAlptEmpaque->sttempaque;

        $id_empaque++; }

        $numempaques = $alptEmpaqueSelect->num_rows; }

        $alptEmpaqueSelect->close;
    } else
    { $msg_alert = $msg_noread; }
    
    echo '
    <form id="form_alptempaque" name="form_alptempaque" method="POST" action="alptEmpaque.php">
      <table align="center">
        <thead>
          <tr><th>'.$sistModulo_modulo.'</th></tr>
        </thead>
        <tbody>
          <tr><td><label for="label_idempaque">Empaque</label><br/>
              <input type="text" id="text_idempaque" name="text_idempaque" style="width:120px" value="'.$alptEmpaque_idempaque.'" title="Folio del empaque" '.$autofocus_idempaque.' required/></td></tr>
          <tr><td><label for="label_fchempaque">Fecha empaque</label><br/>
              <input type="date" id="text_fchempaque" name="text_fchempaque" style="width:120px" value="'.$alptEmpaque_fchempaque.'" title="Fecha empaque" '.$autofocus_fchempaque.' required/></td></tr>
          <tr><td><label for="label_empaque">Descripci&oacute;n</label><br/>
              <textarea id="textarea_empaque" name="textarea_empaque" style="width:240px" title="Descripcion del empaque" '.$autofocus_empaque.' required>'.$alptEmpaque_empaque.'</textarea></td></tr>
        </tbody>
        <tfoot>
          <tr><td align="right"><input type="submit" id="submit_salvar" name="submit_salvar" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>';
      
    echo '
      <table align="center">
        <thead>
          <tr><td colspan="2"></td>
            <th colspan="5" align="right">
              <input type="checkbox" id="checkbox_vertodo" name="checkbox_vertodo" '.$vertodo.' onclick="document.form_alptempaque.submit()" />
              <label for="label_vertodo">Ver todo</label></th></tr>
          <tr><th><label for="label_ttlidempaque">Empaque</label></th>
            <th><label for="label_ttlfchempaque">Fecha</label></th>            
            <th colspan="5"><label for="label_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';
      
    for($id_empaque = 1; $id_empaque <= $numempaques; $id_empaque++)  
    { echo '
          <tr><td>'.$alptEmpaques_idempaque[$id_empaque].'</td>
            <td>'.$alptEmpaques_fchempaque[$id_empaque].'</td>';
            
      if ((int)$alptEmpaques_sttempaque[$id_empaque] > 0)      
      { if ((int)$alptEmpaques_sttempaque[$id_empaque] > 1)
        { echo '
            <td colspan="3"></td>
            <td align="center"><a href="pdf/alptEmpaque.php?cdgempaque='.$alptEmpaques_cdgempaque[$id_empaque].'&sttsublote=9" target="blank">'.$png_acrobat.'</a></td>
            <td align="center">'.$png_power_blue.'</td>'; }
        else
        { echo '
            <td align="center"><a href="alptEmpaque.php?cdgempaque='.$alptEmpaques_cdgempaque[$id_empaque].'">'.$png_search.'</a></td>
            <td align="center"><a href="alptPacking.php?cdgempaque='.$alptEmpaques_cdgempaque[$id_empaque].'">'.$png_link.'</a></td>
            <td align="center"><a href="alptPackingFree.php?cdgempaque='.$alptEmpaques_cdgempaque[$id_empaque].'">'.$png_box_open.'</a></td>
            <td align="center"><a href="pdf/alptEmpaque.php?cdgempaque='.$alptEmpaques_cdgempaque[$id_empaque].'&sttsublote=8" target="blank">'.$png_acrobat.'</a></td>
            <td align="center"><a href="alptEmpaque.php?cdgempaque='.$alptEmpaques_cdgempaque[$id_empaque].'&proceso=update">'.$png_power_blue.'</a></td>'; } 
      } else
      { echo '
            <td align="center"><a href="alptEmpaque.php?cdgempaque='.$alptEmpaques_cdgempaque[$id_empaque].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td colspan="2"></td>
            <td align="center"><a href="pdf/alptEmpaque.php?cdgempaque='.$alptEmpaques_cdgempaque[$id_empaque].'&sttsublote=8" target="blank">'.$png_acrobat.'</a></td>
            <td align="center"><a href="alptEmpaque.php?cdgempaque='.$alptEmpaques_cdgempaque[$id_empaque].'&proceso=update">'.$png_power_black.'</a></td>'; }

      echo '</tr>'; }
        
    echo '
        </tbody>
        <tfoot>
          <tr><td colspan="7" align="right"><label for="label_piedefiltro">['.$numempaques.'] empaques encontrados</label></td></tr>
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
