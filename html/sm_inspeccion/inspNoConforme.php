<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="/css/2014.css" />   
  </head>
  <body>
    <div id="contenedor">
<?php
  include '../datos/mysql.php';
  $link = conectar();

  m3nu_inspeccion();

  $sistModulo_cdgmodulo = '50090';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);
    
    if ($_GET['mode']=='logout') { cl0s3(); }

    if ($_POST['textusername'] AND $_POST['textpassword']) { val1dat3($_POST['textusername'], $_POST['textpassword']); }

    if ($_SESSION['cdgusuario'])
    { ma1n(); }
    else
    { echo '
      <div id="loginform">
        <form id="login" action="inspNoConforme.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 

      exit; }  

    if ($_POST['text_codigo'])
    { $inspNoConforme_codigo = $_POST['text_codigo']; }

    if ($_POST['btn_buscar'])
    { $prodLoteSelect = $link->query("
        SELECT prodlote.noop,
               prodlote.cdglote,
               pdtoimpresion.impresion,
               pdtoimpresion.cdgimpresion
          FROM prodlote,
               pdtoimpresion
        WHERE (prodlote.cdglote = '".$inspNoConforme_codigo."' OR 
               prodlote.noop = '".$inspNoConforme_codigo."') AND
              (prodlote.cdgproducto = pdtoimpresion.cdgimpresion)");

      if ($prodLoteSelect->num_rows > 0)
      { $regProdLote = $prodLoteSelect->fetch_object();

        $inspNoConforme_noop = $regProdLote->noop;
        $inspNoConforme_producto = $regProdLote->impresion;
        $inspNoConforme_cdgproducto = $regProdLote->cdgimpresion;
        $inspNoConforme_cdglote = $regProdLote->cdglote; 
      } else
      { $prodBobinaSelect = $link->query("
          SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina) AS noop,
                 prodbobina.cdgbobina,
                 prodlote.cdglote,
                 pdtoimpresion.impresion,
                 pdtoimpresion.cdgimpresion
            FROM prodbobina,
                 prodlote,
                 pdtoimpresion
          WHERE (prodbobina.cdglote = prodlote.cdglote) AND
                (prodbobina.cdgbobina = '".$inspNoConforme_codigo."' OR 
          CONCAT(prodlote.noop,'-',prodbobina.bobina) = '".$inspNoConforme_codigo."') AND
                (prodlote.cdgproducto = pdtoimpresion.cdgimpresion)");

        if ($prodBobinaSelect->num_rows > 0)
        { $regProdBobina = $prodBobinaSelect->fetch_object();

          $inspNoConforme_noop = $regProdBobina->noop;
          $inspNoConforme_producto = $regProdBobina->impresion;
          $inspNoConforme_cdgproducto = $regProdBobina->cdgimpresion;
          $inspNoConforme_cdglote = $regProdBobina->cdglote;
          $inspNoConforme_cdgbobina = $regProdBobina->cdgbobina; 
        } else
        { $prodRolloSelect = $link->query("
            SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) AS noop,
                   prodrollo.cdgrollo,
                   prodbobina.cdgbobina,
                   prodlote.cdglote,
                   pdtoimpresion.impresion,
                   pdtoimpresion.cdgimpresion
              FROM prodrollo,
                   prodbobina,
                   prodlote,
                   pdtoimpresion
            WHERE (prodrollo.cdgbobina = prodbobina.cdgbobina AND
                   prodbobina.cdglote = prodlote.cdglote) AND
                  (prodrollo.cdgrollo = '".$inspNoConforme_codigo."' OR 
            CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) = '".$inspNoConforme_codigo."') AND
                  (prodlote.cdgproducto = pdtoimpresion.cdgimpresion)"); 

          if ($prodRolloSelect->num_rows > 0)
          { $regProdRollo = $prodRolloSelect->fetch_object();

            $inspNoConforme_noop = $regProdRollo->noop;
            $inspNoConforme_producto = $regProdRollo->impresion;
            $inspNoConforme_cdgproducto = $regProdRollo->cdgimpresion;
            $inspNoConforme_cdglote = $regProdRollo->cdglote;
            $inspNoConforme_cdgbobina = $regProdRollo->cdgbobina;
            $inspNoConforme_cdgrollo = $regProdRollo->cdgrollo; 
          } else
          { $prodPaqueteSelect = $link->query("
              SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo,'-',prodpaquete.paquete) AS noop,
                     prodpaquete.cdgpaquete,
                     prodrollo.cdgrollo,
                     prodbobina.cdgbobina,
                     prodlote.cdglote,
                     pdtoimpresion.impresion,
                     pdtoimpresion.cdgimpresion
                FROM prodpaquete,
                     prodrollo,
                     prodbobina,
                     prodlote,
                     pdtoimpresion
              WHERE (prodpaquete.cdgrollo = prodrollo.cdgrollo AND
                     prodrollo.cdgbobina = prodbobina.cdgbobina AND
                     prodbobina.cdglote = prodlote.cdglote) AND
                    (prodpaquete.cdgpaquete = '".$inspNoConforme_codigo."' OR 
              CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo,'-',prodpaquete.paquete) = '".$inspNoConforme_codigo."') AND
                    (prodlote.cdgproducto = pdtoimpresion.cdgimpresion)");

            if ($prodPaqueteSelect->num_rows > 0)
            { $regProdPaquete = $prodPaqueteSelect->fetch_object();

              $inspNoConforme_noop =  $regProdPaquete->noop;
              $inspNoConforme_producto = $regProdPaquete->impresion;
              $inspNoConforme_cdgproducto = $regProdPaquete->cdgimpresion;
              $inspNoConforme_cdglote = $regProdPaquete->cdglote;
              $inspNoConforme_cdgbobina = $regProdPaquete->cdgbobina;
              $inspNoConforme_cdgrollo = $regProdPaquete->cdgrollo;
              $inspNoConforme_cdgpaquete = $regProdPaquete->cdgpaquete; }
          }
        }
      }
    }

    if ($_POST['btn_desactivar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { // -> Paquetes No Conformes
        $prodPaqueteSelect = $link->query("
          SELECT * FROM prodpaquete
           WHERE cdgpaquete = '".$inspNoConforme_codigo."'");

        if ($prodPaqueteSelect->num_rows > 0)
        { $link->query("
            UPDATE prodpaquete
               SET sttpaquete = 'C',
                   obs = CONCAT('Sobrante/Merma ',fchmovimiento,' ".$_POST['txt_observacion']." ', 'baja: ',NOW(), obs)
             WHERE cdgpaquete = '".$inspNoConforme_codigo."' AND
                  (sttpaquete != '9' AND sttpaquete != 'C') AND
                   cdgempaque = ''");

          if ($link->affected_rows > 0)
          { $link->query("
              INSERT INTO prodpaqueteope
               (cdgpaquete, cdgoperacion, cdgempleado, cdgmaquina, fchoperacion, fchmovimiento)
              VALUES
               ('".$inspNoConforme_codigo."', '50000C', '".$_SESSION['cdgusuario']."', '0000C', '".$date('Y-m-d')."', NOW())");

            echo $msg_alert = 'Paquete desactivado'; 
          } else
          { echo $msg_alert = 'Paquete previamente desactivado'; }
        } // <- Paquetes No Conformes

        // -> Rollos No Conformes
        $prodRolloSelect = $link->query("
          SELECT * FROM prodrollo
           WHERE cdgrollo = '".$inspNoConforme_codigo."'");

        if ($prodRolloSelect->num_rows > 0)
        { $link->query("
            UPDATE prodrollo
               SET sttrollo = 'C',
                   obs = CONCAT('Merma ',fchmovimiento,' ".$_POST['txt_observacion']." ', 'baja: ',NOW())
             WHERE cdgrollo = '".$inspNoConforme_codigo."' AND
                  (sttrollo != '5' AND sttrollo != '9' AND sttrollo != 'C') AND
                   cdgempaque = ''");

          if ($link->affected_rows > 0)
          { $link->query("
              INSERT INTO prodrolloope
               (cdgrollo, cdgoperacion, cdgempleado, cdgmaquina, fchoperacion, fchmovimiento)
              VALUES
               ('".$inspNoConforme_codigo."', '40000C', '".$_SESSION['cdgusuario']."', '0000C', '".date('Y-m-d')."', NOW())");

            echo $msg_alert = 'Rollo desactivado'; 
          } else
          { echo $msg_alert = 'Rollo previamente desactivado'; }
        } // <- Rollos No Conformes

        // -> Bobinas No Conformes
        $prodBobinaSelect = $link->query("
          SELECT * FROM prodbobina
           WHERE cdgbobina = '".$inspNoConforme_codigo."'");

        if ($prodBobinaSelect->num_rows > 0)
        { $link->query("
            UPDATE prodbobina
               SET sttbobina = 'C',
                   obs = CONCAT('Merma ',fchmovimiento,' ".$_POST['txt_observacion']." ', 'baja: ',NOW())
             WHERE cdgbobina = '".$inspNoConforme_codigo."' AND
                  (sttbobina != '9' AND sttbobina != 'C')");

          if ($link->affected_rows > 0)
          { $link->query("
              INSERT INTO prodbobinaope
               (cdgbobina, cdgoperacion, cdgempleado, cdgmaquina, fchoperacion, fchmovimiento)
              VALUES
               ('".$inspNoConforme_codigo."', '30000C', '".$_SESSION['cdgusuario']."', '0000C', '".date('Y-m-d')."', NOW())");

            echo $msg_alert = 'Bobina desactivada'; 
          } else
          { echo $msg_alert = 'Bobina previamente desactivada'; }
        } // <- Bobinas No Conformes
        
        // -> Lotes No Conformes
        $prodLoteSelect = $link->query("
          SELECT * FROM prodlote
           WHERE cdglote = '".$inspNoConforme_codigo."'");

        if ($prodLoteSelect->num_rows > 0)
        { $link->query("
            UPDATE prodlote
               SET sttlote = 'C',
                   obs = CONCAT('Merma ',fchmovimiento,' ".$_POST['txt_observacion']." ', 'baja: ',NOW())
             WHERE cdglote = '".$inspNoConforme_codigo."' AND
                  (sttlote != '9' AND sttlote != 'C'");

          if ($link->affected_rows > 0)
          { $link->query("
              INSERT INTO prodloteope
               (cdglote, cdgoperacion, cdgempleado, cdgmaquina, fchoperacion, fchmovimiento)
              VALUES
               ('".$inspNoConforme_codigo."', '20000C', '".$_SESSION['cdgusuario']."', '0000C', '".date('Y-m-d')."', NOW())");

            echo $msg_alert = 'Lote desactivado'; 
          } else
          { echo $msg_alert = 'Lote previamente desactivado'; }
        } // <- Lotes No Conformes
      } else
      { $msg_alert = $msg_norewrite; }
    } 

    echo '
      <div class="bloque">
        <form id="formulario" name="formulario" method="post" action="inspNoConforme.php"/>
          <article class="subbloque">
            <label class="modulo_nombre">Producto No Conforme</label>
          </article>
          <!--<a href="ayuda.php#NoConforme"><img id="imagen_ayuda" src="/img_sistema/help_blue.png"/></a>-->
          <section class="subbloque">
            <article>
              <label>Código</label><br/>
              <input type="text" id="text_codigo" name="text_codigo" value="'.$inspNoConforme_codigo.'" autofocus required /></td></tr>
            </article>
            
            <article>';

    if ($_POST['btn_buscar'])
    { echo '
              <input type="submit" id="btn_desactivar" name="btn_desactivar" value="Desactivar"/>'; 
    } else
    { echo '
              <input type="submit" id="btn_buscar" name="btn_buscar" value="Buscar"/>'; }
    echo '
            </article>
          </section>';

    if ($_POST['btn_buscar'])
    { echo '
          <section class="subbloque">
            <article>
              <label class="modulo_listado">NoOP <b>'.$inspNoConforme_noop.'</b><br>
                Producto <b><a href="inspBuscador.php?cdgproducto='.$inspNoConforme_cdgproducto.'">'.$inspNoConforme_producto.'</a></b><br>
            </article>
          </section>'; } 

    echo '
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
