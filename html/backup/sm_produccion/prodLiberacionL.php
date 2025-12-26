<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><br/>
  <div style="position: absolute;left:20px"><?php

  include '../datos/mysql.php';
  
  // Código del moódulo
  $sistModulo_cdgmodulo = '50030';

  // Busqueda del módulo
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);   

    if ($sistModulo_permiso != '')
    { // Recepción de valores
      $liberaLote = $_POST['searchLote'];
      //.....................................

      if ($liberaLote != '')
      { if ($_POST['submitBuscar'] OR $_POST['submitLiberar'])
        { $link_mysqli = conectar();
          // Busqueda de lotes
          $querySelect = $link_mysqli->query("
            SELECT prodlote.noop,
                   prodlote.cdgproducto,
                   pdtoimpresion.impresion,
                   prodlote.cdglote,
                   prodlote.sttlote
              FROM prodlote,
                   pdtoimpresion
             WHERE (prodlote.cdglote = '".$liberaLote."' OR 
                    prodlote.noop = '".$liberaLote."') AND
                   (prodlote.cdgproducto = pdtoimpresion.cdgimpresion)");

          if ($querySelect->num_rows > 0)
          { $regQuery = $querySelect->fetch_object(); 

            $regProdLote_noop = $regQuery->noop;
            $regProdLote_cdgproducto = $regQuery->cdgproducto;
            $regProdLote_producto = $regQuery->impresion;
            $regProdLote_sttlote = $regQuery->sttlote;

            $liberaLote = $regQuery->cdglote; 

            // Busqueda de tintas
            $querySelect = $link_mysqli->query("
              SELECT pdtoimpresiontnt.notinta,
                     pdtopantone.pantone,
                     pdtopantone.HTML
                FROM pdtoimpresiontnt,
                     pdtopantone
               WHERE pdtoimpresiontnt.cdgimpresion = '".$regProdLote_cdgproducto."' AND
                     pdtoimpresiontnt.cdgtinta = pdtopantone.HTML");

            if ($querySelect->num_rows > 0)
            { while ($regQuery = $querySelect->fetch_object())
              { $regPdtoImpresionTnt_pantone[$regQuery->notinta] = $regQuery->pantone;
                $regPdtoImpresionTnt_html[$regQuery->notinta] = $regQuery->HTML; }
            
              $nTintas = $querySelect->num_rows; }
            //........................................

          } else
          { $mensaje = '
          <label for="labelMensaje" style="color:Orange"><b>'.utf8_decode('No se encontraron coincidencias.').'</b></label>';

            $regProdLote_noop = ''; }
          //........................................
        }
      } else
      { $mensaje = '
          <label for="labelMensaje" style="color:Blue"><b>'.utf8_decode('Ingresar lote ha inspeccionar.').'</b></label>'; }

      if ($_POST['submitLiberar'])
      { if ($_POST['checkNoConforme'] == true)
        { // Declaración de producto como No Conforme

          //........................................

          $mensaje = '
          <label for="labelMensaje" style="color:Red"><h2><i>'.utf8_decode('Producto declarado como').'</i> <b>No Conforme</b></h2></label>'; 
        } else
        { $liberar = false;

          for ($nTinta = 1; $nTinta <= $nTintas; $nTinta++)
          { if ($_POST['checkTinta'.$nTinta] == true) 
            { $liberar = true; 
            } else
            { $liberar = false; 
              break; }
          }
             
          if ($liberar == true)
          { switch ($regProdLote_sttlote) {
              case 1:
                // Liberar producto
                if (substr($sistModulo_permiso,0,2) == 'rw')
                { $link_mysqli->query("
                    UPDATE prodlote
                       SET sttlote = '7'
                     WHERE cdglote = '".$liberaLote."' AND
                           sttlote = '1'");

                  if ($link_mysqli->affected_rows > 0)
                  { $mensaje = '
          <label for="labelMensaje" style="color:Green"><b>'.utf8_decode('Producto liberado.').'</b></label>'; 
                  } else
                  { $mensaje = '
          <label for="labelMensaje" style="color:Blue"><b>'.utf8_decode('Registro sin cambios.').'</b></label>'; }
                } else
                { $mensaje = '
          <label for="labelMensaje" style="color:Red"><b>'.utf8_decode('Sin permisos para liberar producto.').'</b></label>'; }
                //............................................
                break;
              case S:
                // Liberar producto no conforme
                if (substr($sistModulo_permiso,0,3) == 'rwx')
                { $link_mysqli->query("
                    UPDATE prodlote
                       SET sttlote = '7'
                     WHERE cdglote = '".$liberaLote."' AND
                           sttlote = 'S'");

                  if ($link_mysql->affected_rows > 0)
                  { $mensaje = '
          <label for="labelMensaje" style="color:Orange"><b>'.utf8_decode('Producto NO CONFORME liberado.').'</b></label>'; 
                  } else
                  { $mensaje = '
          <label for="labelMensaje" style="color:Blue"><b>'.utf8_decode('Registro sin cambios.').'</b></label>'; }
                } else
                { $mensaje = '
          <label for="labelMensaje" style="color:Red"><b>'.utf8_decode('Sin permisos para liberar producto NO CONFORME.').'</b></label>'; }
                //............................................
                break; } 
          } else
          { $mensaje = '
          <label for="labelMensaje" style="color:Red"><b>'.utf8_decode('Todas las tintas deben ser liberadas.').'</b></label>'; } 
        }

        $regProdLote_noop = ''; }

      echo '
    <section>
      <form name="formLibera" name="formLibera" method="POST" action="prodLiberacionL.php">
        <label for="labelModulo">'.utf8_decode('Documento').' <b>'.$sistModulo_modulo.'</b></label><br>
        '.utf8_decode('Código').' <b>RC-02-POT-7.5.1</b><br>
        '.utf8_decode('Versión').' <b>1.0</b><br>
        '.utf8_decode('Fecha de revisión').' <b>Junio 05, 2014</b><br>
        '.utf8_decode('Responsable').' <b>Inspector de Calidad</b><br>
        '.utf8_decode('Inspector').' <b>'.$_SESSION['usuario'].'</b><br><br>
        '.$mensaje.'<br>';

      if ($regProdLote_noop != '')
      { echo '
        <article>
          <dl>
            <dt><label for="labelNoop">'.utf8_decode('Número de Orden de Producción').' <b>'.$regProdLote_noop.'</b></label></dt>
            <dt><label for="labelProducto">'.utf8_decode('Producto').' <b>'.$regProdLote_producto.'</b></label></dt>';
        
        if ($regProdLote_sttlote == '1' OR $regProdLote_sttlote == 'S')
        { echo '
            <dd>Detalle de Tintas
              <dl>';

        for ($nTinta = 1; $nTinta <= $nTintas; $nTinta++)
        { echo '
                <dt><input type="checkbox" name="checkTinta'.$nTinta.'" id="checkTinta'.$nTinta.'" />
                  <label for="labelPantone'.$nTinta.'" style="border-radius: 4px;background-color:#'.$regPdtoImpresionTnt_html[$nTinta].';color:#'.$regPdtoImpresionTnt_html[$nTinta].'">Pantone</label>
                  <label for="labelTinta'.$nTinta.'">['.$nTinta.'] <b>'.$regPdtoImpresionTnt_pantone[$nTinta].'</b></label></dt>'; }

          echo '
              </dl>
            </dd>
            <dt><label for="labelObservacion">'.utf8_decode('Observación').'</label><br>
              <textarea name="textareaObservacion" id="textareaObservacion" rows="4" cols="38"></textarea></dt>            
            <dt>Condiciones que sugieren <b>No Conformidad</b> en el producto
              <ul>
                <li>'.utf8_decode('Desviación de tonos (Fuera de tolerancia)').'</li>
                <li>'.utf8_decode('Perdida de registro (Registro de impresión)').'</li>
                <li>'.utf8_decode('Manchas de impresión (Salpicadura)').'</li>
              </ul>
            </dt>
            <dt><input type="checkbox" name="checkNoConforme" id="checkNoConforme" /><label for="labelNoConforme" style="color:Red">'.utf8_decode('Declarar producto como ').'<b>No Conforme</b></label></dt>
          </dl>
        </article>
        <article>
          <label for="labelCodigo">'.utf8_decode('Código').'</label>
          <input type="text" name="searchLote" id="searchLote" name="searchLote" value="'.$liberaLote.'" readonly="readonly" />';

          switch ($regProdLote_sttlote) {
            case 1:
              echo '
          <input type="submit" name="submitLiberar" id="submitLiberar" value="Liberar/Declarar" />';
              break;
            case S:
              echo '
          <input type="submit" name="submitLiberar" id="submitLiberar" value="Liberar (producto no conforme)" />';
              break; }
        } else 
        { switch ($regProdLote_sttlote) {
            case A:
              echo '
          <label for="labelStatus" style="border-radius: 4px;background-color:Yellow;color:Yellow">Status</label>
          <label for="searchLote"><b>'.utf8_decode('Lote programado').'</b></label>';
              break;            
            case 7:
              echo '
          <label for="labelStatus" style="border-radius: 4px;background-color:Green;color:Green">Status</label>
          <label for="searchLote"><b>'.utf8_decode('Lote liberado').'</b></label>';
               break;
            case 9:
              echo '
          <label for="searchLote"><b>'.utf8_decode('Lote procesado').'</b></label>';
              break; }

          echo '                   
          <input type="submit" name="submitBuscar" id="submitBuscar" value="Buscar nuevamente" autofocus />'; }

        echo '
        </article>';
      } else { 
        echo '
        <article>
          <label for="labelCodigo"><b>'.utf8_decode('Código').'<b></label>
          <input type="search" name="searchLote" id="searchLote" name="searchLote" value="'.$liberaLote.'" placeholder="NoOP / Codigo" required="required" autofocus />
          <input type="submit" name="submitBuscar" id="submitBuscar" value="Buscar" />
        </article>'; }
      echo '
      </form>
    </section>';
    } else
    { echo '
    <div align="center">'.$sistModulo_modulo.' <h1>Sin permisos de acceso.</h1></div>'; }
  } else
  { echo '
    <div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
  ?>
  </div>
  </body>
</html>
