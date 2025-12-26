<?php
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
   ob_start();
   $codigoPermiso='26'; ?>

<!DOCTYPE>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Devoluciones(Logística) | Grupo Labro</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#fff">
</head>

<body> 
<?php   include("../components/barra_lateral2.php");
include("funciones/crud/crudDevolucion.php");  ?>
<style type="text/css">
input[type=radio] { width: inherit; }
</style>
<div id="page-wrapper">
<?php 
if ($_GET==false) {   ?>
   <div class="container-fluid">
   <form method="post" name="formulary" id="formulary" role="form">
<div class="panel panel-default">
   <div class="panel-heading"><b class="titulo">Devoluciones</b></div>
   <div class="panel-body">
      <div  class="col-xs-3">
         <label for="devuelto">Fecha de Devolución</label>
         <input type="date" name="devuelto" size="20" required id="devuelto" class="form-control">
      </div>

   <div  class="col-xs-3">
   <label for="cdg">Código</label>
   <input type="text" id="cdg" name="cdg" size="20" placeholder="Código" required class="form-control">
   </div>

   <div >
   <label for="radios">Tipo de Código</label>
   <div class="btn-group btn-group-toggle" data-toggle="buttons" id="radios">
      <label class="btn btn-primary"><input  type="radio" name="radio1" value="0">Embarque</label>
      <label class="btn btn-primary"><input type="radio" name="radio1" value="1">Empaque</label>
      <label class="btn btn-primary"><input  type="radio" name="radio1" value="2">Paquete</label>
      <label class="btn btn-primary"><input  type="radio" name="radio1" value="3">Rollo</label>
  
</div>
   </div>

</div>
   <button class="btn btn-default"  style="float:right;" type="submit" name="save">Guardar</button>
   </form>
   </div>
   <div>
   <?php
   include ('funciones/mostrar/mostradorDev.php');
}
elseif($_GET==true ){
   
   if($_GET['r']!='--') {
      $consulta=$MySQLiconn->query("SELECT * from $devolucion where id='".$_GET['r']."'");
      $row=$consulta->fetch_array(); ?>

      <form method="post" name="formulary" id="formulary" role="form">
      <div class="panel panel-default">

                <div class="panel-heading"><b class="titulo">Generar reporte</b>
                </div>
      
      <div  class="col-xs-3">
      <p>Folio</p>
      <p><?php echo '<b>'.$row['folio'].'</b>'; ?>
      </p></div>

      <div  class="col-xs-3"><p>Fecha Devolución</p>
      <p><?php echo '<b>'.$row['fechaDev'].'</b>'; ?>
      </p></div>

      <div  class="col-xs-3" style="clear: left;"><p>Embarque</p>
      <p> <?php echo '<b>'.$row['folio'].'</b>'; ?></p>
      </div>

      <div  class="col-xs-3"><p>Sucursal</p>
      <p> <?php echo '<b>'.$row['sucursal'].'</b>'; ?></p>
      </div>
      
      <div  class="col-xs-3" style="clear: left;"><p>O.C.</p>
      <p><?php echo '<b>'.$row['folio'].'</b>'; ?></p>
      </div>
      <div  class="col-xs-3"><p>Producto</p>
      <p><?php echo '<b>'.$row['producto'].'</b>'; ?></p>
      </div>
      
      <div  class="col-xs-3" style="clear: left;"><p>Causa</p>
      <p><input type="text" name="causa"  size="20" placeholder="Razón de Devolución" required></p>
      </div>

      <div  class="col-xs-3">
      <label for="example">Contacto</label>
      <select name="example" data-live-search="true"  class="selectpicker show-menu-arrow form-control" id="example">
      <option value="No Asignado">Seleccionar Contacto</option>
      <?php
      //Seleccionar todos los datos de la tabla 
      $resultado = $MySQLiconn->query("SELECT idconsuc, puestoconsuc, nombreconsuc FROM $tablaconsuc where bajaconsuc='1' ORDER BY idconsuc");

      //mientras se tengan registros:
      while ($row = $resultado->fetch_array()) {  ?>
         <option value="<?php echo $row['idconsuc'];?>"><?php echo $row['nombreconsuc'].' | '.$row['puestoconsuc'];?></option>
         <?php 
      } ?> 
      </optgroup>
      </select>
      </div>
      <button class="btn btn-default" style="float:right;" type="submit" name="saveR">Guardar</button>
      </form>
      <?php 
   }
   elseif($_GET['u']!='--') {
      include ("funciones/mostrar/mostradorUnboxing.php");
   }
}?>
   </div>
   </div>
</body>
<script type="text/javascript" src="../css/menuFunction.js"></script>
</html>
<?php ob_end_flush(); 
} else {
  echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
   echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../ingresar.php'>";  
  exit;
} ?>