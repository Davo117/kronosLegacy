<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../../datos/mysql.php';

  $link_mysqli = conectar();
  $prodConsumoSelect = $link_mysqli->query("
  	SELECT pdtomateria.materia,
	  SUM(prodconsumo.consumo) AS consumo,
	  pdtounidad.unidad
	FROM prodlote,
	  prodconsumo,
	  pdtomateria,
	  pdtounidad
	WHERE prodlote.cdglote = prodconsumo.cdglote
	AND prodconsumo.cdgmateria = pdtomateria.cdgmateria
	AND pdtomateria.cdgunidad = pdtounidad.cdgunidad
	AND fchprograma = '".$_GET['fchprograma']."'
	GROUP BY pdtomateria.materia,
	  pdtounidad.unidad");

  echo '
    <table align="center">
      <thead>
        <tr><th>Materia prima</th>
          <th colspan="2">Consumo</th></tr>
      </thead>
      <tbody>';
    
  while ($regProdConsumo = $prodConsumoSelect->fetch_object())
  { echo '
        <tr>
          <td>'.$regProdConsumo->materia.'</td>
          <td align="right">'.number_format($regProdConsumo->consumo,3).'</td>
          <td>'.$regProdConsumo->unidad.'</td></tr>'; }

  echo '
      </tbody>
      <tfoot>
        <tr><th colspan="3"></th></tr>
      </tfoot>
    </table>';
?>

  </body>	
</html>