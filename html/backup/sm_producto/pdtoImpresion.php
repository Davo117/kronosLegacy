<?php
  header('Content-Type: text/html; charset=ISO-8859-1'); 
  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '20020';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']); 
    
    $pdtoImpresion_cdgdiseno = $_POST['slc_cdgdiseno'];
    $pdtoImpresion_idimpresion = trim($_POST['txt_idimpresion']);
    $pdtoImpresion_impresion = trim($_POST['txt_impresion']);    
    $pdtoImpresion_periodo = $_POST['txt_periodo'];
    $pdtoImpresion_cdgproducto = $_POST['txt_cdgproducto'];
      
    if ($_GET['cdgimpresion']) { $pdtoImpresion_cdgimpresion = $_GET['cdgimpresion']; }    
    if ($_GET['cdgdiseno']) { $pdtoImpresion_cdgdiseno = $_GET['cdgdiseno']; }
    
    // Si doy click en alguna Impresión //
    if (substr($sistModulo_permiso,0,1) == 'r')
    { if ($_GET['cdgimpresion'])
      { $link_mysqli = conectar();
        $pdtoImpresionSelect = $link_mysqli->query("
          SELECT pdtoimpresion.cdgdiseno,
            pdtodiseno.alto,
            pdtodiseno.notintas,
            pdtoimpresion.idimpresion,
            pdtoimpresion.impresion,
            pdtoimpresion.periodo,
            pdtoimpresion.cdgproducto,
            pdtoimpresion.cdgimpresion,
            pdtoimpresion.sttimpresion
          FROM pdtodiseno,
            pdtoimpresion
          WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
            pdtoimpresion.cdgimpresion = '".$pdtoImpresion_cdgimpresion."'");
        
        if ($pdtoImpresionSelect->num_rows > 0)
        { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

          $pdtoImpresion_cdgdiseno = $regPdtoImpresion->cdgdiseno;
          $pdtoImpresion_alto = $regPdtoImpresion->alto;
          $pdtoImpresion_notintas = $regPdtoImpresion->notintas;
          $pdtoImpresion_idimpresion = $regPdtoImpresion->idimpresion;
          $pdtoImpresion_impresion = $regPdtoImpresion->impresion;
          $pdtoImpresion_periodo = $regPdtoImpresion->periodo;
          $pdtoImpresion_cdgproducto = $regPdtoImpresion->cdgproducto;
          $pdtoImpresion_cdgimpresion = $regPdtoImpresion->cdgimpresion;
          $pdtoImpresion_sttimpresion = $regPdtoImpresion->sttimpresion;

          $link_mysqli = conectar();
          $pdtoImpresionTntSelect = $link_mysqli->query("
            SELECT * FROM pdtoimpresiontnt
            WHERE cdgimpresion = '".$pdtoImpresion_cdgimpresion."'");

          if ($pdtoImpresionTntSelect->num_rows > 0)
          { while ($regPdtoImpresionTnt = $pdtoImpresionTntSelect->fetch_object())
            { $pdtoImpresionTnt_cdgtinta[$regPdtoImpresionTnt->notinta] = $regPdtoImpresionTnt->cdgtinta;
              $pdtoImpresionTnt_consumo[$regPdtoImpresionTnt->notinta] = $regPdtoImpresionTnt->consumo;
              $pdtoImpresionTnt_disolvente[$regPdtoImpresionTnt->notinta] = $regPdtoImpresionTnt->disolvente;
              $pdtoImpresionTnt_viscosidad[$regPdtoImpresionTnt->notinta] = $regPdtoImpresionTnt->viscosidad;
              $pdtoImpresionTnt_temperatura[$regPdtoImpresionTnt->notinta] = $regPdtoImpresionTnt->temperatura; }
          }

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($pdtoImpresion_sttimpresion == '1')
              { $pdtoImpresion_newsttimpresion = '0'; }
            
              if ($pdtoImpresion_sttimpresion == '0')
              { $pdtoImpresion_newsttimpresion = '1'; }
              
              $link_mysqli = conectar();
              $link_mysqli->query("
                UPDATE pdtoimpresion
                SET sttimpresion = '".$pdtoImpresion_newsttimpresion."' 
                WHERE cdgimpresion = '".$pdtoImpresion_cdgimpresion."'");
                
              if ($link_mysqli->affected_rows > 0)
              { $msg_alert = 'La impresion fue actualizada en su status.'; }
              else
              { $msg_alert = 'La impresion NO fue actualizada (status).'; }
            } else
            { $msg_alert = $msg_norewrite; }            
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $link_mysqli = conectar();
              $prodLoteSelect = $link_mysqli->query("
                SELECT * FROM prodlote
                WHERE cdgproducto = '".$pdtoImpresion_cdgimpresion."'");
                
              if ($prodLoteSelect->num_rows > 0)
              { $msg_alert = 'La impresion cuenta con Lotes ligados, no debe ser eliminada.'; }
              else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  DELETE FROM pdtoimpresion
                  WHERE cdgimpresion = '".$pdtoImpresion_cdgimpresion."'
                  AND sttimpresion = '0'");
                  
                if ($link_mysqli->affected_rows > 0)
                { $msg_alert = 'La impresion fue eliminada con exito.'; }
                else
                { $msg_alert = 'La impresion NO fue eliminada.'; }
              }
            } else
            { $msg_alert = $msg_nodelete; }              
          }
        }
      } 
    } else
    { $msg_alert = $msg_noread; }

    // Botón salvar //     
    if ($_POST['btn_submit'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if (strlen($pdtoImpresion_cdgdiseno) > 0 AND strlen($pdtoImpresion_idimpresion) > 0)
        { // Buscar coincidencias
          $link_mysqli = conectar();
          $pdtoImpresionSelect = $link_mysqli->query("
            SELECT pdtodiseno.notintas,
                   pdtoimpresion.cdgimpresion
            FROM pdtodiseno,
                 pdtoimpresion
            WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
                 (pdtoimpresion.cdgdiseno = '".$pdtoImpresion_cdgdiseno."' AND 
                  pdtoimpresion.idimpresion = '".$pdtoImpresion_idimpresion."')");
            
          if ($pdtoImpresionSelect->num_rows > 0)
          { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();
            
            $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE pdtoimpresion
              SET impresion = '".$pdtoImpresion_impresion."',
                periodo = '".$pdtoImpresion_periodo."',
                cdgproducto = '".$pdtoImpresion_cdgproducto."'
              WHERE cdgimpresion = '".$regPdtoImpresion->cdgimpresion."' AND 
                sttimpresion = '1'");
                
            if ($link_mysqli->affected_rows > 0) 
            { $msg_alert = 'La impresion fue actualizada con exito.\n\n'; }
            else
            { $msg_alert = 'La impresion NO fue actualizado.\n\n'; } 

            for ($idNoTinta = 1; $idNoTinta <= $regPdtoImpresion->notintas; $idNoTinta++)
            { $link_mysqli = conectar();
              $link_mysqli->query("
                INSERT INTO pdtoimpresiontnt
                  (cdgimpresion, notinta, cdgtinta, consumo, disolvente, viscosidad, temperatura)
                VALUES
                  ('".$regPdtoImpresion->cdgimpresion."', '".$idNoTinta."', '".$_POST['slc_cdgtinta'.$idNoTinta]."', '".$_POST['txt_consumo'.$idNoTinta]."', '".$_POST['txt_disolvente'.$idNoTinta]."', '".$_POST['txt_viscosidad'.$idNoTinta]."', '".$_POST['txt_temperatura'.$idNoTinta]."')");

              if ($link_mysqli->affected_rows > 0) 
              { $msg_alert .= 'La tinta '.$idNoTinta.' fue insertada.\n'; }
              else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE pdtoimpresiontnt
                  SET cdgtinta = '".$_POST['slc_cdgtinta'.$idNoTinta]."',
                      consumo = '".$_POST['txt_consumo'.$idNoTinta]."',
                      disolvente = '".$_POST['txt_disolvente'.$idNoTinta]."',
                      viscosidad = '".$_POST['txt_viscosidad'.$idNoTinta]."',
                      temperatura = '".$_POST['txt_temperatura'.$idNoTinta]."'
                  WHERE cdgimpresion = '".$regPdtoImpresion->cdgimpresion."' AND
                        notinta = '".$idNoTinta."'");
                
                if ($link_mysqli->affected_rows > 0)
                { $msg_alert .= 'La tinta '.$idNoTinta.' fue actualizada.\n'; }
                else
                { $msg_alert .= 'La tinta '.$idNoTinta.' NO fue actualizada.\n'; }
              }
            }
          } 
          else
          { for ($cdgimpresion = 1; $cdgimpresion <= 1000; $cdgimpresion++)
            { $pdtoImpresion_cdgimpresion = $pdtoImpresion_cdgdiseno.str_pad($cdgimpresion,3,'0',STR_PAD_LEFT);
              
              if ($cdgimpresion > 999)
              { $msg_alert = 'La impresion NO fue insertado, se ha alcanzado el tope de impresiones por diseno.'; }
              else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  INSERT INTO pdtoimpresion
                    (cdgproyecto, cdgdiseno, idimpresion, impresion, periodo, cdgproducto, cdgimpresion)
                  VALUES
                    ('".$pdtoImpresion_cdgdiseno."', '".$pdtoImpresion_cdgdiseno."', '".$pdtoImpresion_idimpresion."', '".$pdtoImpresion_impresion."', '".$pdtoImpresion_periodo."', '".$pdtoImpresion_cdgproducto."', '".$pdtoImpresion_cdgimpresion."')");
                
                if ($link_mysqli->affected_rows > 0) 
                { for ($idNoTinta = 1; $idNoTinta <= $pdtoImpresion_notintas; $idNoTinta++)
                  { $link_mysqli = conectar();
                    $link_mysqli->query("
                      INSERT INTO pdtoimpresiontnt
                        (cdgimpresion, notinta, cdgtinta, consumo, disolvente, viscosidad, temperatura)
                      VALUES
                        ('".$pdtoImpresion_cdgimpresion."', '".$idNoTinta."', '".$pdtoImpresionTnt_cdgtinta[$idNoTinta]."', '".$pdtoImpresionTnt_consumo[$idNoTinta]."', '".$pdtoImpresionTnt_disolvente[$idNoTinta]."', '".$pdtoImpresionTnt_viscosidad[$idNoTinta]."', '".$pdtoImpresionTnt_temperatura[$idNoTinta]."')"); }

                  $msg_alert = 'La impresion fue insertada con exito.'; 

                  $cdgimpresion = 1000; }      
              }
            }
          }

          $pdtoImpresionSelect->close; }
      } else
      { $msg_alert = $msg_norewrite; }         
    }

    // Filtro de Pantones //
    $link_mysqli = conectar();
    $pantoneSelect = $link_mysqli->query("
      SELECT * FROM pantone
      ORDER BY pantone");

    if ($pantoneSelect->num_rows > 0)
    { $idPantone = 1;
      
      while ($regPantone = $pantoneSelect->fetch_object())
      { $pantone_idpantone[$idPantone] = $regPantone->idpantone;
        $pantone_pantone[$idPantone] = $regPantone->pantone;

        $pantone_pantones[$regPantone->idpantone] = $regPantone->pantone;
        $pantone_idpantones[$regPantone->idpantone] = $regPantone->idpantone;

        $idPantone++; }

      $numPantones = $pantoneSelect->num_rows; } 

    // Filtro de Diseños //
    if (substr($sistModulo_permiso,0,1) == 'r')
    { $link_mysqli = conectar(); 
      $pdtoDisenoSelect = $link_mysqli->query("
        SELECT * FROM pdtodiseno
        WHERE sttdiseno = '1'
        ORDER BY diseno,
          iddiseno");
      
      $idDiseno = 1;
      while ($regPdtoDiseno = $pdtoDisenoSelect->fetch_object()) 
      { $pdtoDisenos_iddiseno[$idDiseno] = $regPdtoDiseno->iddiseno;
        $pdtoDisenos_diseno[$idDiseno] = $regPdtoDiseno->diseno;
        $pdtoDisenos_numTintas[$idDiseno] = $regPdtoDiseno->notintas;
        $pdtoDisenos_cdgdiseno[$idDiseno] = $regPdtoDiseno->cdgdiseno; 

         $idDiseno++; }

      $numDisenos = $pdtoDisenoSelect->num_rows; }

    // Vista de impresiones por diseño //
    if (substr($sistModulo_permiso,0,1) == 'r')
    { if ($_POST['chk_vertodos'])
      { $vertodo = 'checked'; 
      
        $link_mysqli = conectar();
        $pdtoImpresionSelect = $link_mysqli->query("
          SELECT * FROM pdtoimpresion
          WHERE cdgdiseno = '".$pdtoImpresion_cdgdiseno."'
          ORDER BY sttimpresion DESC,        
            idimpresion"); }
      else
      { $link_mysqli = conectar();
        $pdtoImpresionSelect = $link_mysqli->query("
          SELECT * FROM pdtoimpresion
          WHERE cdgdiseno = '".$pdtoImpresion_cdgdiseno."'
          AND sttimpresion = '1'
          ORDER BY idimpresion"); }
      
      if ($pdtoImpresionSelect->num_rows > 0)
      { $idImpresion = 1;
        while ($regPdtoImpresion = $pdtoImpresionSelect->fetch_object())
        { $pdtoImpresiones_idimpresion[$idImpresion] = $regPdtoImpresion->idimpresion;
          $pdtoImpresiones_impresion[$idImpresion] = $regPdtoImpresion->impresion;    
          $pdtoImpresiones_ancho[$idImpresion] = $regPdtoImpresion->ancho;
          $pdtoImpresiones_ceja[$idImpresion] = $regPdtoImpresion->ceja;
          $pdtoImpresiones_alpaso[$idImpresion] = $regPdtoImpresion->alpaso;
          $pdtoImpresiones_tolerancia[$idImpresion] = $regPdtoImpresion->tolerancia;
          $pdtoImpresiones_corte[$idImpresion] = $regPdtoImpresion->corte;
          $pdtoImpresiones_periodo[$idImpresion] = $regPdtoImpresion->periodo;
          $pdtoImpresiones_cdgproducto[$idImpresion] = $regPdtoImpresion->cdgproducto;
          $pdtoImpresiones_cdgimpresion[$idImpresion] = $regPdtoImpresion->cdgimpresion;
          $pdtoImpresiones_sttimpresion[$idImpresion] = $regPdtoImpresion->sttimpresion; 

          $link_mysqli = conectar();
          $pdtoImpresionTntSelect = $link_mysqli->query("
            SELECT pdtoimpresiontnt.notinta,
              pdtoimpresiontnt.cdgtinta 
            FROM pdtoimpresiontnt, 
              pdtoimpresion
            WHERE pdtoimpresiontnt.cdgimpresion = pdtoimpresion.cdgimpresion AND
              pdtoimpresion.cdgdiseno = '".$pdtoImpresion_cdgdiseno."'");

          if ($pdtoImpresionTntSelect->num_rows > 0)
          { while ($regPdtoImpresionTnt = $pdtoImpresionTntSelect->fetch_object())
            { $pdtoImpresionTnts_cdgtinta[$idImpresion][$regPdtoImpresionTnt->notinta] = $regPdtoImpresionTnt->cdgtinta; }
          }

          $idImpresion++; }

        $numImpresiones = $pdtoImpresionSelect->num_rows; 
      }
    }

    // Otra información //
    $link_mysqli = conectar();
    $pdtoDisenoSelect = $link_mysqli->query("
      SELECT * FROM pdtodiseno
      WHERE cdgdiseno = '".$pdtoImpresion_cdgdiseno."'");

    if ($pdtoDisenoSelect->num_rows > 0)
    { $regPdtoDiseno = $pdtoDisenoSelect->fetch_object();

      $pdtoImpresion_iddiseno = $regPdtoDiseno->iddiseno;
      $pdtoImpresion_diseno = $regPdtoDiseno->diseno;
      $pdtoImpresion_notintas = $regPdtoDiseno->notintas;
      $pdtoImpresion_proyecto = $regPdtoDiseno->diseno;
      $pdtoImpresion_cdgdiseno = $regPdtoDiseno->cdgdiseno;
      $pdtoImpresion_sttdiseno = $regPdtoDiseno->diseno; 

      $link_mysqli = conectar();
      $pdtoImpresionSelect = $link_mysqli->query("
        SELECT pdtoimpresion.cdgdiseno,
          pdtodiseno.alto,
          pdtodiseno.notintas,
          pdtoimpresion.idimpresion,
          pdtoimpresion.impresion,
          pdtoimpresion.periodo,
          pdtoimpresion.cdgproducto,
          pdtoimpresion.cdgimpresion,
          pdtoimpresion.sttimpresion
        FROM pdtodiseno,
          pdtoimpresion
        WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
          pdtodiseno.cdgdiseno = '".$pdtoImpresion_cdgdiseno."'
        LIMIT 0,1");
      
      if ($pdtoImpresionSelect->num_rows > 0)
      { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

        $pdtoImpresion_cdgdiseno = $regPdtoImpresion->cdgdiseno;
        $pdtoImpresion_alto = $regPdtoImpresion->alto;
        $pdtoImpresion_notintas = $regPdtoImpresion->notintas;
        $pdtoImpresion_idimpresion = $regPdtoImpresion->idimpresion;
        $pdtoImpresion_impresion = $regPdtoImpresion->impresion;
        $pdtoImpresion_periodo = $regPdtoImpresion->periodo;
        $pdtoImpresion_cdgproducto = $regPdtoImpresion->cdgproducto;
        $pdtoImpresion_cdgimpresion = $regPdtoImpresion->cdgimpresion;
        $pdtoImpresion_sttimpresion = $regPdtoImpresion->sttimpresion;

        $link_mysqli = conectar();
        $pdtoImpresionTntSelect = $link_mysqli->query("
          SELECT * FROM pdtoimpresiontnt
          WHERE cdgimpresion = '".$pdtoImpresion_cdgimpresion."'");

        if ($pdtoImpresionTntSelect->num_rows > 0)
        { while ($regPdtoImpresionTnt = $pdtoImpresionTntSelect->fetch_object())
          { $pdtoImpresionTnt_cdgtinta[$regPdtoImpresionTnt->notinta] = $regPdtoImpresionTnt->cdgtinta;
            $pdtoImpresionTnt_consumo[$regPdtoImpresionTnt->notinta] = $regPdtoImpresionTnt->consumo;
            $pdtoImpresionTnt_disolvente[$regPdtoImpresionTnt->notinta] = $regPdtoImpresionTnt->disolvente;
            $pdtoImpresionTnt_viscosidad[$regPdtoImpresionTnt->notinta] = $regPdtoImpresionTnt->viscosidad;
            $pdtoImpresionTnt_temperatura[$regPdtoImpresionTnt->notinta] = $regPdtoImpresionTnt->temperatura; }
        }
      }
    }
    
    
    // Generación de página //
    echo '
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />    
  </head>
  <body><br />
    <form id="frm_pdtoimpresion" name="frm_pdtoimpresion" method="POST" action="pdtoImpresion.php">
      <table align="center">
        <thead>
          <tr><th align="left" colspan="6">RC-03-PSG-7.2 Impresiones</th></tr>
        </thead>
        <tbody>
          <tr><td>
              <label for="lbl_cdgbloque"><a href="../sm_producto/pdtoDiseno.php?cdgdiseno='.$pdtoImpresion_cdgdiseno.'">Dise&ntilde;o</a></label><br/>
              <select style="width:180px" id="slc_cdgdiseno" name="slc_cdgdiseno" onchange="document.frm_pdtoimpresion.submit()">
                <option value="">Selecciona una opcion</option>';
      
    for ($idDiseno = 1; $idDiseno <= $numDisenos; $idDiseno++) 
    { echo '
                        <option value="'.$pdtoDisenos_cdgdiseno[$idDiseno].'"';
              
      if ($pdtoImpresion_cdgdiseno == $pdtoDisenos_cdgdiseno[$idDiseno]) { echo ' selected="selected"'; }
      echo '>'.$pdtoDisenos_diseno[$idDiseno].'</option>'; }

    echo '
              </select></td>
            <td><label for="lbl_periodo">Periodo</label><br/>
              <input type="text" style="width:160px;" maxlength="24" id="txt_periodo" name="txt_periodo" value="'.$pdtoImpresion_periodo.'" title="Periodo de vigencia" required/></td>
            <td><label for="lbl_cdgproducto">'.utf8_decode('Código producto').'</label><br/>
              <input type="text" style="width:100px;" maxlength="24" id="txt_cdgproducto" name="txt_cdgproducto" value="'.$pdtoImpresion_cdgproducto.'" title="Codigo de producto (Asignado por el cliente)" required/></td></tr>
          <tr><td><label for="lbl_idmpresion">idImpresion</label><br/>
              <input type="text" style="width:180px;" maxlength="24" id="txt_idimpresion" name="txt_idimpresion" value="'.$pdtoImpresion_idimpresion.'" title="Identificador impresion" required/></td>
            <td colspan="2">
              <label for="lbl_impresion">Impresi&oacute;n</label><br/>
              <input type="text" style="width:300px;" maxlength="80" id="txt_impresion" name="txt_impresion" value="'.$pdtoImpresion_impresion.'" title="Descripcion de impresion" required/></td></tr>';
    
    echo '
        </tbody>
      </table><br />

      <table align="center">
        <thead>
          <tr><th align="left" colspan="6">'.utf8_decode('Asignación de pantones').'</th></tr>
        </thead>
        <tbody>';

    for ($idNoTinta = 1; $idNoTinta <= $pdtoImpresion_notintas; $idNoTinta++)
    { echo '
          <tr><td><label for="ttl_ttltinta'.$idNoTinta.'">Tinta #'.$idNoTinta.'</label><br/>
              <select style="width:180px" id="slc_cdgtinta'.$idNoTinta.'" name="slc_cdgtinta'.$idNoTinta.'">
                <option value="">Selecciona una opcion</option>';
      
      for ($idPantone = 1; $idPantone <= $numPantones; $idPantone++) 
      { echo '
                <option value="'.$pantone_idpantone[$idPantone].'"';
              
        if ($pdtoImpresionTnt_cdgtinta[$idNoTinta] == $pantone_idpantone[$idPantone]) { echo ' selected="selected"'; }
        echo '>'.$pantone_pantone[$idPantone].'</option>'; }

      echo '
              </select></td>
            <td><label for="ttl_consumo'.$idNoTinta.'">Consumo</label><br/>
              <input type="text" id="txt_consumo'.$idNoTinta.'" name="txt_consumo'.$idNoTinta.'" style="width:80px; text-align:right;" maxlenght="16" value="'.$pdtoImpresionTnt_consumo[$idNoTinta].'" title="Consumo de tinta #'.$idNoTinta.' Kgs. por millar "'.$text_amplitud[$idNoTinta].' required/></td>            
            <td><label for="ttl_disolvente'.$idNoTinta.'">Disolvente</label><br/>
              <input type="text" id="txt_disolvente'.$idNoTinta.'" name="txt_disolvente'.$idNoTinta.'" style="width:80px; text-align:left;" maxlenght="16" value="'.$pdtoImpresionTnt_disolvente[$idNoTinta].'" title="Disolvente sugerida en tinta #'.$idNoTinta.'" required/></td>
            <td><label for="ttl_viscosidad'.$idNoTinta.'">Viscosidad</label><br/>
              <input type="text" id="txt_viscosidad'.$idNoTinta.'" name="txt_viscosidad'.$idNoTinta.'" style="width:80px; text-align:right;" maxlenght="16" value="'.$pdtoImpresionTnt_viscosidad[$idNoTinta].'" title="Viscosidad sugerida en tinta #'.$idNoTinta.'" required/></td>
            <td><label for="ttl_temperatura'.$idNoTinta.'">Temperatura</label><br/>
              <input type="text" id="txt_temperatura'.$idNoTinta.'" name="txt_temperatura'.$idNoTinta.'" style="width:80px; text-align:right;" maxlenght="16" value="'.$pdtoImpresionTnt_temperatura[$idNoTinta].'" title="Temperatura sugerida en tinta #'.$idNoTinta.'" required/></td>
            <td bgcolor="#'.$pantone_idpantones[$pdtoImpresionTnt_cdgtinta[$idNoTinta]].'">&nbsp;&nbsp;&nbsp;</td>
          </tr>'; }

    echo '
        </tbody>
        <tfoot>
          <tr><td colspan="6" align="right"><input type="submit" id="btn_submit" name="btn_submit" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>
      
      <table align="center">
        <thead>      
          <tr align="center">
            <td colspan="'.(2+$pdtoImpresion_notintas).'"></th>
            <th colspan="4"><input type="checkbox" name="chk_vertodos" id="chk_vertodos" onclick="document.frm_pdtoimpresion.submit()" '.$vertodo.'>
              <label for="lbl_vertodo">Ver todo</label></th>
          </tr>
          <tr align="left">
            <th><label for="lbl_ttlidimpresion">idImpresion</label></th>
            <th><label for="lbl_ttlimpresion">Impresi&oacute;n</label></th>
            <th colspan="'.$pdtoImpresion_notintas.'" align="center"><label for="lbl_ttlpantones">Pantones</label></th>
            <th colspan="3"><label for="lbl_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';

    if ($numImpresiones > 0)
    { for ($idImpresion=1; $idImpresion<=$numImpresiones; $idImpresion++)
      { echo '
          <tr align="center">
            <td align="left"><strong>'.$pdtoImpresiones_idimpresion[$idImpresion].'</strong></td>
            <td align="left">'.$pdtoImpresiones_impresion[$idImpresion].'</td>';

        for ($idNoTinta = 1; $idNoTinta <= $pdtoImpresion_notintas; $idNoTinta++)
        { echo '
            <td bgcolor="#'.$pantone_idpantones[$pdtoImpresionTnts_cdgtinta[$idImpresion][$idNoTinta]].'">&nbsp;&nbsp;&nbsp;</td>'; } //*/

        if ((int)$pdtoImpresiones_sttimpresion[$idImpresion] > 0)
        { echo '
            <td><a href="pdtoImpresion.php?cdgimpresion='.$pdtoImpresiones_cdgimpresion[$idImpresion].'">'.$png_search.'</a></td>                        
            <td><a href="pdtoImpresionImagen.php?cdgimpresion='.$pdtoImpresiones_cdgimpresion[$idImpresion].'">'.$png_camera.'</a></td>
            <td><a href="pdtoImpresion.php?cdgimpresion='.$pdtoImpresiones_cdgimpresion[$idImpresion].'&proceso=update">'.$png_power_blue.'</a></td>'; }
        else
         { echo '
            <td><a href="pdtoImpresion.php?cdgimpresion='.$pdtoImpresiones_cdgimpresion[$idImpresion].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td>&nbsp;</td>
            <td><a href="pdtoImpresion.php?cdgimpresion='.$pdtoImpresiones_cdgimpresion[$idImpresion].'&proceso=update">'.$png_power_black.'</a></td>'; }

        echo '</tr>';
      }      
    }

    echo '
        </tbody>
        <tfoot>
          <tr><th colspan="19" align="right">              
              <label for="lbl_ppgdatos">['.$numImpresiones.'] Registros encontrados</label></th></tr>
        </tfoot>
      </table>
    </form>'; 

    if ($msg_alert != '')
    { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
    
  } else
  { echo '<br />
    <div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
  
  echo '
  </body> 
</html>';
?>