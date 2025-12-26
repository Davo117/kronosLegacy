<?php
session_start();
	if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
	 
	} else {
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
	}
	?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../css/Stylish.css" />
  <link rel="stylesheet" href="../css/stylees.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/StyleTablas.css" type="text/css" media="screen"/>
   <link rel="stylesheet" href="../css/formulary.css" type="text/css" media="screen"/>
</head>

<body> 
<?php

include("../components/barra_lateral2.php");
include("../css/barra_horizontal2.php");
include("Controlador_Disenio/crud_bandaSPP.php");
?>
<center>
<div id="form">
<form method="post" name="formulary" id="formulary">
<p style="font-size:25px;font-family: monospace;padding-right:335px;padding-top:20px";   id="titulo">Bandas de seguridad por proceso<p>
<select name="comboBSPP" list="exampleList">
<datalist id="exampleList">
    <?php
include ("Controlador_Disenio/db_Producto.php");
$resultado = $MySQLiconn->query("SELECT * FROM bandaseguridad where baja=1");

while ($row = $resultado->fetch_array()) {
?> 
<?php
if( $row ['nombreBanda']==$_SESSION['descripcionBanda'])
{
	?>
	<option value="<?php echo $row ['nombreBanda'];?>" selected><?php echo $row['nombreBanda'];?></option>
	<?php
}
	else{
		?>
<option value="<?php echo $row ['nombreBanda'];?>"><?php echo $row['nombreBanda'];?></option>
<?php
}
}
?>
</datalist>
</select>
<p>Identificador:<input type="text" name="identificadorBSPP" value="<?php if(isset($_GET['edit'])) echo $getROW['identificadorBSPP'];?>"
    size="12" placeholder="nombre corto"> Nombre:<input type="text" name="nombreBSPP"  value="<?php if(isset($_GET['edit'])) echo $getROW['nombreBSPP'];?>"
    size="20" placeholder="nombre completo">  Anchura para laminado:<input type="text" name="anchuraLaminado" value="<?php if(isset($_GET['edit'])) echo $getROW['anchuraLaminado'];?>"
    size="7" placeholder="mm"></p>
<select name="comboSustratos" list="exampleList">
<datalist id="exampleList">
    <?php
include ("Controlador_Disenio/db_Producto.php");
$resultado = $MySQLiconn->query("SELECT * FROM sustrato where baja=1");

while ($row = $resultado->fetch_array()) {
?> 
	<option value="<?php echo $row ['descripcionSustrato'];?>" ><?php echo $row['descripcionSustrato'];?></option>
	<?php 
}
?>
</datalist>
</select>
<?php
?>
<?php
if(isset($_GET['edit']))
{
	
	?>
	<button type="submit" name="update">Actualizar</button>
	<?php
}
else
{
	?>
	<button type="submit" name="save">Guardar</button>
	<?php
}
?>
</form>
<table width="55%" border="1" cellpadding="15" style='margin-left:200px;''>
<?php
include ("Controlador_Disenio/showBandaSPP.php");
?>
</table>

</form>
</div>
</center>
</body>
</html>
