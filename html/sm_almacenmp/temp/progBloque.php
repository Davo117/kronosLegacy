<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';
  $link = conectar();  

  $sistModulo_cdgmodulo = '50010';

  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);    

    $progbloque_idbloque = trim($_POST['txt_idbloque']);
    $progbloque_bloque = $_POST['txt_bloque'];
    $progbloque_fchbloque = $_POST['txt_fchbloque'];  
    
    if ($progbloque_fchbloque == '') 
    { $progbloque_fchbloque = date("Y-m-d"); }
    else
    { $fchbloque = str_replace("-", "", $progbloque_fchbloque);
      
      $dia = str_pad(substr($fchbloque,6,2),2,'0',STR_PAD_LEFT);
      $mes = str_pad(substr($fchbloque,4,2),2,'0',STR_PAD_LEFT);
      $ano = '20'.str_pad(substr($fchbloque,2,2),2,'0',STR_PAD_LEFT);
            
      if (checkdate((int)$mes,(int)$dia,(int)$ano)) 
      { $progbloque_fchbloque = $ano.'-'.$mes.'-'.$dia; }
      else
      { $progbloque_fchbloque = date('Y-m-d'); }
    }

    if ($_GET['cdgbloque'])
    { if (substr($sistModulo_permiso,0,3) == 'rwx')
      { $progBloqueSelect = $link->query("
          SELECT * FROM progbloque
           WHERE cdgbloque = '".$_GET['cdgbloque']."'");
        
        if ($progBloqueSelect->num_rows > 0)
        { $regProgBloque = $progBloqueSelect->fetch_object();

          $progbloque_idbloque = $regProgBloque->idbloque;
          $progbloque_bloque = $regProgBloque->bloque;
          $progbloque_fchbloque = $regProgBloque->fchbloque;
          $progbloque_cdgbloque = $regProgBloque->cdgbloque;
          $progbloque_sttbloque = $regProgBloque->sttbloque;

          if ($_GET['proceso'] == 'upload')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { require_once '../php-excel-reader/excel_reader2.php';
              $data = new Spreadsheet_Excel_Reader("excel/upload2.xls");

              for ($i = 2; $i <= 999; $i++) 
              { $progLote_cdglote = $progbloque_cdgbloque.str_pad($data->sheets[0]['cells'][$i][7],3,'0',STR_PAD_LEFT).'0000';
                    
                if (trim($data->sheets[0]['cells'][$i][1]) != '') 
                { 
                  $link->query("
                    INSERT INTO proglote
                      (cdgbloque, idlote, lote, longitud, amplitud, espesor, encogimiento, peso, tarima, cdglote, fchmovimiento)
                    VALUES
                      ('".$progbloque_cdgbloque."', '".$data->sheets[0]['cells'][$i][7]."', '".$data->sheets[0]['cells'][$i][1]."', '".$data->sheets[0]['cells'][$i][2]."', '".$data->sheets[0]['cells'][$i][3]."', '".$data->sheets[0]['cells'][$i][4]."', '".$data->sheets[0]['cells'][$i][5]."', '".$data->sheets[0]['cells'][$i][6]."', '".$data->sheets[0]['cells'][$i][8]."', '".$progLote_cdglote."', NOW())");
                }
                else
                { break; }
              }
            } else
            { $msg_alert = $msg_noread; }
          }
          
          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($progbloque_sttbloque == '1')
              { $progbloque_newsttbloque = '0'; }
            
              if ($progbloque_sttbloque == '0')
              { $progbloque_newsttbloque = '1'; }
              
              if ($progbloque_newsttbloque != '')
              { $link->query("
                  UPDATE progbloque
                     SET sttbloque = '".$progbloque_newsttbloque."' 
                   WHERE cdgbloque = '".$progbloque_cdgbloque."'");
                
                if ($link->affected_rows > 0)
                { $aviso = 'El bloque fue actualizado en su status.'; }
              }
            } else
            { $msg_alert = $msg_norewrite; }
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $queryselectlote = $link->query("
                SELECT * FROM proglote
                 WHERE cdgbloque = '".$progbloque_cdgbloque."'");
                
              if ($queryselectlote->num_rows > 0)
              { $aviso = 'El bloque no esta vacío, no pudo ser eliminado.'; }
              else
              { $link->query("
                  DELETE FROM progbloque
                   WHERE cdgbloque = '".$progbloque_cdgbloque."' AND 
                         sttbloque = '0'");
                  
                if ($link->affected_rows > 0)
                { $aviso = 'El bloque fue eliminado con éxito.'; }
                else
                { $aviso = 'El bloque no fue eliminado.'; }
              }
            } else
            { $msg_alert = $msg_nodelete; } 
          }
        }
      } else
      { $msg_alert = $msg_noread; }
    } 

    if ($_POST['btn_submit'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if (strlen($progbloque_idbloque) > 0)
        { 
          $progBloqueSelect = $link->query("
            SELECT * FROM progbloque
             WHERE idbloque = '".$progbloque_idbloque."'");
            
          if ($progBloqueSelect->num_rows > 0)
          {	$regProgBloque = $progBloqueSelect->fetch_object();
            
            
            $link->query("
              UPDATE progbloque
                 SET bloque = '".$progbloque_bloque."',
                     fchbloque = '".$progbloque_fchbloque."'            
               WHERE cdgbloque = '".$regProgBloque->cdgbloque."' AND 
                     sttbloque = '1'");
              
            if ($link->affected_rows > 0) 
            { $aviso = 'El bloque fue actualizado éxito.'; }
          }
          else
          { for ($cdgbloque = 1; $cdgbloque <= 99999; $cdgbloque++)
            { $progbloque_cdgbloque = str_pad($cdgbloque,5,'0',STR_PAD_LEFT);
              
              
              $link->query("
                INSERT INTO progbloque
                  (idbloque, bloque, fchbloque, cdgbloque)
                VALUES
                  ('".$progbloque_idbloque."', '".$progbloque_bloque."', '".$progbloque_fchbloque."', '".$progbloque_cdgbloque."')");
              
              if ($link->affected_rows > 0) 
              { $aviso = 'El bloque fue insertado con éxito.'; 
                break; }
            }
          }
        }
      } else
      { $msg_alert = $msg_norewrite; }
    }

    if (substr($sistModulo_permiso,0,1) == 'r')    
    { # Filtrado de registros
      if ($_POST['chk_vertodos'])
      { $progbloque_vertodos = 'checked'; 

        
        $queryselectbloque = $link->query("
          SELECT progbloque.idbloque,
                 progbloque.bloque,
                 progbloque.fchbloque,
                 progbloque.cdgbloque,
                 progbloque.sttbloque
            FROM progbloque
        GROUP BY progbloque.cdgbloque
        ORDER BY progbloque.sttbloque DESC,
                 progbloque.fchbloque,
                 progbloque.idbloque"); 
      } else
      { 
        $queryselectbloque = $link->query("
          SELECT progbloque.idbloque,
                 progbloque.bloque,
                 progbloque.fchbloque,
                 progbloque.cdgbloque,
                 progbloque.sttbloque
            FROM progbloque
           WHERE progbloque.sttbloque >= '1'
        GROUP BY progbloque.cdgbloque
        ORDER BY progbloque.sttbloque DESC,
                 progbloque.fchbloque,
                 progbloque.idbloque"); }

      if ($queryselectbloque->num_rows > 0)
      { $nbloque = 0;
        while ($regbloque = $queryselectbloque->fetch_object())
        { $nbloque++;

          $progbloques_idbloque[$nbloque] = $regbloque->idbloque;
          $progbloques_bloque[$nbloque] = $regbloque->bloque;
          $progbloques_fchbloque[$nbloque] = $regbloque->fchbloque;
          $progbloques_cdgbloque[$nbloque] = $regbloque->cdgbloque;
          $progbloques_sttbloque[$nbloque] = $regbloque->sttbloque;

          $queryselectlote = $link->query("
            SELECT proglote.cdgbloque,
               SUM(proglote.peso) AS peso,
                   proglote.sttlote
              FROM progbloque,
                   proglote
             WHERE progbloque.cdgbloque = '".$regbloque->cdgbloque."' AND
                   progbloque.cdgbloque = proglote.cdgbloque
          GROUP BY proglote.cdgbloque,
                   proglote.sttlote"); 

          if ($queryselectlote->num_rows > 0)
          { while ($regpeso = $queryselectlote->fetch_object())
            { $progbloques_peso[$regpeso->cdgbloque] += $regpeso->peso;
              $progbloques_pesos[$regpeso->cdgbloque][$regpeso->sttlote] = $regpeso->peso; }
          }
        }

        $nbloques = $queryselectbloque->num_rows; }
    } else
    { $msg_alert = $msg_noread; }
    
    echo '
    <form id="frm_progbloque" name="frm_progbloque" method="POST" action="progBloque.php">
      <table align="center">
        <thead>
          <tr><th colspan="4">'.$sistModulo_modulo.'</th></tr>          
        </thead>
        <tbody>
          <tr><td><label for="lbl_idbloque">Bloque</label><br/>
              <input type="text" size="14" maxlength="24" id="txt_idbloque" name="txt_idbloque" value="'.$progbloque_idbloque.'" title="Identificador de bloque" required/></td>
            <td><label for="lbl_bloque">Referencia</label><br/>
              <input type="text" size="40" maxlength="80" id="txt_bloque" name="txt_bloque" value="'.$progbloque_bloque.'" title="Referencia de bloque (Proveedor/Documento)" required/></td>
            <td><label for="lbl_fchbloque">Fecha recepci&oacute;n</label><br/>
              <input type="date" size="10" maxlength="10" id="txt_fchbloque" name="txt_fchbloque" value="'.$progbloque_fchbloque.'" required/></td>
            <td><input type="submit" id="btn_submit" name="btn_submit" value="Salvar" /></td></tr>
        <tbody>
        <tfoot>
          <tr><td colspan="4"></td></tr>
        </tfoot>
      </table><br/>
    
      <table align="center">
        <thead>
          <tr align="left">
            <th><label for="lbl_ttlidbloque">Bloque</label></th>
            <th><label for="lbl_ttlbloque">Referencia</label></th>
            <th><label for="lbl_ttlpeso">Peso</label></th>
            <th><label for="lbl_ttlpeso">Cancelado</label></th>
            <th><label for="lbl_ttlpeso">Recepción</label></th>
            <th><label for="lbl_ttlpeso">Liberado</label></th>
            <th><label for="lbl_ttlpeso">Programado</label></th>
            <th><label for="lbl_ttlpeso">Procesado</label></th>
            <th><label for="lbl_ttlfchbloque">Fecha</label></th>
            <th colspan="6"><label for="lbl_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';
  
    if ($nbloques > 0)
    { for ($nbloque=1; $nbloque<=$nbloques; $nbloque++)
      { echo '
          <tr align="center">
            <td align="right"><strong>'.$progbloques_idbloque[$nbloque].'</strong></td>
            <td align="left">'.$progbloques_bloque[$nbloque].'</td>
            <td align="right">'.number_format($progbloques_peso[$progbloques_cdgbloque[$nbloque]],2).' kgs</td>
            <td align="right">';

        if ($progbloques_pesos[$progbloques_cdgbloque[$nbloque]][0] > 0)
        { echo '<label title="'.number_format($progbloques_pesos[$progbloques_cdgbloque[$nbloque]][0],2).' kgs">'.number_format((($progbloques_pesos[$progbloques_cdgbloque[$nbloque]][0]*100)/$progbloques_peso[$progbloques_cdgbloque[$nbloque]]),2).'%</label>'; } 
          
          echo '</td>
            <td align="right">';

        if ($progbloques_pesos[$progbloques_cdgbloque[$nbloque]][1] > 0)
        { echo '<label title="'.number_format($progbloques_pesos[$progbloques_cdgbloque[$nbloque]][1],2).' kgs">'.number_format((($progbloques_pesos[$progbloques_cdgbloque[$nbloque]][1]*100)/$progbloques_peso[$progbloques_cdgbloque[$nbloque]]),2).'%</label>'; } 

          echo '</td>
            <td align="right">';

        if ($progbloques_pesos[$progbloques_cdgbloque[$nbloque]][7] > 0)
        { echo '<label title="'.number_format($progbloques_pesos[$progbloques_cdgbloque[$nbloque]][7],2).' kgs">'.number_format((($progbloques_pesos[$progbloques_cdgbloque[$nbloque]][7]*100)/$progbloques_peso[$progbloques_cdgbloque[$nbloque]]),2).'%</label>'; } 

          echo '</td>
            <td align="right">';

        if ($progbloques_pesos[$progbloques_cdgbloque[$nbloque]][8] > 0)
        { echo '<label title="'.number_format($progbloques_pesos[$progbloques_cdgbloque[$nbloque]][8],2).' kgs">'.number_format((($progbloques_pesos[$progbloques_cdgbloque[$nbloque]][8]*100)/$progbloques_peso[$progbloques_cdgbloque[$nbloque]]),2).'%</label>'; } 

          echo '</td>
            <td align="right">';

        if ($progbloques_pesos[$progbloques_cdgbloque[$nbloque]][9] > 0)
        { echo '<label title="'.number_format($progbloques_pesos[$progbloques_cdgbloque[$nbloque]][9],2).' kgs">'.number_format((($progbloques_pesos[$progbloques_cdgbloque[$nbloque]][9]*100)/$progbloques_peso[$progbloques_cdgbloque[$nbloque]]),2).'%</label>'; } 

          echo '</td>
            <td><strong>'.$progbloques_fchbloque[$nbloque].'</strong></td>';

        if ((int)$progbloques_sttbloque[$nbloque] > 0)
        { echo '
            <td><a href="progBloque.php?cdgbloque='.$progbloques_cdgbloque[$nbloque].'">'.$png_search.'</a></td>
            <td><a href="progLote.php?cdgbloque='.$progbloques_cdgbloque[$nbloque].'">'.$png_link.'</a></td>
            <td><a href="progBloque.php?cdgbloque='.$progbloques_cdgbloque[$nbloque].'&proceso=upload">'.$jpg_excel.'</a></td>
            <td><a href="pdf/progLoteByBloque.php?cdgbloque='.$progbloques_cdgbloque[$nbloque].'" target="_blank">'.$png_acrobat.'</a></td>
            <td><a href="pdf/progLoteByBloqueBC.php?cdgbloque='.$progbloques_cdgbloque[$nbloque].'" target="_blank">'.$png_barcode.'</a></td>
            <td><a href="progBloque.php?cdgbloque='.$progbloques_cdgbloque[$nbloque].'&proceso=update">'.$png_power_blue.'</a></td>'; }
        else
        { echo '
            <td><a href="progBloque.php?cdgbloque='.$progbloques_cdgbloque[$nbloque].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td colspan="4">&nbsp;</td>
            <td><a href="progBloque.php?cdgbloque='.$progbloques_cdgbloque[$nbloque].'&proceso=update">'.$png_power_black.'</a></td>'; }

        echo '</tr>';
      }      
    }

    echo '
        </tbody>
        <tfoot>
          <tr><th colspan="15" align="right">
              <input type="checkbox" name="chk_vertodos" id="chk_vertodos" onclick="document.frm_progbloque.submit()" '.$progbloque_vertodos.'>
              <label for="lbl_ppgdatos">Ver todos ['.$nbloques.'] Registros encontrados</label></th></tr>
        </tfoot>
      </table>
    </form>'; 

    if ($aviso != '')
    { echo '
    <script type="text/javascript"> alert("'.$aviso.'"); </script>'; }
  } else
  { echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }    

?>

  </body>	
</html>
