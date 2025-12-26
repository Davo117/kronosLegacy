<?php
include("Controlador_misce/db_Producto.php");

//Mientras que se tengan resultados se le asignan a $rows mediante 
$r=$_POST['r'];

$primera = $MySQLiconn->query("SELECT * from juegoprocesos where identificadorJuego='".$r."' && baja=1 && numeroProceso!=0 ORDER BY id");
while($row1 = $primera->fetch_array()){ ?>
	<option value="<?php echo $row1['descripcionProceso'];?>"><?php echo $row1['descripcionProceso'];?></option>
	<?php 
} ?> 
</datalist>
</select>