<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Consumos, materia prima</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
<?php

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_producto();

  $sistModulo_cdgmodulo = '20020';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { if ($_GET['mode']=='logout') { cl0s3(); }

    if ($_POST['textusername'] AND $_POST['textpassword']) 
    { val1dat3($_POST['textusername'], $_POST['textpassword']); }

    if ($_SESSION['cdgusuario'])
    { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

      ma1n(); 
    } else
    { echo '
      <div id="loginform">
        <form id="login" action="pdtoConsumo.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 

      exit; } 
    
    $pdtoConsumo_cdgdiseno = $_POST['slctCdgDiseno'];
    $pdtoConsumo_cdgelemento = $_POST['slctCdgElemento'];
    $pdtoConsumo_cdgsubproceso = $_POST['slctCdgSubProceso'];
    $pdtoConsumo_consumo = $_POST['textConsumo'];
    
    if ($_GET['cdgdiseno']) { $pdtoConsumo_cdgdiseno = $_GET['cdgdiseno']; }
    
    if ($_GET['cdgconsumo'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $pdtoConsumoSelect = $link->query("
          SELECT * FROM pdtoconsumo
           WHERE cdgconsumo = '".$_GET['cdgconsumo']."'");
        
        if ($pdtoConsumoSelect->num_rows > 0)
        { $regPdtoConsumo = $pdtoConsumoSelect->fetch_object();

          $pdtoConsumo_cdgdiseno = $regPdtoConsumo->cdgdiseno;
          $pdtoConsumo_cdgsubproceso = $regPdtoConsumo->cdgsubproceso;
          $pdtoConsumo_cdgelemento = $regPdtoConsumo->cdgelemento;          
          $pdtoConsumo_consumo = $regPdtoConsumo->consumo;
          $pdtoConsumo_cdgconsumo = $regPdtoConsumo->cdgconsumo;
          $pdtoConsumo_sttconsumo = $regPdtoConsumo->sttconsumo;

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($pdtoConsumo_sttconsumo == '1')
              { $pdtoConsumo_newsttconsumo = '0'; }
            
              if ($pdtoConsumo_sttconsumo == '0')
              { $pdtoConsumo_newsttconsumo = '1'; }
              
              if ($pdtoConsumo_newsttconsumo != '')
              { $link->query("
                  UPDATE pdtoconsumo
                     SET sttconsumo = '".$pdtoConsumo_newsttconsumo."' 
                   WHERE cdgconsumo = '".$pdtoConsumo_cdgconsumo."'");
                  
                if ($link->affected_rows > 0)
                { $msg_alert = 'El consumo fue actualizada en su status.'; 
                } else
                { $msg_alert = 'El consumo NO fue actualizada (status).'; }
              }
            } else
            { $msg_alert = $msg_norewrite; }            
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $link->query("
                DELETE FROM pdtoconsumo
                 WHERE cdgconsumo = '".$pdtoConsumo_cdgconsumo."' AND 
                       sttconsumo = '0'");
                
              if ($link->affected_rows > 0)
              { $msg_alert = 'El consumo fue eliminada con éxito.'; 

                $link->query("
                  INSERT INTO krdxconsumo
                      (cdgdiseno, cdgsubproceso, cdgelemento, consumo, cdgconsumo, cdgusuario, operacion, fchmovimiento)
                    VALUES
                      ('".$pdtoConsumo_cdgdiseno."', '".$pdtoConsumo_cdgsubproceso."', '".$pdtoConsumo_cdgelemento."', '".$pdtoConsumo_consumo."', '".$pdtoConsumo_cdgconsumo."', '".$_SESSION['cdgusuario']."', 'DELETE', NOW())");
              } else
              { $msg_alert = 'El consumo NO fue eliminada.'; }
            } else
            { $msg_alert = $msg_nodelete; }              
          }
        }
      } else
      { $msg_alert = $msg_noread; }
    }

    // Botón salvar //     
    if ($_POST['bttnSalvar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if (strlen($pdtoConsumo_cdgdiseno) > 0 AND strlen($pdtoConsumo_cdgsubproceso) > 0 AND strlen($pdtoConsumo_cdgelemento) > 0)
        { // Buscar coincidencias
          $pdtoConsumoSelect = $link->query("
            SELECT * FROM pdtoconsumo
             WHERE cdgdiseno = '".$pdtoConsumo_cdgdiseno."' AND
                   cdgsubproceso = '".$pdtoConsumo_cdgsubproceso."' AND 
                   cdgelemento = '".$pdtoConsumo_cdgelemento."'");
            
          if ($pdtoConsumoSelect->num_rows > 0)
          { $regPdtoConsumo = $pdtoConsumoSelect->fetch_object();
            
            $link->query("
              UPDATE pdtoconsumo
                 SET consumo = '".$pdtoConsumo_consumo."'
               WHERE cdgconsumo = '".$regPdtoConsumo->cdgconsumo."' AND 
                     sttconsumo = '1'");
                
            if ($link->affected_rows > 0) 
            { $msg_alert = 'El consumo fue actualizada con exito.'; 

              $link->query("
                INSERT INTO krdxconsumo
                    (cdgdiseno, cdgsubproceso, cdgelemento, consumo, cdgconsumo, cdgusuario, operacion, fchmovimiento)
                  VALUES
                    ('".$pdtoConsumo_cdgdiseno."', '".$pdtoConsumo_cdgsubproceso."', '".$pdtoConsumo_cdgelemento."', '".$pdtoConsumo_consumo."', '".$regPdtoConsumo->cdgconsumo."', '".$_SESSION['cdgusuario']."', 'UPDATE', NOW())");
            } else
            { $msg_alert = 'El consumo NO fue actualizado.'; } 
          } else
          { for ($cdgconsumo = 1; $cdgconsumo <= 99; $cdgconsumo++)
            { $pdtoConsumo_cdgconsumo = $pdtoConsumo_cdgdiseno.'C'.str_pad($cdgconsumo,2,'0',STR_PAD_LEFT);
              
              if ($cdgconsumo > 99)
              { $msg_alert = 'El consumo NO fue insertado, se ha alcanzado el tope de elementos por diseno.'; }
              else
              { $link->query("
                  INSERT INTO pdtoconsumo
                    (cdgdiseno, cdgsubproceso, cdgelemento, consumo, cdgconsumo)
                  VALUES
                    ('".$pdtoConsumo_cdgdiseno."', '".$pdtoConsumo_cdgsubproceso."', '".$pdtoConsumo_cdgelemento."', '".$pdtoConsumo_consumo."', '".$pdtoConsumo_cdgconsumo."')");
                
                if ($link->affected_rows > 0) 
                { $msg_alert = 'La impresion fue insertada con exito.'; 

                  $link->query("
                    INSERT INTO krdxconsumo
                        (cdgdiseno, cdgsubproceso, cdgelemento, consumo, cdgconsumo, cdgusuario, operacion, fchmovimiento)
                      VALUES
                        ('".$pdtoConsumo_cdgdiseno."', '".$pdtoConsumo_cdgsubproceso."', '".$pdtoConsumo_cdgelemento."', '".$pdtoConsumo_consumo."', '".$pdtoConsumo_cdgconsumo."', '".$_SESSION['cdgusuario']."', 'INSERT', NOW())");
                  break; }
              }
            }
          }
        }
      } else
      { $msg_alert = $msg_norewrite; }
    }


    if (substr($sistModulo_permiso,0,1) == 'r')
    { // Filtro de registros (Diseños)
      $pdtoDisenoSelect = $link->query("
        SELECT * FROM pdtodiseno
         WHERE sttdiseno >= '1'
      ORDER BY sttdiseno DESC,
               diseno,
               iddiseno");

      if ($pdtoDisenoSelect->num_rows > 0)
      { $item = 0;
        
        while ($regPdtoDiseno = $pdtoDisenoSelect->fetch_object()) 
        { $item++;

          $pdtoDisenos_iddiseno[$item] = $regPdtoDiseno->iddiseno;
          $pdtoDisenos_diseno[$item] = $regPdtoDiseno->diseno;
          $pdtoDisenos_numTintas[$item] = $regPdtoDiseno->notintas;
          $pdtoDisenos_cdgdiseno[$item] = $regPdtoDiseno->cdgdiseno; }

        $nDisenos = $item; }
      // Final del filtro de registros  

      // Filtro de registros
      $progElementoSelect = $link->query("
        SELECT progelemento.idelemento,
               progelemento.elemento,
               progelemento.cdgelemento,
               progunimed.unimed
          FROM progelemento,
               progunimed
         WHERE progunimed.cdgunimed = progelemento.cdgunimed
      ORDER BY progelemento.idelemento,
               progelemento.elemento");
      
      if ($progElementoSelect->num_rows > 0)
      { $item = 0;

        while ($regProgElemento = $progElementoSelect->fetch_object()) 
        { $item++;

          $progElementos_idelemento[$item] = $regProgElemento->idelemento;
          $progElementos_elemento[$item] = $regProgElemento->elemento;
          $progElementos_unimed[$item] = $regProgElemento->unimed;
          $progElementos_cdgelemento[$item] = $regProgElemento->cdgelemento; 

          $progElemento_elemento[$regProgElemento->cdgelemento] = $regProgElemento->elemento;
          $progElemento_unimed[$regProgElemento->cdgelemento] = $regProgElemento->unimed; }

        $nElementos = $item; }
      // Final del filtro de registros

      // Filtro de registros
      $vntsEmpaque = $link->query("
        SELECT * FROM vntsempaque
         WHERE sttempaque = '1'
      ORDER BY idempaque");
            
      if ($vntsEmpaque->num_rows > 0)
      { $item = 0;

        while ($regVntsEmpaque = $vntsEmpaque->fetch_object()) 
        { $item++;

          $vntsEmpaques_empaque[$item] = $regVntsEmpaque->empaque;
          $vntsEmpaques_cdgempaque[$item] = '009'.$regVntsEmpaque->cdgempaque;          

          $sistSubProceso_subproceso['009'.$regVntsEmpaque->cdgempaque] = $regVntsEmpaque->empaque; }

        $nEmpaques = $item; }
      // Final del filtro de registros

      // Filtro de registros
      $sistSubProceso = $link->query("
        SELECT sistsubproceso.subproceso,
               sistsecproceso.cdgsubproceso               
          FROM sistsecproceso,
               sistsubproceso
         WHERE sistsecproceso.cdgproceso = '001' AND
               sistsubproceso.cdgsubproceso = sistsecproceso.cdgsubproceso
      ORDER BY sistsecproceso.secuencia");
            
      if ($sistSubProceso->num_rows > 0)
      { $item = 0;

        while ($regSistSubProceso = $sistSubProceso->fetch_object()) 
        { $item++;

          $sistSubProcesos_cdgsubproceso[$item] = $regSistSubProceso->cdgsubproceso;          
          $sistSubProcesos_subproceso[$item] = $regSistSubProceso->subproceso; 

          $sistSubProceso_subproceso[$regSistSubProceso->cdgsubproceso] = $regSistSubProceso->subproceso; }
        

        $nSubProcesos = $item; }
      // Final del filtro de registros 

    // Filtro de registros
    if ($_POST['chckVerTodo'])
    { $vertodo = 'checked'; 
      // Filtrado completo
      $pdtoConsumoSelect = $link->query("
        SELECT * FROM pdtoconsumo
         WHERE cdgdiseno = '".$pdtoConsumo_cdgdiseno."'
      ORDER BY sttconsumo DESC,
               cdgsubproceso"); }
    else
    { // Buscar coincidencias
      $pdtoConsumoSelect = $link->query("
        SELECT * FROM pdtoconsumo
         WHERE cdgdiseno = '".$pdtoConsumo_cdgdiseno."' AND
               sttconsumo = '1'
      ORDER BY cdgsubproceso"); }

    if ($pdtoConsumoSelect->num_rows > 0)
    { $item = 0;
      
      while ($regPdtoConsumo = $pdtoConsumoSelect->fetch_object())
      { $item++;

        $pdtoConsumos_cdgsubproceso[$item] = $regPdtoConsumo->cdgsubproceso;
        $pdtoConsumos_cdgelemento[$item] = $regPdtoConsumo->cdgelemento;
        $pdtoConsumos_consumo[$item] = $regPdtoConsumo->consumo;
        $pdtoConsumos_cdgconsumo[$item] = $regPdtoConsumo->cdgconsumo;
        $pdtoConsumos_sttconsumo[$item] = $regPdtoConsumo->sttconsumo; }

      $nConsumos = $item; }
      // Final del filtro de registros
    }

    echo '
      <form id="formPdtoConsumo" name="formPdtoConsumo" method="POST" action="pdtoConsumo.php">        
        <div class="bloque">
          <article class="subbloque">
            <label class="modulo_nombre">Consumos</label><br>
          </article>
          <input type="checkbox" id="chckVerTodo" name="chckVerTodo" onclick="document.formPdtoConsumo.submit()" '.$vertodo.'><label>ver todo</label>
          <a href="ayuda.php#Consumos">'.$_help_blue.'</a>

          <section class="subbloque">
            <article>
              <label><a href="../sm_producto/pdtoDiseno.php?cdgdiseno='.$pdtoConsumo_cdgdiseno.'">Diseño</a></label><br>
              <select id="slctCdgDiseno" name="slctCdgDiseno" onchange="document.formPdtoConsumo.submit()">
                <option>-</option>';
      
    for ($item = 1; $item <= $nDisenos; $item++) 
    { echo '
                <option value="'.$pdtoDisenos_cdgdiseno[$item].'"';
              
      if ($pdtoConsumo_cdgdiseno == $pdtoDisenos_cdgdiseno[$item]) { echo ' selected="selected"'; }
      echo '>'.$pdtoDisenos_diseno[$item].'</option>'; }

    echo '
              </select>
            </article>

            <article>
              <label>Subproceso</label><br/>
              <select id="slctCdgSubProceso" name="slctCdgSubProceso">
                <option>-</option>';
      
    for ($item = 1; $item <= $nSubProcesos; $item++) 
    { if ($sistSubProcesos_cdgsubproceso[$item] == '009')
      { echo '
                <optgroup label="'.$sistSubProcesos_subproceso[$item].'">';

        for ($subItem = 1; $subItem <= $nEmpaques; $subItem++)
        { echo '
                <option value="'.$vntsEmpaques_cdgempaque[$subItem].'"';

          if ($pdtoConsumo_cdgsubproceso == $vntsEmpaques_cdgempaque[$subItem]) { echo ' selected="selected"'; }
          echo '>'.$vntsEmpaques_empaque[$subItem].'</option>';
        } //*/
      } else
      { echo '
                <option value="'.$sistSubProcesos_cdgsubproceso[$item].'"';

        if ($pdtoConsumo_cdgsubproceso == $sistSubProcesos_cdgsubproceso[$item]) { echo ' selected="selected"'; }
        echo '>'.$sistSubProcesos_subproceso[$item].'</option>'; }
    }
      
    echo '
              </select>
            </article> 

            <article>
              <label><a href="../sm_almacenmp/progElemento.php?cdgelemento='.$pdtoConsumo_cdgelemento.'">Elemento</a></label><br/>
              <select id="slctCdgElemento" name="slctCdgElemento">
                <option>-</option>';
      
    for ($item = 1; $item <= $nElementos; $item++) 
    { echo '
                <option value="'.$progElementos_cdgelemento[$item].'"';
              
      if ($pdtoConsumo_cdgelemento == $progElementos_cdgelemento[$item]) { echo ' selected="selected"'; }
      echo '>'.$progElementos_elemento[$item].' ['.$progElementos_unimed[$item].']</option>'; }

    echo '
              </select>
            </article>            

            <article>
              <label>Consumo</label><br>
              <input type="text" id="textConsumo" name="textConsumo" value="'.$pdtoConsumo_consumo.'" required/>
            </article>

            <article><br>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>
          </section>
        </div>
      </form>';

    if ($pdtoConsumo_notintas > 0)
    { echo '
      <section class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Asignación de Pantone por capa</label>
        </article>'; }

  echo '      
      </section>
      ';

    if ($nConsumos > 0)
    { echo '
      <div class="bloque">      
        <article class="subbloque">
          <label class="modulo_listado">Catálogo de elementos de consumo por diseño</label>
        </article>
        <label><b>'.$nConsumos.'</b> Encontrado(s)</label>';
    
      for ($item=1; $item<=$nConsumos; $item++)
      { echo '
        <section class="listado">
          <article style="vertical-align:top">';

        if ((int)$pdtoConsumos_sttconsumo[$item] > 0)
        { echo '
            <a href="pdtoConsumo.php?cdgconsumo='.$pdtoConsumos_cdgconsumo[$item].'">'.$_search.'</a>
            <a href="pdtoConsumo.php?cdgconsumo='.$pdtoConsumos_cdgconsumo[$item].'&proceso=update">'.$_power_blue.'</a>'; 
        } else
        { echo '
            <a href="pdtoConsumo.php?cdgconsumo='.$pdtoConsumos_cdgconsumo[$item].'&proceso=delete">'.$_recycle_bin.'</a>
            <a href="pdtoConsumo.php?cdgconsumo='.$pdtoConsumos_cdgconsumo[$item].'&proceso=update">'.$_power_black.'</a>'; }

        echo '
          </article>

          <article>
            <label><b>'.$progElemento_elemento[$pdtoConsumos_cdgelemento[$item]].'</b></label><br/>
            <label>'.$sistSubProceso_subproceso[$pdtoConsumos_cdgsubproceso[$item]].'</label>
            <label><i>'.$pdtoConsumos_consumo[$item].'</i> '.$progElemento_unimed[$pdtoConsumos_cdgelemento[$item]].'</i></label>            
          </article>
        </section>'; }

      echo '
      </div>'; }

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
