<?php
session_start();
print("<table width='55%'' border='0' cellpadding='15' style='margin-left:200px''>");
print("<th style='width:150px;'>Acciones</th>");
print("<th>Nombre</th>");
print("<th>Leyenda</th>");
print("<th>Placeholder</th>");
print("<th>Requerido</th>");

include'db_Producto.php';
$q=$_SESSION['envio2'];

if($q=="Termoencogible")
{
$q="Termoencogible";
}
$resultado = $MySQLiconn->query("SELECT* from juegoparametros where baja=1 and identificadorJuego=(select juegoparametros from tipoproducto where alias='$q')");

while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td>&nbsp &nbsp 
    <a href="?del=<?php echo $rows["id"]; ?>" ><IMG src="../pictures/deletProducto.png" title='eliminar'></a></a> 
<?php
//Traemos la tabla y la imprimimos donde pertenece 
$requerido="No";
if($rows['requerido']==1)
{
	$requerido="Sí";
}
print("<td title='Nombre'>".$rows["nombreparametro"]."</td>");
print("<td title='Leyenda'>".$rows["leyenda"]."</td>");
print("<td title='Placeholder'>".$rows["placeholder"]."</td>");
print("<td title='Requerido'>".$requerido."</td>");
print("</tr>");

}


//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar' >Se muestran: ".$row_cnt." Parametros Activos</p>");
$resultado->close();
print("</table>");
//Botón de editar
/*<a href="?edit=<?php echo $rows["id"]; ?>"" ><IMG src="../pictures/modificarproducto.png" title='Modificar'></a> */
?>
