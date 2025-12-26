<?php include_once 'db_Producto.php';
$usuarioF=$_SESSION['usuario'];
/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['producto']))
{
	unset($_GET['edit']);
}
if(isset($_POST['save'])){
	//Pasamos los parametros por medio de post
	$prod = $MySQLiconn->real_escape_string($_POST['producto']);
	$id = trim($_POST['identificadorJ']);
	$diente = trim($_POST['num_dientes']);
	//$ancho = $MySQLiconn->real_escape_string($_POST['ancho_plano']);
	$fecha = $MySQLiconn->real_escape_string($_POST['fechaE']);
	$observacion = $MySQLiconn->real_escape_string($_POST['observaciones']);
	$alturaReal=$MySQLiconn->real_escape_string($_POST['alturaReal']);
	//$repeticiones=$MySQLiconn->real_escape_string($_POST['repeticiones']);
	
	$cat=$MySQLiconn->query("SELECT s.anchura,i.anchoPelicula,i.espacioRegistro FROM sustrato s INNER JOIN impresiones i
		ON  s.idSustrato=i.sustrato WHERE i.id='".$prod."' ");

	$dim=$cat->fetch_array();
	$tabla=$dim['anchura'];//Aquí estoy igualando la tabla y las repeticiones al paso alas del diseñi ligado
	$repeticiones=($tabla-$dim['espacioRegistro'])/$dim['anchoPelicula'];
	if((($dim['anchoPelicula']*$repeticiones)+$dim['espacioRegistro'])==$dim['anchura'])
	{
		if($dim['anchura']==$tabla)
		{

	//Realizamos la consulta
			$consulta=$MySQLiconn->query("SELECT tintas, descripcionImpresion from impresiones where codigoImpresion='$prod' and baja='1'");

			$rowTinta=$consulta->fetch_array();

			$SQL =$MySQLiconn->query("INSERT INTO juegoscireles(producto, identificadorJuego, num_dientes, no_placa, ancho_plano, fecha_entrega, observaciones,descripcionImpresion,alturaReal,repeticiones) VALUES('$prod', '$id', '$diente', '".$rowTinta['tintas']."', '$tabla', '$fecha', '$observacion', '".$rowTinta['descripcionImpresion']."','".$alturaReal."','".$repeticiones."')");
	//En caso de ser diferente la consulta:
			if(!$SQL){
		//Mandar el mensaje de error
				echo $MySQLiconn->error;
			} else{
				$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego el cirel:  $id','Juego de Cireles','Productos',NOW())");

	 //Mandamos un mensaje de exito:
				echo"<script>alert('Se ha Agregado un Nuevo Cirel')</script>";
			}
		}
		
		else
		{
			echo"<script>alert('Ancho plano incorrecto')</script>";
		}
	}
	else
	{
		echo"<script>alert('Las repeticiones al paso no coinciden con las dimensiones del sustrato y diseño')</script>";
	}
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Activar */
//Si se dio clic en Activar:
if(isset($_GET['acti'])){	//Lanzamos la consulta actualizando la baja a 0
	$SQL=$MySQLiconn->query("SELECT identificadorJuego FROM juegoscireles  WHERE id=".$_GET['acti']);
	$get = $SQL->fetch_array();
	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo el cirel: ".$get['identificadorJuego']."','Juego de Cireles','Productos',NOW())");

	$SQL = $MySQLiconn->query("UPDATE juegoscireles SET baja=1 WHERE id=".$_GET['acti']);
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Producto_Cireles.php?producto=".$_GET['producto']);
	}
	/* Fin Código Activar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
	/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
	if(isset($_GET['del'])){
		$SQL = $MySQLiconn->query("SELECT identificadorJuego FROM juegoscireles WHERE id=".$_GET['del']);
		$get = $SQL->fetch_array();
		$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo el cirel: ".$get['identificadorJuego']."','Juego de Cireles','Productos',NOW())");

	//Lanzamos la consulta actualizando la baja a 0
		$SQL = $MySQLiconn->query("UPDATE juegoscireles SET baja=0 WHERE id=".$_GET['del']);
		if(!$SQL){
	 	//Mandar el mensaje de error
			echo $MySQLiconn->error;
		}
		else header("Location: Producto_Cireles.php?producto=".$_GET['producto']);

		}
		/* Fin Código Eliminación Logíca  */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
		/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli'])){ //Cambiar el parametro "del" para que no haya conflictos:

$SQL = $MySQLiconn->query("SELECT identificadorJuego FROM juegoscireles WHERE id=".$_GET['eli']);
$get = $SQL->fetch_array();
$SQL =$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino el cirel: ".$get['identificadorJuego']."','Juego de Cireles','Productos',NOW())");
$SQL = $MySQLiconn->query("DELETE FROM juegoscireles where id=".$_GET['eli']);
	//En caso de ser diferente la consulta:
if(!$SQL){
	 	//Mandar el mensaje de error
	echo $MySQLiconn->error;
}
else
{
	 header("Location: Producto_Cireles.php?producto=".$_GET['producto']);
}
}
/* Fin Código Eliminación Definitiva */
//////////////////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Atualizar  */
if(isset($_GET['edit'])){
	$SQL = $MySQLiconn->query("
		SELECT  jl.*,l.estado as prod FROM juegoscireles jl 
		LEFT JOIN produccion pr
		ON pr.juegoCireles=jl.identificadorJuego
		LEFT JOIN lotes l ON l.juegoLote=pr.juegoLotes and l.estado=1
		WHERE jl.id='".$_GET['edit']."'");
	if(!$SQL){
		//Mandar el mensaje de error
		echo $MySQLiconn->error;
	} 
	else
		$getROW = $SQL->fetch_array();
}

if(isset($_POST['update'])){
	$cat=$MySQLiconn->query("SELECT s.anchura,i.anchoPelicula,i.espacioRegistro FROM sustrato s INNER JOIN impresiones i
		ON  s.idSustrato=i.sustrato WHERE i.id='".$_POST['producto']."'");
	$dim=$cat->fetch_array();
	$tabla=$dim['anchura'];//Aquí estoy igualando la tabla y las repeticiones al paso alas del diseñi ligado
	$repeticiones=($tabla-$dim['espacioRegistro'])/$dim['anchoPelicula'];
	if(($dim['anchoPelicula']*$repeticiones)==$tabla)
	{
		if($tabla==$tabla)
		{
			$porPOST=$_POST['identificadorJ'];

			$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo el cirel:  $porPOST','Juego de Cireles','Productos',NOW())");

			$SQL = $MySQLiconn->query("UPDATE juegoscireles SET producto='".$_POST['producto']."', identificadorJuego='".$_POST['identificadorJ']."', num_dientes='".$_POST['num_dientes']."',  ancho_plano='".$tabla."', fecha_entrega='".$_POST['fechaE']."',  observaciones='".$_POST['observaciones']."',repeticiones='".$repeticiones."',alturaReal='".$_POST['alturaReal']."' WHERE id=".$_REQUEST['edit']); 

	//Realizamos la consulta

			if(!$SQL){
	 	//Mandar el mensaje de error
				echo $MySQLiconn->error;
			}
			else{ header("Location: Producto_Cireles.php");}
			}
			else
			{
				echo"<script>alert('Ancho plano incorrecto')</script>";
			}
		}
		else
		{
			echo"<script>alert('Las repeticiones al paso no coinciden con las dimensiones del sustrato y diseño')</script>";
		}
	}
	

	/* Fin Código Atualizar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////

	?>