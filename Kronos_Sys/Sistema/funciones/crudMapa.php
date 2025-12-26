<?php

include_once '../Database/db.php';

//Estado
/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['guardar'])){
	$estado=$MySQLiconn->real_escape_string($_POST['estado']);
	$alias=$MySQLiconn->real_escape_string($_POST['alias']);
	$pais=$MySQLiconn->real_escape_string($_POST['pais']);
	
	$SQL=$MySQLiconn->query("INSERT into estado(nombreEstado, abreviatura, pais) VALUES('$estado','$alias','$pais')");
	if(!$SQL){	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
}

if(isset($_GET['actiE'])){
	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE estado SET baja='1' WHERE id=".$_GET['actiE']);
	if(!$SQL){
		//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Estado.php");
}

if(isset($_GET['desE'])){
	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE estado SET baja='0' WHERE id=".$_GET['desE']);
	if(!$SQL){
		//Mandar el mensaje de error
		echo $MySQLiconn->error;
	} 
	else header("Location: Estado.php");
}

if(isset($_GET['ciudad'])){
	$_SESSION['estadoID']= $_GET['ciudad'];
	header("Location: Ciudad.php");
}



//Ciudad

if(isset($_POST['saveCity'])){
	$estado = $MySQLiconn->real_escape_string($_POST['example']);
	$cd = $MySQLiconn->real_escape_string($_POST['cd']);
	
	$SQL=$MySQLiconn->query("INSERT into ciudad(nombreCity, estado) VALUES('$cd','$estado')");
	if(!$SQL){	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
}

if(isset($_GET['actiC'])){
	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE ciudad SET baja='1' WHERE id=".$_GET['actiC']);
	if(!$SQL){
		//Mandar el mensaje de error
		echo $MySQLiconn->error;
	} 
	else header("Location: Ciudad.php");
}

if(isset($_GET['desC'])){
	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE ciudad SET baja='0' WHERE id=".$_GET['desC']);
	if(!$SQL){
		//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Ciudad.php");
} ?>