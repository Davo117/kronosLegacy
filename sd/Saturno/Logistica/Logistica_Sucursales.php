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
<title>Sucursales(Logística) | Grupo Labro</title>
<link rel="stylesheet" type="text/css" href="../css/Stylish.css" />
<link rel="stylesheet" href="../css/StyleTablas.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/stylees.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/formulary.css" type="text/css" media="screen"/>
</head>

<body> 
<?php
include("funciones/crud/crudSuc.php");
include("../components/barra_lateral2.php");
include("../css/barra_horizontal3.php");

?>
<center>
<div id="form">
<p style="font-size:25px;font-family: monospace;padding-right:575px;padding-top:20px";   id="titulo">Sucursales<p>
</div>
</center>
<br>
<center>
<div id="form">
<form method="post" name="formulary" id="formulary">
<br>
   <p>Cliente:<?php 
// Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="example" list="exampleList">
<datalist id="exampleList">
    <?php
//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM $tablacli where bajacli=1 ORDER BY ID DESC");


//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
//Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:

if(isset($_GET['edit'])){ 
if($getROW['idcliFKS'] == $row['nombrecli']){
?>
<option value="<?php echo $getROW['idcliFKS'];?>" selected> <?php echo $getROW['idcliFKS'];?></option>
<?php  
}	}

elseif( $row ['nombrecli']==$_SESSION['descripcion'])
{ //Si fue seleccionado. en caso de editar el campo manda a llamar el nombre del "fk" sino, muestra el nombre del cliente seleccionado	?>
	<option value="<?php echo $row ['nombrecli'];?>" selected>
	<?php echo $row ['nombrecli'];?>		
	</option>
	<?php 
}	else{	//Sino manda la lista normalmente:  		?>
<option value="<?php echo $row ['nombrecli'];?>"><?php echo $row ['nombrecli'];?></option>
<?php } }  ?> 
</datalist>
</select>

	Nombre Suc.:<input type="text" name="Name" value="<?php if(isset($_GET['edit'])) echo $getROW['nombresuc'];  ?>"	size="20" placeholder="Nombre Sucursal" >
    
	Domicilio Suc.:<input type="text" name="Domicilio" value="<?php if(isset($_GET['edit'])) echo $getROW['domiciliosuc'];  ?>" size="20" placeholder="Calle y Número" >
	</p>   <br>

	<p>Colonia Suc.:<input type="text" name="Colonia" value="<?php if(isset($_GET['edit'])) echo $getROW['coloniasuc'];  ?>" size="20" placeholder="Colonia" >

	Ciudad Suc.:<input type="text" name="Ciudad" value="<?php if(isset($_GET['edit'])) echo $getROW['ciudadsuc'];  ?>" size="20" placeholder="Ciudad,Edo" >

	CP Suc.:<input type="text" name="CP" value="<?php if(isset($_GET['edit'])) echo $getROW['cpsuc'];  ?>"	size="20" placeholder="Código Postal" >
	</p>   <br>

    <p>Teléfono Suc.:<input type="text" name="Telefono" value="<?php if(isset($_GET['edit'])) echo $getROW['telefonosuc'];  ?>"size="20" placeholder="Número Telefónico">

    Transporte Suc.:<input type="text" name="Transporte" value="<?php if(isset($_GET['edit'])) echo $getROW['transpsuc'];  ?>"size="20" placeholder="Transporte">
    </p>  

<?php
if(isset($_GET['edit']))
{	?>
	<button type="submit" name="update" onclick="valida_envia()">Actualizar</button>
	<?php  }
else
{  ?>
	<button type="submit" name="save" onclick="valida_envia()">Guardar</button>
	<?php } ?>
</form>
</p>


<br /><br />
<table width="20%" border="1" cellpadding="15" style="margin-left: 200px">
<?php
include ("funciones/mostrar/mostradorSuc.php"); ?>


<a href="?mostrar" >Mostrar Todos</a>

<br>

<a href="../Logistica/Logistica_Sucursales.php" id="ocu" onclick="valor(this.form)"> Ocultar</a>

<form method="post">
<?php 
if(isset($_GET['mostrar']) ) {
?>

<table width="20%" border="1" cellpadding="15" style="margin-left: 200px">
<br /><br /><br /><br /><br />
<?php
include("funciones/nomostrar/mostrarTodoSuc.php");
} 
?>

</form>

</div>
</center>
</body>
<script type="text/javascript">
// Name Domicilio Colonia Ciudad CP Telefono Transporte
	function valida_envia(){ 
    	if (document.formulary.Name.value.length==0){ 
      	alert("Tiene que escribir un nombre valido") 
      	document.formulary.Name.focus(); 
      	return 0; 
   	} 

if (document.formulary.Domicilio.value.length==0){ 
      	alert("Tiene que escribir un domicilio valido") 
      	document.formulary.Domicilio.focus(); 
      	return 0; 
   	} 
if (document.formulary.Colonia.value.length==0){ 
      	alert("Tiene que escribir una colonia valida") 
      	document.formulary.Colonia.focus(); 
      	return 0; 
   	} 

	if (document.formulary.Ciudad.value.length==0){ 
      	alert("Tiene que escribir un ciudad valido") 
      	document.formulary.Ciudad.focus(); 
      	return 0; 
   	} 
   		if (document.formulary.CP.value.length==0){ 
      	alert("Tiene que escribir un Codigo Postal valido") 
      	document.formulary.CP.focus(); 
      	return 0; 
   	} 
   		if (document.formulary.Telefono.value.length==0){ 
      	alert("Tiene que escribir un telefono valido") 
      	document.formulary.Telefono.focus(); 
      	return 0; 
   	} 
}

</script>

</html>
