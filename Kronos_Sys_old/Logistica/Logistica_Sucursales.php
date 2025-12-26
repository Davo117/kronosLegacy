<?php
session_start();
	if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
	ob_start();
	$_SESSION['codigoPermiso']='40003';
    include ("funciones/permisos.php");
    include ("funciones/bitacoras/sucursalB.php"); 
    include("funciones/crud/crudSuc.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sucursales(Logística) | Grupo Labro</title>

<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#fff">
  <script type="text/javascript" src="../css/teclasN.js"></script>
</head>

<body> 
<?php    include("../components/barra_lateral2.php"); ?>
<div id="page-wrapper">
	<div class="container-fluid">
<form method="post" name="formulary" id="formulary" role="form">
<div class="panel panel-default">

                <div class="panel-heading"><b class="titulo">Sucursales</b>
                    <?php 
                    if(isset($_POST['checkTodos']))
                    {
                        ?>

                        <label class="checkbox-inline" style="float:right;">
                            <input type="checkbox" id="checkboxEnLinea1" checked value="checkTodos" name="checkTodo" onclick="document.formulary.submit()"> Mostrar todo
                        </label>
                        <?php
                    }
                    else
                        {?>

                            <label class="checkbox-inline" style="float:right;">
                                <input type="checkbox" id="checkboxEnLinea1" value="checkTodo" name="checkTodos" onclick="document.formulary.submit()"> Mostrar todo
                            </label>
                            <?php
                        }?></div>
                        <div class="panel-body">

<div class="col-xs-3">
<label for="example">Cliente</label>
<?php // Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="example" required id="example" onChange="showCombo(this.value)" class="form-control">
    <option value="">[Seleccione un Cliente]</option>
    <?php
//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM $tablacli where bajacli=1 ORDER BY ID DESC");
//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
//Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:

if(isset($_GET['edit'])){ 
    if($getROW['idcliFKS'] == $row['nombrecli']){  ?>
<option value="<?php echo $getROW['idcliFKS'];?>" selected> <?php echo $getROW['idcliFKS'];?></option>
<?php  
}else{  //Sino manda la lista normalmente:          ?>
<option value="<?php echo $row ['nombrecli'];?>"><?php echo $row ['nombrecli'];?></option>
<?php } }

elseif( $row ['nombrecli']==$_SESSION['cliente'])
{ //Si fue seleccionado. en caso de editar el campo manda a llamar el nombre del "fk" sino, muestra el nombre del cliente seleccionado  ?>
    <option value="<?php echo $row ['nombrecli'];?>" selected>
    <?php echo $row ['nombrecli'];?>        
    </option>
    <?php 
}   else{   //Sino manda la lista normalmente:          ?>
<option value="<?php echo $row ['nombrecli'];?>"><?php echo $row ['nombrecli'];?></option>
<?php } }  ?> 
</select>
</div> 
<div class="col-xs-3">
<label for="Ciudad">Ciudad</label>
<?php // Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="Ciudad" id="Ciudad" required class="form-control">
    <option value="">[Seleccione una Ciudad]</option>
    <?php
    //esta variable es una bandera para la ordenacion de campos en el combo box
$empresa="r";
//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM estado where baja=1 order by id");
//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
   // Comparas tu variable con el estado
    if($empresa != $row['nombreEstado']) {
        // Ahora habra que comparar con esta empresa cada que de vuelta los datos 
        $empresa = $row['nombreEstado'];
        //se agrega dentro del if la etiqueta de optgroup para inicializar el orden
        //no te olvides de la etiqueta de cierre ?>
    <optgroup label="<?php echo $empresa; ?>">
    <?php //cerramos el if anterior:
    }
    $consulta = $MySQLiconn->query("SELECT * FROM ciudad where baja=1  && estado='".$row['id']."' order by id");
    
    while($rowCity=$consulta->fetch_array()){
        $concat=$rowCity['nombreCity'].", ".$row['abreviatura'];
        //en caso de editar los campos...
        if(isset($_GET['edit'])){
            if($getROW['ciudadsuc'] == $concat){?>
                <option value="<?php echo $concat;?>" selected> <?php echo $concat;?></option>
                <?php  
            }
            else{   //Sino manda la lista normalmente:          ?>
                <option value="<?php echo $concat;?>"><?php echo $concat;?></option>
                <?php 
            }
        }
        else{   //Sino manda la lista normalmente:          ?>
            <option value="<?php echo $concat;?>"><?php echo $concat;?></option>
            <?php 
        }
    }
} ?> 
</optgroup>
</select>
</div>

<div class="col-xs-3">
<label for="Name">Nombre Suc.</label>
<input type="text" class="form-control" name="Name" id="Name" value="<?php if(isset($_GET['edit'])) echo $getROW['nombresuc'].'" readonly="true';  ?>"    size="20" placeholder="Nombre Sucursal" required>
</div> 

<div class="col-xs-3">
<label for="Domicilio">Domicilio Suc.</label>
<input type="text" name="Domicilio" id="Domicilio" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['domiciliosuc'];  ?>" size="20" placeholder="Calle y Número" required>
</div> 

<div class="col-xs-3">
<label for="Colonia">Colonia Suc.</label>
<input type="text" id="Colonia" class="form-control" name="Colonia" value="<?php if(isset($_GET['edit'])) echo $getROW['coloniasuc'];  ?>" size="20" placeholder="Colonia" required>
</div> 


<div class="col-xs-3">
<label for="CP">CP Suc.</label>
<input type="text" name="CP" id="CP" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['cpsuc'];  ?>"  size="20" placeholder="Código Postal" required onkeypress="return numeros(event)">
</div> 

<div class="col-xs-3"><label for="Telefono">Teléfono Suc.</label>
<input type="text" name="Telefono" id="Telefono" class="Telefono" value="<?php if(isset($_GET['edit'])) echo $getROW['telefonosuc'];  ?>"size="20" placeholder="Número Telefónico" required onkeypress="return numeros(event)">
</div> 

<div class="col-xs-3">
<label for="Transporte">Transporte Suc.</label>
<input type="text" name="Transporte" id="Transporte" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['transpsuc'];  ?>"size="20" placeholder="Transporte">
</div> 
</div>
<?php
if(isset($_GET['edit'])){   ?>
    <button class="btn btn-default" type="submit" style="float:right;" name="update">Actualizar</button>
    <?php  
}
else{  ?>
    <button class="btn btn-default" type="submit" style="float:right;" name="save">Guardar</button>
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

        xmlhttp.open("GET","funciones/mostrar/mostradorSuc.php?q="+str,true);
        xmlhttp2.open("GET","funciones/nomostrar/mostrarTodoSuc.php?q="+str,true);
        xmlhttp.send();
        xmlhttp2.send();

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
        xmlhttp.open("GET","funciones/mostrar/mostradorSuc.php?q="+guat,true);
        
        xmlhttp2.open("GET","funciones/nomostrar/mostrarTodoSuc.php?q="+guat,true);
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
    echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../ingresar.php'>";
    exit;
} ?>