<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Liberación de lotes de materia prima</title>    
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
<?php

  include '../datos/mysql.php';
  $link = conectar();  

  m3nu_almacenmp();

  $sistModulo_cdgmodulo = '50030';
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
        <form id="login" action="pdtoBanda.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 
     
      exit; }

    if ($_POST['bttnBuscar'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $progLote_idlote = trim($_POST['textCdgLote']);

        $progLoteSelect = $link->query("
          SELECT progbloque.bloque,
                 proglote.lote,
                 pdtosustrato.sustrato,
                 pdtosustrato.anchura,
                 proglote.longitud,
                 proglote.peso,
                 proglote.cdglote
            FROM pdtosustrato,
                 progbloque,
                 proglote
          WHERE (progbloque.cdgbloque = proglote.cdgbloque) AND
                (progbloque.cdgsustrato = pdtosustrato.cdgsustrato) AND
                (proglote.lote = '".$progLote_idlote."' OR 
                 proglote.cdglote = '".$progLote_idlote."') AND
                 proglote.sttlote = '1'
        ORDER BY proglote.cdglote");

        if ($progLoteSelect->num_rows > 0)
        { if ($progLoteSelect->num_rows > 1)
          { $msg_alert = 'La Lote que buscas tiene mas de una coincidencia, favor de verificar la información del proveedor.'; 
          } else
          { $regProgLote = $progLoteSelect->fetch_object();
            
            $progLote_bloque = $regProgLote->bloque;
            $progLote_lote = $regProgLote->lote;            
            $progLote_sustrato = $regProgLote->sustrato;
            $progLote_anchura = $regProgLote->anchura;      
            $progLote_longitud = $regProgLote->longitud;
            $progLote_peso = $regProgLote->peso;
            $progLote_cdglote = $regProgLote->cdglote;
          }
        }else
        { $progLote_idlote = '';
          $msg_alert = 'Lote/Código de lote incorrecto o previamente liberado.'; }
      }
    }

    if ($_POST['bttnSalvar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { $progLote_idlote = trim($_POST['textCdgLote']);
        
        $progLoteSelect = $link->query("
          SELECT * FROM proglote
          WHERE (proglote.lote = '".$progLote_idlote."' OR 
                 proglote.cdglote = '".$progLote_idlote."') AND
                 proglote.sttlote = '1'"); 

        if ($progLoteSelect->num_rows > 0)
        { if ($progLoteSelect->num_rows > 1)
          { $msg_alert = 'El lote que buscas tiene mas de una coincidencia, favor de verificar la información del proveedor.'; 
          } else
          { $regProgLote = $progLoteSelect->fetch_object();

            $progLote_lote = '';
            $progLote_longitud = $regProgLote->longitud;            
            $progLote_cdglote = $regProgLote->cdglote;

            if (is_numeric($_POST['textPeso']))
            { $progLote_peso = $_POST['textPeso'];
              $fchoperacion = date('Y-m-d');

              $link->query("
                INSERT INTO prodloteope
                  (cdglote, cdgoperacion, cdgempleado, longitudfin, fchoperacion, fchmovimiento)
                VALUES
                  ('".$progLote_cdglote."', '00090', '".$_SESSION['cdgusuario']."', '".$progLote_longitud."', '".$fchoperacion."', NOW())");

              if ($link->affected_rows > 0) 
              { $msg_alert = 'Operacion registrada. \n'; 
                  
                $link->query("
                  UPDATE proglote
                     SET peso = '".$progLote_peso."',
                         fchmovimiento = NOW(),
                         sttlote = '7'
                   WHERE cdglote = '".$progLote_cdglote."'");

                if ($link->affected_rows > 0) 
                { $msg_alert .= 'Lote afectado y liberado.'; 
                } else
                { $msg_alert .= 'Lote liberado.'; }
              }
            } else
            { $progLote_peso = $_POST['textPeso'];
              $msg_alert = 'El peso debe ser numérico.'; }
          }
        } else
        { $msg_alert = 'Lote/Código de lote incorrecto.'; }
      }

      $progLote_idlote = '';      
    } //Salvar
    
    echo '
      <div class="bloque">
        <form id="formLiberacion" name="formLiberacion" method="POST" action="progLiberacion.php">
          <article class="subbloque">
            <label class="modulo_nombre">Liberación de lotes</label>
          </article>
          <a href="ayuda.php#Liberacion">'.$_help_blue.'</a>

          <section class="subbloque">
            <article>
              <label>Lote/Código de barras</label><br/>
              <input type="text" id="textCdgLote" name="textCdgLote" value="'.$progLote_lote.'" placeholder="identificador" required/>
            </article>';

    if ($progLote_idlote != '')
    { echo '
            <article>
              <label>Kilogramos</label><br>
              <input type="text" id="textPeso" name="textPeso" value="'.$progLote_peso.'" placeholder="kilogramos" required/>
            </article>

            <article><br/>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>
          </section>

          <section class="subbloque">
            <article>
              <article align="right">
                <label>Sustrato</label><br/>
                <label>Bloque</label><br/>
                <label>Lote</label><br/>           
                <label>Longitud</label><br/>
                <label>Anchura</label><br/>
                <label>Peso</label> 
              </article>

              <article>
                <label><b>'.$progLote_sustrato.'</b></label><br/>
                <label><b>'.$progLote_bloque.'</b></label><br/>
                <label><b>'.$progLote_lote.'</b></label><br/>        
                <label><b>'.$progLote_longitud.'</b> m</label><br/>
                <label><b>'.$progLote_anchura.'</b> mm</label><br/>
                <label><b>'.$progLote_peso.'</b> kg</label>
              </article>                 
            </article>

            <article style="vertical-align:top">
              <article align="right">
             
              </article>           
              
              <article>

              </article>             
            </article>'; 
    } else
    { echo '
            <article><br/>
              <input type="submit" id="bttnBuscar" name="bttnBuscar" value="Buscar" />
            </article>'; }
          
    echo '
          </section>
        </form>
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
