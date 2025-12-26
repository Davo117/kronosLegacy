<?php

include_once 'db_materiaPrima.php';

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post
	$codigoPantone= $MySQLiconn->real_escape_string($_POST['codigoPantone']);
	$descripcionPantone = $MySQLiconn->real_escape_string($_POST['descripcionPantone']);
	$codigoHTML=$MySQLiconn->real_escape_string($_POST['codigoHTML']);

	 $SQL =$MySQLiconn->query("INSERT INTO pantone (descripcionPantone,codigoPantone,codigoHTML) VALUES('$descripcionPantone','$codigoPantone','$codigoHTML')");
	 
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Agregado un nuevo Pantone')</script>";
	 }
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE pantone	SET baja=0 WHERE idPantone=".$_GET['del']);
	header("Location: MateriaPrima_Pantone.php");
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
	$SQL = $MySQLiconn->query("SELECT * FROM pantone WHERE idPantone=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
}

if(isset($_POST['update']))
{
	$SQL = $MySQLiconn->query("UPDATE pantone SET descripcionPantone='".$_POST['descripcionPantone']."', codigoPantone='".$_POST['codigoPantone']."',codigoHTML='".$_POST['codigoHTML']."' WHERE idPantone=".$_GET['edit']);

	header("Location: MateriaPrima_Pantone.php");
	//Me quede aqui,estaba agregando los parametros para poder modificar las impresiones
	 
}
if(isset($_POST['panto']))
{
 echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=https://www.pantone.com/color-finder/".$_SESSION['panto']."";
}