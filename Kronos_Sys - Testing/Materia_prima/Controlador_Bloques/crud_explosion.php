<?php
include ("../Database/db.php");

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['filtro'])){
	//Pasamos los parametros por medio de post
	$producto=$MySQLiconn->real_escape_string($_POST['example']);
	$confirmacion=$MySQLiconn->real_escape_string($_POST['planta']);
	
	//Al parecer ya funciona esta primera parte en el showExplosion
	if ($producto!='0') {
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=MateriaPrima_Explosion.php?prod=".$producto."&oc=".$confirmacion."'>";
	}
	//showExplosion
	//En la tabla debe de aparecer, consumos y pantones de acuerdo a la confirmacion
	elseif ($producto=='0' && $confirmacion!="0") {
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=MateriaPrima_Explosion.php?prod=ALL&oc=".$confirmacion."'>";
	}

// lo mismo a lo anterior pero de todas las confirmaciones activas.
	elseif($confirmacion=='0' && $producto=='0') {
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=MateriaPrima_Explosion.php?prod=ALL&oc=0'>";
	}
	else{
		echo"<script>alert('No paso nada')</script>";
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=MateriaPrima_Explosion.php'>";
	}
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////z

if(isset($_POST['report'])){	
	//Pasamos los parametros por medio de post
	$producto = $MySQLiconn->real_escape_string($_GET['prod']);
	$confirmacion =$MySQLiconn->real_escape_string($_GET['oc']);
	
	//Al parecer ya funciona esta primera parte en el showExplosion
	if ($producto!='ALL') {
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=funciones/pdfExplosion.php?prod=".$producto."&oc=".$confirmacion."'>";
	}
	//showExplosion
	//En la tabla debe de aparecer, consumos y pantones de acuerdo a la confirmacion
	elseif ($producto=='ALL' && $confirmacion!="0") {
		//Mandamos un mensaje de exito:
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=funciones/pdfExplosion.php?prod=ALL&oc=".$confirmacion."'>";
	}
// lo mismo a lo anterior pero de todas las confirmaciones activas.
	elseif($confirmacion=='0' && $producto=='ALL') {
		//Mandamos un mensaje de exito:
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=funciones/pdfExplosion.php?prod=ALL&oc=0'>";
	}
	else{
		echo"<script>alert('No paso nada')</script>";
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=funciones/pdfExplosion.php'>";
	}
}
/* Fin Código REPORTE */
//////////////////////////////////////////////////////////////////////////////////////////////