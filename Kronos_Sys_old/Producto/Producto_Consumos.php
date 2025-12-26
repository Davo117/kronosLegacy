<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>

<head>
<title>Consumos | Producto</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">
</head>

<body> 
<?php
/*include("Controlador_Disenio/crud_impresion");*/
$_SESSION['codigoPermiso']='20003';
include ("funciones/permisos.php");
include("../components/barra_lateral2.php");

include("Controlador_Disenio/crud_Consumos.php");
?>
<div id="page-wrapper">
    <div class="container-fluid">
<form method="GET"  name="formulary" id="formulary" role="form">
    <div class="panel panel-default">

                <div class="panel-heading"><b class="titulo">Consumos</b>
<?php
if(isset($_GET['checkTodo']))
{?>
    <label class="checkbox-inline" style="float:right;">
                            <input type="checkbox" id="checkboxEnLinea1" checked value="checkTodo" name="checkTodo" onclick="window.location='Producto_Consumos.php?descripcionCons=<?php echo $producto?>'"> Mostrar todo
                        </label>
<?php
}
else
{?>
<label class="checkbox-inline" style="float:right;">
                                <input type="checkbox" id="checkboxEnLinea1" value="checkTodo" name="checkTodo" onclick="document.formulary.submit()"> Mostrar todo
                            </label>
<?php
}?>
</div>
<div class="panel-body">
<div class="col-xs-3">
<label for="comboDisenios2">Impresión</label>
<select  onChange="document.formulary.submit()" name="comboDisenios2"  id="comboDisenios2" class="form-control">
    <?php
include ("Controlador_Disenio/db_Producto.php");
if(isset($_GET['descripcionCons']))
{

$producto=$_GET['descripcionCons'];
}
else if(isset($_GET['comboDisenios2']))
{
        $producto=$_GET['comboDisenios2'];
}
else if(isset($_GET['edit']))
    {
        $producto=$_GET['comboDisenios2'];
        if(empty($producto))
        {
            $producto==$_GET['descripcionCons'];
        }
    }
	
$resultado = $MySQLiconn->query("SELECT id,descripcionImpresion FROM impresiones where baja=1");
?>
<option value="0">Predeterminados</option>
<?php
while ($row = $resultado->fetch_array()) {
?> 
<?php
if( $row ['id']==$producto)
{
	?>
	<option value="<?php echo $row['id'];?>" selected><?php echo $row['descripcionImpresion'];?></option>
	<?php 
}
	else{
		?>
<option value="<?php echo $row ['id'];?>"><?php echo $row['descripcionImpresion'];?></option>

<?php 
}
} 
?> 
</select>
</div>

<div class="col-xs-3">
<label for="ComboProcesos">Proceso:</label>
<select required name="ComboProcesos" id="ComboProcesos" class="form-control">
    <?php
include ("Controlador_Bloques/db_materiaPrima.php");
if(isset($_GET['comboDisenios2']) || isset($_GET['descripcionCons']) and $producto!=0)
{
    $resultado = $MySQLiconn->query("SELECT descripcionproceso,numeroProceso from juegoprocesos where identificadorJuego=(SELECT juegoProcesos from tipoproducto where alias=(SELECT tipo from producto where descripcion=(
SELECT descripcionDisenio from impresiones where id='".$producto."'))) and descripcionProceso!='programado'");
}
else
{
    $resultado = $MySQLiconn->query("SELECT descripcionProceso as descripcionproceso FROM procesos where baja=1");
}


?>
<option value="">--</option>
<?php
while ($row = $resultado->fetch_array()) {
?> 
	<?php
if( isset($getROW) && $getROW['subProceso']==$row['descripcionProceso'])
{
	?>
	<option value="<?php echo $getROW['subProceso'];?>" selected><?php  echo ucwords($getROW['subProceso']); if($getROW=="rollo" || $getROW=="caja"){ echo ' ['.'Empaque'.']';}?></option>
	<?php 
}
	else{
		?>
<option value="<?php echo $row['descripcionproceso'];?>"><?php echo ucwords($row['descripcionproceso']); if($row['descripcionproceso']=="rollo" || $row['descripcionproceso']=="caja"){ echo ' ['.'Empaque'.']';}?></option>

<?php 
}
} 
?> 
</select>
</div>

<div class="col-xs-3">
<label for="comboElementos">Elemento:</label>
<select required  name="comboElementos" id="comboElementos" class="selectpicker show-menu-arrow" data-style="form-control" data-live-search="true" title="--Selecciona el producto--">
    <?php
include ("Controlador_Bloques/db_materiaPrima.php");

$resultado = sqlsrv_query($SQLconn, "SELECT CIDPRODUCTO as id,CCODIGOPRODUCTO as codigo,CNOMBREPRODUCTO as nombreElemento FROM admproductos WHERE CIDPRODUCTO!=0 AND CSTATUSPRODUCTO=1");
?>
<option value="">--</option>
<?php
while ($row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
?> 
	<?php
if( $getROW['elemento']==$row['id'])
{
	?>
	<option data-tokens="<?php echo $row['nombreElemento'];?>" value="<?php echo $getROW['id'];?>" selected><?php  echo $getROW['elemento'];?></option>
	<?php 
}
	else{
		?>
<option data-tokens="<?php echo $row['nombreElemento'];?>" value="<?php echo $row['id'];?>"><?php echo $row['nombreElemento'];?></option>

<?php 
}
} 
?>
</select>
</div>

<div class="col-xs-3" id="cmbunidades">
<label for="cantConsumo">Cantidad de consumo:</label>
<input type="text" name="cantConsumo" id="cantConsumo"  class="form-control" required value="<?php if(isset($_GET['edit'])) echo $getROW['consumo'];?>" placeholder="cantidad">
</div>
</div>
<?php

if(isset($_GET['edit']))
{
	
	?>
	<button  style="float:right;" class="btn btn-default"  type="submit" name="update">Actualizar</button>
	<?php
}
else
{
	?>
	<button  style="float:right;" class="btn btn-default" type="submit" name="save">Guardar</button>
	<?php
}
?>
</form>
</div>
<h4 class="ordenMedio">Consumos activos</h4>
<div id="txtHint" class="table-responsive">
    <?php include("Controlador_Disenio/showConsumos.php");?>
</div>
<?php
if(isset($_GET['checkTodo']))
{?>
	<h4 class="ordenMedio">Consumos Inactivos</h4>
	<div id="txtHunt" class="table-responsive">
     <?php include("Controlador_Disenio/showConsumos_baja.php");?>   
    </div><?php
}
?>
</div>
</div>
</body>
 <script type="text/javascript" src="../css/menuFunction.js"></script>
<script>
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
        
        xmlhttp.open("GET","Controlador_Disenio/showConsumos.php?e="+str,true);
        xmlhttp.send();
       //window.location="Producto_Consumos.php";
    }
}
/*window.onload = function() {

 showCombo(comboDisenios2.value);
 showComboBajas(comboDisenios2.value);
};

function showComboLoadConsumos(wat) {
	
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

        xmlhttp.open("GET","Controlador_Disenio/showConsumos.php?e="+wat,true);
        xmlhttp.send();

    }
}
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
        
        xmlhttp.open("GET","Controlador_Disenio/showConsumos_baja.php?e="+str,true);
        xmlhttp.send();
       //window.location="Producto_Consumos.php";
    }
}*/
</script>
<script type="text/javascript">
    $(document).ready(function(){
                    
                $("select[name=comboElementos]").change(function(e)
                {
                    consulta = $("select[name=comboElementos]").val();
                $("#cantConsumo").delay(100).queue(function(n) {      
                
                
                $.ajax({
                    type: "POST",
                    url: "Controlador_Disenio/crud_Consumos.php",
                    data: "b="+consulta,
                    dataType: "html",
                    error: function(){
                        alert("error petición ajax");
                    },
                    success: function(data){                                                      
                        $("#cantConsumo").html(data);
                        n();
                    }
                });
                
             });
                });
                });
                </script>
</html>
    <?php
                ob_end_flush();
            } else {
                echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
                include "../ingresar.php";
            }
