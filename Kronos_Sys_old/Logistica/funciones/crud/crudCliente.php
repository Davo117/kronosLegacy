<?php
include("../Database/db.php");


/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save'])){
	//Pasamos los parametros por medio de post a una variable php
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
		$SQLi=$MySQLiconn->query("SELECT nombrecli FROM $tablacli where bajacli='1'");
		while($row=$SQLi->fetch_array()){
			if ($row['nombrecli']==$nombre) {
				echo "<script>alert('No se puede repetir el Cliente.')</script>";
			return;
			}
		}
		//Realizamos la consulta
	 	$SQL =$MySQLiconn->query("INSERT INTO $tablacli(rfccli,nombrecli, domiciliocli, coloniacli, ciudadcli, cpcli, telcli) VALUES('$rfc','$nombre','$domicilio','$colonia','$ciudad','$cp','$telefono')");
	 
		//En caso de ser diferente la consulta:
		if(!$SQL){
	 		//Mandar el mensaje de error
			echo $MySQLiconn->error;
		} 
	}
}

/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Activar */
//Si se dio clic en Activar:
if(isset($_GET['activar'])){
	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE $tablacli	SET bajacli='1' WHERE ID=".$_GET['activar']);
	if(!$SQL){
		//Mandar el mensaje de error
		echo $MySQLiconn->error;
	} 
	else header("Location: Logistica_Clientes.php");
}
/* Fin Código Activar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del'])){
	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE $tablacli	SET bajacli=0 WHERE ID=".$_GET['del']);
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	} 
	else header("Location: Logistica_Clientes.php");
}
/* Fin Código Eliminación Logíca  */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli'])){ 

	//Si tiene datos en sucursales o contactos, los desactiva tambien
	$sqlite = $MySQLiconn->query("SELECT nombrecli FROM $tablacli WHERE ID=".$_GET['eli']);
	$getROW = $sqlite->fetch_array();	
	if (!empty($getROW['nombrecli'])) {
	$MySQLiconn->query("UPDATE $tablaconcli SET bajaconcli=0 WHERE idcliFK='".$getROW['nombrecli']."'");
	$MySQLiconn->query("UPDATE $tablasucursal SET bajasuc=0 WHERE idcliFKS='".$getROW['nombrecli']."'");
	}

	$SQL = $MySQLiconn->query("DELETE FROM  $tablacli where ID=".$_GET['eli']);
	//En caso de ser diferente la consulta:
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	} 
	else header("Location: Logistica_Clientes.php");
}
 /* Fin Código Eliminación Definitiva */
//////////////////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Atualizar  */
if(isset($_GET['edit'])){
	$SQL = $MySQLiconn->query("SELECT * FROM $tablacli WHERE ID=".$_GET['edit']);
	if(!$SQL){
	 		//Mandar el mensaje de error
			echo $MySQLiconn->error;
		} else	$getROW = $SQL->fetch_array();
}

if(isset($_POST['update'])){

	//Si tiene datos en sucursales o contactos, los desactiva tambien		
	
	if ($_POST['RFC']=="" or $_POST['Nombre']=="" or $_POST['Domicilio']=="" or $_POST['Colonia']=="" or $_POST['Ciudad']=="" or $_POST['CP'] =="" or $_POST['TelefonoCli']=="" ){
		echo "<script>alert('Necesita llenar el campo vacío')</script>";
	}
	else{
	
		if (!empty($_POST['Nombre'])) {	
			$SQLi=$MySQLiconn->query("SELECT nombrecli FROM $tablacli where bajacli='1' && nombrecli!='".$getROW['nombrecli']."'");
			while($row=$SQLi->fetch_array()){
				if ($row['nombrecli']==$_POST['Nombre']) {
					echo "<script>alert('No se puede repetir el Cliente.')</script>";
					return;
				}
			} 
			$MySQLiconn->query("UPDATE $tablaconcli SET idcliFK='".$_POST['Nombre']."' WHERE idcliFK='".$getROW['nombrecli']."'");
			$MySQLiconn->query("UPDATE $tablasucursal SET idcliFKS='".$_POST['Nombre']."' WHERE idcliFKS='".$getROW['nombrecli']."'");
		}
		$SQL = $MySQLiconn->query("UPDATE $tablacli SET rfccli='".$_POST['RFC']."', nombrecli='".$_POST['Nombre']."', domiciliocli='".$_POST['Domicilio']."', coloniacli='".$_POST['Colonia']."', ciudadcli='".$_POST['Ciudad']."', cpcli='".$_POST['CP']."', telcli='".$_POST['TelefonoCli']."' WHERE ID=".$_GET['edit']); 
		
		if(!$SQL){
	 		//Mandar el mensaje de error
			echo $MySQLiconn->error;
		} 
		else header("Location: Logistica_Clientes.php");
	}
}
/* Fin Código Atualizar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////

//Mandar al siguiente Modulo Relacionado (Contactos) y mandar un dato relacional 
if(isset($_GET['combocon'])){
	$_SESSION['cliente']= $_GET['combocon'];
	header("Location: Logistica_ContactoClientes.php");	
}

//Mandar al siguiente Modulo Relacionado (Sucursal) y mandar un dato relacional 
if(isset($_GET['combosuc'])){
	$_SESSION['cliente']= $_GET['combosuc'];
	header("Location: Logistica_Sucursales.php");
} ?>