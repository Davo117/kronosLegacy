<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>
<head>
<title>Cilindros | Producto</title>
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">
   <script type="text/javascript" src="../css/teclasN.js"></script>
</head>
<body> 
<?php

$_SESSION['codigoPermiso']='20004';
include ("funciones/permisos.php");
include("Controlador_Disenio/bitacoras/bitacoraCilindro.php");
include("../components/barra_lateral2.php");
include("Controlador_Disenio/crud_JuegosCilindros.php");
//include("../css/barra_horizontal2.php");
$MySQLiconn->query("TRUNCATE pruebas");
?>
    <div id="page-wrapper">
    <div class="container-fluid">

<form method="post"  name="formulary" id="formulary"  role="form">
<div class="panel panel-default">

                <div class="panel-heading"><b class="titulo">Juegos de cilindros</b>
                    <?php
if(isset($_POST['checkTodos']))
{?>

        <label class="checkbox-inline" style="float:right;">
                            <input type="checkbox" id="checkboxEnLinea1" checked value="checkTodos" name="checkTodo" onclick="window.location='Producto_JuegosCilindros.php'"> Mostrar todo
                        </label>
<?php
}
else
{?>
        <label class="checkbox-inline" style="float:right;">
                                <input type="checkbox" id="checkboxEnLinea1" value="checkTodo" name="checkTodos" onclick="document.formulary.submit()"> Mostrar todo
                            </label>
<?php
}
    ?>
</div>
<div class="panel-body">
<div class="col-xs-3">
<label for="comboImpresiones">Impresión:</label>
    <select onChange="showCombo(this.value),showGrupo(this.value)" class="form-control" name="comboImpresiones"   id="comboImpresiones">
<?php
include ("Controlador_Disenio/db_Producto.php");
$reslt = $MySQLiconn->query("SELECT dato from cache where id=3");
$rau = $reslt->fetch_array();
$impresion=$rau['dato'];
$inProd=0;
$resultado = $MySQLiconn->query("SELECT concat(producto.descripcion,' | ' ,impresiones.descripcionImpresion) as productos, impresiones.descripcionImpresion from producto left join impresiones on producto.descripcion=impresiones.descripcionDisenio where impresiones.baja=1 && producto.baja=1 order by producto.descripcion asc");
while ($row = $resultado->fetch_array()) {
?> 
<?php
if( $row ['descripcionImpresion']==$impresion)
{
	?>
	<option value="<?php echo $row ['descripcionImpresion'];?>" selected><?php echo $row['productos'];?></option>
	<?php 
}
	else{
		?>
<option value="<?php echo $row ['descripcionImpresion'];?>"><?php echo $row['productos'];?></option>
<?php 
}
} 
?> 
</select>
</div>
<div class="col-xs-3">
<label for="identificadorCilindro">Identificador:</label>
<input type="text" required class="form-control" name="identificadorCilindro" id="identificadorCilindro" value="<?php if(isset($_GET['edit'])) echo $getROW['identificadorCilindro'].'" readonly="true';  ?>"
    size="15" placeholder="#">
</div>

<div class="col-xs-3">
<label for="proveedor">Proveedor:</label>
<input type="text" name="proveedor"  id="proveedor" class="form-control" value="<?php if(isset($_GET['edit'])){ echo $getROW['proveedor']; if($getROW['prod']==1){echo '"readonly="true';}}?>"
    size="15" placeholder="proveedor" required >
</div>

<div class="col-xs-3">
<label for="fechaRecepcion">Fecha de recepción:</label>
<input type="date" name="fechaRecepcion" class="form-control" value="<?php if(isset($_GET['edit'])){echo $getROW['fechaRecepcion']; if($getROW['prod']==1){echo '"readonly="true';}}?>"
    required >
</div>
<div  class="col-xs-3">
<label for="diametro">Diámetro:</label>
<input id="diametro" class="form-control" onkeypress="return numeros(event)" type="number" name="diametro" value="<?php if(isset($_GET['edit'])){ echo $getROW['diametro']; if($getROW['prod']==1){echo '"readonly="true';}}?>"
     placeholder="mm" required >
</div>

<?php
if(isset($_GET['edit']))
{?>
<div class="col-xs-3">
<label for="tabla">Tabla:</label>
<input  required  id="tabla" readonly="true" onkeypress="return numeros(event)" class="form-control" type="number" name="tabla" value="<?php if(isset($_GET['edit'])){echo $getROW['tabla']; if($getROW['prod']==1){echo '"readonly="true';}}?>"
     placeholder="mm">
</div>
<?php
}

if(isset($_GET['edit']))
{?>
<div class="col-xs-3">
<label for="repAlPaso">Repeticiones al paso:</label>
<input  class="form-control" readonly="true" onkeypress="return numeros(event)" type="number" name="repAlPaso" id="repAlPaso" value="<?php if(isset($_GET['edit'])){ echo $getROW['repAlPaso']; if($getROW['prod']==1){echo '"readonly="true';}}?>"
    placeholder="#">
</div>
<?php
}
?>

<div class="col-xs-3">
<label for="repAlGiro">Repeticiones al giro:</label>
 <input  required class="form-control" onkeypress="return numeros(event)" type="number" name="repAlGiro" id="repAlGiro" value="<?php if(isset($_GET['edit'])){echo $getROW['repAlGiro']; if($getROW['prod']==1){echo '"readonly="true';}}?>"
     placeholder="#">
</div>

<div class="col-xs-3"> 
<label for="girosGarantizados">Giros garantizados:</label>
<input required id="girosGarantizados" class="form-control" onkeypress="return numeros(event)" type="number" name="girosGarantizados"  value="<?php if(isset($_GET['edit'])){echo $getROW['girosGarantizados']; if($getROW['prod']==1){echo '"readonly="true';}}?>"
     placeholder="#">
</div>
    
    <?php
    if(isset($_GET['show']) || isset($_GET['edit']))
    {
        ?> <br><hr style="clear:left;margin-top:10px;margin-right:10px;"><div><?php
    }
    else
    {
        ?><div hidden=""><?php
    }
    ?>
    <br>
    <h4>Parámetros de operación</h4><br>
<div class="col-xs-3">
<label for="viscosidad">Viscosidad:</label>
<input  type="text" name="viscosidad" id="viscosidad" value="<?php if(isset($_GET['edit'])) echo $getROW['viscosidad'];?>"
    size="10" placeholder="segundos" class="form-control">
</div>
<div class="col-xs-3">
<label for="velocidad">Velocidad</label>
<input  class="form-control" type="text" name="velocidad" id="velocidad" value="<?php if(isset($_GET['edit'])) echo $getROW['velocidad'];?>"  size="10" placeholder="mts/min">
</div>

<div class="col-xs-3">
<label for="presionCilindro">Presión del cilindro:</label>
<input class="form-control" type="text" name="presionCilindro" id="presionCilindro" value="<?php if(isset($_GET['edit'])) echo $getROW['presionCilindro'];?>" size="10" placeholder="psi">
</div>

<div class="col-xs-3">
<label for="presionGoma">Presión de la goma:</label>
<input class="form-control" type="text" name="presionGoma" id="presionGoma" value="<?php if(isset($_GET['edit'])) echo $getROW['presionGoma'];?>"
    size="7" placeholder="psi">
</div>

<div class="col-xs-3">
<label for="presionRasqueta">Presión de la rasqueta:</label>
<input class="form-control" type="text" name="presionRasqueta" id="presionRasqueta" value="<?php if(isset($_GET['edit'])) echo $getROW['presionRasqueta'];?>" size="6" placeholder="psi">
</div>

<div class="col-xs-3">
<label for="tolViscosidad">Tolerancia en viscosidad:</label>
<input class="form-control" type="text" name="tolViscosidad" id="tolViscosidad" value="<?php if(isset($_GET['edit'])) echo $getROW['tolViscosidad'];?>"
    size="12" placeholder="+/- segundos">
</div>

<div class="col-xs-3">
 <label for="tolVelocidad">Tolerancia en velocidad:</label>
 <input class="form-control" type="text" name="tolVelocidad" id="tolVelocidad" value="<?php if(isset($_GET['edit'])) echo $getROW['tolVelocidad'];?>"
    size="10" placeholder="+/- mts/min">
</div>

<div class="col-xs-3">
<label for="tolCilindro">Tolerancia en cilindro:</label>
<input  class="form-control" type="text" name="tolCilindro" id="tolCilindro" value="<?php if(isset($_GET['edit'])) echo $getROW['tolCilindro'];?>" size="10" placeholder="+/- psi">
</div>

<div class="col-xs-3"> 
<label for="tolTemperatura">Tol. en temperatura:</label>
<input class="form-control" type="text" name="tolTemperatura" id="tolTemperatura" value="<?php if(isset($_GET['edit'])) echo $getROW['tolTemperatura'];?>"
    size="10" placeholder="+/- grados">
</div>

<div class="col-xs-3">
<label for="temperatura">Temperatura:</label>
<input  class="form-control" type="text" name="temperatura" id="temperatura" value="<?php if(isset($_GET['edit'])) echo $getROW['temperatura'];?>"
    size="10" placeholder="grados">
</div>

<div class="col-xs-3">
<label for="tolGoma">Tolerancia en goma:</label>
 <input type="text" name="tolGoma" class="form-control" id="tolGoma" value="<?php if(isset($_GET['edit'])) echo $getROW['tolGoma'];?>"
    size="10" placeholder="+/- psi">
</div>

<div class="col-xs-3">
<label for="tolRasqueta">Tolerancia en rasqueta:</label>
<input class="form-control" type="text" name="tolRasqueta" id="tolRasqueta" value="<?php if(isset($_GET['edit'])) echo $getROW['tolRasqueta'];?>"
    size="10" placeholder="+/- psi">
</div>
</div>
</div>
<?php
if(isset($_GET['show']) || isset($_GET['edit']))
{
?>
<a href="?hidde" class="" style="float:right;" title="Ocultar parámetros de operación" name="hidde"><IMG src='../pictures/gearfourth.png'></IMG></a>

<?php
}
else
{
    ?>
<a href="?show" class="" style="float:right;" title="Ver parámetros de operación" name="show"><IMG src='../pictures/gearfourth.png'></IMG></a>
<?php
}
if(isset($_GET['edit']))
{
    ?>
    <button style="float:right;" class="btn btn-default"  type="submit" name="update">Actualizar</button>
    <?php
}
else
{
    ?>
    <button style="float:right;" class="btn btn-default"  type="submit" name="save">Guardar</button>
    <?php
}
?>

</form>
</div>
<h4 class="ordenMedio">Juegos Activos</h4>
<div id="txtHint" class="table-responsive"></div>
<?php
if(isset($_POST['checkTodos']))
{?>
    <h4 class="ordenMedio">Juegos Inactivos</h4>
    <div class="table-responsive">
<?php
include("Controlador_Disenio/showCilindros_bajas.php");
}
?>
</div>
</center>
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
        
        xmlhttp.open("GET","Controlador_Disenio/showCilindros.php?e="+str,true);
        xmlhttp.send();
       //window.location="Producto_Consumos.php";
    }
}

window.onload = function() {
 showComboLoad(comboImpresiones.value);
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

        xmlhttp.open("GET","Controlador_Disenio/showCilindros.php?e="+guat,true);
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
        
        xmlhttp.open("GET","Controlador_Disenio/showCilindros_baja.php?e="+str,true);
        xmlhttp.send();
       //window.location="Producto_Consumos.php";
    }
} 
function pregunta(){ 
    if (confirm('¿Los datos ingresados son correctos?')){ 
       document.formulary.submit() 
    } 
}
function showGrupo(disenio)
{
    if(disenio == "")
    {
        document.getElementById("cmbImpresores").innerHTML="";
        return;
    }
    else
    {
        if(window.XMLHttpRequest)
        {
            xmlhttp=new XMLHttpRequest();
        }
        else
        {

        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
     xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                document.getElementById("cmbImpresores").innerHTML = this.responseText;
            }
        };
         xmlhttp.open("GET","Controlador_Disenio/crud_JuegosCilindros.php?c="+disenio,true);
         xmlhttp.send();
     }

}

</script> 
</body>
 <script type="text/javascript" src="../css/menuFunction.js"></script>
</html>
<?php
ob_end_flush();

} else {
      echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
         include "../ingresar.php";
     }
     ?>