<?php
@session_start();

include 'db_produccion.php';
include  'functions.php';
//error_reporting(0);

$arrTemporal=array();


/* Inicio Código buscar */
//Si se dio clic en guardar:

if(!empty($_GET['q']))
{
	$q=$_GET['q'];
	$MySQLiconn->query("UPDATE cache SET dato='$q' WHERE id=8");
	$_SESSION['arrayBS']=array();
	$_SESSION['estatus']="";
}

if(isset($_POST['find']))
{
	//Pasamos los parametros por medio de post
	//$_SESSION['empaqueActual'] = $MySQLiconn->real_escape_string($_POST['comboEmpaque']);

}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['envio']))//Se registran los paquetes agregados en la base de datos
{
	//$empaque=$_GET['envio'];
	$referencia="";
	$producto="";
	$noElementos="";
	$piezas="";
	$longitudTotal=0;
	$piezasTotales=0;
	$referencia="";
	$idProceso="";
	$idProducto="";
	$idEmpaque="";
	$fallas=0;
	$longitud=0;
	$codigoBarras="";
	$pesoTotal=0;
	$banderasTotal=0;
	$maxnoop="";
	$idLote="";
	$empleado=$_GET['cmbEmpleados'];
	if(count($_SESSION['arrayBS'])>1)
	{
		$cod=$_SESSION['arrayBS'][0];
		$elementos=count($_SESSION['arrayBS']);
		$arrayDatos=parsearCodigo($cod);
		$arrayDatos=explode('|',$arrayDatos);
		$producto=$arrayDatos[0];
		$idLote=$arrayDatos[7];
$Pa=$MySQLiconn->query("SELECT descripcionProceso,(SELECT nombreParametro from juegoparametros where numParametro='C' and identificadorJuego=(SELECT packParametros from procesos where descripcionProceso=(SELECT descripcionProceso from juegoprocesos where numeroProceso!=0 and identificadorJuego=(SELECT juegoprocesos from tipoproducto where alias='BS') and baja=1 order by numeroProceso desc limit 1))) as referencial from juegoprocesos where numeroProceso!=0 and identificadorJuego=(SELECT juegoprocesos from tipoproducto where alias='BS') and baja=1 order by numeroProceso desc limit 1");
	$Pe=$Pa->fetch_array();
	$ultimoProceso=$Pe['descripcionProceso'];
	$referencial=$Pe['referencial'];
		
	for($i=0;$i<count($_SESSION['arrayBS']);$i++)
	{
		$cod=$_SESSION['arrayBS'][$i];
		$arrayDatos=parsearCodigo($cod);
		$arrayDatos=explode('|',$arrayDatos);
		$noop=$arrayDatos[4];
		$noopOrigen=$arrayDatos[5];
		$nuevoProducto=$arrayDatos[0];
		$proceso=$arrayDatos[1];
		$row="";
		if($producto==$nuevoProducto)
		{
		
	$SQL=$MySQLiconn->query("SELECT (SELECT noop from prosliteo where id=(SELECT MAX(id) FROM prosliteo WHERE producto='$producto' and noop like '$noopOrigen-%')) as id,unidades,longitud,peso,bandera,lote,amplitud,(SELECT id from juegoprocesos where descripcionProceso='$empaque' and identificadorJuego=(SELECT juegoprocesos from tipoproducto where alias='BS') and baja=1) as proceso  from prosliteo where producto='$producto' and noop='$noop' and rollo_padre=0");

	
	$row=$SQL->fetch_array();
	$idProceso=$row['proceso'];
	$maxnoop=$row['id'];
	$frag=explode('-',$maxnoop);
	$nooplast=(int)$frag[2]+1;
	$maxnoop=$frag[0].'-'.$frag[1].'-'.$nooplast;
	$unidades=$row['unidades'];
	
	$longitud=$row['longitud'];
	$referencia=$row['numero'];
	$codPadre=$row['lote'];
	$amplitud=$row['amplitud'];
	$banderas=$row['bandera'];
	$peso=$row['peso'];
			$longitudTotal=$longitud+$longitudTotal;
			$piezasTotales=$piezasTotales+$unidades;
			$pesoTotal=$pesoTotal+$peso;
			$banderasTotal=$banderasTotal+$banderas;
			
			
			$MySQLiconn->query("UPDATE `pro$proceso` set total=0 where noop='$noop' and tipo='BS'");
			$MySQLiconn->query("UPDATE codigosbarras set baja=0 where codigo='$cod' and tipo='BS'");
	

		}
		else
		{
			$fallas++;
		}
	}
	if($fallas<1)
	{
     $codigoBarras=generarCodigoBarras($idProceso,3,$idLote,0,$maxnoop);

			$piezasTotales=number_format($piezasTotales,3);

			$SQL=$MySQLiconn->query("INSERT into prosliteo(producto,noop,unidades,operador,lote,longitud,amplitud,peso,bandera,tipo,rollo_padre)
		values('$producto','$maxnoop','".$piezasTotales."','".$empleado."','$codPadre','$longitudTotal','$amplitud','$pesoTotal','banderasTotal','BS','0')");

			$_SESSION['etiquetasInd']=$codigoBarras;
			$_SESSION['estatus']="* Disco Agregado corrrectamente" ;

		$_SESSION['arrayBS']=array();
		
		echo"<script>alert('Registrado correctamente')</script>";
//$documento="etiq".$empaque."In";
$name_document = '../etiquetas/etiqsliteoIn.php';


	//echo "<script>Location.reload(true);</script>";
	echo "<script>window.open('$name_document?etiquetasInd=$codPadre', '_blank')</script>";



echo("<script>window.location = '../unionBS.php';</script>");

	}
	else
	{
		$_SESSION['estatus']=$fallas." "."Registros no coincidieron con el empaque,operación no exitosa";
		//$SQL=$MySQLiconn->query("DELETE from $empaque where id='$idEmpaque'");
		//$_SESSION['arrayBS']=array();
		echo("<script>window.location = '../unionBS.php';</script>");
	}

	}
	else
	{
		echo("<script>alert('¡No tienes nada que juntar!');</script>");
		echo("<script>window.location = '../unionBS.php';</script>");
	}
	
}
if(isset($_GET['salto']))
{
	if(count($_SESSION['arrayBS'])>0)
	{
		for($i=0;$i<count($_SESSION['arrayBS']);$i++)
		{

			$cod=$_SESSION['arrayBS'][$i];
			$arrayDatos=parsearCodigo($cod);
			$arrayDatos=explode('|',$arrayDatos);
			$noop=$arrayDatos[4];
			$noopOrigen=$arrayDatos[5];
			$producto=$arrayDatos[0];
			$proceso=$arrayDatos[1];
			$MySQLiconn->BEGIN_TRANSACTION();
			mysqli_autocommit($MySQLiconn,FALSE);

			$ST1=$MySQLiconn->query("UPDATE prosliteo SET total=0 WHERE noop='".$noop."' and tipo='BS'");
			$SQL=$MySQLiconn->query("SELECT unidades,longitud FROM prosliteo WHERE noop='".$noop."' and tipo='BS'");
			$row=$SQL->fetch_array();
			$ST2=$MySQLiconn->query("INSERT INTO baja_BS (codigo,tipo,longitud,producto,unidades,proceso,empleado) values('$cod','BS','".$row['longitud']."','$producto','".$row['unidades']."','sliteo','".$_GET['cmbEmpleados']."')");
			if(!$ST1 || !$SQL || !$ST2)
			{
				$MySQLiconn->ROLLBACK();
				/*if(!$ST1)
				{
					echo"<script>alert('Salio mal la primero');</script>";
				}
				else if(!$ST1)
				{
					echo"<script>alert('Salio mal la segunda');</script>";
				}
				else if(!$ST1)
				{
					echo"<script>alert('Salio mal la tercera');</script>";
				}*/
				echo"<script>alert('Algo salió mal durante la transacción,consulte al encargado de TI');</script>";
				echo("<script>window.location = '../bajaBS.php';</script>");
			}
			else
			{
				$MySQLiconn->COMMIT();
				
				
			}

		}
		echo"<script>alert('Baja exitosa');</script>";
		echo("<script>window.location = '../bajaBS.php';</script>");
		$_SESSION['arrayBS']=array();
	}

}

if(!empty($_GET['e']))
{
	$e=$_GET['e'];
	$_SESSION['cod']="";
	$_SESSION['estatus']="";
	$a=parsearCodigo($e);
	if($a!="")
	{
		$producto=explode('|',$a);
		$array=$a;
		$proceso=$producto[1];
		$noProces=$producto[3];
		$producto=$producto[0];
		

		$already=0;
		$valid=0;
		$SQL=$MySQLiconn->query("SELECT nombreParametro,(SELECT descripcionProceso from juegoprocesos where identificadorJuego=(select juegoprocesos from tipoproducto where 
                    alias='BS') and numeroProceso='".$noProces."'+1 and baja=1) as procesoSiguiente from juegoparametros where numParametro='C' and identificadorJuego=(SELECT packParametros from procesos where descripcionProceso=(SELECT descripcionProceso from juegoprocesos where identificadorJuego=(select juegoprocesos from tipoproducto where alias='BS') and numeroProceso='".$noProces."'+1 and baja=1))");

		

		$He=$SQL->fetch_array();
		$referencial=$He['nombreParametro'];
		$sig=$He['procesoSiguiente'];
		if(!empty($sig))//si no hay proceso siguiente,significa que se tiene que evaluar que no haya sido registrado ese codigo ya.
		{
			$SQL=$MySQLiconn->query("SELECT codigo,(select codigo from profusion where disco='$e') as existente,(SELECT producto from pro".$sig." where $referencial='$e' limit 1) as disponible from codigosbarras where codigo='$e' and baja=1");
		}
		else
		{
			$SQL=$MySQLiconn->query("SELECT codigo,(select codigo from profusion where disco='$e' limit 1) as existente from codigosbarras where codigo='$e' and baja=1");
		}
		$row=$SQL->fetch_array();
		if(empty($row['disponible']))
		{
		
			
				if(!empty($row['codigo']))

				{

					if($proceso=="sliteo")
					{
						$valid=1;
					}
					
				
						for($i=0;$i<count($_SESSION['arrayBS']);$i++)
					{
			//echo '<p>'.$_SESSION['arrayBS'][$i].'</p><br>';

						if($_SESSION['arrayBS'][$i]==$e)
						{
							$already=1;
						}
							array_push($arrTemporal,$_SESSION['arrayBS'][$i]);
						
					}
					if($already==0)
					{
							if($valid==1)
					{
						$esc=$MySQLiconn->query("SELECT noProceso,tipo FROM codigosbarras WHERE codigo='".$e."'");
						$pro=$esc->fetch_array();
						$proceso=$pro['noProceso'];
						$tipo=$pro['tipo'];
						if($proceso>0)
						{
							if($tipo=="BS")
							{
								array_push($arrTemporal,$e);
								$_SESSION['estatus']="";
							}
							else
							{
								$_SESSION['estatus']="Este codigo no corresponde a un disco";
							}
							
						}
						else
						{
							$_SESSION['estatus']="Código inválido";
						}
						
					}
					else
					{
						$_SESSION['estatus']="Tipo de proceso incorrecto";
					}
					
					
					$_SESSION['arrayBS']=$arrTemporal;
					
				}
				else
				{
					$_SESSION['estatus']="El código '".$e ."' ya esta agregado";
				}
			
		}
		else
		{
			$_SESSION['estatus']="El código '".$e ."' ya fue fusionado";
		}
	}
else
{
	$_SESSION['estatus']="El código '".$e ."' ya no puede ser fusionado porque cambió de proceso";
		
}
		
	
}
		
	else
	{
		$_SESSION['estatus']="El código '".$e ."' no esta disponible";
	}
	

	
	

}


/* Inicio Código Atualizar  */

/* Fin Código Atualizar */
