<?php /*
<!doctype html>
<div id="navega">  
<div id="menu">  
<div id="fijo">  
     <a class="bloq" href="Empaque_inventario.php">Inventario</a> |   
     <a  class="bloq" href="Empaque_Ensamble.php">Ensamble</a> |          
     <a  class="bloq" href="Empaque_Ajuste.php">Ajuste de parámetros</a> |          
     <a  class="bloq" href="Empaque_Desempaque.php">Desempaques</a>  |                
</div>   
</div>   
</div> */ ?>


<nav role="navigation" class="nav">
  <ul class="nav-items">
    <li class="nav-item dropdown">
      <a href="../Empaque/Empaque_inventario.php" class="nav-zelda"><span>Inventario</span></a>    
    </li>

	<li class="nav-item dropdown">
      <a href="#" class="nav-link"><span>Empaques</span></a>
      <nav class="submenu">
        <ul class="submenu-items">
          <li class="submenu-item"><a href="../Empaque/Empaque_Ensamble.php" class="submenu-link">Ensamble</a></li>
          <li class="submenu-item"><hr class="submenu-seperator" /></li>
          <li class="submenu-item"><a href="../Empaque/Empaque_Ajuste.php" class="submenu-link">Pesaje</a></li>
        </ul>
      </nav>
    </li>  

	<li class="nav-item dropdown">
      <a href="../Empaque/Empaque_Desempaque.php" class="nav-zelda"><span>Desempaques</span></a>    
    </li>
  </ul>
</nav>
