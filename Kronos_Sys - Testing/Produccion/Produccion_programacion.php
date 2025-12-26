<?php
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { 
    ob_start(); 

    ?>
    <!DOCTYPE>
    <html>
    <head>
        <title>Programación | Producción</title>
        <link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
        <link rel="manifest" href="../pictures/manifest.json">
        <link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="theme-color" content="#000">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
<style type="text/css">
    #cargando
    {
        z-index: 1101;
        width:100%;height:30%;top:30%;bottom:70%;position:absolute;align-items:center;
        background: rgb(25,25,25);
background: linear-gradient(90deg, rgba(25,25,25,1) 0%, rgba(25,25,25,0.4) 0%, rgba(25,25,25,0.3) 0%);
       animation: cargando 1.2s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
    }
    #cargando img
    {
        margin-top: 4%;
    }
    @keyframes cargando {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}
#txtHint
{
    height: 100%;
}
#txtHunt
{
    height: 100%;
}
.lotesDisponibles
{
    padding:5px;
}
.lotesSeleccionados
{
    padding:5px;
}
#leyendaLotes
{
    font:bold 15px Sansation;
}
</style>
    <body style="overflow:auto"> 
        <?php
        include('Controlador_produccion/db_produccion.php');
        include("../css/barra_horizontal5.php");
        include("Controlador_produccion/crud_programacion.php");
        
        //$SQL=$MySQLiconn->query("TRUNCATE pruebas");
        if(empty($_POST['fechaProduccion']))
        {
            $fch=date('Y-m-d');
        }
        else
        {
            $fch=$_POST['fechaProduccion'];
        }
        if($_GET==true)
        {
            $tipo=$_GET['tipo'];
        }
        else if(empty($tipo))
        {
            $tipo="30";
        }
        $SQL=$MySQLiconn->query("SELECT*from produccion where estado='2' and fechaProduccion='".$fch."' and idtipo='$tipo'");
        $det=$tipo;

//////////////////// Aqui se identifica si el tipo de producto requiere elementos de impresión o no///
        $cilinders=0;
        $cirels=0;
        $suaje=0;
        $MySQLiconn->query("SET @p0='".$tipo."'");
        $SOL=$MySQLiconn->query("call getJuegoProcesosbyType(@p0)");
        while($pros=$SOL->fetch_array())
        {

            if($pros['descripcionproceso']=="impresion-flexografica")
            {


                $cirels=1;
                $suaje=1;


            }
            else if($pros['descripcionproceso']=="impresion")
            {
                $cilinders=1;
            }
            else if($pros['descripcionproceso']=="suajado" )
            {
                $suaje=1;
            }
            else if($pros['descripcionproceso']=="troquelado" )
            {
                $suaje=1;
            }
            if($pros['numeroProceso']==1)
            {
                $program=$pros['descripcionproceso'];
                $_SESSION['first']=$program;
            }

        }

        $MySQLiconn->next_result();
//////////////////////////////////////////////////////////////////////
        $row=$SQL->fetch_array();
        if(!empty($row['nombreProducto']))
            include('Produccion_barraDiaria.php');
        ?>

<div id="row">

    <div class="col-md-10 col-md-offset-1" >
        
             <form method="post" name="formulary" id="formulary" role="form">
                <div class="panel panel-default">

                <div class="panel-heading" id="panelhi"><b class="titulo">Programación</b>
                </div>

               <div class="panel-body">
                         <?php /*if(isset($_GET['msj']))
        {
            echo "<div>".base64_decode($_GET['msj'])."</div>";
        } */?>
                    <?php
                    if(isset($_POST['fechaProduccion']))
                    {
                        $fchProgramacion = $_POST['fechaProduccion'];
                    }
                    else
                    {

                        $fchProgramacion = date('Y-m-d');
}/*-----------------------------------------


-------------------------FECHA-*/?>

<div  class="col-xs-3">
    <label for="fechaProduccion">Fecha: </label>
    <input type="date" class="form-control" name="fechaProduccion" id="fechaProduccion"  value="<?php echo $fchProgramacion;?>" title="Fecha de asignación" onchange="document.formulary.submit()" required>
</div>
<?php /*  -----------------------------PRODUCTO*/ ?>
<div class="col-xs-3">
    <label for="comboproductos">Producto:</label>
    <?php 
    if($det!="1"){
        ?>
        <select required onChange="showComboLoad();cargarLote(this.value)<?php if($cirels==1){ echo ",recargarComboCireles(this.value)";}else if($cilinders==1){ echo ",recargarComboCilindros(this.value)";}if($suaje==1){ echo ",recargarComboSuaje(this.value)";}?>" class="form-control" id="comboproductos" name="comboproductos" >

            <?php
            $resultado=$MySQLiconn->query("SELECT concat(producto.descripcion,' | ' ,impresiones.descripcionImpresion) as productos, impresiones.descripcionImpresion,impresiones.id from producto left join impresiones on producto.ID=impresiones.descripcionDisenio where impresiones.baja=1 && producto.baja=1 && producto.tipo='$tipo' order by producto.descripcion asc");

            ?>
            <option value="">--</option>
            <?php
            while($row=$resultado->fetch_array())
            {
                ?>
                <option value="<?php echo $row['id'];?>"><?php echo $row['productos'];?></option>
                <?php
            }
            ?>
        </select>
        <?php
    }
    else
    {
        ?> <select required onChange="showComboLoad();cargarLote(this.value)" id="comboproductos" name="comboproductos" class="form-control">

            <?php
            $resultado=$MySQLiconn->query("SELECT concat(bandaspp.identificadorBS,' | ' ,bandaspp.nombreBSPP) as productos, bandaspp.nombreBSPP as descripcionImpresion,bandaspp.idBSPP as id from bandaseguridad left join bandaspp on bandaseguridad.IDBanda=bandaspp.identificadorBS where bandaspp.baja=1 && bandaseguridad.baja=1 order by bandaspp.identificadorBSPP asc");
            ?>
            <option value="">--</option>
            <?php
            while($row=$resultado->fetch_array())
            {
                ?>
                <option value="<?php echo $row['id'];?>"><?php echo $row['productos'];?></option>
                <?php
            }
            ?>
        </select>
        <?php
    }
    ?>
</div>
<?php /*------------------

---------------CILINDROS*/?>
<?php 
if($cirels==1 || $cilinders==1){
    ?>

    <div  class="col-xs-3">

        <?php
        if($det!="1")
        {

            ?> <label for="<?php if($cilinders==1){echo  'comboCilindros';}else if($cirels==1){echo  'comboCireles';}?>"><?php if($cirels==1){ echo "Juego de cireles";} else if($cilinders==1) { echo "Juego de Cilindros:"; }?></label>
            <select class="form-control" required  class="combosMenores" <?php if($cilinders==1){echo  "name='comboCilindros' id='comboCilindros'";}else if($cirels==1){echo  "name='comboCireles' id='comboCireles'";}?>>
                <?php
////////////Si el tipo de producto es flexografia,en lugar de traerse datos de juegoscilindros,se los va a traer de juegos de cireles/////////

                if($cirels==1)
                {

                    $tabla="juegoscireles";
                    $datos="identificadorJuego as identificadorCilindro,num_dientes as proveedor";
                }
                else if($cilinders==1)
                {
                    $tabla="juegoscilindros";
                    $datos="identificadorCilindro,proveedor";
                }
                $resultado=$MySQLiconn->query("SELECT $datos from $tabla where baja=1");

                ?>
                <option value="">--</option>
                <?php
                while($row=$resultado->fetch_array())
                {
                    ?>
                    <option value="<?php echo $row['identificadorCilindro'];?>"><?php echo "[".$row['identificadorCilindro']."]".$row['proveedor'];?></option>
                    <?php
                }
                ?>
                </select><?php
            }

            ?>
        </div>
        <?php
    }
/*-------------------------


--------------------------MAQUINA*/?>
<div  class="col-xs-3">
    <label for="comboMaquinas">Maquina:</label>
    <select required id="comboMaquinas" class="form-control" name="comboMaquinas">
        <?php
        $resultado=$MySQLiconn->query("SELECT concat('[',maquinas.codigo,'] ',maquinas.descripcionMaq) as maquina,idMaq,maquinas.descripcionMaq from maquinas where baja=1 and maquinas.subproceso='$program'");
        ?>
        <option value="">--</option>
        <?php
        while($row=$resultado->fetch_array())
        {
            ?>
            <option value="<?php echo $row['idMaq'];?>"><?php echo $row['maquina'];?></option>
            <?php
        }
        ?>
    </select>
</div>
<?php
/*-----------------------------------------


--------------------------SUAJE*/

if($suaje==1)
{

    ?>
    <div  class="col-xs-3">
        <label for="comboSuaje">Suaje:</label>
        <select  class="form-control" name="comboSuaje" id="comboSuaje">
            <?php
            $resultado=$MySQLiconn->query("SELECT concat('[',suaje.identificadorSuaje,'] ',suaje.proveedor) as suaje,suaje.identificadorSuaje from suaje where baja=1 and proceso='$program'");
            ?>
            <option value="">--</option>
            <?php
            while($row=$resultado->fetch_array())
            {
                ?>
                <option value="<?php echo $row['identificadorSuaje'];?>"><?php echo $row['suaje'];?></option>
                <?php
            }
            ?>
        </select>
    </div>
    <?php
}

?>
</div>
    <button class="btn btn-info" style="float:right;" type="submit" name="save">Guardar</button>
</div>
</form>

<div  class="row">
    <div id="txtHint"  class="col-xs-5 col-md-5 col-lg-5"></div>
    <div id="txtHunt" class="col-xs-5 col-md-5 col-lg-5"></div>
</div>
</div>

<div hidden="true" id="cargando"><center><img src="../pictures/loader.gif" class="img-responsive" width="100" height="100"></center></div>

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
        
        xmlhttp.open("GET","Controlador_produccion/showReporte.php?q="+str+"&tipo=<?php echo $det?>",true);
        xmlhttp.send();
      // window.location="Produccion_programacion.php";
  }
}

//window.onload = function() {
//showComboLoad(comboTipos.value);
//mostrarTipo();
//};
function showComboLoad() {

              var guat=$('#comboproductos').val();
              var cargando = $("#cargando");
              cargando.show();
              $.ajax({
                type: "GET",
                url: "Controlador_produccion/showReporte.php",
                data: "c="+guat+"&tipo=<?php echo $det?>",
                dataType: "html",
                error: function(){
                  alert("error petición ajax");
                },
                success: function(data){                                                      
                  $("#txtHint").html(data);
                    var cargando = $("#cargando");
                    cargando.hide();
                  n();
                }
              });
}  
</script>

<script type="text/javascript">

function cargarLote(val) {
              $.ajax({
                type: "GET",
                url: "Controlador_produccion/showSeleccionados.php",
                data: "q="+val+"&tipo=<?php echo $tipo?>",
                dataType: "html",
                error: function(){
                  alert("error petición ajax");
                },
                success: function(data){                                                      
                  $("#txtHunt").html(data);
                  n();
                }
              });
}  
function cargarLote2(val) {
              $.ajax({
                type: "GET",
                url: "Controlador_produccion/showReporte.php",
                data: "e="+val+"&tipo=<?php echo $tipo?>",
                dataType: "html",
                error: function(){
                  alert("error petición ajax");
                },
                success: function(data){                                                      
                  $("#txtHint").html(data);
                  n();
                }
              });
}  
function enviarLotes(val) {
              $.ajax({
                type: "GET",
                url: "Controlador_produccion/crud_programacion.php",
                data: "q="+val,
                dataType: "html",
                error: function(){
                  alert("error petición ajax");
                },
                success: function(data){                                                      
                  $("#txtHint").html(data);
                  n();
                }
              });
}  

$(document).ready(function(){
  // tu elemento que quieres activar.
  
  // evento ajax start
  $(document).ajaxStart(function() {
    var cargando = $("#cargando");
    cargando.show();
  });

  // evento ajax stop

});
</script>
</script>
<script type="text/javascript">
    function recargarComboCilindros(val)
    {
      $.ajax({
                type: "GET",
                url: "Controlador_produccion/crud_programacion.php",
                data: "cil="+val,
                dataType: "html",
                error: function(){
                  alert("error petición ajax");
                },
                success: function(data){                                                      
                  $("#comboCilindros").html(data);
                  n();
                }
              });


}
function recargarComboCireles(val)
{
     $.ajax({
                type: "GET",
                url: "Controlador_produccion/crud_programacion.php",
                data: "cir="+val,
                dataType: "html",
                error: function(){
                  alert("error petición ajax");
                },
                success: function(data){                                                      
                  $("#comboCireles").html(data);
                  n();
                }
              });

}
function recargarComboSuaje(val)
{
     $.ajax({
                type: "GET",
                url: "Controlador_produccion/crud_programacion.php",
                data: "suj="+val,
                dataType: "html",
                error: function(){
                  alert("error petición ajax");
                },
                success: function(data){                                                      
                  $("#comboSuaje").html(data);
                  n();
                }
              });

}
document.onclick = function (e) {
   e = e || event
   var target = e.target || e.srcElement
   var elemento = document.getElementById('barra-lateral');
   do {
      if (elemento == target) {
         elemento.style.position='fixed';
         elemento.style.bottom='0';
         elemento.style.left='0';
         elemento.style.width='45%';
         elemento.style.height='100%';
         elemento.style.background='white';
         elemento.style.zIndex='1100';
         return;
     }
     target = target.parentNode;
 } while (target)
 {
     elemento.style.width='8%';
     elemento.style.height='70%';

     
 }
 // Se ha clicado fuera del elemento, se realiza una acción.
 
}
</script>
</html>
<?php
ob_end_flush();
}
else {
    echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
    include "../ingresar.php";
} ?>