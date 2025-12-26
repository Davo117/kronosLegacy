<?php
print("<th>acciones</th>");
print("<th>Identificador</th>");
print("<th>nombre</th>");
print("<th>unidad</th>");

$resultado = $MySQLiconn->query("SELECT * FROM elementosConsumo where baja=1");
while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td>&nbsp &nbsp<a href="?edit=<?php echo $rows['idElemento'];?>"" ><IMG src="../Pictures/modificarProducto.png" title='Modificar'></a>  
    <a href="?del=<?php echo $rows['idElemento']; ?>" onclick="return confirm('sure to delete !'); " ><IMG src="../Pictures/deletProducto.png" title='eliminar'></a></a> 
<?php
//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='identificador'>".$rows["identificadorElemento"]."</td>");
print("<td title='nombre'>".$rows["nombreElemento"]."</td>");
print("<td title='unidad'>".$rows["unidad"]."</td>");
print("</tr>");
}

$row_cnt=$resultado->num_rows;
printf("<p>Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();
?>