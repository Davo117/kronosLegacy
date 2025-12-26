    <option value=0>--</option>
    <?php 
    $valor12= 'pro'.$s;
    $Sic=$MySQLiconn->query("SHOW COLUMNS FROM $valor12");
    while($raos=$Sic->fetch_array()){
      if($raos['Field']!="id" ){
?>
<option value="<?php echo $raos['Field'];?>"><?php echo $raos['Field'];?></option>
<?php
}
}
?>




<?php
include("Controlador_misce/db_Producto.php");

//Mientras que se tengan resultados se le asignan a $rows mediante 
$s=$_POST['s'];
$valor12= 'pro'.$s;


$Sic=$MySQLiconn->query("SHOW COLUMNS FROM $valor12");
while($raos=$Sic->fetch_array()){ ?>
	<option value="<?php echo $row1['descripcionProceso'];?>"><?php echo $row1['descripcionProceso'];?></option>
	<?php 
} ?> 
</datalist>
</select>