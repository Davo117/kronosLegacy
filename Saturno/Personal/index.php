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

include ("funciones/crudEmpleado.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Empleado(RH) | Grupo Labro</title>
<link rel="stylesheet" href="style.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="../css/Stylish.css" />
<link rel="stylesheet" href="../css/StyleTablas.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/stylees.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/formulary.css" type="text/css" media="screen"/>
</head>

<body> 
<?php
include("../components/barra_lateral2.php");
include("../css/barra_horizontal1.php");
?>

<center>
<div id="form1">
<p style="font-size:25px;font-family: monospace;padding-right:500px;padding-top:20px";   id="titulo">Personal(Empleados)<p>
</div>
</center>
<br>
<center>
<div id="form">
<form method="post" name="formulary" id="formulary">
<br>
	<p># Empleado*:<input type="text" name="NumEmp" value="<?php if(isset($_GET['edit'])) echo $getROW['numemple'];  ?>" size="20" placeholder="Número Empleado" required>

		Nombre(s)*:<input type="text" name="Nombre" value="<?php if(isset($_GET['edit'])) echo $getROW['Nombre'];  ?>" size="20" placeholder="Nombre del Empleado">
    >Apellidos:<input type="text" name="Apellido" value="<?php if(isset($_GET['edit'])) echo $getROW['apellido'];  ?>" size="20" placeholder="Apellidos" required>
	</p>   
<br>
	<p>   
    Teléfono*:<input type="text" name="Telefono" value="<?php if(isset($_GET['edit'])) echo $getROW['Telefono'];  ?>" size="20" placeholder="Teléfono del Empleado" required>

    Departamento:<input type="text" name="Depa" value="<?php if(isset($_GET['edit'])) echo $getROW['departamento'];  ?>" size="20" placeholder="Área de Trabajo">

    </p>   
   
<?php
if(isset($_GET['edit']))
{	?>
	<button type="submit" name="update" >Actualizar</button>
	<?php  }
else
{	?>
	<button type="submit" name="save">Guardar</button>
	<?php } ?>
	
</form>

<br /><br />
<table width="20%" border="1" cellpadding="15" style="margin-left: 200px">
<?php include ("funciones/mostradorEmpleado.php"); ?>

<a href="?mostrar" >Mostrar Todos</a>
<form method="post">
<?php 
if(isset($_GET['mostrar']) ) {
?>
<table width="20%" border="1" cellpadding="15" style="margin-left: 200px">
<br><br><br><br><br>
<?php
include("funciones/mostrarTodoEmpleado.php");


} 
?>


</form>
</div>
</center>
</body>
</html>