<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $progLote_cdgprograma = $_POST['slc_cdgbloque'];
  $progLote_idlote = trim($_POST['txt_idlote']);
  $progLote_lote = trim($_POST['txt_lote']);
  $progLote_longitud = trim($_POST['txt_longitud']);
  $progLote_amplitud = trim($_POST['txt_amplitud']);
  $progLote_espesor = trim($_POST['txt_espesor']);
  $progLote_encogimiento = trim($_POST['txt_encogimiento']);
  $progLote_peso = trim($_POST['txt_peso']);
  $progLote_tarima = trim($_POST['txt_tarima']);
  
  if ($_GET['cdgbloque'])
  { $link_mysqli = conectar();
    $progBloqueSelect = $link_mysqli->query("
      SELECT * FROM progbloque
      WHERE cdgbloque = '".$_GET['cdgbloque']."'");
    
    if ($progBloqueSelect->num_rows > 0)
    { $regProgBloque = $progBloqueSelect->fetch_object();

      $progLote_idbloque = $regProgBloque->idbloque;
      $progLote_bloque = $regProgBloque->bloque;
      $progLote_fchbloque = $regProgBloque->fchbloque;      
      $progLote_cdgbloque = $regProgBloque->cdgbloque;
      $progLote_sttbloque = $regProgBloque->sttbloque; }
  }

  if ($_GET['cdglote'])
  { $link_mysqli = conectar();
    $progLoteSelect = $link_mysqli->query("
      SELECT * FROM proglote
      WHERE cdglote = '".$_GET['cdglote']."'");
    
    if ($progLoteSelect->num_rows > 0)
    { $regProgLote = $progLoteSelect->fetch_object();

      $progLote_cdgbloque = $regProgLote->cdgbloque;
      $progLote_idlote = $regProgLote->idlote;
      $progLote_lote = $regProgLote->lote;
      $progLote_longitud = $regProgLote->longitud;
      $progLote_amplitud = $regProgLote->amplitud;
      $progLote_espesor = $regProgLote->espesor;
      $progLote_encogimiento = $regProgLote->encogimiento;
      $progLote_peso = $regProgLote->peso;
      $progLote_tarima = $regProgLote->tarima;
      $progLote_cdglote = $regProgLote->cdglote;
      $progLote_sttlote = $regProgLote->sttlote;

      if ($_GET['proceso'] == 'update')
      { if ($progLote_sttlote == '1')
        { $progLote_newsttlote = '0'; }
      
        if ($progLote_sttlote == '0')
        { $progLote_newsttlote = '1'; }
        
        if ($progLote_newsttlote != '')
        { $link_mysqli = conectar();
          $link_mysqli->query("
            UPDATE proglote
            SET sttlote = '".$progLote_newsttlote."' 
            WHERE cdglote = '".$progLote_cdglote."'");
          
          if ($link_mysqli->affected_rows > 0)
          { $aviso = 'El lote '.$progLote_idlote.' fue actualizado en su status.'; }
        }
      }

      if ($_GET['proceso'] == 'delete')
      { $link_mysqli = conectar();
        $progLoteSelect = $link_mysqli->query("
          SELECT * FROM prodsublote
          WHERE cdglote = '".$progLote_cdglote."'");
          
        if ($progLoteSelect->num_rows > 0)
        { $aviso = 'El lote '.$progLote_idlote.' ya esta en proceso, no pudo ser eliminado.'; }
        else
        { $link_mysqli = conectar();
          $link_mysqli->query("
            DELETE FROM proglote
            WHERE cdglote = '".$progLote_cdglote."'
            AND sttlote = '0'");
            
          if ($link_mysqli->affected_rows > 0)
          { $aviso = 'El lote '.$progLote_idlote.' fue eliminado con exito.'; }
          else
          { $aviso = 'El lote '.$progLote_idlote.' NO fue eliminado.'; }
        }
      }
    }
  } 

  if ($_POST['btn_submit'])
  { if (strlen($progLote_lote) > 0)
    { $link_mysqli = conectar();
      $progLoteSelect = $link_mysqli->query("
        SELECT * FROM proglote
        WHERE cdgbloque = '".$progLote_cdgbloque."'
        AND idlote = '".$progLote_idlote."'");
        
      if ($progLoteSelect->num_rows > 0)
      {	$regProgLote = $progLoteSelect->fetch_object();
        
        $link_mysqli = conectar();
        $link_mysqli->query("
          UPDATE proglote
          SET lote = '".$progLote_lote."',
            longitud = '".$progLote_longitud."',
            amplitud = '".$progLote_amplitud."',
            espesor = '".$progLote_espesor."',
            encogimiento = '".$progLote_encogimiento."',
            peso = '".$progLote_peso."',
            tarima = '".$progLote_tarima."'
          WHERE cdglote = '".$regProgLote->cdglote."'
          AND sttlote = '1'");
          
        if ($link_mysqli->affected_rows > 0) 
        { $aviso = 'El lote '.$progLote_idlote.' fue actualizado exito.'; }
        else
        { $aviso = 'El lote '.$progLote_idlote.' NO fue actualizado.'; }
      }
      else
      { $progLote_cdglote = $progLote_cdgbloque.str_pad($progLote_idlote,3,'0',STR_PAD_LEFT).'0000';
          
        $link_mysqli = conectar();
        $link_mysqli->query("
          INSERT INTO proglote
            (cdgbloque, idlote, lote, longitud, amplitud, espesor, encogimiento, peso, tarima, cdglote)
          VALUES
            ('".$progLote_cdgbloque."', '".$progLote_idlote."', '".$progLote_lote."', '".$progLote_longitud."', '".$progLote_amplitud."', '".$progLote_espesor."', '".$progLote_encogimiento."', '".$progLote_peso."', '".$progLote_tarima."', '".$progLote_cdglote."')");
        
        if ($link_mysqli->affected_rows > 0) 
        { $aviso = 'El lote '.$progLote_idlote.' fue insertado con exito.'; }
        else
        { $aviso = 'El lote '.$progLote_idlote.' NO fue insertado.'; }
      }

      $link_mysqli = conectar();
      $progLoteSelect = $link_mysqli->query("
        SELECT MAX(idlote) AS idlote FROM proglote
        WHERE cdgbloque = '".$progLote_cdgbloque."'");

      $regLoteSelect = $progLoteSelect->fetch_object();

      $progLote_idlote = $regLoteSelect->idlote+1;
    }
  }
  
  $link_mysqli = conectar(); 
  $progBloqueSelect = $link_mysqli->query("
    SELECT * FROM progbloque
    WHERE sttbloque = '1'
    ORDER BY idbloque");
  
  $idbloque = 1;
  while ($regProgBloque = $progBloqueSelect->fetch_object()) 
  { $progBloques_idbloque[$idbloque] = $regProgBloque->idbloque;
    $progBloques_bloque[$idbloque] = $regProgBloque->bloque;
    $progBloques_cdgbloque[$idbloque] = $regProgBloque->cdgbloque; 

     $idbloque++; }

  $idsbloque = $progBloqueSelect->num_rows;
  $progBloqueSelect->close;

  $link_mysqli = conectar();
  $progLoteSelect = $link_mysqli->query("
    SELECT tarima 
    FROM proglote
    WHERE cdgbloque = '".$progLote_cdgbloque."'
    GROUP BY tarima
    ORDER BY tarima");
  
  $idtarima = 1;
  while ($regProgLote = $progLoteSelect->fetch_object())
  { $progLotes_tarima[$idtarima] = $regProgLote->tarima;

    $idtarima++; }

  $idstarima = $progLoteSelect->num_rows;

  if ($_POST['chk_vertodos'])
  { $progLote_vertodos = 'checked'; 

    for ($idtarima = 1; $idtarima <= $idstarima; $idtarima++)
    { $link_mysqli = conectar();
      $progLoteSelect = $link_mysqli->query("
        SELECT * FROM proglote
        WHERE cdgbloque = '".$progLote_cdgbloque."'
        AND tarima = '".$progLotes_tarima[$idtarima]."'        
        ORDER BY sttlote DESC,
          cdglote"); 

      $idlote = 1;
      while ($regProgLote = $progLoteSelect->fetch_object())
      { $progLotes_idlote[$idtarima][$idlote] = $regProgLote->idlote;
        $progLotes_lote[$idtarima][$idlote] = $regProgLote->lote;
        $progLotes_longitud[$idtarima][$idlote] = $regProgLote->longitud;
        $progLotes_amplitud[$idtarima][$idlote] = $regProgLote->amplitud;
        $progLotes_espesor[$idtarima][$idlote] = $regProgLote->espesor;
        $progLotes_encogimiento[$idtarima][$idlote] = $regProgLote->encogimiento;
        $progLotes_peso[$idtarima][$idlote] = $regProgLote->peso;
        $progLotes_cdglote[$idtarima][$idlote] = $regProgLote->cdglote;
        $progLotes_sttlote[$idtarima][$idlote] = $regProgLote->sttlote;        

        $idlote++; }

      $idslote[$idtarima] = $progLoteSelect->num_rows;
    }   
  }
  else
  { for ($idtarima = 1; $idtarima <= $idstarima; $idtarima++)
    { $link_mysqli = conectar();
      $progLoteSelect = $link_mysqli->query("
        SELECT * FROM proglote
        WHERE cdgbloque = '".$progLote_cdgbloque."'
        AND tarima = '".$progLotes_tarima[$idtarima]."'        
        AND sttlote >= '1'
        ORDER BY cdglote"); 

      $idlote = 1;
      while ($regProgLote = $progLoteSelect->fetch_object())
      { $progLotes_idlote[$idtarima][$idlote] = $regProgLote->idlote;
        $progLotes_lote[$idtarima][$idlote] = $regProgLote->lote;
        $progLotes_longitud[$idtarima][$idlote] = $regProgLote->longitud;
        $progLotes_amplitud[$idtarima][$idlote] = $regProgLote->amplitud;
        $progLotes_espesor[$idtarima][$idlote] = $regProgLote->espesor;
        $progLotes_encogimiento[$idtarima][$idlote] = $regProgLote->encogimiento;
        $progLotes_peso[$idtarima][$idlote] = $regProgLote->peso;
        $progLotes_cdglote[$idtarima][$idlote] = $regProgLote->cdglote;
        $progLotes_sttlote[$idtarima][$idlote] = $regProgLote->sttlote;

        $idlote++; }

      $idslote[$idtarima] = $progLoteSelect->num_rows;
    }
  }

  $progLoteSelect->close;

  echo '
    <form id="frm_proglote" name="frm_proglote" method="POST" action="progLote.php">
      <table align="center">
        <thead>
          <tr><th colspan="2">Captura de Lotes por Bloque</th></tr>
        </thead>
        <tbody>
          <tr><td>
              <label for="lbl_cdgbloque"><a href="progBloque.php?cdgbloque='.$progLote_cdgbloque.'">Bloque</a></label><br/>
              <select style="width:120px" id="slc_cdgbloque" name="slc_cdgbloque" onchange="document.frm_proglote.submit()">
                <option value="">Selecciona una opcion</option>';
    
  for ($idbloque = 1; $idbloque <= $idsbloque; $idbloque++) 
  { echo '
                <option value="'.$progBloques_cdgbloque[$idbloque].'"';
            
    if ($progLote_cdgbloque == $progBloques_cdgbloque[$idbloque]) { echo ' selected="selected"'; }
    echo '>'.$progBloques_idbloque[$idbloque].'</option>'; }
    
  echo '
              </select></td>
            <td>
              <label for="lbl_lote">No. Lote</label><br/>
              <input type="text" style="width:80px; text-align:right;" maxlength="24" id="txt_idlote" name="txt_idlote" value="'.$progLote_idlote.'" title="Numero de lote" required /></td></tr>
          <tr><td colspan="2">
              <label for="lbl_lote">Referencia</label><br/>
              <input type="text" style="width:200px; text-align:right;" maxlength="36" id="txt_lote" name="txt_lote" value="'.$progLote_lote.'" title="Referencia del lote (Codigo del proveedor)" required /></td></tr>
          <tr><td>
              <label for="lbl_longitud">Longitud</label><br/>
              <input type="text" style="width:80px; text-align:right;" maxlength="24" id="txt_longitud" name="txt_longitud" value="'.$progLote_longitud.'" title="Metros por bobina" required /></td>
            <td>
              <label for="lbl_amplitud">Amplitud</label><br/>
              <input type="text" style="width:80px; text-align:right;" maxlength="24" id="txt_amplitud" name="txt_amplitud" value="'.$progLote_amplitud.'" title="Ancho plano en milimetros" required /></td></tr>
          <tr><td>
              <label for="lbl_espesor">Espesor</label><br/>
              <input type="text" style="width:80px; text-align:right;" maxlength="12" id="txt_espesor" name="txt_espesor" value="'.$progLote_espesor.'" title="Calibre en micras" required /></td>
            <td>
              <label for="lbl_encogimiento">Encogimiento</label><br/>
              <input type="text" style="width:80px; text-align:right;" maxlength="12" id="txt_encogimiento" name="txt_encogimiento" value="'.$progLote_encogimiento.'" title="Factor de grados" required /></td></tr>
          <tr><td>
              <label for="lbl_peso">Peso</label><br/>
              <input type="text" style="width:80px; text-align:right;" maxlength="24" id="txt_peso" name="txt_peso" value="'.$progLote_peso.'" title="Peso por bobina en kilogramos" required /></td>
            <td>
              <label for="lbl_tarima">Tarima</label><br/>
              <input type="text" style="width:80px;" maxlength="12" id="txt_tarima" name="txt_tarima" value="'.$progLote_tarima.'" title="Tarima contenedora" required /></td></tr>
        <tbody>
        <tfoot>
          <tr><td colspan="2" align="right"><input type="submit" id="btn_submit" name="btn_submit" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>
    
      <table align="center">
        <thead>
          <tr align="left">
            <th><label for="lbl_ttlidlote">No. Lote</label></th>
            <th><label for="lbl_ttllote">Referencia</label></th>
            <th><label for="lbl_ttllote">O.P.</label></th>
            <th><label for="lbl_ttllongitud">Longitud</label></th>
            <th><label for="lbl_ttlamplitud">Amplitud</label></th>
            <th><label for="lbl_ttlespesor">Espesor</label></th>
            <th><label for="lbl_ttlencogimiento">Encogimiento</label></th>
            <th><label for="lbl_ttlpeso">Peso</label></th>
            <th colspan="4"><label for="lbl_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';
  
  if ($idstarima > 0)
  { for ($idtarima = 1; $idtarima <= $idstarima; $idtarima++)
    { for ($idlote = 1; $idlote <= $idslote[$idtarima]; $idlote++)
      { echo '
          <tr align="right">
            <td><strong>'.$progLotes_idlote[$idtarima][$idlote].'</strong></td>
            <td><strong>'.$progLotes_lote[$idtarima][$idlote].'</strong></td>
            <td><strong>&nbsp;</strong></td>
            <td>'.number_format($progLotes_longitud[$idtarima][$idlote]).' Mts</td>
            <td>'.$progLotes_amplitud[$idtarima][$idlote].' mm</td>
            <td>'.$progLotes_espesor[$idtarima][$idlote].' micras</td>
            <td>'.$progLotes_encogimiento[$idtarima][$idlote].' %</td>
            <td>'.number_format($progLotes_peso[$idtarima][$idlote],2).' Kgs</td>';

        if ((int)$progLotes_sttlote[$idtarima][$idlote] > 0)
        { if ((int)$progLotes_sttlote[$idtarima][$idlote] == 1)
          { echo '
            <td align="center"><a href="progLote.php?cdglote='.$progLotes_cdglote[$idtarima][$idlote].'">'.$png_search.'</a></td>
            <td></td>
            <td></td>
            <td align="center"><a href="progLote.php?cdglote='.$progLotes_cdglote[$idtarima][$idlote].'&proceso=update">'.$png_power_blue.'</a></td>'; }

          if ((int)$progLotes_sttlote[$idtarima][$idlote] == 9)
          { echo '
            <td align="center"><a href="progLote.php?cdglote='.$progLotes_cdglote[$idtarima][$idlote].'">'.$png_search.'</a></td>
            <td align="center"><a href="prodSublote.php?cdglote='.$progLotes_cdglote[$idtarima][$idlote].'">'.$png_link.'</a></td>
            <td align="center"><a href="pdf/progLote.php?cdglote='.$progLotes_cdglote[$idtarima][$idlote].'" target="_blank">'.$png_acrobat.'</a></td>
            <td>'.$png_power_blue.'</td>'; }  // candado          
        }
        else
        { echo '
            <td align="center"><a href="progLote.php?cdglote='.$progLotes_cdglote[$idtarima][$idlote].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td colspan="2">&nbsp;</td>
            <td align="center"><a href="progLote.php?cdglote='.$progLotes_cdglote[$idtarima][$idlote].'&proceso=update">'.$png_power_black.'</a></td>'; }

        echo '</tr>';

        $progLote_sumalongitud[$idtarima] = $progLote_sumalongitud[$idtarima]+$progLotes_longitud[$idtarima][$idlote];
        $progLote_sumapeso[$idtarima] = $progLote_sumapeso[$idtarima]+$progLotes_peso[$idtarima][$idlote];
      }

      echo '
          <tr align="right">
            <td></td>
            <th>Tarima '.$progLotes_tarima[$idtarima].'&nbsp;&nbsp;'.$idslote[$idtarima].' Unidades</th>
            <th colspan="2">'.number_format($progLote_sumalongitud[$idtarima]).' Mts</th>
            <th colspan="4">'.number_format($progLote_sumapeso[$idtarima],2).' Kgs</th>
            <td colspan="4"></td></tr>';

      $idlotes = $idlotes+$idslote[$idtarima];
    }      
  }

  echo '
        </tbody>
        <tfoot>
          <tr><th colspan="12" align="right">
              <input type="checkbox" name="chk_vertodos" id="chk_vertodos" onclick="document.frm_proglote.submit()" '.$progLote_vertodos.'>
              <label for="lbl_ppgdatos">Ver todos ['.$idlotes.'] Registros encontrados</label></th></tr>
        </tfoot>
      </table>
    </form>'; 

  if ($aviso != '')
  { echo '
    <script type="text/javascript"> alert("'.$aviso.'"); </script>'; }

  unset($progLotes_idlote);
  unset($progLotes_lote);
  unset($progLotes_longitud);
  unset($progLotes_amplitud);
  unset($progLotes_espesor);
  unset($progLotes_encogimiento);
  unset($progLotes_peso);
  unset($progLotes_cdglote);
  unset($progLotes_sttlote);  
  unset($progLote_sumalongitud);
  unset($progLote_sumapeso);
  unset($idslote);

?>

  </body>	
</html>