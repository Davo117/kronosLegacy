<?php
session_start();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Registro | Producción</title>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
    <link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
    <link rel="manifest" href="../pictures/manifest.json">
    <link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="theme-color" content="#000">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body style="overflow:auto;"> 
    <?php
    include('Controlador_produccion/db_produccion.php');
    include("Controlador_produccion/crud_Registro.php");
    include("../css/barra_horizontal5.php");
    include_once('Controlador_produccion/functions.php');
   // $SQL=$MySQLiconn->query("TRUNCATE lotestemporales");
  /* $SQL=$MySQLiconn->query("SELECT dato from cache where id=6");
    $rem=$SQL->fetch_array();*/

  
    /* $SQL=$MySQLiconn->query("SELECT dato from cache where id=7");
    $tipo="";
    $rem=$SQL->fetch_array();*/
      if(isset($_POST['comboProcesos']))
    {
        $proceso=$_POST['comboProcesos'];
    }
    else if(empty($_GET['proceso']))
    {
        $proceso="";
    }
    else if(!empty($_GET['proceso']))
    {
        $proceso=$_GET['proceso'];
    }
    if(!empty($_GET['tipo']))
{
    $tipo=$_GET['tipo'];
}
else if(empty($tipo))
{
    $tipo="Termoencogible";
}
$_SESSION['tipo']=$tipo;

    
    $_SESSION['etiquetasInd']="";
    ?>
    <div id="row">
    <div class="col-md-10 col-md-offset-1" >


            <form method="post" name="formulary" id="formulary" style="height:auto;">
              <div class="panel panel-default">

                <div class="panel-heading" ><b class="titulo"><?php if(!empty($proceso)){echo "Registro de ".$proceso;}else{echo "Seleccione un proceso";}?></b>
                </div>
               <div class="panel-body" style="overflow:hidden;">

            <?php
            if(isset($_POST['save']))
            {
                ?>
                <div  id="formularius2"  style="visibility: hidden;">
                    <form>
                        <p style="margin-top:7px;">
                            <p>Seleccione el proceso:</p>
                            <p><select onchange="document.formulary.submit()" class="comboProcesos" name="comboProcesos" id="comboProcesos" >
                                <?php
$resultado=$MySQLiconn->query("SELECT descripcionProceso from juegoprocesos where identificadorJuego=(select juegoprocesos from tipoproducto where alias='$tipo') and numeroProceso!=0 and registro=1 and baja=1");//Extrae los procesos donde el id del producto sea igual

?>
<option <?php if(empty($_POST['comboProcesos']) && empty($_GET['proceso'])){echo "selected";}?> value="0">--</option>
<?php
while($row=$resultado->fetch_array())
{
    if($row['descripcionProceso']==$proceso){
        ?>
        <option selected value="<?php echo $row['descripcionProceso'];?>"><?php echo strtoupper($row['descripcionProceso']);?></option>
        <?php
    }
    else
    {
        ?>
        <option value="<?php echo $row['descripcionProceso'];?>"><?php echo strtoupper($row['descripcionProceso']);?></option>
        <?php
    }
}

?>
</select><br>
<?php
$rest = $MySQLiconn->query("SELECT *from juegoparametros where identificadorJuego=(select packParametros from procesos where descripcionProceso='".$proceso."') and baja=1 and numParametro!='G'");

while($rex = $rest->fetch_array()){
    $valiu=$rex['nombreparametro'];
    if($rex['requerido']==1)
    {
        ?>

        <p style="margin-top:5px;"><?php echo $rex['leyenda'] ?></p>
       <input  required type="text" name="<?php echo $rex['nombreparametro'] ?>" value="<?php if(isset($_GET['edit'])) echo $getROW[$valiu];?>"
            size="20" placeholder="<?php echo $rex['placeholder'] ?>" >


            <?php
        }
        else
        {
            ?>

            <p><?php echo $rex['leyenda'] ?></p>
            <input type="text" name="<?php echo $rex['nombreparametro'] ?>" value="<?php if(isset($_GET['edit'])) echo $getROW[$valiu];?>"
                size="20" placeholder="<?php echo $rex['placeholder'] ?>" >

                <?php
            }
            if($rex['numParametro']=="C")
            {
             
                $_SESSION['codigo']=$rex['nombreparametro'];
                $codigo=$rex['nombreparametro'];
            }
        }
        if(!empty($proceso)){

        ?>

        <br>
        <button style="margin-bottom:10px;" type="submit" name="save">Guardar</button>
        <?php
    }
        ?>
    </form>
</div>
<?php
}
else
{
    ?>
    <form id="formulary" name="formulary" role="form">
        <div id="formularius3">
<div class="col-xs-3">
            <label for="comboProcesos">Seleccione el proceso:</label>

            <select onchange="document.formulary.submit()" class="form-control" name="comboProcesos" id="comboProcesos" >
                <?php
$resultado=$MySQLiconn->query("SELECT descripcionProceso from juegoprocesos where identificadorJuego=(select juegoprocesos from tipoproducto where alias='$tipo') and numeroProceso!=0 and registro=1 and baja=1");//Extrae los procesos donde el id del producto sea igual

?>
<option value="0">--</option>
<?php
while($row=$resultado->fetch_array())
{
    if($row['descripcionProceso']==$proceso){
        ?>
        <option selected value="<?php echo $row['descripcionProceso'];?>"><?php echo strtoupper($row['descripcionProceso']);?></option>
        <?php
    }
    else
    {
        ?>
        <option value="<?php echo $row['descripcionProceso'];?>"><?php echo strtoupper($row['descripcionProceso']);?></option>
        <?php
    }
}

?>
</select>
</div>
<?php
$rest = $MySQLiconn->query("SELECT *from juegoparametros where identificadorJuego=(select packParametros from procesos where descripcionProceso='".$proceso."') and baja=1 and numParametro!='G'");
while($rex = $rest->fetch_array()){
    $valiu=$rex['nombreparametro'];
    if($rex['requerido']==1)
    {

        if($rex['nombreparametro']=="maquina")
        {
            ?>
            <div class="col-xs-3" ">
               <label for="<?php echo $rex['nombreparametro'] ?>"><?php echo $rex['leyenda'] ?></label>
               <?php

               $Sel=$MySQLiconn->query("SELECT concat(descripcionMaq,'[',codigo,']') as maq,descripcionMaq as maquina from maquinas where subproceso like '".substr($proceso,0,4)."%' and baja=1");
               ?>

               <SELECT class="form-control" required name="<?php echo $rex['nombreparametro'] ?>" id="<?php echo $rex['nombreparametro'] ?>" >
                <option value="">---</option>
                <?php
                while($op=$Sel->fetch_array())
                {
                    ?>
                    <option value="<?php echo $op['maquina'];?>"><?php echo $op['maq'];?></option>
                    <?php
                }
                ?>
            </SELECT></div>
            <?php
        }
        else if($rex['nombreparametro']=="juegoCilindros")
        {
            ?>
            <div class="col-xs-3">
               <label for="<?php echo $rex['nombreparametro'] ?>"><?php echo $rex['leyenda'] ?></label>
               <?php

               $Sel=$MySQLiconn->query("
SELECT concat(c.identificadorCilindro,' | ',c.proveedor,' D.') as prov,c.identificadorCilindro from juegoscilindros c
INNER JOIN impresiones im ON im.descripcionImpresion=c.descripcionImpresion
INNER JOIN producto p ON p.descripcion=im.descripcionDisenio
where  c.baja=1 and p.tipo='".$tipo."' and im.baja= 1 and p.baja=1");
               ?>

               <SELECT class="form-control" id="<?php echo $rex['nombreparametro'] ?>" required name="<?php echo $rex['nombreparametro'] ?>" >
                <option value="">---</option>
                <?php
                while($op=$Sel->fetch_array())
                {
                    ?>
                    <option value="<?php echo $op['prov'];?>"><?php echo $op['prov'];?></option>
                    <?php
                }
                ?>
            </SELECT></div>
            <?php
        }
      
           else if($rex['nombreparametro']=="juegoCireles")
        {
            ?>
            <div class="col-xs-3">
               <label for="<?php echo $rex['nombreparametro'] ?>"><?php echo $rex['leyenda'] ?></label>
               <?php

               $Sel=$MySQLiconn->query("
SELECT concat(c.identificadorJuego,' | ',c.num_dientes,' D.') as prov,c.identificadorJuego from juegoscireles c
INNER JOIN impresiones im ON im.codigoImpresion=c.producto
INNER JOIN producto p ON p.descripcion=im.descripcionDisenio
where  c.baja=1 and p.tipo='".$tipo."'");
               ?>

               <SELECT class="form-control" required name="<?php echo $rex['nombreparametro'] ?>" id="<?php echo $rex['nombreparametro'] ?>">
                <option value="">---</option>
                <?php
                while($op=$Sel->fetch_array())
                {
                    ?>
                    <option value="<?php echo $op['identificadorJuego'];?>"><?php echo $op['prov'];?></option>
                    <?php
                }
                ?>
            </SELECT></div>
            <?php

        }
        else if($rex['nombreparametro']=="suaje")
        {

            ?>
            <div class="col-xs-3">
               <label for="<?php echo $rex['nombreparametro'] ?>"><?php echo $rex['leyenda'] ?></label>
               <?php

               $Sel=$MySQLiconn->query("
SELECT concat(c.identificadorSuaje,' | ',c.proveedor,' D.') as prov,c.identificadorSuaje from suaje c
INNER JOIN impresiones im ON im.descripcionImpresion=c.descripcionImpresion
INNER JOIN producto p ON p.descripcion=im.descripcionDisenio
where  c.baja=1 and p.tipo='".$tipo."' and c.proceso='".$proceso."'");
               ?>

               <SELECT class="form-control" name="<?php echo $rex['nombreparametro'] ?>" id="<?php echo $rex['nombreparametro'] ?>" >
                <option value="">---</option>
                <?php
                while($op=$Sel->fetch_array())
                {
                    ?>
                    <option value="<?php echo $op['identificadorSuaje'];?>"><?php echo $op['prov'];?></option>
                    <?php
                }
                ?>
            </SELECT></div>
            <?php
        }
        

        else
            {
     ?>

             <div class="col-xs-3">
              <label for="<?php echo $rex['nombreparametro'] ?>"><?php echo $rex['leyenda'] ?></label>
                <input  class="form-control" required type="text" name="<?php echo $rex['nombreparametro'] ?>" value="<?php if(isset($_GET['edit'])) echo $getROW[$valiu];?>"
                    size="20" placeholder="<?php echo $rex['placeholder'] ?>" ></div><?php
                }
                ?>


                <?php
            }
            else
            {
                      if($rex['nombreparametro']=="suaje")
        {

            ?>
            <div class="col-xs-3">
               <label for="<?php echo $rex['nombreparametro'] ?>"><?php echo $rex['leyenda'] ?></label>
               <?php

               $Sel=$MySQLiconn->query("
SELECT concat(c.identificadorSuaje,' | ',c.proveedor,' D.') as prov,c.identificadorSuaje from suaje c
INNER JOIN impresiones im ON im.descripcionImpresion=c.descripcionImpresion
INNER JOIN producto p ON p.descripcion=im.descripcionDisenio
where  c.baja=1 and p.tipo='".$tipo."' and c.proceso='".$proceso."'");
               ?>

               <SELECT class="col-xs-3" id="<?php echo $rex['nombreparametro'] ?>" name="<?php echo $rex['nombreparametro'] ?>" >
                <option value="">---</option>
                <?php
                while($op=$Sel->fetch_array())
                {
                    ?>
                    <option value="<?php echo $op['identificadorSuaje'];?>"><?php echo $op['prov'];?></option>
                    <?php
                }
                ?>
            </SELECT></div>
            <?php
        }
        else
        {
                ?>
                <div class="col-xs-3" ">
                    <label for="<?php echo $rex['nombreparametro'] ?>"><?php echo $rex['leyenda'] ?></label>
                    <input class="form-control" type="text" name="<?php echo $rex['nombreparametro'] ?>" value="<?php if(isset($_GET['edit'])) echo $getROW[$valiu];?>"
                        size="20" placeholder="<?php echo $rex['placeholder'] ?>" ></div>


                        <?php
                    }

                }
                 if($rex['numParametro']=="C")
                    {
                     
                        $_SESSION['codigo']=$rex['nombreparametro'];
                        $codigo=$rex['nombreparametro'];
                    }
            }

                ?>


            </div>
            <?php if(!empty($proceso))
{
     ?>       </div><button  class="btn btn-default" style="float:right;" type="submit" name="save">Guardar</button>
       <?php }?>
            
        </form>
        <?php
    }
    if(isset($_POST['save']))
    {
        ?>
        <div class="formulariosProduccion"  style="position:absolute;top:12%;left:5%;right:5%">
            <?php
            include("Controlador_produccion/showRegistro.php");
            ?>
        </div>
        <?php
    }
    ?>
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
mostrarTipo();
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
xmlhttp.open("GET","Controlador_produccion/showSeguimiento.php?d="+guat,true);
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

        document.getElementById("TitProg").innerHTML="Registro de  "+" "+combo2.options[combo2.selectedIndex].value;


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

        xmlhttp.open("GET","Controlador_produccion/crud_Registro.php?c="+combo.options[combo.selectedIndex].value,true);
        xmlhttp.send();
       // window.location="Produccion_programacion.php";

   }
</script>
<script language="JavaScript">
  function redireccionar() {
    setTimeout('history.back()',10000);
}
function abre() { 
    window.open("addDisc.php","Agregar disco","width=300,height=500, top=100,left=100"); 
    return true; 
} 
</script>
<script type="text/javascript">
    function numeros(e){
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = " 0123456789";
    especiales = [46,47,48,49,13,8];
 
    tecla_especial = false
    for(var i in especiales){
 if(key == especiales[i]){
     tecla_especial = true;
     break;
        } 
    }
 
    if(letras.indexOf(tecla)==-1 && !tecla_especial)
        return false;
}
</script>
</html>