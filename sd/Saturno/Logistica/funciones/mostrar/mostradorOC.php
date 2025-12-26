<?php
print("<th>Opciones</th>");
print("<th>Datos</th>");
print("<th>Documento</th>");
//Mientras que se tengan resultados se le asignan a $rows mediante 

//idorden	orden	documento	recepcion	bajaOrden	sucFK
$resultado = $MySQLiconn->query("SELECT * FROM $tablaOrden where bajaOrden=1  ORDER BY idorden DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
print("<tr>");
//asignamos el atributo value con el id al que corresponde cada registro
?>

<td><a href="?edit=<?php echo $rows["idorden"]; ?>" onclick="return confirm('<?php print("Deseas EDITAR la Orden: ".$rows["orden"]." ?"); ?>'); " ><IMG src="funciones/img/modify.png" title='Modificar'></a> 

<a href="?del=<?php echo $rows["idorden"]; ?>" onclick="return confirm('<?php print("Deseas ELIMINAR la Orden: ".$rows["orden"]." ?"); ?>');" ><IMG src="funciones/img/delete.png" title='Desactivar'></a>
&nbsp 


<a href="?comboRequ=<?php echo $rows["orden"]; ?>"><IMG src="funciones/img/requisitos.png" title='Requerimientos'></a>

<a href="?comboConf=<?php echo $rows["orden"]; ?>"><IMG src="funciones/img/confirmar.png" title='Confirmaciones'></a>
</td>


<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 
print("<td title='Sucursal'>Sucursal: ".$rows["sucFK"]."&nbsp &nbsp &nbsp");
print("Orden: ".$rows["orden"]."</td>");
print("<td title='Fecha'>".$rows["documento"]."</td>");
print("</tr>");
}
 
 //Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();

?>