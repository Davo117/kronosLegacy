<?php
include("crud_explosion.php");
	$producto='';
	$codigo='';
	$oc='';
if ($_GET==true) {
	$producto=$_GET['prod'];
	$codigo=$_GET['cod'];
	$oc=$_GET['oc'];

	print("<table  border='0' cellpadding='15' class='table table-hover'>");	
	print("<th >Elemento</th>");
	print("<th style='width:170px;'>Consumo Individual</th>");
	print("<th style='width:190px;'>Consumo Total</th>");
	
	
	if ($codigo!="ALL") {
		$primera= $MySQLiconn->query("SELECT nombreBanda, descripcionImpresion FROM impresiones where baja=1 && descripcionDisenio='$producto' && codigoImpresion='$codigo'");	
		$row1 = $primera->fetch_array();
		
		$array='';
		if ($oc!='0') { $array="&& ordenConfi='$oc'"; }

		$segunda= $MySQLiconn->query("SELECT prodConfi, cantidadConfi FROM confirmarprod where bajaConfi=1  && prodConfi='".$row1['descripcionImpresion']."' $array");
		while($row2 = $segunda->fetch_array()){
			print("<tr> <th colspan='2'>".$producto." ~ ".$row1['descripcionImpresion']."</th>
			<th >".$row2['cantidadConfi']." pzs</th></tr>");		
		
			$banda= $MySQLiconn->query("SELECT nombreBanda, anchura FROM bandaseguridad where baja=1 && nombreBanda='".$row1['nombreBanda']."'");
			$bs = $banda->fetch_array();
			print("<tr>");		
			$valorBS= $bs["anchura"]* $row2['cantidadConfi'];
			//Traemos la tabla y la imprimimos donde pertenece 
			print("<td title='Elemento'>".$bs["nombreBanda"]."</td>");
			print("<td title='Consumo'>".$bs["anchura"]."</td>");
			print("<td title='Total'>".$valorBS."</td>");
			print("</tr>");

			$tercera= $MySQLiconn->query("SELECT elemento, consumo FROM consumos where baja=1 && producto='$producto'");

			while ($row3 = $tercera->fetch_array()) {
				print("<tr>");		
				$valor= $row3["consumo"]* $row2['cantidadConfi'];
				//Traemos la tabla y la imprimimos donde pertenece 
				print("<td title='Elemento'>".$row3["elemento"]."</td>");
				print("<td title='Consumo'>".$row3["consumo"]."</td>");
				print("<td title='Total'>".$valor."</td>");
				print("</tr>");
			}
			$cuarta=$MySQLiconn->query("SELECT consumoPantone, descripcionPantone FROM pantonepcapa where estado=1 && codigoImpresion='$codigo'");
			while ($row4 = $cuarta->fetch_array()) {
				print("<tr>");
				$cantidadPP=$row2['cantidadConfi']*$row4["consumoPantone"];
				print("<td title='Elemento'>".$row4["descripcionPantone"]."</td>");
				print("<td title='Consumo'>".$row4["consumoPantone"]."</td>");
				print("<td title='Total'>".$cantidadPP."</td>");
				print("</tr>");
			}
		}
	}
	elseif ($oc!='0') {
		$primera=$MySQLiconn->query("SELECT prodConfi, cantidadConfi FROM confirmarprod where bajaConfi=1 && ordenConfi='$oc'");
		while ($row1 = $primera->fetch_array()) {
			//print("<tr>".$row1['prodConfi']."</tr>");	
			$segunda=$MySQLiconn->query("SELECT descripcionDisenio, nombreBanda, codigoImpresion FROM impresiones where baja=1 && descripcionImpresion='".$row1['prodConfi']."'");
			while ($row2 = $segunda->fetch_array()) {
				print("<tr> <th colspan='2'>".$row2['descripcionDisenio']." ~ ".$row1['prodConfi']."</th><th>".$row1['cantidadConfi']." pzs</th></tr>");		
				

				$banda= $MySQLiconn->query("SELECT nombreBanda, anchura FROM bandaseguridad where baja=1 && nombreBanda='".$row2['nombreBanda']."'");
				$bs = $banda->fetch_array();


				print("<tr>");		
				$valorBS= $bs["anchura"]* $row1['cantidadConfi'];
				//Traemos la tabla y la imprimimos donde pertenece 
				print("<td title='Elemento'>".$bs["nombreBanda"]."</td>");
				print("<td title='Consumo'>".$bs["anchura"]."</td>");
				print("<td title='Total'>".$valorBS."</td>");
				print("</tr>");




				$tercera= $MySQLiconn->query("SELECT consumo, elemento FROM consumos where baja=1 && producto='".$row2['descripcionDisenio']."'");
				while ($row3=$tercera->fetch_array()) {
					print("<tr>");		
					$valor= $row3["consumo"]* $row1['cantidadConfi'];
					//Traemos la tabla y la imprimimos donde pertenece 
					print("<td title='Elemento'>".$row3["elemento"]."</td>");
					print("<td title='Consumo'>".$row3["consumo"]."</td>");
					print("<td title='Total'>".$valor."</td>");
					print("</tr>");
				}
				$cuarta=$MySQLiconn->query("SELECT consumoPantone, descripcionPantone FROM pantonepcapa where estado=1 && codigoImpresion='".$row2['codigoImpresion']."'");
				while ($row4 = $cuarta->fetch_array()) {
					print("<tr>");
					$cantidadPP=$row1['cantidadConfi']*$row4["consumoPantone"];
					print("<td title='Elemento'>".$row4["descripcionPantone"]."</td>");
					print("<td title='Consumo'>".$row4["consumoPantone"]."</td>");
					print("<td title='Total'>".$cantidadPP."</td>");
					print("</tr>");
				}
			}
		}
	}
	elseif ($oc=='0' && $codigo=='ALL') {
		$primera=$MySQLiconn->query("SELECT prodConfi, cantidadConfi FROM confirmarprod where bajaConfi=1 ");
		while ($row1 = $primera->fetch_array()) {
			//print("<tr>".$row1['prodConfi']."</tr>");	
			$segunda=$MySQLiconn->query("SELECT descripcionDisenio, nombreBanda, codigoImpresion FROM impresiones where baja=1 && descripcionImpresion='".$row1['prodConfi']."'");
			while ($row2 = $segunda->fetch_array()) {


			$banda= $MySQLiconn->query("SELECT nombreBanda, anchura FROM bandaseguridad where baja=1 && nombreBanda='".$row2['nombreBanda']."'");
			$bs = $banda->fetch_array();



				print("<tr> <th colspan='2'>".$row2['descripcionDisenio']." ~ ".$row1['prodConfi']."</th><th >".$row1['cantidadConfi']." pzs</th></tr>");


				print("<tr>");		
				$valorBS= $bs["anchura"]* $row1['cantidadConfi'];
				//Traemos la tabla y la imprimimos donde pertenece 
				print("<td title='Elemento'>".$bs["nombreBanda"]."</td>");
				print("<td title='Consumo'>".$bs["anchura"]."</td>");
				print("<td title='Total'>".$valorBS."</td>");
				print("</tr>");
		
				$tercera= $MySQLiconn->query("SELECT consumo, elemento FROM consumos where baja=1 && producto='".$row2['descripcionDisenio']."'");
				while ($row3 = $tercera->fetch_array()) {
					print("<tr>");		
					$valor= $row3["consumo"]* $row1['cantidadConfi'];
					//Traemos la tabla y la imprimimos donde pertenece 
					print("<td title='Elemento'>".$row3["elemento"]."</td>");
					print("<td title='Consumo'>".$row3["consumo"]."</td>");
					print("<td title='Total'>".$valor."</td>");
					print("</tr>");
				}
				$cuarta=$MySQLiconn->query("SELECT consumoPantone,descripcionPantone, estado FROM pantonepcapa where estado=1 && codigoImpresion='".$row2['codigoImpresion']."'");
				while ($row4 = $cuarta->fetch_array()) {
					print("<tr>");
					$cantidadPP=$row1['cantidadConfi']*$row4["consumoPantone"];
					print("<td title='Elemento'>".$row4["descripcionPantone"]."</td>");
					print("<td title='Consumo'>".$row4["consumoPantone"]."</td>");
					print("<td title='Total'>".$cantidadPP."</td>");
					print("</tr>");
				}
			}
		}
	} echo '<br><br> </table><br><br>

<form method="post" target="_blank">
<button class="btn btn-success" style="float:right" type="submit" name="report">Imprimir</button></form>
		'; //Agregar boton de generar pdf para crear un pdf con los datos consultados
}	?>











