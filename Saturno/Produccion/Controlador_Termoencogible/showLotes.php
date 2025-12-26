<?php

print("<th>Acciones</th>");
print("<th>Referencia</th>");
print("<th>Longitud</th>");
print("<th>Peso</th>");
print("<th>No c bro</th>");

$desc=$_SESSION['lote'];//Aqui se extrae de la sesion la descripcion de la impresion ala cual corresponde el juego de cilindros
$tarima=$_SESSION['tarima'];
$peso=0;
$longitud=0;
$resultado = $MySQLiconn->query("SELECT * FROM lotes where baja=1 && bloque='$desc' && tarima='$tarima'");
while ($rows = $resultado->fetch_array()) {
print("<tr>");
if($rows["estado"]==0){
?>
<td>&nbsp &nbsp<a href="?edit=<?php echo $rows["idLote"]; ?>"" ><IMG src="../Pictures/modificarProducto.png" title='Modificar'></a>  
    <a href="?produ=<?php echo $rows["idLote"]; ?>" onclick="return confirm('¿Esta seguro? !'); " ><IMG src="../Pictures/factory.png" title='calendario'></a></td> 
<?php
}
else if($rows["estado"]==1)
{
	?>
	<td>&nbsp &nbsp<a href="?edit=<?php echo $rows["idLote"]; ?>"" ><IMG src="../Pictures/modificarProducto.png" title='Modificar'></a>  
    <a href="?calen=<?php echo $rows["idLote"]; ?>" onclick="return confirm('Esta seguro? !'); " ><IMG src="../Pictures/calendar.png" title='producción'></a></td>
    <?php
}
else if($rows["estado"]==2)
{
	?>
	<td>&nbsp &nbsp<a href="?edit=<?php echo $rows["idLote"]; ?>"" ><IMG src="../Pictures/modificarProducto.png" title='Modificar'></a>  
    <a href="?repor=<?php echo $rows["idLote"]; ?>" onclick="return confirm('Esta seguro? !'); " ><IMG src="../Pictures/verify.png" title='palomita'></a></td>
    <?php
}
else if($rows["estado"]==3)
{
	?>
	<td>&nbsp &nbsp<a href="?edit=<?php echo $rows["idLote"]; ?>"" ><IMG src="../Pictures/modificarProducto.png" title='Modificar'></a>  
    <a href="?del=<?php echo $rows["idLote"]; ?>" onclick="return confirm('Esta seguro? !'); " ><IMG src="../Pictures/deletProducto.png" title='eliminar'></a></td>
    <?php
}
//Traemos la tabla y la imprimimos donde pertenece
print("<td title='Referencia'>".$rows["referenciaLote"]."</td>");
print("<td title='Longitud'>".$rows["longitud"]."</td>");
print("<td title='Peso'>".$rows["peso"]."</td>");
print("<td title='No c bro'>"."No se"."</td>");
print("</tr>");
$peso=$peso+$rows['peso'];
$longitud=$longitud+$rows['longitud'];
}
$resultado = $MySQLiconn->query("SELECT tarima from lotes where baja=1 && bloque='$desc' && tarima='$tarima'");
$rows = $resultado->fetch_array();
print("<th>Info Tarima</th>");
print("<tr>");
print("<td  Style=font:'bold' title='Lote'>Tarima:".$rows["tarima"]."<br>Peso:$peso kgs<br>Longitud:$longitud mts</td>");
print("</tr>");




?>
