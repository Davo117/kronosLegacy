<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>BSPP | Producto</title>
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">
</head>

<body> 
<?php

$codigoPermiso='8';
include("funciones/permisos.php");
include("Controlador_Disenio/bitacoras/bitacoraBSPP.php");
include("../components/barra_lateral2.php");
include("Controlador_Disenio/crud_bandaSPP.php");
?>

<div id="page-wrapper">
    <div class="container-fluid">
<form method="GET" name="formulary" id="formulary" role="form">

<div class="panel panel-default">

                <div class="panel-heading"><b class="titulo">Banda de seguridad por proceso</b>
                    <?php 
                    if(isset($_GET['checkTodos']))
                    {
                        ?>

                        <label class="checkbox-inline" style="float:right;">
                            <input type="checkbox" id="checkboxEnLinea1" checked value="checkTodos" name="checkTodo" onclick="window.location='Producto_BandasSPP.php?ban=<?php echo $_GET['comboBSPP']?>';"> Mostrar todo
                        </label>
                        <?php
                    }
                    else
                        {?>

                            <label class="checkbox-inline" style="float:right;">
                                <input type="checkbox" id="checkboxEnLinea1" value="checkTodos" name="checkTodos" onclick="document.formulary.submit()"> Mostrar todo
                            </label>
                            <?php
                        }?>
                    </div>
<div class="panel-body">
<div class="col-xs-3">
<label for="comboBSPP">Banda Seguridad</label>
<select required onChange="document.formulary.submit()" class="form-control" name="comboBSPP" id="comboBSPP">
    <option value="">--</option>
    <?php
include ("Controlador_Disenio/db_Producto.php");
if(isset($_GET['comboBSPP']))
{
    $band=$_GET['comboBSPP'];
}
else if(isset($_GET['ban']))
{
    $band=$_GET['ban'];
}
else
{
    $band="";
}

$resultado = $MySQLiconn->query("SELECT * FROM bandaseguridad where baja=1");

while ($row = $resultado->fetch_array()) {
?> 
<?php
if( $row ['IDBanda']==$band)
{
	?>
	<option value="<?php echo $row ['IDBanda'];?>" selected><?php echo $row['nombreBanda'];?></option>
	<?php
}
	else{
		?>
<option value="<?php echo $row ['IDBanda'];?>"><?php echo $row['nombreBanda'];?></option>
<?php
}
}
?>
</select>
</div>
<?php /*<div class="col-xs-3">
<p> Sustrato:</p>
<select style="width:70px;" type="POST" name="comboEmbosados">
<?php
if($getROW['preEmbosado']==1)
{
    ?>
    <option value="1" selected >PreEmbosado</option>
    <?php
}
?>
<option value="0">--</option>
<option value="1">PreEmbosado</option>
</select>
</div>*/?>
<div class="col-xs-3">
<label for="identificadorBSPP">Identificador</label>
<input required type="text" name="identificadorBSPP" class="form-control" id="identificadorBSPP" value="<?php if(isset($_GET['edit'])) echo $getROW['identificadorBSPP'];?>"
    size="12" placeholder="nombre corto">
</div>

<div class="col-xs-3">
<label for="nombreBSPP">Nombre</label>
<input required type="text" name="nombreBSPP" class="form-control" id="nombreBSPP" value="<?php if(isset($_GET['edit'])) echo $getROW['nombreBSPP'].'" readonly="true';  ?>"
    size="20" placeholder="nombre completo de la banda">
</div>

<div class="col-xs-3">
<label for="anchuraLaminado">Discos resultantes:</label>
<input type="text" name="repeticiones" id="repeticiones" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['repeticiones'];?>"
    size="7" placeholder="cantidad">
</div>

<div class="col-xs-3">
<label for="comboSustratos">Sustrato:</label>
<select required name="comboSustratos" class="form-control" id="comboSustratos">
    <?php
include ("Controlador_Disenio/db_Producto.php");
$resultado = $MySQLiconn->query("SELECT idSustrato,descripcionSustrato FROM sustrato where baja=1");

?>
<option value="0">--</option>
<?php
while ($row = $resultado->fetch_array()) {
?> 
<?php
if($getROW['sustrato']==$row['idSustrato'])
{
    ?>
    <option selected value="<?php echo $row ['idSustrato'];?>" ><?php echo $row['descripcionSustrato'];?></option>
    <?php
}
else
{
?>
    <option value="<?php echo $row ['idSustrato'];?>" ><?php echo $row['descripcionSustrato'];?></option>
    <?php 
}
}
?>
</select>

<?php
/*
OCULTO
*/
?>
<input type="hidden" name="editado" id="editado" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['IdBSPP'];?>"
    size="7" placeholder="mm">
</div>
 </div>
<?php
if(isset($_GET['edit']))
{
	
	?>
	<button style="float:right;" class="btn btn-default" type="submit" name="update">Actualizar</button>
	<?php
}
else
{
	?>
	<button style="float:right;" class="btn btn-default" type="submit" name="save">Guardar</button>
	<?php
} 
?>
</form>
</div>
<h4 class="ordenMedio">BSPP Activas</h4>
<div id="txtHint" class="table-responsive"><?php include('Controlador_Disenio/showBandaSPP.php');?></div>
<?php
if(isset($_GET['checkTodos']))
{?>
    <h4 class="ordenMedio">BSPP Inactivas</h4>
<div id="txtHunt" class="table-responsive"><?php include('Controlador_Disenio/showBandaSPP_bajas.php');?></div>
</div>
</div>
<?php
}
?>
</div>
</body>
 <script type="text/javascript" src="../css/menuFunction.js"></script>
<script type="text/javascript">
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
        
        xmlhttp.open("GET","Controlador_Disenio/showBandaSPP.php?e="+str,true);
        xmlhttp.send();
       //window.location="Producto_Consumos.php";
    }
}

window.onload = function() {
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

        xmlhttp.open("GET","Controlador_Disenio/showBandaSPP.php?e="+guat,true);
        xmlhttp.send();
    }
}  
</script>
<script type="text/javascript">
function showComboBajas(str) {
    if (str == "") {
        
        document.getElementById("txtHunt").innerHTML = "";
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

                document.getElementById("txtHunt").innerHTML = this.responseText;
            }
        };
        
        xmlhttp.open("GET","Controlador_Disenio/showBandaSPP_baja.php?e="+str,true);
        xmlhttp.send();
       //window.location="Producto_Consumos.php";
    }
}
</script>
</html>
<?php
                ob_end_flush();
            } else {
                echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
                include "../ingresar.php";
            }

            ?>
