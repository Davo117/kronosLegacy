<?php

session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { 
	ob_start();	
	include("Controlador_Disenio/db_Producto.php");
	//include("Controlador_Disenio/bitacoras/bitacoraDisenio.php");
	include("Controlador_Disenio/crud_Suaje.php");	?>
	
	<head>
	<title>Suaje| Producto</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
	<link rel="manifest" href="../pictures/manifest.json">
	<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="theme-color" content="#000">
     	<script type="text/javascript" src="../css/teclasN.js"></script>
	</head>
	<body > 
	<?php	include("../components/barra_lateral2.php");	?>
	<div id="page-wrapper">
    <div class="container-fluid">
	<form method="POST" name="formulary" id="formulary" role="form">
            <div class="panel panel-default">

                <div class="panel-heading"><b class="titulo">Suaje</b>
			<?php 
 	if(isset($_POST['checkTodos'])){	?>

        <label class="checkbox-inline" style="float:right;">
                            <input type="checkbox" id="checkboxEnLinea1" checked value="checkTodos" name="checkTodo" onclick="window.location='Producto_Suaje.php';"> Mostrar todo
                        </label>
 		<?php
 	}
 	else{ ?>
 		<label class="checkbox-inline" style="float:right;">
                                <input type="checkbox" id="checkboxEnLinea1" value="checkTodo" name="checkTodos" onclick="document.formulary.submit()"> Mostrar todo
                            </label>
	<?php
 	}?>
 </div>
<div class="panel-body">
<div class="col-xs-3">
<label for="combillo">Producto</label>
<select id="combillo" class="form-control" name="producto" required onChange="showCombo(this.value)">
  
    <option value>--</option>
    <?php
    $consulta=$MySQLiconn->query("SELECT descripcion FROM producto where baja=1 && suaje=1");
while ($row1=$consulta->fetch_array()) {
   
	$segunda=$MySQLiconn->query("SELECT descripcionImpresion, codigoImpresion, descripcionDisenio FROM impresiones where baja='1' and descripcionDisenio='".$row1['descripcion']."'");

  	$empresa='ssss';

  	while($row2=$segunda->fetch_array()){ //mientras se tengan registros:
    	// Comparas tu variable con la empresa
	    if($empresa != $row2['descripcionDisenio']){ 
    	    // Ahora habra que comparar con esta empresa cada que de vuelta los datos 
        	$empresa = $row2['descripcionDisenio'];
	        //se agrega dentro del if la etiqueta de optgroup para inicializar el orden
    	    //no te olvides de la etiqueta de cierre ?>
        	<optgroup label="<?php echo $empresa; ?>">
        	<?php //cerramos el if anterior:
      	}
    	if(isset($_GET['edit'])){
    		if ($row2['descripcionImpresion']==$getROW['descripcionImpresion']) { ?>
	    		<option value="<?php echo $getROW ['descripcionImpresion'];?>" selected><?php echo $row2['descripcionImpresion'];?>  </option>
    			<?php 
    		}
    		else{       //Sino manda la lista normalmente:   ?>
      			<option value="<?php echo $row2['descripcionImpresion'];?>"><?php echo $row2['descripcionImpresion'];?></option>
	      		<?php 
    		}
    	}
    	elseif($row2['descripcionImpresion']==$_SESSION['suaje']) { ?>
	      <option value="<?php echo $row2 ['descripcionImpresion'];?>" selected><?php echo $row2['descripcionImpresion'];?>  </option>
    		<?php 
    	}
    	else{       //Sino manda la lista normalmente:   ?>
      	<option value="<?php echo $row2['descripcionImpresion'];?>"><?php echo $row2['descripcionImpresion'];?></option>
	      <?php 
    	}
  	} 
}?> 
</optgroup>
</select>
</div>

<div class="col-xs-3">
<label for="identificadorS">Identificador:</label>
<input type="text" class="form-control" required name="identificadorS" id="identificadorS" value="<?php if(isset($_GET['edit'])) echo $getROW['identificadorSuaje'].'" readonly="true';  ?>"  placeholder="Nombre Suaje">
</div>
<div class="col-xs-3">
<label for="proveedor">Proveedor:</label>
<input type="text" class="form-control" required name="proveedor" id="proveedor" value="<?php if(isset($_GET['edit'])) echo $getROW['proveedor'];?>" size="15" placeholder="Proveedor">
</div>

<div class="col-xs-3">
<label for="cdg">Código:</label>
<input type="text" class="form-control" required name="cdg" id="cdg" value="<?php if(isset($_GET['edit'])) echo $getROW['codigo'];?>" size="15" placeholder="código">
</div>
<?php
if(isset($_GET['edit']))
{?>
    <div class="col-xs-3">
<label for="pzs">Repeticiones:</label>
<input type="text" maxlength="10" class="form-control" required id="pzs" name="pzs" value="<?php if(isset($_GET['edit'])) echo $getROW['piezas'];?>" onkeypress="return numeros(event)" size="15" placeholder="cantidad">
</div>
<?php
}

?>
<div class="col-xs-3" style="clear: left;">
<label for="alturaReal">Altura real:</label>
<input type="text" class="form-control" maxlength="10" required name="alturaReal" id="alturaReal" value="<?php if(isset($_GET['edit'])) echo $getROW['alturaReal'];?>" onkeypress="return numeros(event)" size="15" placeholder="mm">
</div>

<div class="col-xs-3">
<label for="corteS">Corte de Seguridad:</label>
<input type="text" class="form-control" name="corteS" id="corteS" value="<?php if(isset($_GET['edit'])) echo $getROW['corteSeguridad'];?>" required>
</div>

<div class="col-xs-3">
<label  for="resultado">Resguardo:</label>
<input class="form-control" type="text" name="resguardo" id="resguardo" value="<?php if(isset($_GET['edit'])) echo $getROW['reguardo'];?>" required placeholder="Resguardo">
</div>

<div class="col-xs-3">
<label for="observaciones">Observaciones:</label>
<input type="text" class="form-control" maxlength="250" required id="observaciones" name="observaciones" value="<?php if(isset($_GET['edit'])) echo $getROW['observaciones'];?>" placeholder="Observaciones">
</div>

<div class="col-xs-3">
<label for="proceso">Procesos</label>
<?php // Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="proceso" class="form-control" required id="proceso">
    <option value="">[Seleccione un Proceso]</option>
    <?php
//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT descripcionProceso FROM procesos where baja=1 and descripcionProceso='suajado' or descripcionProceso='troquelado' or descripcionProceso='impresion-flexografica' ORDER BY descripcionProceso");
//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
//Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:
    if(isset($_GET['edit'])){ 
        if($getROW['proceso'] == $row['descripcionProceso']){  ?>
            <option value="<?php echo $getROW['proceso'];?>" selected> <?php echo $row['descripcionProceso'];?></option>
            <?php  
        }
        else{  //Sino manda la lista normalmente:          ?>
            <option value="<?php echo $row ['descripcionProceso'];?>"><?php echo $row ['descripcionProceso'];?></option>
            <?php 
        } 
    }
    else{   //Sino manda la lista normalmente:          ?>
        <option value="<?php echo $row ['descripcionProceso'];?>"><?php echo $row ['descripcionProceso'];?></option>
        <?php 
    } 
}  ?> 
</select>
</div> 
</div>
<?php  
	if(isset($_GET['edit'])){	?>
		<button  style="float:right;" class="btn btn-default" type="submit" name="update" >Actualizar</button>
	   <?php
    }
	else{ ?>
		<button  style="float:right;" class="btn btn-default" type="submit" name="save" >Guardar</button>
		<?php
	} ?>
 </form>
</div>
<div id="txtHint" class="table-responsive"></div>
<?php 
if(isset($_POST['checkTodos'])){?>
    <div id="txtHint2" class="table-responsive"></div>   
    <?php
}?>
	</div>
</div>
	</body>
	<script type="text/javascript" language="JavaScript">
function showCombo(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        document.getElementById("txtHint2").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
             xmlhttp2 = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            xmlhttp2 = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };

        xmlhttp2.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
            document.getElementById("txtHint2").innerHTML = this.responseText;
            }
        };

        xmlhttp.open("GET","Controlador_Disenio/showSuaje.php?q="+str,true);
        xmlhttp2.open("GET","Controlador_Disenio/showSuaje_bajas.php?q="+str,true);
        xmlhttp.send();
        xmlhttp2.send();

    }
}
window.onload = function() {
  showComboLoad(combillo.value);
};
function showComboLoad(guat) {
    if (guat == "") {
        document.getElementById("txtHint").innerHTML = "";
         document.getElementById("txtHint2").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
            xmlhttp2 = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            xmlhttp2 = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
         xmlhttp2.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
            document.getElementById("txtHint2").innerHTML = this.responseText;
            }
        };

        xmlhttp.open("GET","Controlador_Disenio/showSuaje.php?q="+guat,true);
        
        xmlhttp.send();
        xmlhttp2.open("GET","Controlador_Disenio/showSuajes_bajas.php?q="+guat,true);

        xmlhttp2.send();
    }
}
</script>
<script type="text/javascript" src="../css/menuFunction.js"></script>
	</html>
<?php ob_end_flush();
}
else {
	echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
	include "../ingresar.php";
} ?>