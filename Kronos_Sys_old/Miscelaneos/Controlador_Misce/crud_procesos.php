<?php
include_once 'db_Producto.php';
/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post
	
	//Realizamos la consulta
	 $descripcionProceso=$MySQLiconn->real_escape_string($_POST['descripcionProceso']);
	 $abreviacion=$MySQLiconn->real_escape_string($_POST['abreviacion']);
	 $merma_p=$MySQLiconn->real_escape_string($_POST['merma_p']);
	 
	 $cadena_original=$descripcionProceso;
 	$cadena_recortada=substr($cadena_original,0,3);
 	$packParametros="PKP".$cadena_recortada;

	$SQL =$MySQLiconn->query("INSERT INTO procesos(descripcionProceso,abreviacion,packParametros)VALUES('$descripcionProceso','$abreviacion','$packParametros')");
	
	$nameTabla='pro'.$descripcionProceso;
	$SQL2 =$MySQLiconn->query("CREATE TABLE $nameTabla (id int(11) NOT NULL AUTO_INCREMENT, total float NOT NULL, producto varchar(70) NOT NULL, fecha date NOT NULL, PRIMARY KEY (id))
	 ENGINE=InnoDB DEFAULT CHARSET=utf8_unicode_ci;");
	 
	 
	 	header("Location:Misce_ProcesosOnly.php");
	 
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
	$SQL = $MySQLiconn->query("UPDATE tipoproducto SET baja=0 WHERE id=".$_GET['del']);
	 //Mandamos un mensaje de exito:
	header("Location: Misce_Productos.php");
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
$SQL = $MySQLiconn->query("UPDATE procesos set merma_p='".$_POST['merma_p']."' WHERE id=".$_GET['edit']);
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