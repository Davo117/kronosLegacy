<?php
ob_start();
 $buf=getcwd(); ?>
<!doctype html>
<html lang="es">
<div id="barra-lateral">
			<div id="logo-menu">
			<div class="logo-menu" >
				</div>
				<pre class="title-menu">
			</div>
			<?php if($buf=='C:\xampp\htdocs\Saturno v0.6\Saturno\Personal')
				{
					?>
					<div id="totales"  class="contenedor-logos">
				<a href='Personal/index.php'><IMG class="imgmodulo" style="style="opacity:1; filter:alpha(opacity=100);" src="pictures/recursos4.png" title='Recursos humanos'></a></a> 
					<?php
				}
				else
				{
					?>
					<div id="totales"  class="contenedor-logos">
				<a href='Personal/index.php'><IMG class="imgmodulo" style="opacity:1; filter:alpha(opacity=100);" onmouseover="this.style.opacity=0.6;this.filters.alpha.opacity='40';" onmouseout="this.style.opacity=1;this.filters.alpha.opacity='100';" src="pictures/recursos.png" title='Recursos humanos'></a>
					<?php
				}
			?>
			</div>
				 <?php if($buf=='C:\xampp\htdocs\Saturno v0.6\Saturno\Producto')
				{
					?>
					<div id="totales" class="contenedor-logos">
					<a href='Producto/Producto_Disenio.php'><IMG class="imgmodulo" style="opacity:8;background-color:#5858FA; filter:alpha(opacity=20);"  src="pictures/producto4.png" title='Productos'></a> 
					<?php
				}
				else
				{
					?>
					<div id="totales" class="contenedor-logos">
					<a href='Producto/Producto_Disenio.php'><IMG class="imgmodulo" style="opacity:1; filter:alpha(opacity=100);" onmouseover="this.style.opacity=0.6;this.filters.alpha.opacity='40';" onmouseout="this.style.opacity=1;this.filters.alpha.opacity='100';" src="pictures/productos2.png" title='Productos'></a>
						<?php
					}
				?>
			</div>
			 <?php if($buf=='C:\xampp\htdocs\Saturno v0.6\Saturno\Materia_prima')
				{
					?>
					<div id="totales" class="contenedor-logos">
				<a href='Materia_prima/MateriaPrima_Bloques.php'><IMG class="imgmodulo" style="opacity:1; filter:alpha(opacity=100);" src="pictures/materia4.png" title='Materia prima'></a>
					<?php
				}
				else
				{
					?>
				<div id="totales" class="contenedor-logos">
				<a href='Materia_prima/MateriaPrima_Bloques.php'><IMG class="imgmodulo" style="opacity:1; filter:alpha(opacity=100);" onmouseover="this.style.opacity=0.6;this.filters.alpha.opacity='40';" onmouseout="this.style.opacity=1;this.filters.alpha.opacity='100';"  src="pictures/materia2.png" title='Materia prima'></a>
					<?php
					}
				?>
			</div>
			 <?php if($buf=='C:\xampp\htdocs\Saturno v0.6\Saturno\Logistica')
				{
					?>
					<div id="totales" class="contenedor-logos">
				<a href='Logistica/Logistica_Clientes.php'><IMG class="imgmodulo" style="opacity:1; filter:alpha(opacity=100);" src="pictures/logistica4.png" title='Logistica'></a>
					<?php
				}
				else
				{
					?>
					<div id="totales" class="contenedor-logos">
				<a href='Logistica/Logistica_Clientes.php'><IMG class="imgmodulo" style="opacity:1; filter:alpha(opacity=100);" onmouseover="this.style.opacity=0.6;this.filters.alpha.opacity='40';" onmouseout="this.style.opacity=1;this.filters.alpha.opacity='100';" src="pictures/logistica2.png" title='Logistica'></a>
					<?php
					}
				?>
			</div>
			 <?php if($buf=='C:\xampp\htdocs\Saturno v0.6\Saturno\Produccion')
				{
					?>
					<div id="totales" class="contenedor-logos">
				<a href='Produccion/Produccion_control.php'><IMG class="imgmodulo" style="opacity:1; filter:alpha(opacity=100);"  src="pictures/produccion4.png" title='Producción'></a>
					<?php
				}
				else
				{
					?>
					<div id="totales" class="contenedor-logos">
				<a href='Produccion/Produccion_control.php'><IMG class="imgmodulo" style="opacity:1; filter:alpha(opacity=100);" onmouseover="this.style.opacity=0.6;this.filters.alpha.opacity='40';" onmouseout="this.style.opacity=1;this.filters.alpha.opacity='100';" src="pictures/produccion2.png" title='Producción'></a>
					<?php
					}
				?>
			</div>
			<?php if($buf=='C:\xampp\htdocs\Saturno v0.6\Saturno\Empaque')
				{ ?>
					<div id="totales" class="contenedor-logos">
				<a href='Empaque/Empaque_Ensamble.php'><IMG class="imgmodulo" style="opacity:1; filter:alpha(opacity=100);" src="pictures/empaque4.png" title='Empaque'></a>
					<?php 
				}
				else
				{ ?>
					<div id="totales" class="contenedor-logos">
				<a href='Empaque/Empaque_Ensamble.php'><IMG class="imgmodulo" style="opacity:1; filter:alpha(opacity=100);" onmouseover="this.style.opacity=0.6;this.filters.alpha.opacity='40';" onmouseout="this.style.opacity=1;this.filters.alpha.opacity='100';" src="pictures/empaque3.png" title='Empaque'></a>
					<?php
					} ?>
			</div>
 			 <?php if($buf=='C:\xampp\htdocs\Saturno v0.6\Saturno\Calidad')
				{
					?>
					<div id="totales" class="contenedor-logos">
				<a href='../Calidad/Calidad_seguimiento.php'><IMG class="imgmodulo" style="opacity:1; filter:alpha(opacity=100);" src="../pictures/misce4.png" title='Productos nuevos'></a>
					<?php
				}
				else
				{ ?>
					<div id="totales" class="contenedor-logos">
				<a href='../Calidad/Calidad_seguimiento.php'><IMG class="imgmodulo" style="opacity:1; filter:alpha(opacity=100);" onmouseover="this.style.opacity=0.6;this.filters.alpha.opacity='40';" onmouseout="this.style.opacity=1;this.filters.alpha.opacity='100';" src="../pictures/misce.png" title='Productos nuevos'></a>
					<?php
					} ?>
			</div>
 			 
 			 <?php if($buf=='C:\xampp\htdocs\Saturno v0.6\Saturno\Sistema')
				{ ?>
					<div id="totales" class="contenedor-logos">
				<a href='Sistema/bitacora.php'><IMG class="imgmodulo" style="opacity:1; filter:alpha(opacity=100);" src="pictures/sistema4.png" title='Modificar Sistema'></a>
				<?php
				}
				else
				{ ?>
					<div id="totales" class="contenedor-logos">
				<a href='Sistema/bitacora.php'><IMG class="imgmodulo" style="opacity:1; filter:alpha(opacity=100);" onmouseover="this.style.opacity=0.6;this.filters.alpha.opacity='40';" onmouseout="this.style.opacity=1;this.filters.alpha.opacity='100';" src="pictures/sistema2.png" title='Modificar Sistema'></a>
					<?php
					} ?>
			</div>
				<div onclick="document.location.href=" id="totales" class="contenedor-logos">
				<a href='Controler/logout.php'><IMG class="imgmodulo" class="imgmodulo" style="opacity:1; filter:alpha(opacity=100);" onmouseover="this.style.opacity=0.6;this.filters.alpha.opacity='40';" onmouseout="this.style.opacity=1;this.filters.alpha.opacity='100';"  src="pictures/salir2.png" title='Salir'></a>
				<p class="title-menu" href="Controler/logout.php"></p>
			</div>
			<div class="infoSesion">
			<p>
			<?php

						$usuario=$_SESSION['nombre'];
					$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
					$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
					print ("<p style='font-family:Sansation;'>".$usuario."</p>");
					echo  $_SESSION['nombrerol']."</br>";
					print ("<p style='font-family:Sansation;'>".date('d')." de ".$meses[date('n')-1].",".date('Y')."</p>");
					?>
					 </p>
		    </div>
			</div>
			</div>
			</html>
			<?php
ob_end_flush();
?>
