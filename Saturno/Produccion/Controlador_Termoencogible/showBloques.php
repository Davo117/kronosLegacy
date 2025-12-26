<?php

print("<th>Acciones</th>");
print("<th>Bloque</th>");
print("<th>Sustrato</th>");
print("<th>Peso</th>");

$resultado = $MySQLiconn->query("SELECT * FROM bloquesMateriaPrima where baja=1 ");
while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td>&nbsp &nbsp<a href="?edit=<?php echo $rows["idBloque"]; ?>"" ><IMG src="../Pictures/modificarProducto.png" title='Modificar'></a>  
    <a href="?del=<?php echo $rows["idBloque"]; ?>" onclick="return confirm('sure to delete !'); " ><IMG src="../Pictures/deletProducto.png" title='eliminar'></a></a> 
     <a href="?lot=<?php echo $rows["nombreBloque"]; ?>"><IMG src="../Pictures/boxLote.png" title='Lotes de materia prima'></a>
      <a href="?exc=<?php echo $rows["idBloque"]; ?>"><IMG src="../Pictures/excel.png" title='Excel'></a>
       <a href="?repor=<?php echo $rows["idBloque"]; ?>"><IMG src="../Pictures/reports.png" title='Reporte de lotes'></a>
        <a href="?etiq=<?php echo $rows["idBloque"]; ?>"><IMG src="../Pictures/barras.png" title='Etiquetas'></a> </td>
<?php
//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='Bloque'>".$rows["identificadorBloque"]."|".$rows["nombreBloque"]."</td>");
print("<td title='Sustrato'>".$rows["sustrato"]."</td>");
print("<td title='Peso'>".$rows["peso"]."</td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p>Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();

?>