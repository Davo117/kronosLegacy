<?php
include_once '../Database/db.php';

$usuarioF=$_SESSION['usuario'];

/* Inicio Código Insertar */
if(isset($_POST['save']))
{
$nombre = $MySQLiconn->real_escape_string($_POST['Codigo']);
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego la máquina:  $nombre','Máquinas','Producción',NOW())");
}


/* Inicio Código Activar */
if(isset($_GET['activar']))
{
$SQL = $MySQLiconn->query("SELECT * FROM $maquina WHERE idMaq=".$_GET['activar']);
$get = $SQL->fetch_array();
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo la máquina: ".$get['codigo']."','Máquinas','Producción',NOW())");
}


/* Inicio Código Eliminación Logíca */
if(isset($_GET['del']))
{	
$SQL = $MySQLiconn->query("SELECT * FROM $maquina WHERE idMaq=".$_GET['del']);
$get = $SQL->fetch_array();
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo la máquina: ".$get['codigo']."','Máquinas','Producción',NOW())");
}



/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli']))
{
$SQL = $MySQLiconn->query("SELECT * FROM $maquina WHERE idMaq=".$_GET['eli']);
$get = $SQL->fetch_array();
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino la máquina: ".$get['codigo']."','Máquinas','Producción',NOW())");
}


if(isset($_POST['update']))
{
$SQL = $MySQLiconn->query("SELECT * FROM $maquina WHERE idMaq=".$_GET['edit']);
$getAll = $SQL->fetch_array();
$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo la máquina: ".$getAll['codigo']."','Máquinas','Producción',NOW())");

}

?>