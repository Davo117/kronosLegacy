<?php

include_once 'db_materiaPrima.php';

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post
	$identificadorUnidad = $MySQLiconn->real_escape_string($_POST['identificadorUnidad']);
	$nombreUnidad =$MySQLiconn->real_escape_string($_POST['nombreUnidad']);   
	
	//Realizamos la consulta
	 $SQL =$MySQLiconn->query("INSERT INTO unidades (identificadorUnidad,nombreUnidad) VALUES('$identificadorUnidad','$nombreUnidad')");
	 
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Agregado una nueva unidad')</script>";
	 }
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE unidades SET baja=0 WHERE idUnidad=".$_GET['del']);
	header("Location: MateriaPrima_UnidadesMedida.php");
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
	$SQL = $MySQLiconn->query("SELECT * FROM unidades WHERE idUnidad=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
} 

if(isset($_POST['update']))
{
	$SQL = $MySQLiconn->query("UPDATE unidades SET identificadorUnidad='".$_POST['identificadorUnidad']."', nombreUnidad='".$_POST['nombreUnidad']."' WHERE idUnidad=".$_GET['edit']);

	header("Location: MateriaPrima_UnidadesMedida.php");
	 
}

/* Fin Código Atualizar */
