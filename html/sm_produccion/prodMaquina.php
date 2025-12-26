<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Máquinas</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
      <section>
        <!--<a href="ayuda.php"><img id="imagen_ayuda" src="../img_sistema/help_blue.png" border="0"/></a>-->
        <Label><h1>Producción</h1></label>
      </section><?php

  include '../datos/mysql.php';
  $link = conectar();  

  m3nu_produccion();

  $sistModulo_cdgmodulo = '61000';
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
        <form id="login" action="prodMaquina.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 
     
      exit; }

    // captura de valores 
    $prodMaquina_cdgsubproceso = $_POST['slctCdgSubProceso'];
    $prodMaquina_idmaquina = trim($_POST['textIdMaquina']);
    $prodMaquina_maquina = trim($_POST['textMaquina']);
    $prodMaquina_cdgmaquinas = $_POST['textCdgMaquina'];

    // Operaciones con registros existentes  
    if ($_GET['cdgmaquina'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $prodMaquinaSelect = $link->query("
          SELECT * FROM prodmaquina
           WHERE cdgmaquina = '".$_GET['cdgmaquina']."'");
        
        if ($prodMaquinaSelect->num_rows > 0)
        { $regProdMaquina = $prodMaquinaSelect->fetch_object();

          $prodMaquina_cdgsubproceso = $regProdMaquina->cdgsubproceso;
          $prodMaquina_idmaquina = $regProdMaquina->idmaquina;
          $prodMaquina_maquina = $regProdMaquina->maquina;      
          $prodMaquina_cdgmaquina = $regProdMaquina->cdgmaquina;
          $prodMaquina_cdgmaquinas = $regProdMaquina->cdgmaquina;
          $prodMaquina_sttmaquina = $regProdMaquina->sttmaquina;

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($prodMaquina_sttmaquina == '1')
              { $prodMaquina_newsttmaquina = '0'; }
            
              if ($prodMaquina_sttmaquina == '0')
              { $prodMaquina_newsttmaquina = '1'; }
              
              if ($prodMaquina_newsttmaquina != '')
              { 
                $link->query("
                  UPDATE prodmaquina
                     SET sttmaquina = '".$prodMaquina_newsttmaquina."'
                   WHERE cdgmaquina = '".$prodMaquina_cdgmaquina."'"); 

                if ($link->affected_rows > 0)
                { $msg_alert = 'La Maquina fue actualizada en su status.'; }
                else
                { $msg_alert = 'La Maquina NO fue actualizada (status).'; }
              }
            } else
            { $msg_alert = $msg_norewrite; }

            $prodMaquina_cdgmaquinas = ''; }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $prodLoteOpeSelect = $link->query("
                SELECT * FROM prodloteope
                 WHERE cdgmaquina = '".$prodMaquina_cdgmaquina."'");
                
              if ($prodLoteOpeSelect->num_rows > 0)
              { $msg_alert = 'La Maquina cuenta con registros ligados en produccion, no pudo ser eliminada.'; }
              else
              { $link->query("
                  DELETE FROM prodmaquina
                   WHERE cdgmaquina = '".$prodMaquina_cdgmaquina."' AND 
                         sttmaquina = '0'");
                  
                if ($link->affected_rows > 0)
                { $msg_alert = 'La Maquina fue eliminada con exito.'; }
                else
                { $msg_alert = 'La Maquina NO fue eliminada.'; }
              }
            } else
            { $msg_alert = $msg_nodelete; }

            $prodMaquina_cdgmaquinas = ''; }
        }
      } else
      { $msg_alert = $msg_noread; }
    } 
  ///////////////////////////////////////////////////////////////

  // Operaciones con el boton SUBMIT
    if ($_POST['bttnSalvar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if (strlen($prodMaquina_idmaquina) > 0)
        { if ($prodMaquina_cdgmaquinas != '')
          { $link->query("
              UPDATE prodmaquina
                 SET idmaquina = '".$prodMaquina_idmaquina."',
                     maquina = '".$prodMaquina_maquina."'                
               WHERE cdgmaquina = '".$prodMaquina_cdgmaquinas."' AND 
                     sttmaquina = '1'");
                
            if ($link->affected_rows > 0) 
            { $msg_alert = 'El sustrato fue actualizado éxito.'; }

            $prodMaquina_cdgmaquinas = '';   
          } else
          { $prodMaquinaSelect = $link->query("
              SELECT * FROM prodmaquina
               WHERE idmaquina = '".$prodMaquina_idmaquina."'");
              
            if ($prodMaquinaSelect->num_rows > 0)
            { $regProdMaquina = $prodMaquinaSelect->fetch_object();              
              
              $link->query("
                UPDATE prodmaquina
                   SET maquina = '".$prodMaquina_maquina."',                       
                 WHERE cdgmaquina = '".$regProdMaquina->cdgmaquina."' AND 
                       sttmaquina = '1'");
                
              if ($link->affected_rows > 0) 
              { $msg_alert = 'La Maquina fue actualizada con exito.'; }
              else
              { $msg_alert = 'La Maquina NO fue actualizado.'; }      
            } 
            else
            { for ($cdgmaquina = 1; $cdgmaquina <= 1000; $cdgmaquina++)
              { $prodMaquina_cdgmaquina = $prodMaquina_cdgsubproceso.str_pad($cdgmaquina,3,'0',STR_PAD_LEFT);
                
                if ($cdgmaquina > 999)
                { $msg_alert = 'La Maquina NO fue insertado, se ha alcanzado el tope de maquina por proceso.'; }
                else
                { $link->query("
                    INSERT INTO prodmaquina
                      (cdgsubproceso, idmaquina, maquina, cdgmaquina)
                    VALUES
                      ('".$prodMaquina_cdgsubproceso."', '".$prodMaquina_idmaquina."', '".$prodMaquina_maquina."', '".$prodMaquina_cdgmaquina."')");
                  
                  if ($link->affected_rows > 0) 
                  { $msg_alert = 'La Maquina fue insertada con exito.'; 
                    $cdgmaquina = 1000; }      
                }
              }
            }
          }
        }
      } else
      { $msg_alert = $msg_norewrite; }

      $prodMaquina_cdgmaquinas = '';
    }
  ///////////////////////////////////////////////////////////////

  // Filtrado de procesos
    if (substr($sistModulo_permiso,0,1) == 'r')    
    { $sistSubProceso = $link->query("
        SELECT * FROM sistsubproceso
         WHERE sttsubproceso = '1'
      ORDER BY idsubproceso");
      
      $item = 0;
      while ($regSubProceso = $sistSubProceso->fetch_object()) 
      { $item++;

        $sistSubProceso_idsubproceso[$item] = $regSubProceso->idsubproceso;
        $sistSubProceso_subproceso[$item] = $regSubProceso->subproceso;
        $sistSubProceso_cdgsubproceso[$item] = $regSubProceso->cdgsubproceso;

        $sistSubProcesos_subproceso[$regSubProceso->cdgsubproceso] = $regSubProceso->subproceso; }

      $nSubProcesos = $sistSubProceso->num_rows;

      // Filtrado de registros 
      if ($_POST['chckVerTodo'])
      { $vertodo = 'checked'; 
        
        $prodMaquinaSelect = $link->query("
          SELECT * FROM prodmaquina
           WHERE sttmaquina != '9'
        ORDER BY sttmaquina DESC,
                 idmaquina"); }
      else
      { $prodMaquinaSelect = $link->query("
          SELECT * FROM prodmaquina
           WHERE sttmaquina = '1'
        ORDER BY idmaquina"); }
      
      if ($prodMaquinaSelect->num_rows > 0)
      { $item = 0;
        while ($regProdMaquina = $prodMaquinaSelect->fetch_object())
        { $item++;

          $prodMaquinas_cdgsubproceso[$item] = $regProdMaquina->cdgsubproceso;
          $prodMaquinas_idmaquina[$item] = $regProdMaquina->idmaquina;
          $prodMaquinas_maquina[$item] = $regProdMaquina->maquina;
          $prodMaquinas_cdgmaquina[$item] = $regProdMaquina->cdgmaquina;
          $prodMaquinas_sttmaquina[$item] = $regProdMaquina->sttmaquina; }

        $nMaquinas = $prodMaquinaSelect->num_rows; }
    }

    echo '
      <div class="bloque">
        <form id="formProdMaquina" name="formProdMaquina" method="POST" action="prodMaquina.php">
          <article class="subbloque">
            <label class="modulo_nombre">Máquinas</label>
          </article>
          <input type="checkbox" id="chckVerTodo" name="chckVerTodo" onclick="document.formProdMaquina.submit()" '.$vertodo.'><label>ver todo</label>

          <section class="subbloque">
            <article>
              <label>Código</label><br/>
              <input type="text" id="textIdMaquina" name="textIdMaquina" value="'.$prodMaquina_idmaquina.'" placeholder="Nombre corto" required/>
              <input type="hidden" id="textCdgMaquina" name="textCdgMaquina" value="'.$prodMaquina_cdgmaquinas.'" />
            </article>

            <article>
              <label>Descripción</label><br/>
              <input type="text" id="textMaquina" name="textMaquina" value="'.$prodMaquina_maquina.'" placeholder="Nombre completo" required/>
            </article>

            <article>
              <label>Subproceso</label><br/>
              <select id="slctCdgSubProceso" name="slctCdgSubProceso" onchange="document.formProdMaquina.submit()">';
    
    for ($item = 1; $item <= $nSubProcesos; $item++) 
    { echo '
                <option value="'.$sistSubProceso_cdgsubproceso[$item].'"';
            
      if ($prodMaquina_cdgsubproceso == $sistSubProceso_cdgsubproceso[$item]) { echo ' selected="selected"'; }
      echo '>'.$sistSubProceso_subproceso[$item].'</option>'; }
    
    echo '
              </select>
            </article>

            <article><br/>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>
          </section>
        </form>
      </div>';

    if ($nMaquinas > 0)
    { echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Catálogo de máquinas</label>
        </article>
        <label><b>'.$nMaquinas.'</b> Encontrada(s)</label>';


      for ($item=1; $item<=$nMaquinas; $item++)
      { echo '
        <section class="listado">
          <article style="vertical-align:top">';

        if ((int)$prodMaquinas_sttmaquina[$item] == '1')
        { echo '
            <a href="prodMaquina.php?cdgmaquina='.$prodMaquinas_cdgmaquina[$item].'">'.$_search.'</a>
            <a href="prodMaquina.php?cdgmaquina='.$prodMaquinas_cdgmaquina[$item].'&proceso=update">'.$_power_blue.'</a>'; 
        } else
        { echo '
            <a href="prodMaquina.php?cdgmaquina='.$prodMaquinas_cdgmaquina[$item].'&proceso=delete">'.$_recycle_bin.'</a>
            <a href="prodMaquina.php?cdgmaquina='.$prodMaquinas_cdgmaquina[$item].'&proceso=update">'.$_power_black.'</a>'; }

          echo '
          </article>

          <article>
            <article style="text-align:right">
              <label>Código</label><br/>
              <label>Descripción</label>
            </article>

            <article>              
              <label><strong>'.$prodMaquinas_idmaquina[$item].'</strong></label><br/>
              <label><em>'.$prodMaquinas_maquina[$item].'</em></label>
            </article>
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
