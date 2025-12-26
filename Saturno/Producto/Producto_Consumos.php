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
/*include("Controlador_Disenio/crud_impresion");*/
include("../components/barra_lateral2.php");
include("../css/barra_horizontal2.php");

include("Controlador_Disenio/crud_Consumos.php");

?>
<center>
<div id="form">
<form method="post"  name="formulary" id="formulary">
<p style="font-size:25px;font-family: monospace;padding-right:650px;padding-top:6px";   id="titulo">Consumos<p>
<select name="disenios" list="exampleList">
<datalist id="exampleList">
    <?php
include ("Controlador_Disenio/db_Producto.php");
$resultado = $MySQLiconn->query("SELECT * FROM producto where baja=1");

while ($row = $resultado->fetch_array()) {
?> 
<?php
if( $row ['ID']==$_SESSION['descripcionCons'])
{
	?>
	<option value="<?php echo $row ['ID'];?>" selected><?php echo $row['descripcion'];?></option>
	<?php 
}
	else{
		?>
<option value="<?php echo $row ['ID'];?>"><?php echo $row['descripcion'];?></option>

<?php 
}
} 
?> 
</datalist>
</select>
<select name="ComboProcesos" list="exampleList">
<datalist id="exampleList">
    <?php
include ("Controlador_Disenio/db_Producto.php");
$resultado = $MySQLiconn->query("SELECT * FROM procesos where baja=1");

while ($row = $resultado->fetch_array()) {
?> 
<option value="<?php echo $row ['id'];?>"><?php echo $row['descripcionProceso'];?></option>

<?php 
//Estaba añadiendo el combo para los diferentes procesos
} 
?> 
</option>
</datalist>
</select>
<div id="div2">
<select name="comboElementos" list="exampleList">
<datalist id="exampleList">
    <?php
include ("Controlador_Disenio/db_Producto.php");
$resultado = $MySQLiconn->query("SELECT * FROM elementosconsumo where baja=1");

while ($row = $resultado->fetch_array()) {
?> 
<option value="<?php echo $row ['nombreElemento'];?>"><?php echo $row['nombreElemento'];?></option>

<?php 
}
?> 
</datalist>
</select>
<p>Cantidad de consumo:<input type="text" name="cantConsumo" value="<?php if(isset($_GET['edit'])) echo $getROW['consumo'];?>" placeholder="cantidad"></p>
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
</div>
</form>
<table width="55%" border="1" cellpadding="15" style='margin-left:200px;''>
<?php
include ("Controlador_Disenio/showConsumos.php");
?>
</center>
</body>
</html>
