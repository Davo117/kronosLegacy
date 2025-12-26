<?php

print("<th style='width: 10%;'>Acciones</th>");
print("<th style='width: 10%;'>Tipo</th>");
print("<th style='width: 15%;'>Descripcion</th>");
print("<th style='width: 25%;'>Datos</th>");
print("<th style='width: 20%;'>Cantidades</th>");

//Mientras que se tengan resultados se le asignan a $rows mediante 

$resultado = $MySQLiconn->query("SELECT * FROM consumibles where baja=1  ORDER BY id_cons DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
print("<tr>");
//asignamos el atributo value con el id al que corresponde cada registro
?>
<td><a href="?edit=<?php echo $rows["id_cons"]; ?>"><IMG src="funciones/img/modify.png" title='Modificar'></a> 

<a href="?del=<?php echo $rows["id_cons"]; ?>"><IMG src="funciones/img/delete.png" title='Desactivar'></a></td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 

print("<td title='Tipo'>".$rows["tipo"]."</td>");
print("<td title='Descripcion'>".$rows["descripcion"]."</td>");
print("<td >SKU: ".$rows["sku"]." <br>");
print("Ingreso: ".$rows["fch_ingreso"]."<br>");
print("Baja: ".$rows["fch_baja"]."</td>");
print("<td title='Cantidades'>Disponible: ".$rows["cantidadD"]."<br>");
print("Usada: ".$rows["cantidadHistorico"]."</td>");
print("</tr>");
}
//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros</p>");
$resultado->close();
?>