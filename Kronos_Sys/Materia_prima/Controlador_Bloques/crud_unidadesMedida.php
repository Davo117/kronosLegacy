<?php

include_once 'db_materiaPrima.php';
$usuarioF=$_SESSION['usuario'];
/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save'])){
	//Pasamos los parametros por medio de post
	$identificadorUnidad = $MySQLiconn->real_escape_string($_POST['identificadorUnidad']);
	$nombreUnidad =$MySQLiconn->real_escape_string($_POST['nombreUnidad']);   
	
	//Realizamos la consulta
	 $SQL =$MySQLiconn->query("INSERT INTO unidades (identificadorUnidad,nombreUnidad) VALUES('$identificadorUnidad','$nombreUnidad')");
	 
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 $MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego el tipo de medición:  $identificadorUnidad','Unidades de Medida','Materia Prima',NOW())");

	 //Mandamos un mensaje de exito:
	 echo "<div class='alert alert-success alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Operación exitosa</strong> , agregado correctamente
		</div>";
	 }
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del'])){	//Lanzamos la consulta actualizando la baja a 0
$SQL = $MySQLiconn->query("SELECT identificadorUnidad FROM unidades WHERE idUnidad=".$_GET['del']);
$get = $SQL->fetch_array();
$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo el tipo de medición: ".$get['identificadorUnidad']."','Unidades de Medida','Materia Prima',NOW())");


	$SQL = $MySQLiconn->query("UPDATE unidades SET baja=0 WHERE idUnidad=".$_GET['del']);
	
	//Mandamos un mensaje de exito:
	echo "<div class='alert alert-success alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Operación exitosa</strong> , Se ha dado de baja
		</div>";
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
	$SQL = $MySQLiconn->query("SELECT * FROM unidades WHERE idUnidad=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
} 

if(isset($_POST['update']))
{
	$porPost=$_POST['identificadorUnidad'];

$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo el tipo de medición:  $porPost','Unidades de Medida','Materia Prima',NOW())");

	$SQL = $MySQLiconn->query("UPDATE unidades SET identificadorUnidad='".$_POST['identificadorUnidad']."', nombreUnidad='".$_POST['nombreUnidad']."' WHERE idUnidad=".$_GET['edit']);

	header("Location: MateriaPrima_UnidadesMedida.php");
	 
}
if(isset($_GET['acti']))
{
	$SQL = $MySQLiconn->query("SELECT identificadorUnidad FROM unidades WHERE idUnidad=".$_GET['acti']);
$get = $SQL->fetch_array();
$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo el tipo de medición: ".$get['identificadorUnidad']."','Unidades de Medida','Materia Prima',NOW())");

	$MySQLiconn->query("UPDATE unidades SET baja=1 WHERE idUnidad='".$_GET['acti']."'");
}

if(isset($_GET['delfin']))
{
$SQL = $MySQLiconn->query("SELECT identificadorUnidad FROM unidades WHERE idUnidad=".$_GET['delfin']);
$get = $SQL->fetch_array();
$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino el tipo de medición: ".$get['identificadorUnidad']."','Unidades de Medida','Materia Prima',NOW())");


	$MySQLiconn->query("DELETE FROM unidades WHERE idUnidad='".$_GET['delfin']."'");
	echo "<div class='alert alert-success alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Operación exitosa</strong> , Eliminado correctamente
		</div>";
}
/* Fin Código Atualizar */
