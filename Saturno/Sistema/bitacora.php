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

//include ("funciones/crudBitacora.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Bitacora(Sistema) | Grupo Labro</title>
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
<p style="font-size:25px;font-family: monospace;padding-right:500px;padding-top:20px";   id="titulo">Bitacora<p>
</div>
</center>
<br>
<center>



<div id="form">
<form method="post" name="formulary" id="formulary">
<br>
   <p>Departamento:
<select name="example" list="exampleList" id="example"  onChange="showCombo(this.value)">
<datalist id="exampleList">
<option value="Recursos Humanos">Recursos Humanos</option>
<option value="Productos">Productos</option>
<option value="Logistica">Logistica</option>
<option value="Materia Prima">Materia Prima</option>
</datalist>
</select>
</div>
<br><br>
<div id="txtHint">Aqui va esta</div>

</form>

</center>
</body>












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
        xmlhttp.open("GET","funciones/viewBitacora.php?q="+str,true);
        xmlhttp.send();
    }
}
window.onload = function() {
  showComboLoad(example.value);
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
        xmlhttp.open("GET","funciones/viewBitacora.php?q="+guat,true);
        xmlhttp.send();
    }
}
</script>
             
</script>









</html>