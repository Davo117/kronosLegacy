<?php
include_once '../Database/db.php';

$usuarioF=$_SESSION['usuario'];

/*	En caso de hacer clic a un boton o imagen, se registrará en la bd y 
	se mostrará en el modulo de REPORTE
 Inicio Código Insertar */
if(isset($_POST['save'])){
	$nombre = $MySQLiconn->real_escape_string($_POST['Nombre']);
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego al contacto:  $nombre','Contacto Cliente','Logistica',NOW())");
}


/* Inicio Código Activar */
if(isset($_GET['activar'])){
	$SQL = $MySQLiconn->query("SELECT * FROM $tablaconcli WHERE idconcli=".$_GET['activar']);
	$get = $SQL->fetch_array();	
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo al contacto: ".$get['nombreconcli']."','Contacto Cliente','Logistica',NOW())");
}


/* Inicio Código Eliminación Logíca */
if(isset($_GET['del'])){	
	$SQL = $MySQLiconn->query("SELECT * FROM $tablaconcli WHERE idconcli=".$_GET['del']);
	$get = $SQL->fetch_array();	
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo al contacto: ".$get['nombreconcli']."','Contacto Cliente','Logistica',NOW())");
}


/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli'])){
	$SQL = $MySQLiconn->query("SELECT * FROM $tablaconcli WHERE idconcli=".$_GET['eli']);
	$get = $SQL->fetch_array();	
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino al contacto: ".$get['nombreconcli']."','Contacto Cliente','Logistica',NOW())");
}


if(isset($_POST['update'])){
	$SQL = $MySQLiconn->query("SELECT * FROM $tablaconcli WHERE idconcli=".$_GET['edit']);
	$getAll = $SQL->fetch_array();
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo al contacto: ".$getAll['nombreconcli']."','Contacto Cliente','Logistica',NOW())");
} ?>