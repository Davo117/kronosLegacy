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
include("Controlador_Disenio/crud_BandaSeguridad.php")
?>
<center>
<div id="form">
<form method="post" name="formulary" id="formulary" >
<p style="font-size:25px;font-family: monospace;padding-right:500px;padding-top:20px";   id="titulo">Bandas de seguridad<p>
<p>Identificador:<input type="text" name="identificador" value="<?php if(isset($_GET['edit'])) echo $getROW['identificador'];?>"
    size="20" placeholder="identificador">   Nombre:<input type="text" name="nombreBanda"  value="<?php if(isset($_GET['edit'])) echo $getROW['nombreBanda'];?>"
    size="20" placeholder="nombre de banda"> Anchura:<input type="text" name="anchura" value="<?php if(isset($_GET['edit'])) echo $getROW['anchura'];?>"
    size="10" placeholder="anchura"></p>
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
include ("Controlador_Disenio/showBandaSeguridad.php");
?>
</table>

</div>
</center>
</body>
</html>
