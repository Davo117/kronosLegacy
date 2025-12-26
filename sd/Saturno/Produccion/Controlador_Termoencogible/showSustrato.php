<?php

print("<th>Acciones</th>");
print("<th>Código</th>");
print("<th>Descripción</th>");

$resultado = $MySQLiconn->query("SELECT * FROM sustrato where baja=1");
while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td>&nbsp &nbsp<a href="?edit=<?php echo $rows["idSustrato"]; ?>"" ><IMG src="../Pictures/modificarProducto.png" title='Modificar'></a>  
    <a href="?del=<?php echo $rows["idSustrato"]; ?>" onclick="return confirm('sure to delete !'); " ><IMG src="../Pictures/deletProducto.png" title='eliminar'></a></td>
<?php
//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='Código'>".$rows["codigoSustrato"]."</td>");
print("<td title='Descripción'>".$rows["descripcionSustrato"]."</td>");
print("</tr>");
}
//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p>Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();
?>