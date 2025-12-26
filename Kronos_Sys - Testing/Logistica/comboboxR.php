<?php
include ("../Database/db.php");
include("funciones/crud/crudOrdenReq.php");
//Mientras que se tengan resultados se le asignan a $rows mediante 
//traermos el parametro por post y se lo asignamos a la consulta de base de datos.
$r=$_POST['r']; ?>
<option value=''>Seleccionar Diseño</option>
<?php //Seleccionar todos los datos de la tabla 

$retener=$MySQLiconn->query("SELECT prodcliReqFK FROM $reqProd where bajaReq='1' && ordenReqFK='$r' order by prodcliReqFK ASC ");
$i=0; $factor='';
while ($rowR=$retener->fetch_array()){
	if ($i=='0') { $factor=$factor."&& nombre!='".$rowR['prodcliReqFK']."'";	}
	else { 	$factor=$factor." && nombre!='".$rowR['prodcliReqFK']."'";	}
	$i++;
}
$resultado=$MySQLiconn->query("SELECT * FROM $prodcli where baja='1' $factor  order by nombre ASC");
//Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:
while ($row=$resultado->fetch_array()) { ?>
	<option value="<?php echo $row ['nombre'];?>"> [<?php echo $row['IdentificadorCliente'];?>] <?php echo $row ['nombre'];?></option><?php
} ?>
</select>