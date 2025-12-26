<?php
print("<table  width='55%'' border='1' cellpadding='15' style='margin-left:200px'>");
print("<th>Acciones</th>");
print("<th>Codigo</th>");
print("<th>Descripción</th>");

include_once 'db_Producto.php';
$q=$_GET['q'];
$MySQLiconn->query("UPDATE cache set dato='$q' where id=1");
//$MySQLiconn->query("INSERT into cache(dato) values('$q')");
$resultado = $MySQLiconn->query("SELECT * FROM impresiones where baja=1 && descripcionDisenio='$q'");
while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td>&nbsp &nbsp<a href="?edit=<?php echo $rows["id"]; ?>"" ><IMG src="../pictures/modificarproducto.png" title='Modificar'></a>  
    <a href="?del=<?php echo $rows["id"]; ?>" onclick="return confirm('sure to delete !'); " ><IMG src="../pictures/deletProducto.png" title='eliminar'></a></a> 
     <a href="?cil=<?php echo $rows["descripcionImpresion"]; ?>"><IMG src="../pictures/rodillo.png" title='Juegos de cilindros'></a></td>
<?php
//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='Codigo'>".$rows["codigoImpresion"]."</td>");
print("<td title='Descripción'>".$rows["descripcionImpresion"]."</td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar' >Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();
print("</table>");
?>