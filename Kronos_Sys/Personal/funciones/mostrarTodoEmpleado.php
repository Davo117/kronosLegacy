<?php
print("<th style='width: 15%;'>Acciones</th>");
print("<th style='width: 15%;'># Empleado</th>");
print("<th>Nombre Completo</th>");
print("<th>Datos</th>");

//Mientras que se tengan resultados se le asignan a $rows mediante 

$resultado = $MySQLiconn->query("SELECT * FROM $tablaem where baja=0  ORDER BY ID DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
//asignamos el atributo value con el id al que corresponde cada registro?>
<tr>
<td><a href="?activar=<?php echo $rows["ID"]; ?>" ><IMG src="funciones/img/activo.png" title='Activar'></a> 
    <a href="?eli=<?php echo $rows["ID"]; ?>"><IMG src="funciones/img/deleteDef.png" title='Eliminar'></a></td>
<?php
$depa=$MySQLiconn->query("SELECT nombre FROM departamento where id=".$rows["departamento"]);

$rowD = $depa->fetch_array();
//Traemos la tabla y la imprimimos debidamente donde pertenece 
print("<td title='RFC'>".$rows["numemple"]."</td>");
print("<td title='Nombre'>".$rows["Nombre"]." ".$rows["apellido"]."</td>");
print("<td>Telefono: ".$rows["Telefono"]."<br>");
print("Departamento: ".$rowD["nombre"]."</td>");
print("</tr>");
}
//Contar registros:
$row_cnt=$resultado->num_rows;
print("<h4 class='ordenMedio'>Empleados Inactivos</h4>");
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros Desactivados</p>");
$resultado->close(); ?>