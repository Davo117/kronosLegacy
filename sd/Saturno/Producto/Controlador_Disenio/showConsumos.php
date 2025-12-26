<?php

print("<th>Acciones</th>");
print("<th>Elemento</th>");
print("<th>Cantidad</th>");

//$desc=$_SESSION['descripcion'];
$resultado = $MySQLiconn->query("SELECT * FROM consumos where baja=1 ");
while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td>&nbsp &nbsp<a href="?edit=<?php echo $rows["IDConsumo"]; ?>"" ><IMG src="../Pictures/modificarProducto.png" title='Modificar'></a>  
    <a href="?del=<?php echo $rows["IDConsumo"]; ?>" onclick="return confirm('sure to delete !'); " ><IMG src="../Pictures/deletProducto.png" title='eliminar'></a></a></td>
<?php
//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='Elemento'>".$rows["elemento"]."</td>");
print("<td title='Cantidad'>".$rows["consumo"]."</td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar' >Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();

?>
