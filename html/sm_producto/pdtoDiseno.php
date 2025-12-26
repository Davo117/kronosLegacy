<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Diseños de sello de seguridad</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">     
<?php

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_producto();

  $sistModulo_cdgmodulo = '20010';
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
        <form id="login" action="pdtoDiseno.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 
     
      exit; }

    $pdtoDiseno_iddiseno = trim($_POST['textIdDiseno']);
    $pdtoDiseno_diseno = trim($_POST['textDiseno']);

    if ($_POST['bttnSalvar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { // Búsqueda de un registro
        $pdtoDisenoSelect = $link->query("
          SELECT * FROM pdtodiseno
           WHERE iddiseno = '".$pdtoDiseno_iddiseno."'");

        if ($pdtoDisenoSelect->num_rows > 0)
        { $regPdtoDiseno = $pdtoDisenoSelect->fetch_object();
          
          $pdtoDiseno_cdgdiseno = $regPdtoDiseno->cdgdiseno;

          $link->query("
            UPDATE pdtodiseno 
               SET diseno = '".$pdtoDiseno_diseno."'
             WHERE cdgdiseno = '".$pdtoDiseno_cdgdiseno."' AND 
                   sttdiseno = '1'");

          if ($link->affected_rows > 0)
          { $msg_alert .= utf8_decode('El diseño fue actualizado con exito.'); }
          else
          { $msg_alert .= utf8_decode('El diseño NO fue actualizado.'); }
/*
          if ($_POST['chckConsumo'])
          { $pdtoConsumoSelect = $link->query("
              SELECT * FROM pdtoconsumo
               WHERE cdgdiseno = '000'
            ORDER BY cdgconsumo");

            if ($pdtoConsumoSelect->num_rows > 0)
            { $msg_alert = "Si tenemos consumos /n";

              while ($regPdtoConsumo = $pdtoConsumoSelect->fetch_object())
              { for ($item = 1; $item <= 99; $item++)
                { $pdtoConsumo_cdgconsumo = $pdtoDiseno_cdgdiseno.'C'.str_pad($item,2,'0',STR_PAD_LEFT);
                  
                  $link->query("
                    INSERT INTO pdtoconsumo
                      (cdgdiseno, cdgsubproceso, cdgelemento, consumo, cdgconsumo)
                    VALUES
                      ('".$pdtoDiseno_cdgdiseno."', '".$regPdtoConsumo->cdgsubproceso."', '".$regPdtoConsumo->cdgelemento."', '".$regPdtoConsumo->consumo."', '".$pdtoConsumo_cdgconsumo."')");
                  
                  if ($link->affected_rows > 0) 
                  { $msg_alert .= "elemento ".$item."/n";

                    $link->query("
                      INSERT INTO krdxconsumo
                          (cdgdiseno, cdgsubproceso, cdgelemento, consumo, cdgconsumo, cdgusuario, operacion, fchmovimiento)
                        VALUES
                          ('".$pdtoDiseno_cdgdiseno."', '".$regPdtoConsumo->cdgsubproceso."', '".$regPdtoConsumo->cdgelemento."', '".$regPdtoConsumo->consumo."', '".$pdtoConsumo_cdgconsumo."', '".$_SESSION['cdgusuario']."', 'INSERT', NOW())");
                    break; }
                }
              }
            }
          } //*/
        } else
        { for ($cdgdiseno = 1; $cdgdiseno <= 1000; $cdgdiseno++)
          { $pdtoDiseno_cdgdiseno = str_pad($cdgdiseno,3,'0',STR_PAD_LEFT);

            if ($cdgdiseno > 999)
            { $msg_alert = utf8_decode('El diseño NO fue registrado, contacta a soporte.'); }
            else
            { $link->query("
                INSERT INTO pdtodiseno
                  (iddiseno, diseno, cdgdiseno)
                VALUES
                  ('".$pdtoDiseno_iddiseno."', '".$pdtoDiseno_diseno."', '".$pdtoDiseno_cdgdiseno."')");

              if ($link->affected_rows > 0)
              { $msg_alert = utf8_decode('El diseño fue registrado satisfactoriamente.');

                if ($_POST['chckConsumo'])
                { $pdtoConsumoSelect = $link->query("
                    SELECT * FROM pdtoconsumo
                     WHERE cdgdiseno = '000'
                  ORDER BY cdgconsumo");

                  if ($pdtoConsumoSelect->num_rows > 0)
                  { while ($regPdtoConsumo = $pdtoConsumoSelect->fetch_object())
                    { for ($item = 1; $item <= 99; $item++)
                      { $pdtoConsumo_cdgconsumo = $pdtoDiseno_cdgdiseno.'C'.str_pad($item,2,'0',STR_PAD_LEFT);
                        
                        $link->query("
                          INSERT INTO pdtoconsumo
                            (cdgdiseno, cdgsubproceso, cdgelemento, consumo, cdgconsumo)
                          VALUES
                            ('".$pdtoDiseno_cdgdiseno."', '".$regPdtoConsumo->cdgsubproceso."', '".$regPdtoConsumo->cdgelemento."', '".$regPdtoConsumo->consumo."', '".$pdtoConsumo_cdgconsumo."')");
                        
                        if ($link->affected_rows > 0) 
                        { $link->query("
                            INSERT INTO krdxconsumo
                                (cdgdiseno, cdgsubproceso, cdgelemento, consumo, cdgconsumo, cdgusuario, operacion, fchmovimiento)
                              VALUES
                                ('".$pdtoDiseno_cdgdiseno."', '".$regPdtoConsumo->cdgsubproceso."', '".$regPdtoConsumo->cdgelemento."', '".$regPdtoConsumo->consumo."', '".$pdtoConsumo_cdgconsumo."', '".$_SESSION['cdgusuario']."', 'INSERT', NOW())");
                          break; }
                      }
                    }
                  }
                }
                
                break; }
            }
          }
        }
      } else
      { $msg_alert = $msg_norewrite; }
    }

    if (substr($sistModulo_permiso,0,1) == 'r')
    { if ($_GET['cdgdiseno'])
      { $pdtoDisenoSelect = $link->query("
          SELECT * FROM pdtodiseno
           WHERE cdgdiseno = '".$_GET['cdgdiseno']."'");

        if ($pdtoDisenoSelect->num_rows > 0)
        { $regPdtoDiseno = $pdtoDisenoSelect->fetch_object();

          $pdtoDiseno_iddiseno = $regPdtoDiseno->iddiseno;
          $pdtoDiseno_diseno = $regPdtoDiseno->diseno;
          $pdtoDiseno_cdgdiseno = $regPdtoDiseno->cdgdiseno;
          $pdtoDiseno_sttdiseno = $regPdtoDiseno->sttdiseno;

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($pdtoDiseno_sttdiseno == '1')
              { $pdtoDiseno_newsttdiseno = '0'; }

              if ($pdtoDiseno_sttdiseno == '0')
              { $pdtoDiseno_newsttdiseno = '1'; }
              
              if ($pdtoDiseno_newsttdiseno != '')
              { $link->query("
                  UPDATE pdtodiseno
                     SET sttdiseno = '".$pdtoDiseno_newsttdiseno."'
                   WHERE cdgdiseno = '".$pdtoDiseno_cdgdiseno."'");

                if ($link->affected_rows > 0)
                { $msg_alert = utf8_decode('El diseño fue actualizado en su status.'); }
                else
                { $msg_alert = utf8_decode('El diseño NO fue actualizado (status).'); }
              }
            } else
            { $msg_alert = $msg_norewrite; }
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $pdtoConsumoSelect = $link->query("
                SELECT * FROM pdtoconsumo
                 WHERE cdgdiseno = '".$pdtoDiseno_cdgdiseno."'");
 
              if ($pdtoConsumoSelect->num_rows > 0)
              { $msg_alert = utf8_decode('El diseño no puede eliminarse, tiene registros vinculados. (Consumos)');
              } else
              { $pdtoImpresionSelect = $link->query("
                  SELECT * FROM pdtoimpresion
                   WHERE cdgdiseno = '".$pdtoDiseno_cdgdiseno."'");

                if ($pdtoImpresionSelect->num_rows > 0)
                { $msg_alert = utf8_decode('El diseño no puede eliminarse, tiene registros vinculados. (Impresiones)'); }
                else
                { $link->query("
                    DELETE FROM pdtodiseno
                     WHERE cdgdiseno = '".$pdtoDiseno_cdgdiseno."' AND
                           sttdiseno = '0'");

                  if ($link->affected_rows > 0)
                  { $msg_alert = utf8_decode('El diseño fue eliminado con exito.'); }
                  else
                  { $msg_alert = utf8_decode('El diseño NO fue eliminado.'); }
                }
              }
            } else
            { $msg_alert = $msg_nodelete; }
          }
        }
      }

      // Filtro de registros
      if ($_POST['chckVerTodo'])
      { $vertodo = 'checked';
        // Filtrado completo
        $pdtoDisenoSelect = $link->query("
          SELECT * FROM pdtodiseno
        ORDER BY sttdiseno DESC,
                 iddiseno,
                 diseno");
      } else
      { // Buscar coincidencias
        $pdtoDisenoSelect = $link->query("
          SELECT * FROM pdtodiseno
           WHERE sttdiseno = '1'
        ORDER BY iddiseno,
                 diseno"); }

      if ($pdtoDisenoSelect->num_rows > 0)
      { $item = 0;

        while ($regPdtoDiseno = $pdtoDisenoSelect->fetch_object())
        { $item++;

          $pdtoDisenos_iddiseno[$item] = $regPdtoDiseno->iddiseno;
          $pdtoDisenos_diseno[$item] = $regPdtoDiseno->diseno;
          $pdtoDisenos_cdgdiseno[$item] = $regPdtoDiseno->cdgdiseno;
          $pdtoDisenos_sttdiseno[$item] = $regPdtoDiseno->sttdiseno; }

        $nDisenos = $item; }
      // Final del filtro de registros 
    }

    echo '
      <div class="bloque">
        <form id="formPdtoDiseno" name="formPdtoDiseno" method="POST" action="pdtoDiseno.php">
          <article class="subbloque">
            <label class="modulo_nombre">Diseños</label>
          </article>
          <input type="checkbox" id="chckVerTodo" name="chckVerTodo" onclick="document.formPdtoDiseno.submit()" '.$vertodo.'><label>ver todo</label>
          <a href="ayuda.php#Disenos">'.$_help_blue.'</a>

          <section class="subbloque">
            <article>
              <label>Código</label><br>
              <input type="text" id="textIdDiseno" name="textIdDiseno" value="'.$pdtoDiseno_iddiseno.'" autofocus="autofocus" required/>
            </article>
            
            <article>
              <label>Descripción</label><br>
              <input type="text" id="textDiseno" name="textDiseno" value="'.$pdtoDiseno_diseno.'" required/>
            </article>
            
            <article>
              <label>Consumos</label><br>
              <input type="checkbox" id="chckConsumo" name="chckConsumo" /><label>Predeterminados</label>
            </article>

            <article><br>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>
          </section>
        </form>
      </div>';

    if ($nDisenos > 0)
    { echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Catálogo de diseños</label>
        </article>
        <label><b>'.$nDisenos.'</b> Encontrado(s)</label>';

      for ($item=1; $item<=$nDisenos; $item++)
      { echo '
        <section class="listado">
          <article>';

        if ((int)$pdtoDisenos_sttdiseno[$item] > 0)
        { echo '
            <a href="pdtoDiseno.php?cdgdiseno='.$pdtoDisenos_cdgdiseno[$item].'">'.$_search.'</a>
            <a href="pdtoImpresion.php?cdgdiseno='.$pdtoDisenos_cdgdiseno[$item].'">'.$_tag.'</a>
            <a href="pdtoConsumo.php?cdgdiseno='.$pdtoDisenos_cdgdiseno[$item].'">'.$_puzzle.'</a>
            <a href="pdtoDiseno.php?cdgdiseno='.$pdtoDisenos_cdgdiseno[$item].'&proceso=update">'.$_power_blue.'</a>';
        } else
        { echo '
            <a href="pdtoDiseno.php?cdgdiseno='.$pdtoDisenos_cdgdiseno[$item].'&proceso=delete">'.$_recycle_bin.'</a>
            <a href="pdtoDiseno.php?cdgdiseno='.$pdtoDisenos_cdgdiseno[$item].'&proceso=update">'.$_power_black.'</a>'; }

        echo '
          </article>
          <article>
            <label class="textId"><b>'.$pdtoDisenos_iddiseno[$item].'</b></label>
            <label>'.$pdtoDisenos_proyecto[$item].'</label><br/>
            <label class="textNombre">'.$pdtoDisenos_diseno[$item].'</label>
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
