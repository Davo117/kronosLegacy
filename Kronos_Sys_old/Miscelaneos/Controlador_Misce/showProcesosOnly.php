<?php

print("<th style='width:150px;'>Acciones</th>");
print("<th>Descripción del proceso</th>");
print("<th>Abreviación de proceso</th>");
print("<th>Merma permitida</th>");


$resultado = $MySQLiconn->query("SELECT* from procesos where baja=1");

while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td>&nbsp &nbsp<a href="?edit=<?php echo $rows["id"]; ?>"" ><IMG src="../pictures/modificarproducto.png" title='Modificar'></a>  
    <a href="?del=<?php echo $rows["id"]; ?>" ><IMG src="../pictures/deletProducto.png" title='eliminar'></a></a> 
 <?php /*   <a href="?param=<?php echo $rows["descripcionProceso"]; ?>"><IMG src="../pictures/rodillo.png" title='impresiones'></a>*/  ?> 
<?php
//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='descripcion del Proceso'>".$rows["descripcionProceso"]."</td>");
print("<td title='Abreviación'>".$rows["abreviacion"]."</td>");
print("<td title='Merma permitida'>".$rows["merma_p"]."</td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;

printf("<p id='mostrar' >Se muestran: ".$row_cnt." Procesos Activos</p>");
$resultado->close();
?>
