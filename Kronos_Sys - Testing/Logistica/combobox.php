
<option value=''>Seleccionar Impresión</option>

<?php
include ("../Database/db.php");
include("funciones/crud/crudOrdenConfi.php");
//traermos el parametro por post y se lo asignamos a la consulta de base de datos.
$r=$_POST['r'];

$primera = $MySQLiconn->query("SELECT prodcliReqFK FROM $reqProd where ordenReqFK='$r'  && bajaReq='1'");
while($row1 = $primera->fetch_array()){
	//esta segunda consulta, se trae datos de otra tabla relacional a es
	$segunda = $MySQLiconn->query("SELECT * FROM $impresion where codigoCliente='".$row1['prodcliReqFK']."' && baja='1'");
	//bandera que no tendra datos
	$empresa='ssss';
	while($row2 = $segunda->fetch_array()){
		$diseño = $MySQLiconn->query("SELECT descripcion from producto where ID='".$row2['descripcionDisenio']."'");
    	$rowD=$diseño->fetch_array();

		//mientras se tengan registros:
		// asignamos una 3ra consulta para agrupar datos dependiendo el producto cliente
		$agrupar = $MySQLiconn->query("SELECT * from productoscliente where IdProdCliente='".$row1['prodcliReqFK']."' and baja='1'");

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
		if(isset($_GET['edit'])){
			if($getROW['prodConfi']==$row2['id']){ ?>
		<option  value="<?php echo $row2 ['id'];?>">holamundooo   </option>	<?php
			}	
		}else{
			//Sino manda la lista normalmente: 	?>
			<option value="<?php echo $row2 ['id'];?>"><?php echo $rowD['descripcion']." | " .$row2['descripcionImpresion'];?>  </option>	<?php
		}
	}
} ?>
</optgroup>
</datalist>