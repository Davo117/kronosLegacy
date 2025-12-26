<?php
include 'db_materiaPrima.php';
include '../Database/SQLConnection.php';
$usuarioF=$_SESSION['usuario'];
if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post
	$identificadorElemento=$MySQLiconn->real_escape_string($_POST['identificadorElemento']);
	$nombreElemento= $MySQLiconn->real_escape_string($_POST['nombreElemento']);  
	$unidad = $MySQLiconn->real_escape_string($_POST['comboUnidades']);
	$imagen=$_POST['foto_field'];
	move_uploaded_file($_FILES['foto_field']['tmp_name'],'../imagenes/'.$FILES['foto_field']['name']);
	chmod('../imagenes/'.$FILES['foto_field']['name'],0644);
	
	//Realizamos la consulta
	 $SQL =$MySQLiconn->query("INSERT INTO elementosconsumo(identificadorElemento,nombreElemento,unidad) VALUES('$identificadorElemento','$nombreElemento','$unidad','".$imagen."')");
	 
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 	$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego el elemento:  $identificadorElemento','Elementos','Materia Prima',NOW())");
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Agregado un nuevo elemento ')</script>";
	 }
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
$SQL = $MySQLiconn->query("SELECT identificadorElemento FROM elementosconsumo WHERE idElemento=".$_GET['del']);
$get = $SQL->fetch_array();
$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo el elemento: ".$get['identificadorElemento']."','Elementos','Materia Prima',NOW())");

	$SQL = $MySQLiconn->query("UPDATE elementosconsumo	SET baja=0 WHERE idElemento=".$_GET['del']);
	header("Location: MateriaPrima_Elementos.php");
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Dado de Baja Exitosamente')</script>";
}
/* Fin Código Eliminación Logíca  */


/* Inicio Código Atualizar  */
if(isset($_GET['edit']))
{
	$SQL = $MySQLiconn->query("SELECT * FROM elementosconsumo WHERE idElemento=".$_GET['edit']);

	//select*from bandaspp where idBSPP=1
	$getROW = $SQL->fetch_array();
}

if(isset($_POST['update']))
{
	/*chmod('../../imagenes/'.$FILES['foto_field']['name'],0644);
move_uploaded_file($_FILES['foto_field']['tmp_name'],'../../imagenes/'.$FILES['foto_field']['name']);*/
//No he podido hacer lo de la imagen we :'v'
	$archivo = (isset($_FILES['foto_field'])) ? $_FILES['foto_field'] : null;
if ($archivo) {
   $ruta_destino_archivo = "../../imagenes/{$archivo['name']}";
   $archivo_ok = move_uploaded_file($archivo['tmp_name'], $ruta_destino_archivo);
}

if($archivo_ok)
{
	$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo el elemeto:  $porPost','Elementos','Materia Prima',NOW())");


$SQL = $MySQLiconn->query("UPDATE elementosconsumo SET identificadorElemento='".$_POST['identificadorElemento']."', nombreElemento='".$_POST['nombreElemento']."', unidad='".$_POST['comboUnidades']."', foto='".$_POST['foto_field']."' WHERE idElemento=".$_GET['edit']);

	header("Location: MateriaPrima_Elementos.php");
}
else
{
	header("Location: MateriaPima_Elementos.php");
}

	 //Sino modifica el error esta en la sintaxis
}
if(isset($_GET['acti']))
{


$SQL = $MySQLiconn->query("SELECT identificadorElemento FROM elementosconsumo WHERE idElemento=".$_GET['acti']);
$get = $SQL->fetch_array();
$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo el elemento: ".$get['identificadorElemento']."','Elementos','Materia Prima',NOW())");

	$MySQLiconn->query("UPDATE elementosconsumo set baja=1 where idElemento=".$_GET['acti']."");
	header("Location: MateriaPrima_Elementos.php");
}

if(isset($_GET['delfin'])){
	$SQL = $MySQLiconn->query("SELECT identificadorElemento FROM elementosconsumo WHERE idElemento=".$_GET['delfin']);
	$get=$SQL->fetch_array();
	$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino el elemento: ".$get['identificadorElemento']."','Elementos','Materia Prima',NOW())");
	$MySQLiconn->query("DELETE from elementosconsumo where idElemento=".$_GET['delfin']."");
	header("Location: MateriaPrima_Elementos.php");
}