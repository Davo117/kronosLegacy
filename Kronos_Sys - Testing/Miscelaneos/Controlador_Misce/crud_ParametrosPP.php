<?php
include_once 'db_Producto.php';
/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post
	
	//Realizamos la consulta
	$tipo=$MySQLiconn->real_escape_string($_POST['comboTipos']);
	 $SIC =$MySQLiconn->query("SELECT juegoparametros from tipoproducto where tipo='$tipo'");
	 $rew=$SIC->fetch_array();
	 $nombre=$MySQLiconn->real_escape_string($_POST['parametros']);
	 $leyenda=$MySQLiconn->real_escape_string($_POST['leyenda']);
	 $requerido=$MySQLiconn->real_escape_string($_POST['requerido']);
	 $placeholder=$MySQLiconn->real_escape_string($_POST['placeholder']);

	 		$SQL =$MySQLiconn->query("INSERT INTO juegoparametros(identificadorJuego,nombreParametro,requerido,leyenda,placeholder)VALUES('".$rew['juegoparametros']."','$nombre','$requerido','$leyenda','$placeholder')");

	 	
	 	
	 
	 	header("Location:Misce_Parametros.php");
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Agregado una nuevo proceso')</script>";
	 }
	}

/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("DELETE from juegoparametros WHERE id=".$_GET['del']);
	 //Mandamos un mensaje de exito:
	header("Location: Misce_Parametros.php");
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
	$SQL = $MySQLiconn->query("SELECT * FROM procesos WHERE id=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
}

	if(isset($_POST['update']))
{
$SQL = $MySQLiconn->query("UPDATE procesos set descripcionProceso='".$_POST['descripcionProceso']."', abreviacion='".$_POST['abreviacion']."' WHERE id=".$_GET['edit']);
}

	
	 

if(isset($_GET['param']))
{
	header("Location: Misce_Parametros.php");
}
if(isset($_GET['proces']))
{
	//$_SESSION['descripcionCil']=$_GET['cil'];
	header("Location: Misce_Procesos.php");
	 //echo "<META HTTP-EQUIV='REFRESH' CONTENT='0; URL=Producto_juegoscilindros.php'>";
}
if(isset($_GET['acti']))
{
	$MySQLiconn->query("UPDATE impresiones set baja=1 where id=".$_GET['acti']."");
	header("Location: Producto_Impresiones_bajas.php");
}
if(isset($_GET['delfin']))
{
	$MySQLiconn->query("DELETE from  impresiones where id=".$_GET['delfin']."");
	header("Location: Producto_Impresiones_bajas.php");
}
?>