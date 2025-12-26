<?php
function registrarCdgBarras($cdg,$divisions,$noop)
{
	$MySQLiconn = new mysqli('localhost', 'kronos','gl123','saturno');
	//$producto=substr($cdg,0,3);
	//$proceso=substr($cdg,0,3);
	$idRegistro=substr($cdg,0,4);
	$noProceso=substr($cdg,4,2);
	$idLote=substr($cdg,6);
	$newoop=$noop;

	

$VAl=$MySQLiconn->query("SELECT codigo from codigosbarras where codigo='$cdg'");
$rem=$VAl->fetch_array();
if(empty($rem['codigo']))
{
$SA=$MySQLiconn->query("SELECT nombreProducto as producto,(SELECT referenciaLote from lotes where idLote='$idLote') as lote,(SELECT tipo from lotes where idLote='$idLote') as tipo from produccion where juegoLotes=(select juegoLote from lotes where idLote='$idLote')");
	$row=$SA->fetch_array();
	$producto=$row['producto'];
	$lote=$row['lote'];
	$tipo=$row['tipo'];
	$divisions=round($divisions);

	$Pro=$MySQLiconn->query("SELECT descripcionProceso FROM juegoprocesos where numeroProceso='$noProceso' and identificadorJuego=(SELECT juegoprocesos FROM tipoproducto WHERE tipo='$tipo')");
	$act=$Pro->fetch_array();
	$proceso=$act['descripcionProceso'];

	$SQL=$MySQLiconn->query("INSERT into codigosbarras(codigo,producto,proceso,lote,noProceso,noop,divisiones,tipo) values('$cdg','$producto','$proceso','$lote','$noProceso','$newoop','$divisions','$tipo')");
}
else
{
	
}
	

}
function parsearCodigo($cod)
{
	$MySQLi= new mysqli('localhost', 'kronos','gl123','saturno');
	/*$producto=substr($cod,0,3);
	$proceso=substr($cod,3,3);
	$idRegistro=substr($cod,6,4);
	$noProceso=substr($cod,10,2);
	$noop=substr($cod,12);*/

	//$proceso=substr($cod,0,3);
	$idRegistro=substr($cod,0,4);
	$noProceso=substr($cod,4,2);
	$idLote=substr($cod,6);
	$noop=$idLote;
	$datos="";
	

	$VM=$MySQLi->query("SELECT codigo,noop from codigosbarras where codigo='$cod' and baja=1");
	if(!$VM){
		echo $MySQLi->error;
	}

	$rex=$VM->fetch_array();

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


$SAM=$MySQLi->query("SELECT nombreProducto as producto,tipo,(SELECT referenciaLote from lotes where idLote='$noop') as lote,(SELECT noop from lotes where idLote='$noop') as noop from produccion where juegoLotes=(select juegoLote from lotes where idLote='$noop')" );
	$rows=$SAM->fetch_array();
	$producto=$rows['producto'];
	$lote=$rows['lote'];
	$noop=$newOp;
	$noopOrigen=$rows['noop'];
	$tipo=$rows['tipo'];

	$Pro=$MySQLi->query("SELECT descripcionProceso FROM juegoprocesos where numeroProceso='$noProceso' and identificadorJuego=(SELECT juegoprocesos FROM tipoproducto WHERE tipo='$tipo')");
	$act=$Pro->fetch_array();
	$proceso=$act['descripcionProceso'];

	$datos=$producto."|".$proceso."|".$lote."|".$noProceso."|".$noop."|".$noopOrigen.'|'.$tipo.'|'.$idLote;

}
else
{
	$datos=" ";
}
	return $datos;

}

function calcularUnidades($proceso,$noop)
{
	$MySQLi= new mysqli('localhost', 'kronos','gl123','saturno');
	$SIM=$MySQLi->query("SELECT *,(select producto from `pro$proceso` where noop like '$noop%' limit 1) as producto,(SELECT tipo from produccion where nombreProducto=(select producto from `pro$proceso` where noop like '$noop' limit 1) limit 1) as tipo from juegoparametros where identificadorJuego=(SELECT packparametros from procesos where descripcionProceso='$proceso') and baja=1 and numParametro='G'");
	$longitud=false;
	$producto="";
	$unidades=0;
	$tipo="";
	while($row=$SIM->fetch_array())
	{
		if($row['nombreparametro']=="longitud")
		{
			$longitud=true;
		}
		$producto=$row['producto'];
		$tipo=$row['tipo'];
	}


if($tipo!="BS")
{
	$SN=$MySQLi->query("SELECT `pro$proceso`.*,impresiones.alturaEtiqueta,impresiones.millaresPorPaquete,juegoscilindros.alturaReal,juegoscilindros.repAlPaso from `pro$proceso` inner join impresiones on impresiones.descripcionImpresion=`pro$proceso`.producto inner join juegoscilindros on juegoscilindros.descripcionImpresion=`pro$proceso`.producto where `pro$proceso`.producto='$producto' and `pro$proceso`.noop like '$noop'");//Trae todos los parametros de ese registro para calcular las unidades
}
else
{
	$SN=$MySQLi->query("SELECT `pro$proceso`.*,bandaspp.anchuraLaminado,bandaseguridad.anchura from `pro$proceso` inner join bandaspp on bandaspp.nombreBSPP=`pro$proceso`.producto inner join bandaseguridad on bandaspp.identificadorBS=bandaseguridad.nombreBanda where `pro$proceso`.producto='$producto' and `pro$proceso`.noop like '$noop'");//Trae todos los parametros de ese registro para calcular las unidades
}
	
	$obj=$SN->fetch_array();
	if($longitud==true)//si es un parametro con longitud,se hace una serie de calculos dependiendo del proceso
	{

		 if($proceso=="impresion")
		{
			$unidades=($obj['longitud']*($obj['repAlPaso']/$obj['alturaReal']));
		}
		else if($proceso=="refilado")
		{
			if($tipo!="BS")
			{
	$unidades=($obj['longitud']/$obj['alturaReal']);
			}
		
			else
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
			$unidades=($obj['longitud']/$obj['alturaReal']);
		}
	}
			else
	{
		$MY=$MySQLi->query("SELECT codigo from codigosbarras where noop='$noop' order by codigo desc limit 1");
		$co=$MY->fetch_array();

		$arrayDatos=parsearCodigo($co['codigo']);
		$arrayDatos=explode("|",$arrayDatos);
		$producto=$arrayDatos[0];
		$proceso=$arrayDatos[1];
		$noProces=$arrayDatos[3]+1;//Si no es alguno de esos procesos,como es en el caso de revision,las unidades se ajustan al proceso anterior

		$UAN=$MySQLi->query("SELECT descripcionProceso from juegoprocesos where identificadorJuego=(select juegoprocesos from tipoproducto where alias=(select tipo from producto where descripcion=(select descripcionDisenio from impresiones where descripcionImpresion='".$producto."'))) and numeroProceso='$noProces'-1");
		$rev=$UAN->fetch_array();

		$anterior=$rev['descripcionProceso'];
		$AN=$MySQLi->query("SELECT unidades from prorevision  where noop like '$noop%'");
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

function parsearCodigoEmpaque($cod)
{
	$MySQLi= new mysqli('localhost', 'kronos','gl123','saturno');
	$producto=substr($cod,0,3);
	$proceso=substr($cod,3,3);
	$idEmpaque=substr($cod,6,4);
	$noProceso=0;
	$datos="";
	

$VM=$MySQLi->query("SELECT codigo from codigosbarras where codigo='$cod' and baja=1");
if(!$VM)
{
	echo $MySQLi->error;
}

$rex=$VM->fetch_array();

if(!empty($rex['codigo']))
{


$SAM=$MySQLi->query("SELECT descripcionImpresion as producto,(SELECT descripcionProceso from juegoprocesos where id='$proceso') as proceso from impresiones where id='$producto'");
	$rows=$SAM->fetch_array();
	$producto=$rows['producto'];
	$proceso=$rows['proceso'];
	if($proceso=="caja" || $proceso=="rollo")
	{
$SA=$MySQLi->query("SELECT referencia from $proceso where id='$idEmpaque'");
	$ros=$SA->fetch_object();
	$empaqueRef=$ros->referencia;
	$datos=$producto."|".$proceso."|".$empaqueRef."|".$noProceso;
}
else
{

	$datos="slapperro";
}

}
else
{
	$datos="";
}
	return $datos;

}

function registrarCodigoEmpaque($cdg,$empaque)
{
	$MySQLiconn = new mysqli('localhost', 'kronos','gl123','saturno');
	$producto=substr($cdg,0,3);
	$proceso=substr($cdg,3,3);
	$idEmpaque=substr($cdg,6,4);
	$noProceso=0;
	$refEmpaque;
	

$VAl=$MySQLiconn->query("SELECT codigo from codigosbarras where codigo='$cdg'");
$rem=$VAl->fetch_array();
$returna;
if(empty($rem['codigo']))
{
$SA=$MySQLiconn->query("SELECT id,(SELECT referencia from $empaque where id='$idEmpaque') as empaque,(SELECT descripcionImpresion from impresiones where id='$producto') as producto,(SELECT descripcionProceso from juegoprocesos where id='$proceso') as proceso from impresiones where id=1");
	$row=$SA->fetch_object();
	$producto=$row->producto;
	$proceso=$row->proceso;
	$refEmpaque=$row->empaque;


	$MySQLiconn->query("INSERT into codigosbarras(codigo,producto,proceso,lote,noProceso) values('$cdg','$producto','$proceso','$refEmpaque','$noProceso')");
}
else
{
	
} 
}

?>
