<?php include("../Database/db.php");
$usuarioF=$_SESSION['usuario'];
/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save'])){
	//Pasamos los parametros por medio de post a una variable php
	$fechDev = $MySQLiconn->real_escape_string($_POST['devuelto']);
	$radio =$MySQLiconn->real_escape_string($_POST['radio1']);   
	$cdg = $MySQLiconn->real_escape_string($_POST['cdg']);

	 //Numeracion del campo numero embarque:
	$fechard= explode("-", $fechDev, 3);
	
	$lent=$fechard[0];
	$fechardo=$lent[2].''.$lent[3].''.$fechard[1];
	$secuencia=$fechardo."001";

	$resulta =$MySQLiconn->query("SELECT folio FROM $devolucion");
	while ($rows=$resulta->fetch_array()) { 
		if($secuencia==$rows['folio']){ 
			$secuencia++;
		}
	}
	//Fin numeración 
	
	//Verificamos el código 
	if ($radio=="0") { 	
		$variable='Embarque'; 
		$consultar=$MySQLiconn->query("SELECT idEmbarque, sucEmbFK, prodEmbFK, observaEmb from $embarq where numEmbarque='$cdg'");
		$row=$consultar->fetch_array();
		if(is_null($row['idEmbarque'])){
			echo "<script>alert('El código del $variable es incorrecto.')</script>";
			echo ("<script> var varia = window.location.pathname;
			window.location=varia;</script>");
			exit;
		}

		$consultar1=$MySQLiconn->query("SELECT codigo from $devolucion where tipo='$variable'");
		while ($row1=$consultar1->fetch_array()){
			if ($row1['codigo']==$cdg) {
				echo "<script>alert('El código del $variable ya se dio de alta.')</script>";
				echo ("<script> var varia = window.location.pathname;
				window.location=varia;</script>");
				exit;
			}
		}
		$suc=$row['sucEmbFK'];
		$prod=$row['prodEmbFK'];
	}

	if ($radio=="1") { 	
		$variable='Empaque'; 	
		$consultar1=$MySQLiconn->query("SELECT * from caja where codigo='$cdg' && baja='3'");
		$row=$consultar1->fetch_array();
		$empaque='caja';
		if (is_null($row['id'])) {
			$consultar2=$MySQLiconn->query("SELECT * from rollo where codigo='$cdg' && baja='3'");
			$row=$consultar2->fetch_array();
			$empaque='rollo';
		}
		if(is_null($row['id'])){
			echo "<script>alert('El código del $variable es incorrecto o ya se dio de alta')</script>";
			echo ("<script> var varia = window.location.pathname;
			window.location=varia;</script>");
			exit;
		}

		$consultar3=$MySQLiconn->query("SELECT * from $embarq where prodEmbFK='".$row['producto']."' && numEmbarque='".$row['cdgEmbarque']."'");
		$row2=$consultar3->fetch_array();
		$suc=$row2['sucEmbFK'];
		$prod=$row2['prodEmbFK'];
		$MySQLiconn->query("UPDATE $empaque SET baja='5', cdgDev='$secuencia' where cdgEmbarque='".$row['cdgEmbarque']."' && codigo='".$cdg."'");
		$SQL=$MySQLiconn->query("UPDATE ensambleempaques SET baja='5', cdgDev='$secuencia' where cdgEmbarque='".$row['cdgEmbarque']."' && referencia='".$row['referencia']."' && producto='".$row['producto']."'");
	}

	if ($radio=="2" or $radio=="3") { 	
		if ($radio=="2"){ 	$variable='Paquete'; $verificar='CAJA'; $empaque='caja';	}
		if ($radio=="3"){ 	$variable='Rollo';	$verificar='ROLLO';	$empaque='rollo';	}

		$consultar=$MySQLiconn->query("SELECT * from ensambleempaques where codigo='$cdg' && baja='1'"); 
		$row=$consultar->fetch_array();	

		if($row['tipoEmpaque']!=$verificar){
			echo "<script>alert('El código del $variable es incorrecto o ya se dio de Alta.')</script>";
			echo ("<script> var varia = window.location.pathname;
			window.location=varia;</script>");
			exit;
		}

		if(is_null($row['id'])){
			echo "<script>alert('El código del $variable es incorrecto.')</script>";
			echo ("<script> var varia = window.location.pathname;
			window.location=varia;</script>");
			exit;
		}
		$consultar1=$MySQLiconn->query("SELECT * from $embarq where prodEmbFK='".$row['producto']."' && numEmbarque='".$row['cdgEmbarque']."'");
		$row1=$consultar1->fetch_array();
		$suc=$row1['sucEmbFK'];
		$prod=$row1['prodEmbFK'];
		$SQL=$MySQLiconn->query("UPDATE ensambleempaques SET baja='5', cdgDev='$secuencia' where codigo='".$cdg."'");
	}	

	
	//Realizamos la consulta
	$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego una Devolución, Folio:  $secuencia, Tipo: $variable','Devoluciones','Logistica',NOW())");
	$SQL =$MySQLiconn->query("INSERT INTO $devolucion(folio,fechaDev, tipo, codigo, sucursal, producto) VALUES('$secuencia', '$fechDev', '$variable', '$cdg', '$suc', '$prod')");
	//En caso de ser diferente la consulta:
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	} 
	else{
		//Mandamos un mensaje de exito:
		echo"<script>alert('Se Registro Una Nueva Devolución')</script>";
	}	
}

/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////

// ACTIVAR
if(isset($_GET['activar'])){

$SQL=$MySQLiconn->query("SELECT folio, tipo from $devolucion WHERE id='".$_GET['activar']."'");
$row=$SQL->fetch_array();

	$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo una Devolución, Folio: ".$row['folio'].", Tipo: ".$row['tipo']."','Devoluciones','Logistica',NOW())");

	$SQL = $MySQLiconn->query("UPDATE $devolucion SET baja='1' WHERE id='".$_GET['activar']."'");
	if(!$SQL){
		//Mandar el mensaje de error
		echo $MySQLiconn->error;
	} 
	else header("Location: Logistica_Devoluciones.php");
	/*else{
		//Mandamos un mensaje de exito:
		echo"<script>alert('Se ha Activado Una Devolución.')</script>"; 
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_Devoluciones.php'>";
	}*/
}


//Inicio Código Eliminación Logíca 
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del'])){	//Lanzamos la consulta actualizando la baja a 0

$SQL=$MySQLiconn->query("SELECT folio, tipo from $devolucion WHERE id='".$_GET['del']."'");
$row=$SQL->fetch_array();

	$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo una Devolución, Folio: ".$row['folio'].", Tipo: ".$row['tipo']."','Devoluciones','Logistica',NOW())");


	$SQL = $MySQLiconn->query("UPDATE $devolucion SET baja='0' WHERE id=".$_GET['del']);
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	} 
	else{
		//Mandamos un mensaje de exito:	 
		echo"<script>alert('Se ha Desactivado Una Devolución.')</script>";
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_Devoluciones.php'>";
	}
}

//Inicio Código Eliminación Definitiva 
if(isset($_GET['eli'])){
	$SQL=$MySQLiconn->query("SELECT folio, tipo from $devolucion WHERE id='".$_GET['eli']."'");
	$row=$SQL->fetch_array();

	$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino una Devolución, Folio: ".$row['folio'].", Tipo: ".$row['tipo']."','Devoluciones','Logistica',NOW())");
	$SQL = $MySQLiconn->query("DELETE FROM  $devolucion where id=".$_GET['eli']);
	//En caso de ser diferente la consulta:
	 if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Logistica_Devoluciones.php");
	/*else{
		//Mandamos un mensaje de exito:
		echo"<script>alert('Se ha Eliminado Una Devolución.')</script>";
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_Devoluciones.php'>";
	}*/
}
/* Fin Código Eliminación Definitiva */

/* REPORTE */

if(isset($_POST['saveR'])){
	$idr=$_GET['r'];
	$causa = $MySQLiconn->real_escape_string($_POST['causa']);
	$contacto = $MySQLiconn->real_escape_string($_POST['example']);


	$SQL = $MySQLiconn->query("SELECT folio, tipo from $devolucion WHERE id='$idr'");
$row=$SQL->fetch_array();

	$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Asingo Datos de Reporte a la Devolución, Folio: ".$row['folio'].", Tipo: ".$row['tipo']."','Devoluciones','Logistica',NOW())");

	$SQL=$MySQLiconn->query("UPDATE $devolucion SET baja='2', observaciones='".$causa."', idresponsable='".$contacto."' where id='$idr'");

	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Logistica_Devoluciones.php");
	/*else{
		//Mandamos un mensaje de exito:
		echo"<script>alert('Reporte de Devolución Listo.')</script>";
		echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Logistica_Devoluciones.php'>";
	}*/
}



// Parte de desamble de empaques//
if(isset($_GET['devT'])){
	$variable=$_GET['tipo'];
	//mandar a llamar a todos los paquetitos de caja o rollo
	$SQL=$MySQLiconn->query("SELECT referencia, producto, cdgEmbarque From $variable where cdgDev='' && baja='3' && id='".$_GET['devT']."'");
	$row=$SQL->fetch_array();
	
	$SQL=$MySQLiconn->query("UPDATE ensambleempaques set baja='5', cdgDev='".$_SESSION['folioDev']."' WHERE referencia='".$row['referencia']."' && producto='".$row['producto']."' && cdgEmbarque='".$row['cdgEmbarque']."' && cdgDev=''");
	
	$SQL=$MySQLiconn->query("UPDATE $variable SET baja='5', cdgDev='".$_SESSION['folioDev']."' where id='".$_GET['devT']."' && cdgDev=''");
}


if(isset($_GET['devF'])){
	$variable=$_GET['tipo'];
	//mandar a llamar a todos los paquetitos de caja o rollo
	$SQL=$MySQLiconn->query("SELECT referencia, producto, cdgEmbarque From $variable where cdgDev='".$_SESSION['folioDev']."' && baja='5' && id='".$_GET['devF']."'");
	$row=$SQL->fetch_array();
	
	$SQL=$MySQLiconn->query("UPDATE ensambleempaques set baja='1', cdgDev='' where referencia='".$row['referencia']."' && producto='".$row['producto']."' cdgDev='".$_SESSION['folioDev']."' && cdgEmbarque='".$row['cdgEmbarque']."'");

	$SQL=$MySQLiconn->query("UPDATE $variable SET baja='3', cdgDev='' where id='".$_GET['devF']."'");
} ?>