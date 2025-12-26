<?php
include('../../Database/db.php');

$resultado = $MySQLiconn->query("SELECT * FROM $tablaem where baja=1  ORDER BY ID DESC;");
// $var='';

if(isset($_GET['em'])){
	$q=urldecode($_GET['em']);
	$resultado = $MySQLiconn->query("SELECT * FROM $tablaem where baja=1 && (numemple LIKE '%$q%' OR Nombre LIKE '%$q%' OR apellido LIKE '%$q%' OR telefono LIKE'%$q%') ORDER BY ID DESC;");
}
//Ver ?URL
//print('<br>'.$_SERVER['REQUEST_URI']);
//Mientras que se tengan resultados se le asignan a $rows mediante 
if ($resultado->num_rows>0) {
echo "<table border='0' cellpadding='0' class='table table-hover'>";
print("<th style='width: 15%;'>Acciones</th>");
print("<th style='width: 15%;'># Empleado</th>");
print("<th>Nombre Completo</th>");
print("<th>Datos</th>");
	while ($rows = $resultado->fetch_array()) {
		//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
		//asignamos el atributo value con el id al que corresponde cada registro ?>
		<tr>
		<td><a href="?edit=<?php echo $rows["ID"]; ?>"><IMG src="funciones/img/modify.png" title='Modificar'></a> 
	
		<a href="?del=<?php echo $rows["ID"]; ?>"><IMG src="funciones/img/delete.png" title='Desactivar'></a></td>
		<?php	//Traemos la tabla y la imprimimos debidamente donde pertenece 
		$depa=$MySQLiconn->query("SELECT nombre FROM departamento where id=".$rows["departamento"]);
	
		$rowD = $depa->fetch_array();
		print("<td title='#'>".$rows["numemple"]."</td>");
		print("<td title='Nombre'>".$rows["Nombre"]." ".$rows["apellido"]."<br>Puesto: ".$rows["puesto"]."	</td>");
		print("<td>Telefono: ".$rows["Telefono"]."<br>");
		print("Departamento: ".$rowD["nombre"]."</td>");
		print("</tr>");
	}
	//Contar registros:
	$row_cnt=$resultado->num_rows;
	print("<h4 class='ordenMedio'>Empleados Activos</h4>");
	printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>");
	$resultado->close();
}
else {
 print("<h4 class='ordenMedio'>No existen Registros</h4>");
 } ?>