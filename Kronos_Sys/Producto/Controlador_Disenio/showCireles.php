<?php

echo "<table border='0' class='table table-hover'>
	<th style='width:10%;'>Acciones</th>	<th>Identificador</th>	<th>Detalles</th>	<th>Diseño( )</th>";

//$desc=$_SESSION['descripcionCil'];//Aqui se extrae de la sesion la descripcion de la impresion ala cual corresponde el juego de cilindros
include_once 'db_Producto.php';
$q=$producto;
$resultado = $MySQLiconn->query("SELECT  DISTINCT i.id as producto,j.id, j.num_dientes, j.producto, j.identificadorJuego, j.ancho_plano, i.alturaEtiqueta, i.anchoPelicula, i.descripcionImpresion,l.estado as prod from juegoscireles  j inner join impresiones  i on j.producto=i.id
	LEFT JOIN produccion pr
ON pr.juegoCireles=j.identificadorJuego
LEFT JOIN lotes l ON l.juegoLote=pr.juegoLotes and l.estado=1
WHERE j.baja=1  and i.baja=1 and j.producto='$q' group by j.identificadorJuego");
while ($rows = $resultado->fetch_array()) {
print("<tr>"); ?>
<td><a href="?edit=<?php echo $rows["id"]; ?>&producto=<?php echo $rows["producto"]; ?>"><IMG src="../pictures/modificarproducto.png" title='Modificar'></a>
	<?php
	if($rows['prod']!=1)
	{?>
		 <a href="?del=<?php echo $rows["id"]; ?>&producto=<?php echo $rows["producto"]; ?>"><IMG src="../pictures/deletProducto.png" title='Desactivar'></a></a>
		 <?php
	} 
	

//Traemos la tabla y la imprimimos donde pertenece
print("<td title='Identificador de juego de cireles'>Nombre: <b class='pam'>".$rows["identificadorJuego"]."</b><br>Impresión: <b class='pam'>".$rows["descripcionImpresion"]."</b></td>");
print("<td title='cantidad'>Número de dientes:<b class='pam'>".$rows['num_dientes']."</b><br>Ancho Plano: <b class='pam'>".$rows['ancho_plano']."</b></td>");
print("<td title='Medidas de la impresión':>Altura:<b class='pam'>".$rows["alturaEtiqueta"]."</b><br>Anchura:<b class='pam'>".$rows["anchoPelicula"]."</b></td>");
print("</tr>");

}

//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar' >Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();
print("</table>");
?>