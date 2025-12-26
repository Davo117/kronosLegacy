<?php
include_once '../Database/db.php';

$usuarioF=$_SESSION['usuario'];

/* Inicio Código Insertar */
if(isset($_POST['save']))
{
$nombre = $MySQLiconn->real_escape_string($_POST['identificadorCilindro']);
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego el juego de cilindro:  $nombre','Juegos Cilindro','Productos',NOW())");
}



/* Inicio Código Activar */
if(isset($_GET['acti']))
{
$SQL = $MySQLiconn->query("SELECT * FROM $cilindro WHERE IDCilindro=".$_GET['acti']);
$get = $SQL->fetch_array();
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo el juego de cilindro: ".$get['identificadorCilindro']."','Juegos Cilindro','Productos',NOW())");
}


/* Inicio Código Eliminación Logíca */
if(isset($_GET['del']))
{	
$SQL = $MySQLiconn->query("SELECT * FROM $cilindro WHERE IDCilindro=".$_GET['del']);
$get = $SQL->fetch_array();
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo el juego de cilindro: ".$get['identificadorCilindro']."','Juegos Cilindro','Productos',NOW())");
}



/* Inicio Código Eliminación Definitiva */
if(isset($_GET['delfin']))
{
$SQL = $MySQLiconn->query("SELECT * FROM $cilindro WHERE IDCilindro=".$_GET['delfin']);
$get = $SQL->fetch_array();
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino el juego de cilindro: ".$get['identificadorCilindro']."','Juegos Cilindro','Productos',NOW())");
}


if(isset($_POST['update']))
{

$SQL = $MySQLiconn->query("SELECT * FROM $cilindro WHERE IDCilindro=".$_GET['edit']);

$getAll = $SQL->fetch_array();

$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo el juego de cilindro: ".$getAll['identificadorCilindro']."','Juegos Cilindro','Productos',NOW())");

}
?>