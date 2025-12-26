<?php

include_once '../Database/db.php';


/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_GET['download']))
{
	//Pasamos los parametros por medio de post
	$suc= $MySQLiconn->real_escape_string($_GET['sucursal']);
	$prod = $MySQLiconn->real_escape_string($_GET['productos']);
	$dsd = $MySQLiconn->real_escape_string($_GET['fechaDesde']);
	$hst =$MySQLiconn->real_escape_string($_GET['fechaHasta']);   


 	header("Location: funciones/pdf/EntregasPDF.php?dsd=$dsd&hst=$hst&suc=$suc&prod=$prod");

//echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=funciones/pdf/EntregasPDF.php";
}
/* Fin Código Atualizar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
?>