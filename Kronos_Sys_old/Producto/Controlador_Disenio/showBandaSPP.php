<?php
print("<table   border='0' cellpadding='15' class='table table-hover'>");
print("<th style='width:100px;'>Acciones</th>");
print("<th style='width:130px;'>Identificador</th>");
print("<th>Nombre</th>");
print("<th>Sustrato</th>");
print("<th>Anchura</th>");
//print("<th style='width:120px;'>Pre-Embosado</th>");

//$desc=$_SESSION['descripcionBanda'];
include_once'db_Producto.php';
if(isset($_GET['comboBSPP']))
{
	$q=$_GET['comboBSPP'];
}
else if(isset($_GET['ban']))
{
	$q=$_GET['ban'];
}
if(isset($q))
{
	$resultado = $MySQLiconn->query("SELECT * FROM bandaspp where baja=1 && identificadorBS='$q'");
while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td><a href="?edit=<?php echo $rows['IdBSPP'];?>&ban=<?php echo $rows['identificadorBS']?>" ><IMG src="../pictures/modificarproducto.png" title='Modificar'></a>  
    <a href="?del=<?php echo $rows['IdBSPP']; ?>&ban=<?php echo $rows['identificadorBS']?>"" ><IMG src="../pictures/deletProducto.png" title='eliminar'></a></a> 
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
}

print("</table>");
?>