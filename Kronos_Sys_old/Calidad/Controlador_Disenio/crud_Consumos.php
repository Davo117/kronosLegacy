<?php

include_once 'db_Producto.php';

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post
	$subproceso = $MySQLiconn->real_escape_string($_POST['ComboProcesos']);
	$elemento =$MySQLiconn->real_escape_string($_POST['comboElementos']);   
	$consumo =$MySQLiconn->real_escape_string($_POST['cantConsumo']);  
	$producto=$MySQLiconn->real_escape_string($_POST['disenios']);
	//Realizamos la consulta
	 $SQL =$MySQLiconn->query("INSERT INTO consumos(subProceso,elemento,consumo,producto) VALUES('$subproceso','$elemento','$consumo','$producto')");
	 
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Agregado un nuevo consumo')</script>";
	 }
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE consumos SET baja=0 WHERE IDConsumo=".$_GET['del']);
	header("Location: Producto_Consumos.php");
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
	$SQL = $MySQLiconn->query("SELECT * FROM consumos WHERE IDConsumo=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
} 

if(isset($_POST['update']))
{
	$SQL = $MySQLiconn->query("UPDATE consumos SET subProceso='".$_POST['ComboProcesos']."', elemento='".$_POST['comboElementos']."',consumo='".$_POST['cantConsumo']."' WHERE IDConsumo=".$_GET['edit']);

	header("Location: Producto_Consumos.php");
	 
}

/* Fin Código Atualizar */
