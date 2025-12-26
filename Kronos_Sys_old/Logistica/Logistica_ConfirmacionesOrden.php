<?php
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
  ob_start();  
  $_SESSION['codigoPermiso']='40007';
  include ("funciones/permisos.php");
  include ("funciones/bitacoras/confirmarB.php");
  include("funciones/crud/crudOrdenConfi.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Confirmaciones(Logística) | Grupo Labro</title>
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
<?php  include("../components/barra_lateral2.php"); ?>
  
<div id="page-wrapper">
<div class="container-fluid">
<form method="post" name="formulary" id="formulary" role="form">
<div class="panel panel-default">

                <div class="panel-heading"><b class="titulo">Confirmaciones de producto por orden compra</b>
                   </div>
                        <div class="panel-body">
             
<h4 id="demo">Orden:</h4>

<div class="col-xs-3">
<label for="mySelect">Orden Compra</label>
<?php // Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="orden" id="mySelect" class="form-control" onChange="myFunction(this.value); otro();">
  <option value="">Seleccionar Orden de Compra</option>
<?php  //Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT idorden, orden, nombresuc FROM $tablaOrden, $tablasucursal where bajaOrden='1' and $tablasucursal.nombresuc =$tablaOrden.sucFK order by idorden desc");

//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
  //Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:
  if(isset($_GET['edit'])){ 
    if($getROW['ordenConfi'] == $row['orden'] ){    ?>
      <script type="text/javascript">
      document.getElementById("demo").innerHTML = "Orden: <?php echo $row['ordenConfi']; ?> ";
      </script>
      <option value="<?php echo $getROW['ordenConfi'];?>" selected> 
      <?php echo $getROW['ordenConfi']."| ". $row['nombresuc']; ?>
      </option> <?php
    }
  }
  elseif(isset($_SESSION['orden'])){
    if( $row ['orden']==$_SESSION['orden']){
      //Si fue seleccionado. en caso de editar el campo manda a llamar el nombre del "fk" sino, muestra el nombre del cliente seleccionado  ?>

      <script type="text/javascript">
      document.getElementById("demo").innerHTML = "Orden: <?php echo $_SESSION['orden']; ?> ";
      </script>
      <option value="<?php echo $row ['orden'];?>" selected>
      <?php echo $row['nombresuc']." | " .$row['orden'];?>
      </option> <?php
    }
    else{ //Sino manda la lista normalmente:      ?>
     <option value="<?php echo $row ['orden'];?>">
      <?php echo $row['nombresuc']." | " .$row['orden'];?>
      </option> <?php
    }
  }
  else{ //Sino manda la lista normalmente:      ?>
    <option value="<?php echo $row ['orden'];?>">
    <?php echo $row['nombresuc']." | " .$row['orden'];?>
    </option><?php
    }
}  ?>
</select>
</div>

<div class="col-xs-3">
<label for="combillo">Producto</label>
<select id="combillo" name="producto" class="form-control" required >
<option value="" >Seleccionar Producto</option>
  <?php  
  $primera = $MySQLiconn->query("SELECT  prodcliReqFK, ordenReqFK FROM $reqProd ");
  //Mientras que existan productos en la tabla de requerimientos...
while($row1 = $primera->fetch_array()){
  $segunda = $MySQLiconn->query("SELECT distinct descripcionImpresion, descripcionDisenio FROM $impresion where codigoCliente='".$row1['prodcliReqFK']."' && baja=1");
  
  // Inicializamos una variable para agrupar en el combobox
  $empresa='ssss';
  //Mientas que todos los productos distintivos de los diseños de la tabla de impresion coincidan con el producto de la primer consulta :
  while($row2 = $segunda->fetch_array()){ 
    $agrupar = $MySQLiconn->query("SELECT distinct IdentificadorCliente from productoscliente where nombre='".$row1['prodcliReqFK']."' and baja=1");
    //si los distintivos campos de los productos clientes coinciden con el producto de la primer consulta 
    $rowAgrupar = $agrupar->fetch_array();
    // Comparas tu variable con la empresa
    if($empresa != $rowAgrupar['IdentificadorCliente']) { 
      // Ahora habra que comparar con esta empresa cada que de vuelta los datos 
      $empresa = $rowAgrupar['IdentificadorCliente'];      
      //Si viene de otro modulo como Orden de Compra o Requerimiento :
      if (isset($_POST['comboConf'])){
        if( $row1 ['ordenReqFK']==$_POST['comboConf']) { ?>
          <optgroup label="<?php echo $empresa; ?>">
          <option  value="<?php echo $row2['descripcionImpresion'];?>"><?php echo $row2 ['descripcionDisenio']." | " .$row2['descripcionImpresion'];?> </option>  <?php
        }
      }
      else{
        //Si ya selecciono una orden dentro de este modulo: 
        if(isset($_SESSION['orden'])){
          if( $row1 ['ordenReqFK']==$_SESSION['orden']) { ?>
            <optgroup label="<?php echo $empresa; ?>">
            <option  value="<?php echo $row2['descripcionImpresion'];?>"><?php echo $row2 ['descripcionDisenio']." | " .$row2['descripcionImpresion'];?> </option><?php 
          }
        }
      }
    }
  }
  echo "</optgroup>";
} ?> 
</select>
</div>

<div class="col-xs-3">
<label for="empaque2">Empaque</label>
<select name="empaque" list="exampleList2" required id="empaque2" class="form-control">
    <option value="">Seleccionar Empaque</option>
  <?php //Seleccionar todos los datos de la tabla 
    $resultado = $MySQLiconn->query("SELECT * FROM empaque ");
    //mientras se tengan registros:
while ($row = $resultado->fetch_array()) {  ?> 
  <option  value="<?php echo $row ['nameEm'];?>"><?php echo $row['nameEm'];?></option>
  <?php 
} ?> 
</select>
</div>

<div class="col-xs-3">
<label for="cantidad">Cantidad</label>
<input type="text" name="cantidad" id="cantidad" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['cantidadConfi'];  ?>"  size="20" placeholder="millares" required onkeypress="return numeros(event)">
</div>

<div class="col-xs-3">
<label for="referencia">Referencia</label>
<input type="text" name="referencia" id="referencia" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['referenciaConfi'];  ?>" size="20" placeholder="Referencia">
</div>

<div class="col-xs-3" >
<label for="dateEmbar">Fecha Embarque</label>
<input type="date" name="dateEmbar" id="dateEmbar" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['embarqueConfi'];  ?>" size="20" required>
</div>

<div class="col-xs-3">
<label for="dateEntre">Fecha Entrega</label>
<input type="date" name="dateEntre" id="dateEntre" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['entregaConfi'];  ?>"size="20" required>
</div>
</div>
<?php
if(isset($_GET['edit'])) { ?>
  <button class="btn btn-default" type="submit" style="float:right;" name="update">Actualizar</button> <?php
}
else{  ?>
  <button class="btn btn-default" type="submit" style="float:right;" name="save">Guardar</button> <?php
} ?>
</form>
</div>
  <div id="txtHint" class="table-responsive"></div>
  </div>
</div>
</body>
<script src="funciones/ajax.js"></script>
<script type="text/javascript" src="../css/menuFunction.js"></script>
<script type="text/javascript" language="JavaScript">
// Esta funcion solo aplica en este modulo
function otro(){
  //asignamos a X el valor que tendra el combobox 
  var x = document.getElementById("mySelect").value;
  // al id demo (que es texto) se le concatenara un string junto con lo que almacena X
  document.getElementById("demo").innerHTML = "Orden: " + x;
}


//Esta funcion servira para que el segundo combobox sea dinamico y responsivo dependiendo de los datos del primer combobox
function myFunction(str){
  //le pasamos el valor a la siguiente funcion
  mia(str);
  loadDoc("r="+str,"combobox.php",function(){
    if (xmlhttp.readyState==4 && xmlhttp.status==200){
      document.getElementById("combillo").innerHTML=xmlhttp.responseText;
    }
  });
}

//Obteniendo ya el valor del primer combobox servira para mostrar la tabla relacionada
function mia(str){
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
    xmlhttp.open("GET","funciones/mostrar/mostradorOrdenConfir.php?q="+str,true);
    xmlhttp.send();
  }
}
window.onload = function() {
  showComboLoad(mySelect.value);
};

function showComboLoad(guat) {
  if(guat == "") {
    document.getElementById("txtHint").innerHTML = "";
    return;
  } 
  else{ 
    if(window.XMLHttpRequest) {
      // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp = new XMLHttpRequest();
    } 
    else{    // code for IE6, IE5
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("txtHint").innerHTML = this.responseText;
      }
    };
    xmlhttp.open("POST","funciones/mostrar/mostradorOrdenConfir.php?q="+guat,true);
    xmlhttp.send();
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