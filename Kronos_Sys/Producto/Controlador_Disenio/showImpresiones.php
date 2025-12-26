
<table border='0' cellpadding='15' class="table table-hover">
<th style='width: 20%'>Acciones</th>
<th>Codigo</th>
<th>Descripción</th>
<?php
include'db_Producto.php';
if(!empty($_GET['q']))
{
	$q=$_GET['q'];

//$MySQLiconn->query("INSERT into cache(dato) values('$q')");
$resultado = $MySQLiconn->query("
SELECT DISTINCT i.descripcionImpresion,i.id,i.codigoImpresion,p.cilindros,p.cireles,p.suaje FROM impresiones i
RIGHT JOIN producto p on p.id=i.descripcionDisenio
where i.baja=1 && i.descripcionDisenio='$q' && p.baja=1");
while ($rows = $resultado->fetch_array()) {
	$pr=$MySQLiconn->query("SELECT estado FROM tbproduccion WHERE estado=2 and nombreProducto='".$rows['id']."'");
	$isnpro=$pr->fetch_array();
	echo "<tr>";
	?>
	<td ><a href="?edit=<?php echo $rows["id"]; ?>"" ><IMG src="../pictures/modificarproducto.png" title='Modificar'></a> 
	<?php
if(empty($isnpro['estado']))
	{?>
    <a href="?del=<?php echo $rows["id"]; ?>"><IMG src="../pictures/deletProducto.png" title='eliminar'></a></a> 
    <?php
}
if($rows['cilindros']==1)
{?>
	 <a href="Producto_JuegosCilindros.php?cil=<?php echo $rows["id"]; ?>"><IMG src="../pictures/rodillo.png" title='Juegos de cilindros'></a>
	 <?php
}
if($rows['cireles']==1)
{?>
	 <a href="?imp=<?php echo $rows["id"]; ?>"><IMG src="../pictures/cireles.png" title='Juegos de cireles'></a>
<?php
}
if($rows['suaje']==1)
{?>
	<a href="?suaje=<?php echo $rows["id"]; ?>"><IMG src="../pictures/puzzle.png" title='suaje'></a><?php
}
?>
 <a href="?cons=<?php echo $rows["id"]; ?>"><IMG src="../pictures/consumos2.png" title='Ir a consumos'></a>
 <a href="?uploadc=<?php echo $rows["id"]; ?>"><img src="../pictures/upload_consumos.png" title="Cargar consumos" width="30" height="30"></a></td>
 </td>

<td title='Codigo'><?php echo $rows["codigoImpresion"]?></td>
<td title='Descripción'><?php echo $rows["descripcionImpresion"]?></td>
</tr>
<?php
}



//Contar registros:
$row_cnt=$resultado->num_rows;
?>
<p id='mostrar' ><?php echo "Se muestran: ".$row_cnt."  Registros Activos"?></p>
</table>
<?php
}

if(!empty($_GET['a']))
{
	$a=$_GET['a'];
$MySQLiconn->query("UPDATE cache set dato='$a' where id=1");
}

?>