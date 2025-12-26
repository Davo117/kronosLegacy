<?php
include_once '../Database/db.php';

$usuarioF=$_SESSION['usuario'];


/*	En caso de hacer clic a un boton o imagen, se registrará en la bd y 
	se mostrará en el modulo de REPORTE


	 Inicio Código Insertar */
if(isset($_POST['save']))
{
$nombre = $MySQLiconn->real_escape_string($_POST['ordenC']);
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego la orden:  $nombre','Orden Compra','Logistica',NOW())");
}


/* Inicio Código Activar */
if(isset($_GET['activar']))
{
$SQL = $MySQLiconn->query("SELECT * FROM $tablaOrden WHERE idorden=".$_GET['activar']);
$get = $SQL->fetch_array();
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo la orden: ".$get['orden']."','Orden Compra','Logistica',NOW())");
}


/* Inicio Código Eliminación Logíca */
if(isset($_GET['del']))
{	
$SQL = $MySQLiconn->query("SELECT * FROM $tablaOrden WHERE idorden=".$_GET['del']);
$get = $SQL->fetch_array();
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo la orden: ".$get['orden']."','Orden Compra','Logistica',NOW())");
}



/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli']))
{
$SQL = $MySQLiconn->query("SELECT * FROM $tablaOrden WHERE idorden=".$_GET['eli']);
$get = $SQL->fetch_array();
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino la orden: ".$get['orden']."','Orden Compra','Logistica',NOW())");
}


if(isset($_POST['update']))
{

$SQL = $MySQLiconn->query("SELECT * FROM $tablaOrden WHERE idorden=".$_GET['edit']);

$getAll = $SQL->fetch_array();

$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo la orden: ".$getAll['orden']."','Orden Compra','Logistica',NOW())");


}

?>