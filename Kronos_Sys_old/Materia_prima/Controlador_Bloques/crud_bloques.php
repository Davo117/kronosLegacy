<?php

include ('db_materiaPrima.php');
$usuarioF=$_SESSION['usuario'];

/* Inicio Código Insertar */
//Si se dio clic en guardar:

if(isset($_POST['save']))
{
	$SQL =$MySQLiconn->query("SELECT identificadorBloque from bloquesmateriaprima where identificadorBloque='".$_POST['identificadorBloque']."'");
$row=$SQL->fetch_array();
if(empty($row['identificadorBloque']))
{
	//Pasamos los parametros por medio de post
	$nombreBloque= $MySQLiconn->real_escape_string($_POST['nombreBloque']);
	$identificadorBloque =$MySQLiconn->real_escape_string($_POST['identificadorBloque']);   
	$sustrato = $MySQLiconn->real_escape_string($_POST['comboSustratos']);
	
	//Realizamos la consulta
	 $SQL =$MySQLiconn->query("INSERT INTO bloquesmateriaprima(nombreBloque,identificadorBloque,sustrato) VALUES('$nombreBloque','$identificadorBloque','$sustrato')");
	 
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
		
	 } else{
	 //Mandamos un mensaje de exito:
	 	$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego el bloque:  $sustrato','Bloques','Materia Prima',NOW())");
	 echo"<script>alert('Se ha Agregado un nuevo Bloque')</script>";
	 }
	}
	 else
{
	 echo"<script>alert('Bloque no agregado,ya existe en la base de datos')</script>";
}
}

/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("SELECT sustrato FROM bloquesmateriaprima WHERE idBloque=".$_GET['del']);
	$get = $SQL->fetch_array();
	$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo el bloque: ".$get['sustrato']."','Bloques','Materia Prima',NOW())");

	$SQL = $MySQLiconn->query("UPDATE bloquesmateriaprima SET baja=0 WHERE idBloque=".$_GET['del']);
	header("Location: MateriaPrima_Bloques.php");
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Dado de Baja Exitosamente')</script>";
}
/* Fin Código Eliminación Logíca  */



/* Inicio Código Atualizar  */
if(isset($_GET['edit']))
{
	$SQL = $MySQLiconn->query("SELECT * FROM bloquesmateriaprima WHERE idBloque=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
}

if(isset($_POST['update']))
{
	$sustratoPOST=$_POST['comboSustratos'];

$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo el bloque:  $sustratoPOST','Consumos','Materia Prima',NOW())");

	$SQL = $MySQLiconn->query("UPDATE bloquesmateriaprima SET identificadorBloque='".$_POST['identificadorBloque']."', nombreBloque='".$_POST['nombreBloque']."', sustrato='".$_POST['comboSustratos']."' WHERE idBloque=".$_GET['edit']);

	 
}
if(isset($_GET['lot']))
{
	$MySQLiconn->query("UPDATE cache set dato='".$_GET['lot']."' where id=5");
	$_SESSION['lote']= $_GET['lot'];
	$_SESSION['tarima']="";
	header("Location: MateriaPrima_Lotes.php");
}
if(isset($_GET['repor']))
{
	$_SESSION['descripcionBanda']= $_GET['ban'];
	header("Location: Producto_BandasSPP.php");
}
if(isset($_GET['excel']))
{
 	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Cargo datos al bloque: ".$_GET['excel']."','Bloques','Materia Prima',NOW())");
	$_SESSION['desBloque']= $_GET['excel'];

	header("Location: ../importar.php");
}
if(isset($_GET['etiqu']))
{
	$_SESSION['descripcionBanda']= $_GET['ban'];
	header("Location: Producto_BandasSPP.php");
}
if(isset($_GET['acti']))
{
$SQL=$MySQLiconn->query("SELECT sustrato FROM bloquesmateriaprima  WHERE idBloque=".$_GET['acti']);
	$get = $SQL->fetch_array();
 	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo el bloque: ".$get['sustrato']."','Bloques','Materia Prima',NOW())");

	$MySQLiconn->query("UPDATE bloquesmateriaprima set baja=1 where idBloque=".$_GET['acti']."");
	header("Location: MateriaPrima_Bloques.php");
}
if(isset($_GET['delfin']))
{

	$SQL = $MySQLiconn->query("SELECT sustrato FROM bloquesmateriaprima WHERE idBloque=".$_GET['delfin']);
$get = $SQL->fetch_array();
$SQL =$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino el bloque: ".$get['sustrato']."','Bloques','Materia Prima',NOW())");
	$MySQLiconn->query("DELETE from bloquesmateriaprima where idBloque=".$_GET['delfin']."");
	header("Location: MateriaPrima_Bloques.php");
}
if(isset($_GET['etiq']))
{
	$_SESSION['lotesPBloqueImprimir']=$_GET['etiq'];
	header("Location:generarEtiquetasPBloque.php");
}
if(isset($_GET['reporte']))
{
	$_SESSION['reportelotesPBloqueImprimir']=$_GET['reporte'];
	header("Location:generarReporteLotesPBloque.php");
}
