<?php
include_once '../Database/db.php';

$usuarioF=$_SESSION['usuario'];





/*	En caso de hacer clic a un boton o imagen, se registrará en la bd y 
	se mostrará en el modulo de REPORTE

	Inicio Código Insertar */
if(isset($_POST['save']))
{
$nombre = $MySQLiconn->real_escape_string($_POST['Name']);
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego a la sucursal:  $nombre','Sucursal','Logistica',NOW())");
}


/* Inicio Código Activar */
if(isset($_GET['activar']))
{
$SQL = $MySQLiconn->query("SELECT * FROM $tablasucursal WHERE idsuc=".$_GET['activar']);
$get = $SQL->fetch_array();
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo a la sucursal: ".$get['nombresuc']."','Sucursal','Logistica',NOW())");
}


/* Inicio Código Eliminación Logíca */
if(isset($_GET['del']))
{	
$SQL = $MySQLiconn->query("SELECT * FROM $tablasucursal WHERE idsuc=".$_GET['del']);
$get = $SQL->fetch_array();
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo a la sucursal: ".$get['nombresuc']."','Sucursal','Logistica',NOW())");
}



/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli']))
{
$SQL = $MySQLiconn->query("SELECT * FROM $tablasucursal WHERE idsuc=".$_GET['eli']);
$get = $SQL->fetch_array();
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino a la sucursal: ".$get['nombresuc']."','Sucursal','Logistica',NOW())");
}


if(isset($_POST['update']))
{

$SQL = $MySQLiconn->query("SELECT * FROM $tablasucursal WHERE idsuc=".$_GET['edit']);

$getAll = $SQL->fetch_array();

$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo a la sucursal: ".$getAll['nombresuc']."','Sucursal','Logistica',NOW())");


}

?>