<!doctype html>
<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script type="text/javascript" src="../bootstrap/Popper.js"></script>
<script src="../bootstrap/bootstrap-confirmation.js"></script>
<script src="../css/js/jquery.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
   <link rel="stylesheet" href="../css/bootstrap-select-1.12.4/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="../css/bootstrap-select-1.12.4/js/bootstrap-select.js"></script>


<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="../css/bootstrap-select-1.12.4/js/i18n/defaults-*.js"></script>
<script type="text/javascript">

  window.alert = function(){};
  var defaultCSS = document.getElementById('bootstrap-css');
  function changeCSS(css){
    if(css) $('head > link').filter(':first').replaceWith('<link rel="stylesheet" href="'+ css +'" type="text/css" />'); 
    else $('head > link').filter(':first').replaceWith(defaultCSS); 
  }
  $( document ).ready(function() {
    var iframe_height = parseInt($('html').height()); 
    window.parent.postMessage( iframe_height, 'https://bootsnipp.com');
  });
</script>
<style type="text/css">
body {
  margin-top: 70px;
}
</style>
<?php 
include ("../Controler/permisos.php"); 
$buf=$_SERVER['PHP_SELF'];
$modulo=explode('/', $buf);
$nombreSistema="Saturno_edit";

?>

<?php $linkDay= "?dia=".date("j")."&mes=".date("n")."&ano=".date("Y").""; 
include('../Produccion/Controlador_produccion/db_produccion.php');
$result=array();
$aux=0;
$resultado=$MySQLiconn->query("SELECT id as alias,tipo from tipoproducto where baja=1 order by id desc");
while($row=$resultado->fetch_object())
{
  $result[$aux]['alias']=$row->alias;
  $result[$aux]['tipo']=$row->tipo;
  $aux++;
}?>

<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <!-- El logotipo y el icono que despliega el menú se agrupan
   para mostrarlos mejor en los dispositivos móviles -->
   <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse"
    data-target=".navbar-ex1-collapse">
    <span class="sr-only">Desplegar navegación</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </button>
  <a class="navbar-brand" href="../Menu.php"><img class="img-responsive" width="40" height="40" src="../pictures/logo-labro_minis.png"></a>
</div>

  <!-- Agrupar los enlaces de navegación, los formularios y cualquier
   otro elemento que se pueda ocultar al minimizar la barra -->
   <div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav">
     <li class="dropdown">
      <a href="#" class="dropdown-toggle" <?php if($buf=='/Saturno v0.6/'.$nombreSistema.'/Produccion/Produccion_control.php'){ echo "Style='background-color:#E8EDFF'";} ?> data-toggle="dropdown">
        Tablero de control<b class="caret"></b>
      </a>
      <ul class="dropdown-menu">
        <?php
                    //$resultado=$MySQLiconn->query("SELECT id as alias,tipo from tipoproducto where baja=1 order by id desc");
        for($i=0;$i<count($result);$i++)
        {
          if($result[$i]['alias']!="1")
          {

            ?>
            <li><a href="Produccion_control.php?tipo=<?php echo $result[$i]['alias'];?>"><?php echo $result[$i]['tipo'];?></a></li>
            <?php
          }
          else
          {
            ?>
            <li><hr class="divider" /></li>
            <li class="submenu-item"><a href="Produccion_control.php?tipo=<?php echo $result[$i]['alias'];?>" ><?php echo $result[$i]['tipo'];?></a></li>
            <?php
          }
        }
        
        ?>
      </ul>
    </li>
    <li class="dropdown">
      <a href="#" class="dropdown-toggle"  <?php if($buf=='/Saturno v0.6/'.$nombreSistema.'/Produccion/Produccion_programacion.php'){ echo "Style='background-color:#E8EDFF'";} ?> data-toggle="dropdown">
        Programación<b class="caret"></b>
      </a>
      <ul class="dropdown-menu">
        <?php
                    //$resultado=$MySQLiconn->query("SELECT id as alias,tipo from tipoproducto where baja=1 order by id desc");
        for($i=0;$i<count($result);$i++)
        {
          if($result[$i]['alias']!="1")
          {

            ?>
            <li><a href="Produccion_programacion.php?tipo=<?php echo $result[$i]['alias'];?>"><?php echo $result[$i]['tipo'];?></a></li>
            <?php
          }
          else
          {
            ?>
            <li><hr class="divider" /></li>
            <li class="submenu-item"><a href="Produccion_programacion.php?tipo=<?php echo $result[$i]['alias'];?>" ><?php echo $result[$i]['tipo'];?></a></li>
            <?php
          }
        }
        
        ?>
      </ul>
    </li>
    <li class="dropdown">
      <a href="#" class="dropdown-toggle"  <?php if($buf=='/Saturno v0.6/'.$nombreSistema.'/Produccion/Produccion_Registro.php'){ echo "Style='background-color:#E8EDFF'";} ?> data-toggle="dropdown">
        Registro<b class="caret"></b>
      </a>
      <ul class="dropdown-menu">
       <?php
                    //$resultado=$MySQLiconn->query("SELECT id as alias,tipo from tipoproducto where baja=1 order by id desc");
       for($i=0;$i<count($result);$i++)
       {
        if($result[$i]['alias']!="1")
        {

          ?>
          <li><a href="Produccion_Registro.php?tipo=<?php echo $result[$i]['alias'];?>"><?php echo $result[$i]['tipo'];?></a></li>
          <?php
        }
        else
        {
          ?>
          <li><hr class="divider" /></li>
          <li><a href="Produccion_Registro.php?tipo=<?php echo $result[$i]['alias'];?>"><?php echo $result[$i]['tipo'];?></a></li>
          <li><hr class="divider" /></li>
          <li><a href="bajaBS.php">Baja BS</a></li>
          <?php
        }
      }
      
      ?>
    </ul>
  </li>
  <li class="dropdown">
    <a href="#" class="dropdown-toggle"  <?php if($buf=='/Saturno v0.6/'.$nombreSistema.'/Produccion/Produccion_Reporte.php'){ echo "Style='background-color:#E8EDFF'";} ?> data-toggle="dropdown">
      Tablero de registros<b class="caret"></b>
    </a>
    <ul class="dropdown-menu">
     <?php
                    //$resultado=$MySQLiconn->query("SELECT id as alias,tipo from tipoproducto where baja=1 order by id desc");
     for($i=0;$i<count($result);$i++)
     {
      if($result[$i]['alias']!="1")
      {

        ?>
        <li><a href="Produccion_Reporte.php?tipo=<?php echo $result[$i]['alias'];?>"><?php echo $result[$i]['tipo'];?></a></li>
        <?php
      }
      else
      {
        ?>
        <li ><hr class="divider" /></li>
        <li><a href="Produccion_Reporte.php?tipo=<?php echo $result[$i]['alias'];?>"><?php echo $result[$i]['tipo'];?></a></li>

        <?php
      }
    }
    
    ?>
    <li><hr class="divider" /></li>
    <li><a href="PlanEntregas.php<?php echo $linkDay;?>">Plan de entregas</a></li>
  </ul>
</li>
<li class="dropdown">
  <a href="Produccion_Maquinas.php"  <?php if($buf=='/Saturno v0.6/'.$nombreSistema.'/Produccion/Produccion_Maquinas.php'){ echo "Style='background-color:#E8EDFF'";} ?> class="active">
  Máquinas</b>
</a>
</li>
</ul>
<?php
/*
    <form class="navbar-form navbar-right" role="search">
      <div class="form-group">
        <input type="text" class="form-control" placeholder="Código de barras">
      </div>
      <button type="submit" class="btn btn-default">Buscar</button>
    </form>
 */

    ?>
    <h4 class="navbar-text pull-right">
      <a href="#" class="navbar-link"><?php if(isset($_GET['tipo'])){ 
        for($i=0;$i<count($result);$i++)
        {
          if($result[$i]['alias']==$_GET['tipo'])
          {
            echo $result[$i]['tipo'];
          }
        }}
        ?></a>
      </h4>
    </div>
  </nav>