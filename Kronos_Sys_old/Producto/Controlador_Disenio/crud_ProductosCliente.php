<?php

include_once 'db_Producto.php';

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post
	$identificadorCliente = $MySQLiconn->real_escape_string($_POST['IdentificadorCliente']);
	$nombre =$MySQLiconn->real_escape_string($_POST['nombre']);   
	
	//Realizamos la consulta
	 $SQL =$MySQLiconn->query("INSERT INTO productoscliente(identificadorCliente,nombre) VALUES('$identificadorCliente','$nombre')");
	 
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo "<div class='alert alert-success alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Operación exitosa</strong> , se agregó un nuevo producto cliente.
		</div>
		";
	 }
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE productoscliente SET baja=0 WHERE IdProdCliente=".$_GET['del']);
	header("Location: Producto_ProductosCliente.php");
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Dado de Baja Exitosamente')</script>";
}

/* Inicio Código Atualizar  */
if(isset($_GET['edit']))
{
	$SQL = $MySQLiconn->query("SELECT * FROM productoscliente WHERE IdProdCliente=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
} 

if(isset($_POST['update']))
{
	$SQL = $MySQLiconn->query("UPDATE productoscliente SET IdentificadorCliente='".$_POST['IdentificadorCliente']."', nombre='".$_POST['nombre']."' WHERE IdProdCliente=".$_GET['edit']);

	header("Location: Producto_ProductosCliente.php");
	 
}
if(isset($_GET['acti']))
{
	$MySQLiconn->query("UPDATE productoscliente set baja=1 where IdProdCliente=".$_GET['acti']."");
	header("Location: Producto_ProductosCliente.php");
}
if(isset($_GET['delfin']))
{
	$SQL=$MySQLiconn->query("DELETE from productoscliente where idProdCliente=".$_GET['delfin']."");
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


/* Fin Código Actualizar */
