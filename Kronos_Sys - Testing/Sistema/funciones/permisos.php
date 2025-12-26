<?php 
include("../Database/db.php");
	$perfil=$_SESSION['perfil'];
	$codigo=$_SESSION['codigoPermiso'];
	
	$selector1=$MySQLiconn->query("SELECT * FROM accesos WHERE perfil='$perfil' and cdgmodulo='$codigo'");
	$getROW1 = $selector1->fetch_array();


if ($getROW1['permiso']=='rw-'or $getROW1['permiso']=='r--' or $getROW1['permiso']=='---'){
	if(isset($_GET['delU'])){
		echo "<script>alert('No tienes los permisos necesarios para ELIMINAR.')</script>";
		echo ("<script> var varia = window.location.pathname;
		window.location=varia;</script>");
		exit;
	}
}
if ($getROW1['permiso']=='r--' or $getROW1['permiso']=='---'){
	if(isset($_POST['save']) or isset($_POST['save1']) or isset($_POST['saveUser'])){
		
		if(isset($_POST['save'])){ $valor='ACTUALIZAR tu contraseña'; }

		if(isset($_POST['save1'])){	$valor='GUARDAR privilegios'; }

		if(isset($_POST['saveuSER'])){ $valor='AGREGAR USUARIOS'; }

		echo "<script>alert('No tienes los permisos necesarios para $valor.')</script>";
		echo ("<script> var varia = window.location.pathname;
		window.location=varia;</script>");
		exit;
	}
}
if ($getROW1['permiso']=='---'){
	echo "<script>alert('Modulo Bloqueado para el usuario actual.')</script>";
	echo ("<script>history.back();</script>");
	//echo "<META HTTP-EQUIV='REFRESH' CONTENT='0; URL=../Menu.php'>";
} ?>