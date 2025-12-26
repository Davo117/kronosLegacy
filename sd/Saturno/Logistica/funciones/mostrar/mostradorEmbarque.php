<?php
print("<th>&nbsp Opciones &nbsp</th>");
print("<th>ID</th>");
print("<th>Número</th>");
print("<th>Fecha Embarque</th>");
print("<th>O.C.</th>");
print("<th>Producto</th>");
print("<th>Sucursal</th>");
print("<th>Referencia</th>");

//idEmbarque numEmbarque transpEmb referencia diaEmb observaEmb bajaEmb sucEmbFK prodEmbFK empaque
//Mientras que se tengan resultados se le asignan a $rows mediante 

$resultado = $MySQLiconn->query("SELECT * FROM $embarq, $tablaOrden where bajaEmb=1  and sucEmbFK=sucFK  ORDER BY idEmbarque DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
print("<tr>");
//asignamos el atributo value con el id al que corresponde cada registro
?>
<td>&nbsp <a href="?edit=<?php echo $rows["idEmbarque"]; ?>" onclick="return confirm('<?php print("Deseas EDITAR el Embarque: ".$rows["numEmbarque"]." ?"); ?>'); " ><IMG src="funciones/img/modify.png" title='Modificar'></a> &nbsp 
 <a href="?del=<?php echo $rows["idEmbarque"]; ?>" onclick="return confirm('<?php print("Deseas DESACTIVAR el Embarque: ".$rows["numEmbarque"]." ?"); ?>');" ><IMG src="funciones/img/delete.png" title='Desactivar'></a>
 </td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 
print("<td title='ID'>".$rows["idEmbarque"]."</td>");
print("<td title='Número'>".$rows["numEmbarque"]."</td>");
print("<td title='Fecha Embarque'>".$rows["diaEmb"]."</td>");
print("<td title='O.C.'>".$rows["orden"]."</td>");
print("<td title='Producto'>".$rows["prodEmbFK"]."</td>");
print("<td title='Sucursal'>".$rows["sucEmbFK"]."</td>");
print("<td title='Referencia'>".$rows["referencia"]."</td>");
print("</tr>");
}
// que otro campo puede ir ahi en vez de el id de esa tabla :C ededededed
 //Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();
?>