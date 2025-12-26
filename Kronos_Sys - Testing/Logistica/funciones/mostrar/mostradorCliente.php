<?php
include('../../../Database/db.php');
//Mientras que se tengan resultados se le asignan a $rows mediante 
//ORDER BY ID DESC, ASC;
$resultado=$MySQLiconn->query("SELECT * FROM $tablacli where bajacli=1 ORDER BY ID DESC;");

if(isset($_GET['em'])){
	$q=urldecode($_GET['em']);
	$resultado = $MySQLiconn->query("SELECT * FROM $tablacli where bajacli=1 && (rfccli LIKE '%$q%' OR nombrecli LIKE '%$q%' OR telcli LIKE '%$q%') ORDER BY ID DESC;");
}

if ($resultado->num_rows>0) {
echo "<table border='0' cellpadding='0' class='table table-hover'>";
echo"<th>Acciones</th> <th>RFC</th> <th>Cliente</th> <th>Teléfono</th>";
while ($rows=$resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
//asignamos el atributo value con el id al que corresponde cada registro ?>
<tr>
<td> <a href="?edit=<?php echo $rows["ID"]; ?>" ><IMG src="funciones/img/modify.png" title='Modificar'></a>
&nbsp; 
<a href="?del=<?php echo $rows["ID"]; ?>" ><IMG src="funciones/img/delete.png" title='Desactivar'></a>
&nbsp; &nbsp; 
<a href="?combocon=<?php echo $rows["ID"]; ?>"><IMG src="funciones/img/contacto.png" title='Contacto'></a>

<a href="?combosuc=<?php echo $rows["ID"]; ?>"><IMG src="funciones/img/suc.png" title='Sucursal'></a>
</td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 
echo "<td title='RFC'>"; echo $rows["rfccli"]."</td>
<td title='Nombre'>"; echo $rows["nombrecli"]."</td>
<td title='Teléfono'>"; echo $rows["telcli"]."</td>
</tr>";
}

//Contar registros:
$row_cnt=$resultado->num_rows;
echo "<h4 class='ordenMedio'>Clientes Activos</h4>
<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>";
$resultado->close(); 
	echo"</table>";
}
else {
	print("<h4 class='ordenMedio'>No existen Registros</h4>");
} ?>