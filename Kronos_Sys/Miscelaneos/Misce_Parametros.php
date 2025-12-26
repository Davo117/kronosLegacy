<?php
ob_start();
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
<title>Parametros| Misceláneos</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">
<link rel="stylesheet" type="text/css" href="../css/Stylish.css" />
  <link rel="stylesheet" href="../css/stylees.css" type="text/css" media="screen"/>
   <link rel="stylesheet" href="../css/StyleTablas.css" type="text/css" media="screen"/>
     <link rel="stylesheet" href="../css/formulary.css" type="text/css" media="screen"/>

</head>

<body> 
<?php
  $_SESSION['codigoPermiso']='80005';
  include ("funciones/permisos.php");

include("Controlador_Misce/db_Producto.php");
include("../components/barra_lateral2.php");
include("../css/barra_horizontal7.php");
include("Controlador_Misce/crud_ParametrosPP.php");
?>
<center>
<div id="form">
<form method="POST" name="formulary" id="formulary" >
<p id="titulo">Parámetros por Producto</p>
<p>Producto:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Nombre:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Leyenda:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Placeholder:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Requerido:</p><p>

  <select onChange="showComboLoad(this.value)" name="comboTipos" id="comboTipos">
<?php


$resultado=$MySQLiconn->query("SELECT tipoproducto.tipo from tipoproducto where baja=1;");
while($row=$resultado->fetch_array())
{
    if($row['tipo']==$_SESSION['envio2']){
?>
<option selected value="<?php echo $row['tipo'];?>"><?php echo $row['tipo'];?></option>
<?php
}
else
{
    ?>
 <option value="<?php echo $row['tipo'];?>"><?php echo $row['tipo'];?></option>
 <?php
}
}
?>
  </select>&nbsp; &nbsp;
  <select name="parametros">
  	<option value=0>--</option>
  	<?php 
  	$Sic=$MySQLiconn->query("SHOW COLUMNS FROM impresiones");
  	while($raos=$Sic->fetch_array()){
  		if($raos['Field']!="id" && $raos['Field']!="baja" && $raos['Field']!="DisenioFK" && $raos['Field']!="codigoCliente"  && $raos['Field']!="nombreBanda" && $raos['Field']!="sustrato" && $raos['Field']!="DescripcionDisenio"){
?>
<option value="<?php echo $raos['Field'];?>"><?php echo $raos['Field'];?></option>
<?php
}
}
?>
  </select>&nbsp; &nbsp;
 <input type="text" name="leyenda" value="<?php if(isset($_GET['edit'])) echo $getROW['leyenda'];?>"
	size="20" placeholder="mostrador" required> &nbsp;&nbsp;
	<input type="text" name="placeholder" value="<?php if(isset($_GET['edit'])) echo $getROW['placeholder'];?>"
	size="20" placeholder="marcador" required> &nbsp;&nbsp;
	&nbsp;&nbsp;<input type="checkbox" style="margin-left:-40px;" name="requerido" value="1"> &nbsp;&nbsp;
	</p>
 <?php
if(isset($_GET['edit']))
{  ?>
	<button type="submit" class="botonPerson3" style="transform: translate(80px,-35px);" name="update">Actualizar</button>
	<?php
}
else
{	?>
	<button type="submit" class="botonPerson3" style="transform: translate(80px,-35px);" name="save">Guardar</button>
	<?php
} ?>
</form>

<p class="ordenMedio">Parámetros Disponibles</p>
<div id="txtHint"></div>
</div>
</center>
</body>
<script type="text/javascript">
	window.onload = function() {
		showComboLoad(comboTipos.value);
};
function showComboLoad(guat) {

    if (guat == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
      //  var combo = document.getElementById('comboproductos');
//alert('Esta enviando el parametro'+ ' '+combo.options[combo.selectedIndex].value);
        xmlhttp.open("GET","Controlador_Misce/showParametros.php?q="+guat,true);
        xmlhttp.send();
    }
}  
</script>
</html>
<?php
ob_end_flush();
?>
