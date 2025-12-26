<?php
include_once 'Database/db.php';

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save'])){
	//Pasamos los parametros por medio de post
	$type = $MySQLiconn->real_escape_string($_POST['tipo']);
	$marca =$MySQLiconn->real_escape_string($_POST['marca']);
	$model = $MySQLiconn->real_escape_string($_POST['modelo']);
	$fecha = $MySQLiconn->real_escape_string($_POST['fch_ingreso']);
	$idequi= $MySQLiconn->real_escape_string($_POST['idequipo']);

		$SQLi=$MySQLiconn->query("SELECT id_equipo FROM accesorios where baja='1'");
		while($row=$SQLi->fetch_array()){
			if ($row['id_equipo']==$idequi) {
				echo "<script>alert('No se puede repetir el N° de Equipo.')</script>";
				return;
			}
		}
		//Realizamos la consulta
		$SQL =$MySQLiconn->query("INSERT INTO accesorios(tipo,marca, modelo, fch_ingreso, id_equipo) VALUES('$type','$marca','$model','$fecha','$idequi')");
		//$add= $MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego al empleado Num:  $numemp','Personal','Recursos Humanos',NOW())");

		//En caso de ser diferente la consulta:
	 	if(!$SQL){
			//Mandar el mensaje de error
			echo $MySQLiconn->error;
	 	}
	 	else header("Location: Accesorios.php");
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Activar */
//Si se dio clic en Activar:
if(isset($_GET['activar'])){	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE accesorios SET baja=1 WHERE id_acc=".$_GET['activar']);
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Accesorios.php");
}
/* Fin Código Activar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del'])){
	$SQL = $MySQLiconn->query("UPDATE accesorios SET baja=0 WHERE id_acc=".$_GET['del']);
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Accesorios.php");
}
/* Fin Código Eliminación Logíca  */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli'])){ //Cambiar el parametro "del" para que no haya conflictos:
	$SQL = $MySQLiconn->query("DELETE FROM  accesorios where id_acc=".$_GET['eli']);
	//En caso de ser diferente la consulta:
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}else header("Location: Accesorios.php");
}
 /* Fin Código Eliminación Definitiva */
//////////////////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Atualizar  */
if(isset($_GET['edit'])){
	$SQL = $MySQLiconn->query("SELECT * FROM accesorios WHERE id_acc=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
}

if(isset($_POST['update'])){
	//accesorios(tipo,marca, modelo, fch_ingreso, id_equipo)
		//Precaucion por que puede pasar que los nombres sean iguales y tener conflicto a la hora del cambio de nombre
		if (!empty($_POST['idequipo'])) {
			$SQLi=$MySQLiconn->query("SELECT id_equipo FROM accesorios where id_equipo!='".$getROW['id_equipo']."'");
			while($row=$SQLi->fetch_array()){
				if ($row['id_equipo']==$_POST['idequipo']) {
					echo "<script>alert('No se puede repetir el ID de Equipo.')</script>";
					return;
				}
			}
		}
		$SQL = $MySQLiconn->query("UPDATE accesorios SET  tipo='".$_POST['tipo']."', marca='".$_POST['marca']."', modelo='".$_POST['modelo']."', fch_ingreso='".$_POST['fch_ingreso']."', id_equipo='".$_POST['idequipo']."' WHERE id_acc=".$_GET['edit']);
		if(!$SQL){
		 	//Mandar el mensaje de error
			echo $MySQLiconn->error;
		}
		else header("Location: Accesorios.php");
}
/* Fin Código Atualizar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
?>