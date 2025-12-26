<?php

print("<th>Acciones</th>");
print("<th>Identificador</th>");
print("<th>nombre producto</th>");

$resultado = $MySQLiconn->query("SELECT * FROM productoscliente where baja=1");
while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td>&nbsp &nbsp<a href="?edit=<?php echo $rows["IdProdCliente"]; ?>"" ><IMG src="../pictures/modificarproducto.png" title='Modificar'></a>  
    <a href="?del=<?php echo $rows["IdProdCliente"]; ?>" onclick="return confirm('sure to delete !'); " ><IMG src="../pictures/deletProducto.png" title='eliminar'></a></td>
<?php
//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='Identificador'>".$rows["IdentificadorCliente"]."</td>");
print("<td title='nombre Producto'>".$rows["nombre"]."</td>");
print("</tr>");
}
//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar' >Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();
?>