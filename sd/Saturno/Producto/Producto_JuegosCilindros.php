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
include("Controlador_Disenio/crud_JuegosCilindros.php");
include("../components/barra_lateral2.php");
include("../css/barra_horizontal2.php");
?>
<center>
<p style="font-size:25px;font-family: monospace;padding-right:500px;padding-top:20px";   id="titulo">Juegos de cilindros<p>
<div id="form">
<form method="post"  name="formulary" id="formulary">

<select name="comboImpresiones" list="exampleList">
<datalist id="exampleList">
    <?php
include ("Controlador_Disenio/db_Producto.php");
$resultado = $MySQLiconn->query("SELECT * FROM impresiones where baja=1");

while ($row = $resultado->fetch_array()) {
?> 
<?php
if( $row ['descripcionImpresion']==$_SESSION['descripcionCil'])
{
	?>
	<option value="<?php echo $row ['descripcionImpresion'];?>" selected><?php echo $row['descripcionImpresion'];?></option>
	<?php 
}
	else{
		?>
<option value="<?php echo $row ['descripcionImpresion'];?>"><?php echo $row['descripcionImpresion'];?></option>

<?php 
}
} 
?> 
</datalist>
</select>
<p>Identificador:<input type="text"  name="identificadorCilindro" value="<?php if(isset($_GET['edit'])) echo $getROW['identificadorCilindro'];?>"
    size="15" placeholder="#">    Proveedor:<input type="text" name="proveedor"  value="<?php if(isset($_GET['edit'])) echo $getROW['proveedor'];?>"
    size="15" placeholder="proveedor">  Fecha de recepción:<input type="date" name="fechaRecepcion" value="<?php if(isset($_GET['edit'])) echo $getROW['fechaRecepcion'];?>"
    size="10" > </p><br> <p>Diámetro:<input id="numericos" type="text" name="diametro" value="<?php if(isset($_GET['edit'])) echo $getROW['diametro'];?>"
    size="10" placeholder="mm">Tabla:<input id="numericos" type="text" name="tabla" value="<?php if(isset($_GET['edit'])) echo $getROW['tabla'];?>"
    size="10" placeholder="numero de tintas">   Registro:<input id="numericos" type="name" name="registro" value="<?php if(isset($_GET['edit'])) echo $getROW['registro'];?>"
    size="10" placeholder="mm"> </p><br><p> Repeticiones al paso:<input id="numericos" type="text" name="repAlPaso" value="<?php if(isset($_GET['edit'])) echo $getROW['repAlPaso'];?>"
    size="10" placeholder="#">   Repeticiones al giro:<input id="numericos" type="text" name="repAlGiro"  value="<?php if(isset($_GET['edit'])) echo $getROW['repAlGiro'];?>"
    size="10" placeholder="#">   Giros garantizados:<input id="numericos" type="text" name="girosGarantizados"  value="<?php if(isset($_GET['edit'])) echo $getROW['girosGarantizados'];?>"
    size="10" placeholder="#"></p>
    <br>
    <p>Parámetros de operación</p>
<p>Viscosidad: <input type="text" name="viscosidad" value="<?php if(isset($_GET['edit'])) echo $getROW['viscosidad'];?>"
    size="10" placeholder="segundos">   Velocidad: <input id="numericos" type="text" name="velocidad" value="<?php if(isset($_GET['edit'])) echo $getROW['velocidad'];?>"
    size="10" placeholder="mts/min">    Presión del cilindro: <input id="numericos" type="text" name="presionCilindro" value="<?php if(isset($_GET['edit'])) echo $getROW['presionCilindro'];?>" size="10" placeholder="psi"></p><br>
    <p>Presión de la goma: <input id="numericos" type="text" name="presionGoma" value="<?php if(isset($_GET['edit'])) echo $getROW['presionGoma'];?>"
    size="7" placeholder="psi">  Presión de la rasqueta: <input id="numericos" type="text" name="presionRasqueta" value="<?php if(isset($_GET['edit'])) echo $getROW['presionRasqueta'];?>" size="6" placeholder="psi">   Tolerancia en viscosidad: <input id="numericos" type="text" name="tolViscosidad" value="<?php if(isset($_GET['edit'])) echo $getROW['tolViscosidad'];?>"
    size="12" placeholder="+/- segundos"></p><br>
    <p>Tolerancia en velocidad: <input id="numericos" type="text" name="tolVelocidad" value="<?php if(isset($_GET['edit'])) echo $getROW['tolVelocidad'];?>"
    size="10" placeholder="+/- mts/min">   Tolerancia en cilindro: <input id="numericos" type="text" name="tolCilindro" value="<?php if(isset($_GET['edit'])) echo $getROW['tolCilindro'];?>" size="10" placeholder="+/- psi">    Tolerancia en temperatura: <input id="numericos" type="text" name="tolTemperatura" value="<?php if(isset($_GET['edit'])) echo $getROW['tolTemperatura'];?>"
    size="10" placeholder="+/- grados"></p><br>
    <p>Temperatura: <input id="numericos" type="text" name="temperatura" value="<?php if(isset($_GET['edit'])) echo $getROW['temperatura'];?>"
    size="10" placeholder="grados">   Tolerancia en goma: <input  id="numericos" type="text" name="tolGoma" value="<?php if(isset($_GET['edit'])) echo $getROW['tolGoma'];?>"
    size="10" placeholder="+/- psi">   Tolerancia en rasqueta: <input id="numericos" type="text" name="tolRasqueta" value="<?php if(isset($_GET['edit'])) echo $getROW['tolRasqueta'];?>"
    size="10" placeholder="+/- psi"></p>
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
include ("Controlador_Disenio/showCilindros.php");

/*onchange="<?php $_SESSION['descripcionCil']=$_SESSION['descripcionCil'];?*/
?>
</table>
</div>
</center>
</body>
</html>
