<?php
include_once '../Database/db.php';

$usuarioF=$_SESSION['usuario'];

/* Inicio Código Insertar */



/* Inicio Código Activar 
if(isset($_GET['acti']))
{
$SQL = $MySQLiconn->query("SELECT * FROM $impresion WHERE id=".$_GET['acti']);
$get = $SQL->fetch_array();
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo la impresion(código: ".$get['codigoImpresion']."','Impresiones','Productos',NOW())");
}


/* Inicio Código Eliminación Logíca 
if(isset($_GET['del']))
{	
$SQL = $MySQLiconn->query("SELECT * FROM $impresion WHERE id=".$_GET['del']);
$get = $SQL->fetch_array();
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo la impresion(código: ".$get['codigoImpresion']."','Impresiones','Productos',NOW())");
}



/* Inicio Código Eliminación Definitiva 
if(isset($_GET['delfin']))
{
$SQL = $MySQLiconn->query("SELECT * FROM $impresion WHERE id=".$_GET['delfin']);
$get = $SQL->fetch_array();
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino la impresion(código: ".$get['codigoImpresion']."','Impresiones','Productos',NOW())");
}


if(isset($_POST['update']))
{

$SQL = $MySQLiconn->query("SELECT * FROM $impresion WHERE id=".$_GET['edit']);

$getAll = $SQL->fetch_array();

$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo la impresion(código: ".$getAll['codigoImpresion']."','Impresiones','Productos',NOW())");

}*/
?>