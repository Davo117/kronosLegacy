<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Sustratos</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
<?php

  include '../datos/mysql.php';
  $link = conectar();  

  m3nu_almacenmp();

  $sistModulo_cdgmodulo = '50010';
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
        <form id="login" action="pdtoSustrato.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 
     
      exit; }

    $pdtoSuatrato_idsustrato = trim($_POST['textIdSustrato']);
    $pdtoSuatrato_sustrato = $_POST['textSustrato'];
    $pdtoSuatrato_rendimiento = $_POST['textRendimiento'];
    $pdtoSuatrato_anchura = $_POST['textAnchura'];
    $pdtoSuatrato_cdgsustratos = $_POST['textCdgSustrato'];

    if ($_GET['cdgsustrato'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $pdtoSustratoSelect = $link->query("
          SELECT * FROM pdtosustrato
           WHERE cdgsustrato = '".$_GET['cdgsustrato']."'");
        
        if ($pdtoSustratoSelect->num_rows > 0)
        { $regPdtoSustrato = $pdtoSustratoSelect->fetch_object();

          $pdtoSuatrato_idsustrato = $regPdtoSustrato->idsustrato;
          $pdtoSuatrato_sustrato = $regPdtoSustrato->sustrato;
          $pdtoSuatrato_rendimiento = $regPdtoSustrato->rendimiento;
          $pdtoSuatrato_anchura = $regPdtoSustrato->anchura;
          $pdtoSuatrato_cdgsustrato = $regPdtoSustrato->cdgsustrato;
          $pdtoSustrato_cdgsustratos = $regPdtoSustrato->cdgsustrato;
          $pdtoSuatrato_sttsustrato = $regPdtoSustrato->sttsustrato;
          
          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($pdtoSuatrato_sttsustrato == '1')
              { $pdtoSuatrato_newsttsustrato = '0'; }
            
              if ($pdtoSuatrato_sttsustrato == '0')
              { $pdtoSuatrato_newsttsustrato = '1'; }
              
              if ($pdtoSuatrato_newsttsustrato != '')
              { $link->query("
                  UPDATE pdtosustrato
                     SET sttsustrato = '".$pdtoSuatrato_newsttsustrato."' 
                   WHERE cdgsustrato = '".$pdtoSuatrato_cdgsustrato."'");
                
                if ($link->affected_rows > 0)
                { $msg_alert = 'El sustrato fue actualizado en su status.'; }
                else
                { $msg_alert = 'Naranjas '.$pdtoSuatrato_cdgsustrato; }
              }
            } else
            { $msg_alert = $msg_norewrite; }

            $pdtoSustrato_cdgsustratos = ''; }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $progLoteSelect = $link->query("
                SELECT * FROM pdtoimpresiontnt
                 WHERE cdgtinta = '".$pdtoSuatrato_cdgsustrato."'");
                
              if ($progLoteSelect->num_rows > 0)
              { $msg_alert = 'El sustrato cuenta con información vinculada, no debe ser eliminado.'; }
              else
              { $link->query("
                  DELETE FROM pdtosustrato
                   WHERE cdgsustrato = '".$pdtoSuatrato_cdgsustrato."' AND 
                         sttsustrato = '0'");
                  
                if ($link->affected_rows > 0)
                { $msg_alert = 'El sustrato fue eliminado con éxito.'; }
                else
                { $msg_alert = 'El sustrato no fue eliminado.'; }
              }
            } else
            { $msg_alert = $msg_nodelete; }

            $pdtoSustrato_cdgsustratos = ''; }
        }
      } else
      { $msg_alert = $msg_noread; }
    } 

    if ($_POST['bttnSalvar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if (strlen($pdtoSuatrato_idsustrato) > 0)
        { if ($pdtoSustrato_cdgsustratos != '')
          { $link->query("
              UPDATE pdtosustrato
                 SET idsustrato = '".$pdtoSuatrato_idsustrato."',
                     sustrato = '".$pdtoSuatrato_sustrato."',
                     rendimiento = '".$pdtoSuatrato_rendimiento."'                  
               WHERE cdgsustrato = '".$pdtoSustrato_cdgsustratos."' AND 
                     sttsustrato = '1'");
                
            if ($link->affected_rows > 0) 
            { $msg_alert = 'El sustrato fue actualizado éxito.'; }

            $pdtoSustrato_cdgsustratos = '';
          } else
          { $pdtoSustratoSelect = $link->query("
              SELECT * FROM pdtosustrato
               WHERE idsustrato = '".$pdtoSuatrato_idsustrato."'"); 

            if ($pdtoSustratoSelect->num_rows > 0)
            { $regPdtoSustrato = $pdtoSustratoSelect->fetch_object();
              
              $link->query("
                UPDATE pdtosustrato
                   SET sustrato = '".$pdtoSuatrato_sustrato."',
                       rendimiento = '".$pdtoSuatrato_rendimiento."',
                       anchura = '".$pdtoSuatrato_anchura."'
                 WHERE cdgsustrato = '".$regPdtoSustrato->cdgsustrato."' AND 
                       sttsustrato = '1'");
                
              if ($link->affected_rows > 0) 
              { $msg_alert = 'El sustrato fue actualizado éxito.'; }
            } else
            { $pdtoSustratoSelect = $link->query("
              SELECT COUNT(cdgsustrato) AS nsustratos, 
                     MAX(cdgsustrato) AS lsustrato 
                FROM pdtosustrato");

              if ($pdtoSustratoSelect->num_rows > 0)
              { $regPdtoSustrato = $pdtoSustratoSelect->fetch_object();

                if (str_pad($regPdtoSustrato->nsustratos,4,'0',STR_PAD_LEFT) == $regPdtoSustrato->lsustrato)
                { $cdgsustrato = ($regPdtoSustrato->nsustratos+1);
                  $pdtoSuatrato_cdgsustrato = '000'.str_pad($cdgsustrato,5,'0',STR_PAD_LEFT);
                  
                  $link->query("
                    INSERT INTO pdtosustrato
                      (idsustrato, sustrato, rendimiento, anchura, cdgsustrato)
                    VALUES
                      ('".$pdtoSuatrato_idsustrato."', '".$pdtoSuatrato_sustrato."', '".$pdtoSuatrato_rendimiento."', '".$pdtoSuatrato_anchura."', '".$pdtoSuatrato_cdgsustrato."')");
                  
                  if ($link->affected_rows > 0) 
                  { $msg_alert = 'El sustrato fue insertado con éxito.'; }
                } else
                { for ($cdgsustrato = 1; $cdgsustrato <= 9999; $cdgsustrato++)
                  { $pdtoSuatrato_cdgsustrato = '000'.str_pad($cdgsustrato,5,'0',STR_PAD_LEFT);
                    
                    $link->query("
                      INSERT INTO pdtosustrato
                        (idsustrato, sustrato, rendimiento, anchura, cdgsustrato)
                      VALUES
                        ('".$pdtoSuatrato_idsustrato."', '".$pdtoSuatrato_sustrato."', '".$pdtoSuatrato_rendimiento."', '".$pdtoSuatrato_anchura."', '".$pdtoSuatrato_cdgsustrato."')");
                    
                    if ($link->affected_rows > 0) 
                    { $msg_alert = 'El sustrato fue insertado con éxito.'; 
                      break; }
                  }
                }
                $msg_alert = 'El sustrato fue insertado con éxito.';
              } 
            }
          }
        }
      } else
      { $msg_alert = $msg_norewrite; }

      $pdtoSustrato_cdgsustratos = '';
    }

    if (substr($sistModulo_permiso,0,1) == 'r')    
    { // Filtro de sustratos
      if ($_POST['chckVerTodo'])
      { $vertodo = 'checked'; 

        $pdtoSustratoSelect = $link->query("
          SELECT pdtosustrato.idsustrato,
                 pdtosustrato.sustrato,
                 pdtosustrato.rendimiento,
                 pdtosustrato.anchura,
                 pdtosustrato.cdgsustrato,
                 pdtosustrato.sttsustrato
            FROM pdtosustrato
        ORDER BY pdtosustrato.idsustrato");
      } else
      { $pdtoSustratoSelect = $link->query("
          SELECT pdtosustrato.idsustrato,
                 pdtosustrato.sustrato,
                 pdtosustrato.rendimiento,
                 pdtosustrato.anchura,
                 pdtosustrato.cdgsustrato,
                 pdtosustrato.sttsustrato
            FROM pdtosustrato
           WHERE pdtosustrato.sttsustrato >= '1'
        ORDER BY pdtosustrato.idsustrato"); }

      if ($pdtoSustratoSelect->num_rows > 0)
      { $item = 0;

        while ($regPdtoSustrato = $pdtoSustratoSelect->fetch_object())
        { $item++;

          $pdtoSustratos_idsustrato[$item] = $regPdtoSustrato->idsustrato;
          $pdtoSustratos_sustrato[$item] = $regPdtoSustrato->sustrato;
          $pdtoSustratos_rendimiento[$item] = $regPdtoSustrato->rendimiento;
          $pdtoSustratos_anchura[$item] = $regPdtoSustrato->anchura;
          $pdtoSustratos_cdgsustrato[$item] = $regPdtoSustrato->cdgsustrato;
          $pdtoSustratos_sttsustrato[$item] = $regPdtoSustrato->sttsustrato; }

        $nSustratos = $item; }
      // Final del filtro de PANTONE
    } else
    { $msg_alert = $msg_noread; }

    echo '

    <div>
      <form id="formPdtoSustrato" name="formPdtoSustrato" method="POST" action="pdtoSustrato.php">
        <article class="subbloque">
          <label class="modulo_nombre">Catálogo de sustratos</label>
        </article>
        <input type="checkbox" id="chckVerTodo" name="chckVerTodo" onclick="document.formPdtoSustrato.submit()" '.$vertodo.'><label>ver todo</label>
        <a href="ayuda.php#Sustratos">'.$_help_blue.'</a>

        <section class="subbloque">
          <article>
            <label>Código</label><br/>
            <input type="text" id="textIdSustrato" name="textIdSustrato" value="'.$pdtoSuatrato_idsustrato.'" placeholder="Nombre corto" required/>
            <input type="hidden" id="textCdgSustrato" name="textCdgSustrato" value="'.$pdtoSustrato_cdgsustratos.'" />
          </article>

          <article>
            <label>Descripción</label><br/>
            <input type="text" id="textSustrato" name="textSustrato" value="'.$pdtoSuatrato_sustrato.'" placeholder="Nombre completo" required/>
          </article>

          <article>
            <label>Rendimiento (Mts2*Kg)</label><br/>
            <input type="text" class="input_numero" id="textRendimiento" name="textRendimiento" value="'.$pdtoSuatrato_rendimiento.'" placeholder="metros cuadrados por kilo" required/>
          </article>

          <article>
            <label>Anchura en mm</label><br/>
            <input type="text" class="input_numero" id="textAnchura" name="textAnchura" value="'.$pdtoSuatrato_anchura.'" placeholder="milímetros" required/>
          </article>

          <article><br/>
            <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
          </article>
        </section>
      </form>
    </div>';

    if ($nSustratos > 0)
    { echo '<br/>

      <article class="subbloque">
        <label class="modulo_listado">Catálogo de sustratos</label>
      </article>
      <label><b>'.$nSustratos.'</b> Encontrado(s)</label>';

      for ($item=1; $item<=$nSustratos; $item++)
      { echo '
       <section class="listado">
        <article style="vertical-align:top">';

        if ((int)$pdtoSustratos_sttsustrato[$item] > 0)
        { echo '
          <a href="pdtoSustrato.php?cdgsustrato='.$pdtoSustratos_cdgsustrato[$item].'">'.$_search.'</a>
          <a href="pdtoSustrato.php?cdgsustrato='.$pdtoSustratos_cdgsustrato[$item].'&proceso=update">'.$_power_blue.'</a>'; 
        } else
        { echo '
          <a href="pdtoSustrato.php?cdgsustrato='.$pdtoSustratos_cdgsustrato[$item].'&proceso=delete">'.$_recycle_bin.'</a>              
          <a href="pdtoSustrato.php?cdgsustrato='.$pdtoSustratos_cdgsustrato[$item].'&proceso=update">'.$_power_black.'</a>'; }

        echo '
        </article>

        <article style="vertical-align:top">
          <label><strong>'.$pdtoSustratos_idsustrato[$item].'</strong></label><br />          
          <label>'.$pdtoSustratos_sustrato[$item].'</label>
        </article>
      </section>'; }
    }

    if ($msg_alert != '')
    { echo '
      <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
      <div><h1>Módulo no encontrado o pantoneado.</h1></div>'; }
  ?>

    </div>
  </body> 
</html>