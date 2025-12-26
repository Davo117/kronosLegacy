<?php
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
    ob_start();
    $_SESSION['codigoPermiso']='40002';
    include ("funciones/permisos.php");
    include ("funciones/bitacoras/contactoCB.php"); 
    include ("funciones/crud/crudContactoCliente.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Contacto Clientes(Logística) | Grupo Labro</title>
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
<?php    include("../components/barra_lateral2.php"); ?>
<div id="page-wrapper">
	<div class="container-fluid">
<form method="post" name="formulary" id="formulary">
<div class="panel panel-default">

                <div class="panel-heading"><b class="titulo">Contactos cliente</b>
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
<?php  // Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="example" class="form-control" id="example" onChange="showCombo(this.value)">
    <?php
//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM $tablacli where bajacli=1 ORDER BY ID DESC");
//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
//Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:

    if(isset($_GET['edit'])){ 
        if($getROW['idcliFK'] == $row['nombrecli']){?>

            <option value="<?php echo $getROW['idcliFK'];?>" selected> <?php echo $getROW['idcliFK'];?></option>
            <?php  
        }
        else{  //Sino manda la lista normalmente:          ?>
            <option value="<?php echo $row ['nombrecli'];?>"><?php echo $row ['nombrecli'];?></option>

            <?php 
        } 
    }
    elseif( $row['nombrecli']==$_SESSION['cliente']){ //Si fue seleccionado. en caso de editar el campo manda a llamar el nombre del "fk" sino, muestra el nombre del cliente seleccionado  ?>
        <option value="<?php echo $row['nombrecli'];?>" selected>
        <?php echo $row['nombrecli'];?>     
        </option>
        <?php 
    }
    else{   //Sino manda la lista normalmente:          ?>
        <option value="<?php echo $row ['nombrecli'];?>"><?php echo $row ['nombrecli'];?></option>
        <?php 
    } 
}  ?> 
</select>
</div>

<div class="col-xs-3">
<label for="Nombre">Nombre</label>
<input type="text" class="form-control" id="Nombre" name="Nombre" value="<?php if(isset($_GET['edit'])) echo $getROW['nombreconcli'].'" readonly="true'; ?>"  size="20" placeholder="Nombre Completo" required>
</div>

<div class="col-xs-3">
<label for="Puesto">Puesto</label>
<input type="text" id="Puesto" class="form-control" name="Puesto" value="<?php if(isset($_GET['edit'])) echo $getROW['puestoconcli'];  ?>" size="20" placeholder="Puesto" required>
</div>

<div class="col-xs-3">
<label for="Telefono">Teléfono</label>
<input type="text" class="form-control" id="Telefono" name="Telefono" value="<?php if(isset($_GET['edit'])) echo $getROW['telefonoconcli'];  ?>"size="20" placeholder="Número Telefónico" required onkeypress="return numeros(event)">
</div>

<div class="col-xs-3">
<label for="Celular">Celular</label>
<input type="text" name="Celular" id="Celular" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['movilcl'];  ?>"   size="20" placeholder="Celular" onkeypress="return numeros(event)">
</div>

<div class="col-xs-3">
<label for="Correo">Correo</label>
<input type="text" class="form-control" id="Correo" name="Correo" value="<?php if(isset($_GET['edit'])) echo $getROW['emailconcli'];  ?>" size="20" placeholder="Correo Electrónico" >
</div>
</div>
<?php
if(isset($_GET['edit'])){ 	?>
	<button class="btn btn-default" type="submit" style="float:right;" name="update">Actualizar</button>
	<?php  }
else{	?>
	<button class="btn btn-default" type="submit" style="float:right;" name="save">Guardar</button>
	<?php } ?>
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

        xmlhttp.open("GET","funciones/mostrar/mostradorContactoCliente.php?q="+str,true);
        xmlhttp2.open("GET","funciones/nomostrar/mostrarTodoContactoCliente.php?q="+str,true);
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
        xmlhttp.open("GET","funciones/mostrar/mostradorContactoCliente.php?q="+guat,true);
        xmlhttp2.open("GET","funciones/nomostrar/mostrarTodoContactoCliente.php?q="+guat,true);
        xmlhttp.send();
        xmlhttp2.send();
    }
}
</script>     
</html>
<?php ob_end_flush(); 
} 
else {
    echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
    echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../ingresar.php'>";
    exit;
} ?>
