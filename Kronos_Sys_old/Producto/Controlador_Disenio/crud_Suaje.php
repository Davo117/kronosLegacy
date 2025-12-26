<?php include_once 'db_Producto.php';
$usuarioF=$_SESSION['usuario'];
/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save'])){
	//Pasamos los parametros por medio de post
	$prod = $MySQLiconn->real_escape_string($_POST['producto']);
	$id = $MySQLiconn->real_escape_string($_POST['identificadorS']);
	$provee = $MySQLiconn->real_escape_string($_POST['proveedor']);
	$cdg = $MySQLiconn->real_escape_string($_POST['cdg']);
	//$pieza = $MySQLiconn->real_escape_string($_POST['pzs']);
	$corte = $MySQLiconn->real_escape_string($_POST['corteS']);
	$res = $MySQLiconn->real_escape_string($_POST['resguardo']);
	$observacion = $MySQLiconn->real_escape_string($_POST['observaciones']);
	$process = $MySQLiconn->real_escape_string($_POST['proceso']);
	$alturaReal=$MySQLiconn->real_escape_string($_POST['alturaReal']);


	//Realizamos la consulta
	$cat=$MySQLiconn->query("
	SELECT s.anchura,i.anchoPelicula,p.cilindros,p.cireles FROM sustrato s INNER JOIN impresiones i
    ON  s.descripcionSustrato=i.sustrato 
    INNER JOIN producto p 
    ON p.descripcion=i.descripcionDisenio
    WHERE i.descripcionImpresion='".$prod."' ");
	$dim=$cat->fetch_array();
	$tabla=$dim['anchura'];//Aquí estoy igualando la tabla y las repeticiones al paso alas del diseñi ligado
	$pieza=$tabla/$dim['anchoPelicula'];
	$consulta=$MySQLiconn->query("SELECT anchoPelicula, alturaEtiqueta from impresiones where descripcionImpresion='$prod' and baja='1'");

	$rowA=$consulta->fetch_array();
	$herramental=0;
	if($dim['cilindros']==1 || $dim['cireles']==1)//Valido que la altura del juego de cireles o cilindros coincida con la altura agregada para el suaje,en caso de que no tenga ninguno de los dos,se salta esta validacion
	{
		
		if($dim['cilindros']==1)
		{
			$skin=$MySQLiconn->query("SELECT alturaReal FROM juegoscilindros WHERE descripcionImpresion='".$prod."'");
			$still=$skin->fetch_array();
			if($still['alturaReal']==$alturaReal)
			{
				$herramental=1;
			}
		}
		if($dim['cireles']==1)
		{
			$skin=$MySQLiconn->query("SELECT alturaReal FROM juegoscireles WHERE descripcionImpresion='".$prod."'");
			$still=$skin->fetch_array();
			if($still['alturaReal']==$alturaReal)
			{
				$herramental=1;
			}
		}
	}
	else
	{
		$herramental=1;
	}

if($rowA['alturaEtiqueta']+($rowA['alturaEtiqueta']*0.1)>$alturaReal && $rowA['alturaEtiqueta']-($rowA['alturaEtiqueta']*0.1)<$alturaReal)
{

	if($herramental==1)
	{
		$SQL =$MySQLiconn->query("INSERT INTO suaje (identificadorSuaje, proveedor, codigo, alturaImpresion, anchuraImpresion, piezas, alturaReal, corteSeguridad, descripcionImpresion, observaciones, reguardo, proceso) VALUES ('$id', '$provee', '$cdg', '".$rowA['alturaEtiqueta']."', '".$rowA['anchoPelicula']."', '$pieza', '$alturaReal', '$corte', '$prod', '$observacion', '$res', '$process')");
	//En caso de ser diferente la consulta:
 	if(!$SQL){
		//Mandar el mensaje de error
		echo $MySQLiconn->error;
 	} else{
 		$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego el suaje:  $id','Suaje','Productos',NOW())");

	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Agregado un Nuevo Suaje')</script>";
	 }
	}
	else
	{
		echo"<script>alert('La altura real coincide con la del herramental utilizado')</script>";
	}
}
else
{
	 echo"<script>alert('La altura real no esta dentro del margen aceptado')</script>";
}
	
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Activar */
//Si se dio clic en Activar:
if(isset($_GET['acti'])){	//Lanzamos la consulta actualizando la baja a 0
	$SQL=$MySQLiconn->query("SELECT identificadorSuaje FROM suaje  WHERE id=".$_GET['acti']);
	$get = $SQL->fetch_array();
 	$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo el suaje: ".$get['identificadorSuaje']."','Suajes','Productos',NOW())");
	$SQL = $MySQLiconn->query("UPDATE suaje SET baja=1 WHERE id=".$_GET['acti']);
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Producto_Suaje.php");
}
/* Fin Código Activar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del'])){
	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("SELECT identificadorSuaje FROM suaje WHERE id=".$_GET['del']);
$get = $SQL->fetch_array();
$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo el suaje: ".$get['identificadorSuaje']."','Suajes','Productos',NOW())");

	$SQL = $MySQLiconn->query("UPDATE suaje SET baja=0 WHERE id=".$_GET['del']);
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Producto_Suaje.php");
}
/* Fin Código Eliminación Logíca  */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli'])){ //Cambiar el parametro "del" para que no haya conflictos:
$SQL = $MySQLiconn->query("SELECT identificadorSuaje FROM suaje WHERE id=".$_GET['eli']);
$get = $SQL->fetch_array();
$SQL =$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino el suaje: ".$get['identificadorSuaje']."','Suaje','Productos',NOW())");

	$SQL = $MySQLiconn->query("DELETE FROM suaje where id=".$_GET['eli']);
	//En caso de ser diferente la consulta:
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
}
 /* Fin Código Eliminación Definitiva */
//////////////////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Atualizar  */
if(isset($_GET['edit'])){
	$SQL = $MySQLiconn->query("SELECT * FROM suaje WHERE id=".$_GET['edit']);
	if(!$SQL){
		//Mandar el mensaje de error
		echo $MySQLiconn->error;
	} 
	else
		$getROW = $SQL->fetch_array();
}

if(isset($_POST['update'])){
	$porPOST=$_POST['identificadorS'];

$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo el suaje:  $porPOST','Suajes','Productos',NOW())");

	//Si tiene datos en sucursales o contactos, los desactiva tambien			
	$SQL = $MySQLiconn->query("UPDATE suaje SET identificadorSuaje='".$_POST['identificadorS']."', proveedor='".$_POST['proveedor']."',  codigo='".$_POST['cdg']."', piezas='".$_POST['pzs']."', alturaReal='".$_POST['alturaReal']."',  corteSeguridad='".$_POST['corteS']."',  descripcionImpresion='".$_POST['producto']."',  observaciones='".$_POST['observaciones']."',  reguardo='".$_POST['resguardo']."',  proceso='".$_POST['proceso']."' WHERE id=".$_GET['edit']); 
	//Realizamos la consulta

	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Producto_Suaje.php");
}

/* Fin Código Atualizar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////

/*

hoy por haber despertado pronto todavia me falta el aire
mi sueño se quedó roto,no se cómo recuperarle
ya me sé el guión de mis dias,que triste esta peripecia
la inercia me tira a un hoyo,y no lo digo yo,lo dice la ciencia






*/

?>