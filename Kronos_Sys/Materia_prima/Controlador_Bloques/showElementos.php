<?php
error_reporting(0);
echo "<th style='width:100px;'>acciones</th>
	<th>Identificador</th>
	<th>nombre</th>
	<th>Unidad de medida</th>
	<th>U.M. secundaria</th>
	<th>Ingresar precio</th>
	<th>Criterio de control</th>";

$SQL=$MySQLiconn->query("SELECT u.nombreUnidad as unidad,(SELECT nombreUnidad from saturno.unidades where idUnidad=p.unidad2) as unidad2,p.hascode,p.producto as id,p.id as idM,p.hasprice,p.criterio FROM saturno.unidades u inner join obelisco.productosCK p on p.unidad=u.idUnidad where p.baja=1");
$row_count = 0; 
	/* 

$query="SELECT * FROM admproductos order by CIDPRODUCTO desc";
$resultado = sqlsrv_query($SQLconn, $query);

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
<td><a href="?edit=<?php echo $rows['CIDPRODUCTO'];?>" ><IMG src="../pictures/modificarproducto.png" title='Modificar'></a>  
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
*/   

while ($rows =  $SQL->fetch_array()) {
$query="SELECT CNOMBREPRODUCTO as nombreElemento,CCODIGOPRODUCTO as identificadorElemento FROM admproductos WHERE CIDPRODUCTO='".$rows['id']."'";
$resultado = sqlsrv_query($SQLconn, $query);
$rowSQL = (sqlsrv_fetch_array($resultado,SQLSRV_FETCH_ASSOC));
echo "<tr>";
//Traemos la tabla y la imprimimos donde pertenece 
if(isset($_GET['edit']) && $_GET['edit']==$rows['id'])
{
	
?>
   <td class="selected"><a href="?delfin=<?php echo $rows['idM'].'&idp='.$rows['id']; ?>" ><IMG src="../pictures/deletProducto.png" title='eliminar'></a></a> 
<?php

	echo "<td class='selected' title='identificador'>".$rowSQL["identificadorElemento"]."</td>
	<td class='selected' title='nombre'>".$rowSQL["nombreElemento"]."</td>
	<td class='selected' title='unidad'>".$rows["unidad"]."</td>
	<td class='selected' title='unidad'>".$rows["unidad2"]."</td>";

	/*
	if($rows["hascode"]==1)
	{
		echo "<td class='selected' title='unidad'><IMG class='img-responsive' width='40' height='40' src='../pictures/codigo_si.png' title='eliminar'></td>";
	}
	echo "</tr>"; */
	
}
else
{
	//<a href="?edit=<?php echo $rows['idM'];><IMG src="../pictures/modificarproducto.png" title='Modificar'></a>  
?>
<!-- Botones para eliminar y modificar   -->
<td>
    <a href="?delfin=<?php echo $rows['idM'].'&idp='.$rows['id']; ?>" ><IMG src="../pictures/deletProducto.png" title='eliminar'></a></a> 
    <!-- boton modificar -->
    <a href="?edit=<?php echo $rows["id"]; ?>"><IMG src="../pictures/modificarproducto.png" title='Modificar'></a>

<?php
	echo "<td title='identificador'>".$rowSQL["identificadorElemento"]."</td>
	<td title='nombre'>".$rowSQL["nombreElemento"]."</td>
	<td title='clave_sat'>".$rows["unidad"]."</td>
	<td class='selected' title='unidad'>".$rows["unidad2"]."</td>";
	
	if($rows["hasprice"]==1)
	{
		echo "<td class='selected' title='unidad'><IMG class='img-responsive' width='40' height='40' src='../pictures/precio_si.png' title='eliminar'></td>";
	}
	else
	{
		echo "<td class='selected' title='unidad'><IMG class='img-responsive' width='40' height='40' src='../pictures/precio_no.png' title='eliminar'></td>";
	}
	if($rows['criterio']==1)
	{
		$crit="Por explosión";
	}
	else if($rows['criterio']==2)
	{
		$crit="Máximos y mínimos";
	}
	else
	{
		$crit="Sin criterio";
	}
	echo "<td>".$crit."</td>";
	echo "</tr>";
}
$row_count++;
}
echo "<p id='mostrar'>Se muestran: ".$row_count." Registros Activos</p>";
//sqlsrv_free_stmt($resultado);
//sqlsrv_close($SQLconn);
?>