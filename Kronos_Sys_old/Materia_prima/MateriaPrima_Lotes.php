<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Lotes | Materia Prima</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">
      <script src="../jquery-3.2.1.min.js"></script>
</head>

<body> 
<?php

include("../components/barra_lateral2.php");
include("Controlador_Bloques/crud_lotes.php");
//$MySQLiconn->query("TRUNCATE pruebas");

?>
<?php 
error_reporting(0); // xdxd
if(isset($_GET['msj']))
{
  if($_GET['msj']=="success")
  {
    if($_GET['type']==1)
    {
       $texto="Los lotes :<strong> ".$_GET['cdgs']."</strong> Han sido liberados exitosamente";
    $mensaje="<div class='alert alert-success alert-dismissible fade in'>
    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
    <strong>Operación exitosa</strong> ,".$texto."
    </div>
    ";
    }
    else if($_GET['type']==2)
    {
      $mensaje="<div class='alert alert-success alert-dismissible fade in'>
    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
    <strong>Operación exitosa</strong> , Lote retornado
    </div>
    ";
    }
   
  }
  else if($_GET['msj']=="warning")
  {
     $mensaje="<div class='alert alert-warning alert-dismissible fade in'>
    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
    <strong>Error de permisos</strong> , Tu usuario no tiene los permisos para realizar esta acción.
    </div>
    ";
  
  }
  else if($_GET['msj']=="danger")
  {
     $mensaje="<div class='alert alert-danger alert-dismissible fade in'>
    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
    <strong>Error</strong> , No se puede realizar la operación porque este lote está siendo manipulado en otro módulo.
    </div>
    ";
  }
  echo $mensaje;
}
?>
<div id="page-wrapper">
  <div class="container-fluid">

<form method="post" name="formulary" id="formulary" role="form">
<div class="panel panel-default">

        <div class="panel-heading"><b class="titulo">Lotes de materia prima</b>
</div>
            <div class="panel-body">
              
  <div class="col-xs-3">
 <label for="comboBloquesillos">Bloque</label>
  <select onChange="showCombo(this.value)"  class="form-control" name="comboBloquesillos" id="comboBloquesillos">
    <?php
include ("Controlador_Bloques/db_materiaPrima.php");
$reslt = $MySQLiconn->query("SELECT dato from cache where id=5");
$rau = $reslt->fetch_array();
$bloqueDescripcion=$rau['dato'];
$resultado = $MySQLiconn->query("SELECT * FROM sustrato where baja=1");

while ($row = $resultado->fetch_array()) {

	if( $row ['descripcionSustrato']==$bloqueDescripcion)
{
	?>
	<option value="<?php echo $row ['descripcionSustrato'];?>" selected><?php echo $row['descripcionSustrato'];?></option>
	<?php
}
	else{
		?>
<option value="<?php echo $row ['descripcionSustrato'];?>"> <?php echo $row['descripcionSustrato'];?></option>
<?php
}
}
?>
</select>
</div>
<div class="col-xs-3">
<label for="referenciaLote">Lote:</label>
<input type="text" id="referenciaLote" class="form-control" required autocomplete="off" id="referenciaLote" name="referenciaLote" value="<?php if(isset($_GET['edit'])) echo $getROW['referenciaLote'].'" readonly="true';  ?>"
    size="20" placeholder="Dato único">
    <div id="resultado">
    	
    </div>
</div>

<div class="col-xs-3">
<label for="longitud">Longitud</label>
<input type="text" required name="longitud" id="longitud" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['longitud'];?>"
    size="20" placeholder="Metros">
</div>

<div class="col-xs-3">
<label for="peso">Peso</label>
<input type="text" name="peso" id="peso" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['peso'];?>"
    size="20" placeholder="Kgs">
</div>

<div class="col-xs-3">
<label for="tarima">Tarima</label>
<input required type="text" name="tarima" id="tarima" class="form-control"  value="<?php if(isset($_GET['edit'])) echo $getROW['tarima'].'" readonly="true';  ?>"
    size="20" placeholder="Referencia de la tarima">
</div>
</div>
  
<?php
if(isset($_GET['edit']))
{
	
	?>
	<button style="float:right;" class="btn btn-default"  type="submit" name="update">Actualizar</button>
	<?php
}
else
{
	?>
	<button  style="float:right;" class="btn btn-default"  type="submit" name="save">Guardar</button>
	<?php
}
?>
</form>
</div>
<h4 class="ordenMedio">Lotes por tarima</h4>
<?php

include ("Controlador_Bloques/db_materiaPrima.php");
//Contar registros:

//
$MySQLiconn->query("UPDATE lotes set shower=1");

$resaltado= $MySQLiconn->query("SELECT tarima from lotes where  bloque='".$bloqueDescripcion."'");
$rowes = $resaltado->fetch_array();
$rowe_cnt=$resaltado->num_rows;
printf("<p id='mostrar'>Se muestran: ".$rowe_cnt." Registros Activos</p>");
$resaltado->close(); 
//
$resultado = $MySQLiconn->query("SELECT count(*),tarima from lotes where  bloque='".$bloqueDescripcion."' group by tarima");
$count= mysqli_fetch_array($resultado);
$row_cnt=$resultado->num_rows;

$num=$row_cnt;
for ($i=0;$i<$num;$i++) {	?>


	<form method="post" name="<?php echo 'tabla'.$i; ?>" role="form">
			
<br>	
<?php
//Selecciona las tarimas faltantes que aun tengan el campo "shower" en "1"
if($row_cnt<=1){
	$resultado = $MySQLiconn->query("SELECT tarima from lotes where  bloque='".$bloqueDescripcion."' order by tarima asc");

	$cant= mysqli_fetch_array($resultado);
	$_SESSION['tarima']=$cant[0];
	include ("Controlador_Bloques/showLotes.php");
}
else{
	$resultado = $MySQLiconn->query("SELECT tarima from lotes where  bloque='".$bloqueDescripcion."' and tarima !='".$_SESSION['tarima']."' and shower=1 order by tarima asc");

	$cant= mysqli_fetch_array($resultado);
	$_SESSION['tarima']=$cant[0];
	$MySQLiconn->query("UPDATE lotes set shower=0 where tarima='$cant[0]'");
	
	include ("Controlador_Bloques/showLotes.php");
}
?> </form><?php
}
?>

</div>
</div>
</body>
	 <script type="text/javascript" src="../css/menuFunction.js"></script>
<script>
function showCombo(str) {
    if (str == "") {
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
        xmlhttp.open("GET","Controlador_Bloques/showLotes.php?q="+str,true);
        xmlhttp.send();
       window.location="MateriaPrima_Lotes.php";
    }
}
</script>
<script>
window.onload=function(){
var pos=window.name || 0;
window.scrollTo(0,pos);
}
window.onunload=function(){
window.name=self.pageYOffset || (document.documentElement.scrollTop+document.body.scrollTop);
}




 function seleccionar_todo(event){
	x= event;
    for (i=0;i<x.elements.length;i++) 
    	if(x.elements[i].type == "checkbox") 
        	x.elements[i].checked=1;
  }

function deseleccionar_todo(event){ 
   x= event;
   for (i=0;i<x.elements.length;i++) 
      if(x.elements[i].type == "checkbox")  
         x.elements[i].checked=0;
}

</script>
</html>
  <?php
        ob_end_flush();
      } else {
        echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
        include "../ingresar.php";
      }

      ?>
<script type="text/javascript">
	$(document).ready(function(){
                         
      var consulta;
             
      //hacemos focus
      $("#referenciaLote").focus();
                                                 
      //comprobamos si se pulsa una tecla
      $("#referenciaLote").keyup(function(e){
             //obtenemos el texto introducido en el campo
             consulta = $("#referenciaLote").val();
                                      
             //hace la búsqueda
             $("#resultado").delay(100).queue(function(n) {      
                                           
                  $("#resultado").html('<img src="cargando.gif" />');
                                           
                        $.ajax({
                              type: "POST",
                              url: "Controlador_Bloques/crud_lotes.php",
                              data: "b="+consulta,
                              dataType: "html",
                              error: function(){
                                    alert("error petición ajax");
                              },
                              success: function(data){                                                      
                                    $("#resultado").html(data);
                                    n();
                              }
                  });
                                           
             });
                                
      });
                          
});
</script>