<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '20020';

  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']); 
    
    $pdtoImpresion_cdgdiseno = $_POST['slc_cdgdiseno'];
    $pdtoImpresion_idimpresion = trim($_POST['txt_idimpresion']);
    $pdtoImpresion_impresion = trim($_POST['txt_impresion']);    
    $pdtoImpresion_ancho = number_format($_POST['txt_ancho'],3);
    $pdtoImpresion_ceja = number_format($_POST['txt_ceja'],3);
    $pdtoImpresion_alpaso = number_format($_POST['txt_alpaso'],3);
    $pdtoImpresion_tolerancia = number_format($_POST['txt_tolerancia'],3);
    $pdtoImpresion_alto = number_format($_POST['txt_corte'],3);
    $pdtoImpresion_periodo = $_POST['txt_periodo'];
    $pdtoImpresion_cdgproducto = $_POST['txt_cdgproducto'];
    $pdtoImpresion_cdgsustrato = $_POST['slc_cdgsustrato'];

    if ($_GET['cdgimpresion']) { $pdtoImpresion_cdgimpresion = $_GET['cdgimpresion']; }
    if ($_GET['cdgdiseno']) { $pdtoImpresion_cdgdiseno = $_GET['cdgdiseno']; }

    $link_mysqli = conectar();
    $pdtoDisenoSelect = $link_mysqli->query("
      SELECT * FROM pdtodiseno
      WHERE cdgdiseno = '".$pdtoImpresion_cdgdiseno."'");

    if ($pdtoDisenoSelect->num_rows > 0)
    { $regPdtoDiseno = $pdtoDisenoSelect->fetch_object();

      $pdtoImpresion_iddiseno = $regPdtoDiseno->iddiseno;
      $pdtoImpresion_diseno = $regPdtoDiseno->diseno;
      $pdtoImpresion_anchos = $regPdtoDiseno->ancho;
      $pdtoImpresion_altos = $regPdtoDiseno->alto;
      $pdtoImpresion_notintas = $regPdtoDiseno->notintas;
      $pdtoImpresion_proyecto = $regPdtoDiseno->diseno;
      $pdtoImpresion_cdgdiseno = $regPdtoDiseno->cdgdiseno;
      $pdtoImpresion_sttdiseno = $regPdtoDiseno->diseno; }    

    for ($idNoTinta = 1; $idNoTinta <= $pdtoImpresion_notintas; $idNoTinta++)
    { $pdtoImpresionTnt_cdgtinta[$idNoTinta] = $_POST['slc_cdgtinta'.$idNoTinta];
      $pdtoImpresionTnt_consumo[$idNoTinta] = $_POST['txt_consumo'.$idNoTinta]; }  

    // Si doy click en alguna Impresión //
    if ($_GET['cdgimpresion'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $link_mysqli = conectar();
        $pdtoImpresionSelect = $link_mysqli->query("
          SELECT pdtoimpresion.cdgdiseno,
            pdtodiseno.notintas,
            pdtoimpresion.idimpresion,
            pdtoimpresion.impresion,
            pdtoimpresion.cdgsustrato,
            pdtoimpresion.ancho,
            pdtoimpresion.ceja,
            pdtoimpresion.alpaso,
            pdtoimpresion.tolerancia,
            pdtoimpresion.corte,
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
          $pdtoImpresion_notintas = $regPdtoImpresion->notintas;
          $pdtoImpresion_idimpresion = $regPdtoImpresion->idimpresion;
          $pdtoImpresion_impresion = $regPdtoImpresion->impresion;
          $pdtoImpresion_cdgsustrato = $regPdtoImpresion->cdgsustrato;
          $pdtoImpresion_ancho = $regPdtoImpresion->ancho;
          $pdtoImpresion_ceja = $regPdtoImpresion->ceja;
          $pdtoImpresion_alpaso = $regPdtoImpresion->alpaso;
          $pdtoImpresion_tolerancia = $regPdtoImpresion->tolerancia;
          $pdtoImpresion_alto = $regPdtoImpresion->corte;
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
              $pdtoImpresionTnt_consumo[$regPdtoImpresionTnt->notinta] = $regPdtoImpresionTnt->consumo; }
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
              $pdtoMezclaSelect = $link_mysqli->query("
                SELECT * FROM pdtomezcla
                WHERE cdgimpresion = '".$pdtoImpresion_cdgimpresion."'");
                
              if ($pdtoMezclaSelect->num_rows > 0)
              { $msg_alert = 'La impresion cuenta con mezclas ligadas, no pudo ser eliminada.'; }
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
      } else
      { $msg_alert = $msg_noread; }
    }

    // Botón salvar //     
    if ($_POST['btn_submit'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if (strlen($pdtoImpresion_cdgdiseno) > 0 AND strlen($pdtoImpresion_idimpresion) > 0)
        { // Buscar coincidencias
          $link_mysqli = conectar();
          $pdtoImpresionSelect = $link_mysqli->query("
            SELECT * FROM pdtoimpresion
            WHERE cdgdiseno = '".$pdtoImpresion_cdgdiseno."' AND 
              idimpresion = '".$pdtoImpresion_idimpresion."'");
            
          if ($pdtoImpresionSelect->num_rows > 0)
          { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();
            
            // Validar que no tenga consumo, 
            // ya que si se modifica el corte, el millar de piezas generara
            // variaciones y no sera correcto
/*
            $link_mysqli = conectar();
            $alptEmpaqueSelect = $link_mysqli->query("
              SELECT * FROM prodlote 
              WHERE cdgproducto = '".$regPdtoImpresion->cdgimpresion."'");                 

            if ($alptEmpaqueSelect->num_rows > 0)
            { $link_mysqli = conectar();
              $link_mysqli->query("
                UPDATE pdtoimpresion
                SET impresion = '".$pdtoImpresion_impresion."',                  
                  cdgproducto = '".$pdtoImpresion_cdgproducto."'
                WHERE cdgimpresion = '".$regPdtoImpresion->cdgimpresion."' AND 
                  sttimpresion = '1'");
                
              if ($link_mysqli->affected_rows > 0) 
              { $msg_alert = 'La impresion fue actualizada, solo en el codigo de referencia debido a que ya cuenta con producto programado.'; }
              else
              { $msg_alert = 'La impresion NO fue actualizado.'; } 
            } 
            else
            { //*/

              $link_mysqli = conectar();
              $link_mysqli->query("
                UPDATE pdtoimpresion
                SET impresion = '".$pdtoImpresion_impresion."',
                  ancho = '".$pdtoImpresion_ancho."',
                  ceja = '".$pdtoImpresion_ceja."',
                  alpaso = '".$pdtoImpresion_alpaso."',
                  tolerancia = '".$pdtoImpresion_tolerancia."',
                  corte = '".$pdtoImpresion_alto."',
                  periodo = '".$pdtoImpresion_periodo."',
                  cdgproducto = '".$pdtoImpresion_cdgproducto."',
                  cdgsustrato = '".$pdtoImpresion_cdgsustrato."'
                WHERE cdgimpresion = '".$regPdtoImpresion->cdgimpresion."' AND 
                  sttimpresion = '1'");
                
              if ($link_mysqli->affected_rows > 0) 
              { $msg_alert = 'La impresion fue actualizada con exito.'; }
              else
              { $msg_alert = 'La impresion NO fue actualizado.'; } 

              for ($idNoTinta = 1; $idNoTinta <= $pdtoImpresion_notintas; $idNoTinta++)
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  INSERT INTO pdtoimpresiontnt
                    (cdgimpresion, notinta, cdgtinta, consumo)
                  VALUES
                    ('".$regPdtoImpresion->cdgimpresion."', '".$idNoTinta."', '".$pdtoImpresionTnt_cdgtinta[$idNoTinta]."', '".$pdtoImpresionTnt_consumo[$idNoTinta]."')");

                if ($link_mysqli->affected_rows > 0) 
                { /*Excelente*/ }
                else
                { $link_mysqli = conectar();
                  $link_mysqli->query("
                    UPDATE pdtoimpresiontnt
                    SET cdgtinta = '".$pdtoImpresionTnt_cdgtinta[$idNoTinta]."',
                      consumo = '".$pdtoImpresionTnt_consumo[$idNoTinta]."'
                    WHERE cdgimpresion = '".$regPdtoImpresion->cdgimpresion."' AND
                      notinta = '".$idNoTinta."'"); }
              }
            //}                
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
                    (cdgproyecto, cdgdiseno, idimpresion, impresion, ancho, ceja, alpaso, tolerancia, corte, periodo, cdgproducto, cdgimpresion)
                  VALUES
                    ('".$pdtoImpresion_cdgdiseno."', '".$pdtoImpresion_cdgdiseno."', '".$pdtoImpresion_idimpresion."', '".$pdtoImpresion_impresion."', '".$pdtoImpresion_ancho."', '".$pdtoImpresion_ceja."', '".$pdtoImpresion_alpaso."', '".$pdtoImpresion_tolerancia."', '".$pdtoImpresion_alto."', '".$pdtoImpresion_periodo."', '".$pdtoImpresion_cdgproducto."', '".$pdtoImpresion_cdgimpresion."')");
                
                if ($link_mysqli->affected_rows > 0) 
                { for ($idNoTinta = 1; $idNoTinta <= $pdtoImpresion_notintas; $idNoTinta++)
                  { $link_mysqli = conectar();
                    $link_mysqli->query("
                      INSERT INTO pdtoimpresiontnt
                        (cdgimpresion, notinta, cdgtinta, consumo)
                      VALUES
                        ('".$regPdtoImpresion->cdgimpresion."', '".$idNoTinta."', '".$pdtoImpresionTnt_cdgtinta[$idNoTinta]."', '".$pdtoImpresionTnt_consumo[$idNoTinta]."')"); }

                  $msg_alert = 'La impresion fue insertada con exito.'; 

                  $cdgimpresion = 1000; }      
              }
            }
          }

          $pdtoImpresionSelect->close; }
      } else
      { $msg_alert = $msg_norewrite; }         
    }

    // Filtro de Sustratos //
    $link_mysqli = conectar();
    $pdtoSustratoSelect = $link_mysqli->query("
      SELECT * FROM pdtosustrato
      ORDER BY idsustrato");

    if ($pdtoSustratoSelect->num_rows > 0)
    { $idSustrato = 1;

      while ($regPdtoSustrato = $pdtoSustratoSelect->fetch_object())
      { $pdtoSustratos_sustrato[$idSustrato] = $regPdtoSustrato->sustrato;
        $pdtoSustratos_cdgsustrato[$idSustrato] = $regPdtoSustrato->cdgsustrato;
        
        $idSustrato++; }

      $numSustratos = $pdtoSustratoSelect->num_rows; }

    // Filtro de Tintas //
    $link_mysqli = conectar();
    $pdtoTintaSelect = $link_mysqli->query("
      SELECT * FROM pdtotinta 
      ORDER BY idtinta");

    if ($pdtoTintaSelect->num_rows > 0)
    { $idTinta = 1;
      
      while ($regPdtoTinta = $pdtoTintaSelect->fetch_object())
      { $pdtoTintas_idtinta[$idTinta] = $regPdtoTinta->idtinta;
        $pdtoTintas_tinta[$idTinta] = $regPdtoTinta->tinta;
        $pdtoTintas_proveedor[$idTinta] = $regPdtoTinta->proveedor;
        $pdtoTintas_proveedor[$idTinta] = $regPdtoTinta->proveedor;        
        $pdtoTintas_cdgtinta[$idTinta] = $regPdtoTinta->cdgtinta;
        $pdtoTintas_cdghex[$idTinta] = $regPdtoTinta->cdghex;

        $pdtoTintas_tintas[$regPdtoTinta->cdgtinta] = $regPdtoTinta->tinta;        
        $pdtoTintas_cdgtintas[$regPdtoTinta->cdgtinta] = $regPdtoTinta->cdghex;
        $pdtoTintas_cdgproveedor[$regPdtoTinta->cdgtinta] = $regPdtoTinta->cdgproveedor;

        $idTinta++; }

      $numTintas = $pdtoTintaSelect->num_rows; } 

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

          $idImpresion++; }

        $numImpresiones = $pdtoImpresionSelect->num_rows; 
      }
    }

    // Generación de página //
    echo '
    <form id="frm_pdtoimpresion" name="frm_pdtoimpresion" method="POST" action="pdtoImpresion.php">
      <table align="center">
        <thead>
          <tr><th colspan="5" align="left">'.$sistModulo_modulo.'</th></tr>
        </thead>
        <tbody>
          <tr><td>
              <table>
                <thead>
                  <tr><th colspan="2">Especificaciones del producto</th></tr>
                </thead>
                <tbody>
                  <tr><td colspan="2">
                      <label for="lbl_cdgbloque"><a href="../sm_producto/pdtoDiseno.php?cdgdiseno='.$pdtoImpresion_cdgdiseno.'">Dise&ntilde;o</a></label><br/>
                      <select style="width:240px" id="slc_cdgdiseno" name="slc_cdgdiseno" onchange="document.frm_pdtoimpresion.submit()">
                        <option value="">Selecciona una opcion</option>';
      
    for ($idDiseno = 1; $idDiseno <= $numDisenos; $idDiseno++) 
    { echo '
                        <option value="'.$pdtoDisenos_cdgdiseno[$idDiseno].'"';
              
      if ($pdtoImpresion_cdgdiseno == $pdtoDisenos_cdgdiseno[$idDiseno]) { echo ' selected="selected"'; }
      echo '>'.$pdtoDisenos_diseno[$idDiseno].'</option>'; }

    echo '
                      </select></td></tr>
                  <tr><td colspan="2"><label for="lbl_idmpresion">Impresion</label><br/>
                      <input type="text" style="width:100px;" maxlength="24" id="txt_idimpresion" name="txt_idimpresion" value="'.$pdtoImpresion_idimpresion.'" title="Identificador impresion" required/></td></tr>
                  <tr><td colspan="2">
                      <label for="lbl_impresion">Descripci&oacute;n</label><br/>
                      <input type="text" style="width:300px;" maxlength="80" id="txt_impresion" name="txt_impresion" value="'.$pdtoImpresion_impresion.'" title="Descripcion de impresion" required/></td></tr>
                  <tr><td><label for="lbl_periodo">Periodo</label><br/>
                      <input type="text" style="width:160px;" maxlength="24" id="txt_periodo" name="txt_periodo" value="'.$pdtoImpresion_periodo.'" title="Periodo de vigencia" required/></td>
                    <td><label for="lbl_cdgproducto">C&oacute;digo producto</label><br/>
                      <input type="text" style="width:100px;" maxlength="24" id="txt_cdgproducto" name="txt_cdgproducto" value="'.$pdtoImpresion_cdgproducto.'" title="Codigo de producto (Asignado por el cliente)" required/></td></tr>
                </tbody>
                <tfoot>
                </tfoot>
              </table></td>
            <td>
              <table align="center">
                <thead>
                  <tr><th colspan="2">Definici&oacute;n de dimensiones en milimetros</th></tr>
                </thead>
                <tbody>
                  <tr><td><label for="lbl_ancho">Ancho</label></td>
                    <td><input type="text" style="width:100px;text-align:right;" maxlength="12" id="txt_ancho" name="txt_ancho" value="'.$pdtoImpresion_ancho.'" title="Ancho en milimetros (Ancho plano)" required/></td></tr>
                  <tr><td><label for="lbl_ceja">Ceja</label></td>
                    <td><input type="text" style="width:100px;text-align:right;" maxlength="12" id="txt_ceja" name="txt_ceja" value="'.$pdtoImpresion_ceja.'" title="Ancho en milimetros (Ancho plano)" required/></td></tr>
                  <tr><td><label for="lbl_alpaso">Al paso</label></td>
                    <td><input type="text" style="width:100px;text-align:right;" maxlength="12" id="txt_alpaso" name="txt_alpaso" value="'.$pdtoImpresion_alpaso.'" title="Impresiones al paso" required/></td></tr>
                  <tr><td><label for="lbl_tolerancia">Tolerancia</label></td>
                    <td><input type="text" style="width:100px;text-align:right;" maxlength="12" id="txt_tolerancia" name="txt_tolerancia" value="'.$pdtoImpresion_tolerancia.'" title="Tolerancia de excedente" required/></td></tr>
                  <tr><td><label for="lbl_corte">Corte</label></td>
                    <td><input type="text" style="width:100px;text-align:right;" maxlength="12" id="txt_corte" name="txt_corte" value="'.$pdtoImpresion_alto.'" title="Corte en milimetros" required/></td></tr>                
                </tbody>
                <tfoot>
                </tfoot>
              </table>
            </td>
          </tr>
          <tr><td>
              <table>';
                    
    echo '
                <tr><td colspan="2">
                    <label for="lbl_ttlsustrato">Sustrato</label><br/>
                    <select style="width:300px" id="slc_cdgsustrato" name="slc_cdgsustrato">
                      <option value="">Selecciona una opcion</option>';
      
    for ($idSustrato = 1; $idSustrato <= $numSustratos; $idSustrato++) 
    { echo '
                      <option value="'.$pdtoSustratos_cdgsustrato[$idSustrato].'"';
              
      if ($pdtoImpresion_cdgsustrato == $pdtoSustratos_cdgsustrato[$idSustrato]) { echo ' selected="selected"'; }
      echo '>'.$pdtoSustratos_sustrato[$idSustrato].'</option>'; }

    echo '
                    </select></td>
                </tr>';

    for ($idNoTinta = 1; $idNoTinta <= $pdtoImpresion_notintas; $idNoTinta++)
    { echo '
                <tr><td><label for="ttl_ttltinta'.$idNoTinta.'">Tinta #'.$idNoTinta.'</label><br/>
                    <select style="width:180px" id="slc_cdgtinta'.$idNoTinta.'" name="slc_cdgtinta'.$idNoTinta.'">
                      <option value="">Selecciona una opcion</option>';
      
      for ($idTinta = 1; $idTinta <= $numTintas; $idTinta++) 
      { echo '
                      <option class="'.$pdtoTintas_cdghex[$idTinta].'" value="'.$pdtoTintas_cdgtinta[$idTinta].'"';
              
        if ($pdtoImpresionTnt_cdgtinta[$idNoTinta] == $pdtoTintas_cdgtinta[$idTinta]) { echo ' selected="selected"'; }
        echo '>'.$pdtoTintas_tinta[$idTinta].'</option>'; }

      echo '
                    </select></td>
                  <td><label for="ttl_consumo'.$idNoTinta.'">Consumo</label><br/>
                    <input type="text" id="txt_consumo'.$idNoTinta.'" name="txt_consumo'.$idNoTinta.'" style="width:80px; text-align:right;" maxlenght="16" value="'.$pdtoImpresionTnt_consumo[$idNoTinta].'" title="Consumo de tinta #'.$idNoTinta.' Kgs. por millar "'.$text_amplitud[$idNoTinta].' required/></td></tr>'; }
    echo '
              </table>
            </td>
            <td>
              <table>
                <tr><td colspan="'.$pdtoImpresion_notintas.'">
                    <img src="images/'.$pdtoImpresion_cdgimpresion.'.jpg" height="'.($pdtoImpresion_alto*1.5).'" width="'.($pdtoImpresion_ancho*1.5).'"></td></tr>
                <tr>';
    
    for ($idNoTinta = 1; $idNoTinta <= $pdtoImpresion_notintas; $idNoTinta++)
    { echo '
                  <td>Tinta #'.$idNoTinta.'<br/>
                    <strong>'.$pdtoTintas_tintas[$pdtoImpresionTnt_cdgtinta[$idNoTinta]].'</strong><br/>
                    <em>'.$pdtoTintas_cdgproveedor[$pdtoImpresionTnt_cdgtinta[$idNoTinta]].'</em></td>'; } 
    echo '</tr>
                <tr>';

    for ($idNoTinta = 1; $idNoTinta <= $pdtoImpresion_notintas; $idNoTinta++)
    { echo '
                  <td bgcolor="'.$pdtoTintas_cdgtintas[$pdtoImpresionTnt_cdgtinta[$idNoTinta]].'">&nbsp;</td>'; } 

    echo '</tr>
              </table>
            </td>
          </tr>
        <tbody>
        <tfoot>
          <tr><td colspan="4" align="right"><input type="submit" id="btn_submit" name="btn_submit" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>
      
      <table align="center">
        <thead>      
          <tr align="center">
            <td colspan="2"></td>
            <th colspan="4">Milimetros</th>
            <th colspan="4"><input type="checkbox" name="chk_vertodos" id="chk_vertodos" onclick="document.frm_pdtoimpresion.submit()" '.$vertodo.'>
              <label for="lbl_vertodo">Ver todo</label></th>
          </tr>
          <tr align="left">
            <th><label for="lbl_ttlidimpresion">Impresion</label></th>
            <th><label for="lbl_ttlimpresion">Descripcion</label></th>
            <th colspan="3" align="center"><label for="lbl_ttlancho">Distribuci&oacute;n</label></th>
            <th><label for="lbl_ttlcorte">Corte</label></th>            
            <th colspan="3"><label for="lbl_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';

    if ($numImpresiones > 0)
    { for ($idImpresion=1; $idImpresion<=$numImpresiones; $idImpresion++)
      { echo '
          <tr align="center">
            <td align="left"><strong>'.$pdtoImpresiones_idimpresion[$idImpresion].'</strong></td>
            <td align="left">'.$pdtoImpresiones_impresion[$idImpresion].'</td>
            <td colspan="2" align="right">((('.$pdtoImpresiones_ancho[$idImpresion].'+'.$pdtoImpresiones_ceja[$idImpresion].')*'.$pdtoImpresiones_alpaso[$idImpresion].')+'.$pdtoImpresiones_tolerancia[$idImpresion].') =</td>
            <td align="right"><strong>'.((($pdtoImpresiones_ancho[$idImpresion]+$pdtoImpresiones_ceja[$idImpresion])*$pdtoImpresiones_alpaso[$idImpresion])+$pdtoImpresiones_tolerancia[$idImpresion]).'</strong></td>
            <td align="right"><strong>'.$pdtoImpresiones_corte[$idImpresion].'</strong></td>';

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
    
    unset($pdtoDisenos_iddiseno);
    unset($pdtoDisenos_diseno);
    unset($pdtoDisenos_cdgdiseno);
    unset($pdtoDisenos_sttdiseno);      

    unset($pdtoImpresiones_idimpresion);
    unset($pdtoImpresiones_impresion);  
    unset($pdtoImpresiones_ancho);  
    unset($pdtoImpresiones_alpaso);  
    unset($pdtoImpresiones_tolerancia);  
    unset($pdtoImpresiones_corte);  
    unset($pdtoImpresiones_cdgimpresion);
    unset($pdtoImpresiones_sttimpresion);

  } else
  { echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }

/*
      $link_mysqli = conectar();
      $alptEmpaqueSelect = $link_mysqli->query("
        SELECT * FROM alptempaque 
        WHERE cdgproducto = '".$_GET['cdgimpresion']."' AND
          tpoempaque = 'C'");

      if ($alptEmpaqueSelect->num_rows > 0)
      { while ($regAlptEmpaque = $alptEmpaqueSelect->fetch_object()) 
        { $link_mysqli = conectar();
          $alptEmpaquePSelect = $link_mysqli->query("
            SELECT * FROM prodpaquete
            WHERE cdgempaque = '".$regAlptEmpaque->cdgempaque."' AND
              cdgproducto = ''"); 

          if ($alptEmpaquePSelect->num_rows > 0)
          { $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE prodpaquete
              SET cdgproducto = '".$_GET['cdgimpresion']."'
              WHERE cdgempaque = '".$regAlptEmpaque->cdgempaque."' AND
                cdgproducto = ''");
            
          }
        }
      } //*/

/*
      $link_mysqli = conectar();
      $prodRolloSelect = $link_mysqli->query("
        SELECT prodrollo.cdgrollo 
        FROM prodrollo,
          prodpaquete 
        WHERE prodrollo.cdgrollo = prodpaquete.cdgrollo AND
          prodrollo.cdgproducto = '".$_GET['cdgimpresion']."' AND
          prodpaquete.cdgproducto = ''
        GROUP BY prodrollo.cdgrollo");

      if ($prodRolloSelect->num_rows > 0)
      { while ($regProdRollo = $prodRolloSelect->fetch_object()) 
        { $link_mysqli = conectar();
          $link_mysqli->query("
            UPDATE prodpaquete
            SET cdgproducto = '".$_GET['cdgimpresion']."'
            WHERE cdgrollo = '".$regProdRollo->cdgrollo."' AND
              cdgproducto = ''");
        }
      } //*/    
?>

  </body> 
</html>
