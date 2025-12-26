<?php
include_once '../Database/db.php';

$usuarioF=$_SESSION['usuario'];

/*	En caso de hacer clic a un boton o imagen, se registrará en la bd y 
	se mostrará en el modulo de REPORTE

 Inicio Código Insertar */
if(isset($_POST['save'])){	
	$rfc = $MySQLiconn->real_escape_string($_POST['RFC']);
 	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego al cliente con RFC:  $rfc','Clientes','Logistica',NOW())");
}


/* Inicio Código Activar */
if(isset($_GET['activar'])){

	$SQL = $MySQLiconn->query("SELECT * FROM $tablacli WHERE ID=".$_GET['activar']);
	$get = $SQL->fetch_array();
 	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo al cliente con RFC: ".$get['rfccli']."','Clientes','Logistica',NOW())");
}


/* Inicio Código Eliminación Logíca */
if(isset($_GET['del']))	{	
	$SQL = $MySQLiconn->query("SELECT * FROM $tablacli WHERE ID=".$_GET['del']);
	$get = $SQL->fetch_array();
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo al cliente con RFC: ".$get['rfccli']."','Clientes','Logistica',NOW())");
}


/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli'])){
	$SQL = $MySQLiconn->query("SELECT * FROM $tablacli WHERE ID=".$_GET['eli']);
	$get = $SQL->fetch_array();
 	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino al cliente con RFC: ".$get['rfccli']."','Clientes','Logistica',NOW())");
}


if(isset($_POST['update'])){
	$SQLion = $MySQLiconn->query("SELECT * FROM $tablacli WHERE ID=".$_GET['edit']);
	$SQL = $MySQLiconn->query("SELECT * FROM $tablacli WHERE ID=".$_GET['edit']);
	$getAll = $SQLion->fetch_array();
/*
while ($get = $SQL->fetch_array()) {
	if ($get['rfccli']!= $_POST['NumEmp'] ) {
		$antiguo=$get["rfccli"];
		$campo='Numero de Empleado';
		$postear=$_POST['NumEmp'];
	}
	
	if ($get['Nombre']!= $_POST['Nombre'] ) {
		$antiguo=$get["Nombre"];
		$campo='Nombre';
		$postear= $_POST['Nombre'];
	}
	
	if ($get['apellido']!= $_POST['Apellido'] ) {
		$antiguo=$get["apellido"];
		$campo='Apellido';
		$postear= $_POST['Apellido'];
	}

	if ($get['Telefono']!= $_POST['Telefono'] ) {
		$antiguo=$get["Telefono"];
		$campo='Telefono';
		$postear= $_POST['Telefono'];
	}

	if ($get['departamento']!= $_POST['Depa'] ) {
		$antiguo=$get["departamento"];
		$campo='Departamento';
		$postear= $_POST['Depa'];
	}
	}

	//Restricciones: no se podran guardar mas de un cambio en la bitacora.
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo al cliente con RFC: ".$getAll['rfccli']." el campo ".$campo." de ".$antiguo." a ".$postear."','Clientes','Logistica',NOW())");
	*/

	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo al cliente con RFC: ".$getAll['rfccli']."','Clientes','Logistica',NOW())");
} ?>