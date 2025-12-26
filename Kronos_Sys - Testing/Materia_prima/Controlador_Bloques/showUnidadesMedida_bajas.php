<?php

print("<th style='width:100px;'>Acciones</th>");
print("<th>Identificador</th>");
print("<th>Nombre</th>");

$resultado = $MySQLiconn->query("SELECT * FROM unidades where baja=0 ");
while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td>&nbsp &nbsp<a href="?acti=<?php echo $rows["idUnidad"]; ?>"" ><IMG src="../pictures/unnamed.png" title='Modificar'></a>  
    <a href="?delfin=<?php echo $rows["idUnidad"]; ?>" onclick="return confirm('¿Seguro que desea eliminar definitivamente?(Eso es mucho tiempo); " ><IMG src="../pictures/definitivo.png" title='eliminar'></a></a></td>
<?php
//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='Identificador'>".$rows["identificadorUnidad"]."</td>");
print("<td title='nombre de unidad'>".$rows["nombreUnidad"]."</td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();

?>