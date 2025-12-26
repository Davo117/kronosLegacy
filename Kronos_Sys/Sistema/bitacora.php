<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { 
//include ("funciones/crudBitacora.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Bitácora(Sistema) | Grupo Labro</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
    <link rel="manifest" href="../pictures/manifest.json">
    <meta name="theme-color" content="#fff">
</head>

<body> 
<?php $codigoPermiso='27';
include("../components/barra_lateral2.php");?>

<div id="page-wrapper">
	<div class="container-fluid">
        <form method="post" name="formulary" id="formulary" role="form">
        <div class="panel panel-default">

            <div class="panel-heading"><b class="titulo">Bitácora</b></div>
            <div class="panel-body">
   
                <div class="col-xs-3">
                    <label for="example19">Departamento</label>
                    <select name="example" list="exampleList" class="form-control" id="example19"  onChange="showCombo(this.value)">
                    <option value="--">[Selecciona un Departamento]</option>
                    <option value="Recursos Humanos">Recursos Humanos</option>
                    <option value="Productos">Productos</option>
                    <option value="Logistica">Logistica</option>
                    <option value="Materia Prima">Materia Prima</option>
                    <option value="Producción">Producción</option>
                    <option value="Calidad">Calidad</option>
                    </select>
                </div>
            </div>
        </div>
        <div id="txtHint" class="table-responsive">Contenedor</div>
        </form>
    </div>
</div>
</body>

<script type="text/javascript" language="JavaScript">
function showCombo(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    }
    else{
        if(window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else{
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
  showComboLoad(example19.value);
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
<script type="text/javascript" src="../css/menuFunction.js"></script>
</html>
<?php
ob_end_flush();
} else {
    echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
    include "../ingresar.php";
} ?>