<?php
echo"<th>Registros</th>";

//Mientras que se tengan resultados se le asignan a $rows mediante 

$resultado = $MySQLiconn->query("SELECT * FROM $user  ORDER BY id DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
echo "<tr>";
//asignamos el atributo value con el id al que corresponde cada registro
?>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 

echo"<td><p>Empleado: <b>"; echo $rows["nombre"]."</b>"; ?>
<a href="?delU=<?php echo $rows["id"]; ?>"  style="float: right; margin-right: 30px; "><IMG src="funciones/img/deletee.png" title='Desactivar'></a></p>
<?php
echo "<p>Usuario:<b> ".$rows["usuario"]."</b></p>";
echo "<p>Rol:<b>".$rows["rol"]."</b></p></td>";
echo "</tr>";
}
$resultado->close();
?>