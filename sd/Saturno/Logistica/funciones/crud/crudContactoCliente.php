<?php

include("../Database/db.php");

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{

//idconcli	nombreconcli	puestoconcli	telefonoconcli	movilcl	emailconcli	bajaconcli	idcliFK
/* Nombre Puesto Telefono Celular Correo */

	//Pasamos los parametros por medio de post
	$idfk =$MySQLiconn->real_escape_string($_POST['example']);   
	$nombre =$MySQLiconn->real_escape_string($_POST['Nombre']);   
	$puesto = $MySQLiconn->real_escape_string($_POST['Puesto']);
	$telefono = $MySQLiconn->real_escape_string($_POST['Telefono']);
	$celular = $MySQLiconn->real_escape_string($_POST['Celular']);
	$correo = $MySQLiconn->real_escape_string($_POST['Correo']);
	
	if ($nombre=="" or $puesto=="" or $telefono=="" or $correo==""){
	echo "<script>alert('No se registro el Contacto')</script>";

}
else{
	//Realizamos la consulta
	 $SQL =$MySQLiconn->query("INSERT INTO $tablaconcli(nombreconcli, puestoconcli, telefonoconcli, movilcl, emailconcli, idcliFK) VALUES('$nombre','$puesto','$telefono','$celular','$correo','$idfk')");

	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Agregado un Nuevo Contacto para Cliente')</script>";
	 }
}}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Activar */
//Si se dio clic en Activar:
if(isset($_GET['activar']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE $tablaconcli	SET bajaconcli=1 WHERE idconcli=".$_GET['activar']);
	if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	echo"<script>alert('Se ha Reactivado Un Contacto del Cliente')</script>"; 
	 echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_ContactoClientes.php?mostrar'>";
	 }
}
/* Fin Código Activar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE $tablaconcli	SET bajaconcli=0 WHERE idconcli=".$_GET['del']);
	if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:	 
	echo"<script>alert('Se ha Desactivado Un Contacto Cliente')</script>"; 
	 echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_ContactoClientes.php?mostrar'>";
	 }
}
/* Fin Código Eliminación Logíca  */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli']))
{ //Cambiar el parametro "del" para que no haya conflictos:
	$SQL = $MySQLiconn->query("DELETE FROM  $tablaconcli where idconcli=".$_GET['eli']);
	//En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Eliminado el Contacto desactivado')</script>";
	 echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_ContactoClientes.php?mostrar'>";
	 }
}
 /* Fin Código Eliminación Definitiva */
//////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Atualizar  */
if(isset($_GET['edit']))
{
	$SQL = $MySQLiconn->query("SELECT * FROM $tablaconcli WHERE idconcli=".$_GET['edit']);
	$getROW = $SQL->fetch_array();

}

if(isset($_POST['update']))
{
//idconcli	nombreconcli	puestoconcli	telefonoconcli	movilcl	emailconcli	bajaconcli	idcliFK

/* Nombre   Puesto  Telefono  Celular  Correo */ 

if ($_POST['Nombre']=="" or $_POST['Puesto']=="" or $_POST['Telefono']=="" or $_POST['Correo']=="" ){
	echo "<script>alert('Necesita llenar el campo vacío')</script>";
}
else{

	$SQL = $MySQLiconn->query("UPDATE $tablaconcli SET nombreconcli='".$_POST['Nombre']."', puestoconcli='".$_POST['Puesto']."', telefonoconcli='".$_POST['Telefono']."', movilcl='".$_POST['Celular']."', emailconcli='".$_POST['Correo']."', idcliFK='".$_POST['example']."' WHERE idconcli=".$_GET['edit']);

 	
if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	echo"<script>alert('Se ha Modificado El Contacto..')</script>"; 
echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_ContactoClientes.php'>";	 
	 }	 }
}
/* Fin Código Atualizar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
?>