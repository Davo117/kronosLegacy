	<?php
	function registrarCdgBarras($cdg,$divisions,$noop,$MySQLiconn)
	{
		
		$idRegistro=substr($cdg,0,5);
		$noProceso=substr($cdg,5,1);
		$idLote=substr($cdg,6);
		$newoop=$noop;

		
		$VAl=$MySQLiconn->query("SELECT codigo from tbcodigosbarras where codigo='$cdg'");
		$rem=$VAl->fetch_assoc();
		if(empty($rem['codigo']))
		{
			$SA=$MySQLiconn->query("SELECT nombreProducto as producto,(SELECT referenciaLote from tblotes where idLote='$idLote') as lote,(SELECT tipo from tblotes where idLote='$idLote') as tipo from tbproduccion where juegoLotes=(select juegoLote from tblotes where idLote='$idLote')");
			$row=$SA->fetch_assoc();
			$producto=$row['producto'];
			$lote=$row['lote'];
			$tipo=$row['tipo'];
			$divisions=round($divisions);

			$Pro=$MySQLiconn->query("SELECT id as descripcionProceso from procesos where descripcionProceso=(SELECT descripcionProceso FROM juegoprocesos where numeroProceso='$noProceso' and identificadorJuego=(SELECT juegoprocesos FROM tipoproducto WHERE id='$tipo') limit 1)");

			$act=$Pro->fetch_assoc();
			$proceso=$act['descripcionProceso'];

			$generate="INSERT INTO tbcodigosbarras(codigo,producto,proceso,lote,noProceso,noop,divisiones,tipo) values('$cdg','$producto','$proceso','$lote','$noProceso','$newoop','$divisions','$tipo')";
			if ($MySQLiconn->query($generate) === TRUE) {
				$last_id = $MySQLiconn->insert_id;
				//echo "Nuevo registro insertado. El ID es: " . $last_id;
				$MySQLiconn->COMMIT();
			}
			else {
				echo "Error: " . $generate . "<br>" . $MySQLiconn->error;
			}
			

		}
		else
		{
			echo "No registró nada";
		}
		

	}
	function is_noop($codigo,$tipo,$MySQLiconn)
	{
		$SQL=$MySQLiconn->query("SELECT noop from tbcodigosbarras where noop='$codigo' and tipo='$tipo'");
		$row=$SQL->fetch_assoc();
		if(!empty($row["noop"]))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function get_code($codigo,$tipo,$MySQLiconn)
	{
		$SQL=$MySQLiconn->query("SELECT codigo from tbcodigosbarras where noop='".$codigo."' and tipo='".$tipo."' order by noProceso desc limit 1");
		$row=$SQL->fetch_assoc();
		return $row["codigo"];
	}
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

		$SIM=$MySQLi->query("SELECT nombreparametro,(select producto from `tbpro".$proceso."` where noop like '$noop' and tipo='$tipo' limit 1) as producto,(SELECT tipo from tbproduccion where nombreProducto=(select producto from `tbpro".$proceso."` where noop like '$noop' limit 1) limit 1) as tipo from juegoparametros where identificadorJuego=(SELECT packparametros from procesos where descripcionProceso='$proceso') and baja=1 and numParametro='G'");
		
		$longitud=false;
		$producto="";
		$unidades=0;
		$noopEvo=explode('-',$noop);
		$noopEvo=$noopEvo[0];
		while($row=$SIM->fetch_assoc())
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
		$SN=$MySQLi->query("SELECT `tbpro".$proceso."`.*,impresiones.alturaEtiqueta,impresiones.anchoPelicula as anchura,impresiones.millaresPorPaquete,impresiones.refParcial,juegoscilindros.alturaReal,juegoscilindros.repAlPaso from `tbpro".$proceso."` inner join impresiones on impresiones.id=`tbpro".$proceso."`.producto inner join juegoscilindros on juegoscilindros.descripcionImpresion=`tbpro".$proceso."`.producto where `tbpro".$proceso."`.producto='$producto' and `tbpro".$proceso."`.noop like '$noop' and `tbpro".$proceso."`.tipo='$tipo' and juegoscilindros.descripcionImpresion=impresiones.id and juegoscilindros.identificadorCilindro=(SELECT juegoCilindros FROM tbproduccion WHERE juegoLotes=(SELECT juegoLote FROM tblotes WHERE noop=(SELECT noop FROM tblotes WHERE referenciaLote=any (SELECT lote FROM tbcodigosbarras WHERE noop='$noopEvo' and tipo='$tipo') and tipo='$tipo') and tipo='$tipo'))");//Trae todos los parametros de ese registro para calcular las unidades

		if(mysqli_num_rows($SN)==0)
		{
			///Si falla, modificar juegoscireles.producto=impresiones.id por impresiones.codigo
			$SN=$MySQLi->query("SELECT `tbpro".$proceso."`.*,impresiones.alturaEtiqueta,impresiones.anchoPelicula,impresiones.anchoEtiqueta,impresiones.refParcial,impresiones.millaresPorPaquete,juegoscireles.ancho_plano,juegoscireles.alturaReal,juegoscireles.repeticiones from `tbpro".$proceso."` inner join impresiones on impresiones.id=`tbpro".$proceso."`.producto inner join juegoscireles on juegoscireles.producto=`tbpro".$proceso."`.producto where `tbpro".$proceso."`.producto='$producto' and `tbpro".$proceso."`.noop like '$noop' and `tbpro".$proceso."`.tipo='$tipo' and juegoscireles.producto=impresiones.id and juegoscireles.identificadorJuego=(SELECT juegoCireles FROM tbproduccion WHERE juegoLotes=(SELECT juegoLote FROM tblotes WHERE noop=(SELECT noop FROM tblotes WHERE referenciaLote=any (SELECT lote FROM tbcodigosbarras WHERE noop='$noopEvo' and tipo='$tipo') and tipo='$tipo') and tipo='$tipo'))");


		}
		if(mysqli_num_rows($SN)==0)
		{
			///Si falla, modificar juegoscireles.producto=impresiones.id por impresiones.codigo
			$SN=$MySQLi->query("SELECT `tbpro".$proceso."`.*,impresiones.alturaEtiqueta,impresiones.anchoPelicula,impresiones.anchoEtiqueta,impresiones.refParcial,impresiones.millaresPorPaquete,suaje.anchuraImpresion as ancho_plano,suaje.alturaReal,suaje.piezas as repeticiones from `tbpro".$proceso."` inner join impresiones on impresiones.id=`tbpro".$proceso."`.producto inner join suaje on suaje.descripcionImpresion=`tbpro".$proceso."`.producto where `tbpro".$proceso."`.producto='$producto' and `tbpro".$proceso."`.noop like '$noop' and `tbpro".$proceso."`.tipo='$tipo' and suaje.descripcionImpresion=impresiones.id and suaje.identificadorSuaje=(SELECT suaje FROM tbproduccion WHERE juegoLotes=(SELECT juegoLote FROM tblotes WHERE noop=(SELECT noop FROM tblotes WHERE referenciaLote=any (SELECT lote FROM tbcodigosbarras WHERE noop='$noopEvo' and tipo='$tipo') and tipo='$tipo') and tipo='$tipo'))");
		}
		if(mysqli_num_rows($SN)==0)
		{
			$SN=$MySQLi->query("SELECT `tbpro".$proceso."`.*,impresiones.alturaEtiqueta as alturaReal,impresiones.anchoPelicula,impresiones.refParcial,impresiones.anchoEtiqueta,impresiones.anchoPelicula as anchura,impresiones.millaresPorPaquete from `tbpro".$proceso."` inner join impresiones on impresiones.id=`tbpro".$proceso."`.producto  where `tbpro".$proceso."`.producto='$producto' and `tbpro".$proceso."`.noop like '$noop' and `tbpro".$proceso."`.tipo='$tipo'");

		}

	}
	else
	{
		$SN=$MySQLi->query("SELECT `tbpro".$proceso."`.*,bandaspp.repeticiones,(SELECT anchura from sustrato where idSustrato=(SELECT
sustrato from bandaspp where idBSPP=bandaspp.IdBSPP limit 1)) as mm,bandaseguridad.anchura from `tbpro".$proceso."` inner join bandaspp on bandaspp.idBSPP=`tbpro".$proceso."`.producto inner join bandaseguridad on bandaspp.identificadorBS=bandaseguridad.IDBanda where `tbpro".$proceso."`.producto='$producto' and `tbpro".$proceso."`.noop like '$noop' and `tbpro".$proceso."`.tipo='$tipo'");//Trae todos los parametros de ese registro para calcular las unidades
	}
	$obj=$SN->fetch_assoc();
		if($longitud==true)//si es un parametro con longitud,se hace una serie de calculos dependiendo del proceso
		{
			$MySQLi->query("SET @p0='".$tipo."'");
			$compr=$MySQLi->query("CALL getJuegoProcesosByType(@p0)");
			$ref=0;
			$act=0;
			$posSlit=0;
			$posRef=0;
			while($rom=$compr->fetch_assoc())
			{
				//verifico que el proceso tenga o no refilado, si tiene o tiene sliteo, guardo la posición respecto a la del proceso actual para poder calcular piezas en base a eso.
				if($rom['descripcionproceso']=="refilado" || $rom['descripcionproceso']=="sliteo")
				{
					$ref=$rom['numeroProceso'];
					if($rom['descripcionproceso']=="refilado")
					{
						$posRef=$rom['numeroProceso'];
					}
					else if($rom['descripcionproceso']=="sliteo")
					{
						$posSlit=$rom['numeroProceso'];
					}
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
				//Vamos a modificar la manera en la que se calcula el refilado
				if($tipo!="1")
				{
					if(!empty($obj['anchura']))
					{
						
						$unidades=($obj['longitud']/$obj['alturaReal'])*round($obj['amplitud']/$obj['anchura']);
						
					}
					else
					{
						$unidades=($obj['longitud']/$obj['alturaReal'])*round($obj['amplitud']/$obj['anchoPelicula']);
					}
					
					
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
				if($ref!=0 and $ref<$act)
				{
					
					$unidades=($obj['longitud']/$obj['alturaReal']);

				}
				else if($ref==0)
				{
					if(!empty($obj['amplitud']))
					{
						$unidades=($obj['longitud']/$obj['alturaReal'])*($obj['amplitud']/$obj['anchura']);
					}
					else
					{
						$nep=explode('-',$noop);
						$nep=$nep[0];
						$Pay=$MySQLi->query("SELECT ancho from tblotes where noop like '$nep' and tipo='$tipo'");
						$br=$Pay->fetch_assoc();
						$unidades=($obj['longitud']/$obj['alturaReal'])*($br['ancho']/$obj['anchura']);
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
			else if($proceso=="laminado")
			{
				if($ref!=0 and $ref<$act)
				{
					if($tipo!=1)
					{
						$unidades=($obj['longitud']/$obj['alturaReal']);
					}
					else
					{
						$unidades=$obj['repeticiones']*$obj['longitud'];
					}
				}
				else if($ref==0)
				{
					$unidades=($obj['longitud']/$obj['alturaReal'])*$obj['repeticiones'];
				}
				else if($ref!=0 and $ref>$act and !empty($obj['repeticiones']))
				{
					$unidades=($obj['longitud']/$obj['alturaReal'])*$obj['repeticiones'];
				}
				else
				{	
					if(!empty($obj['repAlPaso']))
					{
						$unidades=($obj['longitud']/$obj['alturaReal'])*$obj['repAlPaso'];
					}
					else
					{
						if($tipo!=1)
							{
								$unidades=($obj['longitud']/$obj['alturaReal']);
							}
							else
							{
								$unidades=$obj['repeticiones']*$obj['longitud'];
							}
					}
				}
			}
			else if($proceso=="laminado 2")
			{
				$unidades=($obj['amplitud']/$obj['anchura'])*$obj['longitud'];
			}
			elseif ($proceso=="embosado") {
				//Fórmula modificada el 11 de septiembre del 2019 para Infasa
				$unidades=(($obj['longitud']/$obj['alturaReal'])*($obj['repeticiones']));
			}
			else if($proceso=="sliteo")
			{
				if($tipo!=1)
				{
					if($obj['refParcial']==1)
					{
						$unidades=($obj['amplitud']/$obj['anchura'])*$obj['longitud'];
					}
					else
					{
						//$unidades=(($obj['amplitud']/$obj['anchura'])*$obj['longitud'])/$obj['alturaReal'];
						$unidades=$obj['longitud']/$obj['alturaReal'];
					}
					
				}
				else
				{
					$unidades=$obj['longitud'];
				}
			}
			else if($proceso=="impresion-flexografica")
			{
				$unidades=($obj['longitud']/$obj['alturaReal'])*$obj['repeticiones'];
			}
			else if($proceso=="troquelado")
			{
				if($ref!=0 and $ref<$act)
				{
					if(!empty($obj['alturaReal']))
					{
						$unidades=($obj['longitud']/$obj['alturaReal']);
					}
					else
					{
						//
						$unidades=($obj['longitud']/$obj['alturaEtiqueta']);
					}
					
				}
				else if($ref==0)
				{
					$unidades=($obj['longitud']/$obj['alturaReal'])*($obj['amplitud']/$obj['anchura']);
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
				$Pay=$MySQLi->query("SELECT piezas,alturaReal from suaje where identificadorSuaje=(SELECT suaje from `tbpro".$proceso."` where noop='$noop' and tipo='$tipo')");
				$br=$Pay->fetch_assoc();
				//Si no hay refilado o es mayor que el proceso actual, quiere decir que aún no se corta, por lo tanto calcula las piezas con base en las repeticiones a la tabla
				if($posRef==0 || $posRef>$act)
				{
					$unidades=(($obj['longitud']/$br['alturaReal'])*($br['piezas']));
				}
				else if($posRef<$act)
				{
					
					if($obj['refParcial']>1)
					{
						if($posSlit<$act)
						{
							$unidades=$obj['longitud']/$br['alturaReal'];
						}
						else
						{
							$unidades=($obj['longitud']/$br['alturaReal'])*($br['piezas']/$obj['refParcial']);
						}
					}
					else
					{
						$unidades=$obj['longitud']/$br['alturaReal'];
					}
				}
				
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
			$co=$MY->fetch_assoc();

			$arrayDatos=parsearCodigo($co['codigo'],$MySQLi);
			$arrayDatos=explode("|",$arrayDatos);
			$producto=$arrayDatos[0];
			$proceso=$arrayDatos[1];
			$noProces=$arrayDatos[3]+1;//Si no es alguno de esos procesos,como es en el caso de revision,las unidades se ajustan al proceso anterior

			$UAN=$MySQLi->query("SELECT descripcionProceso from juegoprocesos where identificadorJuego=(select juegoprocesos from tipoproducto where id=(select tipo from producto where ID=(select descripcionDisenio from impresiones where descripcionImpresion='".$producto."'))) and numeroProceso='$noProces'-1");
			$rev=$UAN->fetch_assoc();
			$anterior=$rev['descripcionProceso'];
			$AN=$MySQLi->query("SELECT unidades from `tbpro".$anterior."` where noop like '$noop' and tipo='$tipo'");
			$RAN=$AN->fetch_assoc();
			$unidades=$RAN['unidades'];

		}

		return $unidades;

	}
	function codAntiguo($noop,$noProceso,$MySQLin)
	{

		$VM=$MySQLin->query("SELECT codigo from tbcodigosbarras where noop='$noop' and noProceso='".$noProceso."'-1");
		$rex=$VM->fetch_assoc();
		$cod=$rex['codigo'];
		return $cod;
	}



	function calcularTolerancia($codigo,$proceso,$noop,$divisiones,$MySQLiconn)
	{
		$resultado=0;
		$arrayDatos=parsearCodigo($codigo,$MySQLiconn);
		$arrayDatos=explode("|",$arrayDatos);
		$producto=$arrayDatos[8];
		$noop=$arrayDatos[4];
		$tipo=$arrayDatos[9];
		$My=$MySQLiconn->query("SELECT porcentajeMPR from impresiones where id='".$producto."'");
		$Ro=$My->fetch_assoc();
		$tolerancia=$Ro['porcentajeMPR'];
		$My=$MySQLiconn->query("SELECT unidadesIn,unidadesOut from tbmerma where codigo='".$codigo."'");
		$Ro=$My->fetch_assoc();
		$diferencia=$Ro['unidadesIn']-$Ro['unidadesOut'];
		$toleranciaMas=($tolerancia*$Ro['unidadesIn']/100)+$Ro['unidadesIn'];
		$toleranciaMenos=$Ro['unidadesIn']-($tolerancia*$Ro['unidadesIn']/100);
		if($Ro['unidadesOut']<=$toleranciaMas && $Ro['unidadesOut']>=$toleranciaMenos)
		{
			$resultado=1;
		}
		else
		{

			$resultado=0;
		}
		if($resultado==0)
		{
			$MySQLiconn->query("UPDATE tbmerma set longOut='',unidadesOut='' where codigo='".$codigo."'");
			$rest = $MySQLiconn->query("
				SELECT nombreparametro
				from juegoparametros 
				where identificadorJuego=(
				select packParametros 
				from procesos 
				where descripcionProceso='".$proceso."') and baja=1 and numParametro='G'");
			if($divisiones>1)
			{
				$MySQLiconn->query("UPDATE `tbpro".$proceso."` set unidades='' where noop like '".$noop."-%' and tipo='".$tipo."'");
			//$MySQLiconn->COMMIT();
				
			while($rex = $rest->fetch_assoc()){
				$parametro=$rex['nombreparametro'];
				$MySQLiconn->query("UPDATE `tbpro".$proceso."` set $parametro='' where noop like '".$noop."-%' and tipo='".$tipo."'");
				
			}
			}
			else if($divisiones==1)
			{
				$MySQLiconn->query("UPDATE `tbpro".$proceso."` set unidades='' where noop='".$noop."' and tipo='".$tipo."'");

			//$MySQLiconn->COMMIT();
				
			while($rex = $rest->fetch_assoc()){
				$parametro=$rex['nombreparametro'];
				$MySQLiconn->query("UPDATE `tbpro".$proceso."` set $parametro='' where noop='".$noop."' and tipo='".$tipo."'");
				
			}
			}
			
		}
		return $resultado;

	}
	function generarCodigoBarras($idProceso,$noProces,$idLote,$divisiones,$noop,$MySQLiconn)
	{
		$cadena="";
		$noop1=explode("-",$noop);
		if(count($noop1)>1)
		{
			for($i=1;$i<=count($noop1);$i++)
			{
				$cadena=$cadena.$noop1[$i];
			}
		}

		$id=$idLote;
		$codigoBarras="";
	     // $codigoBarras=$codigoBarras.str_repeat("0",3 - strlen($idProceso)).$idProceso;
		$codigoBarras=$codigoBarras.str_repeat("0",5 - strlen($cadena)).$cadena;
		//$codigoBarras=$codigoBarras.str_repeat("0",1 - strlen($noProces)).$noProces;
		$codigoBarras=$codigoBarras.$noProces;
	    $codigoBarras=$codigoBarras.$idLote;//Este era,anteriormente el noop

	    registrarCdgBarras($codigoBarras,$divisiones,$noop,$MySQLiconn);
	    return $codigoBarras;
	}
	function sendIdLote($noop,$producto,$MySQLiconn)//Envia el id del lote que se esta tratando
	{
		$SQL=$MySQLiconn->query("SELECT idLote from tblotes where referenciaLote=(SELECT lote from tbcodigosbarras where noop='$noop' and producto='$producto' limit 1)");

		if(mysqli_num_rows($SQL)==0)
		{
			$SQL=$MySQLiconn->query("SELECT idLote from tblotes where referenciaLote=(SELECT lote from tbcodigosbarras where noop='$noop' and producto='$producto' limit 1)");
		}
		$rao=$SQL->fetch_assoc();
		return $rao['idLote'];
	}
	function convertCode($codigo,$proceso,$tipo,$MySQLiconn)//Se trae el código correspondiente si se reigstra un código que no corresponde
	{
		$SQL=$MySQLiconn->query("SELECT noop from tbcodigosbarras where codigo='$codigo'");
		$row=$SQL->fetch_assoc();
		$SQL=$MySQLiconn->query("SELECT codigo from tbcodigosbarras where noop='".$row['noop']."' and proceso=(SELECT id from procesos where descripcionProceso='".$proceso."') and tipo='".$tipo."'");
		$row=$SQL->fetch_assoc();
		return $row['codigo'];
	}
	function sendDivisions($tipo,$procesoSiguiente,$procesoActual,$producto,$codigo,$noop,$MySQLiconn)
	{
		$pam=$MySQLiconn->query("SELECT nombreParametro from juegoparametros where numParametro='C' and identificadorJuego=(SELECT packparametros from procesos where descripcionProceso='".$procesoActual."')");
		$arr=$pam->fetch_assoc();
		$param=$arr['nombreParametro'];
		$divisiones=0;
		if($tipo!="1")
		{
			if($procesoSiguiente=="revision")
			{
				$divisiones=1;
			}
			else if($procesoSiguiente=="refilado")
			{
				$SQL=$MySQLiconn->query("SELECT `tbpro".$procesoActual."`.amplitud as anchuraBloque,impresiones.refParcial,(SELECT repAlPaso from juegoscilindros where identificadorCilindro=(SELECT juegoCilindros from tbproduccion where juegoLotes=(SELECT juegoLote from tblotes where noop='$noop' and tipo='$tipo'))) as repeticionesC,(SELECT repeticiones from juegoscireles where identificadorJuego=(SELECT juegoCireles from tbproduccion where juegoLotes=(SELECT juegoLote from tblotes where noop='$noop' and tipo='$tipo'))) as repeticionesCir,impresiones.anchoPelicula from `tbpro".$procesoActual."` inner join impresiones on `tbpro".$procesoActual."`.producto=impresiones.id where `tbpro".$procesoActual."`.$param='$codigo' and `tbpro".$procesoActual."`.noop='$noop' and `tbpro".$procesoActual."`.tipo='$tipo'");
				
				if(!$SQL)
				{
					$arrayDatos=parsearCodigo($codigo,$MySQLiconn);
					$arr=explode('|',$arrayDatos);
					
					if(!empty($arr[2]))
					{
						$lote=$arr[2];
						$SQL=$MySQLiconn->query("SELECT anchuraBloque as repeticionesC,(SELECT anchoPelicula from impresiones where id='$producto') as anchoPelicula,(SELECT espacioRegistro from impresiones where id='$producto') as espacioRegistro from tblotes where referenciaLote='$lote'");
						$bit=$SQL->fetch_assoc();
						$divisiones=($bit['repeticionesC']-$bit['espacioRegistro'])/$bit['anchoPelicula'];

					}
					else
					{
						$SQL=$MySQLiconn->query("SELECT anchuraBloque as repeticionesC,(SELECT anchoPelicula from impresiones where id='$producto') as anchoPelicula,(SELECT espacioRegistro from impresiones where id='$producto') as espacioRegistro from tblotes where noop='$noop' and tipo='$tipo'");
						$bit=$SQL->fetch_assoc();
						$divisiones=($bit['repeticionesC']-$bit['espacioRegistro'])/$bit['anchoPelicula'];
					}
					
					
				}
				else
				{
					$bit=$SQL->fetch_assoc();
				if($bit['refParcial']==1)
				{
					if(!empty($bit['repeticionesC']))
				{
					$divisiones=$bit['repeticionesC'];
				}
				else
				{
					$divisiones=$bit['repeticionesCir'];
				}
				}
				else
				{
					$divisiones=$bit['refParcial'];
				}
				}
				
				
				
				
			}
			else if($procesoSiguiente=="fusion")
			{
				$SQL=$MySQLiconn->query("SELECT `tbpro".$procesoActual."`.longitud,impresiones.alturaEtiqueta,impresiones.millaresPorRollo from `tbpro".$procesoActual."` inner join impresiones on `tbpro".$procesoActual."`.producto=impresiones.id where `tbpro".$procesoActual."`.$param='$codigo' and `tbpro".$procesoActual."`.noop='$noop' and `tbpro".$procesoActual."`.tipo='$tipo'");
				if(!$SQL)
				{
					$arrayDatos=parsearCodigo($codigo,$MySQLiconn);
					$arr=explode('|',$arrayDatos);
					$lote=$arr[2];
					$SQL=$MySQLiconn->query("SELECT longitud,(SELECT alturaEtiqueta from impresiones where id='$producto') as alturaEtiqueta,(SELECT millaresPorRollo from impresiones where id='$producto') as millaresPorRollo from tblotes where referenciaLote='$lote'");
				}
				$bit=$SQL->fetch_assoc();
				$divisiones = ceil((($bit['longitud']/$bit['alturaEtiqueta'])/$bit['millaresPorRollo']));
			}
			else if($procesoSiguiente=="corte")
			{
				$SQL=$MySQLiconn->query("SELECT `tbpro".$procesoActual."`.unidades,impresiones.millaresPorPaquete from `tbpro".$procesoActual."` inner join impresiones on `tbpro".$procesoActual."`.producto=impresiones.id where `tbpro".$procesoActual."`.$param='$codigo' and `tbpro".$procesoActual."`.noop='$noop' and `tbpro".$procesoActual."`.tipo='$tipo'");
				if(!$SQL)
				{
					$arrayDatos=parsearCodigo($codigo,$MySQLiconn);
					$arr=explode('|',$arrayDatos);
					$lote=$arr[2];
					$SQL=$MySQLiconn->query("SELECT unidades,(SELECT millaresPorPaquete from impresiones where id='$producto') as millaresPorPaquete from tblotes where referenciaLote='$lote'");
				}
				$bit=$SQL->fetch_assoc();
				$divisiones =number_format($bit['unidades']/$bit['millaresPorPaquete']);//anteriormente en lugar de round estaba number_format
			}
			else if($procesoSiguiente=="impresion-flexografica")
			{
				$divisiones=1;
			}
			else if($procesoSiguiente=="foliado")
			{
				$SQL=$MySQLiconn->query("SELECT `tbpro".$procesoActual."`.unidades,impresiones.alturaEtiqueta,impresiones.millaresPorRollo from `tbpro".$procesoActual."` inner join impresiones on `tbpro".$procesoActual."`.producto=impresiones.id where `tbpro".$procesoActual."`.$param='$codigo' and `tbpro".$procesoActual."`.noop='$noop' and `tbpro".$procesoActual."`.tipo='$tipo'");
				if(!$SQL)
				{
					$arrayDatos=parsearCodigo($codigo,$MySQLiconn);
					$arr=explode('|',$arrayDatos);
					$lote=$arr[2];
					$SQL=$MySQLiconn->query("SELECT longitud,(SELECT alturaEtiqueta from impresiones where id='$producto') as alturaEtiqueta,(SELECT millaresPorRollo from impresiones where id='$producto') as millaresPorRollo from tblotes where referenciaLote='$lote'");
				}
				$bit=$SQL->fetch_assoc();
				$divisiones = ceil(($bit['unidades']/$bit['millaresPorRollo']));
			}
			else if($procesoSiguiente=="sliteo")
			{
				$SQL=$MySQLiconn->query("SELECT `tbpro".$procesoActual."`.longitud,`tbpro".$procesoActual."`.amplitud,impresiones.anchoPelicula as ancho,impresiones.alturaEtiqueta,impresiones.millaresPorRollo from `tbpro".$procesoActual."` inner join impresiones on `tbpro".$procesoActual."`.producto=impresiones.id where `tbpro".$procesoActual."`.$param='$codigo' and `tbpro".$procesoActual."`.noop='$noop' and `tbpro".$procesoActual."`.tipo='$tipo'");
				if(!$SQL)
				{

					$Sual=$MySQLiconn->query("SELECT `tbpro".$procesoActual."`.longitud,`tbpro".$procesoActual."`.unidades,impresiones.refParcial,impresiones.espacioregistro,impresiones.anchoPelicula as ancho,impresiones.alturaEtiqueta,impresiones.millaresPorRollo from `tbpro".$procesoActual."` inner join impresiones on `tbpro".$procesoActual."`.producto=impresiones.id where `tbpro".$procesoActual."`.$param='$codigo' and `tbpro".$procesoActual."`.noop='$noop' and `tbpro".$procesoActual."`.tipo='$tipo'");
					$rik=$Sual->fetch_assoc();
					if($rik['refParcial']==1)
					{
						$arrayDatos=parsearCodigo($codigo,$MySQLiconn);
					$arr=explode('|',$arrayDatos);
					$lote=$arr[2];
					$SQL=$MySQLiconn->query("SELECT unidades,(SELECT anchoPelicula from impresiones where id='$producto') as amplitud,ancho from tblotes where referenciaLote='$lote'");
					$bit=$SQL->fetch_assoc();
					$divisiones=ceil($bit['amplitud']/$bit['ancho']);
					}
					else
					{
						$arrayDatos=parsearCodigo($codigo,$MySQLiconn);
					$arr=explode('|',$arrayDatos);
					$lote=$arr[2];
					$SQL=$MySQLiconn->query("SELECT unidades,(SELECT anchoPelicula from impresiones where id='$producto') as amplitud,ancho from tblotes where referenciaLote='$lote'");
					$bit=$SQL->fetch_assoc();
					//echo $bit['ancho'].'**'.$rik['espacioregistro'].'--'.$rik['refParcial'].'{{'.$bit['amplitud'].'__'.$rik['unidades'].'??'.$rik['millaresPorRollo'];
					$divisiones=round(($rik['unidades']/$rik['millaresPorRollo']));
					}
					
				}
				else
				{
					$bit=$SQL->fetch_assoc();
					$divisiones=ceil($bit['ancho']/$bit['amplitud']);
				}
				
			}
			else
			{
				$divisiones=1;
			}

		}
		else
		{
			if($procesoSiguiente=="sliteo")
			{
				$SQL=$MySQLiconn->query("SELECT repeticiones from bandaspp where IdBSPP='".$producto."'");
				if(!$SQL)
				{
					$arrayDatos=parsearCodigo($codigo,$MySQLiconn);
					$arr=explode('|',$arrayDatos);
					$lote=$arr[2];
					//Esta incorrecta
					$SQL=$MySQLiconn->query("SELECT unidades,(SELECT anchura from bandaseguridad where IDBanda=(SELECT identificadorBS from bandaspp where IdBSPP='".$producto."') as anchuraBS  from tblotes where referenciaLote='$lote'");
					$bit=$SQL->fetch_assoc();
					$divisiones=ceil($bit['amplitud']/$bit['anchuraBS']);
				}
				else
				{
					$bit=$SQL->fetch_assoc();
					$divisiones=$bit['repeticiones'];
				}
				
			}
			else if($procesoSiguiente=="refilado")
			{
				$SQL=$MySQLiconn->query("SELECT `tbpro".$procesoActual."`.amplitud,bandaspp.anchuraLaminado as alturaEtiqueta  from `tbpro".$procesoActual."` inner join bandaspp on `tbpro".$procesoActual."`.producto=bandaspp.IdBSPP where `tbpro".$procesoActual."`.$param='$codigo' and `tbpro".$procesoActual."`.noop='$noop' and `tbpro".$procesoActual."`.tipo='$tipo'");
				if(!$SQL)
				{
					$arrayDatos=parsearCodigo($codigo,$MySQLiconn);
					$arr=explode('|',$arrayDatos);
					$lote=$arr[2];
					if(empty($lote))
					{
						$Lot=$MySQLiconn->query("SELECT referenciaLote from tblotes where noop='$noop' and tipo='$tipo'");
						$getl=$Lot->fetch_assoc();
						$lote=$getl['referenciaLote'];
					}
					$SQL=$MySQLiconn->query("SELECT anchuraBloque as amplitud,(SELECT bandaspp.anchuraLaminado as alturaEtiqueta from bandaspp where IdBSPP='$producto') as alturaEtiqueta from tblotes where referenciaLote='$lote'");
				}
				$bit=$SQL->fetch_assoc();

	  			$divisiones=ceil($bit['amplitud']/$bit['alturaEtiqueta']);//Divisiones para banda de seguridad
	  		}
	  		else if($procesoSiguiente=="laminado")
	  		{
	  			$divisiones=1;
	  		}
	  		else
	  		{
	  			$divisiones=1;
	  		}
	  	}
	  	return $divisiones;
	  }
	  function calcularLongitud($proceso,$noop,$tipo,$MySQLi)
	  {
	  	$SOUL=$MySQLi->query("SELECT `tbpro".$proceso."`.unidades,suaje.piezas,suaje.alturaReal from suaje inner join `tbpro".$proceso."` on `tbpro".$proceso."`.producto=suaje.descripcionImpresion where `tbpro".$proceso."`.noop='$noop' and `tbpro".$proceso."`.tipo='$tipo' and suaje.baja=1 and suaje.proceso=(SELECT id from procesos where descripcionProceso='".$proceso."')");
	  	if(mysqli_num_rows($SOUL)==0)
	  	{
	  		$SOUL=$MySQLi->query("SELECT `tbpro".$proceso."`.unidades,impresiones.anchoPelicula,impresiones.anchoEtiqueta,impresiones.alturaEtiqueta,juegoscireles.alturaReal,juegoscireles.repeticiones from impresiones inner join `tbpro".$proceso."` on impresiones.id=`tbpro".$proceso."`.producto inner join juegoscireles on juegoscireles.descripcionImpresion=`tbpro".$proceso."`.producto where `tbpro".$proceso."`.noop='$noop' and `tbpro".$proceso."`.tipo='$tipo' and juegoscireles.identificadorJuego=(SELECT juegoCireles FROM tbproduccion WHERE juegoLotes=(SELECT juegoLote FROM tblotes WHERE noop=(SELECT noop FROM tblotes WHERE referenciaLote=any (SELECT lote FROM tbcodigosbarras WHERE noop='$noop' and tipo='$tipo') and tipo='$tipo') and tipo='$tipo'))");

	  		if(mysqli_num_rows($SOUL)==0)
	  		{
	  			$SOUL=$MySQLi->query("SELECT `tbpro".$proceso."`.unidades,impresiones.anchoPelicula,impresiones.anchoEtiqueta,impresiones.alturaEtiqueta as alturaReal,sustrato.anchura/impresiones.anchoPelicula as repeticiones from impresiones inner join sustrato on sustrato.idSustrato=impresiones.sustrato inner join `tbpro".$proceso."` on impresiones.id=`tbpro".$proceso."`.producto where `tbpro".$proceso."`.noop='$noop' and `tbpro".$proceso."`.tipo='$tipo'");
	  			$SIM=$SOUL->fetch_assoc();
	  		}
	  		else
	  		{
	  			$SIM=$SOUL->fetch_assoc();
	  		}

	  		$unidades=$SIM['unidades'];
	  		$piezas=$SIM['repeticiones'];
	  		$alturaImpresion=$SIM['alturaReal'];
	  		$MySQLi->query("SET @p0='".$tipo."'");
	  		$compr=$MySQLi->query("CALL getJuegoProcesosByType(@p0)");
	  		$ref=0;
	  		$act=0;
	  		while($rom=$compr->fetch_assoc())
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
	  		if($ref==0)
	  		{
	  			$longitud=($alturaImpresion*$unidades)/$piezas;
	  		}
	  		else if($ref<$act)
	  		{
	  			$longitud=($alturaImpresion*$unidades);
	  		}
	  		else if($ref>$act)
	  		{
	  			$longitud=($alturaImpresion*$unidades)/$piezas;
	  		}




	  	}
	  	else
	  	{
	  		$SIM=$SOUL->fetch_assoc();
	  		$unidades=$SIM['unidades'];
	  		$piezas=$SIM['piezas'];
	  		$alturaImpresion=$SIM['alturaReal'];

	  		$MySQLi->query("SET @p0='".$tipo."'");
	  		$compr=$MySQLi->query("CALL getJuegoProcesosByType(@p0)");
	  		$ref=0;
	  		$act=0;
	  		while($rom=$compr->fetch_assoc())
	  		{
	  			if($rom['descripcionproceso']=="sliteo" || $rom['descripcionproceso']=="refilado")
	  			{
	  				$ref=$rom['numeroProceso'];
	  			}
	  			if($rom['descripcionproceso']==$proceso)
	  			{
	  				$act=$rom['numeroProceso'];
	  			}
	  		}
	  		$MySQLi->next_result();
	  		if($ref==0)
	  		{
	  			$longitud=($alturaImpresion*$unidades)/$piezas;
	  		}
	  		else if($ref<$act)
	  		{
	  			$longitud=($alturaImpresion*$unidades);
	  		}
	  		else if($ref>$act)
	  		{
	  			$longitud=($alturaImpresion*$unidades)/$piezas;
	  		}

	  	}
	  	return $longitud;


	  }
	  function quitar_tildes($cadena) {
	  	$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
	  	$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
	  	$texto = str_replace($no_permitidas, $permitidas ,$cadena);
	  	return $texto;
	  }
	  function is_bandera_in_array($dato,$arreglo)
	  {
	  	$indicador=false;
	  	for($c=0;$c<count($arreglo);$c++)
	  	{
	  		if($arreglo[$c]['proceso']==$dato)
	  		{
	  			return $indicador=true;
	  		}
	  	}
	  	return $indicador;
	  }
	function register_cdg($stringfile,$etiquetasInd,$processName,$MySQLiconn) //Esta función registra el codigo de barras y manda las etiquetas correspondientes.
	{
		if(!empty($etiquetasInd))
		{
			if($processName!="programado")//Si no es programado, independientemente del tipo
			{
				$label="";
				$numero=$etiquetasInd;
				$proceso=explode("|", $numero,3);
				$oldCodigo=$proceso[0];
				$arrayDatos=parsearCodigo($oldCodigo,$MySQLiconn);
				$arrayDatos=explode("|",$arrayDatos);
				$producto=$arrayDatos[0];
				$proceso=$arrayDatos[1];
				$noProces=$arrayDatos[3]+1;
				$noop=$arrayDatos[4];
				$idLote=$arrayDatos[7];
				$tipo=$arrayDatos[6];
				$idProducto=$arrayDatos[8];
				$idtipo=$arrayDatos[9];
				$noopOrigen=$arrayDatos[5];
	if($idtipo!="1") //Si no es banda de seguridad
	{
		$MySQLiconn->query("SET @p0='".$producto."',@p1='".$noProces."'");
		$cons=$MySQLiconn->query("call getNodoProceso(@p0,@p1)");
		$consRo=$cons->fetch_assoc();
			$idProceso=$consRo['id'];//El id del proceso actual
			$numeroProceso=$consRo['numeroProceso'];//numero del proceso que se esta registrando
			$procesoSiguiente=$consRo['siguiente'];//el proceso siguiente para colocarlo en la etiqueta
			$procesoActual=$consRo['actual'];//la descripcion del proceso que se esta registrando
			$procesoAnterior=$consRo['anterior'];
			$MySQLiconn->next_result();
			if($processName=="impresion")
			{
				$codeList=array();
				$consul=$MySQLiconn->query("SELECT tblotes.*,impresiones.id as newid,tbproimpresion.longitud as newLong,tbproimpresion.peso as newPeso,tbproimpresion.fecha as fecha,tbproimpresion.unidades as newUnidades,impresiones.id,(select descripcion from producto where ID=impresiones.descripcionDisenio) as descripcionDisenio,impresiones.refParcial,impresiones.descripcionImpresion,impresiones.anchoPelicula,impresiones.anchoEtiqueta,impresiones.espaciofusion from tblotes inner join impresiones inner join tbproimpresion  where tblotes.noop like '$noop' and impresiones.id='$idProducto' and tbproimpresion.noop like '$noop' and tblotes.tipo='$idtipo' and tbproimpresion.tipo='$idtipo'");
				if ($consul->num_rows > 0)
				{
					$index=0;
					while ($regPackingList = $consul->fetch_assoc())
					{
						$codeList[$index]['descripcionDisenio']=$regPackingList["descripcionDisenio"];
						$codeList[$index]['descripcionImpresion']=$regPackingList["descripcionImpresion"];
						$codeList[$index]['newLong']=$regPackingList["newLong"];
						$codeList[$index]['anchuraBloque']=$regPackingList["anchuraBloque"];
						$codeList[$index]['newPeso']=$regPackingList["newPeso"];
						$codeList[$index]['newUnidades']=$regPackingList["newUnidades"];
						$codeList[$index]['refParcial']=$regPackingList["refParcial"];
						$codeList[$index]['peso']=$regPackingList["peso"];
						$codeList[$index]['noop']=$regPackingList["noop"];
						$codeList[$index]['procesoSiguiente']=$procesoSiguiente;
						$codeList[$index]['tarima']=$regPackingList["tarima"];
						$codeList[$index]['numeroLote']=$regPackingList["numeroLote"];
						$codeList[$index]['referenciaLote']=$regPackingList["referenciaLote"];
						$codeList[$index]['anchoEtiqueta']=$regPackingList["anchoEtiqueta"];
						$codeList[$index]['espaciofusion']=$regPackingList["espaciofusion"];
						$codeList[$index]['anchoPelicula']=$regPackingList["anchoPelicula"];
						$codeList[$index]['alturaEtiqueta']=$regPackingList["alturaEtiqueta"];
						$divisiones=sendDivisions($idtipo,$procesoSiguiente,$procesoActual,$idProducto,$oldCodigo,$regPackingList["noop"],$MySQLiconn);
						$codigoBarras=generarCodigoBarras($idProceso,$noProces,$idLote,$divisiones,$regPackingList["noop"],$MySQLiconn);
						$codeList[$index]['codigoBarras']=$codigoBarras;
						$codeList[$index]['divisiones']=$divisiones;
						$index++;
					}
					$codeList = serialize($codeList);
					$codeList = base64_encode($codeList);
					$codeList = urlencode($codeList);
					$label="<script>window.open('$stringfile?etiquetasInd=$codeList&idtipo=$idtipo', '_blank')</script>";
				}
				
			}
			else if($processName=="refilado")
			{
				$map=$_SESSION['codigo'];
				$consul=$MySQLiconn->query("SELECT `tbpro".$procesoActual."`.*,impresiones.id as newid,(select descripcion from producto where ID=impresiones.descripcionDisenio) as descripcionDisenio,impresiones.descripcionImpresion,impresiones.anchoPelicula,impresiones.refParcial,impresiones.anchoEtiqueta,impresiones.espaciofusion,impresiones.alturaEtiqueta,impresiones.millaresPorRollo from `tbpro".$procesoActual."` inner join impresiones   where `tbpro".$procesoActual."`.noop like '$noop-%' and `tbpro".$procesoActual."`.tipo='$idtipo'  and $map='$oldCodigo' and impresiones.id='$idProducto'  order by `tbpro".$procesoActual."`.noop DESC");
				if ($consul->num_rows > 0)
				{
					$index=0;
					while ($regPackingList = $consul->fetch_assoc())
					{
						if($regPackingList["longitud"]>0)
						{
							$codeList[$index]['descripcionDisenio']=$regPackingList["descripcionDisenio"];
							$codeList[$index]['descripcionImpresion']=$regPackingList["descripcionImpresion"];
							$codeList[$index]['longitud']=$regPackingList["longitud"];
							$codeList[$index]['refParcial']=$regPackingList["refParcial"];
							$codeList[$index]['amplitud']=$regPackingList["amplitud"];
							$codeList[$index]['peso']=$regPackingList["peso"];
							$codeList[$index]['unidades']=$regPackingList["unidades"];
							$codeList[$index]['noop']=$regPackingList["noop"];
							$codeList[$index]['procesoSiguiente']=$procesoSiguiente;
							$codeList[$index]['fecha']=$regPackingList["fecha"];
							$codeList[$index]['anchoEtiqueta']=$regPackingList["anchoEtiqueta"];
							$codeList[$index]['espaciofusion']=$regPackingList["espaciofusion"];
							$codeList[$index]['anchoPelicula']=$regPackingList["anchoPelicula"];
							$codeList[$index]['alturaEtiqueta']=$regPackingList["alturaEtiqueta"];
							$divisiones=sendDivisions($idtipo,$procesoSiguiente,$procesoActual,$idProducto,$oldCodigo,$regPackingList["noop"],$MySQLiconn);
							$codigoBarras=generarCodigoBarras($idProceso,$noProces,$idLote,$divisiones,$regPackingList["noop"],$MySQLiconn);
							$codeList[$index]['codigoBarras']=$codigoBarras;
							$codeList[$index]['divisiones']=$divisiones;
							$index++;
						}
					}
					$codeList = serialize($codeList);
					$codeList = base64_encode($codeList);
					$codeList = urlencode($codeList);
					$label="<script>window.open('$stringfile?etiquetasInd=$codeList&idtipo=$idtipo', '_blank')</script>";
				}
			}
			else if($processName=="fusion")
			{
				$consul=$MySQLiconn->query("SELECT `pro".$procesoActual."`.*,impresiones.id as newid,(select descripcion from producto where ID=impresiones.descripcionDisenio) as descripcionDisenio,impresiones.descripcionImpresion,impresiones.anchoPelicula,impresiones.refParcial,impresiones.anchoEtiqueta,impresiones.espaciofusion,impresiones.alturaEtiqueta,impresiones.millaresPorRollo from `tbpro".$procesoActual."` `pro".$procesoActual."` inner join impresiones   where `pro".$procesoActual."`.noop like '$noop%' and impresiones.id='$idProducto' and `pro".$procesoActual."`.tipo='$idtipo' and `pro".$procesoActual."`.rollo_padre!='1' order by `pro".$procesoActual."`.noop DESC");
				if ($consul->num_rows > 0)
				{
					$index=0;
					while ($regPackingList = $consul->fetch_assoc())
					{
						if($regPackingList["longitud"]>0)
						{
							$codeList[$index]['descripcionDisenio']=$regPackingList["descripcionDisenio"];
							$codeList[$index]['descripcionImpresion']=$regPackingList["descripcionImpresion"];
							$codeList[$index]['longitud']=$regPackingList["longitud"];
							$codeList[$index]['amplitud']=$regPackingList["amplitud"];
							$codeList[$index]['refParcial']=$regPackingList["refParcial"];
							$codeList[$index]['unidades']=$regPackingList["unidades"];
							$codeList[$index]['noop']=$regPackingList["noop"];
							$codeList[$index]['procesoSiguiente']=$procesoSiguiente;
							$codeList[$index]['peso']=$regPackingList["peso"];
							$codeList[$index]['fecha']=$regPackingList["fecha"];
							$codeList[$index]['anchoEtiqueta']=$regPackingList["anchoEtiqueta"];
							$codeList[$index]['espaciofusion']=$regPackingList["espaciofusion"];
							$codeList[$index]['anchoPelicula']=$regPackingList["anchoPelicula"];
							$codeList[$index]['alturaEtiqueta']=$regPackingList["alturaEtiqueta"];
							$divisiones=sendDivisions($idtipo,$procesoSiguiente,$procesoActual,$idProducto,$oldCodigo,$regPackingList["noop"],$MySQLiconn);
							$codigoBarras=generarCodigoBarras($idProceso,$noProces,$idLote,$divisiones,$regPackingList["noop"],$MySQLiconn);
							$codeList[$index]['codigoBarras']=$codigoBarras;
							$codeList[$index]['divisiones']=$divisiones;
							$index++;
						}
						
						
					}
					$codeList = serialize($codeList);
					$codeList = base64_encode($codeList);
					$codeList = urlencode($codeList);
					$label="<script>window.open('$stringfile?etiquetasInd=$codeList&idtipo=$idtipo', '_blank')</script>";
				}
			}
			else if($processName=="corte")
			{
				/*$MySQLiconn->begin_transaction();
				mysqli_autocommit($MySQLiconn,FALSE);*/
				$consul=$MySQLiconn->query("SELECT `pro".$procesoActual."`.*,impresiones.id as newid,impresiones.codigoImpresion,impresiones.descripcionDisenio,impresiones.refParcial,impresiones.descripcionImpresion,impresiones.anchoPelicula,impresiones.anchoEtiqueta,impresiones.espaciofusion,impresiones.alturaEtiqueta,impresiones.millaresPorRollo,impresiones.millaresPorPaquete from `tbpro".$procesoActual."` `pro".$procesoActual."` inner join impresiones  on impresiones.id=`pro".$procesoActual."`.producto where `pro".$procesoActual."`.noop like '$noop' and impresiones.id='$idProducto' and rollo_padre=1 order by `pro".$procesoActual."`.noop DESC");

				$jue=$MySQLiconn->query("SELECT alturaReal as altJuego from juegoscilindros where descripcionImpresion='$idProducto' and identificadorCilindro=(SELECT juegoCilindros from tbproduccion where juegoLotes=(SELECT juegoLote from tblotes where idLote='$idLote'))");
				if(!$jue or mysqli_num_rows($jue)==0)
				{
					$jue=$MySQLiconn->query("SELECT juegoscireles.alturaReal as altJuego FROM juegoscireles where juegoscireles.producto='$idProducto' and identificadorJuego=(SELECT juegoCireles from tbproduccion where juegoLotes=(SELECT juegoLote from tblotes where idLote='$idLote'))");
				}
				$or=$jue->fetch_assoc();
				$estado=0;
				$mpp=0;
				if ($consul->num_rows > 0)
				{
					$contador=1;
					$nPaquetes=0;
					$regCodePaquete = $consul->fetch_assoc();
					$altJuego=$or["altJuego"];
					$ancho=$regCodePaquete["anchoEtiqueta"];
					$alto=$regCodePaquete["alturaEtiqueta"];
					$medida=$ancho.'x'.$alto;
					$codigo=$regCodePaquete["codigoImpresion"];
					$codFat=$regCodePaquete["rollo"];
					if($regCodePaquete["total"]==0)
					{
						$estado=1;
					}
					$unidades=$regCodePaquete["unidades"]*1000;
					$mpp= $regCodePaquete["millaresPorPaquete"]*1000;
						$nCodigos=number_format(($unidades/$mpp));//Anteriormente estaba ceil en lugar de round
	          // concatenacion de Codigo de barras
						$codePaquete_cdgpaquete = array($nCodigos);
						for($i=1;$i<=$nCodigos;$i++)
						{
							$opFinal=$regCodePaquete["noop"]."-".$i;
	 // Código de barras
							$codigoBarras=generarCodigoBarras($idProceso,$noProces,$idLote,0,$opFinal,$MySQLiconn);
							$es=$MySQLiconn->query("SELECT noop FROM tbprocorte where noop='$opFinal'");
							if(mysqli_num_rows($es)>0)
							{
								$codePaquete_cdgpaquete[$i] = $codigoBarras; 
								$codePaquete_paquete[$i] = $codigoBarras;
							}
						}

	    					$nPaquetes=number_format((($unidades/$mpp)/2));//Antes estaba number_format
	    					$nPac=number_format($unidades/$mpp);//Ants estaba number_format
	    					$mtrsOut=((($mpp/1000)*($altJuego)))*$nPac;
	    					$newUnidades=$mtrsOut/$altJuego;
	    					$Mod1=$MySQLiconn->query("UPDATE tbmerma set longOut='".$mtrsOut."',unidadesOut='$newUnidades' where codigo='".$codFat."'");
	    					for ($i =1; $i <= number_format($nPac); $i++)
	    					{ 
	    						$noopCon=$arrayDatos[4]."-".$i;
	    						$unidades=$mpp/1000; 
	    						if($estado==0)
	    						{
	    							if(!empty($codePaquete_cdgpaquete[$i]))
	    							{
	    								$Mod2=$MySQLiconn->query("UPDATE `tbpro".$procesoActual."` set rollo='".$codePaquete_cdgpaquete[$i]."',unidades='".$unidades."' where noop='".$noopCon."'");
	    								$Mod3=$MySQLiconn->query("UPDATE tbcodigosbarras set noop='".$noopCon."' where codigo='".$codePaquete_cdgpaquete[$i]."'");
	    							}
	    						}
	    					}
	    					$Mod4=$MySQLiconn->query("UPDATE `tbpro".$procesoActual."` set total=0 where noop='".$arrayDatos[4]."'");

	    					$contador++;
	    					/*if(isset($Mod1) and !$Mod1 || isset($Mod2) and !$Mod2 || isset($Mod3) and !$Mod3 || isset($Mod4) and !$Mod4)
	    					{
	    						$MySQLiconn->ROLLBACK();
	    						echo"<script>alert('Algo salió mal durante la transacción,consulte al encargado de TI')</script>";
	    					}
	    					else
	    					{
	    						$MySQLiconn->COMMIT();
	    					}*/
	    					$codeList1 = serialize($codePaquete_cdgpaquete);
	    					$codeList2 = serialize($codePaquete_paquete);
	    					$codeList1 = base64_encode($codeList1);
	    					$codeList2 = base64_encode($codeList2);
	    					$codeList1 = urlencode($codeList1);
	    					$codeList2 = urlencode($codeList2);
	    					$label="<script>window.location='$stringfile?codePaquete_cdgpaquete=$codeList1&codePaquete_paquete=$codeList2&idtipo=$idtipo&nPaquetes=$nPaquetes&medida=$medida&codigo=$codigo&conte=$mpp'</script>";
	    				}


	    			}
	    			else if($processName=="impresion-flexografica")
	    			{
	    				$codeList=array();
	    				$consul=$MySQLiconn->query("SELECT lotes.*,impresiones.id as newid,`proimpresion-flexografica`.longitud as newLong,`proimpresion-flexografica`.peso as newPeso,`proimpresion-flexografica`.fecha as fecha,`proimpresion-flexografica`.unidades as newUnidades,impresiones.id,(select descripcion from producto where ID=impresiones.descripcionDisenio) as descripcionDisenio,impresiones.descripcionImpresion,impresiones.anchoPelicula,impresiones.refParcial,impresiones.anchoEtiqueta,impresiones.espaciofusion from tblotes lotes inner join impresiones inner join `tbproimpresion-flexografica` `proimpresion-flexografica`  where lotes.noop like '$noop' and impresiones.id='$idProducto' and `proimpresion-flexografica`.noop like '$noop' and lotes.tipo='$idtipo' and `proimpresion-flexografica`.tipo='$idtipo'");
	    				if ($consul->num_rows > 0)
	    				{
	    					$index=0;
	    					while ($regPackingList = $consul->fetch_assoc())
	    					{
	    						$codeList[$index]['descripcionDisenio']=$regPackingList["descripcionDisenio"];
	    						$codeList[$index]['descripcionImpresion']=$regPackingList["descripcionImpresion"];
	    						$codeList[$index]['newLong']=$regPackingList["newLong"];
	    						$codeList[$index]['anchuraBloque']=$regPackingList["anchuraBloque"];
	    						$codeList[$index]['newPeso']=$regPackingList["newPeso"];
	    						$codeList[$index]['newUnidades']=$regPackingList["newUnidades"];
	    						$codeList[$index]['peso']=$regPackingList["peso"];
	    						$codeList[$index]['refParcial']=$regPackingList["refParcial"];
	    						$codeList[$index]['noop']=$regPackingList["noop"];
	    						$codeList[$index]['procesoSiguiente']=$procesoSiguiente;
	    						$codeList[$index]['tarima']=$regPackingList["tarima"];
	    						$codeList[$index]['numeroLote']=$regPackingList["numeroLote"];
	    						$codeList[$index]['referenciaLote']=$regPackingList["referenciaLote"];
	    						$codeList[$index]['anchoEtiqueta']=$regPackingList["anchoEtiqueta"];
	    						$codeList[$index]['espaciofusion']=$regPackingList["espaciofusion"];
	    						$codeList[$index]['anchoPelicula']=$regPackingList["anchoPelicula"];
	    						$codeList[$index]['alturaEtiqueta']=$regPackingList["alturaEtiqueta"];
	    						$divisiones=sendDivisions($idtipo,$procesoSiguiente,$procesoActual,$idProducto,$oldCodigo,$regPackingList["noop"],$MySQLiconn);
	    						$codigoBarras=generarCodigoBarras($idProceso,$noProces,$idLote,$divisiones,$regPackingList["noop"],$MySQLiconn);
	    						$codeList[$index]['codigoBarras']=$codigoBarras;
	    						$codeList[$index]['divisiones']=$divisiones;
	    						$index++;
	    					}
	    					$codeList = serialize($codeList);
	    					$codeList = base64_encode($codeList);
	    					$codeList = urlencode($codeList);
	    					$label="<script>window.open('$stringfile?etiquetasInd=$codeList&idtipo=$idtipo', '_blank')</script>";
	    				}
	    			}
	    			else if($processName=="revision")
	    			{
	    				$consul=$MySQLiconn->query("SELECT `tbpro".$procesoActual."`.*,impresiones.id as newid,(select descripcion from producto where ID=impresiones.descripcionDisenio) as descripcionDisenio,impresiones.descripcionImpresion,impresiones.anchoPelicula,impresiones.anchoEtiqueta,impresiones.espaciofusion,impresiones.alturaEtiqueta,impresiones.refParcial,impresiones.millaresPorRollo,impresiones.millaresPorPaquete from `tbpro".$procesoActual."` inner join impresiones   where `tbpro".$procesoActual."`.noop like '$noop' and impresiones.id='$idProducto' and `tbpro".$procesoActual."`.tipo='$idtipo' order by `tbpro".$procesoActual."`.noop DESC");
	    				if ($consul->num_rows > 0)
	    				{
	    					$index=0;
	    					while ($regPackingList = $consul->fetch_assoc())
	    					{
	    						$SIC=$MySQLiconn->query("SELECT amplitud from `tbpro".$processName."` where noop  like '".$regPackingList["noop"]."' and tipo='".$idtipo."'");
	    						if(mysqli_num_rows($SIC)==0 || !$SIC) 
	    						{
	    							$SIC=$MySQLiconn->query("SELECT amplitud from `tbpro".$procesoAnterior."` where noop  like '".$regPackingList["noop"]."' and tipo='".$idtipo."'");
	    							
	    							if(mysqli_num_rows($SIC)==0)
	    							{
	    								$SIC=$MySQLiconn->query("SELECT anchuraBloque as amplitud from tblotes where noop='".$noopOrigen."' and tipo='".$idtipo."'");
	    							}
	    						}
	    						$regSub=$SIC->fetch_assoc();
	    						
	    						$codeList[$index]['descripcionDisenio']=$regPackingList["descripcionDisenio"];
	    						$codeList[$index]['descripcionImpresion']=$regPackingList["descripcionImpresion"];
	    						$codeList[$index]['longitud']=$regPackingList["longitud"];
	    						$codeList[$index]['unidades']=$regPackingList["unidades"];
	    						$codeList[$index]['refParcial']=$regPackingList["refParcial"];
	    						$codeList[$index]['noop']=$regPackingList["noop"];
	    						$codeList[$index]['procesoSiguiente']=$procesoSiguiente;
	    						$codeList[$index]['fecha']=$regPackingList["fecha"];
	    						$codeList[$index]['amplitud']=$regSub['amplitud'];
	    						$codeList[$index]['anchoEtiqueta']=$regPackingList["anchoEtiqueta"];
	    						$codeList[$index]['espaciofusion']=$regPackingList["espaciofusion"];
	    						$codeList[$index]['anchoPelicula']=$regPackingList["anchoPelicula"];
	    						$codeList[$index]['alturaEtiqueta']=$regPackingList["alturaEtiqueta"];
	    						$divisiones=sendDivisions($idtipo,$procesoSiguiente,$procesoActual,$idProducto,$oldCodigo,$regPackingList["noop"],$MySQLiconn);
	    						$codigoBarras=generarCodigoBarras($idProceso,$noProces,$idLote,$divisiones,$regPackingList["noop"],$MySQLiconn);
	    						$codeList[$index]['codigoBarras']=$codigoBarras;
	    						$codeList[$index]['divisiones']=$divisiones;
	    						$index++;
	    					}
	    					$codeList = serialize($codeList);
	    					$codeList = base64_encode($codeList);
	    					$codeList = urlencode($codeList);
	    					$label="<script>window.open('$stringfile?etiquetasInd=$codeList&idtipo=$idtipo', '_blank')</script>";
	    				}

	    			}
	    			else if($processName=="sliteo") 
	    			{
	    				$consul=$MySQLiconn->query("SELECT `tbpro".$procesoActual."`.*,impresiones.id as newid,impresiones.anchoPelicula as anchuraSliteo,(select descripcion from producto where ID=impresiones.descripcionDisenio) as descripcionDisenio,impresiones.refParcial,impresiones.descripcionImpresion,impresiones.anchoPelicula from  `tbpro".$procesoActual."` inner join impresiones where `tbpro".$procesoActual."`.noop like '".$noop."-%' and impresiones.id='$idProducto' and `tbpro".$procesoActual."`.tipo='$idtipo' order by `tbpro".$procesoActual."`.id DESC");

	    				if ($consul->num_rows > 0)
	    				{
	    					$index=0;
	    					while ($regPackingList = $consul->fetch_assoc())
	    					{
	    						$SIC=$MySQLiconn->query("SELECT amplitud from `tbpro".$processName."` where noop  like '".$regPackingList["noop"]."' and tipo='".$idtipo."'");
	    						if(mysqli_num_rows($SIC)==0) 
	    						{
	    							$SIC=$MySQLiconn->query("SELECT amplitud from `tbpro".$procesoAnterior."` where noop  like '".$regPackingList["noop"]."' and tipo='".$idtipo."'");
	    							
	    							if(mysqli_num_rows($SIC)==0)
	    							{
	    								$SIC=$MySQLiconn->query("SELECT anchuraBloque as amplitud from tblotes where noop='".$noopOrigen."' and tipo='".$idtipo."'");
	    							}
	    						}
	    						$regSub=$SIC->fetch_assoc();
	    						if($regPackingList["longitud"]>0)
	    						{
	    						$codeList[$index]['descripcionDisenio']=$regPackingList["descripcionDisenio"];
	    						$codeList[$index]['descripcionImpresion']=$regPackingList["descripcionImpresion"];
	    						$codeList[$index]['longitud']=$regPackingList["longitud"];
	    						$codeList[$index]['refParcial']=$regPackingList["refParcial"];
	    						$codeList[$index]['unidades']=$regPackingList["unidades"];
	    						$codeList[$index]['noop']=$regPackingList["noop"];
	    						$codeList[$index]['procesoSiguiente']=$procesoSiguiente;
	    						$codeList[$index]['fecha']=$regPackingList["fecha"];
	    						$codeList[$index]['amplitud']=$regSub['amplitud'];
	    						$codeList[$index]['anchura']=$regPackingList["anchuraSliteo"];
	    						$codeList[$index]['peso']=$regPackingList["peso"];
	    						$divisiones=sendDivisions($idtipo,$procesoSiguiente,$procesoActual,$idProducto,$oldCodigo,$regPackingList["noop"],$MySQLiconn);
	    						$codigoBarras=generarCodigoBarras($idProceso,$noProces,$idLote,$divisiones,$regPackingList["noop"],$MySQLiconn);
	    						$codeList[$index]['codigoBarras']=$codigoBarras;
	    						$codeList[$index]['divisiones']=$divisiones;
	    						$index++;
	    						}
	    						
	    					}
	    					$codeList = serialize($codeList);
	    					$codeList = base64_encode($codeList);
	    					$codeList = urlencode($codeList);
	    					if($processName=="sliteo")
	    					{
	    						$label="<script>window.open('$stringfile?etiquetasInd=$codeList&idtipo=$idtipo&proceso=$processName', '_blank')</script>";
	    					}
	    					else
	    					{
	    						$label="<script>window.open('../generarEtiquetasDinamicas.php?etiquetasInd=$codeList&idtipo=$idtipo&proceso=$processName', '_blank')</script>";
	    					}
	    					
	    				}
	    			}
	    			else //En caso de que no sea ninguno de los procesos anteriores, diseñará una etiqueta genérica
	    			{
	    				$div=$MySQLiconn->query("SELECT divisiones from procesos where descripcionProceso='".$_GET['proceso']."'");
				$pr=$div->fetch_array();
				if($pr['divisiones']==1)
				{
					$divis=1;
				};

				if($divis==1)
				{
  					$consul=$MySQLiconn->query("SELECT  `tbpro".$procesoActual."`.*,impresiones.id as newid,(select descripcion from producto where ID=impresiones.descripcionDisenio) as descripcionDisenio,impresiones.descripcionImpresion,impresiones.refParcial,impresiones.anchoPelicula,impresiones.anchoEtiqueta,impresiones.espaciofusion,impresiones.alturaEtiqueta,impresiones.millaresPorRollo,impresiones.millaresPorPaquete from  `tbpro".$procesoActual."` inner join impresiones   where `tbpro".$procesoActual."`.noop like '$noop-%' and impresiones.id='$idProducto' and `tbpro".$procesoActual."`.tipo='$idtipo' and `tbpro".$procesoActual."`.longitud!='' order by `tbpro".$procesoActual."`.noop DESC");
				}
				else
				{

  					$consul=$MySQLiconn->query("SELECT `tbpro".$procesoActual."`.*,impresiones.id as newid,(select descripcion from producto where ID=impresiones.descripcionDisenio) as descripcionDisenio,impresiones.descripcionImpresion,impresiones.refParcial,impresiones.anchoPelicula,impresiones.anchoEtiqueta,impresiones.espaciofusion,impresiones.alturaEtiqueta,impresiones.millaresPorRollo,impresiones.millaresPorPaquete from  `tbpro".$procesoActual."` inner join impresiones   where `tbpro".$procesoActual."`.noop like '$noop' and impresiones.id='$idProducto' and `tbpro".$procesoActual."`.tipo='$idtipo' order by `tbpro".$procesoActual."`.noop DESC");
				}
	    			/*$consul=$MySQLiconn->query("SELECT `tbpro".$procesoActual."`.*,impresiones.id as newid,(select descripcion from producto where ID=impresiones.descripcionDisenio) as descripcionDisenio,impresiones.descripcionImpresion,impresiones.anchoPelicula,impresiones.anchoEtiqueta,impresiones.espaciofusion,impresiones.alturaEtiqueta,impresiones.millaresPorRollo,impresiones.millaresPorPaquete from `tbpro".$procesoActual."` inner join impresiones   where `tbpro".$procesoActual."`.noop like '$noop' and impresiones.id='$idProducto' and `tbpro".$procesoActual."`.tipo='$idtipo' order by `tbpro".$procesoActual."`.noop DESC");*/

	    				if ($consul->num_rows > 0)
	    				{
	    					$index=0;
	    					while ($regPackingList = $consul->fetch_assoc())
	    					{
	    						$SIC=$MySQLiconn->query("SELECT amplitud from `tbpro".$processName."` where noop  like '".$regPackingList["noop"]."' and tipo='".$idtipo."'");
	    						if(mysqli_num_rows($SIC)==0) 
	    						{
	    							$SIC=$MySQLiconn->query("SELECT amplitud from `tbpro".$procesoAnterior."` where noop  like '".$regPackingList["noop"]."' and tipo='".$idtipo."'");
	    							
	    							if(mysqli_num_rows($SIC)==0)
	    							{
	    								$SIC=$MySQLiconn->query("SELECT anchuraBloque as amplitud from tblotes where noop='".$noopOrigen."' and tipo='".$idtipo."'");
	    							}
	    						}
	    						$regSub=$SIC->fetch_assoc();
	    						$codeList[$index]['descripcionDisenio']=$regPackingList["descripcionDisenio"];
	    						$codeList[$index]['descripcionImpresion']=$regPackingList["descripcionImpresion"];
	    						$codeList[$index]['longitud']=$regPackingList["longitud"];
	    						$codeList[$index]['unidades']=$regPackingList["unidades"];
	    						$codeList[$index]['refParcial']=$regPackingList["refParcial"];
	    						$codeList[$index]['noop']=$regPackingList["noop"];
	    						$codeList[$index]['procesoSiguiente']=$procesoSiguiente;
	    						$codeList[$index]['fecha']=$regPackingList["fecha"];
	    						$codeList[$index]['amplitud']=$regSub['amplitud'];
	    						$codeList[$index]['anchoEtiqueta']=$regPackingList["anchoEtiqueta"];
	    						$codeList[$index]['espaciofusion']=$regPackingList["espaciofusion"];
	    						$codeList[$index]['anchoPelicula']=$regPackingList["anchoPelicula"];
	    						$codeList[$index]['alturaEtiqueta']=$regPackingList["alturaEtiqueta"];
	    						$divisiones=sendDivisions($idtipo,$procesoSiguiente,$procesoActual,$idProducto,$oldCodigo,$regPackingList["noop"],$MySQLiconn);
	    						$codigoBarras=generarCodigoBarras($idProceso,$noProces,$idLote,$divisiones,$regPackingList["noop"],$MySQLiconn);
	    						$codeList[$index]['codigoBarras']=$codigoBarras;
	    						$codeList[$index]['divisiones']=$divisiones;
	    						$index++;
	    					}
	    					$codeList = serialize($codeList);
	    					$codeList = base64_encode($codeList);
	    					$codeList = urlencode($codeList);
	    					$label="<script>window.open('../generarEtiquetasDinamicas.php?etiquetasInd=$codeList&idtipo=$idtipo&proceso=$processName', '_blank')</script>";
	    				}

	    			}


	    		}
	   	else if($idtipo=="1") //Si es banda de seguridad
	   	{
	   		$MySQLiconn->query("SET @p0='".$producto."',@p1='".$noProces."'");
	   		$OPS=$MySQLiconn->query("call getNodoBS(@p0,@p1)");
	   		$consRo=$OPS->fetch_assoc();
			$idProceso=$consRo['id'];//El id del proceso actual
			$numeroProceso=$consRo['numeroProceso'];//numero del proceso que se esta registrando
			$procesoSiguiente=$consRo['siguiente'];//el proceso siguiente para colocarlo en la etiqueta
			$procesoActual=$consRo['actual'];//la descripcion del proceso que se esta registrando
			$MySQLiconn->next_result();
			if($processName=="refilado")
			{
				$map=$_SESSION['codigo'];
				$consul=$MySQLiconn->query("SELECT `tbpro".$procesoActual."`.*,bandaspp.idBSPP as newid,bandaspp.idBSPP as newid,(select nombreBanda from bandaseguridad where IDBanda=bandaspp.identificadorBS) as descripcionDisenio,bandaspp.nombreBSPP as  descripcionImpresion,bandaspp.anchuraLaminado as alturaEtiqueta from `tbpro".$procesoActual."` inner join bandaspp   where `tbpro".$procesoActual."`.noop like '$noop-%' and `tbpro".$procesoActual."`.tipo='$idtipo' and $map='$oldCodigo' and bandaspp.IdBSPP='$producto'  order by `tbpro".$procesoActual."`.noop DESC");
			}
			else if($processName=="laminado" or $processName=="sliteo")
			{

				if($processName=="sliteo")
					{
						$consul=$MySQLiconn->query("SELECT `tbpro".$procesoActual."`.*,bandaspp.IdBSPP as newid,(select anchura from bandaseguridad where IDBanda=bandaspp.identificadorBS) as anchuraSliteo,(select nombreBanda from bandaseguridad where IDBanda=bandaspp.identificadorBS) as descripcionDisenio,bandaspp.nombreBSPP as descripcionImpresion,bandaspp.anchuraLaminado as anchoPelicula from  `tbpro".$procesoActual."` inner join bandaspp where `tbpro".$procesoActual."`.noop like '".$noop."-%' and bandaspp.IdBSPP='$idProducto' and `tbpro".$procesoActual."`.tipo='1' order by `tbpro".$procesoActual."`.id DESC");
					}
					else
					{
						$consul=$MySQLiconn->query("SELECT `tbpro".$procesoActual."`.*,bandaspp.IdBSPP as newid,(select anchura from bandaseguridad where IDBanda=bandaspp.identificadorBS) as anchuraSliteo,(select nombreBanda from bandaseguridad where IDBanda=bandaspp.identificadorBS) as descripcionDisenio,bandaspp.nombreBSPP as descripcionImpresion,bandaspp.anchuraLaminado as anchoPelicula from  `tbpro".$procesoActual."` inner join bandaspp where `tbpro".$procesoActual."`.noop like '$noop' and bandaspp.IdBSPP='$idProducto' and `tbpro".$procesoActual."`.tipo='1' order by `tbpro".$procesoActual."`.id DESC");
					} 
  					
  						
	    				if ($consul->num_rows > 0)
	    				{
	    					$index=0;
	    					while ($regPackingList = $consul->fetch_assoc())
	    					{
	    						$SIC=$MySQLiconn->query("SELECT amplitud from `tbpro".$processName."` where noop  like '".$regPackingList["noop"]."' and tipo='".$idtipo."'");
	    						if(mysqli_num_rows($SIC)==0) 
	    						{
	    							$SIC=$MySQLiconn->query("SELECT amplitud from `tbpro".$procesoAnterior."` where noop  like '".$regPackingList["noop"]."' and tipo='".$idtipo."'");
	    							
	    							if(mysqli_num_rows($SIC)==0)
	    							{
	    								$SIC=$MySQLiconn->query("SELECT anchuraBloque as amplitud from tblotes where noop='".$noopOrigen."' and tipo='".$idtipo."'");
	    							}
	    						}
	    						$regSub=$SIC->fetch_assoc();
	    						$codeList[$index]['descripcionDisenio']=$regPackingList["descripcionDisenio"];
	    						$codeList[$index]['descripcionImpresion']=$regPackingList["descripcionImpresion"];
	    						$codeList[$index]['longitud']=$regPackingList["longitud"];
	    						$codeList[$index]['unidades']=$regPackingList["unidades"];
	    						$codeList[$index]['noop']=$regPackingList["noop"];
	    						$codeList[$index]['procesoSiguiente']=$procesoSiguiente;
	    						$codeList[$index]['fecha']=$regPackingList["fecha"];
	    						$codeList[$index]['amplitud']=$regSub['amplitud'];
	    						$codeList[$index]['anchura']=$regPackingList["anchuraSliteo"];
	    						$codeList[$index]['peso']=$regPackingList["peso"];
	    						$divisiones=sendDivisions($idtipo,$procesoSiguiente,$procesoActual,$idProducto,$oldCodigo,$regPackingList["noop"],$MySQLiconn);
	    						$codigoBarras=generarCodigoBarras($idProceso,$noProces,$idLote,$divisiones,$regPackingList["noop"],$MySQLiconn);
	    						$codeList[$index]['codigoBarras']=$codigoBarras;
	    						$index++;
	    					}
	    					$codeList = serialize($codeList);
	    					$codeList = base64_encode($codeList);
	    					$codeList = urlencode($codeList);
	    					if($processName=="sliteo")
	    					{
	    						$label="<script>window.open('$stringfile?etiquetasInd=$codeList&idtipo=$idtipo&proceso=$processName', '_blank')</script>";
	    					}
	    					else
	    					{
	    						$label="<script>window.open('../generarEtiquetasDinamicas.php?etiquetasInd=$codeList&idtipo=$idtipo&proceso=$processName', '_blank')</script>";
	    					}
	    					
	    				}
				}

	    			

			
		}
	}
	else if($processName=="programado")
	{

		$numero=$etiquetasInd;
		$CK=$MySQLiconn->query("SELECT tipo,nombreProducto from tbproduccion where juegoLotes='$numero'");
		$Rl=$CK->fetch_assoc();
		$idtipo=$Rl["tipo"];
		$producto=$Rl["nombreProducto"];
		$numeroProceso=0;
		if($idtipo!="1")
		{
			$MySQLiconn->query("SET @p0='".$producto."',@p1='".$numeroProceso."'");
			$cons=$MySQLiconn->query("call getNodoProceso(@p0,@p1)");
			$consRo=$cons->fetch_assoc();
			$procesoSiguiente=$consRo['siguiente'];//el proceso siguiente para colocarlo en la etiqueta
			$procesoActual=$consRo['actual'];
			$MySQLiconn->next_result();
			$consul=$MySQLiconn->query("SELECT lotes.*,impresiones.id,(select descripcion from producto where ID=impresiones.descripcionDisenio) as descripcionDisenio,impresiones.descripcionImpresion,impresiones.anchoPelicula,impresiones.anchoEtiqueta,impresiones.espaciofusion from lotes inner join impresiones where lotes.juegoLote='$numero' and impresiones.descripcionImpresion=(SELECT nombreProducto from produccion where juegoLotes='$numero') and lotes.estado=1");
		}
		else
		{
			$MySQLiconn->query("SET @p0='".$producto."',@p1='".$numeroProceso."'");
			$cons=$MySQLiconn->query("call getNodoBS(@p0,@p1)");
			$consRo=$cons->fetch_assoc();
			$procesoSiguiente=$consRo['siguiente'];//el proceso siguiente para colocarlo en la etiqueta
			$procesoActual=$consRo['actual'];
			$MySQLiconn->next_result();
			$consul=$MySQLiconn->query( "SELECT lotes.idLote,lotes.longitud,lotes.anchuraBloque,lotes.peso,(lotes.unidades/1000) as unidades,lotes.noop,lotes.tarima,lotes.numeroLote,lotes.referenciaLote,produccion.juegoLotes,bandaspp.idBSPP as id,(select nombreBanda from bandaseguridad where IDBanda=bandaspp.identificadorBS) as descripcionDisenio,bandaspp.nombreBSPP as descripcionImpresion,bandaspp.anchuraLaminado as anchura from tbproduccion produccion inner join tblotes lotes on produccion.juegoLotes=lotes.juegoLote inner join bandaspp on produccion.nombreProducto=bandaspp.idBSPP where produccion.nombreProducto='$producto' and produccion.estado=2 and lotes.estado=1 and lotes.tipo='$idtipo' and lotes.juegoLote='$numero'");

	/*$consul=$MySQLiconn->query("SELECT  `tbpro".$procesoActual."`.*,bandaspp.idBSPP as newid,(select nombreBanda from bandaseguridad qwhere IDBanda=bandaspp.identificadorBS) as descripcionDisenio,bandaspp.nombreBSPP as  descripcionImpresion,bandaspp.anchuraLaminado as alturaEtiqueta,(SELECT anchura from bandaseguridad where IDBanda=(SELECT identificadorBS from bandaspp where IdBSPP='$producto')) as anchuraBS from  `tbpro".$procesoActual."` inner join bandaspp   where  `tbpro".$procesoActual."`.noop like '$noop%' and  `tbpro".$procesoActual."`.tipo='$idtipo' and $map='$oldCodigo' and bandaspp.IdBSPP='$idProducto'  order by  `tbpro".$procesoActual."`.noop DESC");*/
		}
		while ($regPackingList = $consul->fetch_assoc())
		{
		$divisiones=1;
		$idProducto=$regPackingList['id'];
	      			/*	if($procesoSiguiente=="corte")//En corte ya no se ajustan parametros,por lo tanto queda en 0
	      				{
	      					$divisiones =ceil($regPackingList["unidades"]/$regPackingList["millaresPorPaquete"]);
	      				}
	      				else if($procesoSiguiente=="refilado")
	      				{
	      					if($tipo!="1")
	      					{
	      						$divisiones=ceil($regPackingList["anchuraBloque"])/($regPackingList["anchura"]);
	      					}
	      					else
	      					{
	      						$divisiones=ceil($regPackingList["anchuraBloque"])/($regPackingList["anchura"]);
	      					}
	      				}
	      				else if($procesoSiguiente=="fusion")
	      				{         
	      					$divisiones = ceil((($regPackingList["longitud"]/$regPackingList["alturaEtiqueta"])/$regPackingList["millaresPorRollo"]));
	      				}
	      				else
	      				{
	      					$divisiones=1;
	      				}*/

	      				$divisiones=sendDivisions($idtipo,$procesoSiguiente,$procesoActual,$idProducto,'',$regPackingList["noop"],$MySQLiconn);
	      				$codigoBarras=generarCodigoBarras("20","0",$regPackingList["idLote"],$divisiones,$regPackingList["noop"],$MySQLiconn);
	      			}
	      			$label="No Label";
	      		}
	      	}

	      	return $label;
	      }
	      ?>

