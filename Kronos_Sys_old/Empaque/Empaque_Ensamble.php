<?php
session_start();
	if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
		?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ensamble | Empaque</title>
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">

</head>
<body> 
<?php
include("../components/barra_lateral2.php");
include("Controlador_empaque/crud_Empaque.php");
include("Controlador_empaque/db_Producto.php");
//include("../css/barra_horizontal8.php");

//$SQL=$MySQLiconn->query("TRUNCATE pruebas");

//$Ce=$MySQLiconn->query("SELECT dato from cache where id=8");
//$re=$Ce->fetch_object();
if(isset($_GET['comboEmpaque']))
{
	$empaque=$_GET['comboEmpaque'];

}
else
{
	$empaque="rollo";
}

$_SESSION['empaqueActual'] =$empaque;
$_SESSION['etiquetasInd']="";
?>
<div id="page-wrapper">
	<div class="container-fluid">
<form onsubmit="cargarPaquetes();" name="formularius" role="form">
<div class="panel panel-default">

				<div class="panel-heading"  style="overflow:hidden;"><b class="titulo"><?php echo strtoupper($empaque);?></b><a style="float:right" class="btn btn-large btn-primary" data-toggle="confirmation"
	title="Vaciar contenido" href="Controlador_empaque/crud_Empaque.php?del=yes"  onClick="return confirm('¿Desea vaciar el contenido?');" name="find"><img src="../pictures/deletProducto.png"></img></a>
				</div>
<div class="panel-body">
<div class="col-xs-3">
<label for="comboEmpaque">Tipo de empaque</label>
<select  class="form-control" name="comboEmpaque" title="Seleccione el tipo de empaque" id="comboEmpaque" onChange="document.formularius.submit()">
	<?php 
	if($empaque=="rollo")
	{
		?>
		<option value="rollo" selected>rollo</option>
<option value="caja">caja</option>
<?php
	}

	else if($empaque=="caja")
	{
		?>
		<option value="rollo">rollo</option>
<option value="caja" selected>caja</option>
<?php
	}
	?>

</select>
</div>
<div class="col-xs-3">
<label for="code">Paquete/Rollo</label>
<input class="form-control" onkeypress="return numeros(event)" required autofocus  type="text" id="code" title="Ingresa el codigo de barras del paquete o rollo" name="codigo" placeholder="escanee el código">
</div>
	<a style="float:right;" class="btn btn-large btn-primary" data-toggle="confirmation"
        data-btn-ok-label="Continue" data-btn-ok-class="btn-success"
        data-btn-ok-icon-class="material-icons" data-btn-ok-icon-content="check"
        data-btn-cancel-label="Stoooop!" data-btn-cancel-class="btn-danger"
        data-btn-cancel-icon-class="material-icons" data-btn-cancel-icon-content="close"
	title="Registrar el empaque con el contenido agregado" href="Controlador_empaque/crud_Empaque.php?envio=<?php echo $_SESSION['empaqueActual'];?>"  onClick="return confirm('¿Desea continuar?');" name="find"><img src="../pictures/box.png"></img></a>
	

</form>

</div>
<hr>
<div id="paquetines" class="panel panel-default" style="overflow:hidden;">
	<div class="panel-heading" >
<b class="ordenMedio"><?php echo "Codigos agregados:  ";?></b><b><?php echo count($_SESSION['array']);?></b></div><?php
if(!empty($_SESSION['estatus'][0]))
{
	if($_SESSION['estatus'][0]=='*')
{
?><div class='alert alert-success alert-dismissible fade in' style="display: inline-block;">
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Operación exitosa</strong> ,<<?php echo $_SESSION['estatus'];?>
		</div>

<?php
}
 
else 
{
?><div class='alert alert-danger alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Error</strong> ,<?php echo $_SESSION['estatus']?>
		</div>
		<?php
} 
}
?>
</b>
</p>

<?php
for($i=0;$i<count($_SESSION['array']);$i++)
  {
    ?>
      <p class="paquetes"><?php echo $_SESSION['array'][$i];?></p>
      <?php
      $comparador=$i;
  }	
  
  ?>
	</div>
</div>
</body>
<script type="text/javascript">

	window.onload = function() {
	//recargardiv();
};
	function showCombo(empaque)
	{

		if(empaque=="")
		{
			document.getElementById("paquetines").innerHTML = "";
			return
		}
		else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        
        
        xmlhttp.open("GET","Controlador_empaque/crud_Empaque.php?q="+empaque,true);
        xmlhttp.send();
        
    }
}
function cargarPaquetes()
{
	var x=document.getElementById("code").value;
	if(x=="")
		{
			document.getElementById("paquetines").innerHTML = "";
			return
		}
		else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("paquetines").innerHTML = this.responseText;
            }
        };
        
        
        xmlhttp.open("GET","Controlador_empaque/crud_Empaque.php?e="+x,true);
        xmlhttp.send();

    }
}

function recargardiv() {
      var refreshId =  setInterval( function(){
    $('#paquetines').load("Controlador_empaque/show.php");//actualizas el div
   }, 500 );
};
function registrarEmpaque()
{
	var z="0";
	 if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
	xmlhttp.open("GET","Controlador_empaque/crud_Empaque.php?z="+x,true);
        xmlhttp.send();
}
	
</script>
<script type="text/javascript" src="../css/teclasN.js"></script>
<script type="text/javascript" src="../css/menuFunction.js"></script>
</html>
<?php 
} else {
	  echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
	  echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../ingresar.php'>";
	exit;
	} ?>