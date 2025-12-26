<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Unidades de medida, materia prima</title>
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
        <form id="login" action="progUniMed.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 
     
      exit; }

    $progUniMed_idunimed = trim($_POST['textIdUniMed']);
    $progUniMed_unimed = $_POST['textUniMed'];    

    if ($_GET['cdgunimed'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $progUniMedSelect = $link->query("
          SELECT * FROM progunimed
           WHERE cdgunimed = '".$_GET['cdgunimed']."'");
        
        if ($progUniMedSelect->num_rows > 0)
        { $regProgunimed = $progUniMedSelect->fetch_object();

          $progUniMed_idunimed = $regProgunimed->idunimed;
          $progUniMed_unimed = $regProgunimed->unimed;
          $progUniMed_cdgunimed = $regProgunimed->cdgunimed;
          
          /*
          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($progUniMed_sttunimed == '1')
              { $progUniMed_newsttunimed = '0'; }
            
              if ($progUniMed_sttunimed == '0')
              { $progUniMed_newsttunimed = '1'; }
              
              if ($progUniMed_newsttunimed != '')
              { $link->query("
                  UPDATE progunimed
                     SET sttunimed = '".$progUniMed_newsttunimed."' 
                   WHERE cdgunimed = '".$progUniMed_cdgunimed."'");
                
                if ($link->affected_rows > 0)
                { $msg_alert = 'El unimed fue actualizado en su status.'; }
              }
            } else
            { $msg_alert = $msg_norewrite; }
          } //*/

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $progLoteSelect = $link->query("
                SELECT * FROM progelemento
                 WHERE cdgunimed = '".$progUniMed_cdgunimed."'");
                
              if ($progLoteSelect->num_rows > 0)
              { $msg_alert = 'El unidad de medida cuenta con registros ligados, no debe ser eliminada.'; }
              else
              { $link->query("
                  DELETE FROM progunimed
                   WHERE cdgunimed = '".$progUniMed_cdgunimed."'");
                  
                if ($link->affected_rows > 0)
                { $msg_alert = 'El unimed fue eliminado con éxito.'; }
                else
                { $msg_alert = 'El unimed no fue eliminado.'; }
              }
            } else
            { $msg_alert = $msg_nodelete; }
          }
        }
      } else
      { $msg_alert = $msg_noread; }
    } 

    if ($_POST['bttnSalvar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if (strlen($progUniMed_idunimed) > 0)
        { $progUniMedSelect = $link->query("
            SELECT * FROM progunimed
             WHERE idunimed = '".$progUniMed_idunimed."'"); 

          if ($progUniMedSelect->num_rows > 0)
          { $regProgunimed = $progUniMedSelect->fetch_object();
            
            $link->query("
              UPDATE progunimed
                 SET unimed = '".$progUniMed_unimed."'
               WHERE cdgunimed = '".$regProgunimed->cdgunimed."'");
              
            if ($link->affected_rows > 0) 
            { $msg_alert = 'El unimed fue actualizado éxito.'; }
          } else
          { $progUniMedSelect = $link->query("
            SELECT COUNT(cdgunimed) AS nunimeds, 
                   MAX(cdgunimed) AS lunimed 
              FROM progunimed");

            if ($progUniMedSelect->num_rows > 0)
            { $regProgunimed = $progUniMedSelect->fetch_object();

              if (str_pad($regProgunimed->nunimeds,2,'0',STR_PAD_LEFT) == $regProgunimed->lunimed)
              { $cdgunimed = ($regProgunimed->nunimeds+1);
                $progUniMed_cdgunimed = str_pad($cdgunimed,2,'0',STR_PAD_LEFT);
                
                $link->query("
                  INSERT INTO progunimed
                    (idunimed, unimed, cdgunimed)
                  VALUES
                    ('".$progUniMed_idunimed."', '".$progUniMed_unimed."', '".$progUniMed_cdgunimed."')");
                
                if ($link->affected_rows > 0) 
                { $msg_alert = 'El unimed fue insertado con éxito.'; }
              } else
              { for ($cdgunimed = 1; $cdgunimed <= 99; $cdgunimed++)
                { $progUniMed_cdgunimed = str_pad($cdgunimed,2,'0',STR_PAD_LEFT);
                  
                  $link->query("
                    INSERT INTO progunimed
                      (idunimed, unimed, cdgunimed)
                    VALUES
                      ('".$progUniMed_idunimed."', '".$progUniMed_unimed."', '".$progUniMed_cdgunimed."')");
                  
                  if ($link->affected_rows > 0) 
                  { $msg_alert = 'El unimed fue insertado con éxito.'; 
                    break; }
                }
              }
              $msg_alert = 'El unimed fue insertado con éxito.';
            } 
          }
        }
      } else
      { $msg_alert = $msg_norewrite; }
    }

    if (substr($sistModulo_permiso,0,1) == 'r')    
    { // Filtro de unimeds de materia prima
      $progUniMedSelect = $link->query("
        SELECT progunimed.idunimed,
               progunimed.unimed,
               progunimed.cdgunimed
          FROM progunimed
      GROUP BY progunimed.cdgunimed
      ORDER BY progunimed.idunimed"); 

      if ($progUniMedSelect->num_rows > 0)
      { $item = 0;

        while ($regProgunimed = $progUniMedSelect->fetch_object())
        { $item++;

          $progUniMeds_idunimed[$item] = $regProgunimed->idunimed;
          $progUniMeds_unimed[$item] = $regProgunimed->unimed;
          $progUniMeds_cdgunimed[$item] = $regProgunimed->cdgunimed; }

        $nUniMeds = $item; }
      // Final del fintro de unimeds de materia prima    
    } else
    { $msg_alert = $msg_noread; }

    echo '

    <div>
      <form id="formProgUniMed" name="formProgUniMed" method="POST" action="progUniMed.php">
        <article class="subbloque">
          <label class="modulo_nombre">Unidades de medida</label>
        </article>        
        <a href="ayuda.php#unimeds">'.$_help_blue.'</a>

        <section class="subbloque">
          <article>
            <label>Identificador</label><br/>
            <input type="text" id="textIdUniMed" name="textIdUniMed" value="'.$progUniMed_idunimed.'" placeholder="Nombre corto" required/>
          </article>

          <article>
            <label>Nombre</label><br/>
            <input type="text" id="textUniMed" name="textUniMed" value="'.$progUniMed_unimed.'" placeholder="Nombre completo" required/>
          </article>

          <article><br/>
            <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
          </article>
        </section>
      </form>
    </div>';

    if ($nUniMeds > 0)
    { echo '<br/>

      <article class="subbloque">
        <label class="modulo_listado">Catálogo de unimeds</label>
      </article>
      <label><b>'.$nUniMeds.'</b> Encontrado(s)</label>';

      for ($item=1; $item<=$nUniMeds; $item++)
      { echo '
       <section class="listado">
        <article style="vertical-align:top">
          <a href="progUniMed.php?cdgunimed='.$progUniMeds_cdgunimed[$item].'">'.$_search.'</a>
          <a href="progUniMed.php?cdgunimed='.$progUniMeds_cdgunimed[$item].'&proceso=delete">'.$_recycle_bin.'</a>
        </article>

        <article style="vertical-align:top">
          <label>Unidad de medida <b>'.$progUniMeds_idunimed[$item].'</b></label><br/>
          <label>Nombre <b>'.$progUniMeds_unimed[$item].'</b></label>
        </article>
      </section>'; }
    }

    if ($msg_alert != '')
    { echo '
      <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
      <div><h1>Módulo no encontrado o unimedado.</h1></div>'; }
  ?>

    </div>
  </body> 
</html>