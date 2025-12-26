<?php
echo "<th>Acciones</th> <th>RFC</th> <th>Cliente</th> <th>Teléfono</th>";

//Mientras que se tengan resultados se le asignan a $rows mediante 

$resultado= $MySQLiconn->query("SELECT * FROM $tablacli where bajacli=0  ORDER BY ID DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
//asignamos el atributo value con el id al que corresponde cada registro ?>
<tr>
<td><a href="?activar=<?php echo $rows["ID"]; ?>" ><IMG src="funciones/img/activo.png" title='Activar'></a> 
&nbsp; &nbsp; 
<a href="?eli=<?php echo $rows["ID"]; ?>" ><IMG src="funciones/img/deleteDef.png" title='Eliminar'></a></td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 
echo "<td title='RFC'>"; echo $rows["rfccli"]; echo "</td>
<td title='Nombre'>"; echo $rows["nombrecli"]; echo "</td>
<td title='Teléfono'>"; echo $rows["telcli"]; echo "</td>
</tr>";
}

//Contar registros:
$row_cnt=$resultado->num_rows;
echo "<h4 class='ordenMedio'>Clientes Inactivos</h4>
<p id='mostrar'>Se muestran: ".$row_cnt." Registros Desactivados</p>";
$resultado->close();
?>