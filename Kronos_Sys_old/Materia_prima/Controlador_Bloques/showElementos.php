<?php
echo "<th style='width:100px;'>acciones</th>
	<th>Identificador</th>
	<th>nombre</th>
	<th>clasificaciones</th>
	<th>clave sat</th>";

	

$query="SELECT * FROM admproductos order by CIDPRODUCTO desc";
$resultado = sqlsrv_query($SQLconn, $query);
$row_count = 0;
while ($rows = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {

echo "<tr>";
//Traemos la tabla y la imprimimos donde pertenece 
if(isset($_GET['edit']) && $_GET['edit']==$rows['CIDPRODUCTO'])
{
	
?>
   <td class="selected"><a href="?del=<?php echo $rows['CIDPRODUCTO']; ?>" ><IMG src="../pictures/deletProducto.png" title='eliminar'></a></a> 
<?php
	echo "<td class='selected' title='identificador'>".$rows["CCODIGOPRODUCTO"]."</td>
	<td class='selected' title='nombre'>".$rows["CNOMBREPRODUCTO"]."</td>
	<td class='selected' title='unidad'>".$rows["CCLAVESAT"]."</td>
	</tr>";
}
else
{
?>
<td><a href="?edit=<?php echo $rows['CIDPRODUCTO'];?>"" ><IMG src="../pictures/modificarproducto.png" title='Modificar'></a>  
    <a href="?del=<?php echo $rows['CIDPRODUCTO']; ?>" ><IMG src="../pictures/deletProducto.png" title='eliminar'></a></a> 
<?php
	echo "<td title='identificador'>".$rows["CCODIGOPRODUCTO"]."</td>
	<td title='nombre'>".$rows["CNOMBREPRODUCTO"]."</td>
	<td title='Clasificaciones'>1-".$rows["CIDVALORCLASIFICACION1"]."<br>2-".$rows["CIDVALORCLASIFICACION2"]."<br>3-".$rows["CIDVALORCLASIFICACION3"]."<br>4-".$rows["CIDVALORCLASIFICACION4"]."</td>
	<td title='clave_sat'>".$rows["CCLAVESAT"]."</td>
	</tr>";
}
$row_count++;
}


echo "<p id='mostrar'>Se muestran: ".$row_count." Registros Activos</p>";
sqlsrv_free_stmt($resultado);
sqlsrv_close($SQLconn);
?>