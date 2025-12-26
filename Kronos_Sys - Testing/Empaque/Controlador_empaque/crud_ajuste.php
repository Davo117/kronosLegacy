<?php
@session_start();
include 'db_Producto.php';
include 'functionsEmpaque.php';

/* Inicio Código buscar */
//Si se dio clic en guardar:

/*if(!empty($_SESSION['empaqueActual']))
{
	$e=$_SESSION['empaqueActual'];	
	$_SESSION['estatusActualizar']="";
	$datos=parsearCodigoEmpaque($e);
	if(!empty($datos))
	{
		$datos=explode('|', $datos);
		$producto=$datos[0];
		$empaque=$datos[1];
		$refEmpaque=$datos[2];
		$SQL=$MySQLiconn->query("SELECT*from $empaque where codigo=$e");
		$row=$SQL->fetch_object();

		?>
			<p style="font:bold 20px Sansation" ;>Información</p>
			<p style="font:bold 20px Sansation Light" ><?php echo $empaque.":";?><b style="color:black;font:bold 30px Sansation;margin-right:10px;"><?php echo $refEmpaque;?></b></p>
			<p style="font:bold 20px Sansation Light" >Producto:<b style="color:black;font:bold 20px Sansation ;margin-left: 10px;"><?php echo $producto;?></b></p>
			<p style="font:bold 20px Sansation Light" >Piezas:<b style="color:black;font:bold 20px Sansation ;margin-left: 10px;"><?php echo $row->noElementos;?></b></p>
			<p style="font:bold 20px Sansation Light" >Millares:<b style="color:black;font:bold 20px Sansation;margin-left: 10px;"><?php echo $row->piezas;?></b></p>
		
		<?php
	}

	
}*/

if(isset($_GET['peso']))
{
	$b=$_SESSION['empaqueActual'];	
	$datos=parsearCodigoEmpaque($b,$MySQLiconn);
	if(!empty($datos) )
	{
		if($datos!="slapperro" || $datos!="slagato")
		{
			$datos=explode('|', $datos);
		$empaque=$datos[1];
		$refEmpaque=$datos[2];
		$peso=$_GET['peso'];

		$MySQLiconn->query("UPDATE $empaque set peso='$peso',baja=2 where codigo='$b'");
		//echo "<script>alert('Paquete actualizado');</script>";
		$_SESSION['empaqueActual']="";
		$_SESSION['estatusActualizar']="Empaque '".$refEmpaque."' actualizado exitosamente";
		$emp="etiq".$empaque."In";
		//echo "<script>window.open('../Empaque/pdf/empaques_exterior.php?etiquetasInd=$b&empaque=$empaque', '_blank')</script>";
		}
		else
		{
			$_SESSION['estatusActualizar']="Sin datos";
		}
		
	}
	else
	{
		$_SESSION['estatusActualizar']="Sin datos";
	}


}
if(isset($_GET['Buscar']))
{
	$e=$_GET['Buscar'];
	$datos=parsearCodigoEmpaque($e,$MySQLiconn);
	if(!empty($datos))
	{
		$_SESSION['empaqueActual']=$e;
	if(!empty($datos))
	{
		$datos=explode('|', $datos);
		$producto=$datos[0];
		$empaque=$datos[1];
		$refEmpaque=$datos[2];
		$SQL=$MySQLiconn->query("SELECT e.*,i.descripcionImpresion as productoc from $empaque e inner join impresiones i
		on e.producto=i.id where e.codigo='$e'");
		$getRow=$SQL->fetch_array();
		if(!empty($getRow['cdgEmbarque']))
		{
				$_SESSION['estatusActualizar']="Este empaque ya fue enlazado a un embarque";
		}
		else
		{
			$_SESSION['estatusActualizar']="";
		}

}
	}
	
		
}
?>