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
include ("funciones/bitacora/bitacoraMaquinas.php");
include ("funciones/crud/crudMaquinas.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Máquinas | Producción</title>
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">
<link rel="stylesheet" href="style.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="../css/Stylish.css" />
<link rel="stylesheet" href="../css/StyleTablas.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/stylees.css" type="text/css" media="screen"/>
   <link rel="stylesheet" href="../css/produccionCss.css" type="text/css" media="screen"/>

</head>

<body> 
<?php

include("../css/barra_horizontal5.php");
?>
<a href="../Menu.php" ><IMG style="float:left;transform:translate(0px,-30px);" src="../pictures/logo-labro2.png" title='Menu principal'></IMG><a>
<center>
<p style="font-size:30px;font-family: Sansation;padding-right: 1150px;"   id="titulo">Producción</p>
<div  style="transform:translate(100px,150px)">
<form method="post" name="formular" id="formular" style="height: 100px; margin-left: -120px;transform:translate(50px,-200px)">
<p style="font-size:25px;font-family: Sansation;padding-right:500px;"   id="titulo">Máquinas</p>
	<p>
	Código:
	&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
	Descripción: 
	&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
	Subproceso: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
	Producto:
	</p>
<p><input style="color:black" type="text" class="maqui" name="Codigo" value="<?php if(isset($_GET['edit'])) echo $getROW['codigo'];  ?>" size="20" placeholder="Codigo Completo" required>

&nbsp;
	<input style="color:black" type="text" name="Descripcion" class="maqui" value="<?php if(isset($_GET['edit'])) echo $getROW['descripcionMaq'];  ?>" size="20" placeholder="Descripción Completa" required> 
&nbsp;
    
     <?php 
// Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="Subproceso" list="exampleList" id="Subproceso" onChange="showCombo(this.value)">
<datalist id="exampleList">
    <?php
//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM $sub where baja=1");
//mientras se tengan registros:
?>
<option value="0">--</option>
<?php
while ($row = $resultado->fetch_array()) {
//Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:

if(isset($_GET['edit'])){ 
if($getROW['subproceso'] == $row['descripcionProceso']){
?>
<option value="<?php echo $getROW['subproceso'];?>" selected> <?php echo $getROW['subproceso'];?></option>
<?php  
}else{  //Sino manda la lista normalmente:          ?>
<option value="<?php echo $row ['descripcionProceso'];?>"><?php echo $row ['descripcionProceso'];?></option>

<?php }	}

else{	//Sino manda la lista normalmente:  		?>
<option value="<?php echo $row ['descripcionProceso'];?>"><?php echo $row ['descripcionProceso'];?></option>
<?php 
} }  ?> 
</datalist>
</select>
<SELECT name="tipocombo"  method="post" > 
	<?php
	$resultado=$MySQLiconn->query("SELECT alias as tipo from tipoproducto where baja=1");
?>
<option value="0">--</option>
<?php
while($row=$resultado->fetch_array())
{
	
	if($getROW['tipoproducto']==$row['tipo'])
	{

?>
<OPTION selected VALUE="<?php echo $row['tipo']; ?>"  ><?php echo $row['tipo']; ?></OPTION>
<?php
}
else
{
	?>
<OPTION VALUE="<?php echo $row['tipo']; ?>" ><?php echo $row['tipo']; ?></OPTION>
<?php
}
}
?>
</select>
	<?php
if(isset($_GET['edit']))
{	?>
	<button  style="height:30px;width:80px;" type="submit" name="update" >Actualizar</button>
	<?php  }
else
{	?>
	<button style="height:30px;width:80px;" type="submit" name="save">Guardar</button>
	<?php } ?>
</p><br>
<p class="checkTxt3" style="margin-top:-90px;margin-left:800px;">Mostrar todo<input  style="font:light 100% Sansation;margin-left:10px;" class="boxcheck" checked type="checkbox" name="checkTodos" onclick="if (this.checked==false){ location.href='Produccion_Maquinas.php';};"></p>
</form>
<table border="1" cellpadding="15" style="margin-right: 0px; width: 1000px;margin-top:-100px;">
<?php include ("funciones/mostrar/mostradorMaquinas.php"); ?>
</table>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br><br>
<br>
<br>
<table border="1" cellpadding="15" style="margin-right: 200px; width: 800px">
<?php
include ("funciones/nomostrar/mostrarTodoMaquinas.php"); ?>

</div>
</center>
</body>
</html>