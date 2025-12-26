<?php

include_once '../Database/db.php';


/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save'])){
	//Pasamos los parametros por medio de post
	$cod = $MySQLiconn->real_escape_string($_POST['Codigo']);
	$desc =$MySQLiconn->real_escape_string($_POST['Descripcion']);   
	$sub = $MySQLiconn->real_escape_string($_POST['Subproceso']);

	//Realizamos la consulta
	$SQL =$MySQLiconn->query("INSERT INTO $maquina(codigo, descripcionMaq, subproceso) VALUES('$cod','$desc','$sub')");
	 	
	//En caso de ser diferente la consulta:
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	echo "<script>window.location='Produccion_Maquinas.php';</script>";
}

/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Activar */
//Si se dio clic en Activar:
if(isset($_GET['activar'])){
	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE $maquina	SET baja=1 WHERE idMaq=".$_GET['activar']);
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Produccion_Maquinas.php");
}
/* Fin Código Activar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del'])){	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE $maquina	SET baja=0 WHERE idMaq=".$_GET['del']);
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	} else header("Location: Produccion_Maquinas.php");
}
/* Fin Código Eliminación Logíca  */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli'])){ //Cambiar el parametro "del" para que no haya conflictos:
	$SQL = $MySQLiconn->query("DELETE FROM  $maquina where idMaq=".$_GET['eli']);
	//En caso de ser diferente la consulta:
	 if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	} else header("Location: Produccion_Maquinas.php");
}
 /* Fin Código Eliminación Definitiva */
//////////////////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Atualizar  */
if(isset($_GET['edit'])){
	$SQL = $MySQLiconn->query("SELECT * FROM $maquina WHERE idMaq=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
}

if(isset($_POST['update'])){	
	$SQL = $MySQLiconn->query("UPDATE $maquina SET codigo='".$_POST['Codigo']."', descripcionMaq='".$_POST['Descripcion']."', subproceso='".$_POST['Subproceso']."',tipoproducto='".$_POST['tipocombo']."' WHERE idMaq=".$_GET['edit']);
	if(!$SQL) {
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	} else header("Location: Produccion_Maquinas.php");	 
}
/* Fin Código Atualizar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
?>