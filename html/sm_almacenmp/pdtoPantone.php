<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Catálogo PANTONE</title>
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
        <form id="login" action="pdtoPantone.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 
     
      exit; }

    $pdtoPantone_idpantone = trim($_POST['textIdPantone']);
    $pdtoPantone_pantone = $_POST['textPantone'];
    $pdtoPantone_HTML = $_POST['textHTML'];
    $pdtoPantone_cdgpantones = $_POST['textCdgPantone'];

    if ($_GET['cdgpantone'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $pdtoPantoneSelect = $link->query("
          SELECT * FROM pdtopantone
           WHERE cdgpantone = '".$_GET['cdgpantone']."'");
        
        if ($pdtoPantoneSelect->num_rows > 0)
        { $regPdtoPantone = $pdtoPantoneSelect->fetch_object();

          $pdtoPantone_idpantone = $regPdtoPantone->idpantone;
          $pdtoPantone_pantone = $regPdtoPantone->pantone;
          $pdtoPantone_HTML = $regPdtoPantone->HTML;
          $pdtoPantone_cdgpantone = $regPdtoPantone->cdgpantone;
          $pdtoPantone_cdgpantones = $regPdtoPantone->cdgpantone;
          $pdtoPantone_sttpantone = $regPdtoPantone->sttpantone;
          
          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($pdtoPantone_sttpantone == '1')
              { $pdtoPantone_newsttpantone = '0'; }
            
              if ($pdtoPantone_sttpantone == '0')
              { $pdtoPantone_newsttpantone = '1'; }
              
              if ($pdtoPantone_newsttpantone != '')
              { $link->query("
                  UPDATE pdtopantone
                     SET sttpantone = '".$pdtoPantone_newsttpantone."' 
                   WHERE cdgpantone = '".$pdtoPantone_cdgpantone."'");
                
                if ($link->affected_rows > 0)
                { $msg_alert = 'El pantone fue actualizado en su status.'; }
                else
                { $msg_alert = 'Naranjas '.$pdtoPantone_cdgpantone; }
              }
            } else
            { $msg_alert = $msg_norewrite; }

            $pdtoPantone_cdgpantones = ''; }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $progLoteSelect = $link->query("
                SELECT * FROM pdtoimpresiontnt
                 WHERE cdgtinta = '".$pdtoPantone_cdgpantone."'");
                
              if ($progLoteSelect->num_rows > 0)
              { $msg_alert = 'El pantone cuenta con información vinculada, no debe ser eliminado.'; }
              else
              { $link->query("
                  DELETE FROM pdtopantone
                   WHERE cdgpantone = '".$pdtoPantone_cdgpantone."' AND 
                         sttpantone = '0'");
                  
                if ($link->affected_rows > 0)
                { $msg_alert = 'El pantone fue eliminado con éxito.'; }
                else
                { $msg_alert = 'El pantone no fue eliminado.'; }
              }
            } else
            { $msg_alert = $msg_nodelete; }

            $pdtoPantone_cdgpantones = ''; }
        }
      } else
      { $msg_alert = $msg_noread; }
    } 

    if ($_POST['bttnSalvar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if (strlen($pdtoPantone_idpantone) > 0)
        { if ($pdtoPantone_cdgpantones != '')
          { $link->query("
              UPDATE pdtopantone
                 SET idpantone = '".$pdtoPantone_idpantone."',
                     pantone = '".$pdtoPantone_pantone."',
                     HTML = '".$pdtoPantone_HTML."'                  
               WHERE cdgpantone = '".$pdtoPantone_cdgpantones."' AND 
                     sttpantone = '1'");
                
            if ($link->affected_rows > 0) 
            { $msg_alert = 'El pantone fue actualizado éxito.'; }

            $pdtoPantone_cdgpantones = '';
          } else
          { $pdtoPantoneSelect = $link->query("
              SELECT * FROM pdtopantone
               WHERE idpantone = '".$pdtoPantone_idpantone."'"); 

            if ($pdtoPantoneSelect->num_rows > 0)
            { $regPdtoPantone = $pdtoPantoneSelect->fetch_object();
              
              $link->query("
                UPDATE pdtopantone
                   SET pantone = '".$pdtoPantone_pantone."',
                       HTML = '".$pdtoPantone_HTML."'
                 WHERE cdgpantone = '".$regPdtoPantone->cdgpantone."' AND 
                       sttpantone = '1'");
                
              if ($link->affected_rows > 0) 
              { $msg_alert = 'El pantone fue actualizado éxito.'; }
            } else
            { $pdtoPantoneSelect = $link->query("
              SELECT COUNT(cdgpantone) AS npantones, 
                     MAX(cdgpantone) AS lpantone 
                FROM pdtopantone");

              if ($pdtoPantoneSelect->num_rows > 0)
              { $regPdtoPantone = $pdtoPantoneSelect->fetch_object();

                if (str_pad($regPdtoPantone->npantones,4,'0',STR_PAD_LEFT) == $regPdtoPantone->lpantone)
                { $cdgpantone = ($regPdtoPantone->npantones+1);
                  $pdtoPantone_cdgpantone = 'CP'.str_pad($cdgpantone,4,'0',STR_PAD_LEFT);
                  
                  $link->query("
                    INSERT INTO pdtopantone
                      (idpantone, pantone, HTML, cdgpantone)
                    VALUES
                      ('".$pdtoPantone_idpantone."', '".$pdtoPantone_pantone."', '".$pdtoPantone_HTML."', '".$pdtoPantone_cdgpantone."')");
                  
                  if ($link->affected_rows > 0) 
                  { $msg_alert = 'El pantone fue insertado con éxito.'; }
                } else
                { for ($cdgpantone = 1; $cdgpantone <= 9999; $cdgpantone++)
                  { $pdtoPantone_cdgpantone = 'CP'.str_pad($cdgpantone,4,'0',STR_PAD_LEFT);
                    
                    $link->query("
                      INSERT INTO pdtopantone
                        (idpantone, pantone, HTML, cdgpantone)
                      VALUES
                        ('".$pdtoPantone_idpantone."', '".$pdtoPantone_pantone."', '".$pdtoPantone_HTML."', '".$pdtoPantone_cdgpantone."')");
                    
                    if ($link->affected_rows > 0) 
                    { $msg_alert = 'El pantone fue insertado con éxito.'; 
                      break; }
                  }
                }
                $msg_alert = 'El pantone fue insertado con éxito.';
              } 
            }
          }
        }
      } else
      { $msg_alert = $msg_norewrite; }

      $pdtoPantone_cdgpantones = '';
    }

    if (substr($sistModulo_permiso,0,1) == 'r')    
    { // Filtro de PANTONE
      if ($_POST['chckVerTodo'])
      { $vertodo = 'checked'; 

        $pdtoPantoneSelect = $link->query("
          SELECT pdtopantone.idpantone,
                 pdtopantone.pantone,
                 pdtopantone.HTML,
                 pdtopantone.cdgpantone,
                 pdtopantone.sttpantone
            FROM pdtopantone
        ORDER BY pdtopantone.pagina,
                 pdtopantone.HTML"); 
      } else
      { $pdtoPantoneSelect = $link->query("
          SELECT pdtopantone.idpantone,
                 pdtopantone.pantone,
                 pdtopantone.HTML,           
                 pdtopantone.cdgpantone,
                 pdtopantone.sttpantone
            FROM pdtopantone
           WHERE pdtopantone.sttpantone >= '1'
        ORDER BY pdtopantone.pagina,
                 pdtopantone.HTML"); }

      if ($pdtoPantoneSelect->num_rows > 0)
      { $item = 0;

        while ($regPdtoPantone = $pdtoPantoneSelect->fetch_object())
        { $item++;

          $pdtoPantones_idpantone[$item] = $regPdtoPantone->idpantone;
          $pdtoPantones_pantone[$item] = $regPdtoPantone->pantone;
          $pdtoPantones_HTML[$item] = $regPdtoPantone->HTML;
          $pdtoPantones_cdgpantone[$item] = $regPdtoPantone->cdgpantone;
          $pdtoPantones_sttpantone[$item] = $regPdtoPantone->sttpantone; }

        $npantones = $item; }
      // Final del filtro de PANTONE
    } else
    { $msg_alert = $msg_noread; }

    echo '

    <div>
      <form id="formPdtoPantone" name="formPdtoPantone" method="POST" action="pdtoPantone.php">
        <article class="subbloque">
          <label class="modulo_nombre">Catálogo PANTONE</label>
        </article>
        <input type="checkbox" id="chckVerTodo" name="chckVerTodo" onclick="document.formPdtoPantone.submit()" '.$vertodo.'><label>ver todo</label>
        <!--<a href="ayuda.php#pantones">'.$_help_blue.'</a>-->

        <section class="subbloque">
          <article>
            <label>Código</label><br/>
            <input type="text" id="textIdPantone" name="textIdPantone" value="'.$pdtoPantone_idpantone.'" placeholder="Nombre corto" required/>
            <input type="hidden" id="textCdgPantone" name="textCdgPantone" value="'.$pdtoPantone_cdgpantones.'" />
          </article>

          <article>
            <label>Descripción</label><br/>
            <input type="text" id="textPantone" name="textPantone" value="'.$pdtoPantone_pantone.'" placeholder="Nombre completo" required/>
          </article>

          <article>
            <label>HTML</label><br/>
            <input type="text" id="textHTML" name="textHTML" value="'.$pdtoPantone_HTML.'" placeholder="Hexadecimal" required/>
          </article>

          <article><br/>
            <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
          </article>
        </section>
      </form>
    </div>';

    if ($npantones > 0)
    { echo '<br/>

      <article class="subbloque">
        <label class="modulo_listado">Catálogo de pantones</label>
      </article>
      <label><b>'.$npantones.'</b> Encontrado(s)</label>';

      for ($item=1; $item<=$npantones; $item++)
      { echo '
       <section class="listado">
        <article style="vertical-align:top">';

        if ((int)$pdtoPantones_sttpantone[$item] > 0)
        { echo '
          <a href="pdtoPantone.php?cdgpantone='.$pdtoPantones_cdgpantone[$item].'">'.$_search.'</a>
          <a href="pdtoPantone.php?cdgpantone='.$pdtoPantones_cdgpantone[$item].'&proceso=update">'.$_power_blue.'</a>'; 
        } else
        { echo '
          <a href="pdtoPantone.php?cdgpantone='.$pdtoPantones_cdgpantone[$item].'&proceso=delete">'.$_recycle_bin.'</a>              
          <a href="pdtoPantone.php?cdgpantone='.$pdtoPantones_cdgpantone[$item].'&proceso=update">'.$_power_black.'</a>'; }

        echo '
        </article>

        <article style="vertical-align:top">
          <label><strong>'.$pdtoPantones_idpantone[$item].'</strong></label><br />
          <label style="border-radius: 4px;background-color:#'.$pdtoPantones_HTML[$item].';color:#'.$pdtoPantones_HTML[$item].'">##</label>
          <label>&nbsp;'.$pdtoPantones_pantone[$item].'</label>
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
