<?php

include_once '../Database/db.php';

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post
	$numemp = $MySQLiconn->real_escape_string($_POST['NumEmp']);
	$nombre =$MySQLiconn->real_escape_string($_POST['Nombre']);   
	$ape = $MySQLiconn->real_escape_string($_POST['Apellido']);
	$telefono = $MySQLiconn->real_escape_string($_POST['Telefono']);
	

	if ($numemp=="" or $nombre=="" or $ape=="" or $telefono==""){
	echo "<script>alert('No se registro el Empleado')</script>";

}
else{
	//Realizamos la consulta
	 $SQL =$MySQLiconn->query("INSERT INTO empleado(numemple,Nombre,apellido,Telefono) VALUES('$numemp','$nombre','$ape','$telefono')");
	 
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Agregado un nuevo empleado')</script>";
	 } }
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Activar */
//Si se dio clic en Activar:
if(isset($_GET['activar']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE empleado	SET baja=1 WHERE id=".$_GET['activar']);
	if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	echo"<script>alert('Se ha Reactivado Un empleado')</script>"; 
	 echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=index.php?mostrar'>";
	 }
}
/* Fin Código Activar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE empleado	SET baja=0 WHERE id=".$_GET['del']);
	if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:	 
	echo"<script>alert('Se ha Desactivado Un empleado')</script>"; 
	 echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=index.php?mostrar'>";
	 }
}
/* Fin Código Eliminación Logíca  */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli']))
{ //Cambiar el parametro "del" para que no haya conflictos:
	$SQL = $MySQLiconn->query("DELETE FROM  empleado where ID=".$_GET['eli']);
	//En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Eliminado un empleado desactivado')</script>";
	 echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=index.php?mostrar'>";
	 }
}
 /* Fin Código Eliminación Definitiva */
//////////////////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Atualizar  */
if(isset($_GET['edit']))
{
	$SQL = $MySQLiconn->query("SELECT * FROM empleado WHERE id=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
}

if(isset($_POST['update']))
{

if ($_POST['NumEmp']=="" or $_POST['Nombre']=="" or $_POST['Apellido']=="" or $_POST['Telefono']=="" ){
	echo "<script>alert('Necesita llenar el campo vacío')</script>";
}
else{

	$SQL = $MySQLiconn->query("UPDATE empleado SET numemple='".$_POST['NumEmp']."', Nombre='".$_POST['Nombre']."', apellido='".$_POST['Apellido']."', Telefono='".$_POST['Telefono']."' WHERE id=".$_GET['edit']);
if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	echo"<script>alert('Se ha Modificado Un empleado')</script>"; 
echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=index.php'>";	 
	 }	 }
}
/* Fin Código Atualizar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
?>