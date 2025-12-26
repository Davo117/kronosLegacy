<?php
include("../Database/db.php");

$usuarioF=$_SESSION['usuario'];
/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{
/* sucursal producto  empaque  Transporte DateEmb  Observa */
	//Pasamos los parametros por medio de post
	$sucfk =$MySQLiconn->real_escape_string($_POST['sucursal']);   
	$prodfk =$MySQLiconn->real_escape_string($_POST['producto']);   
	$empfk =$MySQLiconn->real_escape_string($_POST['empaque']); 
	$transporte = $MySQLiconn->real_escape_string($_POST['Transporte']);
	$referencia = $empfk;
	$diaEmbarque = $MySQLiconn->real_escape_string($_POST['DateEmb']);
	$observa = $MySQLiconn->real_escape_string($_POST['Observa']);
//Numeracion del campo numero embarque:
$fechard = explode("-", $diaEmbarque, 3);
$fechardo= implode($fechard);
$secuencia=$fechardo."001";

$resulta =$MySQLiconn->query("SELECT numEmbarque FROM $embarq;");
while ($rows = $resulta->fetch_array()) { 
	if($secuencia==$rows['numEmbarque']){ 
		$secuencia++;
	}
}
//Fin numeración 

if ($sucfk=="" or $prodfk=="" or $empfk=="" or $transporte=="" or $diaEmbarque==""){
	echo "<script>alert('No se registro Embarque')</script>";

}
else{ 
	$_SESSION['secuenB']= $secuencia;
	
	$nombre=$_SESSION['secuenB'];
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego el embarque:  $nombre','Embarque','Logistica',NOW())");
	$SQL =$MySQLiconn->query("INSERT INTO $embarq (numEmbarque, transpEmb, referencia, diaEmb, observaEmb, sucEmbFK, prodEmbFK, empaque) VALUES('$secuencia','$transporte','$referencia','$diaEmbarque','$observa','$sucfk','$prodfk','$empfk')");
		 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 	$_SESSION['sucursal']=$sucfk;
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Añadido un nuevo Embarque')</script>";
	 }
	 }}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Activar */
//Si se dio clic en Activar:
if(isset($_GET['activar']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE $embarq SET bajaEmb=1 WHERE idEmbarque=".$_GET['activar']);
	if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 }
	 else header("Location: Logistica_Embarques.php");
	 /* else{
	 //Mandamos un mensaje de exito:
	echo"<script>alert('Se ha Activado Un Embarque')</script>";
	echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_Embarques.php'>";
	 }*/
}
/* Fin Código Activar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////

//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE $embarq	SET bajaEmb=0 WHERE idEmbarque=".$_GET['del']);
	if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 }
	 else header("Location: Logistica_Embarques.php"); /*else{
	 //Mandamos un mensaje de exito:	 
	echo"<script>alert('Se ha Desactivado Un Embarque')</script>"; 
	 echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_Embarques_bajas.php'>";
	 }*/
}
/* Fin Código Eliminación Logíca  */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Fin Código Eliminación Logíca  */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli']))
{ //Cambiar el parametro "del" para que no haya conflictos:
	$SQL = $MySQLiconn->query("DELETE FROM  $embarq where idEmbarque=".$_GET['eli']);
	//En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 }
	 else header("Location: Logistica_Embarques.php");/* else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Eliminado un Embarque')</script>";
	 echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_Embarques_bajas.php'>";
	 }*/
} /* Fin Código Eliminación Definitiva */
//////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////
 /* Inicio Código Atualizar  */
if(isset($_GET['edit'])){
	$SQL = $MySQLiconn->query("SELECT * FROM $embarq WHERE idEmbarque=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
	$_SESSION['sucursal']=$getROW['sucEmbFK'];
}

if(isset($_POST['update'])){
	if ($_POST['Transporte']=="" or $_POST['DateEmb']=="" ){
		echo "<script>alert('Necesita llenar el campo vacío')</script>";
	}
	else{

		$SQL = $MySQLiconn->query("UPDATE $embarq SET sucEmbFK='".$_POST['sucursal']."', transpEmb='".$_POST['Transporte']."', prodEmbFK='".$_POST['producto']."', referencia='".$_POST['empaque']."', diaEmb='".$_POST['DateEmb']."', observaEmb='".$_POST['Observa']."' WHERE idEmbarque=".$_GET['edit']);

		if(!$SQL){
		 	//Mandar el mensaje de error
			echo $MySQLiconn->error;
		}
		else header("Location: Logistica_Embarques.php");/* else{
		//Mandamos un mensaje de exito:
		echo"<script>alert('Se ha Modificado Un Embarque')</script>"; 
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_Embarques.php'>";	 
	 }	 */
	} 
}
/* Fin Código Atualizar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////


if (isset($_GET['cdgEmb'])){
	$sql=$MySQLiconn->query("SELECT * from embarque where idEmbarque='".$_GET['id']."' && numEmbarque='".$_GET['cdgEmb']."'");

	$rows=$sql->fetch_array();
	if($rows['producto']=='' && $rows['cantidad']=='0.000' && $rows['registrado']=='0000-00-00'){
    	echo"<script>alert('Necesitas confirmar los datos en Surtido de Confirmaciones');
    	var varia = window.location.pathname; window.location=varia;</script>"; 
	}
	else{
		header("Location: Logistica_ArmadoEmbarque.php?cdgEmb=".$_GET['cdgEmb']."&id=".$_GET['id']."&emp=".$_GET['emp']."");	
	}
}



if (isset($_GET['abrir'])){
	$usuarioF=$_SESSION['usuario'];
	$MySQLiconn->query("UPDATE $embarq SET cerrar='0' WHERE numEmbarque='".$_GET['abrir']."' && idEmbarque='".$_GET['id']."'" );

	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Abrio el embarque: ".$_GET['abrir']."','Embarque','Logistica',NOW())");
	
	header("Location: Logistica_Embarques.php");	
}


?>