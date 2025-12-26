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

$_SESSION['descripcion']=" ";
$_SESSION['descripcionCil']=" ";
$_SESSION['descripcionBanda']=" ";
include("Controlador_Disenio/crud_product.php");
include("Controlador_Disenio/db_Producto.php");
include("../components/barra_lateral2.php");
?>
<?php
include("../css/barra_horizontal2.php");
?>
<center>
<div id="form">
<form method="POST" name="formulary" id="formulary" style="height:110px" >
<p style="font-size:25px;font-family: monospace;padding-right:670px;padding-top:6px";   id="titulo">Diseño</p>
<p>Descripción:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Codigo:</p><p><input type="text" name="descripcion" value="<?php if(isset($_GET['edit'])) echo $getROW['descripcion'];?>"
    size="20" placeholder="Descripción" required> &nbsp;&nbsp;<input type="text" name="codigo" value="<?php if(isset($_GET['edit'])) echo $getROW['codigo'];?>"
	size="20" placeholder="Codigo" required> &nbsp;&nbsp;<SELECT name="tipocombo"  method="post" > 
<OPTION VALUE="Manga" >Manga</OPTION>
<OPTION VALUE="Flexografia">Flexografia</OPTION>
<OPTION VALUE="Holograma">Holográfico</OPTION>
 </SELECT></p>
<p class="txtChecks">Mostrar todo<input class="checks" type="checkbox" name="checkTodos" onclick="if (this.checked==true){ location.href='Producto_Disenio_bajas.php';}
else{
location.href='Producto_Disenio_bajas.php';
};
			
			
			"></p>
    
<?php
if(isset($_GET['edit']))
{
	?>
	<button class="botonPerson1" type="submit" name="update">Actualizar</button>
	<?php
}
else
{
	?>
	<button class="botonPerson1" type="submit" name="save">Guardar</button>
	<?php
}
?>
</form>
<p class="ordenMedio">Diseños Activos</p>
<table width="55%" border="1" cellpadding="15" style='margin-left:200px;''>
<?php
include ("Controlador_Disenio/show.php");
?>
</table>
</div>
</center>
</body>
</html>
