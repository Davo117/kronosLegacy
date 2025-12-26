<?php

include_once 'db_Producto.php';

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post
	$nombre= $MySQLiconn->real_escape_string($_POST['nombreBanda']);
	$identificador =$MySQLiconn->real_escape_string($_POST['identificador']);   
	$anchura = $MySQLiconn->real_escape_string($_POST['anchura']);
	
	//Realizamos la consulta
	 $SQL =$MySQLiconn->query("INSERT INTO bandaSeguridad(nombreBanda,identificador,anchura) VALUES('$nombre','$identificador','$anchura')");
	 
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Agregado una nueva banda de seguridad')</script>";
	 }
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE bandaSeguridad SET baja=0 WHERE IDBanda=".$_GET['del']);
	header("Location: Producto_BandasSeguridad.php");
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Dado de Baja Exitosamente')</script>";
}
/* Fin Código Eliminación Logíca  */

/* Inicio Código Eliminación Definitiva 
if(isset($_GET['del']))
{ //Cambiar el parametro "del" para que no haya conflictos:
	$SQL = $MySQLiconn->query("DELETE FROM empleado WHERE id=".$_GET['del']);
	header("Location: index.php");
}
 Fin Código Eliminación Definitiva */



/* Inicio Código Atualizar  */
if(isset($_GET['edit']))
{
	$SQL = $MySQLiconn->query("SELECT * FROM bandaSeguridad WHERE IDBanda=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
}

if(isset($_POST['update']))
{
	$SQL = $MySQLiconn->query("UPDATE bandaSeguridad SET identificador='".$_POST['identificador']."', nombreBanda='".$_POST['nombreBanda']."', anchura=".$_POST['anchura']." WHERE IDBanda=".$_GET['edit']);

	header("Location: Producto_BandasSeguridad.php");
	 
}

if(isset($_GET['ban']))
{
	$_SESSION['descripcionBanda']= $_GET['ban'];
	header("Location: Producto_BandasSPP.php");
}
