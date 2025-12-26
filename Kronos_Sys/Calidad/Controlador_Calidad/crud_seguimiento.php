<?php
@session_start();
include_once 'db_Producto.php';
include_once '../Produccion/Controlador_produccion/functions.php';


if(isset($_GET['peso'])){
	$e=$_SESSION['empaqueActual'];	
	$datos=parsearCodigoEmpaque($e);
	if(!empty($datos))
	{
		$datos=explode('|', $datos);
		$empaque=$datos[1];
		$refEmpaque=$datos[2];
		$peso=$_GET['peso'];

		$MySQLiconn->query("UPDATE $empaque set peso='$peso',baja=2 where codigo='$e'");
		echo "<script>alert('Paquete actualizado');</script>";
		$_SESSION['empaqueActual']="";
		$_SESSION['estatusActualizar']="Empaque '".$refEmpaque."' actualizado exitosamente";
	}
}


if(isset($_GET['Buscar'])){
	$e=$_GET['Buscar'];
	if(is_numeric($e)){
		$datos=parsearCodigo($e,$MySQLiconn);
		$datos=trim($datos);
		if(!empty($datos)){	
			$_SESSION['empaqueActual']=$e;
			if(!empty($datos)){
				$datos=explode('|', $datos);
				$producto=$datos[0];
				$lote=$datos[2];
				$proceso=$datos[1];
				$noop=$datos[4];
				$tableIn=$proceso;
		
				//echo "SELECT operador from $tableIn where noop= (SELECT noop from lotes where referenciaLote='$lote')  )";
		
				if($proceso!="programado"){
					$INFO=$MySQLiconn->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'pro".$tableIn."' and table_schema = 'saturno'");
				}
				else{
					$INFO=$MySQLiconn->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'lotes' and table_schema = 'saturno'");
				}
			}
		}
	}
	else{	 }	
}


if(isset($_GET['delet'])){
	echo "<script>alert('".$_GET['delet']." dice: `Adiós`')</script>";
	$tableIn=$_GET['proceso'];
		$MySQLiconn->begin_transaction();
		mysqli_autocommit($MySQLiconn,FALSE);
	$PAM=$MySQLiconn->query("SELECT nombreparametro FROM juegoparametros WHERE identificadorJuego=(SELECT packParametros FROM procesos WHERE descripcionProceso='".$tableIn."') and numParametro='C'");
	$pr=$PAM->fetch_array();
	
	$parametro=$pr['nombreparametro'];
	$array=parsearCodigo($_GET['delet'],$MySQLiconn);
	
		$arrais=explode('|', $array);
	$noop=$arrais[4];
	$tipo=$arrais[9];
	$producto=$arrais[8];
	$proceso=$arrais[1];
	if(!empty($tipo) and !empty($proceso)){
		$POM=$MySQLiconn->query("SELECT r.unidades FROM `tbpro".$tableIn."` r where r.noop='".$noop."'");
		$pium=$POM->fetch_array();
		//$longitud=$pium['longitud'];
		$unidades=$pium['unidades'];
	
		$Mo5=$MySQLiconn->query("UPDATE `tbpro".$tableIn."` SET total=0 WHERE noop='".$noop."' and tipo='$tipo'");
	
		$Mo1=$MySQLiconn->query("UPDATE `tbpro".$tableIn."` SET total=0 WHERE $parametro='".$_GET['delet']."'");

		$Mo2=$MySQLiconn->query("UPDATE tbcodigosbarras SET baja=0 WHERE codigo='".$_GET['delet']."'");

		$Mo4=$MySQLiconn->query("INSERT INTO codigos_baja(codigo,tipo,producto,unidades,proceso) values('".$_GET['delet']."','$tipo','$producto','$unidades','".$tableIn."')");
		if(!$PAM || !$Mo1 ||!$Mo2 || !$Mo4 || !$POM || !$Mo5){
			$MySQLiconn->ROLLBACK();
			echo"<script>alert('Algo salió mal durante la transacción,consulte al encargado de TI')</script>";
		}
		else{
			$SQUIL=$MySQLiconn->query("INSERT INTO reporte(nombre,accion,modulo,departamento,registro) values('".$_SESSION['usuario']."','Eliminó el producto ".$_GET['delet']."','Seguimiento','Calidad',now())");
			$MySQLiconn->COMMIT();
		}
	}
}


if(isset($_GET['redo'])){
	$MySQLiconn->begin_transaction();
	mysqli_autocommit($MySQLiconn,FALSE);
	$tableIn="tbpro".$_GET['proceso'];
	$noop=$_GET['redonoop'];
	$idProceso=$_GET['idProceso'];
	//Se trae el número de operación de un proceso anterior
	$SELL=$MySQLiconn->query("SELECT noop,proceso,codigo,lote FROM codigosbarras where noProceso=(select noProceso-1 from tbcodigosbarras where noop='".$noop."' and tipo='".$_GET['tipo']."' order by noProceso desc limit 1) and lote=(SELECT lote from tbcodigosbarras WHERE noop='".$noop."' and tipo='".$_GET['tipo']."' order by noProceso desc limit 1)");
	//Se asegura de que no se haya intentado capturar un movimiento en el proceso futuro
	$Xatu=$MySQLiconn->query("SELECT descripcionProceso from juegoprocesos where  numeroProceso=(SELECT noProceso+1 from tbcodigosbarras where noop='".$noop."' and tipo='".$_GET['tipo']."' order by noProceso desc limit 1) and identificadorJuego=(SELECT juegoProcesos from tipoproducto WHERE id='".$_GET['tipo']."')");
	$ruX=$Xatu->fetch_assoc();
	if($Xatu)
	{
		$MySQLiconn->query("DELETE FROM `tbpro".$ruX['descripcionProceso']."` WHERE noop like '".$noop."%' and tipo='".$_GET['tipo']."'");
		//--echo "DELETE FROM `tbpro".$ruX['descripcionProceso']."` WHERE noop like '".$noop."%' and tipo='".$_GET['tipo']."'";
		$Skpe=$MySQLiconn->query("DELETE FROM tbmerma where codigo='".$_GET['redo']."'");
		//--echo "DELETE FROM tbmerma where codigo='".$_GET['redo']."'";
	}
	
	/*echo "SELECT noop,proceso,codigo,lote FROM codigosbarras where noProceso=(select noProceso-1 from tbcodigosbarras where noop='".$noop."' and tipo='".$_GET['tipo']."' order by noProceso desc limit 1) and lote=(SELECT lote from tbcodigosbarras WHERE noop='".$noop."' and tipo='".$_GET['tipo']."' order by noProceso desc limit 1)";*/
	//En esta linea busco el noop que sea igual al que se quiera eliminar, para identificarlo
	while($rum=$SELL->fetch_object()){
		if(strncmp($rum->noop,$noop,strlen($rum->noop))==0){
			$noopOld=$rum->noop;
			$tableAn="tbpro".$rum->proceso;
			$codigoAnterior=$rum->codigo;
			$lote=$rum->lote;
		}
	}
	//Me traigo todos los numeros de operación que surgen a partir del noop origen
	/*echo $noopOld."---";
	echo $tableAn."**";
	echo $codigoAnterior."-----";
	echo $lote."//";*/
	//Anteriormente estaba esta consulta que traía todos los que tenían un código de barras asignado
	//$SQLf=$MySQLiconn->query("SELECT p.noop FROM `".$tableIn."` p inner join tbcodigosbarras c on c.noop=p.noop where p.noop like '".$noopOld."%' and p.tipo='".$_GET['tipo']."' and c.lote='".$lote."'");
	$SQLf=$MySQLiconn->query("SELECT p.noop FROM `".$tableIn."` p where p.noop like '".$noopOld."%' and p.tipo='".$_GET['tipo']."'");
	/*echo "SELECT p.noop FROM `".$tableIn."` p inner join tbcodigosbarras c on c.noop=p.noop where p.noop like '".$noopOld."%' and p.tipo='".$_GET['tipo']."' and c.lote='".$lote."'";
	return;*/
	
	while($row=$SQLf->fetch_object()){
		if(strncmp($noopOld,$row->noop,strlen($noopOld))==0){ 
		 	//Si coincide la primera fracción del numero de operación va a borrar de la tabla de proceso y de codigosBarras
        	$SKU=$MySQLiconn->query("DELETE FROM `".$tableIn."` WHERE noop='".$row->noop."' and tipo='".$_GET['tipo']."'");
        	//--echo "--DELETE FROM `".$tableIn."` WHERE noop='".$row->noop."' and tipo='".$_GET['tipo']."'";
        	$SQU=$MySQLiconn->query("DELETE FROM tbcodigosbarras where noop='".$row->noop."' and proceso='".$idProceso."' and tipo='".$_GET['tipo']."'");
        	//--echo "--DELETE FROM tbcodigosbarras where noop='".$row->noop."' and proceso='".$idProceso."' and tipo='".$_GET['tipo']."'";
      	}
	}
	if($tableAn!="tbproprogramado"){
		$QUERY=$MySQLiconn->query("UPDATE `".$tableAn."` SET total=1 WHERE noop='".$noopOld."' and tipo='".$_GET['tipo']."'");
		//--echo "UPDATE `".$tableAn."` SET total=1 WHERE noop='".$noopOld."' and tipo='".$_GET['tipo']."'";
		$SCAR=$MySQLiconn->query("DELETE FROM tbmerma where codigo='".$codigoAnterior."'");
		//--echo "DELETE FROM tbmerma where codigo='".$codigoAnterior."'";
	}
	else{
		$QUERY=$MySQLiconn->query("UPDATE tblotes SET estado=1 WHERE referenciaLote='".$lote."' and tipo='".$_GET['tipo']."'");
		//--echo "--UPDATE tblotes SET estado=1 WHERE referenciaLote='".$lote."' and tipo='".$_GET['tipo']."'";
		$SCAR=$MySQLiconn->query("DELETE FROM tbmerma where codigo='".$codigoAnterior."'");
		//--echo "--DELETE FROM tbmerma where codigo='".$codigoAnterior."'";

	}
	
	if(!$SKU or !$SQU or !$QUERY or !$SCAR){
		$MySQLiconn->rollback();
		echo "Hizo rollback
		<script>alert('Ocurrió un error durante el registro,contacte a soporte técnico')</script>
		<script>window.location='Calidad_seguimiento.php';</script>";
	} else{
		$SQUIL=$MySQLiconn->query("INSERT INTO reporte(nombre,accion,modulo,departamento,registro) values('".$_SESSION['usuario']."','Deshizo el rollo ".$noopOld."','Seguimiento','Calidad',now())");
		$MySQLiconn->commit();
		echo"<script>alert('Movimiento eliminado')</script>
		<script>window.location='Calidad_seguimiento.php';</script>";
	}
} ?>