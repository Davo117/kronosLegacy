<?php
include ("../Database/db.php");
include("funciones/crud/crudOrdenConfi.php");
//Mientras que se tengan resultados se le asignan a $rows mediante 
//traermos el parametro por post y se lo asignamos a la consulta de base de datos.
$r=$_POST['r'];

?>
<select id="combillo" name="producto" class="form-control" required>

<?php 
$primera = $MySQLiconn->query("SELECT prodcliReqFK FROM $reqProd where ordenReqFK='$r' ");
while($row1 = $primera->fetch_array()){
	//esta segunda consulta, se trae datos de otra tabla relacional a es
	$segunda = $MySQLiconn->query("SELECT descripcionImpresion, descripcionDisenio FROM $impresion where codigoCliente='".$row1['prodcliReqFK']."' && baja='1'");
	//bandera que no tendra datos
	$empresa='ssss';
	while($row2 = $segunda->fetch_array()){
		//mientras se tengan registros:
		// asignamos una 3ra consulta para agrupar datos dependiendo el producto cliente
		$agrupar = $MySQLiconn->query("SELECT * from productoscliente where nombre='".$row1['prodcliReqFK']."' and baja='1'");

		$rowAgrupar = $agrupar->fetch_array();

		// Comparas tu variable con la empresa
		if($empresa != $rowAgrupar['IdentificadorCliente']) {
        	// Ahora habra que comparar con esta empresa cada que de vuelta los datos 
			$empresa = $rowAgrupar['IdentificadorCliente'];
			//se agrega dentro del if la etiqueta de optgroup para inicializar el orden
        	//no te olvides de la etiqueta de cierre
			?><optgroup label="<?php echo $empresa; ?>">
			<?php //cerramos el if anterior:
		}
		//Sino manda la lista normalmente: 	?>
		<option value="<?php echo $row2 ['descripcionImpresion'];?>"><?php echo $row2 ['descripcionDisenio']." | " .$row2['descripcionImpresion'];?>  </option>
		<?php 
	} 
} ?> 
</optgroup>
</datalist>
</select>

