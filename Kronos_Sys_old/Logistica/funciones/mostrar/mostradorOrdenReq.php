<?php
include_once '../../../Database/db.php';	
echo "<table class='table table-hover' border='0'>	<th style='width: 150px;'>Acciones</th>	<th>Producto</th>";

//Mientras que se tengan resultados se le asignan a $rows mediante 
$q=$_GET['q'];
$resultado = $MySQLiconn->query("SELECT * FROM $reqProd where bajaReq='1' && ordenReqFK='$q' ORDER BY idReq DESC;");

// SELECT * FROM `requerimientoprod`inner join ordencompra where bajaReq=1 and ordenReqFK=orden
while ($rows = $resultado->fetch_array()) {
	$resultado24=$MySQLiconn->query("SELECT descripcionImpresion FROM $impresion WHERE codigoCliente='".$rows['prodcliReqFK']."'"); 
	$confirmar=0;
	while ($resultaque=$resultado24->fetch_array()) {

		$resultado23 = $MySQLiconn->query("SELECT cantidadConfi FROM $confirProd where ordenConfi='".$rows['ordenReqFK']."' && prodConfi='".$resultaque['descripcionImpresion']."' ORDER BY idConfi DESC;");
		while($rows23 = $resultado23->fetch_array()){
			$confirmar= $rows23["cantidadConfi"]+ $confirmar;
		}
	}

	$restante=$rows["cantReq"]- $confirmar;

	//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
	
	//asignamos el atributo value con el id al que corresponde cada registro ?>
	<br><tr>
	<td> <a href="?edit=<?php echo $rows["idReq"]; ?>" ><IMG src="funciones/img/modify.png" title='Modificar'></a>
	&nbsp; 
	<a href="?del=<?php echo $rows["idReq"]; ?>" ><IMG src="funciones/img/deleteOtro.png" title='Desactivar'></a>
	&nbsp; 
	<a href="?comboconfir=<?php echo $rows["ordenReqFK"]; ?>"><IMG src="funciones/img/confirmar.png" title='Confirmar Producto'></a> </td><?php

	$solouna= $MySQLiconn->query("SELECT descripcionImpresion FROM $impresion where baja=1 && codigoCliente='".$rows['prodcliReqFK']."'");

	//Traemos la tabla y la imprimimos debidamente donde pertenece 
	echo "<td style='text-align:left;' title='Producto'><p><b>".$rows["prodcliReqFK"]."</b></p>
	<p>Referencia:<b> ".$rows["refeReq"]."</b></p>
	Saldo Restante: <b>$restante</b> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Requerido Total: <b>".$rows["cantReq"]." </b><br>";
	echo " Confirmado:<b>".$confirmar."</b><br>
	<br></td></tr><tr>
	<td>Confirmaciones:</td>
	<td style='text-align:left;'>";

	while ($rowsolouna = $solouna->fetch_array()) {
		$resultado22 = $MySQLiconn->query("SELECT * FROM $confirProd, $tablaOrden where bajaConfi=1 && ordenConfi='".$rows['ordenReqFK']."' && ordenConfi=orden && prodConfi='".$rowsolouna['descripcionImpresion']."'  ORDER BY idConfi DESC;");

		while ($rows22 = $resultado22->fetch_array()) {

			//Traemos la tabla y la imprimimos debidamente donde pertenece 
			echo "<p><b>**</b>Producto Confirmado: "; echo $rows22["prodConfi"]; echo "**<br>
			Empaque:"; echo $rows22["empaqueConfi"]; echo " <br>
			Cantidad Confirmada: "; echo $rows22["cantidadConfi"]; echo " <br>
			Surtido:"; echo $rows22["surtido"]; echo " <br>
			Embarque: "; echo $rows22["embarqueConfi"]; echo "<br>
			Entrega: "; echo $rows22["entregaConfi"]; echo " <p> __________________________________<br><br>";
		} 
	}
	echo "</td> </tr>";
}
//Contar registros:
$row_cnt=$resultado->num_rows;
echo "<h4 class='ordenMedio'>Requerimientos Activos</h4>
<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>";
$resultado->close(); ?>