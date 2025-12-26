<?php
echo "<th style='width:100px;'>acciones</th>
	<th>Identificador</th>
	<th>nombre</th>
	<th>unidad</th>";

$resultado = $MySQLiconn->query("SELECT * FROM elementosconsumo where baja=0");
while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td>&nbsp &nbsp<a href="?acti=<?php echo $rows['idElemento'];?>"" ><IMG src="../pictures/unnamed.png" title='Modificar'></a>  
    <a href="?delfin=<?php echo $rows['idElemento']; ?>" onclick="return confirm('¿Seguro que desea eliminar definitivamente?(Eso es mucho tiempo)'); " ><IMG src="../pictures/definitivo.png" title='eliminar'></a></a> 
<?php
//Traemos la tabla y la imprimimos donde pertenece 
echo "<td title='identificador'>".$rows["identificadorElemento"]."</td>
	<td title='nombre'>".$rows["nombreElemento"]."</td>
	<td title='unidad'>".$rows["unidad"]."</td>
	</tr>";
}

$row_cnt=$resultado->num_rows;
echo "<p id='mostrar'>Se muestran: ".$row_cnt." Registros Inactivos</p>";
$resultado->close();
?>