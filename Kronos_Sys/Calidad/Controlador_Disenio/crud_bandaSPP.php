<?php

include_once 'db_Producto.php';

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post
	$identificadorBS=$MySQLiconn->real_escape_string($_SESSION['descripcionBanda']);
	$nombreBSPP= $MySQLiconn->real_escape_string($_POST['nombreBSPP']);
	$identificadorBSPP =$MySQLiconn->real_escape_string($_POST['identificadorBSPP']);   
	$anchuraLaminado = $MySQLiconn->real_escape_string($_POST['anchuraLaminado']);
	$sustrato = $MySQLiconn->real_escape_string($_POST['comboSustratos']);
	
	//Realizamos la consulta
	 $SQL =$MySQLiconn->query("INSERT INTO bandaspp(identificadorBS,nombreBSPP,identificadorBSPP,anchuraLaminado,sustrato) VALUES('$identificadorBS','$nombreBSPP','$identificadorBSPP','$anchuraLaminado','$sustrato')");
	 
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Agregado una nueva banda de seguridad por proceso')</script>";
	 }
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE bandaspp	SET baja=0 WHERE idBSPP=".$_GET['del']);
	header("Location: Producto_BandasSPP.php");
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
	echo $_GET['edit'];
	$SQL = $MySQLiconn->query("SELECT * FROM bandaspp WHERE IdBSPP=".$_GET['edit']);

	//select*from bandaspp where idBSPP=1
	$getROW = $SQL->fetch_array();
}

if(isset($_POST['update']))
{
	$SQL = $MySQLiconn->query("UPDATE bandaspp SET identificadorBSPP='".$_POST['identificadorBSPP']."', nombreBSPP='".$_POST['nombreBSPP']."', anchuraLaminado=".$_POST['anchuraLaminado'].",sustrato='".$_POST['comboSustratos']."' WHERE IdBSPP=".$_GET['edit']);

	header("Location: Producto_BandasSPP.php");
	 //Sino modifica el error esta en la sintaxis
}

if(isset($_GET['imp']))
{
	$_SESSION['descripcion']= $_GET['imp'];
	header("Location: Producto_Impresiones.php");
}
if(isset($_GET['cons']))
{
	$_SESSION['descripcionCons']=$_GET['cons'];
	header("Location: Producto_Consumos.php");
}

/* Fin Código Atualizar */
