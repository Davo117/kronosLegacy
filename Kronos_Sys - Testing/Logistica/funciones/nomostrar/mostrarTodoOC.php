<?php
echo "<th>Acciones</th> <th>Orden Compra</th> <th>Documento</th>";
//Mientras que se tengan resultados se le asignan a $rows mediante 

//idorden	orden	documento	recepcion	bajaOrden	sucFK

$resultado=$MySQLiconn->query("SELECT o.*, s.nombresuc FROM $tablaOrden o left join $tablasucursal s on o.sucFK=s.idsuc where o.bajaOrden=0 ORDER BY o.idorden DESC;");


while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
//asignamos el atributo value con el id al que corresponde cada registro ?>
<tr>
<td><a href="?activar=<?php echo $rows["idorden"]; ?>" ><IMG src="funciones/img/activo.png" title='Activar'></a> 
&nbsp; &nbsp; 
    <a href="?eli=<?php echo $rows["idorden"]; ?>"><IMG src="funciones/img/deleteDef.png" title='Eliminar'></a></td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 
echo "<td title='Sucursal'>Sucursal: "; echo $rows["nombresuc"]; echo "&nbsp &nbsp &nbsp
Orden: "; echo $rows["orden"]; echo "</td>
<td title='Fecha'>"; echo $rows["documento"]; echo "</td>
</tr>";
}

 //Contar registros:

$row_cnt=$resultado->num_rows;
echo "<h4 class='ordenMedio'>Ordenes Inactivas</h4>
<p id='mostrar'>Se muestran: ".$row_cnt." Registros Desactivados</p>";
$resultado->close();

?>