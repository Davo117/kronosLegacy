<?php

print("<th>Opciones</th>");
print("<th>RFC</th>");
print("<th>Empresa</th>");


//Mientras que se tengan resultados se le asignan a $rows mediante 
//ORDER BY ID DESC, ASC;
$resultado = $MySQLiconn->query("SELECT * FROM $tablacli where bajacli=1 ORDER BY ID DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
print("<tr>");
//asignamos el atributo value con el id al que corresponde cada registro
?>
<td> <a href="?edit=<?php echo $rows["ID"]; ?>" onclick="return confirm('<?php print("Deseas EDITAR al cliente: ".$rows["nombrecli"]." ?"); ?>'); " ><IMG src="funciones/img/modify.png" title='Modificar'></a>

<a href="?del=<?php echo $rows["ID"]; ?>" onclick="return confirm('<?php print("Deseas ELIMINAR al cliente: ".$rows["nombrecli"]." ?"); ?>');" ><IMG src="funciones/img/delete.png" title='Desactivar'></a>
&nbsp 
<a href="?combocon=<?php echo $rows["nombrecli"]; ?>"><IMG src="funciones/img/contacto.png" title='Contacto'></a>

<a href="?combosuc=<?php echo $rows["nombrecli"]; ?>"><IMG src="funciones/img/suc.png" title='Sucursal'></a>
    </td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 

print("<td title='RFC'>".$rows["rfccli"]."</td>");
print("<td title='Datos'>Nombre: ".$rows["nombrecli"]."&nbsp &nbsp &nbsp ");
print("Telefono: ".$rows["telcli"]."</td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();

?>