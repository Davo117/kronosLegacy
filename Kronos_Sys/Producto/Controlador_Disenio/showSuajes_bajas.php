
<?php
echo "<h4 class='ordenMedio'>Suajes Inactivos</h4>
<table border='1' cellpadding='15' class='table table-hover'>	<th style='width:10%;'>Acciones</th>	<th>Identificador</th>	<th>Detalles</th>	<th>Diseño( )</th>	<th>Observaciones</th>";

include_once 'db_Producto.php';
$q=$_GET['q'];
$resultado = $MySQLiconn->query("SELECT * from suaje where baja=0 and descripcionImpresion='$q'");
while ($rows = $resultado->fetch_array()) {
echo "<tr>"; ?>
<td><a href="?acti=<?php echo $rows["id"]; ?>" ><IMG src="../pictures/unnamed.png" title='Activar'></a>
    <a href="?eli=<?php echo $rows["id"]; ?>"><IMG src="../pictures/definitivo.png" title='Eliminar'></a></a>
<?php

//Traemos la tabla y la imprimimos donde pertenece
print("<td title='Identificador de Suaje'><b class='pam'>".$rows["identificadorSuaje"]."
	<b class'pam'>".$rows["proveedor"]."</b>
	
	</td>");
print("<td title='cantidad'>
	Altura Real:<b class='pam'>".$rows['alturaReal']."</b><br>Código: <b class='pam'>".$rows['codigo']."</b><br>Rep. al Paso: <b class='pam'>".$rows['piezas']."</b></td>");
print("<td title='Medidas de la impresión':>Altura:<b class='pam'>".$rows["alturaImpresion"]."</b><br>Anchura:<b class='pam'>".$rows["anchuraImpresion"]."</b></td>");
print("<td title='Observaciones':>Resguardo: <b>".$rows["reguardo"]."</b><br>Observaciones:<b class='pam'>".$rows["observaciones"]."</b></td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar' >Se muestran: ".$row_cnt." Registros Inactivos</p>");
$resultado->close();
print("</table>");
?>