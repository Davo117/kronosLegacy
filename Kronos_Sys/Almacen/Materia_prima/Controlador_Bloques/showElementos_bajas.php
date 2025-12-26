<?php
echo "<th style='width:100px;'>acciones</th>
	<th>Identificador</th>
	<th>nombre</th>
	<th>unidad</th>";

$resultad = $MySQLiconn->query("SELECT u.nombreUnidad as unidad,p.hascode,p.producto as id,p.id as idM FROM saturno.unidades u inner join obelisco.productosCK p on p.unidad=u.idUnidad where p.baja=0");
while($roms = $resultad->fetch_array()) {
$query="SELECT CNOMBREPRODUCTO as nombreElemento,CCODIGOPRODUCTO as identificadorElemento FROM admproductos WHERE CIDPRODUCTO='".$roms['id']."'";
$resultad = sqlsrv_query($SQLconn, $query);
$rowSQL = sqlsrv_fetch_array($resultad, SQLSRV_FETCH_ASSOC);
print("<tr>");
?>
<td>&nbsp &nbsp<a href="?acti=<?php echo $rows['idM'];?>"" ><IMG src="../pictures/unnamed.png" title='Modificar'></a>  
    <a href="?delfin=<?php echo $roms['idM']; ?>&productos=<?php echo $roms['id'];?>" onclick="return confirm('¿Seguro que desea eliminar definitivamente?(Eso es mucho tiempo)'); " ><IMG src="../pictures/definitivo.png" title='eliminar'></a></a> 
<?php
//Traemos la tabla y la imprimimos donde pertenece 
echo "<td title='identificador'>".$rowSQL["identificadorElemento"]."</td>
	<td title='nombre'>".$rowSQL["nombreElemento"]."</td>
	<td title='unidad'>".$roms["unidad"]."</td>
	</tr>";
}

$row_cnt=$resultado->num_rows;
echo "<p id='mostrar'>Se muestran: ".$row_cnt." Registros Inactivos</p>";
$resultado->close();
?>