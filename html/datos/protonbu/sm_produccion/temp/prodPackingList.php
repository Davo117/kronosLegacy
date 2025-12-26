<!DOCTYPE html>
<html>
  <head>
    <title>Producci&oacute;n Refilado</title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '60031';

  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);
    
    if (substr($sistModulo_permiso,0,1) == 'r')
    { $link_mysql = conectar();
      $prodSubLoteSelect = $link_mysql->query("
        SELECT proglote.tarima,
		  proglote.idlote,
		  proglote.lote,
		  prodlote.noop,
		  prodsublote.sublote,
		  pdtoproyecto.proyecto,
		  pdtoimpresion.impresion,
		  pdtomezcla.mezcla,
		  pdtomezcla.idmezcla,
		  prodsublote.amplitud,
		  prodsublote.longitud,
		  prodsublote.peso,
		  prodsublote.cdgsublote 
		FROM prodsublote,
		  prodlote,
		  proglote,
		  pdtomezcla,
		  pdtoimpresion,
		  pdtoproyecto
		WHERE (proglote.cdglote = prodlote.cdglote 
		  AND prodlote.cdglote = prodsublote.cdglote)
		AND (prodlote.cdgmezcla = pdtomezcla.cdgmezcla
		  AND pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion
		  AND pdtoimpresion.cdgproyecto = pdtoproyecto.cdgproyecto)
		AND prodsublote.cdgproceso = '50'
		AND prodsublote.documento = ''
		AND prodsublote.sttsublote = '1'
		ORDER BY prodlote.noop,
		  prodsublote.sublote");
		  
      if ($prodSubLoteSelect->num_rows > 0)
	  { echo '
	<form id="form_prodpacking" name="form_prodpacking" method="POST" action="prodPacking.php">
	  <table align="center">
	    <thead>
	      <tr><th colspan="2">Bobina</th>
	        <th colspan="2">Origen</th>
	        <th>NoOp-Fragmento</th>
	        <th colspan="3">Producto</th>
	        <th colspan="3">Medidas</th></tr>
	    </thead>
	    <tbody>';
	  
		while ($regProdSubLote = $prodSubLoteSelect->fetch_object())
	    { echo '
	      <tr><td></td>
	        <td>'.$regProdSubLote->cdgsublote.'</td>
	        <td>'.$regProdSubLote->tarima.'/'.$regProdSubLote->idlote.'</td>
	        <td>'.$regProdSubLote->lote.'</td>
	        <td>'.$regProdSubLote->noop.'-'.$regProdSubLote->sublote.'</td>
	        <td>'.$regProdSubLote->proyecto.'</td>
	        <td>'.$regProdSubLote->impresion.'</td>
	        <td>'.$regProdSubLote->idmezcla.' '.$regProdSubLote->mezcla.'</td>
	        <td align="right">'.$regProdSubLote->amplitud.' <strong>mm</strong></td>
	        <td align="right">'.number_format($regProdSubLote->longitud,2).' <strong>mts</strong></td>
	        <td align="right">'.number_format($regProdSubLote->peso,3).' <strong>kgs</strong></td></tr>'; } 
	    
	    echo '
	    </tbody>
	    <tfoot>
	    </tfoot>
	  </table>
	</form>';
	  }
	  
	  $prodSubLoteSelect->close;
	}  
  
    if ($msg_alert != '')
    { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
  ?>
  
  </body>
</html>  
