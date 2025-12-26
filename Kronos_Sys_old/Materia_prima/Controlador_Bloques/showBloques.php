<?php

echo "<th style='width:20%;' >Acciones</th>
	 <th>Bloque</th>
     <th style='width:15%'>Anchura</th>
     <th style='width:10%'>Lotes (Total)</th>
     <th style='width:120px;' >Peso</th>";

$resultado = $MySQLiconn->query("SELECT * FROM sustrato where baja=1 order by idSustrato desc ");
while ($rows = $resultado->fetch_array()) {
	$MYS=$MySQLiconn->query("SELECT sum(peso) as peso,count(referenciaLote) as num  from lotes where bloque='".$rows['descripcionSustrato']."'");
	$row=$MYS->fetch_array();
	echo "<tr>";
	?>

	<td>
		<a href="?lot=<?php echo $rows["descripcionSustrato"]; ?>"><IMG src="../pictures/boxLote.png" title='Lotes de materia prima'></a>
		<a id="excel" href="?excel=<?php echo $rows["descripcionSustrato"]; ?>"><IMG src="../pictures/excel.png" title='subir lotes'></a>
		<a  target="_blank" href="funciones/pdfReporteLotes.php?cdgbloque=<?php echo $rows['descripcionSustrato'];?>"><IMG src="../pictures/reports.png" title='Reporte de lotes'></a>
		<a target="_blank" href="funciones/pdfBarCodeBloque.php?cdgbloque=<?php echo $rows["descripcionSustrato"]; ?>"><IMG src="../pictures/barras.png" title='Etiquetas'></a></td>
		<?php
//Traemos la tabla y la imprimimos donde pertenece 
		echo "<td nowrap title='Bloque'><b style='font:bold 15px Sansation Light'>".$rows["descripcionSustrato"]."&nbsp;</b><br>".$rows["codigoSustrato"]."</td>

		 <td title='Anchura'>".$rows["anchura"]."</td>
		 <td title='Cantidad de lotes por bloque'>".$row["num"]."</td>
		 <td title='Peso'>".number_format($row["peso"],2)." "."kgs.</td>
		</tr>";
		if(isset($_POST['checkTodos']))
		{
			$TAM=$MySQLiconn->query("SELECT tarima as numT from lotes where bloque='".$rows['descripcionSustrato']."' group by tarima");
		if($TAM->num_rows>0)
		{
			echo "<th style=''></th><th style=''>Tarimas (".$TAM->num_rows.")<tr>
		<td style=''></td><td>";
		while($rw=$TAM->fetch_array())
		{
			echo $rw['numT']."<br>";
		}
		echo "</td></tr>";
		}
		echo "<th style=''></th>";
		
		$MySQLiconn->query("UPDATE bloquesmateriaprima set peso=".$row['peso']." where sustrato=".$rows['descripcionSustrato']."");

		$TIM=$MySQLiconn->query("SELECT descripcionImpresion as numT,codigoImpresion from impresiones where sustrato='".$rows['descripcionSustrato']."'");
		if($TIM->num_rows>0)
		{
		echo "<th style=''>Productos enlazados (".$TIM->num_rows.")<tr>
		<td style=''></td><td>";
		while($ruw=$TIM->fetch_array())
		{
			echo '*'.$ruw['numT'].' ['.$ruw['codigoImpresion'].']'."<br>";
		}
		echo "</td></tr>";
		}
		echo "<th style=''></th>";
		}
		
	}

//Contar registros:
	$row_cnt=$resultado->num_rows;
	echo "<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>";
	$resultado->close();


	?>


