<?php
print("<table  border='0' cellpadding='15' class='table table-hover'>");
print("<th style='width:100px;'>Acciones</th>");
print("<th style='width:130px;'>Identificador</th>");
print("<th>Nombre</th>");
print("<th>Sustrato</th>");
print("<th>Anchura</th>");
//print("<th style='width:120px;'>Pre-Embosado</th>");

//$desc=$_SESSION['descripcionBanda'];
include_once'db_Producto.php';
$q=$_GET['comboBSPP'];
$resultado = $MySQLiconn->query("SELECT * FROM bandaspp where baja=0 && identificadorBS='$q'");
while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td><a href="?acti=<?php echo $rows['IdBSPP'];?>&ban=<?php echo $rows['identificadorBS']?>""" ><IMG src="../pictures/unnamed.png" title='reactivar'></a>  
    <a href="?delfin=<?php echo $rows['IdBSPP']; ?>&ban=<?php echo $rows['identificadorBS']?>"" onclick="return confirm('¿Seguro que desea eliminar definitivamente?(Eso es mucho tiempo)'); " ><IMG src="../pictures/definitivo.png" title='eliminar definitivamente'></a></a> 
<?php
//Traemos la tabla y la imprimimos donde pertenece 
$embosado="No";
if($rows["preEmbosado"]==1)
{
$embosado="Sí";
}
print("<td title='identificador'>".$rows["identificadorBSPP"]."</td>");
print("<td title='nombre'>".$rows["nombreBSPP"]."</td>");
print("<td title='sustrato'>".$rows["sustrato"]."</td>");
print("<td title='anchura'>".$rows["anchuraLaminado"]."</td>");
//print("<td title='anchura'>".$embosado."</td>");
print("</tr>");
}

$row_cnt=$resultado->num_rows;
printf("<p id='mostrar' >Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();
print("</table>");
?>