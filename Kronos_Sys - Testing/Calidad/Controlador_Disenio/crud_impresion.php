<?php
include_once 'db_Producto.php';
/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post
	
	//Realizamos la consulta
	 if(empty($_POST['numTintas']) && empty($_POST['espacioFusion']))
	 	{
	 		$productoscliente=$MySQLiconn->real_escape_string($_POST['comboPCliente']);
	 		$bspp=$MySQLiconn->real_escape_string($_POST['comboBSPP']);
	 		$sustratos=$MySQLiconn->real_escape_string($_POST['comboSustratos']);
	 		$tipo = $MySQLiconn->real_escape_string($_POST['comboDisenios']);
	$codigoImpresion = $MySQLiconn->real_escape_string($_POST['codigoImpresion']);
	$descripcionImpresion =$MySQLiconn->real_escape_string($_POST['descImpresion']);   
	$millaresPorRollo = $MySQLiconn->real_escape_string($_POST['millaresPR']);
	$millaresPorPaquete = $MySQLiconn->real_escape_string($_POST['millaresPP']);
	$anchoPelicula = $MySQLiconn->real_escape_string($_POST['anchoPelicula']);
	$anchoEtiqueta = $MySQLiconn->real_escape_string($_POST['anchoEtiqueta']);
	$alturaEtiqueta = $MySQLiconn->real_escape_string($_POST['alturaEtiqueta']);
	$porcentajeMPR = $MySQLiconn->real_escape_string($_POST['porcentajeMPR']);
	 		$SQL =$MySQLiconn->query("INSERT INTO impresiones(descripcionDisenio,codigoImpresion,descripcionImpresion, millaresPorPaquete,millaresPorRollo,anchoEtiqueta,anchoPelicula,alturaEtiqueta,porcentajeMPR,codigoCliente,nombreBanda,sustrato) VALUES('$tipo','$codigoImpresion','$descripcionImpresion','$millaresPorRollo','$millaresPorPaquete','$anchoPelicula','$anchoEtiqueta','$alturaEtiqueta','$porcentajeMPR','$productoscliente','$bspp','$sustratos')");
	 		
	 	}

	 	else if (empty($_POST['espacioFusion'])) {
	 		$productoscliente=$MySQLiconn->real_escape_string($_POST['comboPCliente']);
	 		$bspp=$MySQLiconn->real_escape_string($_POST['comboBSPP']);
	 		$sustratos=$MySQLiconn->real_escape_string($_POST['comboSustratos']);
	 			$tipo = $MySQLiconn->real_escape_string($_POST['comboDisenios']);
	$codigoImpresion = $MySQLiconn->real_escape_string($_POST['codigoImpresion']);
	$descripcionImpresion =$MySQLiconn->real_escape_string($_POST['descImpresion']);   
	$millaresPorRollo = $MySQLiconn->real_escape_string($_POST['millaresPR']);
	$millaresPorPaquete = $MySQLiconn->real_escape_string($_POST['millaresPP']);
	$anchoPelicula = $MySQLiconn->real_escape_string($_POST['anchoPelicula']);
	$anchoEtiqueta = $MySQLiconn->real_escape_string($_POST['anchoEtiqueta']);
	$alturaEtiqueta = $MySQLiconn->real_escape_string($_POST['alturaEtiqueta']);
	$tintas = $MySQLiconn->real_escape_string($_POST['numTintas']);
	$porcentajeMPR = $MySQLiconn->real_escape_string($_POST['porcentajeMPR']);
	 		$SQL =$MySQLiconn->query("INSERT INTO impresiones(descripcionDisenio,codigoImpresion,descripcionImpresion, millaresPorPaquete,millaresPorRollo,anchoEtiqueta,anchoPelicula,alturaEtiqueta,tintas,porcentajeMPR,codigoCliente,nombreBanda,sustrato) VALUES('$tipo','$codigoImpresion','$descripcionImpresion','$millaresPorRollo','$millaresPorPaquete','$anchoPelicula','$anchoEtiqueta','$alturaEtiqueta','$tintas','$porcentajeMPR','$productoscliente','$bspp','$sustratos')");
	 	}
	 	else  {
	 		$productoscliente=$MySQLiconn->real_escape_string($_POST['comboPCliente']);
	 		$bspp=$MySQLiconn->real_escape_string($_POST['comboBSPP']);
	 		$sustratos=$MySQLiconn->real_escape_string($_POST['comboSustratos']);
	 		$tipo = $MySQLiconn->real_escape_string($_POST['comboDisenios']);
	$codigoImpresion = $MySQLiconn->real_escape_string($_POST['codigoImpresion']);
	$descripcionImpresion =$MySQLiconn->real_escape_string($_POST['descImpresion']);   
	$millaresPorRollo = $MySQLiconn->real_escape_string($_POST['millaresPR']);
	$millaresPorPaquete = $MySQLiconn->real_escape_string($_POST['millaresPP']);
	$anchoPelicula = $MySQLiconn->real_escape_string($_POST['anchoPelicula']);
	$anchoEtiqueta = $MySQLiconn->real_escape_string($_POST['anchoEtiqueta']);
	$alturaEtiqueta = $MySQLiconn->real_escape_string($_POST['alturaEtiqueta']);
	//$nombreBanda = $MySQLiconn->real_escape_string($_POST['nombreBanda']);
	//$codigoCliente = $MySQLiconn->real_escape_string($_POST['codigoCliente']);
	$espacioFusion = $MySQLiconn->real_escape_string($_POST['espacioFusion']);
	$tintas = $MySQLiconn->real_escape_string($_POST['numTintas']);
	//$sustrato = $MySQLiconn->real_escape_string($_POST['sustrato']);
	$porcentajeMPR = $MySQLiconn->real_escape_string($_POST['porcentajeMPR']);
	
	 		$SQL =$MySQLiconn->query("INSERT INTO impresiones(descripcionDisenio,codigoImpresion,descripcionImpresion, millaresPorPaquete,millaresPorRollo,anchoEtiqueta,anchoPelicula,alturaEtiqueta,espacioFusion,tintas,porcentajeMPR,codigoCliente,nombreBanda,sustrato) VALUES('$tipo','$codigoImpresion','$descripcionImpresion','$millaresPorRollo','$millaresPorPaquete','$anchoPelicula','$anchoEtiqueta','$alturaEtiqueta','$espacioFusion','$tintas','$porcentajeMPR','$productoscliente','$bspp','$sustratos')");
	 	}

	 if(empty($_POST['numTintas']))
	 {
	 }
	 else
	 {
	 	for($i=0;$i<$_POST['numTintas'];$i++)
	 	{
	 		$codigoCapa="C".$_POST['codigoImpresion'].$i;
	 		$SQL =$MySQLiconn->query("INSERT INTO pantonepcapa(codigoImpresion,descripcionPantone,codigoCapa,consumoPantone,disolvente) VALUES('$codigoImpresion','PANTONE WHITE C','$codigoCapa',0.0,'70/30')");
	 	}
	 }
	 	
	 
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Agregado una nueva impresión')</script>";
	 }
	}

/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE impresiones SET baja=0 WHERE id=".$_GET['del']);
	header("Location: Producto_impresiones.php");
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
	$SQL = $MySQLiconn->query("SELECT * FROM impresiones WHERE id=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
}

	if(isset($_POST['update']))
{
$registrosPantones=$_SESSION['pantonesSend'];//Envia el numero de registros de pantone que va a modificar
if(empty($_POST['numTintas']) && empty($_POST['espacioFusion']))
{
	$SQL = $MySQLiconn->query("UPDATE impresiones SET descripcionImpresion='".$_POST['descImpresion']."', codigoImpresion='".$_POST['codigoImpresion']."',  anchoPelicula=".$_POST['anchoPelicula'].", anchoEtiqueta=".$_POST['anchoEtiqueta'].",millaresPorRollo=".$_POST['millaresPR'].",alturaEtiqueta=".$_POST['alturaEtiqueta'].",porcentajeMPR=".$_POST['porcentajeMPR'].",millaresPorPaquete=".$_POST['millaresPP'].",codigoCliente=".$_POST['comboPCliente'].",sustrato=".$_POST['comboSustratos'].",nombreBanda=".$_POST['comboBSPP']." WHERE id=".$_GET['edit']."");
}

else if (empty($_POST['espacioFusion'])) {
	$SQL = $MySQLiconn->query("UPDATE impresiones SET descripcionImpresion='".$_POST['descImpresion']."', codigoImpresion='".$_POST['codigoImpresion']."',  anchoPelicula=".$_POST['anchoPelicula'].", anchoEtiqueta=".$_POST['anchoEtiqueta'].",millaresPorRollo=".$_POST['millaresPR'].",alturaEtiqueta=".$_POST['alturaEtiqueta'].",porcentajeMPR=".$_POST['porcentajeMPR'].",millaresPorPaquete=".$_POST['millaresPP'].",tintas=".$_POST['numTintas'].",codigoCliente=".$_POST['comboPCliente'].",sustrato=".$_POST['comboSustratos'].",nombreBanda=".$_POST['comboBSPP']." WHERE id=".$_GET['edit']."");
	for($i=0;$i<$registrosPantones;$i++)
{
$stosPantones=$_POST['comboPantones'.$i];
$disolvent=$_POST['disolvente'.$i];
$consum=$_POST['consumoPantone'.$i];
$codigoCapa="C".$_POST['codigoImpresion'].$i;
 $MySQLiconn->query("UPDATE pantonepcapa SET descripcionPantone='".$stosPantones."', disolvente='".$disolvent."', consumoPantone=".$consum." where codigoCapa='".$codigoCapa."'");
}
}
else  {
	$SQL = $MySQLiconn->query("UPDATE impresiones SET descripcionImpresion='".$_POST['descImpresion']."', codigoImpresion='".$_POST['codigoImpresion']."',  anchoPelicula=".$_POST['anchoPelicula'].", anchoEtiqueta=".$_POST['anchoEtiqueta'].", millaresPorRollo=".$_POST['millaresPR'].", alturaEtiqueta=".$_POST['alturaEtiqueta'].", espacioFusion=".$_POST['espacioFusion'].", porcentajeMPR=".$_POST['porcentajeMPR'].", millaresPorPaquete=".$_POST['millaresPP'].", tintas=".$_POST['numTintas'].",codigoCliente='".$_POST['comboPCliente']."',sustrato='".$_POST['comboSustratos']."',nombreBanda='".$_POST['comboBSPP']."' WHERE id=".$_GET['edit']."");
for($i=0;$i<$registrosPantones;$i++)
{
$stosPantones=$_POST['comboPantones'.$i];
$disolvent=$_POST['disolvente'.$i];
$consum=$_POST['consumoPantone'.$i];
$codigoCapa="C".$_POST['codigoImpresion'].$i;
 $MySQLiconn->query("UPDATE pantonepcapa SET descripcionPantone='".$stosPantones."', disolvente='".$disolvent."', consumoPantone=".$consum." where codigoCapa='".$codigoCapa."'");
}
}

	//$SQL = $MySQLiconn->query("UPDATE impresiones SET descripcionImpresion='Tacos deliciosos' where id=2");*/

 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se modificó correctamente')</script>";
	 header("Location: Producto_Impresiones.php?edit=".$_GET['edit']."");
	 }
	//header("Location: Producto_Impresiones.php");
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

/* Fin Código Atualizar */
