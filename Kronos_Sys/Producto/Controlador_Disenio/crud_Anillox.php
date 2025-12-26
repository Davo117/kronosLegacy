<?php

include_once 'db_Producto.php';
$usuarioF=$_SESSION['usuario'];
/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save'])){
	//Pasamos los parametros por medio de post
	$id = $MySQLiconn->real_escape_string($_POST['identificador']);
	$linea =$MySQLiconn->real_escape_string($_POST['lineas']);
	$bcm = $MySQLiconn->real_escape_string($_POST['bcm']);
	$process = $MySQLiconn->real_escape_string($_POST['proceso']);
	
	//Realizamos la consulta
	$MySQLiconn->query("INSERT INTO anillox(identificadorAnillox,num_lineas,bcm, proceso) VALUES('$id','$linea','$bcm','$process')");
$SQL =$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego el anillox:  $id','Anillox','Productos',NOW())");

	//En caso de ser diferente la consulta:
 	if(!$SQL){
		//Mandar el mensaje de error
		echo $MySQLiconn->error;
 	} else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Agregado un Nuevo Anillox')</script>";
	 }
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Activar */
//Si se dio clic en Activar:
if(isset($_GET['acti'])){	//Lanzamos la consulta actualizando la baja a 0

	$SQL=$MySQLiconn->query("SELECT identificadorAnillox FROM anillox  WHERE id=".$_GET['acti']);
	$get = $SQL->fetch_array();
 	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo el anillox: ".$get['identificadorAnillox']."','Anillox','Productos',NOW())");

	$SQL = $MySQLiconn->query("UPDATE anillox SET baja=1 WHERE id=".$_GET['acti']);
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Producto_Anillox.php");
}
/* Fin Código Activar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del'])){
	//Lanzamos la consulta actualizando la baja a 0
$SQL = $MySQLiconn->query("SELECT identificadorAnillox FROM anillox WHERE id=".$_GET['del']);
$get = $SQL->fetch_array();
$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo el anillox: ".$get['identificadorAnillox']."','Anillox','Productos',NOW())");

	$SQL = $MySQLiconn->query("UPDATE anillox SET baja=0 WHERE id=".$_GET['del']);
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Producto_Anillox.php");
}
/* Fin Código Eliminación Logíca  */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli'])){ //Cambiar el parametro "del" para que no haya conflictos:
$SQL = $MySQLiconn->query("SELECT identificadorAnillox FROM anillox WHERE id=".$_GET['eli']);
$get = $SQL->fetch_array();
$SQL =$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino el anillox: ".$get['identificadorAnillox']."','Anillox','Productos',NOW())");


	$SQL = $MySQLiconn->query("DELETE FROM anillox where id=".$_GET['eli']);
	//En caso de ser diferente la consulta:
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
}
 /* Fin Código Eliminación Definitiva */
//////////////////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Atualizar  */
if(isset($_GET['edit'])){
	$SQL = $MySQLiconn->query("SELECT * FROM anillox WHERE id=".$_GET['edit']);
	if(!$SQL){
	 		//Mandar el mensaje de error
			echo $MySQLiconn->error;
		} else	$getROW = $SQL->fetch_array();
}

if(isset($_POST['update'])){
$porPOST=$_POST['identificador'];

$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo el anillox:  $porPOST','Anillox','Productos',NOW())");


	//Si tiene datos en sucursales o contactos, los desactiva tambien			
	$SQL = $MySQLiconn->query("UPDATE anillox SET identificadorAnillox='".$_POST['identificador']."', num_lineas='".$_POST['lineas']."',  bcm='".$_POST['bcm']."', proceso='".$_POST['proceso']."' WHERE id=".$_GET['edit']); 
		
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Producto_Anillox.php");
}

/* Fin Código Atualizar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
?>