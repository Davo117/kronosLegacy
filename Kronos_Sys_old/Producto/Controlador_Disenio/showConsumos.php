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
else if(empty($q) and isset($_GET['comboDisenios2']))
{
	$q=$_GET['comboDisenios2'];
}
else
{
	$q=0;
}
//$MySQLiconn->query("UPDATE cache set dato='$q' where id=2");
if($q!=0)
{
	$resultado = $MySQLiconn->query("SELECT * FROM consumos where baja=1 and producto='".$q."'");
}
else
{

	$resultado = $MySQLiconn->query("SELECT * FROM consumos where baja=1 and producto='$q'");
}

	while ($rows = $resultado->fetch_array()) {  
		$sqlstt=sqlsrv_query($SQLconn,"SELECT CNOMBREPRODUCTO as nombre FROM admproductos WHERE CIDPRODUCTO='".$rows['elemento']."'");
		$sqlrow=sqlsrv_fetch_array($sqlstt, SQLSRV_FETCH_ASSOC);
		?>
		<tr>
		<td><a href="?edit=<?php echo $rows["IDConsumo"]; ?>&descripcionCons=<?php echo $q?>" ><IMG src="../pictures/modificarproducto.png" title='Modificar'></a>  
   		<a href="?del=<?php echo $rows["IDConsumo"]; ?>&descripcionCons=<?php echo $q?>"><IMG src="../pictures/deletProducto.png" title='eliminar'></a></a></td>
	
		<?php //Traemos la tabla y la imprimimos donde pertenece 
		print("<td title='Elemento'>".$sqlrow['nombre']."</td>");
		print("<td title='Cantidad'>".$rows["subProceso"]."</td>");
		print("<td title='Cantidad'>".$rows["consumo"]."</td>");
		print("</tr>");
	}
	//Contar registros:
	$row_cnt=$resultado->num_rows;
	printf("<p id='mostrar' >Se muestran: ".$row_cnt." Registros Activos</p>");
	$resultado->close();

//	print("<td title='Elementos'>".'Este producto no tiene consumos predeterminados'."</td>");

print("</table>");
?>











