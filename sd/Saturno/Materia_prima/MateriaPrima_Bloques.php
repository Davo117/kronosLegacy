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
include("Controlador_Bloques/crud_bloques.php");
include("../css/barra_horizontal4.php");
?>
<center>
<div id="form">
<form method="post">
<p style="font-size:25px;font-family: monospace;padding-right:400px;padding-top:20px";   id="titulo">Bloques de materia prima<p>
<p>Identificador:<input type="text" name="identificadorBloque" value="<?php if(isset($_GET['edit'])) echo $getROW['identificadorBloque'];?>"
    size="20" placeholder="nombre corto"></p>
<p>Nombre:<input type="text" name="nombreBloque"  value="<?php if(isset($_GET['edit'])) echo $getROW['nombreBloque'];?>"
    size="20" placeholder="nombre del bloque"></p>
    <select name="comboSustratos" list="exampleList">
<datalist id="exampleList">
    <?php
include ("Controlador_Bloques/db_materiaPrima.php");
$resultado = $MySQLiconn->query("SELECT * FROM sustrato where baja=1");

while ($row = $resultado->fetch_array()) {
?> 
	<option value="<?php echo $row ['descripcionSustrato'];?>"><?php echo $row['descripcionSustrato'];?>
	<?php 
} 
?> 
</option>
</option>
</datalist>
</select>
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
include ("Controlador_Bloques/showBloques.php");
?>
</table>
</form>
</div>
</center>
</body>
</html>
