<?php
session_start();
include('../../../Database/db.php');

$resultado = $MySQLiconn->query("SELECT e.*, s.nombresuc, i.descripcionImpresion, i.id as idIm FROM $embarq e left join $tablasucursal s on e.sucEmbFK=s.idsuc left join $impresion i on e.prodEmbFK=i.id where e.bajaEmb=1 ORDER BY e.idEmbarque DESC;");
$_SESSION['busqueda']='';
if(isset($_GET['em'])){
	$q=urldecode($_GET['em']);
	$_SESSION['busqueda']=$q;
	$resultado = $MySQLiconn->query("SELECT e.*, s.nombresuc, i.descripcionImpresion, i.id as idIm FROM $embarq e left join $tablasucursal s on e.sucEmbFK=s.idsuc left join $impresion i on e.prodEmbFK=i.id where e.bajaEmb=1 && (e.numEmbarque LIKE '%$q%' OR e.referencia LIKE '%$q%' OR e.empaque LIKE '%$q%' OR s.nombresuc LIKE '%$q%' OR i.descripcionImpresion LIKE '%$q%') ORDER BY e.idEmbarque DESC;");
}
if ($resultado->num_rows>0) {
	echo "<table border='0' cellpadding='0' class='table table-hover'>";
	echo "<th style='width: 26%;'>Acciones</th> <th >N° Embarque</th> <th >Producto</th> <th>Embarque</th> <th>Observaciones</th>";
	//idEmbarque numEmbarque transpEmb referencia diaEmb observaEmb bajaEmb sucEmbFK prodEmbFK empaque
	//Mientras que se tengan resultados se le asignan a $rows mediante 
	while ($rows = $resultado->fetch_array()) {
		$azul= $rows["idorden"];
		$reOrdenAz="";
		if ($azul=="0") {
			$reOrdenAz='Sin Enlace';
		}
		else{
			$orden = $MySQLiconn->query("SELECT o.orden FROM $tablaOrden as o inner join $confirProd as c on o.idorden=c.ordenConfi where c.idConfi='".$rows['idorden']."';");
			$re=$orden->fetch_array();
			$reOrdenAz=$re['orden'];
		}
		//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:

		//asignamos el atributo value con el id al que corresponde cada registro ?>
		<tr>
		<td><a href="?edit=<?php echo $rows["idEmbarque"]; ?>" ><IMG src="funciones/img/modify.png" title='Modificar'></a> 
		&nbsp; 
 		<a href="?del=<?php echo $rows["idEmbarque"]; ?>" ><IMG src="funciones/img/deleteOtro.png" title='Desactivar'></a>
		&nbsp; <?php 
		if ($rows['cerrar']=='0') {
			if($reOrdenAz !="Sin Enlace"){?>
			
				<a target="_blank" href="?cdgEmb=<?php echo $rows["numEmbarque"].'&id='.$rows["idEmbarque"].'&emp='.$rows['empaque'].'&pr='.$rows["prodEmbFK"]; ?>"><IMG src="funciones/img/armado.png" title='Armado de Embarques'></a><?php 
			}
		} elseif ($rows['cerrar']=='1'){?>
			<a href="?abrir=<?php echo $rows["numEmbarque"].'&id='.$rows["idEmbarque"].'&emp='.$rows['empaque']; ?>"><IMG src="funciones/img/abrir.png" title='Abrir Armado'  onclick="return confirm('¿Deseas Abrir el Armado al Embarque: <?php echo ' '.$rows['numEmbarque'];?>?'); "></a><?php 
		}

		if($reOrdenAz !="Sin Enlace"){ ?>
			&nbsp; 
			<a target="_blank" href="funciones/pdf/EmbarqueLabelCar.php?numEmbarque=<?php echo $rows["numEmbarque"]; ?>" ><IMG src="funciones/img/camion.png" title='Etiquetas de embarque'></a>
			&nbsp; 
			<a target="_blank" href="funciones/pdf/excelEmb.php?numEmbarque=<?php echo $rows["numEmbarque"]; ?>"><IMG src="funciones/img/excel.png" title='Hoja de Cálculo'></a>
			&nbsp; 
			<a target="_blank" href="funciones/pdf/ListadoEmbarque.php?numEmbarque=<?php echo $rows["numEmbarque"]; ?>" > <IMG src="funciones/img/pdf.png" title='PDF'></a><?php
		}

		//Traemos la tabla y la imprimimos debidamente donde pertenece 
		echo "</td> <td title='N° Embarque'>"; echo $rows["numEmbarque"]; echo "</td>
		<td><b>Producto:</b><br>"; echo $rows["descripcionImpresion"]; echo "<br>
		<b>Sucursal:</b><br>"; echo $rows["nombresuc"]; echo "</td>
		<td>Fecha: "; echo $rows["diaEmb"]; echo "<br>";
		if($reOrdenAz =="Sin Enlace"){
			$ordenC = $MySQLiconn->query("SELECT o.orden, c.cantidadConfi, c.empaqueConfi, c.idConfi FROM ordencompra o inner join confirmarprod c on o.idorden=c.ordenConfi where c.enlaceEmbarque='0' && c.empaqueConfi='".$rows['empaque']."' && o.sucFK='".$rows['sucEmbFK']."' && c.prodConfi='".$rows['idIm']."'"); //selectpicker show-menu-arrow?>
			<form method="post" name="f6rmulary" id="f6rmulary">
			<select name="enlace" required id="enlace"  class="form-control">
				<?php 			
			if($ordenC->num_rows>0){?>
				<option value="">Seleccionar O.C.</option> <?php //Seleccionar todos los datos de la tabla 
    		
				while ($ordenes = $ordenC->fetch_array()) {  ?>
					<option  value="<?php echo $ordenes['idConfi'].'-'.$rows['idEmbarque']; ?>"><?php echo $ordenes['orden']."[".$ordenes['cantidadConfi']."]"; ?></option><?php
				}
			}
			else{
				echo "<option value=''>No hay OC.</option>";
			}?>
			</select>
			<br><br><input type="submit" class="btn btn-success btn-sm" name="saveOC" value="Enlazar">
				<form></td><?php
		}
		else{
			echo "O.C.: "; echo $reOrdenAz; echo "</td>";
		}
		echo "<td title='Observación'>Tipo: "; echo $rows["empaque"].'<br>'.$rows["observaEmb"]; echo "</td>
		</tr>";
	}
	// que otro campo puede ir ahi en vez de el id de esa tabla :C ededededed
	//Contar registros:
	$row_cnt=$resultado->num_rows;
	echo "<h4 class='ordenMedio'>Embarques Activos</h4>
	<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>";
	$resultado->close();
	echo "</table>";
} ?>