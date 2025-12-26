<?php

include_once 'db_Producto.php';

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post
	$descripcionImpresion= $MySQLiconn->real_escape_string($_POST['comboImpresiones']);
	$identificadorCilindro = $MySQLiconn->real_escape_string($_POST['identificadorCilindro']);
	$proveedor=$MySQLiconn->real_escape_string($_POST['proveedor']);
	$fechaRecepcion = $MySQLiconn->real_escape_string($_POST['fechaRecepcion']);
	$diametro= $MySQLiconn->real_escape_string($_POST['diametro']);
	$tabla = $MySQLiconn->real_escape_string($_POST['tabla']);
	$registro = $MySQLiconn->real_escape_string($_POST['registro']);
	$repAlPaso = $MySQLiconn->real_escape_string($_POST['repAlPaso']);
	$repAlGiro = $MySQLiconn->real_escape_string($_POST['repAlGiro']);
	$girosGarantizados = $MySQLiconn->real_escape_string($_POST['girosGarantizados']);
	$presionCilindro = $MySQLiconn->real_escape_string($_POST['presionCilindro']);
	$presionGoma = $MySQLiconn->real_escape_string($_POST['presionGoma']);
	$altura=$MySQLiconn->real_escape_string($_POST['altura']);
	$presionRasqueta = $MySQLiconn->real_escape_string($_POST['presionRasqueta']);
	$tolVelocidad = $MySQLiconn->real_escape_string($_POST['tolVelocidad']);
	$tolViscosidad=$MySQLiconn->real_escape_string($_POST['tolViscosidad']);
	$tolCilindro = $MySQLiconn->real_escape_string($_POST['tolCilindro']);
	$tolTemperatura = $MySQLiconn->real_escape_string($_POST['tolTemperatura']);
	$temperatura = $MySQLiconn->real_escape_string($_POST['temperatura']);
	$tolgoma = $MySQLiconn->real_escape_string($_POST['tolGoma']);
	$tolRasqueta = $MySQLiconn->real_escape_string($_POST['tolRasqueta']);

	 $SQL =$MySQLiconn->query("INSERT INTO juegoscilindros (descripcionImpresion,identificadorCilindro,proveedor,fechaRecepcion,diametro, tabla,registro,repAlPaso,repAlGiro,girosGarantizados,presionCilindro,presionGoma,presionRasqueta,tolVelocidad,tolViscosidad,tolCilindro,tolTemperatura,temperatura,tolgoma,tolRasqueta) VALUES('$descripcionImpresion','$identificadorCilindro','$proveedor','$fechaRecepcion',$diametro,$tabla,$registro,$repAlPaso,$repAlGiro,$girosGarantizados,$presionCilindro,$presionGoma,$presionRasqueta,$tolVelocidad,$tolViscosidad,$tolCilindro,$tolTemperatura,$temperatura,$tolgoma,$tolRasqueta)");
	 
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Agregado un nuevo juego de cilindros')</script>";
	 }
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE juegoscilindros	SET baja=0 WHERE id=".$_GET['del']);
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
	$SQL = $MySQLiconn->query("SELECT * FROM juegoscilindros WHERE IDCilindro=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
}

if(isset($_POST['update']))
{
	$SQL = $MySQLiconn->query("UPDATE juegoscilindros SET  identificadorCilindro='".$_POST['identificadorCilindro']."',proveedor='".$_POST['proveedor']."',fechaRecepcion='".$_POST['fechaRecepcion']."', diametro=".$_POST['diametro'].",tabla=".$_POST['tabla'].",registro=".$_POST['registro'].",repAlPaso=".$_POST['repAlPaso'].",repAlGiro=".$_POST['repAlGiro'].", girosGarantizados=".$_POST['girosGarantizados'].",viscosidad=".$_POST['viscosidad'].",velocidad=".$_POST['velocidad'].",presionCilindro=".$_POST['presionCilindro'].", presionGoma=".$_POST['presionGoma'].",presionRasqueta=".$_POST['presionRasqueta'].", tolVelocidad=".$_POST['tolVelocidad'].", tolCilindro=".$_POST['tolCilindro'].", tolTemperatura=".$_POST['tolTemperatura'].",temperatura=".$_POST['temperatura'].", tolGoma=".$_POST['tolGoma'].", tolRasqueta=".$_POST['tolRasqueta']." WHERE idCilindro=".$_GET['edit']);

	header("Location: Producto_JuegosCilindros.php");
	//Me quede aqui,estaba agregando los parametros para poder modificar las impresiones
	 
}

if(isset($_GET['imp']))
{
	$_SESSION['descripcion']= $_GET['imp'];
	header("Location: Producto_Impresiones.php");
}
if(isset($_GET['cil']))
{
	$_SESSION['descripcionCil']=$_GET['cil'];
	header("Location: Producto_JuegosCilindros.php");
}

/* Fin Código Atualizar */