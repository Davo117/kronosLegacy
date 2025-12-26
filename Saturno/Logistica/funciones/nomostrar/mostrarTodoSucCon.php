<?php
print("<th>&nbsp Opciones &nbsp</th>");
print("<th>ID</th>");
print("<th>Nombre</th>");
print("<th>Puesto</th>");
print("<th>Telefono</th>");
print("<th>Celular</th>");
print("<th>Correo</th>");

//Mientras que se tengan resultados se le asignan a $rows mediante 
$resultado = $MySQLiconn->query("SELECT * FROM $tablaconsuc where bajaconsuc=0  ORDER BY idconsuc DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
print("<tr>");
//asignamos el atributo value con el id al que corresponde cada registro
?>
<td><a href="?activar=<?php echo $rows["idconsuc"]; ?>" onclick="return confirm('<?php print("Deseas ACTIVAR al Contacto: ".$rows["nombreconsuc"]." ?"); ?>'); " ><IMG src="funciones/img/activo.png" title='Activar'></a> 
&nbsp
    <a href="?eli=<?php echo $rows["idconsuc"]; ?>" onclick="return confirm('<?php print("Deseas ELIMINAR al Contacto: ".$rows["nombreconsuc"]." ?"); ?>');" ><IMG src="funciones/img/deleteDef.png" title='Eliminar'></a></td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 
print("<td title='ID'>".$rows["idconsuc"]."</td>");
print("<td title='Nombre'>".$rows["nombreconsuc"]."</td>");
print("<td title='Puesto'>".$rows["puestoconsuc"]."</td>");
print("<td title='Telefono'>".$rows["telconsuc"]."</td>");
print("<td title='Celular'>".$rows["movilconsuc"]."</td>");
print("<td title='Correo'>".$rows["emailconsuc"]."</td>");
print("</tr>");
}
//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros Desactivados</p>");
$resultado->close();
?>