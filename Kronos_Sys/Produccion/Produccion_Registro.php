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
</style>
    <body style="overflow:auto;"> 
        <?php
        include('Controlador_produccion/db_produccion.php');
        include('Controlador_produccion/functions.php');
        include("Controlador_produccion/crud_Registro.php");
        include("../css/barra_horizontal5.php");
        
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
            $tipo="30";
        }
        $_SESSION['tipo']=$tipo;

        
        $_SESSION['etiquetasInd']="";
        ?>
        <div id="row">
            <div class="col-md-10 col-md-offset-1">


                <form method="post" name="formulary" id="formulary" style="height:auto;">
                  <div class="panel panel-default" id="contuner">

                    <div class="panel-heading"  style="overflow:hidden;"><b class="titulo"><?php if(!empty($proceso)){echo "Registro de ".$proceso;}else{echo "Seleccione un proceso";}?></b>
                    <a class="tooltip-test" style="float:right;"  data-toggle="modal" data-target="#exampleModalLong" href="#" title="Sección de ayuda"><img src="../pictures/question.png" class="img-responsive" width="25" height="25"></a></div>
                    <div class="panel-body">

                        <?php
                        if(isset($_POST['save']))
                        {
                            ?>
                            <div  id="formularius2"  hidden="true" ">
                                <form>
                                    <p style="margin-top:7px;"></p>
                                        <p>Seleccione el proceso:</p>
                                        <p><select onchange="document.formulary.submit()" class="comboProcesos" name="comboProcesos" id="comboProcesos" >
                                            <?php
    $resultado=$MySQLiconn->query("SELECT j.descripcionProceso,(select id from procesos where descripcionProceso=j.descripcionProceso) as idPorigen from juegoprocesos j where j.identificadorJuego=(select juegoprocesos from tipoproducto where id='$tipo') and j.numeroProceso!=0 and j.registro=1 and j.baja=1");//Extrae los procesos donde el id del producto sea igual

    ?>
    <option <?php if(empty($_POST['comboProcesos']) && empty($_GET['proceso'])){echo "selected";}?> value="0">--</option>
    <?php
    while($row=$resultado->fetch_array())
    {
        if($row['idPorigen']==$proceso){
            ?>
            <option selected value="<?php echo $row['idPorigen'];?>"><?php echo strtoupper($row['descripcionProceso']);?></option>
            <?php
        }
        else
        {
            ?>
            <option value="<?php echo $row['idPorigen'];?>"><?php echo strtoupper($row['descripcionProceso']);?></option>
            <?php
        }
    }

    ?>
    </select></p><br>
    <?php
    $rest = $MySQLiconn->query("SELECT *from juegoparametros where identificadorJuego=(select packParametros from procesos where id='".$proceso."') and baja=1 and numParametro!='G'");

    while($rex = $rest->fetch_array())
    {
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
    if(!empty($proceso))
    {

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
                    <input type="hidden" name="comboProcesosName" id="comboProcesosName" value="">
                    <label for="comboProcesos">Seleccione el proceso:</label>

                    <select onchange="document.formulary.submit()" class="form-control" name="comboProcesos" id="comboProcesos" >
                        <?php
    $resultado=$MySQLiconn->query("SELECT j.descripcionProceso,(select id from procesos where descripcionProceso=j.descripcionProceso) as idPorigen from juegoprocesos j where j.identificadorJuego=(select juegoprocesos from tipoproducto where id='$tipo') and j.numeroProceso!=0 and j.registro=1 and j.baja=1");//Extrae los procesos donde el id del producto sea igual

    ?>
    <option value="0">--</option>
    <?php
    while($row=$resultado->fetch_array())
    {
        if($row['idPorigen']==$proceso)
        {
            ?>
            <option selected value="<?php echo $row['idPorigen'];?>"><?php echo strtoupper($row['descripcionProceso']);?></option>
            <?php
        }
        else
        {
            ?>
            <option value="<?php echo $row['idPorigen'];?>"><?php echo strtoupper($row['descripcionProceso']);?></option>
            <?php
        }
    }

    ?>
    </select>
    </div>
    <?php
    $rest = $MySQLiconn->query("SELECT *from juegoparametros where identificadorJuego=(select packParametros from procesos where id='".$proceso."') and baja=1 and numParametro!='G'");
    while($rex = $rest->fetch_array())
    {
        $valiu=$rex['nombreparametro'];
        if($rex['requerido']==1)
        {

            if($rex['nombreparametro']=="maquina")
            {
                ?>
                <div class="col-xs-3" ">
                 <label for="<?php echo $rex['nombreparametro'] ?>"><?php echo $rex['leyenda'] ?></label>
                 <?php

                 $Sel=$MySQLiconn->query("SELECT concat(descripcionMaq,'[',codigo,']') as maq,idMaq as maquina from maquinas where substring(subproceso,1,4)=substring((select descripcionProceso from procesos where id='".$proceso."'),1,4) and baja=1");
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

                 /*$Sel=$MySQLiconn->query("
                    SELECT concat(c.identificadorCilindro,' | ',c.proveedor,' D.') as prov,c.IDCilindro as id,c.identificadorCilindro from juegoscilindros c
                    INNER JOIN impresiones im ON im.id=c.descripcionImpresion
                    INNER JOIN producto p ON p.ID=im.descripcionDisenio
                    where  c.baja=1 and p.tipo='".$tipo."' and im.baja= 1 and p.baja=1");*/
                    ?>

                    <input class="form-control" id="<?php echo $rex['nombreparametro'] ?>" placeholder="Escanee el código" required name="<?php echo $rex['nombreparametro'] ?>" ></div>
                    <?php
               /* }*/
}
                else if($rex['nombreparametro']=="juegoCireles")
                {
                    ?>
                    <div class="col-xs-3">
                     <label for="<?php echo $rex['nombreparametro'] ?>"><?php echo $rex['leyenda'] ?></label>
                     <?php

                     $Sel=$MySQLiconn->query("
                        SELECT concat(c.identificadorJuego,' | ',c.num_dientes,' D.') as prov,c.id,c.identificadorJuego from juegoscireles c
                        INNER JOIN impresiones im ON im.id=c.producto
                        INNER JOIN producto p ON p.ID=im.descripcionDisenio
                        where  c.baja=1 and p.tipo='".$tipo."'");
                        ?>

                        <SELECT class="form-control" required name="<?php echo $rex['nombreparametro'] ?>" id="<?php echo $rex['nombreparametro'] ?>">
                            <option value="">---</option>
                            <?php
                            while($op=$Sel->fetch_array())
                            {
                                ?>
                                <option value="<?php echo $op['id'];?>"><?php echo $op['prov'];?></option>
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
                                INNER JOIN impresiones im ON im.id=c.descripcionImpresion
                                INNER JOIN producto p ON p.ID=im.descripcionDisenio
                                where  c.baja=1 and p.tipo='".$tipo."'");
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
                                INNER JOIN impresiones im ON im.id=c.descripcionImpresion
                                INNER JOIN producto p ON p.ID=im.descripcionDisenio
                                where  c.baja=1 and p.tipo='".$tipo."'");
                                ?>

                                <SELECT class="form-control"  id="<?php echo $rex['nombreparametro'] ?>" name="<?php echo $rex['nombreparametro'] ?>" >
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
                       ?>      <hr style="clear:both;"><b style="font:bold 10px Sansation Light">Escanear código o presionar ENTER para guardar</b><button style="display:none;" class="btn btn-default" style="float:right;" type="submit" name="save"></button></form>

                   <?php }?>


                   <?php
               }
               if(isset($_POST['save']))
               {
                ?>
                    <?php
                    include("Controlador_produccion/showRegistro.php");
                    ?>
                <?php
            }
            ?> 
        </div>
    </div>
    </form>
    </div>


<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Ayuda</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4>La captura de bobinas se puede realizar de dos maneras:</h4>
        <ul>

<li type="disc">Escaneando el código de barras de la etiqueta.</li>
<li type="disc">Colocar el número de operación (NoOP) que viene en la etiqueta.</li>
<li type="disc">El número de operación es a partir del punto,<strong> "NoOP:30."</strong>-> a partir de aquí es lo que se debe capturar.</li>

</ul>
  <h4>De existir un error al capturar hay que comunicarse con su superior para deshacer el movimiento y se pueda capturar nuevamente, no se puede modificar.</h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
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
    
       $( document ).ready(function() {
        if($('#comboProcesos option:selected').text()!="")
        {
            $('.titulo').text("Registro de "+$('#comboProcesos option:selected').text().toLowerCase());
            $('#comboProcesosName').val($('#comboProcesos option:selected').text().toLowerCase());
            //console.log($('#comboProcesos option:selected').text());
        }
    });
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