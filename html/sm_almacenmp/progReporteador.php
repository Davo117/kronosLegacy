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

    if ($_POST['slctCdgProducto']) { $progReporteador_cdgproducto = $_POST['slctCdgProducto']; }
    if ($_POST['slctCdgLote']) { $progReporteador_cdglote = $_POST['slctCdgLote']; }
    if ($_POST['dateFchInicial']) { $progReporteador_fchinicial = $_POST['dateFchInicial']; }
    if ($_POST['dateFchFinal']) { $progReporteador_fchfinal = $_POST['dateFchFinal']; }

    $progReporteador_fchinicial = ValidarFecha($progReporteador_fchinicial);
    $progReporteador_fchfinal = ValidarFecha($progReporteador_fchfinal); 

    // Búsqueda de productos activos
    $pdtoImpresionSelect = $link->query("
      SELECT pdtoimpresion.idimpresion,
             pdtoimpresion.impresion,
             pdtoimpresion.cdgimpresion
        FROM pdtodiseno,
             pdtoimpresion
       WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
            (pdtoimpresion.sttimpresion = '1' AND
             pdtodiseno.sttdiseno = '1')
    ORDER BY pdtodiseno.proyecto,
             pdtoimpresion.periodo");

    if ($pdtoImpresionSelect->num_rows > 0)
    { $item = 0;
      
      while ($regPdtoImpresion = $pdtoImpresionSelect->fetch_object())
      { $item++;

        $pdtoImpresiones_idimpresion[$item] = $regPdtoImpresion->idimpresion;
        $pdtoImpresiones_impresion[$item] = $regPdtoImpresion->impresion;
        $pdtoImpresiones_cdgimpresion[$item] = $regPdtoImpresion->cdgimpresion; }

      $nImpresiones = $item; 
    } // Fin de la búsqueda de productos activos

    // Busqueda de ordenes de compra activas
    if ($progReporteador_cdgproducto != '')
    { $vntsOCLoteSelect = $link->query("
        SELECT vntsoc.oc,
               vntsoclote.idlote,
               vntssucursal.sucursal,
               vntsoclote.cdglote,
               vntsoclote.cdgproducto
          FROM vntsoc,
               vntsoclote,
               vntssucursal
         WHERE vntsoc.cdgoc = vntsoclote.cdgoc AND
               vntsoc.sttoc = '1' AND
               vntssucursal.cdgsucursal = vntsoc.cdgsucursal AND
               vntsoclote.cdgproducto = '".$progReporteador_cdgproducto."'
      ORDER BY vntsoc.cdgoc DESC,
               vntsoclote.cdglote"); 
    } else
    { $vntsOCLoteSelect = $link->query("
        SELECT vntsoc.oc,
               vntsoclote.idlote,
               vntssucursal.sucursal,
               vntsoclote.cdglote,
               vntsoclote.cdgproducto
          FROM vntsoc,
               vntsoclote,
               vntssucursal
         WHERE vntsoc.cdgoc = vntsoclote.cdgoc AND
               vntsoc.sttoc = '1' AND
               vntssucursal.cdgsucursal = vntsoc.cdgsucursal
      ORDER BY vntsoc.cdgoc DESC,
               vntsoclote.cdglote"); }

    if ($vntsOCLoteSelect->num_rows > 0)
    { $item = 0;

      while ($regVntsOCLote = $vntsOCLoteSelect->fetch_object())
      { $item++;

        $vntsOClotes_oc[$item] = $regVntsOCLote->oc;
        $vntsOClotes_idlote[$item] = $regVntsOCLote->idlote;
        $vntsOClotes_sucursal[$item] = $regVntsOCLote->sucursal;
        $vntsOClotes_cdglote[$item] = $regVntsOCLote->cdglote; }

      $nOCLotes = $item; 
    } // Fin de la busqueda de ordenes de compra activas 

    // Filtro de elementos
    $progElementoSelect = $link->query("
      SELECT progelemento.idelemento,
             progelemento.elemento,
             progelemento.cdgelemento,
             progunimed.idunimed
        FROM progelemento,
             progunimed
       WHERE progelemento.cdgunimed = progunimed.cdgunimed");

    if ($progElementoSelect->num_rows > 0)
    { while ($regProgElemento = $progElementoSelect->fetch_object())
      { $progReporteador_idelemento[$regProgElemento->cdgelemento] = $regProgElemento->idelemento;
        $progReporteador_elemento[$regProgElemento->cdgelemento] = $regProgElemento->elemento;
        $progReporteador_idunimed[$regProgElemento->cdgelemento] = $regProgElemento->idunimed; }
    } // Fin del filtro de elementos

    // Filtro de Pantones
    $pdtoPantoneSelect = $link->query("
      SELECT * FROM pdtopantone
    ORDER BY pantone");

    if ($pdtoPantoneSelect->num_rows > 0)
    { while ($regPdtoPantone = $pdtoPantoneSelect->fetch_object())
      { $progReporteador_idelemento[$regPdtoPantone->cdgpantone] = $regPdtoPantone->idpantone;
        $progReporteador_elemento[$regPdtoPantone->cdgpantone] = $regPdtoPantone->pantone;
        $progReporteador_idunimed[$regPdtoPantone->cdgpantone] = 'kgs'; }
    } // Fin del filtro de Pantones

    // Filtro de diseños
    $pdtoBandaSelect = $link->query("
      SELECT * FROM pdtobanda
    ORDER BY sttbanda DESC,
             idbanda");

    if ($pdtoBandaSelect->num_rows > 0)
    { while ($regPdtoBanda = $pdtoBandaSelect->fetch_object())
      { $progReporteador_idelemento[$regPdtoBanda->cdgbanda] = $regPdtoBanda->idbanda; 
        $progReporteador_elemento[$regPdtoBanda->cdgbanda] = $regPdtoBanda->banda; 
        $progReporteador_idunimed[$regPdtoBanda->cdgbanda] = 'mts'; }
    } // Final del filtro de bandas
    
    // Filtro de tipos de materia prima de acuerdo al proceso 001 Sello de seguridad  
    $sistTipoMPSelect = $link->query("
      SELECT sisttipomp.idtipomp,
             sisttipomp.tipomp,
             sisttipomp.cdgtipomp
        FROM sistproceso,
             progtipo,
             sisttipomp
      WHERE (sisttipomp.cdgtipomp = progtipo.cdgtipomp AND
             progtipo.cdgproceso = sistproceso.cdgproceso AND
             sistproceso.cdgproceso = '001')
    ORDER BY sisttipomp.idtipomp");

    if ($sistTipoMPSelect->num_rows > 0)
    { while ($regSistTipoMP = $sistTipoMPSelect->fetch_object())
      { $progReporteador_idelemento[$regSistTipoMP->cdgtipomp] = $regSistTipoMP->idtipomp;
        $progReporteador_elemento[$regSistTipoMP->cdgtipomp] = $regSistTipoMP->tipomp; 
        $progReporteador_idunimed[$regSistTipoMP->cdgtipomp] = 'kgs'; }
    }
    // Final del filtro de sustratos  

    // Generación del filtro
    if ($_POST['bttnEjecutar'])
    { if ($_POST['slctCdgProducto'])
      { if ($_POST['slctCdgLote'])
        { //Ver un producto de un pedido

          $progReporteadorSelect = $link->query("
            SELECT progrequeboom.cdgelemento,
               SUM(requerido) AS requerido
              FROM vntsoc,
                   vntsoclote,
                   progreque,
                   progrequeboom
            WHERE (vntsoc.cdgoc = vntsoclote.cdgoc AND
                   vntsoclote.cdglote = progreque.cdglote) AND                  
                  (progreque.cdgreque = progrequeboom.cdgreque AND
                   progreque.cdglote = progrequeboom.cdglote) AND
                  (vntsoclote.cdglote = '".$progReporteador_cdglote."' AND
                   vntsoclote.cdgproducto = '".$progReporteador_cdgproducto."')
          GROUP BY progrequeboom.cdgelemento
          ORDER BY CHAR_LENGTH(progrequeboom.cdgelemento)");         
        } else
        { //Ver un producto en todos los pedidos

          $progReporteadorSelect = $link->query("
            SELECT progrequeboom.cdgelemento,
               SUM(requerido) AS requerido
              FROM vntsoc,
                   vntsoclote,
                   progreque,
                   progrequeboom
            WHERE (vntsoc.cdgoc = vntsoclote.cdgoc AND
                   vntsoclote.cdglote = progreque.cdglote) AND
                  (vntsoclote.fchembarque BETWEEN '".$progReporteador_fchinicial."' AND '".$progReporteador_fchfinal."') AND
                  (progreque.cdgreque = progrequeboom.cdgreque AND
                   progreque.cdglote = progrequeboom.cdglote) AND
                  (vntsoclote.cdgproducto = '".$progReporteador_cdgproducto."')
          GROUP BY progrequeboom.cdgelemento
          ORDER BY CHAR_LENGTH(progrequeboom.cdgelemento)"); }
      } else
      { if ($_POST['slctCdgLote'])
        { //Ver pedido completo

          $progReporteadorSelect = $link->query("
            SELECT progrequeboom.cdgelemento,
               SUM(requerido) AS requerido
              FROM vntsoc,
                   vntsoclote,
                   progreque,
                   progrequeboom
            WHERE (vntsoc.cdgoc = vntsoclote.cdgoc AND
                   vntsoclote.cdglote = progreque.cdglote) AND
                  (progreque.cdgreque = progrequeboom.cdgreque AND
                   progreque.cdglote = progrequeboom.cdglote) AND
                  (vntsoclote.cdglote = '".$progReporteador_cdglote."')
          GROUP BY progrequeboom.cdgelemento
          ORDER BY CHAR_LENGTH(progrequeboom.cdgelemento)");        
        } else
        { //Ver productos y pedidos

          $progReporteadorSelect = $link->query("
            SELECT progrequeboom.cdgelemento,
               SUM(requerido) AS requerido
              FROM vntsoc,
                   vntsoclote,
                   progreque,
                   progrequeboom
            WHERE (vntsoc.cdgoc = vntsoclote.cdgoc AND
                   vntsoclote.cdglote = progreque.cdglote) AND
                  (vntsoclote.fchembarque BETWEEN '".$progReporteador_fchinicial."' AND '".$progReporteador_fchfinal."') AND
                  (progreque.cdgreque = progrequeboom.cdgreque AND
                   progreque.cdglote = progrequeboom.cdglote)
          GROUP BY progrequeboom.cdgelemento
          ORDER BY CHAR_LENGTH(progrequeboom.cdgelemento)"); }
      }

      if ($progReporteadorSelect->num_rows > 0)
      { $item = 0;

        while ($regProgElemento = $progReporteadorSelect->fetch_object())
        { $item++;

          $progReporteador_cdgelemento[$item] = $regProgElemento->cdgelemento;
          $progReporteador_requerido[$item] = $regProgElemento->requerido; }

        $nElementos = $progReporteadorSelect->num_rows; }
    } // Fin de la generación del filtro
    
    echo '
      <div class="bloque">
        <form id="formProgReporte" name="formProgReporte" method="POST" action="progReporteador.php" />
          <article class="subbloque">
            <label class="modulo_nombre">Explosión de materiales</label>
          </article>
          <a href="ayuda.php#report1">'.$_help_blue.'</a>

          <section class="subbloque">
            <article>
              <label><a href="../sm_producto/pdtoimpresion.php?cdgimpresion='.$progReporteador_cdgproducto.'">Producto</a></label><br/>
              <select id="slctCdgProducto" name="slctCdgProducto" onchange="document.formProgReporte.submit()"> 
               <option value="">*</option>';
      
    for ($item = 1; $item <= $nImpresiones; $item++) 
    { echo '
                <option value="'.$pdtoImpresiones_cdgimpresion[$item].'"';
              
      if ($progReporteador_cdgproducto == $pdtoImpresiones_cdgimpresion[$item]) 
      { echo ' selected="selected"'; }
      
      echo '>'.$pdtoImpresiones_impresion[$item].'</option>'; }

    echo '
              </select> 
            </article>

            <article>
              <label><a href="../sm_ventas/vntsOClote.php?cdglote='.$progReporteador_cdglote.'">Confirmación</a></label><br/>
              <select id="slctCdgLote" name="slctCdgLote" onchange="document.formProgReporte.submit()"> 
                <option value="">*</option>';

    for ($item = 1; $item <= $nOCLotes; $item++) 
    { echo '
                <option value="'.$vntsOClotes_cdglote[$item].'"';
          
      if ($progReporteador_cdglote == $vntsOClotes_cdglote[$item]) 
      { echo ' selected="selected"'; }

      echo '>'.$vntsOClotes_sucursal[$item].' ['.$vntsOClotes_oc[$item].'-'.$vntsOClotes_idlote[$item].']</option>'; }
    
    echo '
              </select>
            </article>

            <article>
              <label>Fecha inicial</label><br/>
              <input type="date" id="dateFchInicial" name="dateFchInicial" value="'.$progReporteador_fchinicial.'" required />
            </article>
            
            <article>
              <label>Fecha final</label><br/>
              <input type="date" id="dateFchFinal" name="dateFchFinal" value="'.$progReporteador_fchfinal.'" required /> 
            </article>

            <article><br/>
             <input type="submit" id="bttnEjecutar" name="bttnEjecutar" value="Ejecutar filtro" />
            </article>
          </section>
        </form>
      </div>';

    if ($nElementos > 0)
    { echo '<br/>
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Materiales requeridos</label>
        </article>
        <label><b>'.$nElementos.'</b> Encontrado(s)</label>

        <section class="listado">
          <table align="center">
            <thead>
              <tr>
                <td><label><strong>Código</strong></label></td>
                <td><label><strong>Descripción</strong></label></td>
                <td><label><strong>Requerido</strong></label></td>
                <td><label><strong><acronym title="Unidad de medida">U.M.</acronym></strong></label></td>
              </tr>
            </thead>

            <tbody>';

      for ($item=1; $item<=$nElementos; $item++)
      { echo '         
              <tr>
                <td><label><strong>'.$progReporteador_idelemento[$progReporteador_cdgelemento[$item]].'</strong></label></td>
                <td><label><em>'.$progReporteador_elemento[$progReporteador_cdgelemento[$item]].'</em></label></td>
                <td align="right"><label><strong>'.number_format($progReporteador_requerido[$item],4,'.',',').'</strong></label></td>
                <td><label><em>'.utf8_decode($progReporteador_idunimed[$progReporteador_cdgelemento[$item]]).'</em></label></td>
              </tr>'; }

      echo '
            </tbody>

            <tfoot>
              <tr><td colspan="4" align="right"><br /><a href="pdf/CO-RPT02.php?cdgproducto='.$progReporteador_cdgproducto.'&cdglote='.$progReporteador_cdglote.'&fchinicial='.$progReporteador_fchinicial.'&fchfinal='.$progReporteador_fchfinal.'" target="blank_"><strong>Imprimir reporte</strong></a></td></tr>
            </tfoot>
          </table>
        </section>
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