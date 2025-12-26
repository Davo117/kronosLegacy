<?php

include("../Database/db.php");

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{
//idconsuc	nombreconsuc	puestoconsuc	telconsuc	movilconsuc	emailconsuc	bajaconsuc	FKCliente
	$idfk =$MySQLiconn->real_escape_string($_POST['example']);   
	$nombre =$MySQLiconn->real_escape_string($_POST['Nombre']);   
	$puesto = $MySQLiconn->real_escape_string($_POST['Puesto']);
	$telefono = $MySQLiconn->real_escape_string($_POST['Telefono']);
	$celular = $MySQLiconn->real_escape_string($_POST['Celular']);
	$correo = $MySQLiconn->real_escape_string($_POST['Correo']);

	//Realizamos la consulta
	 $SQL =$MySQLiconn->query("INSERT INTO $tablaconsuc(nombreconsuc, puestoconsuc, telconsuc, movilconsuc, emailconsuc, sucFK) VALUES('$nombre','$puesto','$telefono','$celular','$correo','$idfk')");
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 	$_SESSION['sucursal']=$idfk;
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Agregado un Nuevo Contacto para la Sucursal')</script>";
	 }
}
/* Fin Código Insertar */

//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Activar */
//Si se dio clic en Activar:
if(isset($_GET['activar']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE $tablaconsuc	SET bajaconsuc=1 WHERE idconsuc=".$_GET['activar']);
	if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	echo"<script>alert('Se ha Activado Un Contacto de la Sucursal')</script>"; 
	 echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_ContactoSucursales.php'>";
	 }
}
/* Fin Código Activar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE $tablaconsuc	SET bajaconsuc=0 WHERE idconsuc=".$_GET['del']);
	if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:	 
	echo"<script>alert('Se ha Desactivado Un Contacto de Sucursal')</script>"; 
	 echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_ContactoSucursales.php'>";
	 }
}
/* Fin Código Eliminación Logíca  */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli']))
{ //Cambiar el parametro "del" para que no haya conflictos:
	$SQL = $MySQLiconn->query("DELETE FROM  $tablaconsuc where idconsuc=".$_GET['eli']);
	//En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Eliminado el Contacto')</script>";
	 echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_ContactoSucursales.php'>";
	 }
}
 /* Fin Código Eliminación Definitiva */
 //////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Atualizar  */
if(isset($_GET['edit']))
{
	$SQL = $MySQLiconn->query("SELECT * FROM $tablaconsuc WHERE idconsuc=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
	$_SESSION['sucursal']=$getROW['sucFK'];
}
if(isset($_POST['update']))
{
	//idconsuc	nombreconsuc	puestoconsuc	telconsuc	movilconsuc	emailconsuc	bajaconsuc	sucFK
if ($_POST['Nombre']=="" or $_POST['Puesto']=="" or $_POST['Telefono']=="" or $_POST['Correo']=="" ){
	echo "<script>alert('Necesita llenar el campo vacío')</script>";
}
else{
	$SQL = $MySQLiconn->query("UPDATE $tablaconsuc SET nombreconsuc='".$_POST['Nombre']."', puestoconsuc='".$_POST['Puesto']."', telconsuc='".$_POST['Telefono']."', movilconsuc='".$_POST['Celular']."', emailconsuc='".$_POST['Correo']."', sucFK='".$_POST['example']."' WHERE idconsuc=".$_GET['edit']);

if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	echo"<script>alert('Se ha Modificado El Contacto..')</script>"; 
echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_ContactoSucursales.php'>";	 
	 }	 
}}
/* Fin Código Atualizar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
?>