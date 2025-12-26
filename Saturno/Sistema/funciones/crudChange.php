<?php

include_once '../Database/db.php';
$usu= $_SESSION['nombre'];





/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post
	$actu = $MySQLiconn->real_escape_string($_POST['actual']);
	$new =$MySQLiconn->real_escape_string($_POST['nueva']);   
	$confi = $MySQLiconn->real_escape_string($_POST['confirmar']);

	if ($actu=="" or $new=="" or $confi=="" ){
	echo "<script>alert('No se registro el cambio de contraseña')</script>";
}else{

$resultado = $MySQLiconn->query("SELECT * FROM usuarios where nombre='$usu'");
while ($rows = $resultado->fetch_array()) {
	if ($new == $confi) {
if ($actu == $rows['contrasenia']) {
		$SQL = $MySQLiconn->query("UPDATE usuarios SET contrasenia='$new' WHERE nombre='$usu'");
if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 }
	  else{
	 //Mandamos un mensaje de exito:
	echo"<script>alert('Se ha Modificado la contraseña')</script>"; 
echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=newpass.php'>";	 
	 }	 
	}	
}
}
}
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

	

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['saveUser']))
{
	//Pasamos los parametros por medio de post
	$nombre = $MySQLiconn->real_escape_string($_POST['example']);
	$rol =$MySQLiconn->real_escape_string($_POST['rol']);   
	$newuser = $MySQLiconn->real_escape_string($_POST['usuario']);
	$clave = $MySQLiconn->real_escape_string($_POST['contra']);
	$confclave = $MySQLiconn->real_escape_string($_POST['confirmar']);

if($clave!=$confclave){
	echo "<script>alert('Las contraseñas son incorrectas')</script>";
	return 0;
}else 
//Realizamos la consulta
	 $SQL =$MySQLiconn->query("INSERT INTO $user(nombre, usuario, contrasenia,rol) VALUES('$nombre','$newuser','$clave','$rol')");
	 
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha añadido un nuevo usuario al sistema')</script>";
	 }
}
