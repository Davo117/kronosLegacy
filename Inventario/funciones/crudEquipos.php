<?php
include_once 'Database/db.php';

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save'])){
	//Pasamos los parametros por medio de post Costo  
	$type = $MySQLiconn->real_escape_string($_POST['tipo']);
	$desc = $MySQLiconn->real_escape_string($_POST['Descripcion']);
	$name = $MySQLiconn->real_escape_string($_POST['Nombre']);
	$resp = $MySQLiconn->real_escape_string($_POST['Responsable']);
	$depa= $MySQLiconn->real_escape_string($_POST['Departamento']);
	$marca =$MySQLiconn->real_escape_string($_POST['Marca']);
	$model = $MySQLiconn->real_escape_string($_POST['Modelo']);
	$process= $MySQLiconn->real_escape_string($_POST['Procesador']);
	$ram =$MySQLiconn->real_escape_string($_POST['MemoriaRAM']);
	$dd = $MySQLiconn->real_escape_string($_POST['DiscoDuro']);
	$cost= $MySQLiconn->real_escape_string($_POST['Costo']);
	$fac =$MySQLiconn->real_escape_string($_POST['Factura']);
	$fecha = $MySQLiconn->real_escape_string($_POST['fch_ingreso']);

		//Realizamos la consulta
		$SQL =$MySQLiconn->query("INSERT INTO equipos(tipo, descripcion, nombre, responsable, departamento, marca, modelo, procesador, memoriaRAM,capacidadHDD,costoEquipo, factura, fchEntrada) VALUES('$type','$desc','$name','$resp','$depa','$marca','$model','$process','$ram','$dd','$cost','$fac','$fecha')");

		//En caso de ser diferente la consulta:
	 	if(!$SQL){
			//Mandar el mensaje de error
			echo $MySQLiconn->error;
	 	}
	 	else header("Location: Equipos.php");
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Activar */
//Si se dio clic en Activar:
if(isset($_GET['activar'])){	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE equipos SET baja=1 WHERE id_eq=".$_GET['activar']);
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Equipos.php");
}
/* Fin Código Activar */
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del'])){
	$SQL = $MySQLiconn->query("UPDATE equipos SET baja=0 WHERE id_eq=".$_GET['del']);
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Equipos.php");
}
/* Fin Código Eliminación Logíca  */
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli'])){ //Cambiar el parametro "del" para que no haya conflictos:
	$SQL = $MySQLiconn->query("DELETE FROM  equipos where id_eq=".$_GET['eli']);
	//En caso de ser diferente la consulta:
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}else header("Location: Equipos.php");
}
 /* Fin Código Eliminación Definitiva */
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Atualizar  */
if(isset($_GET['edit'])){
	$SQL = $MySQLiconn->query("SELECT * FROM equipos WHERE id_eq=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
}

if(isset($_POST['update'])){
	$SQL = $MySQLiconn->query("UPDATE equipos SET tipo='".$_POST['tipo']."', descripcion='".$_POST['Descripcion']."', nombre='".$_POST['Nombre']."', responsable='".$_POST['Responsable']."', departamento='".$_POST['Departamento']."', marca='".$_POST['Marca']."', modelo='".$_POST['Modelo']."', procesador='".$_POST['Procesador']."', memoriaRAM='".$_POST['MemoriaRAM']."', capacidadHDD='".$_POST['DiscoDuro']."', costoEquipo='".$_POST['Costo']."', factura='".$_POST['Factura']."', fchEntrada='".$_POST['fch_ingreso']."' WHERE id_eq=".$_GET['edit']);
	if(!$SQL){
		 //Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Equipos.php");
}
/* Fin Código Atualizar */
?>