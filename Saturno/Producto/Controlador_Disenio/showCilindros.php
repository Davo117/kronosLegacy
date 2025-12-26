<?php

print("<th>Acciones</th>");
print("<th>Cilindro</th>");
print("<th>Detalles</th>");
print("<th>Producción (Millares)</th>");
print("<th>Diseño( )</th>");
print("<th>Cilindro[ ]</th>");

$desc=$_SESSION['descripcionCil'];//Aqui se extrae de la sesion la descripcion de la impresion ala cual corresponde el juego de cilindros

$resultado = $MySQLiconn->query("SELECT*from juegoscilindros inner join impresiones on juegoscilindros.descripcionImpresion=impresiones.descripcionImpresion where juegoscilindros.baja=1 && juegosCilindros.descripcionImpresion='$desc'");
while ($rows = $resultado->fetch_array()) {
print("<tr>");
?>
<td>&nbsp &nbsp<a href="?edit=<?php echo $rows["IDCilindro"]; ?>"" ><IMG src="../Pictures/modificarProducto.png" title='Modificar'></a>  
    <a href="?del=<?php echo $rows["ID"]; ?>" onclick="return confirm('sure to delete !'); " ><IMG src="../Pictures/deletProducto.png" title='eliminar'></a></a> 
<?php


$perimetro=$rows['diametro']*3.1416;
$rendimiento=($rows['repAlPaso']*$rows['repAlGiro']*$rows['girosGarantizados'])/1000;
//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='Juego Cilindro'><b Style='font-weight:bold'>".$rows["identificadorCilindro"]."</b></td>");
print("<td title='Detalles'>Perimetro:<b Style='font-weight:bold'>".$perimetro."</b><br>Diámetro:<b Style='font-weight:bold'>".$rows["diametro"]."</b><br>Tabla:<b Style='font-weight:bold'>".$rows["tabla"]."</td>");
print("<td title='producción':>Rendimiento:<b Style='font-weight:bold'>".$rendimiento."</b><br>Programado:<br>Registrado:</td>");
print("<td title='Diseño':>Altura:<b Style='font-weight:bold'>".$rows["alturaEtiqueta"]."</b><br>Anchura:<b Style='font-weight:bold'>".$rows["anchoEtiqueta"]."</b></td>");
print("<td title='Cilindro':>Altura:<b Style='font-weight:bold'>".$rows["alturaEtiqueta"]."</b><br>Anchura:<b Style='font-weight:bold'>".$rows["anchoEtiqueta"]."</b></td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar' >Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();
?>