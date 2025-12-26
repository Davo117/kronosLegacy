<?php
echo "<div class='table-responsive'><table border='0'  cellpadding='15' class='table table-hover'>
<th style='width:120px;'>Acciones</th>
<th style='width:300px;'>Lote</th>
<th>Longitud</th>
<th style='width:120px;'>Peso</th>
<th>ID Lote</th>
<th>noop</th>
<th style='width:120px;'>Seleccionar</th>"; ?>


<?php
include 'db_materiaPrima.php';
//$desc=$_SESSION['lote'];//Aqui se extrae de la sesion la descripcion de la impresion ala cual corresponde el juego de cilindros
if(!empty($_GET['q']))
{
$q=$_GET['q'];

$MySQLiconn->query("UPDATE cache set dato='$q' where id=5");
}

$tarima=$_SESSION['tarima'];
$peso=0;
$longitud=0;
/*$reslt = $MySQLiconn->query("SELECT dato from cache where id=5");
$rau = $reslt->fetch_array();
$bloqueDescripcion=$rau['dato'];*/
$contador=0;
$resultado = $MySQLiconn->query("SELECT * FROM tblotes where bloque='$bloqueDescripcion' && tarima='$tarima'");
while ($rows = $resultado->fetch_array()) {
echo "<tr>";

if(isset($_GET['edit']) && $_GET['edit']==$rows['idLote'])
{?>
    <td class='selected'>
     <?php
if($rows["estado"]==0){
?>

    <a href="?del=<?php echo $rows["idLote"]; ?>" ><IMG src="../pictures/deletProducto.png" title='calendario'></a>
    <a href="?produ=<?php echo $rows["idLote"]."&bloqueDescripcion=$bloqueDescripcion"; ?>" onclick="return confirm('¿Desea liberar el lote<?php echo ' ´'.$rows['referenciaLote'].'´';?>?'); "  ><IMG src="../pictures/factory.png" title='Liberar lote'></a>
    </td> 
<?php
}
else if($rows["estado"]==1)
{
    ?>

    <a href="?calen=<?php echo $rows["idLote"]; ?>" onclick="return confirm('¿Retirar el lote <?php echo ' '.$rows['referenciaLote'];?> de producción?'); " ><IMG src="../pictures/calendar.png" title='En producción'></a></td>
    <?php
}
else if($rows["estado"]==2)
{
    ?>
    
    <a href="?repor=<?php echo $rows["idLote"]."&bloqueDescripcion=$bloqueDescripcion"?>" onclick="return confirm('El lote   <?php echo ' '.$rows['referenciaLote'];?> ya esta liberado,¿desea retornar?')"; ><IMG src="../pictures/verify.png" title='Liberado'></a></td>
    <?php
}
else if($rows["estado"]==3)
{
    ?>
     
    <a href="?del=<?php echo $rows["idLote"]; ?>" onclick="return confirm('Esta seguro que desea eliminar el lote  <?php echo ' '.$rows['referenciaLote'];?>? !'); " ><IMG src="../pictures/deletProducto.png" title='Eliminar'></a></td>
    <?php
}
else if($rows["estado"]==4)//Eliminación definitiva
{
    ?>
    <td><a href="?acti=<?php echo $rows["idLote"]."&bloqueDescripcion=$bloqueDescripcion"; ?>"><IMG src="../pictures/unnamed.png" title='Modificar'></a>  
    <a href="?delfin=<?php echo $rows["idLote"]."&bloqueDescripcion=$bloqueDescripcion"?>" onclick="return confirm('¿Deseas eliminar definitivamente el lote  <?php echo ' '.$rows['referenciaLote'];?>?'); " ><IMG src="../pictures/definitivo.png" title='Eliminar'></a></td>
    <?php
}
else if($rows["estado"]==5)//Lote producido
{
    ?>
    <td> 
    <a href="?end=<?php echo $rows["referenciaLote"]; ?>" onclick="return confirm('El lote  <?php echo ' '.$rows['referenciaLote'];?> ya fué impreso,¿Ir al seguimiento de lotes?'); " ><IMG src="../pictures/gearfourth.png" title='Finalizó proceso'></a></td>
    <?php
}
//Traemos la tabla y la imprimimos donde pertenece
echo "<td class='selected' title='Referencia'>".$rows['referenciaLote']."</td>
<td class='selected' title='Longitud'>".$rows['longitud']."</td>
<td class='selected' title='Peso'>".$rows['peso']."</td>
<td class='selected' title='Numero de lote por tarima'>".$rows['numeroLote']."</td>
<td class='selected' title='-'>".$rows['noop']."</td>";
if($rows['estado']==0)
{
   echo "<td class='selected' title='Seleccionar lote'><input style='float:right;' type='checkbox' name='check".$contador."' value='".$rows['idLote']."'></td>";
}
else
{
   echo  "<td  class='selected' title=':)'><input style='float:right;' type='hidden' name=0></td>";
}
echo "</tr>";
}
else
{
    if($rows["estado"]==0){
?>
<td><a>
    <a href="?edit=<?php echo $rows["idLote"]."&bloqueson=$bloqueDescripcion"; ?>" ><IMG src="../pictures/modificarproducto.png" title='Modificar'></a>  
    <a href="?del=<?php echo $rows["idLote"]."&bloqueson=$bloqueDescripcion"; ?>" ><IMG src="../pictures/deletProducto.png" title='calendario'></a>
    <a href="?produ=<?php echo $rows["idLote"]."&bloqueDescripcion=$bloqueDescripcion"; ?>" onclick="return confirm('¿Desea liberar el lote<?php echo ' ´'.$rows['referenciaLote'].'´';?>?'); "  ><IMG src="../pictures/factory.png" title='Liberar lote'></a>
    </td> 
<?php
}
else if($rows["estado"]==1)
{
    ?>
    <td><a><IMG style='filter:grayscale(120%);' src="../pictures/modificarproducto.png" title='Modificar'></a>  
    <a href="?calen=<?php echo $rows["idLote"]; ?>" onclick="return confirm('¿Retirar el lote <?php echo ' '.$rows['referenciaLote'];?> de producción?'); " ><IMG src="../pictures/calendar.png" title='En producción'></a></td>
    <?php
}
else if($rows["estado"]==2)
{
    ?>
    <td><a><IMG style='filter:grayscale(120%);' src="../pictures/modificarproducto.png" title='Modificar'></a>  
    <a href="?repor=<?php echo $rows["idLote"]."&bloqueDescripcion=$bloqueDescripcion"?>" onclick="return confirm('El lote   <?php echo ' '.$rows['referenciaLote'];?> ya esta liberado,¿desea retornar?')"; ><IMG src="../pictures/verify.png" title='Liberado'></a></td>
    <?php
}
else if($rows["estado"]==3)
{
    ?>
    <td><a href="?edit=<?php echo $rows["idLote"]."&bloqueDescripcion=$bloqueDescripcion"; ?>" ><IMG src="../pictures/modificarproducto.png" title='Modificar'></a>  
    <a href="?del=<?php echo $rows["idLote"]."&bloqueson=$bloqueDescripcion"; ?>" onclick="return confirm('Esta seguro que desea eliminar el lote  <?php echo ' '.$rows['referenciaLote'];?>? !'); " ><IMG src="../pictures/deletProducto.png" title='Eliminar'></a></td>
    <?php
}
else if($rows["estado"]==4)//Eliminación definitiva
{
    ?>
    <td><a href="?acti=<?php echo $rows["idLote"]."&bloqueDescripcion=$bloqueDescripcion"; ?>" ><IMG src="../pictures/unnamed.png" title='Modificar'></a>  
    <a href="?delfin=<?php echo $rows["idLote"]."&bloqueDescripcion=$bloqueDescripcion"?>" onclick="return confirm('¿Deseas eliminar definitivamente el lote  <?php echo ' '.$rows['referenciaLote'];?>?'); " ><IMG src="../pictures/definitivo.png" title='Eliminar'></a></td>
    <?php
}
else if($rows["estado"]==5)//Lote producido
{
    ?>
    <td> 
    <a href="?end=<?php echo $rows["referenciaLote"]; ?>" onclick="return confirm('El lote  <?php echo ' '.$rows['referenciaLote'];?> ya fué impreso,¿Ir al seguimiento de lotes?'); " ><IMG src="../pictures/gearfourth.png" title='Finalizó proceso'></a></td>
    <?php
}
//Traemos la tabla y la imprimimos donde pertenece
echo "<td title='Referencia'>".$rows['referenciaLote']."</td>
<td title='Longitud'>".$rows['longitud']."</td>
<td title='Peso'>".$rows['peso']."</td>
<td title='Numero de lote por tarima'>".$rows['numeroLote']."</td>
<td title='-'>".$rows['noop']."</td>";
if($rows['estado']==0)
{
   echo "<td title='Seleccionar lote'><input style='float:right;' type='checkbox' name='check".$contador."' value='".$rows['idLote']."'></td>";
}
else
{
   echo  "<td  title=':)'><input style='float:right;' type='hidden' name=0></td>";
}
echo "</tr>";
}

$peso=$peso+$rows['peso'];
$longitud=$longitud+$rows['longitud'];
$peso=number_format($peso, 2, '.', '');

$longitud=number_format((float)$longitud, 2, '.', '');
$contador=$contador+1;
}
$resultado = $MySQLiconn->query("SELECT tarima from tblotes where bloque='$bloqueDescripcion' && tarima='$tarima'");
$rows = $resultado->fetch_array();
print("<th colspan=2>Info Tarima</th>");
print("<tr>");
print("<td  colspan=2 Style=font:'bold' title='Lote'>Tarima:<b  style='font:bold 100% Sansation;'>".$rows["tarima"]."</b><br>Peso:<b  style='font:bold 100% Sansation;'>$peso kgs</b><br>Longitud:<b  style='font:bold 100% Sansation;'>$longitud mts</b></td>");
print("</tr>");
?>
<input type="hidden" name="bloque" value="<?php echo $rows['tarima']."|$bloqueDescripcion";?>">
 <button  class="btn btn-large btn-primary" data-toggle="confirmation"
        data-btn-ok-label="Continue" data-btn-ok-class="btn-success"
        data-btn-ok-icon-class="material-icons" data-btn-ok-icon-content="check"
        data-btn-cancel-label="Stoooop!" data-btn-cancel-class="btn-danger"
        data-btn-cancel-icon-class="material-icons" data-btn-cancel-icon-content="close"
        data-title="Is it ok?" data-content="This might be dangerous" onclick="return confirm('¿Desea liberar los lotes seleccionados?');" style="float:right;" type="submit" title='Liberar lotes seleccionados' name="sendAll"><IMG style="padding-right:10px;padding-left:10px;padding-top:4px;padding-bottom:4px" src="../pictures/factory.png"></IMG></button>
 <?php
 /*<button  onclick="return confirm('¿Desea eliminar los lotes seleccionados?');" style="margin-left:1000px;background-color:white;float:left;" type="submit" title='Liberar lotes seleccionados' name="sendAll"><IMG style="padding-right:10px;padding-left:10px;padding-top:4px;padding-bottom:4px" src="../pictures/definitivo.png"></IMG></button>
 <button  onclick="return confirm('¿Desea retirar de produccion los lotes seleccionados?');" style="margin-left:900px;background-color:white;float:left;" type="submit" title='Liberar lotes seleccionados' name="sendAll"><IMG style="padding-right:10px;padding-left:10px;padding-top:4px;padding-bottom:4px" src="../pictures/verify.png"></IMG></button>
 */?>

 <a title="Seleccionar todos" href="javascript:seleccionar_todo(<?php echo "tabla".$i; ?>);" ><img src="../pictures/check_all.png"></a>
            <a title="Deseleccionar todos" href="javascript:deseleccionar_todo(<?php echo "tabla".$i; ?>);" ><img src="../pictures/uncheck.png"></a>

 <?php
 print("<br><p id='mostrar' style='color:#585858' >Tarima:<b style='font:bold 100% Sansation;'>".$_SESSION['tarima']."</b> &nbsp;&nbsp;&nbsp;   Lotes:<b style='font:bold 100% Sansation;'>".$contador."</b></p>");

