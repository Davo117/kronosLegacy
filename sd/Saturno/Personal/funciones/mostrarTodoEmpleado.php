<?php

print("<th>&nbsp Opciones &nbsp</th>");
print("<th># Empleado</th>");
print("<th>Nombre Completo</th>");
print("<th>Teléfono</th>");

//Mientras que se tengan resultados se le asignan a $rows mediante 

$resultado = $MySQLiconn->query("SELECT * FROM $tablaem where baja=0  ORDER BY ID DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
print("<tr>");
//asignamos el atributo value con el id al que corresponde cada registro
?>
<td>&nbsp &nbsp<a href="?activar=<?php echo $rows["ID"]; ?>" onclick="return confirm('<?php print("Deseas ACTIVAR al empleado: ".$rows["Nombre"]." ?"); ?>'); " ><IMG src="funciones/img/activo.png" title='Activar'></a> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
    <a href="?eli=<?php echo $rows["ID"]; ?>" onclick="return confirm('<?php print("Deseas ELIMINAR al empleado: ".$rows["Nombre"]." ?"); ?>');" ><IMG src="funciones/img/deleteDef.png" title='Eliminar'></a></td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 
print("<td title='RFC'>".$rows["numemple"]."</td>");
print("<td title='Nombre'>".$rows["Nombre"]." ".$rows["apellido"]."</td>");
print("<td title='Teléfono'>".$rows["Telefono"]."</td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros Desactivados</p>");
$resultado->close();

?>