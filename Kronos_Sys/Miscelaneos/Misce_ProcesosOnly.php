<?php
session_start();
 ob_start();
  if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
	?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Procesos| Misceláneos</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">

</head>

<body> 
<?php
	$_SESSION['codigoPermiso']='80005';
	include ("funciones/permisos.php");

include("Controlador_Misce/db_Producto.php");
include("../components/barra_lateral2.php");
//include("../css/barra_horizontal7.php");
include("Controlador_Misce/crud_procesos.php");
?>
<div id="page-wrapper">
  <div class="container-fluid">
<form method="POST" name="formulary" id="formulary" role="form">
<div class="panel panel-default">

        <div class="panel-heading"><b class="titulo">Procesos</b>
</div>
<div class="panel-body">

<div class="col-sm-3">
<label for="descripcionProceso">Descripción proceso:</label>
<input disabled type="text" class="form-control" name="descripcionProceso" id="descripcionProceso" value="<?php if(isset($_GET['edit'])) echo $getROW['descripcionProceso'];?>"
	size="20" placeholder="nombre proceso" required> 
</div> 
<div class="col-sm-3"> 
<label for="abreviacion">Abreviación:</label>
<input disabled id="abreviacion" class="form-control" type="text" placeholder="nombre corto" name="abreviacion" value="<?php if(isset($_GET['edit'])) echo $getROW['abreviacion'];?>">
</div>
<div class="col-sm-3">
<label for="merma_p">Merma permitida</label>
<input type="text" name="merma_p" id="merma_p" class="form-control" name="merma_p" placeholder="Porcentaje" value="<?php if(isset($_GET['edit'])) echo $getROW['merma_p'];?>">
</div>
</div>
 <?php
if(isset($_GET['edit']))
{
	?>
	<button type="submit" class="btn btn-default" style="float:right;" name="update">Actualizar</button>
	<?php
}
else
{
	?>
	<button type="submit" disabled class="btn btn-default" style="float:right;" name="save">Guardar</button>
	<?php
}
?>

</form>
</div>
<h4 class="ordenMedio">Procesos activos</h4>
<div class="table-responsive">
<table  border='0' cellpadding='15' class="table table-hover">
<?php
include ("Controlador_Misce/showProcesosOnly.php");
?>
</table>
</div>
</div>
</body>
</html>
<?php 
}
else {
  echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
  echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../ingresar.php'>";
  exit;
}
ob_end_flush();  ?>