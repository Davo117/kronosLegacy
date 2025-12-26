<?php
print("<th style='width:150px;'>Acciones</th>");
print("<th>Nombre Campo</th>");
print("<th>Tipo</th>");
print("<th>Valor por default</th>");

include'db_Producto.php';

$resultado = $MySQLiconn->query("SHOW COLUMNS FROM impresiones");

while ($rows = $resultado->fetch_array()) {
	if($rows['Field']!="id" && $rows['Field']!="baja" && $rows['Field']!="DisenioFK" && $rows['Field']!="codigoCliente"  && $rows['Field']!="nombreBanda" && $rows['Field']!="sustrato"){
print("<tr>");
?>
<td>&nbsp &nbsp 
    <a href="" onclick="alert('Eliminar un campo puede alterar la estabilidad del sistema,esta acción no esta permitida actualmente')"><IMG src="../pictures/deletProducto.png" title='eliminar'></a></a> 
<?php
//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='Nombre del parametro'>".$rows["Field"]."</td>");
print("<td title='Tipo de dato'>".$rows["Type"]."</td>");
print("<td title='Valor por default del parametro'>".$rows["Default"]."</td>");
print("</tr>");

}
}


//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar' >Se muestran: ".$row_cnt." Parametros Activos</p>");
$resultado->close();
//Botón de editar
/*<a href="?edit=<?php echo $rows["id"]; ?>"" ><IMG src="../pictures/modificarproducto.png" title='Modificar'></a> */
?>
