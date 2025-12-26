<?php
include_once 'db_Producto.php';
/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save'])){
	//Pasamos los parametros por medio de post
	$identificador=$MySQLiconn->real_escape_string($_POST['example']);
	$proceso=$MySQLiconn->real_escape_string($_POST['SubProceso']);
	$num=$MySQLiconn->real_escape_string($_POST['numeroProceso']);
	$tablero=$MySQLiconn->real_escape_string($_POST['table']);
	$registro=$MySQLiconn->real_escape_string($_POST['register']);
	
	$SQL =$MySQLiconn->query("INSERT INTO juegoprocesos(identificadorJuego, descripcionProceso, numeroProceso, tablero, registro) VALUES('$identificador','$proceso', '$num','$tablero','$registro')");
 	//En caso de ser diferente la consulta:
	if(!$SQL){
		//Mandar el mensaje de error
		echo $MySQLiconn->error;
	} 
	else{
		//Mandamos un mensaje de exito:
		echo"<script>alert('Se ha Agregado una nuevo proceso')</script>";
		echo"<script>window.location:'Misce_Procesos.php';</script>";
	}
}

/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE juegoprocesos SET baja=0 WHERE id=".$_GET['del']);
	 //Mandamos un mensaje de exito:
	header("Location: Misce_Procesos.php");
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
if(isset($_GET['edit'])){
	$SQL = $MySQLiconn->query("SELECT * FROM juegoprocesos WHERE id=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
}

	if(isset($_POST['update'])){
	$SQL = $MySQLiconn->query("UPDATE juegoprocesos set identificadorJuego='".$_POST['example']."', descripcionProceso='".$_POST['SubProceso']."', numeroProceso='".$_POST['numeroProceso']."', tablero='".$_POST['table']."', registro='".$_POST['register']."' WHERE id=".$_GET['edit']);

if(!$SQL)
	{
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	} 
	else
	{
	 	//Mandamos un mensaje de exito:
		echo"<script>alert('Se ha Modificado Un Proceso')</script>"; 
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Misce_Procesos.php'>";	 
	}	 
}



	 
if(isset($_GET['parameter'])){
	$_SESSION['productoProd']=$_GET['parameter'];	
	$_SESSION['procesoProd']=$_GET['processo'];
	echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Misce_ParametrosRegistros.php'>";	
	//header("Location: Misce_ParametrosRegistros.php");
}
if(isset($_GET['acti'])){
	$MySQLiconn->query("UPDATE juegoprocesos set baja=1 where id=".$_GET['acti']."");
	header("Location:Misce_Procesos.php");
}

if(isset($_GET['delfin'])){
	$MySQLiconn->query("DELETE from  juegoprocesos where id=".$_GET['delfin']."");
	header("Location:Misce_Procesos.php");
}


//////////////////////////////////////////////////////////////////////
//PARTE DEL MODULO PARA AGREGAR PROCESOS A UN NUEVO PRODUCTO() 

if(isset($_POST['saveProcess'])){
	$id=$MySQLiconn->real_escape_string($_POST['process']);
	$isCut=0;
	if(!empty($_POST['number'])&& is_array($_POST["number"])){
		$i=0;
		foreach ( $_POST["number"] as $procesoA[$i] ) {
        	$i++;
    	}
		$count=$MySQLiconn->query("SELECT referenciaProceso from juegoprocesos where identificadorJuego='$id'and numeroProceso!='0' and descripcionProceso='' and baja='1'");

		$j=0;
		while($row=$count->fetch_array()) {
if(!empty($procesoA[$j]))
{
	if($procesoA[$j]=="corte")
	{
		$isCut=1;
	}
	$MySQLiconn->query("UPDATE juegoprocesos set descripcionProceso='".$procesoA[$j]."' where identificadorJuego='$id' and numeroProceso!='0' and baja='1' and descripcionProceso='' and referenciaProceso='".$row['referenciaProceso']."'");
		
}
				$j++;
		}
		$SQL=$MySQLiconn->query("SELECT descripcionProceso,id from juegoprocesos where identificadorJuego='$id'and numeroProceso!='0'");
		echo"<script>alert('Procesos agregados')</script>";
		$banderin=0;
		while($rem=$SQL->fetch_array())
		{
			if(empty($rem['descripcionProceso']))
			{
				$banderin=1;
				$MySQLiconn->query("DELETE FROM juegoprocesos where id=".$rem['id']."");
			}
		}
		if($isCut==0)
		{
			$MySQLiconn->query("DELETE FROM juegoprocesos WHERE descripcionProceso='caja' and identificadorJuego='$id'");
		}
		if($banderin==1)
		{
			echo"<script>alert('Hay campos de proceso vacíos,se eliminarán automaticamente')</script>";
			echo "<script>window.location='Misce_Procesos.php';</script>";
		}
		else
		{
			echo"<script>alert('El modelo de procesos esta listo para utilizar')</script>";
			echo "<script>window.location='Misce_Procesos.php';</script>";
		}


		
	}
} ?>