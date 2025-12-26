<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Elementos, materia prima</title>
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
        <form id="login" action="progElemento.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 
     
      exit; }

    $progElemento_idelemento = trim($_POST['textIdElemento']);
    $progElemento_elemento = $_POST['textElemento'];
    $progElemento_cdgunimed = $_POST['slctCdgUniMed'];
    $progElemento_cdgelementos = $_POST['textCdgElemento'];

    if ($_GET['cdgelemento'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $progElementoSelect = $link->query("
          SELECT * FROM progelemento
           WHERE cdgelemento = '".$_GET['cdgelemento']."'");
        
        if ($progElementoSelect->num_rows > 0)
        { $regProgElemento = $progElementoSelect->fetch_object();

          $progElemento_idelemento = $regProgElemento->idelemento;
          $progElemento_elemento = $regProgElemento->elemento;
          $progElemento_cdgunimed = $regProgElemento->cdgunimed;
          $progElemento_cdgelemento = $regProgElemento->cdgelemento;
          $progElemento_cdgelementos = $regProgElemento->cdgelemento;
          $progElemento_sttelemento = $regProgElemento->sttelemento;
          
          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($progElemento_sttelemento == '1')
              { $progElemento_newsttelemento = '0'; }
            
              if ($progElemento_sttelemento == '0')
              { $progElemento_newsttelemento = '1'; }
              
              if ($progElemento_newsttelemento != '')
              { $link->query("
                  UPDATE progelemento
                     SET sttelemento = '".$progElemento_newsttelemento."' 
                   WHERE cdgelemento = '".$progElemento_cdgelemento."'");
                
                if ($link->affected_rows > 0)
                { $msg_alert = 'El elemento fue actualizado en su status.'; }
              }
            } else
            { $msg_alert = $msg_norewrite; }

            $progElemento_cdgelementos = ''; }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $progLoteSelect = $link->query("
                SELECT * FROM pdtoconsumo
                 WHERE cdgelemento = '".$progElemento_cdgelemento."'");
                
              if ($progLoteSelect->num_rows > 0)
              { $msg_alert = 'El elemento no esta vacío, no pudo ser eliminado.'; }
              else
              { $link->query("
                  DELETE FROM progelemento
                   WHERE cdgelemento = '".$progElemento_cdgelemento."' AND 
                         sttelemento = '0'");
                  
                if ($link->affected_rows > 0)
                { $msg_alert = 'El elemento fue eliminado con éxito.'; }
                else
                { $msg_alert = 'El elemento no fue eliminado.'; }
              }
            } else
            { $msg_alert = $msg_nodelete; }

            $progElemento_cdgelementos = ''; }
        }
      } else
      { $msg_alert = $msg_noread; }
    } 

    if ($_POST['bttnSalvar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if (strlen($progElemento_idelemento) > 0)
        { if ($progElemento_cdgelementos != '')
          { $link->query("
              UPDATE progelemento
                 SET idelemento = '".$progElemento_idelemento."',
                     elemento = '".$progElemento_elemento."',
                     cdgunimed = '".$progElemento_cdgunimed."'
               WHERE cdgelemento = '".$progElemento_cdgelementos."' AND 
                     sttelemento = '1'");
                
            if ($link->affected_rows > 0) 
            { $msg_alert = 'El elemento fue actualizado éxito.'; }

            $progElemento_cdgelementos = '';
          } else
          { $progElementoSelect = $link->query("
              SELECT * FROM progelemento
               WHERE idelemento = '".$progElemento_idelemento."'"); 

            if ($progElementoSelect->num_rows > 0)
            { $regProgElemento = $progElementoSelect->fetch_object();
              
              $link->query("
                UPDATE progelemento
                   SET elemento = '".$progElemento_elemento."',
                       cdgunimed = '".$progElemento_cdgunimed."'
                 WHERE cdgelemento = '".$regProgElemento->cdgelemento."' AND 
                       sttelemento = '1'");
                
              if ($link->affected_rows > 0) 
              { $msg_alert = 'El elemento fue actualizado éxito.'; }
            } else
            { $progElementoSelect = $link->query("
              SELECT COUNT(cdgelemento) AS nelementos, 
                     MAX(cdgelemento) AS lelemento 
                FROM progelemento");

              if ($progElementoSelect->num_rows > 0)
              { $regProgElemento = $progElementoSelect->fetch_object();

                if (str_pad($regProgElemento->nelementos,4,'0',STR_PAD_LEFT) == $regProgElemento->lelemento)
                { $cdgelemento = ($regProgElemento->nelementos+1);
                  $progElemento_cdgelemento = str_pad($cdgelemento,4,'0',STR_PAD_LEFT);
                  
                  $link->query("
                    INSERT INTO progelemento
                      (idelemento, elemento, cdgunimed, cdgelemento)
                    VALUES
                      ('".$progElemento_idelemento."', '".$progElemento_elemento."', '".$progElemento_cdgunimed."', '".$progElemento_cdgelemento."')");
                  
                  if ($link->affected_rows > 0) 
                  { $msg_alert = 'El elemento fue insertado con éxito.'; }
                } else
                { for ($cdgelemento = 1; $cdgelemento <= 9999; $cdgelemento++)
                  { $progElemento_cdgelemento = str_pad($cdgelemento,4,'0',STR_PAD_LEFT);
                    
                    $link->query("
                      INSERT INTO progelemento
                        (idelemento, elemento, cdgunimed, cdgelemento)
                      VALUES
                        ('".$progElemento_idelemento."', '".$progElemento_elemento."', '".$progElemento_cdgunimed."', '".$progElemento_cdgelemento."')");
                    
                    if ($link->affected_rows > 0) 
                    { $msg_alert = 'El elemento fue insertado con éxito.'; 
                      break; }
                  }
                }
                $msg_alert = 'El elemento fue insertado con éxito.';
              } 
            }
          }
        }
      } else
      { $msg_alert = $msg_norewrite; }

      $progElemento_cdgelementos = '';
    }

    if (substr($sistModulo_permiso,0,1) == 'r')    
    { // Filtro de elementos de materia prima
      if ($_POST['chckVerTodo'])
      { $vertodo = 'checked'; 

        $progElementoSelect = $link->query("
          SELECT progelemento.idelemento,
                 progelemento.elemento,
                 progelemento.cdgunimed,
                 progelemento.cdgelemento,
                 progelemento.sttelemento
            FROM progelemento
        ORDER BY progelemento.sttelemento DESC,
                 progelemento.idelemento"); 
      } else
      { $progElementoSelect = $link->query("
          SELECT progelemento.idelemento,
                 progelemento.elemento,
                 progelemento.cdgunimed,
                 progelemento.cdgelemento,
                 progelemento.sttelemento
            FROM progelemento
           WHERE progelemento.sttelemento >= '1'
        ORDER BY progelemento.sttelemento DESC,
                 progelemento.idelemento"); }

      if ($progElementoSelect->num_rows > 0)
      { $item = 0;

        while ($regProgElemento = $progElementoSelect->fetch_object())
        { $item++;

          $progElementos_idelemento[$item] = $regProgElemento->idelemento;
          $progElementos_elemento[$item] = $regProgElemento->elemento;
          $progElementos_cdgunimed[$item] = $regProgElemento->cdgunimed;
          $progElementos_cdgelemento[$item] = $regProgElemento->cdgelemento;
          $progElementos_sttelemento[$item] = $regProgElemento->sttelemento; }

        $nElementos = $item; }
      // Final del fintro de elementos de materia prima

      // Filtro de unidades de medida
      $progUniMedSelect = $link->query("
        SELECT * FROM progunimed
      ORDER BY idunimed");

      if ($progUniMedSelect->num_rows > 0)
      { $item = 0;

        while ($regUniMed = $progUniMedSelect->fetch_object())
        { $item++;

          $progUniMeds_idunimed[$item] = $regUniMed->idunimed;
          $progUniMeds_unimed[$item] = $regUniMed->unimed;
          $progUniMeds_cdgunimed[$item] = $regUniMed->cdgunimed; 

          $progUniMed_unimed[$regUniMed->cdgunimed] = $regUniMed->unimed; }

        $nUniMed = $item;
      }
      // Final del filtro de unidades de medida      
    } else
    { $msg_alert = $msg_noread; }

    echo '

    <div>
      <form id="formProgElemento" name="formProgElemento" method="POST" action="progElemento.php">
        <article class="subbloque">
          <label class="modulo_nombre">Elementos</label>
        </article>
        <input type="checkbox" id="chckVerTodo" name="chckVerTodo" onclick="document.formProgElemento.submit()" '.$vertodo.'><label>ver todo</label>
        <a href="ayuda.php#elementos">'.$_help_blue.'</a>

        <section class="subbloque">
          <article>
            <label>Identificador</label><br/>
            <input type="text" id="textIdElemento" name="textIdElemento" value="'.$progElemento_idelemento.'" placeholder="Nombre corto" required/>
            <input type="hidden" id="textCdgElemento" name="textCdgElemento" value="'.$progElemento_cdgelementos.'" />
          </article>

          <article>
            <label>Nombre</label><br/>
            <input type="text" id="textElemento" name="textElemento" value="'.$progElemento_elemento.'" placeholder="Nombre completo" required/>
          </article>

          <article>
            <label>Unidad</label><br/>
            <select id="slctCdgUniMed" name="slctCdgUniMed">';

    if ($nUniMed > 0)
    { for ($item=1; $item<=$nUniMed; $item++)
      { echo '
              <option value="'.$progUniMeds_cdgunimed[$item].'"';

        if ($progElemento_cdgunimed == $progUniMeds_cdgunimed[$item]) 
        { echo ' selected="selected"'; }

        echo '>'.$progUniMeds_unimed[$item].'</option>'; }
    }

    echo '
            </select>
          </article>

          <article><br/>
            <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
          </article>
        </section>
      </form>
    </div>';

    if ($nElementos > 0)
    { echo '<br/>

      <article class="subbloque">
        <label class="modulo_listado">Catálogo de elementos</label>
      </article>
      <label><b>'.$nElementos.'</b> Encontrado(s)</label>';

      for ($item=1; $item<=$nElementos; $item++)
      { echo '
       <section class="listado">
        <article style="vertical-align:top">';

        if ((int)$progElementos_sttelemento[$item] > 0)
        { echo '
          <a href="progElemento.php?cdgelemento='.$progElementos_cdgelemento[$item].'">'.$_search.'</a>
          <a href="progElemento.php?cdgelemento='.$progElementos_cdgelemento[$item].'&proceso=update">'.$_power_blue.'</a>'; 
        } else
        { echo '
          <a href="progElemento.php?cdgelemento='.$progElementos_cdgelemento[$item].'&proceso=delete">'.$_recycle_bin.'</a>              
          <a href="progElemento.php?cdgelemento='.$progElementos_cdgelemento[$item].'&proceso=update">'.$_power_black.'</a>'; }

        echo '
        </article>

        <article style="vertical-align:top">
          <label>Elemento <b>'.$progElementos_idelemento[$item].' | '.$progElementos_elemento[$item].'</b></label><br/>
          <label>Unidad de medida <b>'.utf8_decode($progUniMed_unimed[$progElementos_cdgunimed[$item]]).'</b></label>
        </article>
      </section>'; }
    }

    if ($msg_alert != '')
    { echo '
      <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
      <div><h1>Módulo no encontrado o elementoado.</h1></div>'; }
  ?>

    </div>
  </body> 
</html>