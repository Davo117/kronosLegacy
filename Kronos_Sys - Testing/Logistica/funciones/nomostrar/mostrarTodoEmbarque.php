<?php
echo "<th style='width: 10%;'>Acciones</th> <th style='width: 15%;'>N° Embarque</th> <th>Embarque</th> <th style='width: 30%;'>Producto</th> <th>Observaciones</th>";

//idEmbarque numEmbarque transpEmb referencia diaEmb observaEmb bajaEmb sucEmbFK prodEmbFK empaque
//Mientras que se tengan resultados se le asignan a $rows mediante 
//$ordenar = $MySQLiconn->query("SELECT * FROM $tablaOrden ORDER BY idorden DESC;");
//$reorden=$ordenar->fetch_array();

$resultado = $MySQLiconn->query("SELECT * FROM $embarq where bajaEmb=0   ORDER BY idEmbarque DESC;");

while ($rows = $resultado->fetch_array()) {

	$azul= $rows["sucEmbFK"];
	$orden = $MySQLiconn->query("SELECT orden FROM $tablaOrden where sucFK='$azul'   ORDER BY idorden DESC;");
	$re=$orden->fetch_array();

	$sucu=$MySQLiconn->query("SELECT nombresuc FROM $tablasucursal where idsuc=".$rows['sucEmbFK'].";");
	$rowSuc=$sucu->fetch_array();

	$prod=$MySQLiconn->query("SELECT descripcionImpresion FROM $impresion where id=".$rows['prodEmbFK'].";");
	$rowProd=$prod->fetch_array();

	//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
	//asignamos el atributo value con el id al que corresponde cada registro ?>
	<tr><td> 
	<a href="?activar=<?php echo $rows["idEmbarque"]; ?>" ><IMG src="funciones/img/unnamed.png" title='Activar'></a>
	&nbsp;  
 	<a href="?eli=<?php echo $rows["idEmbarque"]; ?>" ><IMG src="funciones/img/deleteDef.png" title='Eliminar'></a>
 	</td>
 	<?php
	//Traemos la tabla y la imprimimos debidamente donde pertenece 
	echo "<td title='N° Embarque'>"; echo $rows["numEmbarque"]; echo "<br>Referencia: "; echo $rows["referencia"];echo "</td>
	<td>Fecha Embarque: "; echo $rows["diaEmb"]; echo "<br>
	O.C.: "; echo $re["orden"]; echo "</td>
	<td>Producto: "; echo $rowProd["descripcionImpresion"]."<br>
	Sucursal: "; echo $rowSuc["nombresuc"]; echo "</td>
	<td title='Observaciones'>"; echo $rows["observaEmb"]; echo "</td>
	</tr>";
}
// que otro campo puede ir ahi en vez de el id de esa tabla :C ededededed
 //Contar registros:
$row_cnt=$resultado->num_rows;
echo "<h4 class='ordenMedio'>Embarques Inactivos</h4>
<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>";
$resultado->close(); ?>