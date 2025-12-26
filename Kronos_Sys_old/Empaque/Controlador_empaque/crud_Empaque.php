<?php
@session_start();

include 'db_Producto.php';
include  'functionsEmpaque.php';
error_reporting(0);

$arrTemporal=array();


/* Inicio Código buscar */
//Si se dio clic en guardar:

if(!empty($_GET['comboEmpaque']))
{
	$q=$_GET['comboEmpaque'];
	//$MySQLiconn->query("UPDATE cache SET dato='$q' WHERE id=8");
	if($q!=$_SESSION['empaqueActual'])
	{
		$_SESSION['array']=array();
	$_SESSION['estatus']="";
	}
	
}
if(isset($_GET['del']))
{
		$_SESSION['array']=array();
		$_SESSION['estatus']="";
		echo "<script>window.open('../Empaque_Ajuste.php)</script>";

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
	$empaque=$_GET['envio'];
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
	$tipo="";
	$fallas=0;
	$longitud=0;
	$codigoBarras="";
	if(count($_SESSION['array'])>0)
	{
		$cod=$_SESSION['array'][0];
		$elementos=count($_SESSION['array']);
		$arrayDatos=parsearCodigo($cod);
		$arrayDatos=explode('|',$arrayDatos);
		$producto=$arrayDatos[0];
$Pa=$MySQLiconn->query("SELECT descripcionProceso,(SELECT nombreParametro from juegoparametros where numParametro='C' and identificadorJuego=(SELECT packParametros from procesos where descripcionProceso=(SELECT descripcionProceso from juegoprocesos where numeroProceso!=0 and identificadorJuego=(SELECT juegoprocesos from tipoproducto where alias=(SELECT tipo from producto where descripcion=(SELECT descripcionDisenio from impresiones where descripcionImpresion='$producto'))) and baja=1 order by numeroProceso desc limit 1))) as referencial from juegoprocesos where numeroProceso!=0 and identificadorJuego=(SELECT juegoprocesos from tipoproducto where alias=(SELECT tipo from producto where descripcion=(SELECT descripcionDisenio from impresiones where descripcionImpresion='$producto'))) and baja=1 order by numeroProceso desc limit 1");
	$Pe=$Pa->fetch_array();
	$ultimoProceso=$Pe['descripcionProceso'];
	$referencial=$Pe['referencial'];
		if($empaque=="caja")
		{
			$SQL=$MySQLiconn->query("INSERT into caja(producto)
		values('$producto')");
			//$ultimo_id =$MySQLiconn->insert_id;
		}
		else if($empaque=="rollo")
		{
			$SQL=$MySQLiconn->query("INSERT into rollo(producto)
		values('$producto')");
		}

	for($i=0;$i<count($_SESSION['array']);$i++)
	{
		$cod=$_SESSION['array'][$i];
		$arrayDatos=parsearCodigo($cod);
		$arrayDatos=explode('|',$arrayDatos);
		$noop=$arrayDatos[4];
		$nuevoProducto=$arrayDatos[0];
		$proceso=$arrayDatos[1];
		$tipo=$arrayDatos[6];
		$row="";
		if($producto==$nuevoProducto)
		{
			if($empaque=="rollo")
		{
	$SQL=$MySQLiconn->query("SELECT MAX(id) as id,count(id) as numero,(SELECT id from impresiones where descripcionImpresion='$producto') as idProducto,(SELECT id from juegoprocesos where descripcionProceso='$empaque' and identificadorJuego=(SELECT juegoprocesos from tipoproducto where alias=(SELECT tipo from producto where descripcion=(SELECT descripcionDisenio from impresiones where descripcionImpresion='$producto'))) and baja=1) as proceso,(SELECT millaresPorPaquete from impresiones where descripcionImpresion='$producto') as piezas,(SELECT unidades from `pro$proceso` where noop='$noop' and `pro$proceso`.tipo='$tipo') as unidades,(SELECT longitud from `pro$proceso` where noop='$noop' and `pro$proceso`.tipo='$tipo') as longitud from $empaque where producto='$producto'");
	$row=$SQL->fetch_array();
	$idProceso=$row['proceso'];
	$idProducto=$row['idProducto'];
	$idEmpaque=$row['id'];
	$unidades=$row['unidades'];
	$longitud=$row['longitud'];
	$referencia=$row['numero'];

		}
		else
		{
				$SQL=$MySQLiconn->query("SELECT MAX(id) as id,count(id) as numero,(SELECT id from impresiones where descripcionImpresion='$producto') as idProducto,(SELECT id from juegoprocesos where descripcionProceso='$empaque' and identificadorJuego=(SELECT juegoprocesos from tipoproducto where alias=(SELECT tipo from producto where descripcion=(SELECT descripcionDisenio from impresiones where descripcionImpresion='$producto'))) and baja=1) as proceso,(SELECT millaresPorPaquete from impresiones where descripcionImpresion='$producto') as piezas from $empaque where producto='$producto'");
				$row=$SQL->fetch_array();
				$idProceso=$row['proceso'];
				$idProducto=$row['idProducto'];
				$idEmpaque=$row['id'];
				$piezas=$row['piezas']*1000;
				$referencia=$row['numero'];
		}
	
		if($empaque=="caja")
		{
			$piezasTotales=($elementos*$piezas)/1000;
			$SQL=$MySQLiconn->query("INSERT into ensambleempaques(referencia,producto,piezas,codigo,tipoEmpaque)
		values('C$referencia','$producto','$piezas','$cod','$empaque')");
			$MySQLiconn->query("UPDATE `pro$ultimoProceso` set total=0 where $referencial='$cod'");
			$MySQLiconn->query("UPDATE codigosbarras set baja=0 where codigo='$cod'");
			
		}
		else if($empaque=="rollo")
		{
			$longitudTotal=$longitud+$longitudTotal;
			$piezasTotales=$piezasTotales+$unidades;
			$SQL=$MySQLiconn->query("INSERT into ensambleempaques(referencia,producto,longitud,piezas,codigo,tipoEmpaque)
		values('Q$referencia','$producto','".$longitud."','$unidades','$cod','$empaque')");
			
			$MySQLiconn->query("UPDATE `pro$proceso` set total=0 where noop='$noop' and tipo='$tipo'");
			$MySQLiconn->query("UPDATE codigosbarras set baja=0 where codigo='$cod'");
		}

		}
		else
		{
			$fallas++;
		}
	}
	if($fallas<1)
	{
		 $codigoBarras=str_repeat("0",3 - strlen($idProducto)).$idProducto;
      $codigoBarras=$codigoBarras.str_repeat("0",3 - strlen($idProceso)).$idProceso;
      $codigoBarras=$codigoBarras.str_repeat("0",4 - strlen($idEmpaque)).$idEmpaque;
 	  $divisiones = 0;

	if($empaque=="caja")
		{
			$SQL=$MySQLiconn->query("UPDATE caja set referencia='C$referencia',producto='$producto',noElementos='$elementos',piezas='$piezasTotales',codigo='$codigoBarras'  where id=(select id from (select caja.id from caja order by caja.id desc limit 1) as consulta)");
			$_SESSION['etiquetasInd']=$codigoBarras;
			$_SESSION['estatus']='*'.$empaque." '"."C".$referencia."' Agregado corrrectamente" ;
		}
		else if($empaque=="rollo")
		{
			$piezasTotales=number_format($piezasTotales,3);
			$SQL=$MySQLiconn->query("UPDATE rollo set referencia='Q$referencia',producto='$producto',noElementos='$elementos',piezas='$piezasTotales',longitud='$longitudTotal',codigo='$codigoBarras' where id=(select id from (select rollo.id from rollo order by rollo.id desc limit 1) as consulta)");//antes era Q por queso,pero ahora se maneja por rollo 'R'
			$_SESSION['etiquetasInd']=$codigoBarras;
			$_SESSION['estatus']='*'.$empaque." '"."Q".$referencia."' Agregado corrrectamente" ;
		}
		 registrarCodigoEmpaque($codigoBarras,$empaque);
		$_SESSION['array']=array();
		
		echo"<script>alert('Registrado correctamente')</script>";
$documento="etiq".$empaque."In";
$name_document = '../../Produccion/etiquetas/'.$documento.'.php';


if (file_exists($name_document)) {
	//echo "<script>Location.reload(true);</script>";
	$_SESSION['banderin']="";
	echo "<script>window.open('../Empaque_Ajuste.php?Buscar=$codigoBarras&stat=1', '_blank')</script>";
} else {
	echo "<script>window.open('../../Produccion/generarEtiquetasDinamicas.php', '_blank');</script>";
}


echo("<script>window.location = '../Empaque_Ensamble.php';</script>");

	}
	else
	{
		$_SESSION['estatus']=$fallas." "."Registros no coincidieron con el empaque,operación no exitosa";
		$SQL=$MySQLiconn->query("DELETE from $empaque where id='$idEmpaque'");
		$_SESSION['array']=array();
		echo("<script>window.location = '../Empaque_Ensamble.php';</script>");
	}

	}
	else
	{
		echo("<script>alert('¡El empaque no puede estar vacío!')';</script>");
	}
	
}

if(!empty($_GET['e']))
{
	$e=$_GET['e'];
	$_SESSION['cod']="";
	$empaque=$_SESSION['empaqueActual'];
	$_SESSION['estatus']=$empaque;
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
                    alias=(select tipo from producto where 
                          descripcion=(select descripcionDisenio from impresiones where descripcionImpresion='".$producto."'))) and numeroProceso='".$noProces."'+1 and baja=1) as procesoSiguiente from juegoparametros where numParametro='C' and identificadorJuego=(SELECT packParametros from procesos where descripcionProceso=(SELECT descripcionProceso from juegoprocesos where identificadorJuego=(select juegoprocesos from tipoproducto where alias=(select tipo from producto where 
                          descripcion=(select descripcionDisenio from impresiones where descripcionImpresion='".$producto."'))) and numeroProceso='".$noProces."'+1 and baja=1))");


		$He=$SQL->fetch_array();
		$referencial=$He['nombreParametro'];
		$sig=$He['procesoSiguiente'];
		if(!empty($sig))//si no hay proceso siguiente,significa que se tiene que evaluar que no haya sido registrado ese codigo ya.

		{
			$SQL=$MySQLiconn->query("SELECT codigo,(select codigo from ensambleempaques where codigo='$e') as existente,(SELECT producto from pro".$sig." where $referencial='$e' limit 1) as disponible from codigosbarras where codigo='$e' and baja=1");
		}
		else
		{
			$SQL=$MySQLiconn->query("SELECT codigo,(select codigo from ensambleempaques where codigo='$e') as existente from codigosbarras where codigo='$e' and baja=1");
		}
		$row=$SQL->fetch_array();
		if(empty($row['disponible']))
		{
			if(empty($row['existente']))
		{
			
				if(!empty($row['codigo']))

				{
					if($proceso=="corte" && $empaque=="caja")
					{
						$valid=1;
					}
					else if($proceso!="corte" && $empaque=="rollo")
					{
						$valid=1;
					}
				
						for($i=0;$i<count($_SESSION['array']);$i++)
					{
			//echo '<p>'.$_SESSION['array'][$i].'</p><br>';

						if($_SESSION['array'][$i]==$e)
						{
							$already=1;
						}
							array_push($arrTemporal,$_SESSION['array'][$i]);
						
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
							if($tipo!="BS")
							{
								array_push($arrTemporal,$e);
								$_SESSION['estatus']="";
							}
							else
							{
								$_SESSION['estatus']="La banda de seguridad no se puede empacar";
							}
							
						}
						else
						{
							$_SESSION['estatus']="Código inválido";
						}
						
					}
					else
					{
						$_SESSION['estatus']="Tipo de empaque incorrecto";
					}
					
					}
					else
					{
						$_SESSION['estatus']="El código '".$e ."' ya esta agregado";
					}
					
					$_SESSION['array']=$arrTemporal;
					
				}
				else
				{
					$_SESSION['estatus']="El código '".$e ."' no puede empacarse en este momento";
				}
			
		}
		else
		{
			$_SESSION['estatus']="El código '".$e ."' ya fue empacado";
		}
	}
else
{
	$_SESSION['estatus']="El código '".$e ."' ya no puede ser empacado porque cambió de proceso";
		
}
		
	
}
		
	else
	{
		$_SESSION['estatus']="El código '".$e ."' no esta disponible";
	}
	

	
	

}


/* Inicio Código Atualizar  */

/* Fin Código Atualizar */
