<?php
include_once '../Database/db.php';

$usuarioF=$_SESSION['usuario'];


/*	En caso de hacer clic a un boton o imagen, se registrará en la bd y 
	se mostrará en el modulo de REPORTE

Inicio Código Insertar */
if(isset($_POST['save'])){
	$nombre = $MySQLiconn->real_escape_string($_POST['Nombre']);
 	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego al contacto:  $nombre','Contacto Sucursal','Logistica',NOW())");
}


/* Inicio Código Activar */
if(isset($_GET['activar'])){
	$SQL = $MySQLiconn->query("SELECT * FROM $tablaconsuc WHERE idconsuc=".$_GET['activar']);
	$get = $SQL->fetch_array();
 	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo al contacto: ".$get['nombreconsuc']."','Contacto Sucursal','Logistica',NOW())");
}


/* Inicio Código Eliminación Logíca */
if(isset($_GET['del'])){	
	$SQL = $MySQLiconn->query("SELECT * FROM $tablaconsuc WHERE idconsuc=".$_GET['del']);
	$get = $SQL->fetch_array();
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo al contacto: ".$get['nombreconsuc']."','Contacto Sucursal','Logistica',NOW())");
}


/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli'])){
	$SQL = $MySQLiconn->query("SELECT * FROM $tablaconsuc WHERE idconsuc=".$_GET['eli']);
	$get = $SQL->fetch_array();
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino al contacto: ".$get['nombreconsuc']."','Contacto Sucursal','Logistica',NOW())");
}


if(isset($_POST['update'])){
	$SQL = $MySQLiconn->query("SELECT * FROM $tablaconsuc WHERE idconsuc=".$_GET['edit']);
	$getAll = $SQL->fetch_array();

	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo al contacto: ".$getAll['nombreconsuc']."','Contacto Sucursal','Logistica',NOW())");
}
?>