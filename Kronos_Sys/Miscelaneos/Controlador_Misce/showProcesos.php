<?php
include ("db_Producto.php");
echo "<table   cellpadding='15' style='margin-left:200px'>
<th style='width:150px;'>Acciones</th> <th>Identificador</th> <th>Descripcion del proceso</th> <th>Numero de proceso</th>";

$q=$_GET['q'];
$_SESSION['procesoProd']=$_GET['q'];

$resultado = $MySQLiconn->query("SELECT * from juegoprocesos where identificadorJuego='$q'");
while ($rows = $resultado->fetch_array()) {
	echo "<tr>"; ?>
	<td>
	<?php if($rows['baja']==1){ ?>
		<a href="?edit=<?php echo $rows["id"]; ?>" ><IMG src="../pictures/modificarproducto.png" title='Modificar'></a>  
    	<a href="?del=<?php echo $rows["id"]; ?>"><IMG src="../pictures/deletProducto.png" title='eliminar'></a>
		<?php
	/*	if ($rows["numeroProceso"]!='0') { ?>
    		<a style="padding-top:10px;" href="?parameter=<?php echo $rows["descripcionProceso"]; ?>&processo=<?php echo $rows["identificadorJuego"]; ?>"><IMG src="../pictures/cd.png" title='Parametros por proceso'></a>
			<?php
		}*/
	}
	if($rows["baja"]==0){ ?>
 		<a style="padding-top:10px;" href="?acti=<?php echo $rows["id"]; ?>"><IMG src="../pictures/unnamed.png" title='Reactivar proceso'></a>
 		<a href="?delfin=<?php echo $rows["id"]; ?>"><IMG src="../pictures/definitivo.png" title='eliminar'></a>
		<?php
	} ?>
	</td>

	<?php
	//Traemos la tabla y la imprimimos donde pertenece 
	print("<td title='identificador'>".$rows["identificadorJuego"]."</td>");
	print("<td title='descrpcion'>".$rows["descripcionProceso"]."</td>");
	print("<td title='numero de proceso'>".$rows["numeroProceso"]."</td>");
	print("</tr>");
}
//Contar registros:
$row_cnt=$resultado->num_rows;
print("<p class='ordenMedio'>Procesos</p>");
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros</p>");
$resultado->close(); ?>