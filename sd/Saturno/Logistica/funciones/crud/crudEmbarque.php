<?php
include("../Database/db.php");


/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{


/* sucursal producto  empaque  Transporte Referencia DateEmb  Observa */
	//Pasamos los parametros por medio de post
	$sucfk =$MySQLiconn->real_escape_string($_POST['sucursal']);   
	$prodfk =$MySQLiconn->real_escape_string($_POST['producto']);   
	$empfk =$MySQLiconn->real_escape_string($_POST['empaque']); 
	$transporte = $MySQLiconn->real_escape_string($_POST['transporte']);
	$referencia = $MySQLiconn->real_escape_string($_POST['referencia']);
	$diaEmbarque = $MySQLiconn->real_escape_string($_POST['dateEmbar']);
	$observa = $MySQLiconn->real_escape_string($_POST['observacion']);
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
	$SQL =$MySQLiconn->query("INSERT INTO $embarq (numEmbarque, transpEmb, referencia, diaEmb, observaEmb, sucEmbFK, prodEmbFK, empaque) VALUES('$secuencia','$transporte','$referencia','$diaEmbarque','$observa','$sucfk','$prodfk','$empfk')");

		 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Añadido un nuevo Embarque: ')</script>";
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
	 } else{
	 //Mandamos un mensaje de exito:
	echo"<script>alert('Se ha Reactivado Un Embarque')</script>";
	echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_Embarques.php?mostrar'>";
	 }
}
/* Fin Código Activar */


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
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Eliminado un Embarque desactivada')</script>";
	 echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_Embarques.php?mostrar'>";
	 }
} /* Fin Código Eliminación Definitiva */
//////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////
 /* Inicio Código Atualizar  */
if(isset($_GET['edit']))
{



	//idEmbarque	transpEmb	referencia	diaEmb	observaEmb	bajaEmb	sucEmbFK	prodEmbFK	empaque

	$SQL = $MySQLiconn->query("SELECT * FROM $embarq WHERE idEmbarque=".$_GET['edit']);
	$getROW = $SQL->fetch_array();

}

if(isset($_POST['update']))
{
/* sucursal producto  empaque  Transporte Referencia DateEmb  Observa */
	if ($_POST['sucursal']=="" or $_POST['producto']=="" or $_POST['empaque']=="" or $_POST['Transporte']=="" or $_POST['DateEmb']=="" ){
	echo "<script>alert('Necesita llenar el campo vacío')</script>";
}
else{
	$SQL = $MySQLiconn->query("UPDATE $embarq SET transpEmb='".$_POST['Transporte']."', referencia='".$_POST['Referencia']."', diaEmb='".$_POST['DateEmb']."', observaEmb='".$_POST['Observa']."' WHERE idEmbarque=".$_GET['edit']);
			
 	
if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	echo"<script>alert('Se ha Modificado Una Sucursal')</script>"; 
echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_Sucursales.php'>";	 
	 }	 
} }
/* Fin Código Atualizar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
