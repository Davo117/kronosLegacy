<?php
include_once '../../Database/db.php';
echo '<table border="0" class="table table-hover"> <th></th>';
$q=$_GET['q'];

//Mientras que se tengan resultados se le asignan a $rows mediante 

$mostrar=$MySQLiconn->query("SELECT * from estado where id='$q'");
$estado=$mostrar->fetch_array();
$resultado = $MySQLiconn->query("SELECT * FROM ciudad where estado='$q' ORDER BY id DESC;");

while ($rows = $resultado->fetch_array()) {
	//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
	echo "<tr>";
	//asignamos el atributo value con el id al que corresponde cada registro ?>
	<?php
	//Traemos la tabla y la imprimimos debidamente donde pertenece 

	echo "<td><p>Ciudad: ".$rows["nombreCity"].", ".$estado['nombreEstado'].", ".$estado['pais']." &nbsp";
	if ($rows['baja']=='1') { ?>

		<a href="?desC=<?php echo $rows["id"]; ?>"  style="float: right; margin-right: 10%; "><IMG src="funciones/img/deletee.png" title='Desactivar'></a>
		<?php
	} else {?>
		<a href="?actiC=<?php echo $rows["id"]; ?>"  style="float: right; margin-right: 10%; "><IMG src="../pictures/unnamed.png" title='Activar'></a>
		<?php 
	} ?>
	</p>
	</td>
	</tr><?php
}
$resultado->close(); ?>