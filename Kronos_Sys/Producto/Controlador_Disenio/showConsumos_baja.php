<?php
print("<table border='0' class='table table-hover'>");
print("<th>Acciones</th>");
print("<th>Elemento</th>");
print("<th>Subproceso</th>");
print("<th>Cantidad</th>");

include_once 'db_Producto.php';
if(isset($_GET['descripcionCons']))
{
	$q=$_GET['descripcionCons'];
}

if(empty($q))
{
	$q=$_GET['comboDisenios2'];
}
//$MySQLiconn->query("UPDATE cache set dato='$q' where id=2");
$resultado = $MySQLiconn->query("SELECT * FROM consumos where baja=0 and producto='".$q."'");
	while ($rows = $resultado->fetch_array()) {  ?>
		<tr>
		<td><a href="?acti=<?php echo $rows["IDConsumo"]; ?>&descripcionCons=<?php echo $q?>"><IMG src="../pictures/unnamed.png" title='Activar'></a>  
   		<a href="?delfin=<?php echo $rows["IDConsumo"]; ?>&descripcionCons=<?php echo $q?>"><IMG src="../pictures/definitivo.png" title='Eliminar'></a></td>
	
		<?php //Traemos la tabla y la imprimimos donde pertenece 
		print("<td title='Elemento'>".$rows["elemento"]."</td>");
		print("<td title='Cantidad'>".$rows["subProceso"]."</td>");
		print("<td title='Cantidad'>".$rows["consumo"]."</td>");
		print("</tr>");
	}
	//Contar registros:
	$row_cnt=$resultado->num_rows;
	printf("<p id='mostrar' >Se muestran: ".$row_cnt." Registros</p>");
	$resultado->close();
	
//	print("<td title='Elementos'>".'Este producto no tiene consumos predeterminados'."</td>");

print("</table>");
?>