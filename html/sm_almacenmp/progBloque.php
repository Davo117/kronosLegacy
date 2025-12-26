<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Bloques de materia prima</title>
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
        <form id="login" action="progBloque.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 
     
      exit; }

    $progBloque_idbloque = trim($_POST['textIdBloque']);
    $progBloque_bloque = $_POST['textBloque'];
    $progBloque_cdgsustrato = $_POST['slctCdgSustrato'];
    $progBloque_fchbloque = $_POST['dateFchBloque'];  

    if ($progBloque_fchbloque == '') 
    { $progBloque_fchbloque = date("Y-m-d"); }
    else
    { $fchbloque = str_replace("-", "", $progBloque_fchbloque);
      
      $dia = str_pad(substr($fchbloque,6,2),2,'0',STR_PAD_LEFT);
      $mes = str_pad(substr($fchbloque,4,2),2,'0',STR_PAD_LEFT);
      $ano = '20'.str_pad(substr($fchbloque,2,2),2,'0',STR_PAD_LEFT);
            
      if (checkdate((int)$mes,(int)$dia,(int)$ano)) 
      { $progBloque_fchbloque = $ano.'-'.$mes.'-'.$dia; }
      else
      { $progBloque_fchbloque = date('Y-m-d'); }
    }

    if ($_GET['cdgbloque'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $progBloqueSelect = $link->query("
          SELECT * FROM progbloque
           WHERE cdgbloque = '".$_GET['cdgbloque']."'");
        
        if ($progBloqueSelect->num_rows > 0)
        { $regProgBloque = $progBloqueSelect->fetch_object();

          $progBloque_idbloque = $regProgBloque->idbloque;
          $progBloque_bloque = $regProgBloque->bloque;
          $progBloque_cdgsustrato = $regProgBloque->cdgsustrato;
          $progBloque_fchbloque = $regProgBloque->fchbloque;
          $progBloque_cdgbloque = $regProgBloque->cdgbloque;
          $progBloque_sttbloque = $regProgBloque->sttbloque;

          if ($_GET['proceso'] == 'upload')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { $pdtoSustratoSelect = $link->query("
                SELECT * FROM pdtosustrato
                 WHERE cdgsustrato = '".$progBloque_cdgsustrato."'");

              if ($pdtoSustratoSelect->num_rows > 0)
              { $regPdtoSustrato = $pdtoSustratoSelect->fetch_object();

                require_once '../php-excel-reader/excel_reader2.php';
                $data = new Spreadsheet_Excel_Reader("excel/upload2.xls");

                // Inicio de la importación de registros
                for ($i = 2; $i <= 999; $i++) 
                { $progLote_cdglote = $progBloque_cdgbloque.str_pad($data->sheets[0]['cells'][$i][7],3,'0',STR_PAD_LEFT).'0000';
                      
                  if (trim($data->sheets[0]['cells'][$i][1]) != '') 
                  { $link->query("
                      INSERT INTO proglote
                        (cdgbloque, idlote, lote, longitud, peso, tarima, cdglote, fchmovimiento)
                      VALUES
                        ('".$progBloque_cdgbloque."', '".$data->sheets[0]['cells'][$i][7]."', '".$data->sheets[0]['cells'][$i][1]."', '".$data->sheets[0]['cells'][$i][2]."', '".$data->sheets[0]['cells'][$i][6]."', '".$data->sheets[0]['cells'][$i][8]."', '".$progLote_cdglote."', NOW())");
                  } else
                  { break; }
                }
                // Final de la importación de registros                
              } else
              { // Error, el sustrato del bloque no fue definido
                $msg_alert = 'Código de bloque '.$progLote_cdgbloque.' error en el sustrato [No definido]'; }
            } else
            { $msg_alert = $msg_norewrite; }
          }
          
          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($progBloque_sttbloque == '1')
              { $progBloque_newsttbloque = '0'; }
            
              if ($progBloque_sttbloque == '0')
              { $progBloque_newsttbloque = '1'; }
              
              if ($progBloque_newsttbloque != '')
              { $link->query("
                  UPDATE progbloque
                     SET sttbloque = '".$progBloque_newsttbloque."' 
                   WHERE cdgbloque = '".$progBloque_cdgbloque."'");
                
                if ($link->affected_rows > 0)
                { $msg_alert = 'El bloque fue actualizado en su status.'; }
              }
            } else
            { $msg_alert = $msg_norewrite; }
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $progLoteSelect = $link->query("
                SELECT * FROM proglote
                 WHERE cdgbloque = '".$progBloque_cdgbloque."'");
                
              if ($progLoteSelect->num_rows > 0)
              { $msg_alert = 'El bloque no esta vacío, no pudo ser eliminado.'; }
              else
              { $link->query("
                  DELETE FROM progbloque
                   WHERE cdgbloque = '".$progBloque_cdgbloque."' AND 
                         sttbloque = '0'");
                  
                if ($link->affected_rows > 0)
                { $msg_alert = 'El bloque fue eliminado con éxito.'; }
                else
                { $msg_alert = 'El bloque no fue eliminado.'; }
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
      { if (strlen($progBloque_idbloque) > 0)
        { $progBloqueSelect = $link->query("
            SELECT * FROM progbloque
             WHERE idbloque = '".$progBloque_idbloque."'");
            
          if ($progBloqueSelect->num_rows > 0)
          {	$regProgBloque = $progBloqueSelect->fetch_object();
            
            $link->query("
              UPDATE progbloque
                 SET bloque = '".$progBloque_bloque."'                     
               WHERE cdgbloque = '".$regProgBloque->cdgbloque."' AND 
                     sttbloque = '1'");
              
            if ($link->affected_rows > 0) 
            { $msg_alert = 'El bloque fue actualizado éxito.'; }
          } else
          { $progBloqueSelect = $link->query("
            SELECT COUNT(cdgbloque) AS nBloques, 
                   MAX(cdgbloque) AS lBloque 
              FROM progbloque");

            if ($progBloqueSelect->num_rows > 0)
            { $regProgBloque = $progBloqueSelect->fetch_object();

              if (str_pad($regProgBloque->nBloques,5,'0',STR_PAD_LEFT) == $regProgBloque->lBloque)
              { $cdgbloque = ($regProgBloque->nBloques+1);
                $progBloque_cdgbloque = str_pad($cdgbloque,5,'0',STR_PAD_LEFT);
                
                $link->query("
                  INSERT INTO progbloque
                    (idbloque, bloque, cdgsustrato, fchbloque, cdgbloque)
                  VALUES
                    ('".$progBloque_idbloque."', '".$progBloque_bloque."', '".$progBloque_cdgsustrato."', '".$progBloque_fchbloque."', '".$progBloque_cdgbloque."')");
                
                if ($link->affected_rows > 0) 
                { $msg_alert = 'El bloque fue insertado con éxito.'; }
              } else
              { for ($cdgbloque = 1; $cdgbloque <= 99999; $cdgbloque++)
                { $progBloque_cdgbloque = str_pad($cdgbloque,5,'0',STR_PAD_LEFT);
                  
                  $link->query("
                    INSERT INTO progbloque
                      (idbloque, bloque, cdgsustrato, fchbloque, cdgbloque)
                    VALUES
                      ('".$progBloque_idbloque."', '".$progBloque_bloque."', '".$progBloque_cdgsustrato."', '".$progBloque_fchbloque."', '".$progBloque_cdgbloque."')");
                  
                  if ($link->affected_rows > 0) 
                  { $msg_alert = 'El bloque fue insertado con éxito.'; 
                    break; }
                }
              }

              $msg_alert = 'El bloque fue insertado con éxito.'.$regProgBloque->nBloques;
            } else
            { // Registro inicial
            }
          }
        }
      } else
      { $msg_alert = $msg_norewrite; }
    }

    if (substr($sistModulo_permiso,0,1) == 'r')    
    { // Filtro de bloques de materia prima
      if ($_POST['chckVerTodo'])
      { $vertodo = 'checked'; 

        $progBloqueSelect = $link->query("
          SELECT progbloque.idbloque,
                 progbloque.bloque,
                 progbloque.cdgsustrato,                 
                 progbloque.cdgbloque,
                 progbloque.sttbloque
            FROM progbloque
        GROUP BY progbloque.cdgbloque
        ORDER BY progbloque.sttbloque DESC,
                 progbloque.idbloque DESC"); 
      } else
      { $progBloqueSelect = $link->query("
          SELECT progbloque.idbloque,
                 progbloque.bloque,
                 progbloque.cdgsustrato,
                 progbloque.cdgbloque,
                 progbloque.sttbloque
            FROM progbloque
           WHERE progbloque.sttbloque >= '1'
        GROUP BY progbloque.cdgbloque
        ORDER BY progbloque.sttbloque DESC,
                 progbloque.idbloque DESC"); }

      if ($progBloqueSelect->num_rows > 0)
      { $item = 0;

        while ($regProgBloque = $progBloqueSelect->fetch_object())
        { $item++;

          $progBloques_idbloque[$item] = $regProgBloque->idbloque;
          $progBloques_bloque[$item] = $regProgBloque->bloque;
          $progBloques_cdgsustrato[$item] = $regProgBloque->cdgsustrato;
          $progBloques_cdgbloque[$item] = $regProgBloque->cdgbloque;
          $progBloques_sttbloque[$item] = $regProgBloque->sttbloque;

          $progLoteSelect = $link->query("
            SELECT proglote.cdgbloque,
               SUM(proglote.peso) AS peso,
                   proglote.sttlote
              FROM progbloque,
                   proglote
             WHERE progbloque.cdgbloque = '".$regProgBloque->cdgbloque."' AND
                   progbloque.cdgbloque = proglote.cdgbloque
          GROUP BY proglote.cdgbloque,
                   proglote.sttlote"); 

          if ($progLoteSelect->num_rows > 0)
          { while ($regProgLote = $progLoteSelect->fetch_object())
            { $progBloques_peso[$regProgLote->cdgbloque] += $regProgLote->peso;
              $progBloques_pesos[$regProgLote->cdgbloque][$regProgLote->sttlote] = $regProgLote->peso; }
          }
        }

        $nBloques = $item; }
      // Fin de filtros de materia prima

      // Filtro de sustratos
      $pdtoSustratoSelect = $link->query("
        SELECT pdtosustrato.idsustrato,
               pdtosustrato.sustrato,
               pdtosustrato.cdgsustrato
          FROM pdtosustrato
      ORDER BY pdtosustrato.idsustrato");

      if ($pdtoSustratoSelect->num_rows > 0)
      { $item = 0;

        while ($regPdtoSustrato = $pdtoSustratoSelect->fetch_object())
        { $item++;

          $pdtoSustrato_idsustrato[$item] = $regPdtoSustrato->idsustrato;
          $pdtoSustrato_sustrato[$item] = $regPdtoSustrato->sustrato; 
          $pdtoSustrato_cdgsustrato[$item] = $regPdtoSustrato->cdgsustrato;

          $pdtoSustratos_sustrato[$regPdtoSustrato->cdgsustrato] = $regPdtoSustrato->sustrato; }

        $nSustratos = $item; }
      // Final del filtro de sustratos 
    } else
    { $msg_alert = $msg_noread; }

    echo '
      <div class="bloque">
        <form id="formProgBloque" name="formProgBloque" method="POST" action="progBloque.php">
          <article class="subbloque">
            <label class="modulo_nombre">Bloque de materia prima</label>
          </article>
          <input type="checkbox" id="chckVerTodo" name="chckVerTodo" onclick="document.formProgBloque.submit()" '.$vertodo.'><label>Ver todo</label>
          <a href="ayuda.php#Bloques">'.$_help_blue.'</a>

          <section class="subbloque">
            <article>
              <label>Identificador</label><br/>
              <input type="text" id="textIdBloque" name="textIdBloque" value="'.$progBloque_idbloque.'" placeholder="Nombre corto" required/>
            </article>

            <article>
              <label>Nombre</label><br/>
              <input type="text" id="textBloque" name="textBloque" value="'.$progBloque_bloque.'" placeholder="Nombre completo" required/>
            </article>

            <article>
              <label>Sustrato</label><br/>
              <select id="slctCdgSustrato" name="slctCdgSustrato">';

    if ($nSustratos > 0)
    { for ($item=1; $item<=$nSustratos; $item++)
      { echo '
                <option value="'.$pdtoSustrato_cdgsustrato[$item].'"';

        if ($progBloque_cdgsustrato == $pdtoSustrato_cdgsustrato[$item]) 
        { echo ' selected="selected"'; }

        echo '>'.$pdtoSustrato_sustrato[$item].'</option>'; }
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

    echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Catálogo de bloques</label>
        </article>
        <label><b>'.$nBloques.'</b> Encontrado(s) '.$test.'</label>';

    if ($nBloques > 0)
    { for ($item=1; $item<=$nBloques; $item++)
      { echo '
        <section class="listado">
          <article style="vertical-align:top">';

        if ((int)$progBloques_sttbloque[$item] > 0)
        { echo '
            <a href="progBloque.php?cdgbloque='.$progBloques_cdgbloque[$item].'">'.$_search.'</a>
            <a href="progLote.php?cdgbloque='.$progBloques_cdgbloque[$item].'">'.$_link.'</a>
            <a href="progBloque.php?cdgbloque='.$progBloques_cdgbloque[$item].'&proceso=upload">'.$_excel.'</a>
            <a href="pdf/CO-RPT01.php?cdgbloque='.$progBloques_cdgbloque[$item].'" target="_blank">'.$_acrobat.'</a>
            <a href="pdf/progLoteBC.php?cdgbloque='.$progBloques_cdgbloque[$item].'" target="_blank">'.$_barcode.'</a>
            <a href="progBloque.php?cdgbloque='.$progBloques_cdgbloque[$item].'&proceso=update">'.$_power_blue.'</a>'; 
        } else
        { echo '
            <a href="progBloque.php?cdgbloque='.$progBloques_cdgbloque[$item].'&proceso=delete">'.$_recycle_bin.'</a>              
            <a href="progBloque.php?cdgbloque='.$progBloques_cdgbloque[$item].'&proceso=update">'.$_power_black.'</a>'; }

        echo '
          </article>

          <article align="right">
            <label><b>Bloque</b></label><br/>
            <label><b>Sust</b></label><br/>
            <label><b>Peso</b></label>
          </article>

          <article style="vertical-align:top">
            <label>'.$progBloques_idbloque[$item].' | '.$progBloques_bloque[$item].'</label><br/>
            <label>'.utf8_decode($pdtoSustratos_sustrato[$progBloques_cdgsustrato[$item]]).'</label><br/>
            <label>'.number_format($progBloques_peso[$progBloques_cdgbloque[$item]],3).' kgs </label>
          </article>
        </section>'; }
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