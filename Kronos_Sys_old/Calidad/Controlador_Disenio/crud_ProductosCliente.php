<?php

include_once 'db_Producto.php';

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post
	$identificadorCliente = $MySQLiconn->real_escape_string($_POST['identificadorCliente']);
	$nombre =$MySQLiconn->real_escape_string($_POST['nombre']);   
	
	//Realizamos la consulta
	 $SQL =$MySQLiconn->query("INSERT INTO productoscliente(identificadorCliente,nombre) VALUES('$identificadorCliente','$nombre')");
	 
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
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE productoscliente SET baja=0 WHERE IdProdCliente=".$_GET['del']);
	header("Location: Producto_productoscliente.php");
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
	$SQL = $MySQLiconn->query("SELECT * FROM productoscliente WHERE IdProdCliente=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
} 

if(isset($_POST['update']))
{
	$SQL = $MySQLiconn->query("UPDATE productoscliente SET identificadorCliente='".$_POST['IdentificadorCliente']."', nombre='".$_POST['nombre']."' WHERE idProdCliente=".$_GET['edit']);

	header("Location: Producto_productoscliente.php");
	 
}

/* Fin Código Atualizar */
