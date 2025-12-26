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

include ("funciones/crudChange.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Agregar Usuario(Sistema) | Grupo Labro</title>
<link rel="stylesheet" href="style.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="../css/Stylish.css" />
<link rel="stylesheet" href="../css/StyleTablas.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/stylees.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/formulary.css" type="text/css" media="screen"/>
</head>

<body> 
<?php
include("../components/barra_lateral2.php");
include("../css/barra_horizontalsistema.php");
?>

<center>
<div id="form1">
<p style="font-size:25px;font-family: monospace;padding-right:500px;padding-top:20px";   id="titulo">Agregar Usuario<p>
</div>
</center>
<br>
<center>
<div id="form">
<form method="post" name="formulary" id="formulary">
<br>
	
   <p>Empleado:<?php 
// Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="example" list="exampleList">
<datalist id="exampleList">
    <?php
//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM $tablaem where usuario=0 and Baja=1 ORDER BY ID DESC");
//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
	//Mandamos la lista normalmente:  		?>
<option value="<?php echo $row ['Nombre'];?>">
<?php echo $row['numemple']." | " .$row['Nombre'];?>
</option>
<?php }   ?> 
</datalist>
</select>
Rol:
<input type="text" name="rol"  size="20" placeholder="Área de Trabajo" required>
</p> 
<br>
<p>Usuario:
<input type="text" name="usuario" size="20" placeholder="Usuario" required>
</p>
<br>
<p>Contraseña:
<input type="password" name="contra"  size="20" placeholder=" Contraseña" required>
 </p>   
<br>
<p>Repetir Contraseña:
<input type="password" name="confirmar"  size="20" placeholder="Confirmar Cambio" required>
 </p>  

	<button type="submit" name="saveUser">Agregar</button>
</form>
</div>
</center>
</body>
</html>
























