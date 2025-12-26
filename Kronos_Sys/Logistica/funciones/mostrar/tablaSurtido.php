<?php
//Mientras que se tengan resultados se le asignan a $rows mediante 
//ORDER BY ID DESC, ASC;
$resultado = $MySQLiconn->query("SELECT * FROM $confirProd where bajaConfi=1 && idConfi='".$_GET['comboSurtido']."' ORDER BY idConfi DESC;");
$rows=$resultado->fetch_array();
if($rows['enlaceEmbarque']!=0){
  $respuesta= $MySQLiconn->query("SELECT numEmbarque FROM $embarq where idEmbarque='".$rows['enlaceEmbarque']."'");

$rowR=$respuesta->fetch_array();
echo '<div class="container-fluid col-xs-12 justify-content-between text-center titulo">Embarque: '.$rowR['numEmbarque'].' </div>';
}
else{
echo '<div class="container-fluid col-xs-12 justify-content-between text-center titulo">Embarques Compatibles</div>';
?>
<input type="text" id="dato1" name="dato1" value="<?php echo $rows['idConfi'];  ?>"style="visibility: hidden;">
<input type="text" id="dato2" name="dato2" value="<?php echo $rows['cantidadConfi'];  ?>" style="display: none;">
<input type="text" id="dato3" name="dato3" value="<?php echo $rows['prodConfi'];  ?>" style="visibility: hidden;">

<?php 
  $resp1= $MySQLiconn->query("SELECT descripcionImpresion FROM impresiones where id='".$rows['prodConfi']."'");
  $rowI=$resp1->fetch_array();



  echo "<div class='col-xs-12'><p> Producto: <b>"; echo $rowI["descripcionImpresion"]; echo "</b></p></div>
  <div class='col-xs-12'><p>Confirmado: <b>"; echo $rows["cantidadConfi"]; echo "</b></p></div>
  <div class='col-xs-3 text-left'><p>Fecha de embarque: <b>"; echo $rows["embarqueConfi"]; echo "</b></p></div>
  <div class='col-xs-3 text-left'><p>Fecha de entrega: <b>"; echo $rows["entregaConfi"]; echo "</b></p></div>
  <div class='col-xs-10'><p>Referencia: <b>"; echo $rows["referenciaConfi"]; echo "</b></p></div>
  <div class='col-xs-3'><p>Embarques: ";

  $sucur=$MySQLiconn->query("SELECT sucFK FROM $tablaOrden where idorden='".$rows['ordenConfi']."';");
  $sucrow=$sucur->fetch_array();

  $embarquery=$MySQLiconn->query("SELECT idEmbarque, numEmbarque, empaque FROM $embarq where sucEmbFK='".$sucrow['sucFK']."' && cantidad='' && producto='' && prodEmbFK='".$rows["prodConfi"]."' && bajaEmb=1 ORDER BY idEmbarque DESC;");


  echo '<select id="slctCdgEmbarque" name="slctCdgEmbarque" required data-live-search="true"  class="selectpicker show-menu-arrow form-control" style="width:200px;">
    <option value="">Elige un embarque</option>';

  while ($rowquery = $embarquery->fetch_array()){ ?>
    <option value="<?php echo $rowquery['idEmbarque'];?>"><?php echo $rowquery['numEmbarque'].'|'.$rowquery['empaque'];?></option>
    <?php  
  }    
  echo "</select></p></div>
  <div class='col-xs-9'>
  <button class='btn btn-default' type='submit' name='save'>Guardar</button></div>";
  $resultado->close(); 
} ?>