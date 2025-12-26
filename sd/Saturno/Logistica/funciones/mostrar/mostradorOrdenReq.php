<?php
print("<th>&nbsp Opciones &nbsp</th>");
print("<th>ID</th>");
print("<th>Cantidad</th>");
print("<th>Referencia</th>");

//Mientras que se tengan resultados se le asignan a $rows mediante 

$resultado = $MySQLiconn->query("SELECT * FROM $reqProd, $tablaOrden where bajaReq=1 and ordenReqFK=orden ORDER BY idReq DESC;");


// SELECT * FROM `requerimientoprod`inner join ordencompra where bajaReq=1 and ordenReqFK=orden

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
print("<tr>");
//asignamos el atributo value con el id al que corresponde cada registro
?>
<td> <a href="?edit=<?php echo $rows["idReq"]; ?>" onclick="return confirm('<?php print("Deseas EDITAR el Requerimiento: ".$rows["ordenReqFK"]." ?"); ?>'); " ><IMG src="funciones/img/modify.png" title='Modificar'></a>

<a href="?del=<?php echo $rows["idReq"]; ?>" onclick="return confirm('<?php print("Deseas ELIMINAR el Requerimiento: ".$rows["ordenReqFK"]." ?"); ?>');" ><IMG src="funciones/img/delete.png" title='Desactivar'></a>
&nbsp  
<a href="?comboconfir=<?php echo $rows["idReq"]; ?>"><IMG src="funciones/img/confirmar.png" title='Confirmar Producto'></a>
 </td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 
print("<td title='ID'> &nbsp  ".$rows["idReq"]." &nbsp  </td>");
print("<td title='Nombre'>&nbsp &nbsp   ".$rows["cantReq"]."&nbsp &nbsp   </td>");
print("<td title='Teléfono'>&nbsp  &nbsp  ".$rows["refeReq"]."&nbsp  &nbsp  </td>");
print("</tr>");
}
 

 //Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();

?>