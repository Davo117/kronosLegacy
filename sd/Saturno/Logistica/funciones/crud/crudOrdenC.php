<?php
include("../Database/db.php");


/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{
	// example	ordenC 	dateDoc  dateRec

	

	//Pasamos los parametros por medio de post
	$idfk =$MySQLiconn->real_escape_string($_POST['example']);   
	$ordenC =$MySQLiconn->real_escape_string($_POST['ordenC']);   
	$diaDoc = $MySQLiconn->real_escape_string($_POST['dateDoc']);
	$diaRec = $MySQLiconn->real_escape_string($_POST['dateRec']);

if ($ordenC=="" or $diaDoc=="" or $diaRec==""){
	echo "<script>alert('No se registro la orden')</script>";

}
else{

	$SQL =$MySQLiconn->query("INSERT INTO $tablaOrden(orden, documento, recepcion,sucFK) VALUES('$ordenC','$diaDoc','$diaRec', '$idfk')");
	//En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Añadido una nueva Orden')</script>";
	 }
	 }}
	 /* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Activar */
//Si se dio clic en Activar:
if(isset($_GET['activar']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE $tablaOrden SET bajaOrden=1 WHERE idorden=".$_GET['activar']);
	if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	echo"<script>alert('Se ha Reactivado Una Orden de Compra')</script>";
	echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_OrdenesCompra.php?mostrar'>";
	 }
}
/* Fin Código Activar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE $tablaOrden SET bajaOrden=0 WHERE idorden=".$_GET['del']);
	if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:	 
	echo"<script>alert('Se ha Desactivado Una Sucursal')</script>"; 
	echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_OrdenesCompra.php?mostrar'>";
	 }
}
/* Fin Código Eliminación Logíca  */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli']))
{ //Cambiar el parametro "del" para que no haya conflictos:
	$SQL = $MySQLiconn->query("DELETE FROM  $tablaOrden where idorden=".$_GET['eli']);
	//En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Eliminado una Orden de Compra desactivada')</script>";
	 echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_OrdenesCompra.php?mostrar'>";
	 }
}
 /* Fin Código Eliminación Definitiva */
 //////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
 /* Inicio Código Atualizar  */
if(isset($_GET['edit']))
{
	$SQL = $MySQLiconn->query("SELECT * FROM $tablaOrden WHERE idorden=".$_GET['edit']);
	$getROW = $SQL->fetch_array();

}

if(isset($_POST['update']))
{
	$SQL = $MySQLiconn->query("UPDATE $tablaOrden SET orden='".$_POST['ordenC']."', documento='".$_POST['dateDoc']."', recepcion='".$_POST['dateRec']."', sucFK='".$_POST['example']."'WHERE idorden=".$_GET['edit']);
			
 	//idorden	orden	documento	recepcion	bajaOrden	sucFK
 	// example	ordenC 	dateDoc  dateRec
if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	echo"<script>alert('Se ha Modificado Una Orden de Compra')</script>"; 
echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_OrdenesCompra.php'>";	 
	 }	 
}
/* Fin Código Atualizar */




//Mandar al siguiente Modulo Relacionado (Requerimientos) y mandar un dato relacional 
if(isset($_GET['comboRequ']))
{
	$_SESSION['descripcion']= $_GET['comboRequ'];
	header("Location: Logistica_RequerimientosOrden.php");	
}


//Mandar al siguiente Modulo Relacionado (Confirmaciones) y mandar un dato relacional 
if(isset($_GET['comboConf']))
{
	$_SESSION['descripcion']= $_GET['comboConf'];
	header("Location: Logistica_ConfirmacionesOrden.php");	
}

?>