<?php
echo "<th style='width:30%;'>Acciones</th>	<th>Nombre</th>	<th>Proceso</th>";

$resultado = $MySQLiconn->query("SELECT * FROM anillox ORDER BY id ASC, baja DESC");

while ($rows = $resultado->fetch_array()) {
echo "<tr>";	
if ($rows['baja']=='1'){?>
	<td><a href="?edit=<?php echo $rows["id"]; ?>"" ><IMG src="../pictures/modificarproducto.png" title='Editar'></a>  &nbsp; 
    <a href="?del=<?php echo $rows["id"]; ?>" ><IMG src="../pictures/deletProducto.png" title='Eliminar'></a></a> </td>
	<?php
}
else{ ?>
	<td><a href="?acti=<?php echo $rows["id"]; ?>"" ><IMG src="../pictures/unnamed.png" title='Editar'></a>  &nbsp; 
    <a href="?eli=<?php echo $rows["id"]; ?>" ><IMG src="../pictures/definitivo.png" title='Eliminar'></a></a> </td>
<?php 
}
//Traemos la tabla y la imprimimos donde pertenece 
echo "<td title='Anillox'>Identificador: ".$rows["identificadorAnillox"]."<br>
	Líneas: ".$rows["num_lineas"]."<br>
	BCM: ".$rows["bcm"]."</td>
	<td title='Proceso'>".$rows["proceso"]."</td>
	</tr>";
}


//Contar registros:
$row_cnt=$resultado->num_rows;
echo "<p id='mostrar' >Se muestran: ".$row_cnt." Registros Activos</p>";
$resultado->close();
?>