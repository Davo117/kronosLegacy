<?php
include_once '../Database/db.php';
/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save1'])){
	$perfil = $MySQLiconn->real_escape_string($_POST['example']);
	$seleccion= $MySQLiconn->query("SELECT * FROM accesos where  perfil='$perfil'");
	
	while ($rowSe = $seleccion->fetch_array()) {
		$concat='permiso'.$rowSe['cdgmodulo'];
		if(isset($_POST[$concat])){
			$permitir = $MySQLiconn->real_escape_string($_POST[$concat]);
			$update=$MySQLiconn->query("UPDATE accesos SET permiso='$permitir' WHERE perfil='$perfil' and cdgmodulo='".$rowSe['cdgmodulo']."'");
		}
	}
} ?>