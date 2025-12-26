<?php

include_once 'db_materiaPrima.php';
if(!empty($_SESSION['usuario']))
{
	$usuarioF=$_SESSION['usuario'];

}
else
{
	$usuario="";
}


/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['save'])){

	//Pasamos los parametros por medio de post
	$bloque = $MySQLiconn->real_escape_string($_POST['comboBloquesillos']);
	$referencia = $MySQLiconn->real_escape_string($_POST['referenciaLote']);
	$longitud =$MySQLiconn->real_escape_string($_POST['longitud']);   
	$peso = $MySQLiconn->real_escape_string($_POST['peso']);
	$tarima = $MySQLiconn->real_escape_string($_POST['tarima']);
	$numero=1;
	$valNum=0;
	if($longitud>0)
	{
		while($valNum<1){
		$SK=$MySQLiconn->query("SELECT numeroLote from tblotes where numeroLote='$numero' and tarima='$tarima'");
		if($SK->num_rows>0){	$numero++;	}
		else{	$valNum=1;	}
	}
	if($valNum==1){
		$patch=$MySQLiconn->query("SELECT anchura from sustrato where idSustrato='$bloque'");
		$ol=$patch->fetch_array();
		$ancho=$ol['anchura'];
		//Realizamos la consulta
		/* $SQL =$MySQLiconn->query("INSERT INTO impresiones(descripcionDisenio,codigoImpresion,descripcionImpresion, millaresPorPaquete,millaresPorRollo,anchoEtiqueta,anchoPelicula,alturaEtiqueta,nombreBanda,codigoCliente,espacioFusion,tintas,sustrato,porcentajeMPR) VALUES('$tipo','$codigoImpresion','$descripcionImpresion','$millaresPorRollo','$millaresPorPaquete','$anchoPelicula','$anchoEtiqueta','$alturaEtiqueta','$nombreBanda','$codigoCliente','$espacioFusion','$tintas','$sustrato','$porcentajeMPR'");*/
		$SQL =$MySQLiconn->query("INSERT INTO tblotes(bloque,referenciaLote,longitud, peso,tarima,numeroLote,ancho) VALUES('$bloque','$referencia',$longitud,$peso,'$tarima','$numero','$ancho')");
		//$MySQLiconn->query("UPDATE bloquesmateriaprima set longitud=longitud+'$longitud' where nombreBloque='$bloque'");
		//En caso de ser diferente la consulta:
	 
		if(!$SQL){
	 		//Mandar el mensaje de error
			echo $MySQLiconn->error;
		} 
		else{
			$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego un lote al bloque:  $bloque','Lotes','Materia Prima',NOW())");

			//Mandamos un mensaje de exito:
		 	echo"<script>alert('Se ha Agregado un nuevo lote')</script>
			<script>window.location='MateriaPrima_Lotes.php';</script>";
		}
	}
	}
	else
	{
		echo"<script>alert('La longitud no es válida')</script>";
	}
	
}

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del'])){	//Lanzamos la consulta actualizando la baja a 0

	$SQL = $MySQLiconn->query("SELECT referenciaLote FROM tblotes WHERE idLote=".$_GET['del']);
	$get = $SQL->fetch_array();
	$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Desactivo el lote: ".$get['referenciaLote']."','Lotes','Materia Prima',NOW())");

	$SQL = $MySQLiconn->query("UPDATE tblotes	SET baja=0 ,estado=4 WHERE idLote=".$_GET['del']."");
	
	echo"<script>alert('Se ha Dado de Baja Exitosamente')</script>";
	echo "<script>window.location='MateriaPrima_Lotes.php?bloqueson=".$bloqueDescripcion."'</script>";
	//Mandamos un mensaje de exito:
}
if(isset($_POST['sendAll'])){
	$contador=0;
	$arrayFree='\n';
	//$numero=count(getElementsByTagName('input'));
	//$numero=count($_POST['sendAll']); 
	$bloque=explode('|', $_POST['bloque']);
	$SQL=$MySQLiconn->query("SELECT idLote,referenciaLote from tblotes where tarima='".$bloque[0]."'");
	//$numero=$SQL->num_rows;

	while($ro2=$SQL->fetch_array()){
		$var='check'.$contador;
		if(!empty($_POST[$var])){
			$loteActual=$_POST[$var];

			if(isset($_POST[$var])){
				if($_POST[$var]==$ro2['idLote'])
				{
					$arrayFree=$arrayFree.$ro2['referenciaLote'].','.'\n';
				}
				
				$MySQLiconn->query("UPDATE tblotes SET estado=2 WHERE idLote='".$loteActual."'");
			}
		}
		$contador++;
	}

	//header("Location: MateriaPrima_lotes.php");
	//Mandamos un mensaje de exito:
	$msj="success";
	echo("<script>window.location = 'MateriaPrima_Lotes.php?msj=".$msj."&cdgs=".$arrayFree."&type=1&bloqueson=".$bloque[1]."';</script>");
}

if(isset($_GET['edit'])){
	$SQL = $MySQLiconn->query("SELECT idLote,referenciaLote,bloque,longitud,peso,tarima FROM lotes WHERE idLote=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
}


if(isset($_POST['update'])){
	$porPost=$getROW['referenciaLote'];
	$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Actualizo el lote:  $porPost','Lotes','Materia Prima',NOW())");

	$SQL=$MySQLiconn->query("UPDATE tblotes SET referenciaLote='".$_POST['referenciaLote']."', longitud='".$_POST['longitud']."', peso='".$_POST['peso']."',tarima='".$_POST['tarima']."' WHERE idLote=".$_GET['edit']."");
	unset($_GET['edit']);
	echo("<script>window.location = 'MateriaPrima_Lotes.php?bloqueson=".$_POST['comboBloquesillos']."';</script>"); 
}

if(isset($_GET['imp'])){
	$_SESSION['descripcion']= $_GET['imp'];
	echo("<script>window.location = 'Producto_Impresiones.php';</script>");
}

if(isset($_GET['produ'])){
	$SQL = $MySQLiconn->query("UPDATE tblotes	SET estado=2 WHERE idLote=".$_GET['produ']);
	header("location:MateriaPrima_Lotes.php?bloqueson=".$_GET['bloqueDescripcion']."");
	//Mandamos un mensaje de exito:
}
if(isset($_GET['repor'])){
	

if($_SESSION['rol']!="Compras")
{
	$SQ=$MySQLiconn->query("
					SELECT referencia 
					from lotestemporales where id='".$_GET['repor']."'");
				$rem=$SQ->fetch_array();
				if(empty($rem['referencia']))
				{
					$SQLi=$MySQLiconn->query("
					UPDATE  tblotes 
					set estado=0 
					where idLote='".$_GET['repor']."' and estado=2");
					$mensaje="success";
					header("location:MateriaPrima_Lotes.php?msj=".$mensaje."&type=2&bloqueson=".$_GET['bloqueDescripcion']."");
		//echo "<script>alert'Lote retornado')</script>";
		//echo "<script>window.location:'MateriaPrima_Lotes.php?</script>";
				}
				else
				{
					$mensaje="danger";
					echo"<script>window.location:'MateriaPrima_Lotes.php?msj=".$mensaje."&type=2'</script>";
				}
		

}
else{
$mensaje="warning";
header("location:MateriaPrima_Lotes.php?msj=".$mensaje."");
	}
}
if(isset($_GET['calen'])){
	if($_SESSION['rol']=="Producción"){
	$SQ=$MySQLiconn->query("SELECT juegoLote,unidades from tblotes where idLote='".$_GET['calen']."'");
				$rem=$SQ->fetch_array();

				$SQLi=$MySQLiconn->query("
					UPDATE  produccion 
					set cantLotes=cantLotes-1,unidades=unidades-".$rem['unidades']." where juegoLotes='".$rem['juegoLote']."'");

				$SQL=$MySQLiconn->query("
					UPDATE  tblotes 
					set estado=2,unidades=0,noLote='',anchuraBloque='',juegoLote='', noop='' where idLote='".$_GET['calen']."'");
				echo "<script>alert('El lote: ´".$_GET['calen']."´ fué retornado');</script>";
				echo "<script>window.location='MateriaPrima_Lotes.php';</script>";
	}
	else
	{
echo"<script>alert('Solo personal autorizado puede hacer esta acción')</script>";
	}
	
	 //Mandamos un mensaje de exito:
}
if(isset($_GET['acti']))
{

	$SQL=$MySQLiconn->query("SELECT referenciaLote FROM lotes WHERE idLote=".$_GET['acti']);
	$get = $SQL->fetch_array();

	$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Activo el lote: ".$get['referenciaLote']."','Lotes','Materia Prima',NOW())");

	$MySQLiconn->query("UPDATE tblotes set baja=1, estado=0 where idLote=".$_GET['acti']."");
	header("Location: MateriaPrima_Lotes.php?bloqueson=".$_GET['bloqueDescripcion']);
}
if(isset($_GET['delfin']))
{

$SQL = $MySQLiconn->query("SELECT referenciaLote FROM tblotes WHERE idLote=".$_GET['delfin']);
$get = $SQL->fetch_array();
$SQL =$MySQLiconn->query("INSERT INTO reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Elimino el lote: ".$get['referenciaLote']."','Lotes','Materia Prima',NOW())");


	/*$resultado = $MySQLiconn->query("SELECT peso,longitud from tblotes where idLote=".$_GET['delfin']."");
	$row=$resultado->fetch_array();
	$menosPeso=$row['peso'];
	$menosLongitud=$row['longitud'];

	$resul = $MySQLiconn->query("SELECT  bloquesmateriaprima.nombreBloque, bloquesmateriaprima.peso,bloquesmateriaprima.longitud from bloquesmateriaprima inner join tblotes on bloquesmateriaprima.nombreBloque=lotes.bloque where lotes.idLote=".$_GET['delfin']."");
	$raw=$resul->fetch_array();
	$nombreBloque=$raw['nombreBloque'];
	$pesoBloque=$raw['peso'];
	$longitudBloque=$raw['longitud'];
	$pesoTotal=$pesoBloque-$menosPeso;
	$LongitudTotal=$LongitudBloque-$menosLongitud;*/

	
	$MySQLiconn->query("DELETE from tblotes where idLote=".$_GET['delfin']."");
	//$SQL = $MySQLiconn->query("UPDATE bloquesmateriaprima SET peso=".$pesoTotal.", longitud=".$LongitudTotal." WHERE nombreBloque='".$nombreBloque."'");
	header("Location: MateriaPrima_Lotes.php?bloqueson=".$_GET['bloqueDescripcion']);

}
if(isset($_GET['end']))
{
	$SKUL=$MySQLiconn->query("SELECT codigo from codigosbarras where lote='".$_GET['end']."' order by noProceso desc limit 1 ");
	$row=$SKUL->fetch_array();
	$codigo=$row['codigo'];
	echo "<script>window.location='../Calidad/Buscador_Calidad.php?lista=1&codigo=$codigo';</script>";
}

if(isset($_POST['b']))
{
	$user = $_POST['b'];
       
      if(!empty($user)) {
            comprobar($user);
      }
}
function comprobar($b) {
           include 'db_materiaPrima.php';
       
            $sql = $MySQLiconn->query("SELECT referenciaLote FROM tblotes WHERE referenciaLote = '".$b."'");
             
            $contar = $sql->num_rows;
             
            if($contar == 0){
                  echo "<span style='font-weight:bold;color:green;'>Disponible.</span>";
            }else{
                  echo "<span style='font-weight:bold;color:red;'>Referencia existente.</span>";
            }
      }  
/* Fin Código Atualizar */