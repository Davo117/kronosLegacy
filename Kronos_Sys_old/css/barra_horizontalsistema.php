<?php /*
<!doctype html>
<html lang="es">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<div>
<ul>
  <li onclick="document.location.href='../Sistema/newpass.php'">Cambiar Contraseña</li>
  <li onclick="document.location.href='../Sistema/bitacora.php'">Bitácora</li>
  <li onclick="document.location.href='../Sistema/newUser.php'">Agregar Usuario</li>
  <li onclick="document.location.href='../Sistema/perfiles.php'">Privilegios</li>  
  <li onclick="document.location.href='../Miscelaneos/Misce_Productos.php'">Misceláneos</li>
</ul>
</div>
</html>


<div id="navega">  
<div id="menu">  
<div id="fijo">  
     <a class="bloq" href="../Sistema/newpass.php">Cambiar Contraseña</a> |   
     <a  class="bloq" href="../Sistema/Estado.php">Mapa</a> |   
     <a  class="bloq" href="../Sistema/bitacora.php">Bitácora</a> |          
     <a  class="bloq" href="../Sistema/newUser.php">Agregar Usuario</a> |          
     <a  class="bloq" href="../Sistema/perfiles.php">Privilegios</a> |         
     <a  class="bloq" href="../Miscelaneos/Misce_Productos.php">Misceláneos</a> |          

</div>   
</div>   
</div>  */ ?> 

<nav role="navigation" class="nav">
  <ul class="nav-items">

    <li class="nav-item dropdown">
      <a href="#" class="nav-link"><span>Misceláneos</span></a>
      <nav class="submenu">
        <ul class="submenu-items">
          <li class="submenu-item"><a href="../Miscelaneos/Misce_Productos.php" class="submenu-link">Productos</a></li>
          <li class="submenu-item"><hr class="submenu-seperator" /></li>
          <li class="submenu-item"><a href="../Miscelaneos/Misce_Procesos.php" class="submenu-link">Procesos por Producto</a></li>
        </ul>
      </nav>
    </li>  









<li class="nav-item dropdown">
      <a href="#" class="nav-link"><span>Manejo de Usuarios</span></a>
      <nav class="submenu">
        <ul class="submenu-items">
          <li class="submenu-item"><a href="../Sistema/newUser.php" class="submenu-link">Agregar Usuario</a></li>
          <li class="submenu-item"><hr class="submenu-seperator" /></li>
          <li class="submenu-item"><a href="../Sistema/perfiles.php" class="submenu-link">Privilegios de Usuario</a></li>
        </ul>
      </nav>
    </li>  


    <li class="nav-item dropdown">
      <a href="#" class="nav-link"><span>Mapa</span></a>
      <nav class="submenu">
        <ul class="submenu-items">
          <li class="submenu-item"><a href="../Sistema/Estado.php" class="submenu-link">Estados</a></li>
          <li class="submenu-item"><hr class="submenu-seperator" /></li>
          <li class="submenu-item"><a href="../Sistema/Ciudad.php" class="submenu-link">Ciudades</a></li>
          <li class="submenu-item"><hr class="submenu-seperator" /></li>
        </ul>
      </nav>
    </li>


    <li class="nav-item">
      <a href="../Sistema/bitacora.php" class="nav-zelda"><span>Bitácora</span></a>
    </li>



    <li class="nav-item dropdown">
      <a href="../Sistema/newpass.php" class="nav-zelda"><span>Cambiar Contraseña</span></a>    
    </li>

  </ul>
</nav>
<br>