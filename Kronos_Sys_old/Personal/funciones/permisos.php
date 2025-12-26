<?php 
include("../Database/db.php");
	$perfil=$_SESSION['perfil'];
	$codigo=$_SESSION['codigoPermiso'];
	
	$selector1= $MySQLiconn->query("SELECT * FROM accesos WHERE perfil='$perfil' and cdgmodulo='$codigo'");
	$getROW1 = $selector1->fetch_array();


if ($getROW1['permiso']=='rw-'or $getROW1['permiso']=='r--' or $getROW1['permiso']=='---'){
	if(isset($_GET['eli'])){
		echo "<script>alert('No tienes los permisos necesarios para ELIMINAR.')</script>";
		echo ("<script> var varia = window.location.pathname;
		window.location=varia;</script>");
		exit;
	}
	if(isset($_GET['del'])){
		echo "<script>alert('No tienes los permisos necesarios para DESACTIVAR.')</script>";
		echo ("<script> var varia = window.location.pathname;
		window.location=varia;</script>");
		exit;
	}
	if(isset($_GET['eliminarDepa'])){
		echo "<script>alert('No tienes los permisos necesarios para ELIMINAR.')</script>";
		echo ("<script> var varia = window.location.pathname;
		window.location=varia;</script>");
		exit;
	}
	if(isset($_GET['activar'])){
		echo "<script>alert('No tienes los permisos necesarios para ACTIVAR.')</script>";
		echo ("<script> var varia = window.location.pathname;
		window.location=varia;</script>");
		exit;
	}
}
if ($getROW1['permiso']=='r--' or $getROW1['permiso']=='---'){
	if(isset($_POST['save'])){
		echo "<script>alert('No tienes los permisos necesarios para GUARDAR.')</script>";
		echo ("<script> var varia = window.location.pathname;
		window.location=varia;</script>");
		exit;
	}
	if(isset($_GET['edit'])){
		echo "<script>alert('No tienes los permisos necesarios para EDITAR.')</script>";
		echo ("<script> var varia = window.location.pathname;
		window.location=varia;</script>");
		exit;
	}
	if(isset($_POST['guardar'])){
		echo "<script>alert('No tienes los permisos necesarios para GUARDAR.')</script>";
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