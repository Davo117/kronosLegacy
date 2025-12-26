<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '30250';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    $progLote_cdgbloque = $_POST['slc_cdgbloque'];
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

        $progLote_nBloque = $regProgBloque->nBloque;
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
        { if (substr($sistModulo_permiso,0,3) == 'rwx')
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
              { $msg_alert = 'El lote '.$progLote_idlote.' fue actualizado en su status.'; }
            }
          } else
          { $msg_alert = 'No cuentas con permisos'; }
        }

        if ($_GET['proceso'] == 'undo')
        { if (substr($sistModulo_permiso,0,3) == 'rwx')
          { $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE proglote
                 SET sttlote = '1' 
               WHERE cdglote = '".$progLote_cdglote."' AND
                     sttlote = '7'");
              
            if ($link_mysqli->affected_rows > 0)
            { $link_mysqli->query("
                DELETE FROM prodloteope
                      WHERE cdglote = '".$progLote_cdglote."' AND
                            cdgoperacion = '00090'");

              $link_mysqli->query("
                DELETE FROM prodloteproc
                      WHERE cdglote = '".$progLote_cdglote."' AND
                            cdgproceso = '09'");        

                $msg_alert = 'El lote '.$progLote_idlote.' fue Retornado al almacen.'; 
            } else
            { $msg_alert = 'El lote '.$progLote_idlote.' NO fue Retornado al almacen.'; }
          } else
          { $msg_alert = 'No cuentas con permisos'; }
        } 

        if ($_GET['proceso'] == 'delete')
        { if (substr($sistModulo_permiso,0,3) == 'rwx')
          { $link_mysqli = conectar();
            $progLoteSelect = $link_mysqli->query("
              SELECT * FROM prodsublote
              WHERE cdglote = '".$progLote_cdglote."'");
              
            if ($progLoteSelect->num_rows > 0)
            { $msg_alert = 'El lote '.$progLote_idlote.' ya esta en proceso, no pudo ser eliminado.'; }
            else
            { $link_mysqli = conectar();
              $link_mysqli->query("
                DELETE FROM proglote
                WHERE cdglote = '".$progLote_cdglote."'
                AND sttlote = '0'");
                
              if ($link_mysqli->affected_rows > 0)
              { $msg_alert = 'El lote '.$progLote_idlote.' fue eliminado con exito.'; }
              else
              { $msg_alert = 'El lote '.$progLote_idlote.' NO fue eliminado.'; }
            }
          } else
          { $msg_alert = 'No cuentas con permisos'; }
        }
      }
    } 

    if ($_POST['btn_submit'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if (strlen($progLote_lote) > 0)
        { $link_mysqli = conectar();
          $progLoteSelect = $link_mysqli->query("
            SELECT * FROM proglote
            WHERE cdgbloque = '".$progLote_cdgbloque."'
            AND idlote = '".$progLote_idlote."'");
            
          if ($progLoteSelect->num_rows > 0)
          { $regProgLote = $progLoteSelect->fetch_object();
            
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
            { $msg_alert = 'El lote '.$progLote_idlote.' fue actualizado exito.'; }
            else
            { $msg_alert = 'El lote '.$progLote_idlote.' NO fue actualizado (Posiblemente liberado).'; }
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
            { $msg_alert = 'El lote '.$progLote_idlote.' fue insertado con exito.'; }
            else
            { $msg_alert = 'El lote '.$progLote_idlote.' NO fue insertado.'; }
          }

          $link_mysqli = conectar();
          $progLoteSelect = $link_mysqli->query("
            SELECT MAX(idlote) AS idlote FROM proglote
            WHERE cdgbloque = '".$progLote_cdgbloque."'");

          $regLoteSelect = $progLoteSelect->fetch_object();

          $progLote_idlote = $regLoteSelect->idlote+1;
        }
      } else
      { $msg_alert = 'No cuentas con permisos de escritura'; }
    }
  
    if (substr($sistModulo_permiso,0,1) == 'r')
    { // Filtro de bloques
      $link_mysqli = conectar(); 
      $querySelect = $link_mysqli->query("
        SELECT * FROM progbloque
         WHERE sttbloque = '1'
      ORDER BY bloque");
    
      while ($regQuery = $querySelect->fetch_object()) 
      { $nBloque++; 

        $progBloques_idbloque[$nBloque] = $regQuery->idbloque;
        $progBloques_bloque[$nBloque] = $regQuery->bloque;
        $progBloques_cdgbloque[$nBloque] = $regQuery->cdgbloque; }

      $nBloques = $querySelect->num_rows; 

      // Filtro de tarimas por bloque 
      $querySelect = $link_mysqli->query("
        SELECT tarima 
          FROM proglote
         WHERE cdgbloque = '".$progLote_cdgbloque."'
      GROUP BY tarima
      ORDER BY tarima");
    
      $nTarima = 1;
      while ($regQuery = $querySelect->fetch_object())
      { $progLotes_tarima[$nTarima] = $regQuery->tarima;

        $nTarima++; }

      $nTarimas = $querySelect->num_rows; 

      //NoOP por Lote
      $prodLoteSelect = $link_mysqli->query("
        SELECT prodlote.cdglote, 
               prodlote.noop
          FROM proglote, 
               prodlote
         WHERE proglote.cdglote = prodlote.cdglote AND
               proglote.cdgbloque = '".$progLote_cdgbloque."'");

      while ($regProdLote = $prodLoteSelect->fetch_object())
      { $prodLote_noop[$regProdLote->cdglote] = $regProdLote->noop; }

      // Contenido del bloque
      for ($nTarima = 1; $nTarima <= $nTarimas; $nTarima++)
      { $link_mysqli = conectar();
        $progLoteSelect = $link_mysqli->query("
          SELECT * FROM proglote
           WHERE cdgbloque = '".$progLote_cdgbloque."' AND 
                 tarima = '".$progLotes_tarima[$nTarima]."'        
        ORDER BY cdglote"); 

        $idlote = 1;
        while ($regProgLote = $progLoteSelect->fetch_object())
        { $progLotes_idlote[$nTarima][$idlote] = $regProgLote->idlote;
          $progLotes_lote[$nTarima][$idlote] = $regProgLote->lote;
          $progLotes_longitud[$nTarima][$idlote] = $regProgLote->longitud;
          $progLotes_amplitud[$nTarima][$idlote] = $regProgLote->amplitud;
          $progLotes_espesor[$nTarima][$idlote] = $regProgLote->espesor;
          $progLotes_encogimiento[$nTarima][$idlote] = $regProgLote->encogimiento;
          $progLotes_peso[$nTarima][$idlote] = $regProgLote->peso;
          $progLotes_fchmovimiento[$nTarima][$idlote] = $regProgLote->fchmovimiento;
          $progLotes_cdglote[$nTarima][$idlote] = $regProgLote->cdglote;
          $progLotes_sttlote[$nTarima][$idlote] = $regProgLote->sttlote;        

          $idlote++; }

        $idslote[$nTarima] = $progLoteSelect->num_rows; }
    }
  
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
    
    for ($nBloque = 1; $nBloque <= $nBloques; $nBloque++)
    { echo '
                <option value="'.$progBloques_cdgbloque[$nBloque].'"';
            
      if ($progLote_cdgbloque == $progBloques_cdgbloque[$nBloque]) { echo ' selected="selected"'; }
      echo '>'.$progBloques_bloque[$nBloque].'</option>'; }
    
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
              <input type="text" style="width:100px;" maxlength="20" id="txt_tarima" name="txt_tarima" value="'.$progLote_tarima.'" title="Tarima contenedora" required /></td></tr>
        <tbody>
        <tfoot>
          <tr><td colspan="2" align="right"><input type="submit" id="btn_submit" name="btn_submit" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>
    
      <table align="center">
        <thead>
          <tr align="left">
            <th><label for="lbl_ttlidlote">No. Lote</label></th>
            <th colspan="2"><label for="lbl_ttllote">Referencia</label></th>            
            <th><label for="lbl_ttllongitud">Longitud</label></th>
            <th><label for="lbl_ttlamplitud">Amplitud</label></th>
            <th><label for="lbl_ttlespesor">Espesor</label></th>
            <th><label for="lbl_ttlencogimiento">Encogimiento</label></th>
            <th><label for="lbl_ttlpeso">Peso</label></th>
            <th><label for="lbl_ttlrendimiento">Rendimiento</label></th>
            <th colspan="2"><label for="lbl_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';
  
    if ($nTarimas > 0)
    { for ($nTarima = 1; $nTarima <= $nTarimas; $nTarima++)
      { for ($idlote = 1; $idlote <= $idslote[$nTarima]; $idlote++)
        { echo '
          <tr align="right">
            <td><strong>'.$progLotes_idlote[$nTarima][$idlote].'</strong></td>
            <td><strong>'.$progLotes_lote[$nTarima][$idlote].'</strong></td>
            <td><strong>'.$prodLote_noop[$progLotes_cdglote[$nTarima][$idlote]].'</strong></td>
            <td>'.number_format($progLotes_longitud[$nTarima][$idlote],2).' mts</td>
            <td>'.number_format($progLotes_amplitud[$nTarima][$idlote]).' mm</td>
            <td>'.number_format($progLotes_espesor[$nTarima][$idlote]).' micras</td>
            <td>'.$progLotes_encogimiento[$nTarima][$idlote].' %</td>
            <td>'.number_format($progLotes_peso[$nTarima][$idlote],3).' kgs</td>
            <td>'.number_format((($progLotes_longitud[$nTarima][$idlote]/$progLotes_peso[$nTarima][$idlote])*($progLotes_amplitud[$nTarima][$idlote]/1000)),3).' mts2</td>';

          if ((int)$progLotes_sttlote[$nTarima][$idlote] > 0)
          { if ((int)$progLotes_sttlote[$nTarima][$idlote] == 9)
            { echo '
            <td align="center"><a href="progLote.php?cdglote='.$progLotes_cdglote[$nTarima][$idlote].'">'.$png_search.'</a></td>
            <td align="center">'.$png_gear.'</td>'; }

            if ((int)$progLotes_sttlote[$nTarima][$idlote] == 8)
            { echo '
            <td align="center"><a href="progLote.php?cdglote='.$progLotes_cdglote[$nTarima][$idlote].'">'.$png_search.'</a></td>
            <td align="center">'.$png_calendar.'</td>'; }

            if ((int)$progLotes_sttlote[$nTarima][$idlote] == 7)
            { echo '
            <td align="center"><a href="progLote.php?cdglote='.$progLotes_cdglote[$nTarima][$idlote].'">'.$png_search.'</a></td>
            <td align="center"><a href="progLote.php?cdglote='.$progLotes_cdglote[$nTarima][$idlote].'&proceso=undo">'.$png_blue_accept.'</a></td>'; } 

            if ((int)$progLotes_sttlote[$nTarima][$idlote] == 1)
            { echo '
            <td align="center"><a href="progLote.php?cdglote='.$progLotes_cdglote[$nTarima][$idlote].'">'.$png_search.'</a></td>
            <td align="center"><a href="progLote.php?cdglote='.$progLotes_cdglote[$nTarima][$idlote].'&proceso=update">'.$png_power_blue.'</a></td>'; }

          } else
          { echo '
            <td align="center"><a href="progLote.php?cdglote='.$progLotes_cdglote[$nTarima][$idlote].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td align="center"><a href="progLote.php?cdglote='.$progLotes_cdglote[$nTarima][$idlote].'&proceso=update">'.$png_power_black.'</a></td>'; }

          echo '</tr>';

          $progLote_sumalongitud[$nTarima] += $progLotes_longitud[$nTarima][$idlote];
          $progLote_sumapeso[$nTarima] += $progLotes_peso[$nTarima][$idlote]; 
          $progLote_sumarendimiento[$nTarima] += (($progLotes_longitud[$nTarima][$idlote]/$progLotes_peso[$nTarima][$idlote])*($progLotes_amplitud[$nTarima][$idlote]/1000)); }

        echo '
          <tr align="right">
            <td></td>
            <th colspan="2">Tarima '.$progLotes_tarima[$nTarima].' : '.$idslote[$nTarima].' Unidades</th>
            <th colspan="2">'.number_format($progLote_sumalongitud[$nTarima],2).' Mts</th>
            <th colspan="3">'.number_format($progLote_sumapeso[$nTarima],3).' Kgs</th>
            <th>'.number_format(($progLote_sumarendimiento[$nTarima]/$idslote[$nTarima]),3).' Mts2</th>
            <td colspan="2"></td></tr>';

        $idlotes = $idlotes+$idslote[$nTarima]; 
      }      
    }

    echo '
          </tbody>
          <tfoot>
            <tr><th colspan="12" align="right">              
                <label for="lbl_ppgdatos"> ['.$idlotes.'] Bobinas encontrados</label></th></tr>
          </tfoot>
        </table>
      </form>'; 

    if ($msg_alert != '')
    { echo '
      <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }


  } else
  { echo '
    <br/><div align="center"><h1>'.utf8_decode('Módulo no encontrado o bloqueado.').'</h1></div>'; }
?>
  </body>	
</html>