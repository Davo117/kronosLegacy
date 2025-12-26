<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $progBloque_idbloque = trim($_POST['txt_idbloque']);
  $progBloque_bloque = $_POST['txt_bloque'];
  $progBloque_fchbloque = $_POST['txt_fchbloque'];  
  
  if ($progBloque_fchbloque == '') 
  { $progBloque_fchbloque = date("Y-m-d"); }
  else
  { $fchbloque = str_replace("-", "", $progBloque_fchbloque);
    
    $dia = str_pad(substr($fchbloque,6,2),2,'0',STR_PAD_LEFT);
    $mes = str_pad(substr($fchbloque,4,2),2,'0',STR_PAD_LEFT);
    $ano = '20'.str_pad(substr($fchbloque,2,2),2,'0',STR_PAD_LEFT);
          
    if (checkdate((int)$mes,(int)$dia,(int)$ano)) 
    { $progBloque_fchbloque = $ano.'-'.$mes.'-'.$dia; }
    else
    { $progBloque_fchbloque = date('Y-m-d'); }
  }

  if ($_GET['cdgbloque'])
  { $link_mysqli = conectar();
    $progBloqueSelect = $link_mysqli->query("
      SELECT * FROM progbloque
      WHERE cdgbloque = '".$_GET['cdgbloque']."'");
    
    if ($progBloqueSelect->num_rows > 0)
    { $regProgBloque = $progBloqueSelect->fetch_object();

      $progBloque_idbloque = $regProgBloque->idbloque;
      $progBloque_bloque = $regProgBloque->bloque;
      $progBloque_fchbloque = $regProgBloque->fchbloque;
      $progBloque_cdgbloque = $regProgBloque->cdgbloque;
      $progBloque_sttbloque = $regProgBloque->sttbloque;

      if ($_GET['proceso'] == 'upload')
      { require_once '../php-excel-reader/excel_reader2.php';
        $data = new Spreadsheet_Excel_Reader("excel/upload.xls");

        $subcdglote = 999;
        for ($i = 2; $i <= $subcdglote; $i++) 
        { $progLote_cdglote = $progBloque_cdgbloque.str_pad($data->sheets[0]['cells'][$i][7],3,'0',STR_PAD_LEFT).'0000';
              
          if (trim($data->sheets[0]['cells'][$i][1]) != '') 
          { $link_mysqli = conectar();
            $link_mysqli->query("
              INSERT INTO proglote
                (cdgbloque, idlote, lote, longitud, amplitud, espesor, encogimiento, peso, tarima, cdglote)
              VALUES
                ('".$progBloque_cdgbloque."', '".$data->sheets[0]['cells'][$i][7]."', '".$data->sheets[0]['cells'][$i][1]."', '".$data->sheets[0]['cells'][$i][2]."', '".$data->sheets[0]['cells'][$i][3]."', '".$data->sheets[0]['cells'][$i][4]."', '".$data->sheets[0]['cells'][$i][5]."', '".$data->sheets[0]['cells'][$i][6]."', '".$data->sheets[0]['cells'][$i][8]."', '".$progLote_cdglote."')");    
          }
          else
          { $subcdglote = 1000; }
        }      
      }
      
      if ($_GET['proceso'] == 'update')
      { if ($progBloque_sttbloque == '1')
        { $progBloque_newsttbloque = '0'; }
      
        if ($progBloque_sttbloque == '0')
        { $progBloque_newsttbloque = '1'; }
        
        if ($progBloque_newsttbloque != '')
        { $link_mysqli = conectar();
          $link_mysqli->query("
            UPDATE progbloque
            SET sttbloque = '".$progBloque_newsttbloque."' 
            WHERE cdgbloque = '".$progBloque_cdgbloque."'");
          
          if ($link_mysqli->affected_rows > 0)
          { $aviso = 'El bloque fue actualizado en su status.'; }
        }
      }

      if ($_GET['proceso'] == 'delete')
      { $link_mysqli = conectar();
        $progBloqueLoteSelect = $link_mysqli->query("
          SELECT * FROM proglote
          WHERE cdgbloque = '".$progBloque_cdgbloque."'");
          
        if ($progBloqueLoteSelect->num_rows > 0)
        { $aviso = 'El bloque no esta vacío, no pudo ser eliminado.'; }
        else
        { $link_mysqli = conectar();
          $link_mysqli->query("
            DELETE FROM progbloque
            WHERE cdgbloque = '".$progBloque_cdgbloque."'
            AND sttbloque = '0'");
            
          if ($link_mysqli->affected_rows > 0)
          { $aviso = 'El bloque fue eliminado con éxito.'; }
          else
          { $aviso = 'El bloque no fue eliminado.'; }
        }

        $progBloqueLoteSelect->close;
      }
    }
  } 

  if ($_POST['btn_submit'])
  { if (strlen($progBloque_idbloque) > 0)
    { $link_mysqli = conectar();
      $progBloqueSelect = $link_mysqli->query("
        SELECT * FROM progbloque
        WHERE idbloque = '".$progBloque_idbloque."'");
        
      if ($progBloqueSelect->num_rows > 0)
      {	$regProgBloque = $progBloqueSelect->fetch_object();
        
        $link_mysqli = conectar();
        $link_mysqli->query("
          UPDATE progbloque
          SET bloque = '".$progBloque_bloque."',
            fchbloque = '".$progBloque_fchbloque."'            
          WHERE cdgbloque = '".$regProgBloque->cdgbloque."'
          AND sttbloque = '1'");
          
        if ($link_mysqli->affected_rows > 0) 
        { $aviso = 'El bloque fue actualizado éxito.'; }
      }
      else
      { for ($cdgbloque = 1; $cdgbloque <= 99999; $cdgbloque++)
        { $progBloque_cdgbloque = str_pad($cdgbloque,5,'0',STR_PAD_LEFT);
          
          $link_mysqli = conectar();
          $link_mysqli->query("
            INSERT INTO progbloque
              (idbloque, bloque, fchbloque, cdgbloque)
            VALUES
              ('".$progBloque_idbloque."', '".$progBloque_bloque."', '".$progBloque_fchbloque."', '".$progBloque_cdgbloque."')");
          
          if ($link_mysqli->affected_rows > 0) 
          { $aviso = 'El bloque fue insertado con éxito.'; 
            $cdgbloque = 100000; }      
        }
      }
    }
  }

  // Filtrado de peso
  
  $link_mysqli = conectar();
  $progLoteSelect = $link_mysqli->query("
    SELECT cdgbloque,
      SUM(peso) AS peso
    FROM proglote    
    GROUP BY cdgbloque"); 

  while ($regProgLote = $progLoteSelect->fetch_object())
  { $progLotes_peso[$regProgLote->cdgbloque] = $regProgLote->peso; }

  $progLoteSelect->close; //*/

  // 
  if ($_POST['chk_vertodos'])
  { $progBloque_vertodos = 'checked'; 

    $link_mysqli = conectar();
    $progBloqueSelect = $link_mysqli->query("
      SELECT * FROM progbloque      
      ORDER BY progbloque.sttbloque DESC,
        progbloque.fchbloque,
        progbloque.idbloque"); }
  else
  { $link_mysqli = conectar();
    $progBloqueSelect = $link_mysqli->query("
      SELECT * FROM progbloque
      WHERE progbloque.sttbloque >= '1'      
      ORDER BY progbloque.fchbloque,
        progbloque.idbloque"); }    
  
  $idbloque = 1;
  while ($regProgBloque = $progBloqueSelect->fetch_object())
  { $progBloques_idbloque[$idbloque] = $regProgBloque->idbloque;
    $progBloques_bloque[$idbloque] = $regProgBloque->bloque;
    //$progBloques_peso[$idbloque] = $regProgBloque->peso;
    $progBloques_fchbloque[$idbloque] = $regProgBloque->fchbloque;
    $progBloques_cdgbloque[$idbloque] = $regProgBloque->cdgbloque;
    $progBloques_sttbloque[$idbloque] = $regProgBloque->sttbloque; 

    $idbloque++; }

  $idsbloque = $progBloqueSelect->num_rows;

  $progBloqueSelect->close;
  
  echo '
    <form id="frm_progbloque" name="frm_progbloque" method="POST" action="progBloque.php">
      <table align="center">
        <thead>
          <tr><th colspan="2">Captura de Bloques</th></tr>
        </thead>
        <tbody>
          <tr><td>
              <label for="lbl_idbloque">Bloque</label><br/>
              <input type="text" size="14" maxlength="24" id="txt_idbloque" name="txt_idbloque" value="'.$progBloque_idbloque.'" title="Identificador de bloque" required/></td>
            <td align="center">
              <label for="lbl_fchbloque">Fecha recepci&oacute;n</label><br/>
              <input type="date" size="10" maxlength="10" id="txt_fchbloque" name="txt_fchbloque" value="'.$progBloque_fchbloque.'" required/></td></tr>
          <tr><td colspan="2">
              <label for="lbl_bloque">Referencia</label><br/>
              <input type="text" size="40" maxlength="80" id="txt_bloque" name="txt_bloque" value="'.$progBloque_bloque.'" title="Referencia de bloque (Proveedor/Documento)" required/></td></tr>
        <tbody>
        <tfoot>
          <tr><td colspan="2" align="right"><input type="submit" id="btn_submit" name="btn_submit" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>
    
      <table align="center">
        <thead>
          <tr align="left">
            <th><label for="lbl_ttlidbloque">Bloque</label></th>
            <th><label for="lbl_ttlbloque">Referencia</label></th>
            <th><label for="lbl_ttlpeso">Peso</label></th>
            <th><label for="lbl_ttlfchbloque">Fecha</label></th>
            <th colspan="5"><label for="lbl_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';
  
  if ($idsbloque > 0)
  { for ($idbloque=1; $idbloque<=$idsbloque; $idbloque++)
    { echo '
          <tr align="center"><td align="right"><strong>'.$progBloques_idbloque[$idbloque].'</strong></td>
            <td align="left">'.$progBloques_bloque[$idbloque].'</td>
            <td align="right">'.number_format($progLotes_peso[$progBloques_cdgbloque[$idbloque]],2).' kgs</td>
            <td><strong>'.$progBloques_fchbloque[$idbloque].'</strong></td>';

      if ((int)$progBloques_sttbloque[$idbloque] > 0)
      { echo '
            <td><a href="progBloque.php?cdgbloque='.$progBloques_cdgbloque[$idbloque].'">'.$png_search.'</a></td>
            <td><a href="progLote.php?cdgbloque='.$progBloques_cdgbloque[$idbloque].'">'.$png_link.'</a></td>
            <td><a href="progBloque.php?cdgbloque='.$progBloques_cdgbloque[$idbloque].'&proceso=upload">'.$jpg_excel.'</a></td>
            <td><a href="pdf/progLoteByBloque.php?cdgbloque='.$progBloques_cdgbloque[$idbloque].'" target="_blank">'.$png_acrobat.'</a></td>
            <td><a href="progBloque.php?cdgbloque='.$progBloques_cdgbloque[$idbloque].'&proceso=update">'.$png_power_blue.'</a></td>'; }
      else
       { echo '
            <td><a href="progBloque.php?cdgbloque='.$progBloques_cdgbloque[$idbloque].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td colspan="2">&nbsp;</td>
            <td><a href="progBloque.php?cdgbloque='.$progBloques_cdgbloque[$idbloque].'&proceso=update">'.$png_power_black.'</a></td>'; }

      echo '</tr>';
    }      
  }

  echo '
        </tbody>
        <tfoot>
          <tr><th colspan="9" align="right">
              <input type="checkbox" name="chk_vertodos" id="chk_vertodos" onclick="document.frm_progbloque.submit()" '.$progBloque_vertodos.'>
              <label for="lbl_ppgdatos">Ver todos ['.$idsbloque.'] Registros encontrados</label></th></tr>
        </tfoot>
      </table>
    </form>'; 

  if ($aviso != '')
  { echo '
    <script type="text/javascript"> alert("'.$aviso.'"); </script>'; }

  unset($progBloques_idbloque);
  unset($progBloques_bloque);
  unset($progBloques_fchbloque);
  unset($progBloques_cdgbloque);
  unset($progBloques_sttbloque);

?>

  </body>	
</html>