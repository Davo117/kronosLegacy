<?php
print("<th>acciones</th>");
print("<th>Identificador</th>");
print("<th>nombre</th>");
print("<th>Sustrato</th>");
print("<th>anchura</th>");

$desc=$_SESSION['descripcionBanda'];
$resultado = $MySQLiconn->query("SELECT * FROM bandaSPP where baja=1 && identificadorBS='$desc'");
while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td>&nbsp &nbsp<a href="?edit=<?php echo $rows['IdBSPP'];?>"" ><IMG src="../Pictures/modificarProducto.png" title='Modificar'></a>  
    <a href="?del=<?php echo $rows['IdBSPP']; ?>" onclick="return confirm('sure to delete !'); " ><IMG src="../Pictures/deletProducto.png" title='eliminar'></a></a> 
<?php
//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='identificador'>".$rows["identificadorBSPP"]."</td>");
print("<td title='nombre'>".$rows["nombreBSPP"]."</td>");
print("<td title='sustrato'>".$rows["sustrato"]."</td>");
print("<td title='anchura'>".$rows["anchuraLaminado"]."</td>");
print("</tr>");
}

$row_cnt=$resultado->num_rows;
printf("<p id='mostrar' >Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();
?>