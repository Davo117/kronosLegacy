<?php

print("<th style='width: 15%;'>Acciones</th>");
print("<th style='width: 15%;'>Tipo</th>");
print("<th style='width: 25%;'>Datos</th>");
print("<th style='width: 40%;'>Movimiento</th>");
print("<th>Estatus</th>");

//Mientras que se tengan resultados se le asignan a $rows mediante 

$resultado = $MySQLiconn->query("SELECT * FROM accesorios where baja=1  ORDER BY id_acc DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
print("<tr>");

//asignamos el atributo value con el id al que corresponde cada registro
?>
<td><a href="?edit=<?php echo $rows["id_acc"]; ?>"><IMG src="funciones/img/modify.png" title='Modificar'></a> 

<a href="?del=<?php echo $rows["id_acc"]; ?>"><IMG src="funciones/img/delete.png" title='Desactivar'></a></td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 

print("<td title='Tipo'>".$rows["tipo"]."</td>");
print("<td >Marca: ".$rows["marca"]." <br>");
print("Modelo: ".$rows["modelo"]."</td>");
print("<td>Fecha ingreso: ".$rows["fch_ingreso"]."</td>");
if ($rows["baja"]==1) {
print("<td title='Estatus'>Activo</td>");	
}
else{
print("<td title='Estatus'>Inactivo</td>");		
}

print("</tr>");
}
//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros</p>");
$resultado->close();
?>