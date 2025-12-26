<?php

print("<th>Acciones</th>");
print("<th>Codigo</th>");
print("<th>Descripción</th>");
print("<th>Tipo</th>");


$resultado = $MySQLiconn->query("SELECT * FROM producto where baja=1");

while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td>&nbsp &nbsp<a href="?edit=<?php echo $rows["ID"]; ?>"" ><IMG src="../Pictures/modificarProducto.png" title='Modificar'></a>  
    <a href="?del=<?php echo $rows["ID"]; ?>" onclick="return confirm('sure to delete !'); " ><IMG src="../Pictures/deletProducto.png" title='eliminar'></a></a> 
    <a href="?imp=<?php echo $rows["descripcion"]; ?>"><IMG src="../Pictures/impresion.png" title='impresiones'></a>
     <a href="?cons=<?php echo $rows["ID"]; ?>"><IMG src="../Pictures/consumos2.png" title='consumos'></a></td>
<?php
//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='Codigo'>".$rows["codigo"]."</td>");
print("<td title='Descripción'>".$rows["descripcion"]."</td>");
print("<td title='Tipo'>".$rows["tipo"]."</td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p>Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();
?>
