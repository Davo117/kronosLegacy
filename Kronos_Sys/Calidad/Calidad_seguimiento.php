<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Seguimiento | Calidad</title>
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">

</head>
<body> 
<?php
include("../components/barra_lateral2.php");
include_once ("Controlador_Calidad/crud_seguimiento.php");
include("Controlador_Calidad/db_Producto.php");
//include("../css/barra_horizontal9.php");
$_SESSION['etiquetasInd']="";
?>

<div id="page-wrapper">
	<div class="container-fluid">

		<form method="GET" name="formulary" id="formulary" role="form">
			<div class="panel panel-default">

				<div class="panel-heading"><b class="titulo">Seguimiento de producto</b>
					
	</div>
	<div class="panel-body">
	<?php
	if(isset($_GET['Buscar']) && !empty($getRow))
	{
		?>


<div class="col-xs-3">
<label>Código</label>
<input id="peso" autofocus required type="text" name="peso" placeholder="Código de barras">
</div>
<button class="btn btn-default" type="submit" name="update">Actualizar</button>
<?php

	}
	else
	{
?>
<div class="col-xs-3">
<label for="Buscar">Código</label>
<input id="code" autofocus required class="form-control" type="text" name="Buscar" id="Buscar" placeholder="inserte el código del producto">
</div>
</div>
<button class="btn btn-info" style="float:right;">Buscar</button>
<?php
	}

	
?>
</form>
</div><hr><?php
if(isset($_GET['Buscar'])){
	if(!empty($INFO))
	{
		echo "<div class='panel panel-default'>

				<div class='panel-heading'><b class='titulo'>Información</b></div>
	<div class='panel-body'>";

		?>
		<a  style="float:right;" class="btn btn-default" onclick="return confirm('¿Deseas eliminar definitivamente el código  <?php echo ' '.$_GET['Buscar'];?>?'); "  title="Eliminar código de barras" href="?delet=<?php echo $_GET['Buscar'];?>&proceso=<?php echo $tableIn;?>"><img src="../pictures/deletProducto.png"></a>
		<?php
		$SQL=$MySQLiconn->query("SELECT c.noop,c.noProceso,c.codigo as id,c.tipo,c.proceso,(SELECT noop from tbcodigosbarras where noop=c.noop and tipo=c.tipo and noProceso=c.noProceso-1) as hasantnoop from tbcodigosbarras c where c.codigo='".$_GET['Buscar']."'");
		$res=$SQL->fetch_object();
		$noop=$res->noop;
		$tipo=$res->tipo;
		$idin=$res->id;
		$noProceso=$res->noProceso;
		$idProceso=$res->proceso;
		$opAnterior=explode('-',$noop);
		if(count($opAnterior)>1 and empty($res->hasantnoop))
		{
			//Estaba creando el noop anterior para ver que se pueda dar de baja posteriormente, verificar los procesos siguientes y si no se ha capturado
			if(count($opAnterior)==2)
			{
				$opAnterior=$opAnterior[0];
			}
			else if(count($opAnterior)==3)
			{
				$opAnterior=$opAnterior[0].'-'.$opAnterior[1];
			}
			else if(count($opAnterior)==4)
			{
				$opAnterior=$opAnterior[0].'-'.$opAnterior[1].'-'.$opAnterior[2];
			}
			
		}
		else
		{
			$opAnterior=$noop;
		}
		//Aquí se evalua que el mismo codigo sea el último generado, de lo contrario no deja deshacer
		$SQLo=$MySQLiconn->query("SELECT noop,proceso,codigo as id,tipo from codigosbarras where noop like '".$noop."%' and tipo=(select tipo from tipoproducto where id='".$tipo."') order by id desc limit 1");
		$ris=$SQLo->fetch_object();
		$proceso=$ris->proceso;
		$noop2=$ris->noop;
		$idre=$ris->id;
		$nametipo=$ris->tipo;
		if($idre!=$idin)
		{
			$SQLin=$MySQLiconn->query("SELECT id FROM `tbpro".$proceso."` where noop='".$noop2."' and tipo='".$tipo."'");
			//echo "SELECT id FROM `tbpro".$proceso."` where noop='".$noop2."' and tipo='".$tipo."'";
			//echo "SELECT id FROM `tbpro".$proceso."` where noop='".$noop2."' and tipo='".$tipo."'";
		$rowo=$SQLin->fetch_object();
		if(empty($rowo->id))
		{

		?>
		<a  style="float:right;"  class="btn btn-default" onclick="return confirm('¿Deseas deshacer el movimiento asociado al código <?php echo ' '.$_GET['Buscar'];?>?'); "  title="Deshacer movimiento" href="?redo=<?php echo $_GET['Buscar'];?>&proceso=<?php echo $tableIn;?>&redonoop=<?php echo $noop;?>&tipo=<?php echo $tipo;?>"><img class="image-responsive" width="32" height="28" src="../pictures/redo.png"></a>
		<?php
		}
		else
		{
			
			?>
		<a  style="float:right;opacity: 50%" class="btn btn-default"  title="No se puede deshacer porque existen movimientos posteriores, intente con otro código del mismo origen"><img class="image-responsive" width="32" height="28" src="../pictures/redo.png"></a>
		<?php
		echo "<br><br><div class='alert alert-warning alert-dismissible fade in'>
			<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			<strong>Sugerencia:</strong> , Intente agregando el último código pertececiente al mismo grupo.
			</div>";
		}
		}
		else if($idre==$idin)
		{
			$SQLin=$MySQLiconn->query("SELECT codigo FROM tbcodigosbarras where noop like '".$opAnterior."%' and tipo='".$tipo."' and noProceso>'".$noProceso."'");
			//echo "SELECT codigo FROM tbcodigosbarras where noop like '".$opAnterior."%' and tipo='".$tipo."' and noProceso>'".$noProceso."'";
			//echo "SELECT codigo FROM tbcodigosbarras where noop like '".$opAnterior."%' and tipo='".$tipo."' and noProceso>'".$noProceso."'";
		$rowo=$SQLin->fetch_object();
		if(empty($rowo->codigo))
		{
		?>
		<a  style="float:right;" class="btn btn-default" onclick="return confirm('¿Deseas deshacer el movimiento asociado al código <?php echo ' '.$_GET['Buscar'];?>?'); "  title="Deshacer movimiento" href="?redo=<?php echo $_GET['Buscar'];?>&proceso=<?php echo $tableIn;?>&redonoop=<?php echo $noop;?>&tipo=<?php echo $tipo;?>&idProceso=<?php echo $idProceso;?>"><img class="image-responsive" width="32" height="28" src="../pictures/redo.png"></a>
		<?php
		}
		else
		{
			?>
		<a  style="float:right;opacity: 50%" class="btn btn-default"  title="No se puede deshacer porque existen movimientos posteriores,intente con otro código del mismo origen"><img class="image-responsive" width="32" height="28" src="../pictures/redo.png"></a>
		<?php
		echo "<br><br><div class='alert alert-warning alert-dismissible fade in'>
			<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			<strong>Sugerencia:</strong> , Intente agregando el último código pertececiente al mismo grupo.
			</div>";
		}

			?>
		
		<?php
		}?>
		
			<p style="font:bold 20px Sansation Light" >Proceso: <?php echo strtoupper($tableIn)."";?> | Código:<?php echo $_GET['Buscar'];?></p>
			<?php
		while($row=$INFO->fetch_array())
		{
			if($row['COLUMN_NAME']!="total" && $row['COLUMN_NAME']!="id" && $row['COLUMN_NAME']!="rollo_padre" && $row['COLUMN_NAME']!="baja" && $row['COLUMN_NAME']!="shower" && $row['COLUMN_NAME']!="estado" && $row['COLUMN_NAME']!="idLote" && $row['COLUMN_NAME']!="noLote")
			{
				if($tableIn!="programado")
				{
					$SQL=$MySQLiconn->query("SELECT ".$row['COLUMN_NAME']." as dato from `pro".$tableIn."` where noop='$noop' and tipo='".$nametipo."'");//Puede fallar
			$op=$SQL->fetch_array();
				}
				else
				{
					$SQL=$MySQLiconn->query("SELECT ".$row['COLUMN_NAME']." as dato from `lotes` where noop='$noop' and tipo='".$nametipo."'");//Puede fallar
			$op=$SQL->fetch_array();
				}
			
		$string = $row['COLUMN_NAME'];
$string = strtolower(preg_replace('/([A-Z])/', ' $1', $string));
if($string=="lote" || $string=="bobina" || $string=="rollo")
{
	$string="Código de barras";
}
else if($string!="disco")
{

		?>
		
			<div style=""><p style="font:bold 20px Sansation Light" ><?php echo ucwords($string);?></p></div>
				<div style=""><p style="color:black;font:bold 20px Sansation ;margin-left: 10px;"><?php echo $op['dato'];?></p></div><hr>
				<?php
}
else if($string=="disco")
{	
		$Ban=$MySQLiconn->query("SELECT producto FROM codigosbarras WHERE codigo='".$op['dato']."' limit 1");
		$BS=$Ban->fetch_object();
		$nombreban=$BS->producto;
	?>
			<div style=""><p style="font:bold 20px Sansation Light" ><?php echo ucwords($string);?></p></div>
			<div style=""><p style="color:black;font:bold 20px Sansation ;margin-left: 10px;"><?php echo $op['dato']."  [".$nombreban."]";?></p></div><hr>
				<?php
}

}
}
echo "</div>";
echo "</div>";
}

else
{
	?>
	<div id="empaques">
	<p style="text-align:center;">El código ingresado no existe</p>
	</div>
	<?php
}
}?>
</center>
</body>
<script type="text/javascript">
	
</script>
</html>
	<?php
				ob_end_flush();
			} else {
				echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
				include "../ingresar.php";
			}

			?>

