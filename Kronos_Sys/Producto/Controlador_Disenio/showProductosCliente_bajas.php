<?php

print("<th style='width:100px;'>Acciones</th>");
print("<th>Identificador</th>");
print("<th>nombre producto</th>");

$resultado = $MySQLiconn->query("SELECT * FROM productoscliente where baja=0 order by IdProdCliente desc");
while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td>&nbsp &nbsp<a href="?acti=<?php echo $rows["IdProdCliente"]; ?>"" ><IMG src="../pictures/unnamed.png" title='Modificar'></a>  
    <a href="?delfin=<?php echo $rows["IdProdCliente"]; ?>" onclick="return confirm('¿Seguro que desea eliminar definitivamente?(eso es mucho tiempo)'); " ><IMG src="../pictures/definitivo.png" title='eliminar'></a></td>
<?php
//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='Identificador'>".$rows["IdentificadorCliente"]."</td>");
print("<td title='nombre Producto'>".$rows["nombre"]."</td>");
print("</tr>");
}
//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar' >Se muestran: ".$row_cnt." Registros inactivos</p>");
$resultado->close();
?>