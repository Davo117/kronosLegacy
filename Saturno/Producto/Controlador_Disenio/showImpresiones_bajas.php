<?php
print("<table  width='55%'' border='1' cellpadding='15' style='margin-left:200px'>");
print("<th>Acciones</th>");
print("<th>Codigo</th>");
print("<th>Descripción</th>");

include_once 'db_Producto.php';
$q=$_GET['q'];
$resultado = $MySQLiconn->query("SELECT * FROM impresiones where baja=0 && descripcionDisenio='$q'");
while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td>&nbsp &nbsp<a href="?acti=<?php echo $rows["id"]; ?>"" ><IMG src="../Pictures/unnamed.png" title='Modificar'></a>  
    <a href="?delfin=<?php echo $rows["id"]; ?>" onclick="return confirm('¿Seguro que desea eliminar definitivamente?(eso es mucho tiempo)'); " ><IMG src="../Pictures/definitivo.png" title='eliminar'></a></a> </td>
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