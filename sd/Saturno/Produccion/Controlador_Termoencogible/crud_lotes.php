<?php

include_once 'db_materiaPrima.php';

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post
	$bloque = $MySQLiconn->real_escape_string($_POST['comboBloquesillos']);
	$referencia = $MySQLiconn->real_escape_string($_POST['referenciaLote']);
	$longitud =$MySQLiconn->real_escape_string($_POST['longitud']);   
	$peso = $MySQLiconn->real_escape_string($_POST['peso']);
	$tarima = $MySQLiconn->real_escape_string($_POST['tarima']);
	
	//Realizamos la consulta
	/* $SQL =$MySQLiconn->query("INSERT INTO impresiones(descripcionDisenio,codigoImpresion,descripcionImpresion, millaresPorPaquete,millaresPorRollo,anchoEtiqueta,anchoPelicula,alturaEtiqueta,nombreBanda,codigoCliente,espacioFusion,tintas,sustrato,porcentajeMPR) VALUES('$tipo','$codigoImpresion','$descripcionImpresion','$millaresPorRollo','$millaresPorPaquete','$anchoPelicula','$anchoEtiqueta','$alturaEtiqueta','$nombreBanda','$codigoCliente','$espacioFusion','$tintas','$sustrato','$porcentajeMPR'");*/
	 $SQL =$MySQLiconn->query("INSERT INTO lotes(bloque,referenciaLote,longitud, peso,tarima ) VALUES('$bloque','$referencia',$longitud,$peso,'$tarima')");
	 $MySQLiconn->query("UPDATE bloquesMateriaPrima set peso=peso+'$peso' where nombreBloque='$bloque'");
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Agregado un nuevo lote')</script>";
	  echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=MateriaPrima_Lotes.php'>";
	 }
}

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE lotes	SET baja=0 WHERE idLote=".$_GET['del']);
	header("Location: MateriaPrima_lotes.php");
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
	$SQL = $MySQLiconn->query("SELECT * FROM lotes WHERE idLote=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
}


/*if(isset($_POST['update']))
{
	$SQL = $MySQLiconn->query("UPDATE impresiones SET codigoImpresion='".$_POST['codigoImpresion']."', descripcionImpresion='".$_POST['descImpresion']."', anchoPelicula='".$_POST['anchoPelicula']."', anchoEtiqueta='".$_POST['anchoEtiqueta']."',millaresPorRollo='".$_POST['millaresPR']."',alturaEtiqueta='".$_POST['alturaEtiqueta']."',espacioFusion='".$_POST['espacioFusion']."',porcentajeMPR='".$_POST['porcentajeMPR']."', descripcionImpresion='".$_POST['descImpresion']."',millaresPorPaquete='".$_POST['millaresPP']."',tintas='".$_POST['numtintas']."',sustrato='".$_POST['sustrato']."', nombreBanda='".$_POST['nombreBanda']."', codigoCliente='".$_POST['codigoCliente']."', WHERE id=".$_GET['edit']);*/

	if(isset($_POST['update']))
{

	$SQL = $MySQLiconn->query("UPDATE lotes SET referenciaLote='".$_POST['referenciaLote']."', longitud='".$_POST['longitud']."', peso='".$_POST['peso']."',tarima='".$_POST['tarima']."' WHERE idLote=".$_GET['edit']."");



	header("Location: MateriaPrima_Lotes.php");
	 
}

if(isset($_GET['imp']))
{
	$_SESSION['descripcion']= $_GET['imp'];
	header("Location: Producto_Impresiones.php");
}
if(isset($_GET['produ']))
{
	$SQL = $MySQLiconn->query("UPDATE lotes	SET estado=1 WHERE idLote=".$_GET['produ']);
	header("Location: MateriaPrima_Lotes.php");
	 //Mandamos un mensaje de exito:
}
if(isset($_GET['repor']))
{
	$SQL = $MySQLiconn->query("UPDATE lotes	SET estado=3 WHERE idLote=".$_GET['repor']);
	header("Location: MateriaPrima_Lotes.php");
	 //Mandamos un mensaje de exito:
}
if(isset($_GET['calen']))
{
	$SQL = $MySQLiconn->query("UPDATE lotes	SET estado=2 WHERE idLote=".$_GET['calen']);
	header("Location: MateriaPrima_Lotes.php");
	 //Mandamos un mensaje de exito:
}

/* Fin Código Atualizar */