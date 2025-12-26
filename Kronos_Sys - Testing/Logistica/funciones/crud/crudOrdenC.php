<?php
include("../Database/db.php");


/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save'])){
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
		$SQLi=$MySQLiconn->query("SELECT orden FROM $tablaOrden where bajaOrden='1'");
		while($row=$SQLi->fetch_array()){
			if ($row['orden']==$ordenC) {
				echo "<script>alert('No se puede repetir la O.C.')</script>";
				return;
			}
		} 	
		$SQL =$MySQLiconn->query("INSERT INTO $tablaOrden(orden, documento, recepcion,sucFK) VALUES('$ordenC','$diaDoc','$diaRec', '$idfk')");
		//En caso de ser diferente la consulta:
	 	if(!$SQL){
	 		//Mandar el mensaje de error
			echo $MySQLiconn->error;
	 	}
	 	else{
	 	$_SESSION['sucursal']=$idfk;
	 	//Mandamos un mensaje de exito:
		echo"<script>alert('Se ha Añadido una nueva Orden')</script>";
	 	}
	}
}
	 /* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Activar */
//Si se dio clic en Activar:
if(isset($_GET['activar'])){	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE $tablaOrden SET bajaOrden=1 WHERE idorden=".$_GET['activar']);
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Logistica_OrdenesCompra.php");/*else{
		//Mandamos un mensaje de exito:
		echo"<script>alert('Se ha Activado Una Orden de Compra')</script>";
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_OrdenesCompra_bajas.php'>";
	}*/
}
/* Fin Código Activar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del'])){	

	//Si tiene datos en otras tablas relacionales, los desactiva tambien

//Lanzamos la consulta actualizando la baja a 0

	$query = $MySQLiconn->query("SELECT idConfi, enlaceEmbarque from confirmarprod WHERE bajaConfi=1 && ordenConfi=".$_GET['del']);
	if ($query->num_rows>0) {
		$MySQLiconn->query("UPDATE confirmarprod SET bajaConfi='2', referenciaConfi= CONCAT(referenciaConfi, '(ORDEN DE COMPRA DADA DE BAJA)') WHERE bajaConfi=1 && ordenConfi=".$_GET['del']);
	}
	
	$SQL = $MySQLiconn->query("UPDATE $tablaOrden SET bajaOrden=0 WHERE idorden=".$_GET['del']);
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Logistica_OrdenesCompra.php");/*else{
		//Mandamos un mensaje de exito:	 
		echo"<script>alert('Se ha Desactivado una Orden')</script>"; 
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_OrdenesCompra_bajas.php'>";
	}*/
}
/* Fin Código Eliminación Logíca  */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli'])){ //Cambiar el parametro "del" para que no haya conflictos:
	$SQL = $MySQLiconn->query("DELETE FROM  $tablaOrden where idorden=".$_GET['eli']);
	//En caso de ser diferente la consulta:
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Logistica_OrdenesCompra.php");/*else{
		//Mandamos un mensaje de exito:
		echo"<script>alert('Se ha Eliminado una Orden de Compra')</script>";
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_OrdenesCompra_bajas.php'>";
	}*/
}
 /* Fin Código Eliminación Definitiva */
 //////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
 /* Inicio Código Atualizar  */
if(isset($_GET['edit'])){
	$SQL = $MySQLiconn->query("SELECT * FROM $tablaOrden WHERE idorden=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
	$_SESSION['sucursal']=$getROW['sucFK'];
}

if(isset($_POST['update'])){

	if (!empty($_POST['ordenC'])) {

		$SQLi=$MySQLiconn->query("SELECT orden FROM $tablaOrden where bajaOrden='1' && orden!='".$getROW['orden']."'");
		while($row=$SQLi->fetch_array()){
			if ($row['orden']==$_POST['ordenC']) {
				echo "<script>alert('No se puede repetir la O.C.')</script>";
				return;
			}
		}
	}
	$SQL=$MySQLiconn->query("UPDATE $tablaOrden SET orden='".$_POST['ordenC']."', documento='".$_POST['dateDoc']."', recepcion='".$_POST['dateRec']."', sucFK='".$_POST['example']."'WHERE idorden=".$_GET['edit']);

	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Logistica_OrdenesCompra.php");/*else{
	 //Mandamos un mensaje de exito:
	echo"<script>alert('Se ha Modificado Una Orden de Compra')</script>"; 
	return;
	//echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_OrdenesCompra.php'>";	 
	}	 */
}
/* Fin Código Atualizar */
/////////////////////////////////////////////////////////////////////////////////////////////////////
//Mandar al siguiente Modulo Relacionado (Requerimientos) y mandar un dato relacional 
if(isset($_GET['comboRequ'])){
	$_SESSION['orden']= $_GET['comboRequ'];
	header("Location: Logistica_RequerimientosOrden.php");	
}
//Mandar al siguiente Modulo Relacionado (Confirmaciones) y mandar un dato relacional 
if(isset($_GET['comboConf'])){
	$_SESSION['orden']= $_GET['comboConf'];
	header("Location: Logistica_ConfirmacionesOrden.php");	
} ?>