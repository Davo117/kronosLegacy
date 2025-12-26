<!doctype html>
<html lang="es">
<div id="barra-lateral">
			<div id="logo-menu">
			<div class="logo-menu" >
				</div>
				<pre class="title-menu">
				
			
			</div>

			<div onclick="document.location.href='Personal/index.php'" id="totales"  class="contenedor-logos">
				<div  class="logo-personal">
				</div>
				<pre class="title-menu">Recursos humanos</pre>
			</div>
			<div onclick="document.location.href='Producto/Producto_Disenio.php'" id="totales" class="contenedor-logos">
				<div class="logo-productos">
				</div>
				<p class="title-menu">Productos</p>
			</div>
			<div onclick="document.location.href='Logistica/Logistica_Clientes.php'" id="totales" class="contenedor-logos">
				<div class="logo-logistica">
				</div>
				<p class="title-menu">Logística</p>
			</div>
			<div  onclick="document.location.href='Materia_Prima/MateriaPrima_Bloques.php'" id="totales" class="contenedor-logos">
				<div class="logo-materiaprima">
				</div>
				<pre class="title-menu">Materia prima</pre>
			</div>
			<div onclick="document.location.href='Produccion/Produccion_Termoencogibles.php'" id="totales" class="contenedor-logos">
				<div class="logo-produccion">
				</div>
				<p class="title-menu">Producción</p>
			</div>

			<div id="totales" class="contenedor-logos">
				<div class="logo-calidad">
				</div>
				<p class="title-menu">Calidad</p>
			</div>

			<div id="totales" class="contenedor-logos">
				<div class="logo-empaque">
				</div>
				<p class="title-menu">Empaque</p>
			</div>

<div onclick="document.location.href='Sistema/bitacora.php'" id="totales"  class="contenedor-logos">
				<div  class="logo-personal">
				</div>
				<pre class="title-menu">Sistema</pre>
			</div>
			<div onclick="document.location.href='Controler/logout.php'" id="totales" class="contenedor-logos">
				<div class="logo-cerrarSesion">
				</div>
				<p class="title-menu" href="logout.php">Salir</p>
			</div>
			<div class="infoSesion">
			<p style=""><?php

						$usuario=$_SESSION['nombre'];
					$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
					$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
					echo  $usuario."</br>";
					echo  $_SESSION['rol']."</br>";
					echo date('d')." de ".$meses[date('n')-1].",".date('Y');
					?> </p>
		    </div>
			</div>
			</div>
			</html>
