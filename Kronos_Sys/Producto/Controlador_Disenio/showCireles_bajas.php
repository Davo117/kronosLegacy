<?php

"<table border='0' cellpadding='15' class='table table-hover'>	<th style='width:10%;'>Acciones</th>	<th>Identificador</th>	<th>Detalles</th>	<th>Diseño( )</th>";

//$desc=$_SESSION['descripcionCil'];//Aqui se extrae de la sesion la descripcion de la impresion ala cual corresponde el juego de cilindros
include_once 'db_Producto.php';
$q=$producto;
$resultado = $MySQLiconn->query("SELECT i.id as producto,j.id, j.num_dientes, j.producto, j.identificadorJuego, j.ancho_plano, i.alturaEtiqueta, i.anchoPelicula, i.descripcionImpresion from juegoscireles as j inner join impresiones as i on j.producto=i.id where j.baja=0 and i.baja=1 and j.producto='$q'");
while ($rows = $resultado->fetch_array()) {
print("<tr>"); ?>
<td><a href="?acti=<?php echo $rows["id"]; ?>&producto=<?php echo $rows["producto"]; ?>"><IMG src="../pictures/unnamed.png" title='Activar'></a>
    <a href="?eli=<?php echo $rows["id"]; ?>&producto=<?php echo $rows["producto"]; ?>"><IMG src="../pictures/definitivo.png" title='Eliminar'></a></a>
<?php

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