<?php
session_start();
  if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
    ob_start();
    $_SESSION['codigoPermiso']='80005';
    include ("funciones/permisos.php"); 
    include("Controlador_Misce/db_Producto.php");
    include("Controlador_Misce/crud_procesosPP.php");    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Procesos| Misceláneos</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">
     <link rel="stylesheet" href="funciones/estilo.css" type="text/css" media="screen"/>
  <script type="text/javascript">

    function allowDrop(ev){
      ev.preventDefault();
    }
    function drag(ev){
      ev.dataTransfer.setData("text",ev.target.id);
    }
    function drop(ev){
      ev.preventDefault();
      var data= ev.dataTransfer.getData("text");
      ev.target.appendChild(document.getElementById(data));
      document.getElementById('drag').setAttribute('draggable', 'false');
    }
  </script>
</head>

<body> 
<?php    include("../components/barra_lateral2.php");   ?>
<div id="page-wrapper">
  <div class="container-fluid">
<form method="POST" name="formulary" id="formulary" role="form">
<div class="panel panel-default">

        <div class="panel-heading"><b class="titulo">Procesos por tipo de producto</b>
</div>

<div class="panel-body">

<div class="col-xs-3">
<label for="example">Producto:</label>
<select onChange="showCombo(this.value)" name="example" id="example" class="form-control">
<?php $resultado=$MySQLiconn->query("SELECT * from tipoproducto where baja=1"); ?>
<option value="">--</option>
<?php
while($row=$resultado->fetch_array()){   
  
    if( $row['juegoProcesos']==$_SESSION['procesoProd']){ //Si fue seleccionado. en caso de editar el campo manda a llamar el nombre del "fk" sino, muestra el nombre del cliente seleccionado  ?>
       <option value="<?php echo $row['juegoProcesos'];?>" selected>
       <?php echo $row['tipo'];?>       
        </option>
       <?php 
    }

    else{   //Sino manda la lista normalmente: ?>
        <option value="<?php echo $row['juegoProcesos'];?>"><?php echo $row ['tipo'];?></option>
        <?php 
    }
} ?>
</select>

</div>

</div>
<p class="bg-info">Arrastre el proceso del cuadro superior y coloquelo en los recuadros de la parte inferior</p>
</form>
</div>
<a href="Misce_ProcesosOnly.php" class="btn btn-success">Ir a procesos</a>
<div id="txtHint"></div>
</div>
</div>>
</body>
<script type="text/javascript" src="../css/menuFunction.js"></script>
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
    xmlhttp.open("GET","funciones/prueba.php?q="+str,true);
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
    xmlhttp.open("GET","funciones/prueba.php?q="+guat,true);
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