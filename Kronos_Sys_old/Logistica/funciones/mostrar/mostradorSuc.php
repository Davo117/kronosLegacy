<?php
include ('../../../Database/db.php');
echo "<table   border='0' cellpadding='15' class='table table-hover'>
<th>Acciones</th> <th>Nombre</th> <th>Teléfono</th>";

$q=$_GET['q'];
$_SESSION['cliente']=$_GET['q'];

//Mientras que se tengan resultados se le asignan a $rows mediante 

$resultado=$MySQLiconn->query("SELECT * FROM $tablasucursal where bajasuc=1 && idcliFKS='$q' ORDER BY idsuc DESC;");

while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
//asignamos el atributo value con el id al que corresponde cada registro ?>
<tr>
<td> <a href="?edit=<?php echo $rows["idsuc"]; ?>"><IMG src="funciones/img/modify.png" title='Modificar'></a>
&nbsp; 
<a href="?del=<?php echo $rows["idsuc"]; ?>"><IMG src="funciones/img/deleteOtro.png" title='Desactivar'></a>
&nbsp; &nbsp; 
<a href="?comboconsuc=<?php echo $rows["nombresuc"]; ?>"><IMG src="funciones/img/contacto.png" title='Contacto Sucursal'></a>
&nbsp; 
<a href="?comboOC=<?php echo $rows["nombresuc"]; ?>"><IMG src="funciones/img/ordencom.png" title='Orden Compra'></a>
&nbsp; 
<a href="?comboembar=<?php echo $rows["nombresuc"]; ?>"><IMG src="funciones/img/embarque.png" title='Embarques'></a>
 </td>
<?php
//Traemos la tabla y la imprimimos debidamente donde pertenece 
echo "<td title='Nombre'>&nbsp &nbsp  "; echo $rows["nombresuc"]; echo "&nbsp &nbsp   </td>
<td title='Teléfono'>&nbsp  &nbsp  "; echo $rows["telefonosuc"]; echo "&nbsp  &nbsp  </td>
</tr>";
}
 

 //Contar registros:
$row_cnt=$resultado->num_rows;
echo "<h4 class='ordenMedio'>Sucursales Activas</h4>
<p id='mostrar'>Se muestran: ".$row_cnt." Registros Activos</p>";
$resultado->close();

?>