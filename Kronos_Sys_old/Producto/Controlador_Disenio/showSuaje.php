<?php

echo "<h4 class='ordenMedio'>Suajes Activos</h4>
<table border='0' cellpadding='15' class='table table-hover'>
	<th style='width:10%;'>Acciones</th>	<th>Identificador</th>	<th>Detalles</th>	<th>Diseño( )</th>	<th>Observaciones</th>";

//$desc=$_SESSION['descripcionCil'];//Aqui se extrae de la sesion la descripcion de la impresion ala cual corresponde el juego de cilindros
include_once 'db_Producto.php';
$q=$_GET['q'];
$resultado = $MySQLiconn->query("SELECT * from suaje where baja=1 and descripcionImpresion='$q'");
while ($rows = $resultado->fetch_array()) {
echo "<tr>"; ?>
<td><a href="?edit=<?php echo $rows["id"]; ?>"" ><IMG src="../pictures/modificarproducto.png" title='Modificar'></a>
    <a href="?del=<?php echo $rows["id"]; ?>"><IMG src="../pictures/deletProducto.png" title='Desactivar'></a></a>
<?php

//Traemos la tabla y la imprimimos donde pertenece
print("<td title='Identificador de Suaje'><b class='pam'>".$rows["identificadorSuaje"]."</b>
	</td>");
print("<td title='cantidad'>Altura real:<b class='pam'>".$rows['alturaReal']."</b><br>Código: <b class='pam'>".$rows['codigo']."</b><br>Piezas: <b class='pam'>".$rows['piezas']."</b></td>");
print("<td title='Medidas de la impresión':>Altura:<b class='pam'>".$rows["alturaImpresion"]."</b><br>Anchura:<b class='pam'>".$rows["anchuraImpresion"]."</b></td>");
print("<td title='Observaciones':>Resguardo: <b class='pam'>".$rows["reguardo"]."</b><br>Proceso: <b class='pam'>".$rows["proceso"]."</b><br>Observaciones:<b class='pam'>".$rows["observaciones"]."</b></td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar' >Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();
print("</table>");
?>