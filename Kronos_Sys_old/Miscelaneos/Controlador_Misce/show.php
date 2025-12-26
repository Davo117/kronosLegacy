<?php
echo "<th>Acciones</th> <th>Descripción</th> <th>Alias</th> <th>Diseños</th>";

$resultado = $MySQLiconn->query("SELECT tipoproducto.juegoprocesos,tipoproducto.juegoparametros,tipoproducto.id,tipoproducto.tipo,tipoproducto.alias,tipoproducto.baja,(select count(descripcion)from producto where tipo=tipoproducto.alias and producto.baja=1) as numero FROM tipoproducto order by id desc");

while ($rows = $resultado->fetch_array()) {
print("<tr>");
if($rows['baja']==1)
{ ?>
<td><a href="?del=<?php echo $rows["id"]; ?>" ><IMG src="../pictures/deletProducto.png" title='eliminar'></a></a> 
  <?php /*<a href="?param=<?php echo $rows["tipo"]; ?>"><IMG src="../pictures/rodillo.png" title='impresiones'></a>
  */  ?> 
     <a href="?proces=<?php echo $rows["juegoprocesos"]; ?>"><IMG src="../pictures/factory.png" title='Procesos'></a></td>
<?php
}
else if($rows['baja']==0)
{ ?>

<td><a href="?acti=<?php echo $rows["id"]; ?>"" ><IMG src="../pictures/unnamed.png" title='Modificar'></a>  
    <a href="?delfin=<?php echo $rows["id"]; ?>"  onclick="return confirm('Se eliminarán definitivamente todos los registros que involucren este tipo de producto:Impresiones,productos,a excepcion de ordenes de compra y del historial de producción Esta seguro de que desea continuar?')";><IMG src="../pictures/definitivo.png" title='eliminar definitivamente'></a></a>
    <?php
}

//Traemos la tabla y la imprimimos donde pertenece 
print("<td title='Descripción'>".$rows["tipo"]."</td>");
print("<td title='Alias'>".$rows["alias"]."</td>");
print("<td title='Diseños'>".$rows["numero"]."</td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;
echo "<p id='mostrar'>".$row_cnt." Activos</p>";
$resultado->close();
?>