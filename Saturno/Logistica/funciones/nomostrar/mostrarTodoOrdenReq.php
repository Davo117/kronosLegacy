<?php
print("<th>&nbsp Opciones &nbsp</th>");
print("<th>ID</th>");
print("<th>Cantidad</th>");
print("<th>Referencia</th>");

//Mientras que se tengan resultados se le asignan a $rows mediante 

$resultado = $MySQLiconn->query("SELECT * FROM $reqProd, $tablaOrden where bajaReq=0 and ordenReqFK=orden ORDER BY idReq DESC;");


while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
print("<tr>"); //asignamos el atributo value con el id al que corresponde cada registro?>

<td><a href="?activar=<?php echo $rows["idReq"]; ?>" onclick="return confirm('<?php print("Deseas ACTIVAR el Requerimiento: ".$rows["ordenReqFK"]." ?"); ?>'); " ><IMG src="funciones/img/activo.png" title='Activar'></a> 
&nbsp
<a href="?eli=<?php echo $rows["idReq"]; ?>" onclick="return confirm('<?php print("Deseas ELIMINAR el Requerimiento: ".$rows["ordenReqFK"]." ?"); ?>');" ><IMG src="funciones/img/deleteDef.png" title='Eliminar'></a></td>



<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 
print("<td title='ID'> &nbsp  ".$rows["idReq"]." &nbsp  </td>");
print("<td title='Cantidad'>&nbsp &nbsp   ".$rows["cantReq"]."&nbsp &nbsp   </td>");
print("<td title='Referencia'>&nbsp  &nbsp  ".$rows["refeReq"]."&nbsp  &nbsp  </td>");
print("</tr>");
}
 //Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros Desactivados</p>");
$resultado->close();

?>