<?php
include_once '../../../Database/db.php';
echo "<table border='0' cellpadding='15' class='table table-hover'>
<th>Acciones</th> <th>Contacto</th> <th>Datos</th>";

//Mientras que se tengan resultados se le asignan a $rows mediante 
$q=$_GET['q'];
$resultado = $MySQLiconn->query("SELECT * FROM $tablaconcli where bajaconcli=0 && idcliFK='$q' ORDER BY idconcli DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
//asignamos el atributo value con el id al que corresponde cada registro ?>
<tr>
<td><a href="?activar=<?php echo $rows["idconcli"]; ?>" ><IMG src="funciones/img/activo.png" title='Activar'></a> 
&nbsp
<a href="?eli=<?php echo $rows["idconcli"]; ?>" ><IMG src="funciones/img/deleteDef.png" title='Eliminar'></a></td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 

echo "<td title='Contacto'>Puesto: "; echo $rows["puestoconcli"]; echo "<br>
Nombre: "; echo $rows["nombreconcli"]; echo "</td>
<td>Telefono: "; echo $rows["telefonoconcli"]; echo "<br>
Celular: "; echo $rows["movilcl"]; echo "<br>
Correo: "; echo $rows["emailconcli"]; echo "</td>
</tr>";
}

//Contar registros:
$row_cnt=$resultado->num_rows;
echo "<h4 class='ordenMedio'>Contactos Inactivos</h4>
<p id='mostrar'>Se muestran: ".$row_cnt." Registros Desactivados</p>";
$resultado->close();

?>