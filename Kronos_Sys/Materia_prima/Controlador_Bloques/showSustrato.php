<?php

print("<th style='width:100px;' >Acciones</th>");
print("<th>Código</th>");
print("<th>Descripción</th>");

$resultado = $MySQLiconn->query("SELECT * FROM sustrato where baja=1 order by idSustrato desc");
while ($rows = $resultado->fetch_array()) {
	$stau=$MySQLiconn->query("SELECT count(referenciaLote) as prod FROM lotes WHERE bloque='".$rows['descripcionSustrato']."' and estado=2 or bloque='".$rows['descripcionSustrato']."' and estado=1");
	$rim=$stau->fetch_array();
	if($rim['prod']>0)
	{
		print("<tr>");
?>
<td><a href="?edit=<?php echo $rows["idSustrato"]; ?>"" ><IMG src="../pictures/modificarproducto.png" title='Modificar'></a> 
<?php
	}
	else
	{
			print("<tr>");
?>
<td><a href="?edit=<?php echo $rows["idSustrato"]; ?>"" ><IMG src="../pictures/modificarproducto.png" title='Modificar'></a>  
    <a href="?del=<?php echo $rows["idSustrato"]; ?>"><IMG src="../pictures/deletProducto.png" title='eliminar'></a></td>
<?php
	}

//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='Código'>".$rows["codigoSustrato"]."</td>");
print("<td title='Descripción'>".$rows["descripcionSustrato"]."</td>");
print("</tr>");
}
//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();
?>