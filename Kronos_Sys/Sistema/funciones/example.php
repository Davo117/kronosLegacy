<?php
include_once '../Database/db.php';

$usuarioF=$_SESSION['usuario'];

/* Inicio Código Insertar */
if(isset($_POST['save']))
{
$numemp = $MySQLiconn->real_escape_string($_POST['NumEmp']);
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego al empleado Num:  $numemp','Personal','Recursos Humanos',NOW())");
}


/* Inicio Código Activar */
if(isset($_GET['activar']))
{
 $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo ','Personal','Recursos Humanos',NOW())");
}


/* Inicio Código Eliminación Logíca */
if(isset($_GET['del'])){	
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo','Personal','Recursos Humanos',NOW())");
}



/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli'])){
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino','Personal','Recursos Humanos',NOW())");
}




if(isset($_POST['update']))
{
$SQL = $MySQLiconn->query("SELECT * FROM empleado WHERE id=".$_GET['edit']);
$get = $SQL->fetch_array();

$_SESSION['holi']= $get['numemp'];


$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo al empleado Num: ".$get['numemp'] ." por".$_POST['NumEmp']."','Personal','Recursos Humanos',NOW())");

}

?>