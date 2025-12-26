<?php

print("<th>&nbsp Opciones &nbsp</th>");
print("<th>ID</th>");
print("<th>Nombre</th>");
print("<th>Teléfono</th>");


//Mientras que se tengan resultados se le asignan a $rows mediante 

$resultado = $MySQLiconn->query("SELECT * FROM $tablasucursal where bajasuc=0 ORDER BY idsuc DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
print("<tr>");
//asignamos el atributo value con el id al que corresponde cada registro
?>
<td><a href="?activar=<?php echo $rows["idsuc"]; ?>" onclick="return confirm('<?php print("Deseas ACTIVAR la Sucursal: ".$rows["nombresuc"]." ?"); ?>'); " ><IMG src="funciones/img/activo.png" title='Activar'></a> 
&nbsp 
    <a href="?eli=<?php echo $rows["idsuc"]; ?>" onclick="return confirm('<?php print("Deseas ELIMINAR la Sucursal: ".$rows["nombresuc"]." ?"); ?>');" ><IMG src="funciones/img/deleteDef.png" title='Eliminar'></a></td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 
print("<td title='ID'>".$rows["idsuc"]."</td>");
print("<td title='Nombre'>".$rows["nombresuc"]."</td>");
print("<td title='Teléfono'>".$rows["telefonosuc"]."</td>");

print("</tr>");
}
 

 //Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros Desactivados</p>");
$resultado->close();

?>