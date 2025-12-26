<?php

print("<th>Acciones</th>");
print("<th>Identificador</th>");
print("<th>Nombre</th>");

$resultado = $MySQLiconn->query("SELECT * FROM unidades where baja=1 ");
while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td>&nbsp &nbsp<a href="?edit=<?php echo $rows["idUnidad"]; ?>"" ><IMG src="../Pictures/modificarProducto.png" title='Modificar'></a>  
    <a href="?del=<?php echo $rows["idUnidad"]; ?>" onclick="return confirm('sure to delete !'); " ><IMG src="../Pictures/deletProducto.png" title='eliminar'></a></a></td>
<?php
//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='Identificador'>".$rows["identificadorUnidad"]."</td>");
print("<td title='nombre de unidad'>".$rows["nombreUnidad"]."</td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p>Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();

?>