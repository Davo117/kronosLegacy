<!doctype html>
<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script type="text/javascript" src="../bootstrap/Popper.js"></script>
    <script src="../bootstrap/bootstrap-confirmation.js"></script>
 <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Amatic+SC|Cairo|Leckerli+One|Eater|Rock+Salt|Russo+One" rel="stylesheet">
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
$buf=$_SERVER['PHP_SELF'];
 $modulo=explode('/', $buf);
 $nombreSistema="Saturno_edit";
 /*<html lang="es">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<div >
	<ul>
		

		<li title="Reporte de la producción total" onclick="window.location='Produccion_control.php'">Tablero de control</li>
		<li title="Reporte de entradas y salidas de registros" onclick="window.location='Produccion_Reporte.php'">Tablero de registros</li>
		<li title="Programación de la impresión" onclick="window.location='Produccion_programacion.php'">Programación</li>
		<li title="Registro y ajustes de procesos" onclick="window.location='Produccion_Registro.php'">Registro de procesos</li>
		<li title="Calendario del historial de producción" onclick="window.location='Produccion_Historico.php<?php echo $linkDay;?>'">Histórico de Operaciones</li>
		<li title="Gestión de maquinas" onclick="window.location='Produccion_Maquinas.php'">Máquinas</li>

	</ul>
</div>
</html>*/
?>

<?php $linkDay= "?dia=".date("j")."&mes=".date("n")."&ano=".date("Y").""; 
	include('../Produccion/Controlador_produccion/db_produccion.php');


 /*<div id="navega">  
<div id="menu">  
<div id="fijo">  

     <a class="bloq" title="Reporte de la producción total" href='Produccion_control.php'>Tablero de control</a> |   
     <a  class="bloq" title="Reporte de entradas y salidas de registros" href='Produccion_Reporte.php'>Tablero de registros</a> |   
     <a  class="bloq" title="Programación de la impresión" href='Produccion_programacion.php'>Programación</a> |   
     <a  class="bloq" title="Registro y ajustes de procesos" href='Produccion_Registro.php'>Registro de procesos</a> |   
     <a  class="bloq" title="Calendario del historial de producción" href="PlanEntregas.php<?php echo $linkDay;?>">Plan de Entregas</a> |  
     <a  class="bloq" title="Gestión de maquinas" href='Produccion_Maquinas.php'>Máquinas</a>  | 
       
</div>   
</div>   
</div>   

<nav style="position:fixed;;
    color:#000;">
  <a title="Menu principal" href="../Menu.php" ><img style="left:5%" src="../pictures/logo-labro_minis.png"></a>
        <a class="module" style="position:fixed;left:3%;top:0.5%;"><span style="font:bold 20px Sansation">Producción  <b style="font:bold 17px Sansation Light"><?php if(!empty($_GET['tipo'])) echo '- '.$_GET['tipo'];?></b></span></a>
</nav>
<nav role="navigation" class="nav" >
      
    <ul class="nav-items">
        <li class="nav-item dropdown">
            <a href="#" class="nav-link"><span>Tablero de control</span></a>
            <nav class="submenu">
                <ul class="submenu-items">
                	<?php
                		$resultado=$MySQLiconn->query("SELECT tipo,alias from tipoproducto where baja=1 order by id desc");
                		while($row=$resultado->fetch_object())
                		{
                      if($row->alias!="BS")
                      {

                			?>
  				<li class="submenu-item"><a href="Produccion_control.php?tipo=<?php echo $row->alias;?>" class="submenu-link"><?php echo $row->tipo;?></a></li>
                			<?php
                    }
                    else
                    {
                        ?>
                         <li class="submenu-item"><hr class="submenu-seperator" /></li>
          <li class="submenu-item"><a href="Produccion_control.php?tipo=<?php echo $row->alias;?>" class="submenu-link"><?php echo $row->tipo;?></a></li>
                      <?php
                    }
                		}
                  
                      ?>
            </nav>
        </li>  
        <li class="nav-item dropdown">
            <a href="#" class="nav-link" id="nav-link"><span>Programación</span></a>
              <nav class="submenu">
                <ul class="submenu-items">
                  <?php
                    $resultado=$MySQLiconn->query("SELECT tipo,alias from tipoproducto where baja=1 order by id desc");
                    while($row=$resultado->fetch_object())
                    {
                      if($row->alias!="BS")
                      {

                      ?>
          <li class="submenu-item"><a href="Produccion_programacion.php?tipo=<?php echo $row->alias;?>" class="submenu-link"><?php echo $row->tipo;?></a></li>
                      <?php
                    }
                    else
                    {
                        ?>
                         <li class="submenu-item"><hr class="submenu-seperator" /></li>
          <li class="submenu-item"><a href="Produccion_programacion.php?tipo=<?php echo $row->alias;?>" class="submenu-link"><?php echo $row->tipo;?></a></li>
                      <?php
                    }
                    }
                  
                      ?>
            </nav>
        </li>
         <li class="nav-item dropdown">
            <a href="#" class="nav-link"><span>Registro</span></a>
               <nav class="submenu">
                <ul class="submenu-items">
                  <?php
                    $resultado=$MySQLiconn->query("SELECT tipo,alias from tipoproducto where baja=1 order by id desc");
                    while($row=$resultado->fetch_object())
                    {
                      if($row->alias!="BS")
                      {

                      ?>
          <li class="submenu-item"><a href="Produccion_Registro.php?tipo=<?php echo $row->alias;?>" class="submenu-link"><?php echo $row->tipo;?></a></li>
                      <?php
                    }
                    else
                    {
                        ?>
                         <li class="submenu-item"><hr class="submenu-seperator" /></li>
          <li class="submenu-item"><a href="Produccion_Registro.php?tipo=<?php echo $row->alias;?>" class="submenu-link"><?php echo $row->tipo;?></a></li>
             <li class="submenu-item"><hr class="submenu-seperator" /></li>
          <li class="submenu-item"><a href="bajaBS.php" class="submenu-link">Baja BS</a></li>
                      <?php
                    }
                    }
                  
                      ?>
            </nav>
        </li>  
        <li class="nav-item dropdown">
            <a href="#" class="nav-link"><span>Tablero de registros</span></a>
              <nav class="submenu">
                <ul class="submenu-items">
                  <?php
                    $resultado=$MySQLiconn->query("SELECT tipo,alias from tipoproducto where baja=1 order by id desc");
                    while($row=$resultado->fetch_object())
                    {
                      if($row->alias!="BS")
                      {

                      ?>
          <li class="submenu-item"><a href="Produccion_Reporte.php?tipo=<?php echo $row->alias;?>" class="submenu-link"><?php echo $row->tipo;?></a></li>
                      <?php
                    }
                    else
                    {
                        ?>
                         <li class="submenu-item"><hr class="submenu-seperator" /></li>
          <li class="submenu-item"><a href="Produccion_Reporte.php?tipo=<?php echo $row->alias;?>" class="submenu-link"><?php echo $row->tipo;?></a></li>

                      <?php
                    }
                    }
                  
                    ?>
                      <li class="submenu-item"><hr class="submenu-seperator" /></li>
                    <li class="submenu-item"><a href="PlanEntregas.php<?php echo $linkDay;?>" class="submenu-link">Plan de entregas</a></li>
                </ul>
            </nav>
        </li>
        
          <li class="nav-item dropdown">
            <a href="Produccion_Maquinas.php" class="nav-zelda"><span>Máquinas</span></a>
        </li>
    </ul>
</nav>*/?>

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
                    $resultado=$MySQLiconn->query("SELECT tipo,alias from tipoproducto where baja=1 order by id desc");
                    while($row=$resultado->fetch_object())
                    {
                      if($row->alias!="BS")
                      {

                      ?>
          <li><a href="Produccion_control.php?tipo=<?php echo $row->alias;?>"><?php echo $row->tipo;?></a></li>
                      <?php
                    }
                    else
                    {
                        ?>
                         <li><hr class="divider" /></li>
          <li class="submenu-item"><a href="Produccion_control.php?tipo=<?php echo $row->alias;?>" ><?php echo $row->tipo;?></a></li>
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
                    $resultado=$MySQLiconn->query("SELECT tipo,alias from tipoproducto where baja=1 order by id desc");
                    while($row=$resultado->fetch_object())
                    {
                      if($row->alias!="BS")
                      {

                      ?>
          <li><a href="Produccion_programacion.php?tipo=<?php echo $row->alias;?>"><?php echo $row->tipo;?></a></li>
                      <?php
                    }
                    else
                    {
                        ?>
                         <li><hr class="divider" /></li>
          <li class="submenu-item"><a href="Produccion_programacion.php?tipo=<?php echo $row->alias;?>" ><?php echo $row->tipo;?></a></li>
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
                    $resultado=$MySQLiconn->query("SELECT tipo,alias from tipoproducto where baja=1 order by id desc");
                    while($row=$resultado->fetch_object())
                    {
                      if($row->alias!="BS")
                      {

                      ?>
          <li><a href="Produccion_Registro.php?tipo=<?php echo $row->alias;?>"><?php echo $row->tipo;?></a></li>
                      <?php
                    }
                    else
                    {
                        ?>
                         <li><hr class="divider" /></li>
          <li><a href="Produccion_Registro.php?tipo=<?php echo $row->alias;?>"><?php echo $row->tipo;?></a></li>
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
                    $resultado=$MySQLiconn->query("SELECT tipo,alias from tipoproducto where baja=1 order by id desc");
                    while($row=$resultado->fetch_object())
                    {
                      if($row->alias!="BS")
                      {

                      ?>
          <li><a href="Produccion_Reporte.php?tipo=<?php echo $row->alias;?>"><?php echo $row->tipo;?></a></li>
                      <?php
                    }
                    else
                    {
                        ?>
                         <li ><hr class="divider" /></li>
          <li><a href="Produccion_Reporte.php?tipo=<?php echo $row->alias;?>"><?php echo $row->tipo;?></a></li>

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
  <a href="#" class="navbar-link"><?php if(isset($_GET['tipo'])){ echo $_GET['tipo'];}?></a>
</h4>
  </div>
</nav>