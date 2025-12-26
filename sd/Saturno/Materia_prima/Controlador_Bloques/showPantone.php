<?php

print("<th>Acciones</th>");
print("<th>Codigo</th>");
print("<th>Descripción</th>");
print("<th>Código HTML</th>");

$resultado = $MySQLiconn->query("SELECT * FROM pantone where baja=1 ");
while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td>&nbsp &nbsp<a href="?edit=<?php echo $rows["idPantone"]; ?>"" ><IMG src="../Pictures/modificarProducto.png" title='Modificar'></a>  
    <a href="?del=<?php echo $rows["idPantone"]; ?>" onclick="return confirm('sure to delete !'); " ><IMG src="../Pictures/deletProducto.png" title='eliminar'></a></a></td>
<?php
//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='Código'>".$rows["codigoPantone"]."</td>");
print("<td title='Descripción'>".$rows["descripcionPantone"]."</td>");
print("<td title='Código HTML'>".$rows["codigoPantone"]." <div style=”padding:12px;background-color:#".$rows["codigoHTML"].";line-height:1.2;”>.
</div></td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p>Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();

?>