<?php
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
	ob_start();
	$codigoPermiso='32';
	include ("funciones/crudMapa.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Mapa | Grupo Labro</title>
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#fff">
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body> 
<?php include("../components/barra_lateral2.php");	?>
<div id="page-wrapper">
	<div class="container-fluid">
<form method="post" name="formulary" id="formulary" role="form">
<div class="panel panel-default">

                <div class="panel-heading"><b class="titulo">Ciudades</b>
                </div>
<div class="panel-body">
                            
                            
<div class="col-xs-3">
<label for="example">Estado</label>
<?php  // Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="example" id="example" class="form-control" onChange="showCombo(this.value)" required>
    <?php
//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM estado where baja=1 ORDER BY pais DESC");
//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
//Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:

    if(isset($_GET['edit'])){ 
        if($getROW['estado'] == $row['id']){?>

            <option value="<?php echo $getROW['estado'];?>" selected> <?php echo $row['nombreEstado'];?></option>
            <?php  
        }
        else{  //Sino manda la lista normalmente:          ?>
            <option value="<?php echo $row ['id'];?>"><?php echo $row ['nombreEstado'];?></option>

            <?php 
        } 
    }
    elseif( $row['id']==$_SESSION['estadoID']){ //Si fue seleccionado. en caso de editar el campo manda a llamar el nombre del "fk" sino, muestra el nombre del cliente seleccionado  ?>
        <option value="<?php echo $row['id'];?>" selected>
        <?php echo $row['nombreEstado'];?>     
        </option>
        <?php 
    }
    else{   //Sino manda la lista normalmente:          ?>
        <option value="<?php echo $row ['id'];?>"><?php echo $row ['nombreEstado'];?></option>
        <?php 
    } 
}  ?> 
</select>
</div>

<div class="col-xs-3">
<label for="cd">Ciudad</label>
<input type="text" name="cd" id="cd" class="form-control"  value="" size="20" placeholder="Ejemplo: León" required>
</div> 
</div>


<button class="btn btn-default" style="float:right;" type="submit"  name="saveCity">Guardar</button>
</div>
</form>
<h4 class="ordenMedio">Ciudades</h4>
<div id="txtHint" class="table-responsive"></div>
</div>
</div>
</body>

<script type="text/javascript" language="JavaScript">
function showCombo(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } 
    else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();    
        } 
        else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");    
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","funciones/mostrarCiudad.php?q="+str,true);
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
        xmlhttp.open("GET","funciones/mostrarCiudad.php?q="+guat,true);
        xmlhttp.send();
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