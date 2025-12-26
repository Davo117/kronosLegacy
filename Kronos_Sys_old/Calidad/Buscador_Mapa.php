<?php
ob_start();
session_start();

/*if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {} 
else {
  echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
  echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../ingresar.php'>";
  exit;
}
$now = time();
if($now > $_SESSION['expire']) {
	session_destroy();
	echo '<script language="javascript">alert("Tu sesión caducó");</script>'; 
	echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../ingresar.php'>";
	exit;
}*/
$_SESSION['codigoPermiso']='774887464864';
//include ("funciones/permisos.php");
//include ("funciones/example.php"); 
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Buscador(Calidad) | Grupo Labro</title>
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#fff">
<script type="text/javascript" src="../FusionCharts/js/fusioncharts.js"></script>
<script type="text/javascript" src="../FusionCharts/js/themes/fusioncharts.theme.ocean.js"></script>
</head>

<body> 
<?php 	include("../FusionCharts/integrations/php/fusioncharts-wrapper/fusioncharts.php");
include("../components/barra_lateral2.php");
 include("Controlador_Calidad/db_Producto.php"); ?>
<div id="page-wrapper">
	<div class="container-fluid">

		<form method="POST" name="formulary" id="formulary" role="form">
			<div class="panel panel-default">

				<div class="panel-heading"><b class="titulo">Mapa de procesos</b>
					</div>
						<div class="panel-body">
							
							
<div class="col-xs-3"><label for="lista">Tipo de Código*:</label>
<select name="lista" class="form-control" id="lista" required onchange="document.formulary.submit()">
    <option value="" <?php if(!isset($_POST['lista'])){ echo "selected";}?>>Seleccione una Opción</option>
	<option value="1"  <?php if(isset($_POST['lista']) and $_POST['lista']=="1") echo "selected";?>>Código de bobina o paquete</option>
	<option value="2" <?php if(isset($_POST['lista']) and $_POST['lista']=="2") echo "selected";?>>Código de Empaque (Caja o Queso)</option>
	<option value="3" <?php if(isset($_POST['lista']) and $_POST['lista']=="3") echo "selected";?>>Código de Embarque</option>
	<option value="4" <?php if(isset($_POST['lista']) and $_POST['lista']=="4") echo "selected";?>>NoOP</option>
</select>
</div>
<?php
if(isset($_POST['lista']))
{
	if($_POST['lista']=="4")
	{
?>
<div class="col-xs-3">
	<label for="tipo">Seleccione el tipo</label>
	<select name="tipo" id="tipo" class="form-control">

		<option value="">--</option>
		<?php
		$SQL=$MySQLiconn->query("SELECT tipo FROM tipoproducto WHERE baja=1");
		while($row=$SQL->fetch_array())
		{?>
			<option value="<?php echo $row['tipo'];?>"> <?php echo $row['tipo'];?></option>
			<?php

		}
		?>
	</select>
</div>
<?php
	}
}
if(isset($_POST['lista']))
{
	if($_POST['lista']=="4")
	{?>
		<div class="col-xs-3"><label for="codigo">Número de operación</label>
<input type="text" id="codigo" class="form-control" name="codigo" value="" size="20" placeholder="NoOp" required>
</div>
<?php
	}
	else
	{
		?>
<div class="col-xs-3"><label for="codigo">Código</label>
<input type="text" class="form-control" name="codigo" value="" id="codigo" size="20" placeholder="Código" required>
</div>
<?php
	}
}
else
{?>
<div class="col-xs-3"><label for="codigo">Código</label>
<input type="text" name="codigo" value="" class="form-control" id="codigo" size="20" placeholder="Código" required>
</div>
<?php
}


?>

</div>
<button class="btn btn-info" style="float:right;" type="submit" name="search">Buscar</button>
</form>
</div>
<div class="panel-heading" id="chart-1" style=""></div>
<?php 	include('Controlador_Calidad/Mapeo.php'); ?>

</div>
</body>
</html>
		<?php
				ob_end_flush();
			} else {
				echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
				include "../ingresar.php";
			}

			?>