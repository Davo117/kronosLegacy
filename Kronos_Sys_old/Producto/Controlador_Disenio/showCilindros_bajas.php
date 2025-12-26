<?php
print("<table border='0' cellpadding='15' class='table table-hover'>");
print("<th style='width:10%;'>Acciones</th>");
print("<th>Cilindro</th>");
print("<th>Detalles</th>");
print("<th>Producción (Millares)</th>");
print("<th>Diseño( )</th>");
print("<th>Cilindro[ ]</th>");

//$desc=$_SESSION['descripcionCil'];//Aqui se extrae de la sesion la descripcion de la impresion ala cual corresponde el juego de cilindros
include_once 'db_Producto.php';

$q=$impresion;
$MySQLiconn->query("UPDATE cache set dato='$q' where id=3");
$resultado = $MySQLiconn->query("SELECT*from juegoscilindros inner join impresiones on juegoscilindros.descripcionImpresion=impresiones.descripcionImpresion where juegoscilindros.baja=0 && juegoscilindros.descripcionImpresion='$q'");
while ($rows = $resultado->fetch_array()) {

print("<tr>");
?>
<td><a href="?acti=<?php echo $rows["IDCilindro"]; ?>" ><IMG src="../pictures/unnamed.png" title='Reactivar'></a>  
    <a href="?delfin=<?php echo $rows["IDCilindro"]; ?>"><IMG src="../pictures/definitivo.png" title='Eliminar definitivamente'></a></a> 
<?php


$perimetro=$rows['diametro']*3.1416;
$rendimiento=($rows['repAlPaso']*$rows['repAlGiro']*$rows['girosGarantizados'])/1000;

$soul=$MySQLiconn->query("SELECT sum(unidades) as desgaste,(SELECT sum(unidades) from proimpresion where juegoCilindros='".$rows['identificadorCilindro']."') as registrado from produccion where juegocilindros='".$rows['identificadorCilindro']."'");
$rev=$soul->fetch_array();
$desgaste=number_format($rev["desgaste"],3);
$registrado=number_format($rev["registrado"],3);
//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='Identificador de juego de cilindros'><b class='pam'>".$rows["identificadorCilindro"]."</b></td>");
print("<td title='Medidas en milímetros'>Perimetro:<b class='pam'>".$perimetro."</b><br>Diámetro:<b class='pam'>".$rows["diametro"]."</b><br>Tabla:<b class='pam'>".$rows["tabla"]."</b></td>");
print("<td title='Produccion en millares'>Rendimiento:<b title='Rendimiento total del cilindro' class='pam'>".$rendimiento."</b><br>Desgaste:<b class='pam'>".$desgaste."</b><br>Registrado:<b class='pam'>".$registrado."</b></td>");
print("<td title='Medidas de la impresión':>Altura:<b class='pam'>".$rows["alturaEtiqueta"]."</b><br>Anchura:<b class='pam'>".$rows["anchoPelicula"]."</b></td>");
print("<td title='Medidas reales de la impresión':>Altura:<b class='pam'>".$rows["alturaReal"]."</b><br>Anchura:<b class='pam'>".$rows["anchuraReal"]."</b></td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar' >Se muestran: ".$row_cnt." Registros Activos</p>");
$resultado->close();
print("</table>");
//print("<script>alert('tujfa')</script>");
?>