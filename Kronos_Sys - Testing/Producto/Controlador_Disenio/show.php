<?php
echo "<th style='width:150px;'>Acciones</th>
	<th>Codigo</th>
	<th>Descripción</th>
	<th>Tipo</th>";


$resultado = $MySQLiconn->query("SELECT DISTINCT p.descripcion,p.ID,p.tipo,p.codigo, pr.estado as prod,t.tipo FROM producto p left join tbproduccion pr on pr.disenio=p.ID
INNER JOIN tipoproducto t ON p.tipo=t.id where p.baja=1 and t.baja=1  GROUP BY p.ID order by p.id desc");

while ($rows = $resultado->fetch_array()) {
echo "<tr>";

if(isset($_GET['edit']) && $_GET['edit']==$rows['ID'])
{
	?>
	<td class='selected'>
		<?php 
		if($rows['prod']!=2)
		{?>
   <a href="?del=<?php echo $rows["ID"];?>"><IMG src='../pictures/deletProducto.png' title='eliminar'></a></a>
   <?php
}?>
    <a href="?imp=<?php echo $rows["ID"]; ?>"><IMG src="../pictures/impresion.png" title='impresiones'></a>
     
<?php
//Traemos la tabla y la imprimimos donde pertenece 
echo "<td class='selected'  title='Codigo'>".$rows["codigo"]."</td>
	<td class='selected' title='Descripción'>".$rows["descripcion"]."</td>
	<td class='selected' title='Tipo'>".$rows["tipo"]."</td>
	</tr>";
}
else
{
	?>
	<td><a href="?edit=<?php echo $rows["ID"]; ?>"" ><IMG src="../pictures/modificarproducto.png" title='Modificar'></a>  
    <?php 
		if($rows['prod']!=2)
		{?>
    <a href="?del=<?php echo $rows["ID"];?>"><IMG src='../pictures/deletProducto.png' title='eliminar'></a></a>
<?php }?>
    <a href="?imp=<?php echo $rows["ID"]; ?>"><IMG src="../pictures/impresion.png" title='impresiones'></a>
    
<?php
//Traemos la tabla y la imprimimos donde pertenece 
echo "<td title='Codigo'>".$rows["codigo"]."</td>
	<td title='Descripción'>".$rows["descripcion"]."</td>
	<td title='Tipo'>".$rows["tipo"]."</td>
	</tr>";
}


}

//Contar registros:
$row_cnt=$resultado->num_rows;
echo "<p id='mostrar' >Se muestran: ".$row_cnt."   Registros Activos</p>";
$resultado->close();
?>
