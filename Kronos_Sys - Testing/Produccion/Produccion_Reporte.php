<?php
session_start();
error_reporting(0);
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Reporte de registros | Producción</title>
<script src="../../js/jquery-1.7.2.min.js"></script>
  <script src="../../js/colResizable-1.6.min.js"></script>
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body> 
<?php
include('Controlador_produccion/db_produccion.php');
//include("Controlador_produccion/crud_Registro.php");
include("../css/barra_horizontal5.php");
include_once('Controlador_produccion/functions.php');
//$SQL=$MySQLiconn->query("TRUNCATE lotestemporales");
//$SQL=$MySQLiconn->query("SELECT dato from cache where id=7");
echo ceil(15.012);
if(!empty($_GET['tipo']))
{
    $idtipo=$_GET['tipo'];
}
else
{
    $idtipo="Manga Termoencogible";
}
$SQL2=$MySQLiconn->query("SELECT tipo from tipoproducto where id='".$idtipo."'");
$ros=$SQL2->fetch_array();
$tipo=$ros['tipo'];
if(isset($_POST['comboProcesos']))
{
    $proceso=$_POST['comboProcesos'];
}
else
{
    $proceso="";
}

$_SESSION['etiquetasInd']="";
?>
 <div id="row">
    <div class="col-md-10 col-md-offset-1" >

     <form method="post" name="formulary" id="formulary" style="height:auto;">
              <div class="panel panel-default">

                <div class="panel-heading"><b class="titulo">Reporte de registros</b>
                </div>
               <div class="panel-body">


<?php


if(isset($_POST['cmbLapso']))
{
$fchProgramacion = $_POST['cmbLapso'];
}
else
{

$fchProgramacion = date('Y-m-d');
}
if(isset($_POST['cmbfchFinal']))
{
$fchFinal = $_POST['cmbfchFinal'];
}
else
{

$fchFinal = date('Y-m-d');
}
if($tipo!="BS")
{
?>
<div class="col-xs-3"><label for="cmbLapso">Fecha de inicio</label>
    <input title="fecha de inicio" type="date" class="form-control" id="cmbLapso" name="cmbLapso" value="<?php echo $fchProgramacion;?>" onchange="document.formulary.submit()">
</div>
<div class="col-xs-3">
<label for="cmbfchFinal">Fecha fin</label>
     <input title="Fecha final" type="date" class="form-control" style="" id="cmbfchFinal" name="cmbfchFinal" value="<?php echo $fchFinal;?>" onchange="document.formulary.submit()">
</div>
<div class="col-xs-3">
    <label for="cmbProductos">Producto</label>
    <select  class="form-control" name="cmbProductos" id="cmbProductos" title="Seleccione un producto" onchange="document.formulary.submit()">
        <option value="">Todos los productos</option>
        <?php
        $SCL=$MySQLiconn->query("
            SELECT i.descripcionImpresion,CONCAT(i.descripcionImpresion,'[',p.descripcion,']') as conc
            FROM impresiones i
            INNER JOIN producto p
            ON p.ID=i.descripcionDisenio
            WHERE i.baja=1 AND p.baja=1 AND p.tipo='".$idtipo."'");
        while( $row=$SCL->fetch_array())
        {
            if($row['descripcionImpresion']==$_POST['cmbProductos'])
            {
                 ?>
            <option selected value="<?php echo $row['descripcionImpresion'];?>"><?php echo $row['conc'];?></option>
            <?php
            }
            else
            {
                 ?>
            <option value="<?php echo $row['descripcionImpresion'];?>"><?php echo $row['conc'];?></option>
            <?php
            }
           
        }?></select><?php
    }
    else
    {?>
        <div class="col-xs-3">
            <label for="cmbLapso">Fecha inicio</label>
         <input title="fecha de inicio" type="date" class="form-control" id="cmbLapso" name="cmbLapso" value="<?php echo $fchProgramacion;?>" onchange="document.formulary.submit()">
     </div>
     <div class="col-xs-3">
        <label for="">Fecha fin</label>
           <input title="Fecha final" type="date" class="form-control" style="" id="cmbfchFinal" name="   cmbfchFinal" value="<?php echo $fchFinal;?>" onchange="document.formulary.submit()">
        </div>
    <div class="col-xs-3">
        <label for="cmbProductos"></label>
    <select class="form-control name="cmbProductos" title="Seleccione un producto" onchange="document.formulary.submit()">
        <option value="">Todos los productos</option>
        <?php
        $SCL=$MySQLiconn->query("SELECT nombreBSPP as descripcionImpresion from bandaspp where baja=1");
        while( $row=$SCL->fetch_array())
        {
            if($row['descripcionImpresion']==$_POST['cmbProductos'])
            {
                 ?>
            <option selected value="<?php echo $row['descripcionImpresion'];?>"><?php echo $row['descripcionImpresion'];?></option>
            <?php
            }
            else
            {
                 ?>
            <option value="<?php echo $row['descripcionImpresion'];?>"><?php echo $row['descripcionImpresion'];?></option>
            <?php
            }
           
        }
    }
    ?>
    </select>
</div>
    <div class="col-xs-3">
        <label for="cmbEmpleados">Empleado</label><br>
    <select name="cmbEmpleados" class="selectpicker show-menu-arrow" data-style="form-control" data-live-search="true" title="Seleccione un empleado" onchange="document.formulary.submit()">
        <option value="">Todos los empleados</option>
        <?php
        $Em=$MySQLiconn->query("SELECT concat(nombre,' ',apellido,' [',numemple,']') as nombre,concat(nombre,' ',apellido,' [',numemple,']') as numEmpleado,id from empleado where baja=1");
        while($rai=$Em->fetch_array())
        {
            if($rai['numEmpleado']==$_POST['cmbEmpleados'])
            {
                   ?>
            <option  selected data-tokens="<?php echo $rai['numEmpleado'];?>" value="<?php echo $rai['numEmpleado'];?>"><?php echo $rai['nombre'];?></option>
            <?php
            }
            else
            {
                   ?>
            <option data-tokens="<?php echo $rai['numEmpleado'];?>" value="<?php echo $rai['numEmpleado'];?>"><?php echo $rai['numEmpleado'];?></option>
            <?php
            }
         
        }
        ?>
    </select>
</div>
<div class="col-xs-3">
    <label for="comboProcesos">Proceso</label>
<select title="Seleccione el proceso" onchange="document.formulary.submit()"  class="form-control" name="comboProcesos" id="comboProcesos" >
<?php
$resultado=$MySQLiconn->query("SELECT descripcionProceso from juegoprocesos where identificadorJuego=(select juegoprocesos from tipoproducto where id='".$idtipo."') and numeroProceso!=0 and registro=1");//Extrae los procesos donde el id del producto sea igual

?>
<option value="">Todos los procesos</option>
<?php
while($row=$resultado->fetch_array())
{
    if($row['descripcionProceso']==$_POST['comboProcesos']){
?>
 <option selected value="<?php echo $row['descripcionProceso'];?>"><?php echo strtoupper($row['descripcionProceso']);?></option>
 <?php
}
else
{
    ?>
 <option value="<?php echo $row['descripcionProceso'];?>"><?php echo $row['descripcionProceso'];?></option>
 <?php
}
}

?>
</select>
</div>
</div>
<a  style="float:right;" class="btn btn-large btn-primary" target="_blank"  title="Generar reporte en PDF"  href="documentos/historicoLight.php?proceso=<?php echo $_POST['comboProcesos']."&empleado=".$_POST['cmbEmpleados']."&producto=".$_POST['cmbProductos']."&fch=".$_POST['cmbLapso']."&hstfch=".$_POST['cmbfchFinal']."&tipo=".$tipo?>"><img class="img-responsive" src="../pictures/reports.png"></a>
<a  style="float:right;" class="btn btn-large btn-success" target="_blank"  title="Generar reporte en Excel"  href="documentos/historicoExcel.php?proceso=<?php echo $_POST['comboProcesos']."&empleado=".$_POST['cmbEmpleados']."&producto=".$_POST['cmbProductos']."&fch=".$_POST['cmbLapso']."&hstfch=".$_POST['cmbfchFinal']."&tipo=".$tipo?>&tipo=<?php echo $tipo;?>"><img class="img-responsive" src="../pictures/excel.png"></a>
</form>
</div>

<?php
include("Controlador_produccion/showReporteRegistros.php");
?>
</body>
 <script type="text/javascript" src="../css/menuFunction.js"></script>
<script type="text/javascript">
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
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        
        xmlhttp.open("GET","Controlador_produccion/showReporte.php?q="+str,true);
        xmlhttp.send();
      // window.location="Produccion_programacion.php";
    }
}

window.onload = function() {
//showComboLoad(comboTipos.value);
//mostrarTipo();
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
      //  var combo = document.getElementById('comboproductos');
//alert('Esta enviando el parametro'+ ' '+combo.options[combo.selectedIndex].value);
        xmlhttp.open("GET","Controlador_produccion/showReporteRegistros.php?d="+guat,true);
        xmlhttp.send();
    }
}  
</script>
<script type="text/javascript" language="JavaScript">
function mostrarTipo(){

    var x= document.getElementById("comboTipos").value;
    var z=document.getElementById("comboProcesos").value;
    //var pagina="Producto_Impresiones.php";
    //function redireccionar() 
    //{
var combo = document.getElementById('comboTipos');
var combo2=document.getElementById('comboProcesos');
    //alert('La opción seleccionada es: '+combo.options[combo.selectedIndex].value);
    //location.href=pagina;
    
    //setTimeout ("redireccionar()", 20000);
    if(combo2.options[combo2.selectedIndex].value!=0)
    {
       // document.getElementById("TitProg").innerHTML="Reporte de registros: "+" "+combo2.options[combo2.selectedIndex].value;
        document.getElementById("TitProg").innerHTML="Reporte de registro";

    }
    else
    {
        document.getElementById("TitProg").innerHTML=combo.options[combo.selectedIndex].value;
    }
    
     if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
               
            }
        };
        xmlhttp.open("GET","Controlador_produccion/showReporte.php?e="+combo.options[combo.selectedIndex].value,true);
        xmlhttp.send();
       // window.location="Produccion_programacion.php";


}
</script>
<script language="JavaScript">
  function redireccionar() {

    setTimeout('history.back()',10000);
  }
  
    $("#RepTable").colResizable({
    liveDrag:true, 
    draggingClass:"rangeDrag", 
    gripInnerHtml:"<div class='rangeGrip'></div>", 
    onResize:onSlide,
    minWidth:8
}); 


var onSlider = function(e){
    var columns = $(e.currentTarget).find("td");
    var ranges = [], total = 0, i, s = "Rangos: ", w;
    for(i = 0; i<columns.length; i++){
        w = columns.eq(i).width()-10 - (i==0?1:0);
        ranges.push(w);
        total+=w;
    }        
    for(i=0; i<columns.length; i++){            
        ranges[i] = 100*ranges[i]/total;
        carriage = ranges[i]-w
        s+=" "+ Math.round(ranges[i]) + "%,";           
    }       
    s=s.slice(0,-1);        
    $("#RepTable").html(s);
}

  
  </script>
</html>