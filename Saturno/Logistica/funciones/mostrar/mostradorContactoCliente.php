<?php

print("<th>Opciones</th>");

print("<th>Contacto</th>");

print("<th>Telefono</th>");
print("<th>Correo</th>");

//Mientras que se tengan resultados se le asignan a $rows mediante 
$cliente= $MySQLiconn->query("SELECT nombrecli FROM $tablacli where bajacli=1  ORDER BY ID DESC;");

for ($i=0; $i < $raws =$cliente->fetch_array(); $i++) { 
	printf( $raws["nombrecli"]."    ");
	echo "soy " .$i ."<br> ";
	
}

$resultado = $MySQLiconn->query("SELECT * FROM $tablaconcli where bajaconcli=1 ORDER BY idconcli DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
print("<tr>");
//asignamos el atributo value con el id al que corresponde cada registro
?>
<td><a href="?edit=<?php echo $rows["idconcli"]; ?>" onclick="return confirm('<?php print("Deseas EDITAR al Contacto: ".$rows["nombreconcli"]." ?"); ?>'); " ><IMG src="funciones/img/modify.png" title='Modificar'></a>

    <a href="?del=<?php echo $rows["idconcli"]; ?>" onclick="return confirm('<?php print("Deseas ELIMINAR al Contacto: ".$rows["nombreconcli"]." ?"); ?>');" ><IMG src="funciones/img/delete.png" title='Desactivar'></a></td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 

print("<td title='Contacto'>Puesto: ".$rows["puestoconcli"]."<br>");
print("Nombre: ".$rows["nombreconcli"]."</td>");
print("<td>Telefono: ".$rows["telefonoconcli"]."<br> <br>");
print("Celular: ".$rows["movilcl"]."</td>");
print("<td title='Correo'>".$rows["emailconcli"]."</td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();
?>