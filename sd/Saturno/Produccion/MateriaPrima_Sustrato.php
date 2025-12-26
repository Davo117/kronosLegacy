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

</head>

<body> 
<?php

include("../components/barra_lateral2.php");
include("Controlador_Bloques/crud_sustrato.php");
include("../css/barra_horizontal4.php");
?>
<center>
<div id="form">
<form method="post">
<p style="font-size:25px;font-family: monospace;padding-right:600px;padding-top:20px";   id="titulo">Sustratos<p>

<p>Código:<input type="text" name="codigoSustrato" value="<?php if(isset($_GET['edit'])) echo $getROW['codigoSustrato'];?>"
    size="20" placeholder="nombre corto"></p>
<p>Descripción:<input type="text" name="descripcionSustrato"  value="<?php if(isset($_GET['edit'])) echo $getROW['descripcionSustrato'];?>"
    size="20" placeholder="nombre completo"></p>
<p>Rendimiento:<input type="text" name="rendimiento" value="<?php if(isset($_GET['edit'])) echo $getROW['rendimiento'];?>"
    size="20" placeholder="metros cuadrados por kilo"></p>
    <p>Anchura:<input type="text" name="anchura" value="<?php if(isset($_GET['edit'])) echo $getROW['anchura'];?>"
    size="20" placeholder="mm"></p>
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
include ("Controlador_bloques/showSustrato.php");
?>
</table>
</div>
</center>
</body>
</html>
