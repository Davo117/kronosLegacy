<?php

echo "<th></th>";
echo "<th style='width:100px'>Municipios</th>";

//Mientras que se tengan resultados se le asignan a $rows mediante 

$resultado = $MySQLiconn->query("SELECT * FROM estado  ORDER BY baja DESC, nombreEstado ASC;");

while ($rows = $resultado->fetch_array()) {
	$query=$MySQLiconn->query("SELECT count(nombreCity) as cant FROM ciudad WHERE estado='".$rows['id']."' AND baja=1");

	$row=$query->fetch_array();
	//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
	echo "<tr>";
	//asignamos el atributo value con el id al que corresponde cada registro ?>
	<?php
	//Traemos la tabla y la imprimimos debidamente donde pertenece 

	echo "<td><p>Estado: <b>".$rows["nombreEstado"].", ".$rows["pais"]."</b>"; 
	
	if ($rows['baja']=='1') { ?>

		<a href="?desE=<?php echo $rows["id"]; ?>"  style="float: right; margin-right: 10%; "><IMG src="funciones/img/deletee.png" title='Desactivar'></a>

		<a href="?ciudad=<?php echo $rows["id"]; ?>" style="float: right; margin-right: 5%; "><IMG src="funciones/img/city.png" title='Ciudad'></a>

		<?php
	} else {?>
		<a href="?actiE=<?php echo $rows["id"]; ?>"  style="float: right; margin-right: 10%; "><IMG src="../pictures/unnamed.png" title='Activar'></a>
		<?php 
	} ?>
</p>
<?php
echo "<p>Abreviatura:<b>".$rows["abreviatura"]."</b></p></td>";

echo "<td ><p>".$row['cant']." &nbsp</p></td>";
echo "</tr>";
}
$resultado->close();
?>