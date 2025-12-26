<?php

print("<th style='width: 10%;'>Acciones</th>");
print("<th style='width: 10%;'>Tipo</th>");
print("<th style='width: 20%;'>Descripción </th>");
print("<th style='width: 20%;'>Responsable</th>");
print("<th style='width: 40%;'>Especificaciones Tecnicas</th>");


//Mientras que se tengan resultados se le asignan a $rows mediante 

$resultado = $MySQLiconn->query("SELECT * FROM equipos where baja=1  ORDER BY id_eq DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
print("<tr>");
//asignamos el atributo value con el id al que corresponde cada registro
?>
<td><a href="?edit=<?php echo $rows["id_eq"]; ?>"><IMG src="funciones/img/modify.png" title='Modificar'></a> 

<a href="?del=<?php echo $rows["id_eq"]; ?>"><IMG src="funciones/img/delete.png" title='Desactivar'></a></td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 

print("<td title='Tipo'>".$rows["tipo"]."</td>");
print("<td >Nombre: ".$rows["nombre"]." <br>");
print("Descripción: ".$rows["descripcion"]."</td>");
print("<td> ".$rows["responsable"]."<br>");
print("Departamento: ".$rows["departamento"]."</td>");
print("<td>Marca: ".$rows["marca"]."<br>");
print("Modelo: ".$rows["modelo"]."<br>");
print("Procesador".$rows["procesador"]."<br>");
print("RAM: ".$rows["memoriaRAM"]."<br>");
print("Disco Duro: ".$rows["capacidadHDD"]."<br>");
print("Costo: $".$rows["costoEquipo"]."<br>");
print("Factura: ".$rows["factura"]."</td>");

print("</tr>");
}
//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros</p>");
$resultado->close();
?>