<?php
echo "<table border='0' cellpadding='15' class='table table-hover'>
<th style='width: 11%;'>Acciones</th> <th style='width: 35%;'>Máquina</th> <th>Descripción</th>";

//Mientras que se tengan resultados se le asignan a $rows mediante 

$resultado = $MySQLiconn->query("SELECT * FROM $maquina where baja=1  ORDER BY idMaq DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
echo"<tr>";
//asignamos el atributo value con el idMaq al que corresponde cada registro
?>
<td><a href="?edit=<?php echo $rows["idMaq"]; ?>"><IMG src="funciones/img/modify.png" title='Modificar'></a> 

<a href="?del=<?php echo $rows["idMaq"]; ?>" onclick="return confirm('<?php print("Deseas DESACTIVAR la Máquina: ".$rows["descripcionMaq"]." ?"); ?>');" ><IMG src="funciones/img/delete.png" title='Desactivar'></a></td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 

print("<td> Máquina: ".$rows["codigo"]."<br>Subproceso: ". $rows["subproceso"]. "</td>");
print("<td title='Descripción'>".$rows["descripcionMaq"]."</td>");
echo "</tr>";
}

//Contar registros:
$row_cnt=$resultado->num_rows;
echo "<h4 class='ordenMedio' id='mostrar'>Máquinas Activas: ".$row_cnt."</h4>";
$resultado->close();
?>