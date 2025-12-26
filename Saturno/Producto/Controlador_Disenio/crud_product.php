<?php

include_once 'db_Producto.php';

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post
	$codigo = $MySQLiconn->real_escape_string($_POST['codigo']);
	$descripcion =$MySQLiconn->real_escape_string($_POST['descripcion']);   
	$tipo = $MySQLiconn->real_escape_string($_POST['tipocombo']);
	$SQL;
	$result=$MySQLiconn->query("SELECT*from producto where codigo=".$codigo."");
	$rau = $result->fetch_array();
	if(empty($rau['codigo'])){
	//Realizamos la consulta
	 $SQL =$MySQLiconn->query("INSERT INTO producto(codigo,descripcion, tipo) VALUES('$codigo','$descripcion','$tipo')");
	 
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Agregado un nuevo producto')</script>";
	 }
	}
	 else
	 {
	 	 echo"<script>alert('El codigo que esta intentando ingresar ya esta registrado')</script>";
	 }
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE producto	SET baja=0 WHERE id=".$_GET['del']);
	header("Location: Producto_Disenio.php");
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Dado de Baja Exitosamente')</script>";
}
/* Fin Código Eliminación Logíca  */

/* Inicio Código Eliminación Definitiva 
if(isset($_GET['del']))
{ //Cambiar el parametro "del" para que no haya conflictos:
	$SQL = $MySQLiconn->query("DELETE FROM empleado WHERE id=".$_GET['del']);
	header("Location: index.php");
}
 Fin Código Eliminación Definitiva */



/* Inicio Código Atualizar  */
if(isset($_GET['edit']))
{
	$SQL = $MySQLiconn->query("SELECT * FROM producto WHERE id=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
}

if(isset($_POST['update']))
{
	$SQL = $MySQLiconn->query("UPDATE producto SET codigo='".$_POST['codigo']."', descripcion='".$_POST['descripcion']."', tipo='".$_POST['tipocombo']."' WHERE id=".$_GET['edit']);

	header("Location: Producto_Disenio.php");
	 
}

if(isset($_GET['imp']))
{
	$SQL=$MySQLiconn->query("INSERT INTO cache(dato) VALUES ('".$_GET['imp']."')");
	header("Location: Producto_Impresiones.php");
}
if(isset($_GET['cons']))
{
	$_SESSION['descripcionCons']=$_GET['cons'];
	header("Location: Producto_consumos.php");
}
if(isset($_GET['acti']))
{
	$MySQLiconn->query("UPDATE producto set baja=1 where id='".$_GET['acti']."'");
	header("Location: Producto_Disenio_bajas.php");
}
if(isset($_GET['delfin']))
{
	$MySQLiconn->query("DELETE from producto where id='".$_GET['delfin']."'");
	header("Location: Producto_Disenio_bajas.php");
}
/* Fin Código Atualizar */
