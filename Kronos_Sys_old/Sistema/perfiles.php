<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { 
include ("funciones/crudPrivilegios.php");?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Privilegios(Sistema) | Grupo Labro</title>
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#fff">
<script type="text/javascript" src="funciones/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="funciones/js/cambiarPestanna.js"></script>
<style type="text/css">

#pestanas { float: top;  font-weight: bold; }

#pestanas li{
    list-style-type: none;
    float: left;
    text-align: center;
    margin: 0px 2px -2px -0px;
    color: #b9c9fe;
    background:#758AA8;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    border: 2px solid #D5DCE8;
    border-bottom: dimgray;
    padding: 0px 20px 0px 20px;
}

#pestanas a:link{
    text-decoration: none;
    color: #6A6A6A;
}

#contenidopestanas{
    clear: both;  
    background:#ECECEC;
    padding: 20px 0px 20px 20px;
    width: 85%;
    -webkit-box-shadow: 0px 1px 5px 0px rgba(143,143,143,1);
-moz-box-shadow: 0px 1px 5px 0px rgba(143,143,143,1);
box-shadow: 0px 1px 5px 0px rgba(143,143,143,1);
}
section{
   border-radius: 10px; 
    width:65%;
    background: #476F89;
    font: 105% Sansation Light;
    -webkit-box-shadow: 5px 5px 5px 5px rgba(143,143,143,10);


}   
section:hover{
    background-color:#758AA8; 
    width:67%;
    font:bold 105% Sansation Light;
   -webkit-box-shadow: 4px 4px 4px 4px rgba(143,143,143,1);
  -webkit-transition-duration: 0.4s;
  -o-transition-property: background-color, color, left;
  -o-transition-duration: 1s;
}

input[type=radio] {   width: 2.3%; } section {   margin: 0 auto;   padding: 10px; } article {   display: inline-block;   margin: 0 auto;}
</style>
</head>
<body> 
<?php  $_SESSION['codigoPermiso']='80004';  include ("funciones/permisos.php");
       include("../components/barra_lateral2.php"); ?>
<div id="page-wrapper">
	<div class="container-fluid">
<form method="post" name="formulary" id="formulary" role="formulary">
            <div class="panel panel-default">

                <div class="panel-heading"><b class="titulo">Permisos de usuario</b>
                </div>
<div class="panel-body">       
<div class="col-xs-3">
<label for="example">Usuario</label>
<select name="example" id="example" class="form-control" onChange="showCombo(this.value)" required style="border: none;">
    <option value="">[Seleccione un Usuario]</option>
    <?php //Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM usuarios ");

//mientras se tengan registros:
while ($row = $resultado->fetch_array()) { ?>
	<option value="<?php echo $row['perfil'];?>"><?php echo $row ['usuario'];?></option>
	<?php  
} ?> 
</select>
</div>
</div>
<button class="btn btn-success" style="float:right;" type="submit"  name="save1">Guardar</button>
</div>

<div id="txtHint" class="table-responsive">Tabla</div>
</form>
</div>
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
        xmlhttp.open("GET","funciones/accesos.php?q="+str,true);
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
        xmlhttp.open("GET","funciones/accesos.php?q="+guat,true);
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
            }

            ?>