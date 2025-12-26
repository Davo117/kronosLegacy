<?php
echo "<th style='width:220px;'>Acciones</th>
	 <th>Bloque</th>
     <th>Sustrato</th>
     <th style='width:120px;' >Peso</th>";

$resultado = $MySQLiconn->query("SELECT * FROM bloquesmateriaprima where baja=0 order by idBloque desc");
while ($rows = $resultado->fetch_array()) {
echo "<tr>";
?>
<td>&nbsp &nbsp<a href="?acti=<?php echo $rows["idBloque"]; ?>"" ><IMG src="../pictures/unnamed.png" title='Reactivar'></a>  
    <a href="?delfin=<?php echo $rows["idBloque"]; ?>" onclick="return confirm('sure to delete !'); " ><IMG src="../pictures/definitivo.png" title='eliminar definitivamente'></a></a></td>
<?php
//Traemos la tabla y la imprimimos donde pertenece 
		echo "<td title='Bloque'>".$rows["identificadorBloque"]."&nbsp; | &nbsp;".$rows["nombreBloque"]."</td>
		 <td title='Sustrato'>".$rows["sustrato"]."</td>
		 <td title='Peso'>".number_format($row["peso"],2)." "."kgs.</td>
		</tr>";
		$MySQLiconn->query("UPDATE bloquesmateriaprima set peso=".$row['peso']." where nombreBloque=".$rows['nombreBloque']."");
	}

//Contar registros:
	$row_cnt=$resultado->num_rows;
	echo "<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>";
	$resultado->close();
?>
