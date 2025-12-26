<?php

include_once 'db_materiaPrima.php';

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post
	$codigoSustrato = $MySQLiconn->real_escape_string($_POST['codigoSustrato']);
	$descripcionSustrato =$MySQLiconn->real_escape_string($_POST['descripcionSustrato']);   
	$rendimiento = $MySQLiconn->real_escape_string($_POST['rendimiento']);
	$anchura = $MySQLiconn->real_escape_string($_POST['anchura']);
	
	//Realizamos la consulta
	 $SQL =$MySQLiconn->query("INSERT INTO sustrato(codigoSustrato,descripcionSustrato, rendimiento,anchura) VALUES('$codigoSustrato','$descripcionSustrato','$anchura','$rendimiento')");
	 
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Agregado un nuevo sustrato')</script>";
	 }
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE sustrato	SET baja=0 WHERE idSustrato=".$_GET['del']);
	header("Location: MateriaPrima_Sustrato.php");
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
	$SQL = $MySQLiconn->query("SELECT * FROM sustrato WHERE idSustrato=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
}

if(isset($_POST['update']))
{
	$SQL = $MySQLiconn->query("UPDATE sustrato SET codigoSustrato='".$_POST['codigoSustrato']."', descripcionSustrato='".$_POST['descripcionSustrato']."', anchura='".$_POST['anchura']."', rendimiento='".$_POST['rendimiento']."' WHERE idSustrato=".$_GET['edit']);

	header("Location: MateriaPrima_Sustrato.php");
	 
}
