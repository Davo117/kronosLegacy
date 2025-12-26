<?php
include 'db_Producto.php';
/* Inicio Código Insertar */
//Si se dio clic en guardar:

if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post
	$productoscliente=$MySQLiconn->real_escape_string($_POST['comboPCliente']);
	if(!empty($_POST['comboBSPP']))
	{
		$bspp=$MySQLiconn->real_escape_string($_POST['comboBSPP']);
	}
	else
	{
		$bspp="";
	}
	if(!empty($_POST['hologram']))
	{
		$holog=$_POST['hologram'];
	}
	else
	{
		$holog="";
	}


	$sustratos=$MySQLiconn->real_escape_string($_POST['comboSustratos']);
	$tipo = $MySQLiconn->real_escape_string($_POST['comboDisenios']);
	$ref=$MySQLiconn->query("SELECT refil FROM producto WHERE descripcion='$tipo'");
	$rel=$ref->fetch_array(); 

	$your=$MySQLiconn->query("SELECT anchura FROM sustrato where descripcionSustrato='$sustratos'");
	$my=$your->fetch_array();

	if(isset(($_POST['espacioregistro'])))
	{
		if(is_int((($my['anchura']-$_POST['espacioregistro'])/$_POST['anchoPelicula'])))
		{
			$isEntire=1;
		}
		else
		{
			$isEntire=0;
		}
	}
	else
	{
		$isEntire=1;
	}
	if($rel['refil']==1)
	{
		if(($_POST['anchoPelicula']+$_POST['espacioregistro'])<=$my['anchura'])
		{
			$allowref=1;
		}
		else
		{
			$allowref=0;
		}
	}
	else
	{
		$allowref=1;
	}
	if($_POST['alturaEtiqueta']>0 && $_POST['anchoPelicula']>0)
	{
		if(isset($_POST['espacioFusion']))
		{
			if($my['anchura']>=(($_POST['anchoPelicula']+$_POST['espacioFusion'])+$_POST['espacioregistro']))
			{
				$bigger=1;
			}
			else
			{
				$bigger=0;
			}

		}
		else
		{
			$bigger=1;
		}
		if($bigger==1)
		{
			if(isset($_POST['anchoEtiqueta']) && isset($_POST['espacioFusion']))
			{
				$peli=($_POST['anchoEtiqueta']*2)+$_POST['espacioFusion'];
				if($peli==$_POST['anchoPelicula'])
				{
					$valPeli=1;
				}
				else
				{
					$valPeli=0;
				}
			}
			else
			{
				$valPeli=1;
			}
			if($valPeli==1)
			{
				if($allowref==1)
				{

					if($isEntire==1)
					{
$Sic =$MySQLiconn->query("INSERT INTO impresiones(descripcionDisenio,codigoCliente,nombreBanda,sustrato,holograma) VALUES('$tipo','$productoscliente','$bspp','$sustratos','$holog')");//Hace un insert,creando un nuevo registro


$SQL = $MySQLiconn->query("SELECT* FROM juegoparametros where identificadorJuego=(SELECT juegoparametros from tipoproducto where alias=(SELECT tipo from producto where descripcion='$tipo'))");
while($ram = $SQL->fetch_array()){
	$map=$ram['nombreparametro'];
	${$ram['nombreparametro']}=$MySQLiconn->real_escape_string($_POST[$map]);

	$seil =$MySQLiconn->query("UPDATE impresiones set ".$map."='".${$ram['nombreparametro']}."'  where id=(select id from (select impresiones.id from impresiones order by impresiones.id desc limit 1) as consulta)");


	//Esto imprime como prueba que se estan generando variables con los nombres correspondientes
}
$codigoImpresion = $MySQLiconn->real_escape_string($_POST['codigoImpresion']);

if(empty($_POST['tintas']))
{
}
else
{
	for($i=0;$i<$_POST['tintas'];$i++)
	{
		$codigoCapa="C".$_POST['codigoImpresion'].$i;
		$SQL =$MySQLiconn->query("INSERT INTO pantonepcapa(codigoImpresion,descripcionPantone,codigoCapa,consumoPantone,disolvente) VALUES('$codigoImpresion','PANTONE WHITE C','$codigoCapa',0.0,'70/30')");
	}
}
if(!empty($holog))
{
	$MySQLiconn->query("INSERT INTO hlogpproducto(tipo,impresion) values('".$_POST['hologram']."','".$_POST['descripcionImpresion']."')");
}



if(!$SQL)
{
	 	//Mandar el mensaje de error
	echo $MySQLiconn->error;
} else{
	 	 //$SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego la impresion(código:  $nombre','Impresiones','Productos',NOW())");

	 //Mandamos un mensaje de exito:
	echo "<div class='alert alert-success alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Operación exitosa</strong> ,agregado correctamente
		</div>";
}
}
else
{
	echo "<div class='alert alert-warning alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Datos incorrectos</strong> , El número de repeticiones resultantes no es un entero.
		</div>";
}


}
else
{
	echo "<div class='alert alert-warning alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Datos incorrectos</strong> , El ancho de la pelicula no puede ser el mismo que el del sustrato.
		</div>";
}
}
else
{
	echo "<div class='alert alert-warning alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Datos incorrectos</strong> , Las dimensiones de la anchura del producto no son coherentes.
		</div>";
}
}
else
{
	echo "<div class='alert alert-warning alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Datos incorrectos</strong> , El ancho de la pelicula no puede ser menor que el ancho del sustrato.
		</div>";
}
}
else
{

	echo "<div class='alert alert-warning alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Datos incorrectos</strong> , Datos de altura o anchura incorrectos.
		</div>";
}

}

/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE impresiones SET baja=0 WHERE id=".$_GET['del']);
	 //Mandamos un mensaje de exito:
	echo"<script>alert('Se ha Dado de Baja Exitosamente')</script>";
}
/* Fin Código Eliminación Logíca  */

/* Inicio Código Eliminación Definitiva 
if(isset($_GET['del']))
{ //Cambiar el parametro "del" para que no haya conflictos:
	$SQL = $MySQLiconn->query("DELETE FROM empleado WHERE id=".$_GET['del']);
	header("Location: index.php");
}
Fin Código Eliminación Definitiva */



/* Inicio Código Atualizar  */
if(isset($_GET['edit']))
{
	$SQL = $MySQLiconn->query("SELECT DISTINCT i.*,p.estado FROM impresiones i
LEFT JOIN produccion p ON
i.descripcionImpresion=p.nombreProducto WHERE i.id='".$_GET['edit']."'");
	$getROW = $SQL->fetch_array();
	if($getROW['estado']==2)
	{
		$inProd=1;
	}
	else
	{
		$inProd=0;
	}
}

if(isset($_POST['update']))
{
	$your=$MySQLiconn->query("SELECT anchura FROM sustrato where descripcionSustrato='".$_POST['comboSustratos']."'");
	$my=$your->fetch_array();
	if(isset(($_POST['espacioregistro'])))
	{

		if(is_int((($my['anchura']-$_POST['espacioregistro'])/$_POST['anchoPelicula'])))
		{
			$isEntire=1;
		}
		else
		{
			$isEntire=0;
		}
	}
	else
	{

		$isEntire=1;
	}
	if($my['anchura']>=($_POST['anchoPelicula']+$_POST['espacioregistro']))
	{
		if(isset($_POST['anchoEtiqueta']) && isset($_POST['espacioFusion']))
		{
			$peli=($_POST['anchoEtiqueta']*2)+$_POST['espacioFusion'];
			$peliplus=$_POST['anchoPelicula']+2;
			$peliminus=$_POST['anchoPelicula']-2;
			if($peli>=$peliminus && $peli<=$peliplus)
			{
				$valPeli=1;
			}
			else
			{
				$valPeli=0;
			}
		}
		else
		{
			$valPeli=1;
		}
		if($valPeli==1)
		{
			if($isEntire==1)
			{
$registrosPantones=$_SESSION['pantonesSend'];//Envia el numero de registros de pantone que va a modificar

$productoscliente=$MySQLiconn->real_escape_string($_POST['comboPCliente']);
$bspp=$MySQLiconn->real_escape_string($_POST['comboBSPP']);
$sustratos=$MySQLiconn->real_escape_string($_POST['comboSustratos']);
$tipo = $MySQLiconn->real_escape_string($_POST['comboDisenios']);
$codigoImpresion=$MySQLiconn->real_escape_string($_POST['codigoImpresion']);
if(isset($_POST['hologram']))
{
	$hologram=$MySQLiconn->real_escape_string($_POST['hologram']);

	$MySQLiconn->query("UPDATE hlogpproducto SET tipo='$hologram',consumo='".$_POST['consumoH']."' WHERE impresion='".$_POST['descripcionImpresion']."'");

}
else
{
	$hologram="";
}

$Sic =$MySQLiconn->query("UPDATE impresiones set codigoCliente='$productoscliente',nombreBanda='$bspp',sustrato='$sustratos',holograma='$hologram' where codigoImpresion='$codigoImpresion'");

$SQL = $MySQLiconn->query("SELECT* FROM juegoparametros where identificadorJuego=(SELECT juegoparametros from tipoproducto where alias=(SELECT tipo from producto where descripcion='$tipo'))");
while($ram = $SQL->fetch_array()){
	$map=$ram['nombreparametro'];
	${$ram['nombreparametro']}=$MySQLiconn->real_escape_string($_POST[$map]);

	$seil =$MySQLiconn->query("UPDATE impresiones set ".$map."='".${$ram['nombreparametro']}."'  where id=".$_GET['edit']."");

}
for($i=0;$i<$_POST['tintas'];$i++)
{
	$stosPantones=$_POST['comboPantones'.$i];
	$disolvent=$_POST['disolvente'.$i];
	$consum=$_POST['consumoPantone'.$i];
	$codigoCapa="C".$_POST['codigoImpresion'].$i;
	$MySQLiconn->query("UPDATE pantonepcapa SET descripcionPantone='".$stosPantones."', disolvente='".$disolvent."', consumoPantone=".$consum." where codigoCapa='".$codigoCapa."'");

}

	//$SQL = $MySQLiconn->query("UPDATE impresiones SET descripcionImpresion='Tacos deliciosos' where id=2");*/

if(!$SQL)
{
	 	//Mandar el mensaje de error
	echo $MySQLiconn->error;
} else{

	 //Mandamos un mensaje de exito:
	// echo"<script>alert('Se modificó correctamente')</script>";
	header("Location: Producto_Impresiones.php?edit=".$_GET['edit']."");
	echo "<div class='alert alert-success alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Operación exitosa</strong> ,modificado correctamente
		</div>";
}
}
else
{
	echo "<div class='alert alert-warning alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Datos incorrectos</strong> , El número de repeticiones resultantes no es un entero.
		</div>";
}


}
else
{
	echo "<div class='alert alert-warning alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Datos incorrectos</strong> , Las dimensiones de la anchura del producto no son coherentes.
		</div>";
}
}
else
{
	echo "<div class='alert alert-warning alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Datos incorrectos</strong> , El ancho de la pelicula no puede ser menor que el ancho del sustrato.
		</div>";
}


}
if(isset($_GET['imp']))
{
	$_SESSION['descripcion']= $_GET['imp'];
	header("Location: Producto_Impresiones.php");
}
if(isset($_GET['cil']))
{
	//$_SESSION['descripcionCil']=$_GET['cil'];
	$MySQLiconn->query("UPDATE cache set dato='".$_GET['cil']."' where id=3");
	header("Location: Producto_JuegosCilindros.php");
	 //echo "<META HTTP-EQUIV='REFRESH' CONTENT='0; URL=Producto_juegoscilindros.php'>";
}
if(isset($_GET['acti']))
{
	$MySQLiconn->query("UPDATE impresiones set baja=1 where id=".$_GET['acti']."");
	header("Location: Producto_Impresiones.php");
}
if(isset($_GET['delfin']))
{
	$SQL=$MySQLiconn->query("DELETE from  impresiones where id=".$_GET['delfin']."");
	if(!$SQL)
	{
		echo "<div class='alert alert-danger alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Error</strong> , Hubo un problema eliminar
		</div>
		";
	}
	else
	{
		echo "<div class='alert alert-success alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Operación exitosa</strong> , Eliminado correctamente
		</div>
		";
	}
}



if(isset($_GET['cons']))
{
	//$_SESSION['descripcionCons']=$_GET['cons'];
	echo "<script>window.location= 'Producto_Consumos.php?descripcionCons=".$_GET['cons']."';</script>";
}

if(isset($_GET['imp'])){
	$_SESSION['cirel']= $_GET['imp'];
	header("Location: Producto_Cireles.php");	
} 


if(isset($_GET['suaje'])){
	$_SESSION['suaje']= $_GET['suaje'];
	header("Location: Producto_Suaje.php");	
} 

