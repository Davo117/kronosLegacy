<?php
echo "<th>&nbsp Opciones &nbsp</th>
<th>ID</th>
<th>Cantidad</th>
<th>Referencia</th>";

//Mientras que se tengan resultados se le asignan a $rows mediante 

$resultado = $MySQLiconn->query("SELECT * FROM $confirProd, $tablaOrden where bajaConfi=0 and ordenConfi=orden ORDER BY idConfi DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
//asignamos el atributo value con el id al que corresponde cada registro
?>
<tr>

<td><a href="?activar=<?php echo $rows["idConfi"]; ?>" onclick="return confirm('<?php echo "Deseas ACTIVAR la Confirmación: ".$rows["onderConfi"]." ?"; ?>'); " ><IMG src="funciones/img/activo.png" title='Activar'></a> 
&nbsp
<a href="?eli=<?php echo $rows["idConfi"]; ?>" onclick="return confirm('<?php echo "Deseas ELIMINAR la Confirmación: ".$rows["onderConfi"]." ?"; ?>');" ><IMG src="funciones/img/deleteDef.png" title='Eliminar'></a></td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 
echo "<td title='ID'> &nbsp  "; echo $rows["idConfi"]; echo " &nbsp  </td>
<td title='Cantidad'>&nbsp &nbsp   "; echo $rows["cantidadConfi"]; echo "&nbsp &nbsp   </td>
<td title='Referencia'>&nbsp  &nbsp  "; echo $rows["referenciaConfi"]; echo "&nbsp  &nbsp  </td>
</tr>";
}
 

 //Contar registros:
$row_cnt=$resultado->num_rows;
echo "<h4 class='ordenMedio'>Confirmaciones Inactivas</h4>
<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>";
$resultado->close();

?>


