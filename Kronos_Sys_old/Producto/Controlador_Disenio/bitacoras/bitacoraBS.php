<?php
include_once '../Database/db.php';

$usuarioF=$_SESSION['usuario'];

/* Inicio Código Insertar */
if(isset($_POST['save']))
{
$nombre = $MySQLiconn->real_escape_string($_POST['nombreBanda']);
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego la Banda de Seguridad:  $nombre','Banda de Seguridad','Productos',NOW())");
}



/* Inicio Código Activar */
if(isset($_GET['acti']))
{
$SQL = $MySQLiconn->query("SELECT * FROM $bandaS WHERE IDBanda=".$_GET['acti']);
$get = $SQL->fetch_array();
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo la Banda de Seguridad: ".$get['nombreBanda']."','Banda de Seguridad','Productos',NOW())");
}


/* Inicio Código Eliminación Logíca */
if(isset($_GET['del']))
{	
$SQL = $MySQLiconn->query("SELECT * FROM $bandaS WHERE IDBanda=".$_GET['del']);
$get = $SQL->fetch_array();
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo la Banda de Seguridad: ".$get['nombreBanda']."','Banda de Seguridad','Productos',NOW())");
}



/* Inicio Código Eliminación Definitiva */
if(isset($_GET['delfin']))
{
$SQL = $MySQLiconn->query("SELECT * FROM $bandaS WHERE IDBanda=".$_GET['delfin']);
$get = $SQL->fetch_array();
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino la Banda de Seguridad: ".$get['nombreBanda']."','Banda de Seguridad','Productos',NOW())");
}


if(isset($_POST['update']))
{

$SQL = $MySQLiconn->query("SELECT * FROM $bandaS WHERE IDBanda=".$_GET['edit']);

$getAll = $SQL->fetch_array();

$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo la Banda de Seguridad: ".$getAll['nombreBanda']."','Banda de Seguridad','Productos',NOW())");

}
?>
