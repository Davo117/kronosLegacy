<?php
session_start();
	if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
		?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Producción | Unión BS</title>
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">
<link rel="stylesheet" type="text/css" href="../css/Stylish.css" />
<link rel="stylesheet" href="../css/StyleTablas.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/stylees.css" type="text/css" media="screen"/>
   <link rel="stylesheet" href="../css/EmpaqueCSS.css" type="text/css" media="screen"/>

</head>
<body> 
<?php
include("../components/barra_lateral2.php");
include("Controlador_produccion/crud_unionBS.php");
include("Controlador_produccion/db_produccion.php");
include("../css/barra_horizontal5.php");

?>
<center>
<div id="formularius">
<form onsubmit="fusionarBandas();">
<p style="font-size:25px;font-family: Sansation Light;padding-right:500px;padding-top:10px;padding-bottom:5px;border-bottom: 5px solid #476F89;"   id="titulo">Unión de banda de seguridad<b></p><br>
</p>
<input onkeypress="return numeros(event)" required autofocus style="margin-top:15px;color:black" type="text" id="code" title="Ingresa el codigo de barras del disco" name="codigo" placeholder="escanee el código">
<div style="float:right;" class="fields">
<p>Seleccione el empleado</p>
 <select name="cmbEmpleados" id="cmbEmpleados" class="inputss" required title="Seleccione un empleado">
        <option value="">--</option>
        <?php
        $Em=$MySQLiconn->query("SELECT concat('[',numemple,']',nombre,' ',apellido) as nombre,concat(nombre,' ',apellido,'|',numemple) as numEmpleado from empleado");
        while($rai=$Em->fetch_array())
        {
          
                   ?>
            <option  selected value="<?php echo $rai['numEmpleado'];?>"><?php echo $rai['nombre'];?></option>
            <?php
            
        }
        ?>
    </select>
</div>


	<a title="Fusionar los discos" href="Controlador_produccion/crud_unionBS.php?envio=X" style="height:30px;width:80px;float:left;margin-right:30px;background-color:#F9ABAB;width:4%;border-radius:3px;" onClick="return confirm('¿Desea continuar?');" name="find"><img src="../pictures/link.png"></img></a>

</p><br>

</form>

</div>

<div id="paquetines">

	</div>
</center>
</body>
<script type="text/javascript">

	window.onload = function() {
	recargardiv();
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
function fusionarBandas()
{
	var x=document.getElementById("code").value;
    var emp=document.getElementById("cmbEmpleados").value;
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
        
        
        xmlhttp.open("GET","Controlador_produccion/crud_unionBS.php?e="+x+"&cmbEmpleados="+emp,true);
        xmlhttp.send();

    }
}

function recargardiv() {
      var refreshId =  setInterval( function(){
    $('#paquetines').load("Controlador_produccion/show.php");//actualizas el div
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
	xmlhttp.open("GET","Controlador_produccion/crud_unionBS.php?z="+x,true);
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