<?php
include("../Database/db.php");


/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post
	$rfc = $MySQLiconn->real_escape_string($_POST['RFC']);
	$nombre =$MySQLiconn->real_escape_string($_POST['Nombre']);   
	$domicilio = $MySQLiconn->real_escape_string($_POST['Domicilio']);
	$colonia = $MySQLiconn->real_escape_string($_POST['Colonia']);
	$ciudad = $MySQLiconn->real_escape_string($_POST['Ciudad']);
	$cp = $MySQLiconn->real_escape_string($_POST['CP']);
	$telefono = $MySQLiconn->real_escape_string($_POST['TelefonoCli']);

//Si existen campos vacios, manda un mensaje
if ($rfc=="" or $nombre=="" or $domicilio=="" or $colonia=="" or $ciudad=="" or $cp=="" or $telefono=="" ){
	echo "<script>alert('Necesita llenar el campo vacío')</script>";

}
else{
	//Realizamos la consulta
	 $SQL =$MySQLiconn->query("INSERT INTO $tablacli(rfccli,nombrecli, domiciliocli, coloniacli, ciudadcli, cpcli, telcli) VALUES('$rfc','$nombre','$domicilio','$colonia','$ciudad','$cp','$telefono')");
	 
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Agregado un Nuevo Cliente')</script>";
	 }
}
}

/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Activar */
//Si se dio clic en Activar:
if(isset($_GET['activar']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE $tablacli	SET bajacli=1 WHERE ID=".$_GET['activar']);
	if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	echo"<script>alert('Se ha Reactivado Un Cliente')</script>"; 
	 echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_clientes.php?mostrar'>";
	 }
}
/* Fin Código Activar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE $tablacli	SET bajacli=0 WHERE ID=".$_GET['del']);
	if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:	 
	echo"<script>alert('Se ha Desactivado Un Cliente')</script>"; 
	 echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_clientes.php?mostrar'>";
	 }
}
/* Fin Código Eliminación Logíca  */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli']))
{ //Cambiar el parametro "del" para que no haya conflictos:
	$SQL = $MySQLiconn->query("DELETE FROM  $tablacli where ID=".$_GET['eli']);
	//En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Eliminado un Cliente desactivado')</script>";
	 echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_clientes.php?mostrar'>";
	 }
}
 /* Fin Código Eliminación Definitiva */
//////////////////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Atualizar  */
if(isset($_GET['edit']))
{
	$SQL = $MySQLiconn->query("SELECT * FROM $tablacli WHERE ID=".$_GET['edit']);
	$getROW = $SQL->fetch_array();

}

if(isset($_POST['update']))
{



	if ($_POST['RFC']=="" or $_POST['Nombre']=="" or $_POST['Domicilio']=="" or $_POST['Colonia']=="" or $_POST['Ciudad']=="" or $_POST['CP'] =="" or $_POST['TelefonoCli']=="" ){
	echo "<script>alert('Necesita llenar el campo vacío')</script>";

}
else{
	$SQL = $MySQLiconn->query("UPDATE $tablacli SET rfccli='".$_POST['RFC']."', nombrecli='".$_POST['Nombre']."', domiciliocli='".$_POST['Domicilio']."', coloniacli='".$_POST['Colonia']."', ciudadcli='".$_POST['Ciudad']."', cpcli='".$_POST['CP']."', telcli='".$_POST['TelefonoCli']."' WHERE ID=".$_GET['edit']);

 	
if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	echo"<script>alert('Se ha Modificado Un Cliente')</script>"; 
echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_Clientes.php'>";	 
	 }	 }
}
/* Fin Código Atualizar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////

//Mandar al siguiente Modulo Relacionado (Contactos) y mandar un dato relacional 
if(isset($_GET['combocon']))
{
	$_SESSION['descripcion']= $_GET['combocon'];
	header("Location: Logistica_ContactoClientes.php");	
}


//Mandar al siguiente Modulo Relacionado (Sucursal) y mandar un dato relacional 
if(isset($_GET['combosuc']))
{
	$_SESSION['descripcion']= $_GET['combosuc'];
	header("Location: Logistica_Sucursales.php");
}

?>


