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
<div id="form">
<form method="post"  name="formulary" id="formulary">
<p style="font-size:25px;font-family: monospace;padding-right:500px;padding-top:6px";   id="titulo">Juegos de cilindros<p>


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
<p>Identificador:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Proveedor:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha de recepción:</p>
<p><input type="text"  name="identificadorCilindro" value="<?php if(isset($_GET['edit'])) echo $getROW['identificadorCilindro'];?>"
    size="15" placeholder="#">&nbsp;&nbsp;&nbsp;&nbsp;    <input type="text" name="proveedor"  value="<?php if(isset($_GET['edit'])) echo $getROW['proveedor'];?>"
    size="15" placeholder="proveedor">&nbsp;&nbsp;&nbsp;&nbsp; <input type="date" name="fechaRecepcion" value="<?php if(isset($_GET['edit'])) echo $getROW['fechaRecepcion'];?>"
    size="10" > </p><br> 
    <p>Diámetro:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tabla:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;Registro:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Repeticiones al paso:&nbsp;&nbsp;&nbsp; Repeticiones al giro:&nbsp;&nbsp;&nbsp;  Giros garantizados:</p>
    <p><input class="numeros" type="text" name="diametro" value="<?php if(isset($_GET['edit'])) echo $getROW['diametro'];?>"
    size="10" placeholder="mm">&nbsp;&nbsp;&nbsp;&nbsp; <input class="numeros" type="text" name="tabla" value="<?php if(isset($_GET['edit'])) echo $getROW['tabla'];?>"
    size="10" placeholder="mm">&nbsp;&nbsp;&nbsp;&nbsp;   <input class="numeros" type="name" name="registro" value="<?php if(isset($_GET['edit'])) echo $getROW['registro'];?>"
    size="10" placeholder="mm">&nbsp;&nbsp;&nbsp;&nbsp; <input class="numerosGrandes" type="text" name="repAlPaso" value="<?php if(isset($_GET['edit'])) echo $getROW['repAlPaso'];?>"
    size="10" placeholder="#">&nbsp;&nbsp;&nbsp;&nbsp;   <input class="numerosGrandes" type="text" name="repAlGiro"  value="<?php if(isset($_GET['edit'])) echo $getROW['repAlGiro'];?>"
    size="10" placeholder="#">&nbsp;&nbsp;&nbsp;&nbsp;  <input class="numerosGrandes" type="text" name="girosGarantizados"  value="<?php if(isset($_GET['edit'])) echo $getROW['girosGarantizados'];?>"
    size="10" placeholder="#"></p>
    <br>
    <hr>
    <br>
    <p>Parámetros de operación</p>
<p>Viscosidad:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Velocidad:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Presión del cilindro:&nbsp;&nbsp;Presión de la goma:&nbsp;&nbsp;&nbsp;Presión de la rasqueta:&nbsp;&nbsp;&nbsp;Tolerancia en viscosidad:</p>
<p><input type="text" name="viscosidad" value="<?php if(isset($_GET['edit'])) echo $getROW['viscosidad'];?>"
    size="10" placeholder="segundos" class="numeros">&nbsp;&nbsp;<input class="numeros" type="text" name="velocidad" value="<?php if(isset($_GET['edit'])) echo $getROW['velocidad'];?>"  size="10" placeholder="mts/min"> &nbsp;&nbsp;<input class="numerosGrandes" type="text" name="presionCilindro" value="<?php if(isset($_GET['edit'])) echo $getROW['presionCilindro'];?>" size="10" placeholder="psi">&nbsp;&nbsp;&nbsp;&nbsp;<input class="numerosGrandes" type="text" name="presionGoma" value="<?php if(isset($_GET['edit'])) echo $getROW['presionGoma'];?>"
    size="7" placeholder="psi">&nbsp;&nbsp;&nbsp;&nbsp;   <input class="numerosGrandes" type="text" name="presionRasqueta" value="<?php if(isset($_GET['edit'])) echo $getROW['presionRasqueta'];?>" size="6" placeholder="psi">&nbsp;&nbsp;&nbsp;&nbsp;    <input class="numerosGrandes" type="text" name="tolViscosidad" value="<?php if(isset($_GET['edit'])) echo $getROW['tolViscosidad'];?>"
    size="12" placeholder="+/- segundos"></p><br>
    <p>Tolerancia en velocidad:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tolerancia en cilindro:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tolerancia en temperatura:&nbsp;&nbsp;Temperatura:</p>
    <p><input class="numerosExtraGrandes" type="text" name="tolVelocidad" value="<?php if(isset($_GET['edit'])) echo $getROW['tolVelocidad'];?>"
    size="10" placeholder="+/- mts/min">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input class="numerosExtraGrandes" type="text" name="tolCilindro" value="<?php if(isset($_GET['edit'])) echo $getROW['tolCilindro'];?>" size="10" placeholder="+/- psi"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    <input class="numerosExtraGrandes" type="text" name="tolTemperatura" value="<?php if(isset($_GET['edit'])) echo $getROW['tolTemperatura'];?>"
    size="10" placeholder="+/- grados">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input class="numeros" type="text" name="temperatura" value="<?php if(isset($_GET['edit'])) echo $getROW['temperatura'];?>"
    size="10" placeholder="grados"></p>
    <p> Tolerancia en goma:&nbsp;&nbsp;&nbsp;&nbsp; Tolerancia en rasqueta:</p> <input  class="numerosGrandes" type="text" name="tolGoma" value="<?php if(isset($_GET['edit'])) echo $getROW['tolGoma'];?>"
    size="10" placeholder="+/- psi"> &nbsp;&nbsp;&nbsp;&nbsp;  <input class="numerosGrandes" type="text" name="tolRasqueta" value="<?php if(isset($_GET['edit'])) echo $getROW['tolRasqueta'];?>"
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
