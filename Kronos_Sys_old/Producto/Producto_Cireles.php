<?php

session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { 
	ob_start();	
	include("Controlador_Disenio/db_Producto.php");
	//include("Controlador_Disenio/bitacoras/bitacoraDisenio.php");
	include("Controlador_Disenio/crud_Cireles.php");	?>
	
	
	<title>Cireles| Producto</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
	<link rel="manifest" href="../pictures/manifest.json">
	<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="theme-color" content="#000">
     	<script type="text/javascript" src="../css/teclasN.js"></script>
	</head>
	<body> 
	<?php	include("../components/barra_lateral2.php");	?>
	
    <div id="page-wrapper">
    <div class="container-fluid">
	<form method="POST" name="formulary" id="formulary" role="form">
            <div class="panel panel-default">

                <div class="panel-heading"><b class="titulo">Juegos de cireles</b>
			<?php 
 	if(isset($_POST['checkTodos'])){	?>
 		
        <label class="checkbox-inline" style="float:right;">
                            <input type="checkbox" id="checkboxEnLinea1" checked value="checkTodos" name="checkTodo" onclick="window.location='Producto_Cireles.php';"> Mostrar todo
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
<select id="combillo" name="producto" required onChange="showCombo(this.value)" class="form-control">
  
    <option value>--</option>
    <?php
    $consulta=$MySQLiconn->query("SELECT descripcion FROM producto where baja=1 && cireles=1");
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
    		if ($row2['codigoImpresion']==$getROW['producto']) { ?>
	    		<option value="<?php echo $getROW ['producto'];?>" selected><?php echo $row2['descripcionImpresion'];?>  </option>
    			<?php 
    		}
    		else{       //Sino manda la lista normalmente:   ?>
      			<option value="<?php echo $row2['codigoImpresion'];?>"><?php echo $row2['descripcionImpresion'];?></option>
	      		<?php 
    		}
    	}
    	elseif($row2['codigoImpresion']==$_SESSION['cirel']) { ?>
	      <option value="<?php echo $row2 ['codigoImpresion'];?>" selected><?php echo $row2['descripcionImpresion'];?>  </option>
    		<?php 
    	}

    	else{       //Sino manda la lista normalmente:   ?>
      	<option value="<?php echo $row2['codigoImpresion'];?>"><?php echo $row2['descripcionImpresion'];?></option>
	      <?php 
    	}
  	} 
}?> 
</optgroup>
</select>
</div>

<div class="col-xs-3">
<label for="identificadorJ">Identificador:</label>
<input maxlength="10" type="text" class="form-control" required name="identificadorJ" id="identificadorJ" value="<?php if(isset($_GET['edit'])) echo $getROW['identificadorJuego'].'" readonly="true';  ?>"  placeholder="Nombre Cirel">
</div>
<div class="col-xs-3">
<label for="num_dientes">Número de dientes:</label>
<input maxlength="10" type="text" required name="num_dientes" id="num_dientes" value="<?php if(isset($_GET['edit'])){echo $getROW['num_dientes']; if($getROW['prod']==1){echo '"readonly="true';}}?>" onkeypress="return numeros(event)"  placeholder="#">
</div>
<?php
if(isset($_GET['edit']))
{?>
<div class="col-xs-3">
<label for="repeticiones">Repeticiones ala tabla:</label>
<input maxlength="10" type="text" required name="repeticiones" class="form-control" id="repeticiones" value="<?php if(isset($_GET['edit'])){ echo $getROW['repeticiones'];  if($getROW['prod']==1){echo '"readonly="true';}}?>" onkeypress="return numeros(event)" placeholder="#">
</div>
<?php
}

if(isset($_GET['edit']))
{?>

<div class="col-xs-3">
<label for="ancho_plano">Ancho plano:</label>
<input maxlength="10" type="text" readonly="true" class="form-control" required name="ancho_plano" id="ancho_plano" value="<?php if(isset($_GET['edit'])){ echo $getROW['ancho_plano'];  if($getROW['prod']==1){echo '"readonly="true';}}?>" onkeypress="return numeros(event)"  placeholder="#">
</div>
<?php
}

?>
<div class="col-xs-3">
<label for="alturaReal">Altura real:</label>
<input maxlength="10" type="text" required name="alturaReal" id="alturaReal" class="form-control" value="<?php if(isset($_GET['edit'])){ echo $getROW['alturaReal'];  if($getROW['prod']==1){echo '"readonly="true';}}?>" onkeypress="return numeros(event)" placeholder="#">
</div>

<div class="col-xs-3">
<label for="fechaE">Fecha de recepción:</label>
<input type="date" name="fechaE" id="fechaE" class="form-control" value="<?php if(isset($_GET['edit'])){echo $getROW['fecha_entrega'];  if($getROW['prod']==1){echo '"readonly="true';}}?>" required >
</div>
<div class="col-xs-3">
<label for="observaciones">Observaciones:</label>
<input type="text" maxlength="250" required name="observaciones" id="observaciones" class="form-control" value="<?php if(isset($_GET['edit'])){ echo $getROW['observaciones'];  if($getROW['prod']==1){echo '"readonly="true';}}?>" placeholder="Máximo 250 carácteres">
</div>
</div>

<?php  
	if(isset($_GET['edit'])){	?>
		<button  class="btn btn-default" style="float:right;" type="submit" name="update">Actualizar</button>
		<br>
		<?php
		$numero=$MySQLiconn->query("SELECT ppc.descripcionPantone, p.codigoHTML FROM pantonepcapa as ppc inner join pantone as p WHERE ppc.codigoImpresion='".$getROW['producto']."' && ppc.descripcionPantone= p.descripcionPantone ORDER BY ppc.codigoCapa ASC");
		$i=1;
		while ($tintas=$numero->fetch_array()) {
		echo "<p>Placa ".$i;?>
			
			<p style=" border-radius:2px;border-color:black; font-size:13px;color:black; padding:5px;margin:5px;width:20%;height:10%; background-color:<?php echo '#'.$tintas['codigoHTML']; ?>"><?php  echo $tintas['descripcionPantone'];?></p> </p>
		<?php $i++;

		}
	}
	else{ ?>
		<button class="btn btn-default" style="float:right;" type="submit" name="save">Guardar</button>
		<?php
	} ?>
 </form>
</div>
<h4 class='ordenMedio'>Juegos de cireles activos</h4>
<div id="txtHint" class="table-responsive"></div>

<?php 
if(isset($_POST['checkTodos'])){?>
    <h4 class='ordenMedio'>Juegos de cireles inactivos</h4>
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

        xmlhttp.open("GET","Controlador_Disenio/showCireles.php?q="+str,true);
        xmlhttp2.open("GET","Controlador_Disenio/showCireles_bajas.php?q="+str,true);
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
        xmlhttp.open("GET","Controlador_Disenio/showCireles.php?q="+guat,true);
        xmlhttp2.open("GET","Controlador_Disenio/showCireles_bajas.php?q="+guat,true);
        xmlhttp.send();
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