<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '60038';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($_GET['cdgdocumento'])
  { $_SESSION['prodPacking_cdgdocumento'] = $_GET['cdgdocumento']; }

  $prodPacking_cdgdocumento = $_SESSION['prodPacking_cdgdocumento'];

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    if (substr($sistModulo_permiso,0,1) == 'r')
    { 
  //  $link_mysqli = conectar();
  //  $prodSubLoteSelect = $link_mysqli->query("
  //    SELECT * FROM prodbobina
  //    WHERE cdgdocumento = '' AND
  //      sttbobina = '7'");

  //  if ($prodSubLoteSelect->num_rows <= 0)
  //  { echo '<br/>
  //<div align="center"><h1>No se encontraron bobinas disponibles para transferencia.</h1></div>'; }

      $link_mysqli = conectar();
      $prodDocumentoSelect = $link_mysqli->query("
        SELECT * FROM proddocumento
        WHERE (cdgdocumento = '".$prodPacking_cdgdocumento."' AND
          sttdocumento = '1')");

      if ($prodDocumentoSelect->num_rows > 0)
      { $regProdDocumento = $prodDocumentoSelect->fetch_object();

        $prodPacking_iddocumento = $regProdDocumento->iddocumento;
        $prodPacking_fchdocumento = $regProdDocumento->fchdocumento;

        if ($_GET['cdgbobina'])
        { $prodPacking_cdgbobina = $_GET['cdgbobina'];

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $link_mysqli = conectar();
              $link_mysqli->query("
                UPDATE prodbobina
                SET cdgdocumento = '',
                  sttbobina = '6'
                WHERE (cdgbobina = '".$prodPacking_cdgbobina."' AND
                  cdgdocumento = '".$prodPacking_cdgdocumento."' AND
                  sttbobina = '7')");

              if ($link_mysqli->affected_rows > 0)
              { $msg_alert = 'La bobina ha sido removida con exito.'; }
              else
              { $msg_alert = 'La bobina NO pudo ser removida del documento, posiblemente el documento ya fue procesado.'; }
            } else
            { $msg_alert = $msg_nodelete; }
          }
        }

        if ($_POST['submit_salvar'])
        { if (substr($sistModulo_permiso,0,2) == 'rw')
          { $prodPacking_cdgbobina = trim($_POST['text_cdgbobina']);

            if ($prodPacking_cdgbobina != '')
            { $link_mysqli = conectar();
              $link_mysqli->query("
                UPDATE prodbobina
                SET cdgdocumento = '".$prodPacking_cdgdocumento."',
                  sttbobina = '7'
                WHERE (cdgbobina = '".$prodPacking_cdgbobina."' AND
                  cdgdocumento = '' AND
                  longitud > 0 AND
                  sttbobina = '6')");

              if ($link_mysqli->affected_rows > 0)
              { $msg_alert = 'La bobina ha sido agregada con exito.'; }
              else
              { $link_mysqli = conectar();
                $prodBobinaSelect = $link_mysqli->query("
                  SELECT * FROM prodbobina 
                  WHERE cdgbobina = '".$prodPacking_cdgbobina."'");

                if ($prodBobinaSelect->num_rows > 0)
                { $regProdBobina = $prodBobinaSelect->fetch_object();

                  if ($regProdBobina->sttbobina == 1)
                  { $msg_alert = 'La bobina NO ha sido liberada para su transferencia.'; }

                  if ($regProdBobina->sttbobina == 8)
                  { $msg_alert = 'La bobina ya fue agregada a un documento.'; }

                  if ($regProdBobina->longitud == 0)
                  { $msg_alert = 'La bobina no tiene metros.  ¿?'; }

                } else
                { $msg_alert = 'La bobina NO fue encontrada.'; }
              }
            } else
            { $msg_alert = 'El necesario ingresar el codigo de bobina o la referencia NoOP-Fragmento.';}
          }
        }

        echo '
    <form id="form_prodpacking" name="form_prodpacking" method="POST" action="prodPacking.php"/>
      <table align="center">
        <thead>
          <tr>
            <th colspan="3">'.$sistModulo_modulo.'</th>
          </tr>
        </thead>
        <tbody>
          <tr><td><label for="label_idempleado"><strong>ID </strong>'.$_SESSION['idusuario'].'</label><br/>
              <label for="label_empleado"><strong>Nombre </strong>'.$_SESSION['usuario'].'</label><br/>
              <label for="label_documento"><strong>Documento </strong>'.$prodPacking_iddocumento.'</label><br/>
              <label for="label_fchdocumento"><strong>Fecha </strong>'.$prodPacking_fchdocumento.'</label></td></tr>
          <tr><td align="center"><label for="label_cdgbobina">Bobina</label><br/>
              <input type="text" id="text_cdgbobina" name="text_cdgbobina" style="width:180px" value="'.$prodDocumento_cdgbobina.'" title="Codigo / NoOP-Fragmento" autofocus required/></td></tr>
        </tbody>
        <tfoot>
        <tr><td align="right"><input type="submit" id="submit_salvar" name="submit_salvar" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>';

          $link_mysqli = conectar();
          $prodSubLoteSelect = $link_mysqli->query("
            SELECT CONCAT(proglote.tarima,'/',proglote.idlote) AS idlote,
              proglote.lote,
              CONCAT(prodlote.noop,'-',prodbobina.bobina) AS noop,
              pdtoproyecto.proyecto,
              pdtoimpresion.impresion,
              pdtomezcla.mezcla,
              pdtomezcla.idmezcla,
              prodbobina.amplitud,
              prodbobina.longitud,
              prodbobina.peso,
              prodbobina.cdgbobina
            FROM prodbobina,
              prodlote,
              proglote,
              pdtomezcla,
              pdtoimpresion,
              pdtoproyecto
            WHERE (proglote.cdglote = prodlote.cdglote AND prodlote.cdglote = prodbobina.cdglote) AND 
             (prodlote.cdgmezcla = pdtomezcla.cdgmezcla AND pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion AND pdtoimpresion.cdgproyecto = pdtoproyecto.cdgproyecto) AND 
              prodbobina.cdgdocumento = '".$prodPacking_cdgdocumento."' AND 
              prodbobina.sttbobina = '7'
            ORDER BY pdtoproyecto.proyecto,
              pdtoimpresion.impresion,
              pdtomezcla.mezcla,
              prodlote.noop,
              prodbobina.bobina");

          if ($prodSubLoteSelect->num_rows > 0)
          { echo '
      <table align="center">
        <thead>
          <tr><th colspan="3">Origen</th>
            <th>NoOp-Fragmento</th>
            <th colspan="3">Producto</th>
            <th colspan="3">Medidas</th></tr>
        </thead>
        <tbody>';

            while ($regProdSubLote = $prodSubLoteSelect->fetch_object())
            { echo '
          <tr><td><a href="prodPacking.php?cdgbobina='.$regProdSubLote->cdgbobina.'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td>'.$regProdSubLote->idlote.'</td>
            <td>'.$regProdSubLote->lote.'</td>
            <td>'.$regProdSubLote->noop.'</td>
            <td>'.$regProdSubLote->proyecto.'</td>
            <td>'.$regProdSubLote->impresion.'</td>
            <td>'.$regProdSubLote->idmezcla.' '.$regProdSubLote->mezcla.'</td>
            <td align="right">'.$regProdSubLote->amplitud.' <strong>mm</strong></td>
            <td align="right">'.number_format($regProdSubLote->longitud,2).' <strong>mts</strong></td>
            <td align="right">'.number_format($regProdSubLote->peso,3).' <strong>kgs</strong></td></tr>'; }

            echo '
        </tbody>
        <tfoot>
          <tr><th colspan="10">['.$prodSubLoteSelect->num_rows.'] registros encontrados</th></tr>
        </tfoot>
      </table>'; }

        echo '
    </form>';
        } else
        { echo '<br/>
    <div align="center"><h1>El documento que buscas no existe o ya fue aplicado.</h1></div>'; }
      //}

      $prodSubLoteSelect->close;
    } else
    { $msg_alert = $msg_noread; }

    if ($msg_alert != '')
    { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
  ?>

  </body>
</html>
