<?php
print("<th style='width:150px;'>Acciones</th>");
print("<th>Nombre Campo</th>");
print("<th>Tipo</th>");


include'db_Producto.php';

$resultado = $MySQLiconn->query("SELECT * FROM parametros");

while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td>&nbsp &nbsp 
    <a href="" onclick="alert('Eliminar un campo puede alterar la estabilidad del sistema,esta acción no esta permitida actualmente')"><IMG src="../pictures/deletProducto.png" title='eliminar'></a></a> 
<?php
//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='Nombre del parametro'>".$rows["nombreParametro"]."</td>");
print("<td title='Tipo de dato'>".$rows["tipo"]."</td>");

print("</tr>");


}


//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar' >Se muestran: ".$row_cnt." Parametros PP Activos</p>");
$resultado->close();
//Botón de editar
/*<a href="?edit=<?php echo $rows["id"]; ?>"" ><IMG src="../pictures/modificarproducto.png" title='Modificar'></a> */
?>
