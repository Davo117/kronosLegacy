<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Armado de embarques</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
<?php

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_ventas();

  $sistModulo_cdgmodulo = '40211';
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
        <form id="login" action="vntsEmbarqueMaker.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 

      exit; }

    // Si cuento con un código de empaque
    if ($_GET['cdgempaque'])
    { // Si el proceso me indica descargar
      if ($_GET['proceso'] == 'download')
      { if (substr($sistModulo_permiso,0,3) == 'rwx')
        { // Busco el empaque
          $alptEmpaqueSelect = $link->query("
            SELECT * FROM alptempaque 
             WHERE cdgempaque = '".$_GET['cdgempaque']."'");

          // Si encuentro el empaque
          if ($alptEmpaqueSelect->num_rows > 0)
          { $regAlptEmpaque = $alptEmpaqueSelect->fetch_object();

            // Verifico que el embarque pueda ser modificado
            $vntsSurtidoSelect = $link->query("
              SELECT * FROM vntsembarque
               WHERE cdgembarque = '".$regAlptEmpaque->cdgembarque."' AND
                     cdglote = ''");

            // Si el embarque puede ser modificado
            if ($vntsSurtidoSelect->num_rows > 0)
            { // Actualizo el empaque liberandolo del embarque
              $link->query("
                UPDATE alptempaque
                   SET cdgembarque = '',
                       sttempaque = '1'
                 WHERE cdgempaque = '".$_GET['cdgempaque']."' AND
                       cdgembarque = '".$regAlptEmpaque->cdgembarque."'"); 
            } else
            { // Notifico por medio de mensaje en pantalla que el empaque no puede ser liberado.
              $msg_alert .= "El embarque no puede ser modificado, ya fue ligado una confirmación/pedido."; } 
          } else
          { // Notifico por medio de mensaje en pantalla que el empaque no fue encontrado.
            $msg_alert .= "Empaque no encontrado."; }
        } else
        {  $msg_alert = 'No cuentas con permisos para modificar embarques.'; }
      } // Fin del proceso de descarga
    } //Fin de la validación para código de empaque

    // Si cuento con un código de embarque
    if ($_GET['cdgembarque'])
    { // Capturo los valores de embarque, producto y tipo de empaque en variables globales
      $_SESSION['vntsembarque_cdgembarque'] = $_GET['cdgembarque'];
      $_SESSION['vntsembarque_cdgproducto'] = $_GET['cdgproducto'];
      $_SESSION['vntsembarque_cdgtpoempaque'] = $_GET['cdgtpoempaque']; }

    // Empaque disponbles
      $alptEmpaqueSelect = $link->query("
        SELECT alptempaque.noempaque,
               alptempaque.tpoempaque,
               pdtoimpresion.impresion,
           SUM(alptempaquer.cantidad) AS cantidad,
           SUM(prodrollo.longitud) AS longitud, 
           SUM(prodrollo.peso) AS peso,
               alptempaque.cdgempaque,
               alptempaque.peso AS pesob
          FROM alptempaque,
               alptempaquer,
               prodrollo,               
               pdtoimpresion
         WHERE alptempaque.cdgproducto = '".$_SESSION['vntsembarque_cdgproducto']."' AND
               alptempaque.cdgempaque = alptempaquer.cdgempaque AND
               alptempaquer.cdgrollo = prodrollo.cdgrollo AND
               prodrollo.cdgproducto = pdtoimpresion.cdgimpresion AND               
               alptempaque.sttempaque = '1'
      GROUP BY alptempaque.noempaque,
               alptempaque.tpoempaque,
               pdtoimpresion.impresion
      ORDER BY pdtoimpresion.impresion,
               alptempaque.noempaque");
    
      $nEmpaquesQ = 0;
      if ($alptEmpaqueSelect->num_rows > 0)
      { $item = 0;

        while ($regVntsEmpaque = $alptEmpaqueSelect->fetch_object())
        { $item++;

          $vntsEmbarqueQD_noempaque[$item] = $regVntsEmpaque->tpoempaque.$regVntsEmpaque->noempaque;
          $vntsEmbarqueQD_producto[$item] = $regVntsEmpaque->impresion;
          $vntsEmbarqueQD_cantidad[$item] = $regVntsEmpaque->cantidad;
          $vntsEmbarqueQD_longitud[$item] = $regVntsEmpaque->longitud;
          $vntsEmbarqueQD_peso[$item] = $regVntsEmpaque->peso;
          $vntsEmbarqueQD_pesob[$item] = $regVntsEmpaque->pesob;
          $vntsEmbarqueQD_cdgempaque[$item] = $regVntsEmpaque->cdgempaque;

          $vntsEmbarque_disponibleQ += $regVntsEmpaque->cantidad; }

        $nEmpaquesQ = $alptEmpaqueSelect->num_rows; }

      $alptEmpaqueSelect = $link->query("
        SELECT alptempaque.noempaque,
               alptempaque.tpoempaque,
               pdtoimpresion.impresion,
           SUM(prodpaquete.cantidad) AS cantidad,
               alptempaque.peso,
               alptempaque.cdgempaque
          FROM alptempaque,
               alptempaquep,
               prodpaquete,
               pdtoimpresion
         WHERE alptempaque.cdgproducto = '".$_SESSION['vntsembarque_cdgproducto']."' AND
               alptempaque.cdgempaque = alptempaquep.cdgempaque AND
               alptempaquep.cdgpaquete = prodpaquete.cdgpaquete AND
               prodpaquete.cdgproducto = pdtoimpresion.cdgimpresion AND
               alptempaque.sttempaque = '1'
      GROUP BY alptempaque.noempaque,
               alptempaque.tpoempaque,
               pdtoimpresion.impresion
      ORDER BY pdtoimpresion.impresion,
               alptempaque.noempaque");
    
      $nEmpaquesC = 0;
      if ($alptEmpaqueSelect->num_rows > 0)
      { $item = 0;

        while ($regVntsEmpaque = $alptEmpaqueSelect->fetch_object())
        { $item++;

          $vntsEmbarqueCD_noempaque[$item] = $regVntsEmpaque->tpoempaque.$regVntsEmpaque->noempaque;
          $vntsEmbarqueCD_producto[$item] = $regVntsEmpaque->impresion;           
          $vntsEmbarqueCD_cantidad[$item] = number_format($regVntsEmpaque->cantidad,3);
          $vntsEmbarqueCD_peso[$item] = $regVntsEmpaque->peso;
          $vntsEmbarqueCD_cdgempaque[$item] = $regVntsEmpaque->cdgempaque;

          $vntsEmbarque_disponibleC += $regVntsEmpaque->cantidad; }

        $nEmpaquesC = $alptEmpaqueSelect->num_rows; }

    // Fin de empaque disponibles

    if ($_POST['bttnSalvar'])
    { $vntsSurtidoSelect = $link->query("
        SELECT * FROM vntsembarque
         WHERE cdgembarque = '".$_SESSION['vntsembarque_cdgembarque']."' AND 
               cdglote != ''");

      if ($vntsSurtidoSelect->num_rows > 0)
      { $msg_alert = "El embarque no puede ser modificado, ya fue ligado una orden de compra.";
      } else
      { for ($item=1; $item<=$nEmpaquesQ; $item++)
        { if (isset($_REQUEST['chk_'.$vntsEmbarqueQD_cdgempaque[$item]]))
          { $link->query("
              UPDATE alptempaque
                 SET cdgembarque = '".$_SESSION['vntsembarque_cdgembarque']."',
                     sttempaque = 'E'
               WHERE cdgempaque = '".$vntsEmbarqueQD_cdgempaque[$item]."'");
          }
        }

        for ($item=1; $item<=$nEmpaquesC; $item++)
        { if (isset($_REQUEST['chk_'.$vntsEmbarqueCD_cdgempaque[$item]]))
          { $link->query("
              UPDATE alptempaque
                 SET cdgembarque = '".$_SESSION['vntsembarque_cdgembarque']."',
                     sttempaque = 'E'
               WHERE cdgempaque = '".$vntsEmbarqueCD_cdgempaque[$item]."'");
          } 
        } 
      }
    } 

    if (substr($sistModulo_permiso,0,1) == 'r')
    { $vntsEmbarqueSelect = $link->query("
        SELECT vntsembarque.cdgembarque,
               vntssucursal.sucursal,
               vntsembarque.cdgproducto,
               vntsembarque.cdgempaque,
               vntsembarque.referencia,
               vntsembarque.fchembarque,
               vntsembarque.sttembarque,
               pdtoimpresion.impresion
          FROM vntsembarque,
               vntssucursal,
               pdtoimpresion
         WHERE vntsembarque.cdgembarque = '".$_SESSION['vntsembarque_cdgembarque']."' AND
               vntsembarque.cdgsucursal = vntssucursal.cdgsucursal AND
               vntsembarque.cdgproducto = pdtoimpresion.cdgimpresion");

      if ($vntsEmbarqueSelect->num_rows > 0)
      { $regVntsEmbarque = $vntsEmbarqueSelect->fetch_object();

        $vntsEmbarque_cdgembarque = $regVntsEmbarque->cdgembarque;
        $vntsEmbarque_sucursal = $regVntsEmbarque->sucursal;
        $vntsEmbarque_cdgproducto = $regVntsEmbarque->cdgproducto;
        $vntsEmbarque_cdgtpoempaque = $regVntsEmbarque->cdgempaque;
        $vntsEmbarque_referencia = $regVntsEmbarque->referencia;
        $vntsEmbarque_fchembarque = $regVntsEmbarque->fchembarque;          
        $vntsEmbarque_sttembarque = $regVntsEmbarque->sttembarque;
        $vntsEmbarque_producto = $regVntsEmbarque->impresion;

        $alptEmpaqueSelect = $link->query("
          SELECT alptempaque.noempaque,
                 alptempaque.tpoempaque,
                 pdtoimpresion.impresion,
             SUM(alptempaquer.cantidad) AS cantidad,
             SUM(prodrollo.longitud) AS longitud, 
             SUM(prodrollo.peso) AS peso,
                 alptempaque.peso AS pesob,
                 alptempaque.cdgempaque
            FROM alptempaque,
                 alptempaquer,
                 prodrollo,                   
                 pdtoimpresion
           WHERE alptempaque.cdgembarque = '".$_SESSION['vntsembarque_cdgembarque']."' AND
                 alptempaque.cdgempaque = alptempaquer.cdgempaque AND
                 alptempaquer.cdgrollo = prodrollo.cdgrollo AND
                 prodrollo.cdgproducto = pdtoimpresion.cdgimpresion                   
        GROUP BY alptempaque.noempaque,
                 alptempaque.tpoempaque,
                 pdtoimpresion.impresion
        ORDER BY pdtoimpresion.impresion,
                 alptempaque.noempaque");

        $nEmpaquesQ = 0;
        if ($alptEmpaqueSelect->num_rows > 0)
        { $item = 0;

          while ($regVntsEmpaque = $alptEmpaqueSelect->fetch_object())
          { $item++;
            $vntsEmbarqueQ_cantidadpqt = 0;
            
            $alptEmpaqueRSelect = $link->query("
              SELECT alptempaquer.nocontrol, 
                     alptempaquer.cdgrollo,
                     alptempaquer.cantidad,
                     prodrollo.longitud, 
                     prodrollo.peso, 
                     prodrollo.bandera
                FROM alptempaquer, 
                     prodrollo
               WHERE alptempaquer.cdgrollo = prodrollo.cdgrollo AND
                     alptempaquer.cdgempaque = '".$regVntsEmpaque->cdgempaque."'
            ORDER BY alptempaquer.nocontrol");
            
            while ($regAlptEmpaqueR = $alptEmpaqueRSelect->fetch_object())
            { $vntsEmbarqueQ_cantidadpqt += number_format($regAlptEmpaqueR->cantidad,3); }  

            $vntsEmbarqueQ_noempaque[$item] = $regVntsEmpaque->tpoempaque.$regVntsEmpaque->noempaque;
            $vntsEmbarqueQ_producto[$item] = $regVntsEmpaque->impresion;              
            $vntsEmbarqueQ_cantidad[$item] = $vntsEmbarqueQ_cantidadpqt;
            $vntsEmbarqueQ_longitud[$item] = $regVntsEmpaque->longitud;
            $vntsEmbarqueQ_peso[$item] = $regVntsEmpaque->peso;
            $vntsEmbarqueQ_pesob[$item] = $regVntsEmpaque->pesob;
            $vntsEmbarqueQ_cdgempaque[$item] = $regVntsEmpaque->cdgempaque;

            $vntsEmbarque_embarcadoQ += $vntsEmbarqueQ_cantidadpqt; }

          $nEmpaquesQ = $alptEmpaqueSelect->num_rows; }
      
        // Busca los empaques de caja ligados al embarque seleccionado
        $alptEmpaqueSelect = $link->query("
          SELECT alptempaque.noempaque,
                 alptempaque.tpoempaque,
                 pdtoimpresion.impresion,
                 alptempaque.peso,
             SUM(prodpaquete.cantidad) AS cantidad,
                 alptempaque.cdgempaque
            FROM alptempaque,
                 alptempaquep,
                 prodpaquete,
                 pdtoimpresion
           WHERE alptempaque.cdgembarque = '".$_SESSION['vntsembarque_cdgembarque']."' AND
                 alptempaque.cdgempaque = alptempaquep.cdgempaque AND
                 alptempaquep.cdgpaquete = prodpaquete.cdgpaquete AND
                 prodpaquete.cdgproducto = pdtoimpresion.cdgimpresion
        GROUP BY alptempaque.noempaque,
                 alptempaque.tpoempaque,
                 pdtoimpresion.impresion
        ORDER BY pdtoimpresion.impresion,
                 alptempaque.noempaque");

        $nEmpaquesC = 0;
        if ($alptEmpaqueSelect->num_rows > 0)
        { $item = 0;

          while ($regVntsEmpaque = $alptEmpaqueSelect->fetch_object())
          { $item++;

            $vntsEmbarqueC_noempaque[$item] = $regVntsEmpaque->tpoempaque.$regVntsEmpaque->noempaque;
            $vntsEmbarqueC_producto[$item] = $regVntsEmpaque->impresion;            
            $vntsEmbarqueC_peso[$item] = $regVntsEmpaque->peso;
            $vntsEmbarqueC_cantidad[$item] = $regVntsEmpaque->cantidad;
            $vntsEmbarqueC_cdgempaque[$item] = $regVntsEmpaque->cdgempaque; 

            $vntsEmbarque_embarcadoC += $regVntsEmpaque->cantidad; }

          $nEmpaquesC = $alptEmpaqueSelect->num_rows; }
        // Fin de la búsqueda
      } else
      { $msg_alert = 'Es necesario indicar un código de embarque valido...'; }
    }  else
    { $msg_alert = 'No cuentas con permisos de lectura.'; }

    echo '
    <form id="form_ventas" name="form_ventas" method="POST" action="vntsEmbarqueMaker.php" />
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_nombre">Armado de embarques</label>
        </article>

        <section class="subbloque">
          <article>
            <label>Embarque</label><br/>
            <label><strong><a href="vntsEmbarque.php?cdgembarque='.$vntsEmbarque_cdgembarque.'">'.$vntsEmbarque_cdgembarque.'</a></strong></label>
          </article>

          <article>
            <label>Producto</label><br/>
            <label><strong>'.$vntsEmbarque_producto.'</strong></label>
          </article>

          <article>
            <label>Sucursal</label><br/>
            <label><strong>'.$vntsEmbarque_sucursal.'</strong></label>
          </article>

          <article>
            <label>Fecha embarque</label><br/>
            <label><strong>'.$vntsEmbarque_fchembarque.'</strong></label>
          </article>

          <article>
            <label>Referencia</label><br/>
            <label><strong>'.$vntsEmbarque_referencia.'&nbsp;</strong></label>
          </article><br/>

          <article><br/>
            <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
          </article>
        </section>
      </div>';

    if ($nEmpaquesQ > 0 AND $_SESSION['vntsembarque_cdgtpoempaque'] == 'Q')
    { echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Contenido del embarque</label>
        </article>
        <label><strong>'.$nEmpaquesQ.'</strong> Empaque(s)</label>
        <label><strong>'.number_format($vntsEmbarque_embarcadoQ,3).'</strong> millares</label>';

      for ($item=1; $item<=$nEmpaquesQ; $item++)
      { echo '
        <section class="listado">
          <article style="vertical-align:top">
            <a href="vntsEmbarqueMaker.php?cdgempaque='.$vntsEmbarqueQ_cdgempaque[$item].'&proceso=download">'.$_recycle_bin.'</a>
          </article>

          <article>
            <article style="text-align:right">
              <label>Empaque</label><br/>
              <label>Producto</label>
            </article>

            <article>
              <label><strong>'.$vntsEmbarqueQ_noempaque[$item].'</strong></label><br/>
              <label><strong>'.$vntsEmbarqueQ_producto[$item].'</strong></label>
            </article>
          </article>

          <article>
            <article style="text-align:right">
              <label><strong>'.number_format($vntsEmbarqueQ_cantidad[$item],3).'</strong></label><br/>
              <label><strong>'.number_format($vntsEmbarqueQ_pesob[$item],3).'</strong></label>
            </article>

            <article>
              <label>Millares</label><br/>
              <label>Kilos</label>
            </article>
          </article> 
        </section>'; }

      echo '
      </div>'; }
      
    if ($nEmpaquesC > 0 AND $_SESSION['vntsembarque_cdgtpoempaque'] == 'C')
    { echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Contenido del embarque</label>
        </article>
        <label><strong>'.$nEmpaquesC.'</strong> Empaque(s)</label>
        <label><strong>'.number_format($vntsEmbarque_embarcadoC,3).'</strong> millares</label>';

      for ($item=1; $item<=$nEmpaquesC; $item++)
      { echo '
        <section class="listado">
          <article style="vertical-align:top">
            <a href="vntsEmbarqueMaker.php?cdgempaque='.$vntsEmbarqueC_cdgempaque[$item].'&proceso=download">'.$_recycle_bin.'</a>
          </article>

          <article>
            <article style="text-align:right">
              <label>Empaque</label><br/>
              <label>Producto</label>
            </article>

            <article>
              <label><strong>'.$vntsEmbarqueC_noempaque[$item].'</strong></label><br/>
              <label><strong>'.$vntsEmbarqueC_producto[$item].'</strong></label>
            </article>
          </article>

          <article>
            <article style="text-align:right">
              <label><strong>'.number_format($vntsEmbarqueC_cantidad[$item],3).'</strong></label><br/>
              <label><strong>'.number_format($vntsEmbarqueC_peso[$item],3).'</strong></label>
            </article>

            <article>
              <label>Millares</label><br/>
              <label>Kilos</label>
            </article>
          </article>          
        </section>'; }

      echo '
      </div>'; }

    if ($_SESSION['vntsembarque_cdgtpoempaque'] == 'Q')
    { $alptEmpaqueSelect = $link->query("
        SELECT alptempaque.noempaque,
               alptempaque.tpoempaque,
               pdtoimpresion.impresion,
           SUM(alptempaquer.cantidad) AS cantidad,
           SUM(prodrollo.longitud) AS longitud, 
           SUM(prodrollo.peso) AS peso,
               alptempaque.cdgempaque,
               alptempaque.peso AS pesob
          FROM alptempaque,
               alptempaquer,
               prodrollo,               
               pdtoimpresion
         WHERE alptempaque.cdgproducto = '".$vntsEmbarque_cdgproducto."' AND
               alptempaque.cdgempaque = alptempaquer.cdgempaque AND
               alptempaquer.cdgrollo = prodrollo.cdgrollo AND
               prodrollo.cdgproducto = pdtoimpresion.cdgimpresion AND               
               alptempaque.sttempaque = '1'
      GROUP BY alptempaque.noempaque,
               alptempaque.tpoempaque,
               pdtoimpresion.impresion
      ORDER BY pdtoimpresion.impresion,
               alptempaque.noempaque");
    
      $nEmpaquesQ = 0;
      if ($alptEmpaqueSelect->num_rows > 0)
      { $item = 0;
        $vntsEmbarque_disponibleQ = 0;

        while ($regVntsEmpaque = $alptEmpaqueSelect->fetch_object())
        { $item++;

          $vntsEmbarqueQD_noempaque[$item] = $regVntsEmpaque->tpoempaque.$regVntsEmpaque->noempaque;
          $vntsEmbarqueQD_producto[$item] = $regVntsEmpaque->impresion;
          $vntsEmbarqueQD_cantidad[$item] = $regVntsEmpaque->cantidad;
          $vntsEmbarqueQD_longitud[$item] = $regVntsEmpaque->longitud;
          $vntsEmbarqueQD_peso[$item] = $regVntsEmpaque->peso;
          $vntsEmbarqueQD_pesob[$item] = $regVntsEmpaque->pesob;
          $vntsEmbarqueQD_cdgempaque[$item] = $regVntsEmpaque->cdgempaque;

          $vntsEmbarque_disponibleQ += $regVntsEmpaque->cantidad; }

        $nEmpaquesQ = $alptEmpaqueSelect->num_rows; }

      if ($nEmpaquesQ > 0)
      { echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Empaques disponibles</label>
        </article>
        <label><strong>'.$nEmpaquesQ.'</strong> Empaque(s)</label>
        <label><strong>'.number_format($vntsEmbarque_disponibleQ,3).'</strong> millares</label>';

        for ($item=1; $item<=$nEmpaquesQ; $item++)
        { $vntsEmbarque_acumuladoQ += $vntsEmbarqueQD_cantidad[$item];

          echo '
        <section class="listado">
          <article style="vertical-align:top">
            <input type="checkbox" id="chk_'.$vntsEmbarqueQD_cdgempaque[$item].'" name="chk_'.$vntsEmbarqueQD_cdgempaque[$item].'" '.$chkbox[$vntsEmpaqueQD_cdgempaque[$item]].'/>
          </article>

          <article>
            <article style="text-align:right">
              <label>Empaque</label><br/>
              <label>Producto</label>
            </article>

            <article>
              <label><strong>'.$vntsEmbarqueQD_noempaque[$item].'</strong></label><br/>
              <label><strong>'.$vntsEmbarqueQD_producto[$item].'</strong></label>
            </article>
          </article>

          <article>
            <article style="text-align:right">
              <label><strong>'.number_format($vntsEmbarqueQD_cantidad[$item],3).'</strong></label><br/>
              <label><strong>'.number_format($vntsEmbarqueQD_pesob[$item],3).'</strong></label>
            </article>

            <article>
              <label>Millares</label><br/>
              <label>Kilos</label>
            </article>
          </article>

          <article>
            <label>Acumulado</label><br/>
            <label><strong>'.number_format($vntsEmbarque_acumuladoQ,3).'</strong> millares</label>
          </article>          
        </section>'; }

        echo '
      </div>'; }    
    }

    if ($_SESSION['vntsembarque_cdgtpoempaque'] == 'C')
    { $alptEmpaqueSelect = $link->query("
        SELECT alptempaque.noempaque,
               alptempaque.tpoempaque,
               pdtoimpresion.impresion,
           SUM(prodpaquete.cantidad) AS cantidad,
               alptempaque.peso,
               alptempaque.cdgempaque
          FROM alptempaque,
               alptempaquep,
               prodpaquete,
               pdtoimpresion
         WHERE alptempaque.cdgproducto = '".$vntsEmbarque_cdgproducto."' AND
               alptempaque.cdgempaque = alptempaquep.cdgempaque AND
               alptempaquep.cdgpaquete = prodpaquete.cdgpaquete AND
               prodpaquete.cdgproducto = pdtoimpresion.cdgimpresion AND
               alptempaque.sttempaque = '1'
      GROUP BY alptempaque.noempaque,
               alptempaque.tpoempaque,
               pdtoimpresion.impresion
      ORDER BY pdtoimpresion.impresion,
               alptempaque.noempaque");
    
      $nEmpaquesC = 0;
      if ($alptEmpaqueSelect->num_rows > 0)
      { $item = 0;
        $vntsEmbarque_disponibleC = 0;

        while ($regVntsEmpaque = $alptEmpaqueSelect->fetch_object())
        { $item++;

          $vntsEmbarqueCD_noempaque[$item] = $regVntsEmpaque->tpoempaque.$regVntsEmpaque->noempaque;
          $vntsEmbarqueCD_producto[$item] = $regVntsEmpaque->impresion;           
          $vntsEmbarqueCD_cantidad[$item] = number_format($regVntsEmpaque->cantidad,3);
          $vntsEmbarqueCD_peso[$item] = $regVntsEmpaque->peso;
          $vntsEmbarqueCD_cdgempaque[$item] = $regVntsEmpaque->cdgempaque;

          $vntsEmbarque_disponibleC += $regVntsEmpaque->cantidad; }

        $nEmpaquesC = $alptEmpaqueSelect->num_rows; }

      if ($nEmpaquesC > 0)
      { echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Empaques disponibles</label>
        </article>
        <label><strong>'.$nEmpaquesC.'</strong> Empaque(s)</label>
        <label><strong>'.number_format($vntsEmbarque_disponibleC,3).'</strong> millares</label>';

        for ($item=1; $item<=$nEmpaquesC; $item++)
        { $vntsEmbarque_acumuladoC += $vntsEmbarqueCD_cantidad[$item];
          echo '
        <section class="listado">
          <article style="vertical-align:top">
            <input type="checkbox" id="chk_'.$vntsEmbarqueCD_cdgempaque[$item].'" name="chk_'.$vntsEmbarqueCD_cdgempaque[$item].'" '.$chkbox[$vntsEmpaqueCD_cdgempaque[$item]].'/>
          </article>

          <article>
            <article style="text-align:right">
              <label>Empaque</label><br/>
              <label>Producto</label>
            </article>

            <article>
              <label><strong>'.$vntsEmbarqueCD_noempaque[$item].'</strong></label><br/>
              <label><strong>'.$vntsEmbarqueCD_producto[$item].'</strong></label>
            </article>
          </article>

          <article>
            <article style="text-align:right">
              <label><strong>'.number_format($vntsEmbarqueCD_cantidad[$item],3).'</strong></label><br/>
              <label><strong>'.number_format($vntsEmbarqueCD_peso[$item],3).'</strong></label>
            </article>

            <article>
              <label>Millares</label><br/>
              <label>Kilos</label>
            </article>
          </article> 

          <article>
            <label>Acumulado</label><br/>
            <label><strong>'.number_format($vntsEmbarque_acumuladoC,3).'</strong> millares</label>
          </article>
        </section>'; }

        echo '
      </div>'; }        
    }    

      echo '  
    </form>';

    if ($msg_alert != '')
    { echo '
      <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }      
  } else
  { echo '
      <div align="center"><h1>Módulo no encontrado o bloqueado.</h1></div>'; }  
  
 ?>
    </div>
  </body>
</html>