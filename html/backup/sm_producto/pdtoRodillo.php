<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '20015';

  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']); 
    
    $pdtoJuego_cdgdiseno = $_POST['slc_cdgdiseno'];
    $pdtoJuego_idjuego = trim($_POST['txt_idjuego']);
    $pdtoJuego_proveedor = trim($_POST['txt_proveedor']);    
    $pdtoJuego_fchrecepcion = $_POST['date_fchrecepcion'];
    $pdtoJuego_girosmax = $_POST['txt_girosmax'];
    $pdtoJuego_alpaso = $_POST['txt_alpaso'];
    $pdtoJuego_algiro = $_POST['txt_algiro'];
    $pdtoJuego_registro = $_POST['txt_registro'];
    
    $pdtoJuego_fchrecepcion = ValidarFecha($pdtoJuego_fchrecepcion);    

    if ($_GET['cdgjuego']) { $pdtoJuego_cdgjuego = $_GET['cdgjuego']; }
    if ($_GET['cdgdiseno']) { $pdtoJuego_cdgdiseno = $_GET['cdgdiseno']; }

    $link_mysqli = conectar();
    $pdtoDisenoSelect = $link_mysqli->query("
      SELECT * FROM pdtodiseno
      WHERE cdgdiseno = '".$pdtoJuego_cdgdiseno."'");

    if ($pdtoDisenoSelect->num_rows > 0)
    { $regPdtoDiseno = $pdtoDisenoSelect->fetch_object();

      $pdtoDiseno_iddiseno = $regPdtoDiseno->iddiseno;
      $pdtoDiseno_diseno = $regPdtoDiseno->diseno;
      $pdtoDiseno_proyecto = $regPdtoDiseno->proyecto;
      $pdtoDiseno_cdgholograma = $regPdtoDiseno->cdgholograma;
      $pdtoDiseno_ancho = $regPdtoDiseno->ancho;
      $pdtoDiseno_alto = $regPdtoDiseno->alto;
      $pdtoDiseno_empalme = $regPdtoDiseno->empalme;
      $pdtoDiseno_notintas = $regPdtoDiseno->notintas;      
      $pdtoDiseno_cdgdiseno = $regPdtoDiseno->cdgdiseno;
      $pdtoDiseno_sttdiseno = $regPdtoDiseno->sttdiseno; 

      $pdtoJuego_alpaso = $regPdtoDiseno->alpaso;
      $pdtoJuego_registro = $regPdtoDiseno->registro;
      $pdtoJuego_tabla = ($regPdtoDiseno->ancho*$regPdtoDiseno->alpaso)+$regPdtoDiseno->registro; }

    for ($idNoTinta = 1; $idNoTinta <= $pdtoDiseno_notintas; $idNoTinta++)
    { $pdtoRodillo_rodillo[$idNoTinta] = $_POST['txta_rodillo'.$idNoTinta]; }  

    // Si doy click en alguna Impresión //
    if ($_GET['cdgjuego'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $link_mysqli = conectar();
        $pdtoJuegoSelect = $link_mysqli->query("
          SELECT pdtojuego.cdgdiseno,
            pdtojuego.idjuego,
            pdtojuego.proveedor,
            pdtojuego.fchrecepcion,
            pdtojuego.girosmax,
            pdtodiseno.alpaso,
            pdtojuego.algiro,
            pdtodiseno.registro,
            pdtojuego.cdgjuego,
            pdtojuego.sttjuego,
            pdtodiseno.notintas,
            pdtodiseno.alto,
            ((pdtodiseno.ancho*pdtodiseno.alpaso)+pdtodiseno.registro) AS tabla,
            (pdtodiseno.alto*pdtojuego.algiro) AS circunferencia,
            ((pdtodiseno.alto*pdtojuego.algiro)/".pi().") AS diametro
          FROM pdtodiseno,
            pdtojuego
          WHERE pdtodiseno.cdgdiseno = pdtojuego.cdgdiseno AND
            pdtojuego.cdgjuego = '".$pdtoJuego_cdgjuego."'");
        
        if ($pdtoJuegoSelect->num_rows > 0)
        { $regPdtoJuego = $pdtoJuegoSelect->fetch_object();

          $pdtoJuego_cdgdiseno = $regPdtoJuego->cdgdiseno;
          $pdtoJuego_idjuego = $regPdtoJuego->idjuego;
          $pdtoJuego_proveedor = $regPdtoJuego->proveedor;
          $pdtoJuego_fchrecepcion = $regPdtoJuego->fchrecepcion;
          $pdtoJuego_girosmax = $regPdtoJuego->girosmax;
          $pdtoJuego_alpaso = $regPdtoJuego->alpaso;
          $pdtoJuego_algiro = $regPdtoJuego->algiro;
          $pdtoJuego_registro = $regPdtoJuego->registro;
          $pdtoJuego_cdgjuego = $regPdtoJuego->cdgjuego;
          $pdtoJuego_sttjuego = $regPdtoJuego->sttjuego;
          $pdtoDiseno_notintas = $regPdtoJuego->notintas;
          $pdtoDiseno_alto = $regPdtoJuego->alto;
          $pdtoJuego_tabla = $regPdtoJuego->tabla;
          $pdtoJuego_circunferencia = $regPdtoJuego->circunferencia;
          $pdtoJuego_diametro = $regPdtoJuego->diametro;

          $link_mysqli = conectar();
          $pdtoRodilloSelect = $link_mysqli->query("
            SELECT * FROM pdtorodillo
            WHERE cdgjuego = '".$pdtoJuego_cdgjuego."'");

          if ($pdtoRodilloSelect->num_rows > 0)
          { while ($regPdtoRodillo = $pdtoRodilloSelect->fetch_object())
            { $pdtoRodillo_rodillo[$regPdtoRodillo->idrodillo] = $regPdtoRodillo->rodillo; }
          }

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($pdtoJuego_sttjuego == '1')
              { $pdtoJuego_newsttjuego = '0'; }
            
              if ($pdtoJuego_sttjuego == '0')
              { $pdtoJuego_newsttjuego = '1'; }
              
              $link_mysqli = conectar();
              $link_mysqli->query("
                UPDATE pdtojuego
                SET sttjuego = '".$pdtoJuego_newsttjuego."' 
                WHERE cdgjuego = '".$pdtoJuego_cdgjuego."'");
                
              if ($link_mysqli->affected_rows > 0)
              { $msg_alert = 'El juego de rodillos fue actualizado en su status.'; }
              else
              { $msg_alert = 'El juego de rodillos NO fue actualizado (status).'; }
            } else
            { $msg_alert = $msg_norewrite; }            
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $link_mysqli = conectar();
              $prodLoteSelect = $link_mysqli->query("
                SELECT * FROM prodlote
                WHERE cdgjuego = '".$pdtoJuego_cdgjuego."'");
                
              if ($pdtoLoteSelect->num_rows > 0)
              { $msg_alert = 'El juego de rodillos cuenta con lotes procesados, no puede ser eliminado.'; }
              else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  DELETE FROM pdtojuego
                  WHERE cdgjuego = '".$pdtoJuego_cdgjuego."'
                    AND sttjuego = '0'");
                  
                if ($link_mysqli->affected_rows > 0)
                { $link_mysqli = conectar();
                  $link_mysqli->query("
                    DELETE FROM pdtorodillo
                    WHERE cdgjuego = '".$pdtoJuego_cdgjuego."'");

                  $msg_alert = 'El juego de rodillos fue eliminado con exito.'; 
                } else
                { $msg_alert = 'El juego de rodillos NO fue eliminado.'; }
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
      { if (strlen($pdtoJuego_cdgdiseno) > 0 AND strlen($pdtoJuego_idjuego) > 0)
        { // Buscar coincidencias
          $link_mysqli = conectar();
          $pdtoDisenoSelect = $link_mysqli->query("
            SELECT * FROM pdtodiseno
            WHERE cdgdiseno = '".$pdtoJuego_cdgdiseno."'");

          if ($pdtoDisenoSelect->num_rows > 0)
          { $link_mysqli = conectar();
            $pdtoJuegoSelect = $link_mysqli->query("
              SELECT * FROM pdtojuego
              WHERE cdgdiseno = '".$pdtoJuego_cdgdiseno."' AND 
                idjuego = '".$pdtoJuego_idjuego."'");
          
            if ($pdtoJuegoSelect->num_rows > 0)
            { $regPdtoJuego = $pdtoJuegoSelect->fetch_object();

              $pdtoJuego_cdgjuego = $regPdtoJuego->cdgjuego;

              $link_mysqli = conectar();
              $link_mysqli->query("
                UPDATE pdtojuego
                SET proveedor = '".$pdtoJuego_proveedor."',
                  fchrecepcion = '".$pdtoJuego_fchrecepcion."',
                  algiro = '".$pdtoJuego_algiro."',
                  girosmax = '".$pdtoJuego_girosmax."'
                WHERE cdgjuego = '".$pdtoJuego_cdgjuego."' AND 
                  sttjuego = '1'");
                
              if ($link_mysqli->affected_rows > 0) 
              { $msg_alert = 'El juego de rodillos fue actualizado con exito.'; }
              else
              { $msg_alert = 'El juego de rodillos NO fue actualizado.'; } 
            } 
            else
            { for ($subcdgjuego = 1; $subcdgjuego <= 100; $subcdgjuego++)
              { $pdtoJuego_cdgjuego = $pdtoDiseno_cdgdiseno.str_pad($subcdgjuego,3,'0',STR_PAD_LEFT);

                if ($subcdgjuego > 99)
                { $msg_alert = 'El juego de rodillos NO fue insertado, se ha alcanzado el tope de juegos por diseno.'.$pdtoJuego_cdgjuego; }
                else
                { $link_mysqli = conectar();
                  $link_mysqli->query("
                    INSERT INTO pdtojuego
                      (cdgdiseno, idjuego, proveedor, fchrecepcion, algiro, girosmax, cdgjuego)
                    VALUES
                      ('".$pdtoDiseno_cdgdiseno."', '".$pdtoJuego_idjuego."', '".$pdtoJuego_proveedor."', '".$pdtoJuego_fchrecepcion."', '".$pdtoJuego_algiro."', '".$pdtoJuego_girosmax."', '".$pdtoJuego_cdgjuego."')");
                
                  if ($link_mysqli->affected_rows > 0) 
                  { $msg_alert = 'El juego de rodillos fue insertado con exito.'; 

                    $subcdgjuego = 100; }      
                }
              }
            }

            for ($idNoTinta = 1; $idNoTinta <= $pdtoDiseno_notintas; $idNoTinta++)
            { $link_mysqli = conectar();
              $link_mysqli->query("
                INSERT INTO pdtorodillo
                  (cdgjuego, idrodillo, rodillo)
                VALUES
                  ('".$pdtoJuego_cdgjuego."', '".$idNoTinta."', '".$pdtoRodillo_rodillo[$idNoTinta]."')");

              if ($link_mysqli->affected_rows <= 0) 
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE pdtorodillo
                  SET rodillo = '".$pdtoRodillo_rodillo[$idNoTinta]."'
                  WHERE cdgjuego = '".$pdtoJuego_cdgjuego."' AND
                    idrodillo = '".$idNoTinta."'"); }
            }
          } else
          { $msg_alert = 'El juego de rodillos NO fue procesado. (La circunferencia NO es multiplo)'; }
        } 
      } else
      { $msg_alert = $msg_norewrite; }         
    }
    
    // Vista de juegos por diseño //
    if (substr($sistModulo_permiso,0,1) == 'r')
    { if ($_POST['chk_vertodos'])
      { $vertodo = 'checked'; 
      
        $link_mysqli = conectar();
        $pdtoJuegoSelect = $link_mysqli->query("
          SELECT pdtojuego.cdgdiseno,
            pdtojuego.idjuego,
            pdtojuego.proveedor,
            pdtojuego.fchrecepcion,
            pdtojuego.girosmax,
            pdtodiseno.alpaso,
            pdtojuego.algiro,
            pdtodiseno.registro,
            pdtojuego.cdgjuego,
            pdtojuego.sttjuego,
            pdtodiseno.notintas,
            pdtodiseno.alto,
            ((pdtodiseno.ancho*pdtodiseno.alpaso)+pdtodiseno.registro) AS tabla,
            (pdtodiseno.alto*pdtojuego.algiro) AS circunferencia,
            ((pdtodiseno.alto*pdtojuego.algiro)/".pi().") AS diametro
          FROM pdtodiseno, pdtojuego
          WHERE pdtodiseno.cdgdiseno = pdtojuego.cdgdiseno
            AND pdtojuego.cdgdiseno = '".$pdtoJuego_cdgdiseno."'
          ORDER BY pdtojuego.sttjuego DESC,
            pdtojuego.fchrecepcion DESC"); }
      else
      { $link_mysqli = conectar();
        $pdtoJuegoSelect = $link_mysqli->query("
          SELECT pdtojuego.cdgdiseno,
            pdtojuego.idjuego,
            pdtojuego.proveedor,
            pdtojuego.fchrecepcion,
            pdtojuego.girosmax,
            pdtodiseno.alpaso,
            pdtojuego.algiro,
            pdtodiseno.registro,
            pdtojuego.cdgjuego,
            pdtojuego.sttjuego,
            pdtodiseno.notintas,
            pdtodiseno.alto,
            ((pdtodiseno.ancho*pdtodiseno.alpaso)+pdtodiseno.registro) AS tabla,
            (pdtodiseno.alto*pdtojuego.algiro) AS circunferencia,
            ((pdtodiseno.alto*pdtojuego.algiro)/".pi().") AS diametro
          FROM pdtodiseno, pdtojuego
          WHERE pdtodiseno.cdgdiseno = pdtojuego.cdgdiseno
            AND pdtojuego.cdgdiseno = '".$pdtoJuego_cdgdiseno."'
            AND pdtojuego.sttjuego = '1'
          ORDER BY pdtojuego.fchrecepcion DESC"); }
      
      if ($pdtoJuegoSelect->num_rows > 0)
      { $idJuego = 1;
        while ($regPdtoJuego = $pdtoJuegoSelect->fetch_object())
        { $pdtoJuegos_idjuego[$idJuego] = $regPdtoJuego->idjuego;
          $pdtoJuegos_proveedor[$idJuego] = $regPdtoJuego->proveedor;    
          $pdtoJuegos_fchrecepcion[$idJuego] = $regPdtoJuego->fchrecepcion;
          $pdtoJuegos_alpaso[$idJuego] = $regPdtoJuego->alpaso;
          $pdtoJuegos_algiro[$idJuego] = $regPdtoJuego->algiro;
          $pdtoJuegos_registro[$idJuego] = $regPdtoJuego->registro;
          $pdtoJuegos_girosmax[$idJuego] = $regPdtoJuego->girosmax;
          $pdtoJuegos_tabla[$idJuego] = $regPdtoJuego->tabla;
          $pdtoJuegos_diametro[$idJuego] = $regPdtoJuego->diametro;
          $pdtoJuegos_circunferencia[$idJuego] = $regPdtoJuego->circunferencia;
          $pdtoJuegos_rendimiento[$idJuego] = ((($regPdtoJuego->algiro * $regPdtoJuego->alpaso)*($regPdtoJuego->girosmax))/1000);
          $pdtoJuegos_cdgjuego[$idJuego] = $regPdtoJuego->cdgjuego;
          $pdtoJuegos_sttjuego[$idJuego] = $regPdtoJuego->sttjuego; 

          // Consulta de desgaste proyectado por juego de rodillos //
          $link_mysqli = conectar();
          $prodLoteOpeSelect = $link_mysqli->query("
            SELECT ((SUM(prodloteope.longin)*pdtodiseno.alpaso)/pdtodiseno.alto) AS desgaste
            FROM pdtodiseno, pdtojuego, prodloteope
            WHERE pdtodiseno.cdgdiseno = pdtojuego.cdgdiseno
              AND pdtojuego.cdgjuego = prodloteope.cdgjuego
              AND prodloteope.cdgjuego = '".$pdtoJuegos_cdgjuego[$idJuego]."'
              AND prodloteope.cdgoperacion = '01001'");

          if ($prodLoteOpeSelect->num_rows > 0)
          { $regProdLoteOpe = $prodLoteOpeSelect->fetch_object(); 

            $pdtoJuegos_desgaste[$idJuego] = $regProdLoteOpe->desgaste; }

          // Consulta de desgaste efectuado por juego de rodillos //
          $link_mysqli = conectar();
          $prodLoteOpeSelect = $link_mysqli->query("
            SELECT ((SUM(prodloteope.longin)*pdtodiseno.alpaso)/pdtodiseno.alto) AS desgaste
            FROM pdtodiseno, pdtojuego, prodloteope
            WHERE pdtodiseno.cdgdiseno = pdtojuego.cdgdiseno
              AND pdtojuego.cdgjuego = prodloteope.cdgjuego
              AND prodloteope.cdgjuego = '".$pdtoJuegos_cdgjuego[$idJuego]."'
              AND prodloteope.cdgoperacion = '20001'");

          if ($prodLoteOpeSelect->num_rows > 0)
          { $regProdLoteOpe = $prodLoteOpeSelect->fetch_object(); 

            $pdtoJuegos_desgasteR[$idJuego] = $regProdLoteOpe->desgaste; }            

          $idJuego++; }

        $numJuegos = $pdtoJuegoSelect->num_rows; 
      }
    }

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
        $pdtoDisenos_cdgsustrato[$idDiseno] = $regPdtoDiseno->cdgsustrato;
        $pdtoDisenos_numTintas[$idDiseno] = $regPdtoDiseno->notintas;
        $pdtoDisenos_cdgdiseno[$idDiseno] = $regPdtoDiseno->cdgdiseno; 

         $idDiseno++; }

      $numDisenos = $pdtoDisenoSelect->num_rows; }

    // Generación de página //
    echo '
    <form id="frm_pdtorodillo" name="frm_pdtorodillo" method="POST" action="pdtoRodillo.php">
      <table align="center">
        <thead>
          <tr><th colspan="2" align="left">Juegos de placas</th></tr>
        </thead>
        <tbody>
          <tr><td><label for="lbl_cdgbloque"><a href="../sm_producto/pdtoDiseno.php?cdgdiseno='.$pdtoJuego_cdgdiseno.'">Dise&ntilde;o</a></label><br/>
              <select style="width:240px" id="slc_cdgdiseno" name="slc_cdgdiseno" onchange="document.frm_pdtorodillo.submit()">
                <option value="">Selecciona una opcion</option>';
      
    for ($idDiseno = 1; $idDiseno <= $numDisenos; $idDiseno++) 
    { echo '
                        <option value="'.$pdtoDisenos_cdgdiseno[$idDiseno].'"';
              
      if ($pdtoJuego_cdgdiseno == $pdtoDisenos_cdgdiseno[$idDiseno]) { echo ' selected="selected"'; }

      echo '>'.$pdtoDisenos_diseno[$idDiseno].'</option>'; }

    echo '
              </select></td>
            <td><label for="lbl_idjuego">No. Juego</label><br/>
              <input type="text" style="width:140px;" maxlength="24" id="txt_idjuego" name="txt_idjuego" value="'.$pdtoJuego_idjuego.'" title="Identificador del juego de rodillos" required/></td></tr>
          <tr><td><label for="lbl_proveedor">Proveedor</label><br/>
              <input type="text" style="width:240px;" maxlength="48" id="txt_proveedor" name="txt_proveedor" value="'.$pdtoJuego_proveedor.'" title="Proveedor del juego de rodillos" required/></td>
            <td><label for="lbl_fchrecepcion">Fecha recepci&oacute;n</label><br/>
              <input type="date" style="width:140px;" id="date_fchrecepcion" name="date_fchrecepcion" value="'.$pdtoJuego_fchrecepcion.'" title="Fecha recepcion del juego de rodillos" required/></td>
          <tr><td></td>
            <td><label for="lbl_girosmax">'.utf8_decode('Máximo de giros').'</label><br/>
              <input type="text" style="width:140px;text-align:right;" maxlength="8" id="txt_girosmax" name="txt_girosmax" value="'.$pdtoJuego_girosmax.'" title="Giros garantizados por el proveedor" required/></td></tr>
          <tr><td colspan="2">
              <table align="center">
                <thead>
                  <tr><th colspan="2">Repeticiones</th><th colspan="4">'.utf8_decode('Medidas en milímetros').'</th></tr>
                </thead>
                <tbody>
                  <tr align="center">
                    <th><label for="lbl_alpaso">Al paso</label><br/>
                      <label for="lbl_valoralpaso">'.$pdtoJuego_alpaso.'</label></th>
                    <td><label for="lbl_algiro">Al giro</label><br/>
                      <input type="text" style="width:50px;text-align:right;" maxlength="12" id="txt_algiro" name="txt_algiro" value="'.$pdtoJuego_algiro.'" title="Repeticiones al giro" required/></td>
                    <th><label for="lbl_registro">Registro</label><br/>
                      <label for="lbl_valorregistro">'.$pdtoJuego_registro.'</label></th>
                    <th><label for="lbl_tabla">Tabla</label><br/>
                      <label for="lbl_valortabla">'.$pdtoJuego_tabla.'</label></th>
                    <th><label for="lbl_circunferencia">Circunferencia</label><br/>
                      <label for="lbl_valorcircunferencia">'.$pdtoJuego_circunferencia.'</label></th>
                    <th><label for="lbl_diametro">Diametro</label><br/>
                      <label for="lbl_valordiametro">'.number_format($pdtoJuego_diametro,2).'</label></th></tr>
                </tbody>
              </table>
            </td></tr>';

    for ($idNoTinta = 1; $idNoTinta <= $pdtoDiseno_notintas; $idNoTinta++)
    { echo '
          <tr><td colspan="2"><label for="ttl_rodillo'.$idNoTinta.'">Informaci&oacute;n del cilindro o cliche #'.$idNoTinta.'</label><br/>
              <textarea id="txta_rodillo'.$idNoTinta.'" name="txta_rodillo'.$idNoTinta.'" cols="55" rows="2">'.$pdtoRodillo_rodillo[$idNoTinta].'</textarea></td></tr>'; }
    echo '
        </body>
        <tfoot>
          <tr><td colspan="2" align="right"><input type="submit" id="btn_submit" name="btn_submit" value="Salvar" /></td></tr>
        </tfoot>                        
      </table><br/>
      
      <table align="center">
        <thead>      
          <tr align="center">
            <td colspan="2"></td>
            <th colspan="3">'.utf8_decode('Medidas en milímetros').'</th>
            <td colspan="3"></td>
            <th colspan="2"><input type="checkbox" name="chk_vertodos" id="chk_vertodos" onclick="document.frm_pdtorodillo.submit()" '.$vertodo.'>
              <label for="lbl_vertodo">Ver todo</label></th>
          </tr>
          <tr align="center">
            <th><label for="lbl_ttlidimpresion">No. Juego</label></th>
            <th><label for="lbl_ttlimpresion">Proveedor</label></th>
            <th><label for="lbl_ttltabla">Tabla</label></th>
            <th><label for="lbl_ttlcircunferencia">Circunferencia</label></th>
            <th><label for="lbl_ttldiametro">Diametro</label></th>            
            <th><label for="lbl_ttlcorte">Rendimiento</label></th> 
            <th colspan="2"><label for="lbl_ttlcorte">Desgaste</label></th>     
            <th colspan="2"><label for="lbl_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';

    if ($numJuegos > 0)
    { for ($idJuego=1; $idJuego<=$numJuegos; $idJuego++)
      { echo '
          <tr align="center">
            <td align="left"><strong>'.$pdtoJuegos_idjuego[$idJuego].'</strong></td>
            <td align="left">'.$pdtoJuegos_proveedor[$idJuego].'</td>
            <td><strong>'.$pdtoJuegos_tabla[$idJuego].'</strong></td>
            <td><strong>'.$pdtoJuegos_circunferencia[$idJuego].'</strong></td>
            <td><strong>'.number_format($pdtoJuegos_diametro[$idJuego],2).'</strong></td>            
            <td align="right"><strong>'.$pdtoJuegos_rendimiento[$idJuego].'</strong> mlls</td>
            <td align="right"><strong>'.number_format($pdtoJuegos_desgaste[$idJuego],3,'.','').'</strong> mlls<br />
              <strong>'.number_format($pdtoJuegos_desgasteR[$idJuego],3,'.','').'</strong> mlls</td>
            <td align="right"><strong>'.number_format(((100*$pdtoJuegos_desgaste[$idJuego])/$pdtoJuegos_rendimiento[$idJuego]),2,'.','').'</strong> %<br />
              <strong>'.number_format(((100*$pdtoJuegos_desgasteR[$idJuego])/$pdtoJuegos_rendimiento[$idJuego]),2,'.','').'</strong> %</td>';

        if ((int)$pdtoJuegos_sttjuego[$idJuego] > 0)
        { echo '
            <td><a href="pdtoRodillo.php?cdgjuego='.$pdtoJuegos_cdgjuego[$idJuego].'">'.$png_search.'</a></td>                        
            <td><a href="pdtoRodillo.php?cdgjuego='.$pdtoJuegos_cdgjuego[$idJuego].'&proceso=update">'.$png_power_blue.'</a></td>'; }
        else
        { echo '
            <td><a href="pdtoRodillo.php?cdgjuego='.$pdtoJuegos_cdgjuego[$idJuego].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td><a href="pdtoRodillo.php?cdgjuego='.$pdtoJuegos_cdgjuego[$idJuego].'&proceso=update">'.$png_power_black.'</a></td>'; }
      }      
    }

    echo '
        </tbody>
        <tfoot>
          <tr><th colspan="10" align="right">              
              <label for="lbl_ppgdatos">['.$numJuegos.'] Registros encontrados</label></th></tr>
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
