<?php
include_once '../Database/SQLConnection.php';
include_once 'db_Producto.php';
$usuarioF=$_SESSION['usuario'];
/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_GET['save']))
{
	//Pasamos los parametros por medio de post
	$subproceso = $MySQLiconn->real_escape_string($_GET['ComboProcesos']);
	$elemento =$MySQLiconn->real_escape_string($_GET['comboElementos']);   
	$consumo =$MySQLiconn->real_escape_string($_GET['cantConsumo']);  
	$producto=$MySQLiconn->real_escape_string($_GET['comboDisenios2']);

	//Realizamos la consulta
	 $SQL =$MySQLiconn->query("INSERT INTO consumos(subProceso,elemento,consumo,producto) VALUES('$subproceso','".$elemento."','$consumo','".$producto."')");
	 
	//En caso de ser diferente la consulta:
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
		echo "<div class='alert alert-danger alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Error</strong> , Error al agregar el dato
		</div>
		";
	} 
	else{
		$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego un consumo ala impresión:  $producto','Consumos','Productos',NOW())");

		//Mandamos un mensaje de exito:
		echo "<div class='alert alert-success alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Operación exitosa</strong> , Se ha agregado un nuevo consumo
		</div>
		";
	}
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
$SQL = $MySQLiconn->query("SELECT descripcionImpresion FROM impresiones  WHERE id=(SELECT producto FROM consumos WHERE IDConsumo=".$_GET['del'].")");
$get = $SQL->fetch_array();
$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo un consumo de la impresión: ".$get['producto']."','Consumos','Productos',NOW())");


	$SQL = $MySQLiconn->query("UPDATE consumos SET baja=0 WHERE IDConsumo=".$_GET['del']);
	header("Location: Producto_Consumos.php?descripcionCons=".$_GET['descripcionCons']."");
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Dado de Baja Exitosamente')</script>";
}
/* Fin Código Eliminación Logíca  */

/* Inicio Código Eliminación Definitiva 
if(isset($_GET['del']))
{ //Cambiar el parametro "del" para que no haya conflictos:
	$SQL = $MySQLiconn->query("DELETE FROM empleado WHERE id=".$_GET['del']);
	header("Location: index.php");
}
 Fin Código Eliminación Definitiva */



/* Inicio Código Atualizar  */
if(isset($_GET['edit']))
{
	$SQL = $MySQLiconn->query("SELECT * FROM consumos WHERE IDConsumo=".$_GET['edit']);
	$getROW = $SQL->fetch_array();

} 

if(isset($_GET['update'])){
	$porPOST=$getROW['producto'];

$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo el consumo del producto:  $porPOST','Consumos','Productos',NOW())");

	$SQL = $MySQLiconn->query("UPDATE consumos SET subProceso='".$_GET['ComboProcesos']."', elemento='".$_GET['comboElementos']."',consumo='".$_GET['cantConsumo']."' WHERE IDConsumo=".$_GET['edit']);

	header("Location: Producto_Consumos.php");
	 
}
if(isset($_GET['acti']))
{
	$SQL=$MySQLiconn->query("SELECT descripcionImpresion FROM impresiones  WHERE id=(SELECT producto FROM consumos WHERE IDConsumo=".$_GET['acti'].")");
	$get = $SQL->fetch_array();
 	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo un consumo de la impresión: ".$get['producto']."','Consumos','Productos',NOW())");
	$SQL = $MySQLiconn->query("UPDATE consumos set baja=1 where IDConsumo=".$_GET['acti']);
	header("Location: Producto_Consumos.php?descripcionCons=".$_GET['descripcionCons']."");
} 

if(isset($_GET['delfin'])){

$SQL = $MySQLiconn->query("SELECT descripcionImpresion FROM impresiones  WHERE id=(SELECT producto FROM consumos WHERE IDConsumo=".$_GET['delfin'].")");
$get = $SQL->fetch_array();
$SQL =$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino un consumo del producto: ".$get['producto']."', 'Consumos', 'Productos',NOW())");

	$SQL = $MySQLiconn->query("DELETE from consumos where IDConsumo=".$_GET['delfin']);
	header("Location: Producto_Consumos.php?descripcionCons=".$_GET['descripcionCons']."");
	echo "<div class='alert alert-success alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Operación exitosa</strong> , Se eliminó correctamente.
		</div>
		";
	 
}
if(isset($_GET['b']))
{
      $producto = $_GET['b'];

      if(!empty($producto)) {
            getunidades($producto);
      }

      
}
if(isset($_GET['uploadc']))
{
	header("Location: cargarConsumos.php");
}
function getunidades($b) {
     include("../../Database/conexionphp.php");

     $sql = $mysqli->query("SELECT unidad FROM elementosconsumo WHERE identificadorElemento = '".$b."'");

     $contar = $sql->num_rows;
     $runi=$sql->fetch_array();
     if($contar >0){
      echo "<label for='cantConsumo'>Cantidad</label><input type='number' placeholder='".$runi['unidad']."' class='form-control' name='cantConsumo' id='cantConsumo'></input>";

}   
}?>
