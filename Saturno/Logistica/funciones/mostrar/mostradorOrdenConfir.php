<?php
print("<th>&nbsp Opciones &nbsp</th>");
print("<th>ID</th>");
print("<th>Cantidad</th>");
print("<th>Referencia</th>");

//Mientras que se tengan resultados se le asignan a $rows mediante 

$resultado = $MySQLiconn->query("SELECT * FROM $confirProd, $tablaOrden where bajaConfi=1 and ordenConfi=orden ORDER BY idConfi DESC;");


while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
print("<tr>");
//asignamos el atributo value con el id al que corresponde cada registro
?>
<td> <a href="?edit=<?php echo $rows["idConfi"]; ?>" onclick="return confirm('<?php print("Deseas EDITAR la confirmación: ".$rows["ordenConfi"]." ?"); ?>'); " ><IMG src="funciones/img/modify.png" title='Modificar'></a>

<a href="?del=<?php echo $rows["idConfi"]; ?>" onclick="return confirm('<?php print("Deseas ELIMINAR la confirmación: ".$rows["ordenConfi"]." ?"); ?>');" ><IMG src="funciones/img/delete.png" title='Desactivar'></a>
&nbsp  
<a href="?comboconfir=<?php echo $rows["idConfi"]; ?>"><IMG src="funciones/img/confirmar.png" title='Surtir Producto'></a>
 </td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 
print("<td title='ID'> &nbsp  ".$rows["idConfi"]." &nbsp  </td>");
print("<td title='Nombre'>&nbsp &nbsp   ".$rows["cantidadConfi"]."&nbsp &nbsp   </td>");
print("<td title='Teléfono'>&nbsp  &nbsp  ".$rows["referenciaConfi"]."&nbsp  &nbsp  </td>");
print("</tr>");
}
 

 //Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();

?>