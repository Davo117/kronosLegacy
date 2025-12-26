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

	
	$SQLi=$MySQLiconn->query("SELECT nombresuc FROM $tablasucursal where bajasuc='1'");
	while($row=$SQLi->fetch_array()){
		if ($row['nombresuc']==$nombre) {
			echo "<script>alert('No se puede repetir la Sucursal.')</script>";
			return;
		}
	} 

	$SQL =$MySQLiconn->query("INSERT INTO $tablasucursal(nombresuc, domiciliosuc, coloniasuc, ciudadsuc, cpsuc, telefonosuc, transpsuc, idcliFKS) VALUES('$nombre','$domicilio','$colonia','$ciudad','$cp','$telefono','$transporte', '$idfk')");
	//En caso de ser diferente la consulta:
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
 	} 
	 else{
		$_SESSION['cliente']=$idfk;
		//Mandamos un mensaje de exito:
		echo"<script>alert('Se ha Añadido una nueva Sucursal')</script>";
	} 
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Activar */
//Si se dio clic en Activar:
if(isset($_GET['activar'])){	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE $tablasucursal SET bajasuc=1 WHERE idsuc=".$_GET['activar']);
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	} 
	else{
		//Mandamos un mensaje de exito:
		echo"<script>alert('Se ha Activado Una Sucursal')</script>";
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_Sucursales.php'>";
	}
}
/* Fin Código Activar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del'])){	//Lanzamos la consulta actualizando la baja a 0
	$sqlite = $MySQLiconn->query("SELECT * FROM $tablasucursal WHERE idsuc=".$_GET['del']);
	$getROW = $sqlite->fetch_array();	
	if (!empty($getROW['nombresuc'])) {
		$MySQLiconn->query("UPDATE $tablaOrden SET bajaOrden=0 WHERE sucFK='".$getROW['nombresuc']."'");

		$MySQLiconn->query("UPDATE $tablaconsuc SET bajaconsuc=0 WHERE sucFK='".$getROW['nombresuc']."'");

		$MySQLiconn->query("UPDATE $embarq SET bajaEmb=0 WHERE sucEmbFK='".$getROW['nombresuc']."'");
	}

	$SQL = $MySQLiconn->query("UPDATE $tablasucursal SET bajasuc=0 WHERE idsuc=".$_GET['del']);
	if(!$SQL) {
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	} 
	else{
		//Mandamos un mensaje de exito:	 
		echo"<script>alert('Se ha Desactivado Una Sucursal')</script>"; 
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_Sucursales.php'>";
	}
}
/* Fin Código Eliminación Logíca  */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli'])){ //Cambiar el parametro "del" para que no haya conflictos:
	$SQL = $MySQLiconn->query("DELETE FROM  $tablasucursal where idsuc=".$_GET['eli']);
	//En caso de ser diferente la consulta:
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	} 
	else{
		//Mandamos un mensaje de exito:
		echo"<script>alert('Se ha Eliminado una Sucursal')</script>";
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_Sucursales.php'>";
	}
}
/* Fin Código Eliminación Definitiva */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Atualizar  */
if(isset($_GET['edit'])){
	$SQL = $MySQLiconn->query("SELECT * FROM $tablasucursal WHERE idsuc=".$_GET['edit']);
	
	$getROW = $SQL->fetch_array();
	$_SESSION['cliente']=$getROW['idcliFKS'];
}
if(isset($_POST['update'])){

	//Si tiene datos en sucursales o contactos, los desactiva tambien
	if (!empty($_POST['Name'])){
		$SQLi=$MySQLiconn->query("SELECT nombresuc FROM $tablasucursal where bajasuc='1' && nombresuc!='".$getROW['nombresuc']."'");
		while($row=$SQLi->fetch_array()){
			if ($row['nombresuc']==$_POST['Name']) {
				echo "<script>alert('No se puede repetir la Sucursal.')</script>";
				return;
			}
		} 	
		$MySQLiconn->query("UPDATE $tablaOrden SET sucFK='".$_POST['Name']."' WHERE sucFK='".$getROW['nombresuc']."'");

		$MySQLiconn->query("UPDATE $tablaconsuc SET sucFK='".$_POST['Name']."' WHERE sucFK='".$getROW['nombresuc']."'");

		$MySQLiconn->query("UPDATE $embarq SET sucEmbFK='".$_POST['Name']."' WHERE ='".$getROW['nombresuc']."'");
	}
	$SQL=$MySQLiconn->query("UPDATE $tablasucursal SET nombresuc='".$_POST['Name']."', domiciliosuc='".$_POST['Domicilio']."', coloniasuc='".$_POST['Colonia']."', ciudadsuc='".$_POST['Ciudad']."', cpsuc='".$_POST['CP']."', telefonosuc='".$_POST['Telefono']."', transpsuc='".$_POST['Transporte']."' WHERE idsuc=".$_GET['edit']);
	
	if(!$SQL){
		//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else{
		//Mandamos un mensaje de exito:
		echo"<script>alert('Se ha Modificado Una Sucursal')</script>"; 
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_Sucursales.php'>";	 
	}	 	
}
/* Fin Código Atualizar */
////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////

//Mandar al siguiente Modulo Relacionado (Contactos) y mandar un dato relacional 
if(isset($_GET['comboconsuc'])){
	$_SESSION['sucursal']= $_GET['comboconsuc'];
	header("Location: Logistica_ContactoSucursales.php");	
}
//Mandar al siguiente Modulo Relacionado (Contactos) y mandar un dato relacional 
if(isset($_GET['comboOC'])){
	$_SESSION['sucursal']= $_GET['comboOC'];
	header("Location: Logistica_OrdenesCompra.php");	
}
//Mandar al siguiente Modulo Relacionado (Sucursal) y mandar un dato relacional 
if(isset($_GET['comboembar'])){
	$_SESSION['sucursal']= $_GET['comboembar'];
	header("Location: Logistica_Embarques.php");	
} ?>