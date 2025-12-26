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
	} ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Parametros Procesos| Misceláneos</title>
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
include("Controlador_Misce/crud_ParametrosRegistros.php"); ?>
<center>
<div id="form">
<form method="POST" name="formulary" id="formulary">
<p id="titulo">Parámetros por Proceso</p>
<p>Producto:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
Proceso:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
Nombre:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
</p>
<p>
<select onChange="myFunction(this.value)" name="comboTipos" id="comboTipos">
<?php $resultado=$MySQLiconn->query("SELECT * from tipoproducto where baja=1;");
while($row=$resultado->fetch_array()){
  if($row['tipo']==$_SESSION['procesoProd']){ ?>
    <option selected value="<?php echo $row['juegoprocesos'];?>"><?php echo $row['tipo'];?></option>
    <?php
  }
  else{?>
    <option value="<?php echo $row['juegoprocesos'];?>"><?php echo $row['tipo'];?></option>
    <?php
  }
}?>
</select>&nbsp; &nbsp;

<select name="parametros" id="parametros" onChange="showCombo(this.value)">
<?php $resultado=$MySQLiconn->query("SELECT * from juegoprocesos where identificadorJuego='".$_SESSION['procesoProd']."' && baja=1 && numeroProceso!=0 ORDER BY id;");

while($row=$resultado->fetch_array()){ 
    if($row['descripcionProceso']==$_SESSION['productoProd']){ ?>
      <option selected value="<?php echo $row['descripcionProceso'];?>"><?php echo $row['descripcionProceso'];?></option>
      <?php
  }
  else{?>
    <option value="<?php echo $row['descripcionProceso'];?>"><?php echo $row['descripcionProceso'];?></option>
    <?php
  } 
}?>
</select>&nbsp; &nbsp;


<select name="parameter" >
 <option value=0>--</option>
    <?php 
    $Sic=$MySQLiconn->query("select nombreParametro FROM parametros");
    while($raos=$Sic->fetch_array()){
      
?>
<option value="<?php echo $raos['nombreParametro'];?>"><?php echo $raos['nombreParametro'];?></option>
<?php
}?>
</select>&nbsp; &nbsp; &nbsp; &nbsp; 
Requerido:
<input name="requerido" value="0" type="hidden">
<input type="checkbox" style="margin-left:-70px;" name="requerido" value="1">
&nbsp; 

Referencial:
<input name="Referencial" value="0" type="hidden">
<input type="checkbox" style="margin-left:-70px;" name="Referencial" value="1">
</p>
<p>
Leyenda:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
Placeholder:
</p>
<p>
 <input type="text" name="leyenda" value="<?php if(isset($_GET['edit'])) echo $getROW['leyenda'];?>"
	size="20" placeholder="mostrador" required> &nbsp;&nbsp;
	<input type="text" name="placeholder" value="<?php if(isset($_GET['edit'])) echo $getROW['placeholder'];?>"
	size="20" placeholder="marcador" required>
&nbsp; 
Registro:
<input name="registro" value="0" type="hidden">
<input type="checkbox" style="margin-left:-70px;" name="registro" value="1"></p>

	<button type="submit" class="botonPerson3" style="transform: translate(-300px,-28px);" name="save">Guardar</button>

</form>

<p class="ordenMedio">Parámetros Disponibles</p>

<div id="txtHint"></div>
</div>
</center>
</body>
<script src="Controlador_Misce/ajax.js"></script>
<script type="text/javascript" language="JavaScript">

function showCombo(str) {
    if (str == "") {
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
        xmlhttp.open("GET","Controlador_Misce/showParametrosPP.php?q="+str,true);
        xmlhttp.send();
    }
}
window.onload = function() {
  showComboLoad(parametros.value);
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
        xmlhttp.open("GET","Controlador_Misce/showParametrosPP.php?q="+guat,true);
        xmlhttp.send();
    }

}



// Funcion que al momento de cambiar el primer combo box hara lo siguiente:
function myFunction(str){
  //asignamos una segunda funcion con el mismo value.
  
  //Cargamos el combo box, enviandole un parametro 'value'
  loadDoc("r="+str,"comboboxMisce.php",function(){
    if (xmlhttp.readyState==4 && xmlhttp.status==200){
      document.getElementById("parametros").innerHTML=xmlhttp.responseText;
    }
  });
}
//Agarra el value del primer combobox

</script>
</html>
<?php ob_end_flush();
?>
