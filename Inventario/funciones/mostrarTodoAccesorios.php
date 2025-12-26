<?php
print("<th style='width: 15%;'>Acciones</th>");
print("<th style='width: 15%;'>Tipo</th>");
print("<th style='width: 25%;'>Datos</th>");
print("<th style='width: 40%;'>Movimiento</th>");
print("<th>Baja</th>");

//Mientras que se tengan resultados se le asignan a $rows mediante 

$resultado = $MySQLiconn->query("SELECT * FROM accesorios where baja=0  ORDER BY id_acc DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
print("<tr>");
//asignamos el atributo value con el id al que corresponde cada registro
?>
<td><a href="?activar=<?php echo $rows["id_acc"]; ?>" ><IMG src="funciones/img/activo.png" title='Activar'></a> 
    <a href="?eli=<?php echo $rows["id_acc"]; ?>"><IMG src="funciones/img/deleteDef.png" title='Eliminar'></a></td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 
print("<td title='Tipo'>".$rows["tipo"]."</td>");
print("<td title='Marca'>".$rows["marca"]."</td>");
print("<td>Modelo: ".$rows["modelo"]."<br>");
print("Departamento: ".$rows["fch_ingreso"]."</td>");
print("</tr>");
}
//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros Desactivados</p>");
$resultado->close();
?>