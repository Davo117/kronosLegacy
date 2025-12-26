<?php
$buf=getcwd();
?>
<!doctype html>
<html lang="es">
<div class="barra_lateral_produccion">
	<div class="logo-menu">
			<a  href="../Menu.php" ><IMG style="padding-top:5%;width:80%;height:80%" src="../pictures/logo-labro2.png" title='Menu principal'></IMG><a>
			</div>
<p style="font-size:4vh;font-family: Sansation;"   id="titulo">Producción</p>
<div class="caja" style="">
  <select onChange="mostrarTipo();window.location='Produccion_Registro.php'" name="comboTipos" id="comboTipos">
<?php

$resultado=$MySQLiconn->query("SELECT tipoproducto.tipo,cache.dato from tipoproducto,cache where cache.id=6 and tipoproducto.baja=1");
while($row=$resultado->fetch_array())
{
   
    if($row['tipo']==$row['dato']){
?>
<option selected value="<?php echo $row['tipo'];?>"><?php echo $row['tipo'];?></option>
<?php
}
else
{
    ?>
 <option value="<?php echo $row['tipo'];?>"><?php echo $row['tipo'];?></option>
 <?php
}
}
?>
  </select>
</div>
</div>
</html>
