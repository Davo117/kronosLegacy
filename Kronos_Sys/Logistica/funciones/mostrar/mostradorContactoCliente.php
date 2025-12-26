<?php
include_once '../../../Database/db.php';
echo "<table  border='0' cellpadding='15' class='table table-hover'>
<th>Acciones</th> <th>Contacto</th> <th>Datos</th>";

//Mientras que se tengan resultados se le asignan a $rows mediante 
$q=$_GET['q'];
$resultado = $MySQLiconn->query("SELECT * FROM $tablaconcli where bajaconcli=1 && idcliFK='$q' ORDER BY idconcli DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
//asignamos el atributo value con el id al que corresponde cada registro
?>
<tr>
<td><a href="?edit=<?php echo $rows["idconcli"]; ?>" ><IMG src="funciones/img/modify.png" title='Modificar'></a>
&nbsp; 
    <a href="?del=<?php echo $rows["idconcli"]; ?>" ><IMG src="funciones/img/delete.png" title='Desactivar'></a></td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 

echo "<td title='Contacto'>Puesto: "; echo $rows["puestoconcli"]."<br>
Nombre: ".$rows["nombreconcli"]; echo "</td>
<td>Telefono: "; echo $rows["telefonoconcli"]."<br>
Celular: "; echo $rows["movilcl"]."<br>
Correo: "; echo $rows["emailconcli"]."</td>
</tr>";
}

//Contar registros:
$row_cnt=$resultado->num_rows;
echo "<h4 class='ordenMedio'>Contactos Activos</h4>
<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>";
$resultado->close();
?>