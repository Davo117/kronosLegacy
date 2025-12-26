<?php

include_once 'db_Producto.php';

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_GET['save']))
{
	//Pasamos los parametros por medio de post
	$identificadorBS=$MySQLiconn->real_escape_string($_GET['comboBSPP']);
	$nombreBSPP= $MySQLiconn->real_escape_string($_GET['nombreBSPP']);
	$identificadorBSPP =$MySQLiconn->real_escape_string($_GET['identificadorBSPP']);   
	$repeticiones = $MySQLiconn->real_escape_string($_GET['repeticiones']);
	$sustrato = $MySQLiconn->real_escape_string($_GET['comboSustratos']);
	
	//Realizamos la consulta
	if($anchuraLaminado>0)
	{
		$SQL =$MySQLiconn->query("INSERT INTO bandaspp(identificadorBS,nombreBSPP,identificadorBSPP,repeticiones,sustrato) VALUES('$identificadorBS','$nombreBSPP','$identificadorBSPP','$repeticiones','$sustrato')");

	 //En caso de ser diferente la consulta:
		if(!$SQL)
		{
	 	//Mandar el mensaje de error
			echo $MySQLiconn->error;
			echo "<div class='alert alert-danger alert-dismissible fade in'>
			<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			<strong>Error</strong> ,ocurrió un problema al registrar,inténtelo mas tarde
			</div>";
		} else{
	 //Mandamos un mensaje de exito:
			/*echo "<div class='alert alert-success alert-dismissible fade in'>
			<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			<strong>Operación exitosa</strong> ,agregado correctamente
			</div>";*/
			echo "<script>window.location=Producto_BandasSPP.php;</script>";
		}
	}
	else
	{
		echo"<script>alert('La anchura no puede ser cero')</script>";
	}
	
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE bandaspp	SET baja=0 WHERE idBSPP=".$_GET['del']);
	 //Mandamos un mensaje de exito:
	echo "<div class='alert alert-success alert-dismissible fade in'>
	<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
	<strong>Operación exitosa</strong> ,eliminado correctamente
	</div>
	";
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
	$SQL = $MySQLiconn->query("SELECT * FROM bandaspp WHERE IdBSPP=".$_GET['edit']);

	//select*from bandaspp where idBSPP=1
	$getROW = $SQL->fetch_array();
}

if(isset($_GET['update']))
{
	$SQL = $MySQLiconn->query("UPDATE bandaspp SET identificadorBSPP='".$_GET['identificadorBSPP']."', nombreBSPP='".$_GET['nombreBSPP']."', repeticiones=".$_GET['repeticiones'].",sustrato='".$_GET['comboSustratos']."' WHERE IdBSPP=".$_GET['editado']);
	echo "<script>window.location=Producto_BandasSPP.php;</script>";

	if(!$SQL)
	{
		echo "<div class='alert alert-danger alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Error</strong> ,ocurrió un problema al modificar,intentelo mas tarde
		</div>";
	}
	else
	{
		echo "<div class='alert alert-success alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Operación exitosa</strong> ,modificado correctamente
		</div>";
	}
}


if(isset($_GET['acti']))
{
	$SQL=$MySQLiconn->query("UPDATE bandaspp set baja=1 where IDBSPP=".$_GET['acti']."");
	echo "<script>window.location=Producto_BandasSPP_bajas.php;</script>";
	if(!$SQL)
	{
		echo "<div class='alert alert-success alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Error</strong> , Hubo un problema al dar de alta
		</div>
		";
	}
	else
	{
		echo "<div class='alert alert-success alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Operación exitosa</strong> , Se dió correctamente de alta
		</div>
		";
	}
}
if(isset($_GET['delfin']))
{
	$SQL=$MySQLiconn->query("DELETE from bandaspp where IDBSPP=".$_GET['delfin']."");
	echo "<script>window.location=Producto_BandasSPP_bajas.php;</script>";
	if(!$SQL)
	{
		echo "<div class='alert alert-danger alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Error</strong> , Hubo un problema eliminar
		</div>
		";
	}
	else
	{
		echo "<div class='alert alert-success alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Operación exitosa</strong> , Eliminado correctamente
		</div>
		";
	}
}

/* Fin Código Atualizar */
