<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Programación de Sello de Seguridad</title>    
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
      <section>
        <!--<a href="ayuda.php"><img id="imagen_ayuda" src="../img_sistema/help_blue.png" border="0"/></a>-->
        <Label><h1>Termoencogible</h1></label>
      </section><?php
  
  include '../datos/mysql.php';
  $link = conectar();

  m3nu_produccion();  

  $sistModulo_cdgmodulo = '60010';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { if ($_GET['mode']=='logout') { cl0s3(); }

    if ($_POST['textusername'] AND $_POST['textpassword']) { val1dat3($_POST['textusername'], $_POST['textpassword']); }

    if ($_SESSION['cdgusuario'])
    { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

      ma1n(); 
    } else
    { echo '
      <div id="loginform">
        <form id="login" action="prodProgramacion.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 
     
      exit; }    
    // Captura de parametros para programación
    $prodProgramacion_cdgimpresion = $_POST['slctCdgProducto'];
    $prodProgramacion_cdgjuego = $_POST['slctCdgJuego'];
    $prodProgramacion_cdgmaquina = $_POST['slctCdgMaquina'];    
    $prodProgramacion_fchprograma = trim($_POST['dateFchPrograma']);    
    //////////////////////////////////////////

    /* Determinar que la fecha sea válida */
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
    // Final de la validación en la fecha

    // Trabajar con un lote especifico
    if ($_GET['cdglote'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $prodLoteSelect = $link->query("
          SELECT * FROM prodlote
           WHERE cdglote = '".$_GET['cdglote']."'");

        if ($prodLoteSelect->num_rows > 0)
        { $regProdLote = $prodLoteSelect->fetch_object();

          $prodProgramacion_cdglote = $regProdLote->cdglote;          
          $prodProgramacion_serie = $regProdLote->serie;
          $prodProgramacion_noop = $regProdLote->noop;
          $prodProgramacion_cdgimpresion = $regProdLote->cdgproducto;
          $prodProgramacion_fchprograma = $regProdLote->fchprograma;               
          $prodProgramacion_sttlote = $regProdLote->sttlote;

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($prodProgramacion_sttlote == 'A')
              { $prodProgramacion_newsttlote = 'D'; }

              if ($prodProgramacion_sttlote == 'D')
              { $prodProgramacion_newsttlote = 'A'; }

              if ($prodProgramacion_newsttlote != '')
              { $link->query("
                  UPDATE prodlote
                     SET sttlote = '".$prodProgramacion_newsttlote."'
                   WHERE cdglote = '".$prodProgramacion_cdglote."'");

                if ($link->affected_rows > 0)
                { $msg_alert .= 'El NoOP '.$prodProgramacion_noop.' fue actualizado en su status.'; }
              } else
              { $msg_alert .= 'El NoOP '.$prodProgramacion_noop.' NO fue actualizado en su status, ya se encuentra en otro proceso.'; }
            } else
            { $msg_alert = $msg_norewrite.' (Status)'; }
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $prodLoteOpeSelect = $link->query("
                SELECT * FROM prodloteope
                 WHERE cdglote = '".$prodProgramacion_cdglote."' AND 
                      (cdgoperacion NOT LIKE '00090' AND cdgoperacion NOT LIKE '10001')");

              if ($prodLoteOpeSelect->num_rows > 0)
              { $msg_alert = 'El NoOP '.$prodProgramacion_noop.' NO fue eliminado de la programacion por que ya fue afectado en Produccion.'; }
              else
              { $link->query("
                  DELETE FROM prodlote
                   WHERE cdglote = '".$prodProgramacion_cdglote."' AND 
                         sttlote = 'D'");

                if ($link->affected_rows > 0)
                { $link->query("
                    DELETE FROM prodloteope
                     WHERE cdglote = '".$prodProgramacion_cdglote."' AND
                           cdgoperacion NOT LIKE '00090'");

                  $link->query("
                    UPDATE proglote
                       SET sttlote = '7'
                     WHERE cdglote = '".$prodProgramacion_cdglote."'");

                  if ($link->affected_rows > 0)
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
    } //*/
    //////////////////////////////////////////

    // Filtro de juegos de cilindros
    $prodProgramacion_newcdgjuego = true;

    if ($prodProgramacion_cdgimpresion != '')
    { $pdtoJuegoSelect = $link->query("
        SELECT pdtojuego.idjuego,
               pdtojuego.proveedor,
               pdtojuego.cdgjuego
          FROM pdtojuego,
               pdtoimpresion
        WHERE (pdtoimpresion.cdgimpresion = '".$prodProgramacion_cdgimpresion."' AND
               pdtojuego.cdgimpresion = pdtoimpresion.cdgimpresion AND
               pdtojuego.sttjuego = '1')
      ORDER BY pdtojuego.fchrecepcion DESC");

      if ($pdtoJuegoSelect->num_rows > 0)
      { $item = 0;

        while ($regPdtoJuego = $pdtoJuegoSelect->fetch_object())
        { $item++;

          $pdtoJuego_idjuego[$item] = $regPdtoJuego->idjuego;
          $pdtoJuego_proveedor[$item] = $regPdtoJuego->proveedor;
          $pdtoJuego_cdgjuego[$item] = $regPdtoJuego->cdgjuego;

          if ($prodProgramacion_cdgjuego == $regPdtoJuego->cdgjuego)
          { $prodProgramacion_newcdgjuego = false; }
        }

        $nJuegos = $item; }
    } else
    { $prodProgramacion_cdgjuego = ''; }

    if ($prodProgramacion_newcdgjuego == true)
    { $prodProgramacion_cdgjuego = ''; }
    // Final del filtro de juegos de cilindros

    // Asignar lotes seleccionados    
    if ($_POST['bttnSalvar'])
    { $subSerie = '';

      if (substr($sistModulo_permiso,0,2) == 'rw')
      { if ($prodProgramacion_cdgimpresion != '' AND $prodProgramacion_cdgjuego != '' AND $prodProgramacion_cdgmaquina != '')
        { $progLoteSelect = $link->query("
            SELECT proglote.idlote,
                   proglote.tarima,
                   proglote.lote,
                   proglote.longitud,
                   pdtosustrato.anchura,
                   pdtoimpresion.alto,
                   pdtoimpresion.ancho,
                   proglote.peso,
                   proglote.cdglote,
		   pdtoimpresion.cdgdiseno
              FROM progbloque,
                   proglote,
                   pdtoimpresion,
                   pdtosustrato
            WHERE (progbloque.cdgbloque = proglote.cdgbloque AND
                   proglote.sttlote = '7') AND
                  (progbloque.cdgsustrato = pdtoimpresion.cdgsustrato AND
                   pdtoimpresion.cdgimpresion = '".$prodProgramacion_cdgimpresion."') AND
                  (pdtosustrato.cdgsustrato = progbloque.cdgsustrato)");

          if ($progLoteSelect->num_rows > 0)
          { while ($regProgLote = $progLoteSelect->fetch_object())
            { if (isset($_REQUEST['chck'.$regProgLote->cdglote]))
              { $prodProgramacion_cdglote = $regProgLote->cdglote;
                $prodProgramacion_longitud = $regProgLote->longitud;
                $prodProgramacion_altura = $regProgLote->altura;
                $prodProgramacion_alpaso = $regProgLote->alpaso;
                $prodProgramacion_peso = $regProgLote->peso;
                $prodProgramacion_anchura = $regProgLote->anchura;

                $prodProgramacion_programado = (($prodProgramacion_longitud/$prodProgramacion_altura)*$prodProgramacion_alpaso);

                $progLoteSelectMax = $link->query("
                  SELECT MAX(noop) AS noopmax,
                         COUNT(noop) AS noopnum
                    FROM prodlote
                   WHERE serie = 'SS".$subSerie."'");

                $regProdLoteMax = $progLoteSelectMax->fetch_object();
                $prodProgramacion_noopmax = (int)$regProdLoteMax->noopmax;
                $prodProgramacion_noopnum = (int)$regProdLoteMax->noopnum;
		

		//Código de ECA
		$comets='';
		if($regProgLote->cdgdiseno=="075")
		{
		$getIdentify =$link->query("SELECT count(cdgproducto)+1 as 'loop' FROM prodlote where cdgproducto='".$prodProgramacion_cdgimpresion."' AND fchmovimiento>'2019-01-23'");
		$rowis = $getIdentify->fetch_object();
		$comets = (int)$rowis->loop;
		}
                // Insertar lote en la programación
                
                if ($prodProgramacion_noopmax == $prodProgramacion_noopnum)
                { $prodProgramacion_noop = $prodProgramacion_noopmax+1;

                  $link->query("
                    INSERT INTO prodlote
                      (cdglote, serie, noop, cdgproducto, longitud, amplitud, peso, fchprograma, programado, fchmovimiento,observacion)
                    VALUES
                      ('".$prodProgramacion_cdglote."', 'SS".$subSerie."', '".$prodProgramacion_noop."','".$prodProgramacion_cdgimpresion."','".$prodProgramacion_longitud."
', '".$prodProgramacion_anchura."', '".$prodProgramacion_peso."', '".$prodProgramacion_fchprograma."', '".$prodProgramacion_programado."', NOW(),'".$comets."')");
                } else
                { for ($noop = 1; $noop < $prodProgramacion_noopmax; $noop++)
                  { $prodProgramacion_noop = $noop;
                      
                    $link->query("
                      INSERT INTO prodlote
                        (cdglote, serie, noop, cdgproducto, longitud, amplitud, peso, fchprograma, programado, fchmovimiento,observacion)
                      VALUES
                        ('".$prodProgramacion_cdglote."', 'SS".$subSerie."', '".$prodProgramacion_noop."','".$prodProgramacion_cdgimpresion."','".$prodProgramacion_longitud."', '".$prodProgramacion_anchura."', '".$prodProgramacion_peso."', '".$prodProgramacion_fchprograma."', '".$prodProgramacion_programado."', NOW(),'".$comets."')");

                    if ($link->affected_rows > 0)
                    { $noop = $prodProgramacion_noopmax; }
		    if($regProgLote->cdgdiseno=="075")
			{
				$comets++;
			}
                  }
                }

                // Registrar la operación
                $link->query("
                  INSERT INTO prodloteope
                    (cdglote, cdgoperacion, cdgempleado, cdgmaquina, cdgjuego, longitud, longitudfin, fchoperacion, fchmovimiento)
                  VALUES
                    ('".$prodProgramacion_cdglote."', '10001', '".$_SESSION['cdgusuario']."', '".$prodProgramacion_cdgmaquina."', '".$prodProgramacion_cdgjuego."', '".$prodProgramacion_longitud."', '".$prodProgramacion_longitud."', NOW(), NOW())");

                if ($link->affected_rows > 0)
                { $link->query("
                    UPDATE proglote
                       SET sttlote = '8'
                     WHERE cdglote = '".$prodProgramacion_cdglote."'");

                  if ($link->affected_rows > 0)
                  { $msg_alert .= 'El NoOp '.$prodProgramacion_noop.' fue generado. \n'; } 
                  else
                  { $link->query("
                      DELETE FROM prodloteope
                       WHERE cdglote = '".$prodProgramacion_cdglote."' AND
                             cdgoperacion = '10001'");
                    
                    $link->query("
                      DELETE * FROM prodlote
                       WHERE cdglote = '".$prodProgramacion_cdglote."'"); 
                      
                    $msg_alert .= 'El NoOp '.$prodProgramacion_noop.' NO fue generado (Lote). \n'; }
                } else
                { $msg_alert .= 'El NoOp '.$prodProgramacion_noop.' NO fue generado. \n'; }
                // Final del registro de la operación
              }
            }
          } 
        } else
        { $msg_alert = 'No es posible iniciar el proceso, ya que faltanb parámetros por definir. (Impresión, Juego de cilindros o máquina)'; }
      } else
      { $msg_alert = $msg_norewrite; }
    } //*/
    //////////////////////////////////////////////////////////////

    if (substr($sistModulo_permiso,0,1) == 'r')
    { // Filtro de diseños activos
      $pdtoDisenoSelect = $link->query("
        SELECT * FROM pdtodiseno
         WHERE sttdiseno = '1'
      ORDER BY iddiseno");

      if ($pdtoDisenoSelect->num_rows > 0)
      { $item = 0;

        while ($regPdtoDiseno = $pdtoDisenoSelect->fetch_object())
        { $pdtoDisenos_diseno[$regPdtoDiseno->cdgdiseno] = $regPdtoDiseno->diseno;

          // Filtro de impresiones activas por diseño
          $pdtoImpresionSelect = $link->query("
            SELECT pdtoimpresion.cdgdiseno,
                   pdtoimpresion.idimpresion,
                   pdtoimpresion.impresion,
                   pdtoimpresion.cdgimpresion
              FROM pdtoimpresion
             WHERE pdtoimpresion.cdgdiseno = '".$regPdtoDiseno->cdgdiseno."' AND 
                   pdtoimpresion.sttimpresion = '1'
          ORDER BY pdtoimpresion.idimpresion");

          if ($pdtoImpresionSelect->num_rows > 0)
          { while ($regPdtoImpresion = $pdtoImpresionSelect->fetch_object())
            { $item++;

              $pdtoImpresion_cdgdiseno[$item] = $regPdtoImpresion->cdgdiseno;
              $pdtoImpresion_idimpresion[$item] = $regPdtoImpresion->idimpresion;
              $pdtoImpresion_impresion[$item] = $regPdtoImpresion->impresion;
              $pdtoImpresion_cdgimpresion[$item] = $regPdtoImpresion->cdgimpresion;

              $pdtoImpresions_impresion[$regPdtoImpresion->cdgimpresion] = $regPdtoImpresion->impresion; 

              // Filtro de impresiones activas por diseño
              $pdtoJuegoSelect = $link->query("
                SELECT pdtojuego.idjuego,
                       pdtojuego.proveedor,
                       pdtojuego.cdgjuego
                  FROM pdtoimpresion,
                       pdtojuego
                WHERE (pdtoimpresion.cdgimpresion = pdtojuego.cdgimpresion AND
                       pdtojuego.sttjuego = '1') AND
                      (pdtoimpresion.cdgimpresion = '".$regPdtoImpresion->cdgimpresion."')");

              if ($pdtoJuegoSelect->num_rows > 0)
              { while ($regPdtoJuego = $pdtoJuegoSelect->fetch_object())
                { $pdtoJuegos_idjuego[$regPdtoJuego->cdgjuego] = $regPdtoJuego->idjuego;
                  $pdtoJuegos_proveedor[$regPdtoJuego->cdgjuego] = $regPdtoJuego->proveedor; }
              }
              // Final del filtro de impresiones por diseño   
            }
          }
          // Final del filtro de impresiones por diseño          
        }

        $nImpresiones = $item; }
      // Final del filtro de diseños  

      // Filtro de maquina por subproceso
      $prodMaquina = $link->query("
        SELECT * FROM prodmaquina 
         WHERE cdgsubproceso = '001' AND
               sttmaquina = '1'");
      
      if ($prodMaquina->num_rows > 0)
      { $item = 0;

        while ($regProdMaquina = $prodMaquina->fetch_object())
        { $item++;

          $prodMaquina_idmaquina[$item] = $regProdMaquina->idmaquina;
          $prodMaquina_maquina[$item] = $regProdMaquina->maquina;
          $prodMaquina_cdgmaquina[$item] = $regProdMaquina->cdgmaquina; 

          $prodMaquina_maquinas[$regProdMaquina->cdgmaquina] = $regProdMaquina->maquina; }

        $nMaquinas = $item; }
      // Final del filtro de máquina por subproceso

      // Filtro de programaciones
      $programacionSelect = $link->query("
        SELECT prodlote.cdgproducto,
               pdtodiseno.cdgdiseno, 
         COUNT(prodlote.cdglote) AS nlotes,
               prodloteope.cdgmaquina,
               prodloteope.cdgjuego,
         ((SUM(prodloteope.longitudfin)*pdtojuego.alpaso)/pdtojuego.altura) AS programado
          FROM prodlote,
               prodloteope, 
               pdtodiseno,
               pdtoimpresion,
               pdtojuego
        WHERE (prodlote.cdglote = prodloteope.cdglote AND 
               prodloteope.cdgoperacion = '10001') AND 
              (prodlote.cdgproducto = pdtoimpresion.cdgimpresion AND 
               pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno) AND
              (prodloteope.cdgjuego = pdtojuego.cdgjuego AND
               pdtojuego.cdgimpresion = pdtoimpresion.cdgimpresion) AND
               prodlote.fchprograma = '".$prodProgramacion_fchprograma."'
      GROUP BY prodlote.cdgproducto,
               prodloteope.cdgjuego,
               prodloteope.cdgmaquina");

        if ($programacionSelect->num_rows > 0)
        { $item = 0;

          while ($regProgramacion = $programacionSelect->fetch_object())
          { $item++;

            $programacion_cdgproducto[$item] = $regProgramacion->cdgproducto;
            $programacion_cdgdiseno[$item] = $regProgramacion->cdgdiseno;
            $programacion_nlotes[$item] = $regProgramacion->nlotes;
            $programacion_cdgmaquina[$item] = $regProgramacion->cdgmaquina;        
            $programacion_cdgjuego[$item] = $regProgramacion->cdgjuego;
            $programacion_programado[$item] = $regProgramacion->programado; 

            $programaSelect = $link->query("
              SELECT CONCAT(prodlote.serie,'.',prodlote.noop) AS noop,
                     proglote.tarima,
                     proglote.lote,
                     proglote.idlote,
                     proglote.longitud,
                     pdtosustrato.anchura,
                     pdtojuego.altura,
                     pdtojuego.alpaso,
                     proglote.peso,
                     prodlote.cdglote,
                     prodlote.sttlote
                FROM progbloque,
                     proglote,
                     prodlote,
                     prodloteope,
                     pdtojuego,
                     pdtosustrato
              WHERE (progbloque.cdgbloque = proglote.cdgbloque AND
                     proglote.cdglote = prodlote.cdglote AND
                     prodlote.cdglote = prodloteope.cdglote) AND
                    (progbloque.cdgsustrato = pdtosustrato.cdgsustrato) AND
                    (prodlote.fchprograma = '".$prodProgramacion_fchprograma."' AND
                     prodlote.cdgproducto = '".$regProgramacion->cdgproducto."') AND
                    (prodloteope.cdgoperacion = '10001' AND 
                     prodloteope.cdgjuego = '".$regProgramacion->cdgjuego."' AND
                     pdtojuego.cdgjuego = prodloteope.cdgjuego AND
                     prodloteope.cdgmaquina = '".$regProgramacion->cdgmaquina."')");

            if ($programaSelect->num_rows > o)
            { $subItem = 0;

              while ($regPrograma = $programaSelect->fetch_object())
              { $subItem++;

                $programacion_noop[$item][$subItem] = $regPrograma->noop;
                $programacion_tarima[$item][$subItem] = $regPrograma->tarima;
                $programacion_lote[$item][$subItem] = $regPrograma->lote;
                $programacion_idlote[$item][$subItem] = $regPrograma->idlote;
                $programacion_longitud[$item][$subItem] = $regPrograma->longitud;
                $programacion_anchura[$item][$subItem] = $regPrograma->anchura;
                $programacion_altura[$item][$subItem] = $regPrograma->altura;
                $programacion_alpaso[$item][$subItem] = $regPrograma->alpaso;
                $programacion_peso[$item][$subItem] = $regPrograma->peso;
                $programacion_cdglote[$item][$subItem] = $regPrograma->cdglote;
                $programacion_sttlote[$item][$subItem] = $regPrograma->sttlote; }

              $nLotesProg[$item] = $subItem;
            }
          }

          $nProgramas = $item; }
      // Final del filtro de programaciones

      // Filtro de sustrato disponible para programar
      $progLoteSelect = $link->query("
        SELECT proglote.idlote,
               proglote.tarima,
               proglote.lote,
               proglote.longitud,
               pdtosustrato.anchura,
               pdtoimpresion.alto,
               pdtoimpresion.ancho,
               proglote.peso,
               proglote.cdglote
          FROM progbloque,
               proglote,
               pdtoimpresion,
               pdtosustrato
        WHERE (progbloque.cdgbloque = proglote.cdgbloque AND
               proglote.sttlote = '7') AND
              (progbloque.cdgsustrato = pdtoimpresion.cdgsustrato AND
               pdtoimpresion.cdgimpresion = '".$prodProgramacion_cdgimpresion."') AND
              (pdtosustrato.cdgsustrato = progbloque.cdgsustrato)");
      
      if ($progLoteSelect->num_rows > 0)
      { $item = 0;

        while ($regProgLote = $progLoteSelect->fetch_object())
        { $item++;

          $progLotes_idlote[$item] = $regProgLote->idlote;
          $progLotes_tarima[$item] = $regProgLote->tarima;
          $progLotes_lote[$item] = $regProgLote->lote;
          $progLotes_longitud[$item] = $regProgLote->longitud;
          $progLotes_anchura[$item] = $regProgLote->anchura;
          $progLotes_alto[$item] = $regProgLote->alto;
          $progLotes_ancho[$item] = $regProgLote->ancho;
          $progLotes_peso[$item] = $regProgLote->peso;
          $progLotes_cdglote[$item] = $regProgLote->cdglote;
	//echo $progLotes_cdglote[$item];
 }
        
        $nLotes = $item; }
      // Final del filtro de sustrato disponible para programar        
    } else
    { $msg_alert = $msg_noread; }
  
    echo '
      <form id="formProdProgramacion" name="formProdProgramacion" method="POST" action="prodProgramacion.php">
        <div class="bloque">
          <article class="subbloque">
            <label class="modulo_nombre">Programación de la Impresión</label>
          </article>         

          <section class="subbloque">
            <article>
              <label><a href="../sm_producto/pdtoImpresion.php?cdgimpresion='.$prodProgramacion_cdgimpresion.'">Producto</a></label><br/>
              <select id="slctCdgProducto" name="slctCdgProducto" onchange="document.formProdProgramacion.submit()">
                <option value="">-</option>';

    for ($item = 1; $item <= $nImpresiones; $item++)
    { echo '
                  <option value="'.$pdtoImpresion_cdgimpresion[$item].'"';

      if ($prodProgramacion_cdgimpresion == $pdtoImpresion_cdgimpresion[$item])
      { $prodProgramacion_cdgdiseno = $pdtoDiseno_cdgdiseno[$item];

      echo ' selected="selected"'; }

    echo '>'.$pdtoDisenos_diseno[$pdtoImpresion_cdgdiseno[$item]].' | '.$pdtoImpresion_impresion[$item].'</option>'; }

    echo '
              </select>
            </article>';

    if ($nJuegos > 0)
    { echo '
            <article>
              <label><a href="../sm_producto/pdtoJuego.php?cdgjuego='.$prodProgramacion_cdgjuego.'">Juego de cilindros</a></label><br/>
              <select id="slctCdgJuego" name="slctCdgJuego" onchange="document.formProdProgramacion.submit()">
                <option value="">-</option>';

      for ($item = 1; $item <= $nJuegos; $item++)
      { echo '
                <option value="'.$pdtoJuego_cdgjuego[$item].'"';

        if ($prodProgramacion_cdgjuego == $pdtoJuego_cdgjuego[$item])
        { echo ' selected="selected"'; }

         echo '>['.$pdtoJuego_idjuego[$item].'] '.$pdtoJuego_proveedor[$item].'</option>'; }

        echo '
              </select>
            </article>'; }

    echo '
            <article>
              <label>Máquina</label><br/>
              <select id="slctCdgMaquina" name="slctCdgMaquina">
                <option value="">-</option>';

    for ($item = 1; $item <= $nMaquinas; $item++)
    { echo '
                <option value="'.$prodMaquina_cdgmaquina[$item].'"';

      if ($prodProgramacion_cdgmaquina == $prodMaquina_cdgmaquina[$item])
      { echo ' selected="selected"'; }

      echo '>['.$prodMaquina_idmaquina[$item].'] '.$prodMaquina_maquina[$item].'</option>'; }

    echo '
              </select>
            </article>

            <article>
              <label>Fecha programación</label><br/>
                <input type="date" id="dateFchPrograma" name="dateFchPrograma" value="'.$prodProgramacion_fchprograma.'" title="Fecha de asignacion" onchange="document.formProdProgramacion.submit()" style="width:140px;" required/>
            </article>

            <article><br>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>
          </section>
        </div>';

    if ($nProgramas > 0)
    { echo '
        <div class="bloque">
          <article class="subbloque">
            <label class="modulo_listado">Programación del día</article>
          </article>';

      for ($item=1; $item<=$nProgramas; $item++)
      { echo '
          <section class="listado">
            <article align="right">
              <label><a href="pdf/pro-fr05.php?cdgimpresion='.$programacion_cdgproducto[$item].'&cdgmaquina='.$programacion_cdgmaquina[$item].'&cdgjuego='.$programacion_cdgjuego[$item].'&fchprograma='.$prodProgramacion_fchprograma.'&cdgfchprograma='.$prodProgramacion_cdgfchprograma.'&millares='.number_format($programacion_programado[$item] ,3,'.','').'" target="_blank">PRO-<b>FR05</b></a></label><br/>
              <label><a href="pdf/pro-fr06.php?cdgimpresion='.$programacion_cdgproducto[$item].'&cdgmaquina='.$programacion_cdgmaquina[$item].'&cdgjuego='.$programacion_cdgjuego[$item].'&fchprograma='.$prodProgramacion_fchprograma.'&cdgfchprograma='.$prodProgramacion_cdgfchprograma.'&millares='.number_format($programacion_programado[$item] ,3,'.','').'" target="_blank">PRO-<b>FR06</b></a></label><br/>
              <label><a href="pdf/pro-fr07.php?cdgimpresion='.$programacion_cdgproducto[$item].'&cdgmaquina='.$programacion_cdgmaquina[$item].'&cdgjuego='.$programacion_cdgjuego[$item].'&fchprograma='.$prodProgramacion_fchprograma.'&cdgfchprograma='.$prodProgramacion_cdgfchprograma.'&millares='.number_format($programacion_programado[$item] ,3,'.','').'" target="_blank">PRO-<b>FR07</b></a></label><br/>
              <label><a href="pdf/pro-fr08.php?cdgimpresion='.$programacion_cdgproducto[$item].'&cdgmaquina='.$programacion_cdgmaquina[$item].'&cdgjuego='.$programacion_cdgjuego[$item].'&fchprograma='.$prodProgramacion_fchprograma.'&cdgfchprograma='.$prodProgramacion_cdgfchprograma.'&millares='.number_format($programacion_programado[$item] ,3,'.','').'" target="_blank">PRO-<b>FR08</b></a></label><br>
              <label><a href="../sm_inspeccion/pdf/cc-fr01.php?cdgimpresion='.$programacion_cdgproducto[$item].'&cdgmaquina='.$programacion_cdgmaquina[$item].'&cdgjuego='.$programacion_cdgjuego[$item].'&fchprograma='.$prodProgramacion_fchprograma.'&cdgfchprograma='.$prodProgramacion_cdgfchprograma.'&millares='.number_format($programacion_programado[$item] ,3,'.','').'" target="_blank">CC-<b>FR01</b></a></label>
            </article>
            
            <article style="vertical-align:top">
              <a href="pdf/prodLotesBC.php?cdgproducto='.$programacion_cdgproducto[$item].'&cdgmaquina='.$programacion_cdgmaquina[$item].'&cdgjuego='.$programacion_cdgjuego[$item].'&fchprograma='.$prodProgramacion_fchprograma.'" target="_blank">'.$_barcode.'</a>
            </article>

            <article>
              <article align="right">
                <label>Diseño</label><br/>
                <label>Producto</label><br/>
                <label>Juego de cilindros</label><br/>
                <label>Máquina</label><br/>
                <label>Lotes</label>
              </article>

              <article>
                <label><i>'.$pdtoDisenos_diseno[$programacion_cdgdiseno[$item]].'</i></label><br/>
                <label><b>'.$pdtoImpresions_impresion[$programacion_cdgproducto[$item]].'</b></label><br/>
                <label><b>'.$pdtoJuegos_idjuego[$programacion_cdgjuego[$item]].'</b> | '.$pdtoJuegos_proveedor[$programacion_cdgjuego[$item]].'</label><br>
                <label><i>'.$prodMaquina_maquinas[$programacion_cdgmaquina[$item]].'</i></label><br/>
                <label><b>'.$programacion_nlotes[$item].'</b> | <b>'.number_format($programacion_programado[$item],3).'</b> <i>millares</i></label>
              </article>
            </article>
          </section>';

        for ($subItem=1; $subItem<=$nLotesProg[$item]; $subItem++)
        { echo '
          <section class="listado">
            <article style="vertical-align:top">';

          if ($programacion_sttlote[$item][$subItem] != '1')
          { if ($programacion_sttlote[$item][$subItem] == 'A')
            { echo '
              <a href="prodProgramacion.php?cdglote='.$programacion_cdglote[$item][$subItem].'&proceso=update">'.$_power_blue.'</a>'; }
          
            if ($programacion_sttlote[$item][$subItem] == 'D')
            { echo '
              <a href="prodProgramacion.php?cdglote='.$programacion_cdglote[$item][$subItem].'&proceso=delete">'.$_recycle_bin.'</a>
              <a href="prodProgramacion.php?cdglote='.$programacion_cdglote[$item][$subItem].'&proceso=update">'.$_power_black.'</a>'; }

            if ($programacion_sttlote[$item][$subItem] == '9')
            { echo '
              <a href="#">'.$_power_black.'</a>';}
          } else
          { echo '
              <a href="#">'.$_gear.'</a>'; }

          echo '
            </article>

            <article>
              <label class="textNombre">NoOP <b>'.$programacion_noop[$item][$subItem].'</b></label><br/>
              <label>No. Lote <b>'.$programacion_tarima[$item][$subItem].'</b> | <b>'.$programacion_idlote[$item][$subItem].'</b></label><br/>
              <label>Referencia <i>'.$programacion_lote[$item][$subItem].'</i></label><br/>
              <label><b>'.number_format($programacion_anchura[$item][$subItem]).'</b> mm</label>
            </article>

            <article>
              <article style="text-align:right">
                <label>Longitud</label><br/>
                <label>Peso</label><br/>
                <label><b></i>'.number_format((($programacion_longitud[$item][$subItem]*$programacion_alpaso[$item][$subItem])/$programacion_altura[$item][$subItem]),3).'</i></b></label>
              </article>

              <article>
                <label><b>'.number_format($programacion_longitud[$item][$subItem],2).'</b> m</label><br/>
                <label><b>'.number_format($programacion_peso[$item][$subItem],3).'</b> kg</label><br/>
                <label>Millares aprox.</label>
              </article>
            </article>
          </section>'; }
      }

      echo '
        </div>'; }

    if ($nLotes > 0)
    { echo '
        <div class="bloque">
          <article class="subbloque">
            <label class="modulo_listado">Lotes compatibles</label>
          </article>
          <label><b>'.$nLotes.'</b> Encontrado(s)</label>';

      for ($item=1; $item<=$nLotes; $item++)
      { echo '
          <section class="listado">
            <article style="vertical-align:top">
              <input type="checkbox" id="chck'.$progLotes_cdglote[$item].'" name="chck'.$progLotes_cdglote[$item].'" />
            </article>

            <article style="vertical-align:top">              
              <label>No. Lote <b>'.$progLotes_tarima[$item].'</b> | <b>'.$progLotes_idlote[$item].'</b></label><br/>
              <label>Referencia <i>'.$progLotes_lote[$item].'</i></label><br/>
              <label><b>'.number_format($progLotes_anchura[$item]).'</b> mm</label>
            </article>

            <article style="vertical-align:top">
              <article style="text-align:right">
                <label>Longitud</label><br/>
                <label>Peso</label><br/>
                <label><b></i>'.number_format((($progLotes_longitud[$item]*($progLotes_anchura[$item]/$progLotes_ancho[$item]))/$progLotes_alto[$item]),3).'</i></b></label>
              </article>

              <article>
                <label><b>'.number_format($progLotes_longitud[$item],2).'</b> m</label><br/>
                <label><b>'.number_format($progLotes_peso[$item],3).'</b> kg</label><br/>
                <label>Millares aprox.</label>
              </article>
            </article>
          </section>'; }
    
      echo '
        </div>
      </form>'; }

    if ($msg_alert != '')
    { echo '
      <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
      <div><h1>Módulo no encontrado o bloqueado.</h1></div>'; }
  ?>

    </div>
  </body> 
</html>
