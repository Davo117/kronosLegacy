<?php

print("<th style='width:100px;'>Acciones</th>");
print("<th>Identificador</th>");
print("<th>Anchura</th>");
print("<th>Descripción</th>");

$resultado = $MySQLiconn->query("SELECT * FROM bandaseguridad where baja=0 ");
while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td>&nbsp &nbsp<a href="?acti=<?php echo $rows["IDBanda"]; ?>"" ><IMG src="../pictures/unnamed.png" title='reactivar'></a>  
    <a href="?delfin=<?php echo $rows["IDBanda"]; ?>" onclick="return confirm('¿Seguro que desea eliminar definitivamente?(Eso es mucho tiempo)'); " ><IMG src="../pictures/definitivo.png" title='eliminar definitivamente'></a></td>
<?php
//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='identificador'>".$rows["identificador"]."</td>");
print("<td title='Anchura'>".$rows["anchura"]."</td>");
print("<td title='Descripción'>".$rows["nombreBanda"]."</td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar' >Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();

?>