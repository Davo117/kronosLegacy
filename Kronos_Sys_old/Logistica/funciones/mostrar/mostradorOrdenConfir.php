<?php
include_once '../../../Database/db.php';
//Mientras que se tengan resultados se le asignan a $rows mediante 
$q=$_GET['q'];
$resultado = $MySQLiconn->query("SELECT * FROM $reqProd where bajaReq='1' && ordenReqFK='$q' ORDER BY idReq DESC;");

while ($rows = $resultado->fetch_array()) {

	$resultado24=$MySQLiconn->query("SELECT descripcionImpresion FROM $impresion WHERE codigoCliente='".$rows['prodcliReqFK']."'"); 
	$confirmar=0;
	while ($resultaque=$resultado24->fetch_array()) {

		$resultado23 = $MySQLiconn->query("SELECT cantidadConfi FROM $confirProd 
		where ordenConfi='".$rows['ordenReqFK']."' && prodConfi='".$resultaque['descripcionImpresion']."' ORDER BY idConfi DESC");
		while($rows23 = $resultado23->fetch_array()){
		$confirmar= $rows23["cantidadConfi"]+ $confirmar;}
	}
	$confirmado=$rows["cantReq"]- $confirmar;
	echo "<br><br><table  border='0' cellpadding='15' class='table table-hover'>"; ?>
	<style type="text/css">
		.tdclass1 {text-align:left; }
		.tdclass2 { text-align:center; }
	</style>
	<th colspan='2'> <a id="referencia" href="Logistica_RequerimientosOrden.php?edit=<?php echo $rows["idReq"]; ?>"><?php echo $rows["prodcliReqFK"]; ?></a><font style="float: right; color: #7C6767;">Requerido: <?php echo $rows["cantReq"]; ?> &nbsp; Saldo: <?php echo $confirmado; ?></font></th>
	<?php 
	$solouna= $MySQLiconn->query("SELECT descripcionImpresion FROM $impresion where baja=1 && codigoCliente='".$rows['prodcliReqFK']."'");

	while($rowsolouna = $solouna->fetch_array()){
		$resultado22 = $MySQLiconn->query("SELECT * FROM $confirProd, $tablaOrden where  ordenConfi='".$rows['ordenReqFK']."' && ordenConfi=orden && prodConfi='".$rowsolouna['descripcionImpresion']."'  ORDER BY idConfi DESC;");

		while ($rows22 = $resultado22->fetch_array()) { ?>
			<tr>
			<td rowspan="1" class="tdclass1">
			<?php //Traemos la tabla y la imprimimos debidamente donde pertenece 
			echo "<p>Producto Confirmado: <b>"; echo $rows22["prodConfi"]; echo "</b>&nbsp; &nbsp; &nbsp;
			Empaque:<b>"; echo $rows22["empaqueConfi"]; echo " </b><br>
			Cantidad Confirmada: <b>"; echo $rows22["cantidadConfi"]; echo "</b>&nbsp; &nbsp; &nbsp; 
			Surtido: "; echo $rows22["surtido"]; echo "<br>
			Embarque: <b>"; echo $rows22["embarqueConfi"]; echo "</b>&nbsp; &nbsp; &nbsp; 
			Entrega: <b>"; echo $rows22["entregaConfi"]; 
			echo " </b></p>  </td>";  
			if ($rows22['bajaConfi']==1) {			?>
				<td class="tdclass2"><a href="?del=<?php echo $rows22["idConfi"]; ?>" ><IMG src="funciones/img/deleteOtro.png" title='Desactivar'></a>
				&nbsp; 
				<a href="?comboSurtido=<?php echo $rows22["idConfi"]; ?>" ><IMG src="funciones/img/camion.png" title='Surtido de Confirmaciones'></a>
				</td><?php 
			}
			elseif ($rows22['bajaConfi']==0) { ?>
				<td class="tdclass2"><a href="?activar=<?php echo $rows22["idConfi"]; ?>" ><IMG src="funciones/img/unnamed.png" title='Activar'></a>
				</td> <?php 
			}
			echo "</tr>"; 
		}
		$resultado22->close();
	}
	echo "</table>";
}  $resultado->close();  ?>