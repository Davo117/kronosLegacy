<?php
echo "<br><table border='0' cellpadding='15' class='table table-hover'>
<th>Acciones</th> <th>Devolución</th> <th>Embarque</th> <th>Datos</th>";


//idEmbarque numEmbarque transpEmb referencia diaEmb observaEmb bajaEmb sucEmbFK prodEmbFK empaque
//Mientras que se tengan resultados se le asignan a $rows mediante 

$resultado = $MySQLiconn->query("SELECT * FROM $devolucion ORDER BY baja ASC, id DESC;");

while ($rows = $resultado->fetch_array()) {
	//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
	echo "<tr>";
	if ($rows['baja']=='1') { ?>
		<td> <a href="?r=<?php echo $rows["id"].'&u=--'; ?>" ><IMG src="funciones/img/modifyc.png" title='Armar Reporte'></a> 
		&nbsp; <?php 
		if ($rows['tipo']=='Embarque') {?>
 		<a href="?r=--&u=<?php echo $rows["id"]; ?>"><IMG src="funciones/img/unboxing.png" title='Desempaquetar'></a>
 		&nbsp; <?php } ?>
 		<a href="?del=<?php echo $rows["id"].'&r=--&u=--'; ?>"><IMG src="funciones/img/deleteOtro.png" title='Desactivar'></a>
		</td>
		<?php
	}
	if($rows['baja']=='0') { ?>
		<td> <a href="?activar=<?php echo $rows["id"].'&r=--&u=--'; ?>" ><IMG src="funciones/img/unnamed.png" title='Activar'></a>
		&nbsp;  
 		<a href="?eli=<?php echo $rows["id"].'&r=--&u=--'; ?>" ><IMG src="funciones/img/deleteDef.png" title='Eliminar'></a>
		</td>
		<?php 
	}
	if($rows['baja']=='2') { ?>
		<td> <a target="_blank" href="funciones/pdf/devolucionPDF.php?cdgdev=<?php echo $rows['id']; ?>" ><IMG src="funciones/img/pdf.png" title='Descargar PDF'></a>
		&nbsp;  
		</td>
		<?php 
	}
	$numE='';

	if ($rows['tipo']=='Embarque') {
		$numE=$rows['codigo'];
	}

	if ($rows['tipo']=='Paquete' OR $rows['tipo']=='Rollo') {	
		$consultar=$MySQLiconn->query("SELECT * from ensambleempaques where codigo='".$rows['codigo']."' && baja='5'");
		$row=$consultar->fetch_array();
		$numE=$row['cdgEmbarque'];
	}

	if ($rows['tipo']=='Empaque'){
		$consultar1=$MySQLiconn->query("SELECT * from caja where codigo='".$rows['codigo']."' && baja='5'");
		$consultar2=$MySQLiconn->query("SELECT * from rollo where codigo='".$rows['codigo']."' && baja='5'");
		$row=$consultar1->fetch_array();
		if (is_null($row['id'])) {
			$row=$consultar2->fetch_array();
		}
		$numE=$row['cdgEmbarque'];
	}

	$consultar3=$MySQLiconn->query("SELECT idorden from $embarq where numEmbarque='".$numE."'");
	$row3=$consultar3->fetch_array();

	$consultar4=$MySQLiconn->query("SELECT ordenConfi from $confirProd where idConfi='".$row3['idorden']."'");
	$row4=$consultar4->fetch_array();

	//Traemos la tabla y la imprimimos debidamente donde pertenece 
	echo "<td>Folio: <b>"; echo $rows["folio"]; echo "</b><br>Tipo: <b>"; echo $rows["tipo"]; echo "</b><br>Fecha: <b>"; echo $rows["fechaDev"]; 
	echo "</b></td>
	<td>Embarque: "; echo $numE; echo "<br>
	O.C.: "; echo $row4["ordenConfi"]; echo "</td>
	<td>Producto:<br>";echo $rows["producto"]; echo "<br>
	Sucursal:<br>"; echo $rows["sucursal"]; echo "</td>
	</tr>";
}
$resultado->close(); ?>