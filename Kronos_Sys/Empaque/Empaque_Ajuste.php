<?php
session_start();
	if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
		?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ensamble | Ajuste</title>
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">
   

</head>
<body> 
<?php
include("../components/barra_latera_almacen.php");
include_once ("Controlador_empaque/crud_ajuste.php");
include("Controlador_empaque/db_Producto.php");
//include("../css/barra_horizontal8.php");

//$SQL=$MySQLiconn->query("TRUNCATE pruebas");

$_SESSION['etiquetasInd']="";
$_SESSION['estatus']="";
$_SESSION['array']=array();//Variable para el array de paquetes o rollos

if(isset($_GET['stat']) and empty($_SESSION['banderin']))
{
	if($_GET['stat']==1)
	{
		$_GET['stat']=0;
		$_SESSION['banderin']="Hey";
		echo "<script>location.reload();</script>";

	}
}
?>

<div id="page-wrapper">
	
	<div class="container-fluid">
<?php
	if(isset($_GET['Buscar']) && !isset($_GET['stat']))
	{?>
		<div class='alert alert-warning alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Información</strong> ,<?php echo $_SESSION['estatusActualizar'];?>
		</div>
		<?php
	}?>
	<?php
	
	if(isset($_GET['Buscar']) && !empty($getRow) && empty($getRow['cdgEmbarque']))
	{
		?>
<form role="form">
	<div class="panel panel-default">

				<div class="panel-heading"  style="overflow:hidden;"><b class="titulo">Pesaje de empaques</b></div>
<div class="panel-body">
<div class="col-xs-3">
<label for="peso"></label>
<input id="peso" class="form-control" min="0" max="100" style="margin-bottom:15px;margin-top:10px;color:black;" autofocus required type="number" name="peso" step=".0001"  placeholder="kilos">
</div>
</div>
<button class="btn btn-default" style="float:right;" type="submit" name="update">Actualizar</button>
<?php

	}
	else
	{
?>
<form method="GET" >
	<div class="panel panel-default">

				<div class="panel-heading"  style="overflow:hidden;"><b class="titulo">Pesaje de empaques</b></div>
<div class="panel-body">
	<div class="col-xs-3">
<label for="code"></label>
<input id="code" class="form-control" onkeypress="return numeros(event)" style="margin-bottom:15px;margin-top:10px;color:black;" autofocus required type="text" name="Buscar" placeholder="inserte el código del empaque">
</div>
</div>
<button class="btn btn-default" style="float:right;">Buscar</button>
<?php
	}

	
?>
</form>
</div>
</div>
</div><?php
if(isset($_GET['Buscar'])){
	if(!empty($getRow))
	{
		?>
		<div class="container-fluid col-xs-5"><div id="empaques" class="panel panel-default">
		<div class="panel-heading"  style="overflow:hidden;"><b class="titulo">Información</b></div>
<div class="panel-body">
		<?php $_SESSION['empaqueActual']=$_SESSION['empaqueActual'];?>
			<h4><?php echo $empaque.":";?><b class="bes3"><?php echo $getRow['referencia'];?></b></h4>
			<h4>Producto:<b class="bes3"><?php echo $getRow['productoc'];?></b></h4>
			<h4>Piezas:<b class="bes3"><?php echo $getRow['noElementos'];?></b></h4>
			<h4>Millares:<b class="bes3"><?php echo $getRow['piezas'];?></b></h4>
			<?php if($getRow['peso']>0)
			{?>
				<h4>Peso:<b class="bes3"><?php echo number_format($getRow['peso'],2)."kgs";?></b></h4>
				<?php
			}?>
</div>
</div>
</div>


<?php
}
else
{
	?>
	<div id="empaques">
	<p style="text-align:center;">Sin coincidencias</p>
</div>
	<?php
}
}?>
</center>
</body>
<script type="text/javascript" src="../css/teclasN.js"></script>
<script type="text/javascript">

   /* var r = document.getElementById("r"); 
    //Tus validaciones para saber si los datos son correctos o no....
    r.value = 1 //o 0, dependiendo de tu validacion
    r.element.form.submit();*/
</script>
<script type="text/javascript" src="../css/menuFunction.js"></script>
</html>
<?php
} else {
	  echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
	  echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../ingresar.php'>";
	exit;
	} ?>