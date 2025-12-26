<?php
session_start();
if(!empty($_SESSION['rol']) && !empty($_SESSION['nombre']) && !empty($_SESSION['logan'])){
    if ($_SESSION['rol']=='Producción' && $_SESSION['logan'] == true) {
       
    } else {
      echo '<script language="javascript">alert("No tienes los permisos para realizar alguna accion aquí");</script>';

  }
  
}
if(!empty($_SESSION['nombre']))
{

    ?>


    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
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

    <body style="overflow:auto"> 
        <?php
        include('Controlador_produccion/db_produccion.php');
        include("Controlador_produccion/crud_programacion.php");
        include("../css/barra_horizontal5.php");
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
            $tipo="Termoencogible";
        }
        $_SESSION['tipoProg']=$tipo;
        $SQL=$MySQLiconn->query("SELECT*from produccion where estado='2' and fechaProduccion='".$fch."' and tipo='$tipo'");
        $det=$tipo;

//////////////////// Aqui se identifica si el tipo de producto requiere elementos de impresión o no///
        $cilinders=0;
        $cirels=0;
        $suaje=0;
        $SOL=$MySQLiconn->query("call getJuegoProcesosbyType('$tipo')");
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

                <div class="panel-heading"><b class="titulo">Programación</b>
                </div>
               <div class="panel-body">
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
    if($det!="BS"){
        ?>
        <select required onChange="showComboLoad(this.value);cargarLote()<?php if($cirels==1){ echo ",recargarComboCireles(this.value)";}else if($cilinders==1){ echo ",recargarComboCilindros(this.value)";}if($suaje==1){ echo ",recargarComboSuaje(this.value)";}?>" class="form-control" id="comboproductos" name="comboproductos" >

            <?php
            $resultado=$MySQLiconn->query("SELECT concat(producto.descripcion,' | ' ,impresiones.descripcionImpresion) as productos, impresiones.descripcionImpresion from producto left join impresiones on producto.descripcion=impresiones.descripcionDisenio where impresiones.baja=1 && producto.baja=1 && producto.tipo='$tipo' order by producto.descripcion asc");

            ?>
            <option value="">--</option>
            <?php
            while($row=$resultado->fetch_array())
            {
                ?>
                <option value="<?php echo $row['descripcionImpresion'];?>"><?php echo $row['productos'];?></option>
                <?php
            }
            ?>
        </select>
        <?php
    }
    else
    {
        ?> <select required onChange="showComboLoad(this.value);cargarLote(),recargarComboCilindros(this.value)" name="comboproductos" class="form-control">

            <?php
            $resultado=$MySQLiconn->query("SELECT concat(bandaspp.identificadorBS,' | ' ,bandaspp.nombreBSPP) as productos, bandaspp.nombreBSPP as descripcionImpresion from bandaseguridad left join bandaspp on bandaseguridad.nombrebanda=bandaspp.identificadorBS where bandaspp.baja=1 && bandaseguridad.baja=1 order by bandaspp.identificadorBSPP asc");
            ?>
            <option value="">--</option>
            <?php
            while($row=$resultado->fetch_array())
            {
                ?>
                <option value="<?php echo $row['descripcionImpresion'];?>"><?php echo $row['productos'];?></option>
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
        if($det!="BS")
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
        $resultado=$MySQLiconn->query("SELECT concat('[',maquinas.codigo,'] ',maquinas.descripcionMaq) as maquina,maquinas.descripcionMaq from maquinas where baja=1 and maquinas.subproceso='$program'");
        ?>
        <option value="">--</option>
        <?php
        while($row=$resultado->fetch_array())
        {
            ?>
            <option value="<?php echo $row['descripcionMaq'];?>"><?php echo $row['maquina'];?></option>
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
    <div id="txtHint"  class="col-xs-5"></div>
    <div id="txtHunt" class="col-xs-5"></div>
</div>
</div>
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
            if (this.readyState == 3 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
      //  var combo = document.getElementById('comboproductos');
//alert('Esta enviando el parametro'+ ' '+combo.options[combo.selectedIndex].value);
xmlhttp.open("GET","Controlador_produccion/showReporte.php?c="+guat,true);
xmlhttp.send();
}
}  
</script>
<script type="text/javascript" language="JavaScript">
    function mostrarTipo(){

        var x= document.getElementById("comboTipos").value;
    //var pagina="Producto_Impresiones.php";
    //function redireccionar() 
    //{

        var combo = document.getElementById('comboTipos');
    //alert('La opción seleccionada es: '+combo.options[combo.selectedIndex].value);
    //location.href=pagina;

    
    //setTimeout ("redireccionar()", 20000);
    //ESta linea muestra el encabezado de la programacion del producto
    //document.getElementById("TitProg").innerHTML="Programación de  "+" "+combo.options[combo.selectedIndex].value;
    document.getElementById("TitProg").innerHTML="Programación";

    if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 3 && this.status == 200) {
             
            }
        };

        xmlhttp.open("GET","Controlador_produccion/crud_programacion.php?c="+combo.options[combo.selectedIndex].value,true);
        xmlhttp.send();
       // window.location="Produccion_programacion.php";

   }
</script>
<script type="text/javascript">

    function cargarLote(val) {

        if (val == "") {
            document.getElementById("txtHunt").innerHTML = "";
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
            if (this.readyState == 3 && this.status == 200) {
                document.getElementById("txtHunt").innerHTML = this.responseText;
            }
        };

        xmlhttp.open("GET","Controlador_produccion/showSeleccionados.php?q="+val,true);
        xmlhttp.send();

    }
}  
function cargarLote2(val) {

    if (val == "") {
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
            if (this.readyState == 3 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };

        xmlhttp.open("GET","Controlador_produccion/showReporte.php?e="+val,true);
        xmlhttp.send();

    }
}  
function enviarLotes(val) {

    if (val == "") {
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
            if (this.readyState == 3 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };

        xmlhttp.open("GET","Controlador_produccion/crud_programacion.php?q="+val,true);
        xmlhttp.send();
        
    }
}  
</script>
</script>
<script type="text/javascript">
    function recargarComboCilindros(val)
    {
       if (val == "") {
        document.getElementById("comboCilindros").innerHTML = "";
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
                document.getElementById("comboCilindros").innerHTML = this.responseText;
            }
        };

        xmlhttp.open("GET","Controlador_produccion/crud_programacion.php?cil="+val,true);
        xmlhttp.send();
        
    }

}
function recargarComboCireles(val)
{
   if (val == "") {
    document.getElementById("comboCireles").innerHTML = "";
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
                document.getElementById("comboCireles").innerHTML = this.responseText;
            }
        };

        xmlhttp.open("GET","Controlador_produccion/crud_programacion.php?cir="+val,true);
        xmlhttp.send();
        
    }

}
function recargarComboSuaje(val)
{
   if (val == "") {
    document.getElementById("comboSuaje").innerHTML = "";
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
                document.getElementById("comboSuaje").innerHTML = this.responseText;
            }
        };

        xmlhttp.open("GET","Controlador_produccion/crud_programacion.php?suj="+val,true);
        xmlhttp.send();
        
    }

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
         elemento.style.background='#D8D8D8'
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
}
else
{
  include "../ingresar.php";
}
?>