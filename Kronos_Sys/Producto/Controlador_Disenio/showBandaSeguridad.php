<?php

print("<th style='width:120px;'>Acciones</th>");
print("<th >Identificador</th>");
print("<th>Anchura</th>");
print("<th>Descripción</th>");

$resultado = $MySQLiconn->query("SELECT * FROM bandaseguridad where baja=1 ");
while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td><a href="?edit=<?php echo $rows["IDBanda"]; ?>"" ><IMG src="../pictures/modificarproducto.png" title='Modificar'></a>  
    <a href="?del=<?php echo $rows["IDBanda"]; ?>" ><IMG src="../pictures/deletProducto.png" title='eliminar'></a></a> 
     <a href="?ban=<?php echo $rows["IDBanda"]; ?>"><IMG src="../pictures/banda.png" title='Bandas de seguridad por proceso'></a></td>
<?php
//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='identificador'>".$rows["identificador"]."</td>");
print("<td title='Anchura'>".$rows["anchura"]."</td>");
print("<td title='Descripción'>".$rows["nombreBanda"]."</td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar' >Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();
?>
