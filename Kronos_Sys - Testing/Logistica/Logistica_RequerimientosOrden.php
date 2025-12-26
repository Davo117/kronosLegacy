<?php

session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
  ob_start();
  header("Content-Type: text/html;charset=utf-8");	
  $codigoPermiso='22'; ?>

<!DOCTYPE>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Requerimientos por Orden(Logística) | Grupo Labro</title>
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
<?php    include("../components/barra_lateral2.php"); 
include ("funciones/bitacoras/requerimientoB.php"); 
  include("funciones/crud/crudOrdenReq.php");?>
<div id="page-wrapper">
	<div class="container-fluid">
<form method="post" name="formulary" id="formulary" role="form">

<div class="panel panel-default">
  <div class="panel-heading"><b class="titulo">Requerimientos de Producto por Orden de Compra</b></div>
<div class="panel-body">
  <div class="col-xs-3">
    <label for="example">Orden Compra</label>
  <?php // Creamos un select para traer el nombre del cliente:
  //El atributo name sirve para utilizar los option en el crud ?>
  <select name="example1" data-live-search="true"  class="selectpicker show-menu-arrow form-control" id="example" onChange="myCombo(this.value)">
  <option value=''>Seleccionar Orden</option>
  <?php //Seleccionar todos los datos de la tabla 
  $resultado = $MySQLiconn->query("SELECT idorden, orden, nombresuc FROM $tablaOrden, $tablasucursal where bajaOrden=1 and $tablasucursal.idsuc=$tablaOrden.sucFK && bajasuc=1 order by idorden desc");

//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
  //Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:
  if(isset($_GET['edit'])){ 
    if($getROW['ordenReqFK'] == $row['idorden'] ){ ?>
      <option value="<?php echo $row['idorden'];?>" selected> 
      <?php echo $row['nombresuc']."| ". $row['orden']; ?>
      </option> <?php  
    }
  }
  elseif($row['idorden']==$_SESSION['orden']){ //Si fue seleccionado. en caso de editar el campo manda a llamar el nombre del "fk" sino, muestra el nombre del cliente seleccionado   ?>
    <option value="<?php echo $row['idorden'];?>" selected>
    <?php echo $row['nombresuc']." | " .$row['orden'];?> 
    </option><?php 
  }
  else{   //Sino manda la lista normalmente: ?>
    <option value="<?php echo $row['idorden'];?>">
    <?php echo $row['nombresuc']." | " .$row['orden'];?>
    </option> <?php 
  }
}?> 
</select>
</div>

<div class="col-xs-3">
<label for="combillo">Producto Cliente</label>
<?php /* Creamos un select para traer el nombre del cliente:
El atributo name sirve para utilizar los option en el crud
 Si se edita, se deshabilita el select para que no se les ocurra moverle (aunque no pasaria nada si le movieran, por que ya esta omitido el guardar este select en la base de datos.)*/?>
<select name="producto" id="combillo" required data-live-search="true"  class="selectpicker show-menu-arrow form-control" <?php if(isset($_GET['edit'])){ echo "disabled >"; }else {
  echo "> <option value=''>Seleccionar Diseño</option>";
} ?>

<?php //Seleccionar todos los datos de la tabla 

$retener = $MySQLiconn->query("SELECT idReq, prodcliReqFK FROM $reqProd where bajaReq='1' && ordenReqFK='".$_SESSION['orden']."' order by prodcliReqFK ASC");

$i=0;
$factor='';
while ($rowR=$retener->fetch_array()){
  if(isset($_GET['edit']) && $_GET['edit']==$rowR['idReq']){
    $factor="&& IdProdCliente='".$rowR['prodcliReqFK']."'"; 
    break;
  }
  else{ 
    if ($i=='0') { $factor=$factor."&& IdProdCliente!='".$rowR['prodcliReqFK']."'";  }
    else {  $factor=$factor." && IdProdCliente!='".$rowR['prodcliReqFK']."'";  }
    $i++;
  }
}
$resultado=$MySQLiconn->query("SELECT * FROM $prodcli where baja='1' $factor  order by nombre ASC");
//mientras se tengan registros:
while ($row = $resultado->fetch_array()) { //Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:?>
      <option value="<?php echo $row['IdProdCliente'];?>"> [<?php echo $row['IdentificadorCliente'];?>] <?php echo $row ['nombre'];?></option>
      <?php
  }?>
</select></p>
</div>

<div class="col-xs-3">
<label for="cantidad">Cantidad</label>
<input type="text" name="cantidad" id="cantidad" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['cantReq'];  ?>"  size="20" placeholder="millares" required onkeypress="return numeros(event)">
</div>

<div class="col-xs-3">
<label for="referencia">Referencia</label>
<input type="text" name="referencia" id="referencia"  class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['refeReq'];  ?>" size="20" placeholder="Referencia" ></p>
</div>
  </div>

<?php if(isset($_GET['edit'])){	?>
	<button class="btn btn-default" type="submit" style="float:right;" name="update">Actualizar</button>
	<?php
}
else{  ?>
	<button class="btn btn-default" style="float:right;" type="submit" name="save">Guardar</button>
	<?php 
}?>
</form>
</div>
<div id="txtHint" class="table-responsive"></div>

</div>
</div>
</body>
<script src="funciones/ajax.js"></script>
<script type="text/javascript" language="JavaScript">

function myCombo(str){
  //le pasamos el valor a la siguiente funcion
  mia(str);
  loadDoc("r="+str,"comboboxR.php",function(){
    if (xmlhttp.readyState==4 && xmlhttp.status==200){
      document.getElementById("combillo").innerHTML=xmlhttp.responseText;
    }
  });
}
window.onload = function() {
  mia(example.value);
};

function mia(str){
  $('#txtHint').load('funciones/mostrar/mostradorOrdenReq.php?q='+str);
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