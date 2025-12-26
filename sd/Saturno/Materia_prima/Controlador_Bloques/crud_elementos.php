<?php

include_once 'db_materiaPrima.php';

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post
	$identificadorElemento=$MySQLiconn->real_escape_string($_POST['identificadorElemento']);
	$nombreElemento= $MySQLiconn->real_escape_string($_POST['nombreElemento']);  
	$unidad = $MySQLiconn->real_escape_string($_POST['comboUnidades']);
	
	//Realizamos la consulta
	 $SQL =$MySQLiconn->query("INSERT INTO elementosConsumo(identificadorElemento,nombreElemento,unidad) VALUES('$identificadorElemento','$nombreElemento','$unidad')");
	 
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Agregado un nuevo elemento ')</script>";
	 }
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE elementosConsumo	SET baja=0 WHERE idElemento=".$_GET['del']);
	header("Location: MateriaPrima_Elementos.php");
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
	echo $_GET['edit'];
	$SQL = $MySQLiconn->query("SELECT * FROM elementosConsumo WHERE idElemento=".$_GET['edit']);

	//select*from bandaspp where idBSPP=1
	$getROW = $SQL->fetch_array();
}

if(isset($_POST['update']))
{
	$SQL = $MySQLiconn->query("UPDATE elementosConsumo SET identificadorElemento='".$_POST['identificadorElemento']."', nombreElemento='".$_POST['nombreElemento']."', unidad='".$_POST['comboUnidades']."' WHERE idElemento=".$_GET['edit']);

	header("Location: MateriaPrima_Elementos.php");
	 //Sino modifica el error esta en la sintaxis
}
