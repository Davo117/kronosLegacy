<?php

print("<th style='width:100px;'>Acciones</th>");
print("<th>Codigo</th>");
print("<th>Descripción</th>");
print("<th>Código HTML</th>");

$resultado = $MySQLiconn->query("SELECT * FROM pantone where baja=0 order by codigoPantone desc");
while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td><a href="?acti=<?php echo $rows["idPantone"]; ?>"" ><IMG src="../pictures/unnamed.png" title='Modificar'></a>  
    <a href="?delfin=<?php echo $rows["idPantone"]; ?>" onclick="return confirm('¿Seguro que desea eliminar definitivamente?(Eso es mucho tiempo)'); " ><IMG src="../pictures/definitivo.png" title='eliminar'></a></td>
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
print("<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();

?>