<?php
include_once 'Database/db.php';

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save'])){
	//Pasamos los parametros por medio de post
	$type = $MySQLiconn->real_escape_string($_POST['tipo']);
	$sku =$MySQLiconn->real_escape_string($_POST['SKU']);
	$desc = $MySQLiconn->real_escape_string($_POST['Descripcion']);
	$fecha = $MySQLiconn->real_escape_string($_POST['fch_ingreso']);


		$SQLi=$MySQLiconn->query("SELECT sku FROM consumibles where baja='1'");
		while($row=$SQLi->fetch_array()){
			if ($row['sku']==$sku) {
				echo "<script>alert('No se puede repetir el SKU del  Consumible.')</script>";
				return;
			}
		}
		//Realizamos la consulta
		$SQL =$MySQLiconn->query("INSERT INTO consumibles(tipo,sku, descripcion, fch_ingreso) VALUES('$type','$sku','$desc','$fecha')");
		
		//En caso de ser diferente la consulta:
	 	if(!$SQL){
			//Mandar el mensaje de error
			echo $MySQLiconn->error;
	 	}
	 	else header("Location: Consumibles.php");
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Activar */
//Si se dio clic en Activar:
if(isset($_GET['activar'])){	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE consumibles SET baja=1 WHERE id_cons=".$_GET['activar']);
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Consumibles.php");
}
/* Fin Código Activar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del'])){
	$SQL = $MySQLiconn->query("UPDATE consumibles SET baja=0 WHERE id_cons=".$_GET['del']);
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Consumibles.php");
}
/* Fin Código Eliminación Logíca  */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli'])){ //Cambiar el parametro "del" para que no haya conflictos:
	$SQL = $MySQLiconn->query("DELETE FROM  consumibles where id_cons=".$_GET['eli']);
	//En caso de ser diferente la consulta:
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}else header("Location: Consumibles.php");
}
 /* Fin Código Eliminación Definitiva */
//////////////////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Atualizar  */
if(isset($_GET['edit'])){
	$SQL = $MySQLiconn->query("SELECT * FROM consumibles WHERE id_cons=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
}

if(isset($_POST['update'])){
	//Consumibles(tipo,marca, modelo, fch_ingreso, id_equipo)
		//Precaucion por que puede pasar que los nombres sean iguales y tener conflicto a la hora del cambio de nombre
		if (!empty($_POST['SKU'])) {
			$SQLi=$MySQLiconn->query("SELECT sku FROM consumibles where sku!='".$getROW['sku']."'");
			while($row=$SQLi->fetch_array()){
				if ($row['sku']==$_POST['SKU']) {
					echo "<script>alert('No se puede repetir el SKU del Consumible.')</script>";
					return;
				}
			}
		}
		$SQL = $MySQLiconn->query("UPDATE consumibles SET  tipo='".$_POST['tipo']."', sku='".$_POST['SKU']."', descripcion='".$_POST['Descripcion']."', fch_ingreso='".$_POST['fch_ingreso']."' WHERE id_cons=".$_GET['edit']);
		if(!$SQL){
		 	//Mandar el mensaje de error
			echo $MySQLiconn->error;
		}
		else header("Location: Consumibles.php");
}
/* Fin Código Atualizar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
?>