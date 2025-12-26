

<div class=".col-xs-12 .col-sm-6 .col-md-8">
  <?php //<h3 class="bg-info">Seleccione los Procesos del Producto</h3>?>
  <div class="box">
  <?php 
    include ("../Controlador_Misce/db_Producto.php");
    $q=$_GET['q'];

    $resultado=$MySQLiconn->query("SELECT * from juegoprocesos where identificadorJuego='$q' && numeroProceso!='0'");
    $ols=$MySQLiconn->query("SELECT presentacion FROM tipoproducto WHERE juegoProcesos='$q'");
    $pre=$ols->fetch_array();
    if($pre['presentacion']=="Predeterminados")
    {
       $consulta=$MySQLiconn->query("SELECT * from procesos where process!='1' && baja='1'");
    }
    else if($pre['presentacion']=="EtiqAbierta")
    {
        $consulta=$MySQLiconn->query("SELECT * from procesos where process!='1' && baja='1' and  descripcionProceso!='fusion'");
    }
    else if($pre['presentacion']=="holograma")
    {
       $consulta=$MySQLiconn->query("SELECT * from procesos where process!='1' && baja='1' and descripcionProceso!='impresion' and descripcionProceso!='fusion' and descripcionProceso!='impresion-flexografica'");
    }
   
    $numero=$resultado->num_rows;
    
    $count=0;
    while($row=$consulta->fetch_array()){?>
    
      <div class="img" id="caja<?php echo $row['id']; ?>" >
        <input class="form-control" type="text" name="number[]" id="drag<?php echo $row['id']; ?>" readonly="true"  value="<?php echo $row['descripcionProceso']; ?>" style='width:100%;' draggable="true" ondragstart="drag(event)"/> 
      </div>
      <?php
      $count++;
    } ?>
    </div>
<form method="post">
  <div class=".col-xs-12 .col-sm-6 .col-md-8" id="box1">
    <h3 class="bg-info">Procesos Añadidos (<input type="hidden"  name="process" readonly="true"  value="<?php echo $q; ?>" style='width: 20%; background: none; color:black; border:none; margin-left: -4%;margin-right:-5%;'  /> )</h3>
<?php $i=0; $validar=0;
while ($rows = $resultado->fetch_array()) { $i++; ?>
<?php if($i==4 || $i==8){ echo "<b style='clear:left;float:left;margin-left:20px'>".$i."</b>";}
else{echo "<b style='float:left;margin-left:20px'>".$i."</b>";}?>



  <div  style="margin-right:20px;" class="img1" id="cajaP<?php echo $i; ?>"  <?php if ($rows['descripcionProceso']=='') {echo' ondrop="drop(event)" ondragover="allowDrop(event)" '; } ?> >

    <?php if ($rows['descripcionProceso']!='') {$validar++; ?>
      <p  id="dragP<?php echo $i; ?>" class="parrafo"><?php echo $rows['descripcionProceso']; ?></p>
    <?php
    } ?>
  </div>
  <?php
} ?>

  </div>

  <?php if ($validar!=$numero) {?>
  
  <button type="submit" class="btn btn-success" name="saveProcess">Guardar</button>
  <?php } ?>
</form>
</div>