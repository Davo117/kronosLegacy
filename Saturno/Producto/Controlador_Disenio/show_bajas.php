<?php

print("<th>Acciones</th>");
print("<th>Codigo</th>");
print("<th>Descripción</th>");
print("<th>Tipo</th>");


$resultado = $MySQLiconn->query("SELECT * FROM producto where baja=0");

while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td>&nbsp &nbsp<a href="?acti=<?php echo $rows["ID"]; ?>"" ><IMG src="../Pictures/unnamed.png" title='Activar'></a>  
    <a href="?delfin=<?php echo $rows["ID"]; ?>" onclick="return confirm('¿Seguro que desea eliminar definitivamente?(eso es mucho tiempo)'); " ><IMG src="../Pictures/definitivo.png" title='Eliminar'></a></a> </td>
<?php
//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='Codigo'>".$rows["codigo"]."</td>");
print("<td title='Descripción'>".$rows["descripcion"]."</td>");
print("<td title='Tipo'>".$rows["tipo"]."</td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar' >Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();
?>
