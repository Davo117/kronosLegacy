<html>
  <head>
  </head>
  <body><?php
  include '../../datos/mysql.php';
	
  header("Content-Type: application/vnd.ms-excel");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("content-disposition: attachment;filename=Etiqueta de bobinas refiladas.xls");  
		
  echo '
    <table>
      <tr><th>No Lote</th>        
        <th>Lote</th>        
        <th>NoOP</th>        
        <th>Proyecto</th>
        <th>Impresion</th>
        <th>Mezcla</th>
        <th>Desc</th>
        <th>Longitud</th>
        <th>Amplitud</th>
        <th>Peso</th>
        <th>Codigo</th></tr>';
	
	$link_mysqli = conectar();		
	$packingListSelect = $link_mysqli->query("
    SELECT proglote.lote,
      proglote.tarima,
      proglote.idlote,
      prodlote.noop,
      prodbobina.bobina,
      pdtoproyecto.proyecto,
      pdtoimpresion.impresion,
      pdtomezcla.idmezcla,
      pdtomezcla.mezcla,
      prodbobina.longitud,
      prodbobina.amplitud,
      prodbobina.peso,
      prodbobina.cdgbobina
    FROM proglote,
      prodlote,
      prodbobina,
      pdtomezcla,
      pdtoimpresion,
      pdtoproyecto
    WHERE (proglote.cdglote = prodlote.cdglote 
      AND prodlote.cdglote = prodbobina.cdglote)
    AND (prodlote.cdgmezcla = pdtomezcla.cdgmezcla
      AND pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion
      AND pdtoimpresion.cdgproyecto = pdtoproyecto.cdgproyecto)
    AND prodbobina.sttbobina = '".$_GET['sttbobina']."'
    AND prodlote.cdgmezcla = '".$_GET['cdgmezcla']."' AND
       (prodbobina.longitud/pdtoimpresion.corte) > 1
    ORDER BY prodlote.noop,
      prodbobina.bobina");
    
	while ($regPackingList = $packingListSelect->fetch_object())
	{	echo '
      <tr><td>'.$regPackingList->lote.'</td>
        <td>'.$regPackingList->tarima.'/'.$regPackingList->idlote.'</td>
        <td>'.$regPackingList->noop.'-'.$regPackingList->bobina.'</td>
        <td>'.$regPackingList->proyecto.'</td>
        <td>'.$regPackingList->impresion.'</td>
        <td>'.$regPackingList->idmezcla.'</td>
        <td>'.$regPackingList->mezcla.'</td>
        <td>'.$regPackingList->longitud.'</td>
        <td>'.$regPackingList->amplitud.'</td>
        <td>'.$regPackingList->peso.'</td>
        <td>*'.$regPackingList->cdgbobina.'*</td></tr>';
		
	}		 
  
?>
    </table>
  </body>
</html>
