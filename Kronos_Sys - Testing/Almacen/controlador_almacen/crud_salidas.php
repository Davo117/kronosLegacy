<?php
error_reporting(0);

include('functionStock.php');
if(isset($_GET['envio']))
{
  include("conexionobelisco.php");
  include("../../Database/conexionphp.php");
  include "../modelo_almacen/salida.php";
  session_start();
  $folio=$_GET['folio'];
  $fecha=$_GET['fechain'];
  $arrayP=$_SESSION['arrayProductos'];
  $mysqli->begin_transaction();
  mysqli_autocommit($mysqli,FALSE);
  $CON1=$mysqli->query("INSERT INTO obelisco.salidas(id) values('".$folio."')");
  $costoT=0;
  $contadorPop=array();
  for($i=0;$i<count($arrayP);$i++)
  {
    //Para cada operación esto es siempre
    $costoT=$costoT+$arrayP[$i]->getPrecio();
    $CON2=$mysqli->query("INSERT INTO obelisco.movimientos(idDocumento,tipoDoc,producto,codigoB,costo,cantidad,datoextra1) values('$folio','2','".$arrayP[$i]->getCodigo()."','".$arrayP[$i]->getCodigoB()."','".$arrayP[$i]->getPrecio()."','".$arrayP[$i]->getCantidad()."','".$arrayP[$i]->getOperador()."')");
  }
  $CON3=$mysqli->query("UPDATE obelisco.salidas SET costoTotal='".$costoT."' WHERE id='".$folio."'");

  if(isset($CON1) and !$CON1 || isset($CON2) and !$CON2 || isset($CON3) and !$CON3){
    $mysqli->ROLLBACK();
    echo"<script>alert('Algo salió mal durante la transacción, intentelo más tarde')</script>";
  }else{
    $mysqli->COMMIT();
    echo"<script>alert('Salida realizada con éxito')</script>";
    echo("<script>window.location ='../Salidas_almacen.php';</script>");
  }
}

if(isset($_POST['b']))
{
 if(is_numeric($_POST['b']))
 {
  $codigo = $_POST['b'];

  if(!empty($codigo)) 
  {
    getUnidadeswum($codigo);
  }
}
else 
{
  echo "<div class='alert alert-danger alert-dismissible fade in'>
  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
  <strong>Error</strong> ,El código ingresado no existe.
  </div>";
}



}
if(isset($_POST['c']) and isset($_POST['p']) and isset($_POST['n']))
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

  addOut($_POST['c'],$_POST['p'],$_POST['n'],$pr,$un,$codB,$_POST['ope']);
}
function addOut($c,$p,$n,$pr,$un,$codB,$ope)
{
  include("../modelo_almacen/salida.php");
  include("../../Database/conexionphp.php");
  include("../../Database/SQLConnection.php");

  session_start();
  $entradas = $mysqli->query("SELECT SUM(cantidad) as cantIn FROM obelisco.movimientos WHERE producto = ".$p." AND tipoDoc = 1");
  $in=$entradas->fetch_array();
  $salidas = $mysqli->query("SELECT SUM(cantidad) as cantOut FROM obelisco.movimientos WHERE producto = ".$p." AND tipoDoc = 2");
  $out=$salidas->fetch_array();
  $SQLSERVER=sqlsrv_query($SQLconn, "SELECT CNOMBREPRODUCTO FROM admProductos where CIDPRODUCTO= ".$p."");

  $operador=str_pad($ope,3,"0", STR_PAD_LEFT);

  $rowSERVER=sqlsrv_fetch_array($SQLSERVER, SQLSRV_FETCH_ASSOC);

  $stockreal = $in['cantIn'] - $out['cantOut'];
    if(!empty($c) and !empty($p))
    {
      if ($c <= $stockreal) {
        $fecha=date('d/m/Y');
        $hora=date('h:i:s');
        $salidax=new salida();
        $salidax->setNombre($n);
        $salidax->setHora($hora);
        $salidax->setFecha($fecha);
        $salidax->setCantidad($c);
        $salidax->setCodigo($p);
        $salidax->setCodigoB($codB);
        $salidax->setOperador($operador);
        if(!empty($pr))
        {
          $salidax->setPrecio((double)$pr*(double)$c);
        }
        else
        {
          $salidax->setPrecio(0);
        }
        if(!empty($un))
        {
          $salidax->setUnidades($un);
        }
        else
        {
          $salidax->setUnidades(0);
        }
        
      // $salidax->setHascode($h);
        
        if(isset($_SESSION['arrayProductos']))
        {
          array_push($_SESSION['arrayProductos'],$salidax);
          refreshTable();
        }
      }else{
        ?>
        <div class='alert alert-danger alert-dismissible fade in'>
          <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
          <strong>Error</strong>, Stock insuficiente de este producto. <?php echo $rowSERVER['CNOMBREPRODUCTO'];
        ?>
        </div><?php 
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

function getunidadeswum($b) {
 include("../../Database/SQLConnection.php");
 include("../../Database/conexionphp.php");
 include "../modelo_almacen/salida.php";
 session_start();
 $isIt=0;
 for($i=0;$i<count($_SESSION['arrayProductos']);$i++)
 {
  if($_SESSION['arrayProductos'][$i]->getCodigoB()==$b)
  {
    $isIt=1;
  }
}
if($isIt==0){
  $Squero=$mysqli->query("SELECT producto,porcion,codigoB,baja,metros FROM obelisco.pzcodes where codigoB='".$b."'");
  $fet=$Squero->fetch_assoc();
  if(!empty($fet['producto'])){
    if($fet['baja']==1){
      $b=$fet['producto'];
      $cantidad=$fet['porcion'];
      $codigo=$fet['codigoB'];
      $lstSQL = sqlsrv_query($SQLconn,"SELECT p.ccodigoproducto,p.cnombreproducto,p.CIDPRODUCTO from admProductos p WHERE p.CIDPRODUCTO='".$b."'");
      $runi=sqlsrv_fetch_array($lstSQL,SQLSRV_FETCH_ASSOC);
      $mySQL=$mysqli->query("SELECT u.nombreUnidad as unidad,u.identificadorUnidad as alias,p.hascode,p.hasprice FROM saturno.unidades u inner join obelisco.productosCK p on p.unidad=u.idUnidad WHERE p.producto='".$b."'");
      $rut=$mySQL->fetch_array();
      ?>
      <div class="modal-body" style="overflow:hidden;" id="formCantidad">
        <center style="border-style:solid;border-radius:5px;border-color:#E5E5E5;border-width:2px;padding:5px">
          <h4 id="nameProduct"><?php echo $runi['cnombreproducto']?></h4><h3><?php echo $runi['ccodigoproducto'];?></h3>
          <hr>
          <form id="setProducto" method="POST" style="">
            <div style="width:25%;">
              <input type="hidden" name="nombreproducto" id="nombreproducto" value="<?php echo $runi['cnombreproducto'];?>" class="form-control">
              <input type="hidden" name="idproducto" id="idproducto" value="<?php echo $runi['CIDPRODUCTO'];?>" class="form-control">
              <input type="hidden" name="txtcodigoB" id="codigoB" value="<?php echo $codigo?>" class="form-control">
              <input type="number" placeholder="<?php echo $rut['unidad']?>" name="txtcantidad" id="cantidad" value="" class="form-control">
              <input type="text" placeholder="Código de operador" name="txtoperador" id="operador" value="" class="form-control">
            </div>
          </form>
        </center>
      </div>
      <?php

    }
    else{?>
      <div class='alert alert-danger alert-dismissible fade in'>
        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
        <strong>Error</strong>, Aún no se captura la entrada.
        </div><?php
      }
    }
    else{?>
      <div class='alert alert-danger alert-dismissible fade in'>
        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
        <strong>Error</strong>, El código ingresado no existe.
        </div><?php
      }
    }
    else{
      ?>
      <div class='alert alert-danger alert-dismissible fade in'>
        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
        <strong>Error</strong>, Este código ya está agregado.
        </div><?php
      }
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
        include "../modelo_almacen/salida.php";
        $posicion = $_POST['eliminar'];    
        if(!isset($_SESSION['arrayProductos']))
        {
          session_start();
        }
        unset($_SESSION['arrayProductos'][$posicion]);
        $_SESSION['arrayProductos'] = array_values($_SESSION['arrayProductos']);
        refreshTable();
      }

// Getcode almacén (Cargar packing list)
      if(isset($_POST['load']))
      {
        require('../Excel/Classes/PHPExcel/IOFactory.php'); 
        require('../Excel/Classes/PHPExcel.php');
        require('../Excel/Classes/PHPExcel/Reader/Excel2007.php');
        include("../Database/conexionphp.php");
//cargamos el archivo excel(extension *.xls) 
//echo ;
/*
Anteriormente guardaba el archivo del input file
$ruta_base   = "../Documentos/";
$archivo=$ruta_base . basename( $_FILES["uploadlist"]["name"]);
//move_uploaded_file( $_FILES["uploadlist"]["name"], $archivo);
echo $archivo;
if (move_uploaded_file($_FILES['uploadlist']['tmp_name'], $archivo))
    {
       echo "<div class='alert alert-success alert-dismissible fade in'>
      <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
      <strong>Lectura correcta del archivo</strong> ,Se muestran los siguientes datos.
      </div>";
    } else {
        echo "<div class='alert alert-danger alert-dismissible fade in'>
      <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
      <strong>Carga fallida</strong> ,Hubo un error al cargar el archivo.
      </div>";
    }*/
    $objPHPExcel = PHPExcel_IOFactory::load('../Documentos/PackingList.xls');
// Asignamos la hoja excel activa 
    $objPHPExcel->setActiveSheetIndex(0); 
    $i=2;
//$contador=1;
    $msj="";
    if(empty($objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue() !=
      ''))
    {
      $msj=2;
    }
    $array=array();
    $SQL=$mysqli->query("SELECT MAX(cdglist)+1 as max FROM obelisco.pzcodes");
    $row=$SQL->fetch_array();
    $lista=$row["max"];
    if(empty($lista))
    {
      $lista=1;
    }
    while(!empty($objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue())) 
    {   
      /*INVOCACION DE CLASES Y CONEXION A BASE DE DATOS*/
      /** Invocacion de Clases necesarias */

//DATOS DE CONEXION A LA BASE DE DATOS

//$db = mysqli_select_db ("escuela",$mysqli) or die ("ERROR AL CONECTAR A LA BD");

      $referenciaLote=$objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
//$arrayNumero = explode("-", $referenciaLote, 2);
      $array[$i]['producto']=$objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
      $array[$i]['metros']=$objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();
      $array[$i]['kilos']=$objPHPExcel->getActiveSheet()->getCell('C'.$i)->getValue();
      $array[$i]['caja']=$objPHPExcel->getActiveSheet()->getCell('D'.$i)->getValue();
      $lstSQL = sqlsrv_query($SQLconn,"SELECT p.CIDPRODUCTO from admProductos p WHERE p.CCODIGOPRODUCTO='".$array[$i]['producto']."'");
      $runi=sqlsrv_fetch_array($lstSQL,SQLSRV_FETCH_ASSOC);
      $SQL=$mysqli->query("SELECT class,hascode,(SELECT MAX(consec)+1 from obelisco.pzcodes WHERE producto='".$runi["CIDPRODUCTO"]."' and date(fecha_alta)='".date('y-m-d')."') as consec FROM obelisco.productosCK WHERE producto='".$runi["CIDPRODUCTO"]."'");
      $row=$SQL->fetch_array();
      $clase=$row["class"];
      $contador=$row['consec'];
      if($contador==0)
      {
        $contador=1;
      }
      $fecha=date('dmy');
      $array[$i]['codigo']=$clase.$fecha.str_pad($contador,  2, "0", STR_PAD_LEFT);
      $valNum=0;
      if($row['hascode']==1 and $contador<=99 and !empty($runi["CIDPRODUCTO"]))
      {
        $mysqli->query("INSERT INTO obelisco.pzcodes(codigoB,consec,cdglist,metros,producto,porcion,caja) values('".$array[$i]['codigo']."','".$contador."','".$lista."','".$array[$i]['metros']."','".$runi["CIDPRODUCTO"]."','".$array[$i]['kilos']."','".$array[$i]['caja']."')");
      }
      else
      {
        $msj=1;
      }

      $i++;
    }
    if($msj==2)
    {
     echo "<div class='alert alert-danger alert-dismissible fade in'>
     <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
     <strong>Error de archivo</strong> ,El archivo de carga no contiene datos.
     </div>";
   }
   else if($msj==1)
   {
     echo "<div class='alert alert-warning alert-dismissible fade in'>
     <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
     <strong>Advertencia</strong> ,Algunos códigos no fueron generados ya que no cumplen con las características necesarias.
     </div>";
   }
   else if($msj="")
   {
    echo "<div class='alert alert-success alert-dismissible fade in'>
    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
    <strong>Operación exitosa</strong> ,Códigos generados.
    </div>";
  }
/*for($i=2;$i<count($array);$i++)
{

}*/
//echo "<META HTTP-EQUIV='REFRESH' CONTENT='0; URL=Materia_prima/MateriaPrima_Bloques.php'>";
}
/*
if(isset($_POST['genericodes']))
{
  include("../Database/conexionphp.php");
  $familia=$_POST['familia'];
  $proceso=$_POST['proceso'];
  $division=$_POST['division'];
  $entrada=$_POST['entrada'];
  $datop = $proveedor=$_POST['cdgproveedor'];
  $datop=str_pad($datop,3,"0"); 

 $producto=$_POST['cdgproducto'];
 $producto=str_pad($datod,4,"0");

  $cantidad=$_POST['mountcodes'];
  if($cantidad<=99 and !empty($familia.$proceso.$division.$datop.$producto.$entrada))
  {
    $anio=date('y');
    $anio=substr($anio,1);
    $SQL=$mysqli->query("SELECT MAX(cdglist)+1 as max FROM obelisco.pzcodes");
    $row=$SQL->fetch_array();
    $lista=$row["max"];
    if($lista==0)
    {
      $lista=1;
    }
    
   
    //quitar $i y reemplazar la consulta para que cambien los datos
      $codigo=($cantidad=$familia.$proceso.$division.$datop.$producto.$entrada);
      $mysqli->query("INSERT INTO obelisco.pzcodes(codigoB,consec,cdglist,producto) values('".$codigo."','".$contador."','".$lista."','".$producto."')");
    
    echo "<div class='alert alert-success alert-dismissible fade in'>
      <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
      <strong>Operación exitosa</strong> ,Códigos generados.
      </div>";
  }
}*/

if(isset($_POST['genericodes']))
{
  include("../Database/conexionphp.php");
  $cantidad=$_POST['mountcodes'];
  $familia=$_POST['familia'];
  $proceso=$_POST['proceso'];
  $division=$_POST['division'];
  $entrada=$_POST['entrada'];
  $prov=$_POST['cdgproveedor'];
  $producto=$_POST['cdgproducto'];
  $producto=str_pad($producto,4,"0", STR_PAD_LEFT);
  $datop=$POST['cdgproveedor'];
  $datop=str_pad($prov,3,"0", STR_PAD_LEFT);


  if($cantidad<=99 and !empty($producto))
  {

    $SQL=$mysqli->query("SELECT MAX(cdglist)+1 as max FROM obelisco.pzcodes");
    $row=$SQL->fetch_array();
    $lista=$row["max"];
    if($lista==0)
    {
      $lista=1;
    }

      $codigo=$familia.$proceso.$division.$datop.$producto.$entrada;
      $mysqli->query("INSERT INTO obelisco.pzcodes(codigoB,consec,cdglist,producto,familia,proceso,division,datop,entrada) values('".$codigo."','".$cantidad."','".$lista."','".$producto."','".$familia."','".$proceso."','".$division."','".$prov."','".$entrada."')");
    
    echo "<div class='alert alert-success alert-dismissible fade in'>
    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
    <strong>Operación exitosa</strong> ,Códigos generados.
    </div>";
  }
}
?>
<?php
if (isset($_GET['edit'])) {
  include("conexionobelisco.php");
  $consu=$mysqli->query("SELECT*FROM pzcodes WHERE cdglist=".$_GET['edit']."");
  $gg=$consu->fetch_array();

 
}
    if(isset($_POST['update']))
{

$consec=$_POST['mountcodes'];

$consu=$mysqli->query("UPDATE pzcodes SET  consec='$consec' WHERE cdglist='".$_GET['edit']."'");

header("Location: getcodealmacen.php");
}
?>
