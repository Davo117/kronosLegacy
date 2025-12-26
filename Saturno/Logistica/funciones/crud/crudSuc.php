<?php
include("../Database/db.php");


/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{

	//Pasamos los parametros por medio de post
	$idfk =$MySQLiconn->real_escape_string($_POST['example']);   
	$nombre =$MySQLiconn->real_escape_string($_POST['Name']);   
	$domicilio = $MySQLiconn->real_escape_string($_POST['Domicilio']);
	$colonia = $MySQLiconn->real_escape_string($_POST['Colonia']);
	$ciudad = $MySQLiconn->real_escape_string($_POST['Ciudad']);
	$cp = $MySQLiconn->real_escape_string($_POST['CP']);
	$telefono = $MySQLiconn->real_escape_string($_POST['Telefono']);
	$transporte = $MySQLiconn->real_escape_string($_POST['Transporte']); 

if ($nombre=="" or $domicilio=="" or $colonia=="" or $ciudad=="" or $cp=="" or $telefono=="" or $transporte==""){
	echo "<script>alert('No se registro la Sucursal')</script>";

}
else{
	$SQL =$MySQLiconn->query("INSERT INTO $tablasucursal(nombresuc, domiciliosuc, coloniasuc, ciudadsuc, cpsuc, telefonosuc, transpsuc, idcliFKS) VALUES('$nombre','$domicilio','$colonia','$ciudad','$cp','$telefono','$transporte', '$idfk')");

	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Añadido una nueva Sucursal')</script>";
	 } }
	 }
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Activar */
//Si se dio clic en Activar:
if(isset($_GET['activar']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE $tablasucursal SET bajasuc=1 WHERE ID=".$_GET['activar']);
	if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	echo"<script>alert('Se ha Reactivado Una Sucursal')</script>";
	echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_Sucursales.php?mostrar'>";
	 }
}
/* Fin Código Activar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE $tablasucursal SET bajasuc=0 WHERE idsuc=".$_GET['del']);
	if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:	 
	echo"<script>alert('Se ha Desactivado Una Sucursal')</script>"; 
	echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_Sucursales.php?mostrar'>";
	 }
}
/* Fin Código Eliminación Logíca  */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli']))
{ //Cambiar el parametro "del" para que no haya conflictos:
	$SQL = $MySQLiconn->query("DELETE FROM  $tablasucursal where idsuc=".$_GET['eli']);
	//En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Eliminado una Sucursal desactivada')</script>";
	 echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_Sucursales.php?mostrar'>";
	 }
}
 /* Fin Código Eliminación Definitiva */
//////////////////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////////////////////////////////////////////////////////////////////
 /* Inicio Código Atualizar  */
if(isset($_GET['edit']))
{
	$SQL = $MySQLiconn->query("SELECT * FROM $tablasucursal WHERE idsuc=".$_GET['edit']);
	$getROW = $SQL->fetch_array();

}

if(isset($_POST['update']))
{

	// Name Domicilio Colonia Ciudad CP Telefono Transporte

if ($_POST['Name']=="" or $_POST['Domicilio']=="" or $_POST['Colonia']=="" or $_POST['Ciudad']=="" or $_POST['CP']=="" or $_POST['Telefono']=="" or $_POST['Transporte']=="" ){
	echo "<script>alert('Necesita llenar el campo vacío')</script>";
}
else{

	$SQL = $MySQLiconn->query("UPDATE $tablasucursal SET nombresuc='".$_POST['Name']."', domiciliosuc='".$_POST['Domicilio']."', coloniasuc='".$_POST['Colonia']."', ciudadsuc='".$_POST['Ciudad']."', cpsuc='".$_POST['CP']."', telefonosuc='".$_POST['Telefono']."', transpsuc='".$_POST['Transporte']."' WHERE idsuc=".$_GET['edit']);
			
 	
if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	echo"<script>alert('Se ha Modificado Una Sucursal')</script>"; 
echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_Sucursales.php'>";	 
	 }	 }
}
/* Fin Código Atualizar */
////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////



//Mandar al siguiente Modulo Relacionado (Contactos) y mandar un dato relacional 
if(isset($_GET['comboconsuc']))
{
	$_SESSION['descripcion']= $_GET['comboconsuc'];
	header("Location: Logistica_ContactoSucursales.php");	
}


//Mandar al siguiente Modulo Relacionado (Contactos) y mandar un dato relacional 
if(isset($_GET['comboOC']))
{
	$_SESSION['descripcion']= $_GET['comboOC'];
	header("Location: Logistica_OrdenesCompra.php");	
}


//Mandar al siguiente Modulo Relacionado (Sucursal) y mandar un dato relacional 
if(isset($_GET['comboembar']))
{
	$_SESSION['descripcion']= $_GET['comboembar'];
	header("Location: Logistica_Embarques.php");	
}

?>

