<?php

include_once '../Database/db.php';
$usu= $_SESSION['nombre'];
/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save'])){
	//Pasamos los parametros por medio de post
	$actu = $MySQLiconn->real_escape_string($_POST['actual']);
	$new =$MySQLiconn->real_escape_string($_POST['nueva']);   
	$confi = $MySQLiconn->real_escape_string($_POST['confirmar']);

	if ($actu=="" or $new=="" or $confi=="" ){
		echo "<script>alert('No se registró el cambio de contraseña')</script>";
	}else{
		$resultado = $MySQLiconn->query("SELECT usuario,AES_DECRYPT(contrasenia,667) as contrasenia FROM usuarios where nombre='$usu'");
		while ($rows = $resultado->fetch_array()) {
			if ($new == $confi) {
				if ($actu == $rows['contrasenia']) {
					$SQL = $MySQLiconn->query("UPDATE usuarios SET contrasenia=AES_ENCRYPT('$new','667') WHERE nombre='$usu'");
					if(!$SQL){
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
if(isset($_POST['saveUser'])){
	//Pasamos los parametros por medio de post
	$nombre = $MySQLiconn->real_escape_string($_POST['example']);
	$rol =$MySQLiconn->real_escape_string($_POST['rol']);   
	$newuser = $MySQLiconn->real_escape_string($_POST['usuario']);
	$clave = $MySQLiconn->real_escape_string($_POST['contra']);
	$confclave = $MySQLiconn->real_escape_string($_POST['confirmar']);

	$contador=0;
	$resultado = $MySQLiconn->query("SELECT * FROM usuarios");

	while ($rows = $resultado->fetch_array()) {
		if ($rows["usuario"]==$newuser) {
	 		$contador++;
		}
	}
	if ($contador==0) {
		if($clave!=$confclave){
			echo "<script>alert('Las contraseñas son incorrectas')</script>";
			return 0;
		}
		else{ 
			//Realizamos la consulta
			$consulta=$MySQLiconn->query("SELECT * FROM empleado where ID=$nombre");
			$respuesta=$consulta->fetch_array();
			$name= $respuesta['Nombre'];
			$perfil='PF'.$respuesta['ID'];

			$segunda=$MySQLiconn->query("SELECT codigo FROM modulo");
			while ($dos=$segunda->fetch_array()) {
				$SQLite =$MySQLiconn->query("INSERT INTO accesos(perfil, cdgmodulo, permiso) VALUES('$perfil', '".$dos['codigo']."', 'r--')");
			}

	 		$SQL =$MySQLiconn->query("INSERT INTO $user(nombre, usuario, contrasenia, perfil, rol) VALUES('$name','$newuser',AES_ENCRYPT('$clave',667), '$perfil','$rol')");
	 		$update=$MySQLiconn->query("UPDATE $tablaem SET usuario=1 WHERE ID='$nombre'");
	 		
	 		if(!$SQL){	 	//Mandar el mensaje de error
				echo $MySQLiconn->error;
	 		} 
	 		else{
	 			//Mandamos un mensaje de exito:
	 			echo"<script>alert('Se ha añadido un nuevo usuario al sistema')</script>";
	 		}
		}
	}	
	else{
	 	echo"<script>alert('Este usuario ya existe...')</script>";
	 	return 0;		
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['delU'])){
	$resultado = $MySQLiconn->query("SELECT * FROM usuarios where id=".$_GET['delU']);
	$rows = $resultado->fetch_array();

	$MySQLiconn->query("UPDATE $tablaem	SET usuario='0' WHERE Nombre='".$rows['nombre']."'");
	$MySQLiconn->query("DELETE FROM  accesos WHERE perfil='".$rows['perfil']."'");
	$SQL = $MySQLiconn->query("DELETE FROM  usuarios WHERE id='".$_GET['delU']."'");

	if(!$SQL) {
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	} else{
		//Mandamos un mensaje de exito:	 
		echo"<script>alert('Se ha Eliminado el usuario')</script>"; 
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=newUser.php'>";
	}
} ?>