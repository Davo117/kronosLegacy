<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '60010';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);
    
    // Captura de parametros para programación
    $prodProgramacion_cdgimpresion = $_POST['slc_cdgimpresion'];
    $prodProgramacion_cdgmaquina = $_POST['slc_cdgmaquina'];
    $prodProgramacion_cdgjuego = $_POST['slc_cdgjuego'];
    $prodProgramacion_fchprograma = trim($_POST['txt_fchprograma']);
    $prodProgramacion_ancho = $_POST['text_amplitud'];
    
    //////////////////////////////////////////
    
    // Determinar que la fecha sea válida
    if ($prodProgramacion_fchprograma == '')
    { $prodProgramacion_fchprograma = date('Y-m-d');
      $prodProgramacion_cdgfchprograma = date('ymd'); }
    else
    { $fchprograma = str_replace("-", "", $prodProgramacion_fchprograma);

      $dia = str_pad(substr($fchprograma,6,2),2,'0',STR_PAD_LEFT);
      $mes = str_pad(substr($fchprograma,4,2),2,'0',STR_PAD_LEFT);
      $ano = str_pad(substr($fchprograma,0,4),4,'0',STR_PAD_LEFT);
      $anos = str_pad(substr($fchprograma,2,2),2,'0',STR_PAD_LEFT);

      if (checkdate((int)$mes,(int)$dia,(int)$ano))
      { $prodProgramacion_fchprograma = $ano.'-'.$mes.'-'.$dia; 
        $prodProgramacion_cdgfchprograma = $anos.$mes.$dia;  }
      else
      { $prodProgramacion_fchprograma = date('Y-m-d');
        $prodProgramacion_cdgfchprograma = date('ymd'); }
    }
    //////////////////////////////////////////

    // Trabajar con un lote especifico
    if ($_GET['cdglote'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $link_mysqli = conectar();
        $prodLoteSelect = $link_mysqli->query("
          SELECT * FROM prodlote
          WHERE cdglote = '".$_GET['cdglote']."'");

        if ($prodLoteSelect->num_rows > 0)
        { $regProdLote = $prodLoteSelect->fetch_object();

          $prodProgramacion_cdglote = $regProdLote->cdglote;
          $prodProgramacion_fchprograma = $regProdLote->fchprograma;
          $prodProgramacion_longitud = $regProdLote->longitud;
          $prodProgramacion_noop = $regProdLote->noop;
          $prodProgramacion_cdgimpresion = $regProdLote->cdgproducto;
          $prodProgramacion_sttlote = $regProdLote->sttlote;

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($prodProgramacion_sttlote == 'A')
              { $prodProgramacion_newsttlote = 'D'; }

              if ($prodProgramacion_sttlote == 'D')
              { $prodProgramacion_newsttlote = 'A'; }

              if ($prodProgramacion_newsttlote != '')
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE prodlote
                  SET sttlote = '".$prodProgramacion_newsttlote."'
                  WHERE cdglote = '".$prodProgramacion_cdglote."'");

                if ($link_mysqli->affected_rows > 0)
                { $msg_alert .= 'El NoOP '.$prodProgramacion_noop.' fue actualizado en su status.'; }
              } else
              { $msg_alert .= 'El NoOP '.$prodProgramacion_noop.' NO fue actualizado en su status, ya se encuentra en otro proceso.'; }
            } else
            { $msg_alert = $msg_norewrite.' (Status)'; }
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $link_mysqli = conectar();
              $prodLoteOpeSelect = $link_mysqli->query("
                SELECT * FROM prodloteope
                WHERE cdglote = '".$prodProgramacion_cdglote."'
                AND (cdgoperacion NOT LIKE '00090' AND cdgoperacion NOT LIKE '10001')");

              if ($prodLoteOpeSelect->num_rows > 0)
              { $msg_alert = 'El NoOP '.$prodProgramacion_noop.' NO fue eliminado de la programacion por que ya fue afectado en Produccion.'; }
              else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  DELETE FROM prodlote
                  WHERE cdglote = '".$prodProgramacion_cdglote."'
                  AND sttlote = 'D'");

                if ($link_mysqli->affected_rows > 0)
                { $link_mysqli = conectar();
                  $link_mysqli->query("
                    DELETE FROM prodloteope
                    WHERE cdglote = '".$prodProgramacion_cdglote."' AND
                      cdgoperacion NOT LIKE '00090'");
                    
                  $link_mysqli = conectar();
                  $link_mysqli->query("
                    DELETE FROM prodloteproc
                    WHERE cdglote = '".$prodProgramacion_cdglote."'");

                  $link_mysqli = conectar();
                  $link_mysqli->query("
                    UPDATE proglote
                    SET sttlote = '7'
                    WHERE cdglote = '".$prodProgramacion_cdglote."'");

                  if ($link_mysqli->affected_rows > 0)
                  { $msg_alert = 'El NoOp '.$prodProgramacion_noop.' fue eliminado \n'; }                  
                } else
                { $msg_alert = 'El NoOp '.$prodProgramacion_noop.' NO fue eliminado \n'; }
              }
            } else
            { $msg_alert = $msg_nodelete; }
          }
        } else
        { $msg_alert = "El NoOP no existe, o fue cancelado."; }

        $prodLoteSelect->close;
      } else
      { $msg_alert = $msg_noread; }
    }
    //////////////////////////////////////////

    // Asignar bobinas seleccionadas
    if ($_POST['btn_submit'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { $link_mysqli = conectar();
        $progLoteSelect = $link_mysqli->query("
          SELECT proglote.idlote,
            proglote.lote,
            proglote.amplitud,
            proglote.longitud,
            proglote.espesor,
            proglote.encogimiento,
            proglote.peso,
            proglote.tarima,
            proglote.cdglote
          FROM progbloque,
            proglote
          WHERE progbloque.cdgbloque = proglote.cdgbloque
            AND proglote.amplitud = ".$prodProgramacion_ancho."
            AND proglote.sttlote = '7'
          ORDER BY proglote.amplitud,
            progbloque.fchbloque,
            proglote.tarima,
            proglote.idlote");

        if ($progLoteSelect->num_rows > 0)
        { $link_mysqli = conectar();
          $pdtoImpresionSelect = $link_mysqli->query("
            SELECT pdtodiseno.alto,
              pdtodiseno.alpaso,
              pdtoimpresion.cdgimpresion
            FROM pdtodiseno,
              pdtoimpresion
            WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
              pdtoimpresion.cdgimpresion = '".$prodProgramacion_cdgimpresion."'");

          if ($pdtoImpresionSelect->num_rows > 0)
          { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();
            $pdtoImpresion_corte = $regPdtoImpresion->alto;
            $pdtoImpresion_alpaso = $regPdtoImpresion->alpaso;
            $pdtoImpresion_cdgproducto = $regPdtoImpresion->cdgimpresion; }

          while ($regProgLote = $progLoteSelect->fetch_object())
          { if (isset($_REQUEST['chk_'.$regProgLote->cdglote]))
            { $prodProgramacion_cdglote = $regProgLote->cdglote;
              $prodProgramacion_longitud = $regProgLote->longitud;
              $prodProgramacion_amplitud = $regProgLote->amplitud;
              $prodProgramacion_peso = $regProgLote->peso;

              $link_mysqli = conectar();
              $progLoteSelectMax = $link_mysqli->query("
                SELECT MAX(noop) AS noopmax,
                COUNT(noop) AS noopnum
                FROM prodlote");

              $regProdLoteMax = $progLoteSelectMax->fetch_object();
              $prodProgramacion_noopmax = (int)$regProdLoteMax->noopmax;
              $prodProgramacion_noopnum = (int)$regProdLoteMax->noopnum;

              // Insertar bobina en la programación
              /* Queda pendiente agregar la operacion de programacion en la tabla prodloteope */
              
              if ($prodProgramacion_noopmax == $prodProgramacion_noopnum)
              { $prodProgramacion_noop = $prodProgramacion_noopmax+1;

                $link_mysqli = conectar();
                $link_mysqli->query("
                  INSERT INTO prodlote
                    (cdglote, noop, cdgproducto, longitud, amplitud, peso, cdgproceso, fchprograma, fchmovimiento)
                  VALUES
                    ('".$prodProgramacion_cdglote."', '".$prodProgramacion_noop."', '".$prodProgramacion_cdgimpresion."', '".$prodProgramacion_longitud."', '".$prodProgramacion_amplitud."', '".$prodProgramacion_peso."', '10', '".$prodProgramacion_fchprograma."', NOW())");
              }
              else
              { for ($noop = 1; $noop < $prodProgramacion_noopmax; $noop++)
                { $prodProgramacion_noop = $noop;
                    
                  $link_mysqli = conectar();
                  $link_mysqli->query("
                    INSERT INTO prodlote
                      (cdglote, noop, cdgproducto, longitud, amplitud, peso, cdgproceso, fchprograma, fchmovimiento)
                    VALUES
                      ('".$prodProgramacion_cdglote."', '".$prodProgramacion_noop."', '".$prodProgramacion_cdgimpresion."', '".$prodProgramacion_longitud."', '".$prodProgramacion_amplitud."', '".$prodProgramacion_peso."', '10', '".$prodProgramacion_fchprograma."', NOW())");

                  if ($link_mysqli->affected_rows > 0)
                  { $noop = $prodProgramacion_noopmax; }
                }
              }
                
              $link_mysqli = conectar();
              $link_mysqli->query("
                INSERT INTO prodloteproc
                  (cdglote, cdgproceso, longitud, amplitud, peso, fchmovimiento)
                VALUES
                  ('".$prodProgramacion_cdglote."', '10', '".$prodProgramacion_longitud."', '".$prodProgramacion_amplitud."', '".$prodProgramacion_peso."', NOW())");

              $link_mysqli = conectar();
              $link_mysqli->query("
                INSERT INTO prodloteope
                  (cdglote, cdgoperacion, cdgproceso, cdgempleado, cdgmaquina, cdgjuego, longin, longout, fchoperacion, fchmovimiento)
                VALUES
                  ('".$prodProgramacion_cdglote."', '10001', '10', '".$_SESSION['cdgusuario']."', '".$prodProgramacion_cdgmaquina."', '".$prodProgramacion_cdgjuego."', '".$prodProgramacion_longitud."', '".$prodProgramacion_longitud."', NOW(), NOW())");

              //////////////////////////////////////////////////////////////

              if ($link_mysqli->affected_rows > 0)
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE proglote
                  SET sttlote = '8'
                  WHERE cdglote = '".$prodProgramacion_cdglote."'");

                if ($link_mysqli->affected_rows > 0)
                { $msg_alert .= 'El NoOp '.$prodProgramacion_noop.' fue generado. \n'; } 
                else
                { $link_mysqli = conectar();
                  $link_mysqli->query("
                    DELETE FROM prodproclote
                    WHERE cdglote = '".$prodProgramacion_cdglote."'");
                  
                  $link_mysqli = conectar();
                  $link_mysqli->query("
                    DELETE * FROM prodlote
                    WHERE cdglote = '".$prodProgramacion_cdglote."'"); 
                    
                  $msg_alert .= 'El NoOp '.$prodProgramacion_noop.' NO fue generado (Lote). \n'; }
              } else
              { $msg_alert .= 'El NoOp '.$prodProgramacion_noop.' NO fue generado. \n'; } 
            }
          }
        }
      } else
      { $msg_alert = $msg_norewrite; }
    }
    //////////////////////////////////////////////////////////////

  if (substr($sistModulo_permiso,0,1) == 'r')
  { // Filtrado de mezclas por impresión
    $link_mysqli = conectar();
    $pdtoProyectoSelect = $link_mysqli->query("
      SELECT pdtodiseno.iddiseno,
        pdtodiseno.diseno,
        pdtodiseno.alpaso,
        pdtodiseno.alto,
        pdtosustrato.ancho,
        pdtodiseno.cdgdiseno 
      FROM pdtodiseno, 
        pdtosustrato
      WHERE pdtodiseno.cdgsustrato = pdtosustrato.cdgsustrato AND
        pdtodiseno.sttdiseno = '1'
      ORDER BY pdtodiseno.iddiseno");

    $idproyecto = 1;
    while ($regPdtoDiseno = $pdtoProyectoSelect->fetch_object())
    { $pdtoDiseno_iddiseno[$idproyecto] = $regPdtoDiseno->iddiseno;
      $pdtoDiseno_diseno[$idproyecto] = $regPdtoDiseno->diseno;
      $pdtoDiseno_alpaso[$idproyecto] = $regPdtoDiseno->alpaso;
      $pdtoDiseno_alto[$idproyecto] = $regPdtoDiseno->alto;
      $pdtoDiseno_ancho[$idproyecto] = $regPdtoDiseno->ancho;
      $pdtoDiseno_cdgproyecto[$idproyecto] = $regPdtoDiseno->cdgdiseno;

      $pdtoDiseno_disenos[$regPdtoDiseno->cdgdiseno] = $regPdtoDiseno->diseno;

      $link_mysqli = conectar();
      $pdtoImpresionSelect = $link_mysqli->query("
        SELECT * FROM pdtoimpresion
        WHERE cdgdiseno = '".$regPdtoDiseno->cdgdiseno."' 
          AND sttimpresion = '1'
        ORDER BY impresion, idimpresion");

      $idimpresion = 1;
      while ($regPdtoImpresion = $pdtoImpresionSelect->fetch_object())
      { $pdtoImpresion_idimpresion[$idproyecto][$idimpresion] = $regPdtoImpresion->idimpresion;
        $pdtoImpresion_impresion[$idproyecto][$idimpresion] = $regPdtoImpresion->impresion;
        $pdtoImpresion_cdgimpresion[$idproyecto][$idimpresion] = $regPdtoImpresion->cdgimpresion;

        $pdtoImpresion_impresiones[$regPdtoImpresion->cdgimpresion] = $regPdtoImpresion->impresion;

        if ($prodProgramacion_cdgimpresion == $regPdtoImpresion->cdgimpresion)
        { $prodProgramacion_ancho = $pdtoDiseno_ancho[$idproyecto]; }

        $idimpresion++; }

      $idsimpresion[$idproyecto] = $pdtoImpresionSelect->num_rows;

      $idproyecto++; }

    $idsproyecto = $pdtoProyectoSelect->num_rows;

    //////////////////////////////////////////////////

    $link_mysqli = conectar();
    $pdtoJuegoSelect = $link_mysqli->query("
      SELECT * FROM pdtojuego
      WHERE sttjuego >= '1'");

    if ($pdtoJuegoSelect->num_rows > 0)
    { while($regPdtoJuego = $pdtoJuegoSelect->fetch_object())
      { $pdtoJuego_juegos[$regPdtoJuego->cdgjuego] = $regPdtoJuego->idjuego; }
    }

    $link_mysqli = conectar();
    $pdtoJuegoSelect = $link_mysqli->query("
      SELECT pdtojuego.cdgdiseno,
        pdtojuego.idjuego,
        pdtojuego.proveedor,
        pdtojuego.fchrecepcion,
        pdtojuego.girosmax,
        pdtojuego.alpaso,
        pdtojuego.algiro,
        pdtojuego.registro,
        pdtojuego.cdgjuego,
        pdtojuego.sttjuego,
        pdtodiseno.notintas,
        pdtodiseno.alto,
        ((((pdtodiseno.ancho*2)+pdtodiseno.empalme)*pdtojuego.alpaso)+pdtojuego.registro) AS tabla,
        (pdtodiseno.alto*pdtojuego.algiro) AS circunferencia,
        ((pdtodiseno.alto*pdtojuego.algiro)/".pi().") AS diametro
      FROM pdtodiseno, 
        pdtoimpresion, 
        pdtojuego
      WHERE pdtodiseno.cdgdiseno = pdtojuego.cdgdiseno
        AND pdtojuego.cdgdiseno = pdtoimpresion.cdgdiseno 
        AND pdtoimpresion.cdgimpresion = '".$prodProgramacion_cdgimpresion."'
        AND pdtojuego.sttjuego = '1'
      ORDER BY pdtojuego.fchrecepcion DESC");

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

        // Consulta de desgaste por juego de rodillos //
        $link_mysqli = conectar();
        $prodLoteOpeSelect = $link_mysqli->query("
          SELECT ((SUM(prodloteope.longin)*pdtojuego.alpaso)/pdtodiseno.alto) AS desgaste
          FROM pdtodiseno, pdtojuego, prodloteope
          WHERE pdtodiseno.cdgdiseno = pdtojuego.cdgdiseno
            AND pdtojuego.cdgjuego = prodloteope.cdgjuego
            AND prodloteope.cdgjuego = '".$pdtoJuegos_cdgjuego[$idJuego]."'");

        if ($prodLoteOpeSelect->num_rows > 0)
        { $regProdLoteOpe = $prodLoteOpeSelect->fetch_object(); 

          $pdtoJuegos_desgaste[$idJuego] = $regProdLoteOpe->desgaste; }

        $idJuego++; }

      $numJuegos = $pdtoJuegoSelect->num_rows; 
    }

    //////////////////////////////////////////////////

    $link_mysql = conectar();
    $prodMaquinaSelect = $link_mysql->query("
      SELECT * FROM prodmaquina 
      WHERE cdgproceso = '20' AND 
        sttmaquina >= '1'");
    
    if ($prodMaquinaSelect->num_rows > 0)
    { $idMaquina = 1;
      while ($regProdMaquina = $prodMaquinaSelect->fetch_object())
      { $prodMaquina_idmaquina[$idMaquina] = $regProdMaquina->idmaquina;
        $prodMaquina_maquina[$idMaquina] = $regProdMaquina->maquina;
        $prodMaquina_cdgmaquina[$idMaquina] = $regProdMaquina->cdgmaquina; 

        $prodMaquina_maquinas[$regProdMaquina->cdgmaquina] = $regProdMaquina->maquina;

        $idMaquina++; }

      $numMaquinas = $prodMaquinaSelect->num_rows;
    }

    //////////////////////////////////////////////////
    $link_mysqli = conectar();
    $prodLoteSelect = $link_mysqli->query("
      SELECT proglote.idlote,
        proglote.lote,
        proglote.tarima,
        proglote.amplitud,
        prodlote.longitud,
        proglote.espesor,
        proglote.encogimiento,
        prodlote.peso,
        prodlote.noop,
        pdtodiseno.iddiseno,
        pdtoimpresion.idimpresion,
        pdtodiseno.alto,
        pdtodiseno.alpaso,
        pdtodiseno.empalme,
        prodlote.cdglote,
        prodlote.sttlote
      FROM prodlote,
        pdtodiseno,
        pdtosustrato,
        pdtoimpresion,
        proglote
      WHERE prodlote.cdgproducto = pdtoimpresion.cdgimpresion
        AND pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno
        AND pdtodiseno.cdgsustrato = pdtosustrato.cdgsustrato
        AND prodlote.cdglote = proglote.cdglote
        AND prodlote.fchprograma = '".$prodProgramacion_fchprograma."'
      ORDER BY prodlote.noop");

    if ($prodLoteSelect->num_rows > 0)
    { $id_lote = 1;
      while ($regProdLote = $prodLoteSelect->fetch_object())
      { $prodProgramacions_idlote[$id_lote] = $regProdLote->idlote;
        $prodProgramacions_lote[$id_lote] = $regProdLote->lote;
        $prodProgramacions_tarima[$id_lote] = $regProdLote->tarima;
        $prodProgramacions_amplitud[$id_lote] = $regProdLote->amplitud;
        $prodProgramacions_longitud[$id_lote] = $regProdLote->longitud;
        $prodProgramacions_espesor[$id_lote] = $regProdLote->espesor;
        $prodProgramacions_encogimiento[$id_lote] = $regProdLote->encogimiento;
        $prodProgramacions_peso[$id_lote] = $regProdLote->peso;
        $prodProgramacions_noop[$id_lote] = $regProdLote->noop;
        $prodProgramacions_idproyecto[$id_lote] = $regProdLote->idproyecto;
        $prodProgramacions_idimpresion[$id_lote] = $regProdLote->idimpresion;
        $prodProgramacions_corte[$id_lote] = $regProdLote->alto;
        $prodProgramacions_alpaso[$id_lote] = $regProdLote->alpaso;
        $prodProgramacions_empalme[$id_lote] = $regProdLote->empalme;
        $prodProgramacions_cdglote[$id_lote] = $regProdLote->cdglote;
        $prodProgramacions_sttlote[$id_lote] = $regProdLote->sttlote;

        $id_lote++; }

      $numprodlotes = $prodLoteSelect->num_rows; }
    $prodLoteSelect->close;
    
    $link_mysqli = conectar();
    $progLoteSelect = $link_mysqli->query("
      SELECT proglote.idlote,
        proglote.lote,
        proglote.amplitud,
        proglote.longitud,
        proglote.espesor,
        proglote.encogimiento,
        proglote.peso,
        proglote.tarima,
        proglote.cdglote
      FROM progbloque,
        proglote
      WHERE progbloque.cdgbloque = proglote.cdgbloque
        AND (proglote.amplitud = ".$prodProgramacion_ancho.")
        AND proglote.sttlote = '7'
      ORDER BY proglote.amplitud,
        progbloque.fchbloque,
        proglote.tarima,
        proglote.idlote");

    if ($progLoteSelect->num_rows > 0)
    { $idlote = 1;
      while ($regProgLote = $progLoteSelect->fetch_object())
      { $progLotes_idlote[$idlote] = $regProgLote->idlote;
        $progLotes_tarima[$idlote] = $regProgLote->tarima;
        $progLotes_lote[$idlote] = $regProgLote->lote;
        $progLotes_amplitud[$idlote] = $regProgLote->amplitud;
        $progLotes_longitud[$idlote] = $regProgLote->longitud;
        $progLotes_espesor[$idlote] = $regProgLote->espesor;
        $progLotes_encogimiento[$idlote] = $regProgLote->encogimiento;
        $progLotes_peso[$idlote] = $regProgLote->peso;
        $progLotes_cdglote[$idlote] = $regProgLote->cdglote;

        $idlote++; }

      $numproglotes = $progLoteSelect->num_rows; }    
    $progLoteSelect->close;    
  } else
  { $msg_alert = $msg_noread; }
  
  echo '
      <form id="form_prodprogramacion" name="form_prodprogramacion" method="POST" action="prodProgramacion.php">
        <table align="center">
          <thead>
            <tr><th colspan="3" align="left">'.utf8_decode('Programación de impresión').'</th></tr>
          </thead>
            <tr>
              <td><label for="lbl_ttlproducto"><a href="../sm_producto/pdtoImpresion.php?cdgimpresion='.$prodProgramacion_cdgimpresion.'">Producto</a></label><br/>
                <select style="width:240px" id="slc_cdgimpresion" name="slc_cdgimpresion" onchange="document.form_prodprogramacion.submit()">
                  <option value="">No aplica</option>';

    for ($idproyecto = 1; $idproyecto <= $idsproyecto; $idproyecto++)
    { echo '
                  <optgroup label="'.$pdtoDiseno_iddiseno[$idproyecto].'">';

      for ($idimpresion = 1; $idimpresion <= $idsimpresion[$idproyecto]; $idimpresion++)
      { echo '
                    <option value="'.$pdtoImpresion_cdgimpresion[$idproyecto][$idimpresion].'"';

        if ($prodProgramacion_cdgimpresion == $pdtoImpresion_cdgimpresion[$idproyecto][$idimpresion])
        { $prodProgramacion_alto = $pdtoDiseno_alto[$idproyecto];
          $prodProgramacion_alpaso = $pdtoDiseno_alpaso[$idproyecto];
          $prodProgramacion_ancho = $pdtoDiseno_ancho[$idproyecto];

          echo ' selected="selected"'; }

        echo '>'.$pdtoImpresion_impresion[$idproyecto][$idimpresion].'</option>';        
      }

      echo '
                  </optgroup>';
    }

    echo '
                </select></td>
              <td colspan="2"><label for="lbl_ttlmaquina">'.utf8_decode('Máquina').'</label><br/>
                <select style="width:240px" id="slc_cdgmaquina" name="slc_cdgmaquina" onchange="document.form_prodprogramacion.submit()">
                  <option value="">No aplica</option>';

    for ($idMaquina = 1; $idMaquina <= $numMaquinas; $idMaquina++)
    { echo '
                  <option value="'.$prodMaquina_cdgmaquina[$idMaquina].'"';

      if ($prodProgramacion_cdgmaquina == $prodMaquina_cdgmaquina[$idMaquina])
      { echo ' selected="selected"'; }

      echo '>'.$prodMaquina_maquina[$idMaquina].' ['.$prodMaquina_idmaquina[$idMaquina].']</option>';        
    }

    echo '
                </select></td></tr>
            <tr><td><label for="lbl_ttljuego">'.utf8_decode('Juego de rodillos').'</label><br/>
                <select style="width:240px" id="slc_cdgjuego" name="slc_cdgjuego" onchange="document.form_prodprogramacion.submit()">
                  <option value="">No aplica</option>';

    for ($idJuego = 1; $idJuego <= $numJuegos; $idJuego++)
    { echo '
                  <option value="'.$pdtoJuegos_cdgjuego[$idJuego].'"';

      if ($prodProgramacion_cdgjuego == $pdtoJuegos_cdgjuego[$idJuego])
      { echo ' selected="selected"'; }

      echo '>'.$pdtoJuegos_proveedor[$idJuego].' ['.$pdtoJuegos_idjuego[$idJuego].']</option>';        
    }

    echo '
                </select></td>
              <td><label for="lbl_ttlfecha">Fecha</label><br/>
                <input type="date" id="txt_fchprograma" name="txt_fchprograma" value="'.$prodProgramacion_fchprograma.'" title="Fecha de asignacion" onchange="document.form_prodprogramacion.submit()" style="width:140px;" required/>
                <input type="hidden" id="text_amplitud" name="text_amplitud" value="'.$prodProgramacion_ancho.'" /></td>
              <td align="center"><a href="pdf/prodLotesBC.php?fchprograma='.$prodProgramacion_fchprograma.'" target="_blank"><img alt="Lista de NoOPs" src="../img_sistema/barcode.png" height="36" border="0"/></a></td></tr>
          <tbody>
          <tfoot>
            <tr><td colspan="3" align="right"><input type="submit" id="btn_submit" name="btn_submit" value="Salvar" /></td></tr>
          </tfoot>
        </table><br/>';

  if (substr($sistModulo_permiso,0,1) == 'r')
  { $link_mysqli = conectar();
    $prodLoteSelect = $link_mysqli->query("
      SELECT prodlote.cdgproducto,
        pdtodiseno.cdgdiseno, 
        COUNT(prodlote.cdglote) AS numbobinas,
        prodloteope.cdgmaquina,
        prodloteope.cdgjuego,
        ((SUM(longout)*pdtodiseno.alpaso)/pdtodiseno.alto) AS millares
      FROM prodlote, prodloteope, pdtoimpresion, pdtodiseno
      WHERE (prodlote.cdglote = prodloteope.cdglote AND prodloteope.cdgoperacion = '01001') AND 
        (prodlote.cdgproducto = pdtoimpresion.cdgimpresion AND pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno) AND
        prodlote.fchprograma = '".$prodProgramacion_fchprograma."'
      GROUP BY prodlote.cdgproducto,
        prodloteope.cdgmaquina,
        prodloteope.cdgjuego");

    if ($prodLoteSelect->num_rows > 0)
    { echo '
        <table align="center">
          <thead>
            <tr><th>'.utf8_decode('Diseño <br>Impresión').'</th>
              <th>'.utf8_decode('Máquina <br>de impresión').'</th>
              <th>'.utf8_decode('Juego <br>de rodillos').'</th>
              <th>'.utf8_decode('Cantidad <br>de bobinas').'</th>
              <th>'.utf8_decode('Millares <br>aproximados').'</th></tr>
          </thead>
          <tbody>';

      while ($regProdLote = $prodLoteSelect->fetch_object())
      { echo '
            <tr><td>'.$pdtoDiseno_disenos[$regProdLote->cdgdiseno].'<br>'.$pdtoImpresion_impresiones[$regProdLote->cdgproducto].'</td>
              <td>'.$prodMaquina_maquinas[$regProdLote->cdgmaquina].'</td>
              <td>'.$pdtoJuego_juegos[$regProdLote->cdgjuego].'</td>
              <td align="center">'.$regProdLote->numbobinas.'</td>
              <td align="right">'.number_format($regProdLote->millares,3).' <a href="pdf/RC-01-POT-7.5.1.php?cdgimpresion='.$regProdLote->cdgproducto.'&cdgmaquina='.$regProdLote->cdgmaquina.'&cdgjuego='.$regProdLote->cdgjuego.'&fchprograma='.$prodProgramacion_fchprograma.'&cdgfchprograma='.$prodProgramacion_cdgfchprograma.'&millares='.number_format($regProdLote->millares,3,'.','').'" target="_blank"><img alt="Lista de NoOPs" src="../img_sistema/oplist.png" height="24" border="0"/></a></td></tr>'; }

      echo '
          </tbody>
          <tfoot>
            <tr><td colspan="5"></td></tr>
          </tfoot>
        </table><br/>'; }
  }

    if ($numprodlotes > 0)
    { echo '
        <table align="center">
          <thead>
            <tr><th colspan="15">Bobinas asignadas a la fecha</th></tr>
            <tr><th colspan="2"><label for="lbl_ttltarimalote">Tarima : No. Lote</label></th>
              <th><label for="lbl_ttllote">Referencia</label></th>
              <th><label for="lbl_ttlamplitud">Amplitud</label></th>
              <th><label for="lbl_ttllongitud">Longitud</label></th>
              <th><label for="lbl_ttlespesor">Espesor</label></th>
              <th><label for="lbl_ttlencogimiento">Encogimiento</label></th>
              <th><label for="lbl_ttlpeso">Peso</label></th>
              <th><label for="lbl_ttlpeso">NoOP</label></th>
              <th colspan="3"><label for="lbl_ttlpeso">Impresi&oacute;n</label></th>
              <th><label for="lbl_ttlmillares">Millares</label></th>
              <th colspan="2"><label for="lbl_ttloperaciones">Operaciones</label></th></tr>
          </thead>
          <tbody>';

      for ($id_lote=1; $id_lote<=$numprodlotes; $id_lote++)
      { echo '
            <tr align="right">
              <td><strong>'.$prodProgramacions_tarima[$id_lote].'</strong></td>
              <td><strong>'.$prodProgramacions_idlote[$id_lote].'</strong></td>
              <td><strong>'.$prodProgramacions_lote[$id_lote].'</strong></td>
              <td>'.$prodProgramacions_amplitud[$id_lote].' <strong>mm</strong></td>
              <td>'.number_format($prodProgramacions_longitud[$id_lote],2).' <strong>mts</strong></td>
              <td>'.$prodProgramacions_espesor[$id_lote].' <strong>micras</strong></td>
              <td>'.$prodProgramacions_encogimiento[$id_lote].' <strong>%</strong></td>
              <td>'.number_format($prodProgramacions_peso[$id_lote],3).' <strong>kgs</strong></td>
              <td>'.$prodProgramacions_noop[$id_lote].'</td>
              <td align="left">'.$prodProgramacions_idproyecto[$id_lote].'</td>
              <td align="left">'.$prodProgramacions_idimpresion[$id_lote].'</td>
              <td align="left">'.$prodProgramacions_idmezcla[$id_lote].'</td>
              <td align="left">'.number_format((($prodProgramacions_longitud[$id_lote]*$prodProgramacions_alpaso[$id_lote])/$prodProgramacions_corte[$id_lote]),3).'</td>';
              
        if ($prodProgramacions_sttlote[$id_lote] != '1')
        { if ($prodProgramacions_sttlote[$id_lote] == 'A')
          { echo '
              <td acolspan="2" align="center"><a href="prodProgramacion.php?cdglote='.$prodProgramacions_cdglote[$id_lote].'&proceso=update">'.$png_power_blue.'</a></td>'; }
          
          if ($prodProgramacions_sttlote[$id_lote] == 'D')
          { echo '
              <td align="center"><a href="prodProgramacion.php?cdglote='.$prodProgramacions_cdglote[$id_lote].'&proceso=delete">'.$png_recycle_bin.'</a></td>
              <td align="center"><a href="prodProgramacion.php?cdglote='.$prodProgramacions_cdglote[$id_lote].'&proceso=update">'.$png_power_black.'</a></td>'; }

          if ($prodProgramacions_sttlote[$id_lote] == '9')
          { echo '
              <td colspan="2" align="center">'.$png_power_black.'</td>';}
        } else
        { echo '
              <td colspan="2" align="center">'.$png_gear.'</td>'; }
        
        echo '</tr>';
      }
    
      echo '
          </tbody>
          <tfoot>
            <tr><th colspan="15" align="right"><label for="lbl_ppgdatos">['.$numprodlotes.'] Bobinas programadas</label></th></tr>
          </tfoot>
        </table><br/>'; }
    
    if ($numproglotes > 0)
    { echo '        
        <table align="center">
          <thead>
            <tr><th colspan="10">Bobinas compatibles disponibles</th></tr>
            <tr align="left">
              <th colspan="3"><label for="lbl_ttlidlote">Tarima : No. Lote</label></th>
              <th><label for="lbl_ttllote">Referencia</label></th>
              <th><label for="lbl_ttlamplitud">Amplitud</label></th>
              <th><label for="lbl_ttllongitud">Longitud</label></th>
              <th><label for="lbl_ttlespesor">Espesor</label></th>
              <th><label for="lbl_ttlencogimiento">Encogimiento</label></th>
              <th><label for="lbl_ttlpeso">Peso</label></th>
              <th><label for="lbl_ttlmillares">Millares</label></th></tr>
          </thead>
          <tbody>';

      for ($idlote=1; $idlote<=$numproglotes; $idlote++)
      { echo '
            <tr align="right">
              <td><input type="checkbox" id="chk_'.$progLotes_cdglote[$idlote].'" name="chk_'.$progLotes_cdglote[$idlote].'" '.$chkbox[$progLotes_cdglote[$idlote]].'/></td>
              <td><strong>'.$progLotes_tarima[$idlote].'</strong></td>
              <td><strong>'.$progLotes_idlote[$idlote].'</strong></td>
              <td><strong>'.$progLotes_lote[$idlote].'</strong></td>
              <td>'.number_format($progLotes_amplitud[$idlote]).' <strong>mm</strong></td>
              <td>'.number_format($progLotes_longitud[$idlote],2).' <strong>mts</strong></td>
              <td>'.number_format($progLotes_espesor[$idlote]).' <strong>micras</strong></td>
              <td>'.$progLotes_encogimiento[$idlote].' <strong>%</strong></td>
              <td>'.number_format($progLotes_peso[$idlote],3).' <strong>kgs</strong></td>
              <td>'.number_format((($progLotes_longitud[$idlote]*$prodProgramacion_alpaso)/$prodProgramacion_corte),3).'</td>'; }
    
      echo '
          </tbody>
          <tfoot>
            <tr><th colspan="10" align="right"><label for="lbl_ppgdisponibilidad">['.$numproglotes.'] Bobinas disponibles</label></th></tr>
          </tfoot>
        </table>'; }

    echo '
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