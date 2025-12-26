<?php
  header('Content-Type: text/html; charset=ISO-8859-1'); 
  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '20010';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    $pdtoDiseno_iddiseno = trim($_POST['txt_iddiseno']);
    $pdtoDiseno_diseno = trim($_POST['txt_diseno']);
    $pdtoDiseno_proyecto = trim($_POST['txt_proyecto']);
    $pdtoDiseno_cdgsustrato = $_POST['slc_cdgsustrato'];
    $pdtoDiseno_alpaso = $_POST['txt_alpaso'];
    $pdtoDiseno_alpasof = $_POST['txt_alpasof'];
    $pdtoDiseno_registro = $_POST['txt_registro'];
    $pdtoDiseno_cdgholograma = $_POST['slc_cdgholograma'];
    $pdtoDiseno_notintas = $_POST['txt_notintas'];
    $pdtoDiseno_ancho = $_POST['txt_ancho'];
    $pdtoDiseno_anchof = $_POST['txt_anchof'];
    $pdtoDiseno_alto = $_POST['txt_alto'];    
    $pdtoDiseno_altof = $_POST['txt_altof'];
    $pdtoDiseno_dobles = $_POST['txt_dobles'];
    $pdtoDiseno_empalme = $_POST['txt_empalme'];
    $pdtoDiseno_rollo = $_POST['txt_rollo'];
    $pdtoDiseno_paquete = $_POST['txt_paquete'];


    if ($_GET['cdgdiseno'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $link_mysqli = conectar();
        $pdtoDisenoSelect = $link_mysqli->query("
          SELECT * FROM pdtodiseno
          WHERE cdgdiseno = '".$_GET['cdgdiseno']."'");

        if ($pdtoDisenoSelect->num_rows > 0)
        { $regQuery = $pdtoDisenoSelect->fetch_object();

          $pdtoDiseno_iddiseno = $regQuery->iddiseno;
          $pdtoDiseno_diseno = $regQuery->diseno;
          $pdtoDiseno_proyecto = $regQuery->proyecto;
          $pdtoDiseno_notintas = $regQuery->notintas;
          $pdtoDiseno_cdgsustrato = $regQuery->cdgsustrato;
          $pdtoDiseno_alpaso = $regQuery->alpaso;
          $pdtoDiseno_alpasof = $regQuery->alpasof;
          $pdtoDiseno_registro = $regQuery->registro;
          $pdtoDiseno_cdgholograma = $regQuery->cdgholograma;
          $pdtoDiseno_ancho = $regQuery->ancho;
          $pdtoDiseno_anchof = $regQuery->anchof;
          $pdtoDiseno_alto = $regQuery->alto;
          $pdtoDiseno_altof = $regQuery->altof;
          $pdtoDiseno_dobles = $regQuery->dobles;
          $pdtoDiseno_empalme = $regQuery->empalme;
          $pdtoDiseno_rollo = $regQuery->rollo;
          $pdtoDiseno_paquete = $regQuery->paquete;
          $pdtoDiseno_cdgdiseno = $regQuery->cdgdiseno;
          $pdtoDiseno_sttdiseno = $regQuery->sttdiseno;

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
              { $msg_windows = utf8_decode('El registro fue actualizado en su status.'); }
              else
              { $msg_windows = utf8_decode('El registro NO fue actualizado (status).'); }
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
              { $msg_windows = utf8_decode('El registro no puede eliminarse, tiene otros registros ligados.'); }
              else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  DELETE FROM pdtodiseno
                  WHERE cdgdiseno = '".$pdtoDiseno_cdgdiseno."' AND
                    sttdiseno = '0'");

                if ($link_mysqli->affected_rows > 0)
                { $msg_windows = utf8_decode('El registro fue eliminado con exito.'); }
                else
                { $msg_windows = utf8_decode('El registro NO fue eliminado.'); }
              }
            } else
            { $msg_windows = $msg_nodelete; }
          }
        }
      } else
      { $msg_windows = $msg_noread; }
    }

    if ($_POST['btn_salvar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if (strlen($pdtoDiseno_iddiseno) > 0 AND strlen($pdtoDiseno_diseno) > 0)
        { $link_mysqli = conectar();
          $pdtoDisenoSelect = $link_mysqli->query("
            SELECT * FROM pdtodiseno
            WHERE iddiseno = '".$pdtoDiseno_iddiseno."'");

          if ($pdtoDisenoSelect->num_rows > 0)
          { $regQuery = $pdtoDisenoSelect->fetch_object();

            $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE pdtodiseno
              SET diseno = '".$pdtoDiseno_diseno."',
                proyecto = '".$pdtoDiseno_proyecto."',
                cdgsustrato = '".$pdtoDiseno_cdgsustrato."',
                alpaso = '".$pdtoDiseno_alpaso."',
                alpasof = '".$pdtoDiseno_alpasof."',
                registro = '".$pdtoDiseno_registro."',
                cdgholograma = '".$pdtoDiseno_cdgholograma."',
                ancho = '".$pdtoDiseno_ancho."',
                anchof = '".$pdtoDiseno_anchof."',
                altof = '".$pdtoDiseno_altof."',                
                alto = '".$pdtoDiseno_alto."',
                dobles = '".$pdtoDiseno_dobles."',
                empalme = '".$pdtoDiseno_empalme."',
                notintas = '".$pdtoDiseno_notintas."',
                paquete = '".$pdtoDiseno_paquete."',
                rollo = '".$pdtoDiseno_rollo."'
              WHERE cdgdiseno = '".$regQuery->cdgdiseno."'
              AND sttdiseno = '1'");

            if ($link_mysqli->affected_rows > 0)
            { $msg_windows .= utf8_decode('El registro fue actualizado con exito.'); }
            else
            { $msg_windows .= utf8_decode('El registro NO fue actualizado.'); }
          } else
          { for ($cdgdiseno = 1; $cdgdiseno <= 1000; $cdgdiseno++)
            { $pdtoDiseno_cdgdiseno = str_pad($cdgdiseno,3,'0',STR_PAD_LEFT);

              if ($cdgdiseno > 999)
              { $msg_windows = utf8_decode('El registro NO fue insertado, contacta a soporte.'); }
              else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  INSERT INTO pdtodiseno
                    (iddiseno, diseno, proyecto, cdgsustrato, alpaso, alpasof, registro, cdgholograma, ancho, anchof, alto, altof, dobles, empalme, notintas, rollo, paquete, cdgdiseno)
                  VALUES
                    ('".$pdtoDiseno_iddiseno."', '".$pdtoDiseno_diseno."', '".$pdtoDiseno_proyecto."', '".$pdtoDiseno_cdgsustrato."', '".$pdtoDiseno_alpaso."', '".$pdtoDiseno_alpasof."', '".$pdtoDiseno_registro."', '".$pdtoDiseno_cdgholograma."', '".$pdtoDiseno_ancho."', '".$pdtoDiseno_anchof."', '".$pdtoDiseno_alto."', '".$pdtoDiseno_altof."', '".$pdtoDiseno_dobles."', '".$pdtoDiseno_empalme."', '".$pdtoDiseno_notintas."', '".$pdtoDiseno_rollo."', '".$pdtoDiseno_paquete."', '".$pdtoDiseno_cdgdiseno."')");

                if ($link_mysqli->affected_rows > 0)
                { $msg_windows = utf8_decode('El registro fue insertado satisfactoriamente.');
                  $cdgdiseno = 1000;
                } else
                { $msg_windows = utf8_decode('El registro NO fue insertado.'); }
              }
            }
          }
        }
      } else
      { $msg_windows = $msg_norewrite; }
    }

    // Filtro de diseños
    if (substr($sistModulo_permiso,0,1) == 'r')
    { // Filtro de Hologramas 
      $link_mysqli = conectar();
      $pdtoHologramaSelect = $link_mysqli->query("
        SELECT * FROM pdtoholograma
        ORDER BY sttholograma DESC,
          idholograma");

      if ($pdtoHologramaSelect->num_rows > 0)
      { $idHolograma = 1;

        while ($regPdtoHolograma = $pdtoHologramaSelect->fetch_object())
        { $pdtoHologramas_holograma[$idHolograma] = $regPdtoHolograma->holograma;
          $pdtoHologramas_cdgholograma[$idHolograma] = $regPdtoHolograma->cdgholograma;

          $pdtoHologramas_hologramas[$regPdtoHolograma->cdgholograma] = $regPdtoHolograma->holograma;

          $idHolograma++; }

        $numHologramas = $pdtoHologramaSelect->num_rows; }
      // Fin del filtro de hologramas
      
      // Filtro de Sustratos
      $link_mysqli = conectar();
      $pdtoSustratoSelect = $link_mysqli->query("
        SELECT * FROM pdtosustrato
        ORDER BY idsustrato");

      if ($pdtoSustratoSelect->num_rows > 0)
      { $idSustrato = 1;

        while ($regPdtoSustrato = $pdtoSustratoSelect->fetch_object())
        { $pdtoSustratos_sustrato[$idSustrato] = $regPdtoSustrato->sustrato;
          $pdtoSustratos_cdgsustrato[$idSustrato] = $regPdtoSustrato->cdgsustrato;

          $pdtoSustratos_sustratos[$regPdtoSustrato->cdgsustrato] = $regPdtoSustrato->sustrato;

          $idSustrato++; }

        $numSustratos = $pdtoSustratoSelect->num_rows; }
      // Fin del filtro de sustratos

      if ($_POST['checkbox_vertodos'])
      { $vertodo = 'checked';
        // Filtrado completo
        $link_mysqli = conectar();
        $pdtoDisenoSelect = $link_mysqli->query("
        SELECT * FROM pdtodiseno
        ORDER BY sttdiseno DESC,
          iddiseno,
          diseno");
      } else
      { // Buscar coincidencias
        $link_mysqli = conectar();
        $pdtoDisenoSelect = $link_mysqli->query("
        SELECT * FROM pdtodiseno
        WHERE sttdiseno = '1'
        ORDER BY iddiseno,
          diseno"); }

      if ($pdtoDisenoSelect->num_rows > 0)
      { $idDiseno = 1;
        while ($regQuery = $pdtoDisenoSelect->fetch_object())
        { $pdtoDisenos_iddiseno[$idDiseno] = $regQuery->iddiseno;
          $pdtoDisenos_diseno[$idDiseno] = $regQuery->diseno;
          $pdtoDisenos_proyecto[$idDiseno] = $regQuery->proyecto;
          $pdtoDisenos_cdgsustrato[$idDiseno] = $regQuery->cdgsustrato;
          $pdtoDisenos_alpaso[$idDiseno] = $regQuery->alpaso;
          $pdtoDisenos_alpasof[$idDiseno] = $regQuery->alpasof;
          $pdtoDisenos_registro[$idDiseno] = $regQuery->registro;
          $pdtoDisenos_cdgholograma[$idDiseno] = $regQuery->cdgholograma;
          $pdtoDisenos_ancho[$idDiseno] = $regQuery->ancho;
          $pdtoDisenos_anchof[$idDiseno] = $regQuery->anchof;
          $pdtoDisenos_alto[$idDiseno] = $regQuery->alto;
          $pdtoDisenos_altof[$idDiseno] = $regQuery->altof;
          $pdtoDisenos_empalme[$idDiseno] = $regQuery->empalme;
          $pdtoDisenos_notintas[$idDiseno] = $regQuery->notintas;
          $pdtoDisenos_cdgdiseno[$idDiseno] = $regQuery->cdgdiseno;
          $pdtoDisenos_sttdiseno[$idDiseno] = $regQuery->sttdiseno;

          $idDiseno++; }

        $numDisenos = $pdtoDisenoSelect->num_rows;
      }
    }
    // Fin del filtro de diseños

    echo '
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br />
    <form id="frm_pdtodiseno" name="frm_pdtodiseno" method="POST" action="pdtoDiseno.php">
      <table align="center">
        <thead>
          <tr><th align="left" colspan="2">RC-02-PSG-7.2 Diseños</th></tr>
        </thead>
        <tbody>
          <tr><td><label for="lbl_iddiseno">Dise&ntilde;o</label><br />
              <input type="text" style="width:120px;" maxlength="24" id="txt_iddiseno" name="txt_iddiseno" value="'.$pdtoDiseno_iddiseno.'" title="Identificador de dise&nacute;o" autofocus="autofocus" required/></td>
            <td><label for="lbl_proyecto">Proyecto</label><br />
              <input type="text" style="width:120px;" maxlength="24" id="txt_proyecto" name="txt_proyecto" value="'.$pdtoDiseno_proyecto.'" title="Proyecto al que pertenece" required/></td></tr>
          <tr><td colspan="2"><label for="lbl_diseno">Descripción</label><br />
              <input type="text" style="width:280px;" maxlength="60" id="txt_diseno" name="txt_diseno" value="'.$pdtoDiseno_diseno.'" title="Descripcion del diseno" required/></td></tr>
          <tr><td colspan="2">
        </tbody>
      </table><br />
      
      <table align="center">
        <tbody>
          <tr><td>
              <table align="center">
                <thead>
                  <tr><th align="center" colspan="4"><label for="lbl_ttlimpresion">Información para Impresión</label></th></tr>
                </thead>
                <tbody>
                  <tr><td><label for="lbl_ttlancho">Ancho</label><br/>
                      <input type="text" style="width:60px;text-align:right;" maxlength="12" id="txt_ancho" name="txt_ancho" value="'.$pdtoDiseno_ancho.'" title="Ancho en milimetros (Ancho plano)" required/></td>                    
                    <td><label for="lbl_ttlalto">Alto</label><br/>
                      <input type="text" style="width:60px;text-align:right;" maxlength="12" id="txt_alto" name="txt_alto" value="'.$pdtoDiseno_alto.'" title="Alto en milimetros" required/></td>
                    <td><label for="lbl_registro">Registro</label><br/>
                      <input type="text" style="width:60px;;text-align:right;" maxlength="12" id="txt_registro" name="txt_registro" value="'.$pdtoDiseno_registro.'" title="Registro" required/></td>
                    <td><label for="lbl_notintas">No. Tintas</label><br/>
                      <input type="text" style="width:60px;;text-align:right;" maxlength="12" id="txt_notintas" name="txt_notintas" value="'.$pdtoDiseno_notintas.'" title="Número de tintas" required/></td></tr>
                  <tr><td colspan="3"><label for="lbl_ttlsustrato">Sustrato</label><br/>
                      <select style="width:180px" id="slc_cdgsustrato" name="slc_cdgsustrato">
                        <option value="">Selecciona una opción</option>';

    for ($idSustrato = 1; $idSustrato <= $numSustratos; $idSustrato++)
    { echo '
                        <option value="'.$pdtoSustratos_cdgsustrato[$idSustrato].'"';

      if ($pdtoDiseno_cdgsustrato == $pdtoSustratos_cdgsustrato[$idSustrato]) { echo ' selected="selected"'; }
      echo '>'.$pdtoSustratos_sustrato[$idSustrato].'</option>'; }

    echo '
                      </select></td>
                    <td><label for="lbl_alpaso">Al paso</label><br/>
                      <input type="text" style="width:60px;text-align:right;" maxlength="12" id="txt_alpaso" name="txt_alpaso" value="'.$pdtoDiseno_alpaso.'" title="Impresiones al paso" required/></td></tr>
                </tbody>
              </table>
            </td>
            <td>
              <table align="center">
                <thead>
                  <tr><th align="center" colspan="4"><label for="lbl_ttlimpresion">Información para Suaje o Fusión</label></th></tr>
                </thead>
                <tbody>
                  <tr><td><label for="lbl_ttlanchof">Ancho final</label><br/>
                      <input type="text" style="width:60px;text-align:right;" maxlength="12" id="txt_anchof" name="txt_anchof" value="'.$pdtoDiseno_anchof.'" title="Ancho final en milimetros" required/></td>
                    <td><label for="lbl_ttlaltof">Alto final</label><br/>
                      <input type="text" style="width:60px;text-align:right;" maxlength="12" id="txt_altof" name="txt_altof" value="'.$pdtoDiseno_altof.'" title="Altura final en milimetros" required/></td>
                    <td><label for="lbl_ttlempalme">Empalme</label><br/>
                      <input type="text" style="width:60px;text-align:right;" maxlength="12" id="txt_empalme" name="txt_empalme" value="'.$pdtoDiseno_empalme.'" title="Empalme para fusión en milimetros" required/></td>
                    <td><label for="lbl_ttlempalme">Dobles</label><br/>
                      <input type="text" style="width:60px;text-align:right;" maxlength="12" id="txt_dobles" name="txt_dobles" value="'.$pdtoDiseno_dobles.'" title="Dobles para fusión en milimetros" required/></td></tr>
                  <tr><td colspan="3"><label for="lbl_ttlholograma">Holograma</label><br/>
                      <select style="width:180px" id="slc_cdgholograma" name="slc_cdgholograma">
                        <option value="">Selecciona una opción</option>';

    for ($idHolograma = 1; $idHolograma <= $numHologramas; $idHolograma++)
    { echo '
                        <option value="'.$pdtoHologramas_cdgholograma[$idHolograma].'"';

      if ($pdtoDiseno_cdgholograma == $pdtoHologramas_cdgholograma[$idHolograma]) { echo ' selected="selected"'; }
      echo '>'.$pdtoHologramas_holograma[$idHolograma].'</option>'; }

    echo '
                      </select></td>
                    <td><label for="lbl_alpaso">Al paso</label><br/>
                      <input type="text" style="width:60px;text-align:right;" maxlength="12" id="txt_alpasof" name="txt_alpasof" value="'.$pdtoDiseno_alpasof.'" title="Etiquetas al paso" required/></td></tr>
                </tbody>
              </table>
            </td></tr>
          <tr><td>
              <table align="center">
                <thead>
                  <tr><th align="center" colspan="3"><label for="lbl_ttlimpresion">Presentación pieza Enrollada</label></th></tr>
                </thead>
                <tbody>
                  <tr><td><label for="lbl_ttlrollo">Millares</label><br/>
                      <input type="text" style="width:60px;text-align:right;" maxlength="12" id="txt_rollo" name="txt_rollo" value="'.$pdtoDiseno_rollo.'" title="Millares por rollo" required/></td></tr>
                </tbody>
              </table></td>
            <td>
              <table align="center">
                <thead>
                  <tr><th align="center" colspan="3"><label for="lbl_ttlimpresion">Presentación pieza Contada</label></th></tr>
                </thead>
                <tbody>
                  <tr><td><label for="lbl_ttlpaquete">Millares</label><br/>
                      <input type="text" style="width:60px;text-align:right;" maxlength="12" id="txt_paquete" name="txt_paquete" value="'.$pdtoDiseno_paquete.'" title="Millares por paquete" required/></td></tr>                
                </tbody>
              </table></td></tr>
        <tbody>
        <tfoot>
          <tr><td align="right" colspan="2"><input type="submit" id="btn_salvar" name="btn_salvar" value="Salvar" /></td></tr>
        </tfoot>
      </table><br />

      <table align="center">
        <thead>
          <tr><td colspan="2"></td>
            <th colspan="2">Milímetros</th>
            <th colspan="4" align="right">
              <input type="checkbox" name="checkbox_vertodos" id="checkbox_vertodos" onclick="document.frm_pdtodiseno.submit()" '.$vertodo.'>
              <label for="lbl_vertodo">Ver todo</label></th></tr>
          <tr align="left">
            <th><label for="lbl_ttliddiseno">Dise&ntilde;o</label></th>
            <th><label for="lbl_ttldiseno">Descripción</label></th>
            <th><label for="lbl_ttlancho">Ancho</label></th>
            <th><label for="lbl_ttlalto">Alto</label></th>
            <th colspan="4"><label for="lbl_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';

    if ($numDisenos > 0)
    { for ($idDiseno=1; $idDiseno<=$numDisenos; $idDiseno++)
      { echo '
          <tr align="center">
            <td align="left"><strong>'.$pdtoDisenos_iddiseno[$idDiseno].'</strong></td>
            <td align="left">
              <details>
                <summary><strong>'.$pdtoDisenos_diseno[$idDiseno].'</strong></summary>
                <em>Proyecto:</em> '.$pdtoDisenos_proyecto[$idDiseno].'<br>
                <em>Sustrato:</em> '.$pdtoSustratos_sustratos[$pdtoDisenos_cdgsustrato[$idDiseno]].'<br>
                <em>Holograma:</em> '.$pdtoHologramas_hologramas[$pdtoDisenos_cdgholograma[$idDiseno]].'
              </details>
            <td align="right">'.$pdtoDisenos_ancho[$idDiseno].' /'.$pdtoDisenos_anchof[$idDiseno].'</td>
            <td align="right">'.$pdtoDisenos_alto[$idDiseno].' /'.$pdtoDisenos_altof[$idDiseno].'</td>';

        if ((int)$pdtoDisenos_sttdiseno[$idDiseno] > 0)
        { echo '
            <td><a href="pdtoDiseno.php?cdgdiseno='.$pdtoDisenos_cdgdiseno[$idDiseno].'">'.$png_search.'</a></td>
            <td><a href="pdtoRodillo.php?cdgdiseno='.$pdtoDisenos_cdgdiseno[$idDiseno].'"><img alt="Juegos de rodillos" src="../img_sistema/cilindro.png" height="16" border="0"/></a></td>
            <td><a href="pdtoImpresion.php?cdgdiseno='.$pdtoDisenos_cdgdiseno[$idDiseno].'"><img alt="Impresiones" src="../img_sistema/impresion.png" height="16" border="0"/></a></td>
            <td><a href="pdtoDiseno.php?cdgdiseno='.$pdtoDisenos_cdgdiseno[$idDiseno].'&proceso=update">'.$png_power_blue.'</a></td>';
        } else
        { echo '
            <td><a href="pdtoDiseno.php?cdgdiseno='.$pdtoDisenos_cdgdiseno[$idDiseno].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td colspan="2">&nbsp;</td>
            <td><a href="pdtoDiseno.php?cdgdiseno='.$pdtoDisenos_cdgdiseno[$idDiseno].'&proceso=update">'.$png_power_black.'</a></td>'; }

        echo '</tr>';
      }
    }

    echo '
        </tbody>
        <tfoot>
          <tr><th colspan="8" align="right">
              <label for="lbl_ppgdatos">['.$numDisenos.'] Registros encontrados</label></th></tr>
        </tfoot>
      </table>
    </form>';

    if ($msg_windows != '')
    { echo '7
    <script type="text/javascript"> alert("'.$msg_windows.'"); </script>'; }
  } else
  { echo '
    <br/><div align="center"><h1>'.utf8_decode('Módulo no encontrado o bloqueado.').'</h1></div>'; }
  
  echo '
  </body> 
</html>';
?>