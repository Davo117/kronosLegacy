<?php
print("<th style='width: 15%;'>Acciones</th>");
print("<th>Área de Trabajo</th>");

//Mientras que se tengan resultados se le asignan a $rows mediante 

$resultado = $MySQLiconn->query("SELECT * FROM departamento where baja=1;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
print("<tr>");
//asignamos el atributo value con el id al que corresponde cada registro
?>
<td>
<a href="?eliminarDepa=<?php echo $rows["id"]; ?>" ><IMG src="funciones/img/eliminacion.png" title='Eliminar'></a></td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 


print("<td title='Departamento'>".$rows["nombre"]."</td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;
print("<h4 class='ordenMedio'>Departamentos Activos</h4>");
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close(); ?>