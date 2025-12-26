<style type="text/css">
.lotesSeleccionados{     -webkit-box-shadow: 0px 1px 5px 0px rgba(143,143,143,1);    -moz-box-shadow: 0px 1px 5px 0px rgba(143,143,143,1);   box-shadow: 0px 1px 5px 0px rgba(143,143,143,1);       width: 91%;       font:80% Sansation;     text-align:justify;  }


p.irrelevance{    font:15px Sansation; color:black; }
</style>

<?php 
$resultado = $MySQLiconn->query("SELECT * FROM $devolucion where id='".$_GET['u']."';");
$r = $resultado->fetch_array(); 
$_SESSION['folioDev']=$r['folio']; ?>
<div id="page-wrapper">
	<div class="container-fluid">
<form method="post" name="formulary" id="formulary">
<p id="titulo">Desempaque de Productos</p>

<div  class="fields"><p>Folio</p>
<p><b><?php echo $r['folio']; ?></b></p>
</div>

<div  class="fields"><p>Código</p>
<p><b><?php echo $r['codigo']; ?></b></p>
</div>

<div  class="fields"><p>Tipo</p>
<p><b><?php echo $r['tipo']; ?></b></p>
</div>

<div  class="fields"><p>Sucursal</p>
<p><b><?php echo $r['sucursal']; ?></b></p>
</div>

<div  class="fields" style="clear: left;"><p>Fecha Documento</p>
<p><b><?php echo $r['fechaDev']; ?></b></p>
</div>
<div  class="fields"><p>Producto</p>
<p><b><?php echo $r['producto']; ?></b></p>
</div>

</form>
<br>

<center>
<form>
<?php
$canti2=$MySQLiconn->query("SELECT count(referencia) as cuenta FROM caja where producto='".$r['producto']."' && cdgEmbarque='".$r['codigo']."' && baja='3'");
$canti3=$MySQLiconn->query("SELECT count(referencia) as cuenta FROM rollo where producto='".$r['producto']."' && cdgEmbarque='".$r['codigo']."' && baja='3'");
$suma2=$canti2->fetch_array();
$variable='caja';
if ($suma2['cuenta']=='0') {
  $suma2=$canti3->fetch_array();
  $variable='rollo';
}
        
$SQL=$MySQLiconn->query("SELECT * FROM $variable where producto='".$r['producto']."' && cdgEmbarque='".$r['codigo']."' && baja='3' && cdgDev='' ORDER BY referencia");   ?>

<p style=" margin-top: 1%; text-align:justify; float:left;  margin-left:29%; ">Empaques Añadidos(<?php echo $suma2['cuenta']; ?>)</p>

<div style=" overflow-y:auto; overflow-x:auto; float:left; margin-left: -20%; 
   width: 25%;height:400px;
   font:80% Sansation;  margin-top: 4%;
   text-align:justify;">

<?php 
while ($row=$SQL->fetch_array()) {  ?>

  <div class='lotesSeleccionados'>

  <p class='irrelevance'><td> <a  href="?devT=<?php echo $row["id"].'&tipo='.$variable.'&r=--&u='.$_GET['u']; ?>" ><IMG src="funciones/img/unboxing.png" title='Modificar' style="margin-bottom: -4%;"></a> 
  <?php
  echo "Empaque: <b style='font:bold 100% Sansation;'>"; echo $row['referencia']; echo "</b> &nbsp; Millares: <b style='font:bold 100% Sansation;'>"; echo $row['piezas']; echo "</b></p>

    <p class='irrelevance' style='margin-left:40%;'>Peso: <b style='font:bold 100% Sansation;'>"; echo $row['peso']; echo "</b>
        </td></p><br>
      </div>";
}?>
</div>
<?php 
$canti2=$MySQLiconn->query("SELECT count(referencia) as cuenta FROM caja where producto='".$r['producto']."' && cdgEmbarque='".$r['codigo']."' && baja='5' && cdgDev='".$_SESSION['folioDev']."'");
$canti3=$MySQLiconn->query("SELECT count(referencia) as cuenta FROM rollo where producto='".$r['producto']."' && cdgEmbarque='".$r['codigo']."' && baja='5' && cdgDev='".$_SESSION['folioDev']."'");
$suma2=$canti2->fetch_array();
$variable='caja';
if ($suma2['cuenta']=='0') {
  $suma2=$canti3->fetch_array();
  $variable='rollo';
}

$SQL=$MySQLiconn->query("SELECT * FROM $variable where producto='".$r['producto']."' && cdgEmbarque='".$r['codigo']."' && baja='5' && cdgDev='".$_SESSION['folioDev']."' ORDER BY referencia");  ?>


<p style=" margin-top: 1%; text-align:justify; float: left; margin-left: 5%; ">Empaques en Devolución(<?php echo $suma2['cuenta']; ?>)</p>
<br>

<div style=" overflow-y:auto;overflow-x: auto; float: left; margin-left: 5%;  margin-top: 1%; width: 25%;height:400px; font:80% Sansation; text-align:justify;">
<?php 
while ($row=$SQL->fetch_array()) {  ?>

  <div class='lotesSeleccionados'>

  <p class='irrelevance'><td> <a  href="?devF=<?php echo $row["id"].'&tipo='.$variable.'&r=--&u='.$_GET['u']; ?>" ><IMG src="funciones/img/inboxing.png" title='Retornar'  style="margin-bottom: -4%;"></a> 
  <?php
  echo "Empaque: <b style='font:bold 100% Sansation;'>"; echo $row['referencia']; echo "</b> &nbsp; Millares: <b style='font:bold 100% Sansation;'>"; echo $row['piezas']; echo "</b></p>

    <p class='irrelevance' style='margin-left:40%;'>Peso: <b style='font:bold 100% Sansation;'>"; echo $row['peso']; echo "</b>
        </td></p><br>
      </div>";
}?>
</div>
</form>
</center>