<?php

print("<th style='width:100px;'>Acciones</th>");
print("<th>Código</th>");
print("<th>Descripción</th>");

$resultado = $MySQLiconn->query("SELECT * FROM sustrato where baja=0 order by idSustrato desc");
while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td><a href="?acti=<?php echo $rows["idSustrato"]; ?>"" ><IMG src="../pictures/unnamed.png" title='Modificar'></a>  
    <a href="?delfin=<?php echo $rows["idSustrato"]; ?>" onclick="return confirm('¿Seguro que desea eliminar definitivamente?(eso es mucho tiempo)'); " ><IMG src="../pictures/definitivo.png" title='eliminar'></a></td>
<?php
//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='Código'>".$rows["codigoSustrato"]."</td>");
print("<td title='Descripción'>".$rows["descripcionSustrato"]."</td>");
print("</tr>");
}
//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();
?>