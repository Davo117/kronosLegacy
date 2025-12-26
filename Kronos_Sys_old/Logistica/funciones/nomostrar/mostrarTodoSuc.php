<?php
include_once '../../../Database/db.php';
echo "<table border='0' cellpadding='15' class='table table-hover'>
<th>Acciones</th> <th>Nombre</th> <th>Teléfono</th>";

$q=$_GET['q'];
//Mientras que se tengan resultados se le asignan a $rows mediante 

$resultado=$MySQLiconn->query("SELECT * FROM $tablasucursal where bajasuc=0 && idcliFKS='$q' ORDER BY idsuc DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
//asignamos el atributo value con el id al que corresponde cada registro ?>
<tr>
<td><a href="?activar=<?php echo $rows["idsuc"]; ?>" ><IMG src="funciones/img/activo.png" title='Activar'></a> 
&nbsp; 
    <a href="?eli=<?php echo $rows["idsuc"]; ?>" ><IMG src="funciones/img/deleteDef.png" title='Eliminar'></a></td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 
echo "<td title='Nombre'>"; echo $rows["nombresuc"]; echo "</td>
<td title='Teléfono'>"; echo $rows["telefonosuc"]; echo "</td>
</tr>";
}

 //Contar registros:
$row_cnt=$resultado->num_rows;
echo "<h4 class='ordenMedio'>Sucursales Inactivas</h4>
<p id='mostrar'>Se muestran: ".$row_cnt." Registros Desactivados</p>";
$resultado->close();
?>