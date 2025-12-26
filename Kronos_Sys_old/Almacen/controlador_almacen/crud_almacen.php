<?php
error_reporting(0);

if(isset($_POST['b']))
{
  $producto = $_POST['b'];

  if(!empty($producto)) {
    getunidades($producto);
  }


}
if(isset($_POST['c']) and isset($_POST['p']) and isset($_POST['n']))
{
  include "../modelo_almacen/entrada.php";
  session_start();
  if(!empty($_POST['c']) and !empty($_POST['p']))
  {
    $fecha=date('d/m/Y');
    $hora=date('g:i:s');
    $entradax=new entrada();
    $entradax->setNombre($_POST['n']);
    $entradax->setHora($hora);
    $entradax->setFecha($fecha);
    $entradax->setCantidad($_POST['c']);
    $entradax->setCodigo($_POST['p']);
   if(isset($_SESSION['arrayProductos']))
   {
      array_push($_SESSION['arrayProductos'],$entradax);
      refreshTable();
   }
   
    
    



}
}
if(isset($_POST['mat_in']))
{
  $entrada = $_POST['mat_in'];

  if(!empty($entrada)) {
    getEntradas($entrada);
  }


}
if(isset($_POST['entrada']) && isset($_POST['cantidad']))
{
  $entrada = $_POST['entrada'];
  $cantidad = $_POST['cantidad'];

  if(!empty($entrada) && !empty($cantidad)) {
    setEntrada($entrada,$cantidad);
  }


}
if(isset($_GET['doc']))
{
   session_start();

  if($_GET['doc']==19)
  {
    $envio = serialize($_SESSION['arrayProductos']);
    $envio = base64_encode($envio);
    $envio = urlencode($envio);
    header("location:../EnlaceCompra.php?envio=".$envio."");
  }
  else
  {

  }
  
}
function getunidades($b) {
 include("../../Database/SQLConnection.php");

 $sql = sqlsrv_query($SQLconn,"SELECT p.ccodigoproducto,p.cnombreproducto,(select cnombreunidad from admunidadesmedidapeso where cidunidad=p.cidunidadbase) as unidad from admproductos p WHERE CCODIGOPRODUCTO='".$b."'");

 $runi=sqlsrv_fetch_array($sql,SQLSRV_FETCH_ASSOC);
 if(!$runi)
 {    
  ?>
  <div class='alert alert-danger alert-dismissible fade in'>
    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
    <strong>Error</strong> ,El código ingresado no existe.
  </div>
  <?php
}   
else
{
  ?>
  <div class="modal-body" style="overflow:hidden;" id="formCantidad">
    <h4 id="nameProduct"><?php echo $runi['cnombreproducto']?></h4><h3><?php echo $runi['ccodigoproducto'];?></h3>
    <form id="setProducto" method="POST">
      <div class="col-xs-4">
        <input  type="number" placeholder="<?php echo $runi['unidad']?>" name="txtcantidad" id="cantidad" value="" class="form-control">
      </div>
    </form>
  </div>
  <?php
}
}
function getEntradas($entrada) {
 include("../../Database/SQLConnection.php");
 $SQL=sqlsrv_query($SQLconn,"SELECT a.CNUMEROMOVIMIENTO as mov,(SELECT CNOMBREALMACEN FROM admAlmacenes where CIDALMACEN=a.CIDALMACEN) as almacen,CONCAT(CUNIDADES,' ',(SELECT CABREVIATURA FROM admunidadesMedidaPeso WHERE CIDUNIDAD=a.CIDUNIDAD)) as cantidad,
  (SELECT CNOMBREPRODUCTO FROM admProductos WHERE CIDPRODUCTO=a.CIDPRODUCTO) as producto from admmovimientos a where a.ciddocumento=".$entrada."");
  ?>

  <div class="modal-dialog modal-lg">

    <script type="text/javascript">

    </script>
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2>Entrada de materia prima</h2>
      </div>
      <div class="modal-body">
        <form method="post" name="formu" id="formu" role="form">
          <div class="panel panel-default">

            <div class="panel-heading"><b class="titulo">Agregar entrada</b>
            </div>
            <div class="panel-body">
             <div class="col-xs-3">
              <label for="cdgproducto">Producto</label>
              <input class="form-control" placeholder="Código" autofocus=""  required="" type="text" name="cdgproducto" id="cdgproducto">
            </div>
            <div class="col-xs-3">
              <label for="cdgcantidad">Cantidad</label>
              <input  class="form-control" type="text" name="cdgcantidad" id="cdgcantidad" required="">
            </div>
            <button style="float:right;" class="btn btn-success" name="save_in" id="save_in">Guardar</button>
          </div>
        </form>

        <h4>Entradas de materia prima</h4>
        <div class="table-responsive">
          <table class="table table-hover">
            <?php
//$SQlin=$mysqli->fetch_object();

            ?>
            <th>Acciones</th><th>Código</th><th>Nombre</th><th>Almacén</th><th>Cantidad</th><th>Costo</th><th>Total</th>
            <?php
            while($row=sqlsrv_fetch_array($SQL,SQLSRV_FETCH_ASSOC))
            {
              ?>
              <tr>
                <td><button id="1" class="btn btn-warning">Modificar</button></td>
                <td><?php echo $row['mov'] ?></td>
                <td><?php echo $row['producto'] ?></td>
                <td><?php echo $row['almacen'] ?></td>
                <td><?php echo $row['cantidad'] ?></td>
                <td>$90.65</td>
                <td>4,079.25</td>
              </tr>
              <?php  
            }
            if($entrada=="2")
              {?>
               <tr>
                <td><button id="1" class="btn btn-warning">Modificar</button></td>
                <td>45904</td>
                <td>Cinta canela 48x150 ...</td>
                <td>Materia prima</td>
                <td>45 kg</td>
                <td>$90.65</td>
                <td>4,079.25</td>
              </tr>
              <tr>
                <td><button id="1" class="btn btn-warning">Modificar</button></td>
                <td>45904</td>
                <td>Grapas sello metalico galvanizado ...</td>
                <td>Materia prima</td>
                <td>45 kg</td>
                <td>$90.65</td>
                <td>4,079.25</td>
              </tr>
              <?php
            }
            ?>
          </table>
        </div>
        <div class="modal-footer">

        </div>
      </div>
    </div>
  </div>
  <?php

}
function setCantidad($entrada,$cantidad)
{
  include("../modelo_almacen/entrada.php");
  include("../../Database/SQLConnection.php");
  $SQL=sqlsrv_query($SQLConn,"SELECT CNOMBREPRODUCTO,CIDPRODUCTO from admproductos WHERE CCODIGOPRODUCTO=".$entrada."");
  $row=sqlsrv_fetch_array($SQL, SQLSRV_FETCH_ASSOC);
  $entradax=new entrada();
  $entradax->setNombre($row['CNOMBREPRODUCTO']);
  $entradax->setHora($hora=time());
  $entradax->setFecha($fecha=date()->format('d/m/Y'));
  $entradax->setCantidad($cantidad);
  $entradax->setID($row['CIDPRODUCTO']);
  array_push($_SESSION['array_in'], $entradax);
  echo "<p>Llego aca men</p>";
}
function refreshTable()
{
  session_start();
  ?>
  <h4 class="ordenMedio">Movimientos actuales</h4>
  <div class="table-responsive" id="tablein" style="border-radius:2px;border-color:gray">
    <table class="table table-hover" border="1" style="border-radius:2px;border-color:gray">
      <td>Código</td><td>Nombre</td><td>Cantidad</td><td>Hora</td><td>Fecha</td>
      <?php
      $arrayProductos=$_SESSION['arrayProductos'];
      for($i=0;$i<count($arrayProductos);$i++)
        {?>
         <tr>
          <th><?php echo $arrayProductos[$i]->getCodigo();?></th>
          <th><?php echo $arrayProductos[$i]->getNombre();?></th>
          <th><?php echo $arrayProductos[$i]->getCantidad();?></th>
          <th><?php echo $arrayProductos[$i]->getHora();?></th>
          <th><?php echo $arrayProductos[$i]->getFecha();?></th>
        </tr>
        <?php
      }?>

    </table>
  </div>
  <?php
}

?>