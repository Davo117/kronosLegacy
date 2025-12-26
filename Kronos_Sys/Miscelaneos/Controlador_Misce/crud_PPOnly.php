<?php
include_once 'db_Producto.php';
/* Inicio Código Insertar */
//Si se dio clic en guardar:



if(isset($_POST['savePP']))
{
	$nombreParametro=$MySQLiconn->real_escape_string($_POST['parametroName']);
	$type=$MySQLiconn->real_escape_string($_POST['Tipo']);	 

	$SQL =$MySQLiconn->query("INSERT INTO parametros(nombreParametro, tipo) VALUES('$nombreParametro','$type')");
	//En caso de ser diferente la consulta:
	if(!$SQL) {
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	} 
	else{
		//Mandamos un mensaje de exito:
	 	echo"<script>alert('Se ha Agregado una nuevo proceso')</script>";
	}
}
/* Fin Código Insertar 
//////////////////////////////////////////////////////////////////////////////////////////////
*/
?>