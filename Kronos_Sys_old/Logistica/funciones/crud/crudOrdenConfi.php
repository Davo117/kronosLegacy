<?php
include("../Database/db.php");	

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save'])){
	//Pasamos los parametros por medio de post
	$idfk =$MySQLiconn->real_escape_string($_POST['orden']);   
	$idfk2 =$MySQLiconn->real_escape_string($_POST['producto']); 
	$empaque =$MySQLiconn->real_escape_string($_POST['empaque']); 
	$cant = $MySQLiconn->real_escape_string($_POST['cantidad']);
	$refe = $MySQLiconn->real_escape_string($_POST['referencia']);
	$dateEm = $MySQLiconn->real_escape_string($_POST['dateEmbar']);
	$dateEn = $MySQLiconn->real_escape_string($_POST['dateEntre']);

	if ($cant==""){
		echo "<script>alert('No se registro la Confirmación del Producto.')</script>";
	}
	else{

		$SQL =$MySQLiconn->query("INSERT INTO $confirProd(ordenConfi, prodConfi, empaqueConfi, cantidadConfi, referenciaConfi, embarqueConfi, entregaConfi) VALUES('$idfk','$idfk2','$empaque','$cant', '$refe', '$dateEm', '$dateEn')");
		//En caso de ser diferente la consulta:
		if(!$SQL){
	 		//Mandar el mensaje de error
			echo $MySQLiconn->error;
		}
		else{
			$_SESSION['orden']=$idfk;
			//Mandamos un mensaje de exito:
			//echo"<script>alert('Se ha Añadido una nueva Confirmación.')</script>";
		}
	}
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////

//Inicio Código Activar 
//Si se dio clic en Activar:
if(isset($_GET['activar'])){	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE $confirProd SET bajaConfi=1 WHERE idConfi=".$_GET['activar']);
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	} 
	else{
		//Mandamos un mensaje de exito:
		echo"<script>alert('Se ha ctivado una Confirmación.')</script>";
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_ConfirmacionesOrden.php'>";
	}
}
/* Fin Código Activar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
 
//Inicio Código Eliminación Logíca 
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del'])){	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE $confirProd SET bajaConfi=0 WHERE idConfi=".$_GET['del']);
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	} 
	else{
		//Mandamos un mensaje de exito:	 
		echo"<script>alert('Se ha Desactivado una Confirmación.')</script>"; 
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_ConfirmacionesOrden.php'>";
	}
}
/* Fin Código Eliminación Logíca  */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* 
//Inicio Código Eliminación Definitiva 
if(isset($_GET['eli']))
{ //Cambiar el parametro "del" para que no haya conflictos:
	$SQL = $MySQLiconn->query("DELETE FROM  $confirProd where idConfi=".$_GET['eli']);
	//En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Eliminado una Confirmación')</script>";
	 echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_ConfirmacionesOrden.php'>";
	 }
}
 /* Fin Código Eliminación Definitiva */
//////////////////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////////////////////////////////////////////////////////////////////
 /* Inicio Código Atualizar 
if(isset($_GET['edit']))
{
	$SQL = $MySQLiconn->query("SELECT * FROM $confirProd WHERE idConfi=".$_GET['edit']);
	$getROW = $SQL->fetch_array();

}
if(isset($_POST['update']))
{

// idConfi	ordenConfi	prodConfi	empaqueConfi	cantidadConfi	referenciaConfi	embarqueConfi	entregaConfi bajaConfi
//$idfk $idfk2	$empaque	$cant	$refe	$dateEm	$dateEn 

if ($_POST['cantidad']=="" ){
	echo "<script>alert('Necesita llenar el campo vacío')</script>";
}
else{
	$SQL = $MySQLiconn->query("UPDATE $confirProd SET cantidadConfi='".$_POST['cantidad']."', referenciaConfi='".$_POST['referencia']."', embarqueConfi='".$_POST['dateEmbar']."', entregaConfi='".$_POST['dateEntre']."'	 WHERE idConfi=".$_GET['edit']);
			
 	
if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	echo"<script>alert('Se ha Modificado Una Confirmación')</script>"; 
echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_ConfirmacionesOrden.php'>";	 
	 }	 
}}
/* Fin Código Atualizar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////

//Mandar al siguiente Modulo Relacionado (Confirmaciones) y mandar un dato relacional 
if(isset($_GET['comboSurtido'])){
	$_SESSION['ordenes']= $_GET['comboSurtido'];
	header("Location: Logistica_Surtidos.php");
} ?>