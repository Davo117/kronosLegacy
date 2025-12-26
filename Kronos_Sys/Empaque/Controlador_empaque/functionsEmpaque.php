<?php
function parsearCodigo($cod,$MySQLi)
	{
		
		$idRegistro=substr($cod,0,5);
		$noProceso=substr($cod,5,1);
		$idLote=substr($cod,6);
		$noop=$idLote;
		$datos="";
		

		$VM=$MySQLi->query("SELECT codigo,noop from tbcodigosbarras where codigo='$cod' and baja=1");
		if(!$VM){
			echo $MySQLi->error;
		}

		$rex=$VM->fetch_assoc();

		if(!empty($rex['codigo'])){
			
			/*$idRegistro=(int)$idRegistro;
			$idRegistro=$idRegistro;
			$newOp="";
			if(!empty(strlen($idRegistro)) && $idRegistro!=0){
				if(strlen(($idRegistro))>=1){
					for($i=0;$i<strlen(($idRegistro));$i++){
						$sub="-".substr($idRegistro,$i-1,1);
					$newOp=$sub.$newOp;
					}
				}
			}
			else{
				$newOp="";
			}*/
			$newOp=$rex['noop'];


			$SAM=$MySQLi->query("SELECT (SELECT descripcionImpresion from impresiones WHERE id=t.nombreProducto) as producto,(SELECT tipo from tipoproducto where id=t.tipo) as tipo,t.tipo as idtipo,t.nombreProducto as idProducto,(SELECT referenciaLote from tblotes where idLote='$noop') as lote,(SELECT noop from tblotes where idLote='$noop') as noop from tbproduccion t where t.juegoLotes=(select juegoLote from tblotes where idLote='$noop')");

			
			$rows=$SAM->fetch_assoc();
			$producto=$rows['producto'];
			$lote=$rows['lote'];
			$noop=$newOp;
			$noopOrigen=$rows['noop'];
			$tipo=$rows['tipo'];
			$idProducto=$rows['idProducto'];
			$idtipo=$rows['idtipo'];
			$Pro=$MySQLi->query("SELECT descripcionProceso FROM juegoprocesos where numeroProceso='$noProceso' and identificadorJuego=(SELECT juegoprocesos FROM tipoproducto WHERE id='$idtipo')");
			$act=$Pro->fetch_assoc();
			$proceso=$act['descripcionProceso'];

			$datos=$producto."|".$proceso."|".$lote."|".$noProceso."|".$noop."|".$noopOrigen.'|'.$tipo.'|'.$idLote.'|'.$idProducto.'|'.$idtipo;

		}
		else
		{
			$datos="";
		}
		return $datos;

	}

function calcularUnidades($proceso,$noop,$tipo,$MySQLi)
{
	/*if($MySQLi==false)
	{
		$MySQLi= new mysqli('localhost', 'kronos','gl123','saturno');
	}*/

	
	$SIM=$MySQLi->query("SELECT *,(select producto from `tbpro".$proceso."` where noop like '$noop' and tipo='$tipo' limit 1) as producto,(SELECT tipo from tbproduccion where nombreProducto=(select producto from `tbpro".$proceso."` where noop like '$noop' limit 1) limit 1) as tipo from juegoparametros where identificadorJuego=(SELECT packparametros from procesos where descripcionProceso='$proceso') and baja=1 and numParametro='G'");
	
	$longitud=false;
	$producto="";
	$unidades=0;
	while($row=$SIM->fetch_array())
	{
		if($row['nombreparametro']=="longitud")
		{
			$longitud=true;
		}
		$producto=$row['producto'];
		$tipo=$tipo;
	}


	if($tipo!="1")
	{
	$SN=$MySQLi->query("SELECT `tbpro".$proceso."`.*,impresiones.alturaEtiqueta,impresiones.anchoPelicula as anchura,impresiones.millaresPorPaquete,juegoscilindros.alturaReal,juegoscilindros.repAlPaso from `tbpro".$proceso."` inner join impresiones on impresiones.id=`tbpro".$proceso."`.producto inner join juegoscilindros on juegoscilindros.descripcionImpresion=`tbpro".$proceso."`.producto where `tbpro".$proceso."`.producto='$producto' and `tbpro".$proceso."`.noop like '$noop' and `tbpro".$proceso."`.tipo='$tipo' and juegoscilindros.descripcionImpresion=impresiones.id and juegoscilindros.identificadorCilindro=(SELECT juegoCilindros FROM tbproduccion WHERE juegoLotes=(SELECT juegoLote FROM tblotes WHERE noop=(SELECT noop FROM tblotes WHERE referenciaLote=any (SELECT lote FROM tbcodigosbarras WHERE noop='$noop' and tipo='$tipo') and tipo='$tipo') and tipo='$tipo'))");//Trae todos los parametros de ese registro para calcular las unidades


	if(mysqli_num_rows($SN)==0)
	{
		///Si falla, modificar juegoscireles.producto=impresiones.id por impresiones.codigo
		$SN=$MySQLi->query("SELECT `tbpro".$proceso."`.*,impresiones.alturaEtiqueta,impresiones.anchoPelicula,impresiones.anchoEtiqueta,impresiones.millaresPorPaquete,juegoscireles.ancho_plano,juegoscireles.alturaReal,juegoscireles.repeticiones from `tbpro".$proceso."` inner join impresiones on impresiones.id=`tbpro".$proceso."`.producto inner join juegoscireles on juegoscireles.descripcionImpresion=`tbpro".$proceso."`.producto where `tbpro".$proceso."`.producto='$producto' and `tbpro".$proceso."`.noop like '$noop' and `tbpro".$proceso."`.tipo='$tipo' and juegoscireles.producto=impresiones.id and juegoscireles.identificadorJuego=(SELECT juegoCireles FROM tbproduccion WHERE juegoLotes=(SELECT juegoLote FROM tblotes WHERE noop=(SELECT noop FROM tblotes WHERE referenciaLote=any (SELECT lote FROM tbcodigosbarras WHERE noop='$noop' and tipo='$tipo') and tipo='$tipo') and tipo='$tipo'))");


	}
	if(mysqli_num_rows($SN)==0)
	{
		$SN=$MySQLi->query("SELECT `tbpro".$proceso."`.*,impresiones.alturaEtiqueta as alturaReal,impresiones.anchoPelicula,impresiones.anchoEtiqueta,impresiones.anchoPelicula as anchura,impresiones.millaresPorPaquete from `tbpro".$proceso."` inner join impresiones on impresiones.id=`tbpro".$proceso."`.producto  where `tbpro".$proceso."`.producto='$producto' and `tbpro".$proceso."`.noop like '$noop' and `tbpro".$proceso."`.tipo='$tipo'");

	}

}
else
{
	$SN=$MySQLi->query("SELECT `tbpro".$proceso."`.*,bandaspp.anchuraLaminado,bandaseguridad.anchura from `tbpro".$proceso."` inner join bandaspp on bandaspp.idBSPP=`tbpro".$proceso."`.producto inner join bandaseguridad on bandaspp.identificadorBS=bandaseguridad.IDBanda where `tbpro".$proceso."`.producto='$producto' and `tbpro".$proceso."`.noop like '$noop' and `tbpro".$proceso."`.tipo='$tipo'");//Trae todos los parametros de ese registro para calcular las unidades
}
$obj=$SN->fetch_array();
	if($longitud==true)//si es un parametro con longitud,se hace una serie de calculos dependiendo del proceso
	{
		$MySQLi->query("SET @p0='".$tipo."'");
		$compr=$MySQLi->query("CALL getJuegoProcesosByType(@p0)");
		$ref=0;
		$act=0;
		while($rom=$compr->fetch_array())
		{
			if($rom['descripcionproceso']=="refilado")
			{
				$ref=$rom['numeroProceso'];
			}
			if($rom['descripcionproceso']==$proceso)
			{
				$act=$rom['numeroProceso'];
			}
		}
		$MySQLi->next_result();


		if($proceso=="impresion")
			
		{
			$unidades=($obj['longitud']*($obj['repAlPaso']/$obj['alturaReal']));
		}
		else if($proceso=="refilado")
		{
			if($tipo!="1")
			{
				$unidades=($obj['longitud']/$obj['alturaReal']);
			}
			else if($tipo=="1")
			{
				$unidades=$obj['amplitud']/$obj['anchura']*$obj['longitud'];
			}
		}
		else if($proceso=="fusion")
		{
			$unidades=($obj['longitud']/$obj['alturaReal']);
		}
		else if($proceso=="revision")
		{
			if($ref==0 || $ref<$act)
			{
				
				$unidades=($obj['longitud']/$obj['alturaReal']);

			}
			else
			{
				if(!empty($obj['repAlPaso']))
				{
					$unidades=($obj['longitud']/$obj['alturaReal'])*$obj['repAlPaso'];
				}
				else
				{
					$unidades=($obj['longitud']/$obj['alturaReal'])*$obj['repeticiones'];
				}
				
			}
			
		}
		else if($proceso=="laminado")
		{
			if($ref==0 || $ref<$act)
			{
				
				$unidades=($obj['longitud']/$obj['alturaReal']);

			}
			else
			{
				if(!empty($obj['repAlPaso']))
				{
					$unidades=($obj['longitud']/$obj['alturaReal'])*$obj['repAlPaso'];
				}
				else
				{
					$unidades=($obj['longitud']/$obj['alturaReal'])*$obj['repeticiones'];
				}
				
			}
		}
		else if($proceso=="laminado 2")
		{
			$unidades=$obj['amplitud']/$obj['anchura']*$obj['longitud'];
		}
		elseif ($proceso=="embosado") {
			$unidades=$obj['amplitud']/$obj['anchura']*$obj['longitud'];
		}
		else if($proceso=="sliteo")
		{
			$unidades=$obj['amplitud']/$obj['anchura']*$obj['longitud'];
		}
		else if($proceso=="impresion-flexografica")
		{
			$unidades=($obj['longitud']/$obj['alturaReal'])*$obj['repeticiones'];
		}
		else if($proceso=="troquelado")
		{
			if($ref==0 || $ref<$act)
			{
				if(!empty($obj['alturaReal']))
				{
					$unidades=($obj['longitud']/$obj['alturaReal']);
				}
				else
				{
					$unidades=($obj['longitud']/$obj['alturaEtiqueta']);
				}
				
			}
			else
			{
				if(!empty($obj['repAlPaso']))
				{
					$unidades=($obj['longitud']/$obj['alturaReal'])*$obj['repAlPaso'];
				}
				else
				{
					$unidades=($obj['longitud']/$obj['alturaReal'])*$obj['repeticiones'];
				}
				
			}
		}
		else if($proceso=="suajado")
		{
			$Pay=$MySQLi->query("SELECT piezas,alturaReal from suaje where identificadorSuaje=(SELECT suaje from `pro".$proceso."` where noop='$noop' and tipo='$tipo')");
			$br=$Pay->fetch_array();
			$unidades=(($obj['longitud']/($br['alturaReal']))*($br['piezas']));
		}
		else if($proceso=="foliado")
		{
			$unidades=($obj['unidades']*$obj['alturaEtiqueta']);
		}
		else if($proceso=="revision 2")
		{
			if($ref==0 || $ref<$act)
			{
				$unidades=($obj['longitud']/$obj['alturaReal']);
			}
			else
			{
				if(!empty($obj['repAlPaso']))
				{
					$unidades=($obj['longitud']/$obj['alturaReal'])*$obj['repAlPaso'];
				}
				else
				{
					$unidades=($obj['longitud']/$obj['alturaReal'])*$obj['repeticiones'];
				}
				
			}
		}
	}
	else
	{
		$MY=$MySQLi->query("SELECT codigo from tbcodigosbarras where noop='$noop' and tipo='$tipo' order by codigo desc limit 1");
		$co=$MY->fetch_array();

		$arrayDatos=parsearCodigo($co['codigo']);
		$arrayDatos=explode("|",$arrayDatos);
		$producto=$arrayDatos[0];
		$proceso=$arrayDatos[1];
		$noProces=$arrayDatos[3]+1;//Si no es alguno de esos procesos,como es en el caso de revision,las unidades se ajustan al proceso anterior

		$UAN=$MySQLi->query("SELECT descripcionProceso from juegoprocesos where identificadorJuego=(select juegoprocesos from tipoproducto where id=(select tipo from producto where ID=(select descripcionDisenio from impresiones where descripcionImpresion='".$producto."'))) and numeroProceso='$noProces'-1");
		$rev=$UAN->fetch_array();
		$anterior=$rev['descripcionProceso'];
		$AN=$MySQLi->query("SELECT unidades from `tbpro".$anterior."` where noop like '$noop' and tipo='$tipo'");
		$RAN=$AN->fetch_array();
		$unidades=$RAN['unidades'];

	}

	return $unidades;

}
 function codAntiguo($noop,$noProceso)
 {

$MySQLin= new mysqli('localhost', 'kronos','gl123','saturno');
	$VM=$MySQLin->query("SELECT codigo from codigosbarras where noop='$noop' and noProceso='".$noProceso."'-1");
$rex=$VM->fetch_array();
$cod=$rex['codigo'];


return $cod;
 }

function parsearCodigoEmpaque($cod,$MySQLi)
{
	$producto=substr($cod,0,3);
	$proceso=substr($cod,3,3);
	$idEmpaque=substr($cod,6,4);
	$noProceso=0;
	$datos="";
	

$VM=$MySQLi->query("SELECT codigo,tipo from tbcodigosbarras where codigo='$cod' and baja=1");
if(!$VM)
{
	echo $MySQLi->error;
}

$rex=$VM->fetch_array();

if(!empty($rex['codigo']))
{

$tipo=$rex['tipo'];
$SAM=$MySQLi->query("SELECT descripcionImpresion as producto,id,(SELECT descripcionProceso from procesos where id=(select proceso from tbcodigosbarras where codigo='".$cod."')) as proceso from impresiones where id='$producto'");
	$rows=$SAM->fetch_array();
	$producto=$rows['producto'];
	$proceso=$rows['proceso'];
	$id=$rows['id'];
	if($proceso=="caja" || $proceso=="rollo")
	{
$SA=$MySQLi->query("SELECT referencia from $proceso where codigo='$cod'");
	$ros=$SA->fetch_object();
	$empaqueRef=$ros->referencia;
	$datos=$producto."|".$proceso."|".$empaqueRef."|".$noProceso.'|'.$id.'|'.$tipo;
}
else
{

	$datos="slap";
}

}
else
{
	$datos="";
}
	return $datos;

}

function registrarCodigoEmpaque($cdg,$empaque,$MySQLiconn)
{
	$producto=substr($cdg,0,3);
	$proceso=substr($cdg,3,3);
	$idEmpaque=substr($cdg,6,4);
	$noProceso=0;
	$refEmpaque;
	

$VAl=$MySQLiconn->query("SELECT codigo from tbcodigosbarras where codigo='$cdg'");
$rem=$VAl->fetch_array();
//$returna;
if(empty($rem['codigo']))
{
$producto=ltrim($producto,'0');	
$idEmpaque=ltrim($idEmpaque,'0');
$SA=$MySQLiconn->query("SELECT (SELECT referencia from $empaque where codigo='$cdg') as empaque,(SELECT id from impresiones where id='$producto') as producto,(select id from tipoproducto where id=(select tipo from producto where ID=(select descripcionDisenio from impresiones where id='".$producto."'))) as tipo,descripcionProceso as proceso from juegoprocesos where id='$proceso'");
	$row=$SA->fetch_object();
	$producto=$row->producto;
	$tipo=$row->tipo;
	$refEmpaque=$row->empaque;
if($empaque=="caja")
{
	$proceso=11;
}
else if($empaque=="rollo")
{
	$proceso=12;
}

	$MySQLiconn->query("INSERT into tbcodigosbarras(codigo,producto,proceso,lote,noProceso,tipo) values('$cdg','$producto','$proceso','$refEmpaque','$noProceso','$tipo')");
}
else
{
	
} 
}
function get_last_code($codigo,$MySQLiconn)
{
	$SQL=$MySQLiconn->query("SELECT codigo FROM tbcodigosbarras WHERE noop=(select noop from tbcodigosbarras where codigo='".$codigo."') and tipo=(select tipo from tbcodigosbarras where codigo='".$codigo."') order by id desc limit 1");
	$row=$SQL->fetch_array();
	return $row['codigo'];
}

?>
