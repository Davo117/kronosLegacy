<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>

  <head>
   <title>Códigos| Almacén</title>
   <!-- Latest compiled and minified CSS -->

 </head>
 <?php
 include("../components/barra_latera_almacen.php");
 include("../Database/SQLConnection.php");
 include("controlador_almacen/crud_salidas.php");
 include("conexionobelisco.php");
//$_SESSION['codigoPermiso']='20001';
//include ("funciones/permisos.php");
//$_SESSION['descripcion']=" ";
//$_SESSION['descripcionCil']=" ";
//$_SESSION['descripcionBanda']=" ";
//include("Controlador_Disenio/bitacoras/bitacoraDisenio.php");
 //include "modelo_almacen/salida.php";
 $_SESSION['arrayProductos']=array();

 //include("controlador_almacen/crud_salidas.php");
 
 include("../Database/conexionphp.php");
 if(empty($_POST['fechaIng']))
 {
  $fch=date('Y-m-d');
}
else
{
  $fch=$_POST['fechaIng'];
}


?>
<body>
  <?php

    ?>
    <div id="page-wrapper">
      <div class="container-fluid">


        <form method="POST" name="formulary" id="formulary" role="form">
          <div class="panel panel-success">


            <div class="panel-heading" style="overflow:hidden;"><b class="titulo">Generar códigos</b>
            </div>
            <div class="panel-body">
              <div>
             <div >
              <!--
<label for="cdgproducto">Producto</label><br>
                <select required  name="cdgproducto" id="cdgproducto" class="selectpicker show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="Sólo productos genéricos">
    <?php
/* $resultado = sqlsrv_query($SQLconn, "SELECT CIDPRODUCTO as id,CCODIGOPRODUCTO as codigo,CNOMBREPRODUCTO as nombreElemento FROM admproductos WHERE CIDPRODUCTO!=0 AND CSTATUSPRODUCTO=1 AND CTEXTOEXTRA1=1");
?>
<option value="">--</option>
<?php
while ($row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) 
{
  $myS=$mysqli->query("SELECT hascode FROM obelisco.productosCK WHERE producto='".$row['id']."'");
  $rum=$myS->fetch_assoc();
  if($rum['hascode']==0)
  {
?>

<option data-tokens="<?php echo $row['nombreElemento'].$row['codigo'];?>" value="<?php echo $row['id'];?>"><?php echo $row['nombreElemento'];?></option>

<?php 
  }
} */
?> 
</select>   
</div> -->   


<!--FAMILIA -->
</div>
<div class="panel-body">


<div style="float:left; margin: 10px">
<label for="familia">Familia</label><br>
  <form method="POST" id='familia'  data-style="form-control" data-live-search="true"  >
  <select  name="familia" required="TRUE" class="selectpicker show-menu-arrow form-control" <?php if (isset($_GET['edit'])) {
  echo "disabled"; } ?>> 
  <option value="" >Selecciona una Familia</option >  
  <option value="1" <?php if(isset($_GET['edit']) && $gg['familia']=="1"){ echo "selected";}?>>Tintas</option>
  <option value="2" <?php if(isset($_GET['edit']) && $gg['familia']=="2"){ echo "selected";}?>>Acabados</option>  
  <option value="3" <?php if(isset($_GET['edit']) && $gg['familia']=="3"){ echo "selected";}?>>Aditivos</option> 
  <option value="4" <?php if(isset($_GET['edit']) && $gg['familia']=="4"){ echo "selected";}?>>Solventes</option>
  <option value="5" <?php if(isset($_GET['edit']) && $gg['familia']=="5"){ echo "selected";}?>>Adhesivos</option>
  <option value="6" <?php if(isset($_GET['edit']) && $gg['familia']=="6"){ echo "selected";}?>>Sustratos</option> 
  <option value="7" <?php if(isset($_GET['edit']) && $gg['familia']=="7"){ echo "selected";}?>></option>
  <option value="8" <?php if(isset($_GET['edit']) && $gg['familia']=="8"){ echo "selected";}?>></option>
  <option value="9" <?php if(isset($_GET['edit']) && $gg['familia']=="9"){ echo "selected";}?>>Indirectos</option>
  </option>
</select> </div> 

<!--PROCESO -->
<div style="float:left;  margin: 10px" > 
  <label for="proceso">Proceso</label><br>
    <form  method="POST" name='proceso' id='proceso'  data-style="form-control" data-live-search="true" >
  <select name="proceso" required="TRUE" class="selectpicker show-menu-arrow form-control" <?php if (isset($_GET['edit'])) {
  echo "disabled"; }?> >
  <option value=""  >Selecciona un Proceso</option> 
  <option value="1" <?php if(isset($_GET['edit']) && $gg['proceso']=="1"){ echo "selected";}?>>Flexo</option>
  <option value="2" <?php if(isset($_GET['edit']) && $gg['proceso']=="2"){ echo "selected";}?>>Roto</option>  
  <option value="3" <?php if(isset($_GET['edit']) && $gg['proceso']=="3"){ echo "selected";}?>>Laminado</option> 
  <option value="4" <?php if(isset($_GET['edit']) && $gg['proceso']=="4"){ echo "selected";}?>>Embarque</option>
  <option value="5" <?php if(isset($_GET['edit']) && $gg['proceso']=="5"){ echo "selected";}?>>Varios</option>
  </option>

</select> </div> 

               <!--DIVISION -->
              
<div  style="float:left; margin: 10px">
  <label for="proceso">Division</label><br>
    <form method="POST" name='division'  id='division'  data-style="form-control" data-live-search="true">
  <select name="division" required="TRUE" class="selectpicker show-menu-arrow form-control" <?php if (isset($_GET['edit'])) {
  echo "disabled"; }?>>
  <option value="" >Selecciona una Division</option>  
  <option value="1" <?php if(isset($_GET['edit']) && $gg['division']=="1"){ echo "selected";}?>>Base Agua</option>
  <option value="2" <?php if(isset($_GET['edit']) && $gg['division']=="2"){ echo "selected";}?>>Solvente</option>  
  <option value="3" <?php if(isset($_GET['edit']) && $gg['division']=="3"){ echo "selected";}?>>UV</option> 
  <option value="0" <?php if(isset($_GET['edit']) && $gg['division']=="4"){ echo "selected";}?>>Sustrato</option>
  </option>

</select> </div>

                        <!-- PROVEEDOR --> 
                       
                    <div style="float:left; margin: 10px">
                         <label for="proceso">Proveedor</label><br>
        <select required="true"  name="cdgproveedor" autofocus="true"  id="cdgproveedor"  class="selectpicker show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="--Selecciona el proveedor" <?php if (isset($_GET['edit'])) {
  echo "disabled"; }?>> 

    <?php 

$resultado = sqlsrv_query($SQLconn, "SELECT CRAZONSOCIAL as proveedor,CIDCLIENTEPROVEEDOR as codigo FROM ADMCLIENTES WHERE CESTATUS=1 and CTIPOCLIENTE=3");
?>

<option value="">--</option>
<?php 
while ($row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) 
{
  if($_POST['cdgproveedor']==$row['codigo'])
  {
    ?>
    <option selected data-tokens="<?php echo $row['proveedor'].$row['codigo'];?>" value="<?php echo $row['codigo'];?>"><?php echo $row['proveedor'];?></option>
    <?php
  }
  else
  {
    ?> 

<option data-tokens="<?php echo $row['proveedor'].$row['codigo'];?>" value="<?php echo $row['codigo'];?>"><?php echo $row['proveedor'];?></option>
<?php
  }

} 
?>

<!--  EDITAR PROVEEDOR -->
<?php

if(isset($_GET['edit'])) 
{ 
  $resultado3 = sqlsrv_query($SQLconn, "SELECT CRAZONSOCIAL as proveedor,CIDCLIENTEPROVEEDOR as codigo FROM ADMCLIENTES WHERE CIDCLIENTEPROVEEDOR=".$gg['datop']."");
    $row4 = sqlsrv_fetch_array($resultado3, SQLSRV_FETCH_ASSOC); 

if( $gg['datop']==$row4['codigo'])
{
  ?>

  <option selected data-tokens="<?php echo $row4['proveedor'].$row4['codigo'];?>" value="<?php echo $row4['codigo'];?>"  ><?php  echo $row4['proveedor'];?>  </option>
  <?php 
}
  else{
    ?>
<option  data-tokens="<?php echo $row4['proveedor'].$row4['codigo'];?>" value="<?php echo $row4['codigo'];?>"><?php echo $resultado3['proveedor'];?></option>


<?php 
}
} 
?> 

</select></div>  

<!--PRODUCTOS-->

<div style="float:left; margin: 10px"> <label for="cdgproducto">Productos</label><br>
                <select required="true"  name="cdgproducto" id="cdgproducto" class="selectpicker show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="Selecciona un producto" <?php if (isset($_GET['edit'])) {
  echo "disabled"; }?>>
    <?php
  
  $obel=$mysqli->query("SELECT  producto as id, id as codigo from obelisco.productosCK");
?>
<option value="" >--</option>


<?php
while ($mySQL=$obel->fetch_array())  
{
  $resultado = sqlsrv_query($SQLconn, "SELECT CNOMBREPRODUCTO as nombreElemento FROM admproductos WHERE CIDPRODUCTO='".$mySQL['id']."'");

  $row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);

  $myS=$mysqli->query("SELECT hascode FROM obelisco.productosCK WHERE producto='".$row['id']."'");
  $rum=$myS->fetch_assoc();
  if($rum['hascode']==0)
  {

?>

<option data-tokens="<?php echo $row['nombreElemento'].$mySQL['codigo'];?>" value="<?php echo $mySQL['id'];?>"><?php echo $row['nombreElemento']; ?></option >

<?php 
  }
} 
?> 

<!--  -->
<?php

if(isset($_GET['edit'])) 
{ 
  $qwe= sqlsrv_query($SQLconn, "SELECT CIDPRODUCTO as id, CCODIGOPRODUCTO as codigo, CNOMBREPRODUCTO as nombreElemento FROM admproductos WHERE CIDPRODUCTO=".$gg['producto']."");
    $row2 = sqlsrv_fetch_array($qwe, SQLSRV_FETCH_ASSOC); 

if( $gg['producto']==$row2['id'])
{
  ?>

  <option selected data-tokens="<?php echo $row2['nombreElemento'].$row2['codigo'];?>" value="<?php echo $row2['id'];?>"  ><?php  echo $row2['nombreElemento'];?>  </option>
  <?php 
}
  else{
    ?>
<option data-tokens="<?php echo $row2['nombreElemento'].$row2['codigo'];?>" value="<?php echo $row2['id'];?>"><?php echo $row2['nombreElemento'];?></option>


<?php 
}
} 
?> 

</select>   </div> 

 

<!--ENTRADA -->

</select> 

<div style="float:left; margin: 10px">
 <label for="entrada">Entrada</label><br>
  <form method="POST" name='entrada'  id='entrada' data-style="form-control" data-live-search="true">
  <select name="entrada" required="TRUE" class="selectpicker show-menu-arrow form-control"  <?php if (isset($_GET['edit'])) {
  echo "disabled"; }?>>


<option value="" >Selecciona una Entrada</option> 
  <option value="1" <?php if(isset($_GET['edit']) && $gg['entrada']=="1"){ echo "selected";}?>>Virgen</option>
  <option value="2" <?php if(isset($_GET['edit']) && $gg['entrada']=="2"){ echo "selected";}?>>ReProceso</option> 


</select> </div> </div> 

<br> <!--
<button class="btn btn-primary" type="submit" name="enviar">
            Mostrar codigo
          </button>--><!--
<input type="submit" name="enviar" class="selectpicker show-menu-arrow form-control"> -->


  </form>



<?php 
if (isset($_POST['genericodes'])) 
{

$datop =$_POST['cdgproveedor'];
$datop=str_pad($datop,3,"0", STR_PAD_LEFT); 

 $datod =$_POST['cdgproducto'];
 $datod=str_pad($datod,4,"0", STR_PAD_LEFT);

  $familia=$_POST['familia'];
  $proceso=$_POST['proceso'];
  $division=$_POST['division'];
  $entrada=$_POST['entrada'];
  $resultado=$familia.$proceso.$division.$datop.$datod.$entrada;


 ?>
<?php

   echo'<script type="text/javascript">
    alert(" Codigo Generado: '.$resultado.'");
    window.location.href="getcodealmacen.php";
    </script>';
}

  ?> <br> 
 
<div style="float:left;">
 <img src="barcode.php?text=<?php echo $resultado ?>&size=100&orientation=horizontal&codetype=Code39&print=true&sizefactor=2"> <br>

</div>


<br>    <br>   <!-- BOTON PARA SELECCIONAR LA CANTIDAD DEL CODIGO   -->
            <div style="float:left;" ><label>Cantidad</label>
<input type="number" step="any" max="999" min="1" placeholder="Cantidad de códigos por generar" class="form-control" name="mountcodes" style="float:right;" required value="<?php if(isset($_GET['edit'])) echo $gg['consec']; ?>" ></div>

<?php
if(isset($_GET['edit']))
{
  
  ?>
  <button style="float:left;" class="btn btn-primary" type="submit" name="update">Actualizar</button>
  </div></div></div></form>
  <form method="POST" name="formi" id="formi" enctype="multipart/form-data">
                <div class="panel panel-success"><div class="panel-body">
  <?php
}
else
{
  ?>                    <!-- BOTON PARA GENERAR CODIGOS   --><div style="float:right;">
<button class="btn btn-primary" name="genericodes" style="float:left;">Generar</button> 
            </div></div></div></div></form> 
            <form method="POST" name="formi" id="formi" enctype="multipart/form-data">
                <div class="panel panel-success"><div class="panel-body">
                
           <?php // Anteriormente estaba input file para cargar el archivo en lugar de solo guardar el contenido <input type="file" class="btn btn-success" name="uploadlist"  id="uploadlist">?>
           <br> 
 <?php
}
?> <!--
                     <button name="load" title="Cargar packing list" id="load" class="btn btn-success" style="float:left;"><img class="img-responsive" width="40" height="40" src="../pictures/excel.png"></button>
           
          </div></form>
             
            </div> -->
        <!--refrescar la pagina-->
<!--
<button type="button"><a href="http://192.168.1.162/Kronos/Kronos_Sys%20-%20Testing/Almacen/getcodealmacen.php">Refrescar</a></button> -->

        </form> 

<div class="">
        <h4 class="ordenMedio">Códigos generados</h4>
        <div class="table-responsive">
          <table class="table table-hover">
            <?php
            //$SQlo=sqlsrv_query($SQLconn,"SELECT*FROM admdocumentos where CIDDOCUMENTODE=".$ciddoc."");
            $lstIn=$mysqli->query("SELECT*FROM obelisco.pzcodes group by codigoB  order by fecha_alta desc limit 1000");
            
            ?>
            <th>Códigos</th><th>Fecha</th><th>Código</th><th>Producto</th><th>Serie</th><th>Modificar</th>
            <tr>

              <?php
              while($row=$lstIn->fetch_array())
              {

                $query="SELECT CNOMBREPRODUCTO as nombreElemento, CCODIGOPRODUCTO as identificadorElemento FROM admproductos WHERE CIDPRODUCTO = '".$row['producto']."'";
                $resultado = sqlsrv_query($SQLconn, $query);
                $rowSQL = (sqlsrv_fetch_array($resultado,SQLSRV_FETCH_ASSOC));
                  /*$SQlo=$mysqli->query("SELECT concat(nombre,' ',apellido) as nombre FROM obelisco.empleado WHERE ID='".$lstIn['empleado']."')");
                  $rowS=$SQlo->fetch_assoc();*/

                ?>

                <!--TRAE LA SERIE Y GENERA EL REPORTE EN PDF -->
                 <td><a class="btn" target="_blank" title="Generar reporte PDF" href="alm_doc/packingcode.php?codigo=<?php echo $row['codigoB'];?>" ><img src="../pictures/barcodes.png" class="img-responsive" width="30" height="30"></a></td>
                 <td><?php echo $row['fecha_alta'];?></td><td><?php echo $row['codigoB'];?></td><td><?php echo $rowSQL['nombreElemento']?></td><td><?php echo $row['cdglist'];?></td>

                 <td>
                 <a href="?edit=<?php echo $row["cdglist"]; ?>"><IMG src="../pictures/modificarproducto.png" title='Modificar'></a> </td>

   
                                 <!-- BOTON PARA VER LOS CODIGOS 
            <button data-toggle="collapse" data-target="#chart-1" class="btn btn-default" type="button" data-toggle="dropdown"><img src="../pictures/ojo.png" class="img-responsive" width="20" height="20">
            <span class="caret"></span></button><br> -->

                 <div class="panel-heading collapse" id="chart-1">
                  <?php
                  $lston=$mysqli->query("SELECT codigoB,porcion,metros,caja FROM obelisco.pzcodes where cdglist='".$row['cdglist']."'");
                  while($rum=$lston->fetch_assoc())
                  {
                    echo "<p style='color:#B1B1B1;border-radius:2px;border-style:solid;border-color:#E8E8E8;border-width:2px'>".$rum['codigoB']."</p>"; /*
                     echo "<p style='color:#B1B1B1;border-radius:2px;border-style:solid;border-color:#E8E8E8;border-width:2px'>".$rum['porcion']."</p>";
                     echo "<p style='color:#B1B1B1;border-radius:2px;border-style:solid;border-color:#E8E8E8;border-width:2px'>".$rum['metros']."</p>"; */
                  }?>
                  </div></td>

              </tr>
              <?php 
            }
            ?>
          </table>
        </div>
      </div>
      </div>

</div>
      <?php
      ob_end_flush();
    } else {
      echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
      include "../ingresar.php";
    }

    ?>
