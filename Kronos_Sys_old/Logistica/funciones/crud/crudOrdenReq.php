<?php
include("../Database/db.php");

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save'])){
	//Pasamos los parametros por medio de post
	$idfk =$MySQLiconn->real_escape_string($_POST['example1']);   
	$idfk2 =$MySQLiconn->real_escape_string($_POST['producto']);   
	$cant = $MySQLiconn->real_escape_string($_POST['cantidad']);
	$refe = $MySQLiconn->real_escape_string($_POST['referencia']);

	if ($cant==""){
		echo "<script>alert('No se registro el Requerimiento.')</script>";
	}
	else{
	
		$SQL =$MySQLiconn->query("INSERT INTO $reqProd(cantReq, refeReq, ordenReqFK, prodcliReqFK) VALUES('$cant','$refe','$idfk','$idfk2')");

		//En caso de ser diferente la consulta:
	 	if(!$SQL){
	 		//Mandar el mensaje de error
			echo $MySQLiconn->error;
	 	}
	 	else{
	 		$_SESSION['orden']=$idfk;
	 		//Mandamos un mensaje de exito:
	 		//echo"<script>alert('Se ha Añadido un nuevo Requerimiento')</script>";
	 	}
	}
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* 
//Inicio Código Activar 
//Si se dio clic en Activar:
if(isset($_GET['activar']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE $reqProd SET bajaReq=1 WHERE idReq=".$_GET['activar']);
	if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	echo"<script>alert('Se ha Activado un Requerimiento')</script>";
	echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_RequerimientosOrden.php'>";
	 }
}
/* Fin Código Activar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* 
//Inicio Código Eliminación Logíca 
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE $reqProd SET bajaReq=0 WHERE idReq=".$_GET['del']);
	if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:	 
	echo"<script>alert('Se ha Desactivado Un Requerimiento')</script>"; 
	echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_RequerimientosOrden.php'>";
	 }
}
/* Fin Código Eliminación Logíca  */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Definitiva */
if(isset($_GET['del']))
{ //Cambiar el parametro "del" para que no haya conflictos:
	$SQL = $MySQLiconn->query("DELETE FROM  $reqProd where idReq=".$_GET['del']);
	//En caso de ser diferente la consulta:
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	} 
	else header("Location: Logistica_RequerimientosOrden.php");/*else{
		//Mandamos un mensaje de exito:
		echo"<script>alert('Se ha Eliminado un Requerimiento')</script>";
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_RequerimientosOrden.php'>";
	}*/
}
 /* Fin Código Eliminación Definitiva */
//////////////////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////////////////////////////////////////////////////////////////////
 /* Inicio Código Atualizar  */
if(isset($_GET['edit'])){
	$SQL = $MySQLiconn->query("SELECT * FROM $reqProd WHERE idReq=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
	$_SESSION['orden']=$getROW['ordenReqFK'];
}

if(isset($_POST['update'])){
	
	$SQL = $MySQLiconn->query("UPDATE $reqProd SET cantReq='".$_POST['cantidad']."', refeReq='".$_POST['referencia']."' WHERE idReq=".$_GET['edit']);
			 	
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	} 
	else header("Location: Logistica_RequerimientosOrden.php");/*else{
		//Mandamos un mensaje de exito:
		echo"<script>alert('Se ha Modificado Un Requerimiento')</script>"; 
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_RequerimientosOrden.php'>";	 
	}*/
}
/* Fin Código Atualizar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////


//Mandar al siguiente Modulo Relacionado (Confirmaciones) y mandar un dato relacional 
if(isset($_GET['comboconfir'])){
	$_SESSION['orden']= $_GET['comboconfir'];
	header("Location: Logistica_ConfirmacionesOrden.php");	
} ?>