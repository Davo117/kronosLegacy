

<div id="pestanas">
    <article class="subbloque">
    <label class="modulo_listado">Catálogo de Módulos | Definición de permisos</label><br>
    <label><b>---</b> Bloqueado | <b>r--</b> Lectura | <b>rw-</b> Lectura y escritura | <b>rwx</b> Lectura, escritura y remoción </label>
	</article>
	<br><br>
    <li id="pestana1"><a href='javascript:cambiarPestanna(pestanas,pestana1);'>Recursos Humanos</a></li>
   	<li id="pestana2"><a href='javascript:cambiarPestanna(pestanas,pestana2);'>Productos</a></li>
    <li id="pestana3"><a href='javascript:cambiarPestanna(pestanas,pestana3);'>Materia Prima</a></li>
    <li id="pestana4"><a href='javascript:cambiarPestanna(pestanas,pestana4);'>Logística</a></li>
    <li id="pestana5"><a href='javascript:cambiarPestanna(pestanas,pestana5);'>Sistema</a></li>
</div>
<div id="contenidopestanas">
	<?php  include ('../../Database/db.php'); 
	$q=$_GET['q'];

    //El name de los radios debe ser el mismo en cada section para que se pueda seleccionar solo uno
	//	en cambio si todo los radios tienen el mismo name solo se podra escoger uno
	$valorDiv='900000';
	$valorSect='900000';
	$i=1; 	$j=0;
	$resultado= $MySQLiconn->query("SELECT * FROM accesos where perfil='$q'");
	while ($rows = $resultado->fetch_array()) {
		$resultado2= $MySQLiconn->query("SELECT * FROM modulo where codigo='".$rows['cdgmodulo']."'");
		$rows2 = $resultado2->fetch_array();
		$permisoN='';
		$permisoR='';
		$permisoRW='';
		$permisoRWX='';
	
		if ($rows['permiso']=='---') {	$permisoN= 'checked="checked"'; }
		
		if ($rows['permiso']=='r--') {	$permisoR= 'checked="checked"'; }
		
		if ($rows['permiso']=='rw-') {	$permisoRW= 'checked="checked"'; }
	
		if ($rows['permiso']=='rwx') {	$permisoRWX= 'checked="checked"'; }
		
		$item=$rows['cdgmodulo'][0];
	
		if ($i!=$item) {
			if($i=='5' or $i=='6' or $i=='7'){ $i='8'; }
			else{ echo "</div>"; $i++; }
		}
		
		if ($item!=$valorDiv) {
			$valorDiv=$item;$j++;
			echo "<div id='cpestana"; echo$j; echo"'>";
		}
		
		echo "<section>
			<input type='radio' name='permiso"; echo $rows['cdgmodulo']; echo "'  value='---' "; echo $permisoN; echo " /><b>---</b>
			
	        <input type='radio' name='permiso"; echo $rows['cdgmodulo']; echo "'  value='r--' "; echo $permisoR; echo " /><b>r--</b>
			
	        <input type='radio' name='permiso"; echo $rows['cdgmodulo']; echo "'  value='rw-' "; echo $permisoRW." /><b>rw-</b>
	
			<input type='radio' name='permiso"; echo $rows['cdgmodulo']; echo "'  value='rwx' "; echo $permisoRWX; echo " /><b>rwx</b>";
		echo'<label class="textNombre">| '; echo $rows2['nombre']; echo '</label>
	    </section><br>';
	}  
	echo "</div>"; ?>