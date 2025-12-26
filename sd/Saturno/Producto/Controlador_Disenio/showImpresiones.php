<?php

print("<th>Acciones</th>");
print("<th>Codigo</th>");
print("<th>Descripción</th>");

$desc=$_SESSION['descripcion'];
$resultado = $MySQLiconn->query("SELECT * FROM impresiones where baja=1 && descripcionDisenio='$desc'");
while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td>&nbsp &nbsp<a href="?edit=<?php echo $rows["id"]; ?>"" ><IMG src="../Pictures/modificarProducto.png" title='Modificar'></a>  
    <a href="?del=<?php echo $rows["id"]; ?>" onclick="return confirm('sure to delete !'); " ><IMG src="../Pictures/deletProducto.png" title='eliminar'></a></a> 
     <a href="?cil=<?php echo $rows["descripcionImpresion"]; ?>"><IMG src="../Pictures/rodillo.png" title='Juegos de cilindros'></a></td>
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

?>