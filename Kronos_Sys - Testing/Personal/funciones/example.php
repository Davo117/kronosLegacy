<?php
include_once '../Database/db.php';

$usuarioF=$_SESSION['usuario'];

/* Inicio Código Insertar */
if(isset($_POST['save'])){
	$numemp = $MySQLiconn->real_escape_string($_POST['NumEmp']);
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego al empleado Num:  $numemp','Personal','Recursos Humanos',NOW())");
}

/* Inicio Código Insertar  Modulo DEPARTAMENTO*/
if(isset($_POST['guardar'])){
	$depa = $MySQLiconn->real_escape_string($_POST['depa']);
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego el departamento:  $depa','Departamento','Recursos Humanos',NOW())");
}



/* Inicio Código Activar */
if(isset($_GET['activar'])){
	$SQL = $MySQLiconn->query("SELECT * FROM empleado WHERE id=".$_GET['activar']);
	$get = $SQL->fetch_array();
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo al empleado Num: ".$get['numemple']."','Personal','Recursos Humanos',NOW())");
}


/* Inicio Código Eliminación Logíca */
if(isset($_GET['del'])){
	$SQL = $MySQLiconn->query("SELECT * FROM empleado WHERE id=".$_GET['del']);
	$get = $SQL->fetch_array();
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo al empleado Num: ".$get['numemple']."','Personal','Recursos Humanos',NOW())");
}



/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli'])){
	$SQL = $MySQLiconn->query("SELECT * FROM empleado WHERE id=".$_GET['eli']);
	$get = $SQL->fetch_array();
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino al empleado Num: ".$get['numemple']."','Personal','Recursos Humanos',NOW())");
}



/* Inicio Código Eliminación Definitiva  del modulo DEPARTAMENTO*/
if(isset($_GET['eliminarDepa'])){
	$SQL = $MySQLiconn->query("SELECT nombre FROM departamento WHERE id=".$_GET['eliminarDepa']);
	$get = $SQL->fetch_array();
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino el departamento: ".$get['nombre']."','Departamento','Recursos Humanos',NOW())");
}


if(isset($_POST['update'])){
	$SQLion = $MySQLiconn->query("SELECT * FROM empleado WHERE id=".$_GET['edit']);
	$SQL = $MySQLiconn->query("SELECT * FROM empleado WHERE id=".$_GET['edit']);

	$getAll = $SQLion->fetch_array();
/*
	while ($get = $SQL->fetch_array()) {
		if ($get['numemple']!= $_POST['NumEmp'] ) {
			$antiguo=$get["numemple"];
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
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo al empleado Num: ".$getAll['numemple']." el campo ".$campo." de ".$antiguo." a ".$postear."','Personal','Recursos Humanos',NOW())");
*/
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo al empleado Num: ".$getAll['numemple']."','Personal','Recursos Humanos',NOW())");
}	?>