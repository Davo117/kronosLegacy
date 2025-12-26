<?php

print("<th>&nbsp Opciones &nbsp</th>");
print("<th>N°</th>");
print("<th>Identificador</th>");
print("<th>Cliente</th>");
print("<th>Teléfono</th>");


//Mientras que se tengan resultados se le asignan a $rows mediante 

$resultado= $MySQLiconn->query("SELECT * FROM $tablacli where bajacli=0  ORDER BY ID DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
print("<tr>");
//asignamos el atributo value con el id al que corresponde cada registro
?>
<td><a href="?activar=<?php echo $rows["ID"]; ?>" onclick="return confirm('<?php print("Deseas ACTIVAR al cliente: ".$rows["nombrecli"]." ?"); ?>'); " ><IMG src="funciones/img/activo.png" title='Activar'></a> 
&nbsp
<a href="?eli=<?php echo $rows["ID"]; ?>" onclick="return confirm('<?php print("Deseas ELIMINAR al cliente: ".$rows["nombrecli"]." ?"); ?>');" ><IMG src="funciones/img/deleteDef.png" title='Eliminar'></a></td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 
print("<td title='ID'>".$rows["ID"]."</td>");
print("<td title='RFC'>".$rows["rfccli"]."</td>");
print("<td title='Nombre'>".$rows["nombrecli"]."</td>");
print("<td title='Teléfono'>".$rows["telcli"]."</td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros Desactivados</p>");
$resultado->close();

?>