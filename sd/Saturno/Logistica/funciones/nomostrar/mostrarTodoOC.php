<?php
print("<th>&nbsp Opciones &nbsp</th>");
print("<th>ID</th>");
print("<th>Orden Compra</th>");
print("<th>Documento</th>");
//Mientras que se tengan resultados se le asignan a $rows mediante 

//idorden	orden	documento	recepcion	bajaOrden	sucFK
$resultado = $MySQLiconn->query("SELECT * FROM $tablaOrden where bajaOrden=0 ORDER BY idorden DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
print("<tr>");
//asignamos el atributo value con el id al que corresponde cada registro
?>
<td><a href="?activar=<?php echo $rows["idorden"]; ?>" onclick="return confirm('<?php print("Deseas ACTIVAR la ORDEN: ".$rows["orden"]." ?"); ?>'); " ><IMG src="funciones/img/activo.png" title='Activar'></a> 
&nbsp
    <a href="?eli=<?php echo $rows["idorden"]; ?>" onclick="return confirm('<?php print("Deseas ELIMINAR la ORDEN: ".$rows["orden"]." ?"); ?>');" ><IMG src="funciones/img/deleteDef.png" title='Eliminar'></a></td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 
print("<td title='ID'>".$rows["idorden"]."</td>");
print("<td title='Orden'>".$rows["orden"]."</td>");
print("<td title='Documento'>".$rows["documento"]."</td>");
print("</tr>");
}


 //Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros Desactivados</p>");
$resultado->close();

?>