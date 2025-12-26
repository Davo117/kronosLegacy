<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '60039';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($_GET['cdgdocumento'])
  { $_SESSION['prodPackingFree_cdgdocumento'] = $_GET['cdgdocumento']; }

  $prodPacking_cdgdocumento = $_SESSION['prodPackingFree_cdgdocumento'];

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    if (substr($sistModulo_permiso,0,1) == 'r')
    { $link_mysqli = conectar();
      $prodBobinaSelect = $link_mysqli->query("
        SELECT * FROM prodbobina
    		WHERE (cdgdocumento != '' AND
		      sttbobina = '8')");

    if ($prodBobinaSelect->num_rows > 0)
	  { $link_mysqli = conectar();
        $prodDocumentoSelect = $link_mysqli->query("
          SELECT * FROM proddocumento
          WHERE (cdgdocumento = '".$prodPacking_cdgdocumento."' AND
            sttdocumento = '1')");

        if ($prodDocumentoSelect->num_rows > 0)
        { $regProdDocumento = $prodDocumentoSelect->fetch_object();

          $prodPacking_iddocumento = $regProdDocumento->iddocumento;
          $prodPacking_fchdocumento = $regProdDocumento->fchdocumento;

          if ($_POST['submit_salvar'])
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($prodPacking_cdgdocumento != '')
			  { $link_mysqli = conectar();
			    $link_mysqli->query("
			      UPDATE proddocumento
			      SET sttdocumento = '9',
			        cdgempleado = '".$_SESSION['cdgusuario']."'
			      WHERE (cdgdocumento = '".$prodPacking_cdgdocumento."' AND
			        sttdocumento = '1')");

			    if ($link_mysqli->affected_rows > 0)
			    { $link_mysqli = conectar();
			      $link_mysqli->query("
			        UPDATE prodbobina
			        SET sttbobina = '9'
			        WHERE (cdgdocumento = '".$prodPacking_cdgdocumento."' AND
			          sttbobina = '8')");

				  if ($link_mysqli->affected_rows > 0)
				  { $msg_alert = 'El documento ha sido transferido con exito. \n ['.$link_mysqli->affected_rows.'] SubBobinas transferidas...'; }
				  else
				  { $link_mysqli = conectar();
			        $link_mysqli->query("
			          UPDATE proddocumento
			          SET sttdocumento = '1',
			            cdgempleado = ''
			          WHERE (cdgdocumento = '".$prodPacking_cdgdocumento."' AND
			            sttbobina = '9')");
			        $msg_alert = 'El documento NO fue afectado por que esta vacio..'; }
			    } else
			    { $msg_alert = 'El documento NO fue afectado, ya no existe o fue afectado por alguien mas.'; }
			  } else
			  { $msg_alert = 'El necesario indicar el documento de transferencia a aplicar.';}
			}
          }

	      echo '
    <form id="form_prodpacking" name="form_prodpacking" method="POST" action="prodPackingFree.php"/>
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
	    </tbody>
	    <tfoot>
        <tr><td align="right"><input type="submit" id="submit_salvar" name="submit_salvar" value="Aceptar transferencia" /></td></tr>
	    </tfoot>
	  </table><br/>';

          $link_mysqli = conectar();
          $prodBobinaSelect = $link_mysqli->query("
			SELECT proglote.tarima,
			  proglote.idlote,
			  proglote.lote,
			  prodlote.noop,
			  prodbobina.bobina,
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
			WHERE (proglote.cdglote = prodlote.cdglote
			  AND prodlote.cdglote = prodbobina.cdglote)
			AND (prodlote.cdgmezcla = pdtomezcla.cdgmezcla
			  AND pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion
			  AND pdtoimpresion.cdgproyecto = pdtoproyecto.cdgproyecto)
			AND prodbobina.cdgdocumento = '".$prodPacking_cdgdocumento."'
			AND prodbobina.sttbobina = '8'
			ORDER BY pdtoproyecto.proyecto,
			  pdtoimpresion.impresion,
			  pdtomezcla.mezcla,
			  prodlote.noop,
			  prodbobina.bobina");

		  if ($prodBobinaSelect->num_rows > 0)
	      { echo '
	  <table align="center">
	    <thead>
	      <tr><th colspan="2">Origen</th>
	        <th>SubBobina</th>
	        <th colspan="3">Producto</th>
	        <th colspan="3">Medidas</th></tr>
	    </thead>
	    <tbody>';

		    while ($regProdBobina = $prodBobinaSelect->fetch_object())
	        { echo '
	      <tr><td>'.$regProdBobina->tarima.'/'.$regProdBobina->idlote.'</td>
	        <td>'.$regProdBobina->lote.'</td>
	        <td>'.$regProdBobina->noop.'-'.$regProdBobina->bobina.'</td>
	        <td>'.$regProdBobina->proyecto.'</td>
	        <td>'.$regProdBobina->impresion.'</td>
	        <td>'.$regProdBobina->idmezcla.' '.$regProdBobina->mezcla.'</td>
	        <td align="right">'.number_format($regProdBobina->longitud,2).' <strong>mts</strong></td>
	        <td align="right">'.$regProdBobina->amplitud.' <strong>mm</strong></td>
	        <td align="right">'.number_format($regProdBobina->peso,3).' <strong>kgs</strong></td></tr>'; }

	        echo '
	    </tbody>
	    <tfoot>
	      <tr><th align="right" colspan="10">['.$prodBobinaSelect->num_rows.'] bobinas encontrados</th></tr>
	    </tfoot>
	  </table>'; }
        } else
        { echo '<br/><div align="center"><h1>El documento que buscas no existe o ya fue aplicado como transferencia.</h1></div>'; }

	    echo '
	</form>';
	  } else
	  { echo '<br/><div align="center"><h1>No se encontraron bobinas en transferencia.</h1></div>'; }

	  $prodBobinaSelect->close;
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
