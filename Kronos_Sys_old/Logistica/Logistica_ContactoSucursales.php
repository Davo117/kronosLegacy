<?php
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
  ob_start();
  $_SESSION['codigoPermiso']='40004';
  include ("funciones/permisos.php");
  include ("funciones/bitacoras/contactoSB.php"); 
  include("funciones/crud/crudSucCon.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Contacto Sucursal(Logística) | Grupo Labro</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#fff">
<script type="text/javascript" src="../css/teclasN.js"></script>
</head>

<body> 
<?php  include("../components/barra_lateral2.php");?>

<div id="page-wrapper">
	<div class="container-fluid">
<form method="post" name="formulary" id="formulary">
<div class="panel panel-default">

                <div class="panel-heading"><b class="titulo">Contactos de sucursales por cliente</b>
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
<label for="example">Sucursal</label>
<?php // Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="example" list="exampleList"  class="form-control" id="example" onChange="showCombo(this.value)">
    <?php
    //esta variable es una bandera para la ordenacion de campos en el combo box
$empresa="r";
//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM $tablasucursal where bajasuc=1 ORDER BY idsuc, idcliFKS DESC");

//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
  //Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:

  // Comparas tu variable con la empresa
  if($empresa != $row['idcliFKS']) {
    // Ahora habra que comparar con esta empresa cada que de vuelta los datos 
    $empresa = $row['idcliFKS'];
    //se agrega dentro del if la etiqueta de optgroup para inicializar el orden
    //no te olvides de la etiqueta de cierre ?>
    <optgroup label="<?php echo $empresa; ?>">
    <?php  //cerramos el if anterior:
  }

  if(isset($_GET['edit'])){ 
    if($getROW['sucFK'] == $row['nombresuc']){ ?>
      <option value="<?php echo $getROW['sucFK'];?>" selected> <?php echo $getROW['sucFK'];?></option>
      <?php  
    } 
    else{ //Sino manda la lista normalmente: ?>
      <option value="<?php echo $row ['nombresuc'];?>"><?php echo $row ['nombresuc'];?></option>
      <?php 
    }
  }
  elseif( $row ['nombresuc']==$_SESSION['sucursal']){ //Si fue seleccionado. en caso de editar el campo manda a llamar el nombre del "fk" sino, muestra el nombre del cliente seleccionado  ?>
    <option value="<?php echo $row ['nombresuc'];?>" selected>
    <?php echo $row ['nombresuc']; ?>
    </option>
    <?php
  }
  else{ //Sino manda la lista normalmente: ?>
    <option value="<?php echo $row ['nombresuc'];?>"><?php echo $row ['nombresuc'];?></option>
    <?php
  }
} ?>
</optgroup>
</select>
</div>

<div class="col-xs-3">
<label for="Nombre">Nombre</label>
<input type="text" id="Nombre" class="form-control" name="Nombre" value="<?php if(isset($_GET['edit'])) echo $getROW['nombreconsuc'].'"readonly="true'; ?>" size="20" placeholder="Nombre Completo" required>
</div>

<div class="col-xs-3">
<label for="Puesto">Puesto</label>
<input type="text" name="Puesto" id="Puesto" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['puestoconsuc']; ?>" size="20" placeholder="Puesto" required>
</div>

<div class="col-xs-3">
<label for="Telefono">Teléfono</label>
<input type="text" name="Telefono" id="Telefono" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['telconsuc']; ?>" size="20" placeholder="Número Telefónico" required  onkeypress="return numeros(event)">
</div>

<div class="col-xs-3">
<label for="Celular">Celular</label>
<input type="text" name="Celular" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['movilconsuc']; ?>" size="20" placeholder="Celular"  onkeypress="return numeros(event)">
</div>

<div class="col-xs-3">
<label for="Correo">Correo</label>
<input type="text" name="Correo" id="Correo" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['emailconsuc']; ?>" size="20" placeholder="Correo Electrónico" required>
</div>
</div>
<?php
if(isset($_GET['edit'])){ ?>
	<button class="btn btn-default" type="submit" style="float:right;" name="update">Actualizar</button>
	<?php
}
else{ ?>
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
<script type="text/javascript" src="../css/menuFunction.js"></script>
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

        xmlhttp.open("GET","funciones/mostrar/mostradorSucCon.php?q="+str,true);
        xmlhttp2.open("GET","funciones/nomostrar/mostrarTodoSucCon.php?q="+str,true);
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
        xmlhttp.open("GET","funciones/mostrar/mostradorSucCon.php?q="+guat,true);
        xmlhttp2.open("GET","funciones/nomostrar/mostrarTodoSucCon.php?q="+guat,true);
        xmlhttp.send();
        xmlhttp2.send();
    }
}
</script>  
</html>
<?php ob_end_flush();
} 
else{
  echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
  echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../ingresar.php'>";   
  exit;
} ?>