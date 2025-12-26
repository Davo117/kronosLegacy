<?php
echo "<table border='0' cellpadding='15' class='table table-hover'>
<th style='width: 10%;'>Acciones</th> <th style='width: 280px;'>Máquina</th> <th>Descripción</th>";

//Mientras que se tengan resultados se le asignan a $rows mediante 

$resultado = $MySQLiconn->query("SELECT * FROM $maquina where baja=0  ORDER BY idMaq DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
print("<tr>");
//asignamos el atributo value con el idMaq al que corresponde cada registro
?>
<td><a href="?activar=<?php echo $rows["idMaq"]; ?>" ><IMG src="funciones/img/activo.png" title='Activar'></a>

    <a href="?eli=<?php echo $rows["idMaq"]; ?>" onclick="return confirm('<?php print("Deseas ELIMINAR la Máquina: ".$rows["descripcionMaq"]." ?"); ?>');" ><IMG src="funciones/img/deleteDef.png" title='Eliminar'></a></td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 

print("<td> Máquina: ".$rows["codigo"]."<br>Subproceso: ". $rows["subproceso"]. "</td>");
print("<td title='Descripción'>".$rows["descripcionMaq"]."</td>");
print("</tr>");
}
//Contar registros:
$row_cnt=$resultado->num_rows;
print("<h4 class='ordenMedio' id='mostrar'>Máquinas Inactivas</h4>");
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros Inactivos</p>");
$resultado->close();

?>