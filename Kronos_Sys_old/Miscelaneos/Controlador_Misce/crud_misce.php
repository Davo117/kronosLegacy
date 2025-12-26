<?php
include_once 'db_Producto.php';
/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save'])){
	//Pasamos los parametros por medio de post
	
	//Realizamos la consulta
	$descripcionTip=$MySQLiconn->real_escape_string($_POST['descripcion']);
	$descripcionTipo=trim($descripcionTip);
	$alias=trim($descripcionTipo);
	$procesos=$MySQLiconn->real_escape_string($_POST['procesos']);
	$presentacion=$MySQLiconn->real_escape_string($_POST['comboPresentacion']);
	//$parametros=$MySQLiconn->real_escape_string($_POST['parametros']);
	$juegoprocesos="JP".$alias.date("h");
	$juegoparametros="JPS".$alias.date("h");
	 	$SQL =$MySQLiconn->query("INSERT INTO tipoproducto(tipo,alias,juegoParametros,juegoProcesos,presentacion)VALUES('$descripcionTipo','$alias','$juegoparametros','$juegoprocesos','$presentacion')");
if($_POST['procesos']<=10)
{
	 	for($i=0;$i<=$_POST['procesos'];$i++){
	 		$desPro= '';
	 		
	 		$numeroProceso="PCS".$i;
	 		if ($i=='0') {
	 			$consulta=$MySQLiconn->query("INSERT INTO juegoprocesos(identificadorJuego, descripcionProceso, numeroProceso, referenciaProceso, registro)VALUES('$juegoprocesos', 'programado', '$i', '$numeroProceso', '0')");
	 			$consulta=$MySQLiconn->query("INSERT INTO juegoprocesos(identificadorJuego, descripcionProceso, numeroProceso, referenciaProceso, registro)VALUES('$juegoprocesos', 'caja', '$i', '$numeroProceso', '0')");
	 			$consulta=$MySQLiconn->query("INSERT INTO juegoprocesos(identificadorJuego, descripcionProceso, numeroProceso, referenciaProceso, registro)VALUES('$juegoprocesos', 'rollo', '$i', '$numeroProceso', '0')");
	 		}
	 		else{	
	 			$consulta=$MySQLiconn->query("INSERT INTO juegoprocesos(identificadorJuego, descripcionProceso, numeroProceso, referenciaProceso)VALUES('$juegoprocesos', '$desPro', '$i', '$numeroProceso')");
	 		}
	 	}
	 
	 	$SQL=$MySQLiconn->query("SELECT * FROM juegoparametros where identificadorJuego='$presentacion'");
	 	while ($rows=$SQL->fetch_array()) {
	 		$MySQLiconn->query("INSERT INTO juegoparametros(identificadorJuego, nombreparametro, numParametro, requerido, leyenda, placeholder) 
	 			VALUES('$juegoparametros','".$rows['nombreparametro']."', '".$rows['numParametro']."', '".$rows['requerido']."', '".$rows['leyenda']."', '".$rows['placeholder']."') ");
	 	}
	 
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Agregado una nuevo producto')</script>";
	 }
	}
	else
	{
		 echo"<script>alert('No se pueden agregar mas de 10 procesos')</script>";
	}
	}

/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE tipoproducto SET baja=0 WHERE id=".$_GET['del']);
	 //Mandamos un mensaje de exito:
	header("Location: Misce_Productos.php");
	 echo"<script>alert('Se ha Dado de Baja Exitosamente')</script>";
}

if(isset($_GET['param']))
{
	$_SESSION['envio2']=$_GET['param'];
	header("Location: Misce_Parametros.php");

}
if(isset($_GET['proces']))
{
	$_SESSION['procesoProd']=$_GET['proces'];
	header("Location: Misce_Procesos.php");
	 //echo "<META HTTP-EQUIV='REFRESH' CONTENT='0; URL=Producto_juegoscilindros.php'>";
}
if(isset($_GET['acti']))
{
	$MySQLiconn->query("UPDATE tipoproducto set baja=1 where id=".$_GET['acti']."");
	header("Location: Misce_Productos.php");
}
if(isset($_GET['delfin']))
{
	$MySQLiconn->query("DELETE from tipoproducto where id=".$_GET['delfin']."");
	header("Location: Misce_Productos.php");

}
?>