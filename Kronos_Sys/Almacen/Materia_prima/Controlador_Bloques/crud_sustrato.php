<?php

include_once 'db_materiaPrima.php';
$usuarioF=$_SESSION['usuario'];
/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save'])){
	//Pasamos los parametros por medio de post
	$codigoSustrato = $MySQLiconn->real_escape_string($_POST['codigoSustrato']);
	$descripcionSustrato =$MySQLiconn->real_escape_string($_POST['descripcionSustrato']);   
	$rendimiento = $MySQLiconn->real_escape_string($_POST['rendimiento']);
	$anchura = $MySQLiconn->real_escape_string($_POST['anchura']);
	if($anchura>0)
	{
		 $SQL =$MySQLiconn->query("INSERT INTO sustrato(codigoSustrato,descripcionSustrato, rendimiento,anchura) VALUES('$codigoSustrato','$descripcionSustrato','$rendimiento','$anchura')");
	 
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 	$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego el sustrato:  $codigoSustrato','Sustrato','Materia Prima',NOW())");
	 //Mandamos un mensaje de exito:
	 echo "<div class='alert alert-success alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Operación exitosa</strong> ,agregado correctamente
		</div>";
	 }
	}
 	else
 	{
 		 echo "<div class='alert alert-warning alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Datos inválidos</strong> , Valor de anchura incorrecto.
		</div>";
 	}
	//Realizamos la consulta
	
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del'])){	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("SELECT descripcionSustrato FROM sustrato WHERE idSustrato=".$_GET['del']);
$get = $SQL->fetch_array();
$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo el sustrato: ".$get['descripcionSustrato']."','Sustratos','Materia Prima',NOW())");

	$SQL = $MySQLiconn->query("UPDATE sustrato	SET baja=0 WHERE idSustrato=".$_GET['del']);
	header("Location: MateriaPrima_Sustrato.php");
	 
}


/* Inicio Código Atualizar  */
if(isset($_GET['edit']))
{
	$SQL = $MySQLiconn->query("SELECT * FROM sustrato WHERE idSustrato=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
	$sustrato=$getROW['descripcionSustrato'];
	
	//echo "<script>window.location='MateriaPrima_Sustrato.php';</script>";

}

if(isset($_POST['update']))
{


$porPost=$_POST['descripcionSustrato'];

$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo el sustrato:  $porPost','Sustratos','Materia Prima',NOW())");


	
	$SQL = $MySQLiconn->query("UPDATE sustrato SET codigoSustrato='".$_POST['codigoSustrato']."', descripcionSustrato='".$_POST['descripcionSustrato']."', anchura='".$_POST['anchura']."', rendimiento='".$_POST['rendimiento']."' WHERE idSustrato=".$_GET['edit']);
 echo "<script>alert('Se actualizó el sustrato $sustrato');</script>";
	echo "<script>window.location='MateriaPrima_Sustrato.php';</script>";
	 
}
if(isset($_GET['acti']))
{
	$SQL=$MySQLiconn->query("SELECT descripcionSustrato FROM sustrato  WHERE idSustrato=".$_GET['acti']);
	$get = $SQL->fetch_array();
 	$SQL =$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo el sustrato: ".$get['descripcionSustrato']."','Sustratos','Materia Prima',NOW())");
	$MySQLiconn->query("UPDATE sustrato set baja=1 where idSustrato=".$_GET['acti']."");
	//echo "<script>window.location='MateriaPrima_Sustrato.php';</script>";
}
if(isset($_GET['delfin']))
{
$SQL = $MySQLiconn->query("SELECT descripcionSustrato FROM sustrato WHERE idSustrato=".$_GET['delfin']);
$get = $SQL->fetch_array();
$SQL =$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino el sustrato: ".$get['descripcionSustrato']."','Sustratos','Materia Prima',NOW())");
	$MySQLiconn->query("DELETE from sustrato where idSustrato=".$_GET['delfin']."");
	echo "<script>window.location='MateriaPrima_Sustrato.php';</script>";
}
