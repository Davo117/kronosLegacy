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
<title>Clientes(Logística) | Grupo Labro</title>
<link rel="stylesheet" type="text/css" href="../css/Stylish.css" />
 <link rel="stylesheet" href="../css/StyleTablas.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/stylees.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/formulary.css" type="text/css" media="screen"/>


</head>

<body> 
<?php
include ("funciones/crud/crudCliente.php"); 
include("../components/barra_lateral2.php");
include("../css/barra_horizontal3.php");
?>
<center>
<div id="form">

<p style="font-size:25px;font-family: monospace;padding-right:600px;padding-top:20px;";   id="titulo">Clientes<p>

</div>
</center>
<br>
<center>
<div id="formula">
<form method="post" name="formulary" id="formulary">
<br>
	<p>
  RFC:<input type="text" name="RFC" value="<?php if(isset($_GET['edit'])) echo $getROW['rfccli'];  ?>"	size="20" placeholder="#" required>
  &nbsp 
  Nombre:<input type="text" name="Nombre" value="<?php if(isset($_GET['edit'])) echo $getROW['nombrecli'];  ?>" size="20" placeholder="Razón Social" required>
  &nbsp 
	Domicilio:<input type="text" name="Domicilio" value="<?php if(isset($_GET['edit'])) echo $getROW['domiciliocli'];  ?>" size="20" placeholder="Calle y Número" required>
  </p>
<br>
 <p>
 Colonia:<input type="text" name="Colonia" value="<?php if(isset($_GET['edit'])) echo $getROW['coloniacli'];  ?>" size="20" placeholder="Colonia" required>
&nbsp  
 Ciudad:<input type="text" name="Ciudad" value="<?php if(isset($_GET['edit'])) echo $getROW['ciudadcli'];  ?>"	size="20" placeholder="Ciudad,Edo" required>
&nbsp
  CP:<input type="text" name="CP" value="<?php if(isset($_GET['edit'])) echo $getROW['cpcli'];  ?>" 	size="20" placeholder="Código Postal" required>
  </p>   <br>
  <p>
  Teléfono:<input type="text" name="TelefonoCli" value="<?php if(isset($_GET['edit'])) echo $getROW['telcli'];  ?>"size="20" placeholder="Número Telefónico" required>
   </p>   
    
  <?php
if(isset($_GET['edit']))
{	?>
	<button type="submit" name="update" >Actualizar</button>
	<?php
}
else
{	?>
	<button type="submit" name="save">Guardar</button>
	<?php } ?>
  
</form>

<br /><br />
<table width="20%" border="1" cellpadding="15" style="margin-left: 200px">
<?php
include ("funciones/mostrar/mostradorCliente.php"); ?>

<a href="?mostrar" >Mostrar Todos</a>

<br>

<a href="../Logistica/Logistica_Clientes.php" id="ocu" onclick="valor(this)"> Ocultar</a>

<form method="post">
<?php 
if(isset($_GET['mostrar']) ) {
?>

<table width="20%" border="1" cellpadding="15" style="margin-left: 200px">
<br /><br /><br /><br />
<?php
include("funciones/nomostrar/mostrarTodoCliente.php");
} 
?>

</form>
</div>
</center>
</body>
</html>
