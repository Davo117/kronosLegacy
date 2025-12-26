<?php
print("<table  width='55%'' border='1' cellpadding='15' style='margin-left:200px'>");
print("<th>Acciones</th>");
print("<th>Elemento</th>");
print("<th>Cantidad</th>");

include_once 'db_Producto.php';
$q=$_GET['e'];
$MySQLiconn->query("UPDATE cache set dato='$q' where id=2");
$resulto = $MySQLiconn->query("SELECT consPre FROM producto where baja=1 && descripcion='$q'");
$rowes = $resulto->fetch_array();
if($rowes['consPre']==1)
{

$resultado = $MySQLiconn->query("SELECT * FROM consumos where baja=1 ");
while ($rows = $resultado->fetch_array()) {
print("<tr>");

?>
<td>&nbsp &nbsp<a href="?edit=<?php echo $rows["IDConsumo"]; ?>"" ><IMG src="../pictures/modificarproducto.png" title='Modificar'></a>  
    <a href="?del=<?php echo $rows["IDConsumo"]; ?>" onclick="return confirm('sure to delete !'); " ><IMG src="../pictures/deletProducto.png" title='eliminar'></a></a></td>
<?php
//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='Elemento'>".$rows["elemento"]."</td>");
print("<td title='Cantidad'>".$rows["consumo"]."</td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar' >Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();
}
else
{
	print("<td title='Elementos'>".'Este producto no tiene consumos predeterminados'."</td>");
}
print("</table>");
?>
