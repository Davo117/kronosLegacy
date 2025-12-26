<?php
@session_start();
include_once 'db_Producto.php';
include_once '../Produccion/Controlador_produccion/functions.php';


if(isset($_GET['peso']))
{
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
if(isset($_GET['Buscar']))
{
	$e=$_GET['Buscar'];
	if(is_numeric($e))
	{
		$datos=parsearCodigo($e);
		$datos=trim($datos);
	if(!empty($datos))
	{
		$_SESSION['empaqueActual']=$e;
	

	if(!empty($datos))
	{

		$datos=explode('|', $datos);
		$producto=$datos[0];
		$lote=$datos[2];
		$proceso=$datos[1];
		$noop=$datos[4];
		$tableIn=$proceso;
		
				//echo "SELECT operador from $tableIn where noop= (SELECT noop from lotes where referenciaLote='$lote')  )";
		
		if($proceso!="programado")
		{
		$INFO=$MySQLiconn->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'pro".$tableIn."' and table_schema = 'saturno'");
	}
	else
	{
		$INFO=$MySQLiconn->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'lotes' and table_schema = 'saturno'");
	}
	}
	
}
	}
	else
	{
		
	}
	
		
}
if(isset($_GET['delet']))
{
	echo "<script>alert('".$_GET['delet']." dice: `Adios`')</script>";
	$tableIn=$_GET['proceso'];
		$MySQLiconn->begin_transaction();
		mysqli_autocommit($MySQLiconn,FALSE);
	$PAM=$MySQLiconn->query("SELECT nombreparametro FROM juegoparametros WHERE identificadorJuego=(SELECT packParametros FROM procesos WHERE descripcionProceso='".$tableIn."') and numParametro='C'");
	$pr=$PAM->fetch_array();
	
	$parametro=$pr['nombreparametro'];
	
	$Mo1=$MySQLiconn->query("UPDATE `pro".$tableIn."` SET total=0 WHERE $parametro='".$_GET['delet']."'");

	$Mo2=$MySQLiconn->query("UPDATE codigosbarras SET baja=0 WHERE codigo='".$_GET['delet']."'");


	$array=parsearCodigo($_GET['delet']);
	$arrais=explode('|', $array);
	$noop=$arrais[4];
	$tipo=$arrais[6];
	$producto=$arrais[0];
	$proceso=$arrais[1];
	$POM=$MySQLiconn->query("
		SELECT r.unidades FROM `pro".$tableIn."` r
		where r.noop='".$noop."'");
	$pium=$POM->fetch_array();
	//$longitud=$pium['longitud'];
	$unidades=$pium['unidades'];
	if($proceso!="corte")
	{
		$Mo5=$MySQLiconn->query("UPDATE `pro".$tableIn."` SET total=0 WHERE noop='".$noop."' and tipo='$tipo'");
	}
	
	

	$Mo4=$MySQLiconn->query("INSERT INTO codigos_baja(codigo,tipo,producto,unidades,proceso) values('".$_GET['delet']."','$tipo','$producto','$unidades','".$tableIn."')");
	if(!$PAM || !$Mo1 ||!$Mo2 || !$Mo4 || !$POM || !$Mo5)
	{
		$MySQLiconn->ROLLBACK();
		echo"<script>alert('Algo salió mal durante la transacción,consulte al encargado de TI')</script>";
	}
	else
	{
		$MySQLiconn->COMMIT();
	}
}
?>