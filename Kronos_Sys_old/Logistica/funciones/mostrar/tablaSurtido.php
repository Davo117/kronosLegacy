<?php
//Mientras que se tengan resultados se le asignan a $rows mediante 
//ORDER BY ID DESC, ASC;
$resultado = $MySQLiconn->query("SELECT * FROM $confirProd where bajaConfi=1 && idConfi='".$_SESSION['ordenes']."' ORDER BY idConfi DESC;");

$rows=$resultado->fetch_array();

if($rows['enlaceEmbarque']!=0){
echo '<div class="container-fluid col-xs-12 justify-content-between text-center titulo">Embarque: '.$rows['enlaceEmbarque'].' </div>';
}
else{
echo '<div class="container-fluid col-xs-12 justify-content-between text-center titulo">Embarques Compatibles</div>';

  //Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
  $MySQLiconn->query("UPDATE cache SET dato='".$rows["idConfi"]."' WHERE id=32");
  $MySQLiconn->query("UPDATE cache SET dato='".$rows["cantidadConfi"]."' WHERE id=30");
  $MySQLiconn->query("UPDATE cache SET dato='".$rows["prodConfi"]."' WHERE id=31");  

  echo "<div class='col-xs-12'><p> Producto: <b>"; echo $rows["prodConfi"]; echo "</b></p></div>
  <div class='col-xs-12'><p>Confirmado: <b>"; echo $rows["cantidadConfi"]; echo "</b></p></div>
  <div class='col-xs-3 text-left'><p>Fecha de embarque: <b>"; echo $rows["embarqueConfi"]; echo "</b></p></div>
  <div class='col-xs-3 text-left'><p>Fecha de entrega: <b>"; echo $rows["entregaConfi"]; echo "</b></p></div>
  <div class='col-xs-10'><p>Referencia: <b>"; echo $rows["referenciaConfi"]; echo "</b></p></div>
  <div class='col-xs-3'><p>Embarques: ";

  $sucur=$MySQLiconn->query("SELECT sucFK FROM $tablaOrden where orden='".$rows['ordenConfi']."';");
  $sucrow=$sucur->fetch_array();

  $embarquery=$MySQLiconn->query("SELECT * FROM $embarq where sucEmbFK='".$sucrow['sucFK']."' && cantidad='' && producto='' && $embarq.prodEmbFK='".$rows["prodConfi"]."' && bajaEmb=1 ORDER BY idEmbarque DESC;");

  echo '<select id="slctCdgEmbarque" name="slctCdgEmbarque" required class="form-control" style="width:200px;">
          <option value="">Elige un embarque</option>';

  while ($rowquery = $embarquery->fetch_array()){ ?>
    <option value="<?php echo $rowquery['numEmbarque'];?>"><?php echo $rowquery['numEmbarque'].'|'.$rowquery['empaque'];?></option>
    <?php  
  }    
  echo "</select></p></div>
  <div class='col-xs-9'>
  <button class='btn btn-default' type='submit' name='save'>Guardar</button></div>";
  $resultado->close(); 
} ?>