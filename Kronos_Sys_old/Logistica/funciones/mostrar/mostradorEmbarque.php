<?php
echo "<th style='width: 26%;'>Acciones</th> <th >N° Embarque</th> <th style='width: 30%;'>Producto</th> <th>Embarque</th> <th>Observaciones</th>";

//idEmbarque numEmbarque transpEmb referencia diaEmb observaEmb bajaEmb sucEmbFK prodEmbFK empaque
//Mientras que se tengan resultados se le asignan a $rows mediante 
$ordenar = $MySQLiconn->query("SELECT * FROM $tablaOrden where bajaOrden=1    ORDER BY idorden DESC;");
$reorden=$ordenar->fetch_array();

$resultado = $MySQLiconn->query("SELECT * FROM $embarq where bajaEmb=1 ORDER BY idEmbarque DESC;");

while ($rows = $resultado->fetch_array()) {

$azul= $rows["sucEmbFK"];
$orden = $MySQLiconn->query("SELECT orden FROM $tablaOrden where bajaOrden=1 && sucFK='$azul'   ORDER BY idorden DESC;");
$re=$orden->fetch_array();
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:

//asignamos el atributo value con el id al que corresponde cada registro
?>
<tr>
<td> <a href="?edit=<?php echo $rows["idEmbarque"]; ?>" ><IMG src="funciones/img/modify.png" title='Modificar'></a> 
&nbsp; 
 <a href="?del=<?php echo $rows["idEmbarque"]; ?>" ><IMG src="funciones/img/deleteOtro.png" title='Desactivar'></a>
&nbsp; 

<?php if ($rows['cerrar']=='0') {?>
<a target="_blank" href="?cdgEmb=<?php echo $rows["numEmbarque"].'&id='.$rows["idEmbarque"].'&emp='.$rows['empaque']; ?>"><IMG src="funciones/img/armado.png" title='Armado de Embarques'></a>	
<?php 
} else{?>
<a href="?abrir=<?php echo $rows["numEmbarque"].'&id='.$rows["idEmbarque"].'&emp='.$rows['empaque']; ?>"><IMG src="funciones/img/abrir.png" title='Abrir Armado'  onclick="return confirm('¿Deseas Abrir el Armado al Embarque: <?php echo ' '.$rows['numEmbarque'];?>?'); "></a>
<?php } ?>

&nbsp; 
<a target="_blank" href="funciones/pdf/EmbarqueLabelCar.php?numEmbarque=<?php echo $rows["numEmbarque"]; ?>" ><IMG src="funciones/img/camion.png" title='Etiquetas de embarque'></a>
&nbsp; 
 <a target="_blank" href="funciones/pdf/excelEmb.php?numEmbarque=<?php echo $rows["numEmbarque"]; ?>"><IMG src="funciones/img/excel.png" title='Hoja de Cálculo'></a>
&nbsp; 
<a target="_blank" href="funciones/pdf/ListadoEmbarque.php?numEmbarque=<?php echo $rows["numEmbarque"]; ?>" > <IMG src="funciones/img/pdf.png" title='PDF'></a>
</td>


<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 
echo "<td title='N° Embarque'>"; echo $rows["numEmbarque"]; echo "</td>
<td>Producto:<br>"; echo $rows["prodEmbFK"]; echo "<br>
Sucursal:<br>"; echo $rows["sucEmbFK"]; echo "</td>
<td>Fecha: "; echo $rows["diaEmb"]; echo "<br>
O.C.: "; echo $re["orden"]; echo "</td>
<td title='Observación'>"; echo $rows["observaEmb"]; echo "</td>
</tr>";
}
// que otro campo puede ir ahi en vez de el id de esa tabla :C ededededed
 //Contar registros:
$row_cnt=$resultado->num_rows;
echo "<h4 class='ordenMedio'>Embarques Activos</h4>
<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>";
$resultado->close();
?>