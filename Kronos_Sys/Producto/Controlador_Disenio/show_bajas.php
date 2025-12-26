<?php
echo "<th style='width:150px;'>Acciones</th>
	<th>Codigo</th>
	<th>Descripción</th>
	<th>Tipo</th>";

$resultado = $MySQLiconn->query("SELECT p.ID,p.codigo,p.descripcion,t.tipo as tipo FROM producto p INNER JOIN tipoproducto t ON p.tipo=t.id where p.baja=0 order by p.id desc");

while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td>&nbsp &nbsp<a href="?acti=<?php echo $rows["ID"]; ?>"" ><IMG src="../pictures/unnamed.png" title='Activar'></a>  
    <a href="?delfin=<?php echo $rows["ID"]; ?>" onclick="return confirm('¿Seguro que desea eliminar definitivamente el producto *<?php echo $rows['descripcion'];?>*?(eso es mucho tiempo)'); " ><IMG src="../pictures/definitivo.png" title='Eliminar'></a></a> </td>
<?php
//Traemos la tabla y la imprimimos donde pertenece 
echo "<td title='Codigo'>".$rows["codigo"]."</td>
	<td title='Descripción'>".$rows["descripcion"]."</td>
	<td title='Tipo'>".$rows["tipo"]."</td>
	</tr>";
}

//Contar registros:
$row_cnt=$resultado->num_rows;
echo "<p id='mostrar' >Se muestran: ".$row_cnt." Registros Activos</p>";
$resultado->close();
?>
