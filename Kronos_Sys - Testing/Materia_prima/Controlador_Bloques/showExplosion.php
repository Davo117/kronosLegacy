<?php
error_reporting(0);
include("crud_explosion.php");
	$producto='';
	$oc='';
if ($_GET==true) {
	$producto=$_GET['prod'];
	$oc=$_GET['oc'];

	echo ("<table  border='0' cellpadding='15' class='table table-hover'>");	
	echo ("<th >Elemento</th>");
	echo ("<th style='width:170px;'>Consumo Individual</th>");
	echo ("<th style='width:190px;'>Consumo Total</th>");
	
	if ($producto!="ALL") {
		$primera= $MySQLiconn->query("SELECT nombreBanda, descripcionDisenio, descripcionImpresion, id, codigoImpresion FROM impresiones where baja=1 && id='$producto'");
		
		$row1 = $primera->fetch_array();
		echo "<h4>".$row1['descripcionImpresion']."</h4><br>";
		$array='';
		if ($oc!='0') { $array="&& ordenConfi='$oc'"; }

		$segunda= $MySQLiconn->query("SELECT prodConfi, cantidadConfi, ordenConfi FROM confirmarprod where bajaConfi=1 ");



		
		$cons=$MySQLiconn->query("SELECT descripcion from producto where ID='".$row1['descripcionDisenio']."'");
		$rowProd=$cons->fetch_array();
		while($row2 = $segunda->fetch_array()){

		$conCo=$MySQLiconn->query("SELECT orden from ordencompra where idorden='".$row2['ordenConfi']."'");
		$rowOC=$conCo->fetch_array();
			echo ("<tr> <th colspan='2'>".$rowProd['descripcion']." ~ ".$row1['descripcionImpresion']." O.C.: ".$rowOC['orden']."</th>
			<th >".$row2['cantidadConfi']." pzs</th></tr>");		
			if ($row1['nombreBanda']!="") {
				$banda= $MySQLiconn->query("SELECT nombreBanda, anchura IDBanda FROM bandaseguridad where baja=1 && nombreBanda='".$row1['nombreBanda']."'");
				$bs = $banda->fetch_array();

$unn= $MySQLiconn->query("SELECT idUnidad FROM unidades Where baja=1");
$uii= $unn->fetch_array();
			
	$unidades= $MySQLiconn->query("SELECT idUnidad, identificadorUnidad FROM unidades INNER JOIN obelisco.productosCK ON unidades.idUnidad = productosCK.unidad where unidad='".$uii['idUnidad']."'");
				$uni = $unidades->fetch_array(); 


//se agrego error report, falta que aparezca la unidad en los tiner
/*
$pro2= $MySQLiconn->query("SELECT producto, id  FROM productosCK where baja=1");
$pro3= $pro2->fetch_array();

$ioo=sqlsrv_query($SQLconn, "SELECT CIDPRODUCTO as id, CNOMBREPRODUCTO as nombreElemento FROM admproductos WHERE id= '".$pro3['producto']."'");
$rf= = sqlsrv_fetch_array($ioo, SQLSRV_FETCH_ASSOC);
*/

				echo ("<tr>");		
				$valorBS= $bs["anchura"]* $row2['cantidadConfi'];
				//Traemos la tabla y la imprimimos donde pertenece 
				echo ("<td title='Elemento'>".$bs["nombreBanda"]."</td>");
				echo ("<td title='Consumo'>".$bs["anchura"]." </td>");
				echo ("<td title='Total'>".$valorBS." </td>");
				echo ("</tr>");
			}
			$tercera= $MySQLiconn->query("SELECT elemento, consumo FROM consumos where baja=1 && producto='$producto'");
			while ($row3 = $tercera->fetch_array()) {
				echo ("<tr>");		
				$valor= $row3["consumo"]* $row2['cantidadConfi'];
				//Traemos la tabla y la imprimimos donde pertenece 
				echo ("<td title='Elemento'>".$row3["elemento"]."</td>");
				echo ("<td title='Consumo'>".$row3["consumo"]." ".$uni['identificadorUnidad']."   </td>");
				echo ("<td title='Total'>".$valor." ".$uni['identificadorUnidad']."  </td>");
				echo ("</tr>");
			}
			$codigo=$row1['codigoImpresion'];
			$cuarta=$MySQLiconn->query("SELECT consumoPantone, descripcionPantone FROM pantonepcapa where estado=1 && codigoImpresion='$codigo'");
			while ($row4 = $cuarta->fetch_array()) {
				echo ("<tr>");
				$cantidadPP=$row2['cantidadConfi']*$row4["consumoPantone"];
				echo ("<td title='Elemento'>".$row4["descripcionPantone"]."</td>");
				echo ("<td title='Consumo'>".$row4["consumoPantone"]." ".$uni['identificadorUnidad']."</td>");
				echo ("<td title='Total'>".$cantidadPP." ".$uni['identificadorUnidad']."  </td>");
				echo ("</tr>");
			}
		}
	} //segunda columna de la tabla//
	elseif ($oc!='0') {
		$quinta=$MySQLiconn->query("SELECT prodConfi, cantidadConfi, ordenConfi FROM confirmarprod where bajaConfi=1 && ordenConfi='$oc'");
		while ($row5 = $quinta->fetch_array()) {
			echo "SELECT prodConfi, cantidadConfi, ordenConfi FROM confirmarprod where bajaConfi=1 && ordenConfi='$oc";
			//echo ("<tr>".$row1['prodConfi']."</tr>");	
			$sexta=$MySQLiconn->query("SELECT descripcionDisenio, nombreBanda, codigoImpresion, descripcionImpresion FROM impresiones where baja=1 && descripcionImpresion='".$row5['prodConfi']."'");
			while ($row6 = $sexta->fetch_array()) {
				echo ("<tr> <th colspan='2'>".$row6['descripcionDisenio']." ~ ".$row5['prodConfi']."</th><th>".$row5['cantidadConfi']." pzs</th></tr>");

				$banda= $MySQLiconn->query("SELECT nombreBanda, anchura FROM bandaseguridad where baja=1 && nombreBanda='".$row6['nombreBanda']."'");
				while($bs = $banda->fetch_array()){


					echo ("<tr>");
					$valorBS= $bs["anchura"]* $row5['cantidadConfi'];
					//Traemos la tabla y la imprimimos donde pertenece 
					echo ("<td title='Elemento'>".$bs["nombreBanda"]."</td>");
					echo ("<td title='Consumo'>".$bs["anchura"]."</td>");
					echo ("<td title='Total'>".$valorBS."</td>");
					echo ("</tr>");
				}

				$septima= $MySQLiconn->query("SELECT consumo, elemento, producto FROM consumos where baja=1 && producto='".$row7['descripcionDisenio']."'");
				while ($row7=$septima->fetch_array()) {
					echo ("<tr>");		
					$valor= $row7["consumo"]* $row5['cantidadConfi'];
					//Traemos la tabla y la imprimimos donde pertenece 
					echo ("<td title='Elemento'>".$row7["elemento"]."</td>");
					echo ("<td title='Consumo'>".$row7["consumo"]."</td>");
					echo ("<td title='Total'>".$valor."</td>");
					echo ("</tr>");
				}
				$octaba=$MySQLiconn->query("SELECT consumoPantone, descripcionPantone FROM pantonepcapa where estado=1 && codigoImpresion='".$row6['codigoImpresion']."'");
				while ($row8 = $octaba->fetch_array()) {
					echo ("<tr>");
					$cantidadPP=$row5['cantidadConfi']*$row8["consumoPantone"];
					echo ("<td title='Elemento'>".$row8["descripcionPantone"]."</td>");
					echo ("<td title='Consumo'>".$row8["consumoPantone"]."</td>");
					echo ("<td title='Total'>".$cantidadPP."</td>");
					echo ("</tr>");
				}
			}
		}
	} //tercer columna de la tabla//
	elseif ($oc=='0' && $producto=='ALL') {
		$novena=$MySQLiconn->query("SELECT prodConfi, cantidadConfi FROM confirmarprod where bajaConfi=1 ");
		//hilo
		echo "SELECT prodConfi, cantidadConfi FROM confirmarprod where bajaConfi=1 <br>";
		while ($row9 = $novena->fetch_array()) {
			echo ("<tr>hola soy ".$row9['prodConfi']."</tr>");	
			$decima=$MySQLiconn->query("SELECT descripcionDisenio, nombreBanda, codigoImpresion FROM impresiones where baja=1 && id='".$row9['prodConfi']."'");
			//hilo
			echo "SELECT descripcionDisenio, nombreBanda, codigoImpresion FROM impresiones where baja=1 && descripcionImpresion='".$row9['prodConfi']."' <br>";
			while ($row10 = $decima->fetch_array()) {


			$banda= $MySQLiconn->query("SELECT nombreBanda, anchura FROM bandaseguridad where baja=1 && nombreBanda='".$row10['nombreBanda']."'");
			//hilo
			echo " SELECT nombreBanda, anchura FROM bandaseguridad where baja=1 && nombreBanda='".$row10['nombreBanda']."' <br>";
			while($bs = $banda->fetch_array()){
				echo ("<tr> <th colspan='2'>".$row10['descripcionDisenio']." ~ ".$row9['prodConfi']."</th><th >".$row9['cantidadConfi']." pzs</th></tr>");

				echo ("<tr>");		
				$valorBS= $bs["anchura"]* $row9['cantidadConfi'];
				//Traemos la tabla y la imprimimos donde pertenece 
				echo ("<td title='Elemento'>".$bs["nombreBanda"]."</td>");
				echo ("<td title='Consumo'>".$bs["anchura"]."</td>");
				echo ("<td title='Total'>".$valorBS."</td>");
				echo ("</tr>");
			}
				$decimaprimera= $MySQLiconn->query("SELECT consumo, elemento FROM consumos where baja=1 && producto='".$row10['descripcionDisenio']."'");
				while ($row11 = $decimaprimera->fetch_array()) {
					echo ("<tr>");		
					$valor= $row11["consumo"]* $row9['cantidadConfi'];
					//Traemos la tabla y la imprimimos donde pertenece 
					echo ("<td title='Elemento'>".$row11["elemento"]."</td>");
					echo ("<td title='Consumo'>".$row11["consumo"]."</td>");
					echo ("<td title='Total'>".$valor."</td>");
					echo ("</tr>");
				}
				$decimasegunda=$MySQLiconn->query("SELECT consumoPantone,descripcionPantone, estado FROM pantonepcapa where estado=1 && codigoImpresion='".$row2['codigoImpresion']."'");
				while ($row12 = $decimasegunda->fetch_array()) {
					echo ("<tr>");
					$cantidadPP=$row9['cantidadConfi']*$row12["consumoPantone"];
					echo ("<td title='Elemento'>".$row12["descripcionPantone"]."</td>");
					echo ("<td title='Consumo'>".$row12["consumoPantone"]."</td>");
					echo ("<td title='Total'>".$cantidadPP."</td>");
					echo ("</tr>");
				}
			}
		}
	} echo '<br><br> </table><br><br>
	<form method="post" target="_blank">
	<button class="btn btn-success" style="float:right" type="submit" name="report">Imprimir</button></form>'; //Agregar boton de generar pdf para crear un pdf con los datos consultados
} ?>