<?php
echo "<th>Acciones</th><th>Orden Compra</th><th>Recepción</th><th style='width:20%;'>Pendiente</th>";
//Mientras que se tengan resultados se le asignan a $rows mediante 

$resultado=$MySQLiconn->query("SELECT * FROM $tablaOrden where bajaOrden=1 ORDER BY idorden DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:

//asignamos el atributo value con el id al que corresponde cada registro ?>
<tr>
<td><a href="?edit=<?php echo $rows["idorden"]; ?>" ><IMG src="funciones/img/modifyc.png" title='Modificar'></a> 
<a href="?del=<?php echo $rows["idorden"]; ?>" ><IMG src="funciones/img/deleteOtro.png" title='Desactivar'></a>

<a href="?comboRequ=<?php echo $rows["orden"]; ?>"><IMG src="funciones/img/requisitos.png" title='Requerimientos'></a>
<a href="?comboConf=<?php echo $rows["orden"]; ?>"><IMG src="funciones/img/confirmar.png" title='Confirmaciones'></a>
</td>


<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 
echo "<td title='Sucursal'>Sucursal: "; echo $rows["sucFK"]; echo "&nbsp &nbsp &nbsp
Orden: <b>"; echo $rows["orden"]; echo "</b></td>
<td title='Fecha'>"; echo $rows["documento"]."</td>";
echo "<td>";
$resultado1=$MySQLiconn->query("SELECT surtido, cantidadConfi, enlaceEmbarque FROM $confirProd where bajaConfi=1 and ordenConfi=".$rows["orden"]." ORDER BY idConfi DESC;");
//$count=;
$letra= $letra2=$letra3="";
if(!$resultado1){ echo "<b>Sin datos</b>"; }
else{
	while ($rowOrden = $resultado1->fetch_array()) {
		if ($rowOrden["enlaceEmbarque"]!=0) {
			if ($rowOrden["surtido"]<$rowOrden["cantidadConfi"]) {
			$letra= "<IMG src='funciones/img/warning.png' title='Surtir'> Sin completar";
			}
			else{
			$letra3="<IMG src='funciones/img/verify.png' title='Listo'>";
			}
		}elseif ($rowOrden["enlaceEmbarque"]==0) {
			$letra2="<IMG src='funciones/img/warningRed.png' title='Embarcar'> Asignar Embarque";
		}
	}
	echo $letra." ".$letra2." ".$letra3;
}
echo "</td></tr>";
}
//Contar registros:
	$row_cnt=$resultado->num_rows;
	echo "<h4 class='ordenMedio'>Ordenes Activas</h4>
	<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>";
	$resultado->close();
?>