<?php

print("<th style='width:100px;'>Acciones</th>");
print("<th>Codigo</th>");
print("<th>Descripción</th>");
print("<th>Código HTML</th>");

$resultado = $MySQLiconn->query("SELECT * FROM pantone where baja=1 order by idPantone desc");
while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td><a href="?edit=<?php echo $rows["idPantone"]; ?>"" ><IMG src="../pictures/modificarproducto.png" title='Modificar'></a>  
    <a href="?del=<?php echo $rows["idPantone"]; ?>"><IMG src="../pictures/deletProducto.png" title='eliminar'></a></td>
<?php
//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='Código'>".$rows["codigoPantone"]."</td>");
print("<td title='Descripción'>".$rows["descripcionPantone"]."</td>");
print("<td title='Código HTML'>".$rows["codigoPantone"]." <div style='padding:2px;background-color:#".$rows["codigoHTML"].";line-height:1;border:solid 1px;;'>.
</div></td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;
print("<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();

?>