<h2>Módulos</h2>
<?php  include ('../../Database/db.php');
$q=$_GET['q'];	$m=$_GET['m'];

   //El name de los radios debe ser el mismo en cada section para que se pueda seleccionar solo uno
	//	en cambio si todo los radios tienen el mismo name solo se podra escoger uno
$resultado= $MySQLiconn->query("SELECT a.cdgmodulo, a.permiso, m.nombre FROM accesos a inner join modulo m on a.cdgmodulo=m.id where a.perfil='$q' && SUBSTRING(m.codigo, 1, 1)=$m");
	echo '<header><script src="../bootstrap/js/bootstrap.min.js"></script><header>';
while ($rows = $resultado->fetch_array()) {
	$permisoN='';
	$permisoR='';
	$permisoRW='';
	$permisoRWX='';
	$clase1='class="btn btn-danger"';
	$clase2='class="btn btn-danger"';
	$clase3='class="btn btn-danger"';
	$clase4='class="btn btn-danger"';

	if ($rows['permiso']=='---') {
		$clase1='class="btn btn-primary"';
		$permisoN= 'checked'; 
		$activo="1p".$rows['cdgmodulo'];
	}
	if ($rows['permiso']=='r--') {
		$clase2='class="btn btn-primary"';
		$permisoR= 'checked'; 
		$activo="2p".$rows['cdgmodulo'];
	}
	if ($rows['permiso']=='rw-') {
		$clase3='class="btn btn-primary"';
		$permisoRW= 'checked';
		$activo="3p".$rows['cdgmodulo'];
	}
	if ($rows['permiso']=='rwx') {
		$clase4='class="btn btn-primary"';
		$permisoRWX= 'checked';
		$activo="4p".$rows['cdgmodulo'];
	}

	echo'<div class="col-xs-4"><div class="card col-xs-7 btn-group btn-group-toggle" data-toggle="buttons">
	<div class="card-header"><label class="textNombre">'.$rows['nombre'].'</label></div>
    	
    		<label '.$clase1.' onclick="cambiar'.$rows['cdgmodulo'].'(this.id)" name="labels'.$rows['cdgmodulo'].'" id="a'.$rows['cdgmodulo'].'">
    		<input type="radio" name="permiso'.$rows['cdgmodulo'].'" value="---" '.$permisoN.' /><b>---</b></label><br>

        	<label '.$clase2.' onclick="cambiar'.$rows['cdgmodulo'].'(this.id)" name="labels'.$rows['cdgmodulo'].'" id="b'.$rows['cdgmodulo'].'">
        	<input type="radio" name="permiso'.$rows['cdgmodulo'].'" value="r--" '.$permisoR.'/><b>r--</b></label><br>

			<label '.$clase3.' onclick="cambiar'.$rows['cdgmodulo'].'(this.id)" name="labels'.$rows['cdgmodulo'].'" id="c'.$rows['cdgmodulo'].'">
			<input type="radio" name="permiso'.$rows['cdgmodulo'].'" value="rw-" '.$permisoRW.' /><b>rw-</b></label><br>

			<label '.$clase4.' onclick="cambiar'.$rows['cdgmodulo'].'(this.id)" name="labels'.$rows['cdgmodulo'].'" id="d'.$rows['cdgmodulo'].'">
			<input type="radio" name="permiso'.$rows['cdgmodulo'].'" value="rwx" '.$permisoRWX.'" /><b>rwx</b></label>
	    </div></div>';

echo'
<script type="text/javascript">
    function cambiar'.$rows['cdgmodulo'].'(idT){
        var rb = document.getElementsByName("labels'.$rows['cdgmodulo'].'");
        rb[0].className = "btn btn-danger";
        rb[1].className = "btn btn-danger";
        rb[2].className = "btn btn-danger";
        rb[3].className = "btn btn-danger";
        document.getElementById(idT).className = "btn btn-primary";
    }
    //var rb = document.getElementsByClassName("radiostyle");
    //rb[0].style.backgroundColor = "red";
</script>';
} ?>