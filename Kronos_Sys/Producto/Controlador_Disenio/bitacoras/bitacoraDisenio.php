<?php
include_once '../Database/db.php';

$usuarioF=$_SESSION['usuario'];

/* Inicio Código Insertar */
if(isset($_POST['save']))
{
$nombre = $MySQLiconn->real_escape_string($_POST['descripcion']);
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego el diseño:  $nombre','Diseño','Productos',NOW())");
}



/* Inicio Código Activar */
if(isset($_GET['acti']))
{
$SQL = $MySQLiconn->query("SELECT * FROM $disenio WHERE ID=".$_GET['acti']);
$get = $SQL->fetch_array();
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo el diseño: ".$get['descripcion']."','Diseño','Productos',NOW())");
}


/* Inicio Código Eliminación Logíca */
if(isset($_GET['del']))
{	
$SQL = $MySQLiconn->query("SELECT * FROM $disenio WHERE ID=".$_GET['del']);
$get = $SQL->fetch_array();
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo el diseño: ".$get['descripcion']."','Diseño','Productos',NOW())");
}



/* Inicio Código Eliminación Definitiva */
if(isset($_GET['delfin']))
{
$SQL = $MySQLiconn->query("SELECT * FROM $disenio WHERE ID=".$_GET['delfin']);
$get = $SQL->fetch_array();
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino el diseño: ".$get['descripcion']."','Diseño','Productos',NOW())");
}


if(isset($_POST['update']))
{

$SQL = $MySQLiconn->query("SELECT * FROM $disenio WHERE ID=".$_GET['edit']);

$getAll = $SQL->fetch_array();

$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo el diseño: ".$getAll['descripcion']."','Diseño','Productos',NOW())");

}
?>