<?php
include_once '../Database/db.php';

$usuarioF=$_SESSION['usuario'];

/*	En caso de hacer clic a un boton o imagen, se registrará en la bd y 
	se mostrará en el modulo de REPORTE

*/
//Inicio Código Insertar Armado de Embarque
if(isset($_POST['saveA'])){
$i=0;
$surtir=0;
	if ($_GET['emp']=='caja') {
  		$SQL=$MySQLiconn->query("SELECT piezas, referencia FROM caja where producto='".$_GET['pr']."' && baja='2'");
		while ($row=$SQL->fetch_array()) {
	    	$cdg='number'.$row['referencia'];
    		$chck = $MySQLiconn->real_escape_string($_POST[''.$cdg.'']);
    	  	if ($chck!='--') {
	        	$surtir=$surtir+$row['piezas'];
        		$i++;
      		}
    	}
  	}
	elseif ($_GET['emp']=='rollo') {
    	$SQL=$MySQLiconn->query("SELECT referencia, piezas FROM rollo where producto='".$_GET['pr']."' && baja='2'");
    	while ($row=$SQL->fetch_array()) {
	      $cdg='number'.$row['referencia'];
    	  $chck = $MySQLiconn->real_escape_string($_POST[''.$cdg.'']);
	      if ($chck!='--') {
    	    $surtir=$surtir+$row['piezas'];
        	$i++;
      	}
    }
  }
  $MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego $i empaques, $surtir millares al embarque:".$_GET['cdgEmb']."','Armado de Embarque','Logistica',NOW())");
}


/* Inicio Código Activar */
if(isset($_GET['activar'])){
	$SQL = $MySQLiconn->query("SELECT * FROM $embarq WHERE idEmbarque=".$_GET['activar']);
	$get = $SQL->fetch_array();
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo el embarque: ".$get['numEmbarque']."','Embarque','Logistica',NOW())");
}


/* Inicio Código Eliminación Logíca */
if(isset($_GET['del'])){
	$SQL = $MySQLiconn->query("SELECT * FROM $embarq WHERE idEmbarque=".$_GET['del']);
	$get = $SQL->fetch_array();
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo el embarque: ".$get['numEmbarque']."','Embarque','Logistica',NOW())");
}


/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli'])){
	$SQL = $MySQLiconn->query("SELECT * FROM $embarq WHERE idEmbarque=".$_GET['eli']);
	$get = $SQL->fetch_array();
 	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino el embarque: ".$get['numEmbarque']."','Embarque','Logistica',NOW())");
}


if(isset($_POST['update'])){
	$SQL = $MySQLiconn->query("SELECT * FROM $embarq WHERE idEmbarque=".$_GET['edit']);
	$getAll = $SQL->fetch_array();
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo el embarque: ".$getAll['numEmbarque']."','Embarque','Logistica',NOW())");
} ?>