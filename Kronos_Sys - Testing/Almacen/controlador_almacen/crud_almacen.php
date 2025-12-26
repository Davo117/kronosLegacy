<?php
//error_reporting(0);
include('functionStock.php');
if(isset($_GET['envio']))
{
  if(isset($_GET['proveedor']))
  {
      include("../../Database/conexionphp.php");
      include "../modelo_almacen/entrada.php";
      session_start();
      $folio=$_GET['folio'];
      $proveedor=$_GET['proveedor'];
      $fecha=$_GET['fechaIn'];
      $arrayP=$_SESSION['arrayProductos'];
      $mysqli->begin_transaction();
      mysqli_autocommit($mysqli,FALSE);
      $CON1=$mysqli->query("INSERT INTO obelisco.entradas(id,proveedor,fecha) values('".$folio."','".$proveedor."','".$fecha."')");
      $costoT=0;
      $contadorPop=array();
      for($i = 0; $i < count($arrayP); $i++)
      {
        //Para cada operación esto es siempre
        $hora=time('h:i:s');
        $fechacompleta = $arrayP[$i]->getFecha();
        $costoT=$costoT+$arrayP[$i]->getPrecio();
        $CON2=$mysqli->query("INSERT INTO obelisco.movimientos(idDocumento,tipoDoc,producto,codigoB,costo,fechaMov, horaMov,cantidad,lote) values('".$folio."','".'1'."','".$arrayP[$i]->getCodigo()."','".$arrayP[$i]->getCodigoB()."',".$arrayP[$i]->getPrecio().", '".$arrayP[$i]->getFecha()."', '".$arrayP[$i]->getHora()."' ,'".$arrayP[$i]->getCantidad()."', '".$arrayP[$i]->getLote()."')");
      }
      $CON3=$mysqli->query("UPDATE obelisco.entradas SET costoTotal='".$costoT."' WHERE id='".$folio."'");

    if(isset($CON1) and !$CON1 || isset($CON2) and !$CON2 || isset($CON3) and !$CON3){
      $MySQLiconn->ROLLBACK();
      echo"<script>alert('Algo salió mal durante la transacción, inténtelo más tarde')</script>";
    }
    else{
      $mysqli->COMMIT();
      echo"<script>alert('Entrada realizada con éxito')</script>";
      echo "<script>window.location='../Entradas_almacen.php';</script>";
    }
  }
  else
  {
    echo "<script>alert('Proveedor no seleccionado')</script>";
  }
} 

if(isset($_POST['b']))
{
  if(is_numeric($_POST['b']))
  {
    include("../../Database/conexionphp.php");
    $producto = $_POST['b'];
    $SQulin=$mysqli->query("SELECT codigoB FROM obelisco.pzcodes where  codigoB='".$producto."'");
    $rust=$SQulin->fetch_array();
    if(!empty($rust['codigoB']))
    {
      getunidades($producto);
    }
    else 
    {
      echo "<div class='alert alert-danger alert-dismissible fade in'>
              <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
              <strong>¡Error!</strong>, El código ingresado no está disponible.
            </div>";
    }
  }
  else 
    {
      echo "<div class='alert alert-danger alert-dismissible fade in'>
              <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
              <strong>¡Error!</strong>, El código ingresado no existe.
            </div>";
    }
  }

if(isset($_POST['c']) and isset($_POST['p']) and isset($_POST['n']) and isset($_POST['h']) and isset($_POST['fechaIn']))
{
  if(isset($_POST['pr']))
  {
    $pr=$_POST['pr'];
  }
  else
  {
    $pr="";
  }
  if(isset($_POST['un']))
  {
    $un=$_POST['un'];
  }
  else
  {
    $un="";
  }
  if(isset($_POST['cB']))
  {
    $codB=$_POST['cB'];
  }
  else
  {
    $codB="";
  }
  if(isset($_POST['nlote']))
  {
    $lote=$_POST['nlote'];
  }
  else
  {
    $lote="";
  }

  addIn($_POST['c'],$_POST['p'],$_POST['n'],$_POST['h'],$pr,$un,$codB,$lote,$_POST['fechaIn']);
}

function addIn($c,$p,$n,$h,$pr,$un,$codB,$lote,$fecha)
{
  include "../modelo_almacen/entrada.php";
  session_start();
  if(!empty($c) and !empty($p))
  {
    
    $hora=time('h:i:s');
    $entradax=new entrada();
    $entradax->setNombre($n);
    $entradax->setHora(date("H:i:s", $hora));
    $entradax->setFecha($fecha);
    $entradax->setCantidad($c);
    $entradax->setCodigo($p);
    $entradax->setCodigoB($codB);
    $entradax->setLote($lote);
    if(!empty($pr))
    {
      $entradax->setPrecio((double)$pr*(double)$c);
    }
    else
    {
      $entradax->setPrecio(0);
    }
    if(!empty($un))
    {
      $entradax->setUnidades($un);
    }
    else
    {
      $entradax->setUnidades(0);
    }

    $entradax->setHascode($h);
    if(isset($_SESSION['arrayProductos']))
    {
      array_push($_SESSION['arrayProductos'],$entradax);
      refreshTable();
    }
  }
}

if(isset($_POST['show_mov']))
{
  $detalle = $_POST['show_mov'];

  if(!empty($detalle)) {
    getEntradas($detalle);
  }
}

if(isset($_POST['show_prod']))
{
  $detalle = $_POST['show_prod'];

  if(!empty($detalle)) {
    getProductoUbicacion($detalle);
  }
}

if(isset($_POST['show_']))

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
  if(empty($b))
  {
    $b='';
  }
  include("../../Database/SQLConnection.php");
  include("../../Database/conexionphp.php");
  $Squero=$mysqli->query("SELECT producto, porcion, codigoB FROM obelisco.pzcodes where codigoB='".$b."'");
  $fet=$Squero->fetch_assoc();

  if(!empty($fet['producto'])){
    $b=$fet['producto'];
    $cantidad=$fet['porcion'];
    $codigo=$fet['codigoB'];
    $lstSQL = sqlsrv_query($SQLconn,"SELECT p.ccodigoproducto,p.cnombreproducto,p.CIDPRODUCTO from admProductos p WHERE p.CIDPRODUCTO ='".$b."'");
    $runi=sqlsrv_fetch_array($lstSQL,SQLSRV_FETCH_ASSOC);
    $mySQL=$mysqli->query("SELECT u.nombreUnidad as unidad, u.identificadorUnidad as alias, p.hascode, p.hasprice FROM saturno.unidades u inner join obelisco.productosCK p on p.unidad = u.idUnidad WHERE p.producto='".$b."'");
    $rut=$mySQL->fetch_array();      
    $arr_codigoseparado = str_split($codigo);
    ?>
    <?php
    if ($arr_codigoseparado[10] == 1){
      $tipo = 'Virgen';
    }else{
      $tipo = 'Reproceso';
    }
    ?>
     <div class="modal-body" style="overflow:hidden;" id="formCantidad">
        <center style="border-style:solid;border-radius:5px;border-color:#E5E5E5;border-width:2px;padding:5px">
          <h4 id="nameProduct"><?php echo $runi['cnombreproducto']?></h4><h3><?php echo $runi['ccodigoproducto'];?></h3>
          <hr>
          <form id="setProducto" method="POST" style="">
            <div style="width:25%;">
              <input type="number" placeholder="<?php echo $rut['unidad']?>" name="txtcantidad" id="cantidad" value="" class="form-control">
              <input type="hidden" name="txthascode" id="hascode" value="<?php echo $rut['hascode']?>" class="form-control">
              <input type="hidden" name="txthasprice" id="hasprice" value="<?php echo $rut['hasprice']?>" class="form-control">
              <input type="hidden" name="txtcodigoB" id="codigoB" value="<?php echo $codigo?>" class="form-control">
              <input type="hidden" name="txthaslote" id="haslote" value="1" class="form-control">
              <input type="hidden" name="idprodu" id="idprodu" value="<?php echo $runi['CIDPRODUCTO'];?>" class="form-control">
              <input type="number" step="any" style="margin-top:5px" required placeholder="Precio por <?php echo $rut['alias']?>" name="txtprecio" id="precio" value="" class="form-control"> 
              <input type="text" step="any" style="margin-top:5px" required placeholder="Lote" name="txtlote" id="lote" value="" class="form-control">   
              
            </div>
          </form>
        </center>
      </div>
    <?php
  }
  else{  
          ?>
    <div class='alert alert-danger alert-dismissible fade in'>
      <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
      <strong>¡Error!</strong>, El código ingresado no existe.
    </div>
    <?php
  }   
}

function getEntradas($id) {
  include("../../Database/SQLConnection.php");
  include("../../Database/conexionphp.php");
  ?>
  <div class="modal-dialog modal-lg">
  <h4>Lista de productos</h4>
    <div class="container-fluid">
      <div class="table-responsive" id="detalleIn">
      <table class="table table-hover">
          <?php
          $detalleEntrada=$mysqli->query("SELECT * FROM obelisco.movimientos WHERE idDocumento = ".$id." AND tipoDoc = 1");
          ?>
          <thead>
          <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Almacén</th>
            <th>Cantidad</th>
            <th>Costo</th>
            <th>Total</th>
            <th>Tipo Entrada</th>
            <th>Lote</th>
          </tr>
          <thead>
          <tbody>
            <?php
            while($row=$detalleEntrada->fetch_array())
            {
              $SQLSERVER=sqlsrv_query($SQLconn, "SELECT CNOMBREPRODUCTO FROM admProductos where CIDPRODUCTO= ".$row['producto']."");
              $rowSERVER=sqlsrv_fetch_array($SQLSERVER,SQLSRV_FETCH_ASSOC);
              
              $arr_codigoseparado = str_split($row['codigoB']);
              ?>
              <?php
              if ($arr_codigoseparado[10] == 1){
                $tipo = 'Virgen';
              }else{
                $tipo = 'Reproceso';
              }
              ?>
              <tr>              
                <td><?php echo $row['codigoB'];?></td>
                <td><?php echo $rowSERVER['CNOMBREPRODUCTO'];?></td>
                <td><?php echo "Materia Prima";?></td>
                <td><?php echo $row['cantidad'];?></td>
                <td><?php echo number_format(($row['costo'] / $row['cantidad']));?></td>
                <td><?php echo number_format($row['costo'],2);?></td>
                <td><?php echo $tipo; ?></td>
                <td><?php echo $row['lote']; ?></td>
              </tr>   
            <?php
            }
            ?>
          </tbody>
        </table>
      </div>
    </div> 
  </div> 
<?php
}
?>
<?php

function getProductoUbicacion($ubicacion){
  include("../../Database/SQLConnection.php");
  include("../../Database/conexionphp.php");
  ?>
  <div class="modal-dialog modal-lg">
    <h4>Productos</h4>
    <div class="container-fluid">
      <div class="table-responsive" id="detalleIn">
        <table class="table table-hover">
            <?php
           $datos=$mysqli->query("SELECT producto, unidad, place, id FROM obelisco.productosCK WHERE place = '".$ubicacion."'");
           
            ?>
            <thead>
            <tr>
              <th>Nombre</th>
              <th>Almacen</th>
              <th>Cantidad</th>
              <th>Lugar</th>
              <th>ID CONTPAQi</th>
            </tr>
            <thead>
            <tbody>
              <?php
                $fila=$datos->fetch_array();
                if(!empty($fila)){
                  $SQLSERVER=sqlsrv_query($SQLconn, "SELECT CNOMBREPRODUCTO FROM admProductos where CIDPRODUCTO= ".$fila['producto']."");
                  $rowSERVER=sqlsrv_fetch_array($SQLSERVER, SQLSRV_FETCH_ASSOC);
                  $entradas = $mysqli->query("SELECT SUM(cantidad) as cantIn FROM obelisco.movimientos WHERE producto = ".$fila['producto']." AND tipoDoc = 1");
                  $in=$entradas->fetch_array();
                  $salidas = $mysqli->query("SELECT SUM(cantidad) as cantOut FROM obelisco.movimientos WHERE producto = ".$fila['producto']." AND tipoDoc = 2");
                  $out=$salidas->fetch_array();
                  ?>
                  <tr>              
                    <td><?php echo $rowSERVER['CNOMBREPRODUCTO'];?></td>
                    <td><?php echo "Materia Prima";?></td>
                    <td><?php echo $in['cantIn']-$out['cantOut'];?></td>
                    <td><?php echo $fila['place'];?></td>
                    <td><?php echo $fila['producto']; ?></td>
                  </tr>   
                <?php
                }else{
                  echo "N/A = No Asignado";
                ?>
                <tr>              
                    <td><?php echo "N/A";?></td>
                    <td><?php echo "N/A";?></td>
                    <td><?php echo "N/A";?></td>
                    <td><?php echo "N/A";?></td>
                    <td><?php echo "N/A"; ?></td>
                  </tr>   
                  <?php
                }
                ?>
                
            </tbody>
          </table>
        </div>
      </div> 
  </div> 
  <?php
}

function refreshTable(){
  if(!isset($_SESSION['arrayProductos']))
  {
      session_start();
  }
  ?>
  <div class="container-fluid">
  <h4 class="ordenMedio">Movimientos actuales</h4>
      <table class="table table-hover table-bordered">
        <thead>
          <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Cantidad</th>
            <th>Costo</th>
            <th>Hora</th>
            <th>Fecha</th>
            <th>Lote</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $arrayProductos = $_SESSION['arrayProductos'];
          for($i=0;$i<count($arrayProductos);$i++)
            {?>
            <tr>
              <td><?php echo $arrayProductos[$i]->getCodigoB();?></td>
              <td><?php echo $arrayProductos[$i]->getNombre();?></td>
              <td><?php echo number_format($arrayProductos[$i]->getCantidad(),2);?></td>
              <td><?php echo number_format($arrayProductos[$i]->getPrecio(),2);?></td>
              <td><?php echo $arrayProductos[$i]->getHora();?></td>
              <td><?php echo $arrayProductos[$i]->getFecha();?></td>
              <td><?php echo $arrayProductos[$i]->getLote();?></td>
              <td><a class="btn btn-danger glyphicon glyphicon-remove btn-borrar" id="<?php echo $i; ?>" title="Quitar"></a></td>
            </tr>
            <?php
            }
            ?>
          </tbody>
      </table>
    </div>
<?php
}
  if(isset($_POST['eliminar'])){
    include "../modelo_almacen/entrada.php";
    $posicion = $_POST['eliminar'];    
    if(!isset($_SESSION['arrayProductos']))
    {
        session_start();
    }
    unset($_SESSION['arrayProductos'][$posicion]);
    $_SESSION['arrayProductos'] = array_values($_SESSION['arrayProductos']);
    refreshTable();
  }
  ?>
