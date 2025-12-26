<?php

include_once 'db_materiaPrima.php';
$usuarioF=$_SESSION['usuario'];
/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post
	$codigoPantone=trim($MySQLiconn->real_escape_string($_POST['codigoPantone']));
	$descripcionPantone =trim($MySQLiconn->real_escape_string($_POST['descripcionPantone']));
	$codigoHTML=$codigoPantone;

	 $SQL =$MySQLiconn->query("INSERT INTO pantone (descripcionPantone,codigoPantone,codigoHTML) VALUES('$descripcionPantone','$codigoPantone','$codigoHTML')");
	 
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 	$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego el pantone:  $descripcionPantone','Pantones','Materia Prima',NOW())");

	 //Mandamos un mensaje de exito:
	 echo "<div class='alert alert-success alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Operación exitosa</strong> , agregado correctamente
		</div>";
	 }
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del'])){	//Lanzamos la consulta actualizando la baja a 0
$SQL = $MySQLiconn->query("SELECT descripcionPantone FROM pantone WHERE idPantone=".$_GET['del']);
$get = $SQL->fetch_array();
$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo el pantone: ".$get['descripcionPantone']."','Pantones','Materia Prima',NOW())");

	$SQL=$MySQLiconn->query("UPDATE pantone SET baja=0 WHERE idPantone=".$_GET['del']);
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha dado de baja Exitosamente')</script>";
}
/* Fin Código Eliminación Logíca  */




/* Inicio Código Atualizar  */
if(isset($_GET['edit']))
{
	$SQL = $MySQLiconn->query("SELECT * FROM pantone WHERE idPantone=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
}

if(isset($_POST['update']))
{

$porPost=$_POST['descripcionPantone'];

$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo el pantone:  $porPost','Pantones','Materia Prima',NOW())");
	
	$SQL = $MySQLiconn->query("UPDATE pantone SET descripcionPantone='".$_POST['descripcionPantone']."', codigoPantone='".$_POST['codigoPantone']."',codigoHTML='".trim($_POST['codigoPantone'])."' WHERE idPantone=".$_GET['edit']);

	echo "<div class='alert alert-success alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Operación exitosa</strong> , modificado correctamente
		</div>";
	//Me quede aqui,estaba agregando los parametros para poder modificar las impresiones
	 
}
if(isset($_POST['panto']))
{
 echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=https://www.pantone.com/color-finder/".$_SESSION['panto']."";
}
if(isset($_GET['acti']))
{
$SQL=$MySQLiconn->query("SELECT descripcionPantone FROM pantone  WHERE idPantone=".$_GET['acti']);
	$get = $SQL->fetch_array();
 	$SQL =$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo el pantone: ".$get['descripcionPantone']."','Pantones','Materia Prima',NOW())");

	$MySQLiconn->query("UPDATE pantone set baja=1 where idPantone=".$_GET['acti']."");
	header("Location: MateriaPrima_Pantone.php");
}
if(isset($_GET['delfin']))
{
	$SQL = $MySQLiconn->query("SELECT descripcionPantone FROM pantone WHERE idPantone=".$_GET['delfin']);
$get = $SQL->fetch_array();
$SQL =$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino el pantone: ".$get['descripcionPantone']."','Pantones','Materia Prima',NOW())");

	$MySQLiconn->query("DELETE from pantone where idPantone=".$_GET['delfin']."");
	header("Location: MateriaPrima_Pantone.php");
}