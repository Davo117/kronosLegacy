<?php
ob_start();
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
<title>Parametros| Misceláneos</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">
<link rel="stylesheet" type="text/css" href="../css/Stylish.css" />
  <link rel="stylesheet" href="../css/stylees.css" type="text/css" media="screen"/>
   <link rel="stylesheet" href="../css/StyleTablas.css" type="text/css" media="screen"/>
     <link rel="stylesheet" href="../css/formulary.css" type="text/css" media="screen"/>

</head>

<body> 
<?php
	$_SESSION['codigoPermiso']='80005';
	include ("funciones/permisos.php");

include("Controlador_Misce/db_Producto.php");
include("../components/barra_lateral2.php");
include("../css/barra_horizontal7.php");
include("Controlador_Misce/crud_Parametros.php");
?>
<center>
<div id="form">
<form method="POST" name="formulary" id="formulary" >
<p id="titulo">Parámetros</p>
<p>Nombre:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Tipo:</p>

<p>
	 <input type="text" name="nombreParametro" value="<?php if(isset($_GET['edit'])) echo $getROW['Field'];?>"
	size="20" placeholder="nombre sin espacios" required> &nbsp; &nbsp;
  <select  type="POST" name="Type"> 
	<option value="float">Númerico con decimales</option>
	<option value="int">Númerico entero</option>
	<option value="varchar">Texto</option>
	</select>&nbsp;&nbsp;
	</p>
	<p style="font:bold;">Nota:<b style="color:red;font:bold 100% Sansation light;">Evite usar nombres con "ñ" o palabras con acento</b></p>
 <?php

	?>
	<button type="submit" class="botonPerson3" style="transform:translate(-400px,-50px);" name="save">Guardar</button>
	<?php
?>
</form>

<p class="ordenMedio">Parámetros Disponibles</p>
<table width='55%'' border='0' cellpadding='15' style='margin-left:200px''>
<?php
include("Controlador_Misce/showParametrosOnly.php");
?>
</table>
</div>
</center>
</body>

</html>
<?php
ob_end_flush();
?>
