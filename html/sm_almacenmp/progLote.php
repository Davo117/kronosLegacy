<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Lotes de materia prima</title>    
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
<?php

  include '../datos/mysql.php';
  $link = conectar();  

  m3nu_almacenmp();  

  $sistModulo_cdgmodulo = '30250';
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
        <form id="login" action="progLote.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 
     
      exit; }

    $progLote_cdgbloque = $_POST['slctCdgBloque'];
    $progLote_idlote = trim($_POST['textIdLote']);
    $progLote_lote = trim($_POST['textLote']);
    $progLote_longitud = trim($_POST['textLongitud']);    
    $progLote_peso = trim($_POST['textpeso']);
    $progLote_tarima = trim($_POST['textTarima']);
  
    if ($_GET['cdgbloque'])
    { $progLote_cdgbloque = $_GET['cdgbloque']; } 

    if ($_GET['cdglote'])
    { $progLoteSelect = $link->query("
        SELECT * FROM proglote
         WHERE cdglote = '".$_GET['cdglote']."'");
      
      if ($progLoteSelect->num_rows > 0)
      { $regProgLote = $progLoteSelect->fetch_object();

        $progLote_cdgbloque = $regProgLote->cdgbloque;
        $progLote_idlote = $regProgLote->idlote;
        $progLote_lote = $regProgLote->lote;
        $progLote_longitud = $regProgLote->longitud;
        $progLote_peso = $regProgLote->peso;
        $progLote_tarima = $regProgLote->tarima;
        $progLote_cdglote = $regProgLote->cdglote;
        $progLote_sttlote = $regProgLote->sttlote;

        if ($_GET['proceso'] == 'update')
        { if (substr($sistModulo_permiso,0,3) == 'rwx')
          { if ($progLote_sttlote == '1')
            { $progLote_newsttlote = '0'; }
          
            if ($progLote_sttlote == '0')
            { $progLote_newsttlote = '1'; }
            
            if ($progLote_newsttlote != '')
            { $link->query("
                UPDATE proglote
                   SET sttlote = '".$progLote_newsttlote."' 
                 WHERE cdglote = '".$progLote_cdglote."'");
              
              if ($link->affected_rows > 0)
              { $msg_alert = 'El lote '.$progLote_idlote.' fue actualizado en su status.'; }
            }
          } else
          { $msg_alert = $msg_nodelete; }
        }

        if ($_GET['proceso'] == 'undo')
        { if (substr($sistModulo_permiso,0,3) == 'rwx')
          { $link->query("
              UPDATE proglote
                 SET sttlote = '1' 
               WHERE cdglote = '".$progLote_cdglote."' AND
                     sttlote = '7'");
              
            if ($link->affected_rows > 0)
            { $msg_alert = 'El lote '.$progLote_idlote.' fue Retornado al almacen.';

              $link->query("
                DELETE FROM prodloteope
                      WHERE cdglote = '".$progLote_cdglote."' AND
                            cdgoperacion = '00090'");
            } else
            { $msg_alert = 'El lote '.$progLote_idlote.' NO fue Retornado al almacen.'; }
          } else
          { $msg_alert = $msg_nodelete; }
        } 

        if ($_GET['proceso'] == 'delete')
        { if (substr($sistModulo_permiso,0,3) == 'rwx')
          { $progLoteSelect = $link->query("
              SELECT * FROM prodlote
               WHERE cdglote = '".$progLote_cdglote."'");
              
            if ($progLoteSelect->num_rows > 0)
            { $msg_alert = 'El lote '.$progLote_idlote.' ya esta en proceso, no pudo ser eliminado.'; }
            else
            { $link->query("
                DELETE FROM proglote
                 WHERE cdglote = '".$progLote_cdglote."' AND 
                       sttlote = '0'");
                
              if ($link->affected_rows > 0)
              { $msg_alert = 'El lote '.$progLote_idlote.' fue eliminado con exito.'; }
              else
              { $msg_alert = 'El lote '.$progLote_idlote.' NO fue eliminado.'; }
            }
          } else
          { $msg_alert = $msg_nodelete; }
        }
      }
    }

    // Verificación de la existencia del bloque y extracción del tipo de sustrato que contiene. 
    $querySelect = $link->query("
      SELECT progbloque.idbloque,
             progbloque.bloque,
             progbloque.cdgsustrato,
             progbloque.fchbloque,
             progbloque.cdgbloque,
             progbloque.sttbloque
        FROM progbloque,
             pdtosustrato
       WHERE progbloque.cdgbloque = '".$progLote_cdgbloque."' AND
             progbloque.cdgsustrato = pdtosustrato.cdgsustrato");
    
    if ($querySelect->num_rows > 0)
    { $regQuery = $querySelect->fetch_object();

      $progLote_idbloque = $regQuery->idbloque;
      $progLote_bloque = $regQuery->bloque;
      $progLote_cdgsustrato = $regQuery->cdgsustrato;
      $progLote_fchbloque = $regQuery->fchbloque;
      $progLote_cdgbloque = $regQuery->cdgbloque;
      $progLote_sttbloque = $regQuery->sttbloque;
    } else
    { $progLote_lote = ''; }
    // Final de la verificacion del bloque

    if ($_POST['bttnSalvar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if (strlen($progLote_lote) > 0)
        { $progLoteSelect = $link->query("
            SELECT * FROM proglote
             WHERE cdgbloque = '".$progLote_cdgbloque."' AND 
                   idlote = '".$progLote_idlote."'");
            
          if ($progLoteSelect->num_rows > 0)
          { $regProgLote = $progLoteSelect->fetch_object();
            
            $link->query("
              UPDATE proglote
                 SET lote = '".$progLote_lote."',
                     longitud = '".$progLote_longitud."',
                     peso = '".$progLote_peso."',
                     tarima = '".$progLote_tarima."'
               WHERE cdglote = '".$regProgLote->cdglote."' AND 
                     sttlote = '1'");
              
            if ($link->affected_rows > 0) 
            { $msg_alert = 'El lote '.$progLote_idlote.' fue actualizado exito.'; }
            else
            { $msg_alert = 'El lote '.$progLote_idlote.' NO fue actualizado (Posiblemente liberado).'; }
          } else
          { $progLote_cdglote = $progLote_cdgbloque.str_pad($progLote_idlote,3,'0',STR_PAD_LEFT).'0000';
              
            $link->query("
              INSERT INTO proglote
                (cdgbloque, idlote, lote, longitud, peso, tarima, cdglote)
              VALUES
                ('".$progLote_cdgbloque."', '".$progLote_idlote."', '".$progLote_lote."', '".$progLote_longitud."', '".$progLote_peso."', '".$progLote_tarima."', '".$progLote_cdglote."')");
            
            if ($link->affected_rows > 0) 
            { $msg_alert = 'El lote '.$progLote_idlote.' fue insertado con exito.'; }
            else
            { $msg_alert = 'El lote '.$progLote_idlote.' NO fue insertado.'; }
          }

          $progLoteSelect = $link->query("
            SELECT MAX(idlote) AS idlote FROM proglote
             WHERE cdgbloque = '".$progLote_cdgbloque."'");

          $regLoteSelect = $progLoteSelect->fetch_object();

          $progLote_idlote = $regLoteSelect->idlote+1;
        }
      } else
      { $msg_alert = $msg_norewrite; }
    }
  
    if (substr($sistModulo_permiso,0,1) == 'r')
    { // Filtro de bloques
      $progBloqueSelect = $link->query("
        SELECT * FROM progbloque
         WHERE sttbloque = '1'
      ORDER BY bloque");
      
      if ($progBloqueSelect->num_rows > 0)
      { $item = 0;

        while ($regProgBloque = $progBloqueSelect->fetch_object()) 
        { $item++; 

          $progBloques_idbloque[$item] = $regProgBloque->idbloque;
          $progBloques_bloque[$item] = $regProgBloque->bloque;
          $progBloques_cdgsustrato[$item] = $regProgBloque->cdgsustrato;
          $progBloques_cdgbloque[$item] = $regProgBloque->cdgbloque; }

        $nBloques = $item; }
      // Final del filtro de bloques

      // Filtro de tarimas por bloque 
      $progLoteSelect = $link->query("
        SELECT proglote.tarima 
          FROM proglote
         WHERE proglote.cdgbloque = '".$progLote_cdgbloque."'
      GROUP BY proglote.tarima
      ORDER BY proglote.tarima");
    
      if ($progLoteSelect->num_rows > 0)
      { $item = 0;

        while ($regProgLote = $progLoteSelect->fetch_object())
        { $item++;

          $progLotes_tarima[$item] = $regProgLote->tarima; }

        $nTarimas = $item; }
      // Final del filtro de tarimas por bloque

      // Filtro del contenido del bloque por tarima
      for ($item = 1; $item <= $nTarimas; $item++)
      { $progLoteSelect = $link->query("
          SELECT * FROM proglote
           WHERE cdgbloque = '".$progLote_cdgbloque."' AND 
                 tarima = '".$progLotes_tarima[$item]."'
        ORDER BY cdglote"); 

        if ($progLoteSelect->num_rows > 0)  
        { $subItem = 0;

          while ($regProgLote = $progLoteSelect->fetch_object())
          { $subItem++;

            $progLotes_idlote[$item][$subItem] = $regProgLote->idlote;
            $progLotes_lote[$item][$subItem] = $regProgLote->lote;
            $progLotes_longitud[$item][$subItem] = $regProgLote->longitud;
            $progLotes_peso[$item][$subItem] = $regProgLote->peso;
            $progLotes_fchmovimiento[$item][$subItem] = $regProgLote->fchmovimiento;
            $progLotes_cdglote[$item][$subItem] = $regProgLote->cdglote;
            $progLotes_sttlote[$item][$subItem] = $regProgLote->sttlote; }

          $nLotes[$item] = $subItem; }

        $tLotes += $subItem; }
      // Final del filtro del contenido del bloque por tarima

      //Filtro de NoOP por Lote dentro del bloque
      $prodLoteSelect = $link->query("
        SELECT prodlote.cdglote, 
               prodlote.noop
          FROM proglote, 
               prodlote
         WHERE proglote.cdglote = prodlote.cdglote AND
               proglote.cdgbloque = '".$progLote_cdgbloque."'");

      if ($prodLoteSelect->num_rows > 0)
      { while ($regProdLote = $prodLoteSelect->fetch_object())
        { $prodLote_noop[$regProdLote->cdglote] = $regProdLote->noop; }
      }
      // Final del filtro de NoOP's por lote dentro del bloque      
    }

    echo '
      <div class="bloque">
        <form id="frmProgLote" name="frmProgLote" method="POST" action="progLote.php">
          <article class="subbloque">
            <label class="modulo_nombre">Lote de materia prima</label>
          </article>
          <a href="ayuda.php#Lotes">'.$_help_blue.'</a>

          <section class="subbloque">
            <article>
              <label><a href="progBloque.php?cdgbloque='.$progLote_cdgbloque.'">Bloque</a></label><br/>
              <select id="slctCdgBloque" name="slctCdgBloque" onchange="document.frmProgLote.submit()">
                <option value=""> - </option>';
    
    for ($item = 1; $item <= $nBloques; $item++)
    { echo '
                <option value="'.$progBloques_cdgbloque[$item].'"';
            
      if ($progLote_cdgbloque == $progBloques_cdgbloque[$item]) 
      { echo ' selected="selected"'; }
      
      echo '>'.$progBloques_bloque[$item].'</option>'; }
    
    echo '
              </select>            
            </article>

            <article>
              <label>No. Lote</label><br/>
              <input type="text" id="textIdLote" name="textIdLote" value="'.$progLote_idlote.'" maxlenght="3" placeholder="###" required />
            </article>

            <article>
              <label>Referencia</label><br/>
              <input type="text" id="textLote" name="textLote" value="'.$progLote_lote.'" placeholder="código proveedor" required />
            </article>

            <article>
              <label>Longitud</label><br/>
              <input type="text" class="input_numero" id="textLongitud" name="textLongitud" value="'.$progLote_longitud.'" placeholder="metros" required />
            </article>

            <article>
              <label>Peso</label><br/>
              <input type="text" class="input_numero" id="textpeso" name="textpeso" value="'.$progLote_peso.'" placeholder="kilogramos" required />
            </article>

            <article>
              <label>Tarima</label><br/>
              <input type="text" id="textTarima" name="textTarima" value="'.$progLote_tarima.'" placeholder="Agrupador" required />
            </article>

            <article><br/>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>
          </section>
        </form>
      </div>';
  
    echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Contenido del bloque</label>
        </article>

        <label><b>'.$tLotes.'</b> Lotes encontrados</label>';

    if ($nTarimas > 0)
    { for ($item = 1; $item <= $nTarimas; $item++)
      { echo '
        <section class="listado">';

        for ($subItem = 1; $subItem <= $nLotes[$item]; $subItem++)
        { echo '          
          <article style="vertical-align:top">';

          if ($progLotes_sttlote[$item][$subItem] == 9)
          { echo '
            <a href="progLote.php?cdglote='.$progLotes_cdglote[$item][$subItem].'">'.$_search.'</a>
            <a href="#">'.$_gear.'</a>'; }

          if ($progLotes_sttlote[$item][$subItem] == 8)
          { echo '
            <a href="progLote.php?cdglote='.$progLotes_cdglote[$item][$subItem].'">'.$_search.'</a>
            <a href="#">'.$_calendar.'</a>'; }

          if ($progLotes_sttlote[$item][$subItem] == 7)
          { echo '
            <a href="progLote.php?cdglote='.$progLotes_cdglote[$item][$subItem].'">'.$_search.'</a>
            <a href="progLote.php?cdglote='.$progLotes_cdglote[$item][$subItem].'&proceso=undo">'.$_sub_blue_accept.'</a>'; } 

          if ($progLotes_sttlote[$item][$subItem] == 1)
          { echo '
            <a href="progLote.php?cdglote='.$progLotes_cdglote[$item][$subItem].'">'.$_search.'</a>
            <a href="progLote.php?cdglote='.$progLotes_cdglote[$item][$subItem].'&proceso=update">'.$_power_blue.'</a>'; }
          
          if ($progLotes_sttlote[$item][$subItem] == 0)
          { echo '
            <a href="progLote.php?cdglote='.$progLotes_cdglote[$item][$subItem].'&proceso=delete">'.$_recycle_bin.'</a>
            <a href="progLote.php?cdglote='.$progLotes_cdglote[$item][$subItem].'&proceso=update">'.$_power_black.'</a>'; }
          
          echo '
          </article>
            
          <article>
            <label>Lote <b>'.$progLotes_lote[$item][$subItem].'</b></label>
            <label>No. <b>'.$progLotes_idlote[$item][$subItem].'</b></label><br/>
            <label><b>'.number_format($progLotes_longitud[$item][$subItem],2).'</b> <i>mts</i></label>
            <label><b>'.number_format($progLotes_peso[$item][$subItem],3).'</b> <i>kgs</i></label><br/>
            <label class="textNombre">NoOP <b>'.$prodLote_noop[$progLotes_cdglote[$item][$subItem]].'</b></label>
          </article><br/>';

          $progLote_sumalongitud[$item] += $progLotes_longitud[$item][$subItem];
          $progLote_sumapeso[$item] += $progLotes_peso[$item][$subItem]; }

        echo '
          <article style="text-align:right">
            <label>Tarima <b>'.$progLotes_tarima[$item].'</b> | <b>'.$nLotes[$item].'</b> Unidades</label><br/>
            <label><b>'.number_format($progLote_sumalongitud[$item],2).'</b> <i>mts</i></label><br/>
            <label><b>'.number_format($progLote_sumapeso[$item],3).'</b> <i>kgs</i></label>
          </article>
        </section><br/>'; 
      }
    }

    echo '
      </div>'; 

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
